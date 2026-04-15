<?php 
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;

// Use environment version internally
$apiVersion = env('API_VERSION', 'v1'); // default v1

// Build controller namespace dynamically based on version
$colorControllerClass = "Modules\\Color\\Http\\Controllers\\Api\\{$apiVersion}\\ColorController";

// Route group without version in URL
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    // 'tenant.auth', // <- use your custom middleware
])->group(function () use ($colorControllerClass, $apiVersion) {

    Route::get('colors', [$colorControllerClass, 'index'])->name("{$apiVersion}.colors.index")->middleware('permission:dress');
    Route::get('colors/create', [$colorControllerClass, 'create'])->name("{$apiVersion}.colors.create")->middleware('permission:dress');
    Route::post('colors', [$colorControllerClass, 'store'])->name("{$apiVersion}.colors.store")->middleware('permission:dress');
    Route::get('colors/{color}', [$colorControllerClass, 'show'])->name("{$apiVersion}.colors.show")->middleware('permission:dress');
    Route::put('colors/{color}', [$colorControllerClass, 'update'])->name("{$apiVersion}.colors.update")->middleware('permission:dress');
    Route::delete('colors/{color}', [$colorControllerClass, 'destroy'])->name("{$apiVersion}.colors.destroy")->middleware('permission:dress');

});


 