<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplaintStatusRequest;
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
            'Agencies fetched successfully',
            $this->service->listAgencies()
        );
    }


    public function submitComplaint(SubmitComplaintRequest $request)
    {
        try {
            $user = $request->user();
            $complaint = $this->service->submitComplaint($user, $request);

            return ApiResponse::success(
                'Complaint submitted successfully',
                $complaint,
                201
            );

        } catch (\Exception $e) {
            return ApiResponse::error(
                'Failed to submit complaint',
                $e->getMessage()
            );

        }
    }

    //----------------------------------------------------------------------


    public function complaintsByStatus(ComplaintStatusRequest $request)
    {
        $user = $request->user();

        $complaints = $this->service->getComplaintsByStatusForUser(
            $user,
            $request->status
        );

        return ApiResponse::success(
            'Complaints retrieved successfully',
            $complaints
        );
    }






}
