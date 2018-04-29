<?php

namespace App\Http\Controllers\Backend;

use App\Models\Auth\User;
use App\Models\Course;
use App\Models\Content;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class SampleDataController extends Controller
{
    
    public function removeSampleData()
    {
        
        $user = User::whereUsername('lucy_swindol')->first();
        
        \Eloquent::unguard();
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Content::truncate();
        QuizAnswer::truncate();
        QuizQuestion::truncate();
        Lesson::truncate();
        Section::truncate();
        Course::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        if($user){
            $user->delete();    
        }
        
        $files = \File::allFiles(public_path('/uploads/images/course'));
        
        \File::delete($files);
        
        
        return redirect()->back()->withFlashSuccess('Demo courses have been removed');
        
    }
    
}
