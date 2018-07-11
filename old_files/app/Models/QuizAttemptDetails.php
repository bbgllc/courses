<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class QuizAttemptDetails extends Model
{
    protected $fillable=['attempt_id', 'question','chosen_answer', 'correct_answer'];
    
    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id');
    }
    
}
