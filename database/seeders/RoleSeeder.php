<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Define all permissions by group
        $permissionGroups = [
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

        // Create permissions
        foreach ($permissionGroups as $group => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => strtolower($group) . '.' . $action,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Create Admin role
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        // Assign all permissions to Admin
        $adminRole->syncPermissions(Permission::all());

        // Create an admin user if none exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );

        $admin->assignRole($adminRole);
    }
}
