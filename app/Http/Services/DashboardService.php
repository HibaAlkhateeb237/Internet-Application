<?php

namespace App\Http\Services;

use App\Models\Complaint;
use App\Models\User;

class DashboardService
{

    public function __construct()
    {

    }


    public function getStats()
    {
        return [
            'users_count' => User::count(),
            'complaints_count' => Complaint::count(),

            'complaints_by_status' => Complaint::select('status')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('status')
                ->get(),

            'complaints_by_agency' => Complaint::with('agency:id,name')
                ->select('government_agency_id')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('government_agency_id')
                ->get(),
        ];
    }




}










