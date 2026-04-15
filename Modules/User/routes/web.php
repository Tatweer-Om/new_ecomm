<?php
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Use environment version internally
$apiVersion = env('API_VERSION', 'v1'); // default v1

// Build controller namespace dynamically based on version
$userControllerClass = "Modules\\User\\Http\\Controllers\\Api\\{$apiVersion}\\UserController";
$authControllerClass = "Modules\\User\\Http\\Controllers\\Api\\{$apiVersion}\\AuthController";

// Public routes (no auth required)
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () use ($authControllerClass, $apiVersion) {

    Route::post('/tlogin', [$authControllerClass, 'tlogin'])->name('tlogin');
    Route::get('/tlogout', [$authControllerClass, 'tlogout'])->name('tlogout');
    Route::get('/tshowLogin', [$authControllerClass, 'tshowLogin'])->name('tshowLogin');

    
});


// Protected routes (require tenant authentication)
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    // 'tenant.auth', // <- use your custom middleware
])->group(function () use ($userControllerClass, $apiVersion) {

    Route::get('users', [$userControllerClass, 'index'])->name("{$apiVersion}.users.index")->middleware('permission:user');
    Route::get('users/create', [$userControllerClass, 'create'])->name("{$apiVersion}.users.create")->middleware('permission:user');
    Route::post('users', [$userControllerClass, 'store'])->name("{$apiVersion}.users.store")->middleware('permission:user');
    Route::get('users/{user}', [$userControllerClass, 'show'])->name("{$apiVersion}.users.show")->middleware('permission:user');
    Route::put('users/{user}', [$userControllerClass, 'update'])->name("{$apiVersion}.users.update")->middleware('permission:user');
    Route::delete('users/{user}', [$userControllerClass, 'destroy'])->name("{$apiVersion}.users.destroy")->middleware('permission:user');

});
