<?php

namespace Modules\Setting\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Setting\Models\Setting;
use Modules\Setting\Models\Sms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $settings = Setting::first(); // 10 items per page
            return view('setting::index', compact('settings'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Internal Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $today=date('Y-m-d');
        try {
            $validated = $request->validate([
                'company_name' => 'required|string',
                'company_contact' => 'required|integer',  
                'company_email' => 'required|string',  
                'cr_number' => 'required',  
                'image' => 'image|mimes:jpeg,jpg,png|max:5120', // 5 MB per image
            ]);

            $setting = Setting::find($request['id']);

            if($setting)
            {
                $imageName=$setting->logo;
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    // Move to public/uploads/dress_images/
                    $image->move(public_path('uploads/setting_images'), $imageName);
                }

                
                $setting->update([
                    'name'          => $validated['company_name'],
                    'contact'            => $validated['company_contact'],
                    'email' => $validated['company_email'],
                    'cr_number'          => $validated['cr_number'],
                    'logo'          => $imageName,
                    'updated_by' => Auth::guard('tenant')->user()->name,
                    'update_date'       => $today,
                ]);
                $msg=trans('setting::messages.setting_update_success_lang', [], session('locale'));
            }
            else
            {
                $imageName="";
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    // Move to public/uploads/dress_images/
                    $image->move(public_path('uploads/setting_images'), $imageName);
                }

                
                $setting = Setting::create([
                    'name'          => $validated['title'],
                    'contact'            => $validated['qty'],
                    'email' => $request['purchase_price'] ?? null,
                    'cr_number'          => $validated['price'],
                    'logo'          => $imageName,
                    'user_id' => Auth::guard('tenant')->id(),
                    'added_by' => Auth::guard('tenant')->user()->name,
                    'add_date'       => $today,
                ]);
                $msg=trans('setting::messages.setting_add_success_lang', [], session('locale'));
            }
            
 

            return response()->json([
                'status'  => true,
                'message' => $msg,
                'data'    => $setting
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

    // get sms status
    public function getSmsStatus(Request $request)
    {
        try {
             
            $sms_status = $request->get('sms_status');
            // dress booking detail
            $sms = Sms::where('sms_status', $sms_status)
                ->first();  
                
            $text = "";
            if($sms)
            {
                $text = base64_decode($sms->sms);
            }

            return response()->json([
                'status' => true,
                'message' => 'Get sms successfull',
                'data' => $text
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    // store sms panel
    public function storeSmsPanel(Request $request) {
        $today=date('Y-m-d');
        try {
            $validated = $request->validate([
                'sms_status' => 'required|integer',
                'sms' => 'required|string',  
            ]);

            $sms = Sms::where('sms_status',$request['sms_status'])->first();

            if($sms)
            {
                
                $sms->update([
                    'sms'          => base64_encode($validated['sms']),
                    'updated_by' => Auth::guard('tenant')->user()->name,
                    'update_date'       => $today,
                ]);
                $msg=trans('setting::messages.sms_update_success_lang', [], session('locale'));
            }
            else
            {
                
                $sms = Sms::create([
                    'sms'          => base64_encode($validated['sms']),
                    'sms_status'            => $validated['sms_status'],
                    'user_id' => Auth::guard('tenant')->id(),
                    'added_by' => Auth::guard('tenant')->user()->name,
                    'add_date'       => $today,
                ]);
                $msg=trans('setting::messages.sms_add_success_lang', [], session('locale'));
            }
            
 

            return response()->json([
                'status'  => true,
                'message' => $msg,
                'data'    => $sms
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
}
