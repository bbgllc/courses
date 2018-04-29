<?php

namespace App\Models;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    
    protected $fillable = ['commentable_id', 'commentable_type', 'description', 'user_id', 'parent_id', 'marked_as_answer'];
    
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

}
