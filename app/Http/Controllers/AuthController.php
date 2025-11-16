<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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


        //********************************************************

        //Create the wallet for the user
//        $wallet = new Wallet();
//        $wallet['user_id'] = $user['id'];
//
//        $wallet['balance'] = 0;
//        $wallet->save();


        //**********************************************************************



        return response([
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












}
