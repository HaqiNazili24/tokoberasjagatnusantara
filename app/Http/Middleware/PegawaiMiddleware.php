<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PegawaiMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->isPegawai()) {
            abort(403, 'Akses hanya untuk Pegawai.');
        }
        return $next($request);
    }
}
