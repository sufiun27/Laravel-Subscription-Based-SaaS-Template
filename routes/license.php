<?php
// routes/license.php
use App\Http\Controllers\LicenseController;
use Illuminate\Support\Facades\Route;

Route::middleware('redirect.if.licensed')->group(function () {
    Route::get('license/activate', [LicenseController::class, 'activate'])->name('license.activate');
    Route::post('license/activate', [LicenseController::class, 'activateSubmit'])->name('license.activate.submit');
});

Route::get('license/check-updates', [LicenseController::class, 'checkUpdates'])->name('license.check.updates');


Route::get('license', function () {
    return 'License';
});
