<?php

namespace App\Http\Controllers\Frontend\_Author;

use App\Models\Lesson;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorQuizController extends Controller
{
    
    
    public function saveQuestion(Request $request)
    {
        $this->validate($request, [
            'question' => 'required',
        ]);
        
        $lesson = Lesson::find($request->lesson_id);
        
        $q = $lesson->quizQuestions()->create([
            'question' => $request->question    
        ]);
        
        return response()->json(null, 200);

    }
    
    public function updateQuestion(Request $request, $question_id)
    {
        $this->validate($request, [
            'question' => 'required',
        ]);
        
        $q = QuizQuestion::find($question_id);
        
        $q->question = $request->question;
        $q->save();
        
        return response()->json(null, 200);
    }
    
    public function destroyQuestion($id)
    {
        QuizQuestion::find($id)->delete();
        
        return response()->json(null, 200);
    }
    
    public function storeAnswer(Request $request, $id)
    {
        
        $request->correct = ($request->correct == '' || is_null($request->correct)) ? false : $request->correct;
        
        $this->validate($request, [
            'text' => 'required',
        ]);
        
        $question = QuizQuestion::find($id);
        
        $answer = $question->answers()->create([
            'answer' => $request->text,
            'explanation' => $request->explanation,
            'correct' => $request->correct
        ]);
        
        return response()->json(null, 200);

    }
    
    public function destroyAnswer($id)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        
        QuizAnswer::find($id)->delete();
        
        return response()->json(null, 200);
    }
    
    
}
