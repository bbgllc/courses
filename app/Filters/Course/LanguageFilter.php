<?php

namespace App\Filters\Course;

use App\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class LanguageFilter extends FilterAbstract
{
    
    /**
     * Filter by course language of instruction.
     *
     * @param  string $language
     * @return Illuminate\Database\Eloquent\Builder
     */
     
    public function filter(Builder $builder, $value)
    {
        return $builder->where('language', $value);
    }
}
