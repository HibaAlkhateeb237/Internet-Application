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


use App\Http\Controllers\AgencyComplaintController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintInfoRequestController;
use Illuminate\Support\Facades\Route;


//Route::post('register',[AuthController::class,'adminRegister'])->middleware('check_admin');;


Route::post('Login',[AuthController::class,'adminLogin']) ->middleware('login.throttle');;


Route::middleware(['auth:admin-api'])->group(function () {
    Route::post('logout', [AuthController::class, 'adminLogout']);
    Route::post('register', [AuthController::class, 'adminRegister'])->middleware('check_admin');
    Route::get('/agency/complaints', [AgencyComplaintController::class, 'index']);
    //Route::get('/agency/complaints/{id}', [AgencyComplaintController::class, 'show']);
    Route::post('/agency/complaints/{id}/lock', [AgencyComplaintController::class, 'lock']);
    Route::post('/agency/complaints/{id}/unlock', [AgencyComplaintController::class, 'unlock']);
    Route::post('/agency/complaints/{id}/status', [AgencyComplaintController::class, 'updateStatus']);
    Route::post('/agency/complaints/{id}/note', [AgencyComplaintController::class, 'addNote']);
    Route::post('/complaints/{complaint}/request-info', [ComplaintInfoRequestController::class, 'store']);


});


