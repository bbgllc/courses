<?php

namespace App\Http\Controllers\Frontend\_Author;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorSectionController extends Controller
{
    
    public function fetchSections($course)
    {

        $sections = Section::where('course_id', $course)->orderBy('sortOrder', 'asc')->get();
        return response()->json($sections, 200);
    }


    public function fetchSection($id)
    {
        $section = Section::find($id);
        return response()->json($section, 200);
    }
    
    public function updateSection(Request $request, $id)
    {
        
        $section = Section::find($id);
        
        if(!$section){
            return response()->json(['message' => 'Section not found'], 404);
        }
        
        $section->title = $request->title;
        $section->objective = $request->objective;
        $section->save();
        
        return response()->json($section, 200);
    }
    
    public function storeSection(Request $request)
    {
        
        $this->validate($request, [
            'title' => 'required|max:100'
        ]);
        
        $maxSort = \DB::table('sections')->where('course_id', $request->course)->max('sortOrder');
        $section = new Section();
        $section->course_id = $request->course;
        $section->title = $request->title;
        $section->objective = $request->objective;
        $section->sortOrder = $maxSort+1;
        $section->save();
        
        return response()->json($section, 200);
    }
    
    public function destroy($id)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        
        $section = Section::find($id);
        $section->delete();
        return response()->json(null, 200);
    }
    
    public function updateDraggable(Request $request)
    {
        $section = Section::find($request->data['id']);
        $section->sortOrder = $request->data['sortOrder'];
        $section->save();
        return response()->json($section, 200);
    }


}
