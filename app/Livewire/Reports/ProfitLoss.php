<?php

namespace App\Livewire\Reports;

use App\Services\AccountingService;
use Carbon\Carbon;
use Livewire\Component;

class ProfitLoss extends Component
{
    public $startDate;

    public $endDate;

    public $printRoute = 'reports.profit-loss.print';

    public function mount(AccountingService $accounting)
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();

        if (request()->routeIs('accounting.*')) {
            $this->printRoute = 'accounting.profit-loss.print';
        }
    }

    public function render(AccountingService $accounting)
    {
        $pl = $accounting->getProfitLoss(
            Carbon::parse($this->startDate)->startOfDay(),
            Carbon::parse($this->endDate)->endOfDay()
        );

        return view('livewire.reports.profit-loss', compact('pl'))->layout('layouts.app');
    }
}
