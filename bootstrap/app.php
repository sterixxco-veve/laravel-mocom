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
        
        // 🛠️ PERBAIKAN DI SINI: Arahkan alias secara spesifik ke AuthCompany::class
        $middleware->alias([
            'auth.company' => \App\Http\Middleware\AuthCompany::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();