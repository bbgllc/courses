<?php

namespace App\Filters\Course;

use App\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class AccessFilter extends FilterAbstract
{
    
    /**
     * Filter by course access Free or Paid.
     *
     * @param  string $access
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function mappings()
    {
        return [
            'free' => 'free',
            'premium' => 'premium',
        ];
    }
    
    public function filter(Builder $builder, $value)
    {
        $value = $this->resolveFilterValue($value);

        if ($value === null) {
            return $builder;
        }
        
        if($value == 'free'){   
            return $builder->where('price', 0);
        } else {
            return $builder->where('price', '>', 0);
        }
    }
}
