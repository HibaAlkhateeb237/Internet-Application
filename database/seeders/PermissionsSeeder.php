<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Permission::create(['name' => 'view_complaints']);
        Permission::create(['name' => 'edit_complaints']);
        Permission::create(['name' => 'lock_complaints']);
        Permission::create(['name' => 'add_note']);
        Permission::create(['name' => 'export_reports']);
        Permission::create(['name' => 'manage_employees']);
        Permission::create(['name' => 'manage_users']);
       // view audit logs
        Permission::create(['name' => 'view audit logs']);
    }
}
