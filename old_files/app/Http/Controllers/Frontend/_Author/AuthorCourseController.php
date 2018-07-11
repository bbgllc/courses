<?php

namespace App\Http\Controllers\Frontend\_Author;

use Storage;
use App\Models\Course;
use App\Models\Section;
use App\Models\Category;
use App\Models\Approval;
use Illuminate\Http\Request;
use App\Models\Auth\User;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use App\Notifications\Backend\AdminCourseSubmittedForReview;

class AuthorCourseController extends Controller
{
    protected $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function fetchCourse($id)
    {
        $course = Course::find($id);
        
        return response()->json($course, 200);
    }
    
    /* return a form to create a new course */
    public function create()
    {
        return view('_Author.course-create');
    }
    
    /**
     * Store created course using api 
     * this creates a course with a section and lesson placeholders.
     * Takes a request object
     * 
     * @param Request $request
     *
     * @return mixed
     */

    public function store(Request $request)
    {

    	$this->validate($request, [
            'title' => 'required|max:50',
            'subtitle' => 'required|max:120',
            'category' => 'required',
            'slug' => 'required|unique:courses,slug'
        ]);
        
        // insert the course in the database if validation passes
        $course = Course::create([	
        	'title' => $request->title,
        	'subtitle' => $request->subtitle,
        	'category_id' => $request->category,
        	'slug' => $request->slug,
        	'price' => 0,
        	'user_id' => auth()->user()->id

        ]);
        
        // create the first initial section for the course
        $section = $course->sections()->create([
            'title' => 'Start here',
            'objective' => 'Short course objective',
            'sortOrder' => 1
        ]);
        
        // create a first initial lesson for the section
        $section->lessons()->create([
            'title' => 'Introduction',
            'uid' => random_int(100, 20000) + random_int(99, 2000),
            'sortOrder' => 1
        ]);

        
        return response()->json($course, 200);

    }
    
    /**
     * Render the course edit view
     * 
     * @param Request $request
     * @param Course $course -- uses routeKeyName binding
     *
     * @return mixed
     */
     
    public function edit(Request $request, Course $course)
    {
        
        if($course->user_id != $request->user()->id){
            return redirect(route('frontend.author.dashboard'))->withFlashDanger('You are not allowed to access this resource');
        };
        
        $course->parent_category = $course->category->parentCategory->id;
        
        return view('_Author.course-edit', compact('course'));
    }


    /**
     * Process course update request from api route
     * 
     * @param Request $request
     * @param Course $course -- uses routeKeyName binding
     *
     * @return mixed
     */
   	public function update(Request $request, Course $course)
    {
        
        $this->validate($request, [
            'title' => 'required|max:50',
            'subtitle' => 'required|max:120',
            'description' => 'required|min:50',
            'category' => 'required',
            'language' => 'required',
            'level' => 'required',
            'slug' => 'required|no_spaces_allowed|unique:courses,slug,'.$course->id
        ]);
        
        $course->title = $request->title;
        $course->subtitle = $request->subtitle;
        $course->slug = $request->slug;
        $course->description = $request->description;
        $course->level = $request->level;
        $course->language = $request->language;
        $course->category_id = $request->category;
        $course->save();
        
        return response()->json($course, 200);
    }

    /*
    	view to show curriculum, where users can add and update course content
	*/
    public function curriculum(Request $request, Course $course)
    {
        
        if($course->user_id != $request->user()->id){
            return redirect(route('frontend.author.dashboard'))->withFlashDanger(trans('auth.general_error'));
        };
        
        return view('_Author.course-curriculum', compact('course'));
    }

    // Save new course cover image
    public function updateImage(Request $request, $id)
    {
        $course = Course::find($id);
        $oldImage = $course->image; // delete the old image from the file system after new one is uploaded
        $filename = uniqid(true);
        
        $processedImage = $this->imageManager->make($request->file('files')->getPathName())
            ->fit(1920, 1080, function ($c) {
                $c->aspectRatio();
            })->encode('png')
            ->save(public_path('uploads/images/course/' . $filename . '.png'));
        
        $thumbnailImage = $this->imageManager->make($request->file('files')->getPathName())
            ->fit(250, 140, function ($c) {
                $c->aspectRatio();
            })->encode('png')
            ->save(public_path('uploads/images/course/thumbnails/' . $filename . '.png'));
        
        
        $course->image = $filename.'.png';
        $course->save();
        
        if(!is_null($oldImage)){
            if(Storage::disk('server')->exists('images/course/'.$oldImage)){
                Storage::disk('server')->delete('images/course/'.$oldImage);
            }
            if(Storage::disk('server')->exists('images/course/thumbnails/'.$oldImage)){
                Storage::disk('server')->delete('images/course/thumbnails/'.$oldImage);
            }
        }
        $path = '/uploads/images/course/'.$course->image; 
        
        return response([
            'data' => [
                'path' => $path,
            ]
        ], 200);
    }


    /* 
		Update course price
    */

	public function updatePrice(Request $request, $id)
    {
        $course = Course::find($id);

        $course->price = $request->price;
        $course->save();
        
        return response()->json($course, 200);
    }


    /*
    	Submit a course for author's review
    */

    public function submitForReview(Request $request, Course $course)
    {
       
        if($course->published){
            $course->published = false;
        } else {
            $course->published = true;
        }

        $course->save();
        
        if(!$course->approved){
            $admins = User::whereHas('roles', function($q){
               $q->where('name', 'Administrator'); 
            })->get();
            
            foreach($admins as $admin){
                $admin->notify(new AdminCourseSubmittedForReview($course));
            }
        }
        
        return redirect()->back();
    }

    /* 
    	View to show Administrator's review notes
    */

    public function adminReview(Request $request, Course $course)
    {
        if($course->user_id != $request->user()->id){
            return redirect(route('frontend.author.dashboard'))->withFlashDanger(trans('auth.general_error'));
        };
        
        $approvals = $course->approvals()->latest()->get();
        return view('_Author.course-admin-approval', compact('course', 'approvals'));
    }

    /*
		Delete a course. Conditions:
		- User must own the course they want to delete
		- Course must NOT have any students enrolled - so that authors cannot delete courses that have enrollments
    */
    public function destroy(Request $request, Course $course)
    {
        if($course->user_id != $request->user()->id){
            return redirect(route('frontend.author.dashboard'))->withFlashDanger(trans('auth.general_error'));
        };
        
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        
        if($course->user_id == auth()->user()->id && $course->canBeDeleted()){
            $course->delete();
        }
        
        return redirect('/author/dashboard');
    }


}
