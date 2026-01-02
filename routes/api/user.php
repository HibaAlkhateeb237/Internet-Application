<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ComplaintInfoRequestController;
use App\Http\Controllers\PushNotificationController;
use Illuminate\Support\Facades\Route;



Route::post('register',[AuthController::class, 'userRegister']);


Route::post('Login',[AuthController::class, 'userLogin'])->middleware('login.throttle');


Route::post('sendOtp',[AuthController::class, 'sendOtp']);
// confirm by email
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);




Route::middleware(['auth:user-api'])->group(function () {
    Route::post('logout', [AuthController::class,'userLogout']);
    //->middleware('permission:user-logout');

    Route::get('government-agencies', [ComplaintController::class, 'listAgencies']);
   // ->middleware('permission:complaint.list-agencies');

    Route::post('submit/complaints', [ComplaintController::class, 'submitComplaint']);
    //->middleware('permission:complaint.submit');
    Route::post('/info-request/{infoRequest}/respond', [ComplaintInfoRequestController::class, 'respond']);
    //->middleware('permission:info_request.respond');

    Route::post('complaintsByStatus', [ComplaintController::class, 'complaintsByStatus']);
    //->middleware('permission:complaint.show-by-status');

    //create_device_token
    Route::post('create_device_token', [PushNotificationController::class, 'create_device_token']);
    //->middleware('permission:device_token.create');

    Route::post('complaints/{complaint}/update', [ComplaintController::class, 'updateByUser']);
    //->middleware('permission:complaint.update_own');






});
