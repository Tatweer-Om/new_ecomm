<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;
// use Modules\Color\Http\Controllers\Api\v1\ColorController;
// use Modules\User\Models\User;

$apiVersion = env('API_VERSION', 'v1'); // default to v1
$colorControllerClass = "Modules\\Color\\Http\\Controllers\\Api\\{$apiVersion}\\ColorController";
 
Route::prefix($apiVersion)
->middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum', // <-- move here
])
->group(function () use ($colorControllerClass) { 
     
    Route::apiResource('colors', $colorControllerClass)->names('color');
});
