<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function userRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:55', 'string'],
            'email' => ['email', 'required', 'unique:users'],
            'password' => ['required','confirmed'],

//            'phone_number'=>['required','unique:users,phone_number','digits:10'],
//            'age' =>  ['required'],
//            'nationality' =>['required'] ,
//            'gender' => ['required'],
        ]);

        $user = \App\Models\User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
//            'phone_number' => $request->phone_number,
//            'age' => $request->age,
//            'nationality' => $request->nationality,
//            'gender' => $request->gender,


        ]);
        $accessToken = $user->createToken('MyApp',['user'])->accessToken;


        // توليد OTP
        $otp = rand(100000, 999999);

        // حفظه في قاعدة البيانات
        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // إرسال الإيميل
        Mail::to($user->email)->send(new SendOtpMail($otp));




        return response([
            'message' => 'User created. OTP sent to your email.',
            'user' => $user,
            'access_token' => $accessToken
        ]);
    }




    //=============================================================================================


    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // منع دخول غير المفعل
        if (!$user->is_verified) {
            return response()->json(['message' => 'Please verify your email first'], 403);
        }


        $token = $user->createToken('user-token')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }



    //===================================================================================================

    public function userLogout()
    {
        Auth::guard('user-api')->user()->token()->revoke();
        return response()->json(['success'=>'logged out successfully']);
    }




    public function adminRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:55', 'string'],
            'email' => ['email', 'required', 'unique:admins'],
            'password' => ['required', 'confirmed'],

        ]);


        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),

        ]);


        $accessToken = $admin->createToken('MyApp', ['admin'])->accessToken;

        return response()->json([
            'admin' => $admin,
            'access_token' => $accessToken
        ], 201);
    }

    //********************************************************************************************

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // جلب المسؤول
        $admin = Admin::where('email', $request->email)->first();

        // التحقق من كلمة المرور
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
        }

        // إنشاء التوكن
        $token = $admin->createToken('MyApp', ['admin'])->accessToken;

        return response()->json([
            'admin' => $admin,
            'token' => $token
        ]);
    }


    //*****************************************************************************************


    public function adminLogout()
    {
        Auth::guard('admin-api')->user()->token()->revoke();
        return response()->json(['success' => 'logged out successfully']);
    }



    //***********************************************************************************************





    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->otp_code != $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        $user->update([
            'is_verified' => true,
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        return response()->json(['message' => 'Account verified successfully']);
    }


    //------------------------------------------------------------------------------------






}
