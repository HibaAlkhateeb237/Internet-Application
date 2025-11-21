<?php

namespace Database\Seeders;

use App\Models\GovernmentAgency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernmentAgencySeeder extends Seeder
{

    public function run(): void
    {

        $agencies = [
            'وزارة الصحة',
            'وزارة التعليم',
            'وزارة النقل',
            'وزارة الداخلية',
            'وزارة الإسكان'
        ];

        foreach ($agencies as $agency) {
            GovernmentAgency::create([
                'name' => $agency
            ]);
        }

    }
}
