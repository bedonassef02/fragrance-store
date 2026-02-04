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
        $middleware->web(append: [
            \App\Http\Middleware\TrackPageVisits::class,
        ]);
        $middleware->encryptCookies(except: [
            'moon_wishlist',
        ]);
        $middleware->redirectGuestsTo(function (Illuminate\Http\Request $request) {
            return route('admin.login');
        });
    })
    ->withProviders([
        App\Providers\ComposerServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
