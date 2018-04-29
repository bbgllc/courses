<?php

namespace App\Filters\Course;

use App\Models\Category;
use App\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter extends FilterAbstract
{
    
    /**
     * Filter by course difficulty.
     *
     * @param  string $access
     * @return Illuminate\Database\Eloquent\Builder
     */
     
    public function filter(Builder $builder, $value)
    {
        $category = Category::where('slug', $value)->first();
        
        if($category && $category->parent_id){
            return $builder->whereHas('category', function($q) use ($value){
                $q->where('slug', $value);
            });
        } else {
            return $builder->whereHas('category', function($q) use ($category){
                $q->where('parent_id', $category->id);
            });    
        }
        
        /*
        return $builder->whereHas('category', function($q) use ($value){
            $q->where('slug', $value);
        });*/
        
        
    }
}
