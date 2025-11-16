<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::post('register',[AuthController::class, 'userRegister']);
Route::post('Login',[AuthController::class, 'userLogin']);


Route::middleware(['auth:user-api'])->group(function () {
    Route::post('logout', [AuthController::class,'userLogout']);

});
