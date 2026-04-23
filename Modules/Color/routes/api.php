<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

$apiVersion = strtolower((string) env('API_VERSION', 'v1'));
$versionNs = ucfirst($apiVersion);
$colorControllerClass = "Modules\\Color\\Http\\Controllers\\Api\\{$versionNs}\\ColorController";

Route::prefix($apiVersion)
    ->middleware([
        InitializeTenancyByDomain::class,
        PreventAccessFromCentralDomains::class,
        'auth:sanctum',
    ])
    ->group(function () use ($colorControllerClass) {
        Route::post('colors/{id}/restore', [$colorControllerClass, 'restore'])
            ->whereNumber('id')
            ->name('colors.restore');

        Route::apiResource('colors', $colorControllerClass)->names('color');
    });
