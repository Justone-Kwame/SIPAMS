<?php

namespace App\Services;

use App\Models\SaleItem;
use App\Models\Expense;
use Carbon\Carbon;

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
            'net_profit' => $this->getNetProfit($startDate, $endDate)
        ];
    }
}
