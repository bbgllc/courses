<?php

namespace App\Http\Controllers\Frontend\_Author;

use App\Models\Course;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Notifications\Frontend\NewAnnouncement;
use App\Http\Controllers\Controller;

class AuthorAnnouncementController extends Controller
{
    
    public function index(Course $course, Request $request)
    {
        if($course->user_id != $request->user()->id){
            return redirect(route('frontend.author.dashboard'))->withFlashDanger(trans('auth.general_error'));
        };
        
        $announcements = $course->announcements()->with('comments')->get();
        
        return view('_Author.announcements', compact('course', 'announcements'));
    }
    
    public function create(Request $request, Course $course)
    {
        if($course->user_id != $request->user()->id){
            return redirect(route('frontend.author.dashboard'))->withFlashDanger(trans('auth.general_error'));
        };
        
        $courses = Course::where('user_id', $request->user()->id)
                    ->where('published', true)
                    ->where('approved', true)
                    ->orderBy('title')
                    ->get();
        
        return view('_Author.announcements-create', compact('course', 'courses'));            
    }
    
    public function store(Request $request, Course $course)
    {
        
        if($course->user_id != $request->user()->id){
            return redirect(route('frontend.author.dashboard'))->withFlashDanger(trans('auth.general_error'));
        };
        
        $this->validate($request, [
            'title' => 'required|max:50',
            'body' => 'required',
        ]);
        
        $announcement = Announcement::create([
            'title' => $request->title,
            'body' => $request->body,
            'slug' => mt_rand(10,92500650) + mt_rand(10,192)
        ]);
        
        $announcement->courses()->attach($request->courses);
       
        
        $courses = Course::whereIn('id', $request->courses)->with('students')->get();
        
        foreach($courses as $course){
            foreach($course->students as $student){
                if(setting('notify_when_new_announcement', '', $student->id) == 'true'){
                    $student->notify(new NewAnnouncement($announcement, $course));
                }
            }
        }
        
        return response()->json(null, 200);
        
    }
}
