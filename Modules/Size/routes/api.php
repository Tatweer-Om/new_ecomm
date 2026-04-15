<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;
// use Modules\size\Http\Controllers\Api\v1\sizeController;
// use Modules\User\Models\User;

$apiVersion = env('API_VERSION', 'v1'); // default to v1
$sizeControllerClass = "Modules\\Size\\Http\\Controllers\\Api\\{$apiVersion}\\SizeController";

Route::prefix($apiVersion)
->middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum', // <-- move here
])
->group(function () use ($sizeControllerClass) { 
    Route::apiResource('sizes', $sizeControllerClass)->names('size');
});
