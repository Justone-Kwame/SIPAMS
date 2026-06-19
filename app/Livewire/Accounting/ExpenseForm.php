<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Services\AuditService;

class ExpenseForm extends Component
{
    public ?int    $expenseId          = null;

    // ── Fields ────────────────────────────────────────────────────────────────
    public string      $title               = '';
    public int|string  $expense_category_id = '';
    public string      $amount              = '';
    public string      $date                = '';
    public string      $time                = '';
    public string      $description         = '';

    public function mount(?int $expenseId = null): void
    {
        $this->date = now()->format('Y-m-d');
        $this->time = now()->format('H:i');

        if ($expenseId) {
            $this->expenseId = $expenseId;
            $expense = Expense::findOrFail($expenseId);

            $this->title               = $expense->title;
            $this->expense_category_id = $expense->expense_category_id;
            $this->amount              = $expense->amount;
            $this->description         = $expense->description ?? '';
            $this->date                = \Carbon\Carbon::parse($expense->date)->format('Y-m-d');
            $this->time                = \Carbon\Carbon::parse($expense->date)->format('H:i');
        }
    }

    public function save(): void
    {
        $this->validate([
            'title'               => 'required|string|max:255',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount'              => 'required|numeric|min:0.01',
            'date'                => 'required|date',
            'time'                => 'required',
            'description'         => 'nullable|string|max:1000',
        ]);

        $datetime = $this->date . ' ' . $this->time . ':00';

        $data = [
            'title'               => $this->title,
            'expense_category_id' => $this->expense_category_id,
            'amount'              => $this->amount,
            'date'                => $datetime,
            'description'         => $this->description,
            'user_id'             => auth()->id(),
        ];

        if ($this->expenseId) {
            $expense = Expense::findOrFail($this->expenseId);
            $expense->update($data);
            AuditService::log('Expense Updated', "Updated expense '{$this->title}', amount: ₵{$this->amount}", $expense);
            session()->flash('success', 'Expense updated successfully.');
        } else {
            $expense = Expense::create($data);
            AuditService::log('Expense Recorded', "Recorded expense '{$this->title}', amount: ₵{$this->amount}", $expense);
            session()->flash('success', 'Expense recorded successfully.');
        }

        $this->redirect(route('expenses.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.accounting.expense-form', [
            'categories' => ExpenseCategory::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
