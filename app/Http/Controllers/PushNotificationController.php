<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;


class PushNotificationController extends Controller
{





    public function sendPushNotification($title, $body, $token,array $data = [])
    {

        $deviceToken =$token;

        try {
            $factory = (new Factory)
                ->withServiceAccount(base_path('storage/app/firebase_credentials.json'));

            $messaging = $factory->createMessaging();

            //$notification = Notification::create($title, $body);

            /*$message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification($notification);*/

            $message = CloudMessage::fromArray([
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],

                'data' => $data,// 👈 هون الإضافة
            ]);


            $messaging->send($message);

            return response()->json(['success' => true, 'message' => 'Notification sent to device token successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }




    //****************************************************************************

    public function create_device_token(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
        ]);

        $user = auth()->user();

        if ($user) {

            $user->device_token = $request->device_token;
            $user->save();

            return ApiResponse::success(
                 'Device token added successfully.',
                ['device_token' => $user->device_token],
                 200
            );

        } else {
            return ApiResponse::error(
                 'User not authenticated.',
                 [],
                401
            );
        }
    }
//****************************************************************************************************************



    public function sendNotificationToUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title'   => 'required|string',
            'body'    => 'required|string',
        ]);

        $user = User::find($request->user_id);

        // تأكد أن المستخدم عنده device token
        if (!$user->device_token) {
            return ApiResponse::error(
                'User does not have a device token.',
                [],
                422
            );
        }

        try {
            $factory = (new Factory)
                ->withServiceAccount(base_path('storage/app/firebase_credentials.json'));

            $messaging = $factory->createMessaging();

            $message = CloudMessage::fromArray([
                'token' => $user->device_token,
                'notification' => [
                    'title' => $request->title,
                    'body'  => $request->body,
                ],
            ]);

            $messaging->send($message);

            return ApiResponse::success(
                'Notification sent successfully.',
                [
                    'user_id' => $user->id,
                    'device_token' => $user->device_token
                ],
                200
            );

        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to send notification.',
                ['error' => $e->getMessage()],
                500
            );
        }
    }

























}
