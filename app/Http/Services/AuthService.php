<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use App\Http\Responses\ApiResponse;
use App\Mail\SendOtpMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    protected $users;

    public function __construct(UserRepository $repository)
    {
        $this->users = $repository;
    }

   /* public function register(array $data)
    {
        $user = $this->users->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $otp = rand(100000, 999999);
        $this->users->update($user, [
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new SendOtpMail($otp));
        $token = $user->createToken('MyApp', ['user'])->accessToken;

        return compact('user', 'token');
    }*/

    //-----------------------------------------------------------------------------

    public function login(string $email, string $password)
    {
        $errors = [];
        $maxAttempts = 5;
        $lockMinutes = 3;
        $user = $this->users->findByEmail($email);


        if (!$user) {
            return ApiResponse::error(
                 'Email not found',
                   null,
                   404
            );
        }


        if ($user->locked_until && now()->lessThan($user->locked_until)) {
            $remaining = now()->diffInMinutes($user->locked_until);
            $remaining =ceil($remaining);

            return ApiResponse::error(
                 "Account locked for {$remaining} minutes",
                 null,
                 429
            );
        }


        if (!Hash::check($password, $user->password)) {


            $user->failed_login_attempts += 1;


            if ($user->failed_login_attempts > $maxAttempts) {
                $user->locked_until = now()->addMinutes($lockMinutes);
                $user->save();


                Mail::raw(
                    " Your account has been locked for $lockMinutes  minutes due to multiple failed login attempts.",
                    function ($message) use ($user) {
                        $message->to($user->email)->subject('Account Locked');
                    }
                );

                return ApiResponse::error(
                   "Account locked for $lockMinutes minutes",
                     null,
                    429
                );
            }

            $user->save();

            return ApiResponse::error(
                 'Incorrect password',
                 null,
                 401
            );
        }

        $user->failed_login_attempts = 0;
        $user->locked_until = null;
        $user->save();


        if (!$user->is_verified) {
            return ApiResponse::error(
                 'Email not verified',
                null,
                 403
            );
        }

        $token = $user->createToken('user-token')->accessToken;
        return ApiResponse::success(
             'Login successful',
              [
            'user'  => $user,
            'token' => $token
        ],
            200
        );
    }

    //------------------------------------------------------------------------------





    public function sendOtp(string $email)
    {
        $errors = [];

        $user = $this->users->findByEmail($email);

        if ($user && $user->is_verified) {
            $errors['email'] = 'Email is already registered';
            return ['errors' => $errors];
        }


        if (!$user) {
            $user = $this->users->create([
                'email' => $email,
                'is_verified' => false
            ]);
        }

        $otp = rand(100000, 999999);

        $this->users->update($user, [
            'otp_code'       => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        Mail::to($user->email)->send(new SendOtpMail($otp));
        return ['email' => $email];
    }


    public function verifyOtp(string $email, string $otp, array $data)
    {
        $errors = [];

        $user = $this->users->findByEmail($email);

        if (!$user) {
            $errors['email'] = 'User not found';
            return ['errors' => $errors];
        }

        if ($user->otp_code != $otp) {
            $errors['otp'] = 'Invalid OTP';
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            $errors['otp'] = 'OTP expired';
        }

        if (!empty($errors)) {
            return ['errors' => $errors];
        }


        $this->users->update($user, [
            'is_verified'    => true,
            'otp_code'       => null,
            'otp_expires_at' => null,
            'name'           => $data['name'],
            'password'       => Hash::make($data['password']),
        ]);


       // $user->assignRole('user');


        $token = $user->createToken('user-token')->accessToken;

        return compact('user', 'token');
    }









}
