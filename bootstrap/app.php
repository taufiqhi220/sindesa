<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\RoleMiddleware; // 1. Import Middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 2. Daftarkan alias 'role' agar bisa dipakai di routes/web.php
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
        ]);

        // 3. Pertahankan logic redirect existing
        $middleware->redirectTo(
            guests: '/login',
            users: function (Request $request) {
                $user = $request->user();

                return match ($user->role) {
                    'admin'    => route('admin.dashboard'),
                    'kades'    => route('kades.dashboard'),
                    'operator' => route('operator.dashboard'),
                    default    => route('warga.dashboard'),
                };
            }
        );

        $middleware->preventRequestsDuringMaintenance(except: [
            '/',
            '/login',
            '/logout',
            '/tentang-kami',
            '/admin/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, Request $request) {
            if (! $request->expectsJson()) {
                return back()->with('error', 'Terlalu banyak percobaan. Silakan tunggu beberapa saat lagi.');
            }
        });
    })->create();