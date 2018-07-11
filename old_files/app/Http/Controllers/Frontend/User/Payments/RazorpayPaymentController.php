<?php

namespace App\Http\Controllers\Frontend\User\Payments;

use Session;
use Redirect;
use Cookie;
use Carbon\Carbon;
use Razorpay\Api\Api;
use App\Models\Auth\User;
use App\Models\Course;
use App\Models\Coupon;
use App\Models\Transaction;
use Illuminate\Support\Facades\Input;
use App\Models\Payment as CoursePayment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RazorpayPaymentController extends Controller
{
    
    
    public function charge(Request $request)
    {
        $course = Course::find($request->course);
        
        $coupon = Coupon::where('code', $request->coupon)
            ->where(function($q) use ($course) {
                $q->where('course_id', $course->id)
                  ->orWhere('sitewide', true);
            })->first();

        if(\Gabs::checkSubmittedPrice($request) == 'error'){
            return redirect(route('frontend.user.course.checkout', $course))
                        ->withFlashDanger('The price submitted does not match the course price. Purchase declined!');
        }
        
        $input = Input::all();
        
        //get API Configuration 
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        
        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])
                                ->capture(array('amount'=>$payment['amount'])); 
                
                // save to database
                $affid = $request->cookie('EDUCORE_AFFID');
                $referee = User::where('affiliate_id', $affid)->first();
             
                \Gabs::processPayment($request, $referee, 'razorpay', 'RazorPay ID: ' . $response->id);
                
                return redirect()->route('frontend.course.show', $course)->withFlashSuccess('Payment Processed Successfully. Thank you!');
                
            } catch (\Exception $e) {
                return  $e->getMessage();
                \Session::put('error', $e->getMessage());
                return redirect()->back();
            }
        }
        
    }
}















