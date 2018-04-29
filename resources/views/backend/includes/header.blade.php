<header class="app-header navbar bg-dark">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">☰</button>
    <a class="navbar-brand" href="#">
        <img src="{{config('site_settings.site_logo')}}" style="max-width:100%"/>
    </a>
    <button class="navbar-toggler sidebar-minimizer d-md-down-none" type="button">☰</button>

    <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item px-3">
            <a class="nav-link" href="{{ route('frontend.index') }}"><i class="icon-home"></i></a>
        </li>

        <li class="nav-item px-3">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">{{ __('navs.frontend.dashboard') }}</a>
        </li>
        
        <li class="nav-item px-3 dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                {{LaravelLocalization::getCurrentLocaleName()}}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a class="dropdown-item" hreflang="{{ $localeCode }}" 
                        href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                        {{ $properties['native'] }}
                    </a>
                @endforeach
                
            </div>
        </li>
        
    </ul>

    <ul class="nav navbar-nav ml-auto mr-4">
        <!--
        <li class="nav-item d-md-down-none">
            <a class="nav-link navbar-toggler aside-menu-toggler" href="#">
                <i class="icon-bell text-white"></i><span class="badge badge-pill badge-danger">0</span>
            </a>
        </li>
        -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <img src="{{ $logged_in_user->picture }}" class="img-avatar" alt="{{ $logged_in_user->email }}">
                <span class="d-md-down-none">{{ $logged_in_user->full_name }}</span>
            </a>
            
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('frontend.auth.logout') }}"><i class="fa fa-lock"></i> {{ __('navs.general.logout') }}</a>
            </div>
        </li>
    </ul>
    <!--
    <button class="navbar-toggler aside-menu-toggler" type="button">☰</button>
    -->
</header>