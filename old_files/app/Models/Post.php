<?php

namespace App\Models;

use App\Models\Auth\User;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Translatable;
    
    protected $translatedAttributes = [
        'title', 'intro', 'body', 'slug', 'meta_description', 
    ];
    
    protected $fillable = [
        'title', 'intro', 'body', 'published', 'published_at', 'category_id', 'featured_image',
        'meta_description', 'user_id', 'featured'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'published_at'
    ];
    
    protected $appends = ['image'];
    
    
    public function getImageAttribute()
    {
        /*if($this->featured_image) {
            return '/uploads/images/posts/'. $this->featured_image; 
        } else {
            return '/uploads/images/defaults/cover.jpg';
        }*/
        if($this->featured_image) {
            return '/uploads/images/posts/'. $this->featured_image; 
        } else {
            return null;
        }
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
}
