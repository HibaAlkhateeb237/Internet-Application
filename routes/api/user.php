<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use Illuminate\Support\Facades\Route;



Route::post('register',[AuthController::class, 'userRegister']);
Route::post('Login',[AuthController::class, 'userLogin'])  ->middleware('login.throttle');
;

// confirm by email
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);






Route::middleware(['auth:user-api'])->group(function () {
    Route::post('logout', [AuthController::class,'userLogout']);

    Route::get('government-agencies', [ComplaintController::class, 'listAgencies']);
    Route::post('submit/complaints', [ComplaintController::class, 'submitComplaint']);

});
