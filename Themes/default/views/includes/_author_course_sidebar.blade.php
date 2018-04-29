<div class="create-course-sidebar">
    <ul class="list-bar">
        <a href="{{route('frontend.author.course.edit', $course)}}">
            <li class="{{ active_class(Active::checkUriPattern('*edit')) }}">
                <span class="count">
                    <i class="fa fa-check"></i>
                </span> {{ __('t.course-landing-page') }}
            </li>
        </a> 
        <a href="{{route('frontend.author.course.curriculum', $course)}}">
            <li class="{{ active_class(Active::checkUriPattern('*curriculum*')) }}">
                <span class="count">
                    <i class="fa fa-check"></i>
                </span> {{ __('t.course-curriculum') }}
            </li>
        </a>
        
        <a href="{{route('frontend.author.course.pricing', $course)}}">
            <li class="{{ active_class(Active::checkUriPattern('*price-and-promotion*')) }}">
                <span class="count">
                    <i class="fa fa-check"></i>
                </span> {{ __('t.pricing-and-coupons') }}
            </li>
        </a>
        
        @if($course->approvals->count())
            <a href="{{route('frontend.author.course.approval', $course)}}">
                <li class="{{ active_class(Active::checkUriPattern('*admin-approval')) }}">
                    <span class="count">
                        <i class="fa fa-check"></i>
                    </span> {{ __('t.admin-review-notes') }}
                </li>
            </a>
        @endif
        @if(!$course->published && !$course->approved)
            <button type="button" class="btn btn-block btn-info text-white" 
                data-toggle="popover" 
                data-html="true"
                data-placement="right"
                title="{{__('t.are-you-sure')}}" 
                data-content="<a href='{{route('frontend.author.submit.review', $course)}}' class='btn btn-sm btn-danger'>{{__('t.yes-submit')}}</a> <button class='btn btn-sm btn-secondary cancelPop'>{{__('t.cancel')}}</button>">
                {{ __('t.submit-for-review') }}
            </button>
        @elseif(!$course->published && $course->approved)
            <button type="button" class="btn btn-block btn-info text-white" 
                data-toggle="popover" 
                data-html="true"
                data-placement="right"
                title="{{__('t.are-you-sure')}}" 
                data-content="<a href='{{route('frontend.author.submit.review', $course)}}' class='btn btn-sm btn-danger'>{{__('t.yes-publish')}}</a> <button class='btn btn-sm btn-secondary cancelPop'>{{__('t.cancel')}}</button>">
                {{ __('t.publish-course') }}
            </button>
        @else
            <a href="{{route('frontend.author.announcements', $course)}}">
                <li class="{{ active_class(Active::checkUriPattern('*announcements*')) }}">
                    <span class="count">
                        <i class="fa fa-check"></i>
                    </span> {{ __('t.announcements') }}
                </li>
            </a>
            
            @if($course->approved && $course->canBeDeleted())
                <button type="button" class="btn btn-block btn-info text-white" 
                    data-toggle="popover" 
                    data-html="true"
                    data-placement="right"
                    title="{{__('t.are-you-sure')}}" 
                    data-content="<a href='{{route('frontend.author.submit.review', $course)}}' class='btn btn-sm btn-danger'>{{__('t.yes-unpublish')}}</a> <button class='btn btn-sm btn-secondary cancelPop'>{{__('t.cancel')}}</button>">
                    {{ __('t.unpublish-course') }}
                </button>
            @endif
		@endif
		
        @if($course->published && !$course->approved)
			<button type="button" class="btn btn-block btn-warning disabled">{{ __('t.under-review') }}</button>
        @endif
        
        @if($course->canBeDeleted())
            <button type="button" class="btn btn-block btn-danger text-white" 
                data-toggle="popover" 
                data-html="true"
                data-placement="right"
                title="{{__('t.are-you-sure')}}" 
                data-content="<a href='{{ route('frontend.author.course.destroy', $course) }}' class='btn btn-sm btn-danger'>{{__('t.yes-delete')}}</a> <button class='btn btn-sm btn-secondary cancelPop'>{{__('t.cancel')}}</button>">
                {{ __('t.delete-course') }}
            </button>
        @endif

        
        
    </ul>
</div>