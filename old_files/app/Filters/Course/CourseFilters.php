<?php

namespace App\Filters\Course;

use App\Models\Course;
use App\Models\Category;
use App\Filters\FiltersAbstract;
use App\Filters\Course\CategoryFilter;
use App\Filters\Course\AccessFilter;
use App\Filters\Course\SearchFilter;
use App\Filters\Course\LanguageFilter;
use App\Filters\Course\Ordering\SortOrder;
use App\Filters\Course\DifficultyFilter;

use Illuminate\Database\Eloquent\Builder;

class CourseFilters extends FiltersAbstract
{
    /**
     * Default course filters.
     *
     * @var array
     */
    protected $filters = [
        'category' => CategoryFilter::class,
        'access' => AccessFilter::class,
        'difficulty' => DifficultyFilter::class,
        'price' => PriceOrder::class,
        'language' => LanguageFilter::class,
        'sort_order' => SortOrder::class,
        'keyword' => SearchFilter::class
    ];

    public static function mappings()
    {

        $map = [
            'access' => [
                'free' => trans('t.free'),
                'premium' => trans('t.premium')
            ],
            
            'difficulty' => [
                'beginner' => trans('t.beginner-level'),
                'intermediate' => trans('t.intermediate-level'),
                'advanced' => trans('t.advanced-level')
            ],
            
            'category' => Category::whereNotNull('parent_id')
                ->whereHas('courses', function($q){
                    $q->where('published', true)
                        ->where('approved', true);
                })->orderBy('name')->get()->pluck('name', 'slug')
                ->toArray(),
                
            'language' => Course::all()->pluck('language', 'language')

        ];

        return $map;
    }

    
}
