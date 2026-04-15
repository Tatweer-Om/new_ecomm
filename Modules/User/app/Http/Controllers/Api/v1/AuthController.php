<?php

namespace Modules\User\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Dashboard\Http\Controllers\Api\v1\DashboardController;




class AuthController extends Controller
{
    

    // Show login form
    public function tshowLogin()
    {
        return view('user::login');
    }

    public function tlogin(Request $request)
    {
        $credentials = $request->only('name', 'password');  

        if (Auth::guard('tenant')->attempt($credentials)) {
            $request->session()->regenerate(); // important

            // Redirect to UserController@index
            return redirect()->action([DashboardController::class, 'index']);
        }

        // Back with error
        return back()->withErrors([
            'name' => 'Invalid credentials',
        ])->withInput();
    }



    // Handle logout
    public function tlogout(Request $request)
    {
        Auth::guard('tenant')->logout();  // log out tenant

        $request->session()->invalidate();      // invalidate the session
        $request->session()->regenerateToken(); // regenerate CSRF token
        return redirect()->action([AuthController::class, 'tshowLogin']);
    }
}
