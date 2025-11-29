<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddComplaintNoteRequest;
use App\Http\Requests\UpdateComplaintStatusRequest;
use App\Http\Responses\ApiResponse;
use App\Http\Services\ComplaintAgencyService;

class AgencyComplaintController extends Controller
{
    protected $service;

    public function __construct(ComplaintAgencyService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $complaints = $this->service->listForAgency();
        return ApiResponse::success('تم جلب البيانات', $complaints);
    }

    public function lock($id)
    {
        $result = $this->service->lock($id);

        if (!empty($result['error'])) {
            return ApiResponse::error($result['message'], [], $result['status']);
        }

        return ApiResponse::success('تم قفل الشكوى');
    }

    public function unlock($id)
    {
        $result = $this->service->unlock($id);

        if (!empty($result['error'])) {
            return ApiResponse::error($result['message'], [], $result['status']);
        }

        return ApiResponse::success('تم فتح القفل');
    }

    public function updateStatus(UpdateComplaintStatusRequest $request, $id)
    {
        $result = $this->service->updateStatus($id, $request->status, $request->note);

        if (!empty($result['error'])) {
            return ApiResponse::error($result['message'], [], $result['status']);
        }

        return ApiResponse::success('تم تحديث الحالة');
    }

    public function addNote(AddComplaintNoteRequest $request, $id)
    {
        $result = $this->service->addNote($id, $request->note);

        if (!empty($result['error'])) {
            return ApiResponse::error($result['message'], [], $result['status']);
        }

        return ApiResponse::success('تم إضافة الملاحظة');
    }
}
