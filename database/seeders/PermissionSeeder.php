<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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

            // Other Modules
            ['name' => 'view customers', 'group' => 'customer', 'description' => 'Can view customer list'],
            ['name' => 'manage sales', 'group' => 'sale', 'description' => 'Can manage sales'],
            ['name' => 'manage purchases', 'group' => 'purchase', 'description' => 'Can manage purchases'],
            ['name' => 'view reports', 'group' => 'report', 'description' => 'Can view reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create default roles
        $adminRole = Role::create(['name' => 'admin', 'description' => 'Administrator']);
        $adminRole->syncPermissions(Permission::all());

        $staffRole = Role::create(['name' => 'staff', 'description' => 'Regular Staff']);
        $staffRole->syncPermissions(Permission::whereIn('group', ['customer', 'sale'])->get());

        // Create admin user
        $admin = User::where(['email' => 'admin@bengkel.com'])->first();

        $admin->assignRole('admin');
    }
}
