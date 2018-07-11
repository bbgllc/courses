<?php

namespace App\Http\Controllers\Frontend\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    
    
    public function index(Request $request)
    {
        return view('_User.account-settings');
    }
    
    
    public function updateSettings(Request $request)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        setting()->set('show_profile_in_search', $request->show_profile_in_search, auth()->id());
        setting()->set('notify_when_mentioned', $request->notify_when_mentioned, auth()->id());
        setting()->set('notify_when_question_responded', $request->notify_when_question_responded, auth()->id());
        setting()->set('notify_when_question_i_am_following_responded', $request->notify_when_question_i_am_following_responded, auth()->id());
        setting()->set('notify_when_new_announcement', $request->notify_when_new_announcement, auth()->id());
        setting()->set('notify_when_answer_marked_as_correct', $request->notify_when_answer_marked_as_correct, auth()->id());
        setting()->set('notify_when_followed_question_is_answered', $request->notify_when_followed_question_is_answered, auth()->id());
        setting()->set('notify_when_my_question_is_marked_as_answered', $request->notify_when_my_question_is_marked_as_asnwered, auth()->id());
        setting()->set('notify_when_course_is_reviewed', $request->notify_when_course_is_reviewed, auth()->id());
        setting()->set('send_me_helpful_resources', $request->send_me_helpful_resources, auth()->id());
        setting()->set('notify_when_new_question_in_my_course', $request->notify_when_new_question_in_my_course, auth()->id());
    
        setting()->save(auth()->id());
    }
}
