<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplaintStatusRequest;
use App\Http\Requests\ComplaintUpdateRequest;
use App\Http\Requests\SubmitComplaintRequest;
use App\Http\Services\ComplaintService;
use App\Http\Responses\ApiResponse;
use App\Models\Complaint;

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


    //--------------------------------------------------------------------------


    public function updateByUser(
        ComplaintUpdateRequest $request,
        Complaint $complaint
    ) {
        $user = auth()->user();

        if ($complaint->user_id !== $user->id) {
            return ApiResponse::error('Unauthorized', [], 403);
        }

        try {
            $updated = $this->service->updateComplaintByUser(
                $complaint,
                $request->validated(),
                $request->file('images', [])
            );

            return ApiResponse::success(
                'Complaint updated successfully',
                $updated
            );

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), [], 400);
        }
    }


    //-------------------------------------------------------------------------------------------------



    public function index()
    {
        $complaints = Complaint::with([
            'user:id,name',
            'agency:id,name'
        ])->orderBy('created_at', 'desc')->get();

        return ApiResponse::success(
            'Complaints retrieved successfully',
            $complaints
        );
    }

//===============================================================



    public function getByStatus(ComplaintStatusRequest $request)
    {
        $status = $request->status;

        $complaints = Complaint::with([
            'user:id,name',
            'agency:id,name'
        ])
            ->where('status', $status)
            ->get();

        return ApiResponse::success(
            'Complaints filtered by status',
            $complaints
        );
    }


























}
