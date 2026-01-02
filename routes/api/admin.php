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


use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AgencyComplaintController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ComplaintInfoRequestController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


//Route::post('register',[AuthController::class,'adminRegister'])->middleware('check_admin');;


Route::post('Login',[AuthController::class,'adminLogin']) ->middleware('login.throttle');;


//  ------------------------------super_admin---------------------------------

Route::middleware(['auth:admin-api', 'role:super_admin'])->group(function () {

    Route::post('logout', [AuthController::class, 'adminLogout'])->middleware('permission:admin.logout');
    Route::post('register', [AuthController::class, 'adminRegister'])->middleware('permission:admin.register');
    Route::get('government-agencies', [ComplaintController::class, 'listAgencies'])->middleware('permission:list-agencies');

    // ================= Roles & Permissions =================

    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:manage roles');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->middleware('permission:manage roles');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:manage roles');
    Route::delete('/roles/{id}',[RoleController::class,'destroy'])->middleware('permission:manage roles');
    Route::get('/permissions', [RoleController::class, 'indexPermission'])->middleware('permission:manage permissions');
    Route::put('/roles/{id}/permissions', [RoleController::class, 'updatePermissions'])->middleware('permission:manage roles');
    Route::post('/users/{id}/assign-role', [AdminController::class, 'assignRole'])->middleware('permission:manage roles');
    //Route::put('/roles/{id}', [RoleController::class, 'update'])->middleware('permission:manage roles');
    Route::post('/users/{id}/remove-role', [AdminController::class, 'removeRole'])->middleware('permission:manage roles');


    //======================Employees=============================

    Route::get('/Employees', [AdminController::class, 'index'])->middleware('permission:manage Employees');
    Route::delete('/Employees/{id}', [AdminController::class, 'destroy'])->middleware('permission:manage Employees');
    Route::put('/Employees/{id}', [AdminController::class, 'update'])->middleware('permission:manage Employees');
    Route::get('/Employees/{id}', [AdminController::class, 'show'])->middleware('permission:manage Employees');
    // ================= Users =================

    Route::get('/users', [UserController::class, 'index'])->middleware('permission:manage users');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('permission:manage users');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:manage users');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('permission:manage users');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('permission:manage users');

//=========================Dashboard================================

    Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats'])->middleware('permission:system dashboard');

    Route::get('/reports/complaints/csv', [AdminReportController::class, 'exportCsv'])->middleware('permission:system dashboard');

    Route::get('/reports/complaints/pdf', [AdminReportController::class, 'exportPdf'])->middleware('permission:system dashboard');


    //========================================================================

    Route::post('/notifications/send-to-user', [PushNotificationController::class, 'sendNotificationToUser']);


});







//---------------------------------------employee-------------------------------------

Route::middleware(['auth:admin-api', 'role:employee|super_admin'])->group(function () {

    Route::post('logout', [AuthController::class, 'adminLogout'])->middleware('permission:employee.logout');

    Route::get('/agency/complaints', [AgencyComplaintController::class, 'index'])->middleware('permission:complaint.view');
    //Route::get('/agency/complaints/{id}', [AgencyComplaintController::class, 'show']);
    Route::post('/agency/complaints/{id}/lock', [AgencyComplaintController::class, 'lock'])->middleware('permission:complaint.lock');
    Route::post('/agency/complaints/{id}/unlock', [AgencyComplaintController::class, 'unlock'])->middleware('permission:complaint.unlock');
    Route::post('/agency/complaints/{id}/status', [AgencyComplaintController::class, 'updateStatus'])->middleware('permission:complaint.update_status');
    Route::post('/agency/complaints/{id}/note', [AgencyComplaintController::class, 'addNote'])->middleware('permission:complaint.add_note');
    Route::post('/complaints/{complaint}/request-info', [ComplaintInfoRequestController::class, 'store'])->middleware('permission:info_request.create');




});


