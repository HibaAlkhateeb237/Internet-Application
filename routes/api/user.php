<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ComplaintInfoRequestController;
use App\Http\Controllers\PushNotificationController;
use Illuminate\Support\Facades\Route;



Route::post('register',[AuthController::class, 'userRegister']);


Route::post('Login',[AuthController::class, 'userLogin'])  ->middleware('login.throttle');


Route::post('sendOtp',[AuthController::class, 'sendOtp']);
// confirm by email
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);






Route::middleware(['auth:user-api'])->group(function () {
    Route::post('logout', [AuthController::class,'userLogout']);

    Route::get('government-agencies', [ComplaintController::class, 'listAgencies']);
    Route::post('submit/complaints', [ComplaintController::class, 'submitComplaint']);
    Route::post('/info-request/{infoRequest}/respond', [ComplaintInfoRequestController::class, 'respond']);

    Route::post('complaintsByStatus', [ComplaintController::class, 'complaintsByStatus']);



    //create_device_token
    Route::post('create_device_token', [PushNotificationController::class, 'create_device_token']);


    Route::post('complaints/{complaint}/update', [ComplaintController::class, 'updateByUser']);






});
