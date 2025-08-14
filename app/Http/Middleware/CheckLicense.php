<?php

// app/Http/Middleware/CheckLicense.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class CheckLicense
{
    public function handle(Request $request, Closure $next)
    {
        // Allow the activation routes to pass
        if ($request->routeIs('license.activate')) {
            Log::info('Bypassing CheckLicense middleware for license activation routes');
            return $next($request);
        }


        $licenseKey = Cache::get('license_key') ?? config('license.key_env');

        if (!$licenseKey) {
            return redirect()->route('license.activate');
        }

        Log::info('This is an info message', [
            'license_key' => $licenseKey,
        ]);

        //! check api license in every hour or 1 day
        // Get current hour (0-23)
        $currentHour = now()->format('Y-m-d H'); // e.g., "2025-08-14 13"

        // Get last logged hour from cache
        $lastLoggedHour = Cache::get('last_license_log_hour');

        if ($lastLoggedHour !== $currentHour) {
            // Only log once per hour
            Log::info("Hourly license check", [
                'hour' => $currentHour,
                'license_key' => Cache::get('license_key') ?? config('license.key_env'),
            ]);

            // Store the current hour in cache for 1 hour
            Cache::put('last_license_log_hour', $currentHour, now()->addHour());
        }


        return $next($request);
    }
}
