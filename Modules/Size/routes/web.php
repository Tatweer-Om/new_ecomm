<?php 
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;

// Use environment version internally
$apiVersion = env('API_VERSION', 'v1'); // default v1

// Build controller namespace dynamically based on version
$sizeControllerClass = "Modules\\Size\\Http\\Controllers\\Api\\{$apiVersion}\\SizeController";

// Route group without version in URL
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    // 'tenant.auth', // <- use your custom middleware
])->group(function () use ($sizeControllerClass, $apiVersion) {

    Route::get('sizes', [$sizeControllerClass, 'index'])->name("{$apiVersion}.sizes.index")->middleware('permission:dress');
    Route::get('sizes/create', [$sizeControllerClass, 'create'])->name("{$apiVersion}.sizes.create")->middleware('permission:dress');
    Route::post('sizes', [$sizeControllerClass, 'store'])->name("{$apiVersion}.sizes.store")->middleware('permission:dress');
    Route::get('sizes/{size}', [$sizeControllerClass, 'show'])->name("{$apiVersion}.sizes.show")->middleware('permission:dress');
    Route::put('sizes/{size}', [$sizeControllerClass, 'update'])->name("{$apiVersion}.sizes.update")->middleware('permission:dress');
    Route::delete('sizes/{size}', [$sizeControllerClass, 'destroy'])->name("{$apiVersion}.sizes.destroy")->middleware('permission:dress');

});


 