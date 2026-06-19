<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Audit;

class AuditTrail extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';

    public function sortBy($field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
    }

    public function render()
    {
        $audits = Audit::with('user')
            ->when($this->search, function ($q) {
                $q->where('activity', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', "%{$this->search}%");
                  });
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(20);

        return view('livewire.reports.audit-trail', [
            'audits' => $audits
        ])->layout('layouts.app');
    }
}
