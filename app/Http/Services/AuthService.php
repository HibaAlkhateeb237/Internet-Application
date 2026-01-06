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
        $user = $this->users->findByEmail($email);

        if (!$user) $errors['email'] = 'Email not found';
        if ($user && !Hash::check($password, $user->password)) $errors['password'] = 'Incorrect password';
        if ($user && !$user->is_verified) $errors['email'] = 'Email not verified';

        if (!empty($errors)) return ['errors' => $errors];

        $token = $user->createToken('user-token')->accessToken;
        return compact('user', 'token');
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
