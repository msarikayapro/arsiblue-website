<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Query string'deki UTM parametrelerini session'a kaydeder.
 * Lead form submit ve event log'larda kullanılır.
 */
class CaptureUtmParameters
{
    private const UTM_KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];

    public function handle(Request $request, Closure $next): Response
    {
        $hasAny = false;
        foreach (self::UTM_KEYS as $key) {
            if ($request->filled($key)) {
                $request->session()->put($key, $request->query($key));
                $hasAny = true;
            }
        }

        // İlk landing page (sadece bir kez kaydet, refresh'te ezme)
        if ($hasAny && ! $request->session()->has('landing_page')) {
            $request->session()->put('landing_page', $request->fullUrl());
        }

        return $next($request);
    }
}
