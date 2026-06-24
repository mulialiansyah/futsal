<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'customer') {
            abort(403, 'Akses ditolak. Hanya customer yang diizinkan.');
        }

        return $next($request);
    }
}
