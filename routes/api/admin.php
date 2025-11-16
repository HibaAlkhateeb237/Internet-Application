<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;





Route::post('register',[AuthController::class,'adminRegister']);


Route::post('Login',[AuthController::class,'adminLogin']);


Route::middleware(['auth:admin-api'])->group(function () {
    Route::post('logout', [AuthController::class,'adminLogout']);

});





