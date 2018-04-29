<?php

namespace App\Http\Controllers\Frontend\User;

use Mail;
use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\MyThread as Thread;
use App\Notifications\Frontend\MessageReceived;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use App\Events\Messaging\MessageCreated;

use Illuminate\Support\Facades\Input;

use App\Http\Controllers\Controller;

class MessagingController extends Controller
{
    
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $firstThread = Thread::getAllLatest()->ForUser(auth()->id())->first();

        return view('_User.messenger.inbox', compact('firstThread', 'users' ));
    }
    
    
    public function fetchThreads(Request $request)
    {
        
        $threads = Thread::getAllLatest()->ForUser(auth()->id());
        $keyword = $request->q;
        
        if($keyword){
            $threads = $threads->whereHas('users', function($q) use ($keyword){
                $q->where('username', 'like', "%$keyword%")
                    ->orWhere('first_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%");
                })
                ->orWhereHas('messages', function($q) use ($keyword){
                    $q->where('body', 'like', "%$keyword%");
                })->groupBy(['id', 'subject', 'created_at', 'updated_at', 'deleted_at']);
        }
        
        $threads = $threads->get();
        
        foreach($threads as $thread){
            $thread->creator = $thread->creator()->name;
            $thread->isUnread = $thread->isUnread(auth()->id());
            $thread->creator_image = $thread->creator()->photo_url;
            $thread->created_at_human = $thread->created_at->diffForHumans();
            $thread->latestMessage = str_limit($thread->latestMessage->body, 80);
            $thread->recipient = $thread->users->where('id', '!=', auth()->id())->first();
            //$thread->recipient_picture = $thread->users->where('id', '!=', auth()->id())->first()->picture;
        }
        
        return response()->json($threads, 200);
    }
    
    public function getUnread()
    {
        $threads = Thread::getAllLatest()->ForUserWithNewMessages(Auth::id())->paginate(12);
        $inbox = Thread::getAllLatest()->get();
        $unread = Thread::getAllLatest()->ForUserWithNewMessages(Auth::id())->get()->count();
        //return view('frontend._messaging.inbox', compact('threads'));
        
    }
    
    public function fetchThreadMessages($id)
    {
        $thread = Thread::with('messages', 'attachments')->find($id);
        
        $thread->creator = $thread->creator()->id;
        $chatting_with = $thread->participants()->where('user_id', '!=', auth()->id())->pluck('user_id');
        $thread->chatting_with_user = User::whereIn('id', $chatting_with)->first();
        $thread->isUnread = $thread->isUnread(auth()->id());
        
        foreach($thread->messages as $msg){
            $msg->created_at_human = $msg->created_at->diffForHumans();
            $msg->creator = $msg->user;
            $msg->creator->picture = $msg->user->photo_url;
            $auth_user = auth()->id();
            
        }
        
        $attachments = $thread->attachments()
                        ->join('users', 'attachments.description', '=', 'users.id')
                        ->select('attachments.*', 'users.username')
                        ->get();
                        
        foreach($attachments as $att){
            $user = User::find($att->description);
            $att->user = $user->name;
            $att->created_at_human = $att->created_at->diffForHumans();
            $att->fullPath = '/uploads/attachments/'.$att->filename;    
        }
        
        $data = [
            'messages' => $thread,
            'attachments' => $attachments
        ];
        
        $thread->markAsRead(auth()->id());
        
        return response()->json($data, 200);
    }
    
    
    public function store(Request $request)
    {
        
        $recipients = explode(',', $request->recipient);
        $recipients[1] = (string) auth()->id();
        
        $thread = Thread::whereHas('participants', function ($query) use ($recipients) {
            $query->whereIn('user_id', $recipients)
                ->groupBy('thread_id')
                ->havingRaw('COUNT(thread_id)='.count($recipients));
        })->first();
        
        
        if (!$thread) {
            $thread = Thread::create([
                //'subject' => $request->subject,
                'subject' => 'New Message from '.auth()->user()->name,
            ]);

              // Sender
            Participant::create([
                    'thread_id' => $thread->id,
                    'user_id'   => auth()->id(),
                    'last_read' => new Carbon(),
            ]);

            // Recipients
            if ($request->recipient) {
                $thread->addParticipant($request->recipient);
            }
        }
        
        
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id'   => auth()->id(),
            'body'      => $request->message,
        ]);
        
        $thread->markAsRead(auth()->id());
        
        $recipient = User::find($request->recipient);
        $sender = auth()->user();
        
        //Mail::to($recipient)->send(new NewChatMessage($recipient, $sender ));
        
        return redirect(route('frontend.user.inbox'));
    }
    
    
    public function update(Request $request, $id)
    {
        
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(null, 505);
        }
        $recipient = $thread->participants->where('user_id', '!=', auth()->id())->first();
        
        $thread->activateAllParticipants();
        
        // Message
        $message = Message::create([
            'thread_id' => $id,
            'user_id'   => auth()->id(),
            'body'      => $request->body,
        ]);
        
        
        
        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id'   => auth()->id(),
        ]);
        $participant->last_read = new Carbon;
        $participant->save();
        
        //broadcast(new MessageCreated($message, $recipient))->toOthers();
        
        $recipient = User::find($recipient->user_id);
        $sender = auth()->user();
        
        $recipient->notify(new MessageReceived($recipient, $sender, $message));
 
        return response()->json(null, 200);

    }
    
    public function markThreadAsRead(Request $request)
    {
        $thread = Thread::findOrFail($request->id);
        
        $thread->markAsRead(auth()->id());
        
        return response()->json(null, 200);
    }
    
    
    public function addAttachment(Request $request)
    {
        $thread = Thread::find($request->thread);
        
        // upload file resources
        if($request->hasFile('file')){
            $file = $request->file('file')->getClientOriginalName();
            $file_name = time().'_'.$request->file('file')->getClientOriginalName();
            
            $destination = public_path(). '/uploads/attachments';
            
            $request->file('file')->move($destination, $file_name);
            
            $attachment = $thread->attach($destination.'/'.$file_name, [
                'title' => $file,
                'description' => $request->user_id,
                'key' => str_random(30)        
            ]);
            
            $attachment->save();
        }
        
        return response()->json(null, 200);
        
    
    }
}
