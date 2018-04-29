<?php

namespace App\Http\Controllers\Frontend\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
    
    public function index()
    {
        if(auth()->user()->isAdmin()){
            return redirect('/admin/dashboard');
        }
        return view('_User.notifications');
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification){
            $notification->markAsRead();
        }
        return back();
    }
    
    public function markAllAsRead()
    {
        $notifications = auth()->user()->notifications;

        foreach($notifications as $notification){
            $notification->markAsRead();
        }
        
        return back();
    }
}
