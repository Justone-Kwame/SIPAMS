<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryService extends BaseService
{
    /**
     * Add stock to a specific product by creating a new batch and recording the movement.
     */
    public function receiveStock(Product $product, int $quantity, float $costPrice, ?string $expiryDate = null, ?string $batchNumber = null, ?int $purchaseId = null, ?int $userId = null, ?string $referenceType = null, ?int $referenceId = null)
    {
        return DB::transaction(function () use ($product, $quantity, $costPrice, $expiryDate, $batchNumber, $purchaseId, $userId, $referenceType, $referenceId) {

            // Generate batch number if not provided
            if (! $batchNumber) {
                $batchNumber = 'BCH-'.strtoupper(substr(uniqid(), -6));
            }

            // Create new batch
            $batch = ProductBatch::create([
                'product_id' => $product->id,
                'purchase_id' => $purchaseId,
                'batch_number' => $batchNumber,
                'quantity_initial' => $quantity,
                'quantity_remaining' => $quantity,
                'cost_price' => $costPrice,
                'expiry_date' => $expiryDate,
                'status' => 'active',
            ]);

            // Record stock movement (IN)
            StockMovement::create([
                'product_id' => $product->id,
                'product_batch_id' => $batch->id,
                'type' => 'in',
                'quantity' => $quantity,
                'reference_type' => $referenceType ?? ($purchaseId ? 'purchase' : 'manual'),
                'reference_id' => $referenceId ?? $purchaseId,
                'user_id' => $userId,
                'notes' => 'Stock received into batch '.$batchNumber,
            ]);

            AuditService::log('Stock Added', "Added {$quantity} units of '{$product->name}' to inventory", $product);

            return $batch;
        });
    }

    /**
     * Issue stock using FIFO (First In, First Out) method based on expiry date.
     */
    public function issueStock(Product $product, int $quantity, ?string $referenceType = null, ?int $referenceId = null, ?int $userId = null)
    {
        return DB::transaction(function () use ($product, $quantity, $referenceType, $referenceId, $userId) {

            $remainingToIssue = $quantity;

            // Get active batches sorted by expiry date (FIFO)
            $batches = ProductBatch::where('product_id', $product->id)
                ->where('status', 'active')
                ->where('quantity_remaining', '>', 0)
                ->orderByRaw('expiry_date IS NULL, expiry_date ASC') // Null expiry last
                ->get();

            $totalAvailable = $batches->sum('quantity_remaining');

            if ($totalAvailable < $quantity) {
                throw new \Exception("Insufficient stock. Only {$totalAvailable} available.");
            }

            foreach ($batches as $batch) {
                if ($remainingToIssue <= 0) {
                    break;
                }

                $issueFromBatch = min($batch->quantity_remaining, $remainingToIssue);

                // Deduct from batch
                $batch->quantity_remaining -= $issueFromBatch;
                if ($batch->quantity_remaining == 0) {
                    $batch->status = 'depleted';
                }
                $batch->save();

                // Record stock movement (OUT)
                StockMovement::create([
                    'product_id' => $product->id,
                    'product_batch_id' => $batch->id,
                    'type' => 'out',
                    'quantity' => $issueFromBatch,
                    'reference_type' => $referenceType,
                    'reference_id' => $referenceId,
                    'user_id' => $userId,
                    'notes' => 'Stock issued from batch '.$batch->batch_number,
                ]);

                $remainingToIssue -= $issueFromBatch;
            }

            return true;
        });
    }

    /**
     * Get expiring products based on thresholds.
     */
    public function getExpiringProducts(int $daysThreshold = 30)
    {
        $dateThreshold = Carbon::now()->addDays($daysThreshold);

        return ProductBatch::with('product')
            ->where('status', 'active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', $dateThreshold)
            ->where('expiry_date', '>=', Carbon::now())
            ->orderBy('expiry_date', 'asc')
            ->get();
    }

    /**
     * Get already expired products.
     */
    public function getExpiredProducts()
    {
        return ProductBatch::with('product')
            ->where('status', 'active')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', Carbon::now())
            ->orderBy('expiry_date', 'desc')
            ->get();
    }
}
