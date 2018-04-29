<?php

namespace App\Models;

use Bnb\Laravel\Attachments\HasAttachment;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasAttachment;
     
    protected $fillable = ['section_id', 'title', 'uid', 'description', 'lesson_type', 'preview', 'sortOrder'];
    
    
    protected $appends = ['content_type', 'video_provider', 'video_link', 'article_body', 'minutes_seconds'];
    
    public function getVideoProviderAttribute()
    {
        
        if($this->content && $this->content->content_type == 'video'){
            if($this->content->video_provider){
                return $this->content->video_provider;
            } else {
                return 'mp4';
            }
        } else {
            return null;
        }
        
    }

    public function getVideoLinkAttribute()
    {
        if($this->content && $this->content->content_type == 'video'){
            return $this->content->video_path;
        } else {
            return null;
        }
    }
    
    public function getMinutesSecondsAttribute()
    {
        if($this->content && $this->content->content_type == 'video' && $this->content->video_duration > 0){
            return gmdate("H:i:s", ($this->content->video_duration*60));
        } else {
            return null;
        }
    }
    
    public function getArticleBodyAttribute()
    {
        if($this->content && $this->content->content_type == 'article'){
            return $this->content->article_body;
        } else {
            return null;
        }
    }
    
    public function getContentTypeAttribute()
    {
        if($this->content && $this->content->content_type == 'video'){
            return 'video';
        } elseif($this->content && $this->content->content_type == 'article'){
            return 'article';
        } elseif($this->lesson_type == 'quiz'){
            return 'quiz';
        } else {
            return null;
        }
    }
    
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    
    public function content()
    {
        return $this->hasOne(Content::class);
    }
    
    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class);
    }
    
    
    public function completions()
    {
        return $this->belongsToMany(User::class, 'completions', 'lesson_id', 'user_id')->withTimestamps();
    }
    
    
    
    /*
		use RoutKeyName binding to display slug in url instead of ids
    */
    public function getRouteKeyName()
    {
        return 'uid';
    }

    

}
