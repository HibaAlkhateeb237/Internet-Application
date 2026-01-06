<?php

namespace App\Http\Services;

use App\Http\Repositories\AuditLogRepository;

class AuditLogService
{
    protected AuditLogRepository $auditLogRepository;

    public function __construct(AuditLogRepository $auditLogRepository)
    {
        $this->auditLogRepository = $auditLogRepository;
    }

    public function getLogs(int $perPage = 20)
    {
        return $this->auditLogRepository->paginateWithActor($perPage);
    }
}
