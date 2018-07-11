<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    
    protected $fillable = ['followable_id', 'followable_type', 'user_id'];
    
    public function followable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
