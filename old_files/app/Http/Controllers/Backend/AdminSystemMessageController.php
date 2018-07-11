<?php

namespace App\Http\Controllers\Backend;

use App\Models\SystemMessage;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Notifications\Backend\SystemMessageNotification;
use App\Http\Controllers\Controller;

class AdminSystemMessageController extends Controller
{
    
    public function index(Request $request)
    {   
       
        if($request->filter == 'sent'){
            $messages = SystemMessage::latest()->with('users')->where('sent', true)->get();
            $filter = 'sent';
        } elseif ($request->filter == 'draft'){
            $messages = SystemMessage::latest()->with('users')->where('sent', false)->get();
            $filter = 'draft';
        } else {
            $messages = SystemMessage::latest()->with('users')->get();
            $filter = 'all';
        }
        return view('backend.messages.index', compact('messages', 'filter'));
    }
    
    public function create()
    {
        $users = User::orderBy('first_name')->where('id', '<>', auth()->user()->id)->get();
        return view('backend.messages.create', compact('users'));
    }
    
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'subject' => 'required',
            'body'    => 'required',
            'recipient_group'    => 'required|in:everyone,admins,authors,students,inactive-users,selected-users',
            'recipients' => 'required_if:recipient_group,selected-users'
        ]);
        
        if($request->recipient_group == 'authors'){
            $recipients = User::has('courses')->pluck('id');
        } elseif ($request->recipient_group == 'students'){
            $recipients = User::has('enrollments')->pluck('id');
        } elseif ($request->recipient_group == 'inactive-users'){
            $recipients = User::where('confirmed', 0)->pluck('id');
        } elseif ($request->recipient_group == 'everyone'){
            $recipients = User::all()->pluck('id');
        } elseif ($request->recipient_group == 'admins'){
            $recipients = User::whereHas('roles', function($q){
                $q->where('name', '=', 'Administrator');
            })->pluck('id');
        } else {
            $recipients = explode(',', $request->recipients);
        }
        
        $message = new SystemMessage();
        $message->subject = $request->subject;
        $message->body = $request->body;
        $message->recipient_group = $request->recipient_group;
        $message->save();

        $message->users()->attach($recipients);
        
        return redirect(route('admin.messages.index'));
    }
    
    public function edit($id)
    {
        $users = User::orderBy('first_name')->where('id', '<>', auth()->user()->id)->get();
        $message = SystemMessage::find($id);
        
        //$r = implode(',', ($message->users->pluck('id')));
        //$recipients = $r->toArray();
        
        $recipients = $message->users->pluck('id');
        
        return view('backend.messages.edit', compact('message', 'users', 'recipients'));
    }
    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'subject' => 'required',
            'body'    => 'required',
            'recipient_group'    => 'required|in:everyone,admins,authors,students,inactive-users,selected-users',
            'recipients' => 'required_if:recipient_group,selected-users'
        ]);
        
        if($request->recipient_group == 'authors'){
            $recipients = User::has('courses')->pluck('id');
        } elseif ($request->recipient_group == 'students'){
            $recipients = User::has('enrollments')->pluck('id');
        } elseif ($request->recipient_group == 'inactive-users'){
            $recipients = User::where('confirmed', 0)->pluck('id');
        } elseif ($request->recipient_group == 'everyone'){
            $recipients = User::all()->pluck('id');
        } elseif ($request->recipient_group == 'admins'){
            $recipients = User::whereHas('roles', function($q){
                $q->where('name', '=', 'Administrator');
            })->pluck('id');
        } else {
            $recipients = explode(',', $request->recipients);
        }
        
        $message = SystemMessage::find($id);
        $message->subject = $request->subject;
        $message->body = $request->body;
        $message->recipient_group = $request->recipient_group;
        $message->save();
        
        $message->users()->detach();
        $message->users()->attach($recipients);
        
        return redirect()->route('admin.messages.index');
    }
    
    public function send($id)
    {
        
        $message = SystemMessage::find($id);
        
        if($message->recipient_group == 'authors'){
            $recipients = User::has('courses')->get();
        } elseif ($message->recipient_group == 'students'){
            $recipients = User::has('enrollments')->get();
        } elseif ($message->recipient_group == 'inactive-users'){
            $recipients = User::where('comfirmed', false)->get();
        } elseif ($message->recipient_group == 'everyone'){
            $recipients = User::all();
        } elseif ($message->recipient_group == 'admins'){
            $recipients = User::whereHas('roles', function($q){
                $q->where('name', '=', 'Administrator');
            })->get();
        } else {
            $recipients = $message->users;
        }

        // create notification for the recipients
        foreach($recipients as $user){
            $user->notify(new SystemMessageNotification($message));
        }
        
        $message->sent = true;
        $message->save();
        
        $message->users()->detach();
        $message->users()->attach($recipients);
        
        return redirect()->back()->withFlashSuccess('Message has been sent to the selected recipients');    
        
    }
    
    public function destroy($id)
    {
        SystemMessage::find($id)->delete();
        return redirect()->back()->withFlashSuccess('Deleted');    
    }
}
