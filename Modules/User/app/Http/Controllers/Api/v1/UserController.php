<?php

namespace Modules\User\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = User::latest()->paginate(10); // 10 items per page
            $permissions = [
                'dashboard' => [
                    'label' => 'Dashboard',
                    'icon'  => 'fa-solid fa-chart-line',
                    'class' => 'dashboard',
                ],
                'user' => [
                    'label' => 'Users',
                    'icon'  => 'fa-solid fa-users',
                    'class' => 'user',
                ],
                'booking' => [
                    'label' => 'Bookings',
                    'icon'  => 'fa-solid fa-calendar-check',
                    'class' => 'booking',
                ],
                'customer' => [
                    'label' => 'Customers',
                    'icon'  => 'fa-solid fa-user',
                    'class' => 'customer',
                ],
                'expense' => [
                    'label' => 'Expense',
                    'icon'  => 'fa-solid fa-money-bill',
                    'class' => 'expense',
                ],
                'dress' => [
                    'label' => 'Dress',
                    'icon'  => 'fa-solid fa-shirt',
                    'class' => 'dress',
                ],
                'laundry' => [
                    'label' => 'Laundry',
                    'icon'  => 'fa-solid fa-soap',
                    'class' => 'laundry',
                ],
                'setting' => [
                    'label' => 'Settings',
                    'icon'  => 'fa-solid fa-gear',
                    'class' => 'setting',
                ],
                'add_dress' => [
                    'label' => 'Add Dress',
                    'icon'  => 'fa-solid fa-plus',
                    'class' => 'add_dress',
                ],
                'delete_booking' => [
                    'label' => 'Delete Booking',
                    'icon'  => 'fa-solid fa-trash',
                    'class' => 'delete_booking',
                ],
                'delete_booking' => [
                    'label' => 'Report',
                    'icon'  => 'fa-solid fa-chart-line',
                    'class' => 'report',
                ],
            ];

            // If AJAX request, just return HTML of the table (we will render it in Blade)
            if ($request->ajax()) {
                return view('user::index', compact('users','permissions'))->render();
            }

            return view('user::index', compact('users','permissions'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Internal Server Error: ' . $e->getMessage());
        }
    } 


    public function store(Request $request)
    {
        $today=date('Y-m-d');
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'contact' => 'required|integer', 
                'password' => 'required', 
                'email' => 'email|unique:users',
            ]);

            // Permission fields
            $permissions = [
                'dashboard',
                'user',
                'booking',
                'customer',
                'expense',
                'dress',
                'laundry',
                'setting',
                'add_dress',
                'delete_booking',
                'report'
            ];

            // Build permissions array dynamically
            $permissionData = [];
            foreach ($permissions as $p) {
                $permissionData[$p] = $request->has($p) ? 1 : 0;
            }

            // Create user
            $user = User::create(array_merge([
                'name' => $validated['name'],
                'contact' => $validated['contact'],
                'email' => $request['email'],
                'password' => Hash::make($request->password),
                'user_id' => Auth::guard('tenant')->id(),
                'added_by' => Auth::guard('tenant')->user()->name,
                'add_date' => $today,
            ], $permissionData));

            

            return response()->json([
                'status' => true,
                'message' => trans('user::messages.user_add_success_lang', [], session('locale')),
                'data' => $user
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => trans('User::messages.user_not_found_lang', [], session('locale')),
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'user fetched successfully',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $today = date('Y-m-d');

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'contact' => 'required|integer',
            ]);

            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => trans('user::messages.user_not_found_lang', [], session('locale')),
                ], 404);
            }

            $updateData = [
                'name'       => $validated['name'],
                'contact'    => $validated['contact'],
                'email'      => $request['email'],
                'updated_by' => Auth::guard('tenant')->user()->name,
                'update_date'=> $today,
            ];

            // Update password only if changed
            if (!empty($request->password)) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Permission fields
            $permissions = [
                'dashboard',
                'user',
                'booking',
                'customer',
                'expense',
                'dress',
                'laundry',
                'setting',
                'add_dress',
                'delete_booking',
                'report'
            ];

            // Build permissions array
            foreach ($permissions as $p) {
                $updateData[$p] = $request->has($p) ? 1 : 0;
            }

            $user->update($updateData);

            return response()->json([
                'status' => true,
                'message' => trans('user::messages.user_update_success_lang', [], session('locale')),
                'data' => $user
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => trans('user::messages.user_not_found_lang', [], session('locale')),
                ], 404);
            }

            $user->delete();

            return response()->json([
                'status' => true,
                'message' => trans('user::messages.delete_success_lang', [], session('locale')),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    

     
}
