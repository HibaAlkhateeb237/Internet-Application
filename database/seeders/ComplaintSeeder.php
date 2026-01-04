<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\GovernmentAgency;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ComplaintSeeder extends Seeder
{


    public function run(): void
    {
        $statuses = ['new', 'in_progress', 'resolved', 'rejected'];
        $users = User::all();
        $agencies = GovernmentAgency::all();

        foreach ($users as $user) {
            foreach ($statuses as $index => $status) {
                Complaint::create([
                    'user_id' => $user->id,
                    'government_agency_id' => $agencies[$index % $agencies->count()]->id,
                    'reference_number' => strtoupper(Str::random(10)),
                    'title' => 'Test complaint - ' . $status,
                    'description' => 'This is a seeded complaint for testing dashboard statistics',
                    'location' => 'Damascus',
                    'status' => $status,
                ]);
            }
        }
    }



}
