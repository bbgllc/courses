<?php

namespace App\Http\Controllers\Backend;

use App\Models\AdminSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminSettingsController extends Controller
{
    
    
    public function index()
    {
        $settings = AdminSettings::first();

        return view('backend.settings.index', compact('settings'));
    }
 
    public function update(Request $request)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        
        $setting = AdminSettings::first();
        \DB::table('admin_settings')
            ->update([
                    'site_name' => $request->site_name,
                    'site_description' => $request->site_description,

                    'site_google_analytics' => $request->site_google_analytics,
                    'site_currency_code' => $request->site_currency_code,
                    'site_currency_symbol' => $request->site_currency_symbol,
                    'site_currency_format' => $request->site_currency_format,
                    'site_keywords' => $request->site_keywords,
            
                    'footer_facebook' => $request->footer_facebook, 
                    'footer_twitter' => $request->footer_twitter, 
                    'footer_instagram' => $request->footer_instagram,
            
                    'pricelist' => $request->pricelist,
            
                    'payment_enable_paypal' => $request->payment_enable_paypal,
                    'payment_enable_stripe' => $request->payment_enable_stripe,
                    'payment_enable_braintree' => $request->payment_enable_braintree,
                    'payment_enable_pay_with_razorpay' => $request->payment_enable_pay_with_razorpay,
                    'payment_enable_pay_with_account_balance' => $request->payment_enable_pay_with_account_balance,
                    'payment_enable_omise' => $request->payment_enable_omise,
                    
                    'video_upload_location' => $request->video_upload_location,
                    'video_max_size' => $request->video_max_size,
                    'video_allow_upload' => $request->video_allow_upload,
                    'video_allow_youtube' => $request->video_allow_youtube,
                    'video_allow_vimeo' => $request->video_allow_vimeo,
                    
                    'site_enable_affiliate' => $request->site_enable_affiliate,
                    'earning_organic_sales_percentage' => $request->earning_organic_sales_percentage,
                    'earning_promo_sales_percentage' => $request->earning_promo_sales_percentage,
                    'earning_minimum_payout_amount' => $request->earning_minimum_payout_amount,
                    'earning_affiliate_sales_percentage' => $request->earning_affiliate_sales_percentage ,
                    
                    'receipt_address' => $request->receipt_address
                ]);
        
        return response()->json(null, 200);
    }
    
    public function uploadLogos(Request $request)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        
        $settings = AdminSettings::first();
        
        if ($request->hasFile('files')) {
            $file = $request->file('files');
            
            if($request->type == 'site_logo'){
                $oldImage = $settings->site_logo;
                
                
                $logo = $file->storeAs('img', time().'_'.$file->getClientOriginalName(), 'assets');
                
                $settings->site_logo = '/'.$logo;
                $settings->save();
                
                $path = $settings->site_logo;
                
                if($settings->site_logo && \Storage::disk('assets')->exists($oldImage)){
                    \Storage::disk('assets')->delete($oldImage);
                }
            }
            
            if($request->type == 'site_favicon'){
                $oldImage = $settings->site_favicon;
                $favicon = $file->storeAs('img', time().'_'.$file->getClientOriginalName(), 'assets');
                //$favicon = $file->move('img', 'favicon_'.time().'.png');
                $settings->site_favicon = '/'.$favicon;
                $settings->save();
                
                $path = $settings->site_favicon;
                
                if($settings->site_favicon && \Storage::disk('assets')->exists($oldImage)){
                    \Storage::disk('assets')->delete($oldImage);
                }
            }
        }
        
        return response([
            'data' => [
                'path' => $path,
            ]
        ], 200);
        
        
    }
    
    
}
