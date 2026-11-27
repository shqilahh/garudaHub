<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Tolak (403) kalau belum login atau perannya tidak termasuk yang diizinkan
        abort_unless(
            $request->user() && in_array($request->user()->role, $roles),
            403
        );

        return $next($request);
    }
}
