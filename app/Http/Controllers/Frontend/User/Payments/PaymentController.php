<?php

namespace App\Http\Controllers\Frontend\User\Payments;

use App\Models\Auth\User;
use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    
    
    public function checkout(Request $request, Course $course)
    {
    
        $coupon_code = $request->COUPON;

        if(auth()->user()->canAccessCourse($course)){
            return redirect(route('frontend.course.show', $course));
        }
        
        return view('courses.course-checkout', compact('course', 'coupon_code'));   
        
        
    }
    
    public function applyCoupon(Request $request)
    {
        $code = strToUpper($request->code);
        
        $course_id = $request->course;
        $coupon = Coupon::where('code', $code)
            ->where(function($q) use ($course_id) {
                $q->where('course_id', $course_id)
                  ->orWhere('sitewide', true);
            })->first();
            
        $course = Course::find($request->course);
        
        if($coupon && $coupon->active){
            $status = $this->verifyCoupon($coupon);
            if($status == 'expired' || $status == 'exhausted'){
                $coupon_status = $status;
                $price = $course->price;
            } else {
                $coupon_status = 'valid';
                $price = $course->price - (($coupon->percent/100) * $course->price);
            }
        } else {
            $coupon_status = 'invalid';
            $price = $course->price;
        }
        
        return response()->json([
           'status' => $coupon_status,
           'price' => $price,
           'code' => $code
        ], 200);

    }
    
    
    
    
    protected function verifyCoupon($coupon)
    {
        $quantity = $coupon->quantity;
        $totalUsed = $coupon->payments->count(); 
        
        $now = \Carbon\Carbon::now();
        
        if( !is_null($coupon->expires) && $coupon->expires < $now ){
            return 'expired';
        }
        if( $totalUsed >= $quantity){
            return 'exhausted';
        }

        return 'valid';
    }
    
    
}
