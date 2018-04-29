<?php

namespace App\Http\Controllers\Frontend\_Author;

use App\Models\Course;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorDashboardController extends Controller
{
    
    
    public function dashboard()
    {
        
        if(!auth()->user()->isAuthor()){
            return redirect('/author/create-course');
        }
        
        $unanswered_questions =  Question::whereHas('course', function($q){
                                    $q->where('user_id', auth()->id());
                                })
                                ->withCount('answers')
                                ->has('answers', '<', 1)
                                ->get()->count();

        return view('_Author.dashboard', compact('unanswered_questions'));
    }
    
    public function fetchAuthorCourses(Request $request)
    {
        
        $q = $request->q;
        $order_by = $request->order_by;
        
        $c = auth()->user()->courses()->with('author')->orderBy('approved', 'asc');

        $courses = Course::where('user_id', auth()->user()->id)
                    ->with('author')
                    ->orderBy('approved', 'asc');
        
        if(!is_null($request->q)){
            $c = $courses->where('title', 'like', "%$q%")
                ->orWhere('subtitle', 'like', "%$q%");
        }
        
        if(!is_null($request->order_by)){
            if($order_by == 'title_asc'){
                $c = $courses->orderBy('title', 'asc');
            } elseif($order_by == 'title_desc'){
                $c = $c->orderBy('title', 'desc');
            } elseif($order_by == 'newest_first'){
                $c = $c->orderBy('created_at', 'desc');
            } elseif($order_by == 'oldest_first'){
                $c = $c->orderBy('created_at', 'asc');
            }
        }
        
        
        $courses = $courses->get();
        
        foreach($courses as $course){
            $course->status = $course->status();
            $course->total_students = $course->students->count();
            $course->total_reviews = $course->reviews->count();
        }
        
        $data = [
            'courses' => $courses,
            'medatadata' => 'Some'
        ];
        
        return response()->json($data, 200);
        
    }
    
    
}
