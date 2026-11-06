<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'view users', 'group' => 'user', 'description' => 'Can view user list'],
            ['name' => 'create users', 'group' => 'user', 'description' => 'Can create new users'],
            ['name' => 'edit users', 'group' => 'user', 'description' => 'Can edit existing users'],
            ['name' => 'delete users', 'group' => 'user', 'description' => 'Can delete users'],

            // Role Management
            ['name' => 'view roles', 'group' => 'role', 'description' => 'Can view role list'],
            ['name' => 'create roles', 'group' => 'role', 'description' => 'Can create new roles'],
            ['name' => 'edit roles', 'group' => 'role', 'description' => 'Can edit existing roles'],
            ['name' => 'delete roles', 'group' => 'role', 'description' => 'Can delete roles'],

            // Permission Management
            ['name' => 'view permissions', 'group' => 'permission', 'description' => 'Can view permission list'],
            ['name' => 'edit permissions', 'group' => 'permission', 'description' => 'Can edit permissions'],

            // Customers and Master Data
            ['name' => 'view customers', 'group' => 'customer', 'description' => 'Can view customer list'],
            ['name' => 'manage master data', 'group' => 'master', 'description' => 'Can manage vehicles, services, and customer vehicles'],

            // Workshop
            ['name' => 'manage workshop', 'group' => 'workshop', 'description' => 'Can manage job orders, estimations, breakdowns, and service packages'],

            // Sales and Purchasing
            ['name' => 'manage sales', 'group' => 'sale', 'description' => 'Can manage sales'],
            ['name' => 'manage purchases', 'group' => 'purchase', 'description' => 'Can manage purchases and suppliers'],

            // Inventory
            ['name' => 'manage inventory', 'group' => 'inventory', 'description' => 'Can manage products, movements, supplies, returns, and stock opname'],
            ['name' => 'create supply requests', 'group' => 'inventory', 'description' => 'Can create supply requests from job orders'],

            // Appointments and Follow Ups
            ['name' => 'manage appointments', 'group' => 'appointment', 'description' => 'Can manage appointments'],
            ['name' => 'manage follow-ups', 'group' => 'follow-up', 'description' => 'Can manage follow ups'],

            // Finance
            ['name' => 'manage invoices', 'group' => 'finance', 'description' => 'Can manage invoices'],
            ['name' => 'manage finance', 'group' => 'finance', 'description' => 'Can manage expenses and financial data'],
            ['name' => 'view reports', 'group' => 'report', 'description' => 'Can view reports'],

            // Human Resources
            ['name' => 'manage hr', 'group' => 'hr', 'description' => 'Can manage employees and attendances'],
        ];

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['group' => $permission['group'], 'description' => $permission['description']]
            );
        }

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['description' => 'Administrator']
        );
        $adminRole->syncPermissions(Permission::all());

        $staffRole = Role::firstOrCreate(
            ['name' => 'staff', 'guard_name' => 'web'],
            ['description' => 'Regular Staff']
        );
        $staffRole->syncPermissions([
            'manage workshop',
            'manage sales',
            'manage appointments',
            'manage follow-ups',
            'manage invoices',
            'create supply requests',
        ]);

        $admin = User::where('email', 'admin@bengkel.com')->first();

        if ($admin) {
            $admin->assignRole($adminRole);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
