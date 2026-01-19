<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\AuditLogger;

class AuditActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        $response = $next($request);

        $u = auth()->user();
        if (!$u) return $response;

        $method = strtoupper($request->method());

        if ($method === 'GET' || $method === 'HEAD' || $method === 'OPTIONS') {
            return $response;
        }

        $aksi = match ($method) {
            'POST' => 'tambah',
            'PUT', 'PATCH' => 'ubah',
            'DELETE' => 'hapus',
            default => null,
        };

        if (!$aksi) return $response;

        $route = $request->route();
        $routeName = $route ? $route->getName() : null;

        $payload = $request->except(['password', 'password_confirmation']);

        AuditLogger::log(
            $u->instansi_id,
            $aksi,
            $routeName,
            null,
            'http',
            null,
            null,
            [
                'method' => $method,
                'path' => $request->path(),
                'full_url' => $request->fullUrl(),
                'status' => $response->getStatusCode(),
                'payload' => $payload,
            ],
            $u->id
        );

        return $response;
    }
}
