<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    
    protected $table = 'certificates'; 
    
    protected $fillable=[
        'user_id',
        'course_id',
        'certificate_no',
        'course_title',
        'course_subtitle',
        'video_hours',
        'total_articles',
        'total_quizzes'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    
}
