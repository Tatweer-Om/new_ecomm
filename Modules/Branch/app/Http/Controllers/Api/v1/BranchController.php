<?php

namespace Modules\Branch\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Branch\Models\Branch;
use Modules\Setting\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Tenant;



class BranchController extends Controller
{
    public function index()
    {
        try {
            $branches = Branch::all();
            return response()->json([
                'status' => true,
                'message' => 'branches fetched successfully',
                'data' => $branches
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $today=date('Y-m-d');
        $setting = Setting::first();
        $totalBranches = Branch::count();
        $centralTenant = Tenant::on('mysql')->find($setting->tenant_id);
        if($totalBranches>=$centralTenant->branches)
        {
            return response()->json([
                'status' => false,
                'message' => 'Your branches limit is fullfilled already!',
                'data' => 'Total branches limit : '.$totalBranches
            ], 400); 
        }

        try {
            $validated = $request->validate([
                'branch_name' => 'required|string|max:255',
                'contact' => 'required|integer|max:50', 
            ]);

            $branch = Branch::create([
                'branch_name' => $validated['branch_name'],
                'contact' => $validated['contact'],
                'email' => $request['email'],
                'address' => $request['address'],
                'user_id' => Auth::id(),
                'added_by' => Auth::user()->name,
                'add_date' => $today,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'branch created successfully',
                'data' => $branch
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
            $branch = Branch::find($id);
            if (!$branch) {
                return response()->json([
                    'status' => false,
                    'message' => 'branch not found',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'branch fetched successfully',
                'data' => $branch
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
        $today=date('Y-m-d');
        try {
            $validated = $request->validate([
                'branch_name' => 'required|string|max:255',
                'contact' => 'required|integer|max:50',
            ]);

            $branch = Branch::find($id);
            if (!$branch) {
                return response()->json([
                    'status' => false,
                    'message' => 'branch not found',
                ], 404);
            }

            $branch->update([
                'branch_name' => $validated['branch_name'],
                'contact' => $validated['contact'],
                'email' => $request['email'],
                'address' => $request['address'],
                'updated_by' => Auth::user()->name,
                'update_date' => $today,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'branch updated successfully',
                'data' => $branch
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
            $branch = Branch::find($id);
            if (!$branch) {
                return response()->json([
                    'status' => false,
                    'message' => 'branch not found',
                ], 404);
            }

            $branch->delete();

            return response()->json([
                'status' => true,
                'message' => 'branch deleted successfully',
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
