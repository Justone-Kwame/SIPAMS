<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RoleList extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $deleteId = null;
    public bool $confirmDelete = false;

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->confirmDelete = true;
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            $role = Role::findOrFail($this->deleteId);
            if ($role->name !== 'admin') {
                $role->delete();
            }
        }
        $this->confirmDelete = false;
        $this->deleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmDelete = false;
        $this->deleteId = null;
    }

    public function render()
    {
        $roles = Role::withCount(['users', 'permissions'])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.roles.role-list', [
            'roles' => $roles
        ])->layout('layouts.app');
    }
}
