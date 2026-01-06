<?php

namespace App\Http\Repositories;

use App\Models\AuditLog;

class AuditLogRepository
{
    public function paginateWithActor(int $perPage = 20)
    {
        return AuditLog::with('actor')
            ->latest()
            ->paginate($perPage);
    }
}
