<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

$apiVersion = strtolower((string) env('API_VERSION', 'v1'));
$colorControllerClass = \Modules\Color\Http\Controllers\Web\ColorController::class;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () use ($colorControllerClass, $apiVersion) {
    Route::get('colors', [$colorControllerClass, 'index'])
        ->name("{$apiVersion}.colors.index")
        ->middleware('permission:dress');

    Route::post('colors', [$colorControllerClass, 'store'])
        ->name("{$apiVersion}.colors.store")
        ->middleware('permission:dress');

    Route::get('colors/{color}', [$colorControllerClass, 'show'])
        ->name("{$apiVersion}.colors.show")
        ->middleware('permission:dress');

    Route::put('colors/{color}', [$colorControllerClass, 'update'])
        ->name("{$apiVersion}.colors.update")
        ->middleware('permission:dress');

    Route::delete('colors/{color}', [$colorControllerClass, 'destroy'])
        ->name("{$apiVersion}.colors.destroy")
        ->middleware('permission:dress');

    Route::post('colors/{id}/restore', [$colorControllerClass, 'restore'])
        ->whereNumber('id')
        ->name("{$apiVersion}.colors.restore")
        ->middleware('permission:dress');
});
