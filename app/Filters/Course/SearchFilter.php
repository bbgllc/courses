<?php

namespace App\Filters\Course;

use App\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter extends FilterAbstract
{

    
    public function filter(Builder $builder, $value)
    {

        return $builder->where(function ($q) use ($value) {
                        $q->where('title', 'like', "%$value%")
                          ->orWhere('description', 'like', "%$value%")
                          ->orWhereHas('author', function($q) use ($value){
        	                    $q->where('first_name', 'like', "%$value%")
        	                      ->orWhere('last_name', 'like', "%$value%")
        	                      ->orWhere('username', 'like', "%$value%")
        	                      ->orWhere(\DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', "%".$value."%");
        	                });
                    });
    }
}
