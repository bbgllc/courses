<?php

namespace App\Http\Controllers\Frontend\User\Payments;

use Cookie;
use Session;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Coupon;
use App\Models\Transaction;
use App\Models\Auth\User;
use App\Models\Payment as CoursePayment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StripePaymentController extends Controller
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
        
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $token = $request->token;
        
        $charge = \Stripe\Charge::create(array(
            "amount" => $request->amount * 100,
            "currency" => config('site_settings.site_currency_code'),
            "description" => "Purchase by >> " . $request->user()->email . " <<",
            "source" => $token,
        ));
        
        if ($charge) {
            $affid = $request->cookie('EDUCORE_AFFID');
            $referee = User::where('affiliate_id', $affid)->first();
         
            \Gabs::processPayment($request, $referee, 'stripe', 'Expires: ' . $request->input('expiry-month') . '/' . $request->input('expiry-year'));
            
            return redirect()->route('frontend.course.show', $course)->withFlashSuccess('Payment Processed Successfully. Thank you!');
        } else {
            return redirect(route('frontend.user.course.checkout', $course))->withFlashDanger('Error processing payment. Please try again.');
        }
    }
    
}
