<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    use Sluggable;
    
    public $timestamps = false;
    
    protected $fillable = [
        'title', 'intro', 'body', 'slug', 'meta_description'
    ];
    
    
    
    
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    
}
