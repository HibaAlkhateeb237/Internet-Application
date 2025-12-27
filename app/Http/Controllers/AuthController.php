<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendOtpRequest;
use App\Http\Services\AuthService;
use App\Http\Services\AdminService;
use App\Http\Responses\ApiResponse;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $auth;
    protected $adminService;

    public function __construct(AuthService $authService, AdminService $adminService)
    {
        $this->auth = $authService;
        $this->adminService = $adminService;
    }

    // ---------------- User ----------------
   /* public function userRegister(UserRegisterRequest $request)
    {
        $result = $this->auth->register($request->validated());
        return ApiResponse::success('User created. OTP sent.', $result, 201);
    }*/

    public function userLogin(UserLoginRequest $request)
    {
        return $this->auth->login($request->email, $request->password);
       // if (isset($result['errors'])) return ApiResponse::error('Login failed', $result['errors'], 422);
        //return ApiResponse::success('Login successful', $result);
    }

    public function userLogout()
    {
        Auth::guard('user-api')->user()->token()->revoke();
        return ApiResponse::success('Logged out successfully');
    }




    public function sendOtp(SendOtpRequest $request)
    {
        $result = $this->auth->sendOtp($request->email);

        if (isset($result['errors'])) {
            return ApiResponse::error('Failed to send OTP', $result['errors'], 422);
        }

        return ApiResponse::success('OTP sent successfully', $result);
    }




    public function verifyOtp(VerifyOtpRequest $request)
    {
        $result = $this->auth->verifyOtp(
            $request->email,
            $request->otp,
            $request->only(['name', 'password'])
        );

        if (isset($result['errors'])) {
            return ApiResponse::error('OTP verification failed', $result['errors'], 422);
        }

        return ApiResponse::success('User verified successfully', $result);
    }




    // ---------------- Admin ----------------
    public function adminRegister(AdminRegisterRequest $request)
    {
        $result = $this->adminService->register($request->validated());
        return ApiResponse::success('Admin created', $result, 201);
    }

    public function adminLogin(AdminLoginRequest $request)
    {
        $result = $this->adminService->login($request->email, $request->password);
        if (isset($result['errors'])) return ApiResponse::error('Login failed', $result['errors'], 422);
        return ApiResponse::success('Login successful', $result);
    }

    public function adminLogout()
    {
        Auth::guard('admin-api')->user()->token()->revoke();
        return ApiResponse::success('Logged out successfully');
    }
}
