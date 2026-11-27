<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Cegah halaman dipasang di <iframe> situs lain (clickjacking)
        $response->headers->set('X-Frame-Options', 'DENY');
        // Cegah browser menebak tipe file (MIME sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // CSP: hanya izinkan resource dari domain sendiri (menahan XSS)
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; frame-ancestors 'none'; form-action 'self'; base-uri 'self'"
        );

        return $response;
    }
}
