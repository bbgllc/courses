<ul>
    <li class="{{ active_class(Active::checkUriPattern('*course/'.$course->slug)) }}">
        <a href="{{route('frontend.course.show', $course)}}">
            {{ __('t.overview') }}   
        </a>
    </li>
    <li class="{{ active_class(Active::checkUriPattern('*content*')) }}">
        <a href="{{route('frontend.course.content', $course)}}">
            {{ __('t.course-content') }}
        </a>
    </li>
    <li class="{{ active_class(Active::checkUriPattern('*question*')) }}">
        <a href="{{route('frontend.user.questions.index', $course)}}">
            {{ __('t.q-and-a') }}
        </a>
    </li>
    <li class="{{ active_class(Active::checkUriPattern('*announcement*')) }}">
        <a href="{{route('frontend.user.announcements.index', $course)}}">
            {{ __('t.announcements') }}
        </a>
    </li>
</ul>
