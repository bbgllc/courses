<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminCouponController extends Controller
{
    
    public function index(Request $request)
    {
        $coupons = Coupon::where('sitewide', true)->orderBy('active', 'DESC')->paginate(10);
        foreach($coupons as $coupon){
            $coupon->redeemed = Payment::where('coupon_id', $coupon->id)->count();
        }
        
        return view('backend.coupon.index', compact('coupons'));
    }
    
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'code' => 'required|alpha_dash|unique:coupons,code',
            'percent' => 'required|numeric|max:100',
            'quantity' => 'required|numeric|min:1',
            'expires' => 'required|date'
        ]);
        
        $coupon = new Coupon();
        $coupon->code = strToUpper($request->code);
        $coupon->percent = $request->percent;
        $coupon->quantity = $request->quantity;
        $coupon->expires = date("Y-m-d",strtotime($request->expires));
        $coupon->active = false;
        $coupon->sitewide = true;
        $coupon->save();
        
        return redirect()->back();
    }
    
    public function activate($id)
    {
        
        $coupon = Coupon::find($id);
        $now = Carbon::now();
        
        if($now > $coupon->expires){
            return redirect()->back()->withFlashDanger('This coupon has expired. You cannot reactivate it!');
        } 
        
        $qty_used = Payment::where('coupon_id', $coupon->id)->count();
        
        if($qty_used >= $coupon->quantity){
            return redirect()->back()->withFlashDanger('This coupon is exhausted. You cannot reactivate it');
        }
        
        $coupons = Coupon::where('sitewide', true)->where('id', '!=', $id)->get();
        
        foreach($coupons as $c){
            $c->active = false;
            $c->save();
        }
        
        $coupon->active = $coupon->active ? false : true;
        $coupon->save();
        
        return back();
       
    }
    
    
    public function destroy($id)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        
        Coupon::find($id)->delete();
        return back();
    }
    
}
