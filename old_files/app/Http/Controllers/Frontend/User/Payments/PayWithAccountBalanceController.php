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

class PayWithAccountBalanceController extends Controller
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
        
        $user_balance = $request->user()->account_balance();
        if($user_balance < $request->amount){
            return redirect(route('frontend.user.course.checkout', $course))->withFlashDanger('You do not have sufficient funds in your account to complete this purchase');
        }
        
        // now complete the purchase
        $affid = $request->cookie('EDUCORE_AFFID');
        $referee = User::where('affiliate_id', $affid)->first();
     
        \Gabs::processPayment($request, $referee, 'account-balance', 'Course purchase with account balance');
        
        $author_transaction = $author->transactions()->create([
            'uuid' => 2431000 + time() + random_int(99, 2000),
            'type' => 'credit',
            'description' => 'Sale',
            'long_description' => 'Sale of '. $course->title,
            'amount' => $payment->author_earning
        ]);
        
        $payment->transaction_id = $author_transaction->id;
        $payment->save();
        
        return redirect()->route('frontend.course.show', $course)->withFlashSuccess('Payment Processed Successfully. Thank you!');
    }
}
