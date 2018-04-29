<?php

namespace App\Helpers;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\Payment as CoursePayment;

class Gabs {
    

    public static function currency($value)
    {
        $value = round($value,1);
        if($value > 0){
            if(config('site_settings.site_currency_format') == 'front'){
                return config('site_settings.site_currency_symbol').$value;
            } else {
                return $value . config('site_settings.site_currency_symbol');
            }
        } else {
            return $value;
        }
    }
    
    public static function currency_string($value)
    {
        $value = round($value,1);
        if($value > 0){
            if(config('site_settings.site_currency_format') == 'front'){
                return config('site_settings.site_currency_symbol').$value;
            } else {
                return $value . config('site_settings.site_currency_symbol');
            }
        } else {
            return __('t.free');
        }
    }
    
    
    public static function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
    
        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
    
        return $dates;
    }
    
    
    
    public static function checkSubmittedPrice($request)
    {
        $course = Course::find($request->course);
        
        $coupon = Coupon::where('code', $request->coupon)
            ->where(function($q) use ($course) {
                $q->where('course_id', $course->id)
                  ->orWhere('sitewide', true);
            })->first();
        
        $check = 'success';
        
        if(!is_null($coupon)){
            $expected_amount = (float)($course->price - ($course->price * ($coupon->percent/100)));
    	    
    	    $expected_amount = number_format((int)$expected_amount ,2,'.','');
    	    $sent_amount = number_format((int)$request->amount,2,'.','');
    	    
            if($sent_amount != $expected_amount){
                $check = 'error';
            }
        } else {
            if($request->amount != $course->price){
                $check = 'error';
            }
        }
        
        return $check;
    }
    
    public static function processPayment($request, $referee, $gateway, $comment){
        
        $payer = $request->user();
        $amount = $request->amount;
        $course = Course::find($request->course);
        $author = $course->author;
        $coupon = Coupon::where('code', $request->coupon)
            ->where(function($q) use ($course) {
                $q->where('course_id', $course->id)
                  ->orWhere('sitewide', true);
            })->first();
            
        $organicPercent = config('site_settings.earning_organic_sales_percentage')/100;
        $promoPercent = config('site_settings.earning_promo_sales_percentage')/100;
        $affiliatePercent = config('site_settings.earning_affiliate_sales_percentage')/100;
        
        
        /**************** Calculate Earnings **********/
        if(!is_null($referee) && config('site_settings.site_enable_affiliate') == 1){
            $affiliateEarning = $amount*$affiliatePercent;
            $amountLeft = $amount - $affiliateEarning;
            
            if(!is_null($coupon) && $coupon->sitewide == false){
                $authorEarning = $amountLeft * $promoPercent;
            } else {
                $authorEarning = $amountLeft * $organicPercent;
            }
        } else {
            $affiliateEarning = 0;
            
            if(!is_null($coupon && $coupon->sitewide == false)){
                $authorEarning = $amount * $promoPercent;
            } else {
                $authorEarning = $amount * $organicPercent;
            }
            
        }
        
        /************** Insert Payments ***************/
        $payment = new CoursePayment();
        $payment->course_id = $request->course;
        $payment->payer_id = $payer->id;
        
        if(!is_null($referee) && config('site_settings.site_enable_affiliate') == 1){
            $payment->referred_by = $referee->id;
        }
        if(!is_null($coupon)){
            $payment->coupon_id = $coupon->id;
        }
        $payment->payment_method = $gateway;
        $payment->amount = $amount;
        $payment->description = 'sale';
        $payment->author_earning = $authorEarning;
        $payment->affiliate_earning = $affiliateEarning;
        $payment->payment_id = $comment;
        $payment->save();
        
        // enroll the student
        $course->students()->attach($payer->id);
        
        // insert transaction for author
        $transaction = $author->transactions()->create([
            'uuid' => 2431000 + time() + random_int(99, 2000),
            'type' => 'credit',
            'description' => 'Sale',
            'long_description' => 'Sale of '. $course->title,
            'amount' => $payment->author_earning
        ]);
        
        $payment->transaction_id = $transaction->id;
        $payment->save();
        
        // insert transaction for affiliate if it exists
        if(!is_null($referee) && config('site_settings.site_enable_affiliate') == 1){
            $transaction = $referee->transactions()->create([
                'uuid' => 2431000 + time() + random_int(99, 2000),
                'type' => 'credit',
                'description' => 'Affiliate Program',
                'long_description' => 'Earnings from Affiliate promotion of "'. $course->title . '"',
                'amount' => $affiliateEarning
            ]);
        }
        
        if($gateway=='account-balance'){
            $buyer_transaction = $payer->transactions()->create([
                'uuid' => 2431000 + time() + random_int(99, 2000),
                'type' => 'debit',
                'description' => 'Purchase',
                'long_description' => 'Purchase of '. $course->title . 'with account balance',
                'amount' => -$amount
            ]);
        }
        
        return;
        
    }
    
    
}