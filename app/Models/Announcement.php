<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable=['title', 'body', 'slug'];
    
    
    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
    
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
}
