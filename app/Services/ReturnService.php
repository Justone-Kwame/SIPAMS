<?php

namespace App\Services;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Sale;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReturnService extends BaseService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Record a sales return against a completed sale.
     *
     * Returned goods are put back into inventory (a stock movement IN), and the
     * return reduces revenue, net cash received and profit in the P&L.
     *
     * @param  array  $lines  [['product_id' => int, 'quantity' => int, 'unit_price' => float], ...]
     */
    public function createSalesReturn(Sale $sale, array $lines, ?string $reason = null, ?int $userId = null): SalesReturn
    {
        return DB::transaction(function () use ($sale, $lines, $reason, $userId) {
            $return = SalesReturn::create([
                'sale_id' => $sale->id,
                'customer_id' => $sale->customer_id,
                'user_id' => $userId,
                'return_no' => 'SR-'.strtoupper(Str::random(8)),
                'total_amount' => 0,
                'reason' => $reason,
                'status' => 'completed',
                'date' => now(),
            ]);

            $total = 0.0;

            foreach ($lines as $line) {
                $quantity = (int) $line['quantity'];
                if ($quantity <= 0) {
                    continue;
                }

                $product = Product::findOrFail($line['product_id']);
                $unitPrice = (float) $line['unit_price'];
                $subtotal = $quantity * $unitPrice;
                $total += $subtotal;

                SalesReturnItem::create([
                    'sales_return_id' => $return->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                // Returned goods go back into stock.
                $this->inventoryService->receiveStock(
                    $product,
                    $quantity,
                    $product->cost_price,
                    null,
                    'RET-'.$return->return_no,
                    null,
                    $userId,
                    'sales_return',
                    $return->id
                );
            }

            $return->update(['total_amount' => $total]);

            AuditService::log('Sales Return', "Recorded sales return {$return->return_no} for ₵{$total}", $return);

            return $return;
        });
    }

    /**
     * Record a purchase return against a purchase order.
     *
     * Returned goods are removed from inventory (a stock movement OUT) and the
     * return reduces net cash sent to suppliers in the P&L.
     *
     * @param  array  $lines  [['product_id' => int, 'quantity' => int, 'unit_cost' => float], ...]
     */
    public function createPurchaseReturn(PurchaseOrder $purchaseOrder, array $lines, ?string $reason = null, ?int $userId = null): PurchaseReturn
    {
        return DB::transaction(function () use ($purchaseOrder, $lines, $reason, $userId) {
            $return = PurchaseReturn::create([
                'purchase_order_id' => $purchaseOrder->id,
                'supplier_id' => $purchaseOrder->supplier_id,
                'user_id' => $userId,
                'return_no' => 'PR-'.strtoupper(Str::random(8)),
                'total_amount' => 0,
                'reason' => $reason,
                'status' => 'completed',
                'date' => now(),
            ]);

            $total = 0.0;

            foreach ($lines as $line) {
                $quantity = (int) $line['quantity'];
                if ($quantity <= 0) {
                    continue;
                }

                $product = Product::findOrFail($line['product_id']);
                $unitCost = (float) $line['unit_cost'];
                $subtotal = $quantity * $unitCost;
                $total += $subtotal;

                PurchaseReturnItem::create([
                    'purchase_return_id' => $return->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'subtotal' => $subtotal,
                ]);

                // Returned goods leave our stock (sent back to supplier).
                $this->inventoryService->issueStock(
                    $product,
                    $quantity,
                    'purchase_return',
                    $return->id,
                    $userId
                );
            }

            $return->update(['total_amount' => $total]);

            AuditService::log('Purchase Return', "Recorded purchase return {$return->return_no} for ₵{$total}", $return);

            return $return;
        });
    }
}
