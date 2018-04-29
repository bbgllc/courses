<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Auth\User;
use App\Models\Coupon;
use App\Models\QuizAttempt;
use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Filters\Course\CourseFilters;
use App\Http\Controllers\Controller;


class CourseController extends Controller
{
    
    public function index(Request $request)
    {
        
        $filters = [];
        if($request->access){
            $filters['access'] = $request->access;
        }
        if($request->language){
            $filters['language'] = $request->language;
        }
        if($request->difficulty){
            $filters['difficulty'] = $request->difficulty;
        }
        if($request->category){
            $filters['category'] = $request->category;
        }
        if($request->keyword){
            $filters['keyword'] = $request->keyword;
        }
 
        $courses = Course::where(['approved' => true, 'published' => true])
            ->with(['category'])
            ->filter($request)->paginate(6);
            
     
        return view('courses.courses', compact('courses', 'filters'));
        
    }
    
    
    public function show(Request $request, Course $course)
    {
        //$referee = User::where('affiliate_id', Cookie::get('tutorpro_affid'))->first();
        
        //session()->push('courses.recently_viewed', $course->id);
        
        if(!$course->published){
            return redirect('/')->withFlashWarning('This course is not published');
        }
        $coupon = Coupon::where(['sitewide' => true, 'active' => true])
                    ->where('expires', '>=', \Carbon\Carbon::now())
                    ->first();
        
        
        
        if(!is_null($coupon)){
            $coupon_code = $coupon->code;    
        } else {
            $coupon_code = $request->COUPON;
        }
        
        $course = Course::with(['sections' => function($q){
			$q->orderBy('sortOrder', 'ASC');
			$q->with(['lessons' => function($l){
				$l->orderBy('sortOrder', 'ASC');
				$l->with('content');
			}]);
		}, 'author'])->find($course->id);
        
        /* get course ratings summary to display in chart */
        $ratings = $course->reviews()->get();
        $ratings = $ratings->each(function ($item, $key) {
            $item->rating = round($item->rating, 0);
        });
        $ratings = collect([
            ['rating' => '5', 'total' => 0, 'width' => 0],
            ['rating' => '4', 'total' => 0, 'width' => 0],
            ['rating' => '3', 'total' => 0, 'width' => 0],
            ['rating' => '2', 'total' => 0, 'width' => 0],
            ['rating' => '1', 'total' => 0, 'width' => 0]
        ]);
        
        $data = \DB::table('reviews')
                ->where('course_id', $course->id)
                ->selectRaw('round(rating) as rating, count(rating) as total')
                ->groupBy(\DB::raw('round(rating)'))
                ->get();

        $course_ratings = $ratings->map(function ($item, $key) use ($data, $course) {
            $v = $item['rating'];
            $r = $data->where('rating', $v);
            if($r->isNotEmpty()){
                $x = $r->first()->total;
                $item['total'] = $r->first()->total;
                $item['width'] = ($r->first()->total / $course->reviews->count()) * 100;
            }
            return $item;
        });
        
        $recent_questions = $course->questions()->latest()->get()->take(4);
        $recent_announcements = $course->announcements()->latest()->get()->take(4);
        
        $previews = Lesson::whereHas('section', function($q) use ($course){
                $q->where('course_id', $course->id);    
            })
            ->where('preview', true)
        	->whereHas('content', function($q){
        		$q->where('content_type', 'video');	
        	})->get();
        
        foreach($previews as $l){
            $l->content_type = 'video';
            $l->provider = $l->content->video_provider ? $l->content->video_provider :'mp4';
            $l->video_path = $l->content->video_path;
        }
        //dd($previews);
        
        /* if the user has already enrolled to the course, redirect them to the Dashboard, otherwise send them to the public course page */
        if(auth()->check() && auth()->user()->canAccessCourse($course)){
            return view('courses.course-dashboard', compact('course', 'course_ratings', 'coupon_code', 'recent_questions', 'recent_announcements')); 
        } else {
            return view('courses.course-show', compact('course', 'course_ratings', 'coupon_code', 'previews'));    
        }
        
		
    }
    
    
    public function content(Request $request, Course $course)
    {
        
        $course = Course::with(['sections' => function($q){
			$q->orderBy('sortOrder', 'ASC');
			$q->with(['lessons' => function($l){
				$l->orderBy('sortOrder', 'ASC');
				$l->with('content');
			}]);
		}, 'author'])->find($course->id);
		        
		foreach ($course->sections as $section){
		    $section->percent_completed = auth()->user()->percentSectionCompleted($section);
		    foreach($section->lessons as $lesson){
		        $lesson->user_completed = auth()->user()->hasCompletedLesson($lesson) ? true : false;
		    }
		}
		
		return view('courses.course-content', compact('course')); 
    }
    
