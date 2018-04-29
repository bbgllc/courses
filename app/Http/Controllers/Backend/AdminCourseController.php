<?php

namespace App\Http\Controllers\Backend;

use App\Models\Course;
use App\Models\Approval;
use Illuminate\Http\Request;
use App\Notifications\Frontend\CourseReviewed;
use App\Http\Controllers\Controller;

class AdminCourseController extends Controller
{
    

	/* 
		List all courses
	*/
    public function index(Request $request)
    {

    	$courses = Course::with(['category', 'author', 'sections'])->paginate(10);
    	$pending_approval = Course::where(['published' => false, 'approved' => false])->get();
    	$unpublished_courses = Course::where(['published' => false, 'approved' => true])->get();
    	$live_courses = Course::where(['approved' => true, 'published' => true]);

    	return view('backend.course.index', compact('courses', 'pending_approval', 'unpublished_courses', 'live_courses'));

    }
    
    public function getCoursesData(Request $request)
    {
        $courses = Course::all();
        
        $courses->each(function($e){
            $e->created_by = $e->author->name;
            $e->status_tag = $e->status();
            $e->img = '<img src="'.$e->cover_image.'" width="50" />';
            $e->can_be_deleted = $e->canBeDeleted();
        });
        
        return response()->json($courses, 200);
    }
    
    

    /* 
    	Show course details including approval

    */

    public function details(Course $course)
    {
    	$approvals = $course->approvals()->latest()->get();
    	return view('backend.course.details', compact('course', 'approvals'));
    }


    public function approval(Request $request, Course $course)
    {
    	$this->validate($request, [
            'comment' => 'required',
            'decision' => 'required|in:approved,disapproved'
        ]);

        $course->approvals()->create([
            'comment' => $request->comment,
            'decision' => $request->decision
        ]);
        
        if($request->decision == 'approved'){
            $course->published = true;
            $course->approved = true;
        } else {
            $course->published = false;
            $course->approved = false;
        }

        $course->save();
            
        $course->author->notify(new CourseReviewed($course));

        return back();
    }



    /*	
		Completely destroy course from backend,
		including all its contents
    */
    public function destroy($id)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
		Course::find($id)->delete();
		return response()->json(null, 200);

    }





}
