<?php 
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;

// Use environment version internally
$apiVersion = env('API_VERSION', 'v1'); // default v1

// Build controller namespace dynamically based on version
$settingControllerClass = "Modules\\Setting\\Http\\Controllers\\Api\\{$apiVersion}\\SettingController";

// Route group without version in URL
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    // 'tenant.auth', // <- use your custom middleware
])->group(function () use ($settingControllerClass, $apiVersion) {

    Route::get('settings', [$settingControllerClass, 'index'])->name("{$apiVersion}.settings.index")->middleware('permission:setting');
    Route::post('settings', [$settingControllerClass, 'store'])->name("{$apiVersion}.settings.store")->middleware('permission:setting');
    Route::get('getSmsStatus', [$settingControllerClass, 'getSmsStatus'])->name("{$apiVersion}.settings.getSmsStatus")->middleware('permission:setting');
    Route::post('storeSmsPanel', [$settingControllerClass, 'storeSmsPanel'])->name("{$apiVersion}.settings.storeSmsPanel")->middleware('permission:setting');
});


 