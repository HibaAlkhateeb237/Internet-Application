<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //
    }

    /**
     * Handle unauthenticated exceptions (when no token provided).
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'success' => false,
            'message' => 'You must login first',
            'data'    => null,
            'errors'  => [
                'auth' => 'Unauthenticated'
            ]
        ], 401);
    }
    public function render($request, Throwable $exception)
    {
        // إذا كانت الـ request API
        if ($request->is('api/*')) {

            if ($exception instanceof ThrottleRequestsException) {
                return response()->json([
                    'message' => 'Too many requests. Please try again later.',
                    'retry_after_seconds' => $exception->getHeaders()['Retry-After'] ?? null,
                ], 429);
            }

            // يمكنك هنا معالجة أي Exceptions أخرى إذا أحببت
        }

        return parent::render($request, $exception);
    }

}
