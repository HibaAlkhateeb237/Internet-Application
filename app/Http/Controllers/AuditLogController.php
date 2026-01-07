<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\AuditLogService;
use App\Http\Responses\ApiResponse;

class AuditLogController extends Controller
{
    protected AuditLogService $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    public function index(Request $request)
    {
        $logs = $this->auditLogService->getLogs(20);

        return ApiResponse::success(
            'سجلات التتبع',
            $logs
        );
    }
}
