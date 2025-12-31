<?php

namespace App\Http\Services;

use App\Http\Repositories\ComplaintInfoRequestRepository;
use App\Http\Responses\ApiResponse;
use App\Models\Complaint;
use App\Models\complaint_info_request;
use Illuminate\Support\Facades\Auth;

class ComplaintInfoRequestService
{
    protected  $repository;

    public function __construct(ComplaintInfoRequestRepository $repository)
    {
        $this->repository = $repository;
    }



    public function sendRequest(Complaint $complaint, array $data)
    {

        if ($complaint->locked_by_admin_id !== auth('admin')->id()) {
            throw new \Exception('لا يمكنك إرسال طلب معلومات لأن الشكوى مقفلة من موظف آخر.');
        }

        $infoRequest = $this->repository->createRequest($complaint, $data);

        $complaint->update(['status' => 'in_progress']);

        return $infoRequest;
    }



    public function respond(complaint_info_request $infoRequest, array $data)
    {
        $response = $this->repository->respond($infoRequest, $data);

        $infoRequest->complaint->update(['status' => 'in_progress']);

        return $response;
    }
}
