<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
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
        $maxAttempts = 3;
        $lockMinutes = 3;
        $user = $this->users->findByEmail($email);


        if (!$user) {
            return ['errors' => ['email' => 'Email not found']];
        }


        if ($user->locked_until && now()->lessThan($user->locked_until)) {
            $remaining = now()->diffInMinutes($user->locked_until);
            $remaining =ceil($remaining);
            return ['errors' => ['email' => "Account locked for {$remaining} minutes"]];
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

                return ['errors' => [" email' => 'Account locked for $lockMinutes minutes "]];
            }

            $user->save();
            return ['errors' => ['password' => 'Incorrect password']];
        }

        $user->failed_login_attempts = 0;
        $user->locked_until = null;
        $user->save();


        if (!$user->is_verified) {
            return ['errors' => ['email' => 'Email not verified']];
        }

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

        $token = $user->createToken('user-token')->accessToken;

        return compact('user', 'token');
    }









}
