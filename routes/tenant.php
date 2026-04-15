<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\AuthController;


$apiVersion = env('API_VERSION', 'v1'); // default v1
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
      
    // 🌍 Language switch route
    Route::get('change-language/{lang}', function ($lang) {
        $available = ['en', 'ar'];

        if (in_array($lang, $available)) {
            Session::put('locale', $lang);
            App::setLocale($lang);
        }

        // Redirect back to the previous page
        return redirect()->back();
    })->name('change.language');


});
