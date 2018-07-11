<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Filters\Course\CourseFilters;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    
    public function search(Request $request)
    {
        $search_term = $request->keyword;
        
        $filters = [
            'access' => $request->access,
            'difficulty' => $request->difficulty,
            'category' => $request->category,
            'keyword' => $search_term
        ];

        $courses = Course::with(['category'])
                        ->filter($request)
                        ->paginate(12);

        return view('courses.courses', compact('courses', 'filters'));
    }
    
}
