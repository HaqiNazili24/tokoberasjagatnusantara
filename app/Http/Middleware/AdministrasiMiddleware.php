<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministrasiMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->isAdministrasi()) {
            abort(403, 'Akses hanya untuk bagian Administrasi.');
        }
        return $next($request);
    }
}
