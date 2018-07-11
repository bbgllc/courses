<?php

namespace App\Http\Controllers\Api\Mobile\Auth;

use Auth;
use Validator;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;

class AuthenticationController extends Controller
{
    
    public $successStatus = 200;


    public function login()
    {
        if(auth()->attempt(['email' => request('email'), 'password' => request('password')])){
            $user = auth()->user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
    
    
    public function register()
    {
        $validator = Validator::make($request->all(), [
            'username'             => 'required|string|unique:user,username',
            'first_name'           => 'required|string|max:191',
            'last_name'            => 'required|string|max:191',
            'email'                => ['required', 'string', 'email', 'max:191', Rule::unique('users')],
            'password'             => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        
        $user = User::create([
            'username' => $request->username,  
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'active'            => 1,
            'confirmed'        => config('access.users.requires_approval') || config('access.users.confirm_email') ? 0 : 1,
        ]);
        
        if ($user) {
            $user->assignRole(config('access.users.default_role'));
        }
        
        if (config('access.users.confirm_email')) {
            $user->notify(new UserNeedsConfirmation($user->confirmation_code));
        }
        
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['success'=>$success], $this->successStatus);
    }
    
    
    public function getDetails()
    {
        $user = auth()->user();
        
        return response()->json(['success' => $user], $this->successStatus);
        
    }
    
    public function logout(Request $request)
    {
        
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'No active user session found',
            ], 404);
        }
        
        $request->user()->token()->revoke();
        session()->flush(); 
        session()->regenerate();
            
        $json = [
            'success' => true,
            'code' => 200,
            'message' => 'You are Logged out.',
        ];
        
        return response()->json($json, 200);
        
    }
    
}
