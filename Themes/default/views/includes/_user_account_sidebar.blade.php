<div class="create-course-sidebar card card-body bg-light">
    <div class="text-center mb-2">
        <img src="{{auth()->user()->picture}}" width="100" class="rounded-circle profile-img" />
        <p class="mb-0 font-weight-bold">
            <a href="{{route('frontend.user', auth()->user()->username)}}">{{ auth()->user()->name }}</a>
        </p>
        <p class="text-muted mb-0">
            {{ auth()->user()->tagline }}
        </p>
    </div>
    
    <ul class="list-bar">
        <a href="/account">
            <li class="{{ active_class(Active::checkUriPattern('*account'))}}">
                <span class="count">
                    <i class="fa fa-check"></i>
                </span> {{ __('t.profile') }}
            </li>
        </a> 
        <a href="/account/security">
            <li class="{{ active_class(Active::checkUriPattern('*account/security'))}}">
                <span class="count">
                    <i class="fa fa-check"></i>
                </span> {{ __('t.account-security') }}
            </li>
        </a>
        <a href="/settings">
            <li class="{{ active_class(Active::checkUriPattern('*settings'))}}">
                <span class="count">
                    <i class="fa fa-check"></i>
                </span> {{ __('t.settings') }}
            </li>
        </a>
        
        <a href="/notifications">
            <li class="{{ active_class(Active::checkUriPattern('*notifications'))}}">
                <span class="count">
                    <i class="fa fa-check"></i>
                </span> {{ __('t.notifications') }}
            </li>
        </a> 
        
        
    </ul>
</div>