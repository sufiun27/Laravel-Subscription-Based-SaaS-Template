<?php

// app/Http/Controllers/LicenseController.php
namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class LicenseController extends Controller
{
    public function activate()
    {
        $pageTitle = 'Activate License';
        return view('license.activate', compact('pageTitle'));
    }

    public function activateSubmit(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'email' => 'required|email',
        ]);

        $response = Http::post(config('license.api_url'), [
            'license_key' => $request->license_key,
            'url' => config('app.url'),
            'email' => $request->email,
            'product' => config('app.name', 'MyApp'),
        ]);

        $data = $response->json();

        if ($response->failed() || isset($data['error'])) {
            return response()->json([
                'type' => 'error',
                'message' => $data['message'] ?? 'Failed to validate license key.',
            ]);
        }

        //* Update .env file
        $envPath = base_path('.env');
        $envContent = File::get($envPath);
        $envLines = explode("\n", $envContent);
        $newEnvLines = [];
        $keyFound = false;

        foreach ($envLines as $line) {
            if (str_starts_with($line, config('license.key_env') . '=')) {
                $newEnvLines[] = config('license.key_env') . '=' . $request->license_key;
                $keyFound = true;
            } else {
                $newEnvLines[] = $line;
            }
        }

        if (!$keyFound) {
            $newEnvLines[] = config('license.key_env') . '=' . $request->license_key;
        }

        File::put($envPath, implode("\n", array_filter($newEnvLines)));

        //* Store license details in JSON file
        $licenseData = [
            'license_key' => $request->license_key,
            'install_code' => $data['install_code'] ?? '',
            'license_type' => $data['license_type'] ?? 'standard',
        ];
        File::put(config('license.file_path'), json_encode($licenseData, JSON_PRETTY_PRINT));

        //* Update GeneralSetting
        $general = GeneralSetting::first();
        $general->maintenance_mode = 0;
        $general->save();

        return response()->json(['type' => 'success']);
    }

    public function checkUpdates()
    {
        $general = GeneralSetting::first();
        $response = Http::post(config('license.check_url'), [
            'license_key' => env(config('license.key_env')),
            'website' => request()->getHost() . request()->getRequestUri() . ' - ' . config('app.url'),
        ]);

        $data = $response->json();

        if (isset($data['maintenance_mode'])) {
            $general->maintenance_mode = $data['maintenance_mode'];
        }

        $systemInfo = [];
        if (isset($data['version']) && $data['version'] > config('app.version', '1.0')) {
            $systemInfo['version'] = $data['version'];
            $systemInfo['details'] = $data['details'] ?? '';
        }
        if (isset($data['message'])) {
            $systemInfo['message'] = $data['message'];
        }

        $general->system_info = $systemInfo;
        $general->save();

        return response()->json(['status' => 'success']);
    }
}
