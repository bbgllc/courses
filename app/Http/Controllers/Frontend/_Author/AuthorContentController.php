<?php

namespace App\Http\Controllers\Frontend\_Author;


use File;
use Storage;
use App\Models\Lesson;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Jobs\UploadVideo;
use App\Jobs\ConvertVideoForStreaming;
use App\Http\Controllers\Controller;

class AuthorContentController extends Controller
{
    
    public function uploadVideo(Request $request, $id)
    {
        
        $lesson = Lesson::find($id);
        
        $originalFileName = $request->file('file')->getClientOriginalName();
        $ext = $request->file('file')->extension();
        
        if($ext  != 'mp4'){
            return response()->json(null, 200);
        }
        
        // grab video duration in seconds
        $getID3 = new \getID3;
        //$file = $getID3->analyze(public_path() . $content->video_path);
        $file = $getID3->analyze($request->file('file'));
        $duration_in_seconds = $file['playtime_seconds'];
        
        if($lesson->content && $lesson->content->count()){
            $deleteVideo = $lesson->content->video_filename;
            
            $lesson->content->delete();

            if(config('site_settings.video_upload_location') == 'local' && Storage::disk('server')->exists('videos/'.$deleteVideo)){
                Storage::disk('server')->delete('videos/'.$deleteVideo);
            } elseif(config('site_settings.video_upload_location') == 's3' && Storage::disk('s3')->exists($deleteVideo)){
                Storage::disk('s3')->delete($deleteVideo);
            }
           
        }
        
        $file_base = str_random(2). '-' . substr(str_slug($originalFileName), 0, -3);
        $filename = $file_base .'.'.$ext;
        
        $path = $request->file('file')->storeAs('uploads', $filename, 'tmpStorage');
        
        $this->dispatch(new UploadVideo($filename));
        
        $content = new Content();
        $content->lesson_id = $request->id;
        $content->content_type = 'video';
        $content->video_filename = $filename;
        $content->video_duration = $duration_in_seconds/60; // duration in seconds
        
        if(config('site_settings.video_upload_location') == 's3'){
            $content->video_path = Storage::disk('s3')->url($filename);
        } else {
            $content->video_path = '/uploads/videos/'.$filename;
        }
        $content->video_src = 'upload';
        $content->video_storage = config('site_settings.video_upload_location');
        $content->save();
       
        return response()->json(null, 200);
    }


    public function createArticle(Request $request)
    {
        $content = new Content();
        $content->lesson_id = $request->lesson_id;
        $content->content_type = 'article';
        $content->article_body = $request->article_body;
        $content->save();

        return response()->json($content, 200);
    }
    
    public function edit($id)
    {
        $content = Content::find($id);
        return response()->json($content, 200);
    }
    
    public function updateArticle(Request $request, $id)
    {
  
        $content = Content::find($id);
        $content->article_body = $request->article_body;
        $content->save();
        
        return response()->json($content, 200);
    }
    
    public function embedVideo(Request $request, $id)
    {
        $this->validate($request, [
            'video_link' => 'required|url',
            'video_duration' => 'required|numeric|min:1',
        ]);
        
        $lesson = Lesson::find($id);
        
        $lesson->content()->create([
            'video_src' => 'embed',
            'content_type' => 'video',
            'video_provider' => $request->video_provider,
            'video_duration' => $request->video_duration,
            'video_path' => $request->video_link
        ]);
        
        return response()->json(null, 200);
        
    }

    
    public function updateEmbedVideo(Request $request, $id)
    {
        $content = Content::find($id);
        
        $this->validate($request, [
            'video_link' => 'required|url',
            'video_duration' => 'required|numeric|min:1',
        ]);
        
        $content->video_provider = $request->video_provider;
        $content->video_path = $request->video_link;
        $content->video_duration = $request->video_duration;
        $content->save();
        
        return response()->json(null, 200);
    }
}
