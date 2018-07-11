<?php

namespace App\Models;


use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    
	protected $fillable=['title', 'user_id', 'course_id', 'slug', 'body' ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function answers()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
    public function follows()
    {
        return $this->morphMany(Follow::class, 'followable');
    }
    
    public function hasBeenAnswered()
    {
        return (bool)$this->answers()->where('marked_as_answer', true)->count();
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }


    

}
