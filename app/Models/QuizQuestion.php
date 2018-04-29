<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';
    
    
    protected $fillable=['lesson_id', 'question', 'reference_lesson'];
    
    protected $appends = ['correct_answer_provided'];
    
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
    
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'question_id');
    }
    
    public function getCorrectAnswerProvidedAttribute()
    {
        if($this->answers()->where('correct', true)->count()){
            return true;
        } else {
            return false;
        }
    }
}
