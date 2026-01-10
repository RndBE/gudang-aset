<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekIzin
{
    public function handle($request, \Closure $next, $kode = null)
    {
        $user = auth()->user();
        if (!$user) abort(401);

        $kode = (string) $kode;
        $need = array_values(array_filter(array_map('trim', explode('|', $kode))));

        if (count($need) === 0) {
            return $next($request);
        }

        foreach ($need as $k) {
            if ($k !== '' && $user->punyaIzin($k)) {
                return $next($request);
            }
        }

        abort(403, 'FORBIDDEN_CEKIZIN:' . implode(',', $need));
    }
}
