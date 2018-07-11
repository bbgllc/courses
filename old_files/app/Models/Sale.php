<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'course_id', 'percent', 'active'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'expires'
    ];

}
