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
        // Dodaj middleware do kompresji zasobów
        $middleware->web(append: [
            \App\Http\Middleware\ServeCompressedAssets::class,
        ]);
        
        // Wyłącz szyfrowanie dla cookies JavaScript
        $middleware->encryptCookies(except: [
            'start_place_id',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
