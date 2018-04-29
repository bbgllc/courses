<?php

namespace App\Models\Auth\Traits\Method;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Coupon;
use App\Models\Payment;
/**
 * Trait UserMethod.
 */
trait UserMethod
{
    /**
     * @return mixed
     */
    public function canChangeEmail()
    {
        return config('access.users.change_email');
    }

    /**
     * @return bool
     */
    public function canChangePassword()
    {
        return ! app('session')->has(config('access.socialite_session_name'));
    }

    /**
     * @param bool $size
     *
     * @return mixed
     */
    public function getPicture($size = false)
    {
        switch ($this->avatar_type) {
            case 'gravatar':
                if (! $size) {
                    $size = config('gravatar.default.size');
                }

                return gravatar()->get($this->email, ['size' => $size]);

            case 'storage':
                return url($this->avatar_location);
        }

        $social_avatar = $this->providers()->where('provider', $this->avatar_type)->first();
        if ($social_avatar && strlen($social_avatar->avatar)) {
            return $social_avatar->avatar;
        }

        return false;
    }

    /**
     * @param $provider
     *
     * @return bool
     */
    public function hasProvider($provider)
    {
        foreach ($this->providers as $p) {
            if ($p->provider == $provider) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function isAdmin()
    {
        return $this->hasRole(config('access.users.admin_role'));
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active == 1;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirmed == 1;
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return config('access.users.requires_approval') && $this->confirmed == 0;
    }
    
    
    public function canReviewCourse($course)
    {
        return $this->id != $course->user_id && !(bool)$this->reviews->where('course_id', $course->id)->count()  && (bool)$this->enrollments()->where('course_id', $course->id)->count();
    }
    
    public function canAccessCourse($course)
    {

        return (bool)$this->enrollments()->where('course_id', $course->id)->count() 
                || (bool)$this->courses()->where('id', $course->id)->count()
                || (bool)$this->hasRole('Administrator');
            
    }
    
    public function hasCompletedLesson($lesson)
    {
        return (bool)$this->completions()->where('lessons.id', $lesson->id)->count();
    }
    
    // check if user has completed this course
    public function hasCompletedCourse($course)
    {
        return (bool)$this->certificates()->where('course_id', $course->id)->count();
    }
    
    public function hasBookmarkedCourse($course)
    {
        return (bool)$this->bookmarks()->where('courses.id', $course->id)->count();
    }
    
    public function isFollowingQuestion($question)
    {
        return (bool)$question->follows()->where('user_id', $this->id)->count();
    }
    
    public function percentCompleted($course)
    {
        $sections = $course->sections->pluck('id');
        $total_lessons = Lesson::whereIn('section_id', $sections)->where('lesson_type', 'lecture')->count();
        $lessons_array = Lesson::whereIn('section_id', $sections)->where('lesson_type', 'lecture')->pluck('id');
        $user_completed = $this->completions()->whereIn('lessons.id', $lessons_array)->count();
        
        if($total_lessons > 0){
            $percent_completed = ($user_completed/$total_lessons)*100;
        } else {
            $percent_completed = 0;
        }
        
        return (int)$percent_completed;
    }
    
    public function percentSectionCompleted($section)
    {
        $total_lessons = $section->lessons->count();
        $total_lessons_completed_by_user = $this->completions()->where('section_id', $section->id)->count();
        if($total_lessons > 0){
            $pcnt = ($total_lessons_completed_by_user / $total_lessons) * 100;
            $x = round($pcnt/5) * 5;
        } else {
            $x = 0;
        }
        
        return $x;
    }
    
    public function isAuthor()
    {
        return (bool)$this->courses->count();
    }
    
    
    
    
    
}
