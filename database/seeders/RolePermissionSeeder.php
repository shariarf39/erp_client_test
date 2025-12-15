<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define modules
        $modules = [
            'Dashboard',
            'HR',
            'Payroll',
            'Attendance',
            'Accounts',
            'Sales',
            'Inventory',
            'Purchase',
            'Reports',
            'Settings',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        // Create permissions (skip if already exists)
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                DB::table('permissions')->insertOrIgnore([
                    'module' => $module,
                    'action' => $action,
                    'description' => ucfirst($action) . ' ' . $module . ' module',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Update existing roles or create new ones
        DB::table('roles')->updateOrInsert(
            ['name' => 'Accountant'],
            [
                'description' => 'Accounts module only',
                'is_active' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        DB::table('roles')->updateOrInsert(
            ['name' => 'Operations Manager'],
            [
                'description' => 'Accounts, Sales & Inventory',
                'is_active' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // Get role IDs
        $superAdminRole = DB::table('roles')->where('name', 'Super Admin')->first();
        $hrManagerRole = DB::table('roles')->where('name', 'HR Manager')->first();
        $accountantRole = DB::table('roles')->where('name', 'Accountant')->first();
        $accountsManagerRole = DB::table('roles')->where('name', 'Accounts Manager')->first();
        $salesManagerRole = DB::table('roles')->where('name', 'Sales Manager')->first();
        $operationsManagerRole = DB::table('roles')->where('name', 'Operations Manager')->first();

        // Clear existing role permissions
        DB::table('role_permissions')->truncate();

        // Super Admin - All permissions
        $allPermissions = DB::table('permissions')->pluck('id');
        foreach ($allPermissions as $permissionId) {
            DB::table('role_permissions')->insert([
                'role_id' => $superAdminRole->id,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // HR Manager - HR, Payroll, Attendance, Dashboard, Reports
        $hrModules = ['HR', 'Payroll', 'Attendance', 'Dashboard', 'Reports'];
        foreach ($hrModules as $module) {
            $modulePermissions = DB::table('permissions')->where('module', $module)->pluck('id');
            foreach ($modulePermissions as $permissionId) {
                DB::table('role_permissions')->insert([
                    'role_id' => $hrManagerRole->id,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Accountant / Accounts Manager - Accounts, Dashboard, Reports
        $accountModules = ['Accounts', 'Dashboard', 'Reports'];
        foreach ($accountModules as $module) {
            $modulePermissions = DB::table('permissions')->where('module', $module)->pluck('id');
            foreach ($modulePermissions as $permissionId) {
                DB::table('role_permissions')->insert([
                    'role_id' => $accountantRole->id,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                if ($accountsManagerRole) {
                    DB::table('role_permissions')->insertOrIgnore([
                        'role_id' => $accountsManagerRole->id,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Sales Manager - Sales, Dashboard, Reports
        $salesModules = ['Sales', 'Dashboard', 'Reports'];
        foreach ($salesModules as $module) {
            $modulePermissions = DB::table('permissions')->where('module', $module)->pluck('id');
            foreach ($modulePermissions as $permissionId) {
                DB::table('role_permissions')->insert([
                    'role_id' => $salesManagerRole->id,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Operations Manager - Accounts, Sales, Inventory, Purchase, Dashboard, Reports
        if ($operationsManagerRole) {
            $operationsModules = ['Accounts', 'Sales', 'Inventory', 'Purchase', 'Dashboard', 'Reports'];
            foreach ($operationsModules as $module) {
                $modulePermissions = DB::table('permissions')->where('module', $module)->pluck('id');
                foreach ($modulePermissions as $permissionId) {
                    DB::table('role_permissions')->insert([
                        'role_id' => $operationsManagerRole->id,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        echo "Roles and permissions setup completed!\n";
    }
}
