<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin-auth' => App\Http\Middleware\AdminMiddleware::class,
            'admin-guest' => App\Http\Middleware\AdminGuestMiddleware::class,
            'client-auth' => App\Http\Middleware\ClientMiddleware::class,
            'client-guest' => App\Http\Middleware\ClientGuestMiddleware::class,
            'clientVerifed' => App\Http\Middleware\clientVerifedMiddleware::class,
            'Active-Client' => App\Http\Middleware\ActiveClientMiddleware::class,
            'permission' => App\Http\Middleware\CheckPermission::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
