<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    
    
    public function profile(Request $request)
    {
        
        $user = User::where('username', $request->username)->with('courses')->first();
        
        $courses = $user->courses()->where('published', true)->where('approved', true)->paginate(12);
        
        return view('_User.profile', compact('user', 'courses'));
        
    }
    
    
    
    
    
}
