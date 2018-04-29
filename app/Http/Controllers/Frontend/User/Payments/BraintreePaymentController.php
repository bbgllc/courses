<?php

namespace App\Http\Controllers\Frontend\User\Payments;

use Session;
use Cookie;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Coupon;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Models\Payment as CoursePayment;
use App\Http\Controllers\Controller;


class BraintreePaymentController extends Controller
{
    
    public function token()
    {
        return response()->json([
            'data' => [
                'token' => \Braintree_ClientToken::generate(),
            ]
        ]);
    }
    
    
    public function charge(Request $request)
    {
        
        if( ($nonce = $request->payment_method_nonce) === null ) {
            return back();
        }
        
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
        
        $result = \Braintree_Transaction::sale([
            'amount' => $request->amount,
            'paymentMethodNonce' => $nonce,
            'options' => [ 
                'submitForSettlement' => true 
            ]
        ]);
        
        if ($result->success) {
            
            $affid = $request->cookie('EDUCORE_AFFID');
            $referee = User::where('affiliate_id', $affid)->first();
            \Gabs::processPayment($request, $referee, 'braintree', 'Processed with BrainTree');
            
            return redirect()->route('frontend.course.show', $course)->withFlashSuccess('Payment Processed Successfully. Thank you!');
            
        } else if ($result->transaction) {
            return redirect(route('frontend.user.course.checkout', $course))->withFlashDanger('Error processing payment. Please try again.');
        } else {
            return redirect(route('frontend.user.course.checkout', $course))->withFlashDanger('Error processing payment. Please try again.');
        }
    }
    
}
