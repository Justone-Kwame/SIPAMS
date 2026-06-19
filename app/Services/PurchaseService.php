<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseService extends BaseService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Create a new Purchase Order
     */
    public function createPurchaseOrder(array $data, array $items, ?int $userId = null)
    {
        return DB::transaction(function () use ($data, $items, $userId) {
            // Generate unique PO number
            $poNumber = 'PO-' . strtoupper(Str::random(8));

            // Create PO
            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $data['supplier_id'],
                'user_id' => $userId,
                'po_number' => $poNumber,
                'order_date' => $data['order_date'],
                'expected_delivery_date' => $data['expected_delivery_date'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'notes' => $data['notes'] ?? null,
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            // Create PO items
            foreach ($items as $item) {
                $totalCost = $item['quantity'] * $item['unit_cost'];
                $totalAmount += $totalCost;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity'],
                    'quantity_received' => 0,
                    'unit_cost' => $item['unit_cost'],
                    'total_cost' => $totalCost,
                ]);
            }

            // Update PO total
            $purchaseOrder->update(['total_amount' => $totalAmount]);

            return $purchaseOrder;
        });
    }

    /**
     * Receive items for a Purchase Order
     */
    public function receivePurchaseOrderItem(PurchaseOrderItem $poItem, int $quantity, ?string $expiryDate = null, ?string $batchNumber = null, ?int $userId = null)
    {
        return DB::transaction(function () use ($poItem, $quantity, $expiryDate, $batchNumber, $userId) {
            $remainingToReceive = $poItem->quantity_ordered - $poItem->quantity_received;
            if ($quantity > $remainingToReceive) {
                throw new \Exception("Cannot receive more than ordered. Remaining: {$remainingToReceive}");
            }

            // Update PO item quantity received
            $poItem->quantity_received += $quantity;
            $poItem->save();

            // Add stock to inventory
            $this->inventoryService->receiveStock(
                $poItem->product, 
                $quantity, 
                $poItem->unit_cost, 
                $expiryDate, 
                $batchNumber, 
                $poItem->purchase_order_id, 
                $userId
            );

            // Check if PO is fully received
            $purchaseOrder = $poItem->purchaseOrder;
            $allReceived = $purchaseOrder->items->every(function ($item) {
                return $item->quantity_received >= $item->quantity_ordered;
            });

            if ($allReceived) {
                $purchaseOrder->update(['status' => 'delivered']);
            }

            return $poItem;
        });
    }

    /**
     * Record a payment for a Purchase Order
     */
    public function recordPayment(PurchaseOrder $purchaseOrder, float $amount, ?int $userId = null)
    {
        return DB::transaction(function () use ($purchaseOrder, $amount, $userId) {
            $purchaseOrder->paid_amount += $amount;
            $purchaseOrder->save();

            // Update supplier's outstanding balance
            $supplier = $purchaseOrder->supplier;
            $supplier->outstanding_balance -= $amount;
            $supplier->save();

            return $purchaseOrder;
        });
    }
}
