<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'uuid', 'user_id', 'type', 'description', 'long_description', 'amount'    
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
