<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use PharIo\Manifest\License;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function () {
            require base_path('routes/license.php'); // 👈 Add this
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'redirect.if.licensed' => \App\Http\Middleware\RedirectIfLicensed::class,
            'check.license' => \App\Http\Middleware\CheckLicense::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
