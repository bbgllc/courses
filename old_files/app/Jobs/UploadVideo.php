<?php

namespace App\Jobs;

use File;
use Storage;
use App\Models\Content;
use Illuminate\Bus\Queueable;
use App\Jobs\ConvertVideoForStreaming;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
//use Illuminate\Foundation\Bus\DispatchesJobs;

class UploadVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filename;
     
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    
    
    public function handle()
    {
        $file = storage_path() . '/uploads/' . $this->filename;
        
        if(config('site_settings.video_upload_location') == 's3'){
            if(Storage::disk('s3')->put($this->filename, fopen($file, 'r+'))) {
                File::delete($file);
            }
        } else {
            if(Storage::disk('server')->put('videos/'.$this->filename, fopen($file, 'r+'))) {
                File::delete($file);
            };
        }
        
        
        
        
        
    }
}
