<?php

namespace App\Models;


use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Withdrawal extends Model
{
    use SoftDeletes;
    
    protected $fillable=['user_id', 'uuid', 'transaction_id', 'amount', 'paypal_email', 'status', 'comment'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function getRouteKeyName()
    {
        return 'uuid';    
    }
    
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    
}
