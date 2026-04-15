<?php
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Models\User;

 

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // Route::apiResource('users', UserController::class)->names('users');
});

Route::get('/tshowLogin', function () {
    return view('user::login'); // your Vue app shell
});
 
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    //   Route::get('/generate-token', function () {
         
    //     $user = User::first(); // ✅ now works
         
    //     $token = $user->createToken('tenant-api-token')->plainTextToken;
    //     return response()->json([
    //         'user' => $user,
    //         'token' => $token,
    //     ]);
    // });
    Route::post('/tlogin', [AuthController::class, 'tlogin']);
    Route::get('/tlogout', [AuthController::class, 'tlogout']);
    Route::apiResource('users', UserController::class);
    Route::post('/search-user', [UserController::class, 'searchUser']);
    Route::get('/check-auth', function () {
        return response()->json(['authenticated' => Auth::guard('tenant')->check()]);
    });
     
    
});