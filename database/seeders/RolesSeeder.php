<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
                //
                $super = Role::create(['name' => 'super-admin', 'guard_name' => 'admin']);
                $super->givePermissionTo(Permission::all());

        // موظف عادي
                $employee = Role::create(['name' => 'employee', 'guard_name' => 'admin']);

                $employee->givePermissionTo([
                    'view_complaints',
                    'edit_complaints',
                    'lock_complaints',
                    'add_note',
                ]);



        */
    }
}
