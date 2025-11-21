<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\GovernmentAgency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{

    public function run(): void
    {


        $agencies = GovernmentAgency::all();

        foreach ($agencies as $agency) {
            for ($i = 1; $i <= 3; $i++) {
                Admin::create([
                    'government_agency_id' => $agency->id,
                    'name' => "موظف $i - $agency->name",
                    'email' => "employee{$i}_{$agency->id}@example.com",
                    'password' => bcrypt('password'),

                ]);
            }
        }

    }
}
