<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    
    public function index(Request $request)
    {
        
        $posts = Post::withTranslation()->with('author')
                    ->whereHas('category',  function($q){
                        $q->where('slug', '!=', 'site-pages');
                    })->where('published', true)->get();
        
        return view('blog.index', compact('posts'));
    }
    
    public function getByCategory(Category $category)
    {
        $posts = Post::withTranslation()->with('author')
                    ->where('category_id', $category->id)
                    ->whereHas('category',  function($q){
                        $q->where('slug', '!=', 'site-pages');
                    })->get();
                    
        return view('blog.index', compact('posts'));
    }
    
    
    public function show($slug)
    {
        $post = Post::whereTranslation('slug', $slug)->first();
        
        $posts = Post::withTranslation()->with('author')
                    ->whereHas('category',  function($q){
                        $q->where('slug', '!=', 'site-pages');
                    })->where('published', true)->get();
                    
        return view('blog.show-blog', compact('post', 'posts'));
    }
    
    public function showPage($slug)
    {
        $page = Post::whereTranslation('slug', $slug)->first();
        
        return view('blog.show-page', compact('page'));
    }
    
    
    
    
}
