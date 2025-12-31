<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('actor')
            ->latest()
            ->paginate(20);

        return ApiResponse::success(
            'سجلات التتبع',
            $logs
        );
    }
}
