<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable=['title', 'objective', 'course_id', 'sortOrder'];
    
    protected $appends = ['minutes_seconds'];
    
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function getMinutesSecondsAttribute()
    {
        $minutes = Section::join('lessons', 'sections.id', '=', 'lessons.section_id')
                        ->join('contents', 'lessons.id', '=', 'contents.lesson_id')
                        ->where('sections.id', $this->id)
                        ->where('contents.content_type', 'video')
                        ->sum('contents.video_duration');
        if($minutes > 0){
            return gmdate("H:i:s", ($minutes*60));
        } else {
            return 0;
        }

        
    }
    
}
