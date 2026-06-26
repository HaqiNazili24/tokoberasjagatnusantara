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
            'owner' => \App\Http\Middleware\OwnerMiddleware::class,
            'karyawan' => \App\Http\Middleware\KaryawanMiddleware::class,
            'kurir' => \App\Http\Middleware\KurirMiddleware::class,
            'kepala_toko' => \App\Http\Middleware\KepalaTokoMiddleware::class,
            'administrasi' => \App\Http\Middleware\AdministrasiMiddleware::class,
            'pegawai' => \App\Http\Middleware\PegawaiMiddleware::class,
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
