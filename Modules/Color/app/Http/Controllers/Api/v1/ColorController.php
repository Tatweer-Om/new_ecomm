<?php

namespace Modules\Color\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Color\Models\Color;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class ColorController extends Controller
{
    public function index(Request $request)
    {
        try {
            $colors = Color::latest()->paginate(10); // 10 items per page

            // If AJAX request, just return HTML of the table (we will render it in Blade)
            if ($request->ajax()) {
                return view('color::index', compact('colors'))->render();
            }

            return view('color::index', compact('colors'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Internal Server Error: ' . $e->getMessage());
        }
    }



    public function store(Request $request)
    {
        $today=date('Y-m-d');
        
        try {
            $validated = $request->validate([
                'color_name' => 'required|string|max:255',
                'color_name_ar' => 'required|string|max:255', 
            ]);
          
            $color = Color::create([
                'color_name' => $validated['color_name'],
                'color_name_ar' => $validated['color_name_ar'],
                'color_code' => $request['color_code'],
                'user_id' => Auth::guard('tenant')->id(),
                'added_by' => Auth::guard('tenant')->user()->name,
                'add_date' => $today,
            ]);

            return response()->json([
                'status' => true,
                'message' =>  trans('color::messages.color_add_success_lang', [], session('locale')),
                'data' => $color
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
            $color = Color::find($id);
            if (!$color) {
                return response()->json([
                    'status' => false,
                    'message' => trans('color::messages.color_not_found_lang', [], session('locale')),
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Color fetched successfully',
                'data' => $color
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
                'color_name' => 'required|string|max:255',
                'color_name_ar' => 'required|string|max:255', 
            ]);

            $color = Color::find($id);
            if (!$color) {
                return response()->json([
                    'status' => false,
                    'message' => trans('color::messages.color_not_found_lang', [], session('locale')),
                ], 404);
            }

            $color->update([
                'color_name' => $validated['color_name'],
                'color_name_ar' => $validated['color_name_ar'],
                'color_code' => $request['color_code'],
                'updated_by' => Auth::guard('tenant')->user()->name,
                'update_date' => $today,
            ]);

            return response()->json([
                'status' => true,
                'message' => trans('color::messages.color_update_success_lang', [], session('locale')),
                'data' => $color
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
            $color = Color::find($id);
            if (!$color) {
                return response()->json([
                    'status' => false,
                    'message' => trans('color::messages.color_not_found_lang', [], session('locale')),
                ], 404);
            }

            $color->delete();

            return response()->json([
                'status' => true,
                'message' => trans('color::messages.delete_success_lang', [], session('locale')),
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
