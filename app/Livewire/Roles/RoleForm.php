<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleForm extends Component
{
    public ?int $roleId = null;
    public string $name = '';
    public string $displayName = '';
    public string $description = '';

    // Permissions
    public array $permissions = [];

    // Define permission groups and CRUD actions
    public array $permissionGroups = [
        'Products' => ['view', 'create', 'update', 'delete'],
        'Categories' => ['view', 'create', 'update', 'delete'],
        'Sales' => ['view', 'create', 'update', 'delete'],
        'Purchases' => ['view', 'create', 'update', 'delete'],
        'Inventory' => ['view', 'create', 'update', 'delete'],
        'Customers' => ['view', 'create', 'update', 'delete'],
        'Suppliers' => ['view', 'create', 'update', 'delete'],
        'Expenses' => ['view', 'create', 'update', 'delete'],
        'Reports' => ['view'],
        'Users' => ['view', 'create', 'update', 'delete'],
        'Roles' => ['view', 'create', 'update', 'delete'],
        'Settings' => ['view', 'update'],
    ];

    public function mount(?int $roleId = null): void
    {
        if ($roleId) {
            $this->roleId = $roleId;
            $role = Role::with('permissions')->findOrFail($roleId);
            $this->name = $role->name;
            $this->displayName = $role->display_name ?? $role->name;
            $this->description = $role->description ?? '';
            $this->permissions = $role->permissions->pluck('name')->toArray();
        }
    }

    public function toggleAllPermissions($group, $enabled): void
    {
        foreach ($this->permissionGroups[$group] as $action) {
            $permName = strtolower($group) . '.' . $action;
            if ($enabled) {
                if (!in_array($permName, $this->permissions)) {
                    $this->permissions[] = $permName;
                }
            } else {
                $this->permissions = array_filter($this->permissions, function($perm) use ($permName) {
                    return $perm !== $permName;
                });
            }
        }
    }

    public function toggleAllGroups($enabled): void
    {
        foreach (array_keys($this->permissionGroups) as $group) {
            $this->toggleAllPermissions($group, $enabled);
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name' . ($this->roleId ? ",{$this->roleId},id" : ''),
        ]);

        if ($this->roleId) {
            $role = Role::findOrFail($this->roleId);
            $role->name = $this->name;
            $role->save();
        } else {
            $role = Role::create(['name' => $this->name, 'guard_name' => 'web']);
        }

        // Create permissions if they don't exist
        foreach ($this->permissionGroups as $group => $actions) {
            foreach ($actions as $action) {
                $permName = strtolower($group) . '.' . $action;
                Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
            }
        }

        // Sync permissions
        $role->syncPermissions($this->permissions);

        session()->flash('success', $this->roleId ? 'Role updated successfully!' : 'Role created successfully!');
        $this->redirect(route('roles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.roles.role-form')->layout('layouts.app');
    }
}
