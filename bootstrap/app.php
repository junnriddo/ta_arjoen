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
    ->withMiddleware(function (Middleware $middleware): void {
        // Jika user belum login dan mengakses halaman admin,
        // arahkan ke halaman login
        $middleware->redirectGuestsTo('/login');

        // Webhook dari gateway payment tidak membawa CSRF token browser
        $middleware->validateCsrfTokens(except: [
            'midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