    public function enroll(Request $request, Course $course)
    {
        $course->students()->attach($request->user()->id);
        
        return redirect()->route('frontend.course.show', $course);
    }
    
    public function bookmark(Request $request, Course $course)
    {
        if ($request->user()->hasBookmarkedCourse($course)) {
            auth()->user()->bookmarks()->detach($course->id);
        } else {
            auth()->user()->bookmarks()->attach($course->id);
        }
        
        return response()->json(null, 200);
    }
    
    public function getBookmarkStatus(Course $course)
    {
        if(auth()->user()->hasBookmarkedCourse($course)){
            $user_bookmarked = true; 
        } else {
            $user_bookmarked = false;
        }
        
        return response()->json($user_bookmarked, 200);
    }
    
    
    public function play(Request $request, Course $course, Lesson $lesson)
    {

         // prevent user from accessing course if they have not purchased
        if(!auth()->user()->canAccessCourse($course)){
            return redirect(route('frontend.course.show', $course))->withFlashDanger('You are not permitted to access this course');
        }
        
        if(!$course->published && auth()->id() !== $course->author->id){
            return redirect('/')->withFlashWarning('This course is unpublished');
        }
        $lesson = Lesson::with('attachments', 'content')->find($lesson->id);
        
        
        // mark this lesson as completed
        if(! $request->user()->hasCompletedLesson($lesson) && auth()->user()->id !== $course->user_id){
            auth()->user()->completions()->attach($lesson->id);
        }
        
        $sections = Section::with(['lessons' => function($q){
                $q->orderBy('sortOrder', 'ASC');
                $q->with('content');
                $q->with('attachments');
            }])
            ->where('course_id', $course->id)
            ->orderBy('sortOrder', 'ASC')
            ->get();

        $sec_array = $sections->pluck('id');
        
        $lessons = Lesson::whereIn('section_id', $sec_array)
                    ->join('sections', 'lessons.section_id', '=', 'sections.id')  
                    ->orderBy('sections.sortOrder', 'ASC')
                    ->orderBy('lessons.sortOrder', 'ASC')
                    ->select('lessons.*')
                    ->get();
                    
        $lessons = $lessons->each(function ($item, $key){
            $item->position = $key;
        });
        
        $current = $lessons->where('uid', $lesson->uid)->first();
        $next_lesson = $lessons->where('position', $current->position+1)->first(); 
        $previous_lesson = $lessons->where('position', $current->position-1)->first();
        
        $quiz_attempts = QuizAttempt::latest()->where('user_id', auth()->id())->where('lesson_id', $lesson->id)->get();
        
        
        // if this is the last lesson by this user, then generate course certificate
        $pcnt_course_complete = auth()->user()->percentCompleted($course);
       
        
        if($pcnt_course_complete == 100 && !auth()->user()->hasCompletedCourse($course)){
            auth()->user()->certificates()->create([
                'course_id' => $course->id,
                'certificate_no' => 'C00'.strToUpper(str_random(2)).$course->category->id.'-'. auth()->user()->id . rand(100, 999).rand(3,99),
                'course_title' => $course->title,
                'course_subtitle' => $course->subtitle,
                'video_hours' => $course->total_hours,
                'total_articles' => $course->total_articles,
                'total_quizzes' => $course->total_quizzes
            ]);
        }


        $attachments = Section::whereHas('lessons', function($q) {
            $q->whereHas('attachments');    
        })
        ->where('course_id', $course->id)
        ->with(['lessons', 'lessons.attachments'])
        ->orderBy('sortOrder')->get();
        
        //dd($attachments);
        
        return view('courses.course-play', compact('lesson', 'quiz_attempts', 'sections', 'attachments', 'course', 'next_lesson', 'previous_lesson'));
    }
    
    
    
    public function downloadAttachment(Request $request)
    {
        
        $attachment = \DB::table('attachments')
                        ->where('key', $request->attachment)->first();
        
        if($attachment){
            $path = public_path().'/uploads/attachments/'.$attachment->filename;
            return response()->download($path);
        }
        
    }
    
}
