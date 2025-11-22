<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitComplaintRequest;
use App\Http\Services\ComplaintService;
use App\Http\Responses\ApiResponse;

class ComplaintController extends Controller
{
    protected $service;

    public function __construct(ComplaintService $service)
    {
        $this->service = $service;
    }

    public function listAgencies()
    {
        return ApiResponse::success(
            $this->service->listAgencies()
        );
    }

    public function submitComplaint(SubmitComplaintRequest $request)
    {
        try {
            $user = $request->user();
            $complaint = $this->service->submitComplaint($user, $request);

            return ApiResponse::created([
                'message' => 'Complaint submitted successfully',
                'complaint' => $complaint
            ]);

        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to submit complaint',
                $e->getMessage()
            );
        }
    }
}
