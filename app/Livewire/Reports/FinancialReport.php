<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Sale;
use App\Models\Expense;
use Carbon\Carbon;

class FinancialReport extends Component
{
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

    public function render()
    {
        $totalRevenue = Sale::whereBetween('date', [$this->startDate, $this->endDate])
            ->where('status', 'completed')
            ->sum('net_amount');

        $totalCost = Sale::whereBetween('date', [$this->startDate, $this->endDate])
            ->where('status', 'completed')
            ->with('items')
            ->get()
            ->flatMap(fn($s) => $s->items)
            ->sum(fn($i) => $i->quantity * $i->product->cost_price);

        $grossProfit = $totalRevenue - $totalCost;

        $totalExpenses = Expense::whereBetween('date', [$this->startDate, $this->endDate])->sum('amount');

        $netProfit = $grossProfit - $totalExpenses;

        $expenses = Expense::with('category')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.reports.financial-report', compact(
            'totalRevenue',
            'totalCost',
            'grossProfit',
            'totalExpenses',
            'netProfit',
            'expenses'
        ))->layout('layouts.app');
    }
}
