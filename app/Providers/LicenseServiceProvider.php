<?php
// app/Providers/LicenseServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LicenseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //$this->loadRoutesFrom(base_path('routes/license.php'));
        // $this->app['router']->middleware('check.license', \App\Http\Middleware\CheckLicense::class);
        // $this->app['router']->middleware('redirect.if.licensed', \App\Http\Middleware\RedirectIfLicensed::class);

        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->prependMiddleware(\App\Http\Middleware\CheckLicense::class);
    }

    public function register()
    {
        //
    }
}
