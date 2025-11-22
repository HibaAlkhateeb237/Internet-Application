<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'scopes'      => \Laravel\Passport\Http\Middleware\CheckScopes::class,
         //   'scope'       => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
            'check_admin' => \App\Http\Middleware\CheckAdmin::class,
            'check_owner' => \App\Http\Middleware\CheckOwner::class,
            'client' => \Laravel\Passport\Http\Middleware\CheckClientCredentials::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'You must login first',
                'data'    => null,
                'errors'  => [
                    'auth' => 'Unauthenticated'
                ]
            ], 401);
        });

    })
    ->create();
