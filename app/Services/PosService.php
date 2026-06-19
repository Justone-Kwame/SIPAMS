<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\AuditService;

class PosService extends BaseService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Process a sale from the POS cart.
     * $cartItems should be an array of arrays: ['product_id' => x, 'quantity' => y, 'price' => z]
     */
    public function processCheckout(array $cartItems, array $paymentDetails, ?int $customerId = null, ?int $userId = null)
    {
        return DB::transaction(function () use ($cartItems, $paymentDetails, $customerId, $userId) {
            $totalAmount = 0;
            $totalProfit = 0;

            // Generate unique receipt number
            $receiptNo = 'RCP-' . strtoupper(Str::random(8));

            // Create main sale record
            $sale = Sale::create([
                'customer_id' => $customerId,
                'user_id' => $userId,
                'receipt_no' => $receiptNo,
                'total_amount' => 0, // Will update after calculating items
                'discount_amount' => $paymentDetails['discount'] ?? 0,
                'tax_amount' => $paymentDetails['tax'] ?? 0,
                'net_amount' => 0,
                'payment_method' => $paymentDetails['method'] ?? 'cash',
                'status' => 'completed',
                'date' => now(),
            ]);

            foreach ($cartItems as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                $quantity = $item['quantity'];
                $price = $item['price'];
                $subtotal = $quantity * $price;
                $cost = $product->cost_price * $quantity;
                $profit = $subtotal - $cost;

                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'subtotal' => $subtotal,
                    'profit' => $profit,
                ]);

                // Deduct stock via InventoryService (FIFO)
                $this->inventoryService->issueStock($product, $quantity, 'sale', $sale->id, $userId);

                $totalAmount += $subtotal;
                $totalProfit += $profit;
            }

            // Update sale totals
            $netAmount = $totalAmount - $sale->discount_amount + $sale->tax_amount;
            
            $sale->update([
                'total_amount' => $totalAmount,
                'net_amount' => $netAmount,
            ]);

            AuditService::log('Sale Processed', "Processed sale with receipt #{$sale->receipt_no}, total: ₵{$sale->net_amount}", $sale);

            return $sale;
        });
    }
}
