<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PassportClientsSeeder extends Seeder
{
    public function run(): void
    {
        // Client للمستخدمين
        if (!DB::table('oauth_clients')->where('name', 'User Personal Access Client')->exists()) {
            DB::table('oauth_clients')->insert([
                'id' => (string) Str::uuid(),
                'owner_type' => null,
                'owner_id' => null,
                'name' => 'User Personal Access Client',
                'secret' => Str::random(40),
                'provider' => 'users',
                'redirect_uris' => json_encode(['http://localhost']),
                'grant_types' => json_encode(['personal']),
                'revoked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Client للمسؤولين
        if (!DB::table('oauth_clients')->where('name', 'Admin Personal Access Client')->exists()) {
            DB::table('oauth_clients')->insert([
                'id' => (string) Str::uuid(),
                'owner_type' => null,
                'owner_id' => null,
                'name' => 'Admin Personal Access Client',
                'secret' => Str::random(40),
                'provider' => 'admins',
                'redirect_uris' => json_encode(['http://localhost']),
                'grant_types' => json_encode(['personal']),
                'revoked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
