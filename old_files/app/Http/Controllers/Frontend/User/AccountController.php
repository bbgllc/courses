<?php

namespace App\Http\Controllers\Frontend\User;

use Storage;
use Carbon\Carbon;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdatePasswordRequest;

/**
 * Class AccountController.
 */
class AccountController extends Controller
{
    
    protected $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //dd(setting('show_profile_in_search', '', auth()->user()->id));
        return view('_User.account');
    }
    

    public function updateProfile(Request $request)
    {
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        $user = auth()->user();
        
        $this->validate($request, [
            'country' => 'required|string',
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'username' => 'required|no_spaces_allowed|unique:users,username,'.$user->id,
            'headline' => 'required',
            'web' => 'nullable|url'
        ]);
        
        $country = \Countries::where('cca2', $request->country)->first();
        if($country){
            $country_name = $country->name->common;
        } else {
            $country_name = $request->country;
        }
        
        $user->country = $country_name;
        $user->country_code = $request->country;    
        $user->first_name = strip_tags($request->first_name);
        $user->last_name = strip_tags($request->last_name);
        $user->username = strip_tags($request->username);
        $user->tagline = strip_tags($request->headline);
        $user->bio = $request->biography;
        $user->web = $request->web;
        $user->facebook = strip_tags($request->facebook);
        $user->github = strip_tags($request->github);
        $user->linkedin = strip_tags($request->linkedin);
        $user->twitter = strip_tags($request->twitter);
        
        $user->save();
        
        return response()->json($user, 200);
    }
    
    
    // Save new avatar
    public function uploadAvatar(Request $request, $id)
    {
        
        $user = User::find($id);
        
        $oldImage = $user->avatar; // delete the old image from the file system after new one is uploaded
        $processedImage = $this->imageManager->make($request->file('files')->getPathName())
            ->fit(500, 500, function ($c) {
                $c->aspectRatio();
            })
            ->encode('png')
            ->save(public_path('uploads/images/avatar/' . $filename = uniqid(true) . '.png'));
        
        $user->avatar_location = '/uploads/images/avatar/'.$filename;
        $user->avatar = $filename;
        $user->avatar_type = 'storage';
        $user->save();
        
        if(!is_null($oldImage)){
            if(Storage::disk('server')->exists('images/avatar/'.$oldImage)){
                Storage::disk('server')->delete('images/avatar/'.$oldImage);
            }
        }
        $path = $user->avatar_location; 
        
        return response([
            'data' => [
                'path' => $path,
            ]
        ], 200);
    }
    
    public function passwordUpdate()
    {
        return view('_User.password-update');
    }
    
    
    public function updatePassword(UpdatePasswordRequest $request, $expired = false)
    {
        
        $user = auth()->user();
        
        if (! Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'old_password' => ['The given password does not match our records.']
            ], 422);
        }
        $user->password = bcrypt($request->password);
        
        if ($expired) {
            $user->password_changed_at = Carbon::now()->toDateTimeString();
        }
        
        $user->save();
        
    }
    
}
