<?php

namespace App\Http\Controllers\Frontend\User;

use App\Models\Lesson;
use App\Models\Section;
use App\Models\Course;
use App\Models\Comment;
use App\Models\Question;
use App\Models\Auth\User;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
    
    
    public function index(Request $request, Course $course)
    {
        $course = Course::with(['sections' => function($q){
			$q->orderBy('sortOrder', 'ASC');
			$q->with(['lessons' => function($l){
				$l->orderBy('sortOrder', 'ASC');
				$l->with('content');
			}]);
		}, 'author'])->find($course->id);
		
		$announcements = $course->announcements()->paginate(10);
		
        return view('courses.announcements', compact('course', 'announcements'));
    }
    
    public function show(Course $course, Announcement $announcement)
    {

        $course = Course::with(['sections' => function($q){
			$q->orderBy('sortOrder', 'ASC');
			$q->with(['lessons' => function($l){
				$l->orderBy('sortOrder', 'ASC');
				$l->with('content');
			}]);
		}, 'author'])->find($course->id);
		
		
        return view('courses.announcements-show', compact('question', 'course', 'announcement'));
    }
    
    
    public function fetchAnnouncements(Request $request, $course)
    {
        $course = Course::find($course);
        
        $a = $course->announcements()->with('comments');
        
        if($request->keyword){
            $a = $a->where('title', 'like', "%$request->keyword%")->orWhere('body', 'like', "%$request->keyword%");
        }
        
        $announcements = $a->latest()->paginate(10);
        
        foreach($announcements as $announcement){
            $announcement->user_image = $course->author->picture;
            $announcement->user_name = $course->author->name;
            $announcement->created_at_human = $announcement->created_at->diffForHumans();
        }
        

        return response()->json($announcements, 200);
    }
    
    public function fetchComments(Request $request, $announcement_id)
    {
        
        $announcement = Announcement::find($announcement_id);
        $comments = $announcement->comments()->with('user')->latest()->paginate(4);
        
        foreach($comments as $comment){
            $comment->user->image = $comment->user->picture;
            $comment->user->can_edit = $comment->user->id == $request->user()->id ? true : false;
            $comment->created_at_human = $comment->created_at->diffForHumans();
        }
        
        return response()->json($comments, 200);

    }
    
    public function fetchComment(Request $request, $id)
    {
        $comment = Comment::find($id);
        return response()->json($comment, 200);
    }
    
    public function storeComment(Request $request, $announcement_id)
    {
        $this->validate($request, [
            'body' => 'required',
        ]);
        $announcement = Announcement::find($announcement_id);
        $comment = $announcement->comments()->create([
            'description' => $request->body,
            'user_id' => auth()->user()->id
        ]);
        
        return response()->json($comment, 200);
    }
    
    public function updateComment(Request $request, $id)
    {
        $comment = Comment::find($id);
        $comment->description = $request->body;
        $comment->save();
        return response()->json($comment, 200);
    }
    
    public function deleteComment(Request $request, $id)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        
        $comment = Comment::find($id)->delete();
        
        return response()->json(null, 200);
        
    }
    
}
