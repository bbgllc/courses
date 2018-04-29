<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Filters\Course\CourseFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;

class Course extends Model
{

    protected $fillable = [
    	'user_id', 'category_id', 'title', 'subtitle', 'slug', 'description', 'language', 'image', 'level',
    	'featured', 'price', 'published', 'approved'
    ];

    protected $appends = [
        'total_hours', 'discount_percent', 'average_rating', 
        'status_code', 'total_articles', 'total_attachments', 'access', 
        'total_quizzes', 'cover_image', 'selling_price', 
        'sales_this_month', 'final_price',
        'total_sales', 'thumbnail'
    ];
    
    
    
    public function scopeFilter(Builder $builder, Request $request, array $filters = [])
    {
        return (new CourseFilters(request()))->add($filters)->filter($builder);
    }


    public function category()
    {
    	return $this->belongsTo(Category::class);
    }
    

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    
    public function approvals()
    {
    	//return $this->hasMany(Approval::class);
        return $this->morphMany(Approval::class, 'approvable');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')->withTimestamps();
    }
    
    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'course_id', 'user_id')->withTimestamps();
    }
    
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    
    public function announcements()
    {
        return $this->belongsToMany(Announcement::class);
    }
    
    /* 
		appended attributes
    */
    public function getSalesThisMonthAttribute()
    {
 
        $now = \Carbon\Carbon::now();
        $sales_earnings_this_month = Payment::where('course_id', $this->id)
                                        ->whereBetween('created_at', [$now->startOfMonth() , $now->copy()->endOfMonth()])
                                        ->sum('author_earning');
                                        
        return $sales_earnings_this_month;
    }
    
    public function getTotalSalesAttribute()
    {
        return Payment::where('course_id', $this->id)->sum('author_earning');
    }
    
	public function getTotalHoursAttribute()
    {
        $q = \DB::table('sections')
            ->join('lessons', 'sections.id', '=', 'lessons.section_id')
            ->join('contents', 'lessons.id', '=', 'contents.lesson_id')
            ->where('contents.content_type', '=', 'video')
            ->where('sections.course_id', '=', $this->id)
            ->sum('contents.video_duration');
        $duration = round( $q/60, 1);    
        
        return $duration;
    }
    
    public function getAccessAttribute()
    {
        return $this->price == 0 ? 'Free' : 'Premium';
    }
    
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'),1);
    }
    
    public function getTotalAttachmentsAttribute()
    {
        $q = \DB::table('sections')
            ->join('lessons', 'sections.id', '=', 'lessons.section_id')
            ->join('attachments', 'lessons.id', '=', 'attachments.model_id')
            ->where('sections.course_id', '=', $this->id)
            ->count();
        
        return $q;
    }

	public function getTotalQuizzesAttribute()
    {
        $q = \DB::table('sections')
            ->join('lessons', 'sections.id', '=', 'lessons.section_id')
            ->where('lessons.lesson_type', '=', 'quiz')
            ->where('sections.course_id', '=', $this->id)
            ->count();
        
        return $q;
    }
    
    public function getTotalArticlesAttribute()
    {
        $q = \DB::table('sections')
            ->join('lessons', 'sections.id', '=', 'lessons.section_id')
            ->join('contents', 'lessons.id', '=', 'contents.lesson_id')
            ->where('contents.content_type', '=', 'article')
            ->where('sections.course_id', '=', $this->id)
            ->count();
        
        return $q;
    }
    
    public function getCoverImageAttribute()
    {
        if($this->image){
            return '/uploads/images/course/'.$this->image;
        } else {
            return '/uploads/images/defaults/cover.jpg';
        }
    }
    
    public function getThumbnailAttribute()
    {
        if($this->image){
            if(\Storage::disk('server')->exists('images/course/thumbnails/'.$this->image)){
                return '/uploads/images/course/thumbnails/'.$this->image;
            } else {
                return '/uploads/images/course/'.$this->image;
            }
        } else {
            return '/uploads/images/defaults/cover.jpg';
        }
    }
    
    public function getFinalPriceAttribute()
    {
        $coupon = Coupon::where(['sitewide' => true, 'active' => true])
                    ->where('expires', '>=', \Carbon\Carbon::now())
                    ->first();
        
        if($this->price > 0){
            if(!is_null($coupon)){
                $price = round($this->price - (($coupon->percent / 100) * $this->price), 1);
                return $price;
            } else {
                return $this->price;
                
            }
            
            
        } else {
            return __('t.free');
        }
    }
    
    public function getDiscountPercentAttribute()
    {
        $coupon = Coupon::where(['sitewide' => true, 'active' => true])
                    ->where('expires', '>=', \Carbon\Carbon::now())
                    ->first();
        
        if(!is_null($this->price) && $this->price > 0 && !is_null($coupon)){
            return $coupon->percent;
        } else {
            return null;
        }
    }
    
    public function getSellingPriceAttribute()
    {
        if(!is_null($this->price) && $this->price > 0){
            return $this->price;
        } else {
            return null;
        }
    }
    
    public function getStatusCodeAttribute()
    {
        if($this->approved && $this->published){
            return 'Live';
        } elseif($this->approved && !$this->published){
            return 'Unpublished';
        } elseif(!$this->approved && $this->published){
            return 'Under review';
        } elseif(!$this->approved && !$this->published){
            return 'Draft';
        }
    }
    
    // use slug in urls instead of id
    public function getRouteKeyName()
    {
        return 'slug';
    }


    /* Traits */

    public function canBeDeleted()
    {
        return (bool)(! $this->students->count() && !Certificate::where('course_id', $this->id)->count());
    }
    
    public function status()
    {
        if($this->approved && $this->published){
            return '<span class="badge badge-success">Live</span>';
        } elseif($this->approved && !$this->published){
            return '<span class="badge badge-danger">'. __('t.unpublished-by-author') .'</span>';
        } elseif(!$this->approved && $this->published){
            return '<span class="badge badge-warning">'. __('t.awaiting-admin-approval') .'</span>';
        } elseif(!$this->approved && !$this->published){
            return '<span class="badge badge-info">'. __('t.draft-course') .'</span>';
        }
    }
    
    
    

}

