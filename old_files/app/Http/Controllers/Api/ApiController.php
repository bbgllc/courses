<?php

namespace App\Http\Controllers\Api;

//use Countries;
use App\Models\Course;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    
    public function fetchCategories()
    {
    	$categories = Category::whereNull('parent_id')->whereModel('App\Models\Course')->get();
    	
    	return response()->json($categories, 200);
    }
    
    public function fetchSubcategories($category)
    {
    	$categories = Category::where('parent_id', $category)->get();
    	
    	return response()->json($categories, 200);
    }
    
    
    public function fetchLanguages(Request $request)
    {

        $languages = Language::all()->pluck('name', 'code');

        return response()->json($languages, 200);
        
    }
    
    public function fetchCountries(Request $request)
    {

        $countries = Country::all()->pluck('name', 'cca2');
        return response()->json($countries, 200);
        
    }
    
    
    public function fetchCourses(Request $request)
    {
        
        $search_term = $request->search;
        
        $courses = Course::where('published', true)
                    ->where(function($q) use ($search_term){
                        $q->where('title', 'LIKE', '%'.$search_term.'%')
                          ->orWhere('subtitle', 'LIKE', '%'.$search_term.'%')
                          ->orWhere('description', 'LIKE', '%'.$search_term.'%');
                    })
                    ->get();
        
    	return response()->json($courses);	
    }
    
    
    public function fetchAuthors(Request $request)
    {
        $search_term = $request->search;

        $authors =  User::where( function ($q) use ($search_term) {
	                    $q->where('first_name', 'LIKE', '%'.$search_term.'%')
                            ->orWhere('last_name', 'LIKE', '%'.$search_term.'%')
                            ->orWhere('username', 'LIKE', '%'.$search_term.'%');
	                    })->whereHas('roles', function($query){
                            $query->whereIn('name', ['Author', 'User']);    
                        })
                        ->active()
                        ->orderby('username', 'desc')->get();
                        
    	return response()->json($authors);	
    }
    
    
    
    public function fetchCourseDashboardInformation(Request $request, Course $course)
    {
        $sections = Section::where('course_id', $course->id)
            ->orderBy('sortOrder', 'ASC')
            ->pluck('id');
        $first_lesson =  Lesson::join('sections', 'lessons.section_id', '=', 'sections.id')
                            ->whereIn('sections.id', $sections)
                            ->orderBy('sections.sortOrder')
                            ->orderBy('lessons.sortOrder')
                            ->first(['lessons.*']);
                            
		$lessons = Lesson::whereIn('section_id', $sections)->pluck('id');
		
		$last_watched = auth()->user()->completions()->latest()->whereIn('lessons.id', $lessons)->first();
		
        $rating = $course->reviews()->where('user_id', auth()->user()->id)->first();
        
		$data = [
		    'last_watched' => $last_watched,
		    'first_lesson' => $first_lesson,
		    'user_rating'  => $rating
	    ];
		
		return response()->json($data, 200);
    }
    
    
    public function fetchCourseContent(Request $request, Course $course)
    {
		
		$keyword = $request->q;
		
        $sections = Section::orderBy('sortOrder', 'asc')
    		            ->with(['lessons' => function ($l) use ($keyword){
    		                if(!is_null($keyword)){
    		                    $l->where('title', 'like', "%$keyword%");
    		                }
    		                $l->orderBy('sortOrder', 'ASC');
    		            }])
		            ->where('course_id', $course->id)
		            ->get();
		
		foreach ($sections as $section){
		    $section->percent_completed = auth()->user()->percentSectionCompleted($section);
		    foreach($section->lessons as $lesson){
		        $lesson->user_completed = auth()->user()->hasCompletedLesson($lesson) ? true : false;
		    }
		}
		
		
		return response()->json($sections, 200); 
    }
    
    
    public function loadCountriesAndLanguages()
    {
        //$countries = \Countries::all();
        
        $languages = array_collapse(\Countries::all()->pluck('languages'));
        $languages = collect($languages)->sort();
        //dd($languages);
        
        foreach($languages as $k => $v){
            Language::create([
                'code' => $k,
                'name' => $v
            ]);
        }
        dd('Done');
        
        foreach($countries as $c){
            
            /*Country::create([
                'name' => $c['name']['common'], 
                'cca2' => $c['cca2'], 
                'cca3' => $c['cca3'], 
                'capital' => $c['capital'], 
                'flag_svg' => $c['flag']['svg']    
            ]);  */ 
            
        }
        
    }
}
