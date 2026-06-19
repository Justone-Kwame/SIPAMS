<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'access pos']);
        Permission::create(['name' => 'create sales']);
        Permission::create(['name' => 'generate receipts']);
        
        Permission::create(['name' => 'manage inventory']);
        Permission::create(['name' => 'receive stock']);
        Permission::create(['name' => 'view stock reports']);
        
        Permission::create(['name' => 'manage expenses']);
        Permission::create(['name' => 'view financial reports']);

        // create roles and assign created permissions
        $cashier = Role::create(['name' => 'Cashier']);
        $cashier->givePermissionTo(['access pos', 'create sales', 'generate receipts']);

        $manager = Role::create(['name' => 'Store Manager']);
        $manager->givePermissionTo(['manage inventory', 'receive stock', 'view stock reports']);

        $accountant = Role::create(['name' => 'Accountant']);
        $accountant->givePermissionTo(['manage expenses', 'view financial reports']);

        $adminRole = Role::create(['name' => 'Administrator']);
        // admin gets all permissions via Gate::before rule usually, or assign explicitly
        $adminRole->givePermissionTo(Permission::all());

        // Create Default Admin User
        $admin = User::firstOrCreate([
            'email' => 'admin@sipams.com',
        ], [
            'name' => 'System Administrator',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('Administrator');
    }
}
