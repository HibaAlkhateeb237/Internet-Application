<?php

namespace App\Http\Services;

use App\Http\Repositories\ComplaintInfoRequestRepository;
use App\Http\Responses\ApiResponse;
use App\Models\Complaint;
use App\Models\complaint_info_request;
use Illuminate\Support\Facades\Auth;

class ComplaintInfoRequestService
{
    protected $repository;

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


        $infoRequest = $this->repository->createRequest($complaint, $data);


        $complaint->update(['status' => 'in_progress']);


        $user = $complaint->user;
        $token = $user->device_token;

        if ($token) {
            $pushNotificationController = new \App\Http\Controllers\PushNotificationController();

            $title = 'طلب معلومات إضافية';
            $body = 'تم طلب معلومات إضافية بخصوص الشكوى رقم '
                . $complaint->reference_number;

            $pushNotificationController->sendPushNotification(
                $title,
                $body,
                $token,
                [
                    'complaint_id' => (string)$complaint->id,
                    'reference_number' => (string)$complaint->reference_number
                ]
            );
        }

        return $infoRequest;
    }


    public function respond(Complaint $complaint, array $data)
    {
        $response = $this->repository->respond($complaint, $data);

        $complaint->update(['status' => 'in_progress']);

        return $response;
    }
}
