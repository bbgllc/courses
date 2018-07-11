<?php

namespace App\Http\Controllers\Frontend\User;

use App\Models\QuizQuestion as Quiz;
use App\Models\QuizAnswer;
use App\Models\Lesson;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LessonController extends Controller
{
    
    public function getCompletionStatus(Lesson $lesson)
    {
        $completion_status = auth()->user()->hasCompletedLesson($lesson) ? true : false;
        
        return response()->json($completion_status, 200);
        
    }
    
    public function markAsComplete(Request $request, Lesson $lesson)
    {
        $course = $lesson->section->course;
        
        if(! $request->user()->hasCompletedLesson($lesson) && auth()->user()->id !== $course->user_id){
            auth()->user()->completions()->attach($lesson->id);
            
            $pcnt_course_complete = auth()->user()->percentCompleted($course);
        
            if($pcnt_course_complete == 100 && !auth()->user()->hasCompletedCourse($course)){
                auth()->user()->certificates()->create([
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'certificate_no' => 'C00'.strToUpper(str_random(2)).$course->category->id.'-'. auth()->user()->id . rand(100, 999).rand(3,99),
                    'course_subtitle' => $course->subtitle,
                    'video_hours' => $course->total_hours,
                    'total_articles' => $course->total_articles,
                    'total_quizzes' => $course->total_quizzes
                ]);
            }
        } else {
            auth()->user()->completions()->detach($lesson->id);
        }
        
        $percent_completed = auth()->user()->percentSectionCompleted($lesson->section);
        
        return response()->json($percent_completed, 200);
        
    }
    
    // Quiz
    public function fetchQuestions($lesson)
    {
        $q = Quiz::with('answers')->where('lesson_id', $lesson)->get();
        
        $quiz_attempts = QuizAttempt::latest()->where('user_id', auth()->user()->id)
                            ->where('lesson_id', $lesson)->get();
        $data = [
            'questions' => $q,
            'quiz_attempts' => $quiz_attempts
        ];
        
        return response()->json($data, 200);
    }
    
    public function saveAttempt(Request $request, $lesson)
    {
        $count = 0;
        foreach($request->questions as $q){
           if($q['question']['selectedAnswer']['correct'] == 1){
               $count++;
           } 
        }
        
        $attempt = auth()->user()->quizAttempts()->create([
            'lesson_id' => $lesson,
            'total_attempted' => count($request->questions),
            'total_correct' => $count
        ]);
        
        foreach($request->questions as $q){
            $correctAnswer = QuizAnswer::where('question_id', $q['question']['id'])->where('correct', 1)->first();
            
            $attempt->attemptDetails()->create([
                'question' => $q['question']['question'],    
                'chosen_answer' => $q['question']['selectedAnswer']['answer'],
                'correct_answer' => $correctAnswer ? $correctAnswer->answer : 'NA'
            ]);
        }
        
        return response()->json(null, 200);
        
    }
    
}
