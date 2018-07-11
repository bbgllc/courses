<?php

namespace App\Http\Controllers\Backend;


use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminBlogCategoryController extends Controller
{
    
    public function index()
    {
        $categories = Category::whereModel('App\Models\Post')->with('posts')->paginate(10);
        $catArray = Category::whereNull('parent_id')->whereModel('App\Models\Post')
                        ->orderBy('sortOrder', 'ASC')
                        ->with(['subCategories' => function($q){
                            $q->orderBy('sortOrder', 'ASC');    
                        }])->get();
                        
        $parentCategories = Category::whereNull('parent_id')->whereModel('App\Models\Post')->pluck('name', 'id')->prepend('Choose parent category is necessary','');
        return view('backend.blog.category.index', compact('categories', 'catArray', 'parentCategories'));
    }
    
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
                            ->whereModel('App\Models\Post')
                            ->where('id', '!=', $category->id)
                            ->pluck('name', 'id')->prepend('Choose parent category is necessary','');
        return view('backend.blog.category.edit', compact('category', 'parentCategories'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories,name',
        ]);
        
        $category = new Category();
        $category->name = $request->name;
        $category->slug = str_slug($request->name);
        $category->color = $request->color;
        $category->model = 'App\Models\Post';
        $category->parent_id = $request->parent_category;
        $category->save();
        
        return redirect()->back();
    }
    
    public function update(Request $request, Category $category)
    {
        
        $this->validate($request, [
            'name' => 'required|unique:categories,name,'.$category->id
        ]);
        $category->name = $request->name;
        $category->slug = str_slug($request->name);
        $category->color = $request->color;
        $category->parent_id = $request->parent_category;
        $category->save();
        
        return redirect()->back();
    }
    
    public function destroy(Category $category)
    {

        if(config('settings.enable_demo')){
            return back()->withFlashDanger('Not allowed in Demo mode');
        }
        $category->delete();
        return redirect()->back();
    }
}
