<?php


namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Auth\User;
use App\Models\Payment;
use App\Models\Course;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {   
        
        $courses = Course::where('approved', false)->where('published', true)->latest();
        $withdrawals = Withdrawal::where('status', 'pending')->latest();
        return view('backend.dashboard', compact('withdrawals', 'courses'));
        
    }
    
    
    public function fetchAdminSalesChartData(Request $request)
    {
        
        $filter = $request->period;
        $periods = collect(\Gabs::generateDateRange(Carbon::now()->subDays($filter), Carbon::now()));
        
        $times = [];
        foreach($periods as $p){
            $times[$p] = 0;
        }
        
        $data = [];
        // Total Sales
        $sales = Payment::where('created_at', '>=', Carbon::now()->subDays($filter))
                        ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('sum(amount) as total'))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
        $total_sales = [];
        foreach($sales as $val){
            $total_sales[$val->date] = (float)$val->total;
        }
        
        $grand_total = array_merge($times, $total_sales);
        
        array_push($data, [
            'name' => 'Total Sales',
            'data' => $grand_total
        ]);
        
        
        // get sales minus author commission
        $platform_earnings = Payment::where('created_at', '>=', Carbon::now()->subDays($filter))
                        ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('sum(amount - author_earning - affiliate_earning) as total'))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
        
        $total_earnings = [];
        foreach($platform_earnings as $val){
            $total_earnings[$val->date] = (float)$val->total;
        }
        
        $grand_earnings = array_merge($times, $total_earnings);
        
        array_push($data, [
            'name' => 'Platform Earnings',
            'data' => $grand_earnings
        ]);
        
        
        // total sales
        $lifetime_sales = Payment::all()->sum('amount');
        $total_platform_earnings = Payment::select(\DB::raw('sum(amount - author_earning - affiliate_earning) as total'))->first();
        $lifetime_data = [
           'lifetime_sales' => $lifetime_sales ? (float)$lifetime_sales : 0,
           'lifetime_earnings' => $total_platform_earnings ? (float)$total_platform_earnings->total : 0
        ];
        
        
        // get user countries data
        $country_stats = User::select('country', \DB::raw('count(*) as total'))
                        ->groupBy('country')
                        ->get()->pluck('total', 'country');
        
        // online users
        $user = new User;
        $online = $user->allOnline()->count();
        
        
        // perform checks
        
        $messages = $this->systemCheck();
        
        
        $res = [
            'chartData' => $data,
            'lifetimeData' => $lifetime_data,
            'country_stats' => $country_stats,
            'online' => $online,
            'messages' => $messages
        ];
        
        return response()->json($res, 200); 
    }
    
    
    private function systemCheck()
    {
        $messages = [];
        
        // check that stripe is configured
        $stripe_keys = env('STRIPE_KEY');
        $stripe_secret = env('STRIPE_SECRET');
        
        if((!$stripe_keys || !$stripe_secret) && config('site_settings.payment_enable_stripe')){
            array_push($messages, [
               'feature' => 'Stripe',
               'message' => __('t.stripe-not-configured')
            ]);
        }
        
        
        $paypal_client = env('PAYPAL_CLIENT_ID');
        $paypal_secret = env('PAYPAL_SECRET');
        
        if((!$paypal_client || !$paypal_secret) && config('site_settings.payment_enable_paypal')){
            array_push($messages, [
               'feature' => 'PayPal',
               'message' => __('t.paypal-not-configured') 
            ]);
        }
        
        $paypal_mode = env('PAYPAL_MODE');
        if(env('APP_ENV') == 'production' && strToLower($paypal_mode) != 'live' && config('site_settings.payment_enable_paypal')){
            array_push($messages, [
               'feature' => 'PayPal',
               'message' => __('t.paypal-in-sandbox') 
            ]);
        }
        
        $braintree_mode = env('BRAINTREE_ENV');
        if(env('APP_ENV') == 'production' && strToLower($braintree_mode) != 'live' && config('site_settings.payment_enable_braintree')){
            array_push($messages, [
               'feature' => 'Braintree',
               'message' => __('t.braintree-in-sandbox') 
            ]);
        }
        
        $bt_merchant = env('BRAINTREE_MERCHANT_ID');
        $bt_pk = env('BRAINTREE_PUBLIC_KEY');
        $bt_secret = env('BRAINTREE_PRIVATE_KEY');
        
        if((!$bt_merchant || !$bt_pk || !$bt_secret) && config('site_settings.payment_enable_braintree')){
            array_push($messages, [
               'feature' => 'Braintree',
               'message' => __('t.braintree-not-configured')
            ]);
        }
        
        $s3_key = env('AWS_KEY');
        $s3_secret = env('AWS_SECRET');
        $s3_bucket = env('AWS_BUCKET');
        $s3_region = env('AWS_REGION');
        
        if((!$s3_key || !$s3_secret || !$s3_bucket || !$s3_region) && config('site_settings.video_upload_location') == 's3'){
            array_push($messages, [
               'feature' => 's3',
               'message' => __('t.s3-not-configured')
            ]);
        }
        
        $mail_driver = env('MAIL_DRIVER');
        $mail_host = env('MAIL_HOST');
        $mail_port = env('MAIL_PORT');
        $mail_username = env('MAIL_USERNAME');
        $mail_pswd = env('MAIL_PASSWORD');
        
        if(!$mail_driver || !$mail_host || !$mail_port || !$mail_username || !$mail_pswd){
            array_push($messages, [
               'feature' => 'mail',
               'message' => __('t.mail-not-configured') 
            ]);
        }
        
        
        $disqus_username = env('DISQUS_USERNAME');
        
        if(!$disqus_username && env('DISQUS_ENABLED')){
            array_push($messages, [
               'feature' => 'disqus',
               'message' => __('t.disqus-not-configured')
            ]);
        }
        
        return $messages;
    }
    
    public function removeInstaller()
    {
        if(\File::exists(public_path().'/install.php')){
            \File::move(public_path().'/install.php', public_path().'/'. time() . '_install.BAK');
        }
        
        return back();
    }

}
