<?php

// app/Http/Middleware/RedirectIfLicensed.php
namespace App\Http\Middleware;

use App\Models\GeneralSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RedirectIfLicensed
{
    public function handle(Request $request, Closure $next)
    {
        $licenseFileExists = File::exists(config('license.file_path'));
        $general = GeneralSetting::first();
        $licenseKey = env(config('license.key_env'));

        if ($licenseFileExists && $general->maintenance_mode != 9 && $licenseKey) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
