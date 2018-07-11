<?php

namespace App\Http\Controllers\Frontend\_Author;


use File;
use App\Models\Section;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorLessonController extends Controller
{
    
    public function storeLesson(Request $request)
    {
        //dd();
        
        $this->validate($request, [
            'title' => 'required|max:120',
            'description' => 'max:120'
        ]);
        
        $maxSection = Section::where('course_id', $request->course)->orderBy('sortOrder', 'DESC')->first();
        
        $maxSort = \DB::table('lessons')->where('section_id', $maxSection->id)->max('sortOrder');

        $lesson = new Lesson();
        $lesson->section_id = $maxSection->id;
        $lesson->title = $request->title;
        $lesson->description = $request->description;
        $lesson->lesson_type = $request->lesson_type;
        $lesson->preview = (bool)$request->preview;
        $lesson->uid = random_int(100, 20000) + random_int(99, 2000);
        $lesson->sortOrder = $maxSort+1;
        $lesson->save();
        
        return response()->json($lesson, 200);
    }


    public function fetchLessons($section)
    {
        $lessons = Lesson::with(['content', 'attachments', 'quizQuestions', 'quizQuestions.answers'])->where('section_id', $section)->orderBy('sortOrder', 'ASC')->get();
        return response()->json($lessons, 200);
    }
    
    public function fetchLesson($id)
    {
        $lesson = Lesson::with(['content', 'attachments', 'quizQuestions', 'quizQuestions.answers'])->find($id);
        return response()->json($lesson, 200);
    }
    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:120',
            'description' => 'max:120'
        ]);
        
        $lesson = Lesson::find($id);
        $lesson->title = $request->title;
        $lesson->description = $request->description;
        $lesson->preview = (bool)$request->preview;
        $lesson->save();
        
        return response()->json($lesson, 200);
    }
    
    public function updateDraggable(Request $request)
    {
        $lesson = Lesson::find($request->data['id']);
        $lesson->sortOrder = $request->data['sortOrder'];
        $lesson->section_id = $request->data['section_id'];
        
        $lesson->save();
        
        return response()->json($lesson, 200);
    }
    
    public function destroy($id)
    {
        if(config('settings.enable_demo')){
            return response()->json(['message' => 'Not allowed in Demo mode'], 422);
        }
        
        $lesson = Lesson::find($id);
        $lesson->delete();
        return response()->json(null, 200);
    }



    /**************************************************************************/
    // Lesson attachments
    
    public function uploadAttachment(Request $request, $lesson)
    {
        
        $lesson = Lesson::find($lesson);
        
        if($request->hasFile('file')){
            $file_name = time().'_'.$request->file('file')->getClientOriginalName();
            $destination = public_path(). '/uploads/attachments';
            
            $request->file('file')->move($destination, $file_name);
            
            $attachment = $lesson->attach($destination.'/'.$file_name);
        }
        
        return response()->json(null, 200);
        
    }
    
    public function deleteAttachment($lesson, $attachment)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        
        $lesson = Lesson::find($lesson);
        $att= $lesson->attachment($attachment);
        
        $att->delete();
        
        if (File::exists('uploads/attachments/'.$att->filename)){
            File::delete('uploads/attachments/'.$att->filename);
        }

        return response()->json(null, 200);
        
    }
}
