<?php

namespace App\Http\Controllers\Backend;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminTransactionController extends Controller
{
    
    public function index()
    {
        $transactions = Transaction::with('user')->get();
        
        return view('backend.finance.transactions', compact('transactions'));
    }
    
    
    public function getTransactions()
    {
        $transactions = Transaction::latest()->get();
        
        foreach($transactions as $t){
            $t->owner = $t->user->name;
            $t->created_at_formatted = $t->created_at->format('m/d/Y');
            $t->formatted_amt = \Gabs::currency($t->amount);
            $t->t_type = $t->type == 'credit' ? '<i class="fa fa-plus-circle text-success"></i>' : '<i class="fa fa-minus-circle text-danger"></i>';
        }
        
        return response()->json($transactions, 200);
    }
}
