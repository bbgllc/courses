<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable=['approvable_id', 'approvable_type', 'decision', 'comment'];
    

    public function approvable()
    {
        return $this->morphTo();
    }

    /*
    public function course()
    {
        return $this->belongsTo(Course::class);
    }*/
}
