<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;

$apiVersion = env('API_VERSION', 'v1'); // default to v1
$branchControllerClass = "Modules\\Branch\\Http\\Controllers\\Api\\{$apiVersion}\\BranchController";

Route::prefix($apiVersion)
->middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:sanctum', // <-- move here
])
->group(function () use ($branchControllerClass) { 
    Route::apiResource('branches', $branchControllerClass)->names('branch');
});
