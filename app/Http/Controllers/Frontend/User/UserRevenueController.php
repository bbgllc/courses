<?php

namespace App\Http\Controllers\Frontend\User;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Access\User\User;
use App\Notifications\Frontend\WithdrawalRequestReceived;
use App\Http\Controllers\Controller;

class UserRevenueController extends Controller
{
    public function sales(Request $request)
    {
        
        if(!auth()->user()->isAuthor()){
            return redirect('/author/create-course');
        }
        $user = $request->user();
        $user_courses = Course::where('user_id', $user->id)->pluck('id');
        $all_user_earnings = Payment::with('user', 'coupon', 'course')
                    ->whereIn('course_id', $user_courses)
                    ->latest()->paginate(20);

        return view('_User.revenue-sales', compact('all_user_earnings'));
    }
    
    public function withdrawals(Request $request)
    {
        
        $withdrawals = Withdrawal::latest()->where('user_id', $request->user()->id)->paginate(20);
        
        return view('_User.revenue-withdrawals', compact('withdrawals'));
    }
    
    
    public function transactions(Request $request)
    {
        
        $transactions = Transaction::latest()->where('user_id', auth()->user()->id)->paginate(20);
        return view('_User.revenue-transactions', compact('transactions'));
    }
    
    public function affiliateEarnings(Request $request)
    {
        $affiliate_earnings = Payment::with('course')->where('referred_by', auth()->user()->id)
                    ->latest()->paginate(20);
                    
        return view('_User.revenue-affiliate', compact('affiliate_earnings'));
    }
    
    // request withdrawal
    public function requestWithdrawal(Request $request)
    {
        
        $user_balance = $request->user()->account_balance();
        
        $this->validate($request, [
            'amount' => 'bail|required|numeric|min:'.config('site_settings.earning_minimum_payout_amount').'|max:'.$user_balance,
            'paypal_email' => 'required|email'
        ]);
        
        $transaction =  $request->user()->transactions()->create([
                            'uuid' => 2431000 + time() + random_int(99, 2000),
                            'type' => 'debit',
                            'description' => 'Withdrawal request',
                            'long_description' => 'Withdrawal to ' . $request->paypal_email . ' via PayPal',
                            'amount' => - $request->amount
                        ]);
        
        $withdrawal = $request->user()->withdrawals()->create([
                        'uuid' => uniqid(),
                        'transaction_id' => $transaction->id,
                        'amount' => $request->amount,
                        'paypal_email' => $request->paypal_email,
                        'status' => 'pending',
                        'comment' => 'We have received your request. It will be processed on ' . Carbon::now()->addWeeks(config('settings.payout_weeks'))->toFormattedDateString()
                    ]);
        
        $request->user()->notify(new WithdrawalRequestReceived($withdrawal));
        
        return redirect()->back();
        
    }
    
    
    public function deleteWithdrawal(Withdrawal $withdrawal)
    {
        
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        
        if($withdrawal->status != 'pending'){
            return redirect()->back();
        }
        
        auth()->user()->transactions()->create([
            'uuid' => 2431000 + time() + random_int(99, 2000),
            'type' => 'credit',
            'description' => 'Withdrawal Cancellation',
            'long_description' => 'Withdrawal request via PayPal made on ' . $withdrawal->created_at->toFormattedDateString() . ' cancelled',
            'amount' => $withdrawal->amount
        ]);
        
        $withdrawal->delete();
        
        return redirect()->back();
    }
    
    
    
    
    
}
