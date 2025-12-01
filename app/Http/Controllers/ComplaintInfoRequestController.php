<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplaintInfoRequestStoreRequest;
use App\Http\Requests\ComplaintInfoRequestRespondRequest;
use App\Http\Responses\ApiResponse;
use App\Http\Services\ComplaintInfoRequestService;
use App\Models\Complaint;
use App\Models\complaint_info_request;

class ComplaintInfoRequestController extends Controller
{
    protected $service;

    public function __construct(ComplaintInfoRequestService $service)
    {
        $this->service = $service;
    }


    public function store(ComplaintInfoRequestStoreRequest $request, Complaint $complaint)
    {
        try {
            $data = $request->validated();
            $data['admin_id'] = auth('admin')->id();

            $infoRequest = $this->service->sendRequest($complaint, $data);

            return ApiResponse::success(
                'تم إرسال طلب معلومات إضافية للمواطن.',
                $infoRequest,
                201
            );

        } catch (\Exception $e) {

            return ApiResponse::error(
                $e->getMessage(),
                [],
                403
            );
        }
    }



    public function respond(ComplaintInfoRequestRespondRequest $request, complaint_info_request $infoRequest)
    {
        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('complaint_responses', 'public');
        }

        $response = $this->service->respond($infoRequest, $data);

        return ApiResponse::success(
            'تم إرسال رد المواطن بنجاح.',
            $response,
            200
        );
    }
}
