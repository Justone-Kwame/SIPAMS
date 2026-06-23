<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountingService extends BaseService
{
    public function getGrossProfit(Carbon $startDate, Carbon $endDate)
    {
        // Gross profit = Total Sales Revenue - Cost of Goods Sold (which we track directly as profit on sale items)
        return SaleItem::whereHas('sale', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate])
                ->where('status', 'completed');
        })->sum('profit');
    }

    public function getTotalExpenses(Carbon $startDate, Carbon $endDate)
    {
        return Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
    }

    public function getNetProfit(Carbon $startDate, Carbon $endDate)
    {
        $grossProfit = $this->getGrossProfit($startDate, $endDate);
        $expenses = $this->getTotalExpenses($startDate, $endDate);

        return $grossProfit - $expenses;
    }

    public function getAccountingSummary(Carbon $startDate, Carbon $endDate)
    {
        return [
            'revenue' => SaleItem::whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                    ->where('status', 'completed');
            })->sum('subtotal'),
            'gross_profit' => $this->getGrossProfit($startDate, $endDate),
            'expenses' => $this->getTotalExpenses($startDate, $endDate),
            'net_profit' => $this->getNetProfit($startDate, $endDate),
        ];
    }

    /**
     * Build the full Profit & Loss statement for a period.
     *
     * Returns the figures used by the Profit and Loss report and its print preview:
     * sales/purchases/returns totals, revenue, cash payment flows, expenses and
     * net profit computed both on FIFO and Average Cost basis.
     */
    public function getProfitLoss(Carbon $startDate, Carbon $endDate): array
    {
        // ── Sales (completed) ────────────────────────────────────────────────
        $completedSales = Sale::completed()->whereBetween('date', [$startDate, $endDate]);
        $salesCount = (clone $completedSales)->count();
        $salesAmount = (float) (clone $completedSales)->sum('net_amount');

        // ── Sales returns ────────────────────────────────────────────────────
        $salesReturns = SalesReturn::whereBetween('date', [$startDate, $endDate]);
        $salesReturnCount = (clone $salesReturns)->count();
        $salesReturnAmount = (float) (clone $salesReturns)->sum('total_amount');

        // ── Purchases (delivered purchase orders) ────────────────────────────
        $deliveredPurchases = PurchaseOrder::where('status', 'delivered')
            ->whereBetween('order_date', [$startDate, $endDate]);
        $purchasesCount = (clone $deliveredPurchases)->count();
        $purchasesAmount = (float) (clone $deliveredPurchases)->sum('total_amount');

        // ── Purchase returns ──────────────────────────────────────────────────
        $purchaseReturns = PurchaseReturn::whereBetween('date', [$startDate, $endDate]);
        $purchasesReturnCount = (clone $purchaseReturns)->count();
        $purchasesReturnAmount = (float) (clone $purchaseReturns)->sum('total_amount');

        // ── Revenue ──────────────────────────────────────────────────────────
        $revenue = $salesAmount - $salesReturnAmount;

        // ── Cash payment flows ───────────────────────────────────────────────
        // Refunds to customers reduce net cash received; supplier refunds reduce net cash sent.
        $paymentsReceived = $salesAmount - $salesReturnAmount;
        $paymentsSent = (float) PurchaseOrder::whereBetween('order_date', [$startDate, $endDate])
            ->sum('paid_amount') - $purchasesReturnAmount;

        // ── Expenses ─────────────────────────────────────────────────────────
        $expenses = (float) $this->getTotalExpenses($startDate, $endDate);

        $paymentsNet = $paymentsReceived - $paymentsSent - $expenses;

        // ── Cost of goods sold ───────────────────────────────────────────────
        $saleIds = (clone $completedSales)->pluck('id');

        // Average cost: quantity x product cost price
        $cogsAverage = (float) SaleItem::whereIn('sale_id', $saleIds)
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->sum(DB::raw('sale_items.quantity * products.cost_price'));

        // FIFO: actual batch cost recorded when stock was issued for the sale
        $cogsFifo = (float) StockMovement::where('type', 'out')
            ->where('reference_type', 'sale')
            ->whereIn('reference_id', $saleIds)
            ->join('product_batches', 'stock_movements.product_batch_id', '=', 'product_batches.id')
            ->sum(DB::raw('stock_movements.quantity * product_batches.cost_price'));

        // Returned sales remove their cost from COGS (goods came back into stock),
        // so that a return only strips its own margin from profit.
        $returnIds = (clone $salesReturns)->pluck('id');
        $returnedCogs = (float) SalesReturnItem::whereIn('sales_return_id', $returnIds)
            ->join('products', 'sales_return_items.product_id', '=', 'products.id')
            ->sum(DB::raw('sales_return_items.quantity * products.cost_price'));

        $grossProfitFifo = $revenue - ($cogsFifo - $returnedCogs);
        $grossProfitAverage = $revenue - ($cogsAverage - $returnedCogs);

        $profitNetFifo = $grossProfitFifo - $expenses;
        $profitNetAverage = $grossProfitAverage - $expenses;

        return [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),

            'sales_count' => $salesCount,
            'sales_amount' => $salesAmount,

            'purchases_count' => $purchasesCount,
            'purchases_amount' => $purchasesAmount,

            'sales_return_count' => $salesReturnCount,
            'sales_return_amount' => $salesReturnAmount,

            'purchases_return_count' => $purchasesReturnCount,
            'purchases_return_amount' => $purchasesReturnAmount,

            'revenue' => $revenue,

            'payments_received' => $paymentsReceived,
            'payments_sent' => $paymentsSent,
            'expenses' => $expenses,
            'payments_net' => $paymentsNet,

            'profit_net_fifo' => $profitNetFifo,
            'profit_net_average_cost' => $profitNetAverage,
        ];
    }
}
