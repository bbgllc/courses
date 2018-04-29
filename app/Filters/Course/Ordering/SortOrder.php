<?php

namespace App\Filters\Course\Ordering;

use App\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class SortOrder extends FilterAbstract
{
    /*
    public function filter(Builder $builder, $value)
    {
        return $builder->orderBy('created_at', $this->resolveOrderDirection($value));
    }*/
    
    public function filter(Builder $builder, $value)
    {
        if($value == 'recent_first'){
            return $builder->orderBy('created_at', 'desc');
        } elseif($value == 'oldest_first'){
            return $builder->orderBy('created_at', 'asc');
        } elseif($value == 'price_asc'){
            return $builder->orderBy('price', 'asc');
        } elseif($value == 'price_desc'){
            return $builder->orderBy('price', 'desc');
        } elseif($value == 'highest_rating'){
            $c = $builder->leftJoin('reviews', 'courses.id', '=', 'reviews.course_id')
                ->select(\DB::raw('AVG(rating) as ratings_average, courses.id, courses.title, courses.slug, courses.price, courses.image, courses.description, courses.created_at, courses.updated_at, courses.category_id, courses.user_id'))
                ->groupBy(['courses.id', 'courses.title', 'courses.slug', 'courses.created_at', 'courses.price', 'courses.image', 'courses.description', 'courses.updated_at', 'courses.category_id', 'courses.user_id'])
                ->orderBy('ratings_average', 'DESC');
                
        }
    }
    
}
