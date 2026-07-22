<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
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
        $middleware->trustProxies(at: '*'); // Trust all proxies (for ngrok)
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',
        ]);
        $middleware->alias([
            'isAdmin' => IsAdmin::class,
        ]);
        // Replace default RedirectIfAuthenticated with our custom one
        $middleware->replace(
            RedirectIfAuthenticated::class,
            App\Http\Middleware\RedirectIfAuthenticated::class
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
