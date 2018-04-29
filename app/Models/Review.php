<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    
    protected $fillable=['user_id', 'rating', 'course_id', 'title', 'comment'];
    
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    
}
