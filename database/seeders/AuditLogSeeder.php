<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\Admin;
use Carbon\Carbon;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first(); // السوبر أدمن

        if (! $admin) {
            return;
        }

        AuditLog::insert([
            [
                'actor_type'  => Admin::class,
                'actor_id'    => $admin->id,
                'action'      => 'AgencyComplaintController@index',
                'entity'      => 'Complaint',
                'entity_id'   => null,
                'method'      => 'GET',
                'url'         => '/api/agency/complaints',
                'ip'          => '127.0.0.1',
                'status_code' => 200,
                'success'     => true,
                'payload'     => json_encode([]),
                'created_at'  => Carbon::now()->subMinutes(30),
                'updated_at'  => Carbon::now()->subMinutes(30),
            ],
            [
                'actor_type'  => Admin::class,
                'actor_id'    => $admin->id,
                'action'      => 'AgencyComplaintController@lock',
                'entity'      => 'Complaint',
                'entity_id'   => 1,
                'method'      => 'POST',
                'url'         => '/api/agency/complaints/1/lock',
                'ip'          => '127.0.0.1',
                'status_code' => 200,
                'success'     => true,
                'payload'     => json_encode([]),
                'created_at'  => Carbon::now()->subMinutes(20),
                'updated_at'  => Carbon::now()->subMinutes(20),
            ],
            [
                'actor_type'  => Admin::class,
                'actor_id'    => $admin->id,
                'action'      => 'ComplaintInfoRequestController@store',
                'entity'      => 'Complaint',
                'entity_id'   => 1,
                'method'      => 'POST',
                'url'         => '/api/agency/complaints/1/request-info',
                'ip'          => '127.0.0.1',
                'status_code' => 201,
                'success'     => true,
                'payload'     => json_encode([
                    'message' => 'يرجى تزويدنا بمعلومات إضافية'
                ]),
                'created_at'  => Carbon::now()->subMinutes(15),
                'updated_at'  => Carbon::now()->subMinutes(15),
            ],
            [
                'actor_type'  => Admin::class,
                'actor_id'    => $admin->id,
                'action'      => 'AgencyComplaintController@updateStatus',
                'entity'      => 'Complaint',
                'entity_id'   => 1,
                'method'      => 'POST',
                'url'         => '/api/agency/complaints/1/status',
                'ip'          => '127.0.0.1',
                'status_code' => 200,
                'success'     => true,
                'payload'     => json_encode([
                    'status' => 'in_progress'
                ]),
                'created_at'  => Carbon::now()->subMinutes(5),
                'updated_at'  => Carbon::now()->subMinutes(5),
            ],
        ]);
    }
}
