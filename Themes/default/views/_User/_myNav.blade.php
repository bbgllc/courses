<li class="nav-item">
    <a href="{{route('frontend.user.courses')}}" class="nav-link small font-weight-bold text-uppercase {{ active_class(Active::checkUriPattern('*my-courses/learning'))}}">
        {{ __('t.my-enrolled-courses') }}
    </a>
</li>
@if(auth()->user()->bookmarks()->count())
    <li class="nav-item">
        <a href="{{route('frontend.user.wishlist')}}" class="nav-link small font-weight-bold text-uppercase {{ active_class(Active::checkUriPattern('*my-courses/wishlist'))}}">
            {{ __('t.my-wishlist') }}   
        </a>
    </li>
@endif
@if(auth()->user()->purchases()->count())
    <li class="nav-item">
        <a href="{{route('frontend.user.purchases')}}" class="nav-link small font-weight-bold text-uppercase {{ active_class(Active::checkUriPattern('*my-courses/purchases'))}}">
            {{ __('t.my-purchase-history') }}   
        </a>
    </li>
@endif
@if(auth()->user()->certificates()->count())
    <li class="nav-item">
        <a href="{{route('frontend.user.certificates')}}" class="nav-link small font-weight-bold text-uppercase {{ active_class(Active::checkUriPattern('*my-courses/certificates'))}}">
            {{ __('t.my-certificates') }}   
        </a>
    </li>
@endif