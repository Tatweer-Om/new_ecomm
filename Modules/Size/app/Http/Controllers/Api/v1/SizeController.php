<?php
namespace Modules\Size\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Size\Models\Size;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class SizeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $sizes = Size::latest()->paginate(10); // 10 items per page

            // If AJAX request, just return HTML of the table (we will render it in Blade)
            if ($request->ajax()) {
                return view('size::index', compact('sizes'))->render();
            }

            return view('size::index', compact('sizes'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Internal Server Error: ' . $e->getMessage());
        }
    }



    public function store(Request $request)
    {
        $today=date('Y-m-d');
        
        try {
            $validated = $request->validate([
                'size_name' => 'required|string|max:255',
                'size_name_ar' => 'required|string|max:255',  
            ]);
          
            $size = Size::create([
                'size_name' => $validated['size_name'],
                'size_name_ar' => $validated['size_name_ar'], 
                'size_code' => $request['size_code'], 
                'description' => $request['description'],
                'user_id' => Auth::guard('tenant')->id(),
                'added_by' => Auth::guard('tenant')->user()->name,
                'add_date' => $today,
            ]);

            return response()->json([
                'status' => true,
                'message' =>  trans('size::messages.size_add_success_lang', [], session('locale')),
                'data' => $size
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
            $size = Size::find($id);
            if (!$size) {
                return response()->json([
                    'status' => false,
                    'message' => trans('size::messages.size_not_found_lang', [], session('locale')),
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'size fetched successfully',
                'data' => $size
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
                'size_name' => 'required|string|max:255',
                'size_name_ar' => 'required|string|max:255',  
            ]);

            $size = Size::find($id);
            if (!$size) {
                return response()->json([
                    'status' => false,
                    'message' => trans('size::messages.size_not_found_lang', [], session('locale')),
                ], 404);
            }

            $size->update([
                'size_name' => $validated['size_name'],
                'size_name_ar' => $validated['size_name_ar'], 
                'size_code' => $request['size_code'], 
                'description' => $request['description'], 
                'updated_by' => Auth::guard('tenant')->user()->name,
                'update_date' => $today,
            ]);

            return response()->json([
                'status' => true,
                'message' => trans('size::messages.size_update_success_lang', [], session('locale')),
                'data' => $size
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
            $size = Size::find($id);
            if (!$size) {
                return response()->json([
                    'status' => false,
                    'message' => trans('size::messages.size_not_found_lang', [], session('locale')),
                ], 404);
            }

            $size->delete();

            return response()->json([
                'status' => true,
                'message' => trans('size::messages.delete_success_lang', [], session('locale')),
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
