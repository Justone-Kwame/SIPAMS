<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseList extends Component
{
    use WithPagination;

    // ── Filters ───────────────────────────────────────────────────────────────
    public string $search         = '';
    public string $categoryFilter = '';
    public string $period         = 'month';   // today | week | month | year | custom
    public string $dateFrom       = '';
    public string $dateTo         = '';
    public string $sortBy         = 'date';
    public string $sortDir        = 'desc';

    // ── Delete confirm ────────────────────────────────────────────────────────
    public ?int  $deleteId      = null;
    public bool  $confirmDelete = false;

    protected $queryString = ['search', 'categoryFilter', 'period'];

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo   = now()->endOfMonth()->format('Y-m-d');
    }

    public function updatingSearch(): void        { $this->resetPage(); }
    public function updatingCategoryFilter(): void { $this->resetPage(); }
    public function updatingPeriod(): void         { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        $this->sortBy  = $this->sortBy === $field && $this->sortDir === 'asc' ? $field : $field;
        $this->sortDir = $this->sortBy === $field && $this->sortDir === 'asc' ? 'desc' : 'asc';
        $this->sortBy  = $field;
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId      = $id;
        $this->confirmDelete = true;
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            Expense::findOrFail($this->deleteId)->delete();
            session()->flash('success', 'Expense deleted.');
        }
        $this->confirmDelete = false;
        $this->deleteId      = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmDelete = false;
        $this->deleteId      = null;
    }

    // ── Date range from period ────────────────────────────────────────────────
    private function dateRange(): array
    {
        return match ($this->period) {
            'today'  => [now()->startOfDay(),   now()->endOfDay()],
            'week'   => [now()->startOfWeek(),  now()->endOfWeek()],
            'month'  => [now()->startOfMonth(), now()->endOfMonth()],
            'year'   => [now()->startOfYear(),  now()->endOfYear()],
            'custom' => [
                Carbon::parse($this->dateFrom)->startOfDay(),
                Carbon::parse($this->dateTo)->endOfDay(),
            ],
            default  => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    public function render()
    {
        [$from, $to] = $this->dateRange();

        // ── Paginated list ────────────────────────────────────────────────────
        $expenses = Expense::with(['category', 'user'])
            ->whereBetween('date', [$from, $to])
            ->when($this->search, fn($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
            )
            ->when($this->categoryFilter, fn($q) =>
                $q->where('expense_category_id', $this->categoryFilter)
            )
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(15);

        // ── Summary cards ─────────────────────────────────────────────────────
        $dailyTotal   = Expense::whereBetween('date', [now()->startOfDay(),   now()->endOfDay()])->sum('amount');
        $monthlyTotal = Expense::whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount');
        $yearlyTotal  = Expense::whereBetween('date', [now()->startOfYear(),  now()->endOfYear()])->sum('amount');
        $periodTotal  = Expense::whereBetween('date', [$from, $to])
                            ->when($this->categoryFilter, fn($q) => $q->where('expense_category_id', $this->categoryFilter))
                            ->sum('amount');

        // ── By category (for current period) ─────────────────────────────────
        $byCategory = Expense::select('expense_category_id', DB::raw('SUM(amount) as total'))
            ->with('category')
            ->whereBetween('date', [$from, $to])
            ->groupBy('expense_category_id')
            ->orderByDesc('total')
            ->get();

        // ── Monthly chart data (12 months current year) ───────────────────────
        $monthlyChart = [];
        for ($m = 1; $m <= 12; $m++) {
            $ms = Carbon::create(now()->year, $m)->startOfMonth();
            $me = Carbon::create(now()->year, $m)->endOfMonth();
            $monthlyChart[] = (float) Expense::whereBetween('date', [$ms, $me])->sum('amount');
        }

        $categories = ExpenseCategory::orderBy('name')->get();

        return view('livewire.accounting.expense-list', compact(
            'expenses', 'categories',
            'dailyTotal', 'monthlyTotal', 'yearlyTotal', 'periodTotal',
            'byCategory', 'monthlyChart',
        ))->layout('layouts.app');
    }
}
