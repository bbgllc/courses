<?php

namespace App\Http\Controllers\Backend;

use Storage;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use App\Http\Requests\Backend\PostStoreRequest;
use App\Http\Requests\Backend\PostUpdateRequest;
use App\Http\Requests\Backend\PostMetaUpdateRequest;

class AdminBlogPostController extends Controller
{
    protected $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }
    
    public function index(Request $request)
    {
       
        if($request->filter=='pages'){
            $posts = Post::whereHas('category', function($q){
                $q->whereSlug('site-pages');
            })->orderBy('slug', 'asc')->get();
            
            $filter = 'pages';
            
        } elseif($request->filter=='posts'){
            $posts = Post::whereHas('category', function($q){
                $q->where('slug', '!=', 'site-pages');
            })->orderBy('slug', 'asc')->get();
            
            $filter = 'posts';
        } else {
            $posts = Post::all();
            $filter = 'all';
        }
        
        return view('backend.blog.index', compact('posts', 'filter'));
    }
    
    
    public function create()
    {
        $defaultLang = env('APP_LOCALE');
        
        $categories = Category::where('model', 'App\Models\Post')
                        ->orderBy('name', 'ASC')
                        ->pluck('name', 'id')
                        ->prepend('Choose category...','');
                        
        return view('backend.blog.create', compact('categories', 'defaultLang'));
    }
    
    
    
    public function store(PostStoreRequest $request)
    {

        $default_locale = env('APP_LOCALE');
        
        // create the default post for default language
        $data = [
            'category_id' => (int)$request->category,
            'user_id' => auth()->user()->id,
            'published' => $request->published ? true : false,
            'published_at' => $request->published ? \Carbon\Carbon::now() : null,
            $default_locale => [
                'title' =>  $request->title,
                'intro' =>  $request->intro,
                'slug'  =>  $request->slug,
                'body'  =>  $request->body,
                'meta_description'  =>  $request->meta_description,
            ]
        ];
        
        $post = Post::create($data);
        
        return redirect(route('admin.blog.edit', $post))->withFlashSuccess('Post has been saved!');
    }
    
    
    public function edit(Post $post)
    {
        
        $categories = Category::where('model', 'App\Models\Post')
                        ->orderBy('name', 'ASC')
                        ->pluck('name', 'id');
                        
        return view('backend.blog.edit', compact('post', 'categories'));
    }
    
    public function update(PostUpdateRequest $request, Post $post)
    {
        $lang = $request->lang;
        
        $post->translateOrNew($lang)->title = $request->title;
        $post->translateOrNew($lang)->slug = $request->slug;
        $post->translateOrNew($lang)->intro = $request->intro;
        $post->translateOrNew($lang)->body = $request->body;
        $post->translateOrNew($lang)->meta_description = $request->meta_description;
        $post->save();
        
        return back()->withFlashSuccess('Changes have been updated!');
    }
    
    public function updateMetadata(PostMetaUpdateRequest $request, Post $post)
    {
  
        $post->category_id = $request->category;
        $post->published = $request->published ? true : false;
        $post->featured = $request->featured ? true : false;
        $post->published_at = $request->published ? \Carbon\Carbon::now() : NULL;
        $post->save();
        
        return back()->withFlashSuccess('Changes have been updated!');
    }
    
    public function updateFeaturedImage(Request $request, $id)
    {
        $post = Post::find($id);
        $oldImage = $post->featured_image; // delete the old image from the file system after new one is uploaded
        $processedImage = $this->imageManager->make($request->file('file')->getPathName())
            ->fit(800, 500, function ($c) {
                $c->aspectRatio();
            })
            ->encode('png')
            ->save(public_path('uploads/images/posts/' . $filename = uniqid(true) . '.png'));
        
        $post->featured_image = $filename;
        $post->save();
        
        if(!is_null($oldImage)){
            if(Storage::disk('server')->exists('images/posts/'.$oldImage)){
                Storage::disk('server')->delete('images/posts/'.$oldImage);
            }
        }
        $path = '/uploads/images/posts/'.$post->featured_image; 
        
        return response([
            'data' => [
                'path' => $path,
            ]
        ], 200);
        
    }
    
    public function removeImage($id)
    {
        $post = Post::find($id);
        if($post->image && Storage::disk('server')->exists('images/posts/'.$post->featured_image)){
            Storage::disk('server')->delete('images/posts/'. $post->featured_image);
        }
        
        $post->featured_image = null;
        $post->save();
        
        return response()->json(null, 200);
        
    }
    
    public function destroy(Post $post)
    {
        
        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        
        $post->deleteTranslations();
        $post->delete();
        
        return redirect()->back();
    }
    
}
