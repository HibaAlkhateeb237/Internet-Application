<?php
namespace App\Http\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public static function log(
        string $action,
        string $entityType,
        int $entityId,
        array $metadata = []
    ) {
        $admin = auth('admin')->user();
        $user  = auth('api')->user();

        AuditLog::create([
            'actor_type' => $admin ? 'admin' : 'user',
            'actor_id'   => $admin?->id ?? $user?->id,
            'action'     => $action,
            'entity_type'=> $entityType,
            'entity_id'  => $entityId,
            'metadata'   => $metadata,
            'ip'         => Request::ip(),
        ]);
    }
}

