<?php

namespace App\Http\Controllers\Backend;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\Backend\WithdrawalRequestUpdated;

class AdminWithdrawalController extends Controller
{
    
    public function index(Request $request)
    {
        $filter = $request->filter;
        
        if(!is_null($request->filter)){
            $withdrawals = Withdrawal::where('status', $request->filter)->with('user')->get();
        }else {
            $withdrawals = Withdrawal::with('user')->get();
        }
        
        return view('backend.finance.withdrawals', compact('withdrawals', 'filter'));
    }
    
    public function show(Request $request, Withdrawal $withdrawal)
    {
        return view('backend.finance.withdrawals-show', compact('withdrawal'));    
    }
    
    public function approval(Request $request)
    {
        $withdrawal = Withdrawal::find($request->withdrawal_id);
        $withdrawal->status = $request->status;
        $withdrawal->comment = $request->comment;
        $withdrawal->save();
        
        $request->user()->notify(new WithdrawalRequestUpdated($withdrawal));
        
        return redirect()->back();
    }
    
}
