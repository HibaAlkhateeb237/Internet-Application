<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Services\DashboardService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{

    protected $service;

    public function __construct(
        DashboardService $service
    ) {
        $this->service = $service;
    }

    public function stats()
    {
        return ApiResponse::success(
            'Dashboard statistics',
            $this->service->getStats()
        );
    }














}
