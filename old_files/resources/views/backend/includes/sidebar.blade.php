<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">
                {{ __('menus.backend.sidebar.general') }}
            </li>

            <li class="nav-item">
                <a class="nav-link {{ active_class(Active::checkUriPattern('admin/dashboard')) }}" href="{{ route('admin.dashboard') }}"><i class="icon-speedometer"></i> {{ __('menus.backend.sidebar.dashboard') }}</a>
            </li>

            @if ($logged_in_user->isAdmin())
                <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('*admin/auth*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="icon-user"></i> {{ __('t.access') }}

                        @if ($pending_approval > 0)
                            <span class="badge badge-danger">{{ $pending_approval }}</span>
                        @endif
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/auth/user*')) }}" href="{{ route('admin.auth.user.index') }}">
                                <i class="icon-options"></i> 
                                {{ __('t.user-management') }}

                                @if ($pending_approval > 0)
                                    <span class="badge badge-danger">{{ $pending_approval }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/auth/role*')) }}" href="{{ route('admin.auth.role.index') }}">
                                <i class="icon-options"></i> 
                                {{ __('t.role-management') }}
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('*admin/course*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="icon-graduation"></i> {{ __('t.courses') }}
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/course/categories*')) }}" href="{{ route('admin.course.categories') }}">
                                <i class="icon-options"></i> 
                                {{ __('t.categories') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/course/courses*')) }}" href="{{ route('admin.course.courses') }}">
                                <i class="icon-options"></i> 
                                {{ __('t.courses') }}
                            </a>
                        </li>
                        <!--
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/course/featured_courses*')) }}" href="{{ route('admin.auth.user.index') }}">
                                <i class="icon-options"></i> 
                                {{ __('t.featured-courses') }}
                            </a>
                        </li>
                        -->
                    </ul>
                </li>
                
                <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('*admin/blog*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="icon-notebook"></i> {{ __('t.blog-management') }}
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/blog/categories*')) }}" href="{{ route('admin.blog.categories') }}">
                                <i class="icon-options"></i> 
                                {{ __('t.categories') }}
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/blog/posts*')) }}" href="{{ route('admin.blog.posts') }}">
                                <i class="icon-options"></i> 
                                {{ __('t.posts') }}
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/messages*')) }}" href="{{ route('admin.messages.index') }}">
                        <i class="icon-envelope"></i> 
                        {{ __('t.system-messaging') }}
                    </a>
                </li>
                
                <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('*admin/finance*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="icon-credit-card"></i> {{ __('t.finance-and-promo') }}
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/finance/coupons*')) }}" href="{{ route('admin.finance.coupons') }}">
                                <i class="icon-options"></i> 
                                {{ __('t.discount-coupons') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/finance/withdrawals*')) }}" href="{{ route('admin.finance.withdrawals') }}">
                                <i class="icon-options"></i> 
                                {{ __('t.withdrawals') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/finance/transactions*')) }}" href="{{ route('admin.finance.transactions') }}">
                                <i class="icon-options"></i> 
                                {{ __('t.transactions') }}
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('*admin/settings*'), 'open') }}">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="icon-settings"></i> {{ __('t.settings') }}
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/settings/site-settings*')) }}" href="{{ route('admin.settings') }}">
                                <i class="icon-options"></i> 
                                {{ __('t.site-settings') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('*admin/settings/env*')) }}" href="/admin/settings/env">
                                <i class="icon-options"></i> 
                                {{ __('t.environment') }}
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="nav-item nav-dropdown {{ active_class(Active::checkUriPattern('admin/log-viewer*'), 'open') }}">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="icon-list"></i> {{ __('menus.backend.log-viewer.main') }}
                </a>

                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link {{ active_class(Active::checkUriPattern('admin/log-viewer')) }}" href="{{ route('log-viewer::dashboard') }}">
                            <i class="icon-options"></i> 
                            {{ __('menus.backend.log-viewer.dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ active_class(Active::checkUriPattern('admin/log-viewer/logs*')) }}" href="{{ route('log-viewer::logs.list') }}">
                            <i class="icon-options"></i> 
                            {{ __('menus.backend.log-viewer.logs') }}
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div><!--sidebar-->