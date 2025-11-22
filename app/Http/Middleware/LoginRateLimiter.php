<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;

class LoginRateLimiter
{
    public function handle(Request $request, Closure $next)
    {
        $key = 'login:' . $request->ip() . ':' . $request->email;

        // التحقق من تجاوز عدد المحاولات
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return ApiResponse::error(
                "Too many login attempts. Try again in {$seconds} seconds.",
                null,
                429
            );
        }

        // زيادة المحاولات
        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
