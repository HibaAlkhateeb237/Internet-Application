<?php

namespace App\Http\Repositories;

use App\Models\complaint_info_request;
use App\Models\Complaint;

class ComplaintInfoRequestRepository
{
    public function createRequest(Complaint $complaint, array $data)
    {
        return complaint_info_request::create([
            'complaint_id' => $complaint->id,
            'admin_id'     => $data['admin_id'],
            'request_text' => $data['request_text'],
        ]);
    }

    public function respond(complaint_info_request $infoRequest, array $data)
    {
        $infoRequest->update([
            'citizen_response' => $data['citizen_response'],
            'attachment'       => $data['attachment'] ?? null,
            'is_answered'      => true
        ]);

        return $infoRequest;
    }
}
