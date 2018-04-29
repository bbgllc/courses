<?php

namespace App\Http\Controllers\Frontend\User;


use App\Models\Review;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    
    // save a review to database (api)
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'rating' => 'required|numeric|min:1',
            'title' => 'required',
            'comment' => 'required',
        ]);
        
        $course = Course::find($request->course);
		
		$review = $course->reviews()->create([
			'rating' => $request->rating,
			'title' => $request->title,
			'user_id' => auth()->user()->id,
			'comment' => $request->comment
		]);
		
		
		return response()->json($review, 200);

    }

    // fetch reviews for a provided course
    public function fetchReviews($course)
    {

        $reviews = Review::latest()->with('user', 'comments', 'comments.user')
        	->where('course_id', $course)
        	->paginate(10);
        
        foreach($reviews as $review){
        	$review->created_at_human =	$review->created_at->diffForHumans();
        	$review->author_image = $review->user->picture;
        }
        
        return response()->json($reviews, 200);
        
    }  

    // post a reply to a review. Replies are posted by course author
    public function reply(Request $request, $id)
    {
    	$this->validate($request, [
            'description' => 'required'
        ]);
        
        $review = Review::find($id);
        
        $comment = $review->comments()->create([
            'user_id'     => auth()->id(),
            'description' => $request->description
        ]);
        
        return response()->json(null, 200);
        
    }

    // Delete a review
    public function destroy(Request $request, $id)
    {
    	if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
    	Review::find($id)->delete();
        return response()->json(null, 200);
        
    }
}
