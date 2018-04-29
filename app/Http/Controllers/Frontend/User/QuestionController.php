<?php

namespace App\Http\Controllers\Frontend\User;

use DB;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Course;
use App\Models\Comment;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Auth\User;
use App\Notifications\Frontend\AnswerNotificationToFollowers;
use App\Notifications\Frontend\AnswerNotificationToQuestionAuthor;
use App\Notifications\Frontend\YourAnswerMarkedAsCorrect;
use App\Notifications\Frontend\QuestionYouFollowHasBeenAnswered;
use App\Notifications\Frontend\YourQuestionHasBeenAnswered;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    
	// render view to show questions - under course 
    public function index(Request $request, Course $course)
    {
        $course = Course::with(['sections' => function($q){
			$q->orderBy('sortOrder', 'ASC');
			$q->with(['lessons' => function($l){
				$l->orderBy('sortOrder', 'ASC');
				$l->with('content');
			}]);
		}, 'author'])->find($course->id);
		
        return view('courses.questions', compact('course'));
    }

    // api call to fetch all questions for given course. Used in Vue
    // includes request object for any api search
    public function fetchQuestions(Request $request, $course)
    {
        
        $q = Question::where('course_id', $course)->with('answers', 'user');
        
        if($request->keyword){
            $q = $q->where('title', 'like', "%$request->keyword%")->orWhere('body', 'like', "%$request->keyword%");
        }
        
        $questions = $q->latest()->paginate(10);
        
        foreach($questions as $question){
            $question->user->image = $question->user->picture;
            $question->user->can_edit = $question->user->id == $request->user()->id ? true : false;
            $question->created_at_human = $question->created_at->diffForHumans();
            $question->has_been_answered = $question->hasBeenAnswered() ? true : false;
        }
        

        return response()->json($questions, 200);
    }


	// view to show individual question
    public function show(Course $course, Question $question)
    {

        $course = Course::with(['sections' => function($q){
			$q->orderBy('sortOrder', 'ASC');
			$q->with(['lessons' => function($l){
				$l->orderBy('sortOrder', 'ASC');
				$l->with('content');
			}]);
		}, 'author'])->find($course->id);
		
        return view('courses.questions-show', compact('question', 'course'));
    }

    // api fetch individual question when show page is loaded
    public function fetchQuestion(Request $request, $id)
    {
        $question = Question::find($id);
        $question->author_image = $question->user->picture;
        $question->author_name = $question->user->full_name;
        $question->created_at_human = $question->created_at->diffForHumans();

        return response()->json($question, 200);
    }


    // store new question when created in frontend
    public function storeQuestion(Request $request, $course)
    {
        $course = Course::find($course);
        
        $question = $course->questions()->create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $request->user()->id,
            'course_id' => $course->id,
            'slug' => mt_rand(10,925000) + mt_rand(10,192)
        ]);
        
        
        return response()->json($question, 200);
    }

    // update question when edited in frontend
    public function updateQuestion(Request $request, $id)
    {
        $question = Question::find($id);
        $question->title = $request->title;
        $question->body = $request->body;
        $question->save();
        
        return response()->json($question, 200);
    }
    
    // delete question including all its comments.

    public function deleteQuestion($id)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
       	
        $question = Question::find($id);

        foreach($question->answers as $comment){
        	$comment->delete();
        }

        $question->delete();

        return response()->json(null, 200);
    }


    /**************** Question comments -- Answers ******************/

    // api method to fetch all answers for a given question
    public function fetchAnswers(Request $request, $id)
    {
        
    	$question = Question::find($id);

    	$answers = $question->answers()->with('user')
        			->orderBy('marked_as_answer', 'desc')
        			->orderBy('created_at', 'asc')
        			->paginate(10);
        
        foreach($answers as $answer){
            $answer->user->image = $answer->user->picture;
            $answer->user->can_edit = $answer->user->id == $request->user()->id ? true : false;
            $answer->created_at_human = $answer->created_at->diffForHumans();
        }
        
        return response()->json($answers, 200);

    }

    // api to fetch single answer given the $id
    public function fetchAnswer(Request $request, $id)
    {
        $answer = Comment::find($id);
        return response()->json($answer, 200);
    }


    // store answer
    public function storeAnswer(Request $request, $id)
    {
        $question = Question::find($id);

        $answer = $question->answers()->create([
            'description' => $request->body,
            'user_id' => $request->user()->id
        ]);

        // get all users following the question and notify them if they choose to be notified in their settings
        $userArr = $question->follows->pluck('user_id');
        $followers = User::whereIn('id', $userArr)->get();
        foreach($followers as $user){
            $user_can_be_notified = setting('notify_when_question_i_am_following_responded', '', $user->id) == 'true';
            if($user->id !== auth()->user()->id && $user_can_be_notified){
                $user->notify(new AnswerNotificationToFollowers($question));
            }
        }
        
        // notify the author that a new answer was posted to their question
        $author = $question->user;
        if($author->id !== auth()->user()->id && setting('notify_when_question_responded', '', $author->id) == 'true'){
            $author->notify(new AnswerNotificationToQuestionAuthor($question));
        }

        return response()->json($question, 200);
    }

    // update an answer when edited in frontend
    public function updateAnswer(Request $request, $id)
    {
        $answer = Comment::find($id);
        $answer->description = $request->body;
        $answer->save();
        
        return response()->json($answer, 200);
    }


    // delete answer
    public function deleteAnswer(Request $request, $id)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        
        $answer = Comment::find($id)->delete();

        return response()->json(null, 200);
    }


    // Mark an answer as correct answer
    public function markAsAnswer(Request $request, $answer)
    {
        $answer = Comment::find($answer);
        $question = Question::find($answer->commentable_id);
        
        $bestAnswers = Comment::where('marked_as_answer', true)->where('commentable_id', $answer->commentable_id)->get();
        
        // only the person who asked the question can mark an answer
        if(auth()->check() && (auth()->user()->id == $question->user_id || auth()->user()->id == $question->course->author->id)){
            // set all other answers to unmarked
            foreach($bestAnswers as $b){
                $b->marked_as_answer = false;
                $b->save();
            }
            
            $answer->marked_as_answer = true;
            $answer->save();
            
            // notify the answer author that their answer has been chosen as best answer
            if($answer->user->id !== auth()->user()->id && setting('notify_when_answer_marked_as_correct', '', $answer->user->id) == 'true'){
                $answer->user->notify(new YourAnswerMarkedAsCorrect($question));
            }
            
            // get all users following the question and notify them that the question has been answered
            $userArr = $question->follows->pluck('user_id');
            $followers = User::whereIn('id', $userArr)->get();
            foreach($followers as $user){
                $user_can_be_notified = setting('notify_when_followed_question_is_answered', '', $user->id) == 'true';
                if($user->id !== auth()->user()->id && $user_can_be_notified){
                    $user->notify(new QuestionYouFollowHasBeenAnswered($question));
                }
            }
            
            // notify the question author that their quetion has been answered
            $author = $question->user;
            if($author->id !== auth()->user()->id && setting('notify_when_my_question_is_marked_as_answered', '', $author->id) == 'true'){
                $author->notify(new YourQuestionHasBeenAnswered($question));
            }
            
            return response()->json(null, 200);
        }
        
        
        
    }
    
    
    // Follow Question
    public function getFollowStatus($id)
    {
        $question = Question::find($id);
        if(auth()->user()->isFollowingQuestion($question)){
            $status = true;
        } else {
            $status = false;
        }
        
        return response()->json($status, 200);
    }
    
    public function follow(Request $request, $id)
    {
        $question = Question::find($id);
        
        if( ! auth()->user()->isFollowingQuestion($question)){
            $question->follows()->create([
                'user_id' => auth()->user()->id  
            ]);
        } else {
            $question->follows()->where('user_id', auth()->user()->id)->first()->delete();
        }
        
        return response()->json(null, 200);
        
    }


}
