<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | USER (user-api)
        |--------------------------------------------------------------------------
        */
   /*     $userRole = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'user-api'
        ]);

        $userPermissions = [
            'user-logout',
            'complaint.list-agencies',
            'complaint.submit',
            'info_request.respond',
            'complaint.show-by-status',
            'device_token.create',
            'complaint.update_own',
        ];

        foreach ($userPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'user-api'
            ]);
        }

        $userRole->syncPermissions($userPermissions);


   */

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE (admin-api)
        |--------------------------------------------------------------------------
        */
        $employeeRole = Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'admin-api'
        ]);

        $employeePermissions = [
            'employee.logout',
            'complaint.view',
            'complaint.lock',
            'complaint.unlock',
            'complaint.update_status',
            'complaint.add_note',
            'info_request.create',
        ];

        foreach ($employeePermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin-api'
            ]);
        }

        $employeeRole->syncPermissions($employeePermissions);

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN (admin-api)
        |--------------------------------------------------------------------------
        */
        $adminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'admin-api'
        ]);

        $adminPermissions = array_merge(
            $employeePermissions,
            [
                 'admin.logout',
                'admin.register',
                'list-agencies',
                'manage roles',
                'manage permissions',
                'manage users',
                 'system dashboard',
                'manage Employees',

            ]
        );

        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin-api'
            ]);
        }

        $adminRole->syncPermissions($adminPermissions);
    }
}
