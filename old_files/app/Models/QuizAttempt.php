<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable=['user_id', 'lesson_id', 'total_attempted','total_correct'];
    
    protected $appends=['percent_correct'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function quiz()
    {
        return $this->belongsTo(Lesson::class);
    }
    
    public function attemptDetails()
    {
        return $this->hasMany(QuizAttemptDetails::class, 'attempt_id');
    }
    
    public function getPercentCorrectAttribute()
    {
        return $this->total_attempted > 0 ? round( ($this->total_correct/$this->total_attempted) *100 ) : null;
    }
}
