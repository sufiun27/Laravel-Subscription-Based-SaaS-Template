<?php

// app/Http/Middleware/CheckLicense.php
namespace App\Http\Middleware;

use App\Models\GeneralSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class CheckLicense
{
    public function handle(Request $request, Closure $next)
    {
        $licenseFileExists = File::exists(config('license.file_path'));
        $general = Cache::get('GeneralSetting') ?? GeneralSetting::first();
        $licenseKey = Cache::get('license_key') ?? env(config('license.key_env'));

        if (!$licenseFileExists || $general->maintenance_mode == 9 || !$licenseKey) {
            return redirect()->route('license.activate');
        }

        return $next($request);
    }
}
