<?php

namespace App\Models\Auth\Traits\Relationship;


use App\Models\Withdrawal;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\Review;
use App\Models\Payment;
use App\Models\QuizAttempt;
use App\Models\Transaction;
use App\Models\System\Session;
use App\Models\Auth\SocialAccount;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * @return mixed
     */
    public function providers()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * @return mixed
     */
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
    
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    
    public function enrollments()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id')->withTimestamps();
    }
    
    public function completions()
    {
        return $this->belongsToMany(Lesson::class, 'completions', 'user_id', 'lesson_id')->withTimestamps();
    }
    
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'user_id');
    }
    
    public function bookmarks()
    {
        return $this->belongsToMany(Course::class, 'bookmarks', 'user_id', 'course_id')->withTimestamps();
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    

    
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
    
    public function purchases()
    {
        return $this->hasMany(Payment::class, 'payer_id');
    }
    
    public function follows()
    {
        return $this->hasMany(Follow::class);
    }
    
    public function total_students()
    {
        
        $my_courses = $this->courses;
        foreach($my_courses as $c){
            $c->enrollment_count = $c->students->count();
        }
        return $my_courses->sum('enrollment_count');
        
    }
    
    public function average_rating()
    {
        $rating = \DB::table('reviews')
                    ->join('courses', 'reviews.course_id', '=', 'courses.id')
                    ->join('users', 'courses.user_id', '=', 'users.id')
                    ->where('users.id', $this->id)
                    ->avg('reviews.rating');
                    
        return round($rating, 1);
    }
    
    public function total_reviews()
    {
        $reviews = \DB::table('reviews')
                    ->join('courses', 'reviews.course_id', '=', 'courses.id')
                    ->join('users', 'courses.user_id', '=', 'users.id')
                    ->where('users.id', $this->id)
                    ->count();
                    
        return $reviews;
    }
    
    public function total_earnings()
    {
        $user_courses = Course::where('user_id', $this->id)->pluck('id');
        
        if(count($user_courses)){
            $total_earned = Payment::whereIn('course_id', $user_courses)->sum('author_earning');   
        } else {
            $total_earned = 0.0;
        }
        
        return (float)$total_earned;
    }
    
    public function sales_this_month()
    {
        $user_courses = $this->courses()->pluck('id');
        
        $now = \Carbon\Carbon::now();
        $sales_earnings_this_month = Payment::whereIn('course_id', $user_courses)
                                        ->whereBetween('created_at', [$now->startOfMonth() , $now->copy()->endOfMonth()])
                                        ->sum('author_earning');
                                        
        return $sales_earnings_this_month;
    }
    
    
    public function total_affiliate_earnings()
    {
        
        $total_earned = Payment::where('referred_by', $this->id)->sum('affiliate_earning');
        
        return $total_earned;
    }
    
    public function total_withdrawals()
    {
        return $this->withdrawals()->where('status', 'processed')->sum('amount');
    }

    
    public function account_balance()
    {
        return $this->transactions()->sum('amount');
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
