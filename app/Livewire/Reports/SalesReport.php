<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesReport extends Component
{
    public $period = 'daily'; // daily, weekly, monthly, yearly
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = Carbon::today()->toDateString();
        $this->endDate = Carbon::today()->toDateString();
    }

    public function updatedPeriod()
    {
        switch ($this->period) {
            case 'daily':
                $this->startDate = Carbon::today()->toDateString();
                $this->endDate = Carbon::today()->toDateString();
                break;
            case 'weekly':
                $this->startDate = Carbon::now()->startOfWeek()->toDateString();
                $this->endDate = Carbon::now()->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $this->startDate = Carbon::now()->startOfMonth()->toDateString();
                $this->endDate = Carbon::now()->endOfMonth()->toDateString();
                break;
            case 'yearly':
                $this->startDate = Carbon::now()->startOfYear()->toDateString();
                $this->endDate = Carbon::now()->endOfYear()->toDateString();
                break;
        }
    }

    public function render()
    {
        $sales = Sale::with(['items.product', 'user'])
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->where('status', 'completed')
            ->orderBy('date', 'desc')
            ->get();

        $totalRevenue = $sales->sum('net_amount');
        $totalProfit = $sales->flatMap(fn($s) => $s->items)->sum('profit');
        $totalItemsSold = $sales->flatMap(fn($s) => $s->items)->sum('quantity');
        $totalTransactions = $sales->count();

        return view('livewire.reports.sales-report', compact(
            'sales',
            'totalRevenue',
            'totalProfit',
            'totalItemsSold',
            'totalTransactions'
        ))->layout('layouts.app');
    }
}
