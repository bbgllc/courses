    <div class="overlapblackbg"></div>
    <div class="wsmobileheader clearfix"> 
        <a id="wsnavtoggle" class="animated-arrow">
            <span></span>
        </a> 
        <a href="/" class="smallogo">
            <img src="{{ config('site_settings.site_logo') }}" alt="">
        </a> 
        <a class="callusicon" href="">
            <span class="fa fa-phonex"></span>
        </a> 
    </div>
    
    <div class="headtoppart clearfix d-none d-xs-block d-sm-none"> <!-- only show on small devices -->
        
        <div class="headerwp">
          <div class="headertopleft">
            <div class="address clearfix">
                <span>
                    <i class="fa fa-map-marker" aria-hidden="true"></i> 
                    3982 Aspen Court, Boston, MA 02114 
                </span> 
                <a href="">
                    <i class="fa fa-phone" aria-hidden="true"></i> 123-456-7890
                </a>
            </div>
          </div>
          <div class="headertopright"> 
            @if(config('site_settings.footer_facebook'))
                  <a class="facebookicon" target="_blank" title="Facebook" href="https://www.twitter.com/{{config('site_settings.footer_facebook')}}">
                      <i aria-hidden="true" class="fa fa-facebook"></i> 
                      <span class="mobiletext02">Facebook</span>
                  </a>
            @endif
            @if(config('site_settings.footer_twitter'))
              <a class="twittericon" target="_blank" title="Twitter" href="https://www.twitter.com/{{config('site_settings.footer_twitter')}}">
                  <i aria-hidden="true" class="fa fa-twitter"></i> 
                  <span class="mobiletext02">Twitter</span>
              </a> 
            @endif
            @if(config('site_settings.footer_instagram'))
              <a class="linkedinicon" target="_blank" title="Instagram" href="https://www.twitter.com/{{config('site_settings.footer_instagram')}}">
                  <i aria-hidden="true" class="fa fa-instagram"></i> 
                  <span class="mobiletext02">Instagram</span>
              </a> 
            @endif
          </div>
        </div>
        
        
        
        
    </div>
    
    <div class="headerfull pm_buttoncolor02 blue"> 
        <!--Main Menu HTML Code-->
        <div class="wsmain">
            <div class="smllogo">
                <a href="/">
                    <img src="{{config('site_settings.site_logo')}}" style="max-width:100%;" alt="">
                </a>
            </div>
            <nav class="wsmenu clearfix">
                <ul class="mobile-sub wsmenu-list">
                    <li class="menu-arrow-1">
                    	<span class="wsmenu-click">
                    	<i class="wsmenu-arrow fa fa-angle-down"></i></span>
                    	<a href="#">
                    	    <i class="icon-list"></i>
                    	    {{ __('t.library') }}
                	    </a>
                    	<ul class="wsmenu-submenu">
                    	    @foreach($global_course_categories as $c)
                        		<li class="menu-arrow-2">
                        		    <span class="wsmenu-click02">
                        		        <i class="wsmenu-arrow fa fa-angle-down"></i>
                    		        </span>
                    		        <a href="{{ route('frontend.courses', ['category' => $c->slug] ) }}">
                    		            {{ $c->name }}
                    		            <i class="fa fa-angle-right pull-right mr-0"></i>
                		            </a>
                        			<ul class="wsmenu-submenu-sub">
                        			    @foreach($c->subCategories as $sc)
                        				    <li>
                        				        <a href="{{ route('frontend.courses', ['category' => $sc->slug] ) }}">
                        				            {{ $sc->name }}
                        				        </a>
                    				        </li>
                        				@endforeach
                        			</ul>
                        		</li>
                    		@endforeach
                        </ul>
                    </li>
                    <!--
                    <li>
                        <span class="wsmenu-click">
                            <i class="wsmenu-arrow fa fa-angle-down"></i>
                        </span>
                        <a href="#" class="navtext">
                            <span><i class="icon-list"></i></span> 
                            <span>{{ __('t.library') }}</span>
                        </a>
                        <div class="wsshoptabing wtsdepartmentmenu clearfix bg-light">
                            <div class="wsshopwp clearfix">
                                <ul class="wstabitem clearfix">
                                    @foreach($global_course_categories as $c)
                                        <li class="wsshoplink-active">
                                            <span class="wsmenu-click02">
                                                <i class="wsmenu-arrow fa fa-angle-down"></i>
                                            </span>
                                            <a href="{{ route('frontend.courses', ['category' => $c->slug] ) }}">
                                                <i class="fa fa-angle-right"></i> {{ $c->name }}
                                            </a>
                                            <div class="wstitemright clearfix wstitemrightactive" style="height: auto;">
                                                <div class="wstmegamenucoll clearfix">
                                                    <ul class="wstliststy02">
                                                        @foreach($c->subCategories as $sc)
                                                            <li>
                                                                <a href="{{ route('frontend.courses', ['category' => $sc->slug] ) }}">
                                                                    {{ $sc->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </li>
                    -->
                    <!-- search bar -->
                    <li class="wssearchbar clearfix">
                        <form class="topmenusearch" action="{{ route('frontend.courses') }}">
                            <input class="main-search-field" autocomplete="off" name="keyword" placeholder="{{ __('t.search-for-courses') }}...">
                            <button class="btnstyle" type="submit">
                                <i class="searchicon fa fa-search" aria-hidden="true"></i>
                            </button>
                        </form>
                    </li>
                    
                    <!--
                    <li class="wscarticon clearfix"> 
                        <a href="#">
                            <i class="fa fa-shopping-basket"></i> 
                            <em class="roundpoint">8</em>
                            <span class="mobiletext">Shopping Cart</span>
                        </a> 
                    </li>
                    -->
                    
                    @if(auth()->check())
                        <li class="wsshopmyaccount clearfix">
                            <span class="wsmenu-click">
                                <i class="wsmenu-arrow fa fa-angle-down"></i>
                            </span>
                            <a href="#" class="wtxaccountlink">
                                <img src="{{auth()->user()->picture}}" class="img-responsive rounded-circle profile-img" width="25" height="25" />
                                {{ $logged_in_user->name }}
                                <i class="fa  fa-angle-down"></i>
                            </a>
                            <ul class="wsmenu-submenu">
                                @can('view backend')
                                    <li>
                                        <a href="{{ route('admin.dashboard') }}">
                                            <i class="icon-speedometer"></i> 
                                            {{ __('t.administration') }}
                                        </a>
                                    </li>
                                @endcan
                                <li>
                                    <a href="{{route('frontend.user.revenue.mysales')}}">
                                        <i class="icon-wallet"></i> 
                                        {{ __('t.balance') }}: <span class="badge badge-success">
                                            {{ Gabs::currency(auth()->user()->account_balance()) }}
                                        </span>
                                    </a>
                                </li>
                                
                                <li><a href="{{ route('frontend.user.account') }}"><i class="icon-settings"></i> {{ __('t.account-settings') }}</a></li>
                                @if(auth()->user()->bookmarks()->count())
                                    <li><a href="{{route('frontend.user.wishlist')}}"><i class="icon-heart"></i> {{ __('t.my-wishlist') }}</a></li>
                                @endif
                                @if(auth()->user()->enrollments()->count())
                                    <li><a href="{{route('frontend.user.courses')}}"><i class="icon-book-open"></i> {{ __('t.my-courses') }}</a></li>
                                @endif
                                @if(auth()->user()->certificates()->count())
                                    <li><a href="{{route('frontend.user.certificates')}}"><i class="icon-badge"></i> {{ __('t.certificates') }}</a></li>
                                @endif
                                @if(auth()->user()->purchases()->count())
                                    <li><a href="{{route('frontend.user.purchases')}}"><i class="icon-credit-card"></i> {{ __('t.my-purchase-history') }}</a></li>
                                @endif
                                @if(!auth()->user()->isAdmin())
                                    <li><a href="/notifications"><i class="icon-bell"></i> {{ __('t.notifications') }}</a></li>
                                @endif
                                <li><a href="{{ route('frontend.auth.logout') }}"><i class="icon-lock"></i> {{ __('t.logout') }}</a></li>
                            </ul>
                        </li>
                    
                        <li class="wsshopmyaccount clearfix">
                            <span class="wsmenu-click">
                                <i class="wsmenu-arrow fa fa-angle-down"></i>
                            </span>
                            <a href="#" class="wtxaccountlink">
                                <i class="fa fa-align-justify"></i>{{ __('t.instructor') }} <i class="fa  fa-angle-down"></i>
                            </a>
                            <ul class="wsmenu-submenu">
                              <li><a href="{{route('frontend.author.dashboard')}}">{{ __('t.instructor-dashboard') }}</a></li>
                              <li><a href="{{route('frontend.user.revenue.mysales')}}">{{ __('t.revenue-report') }}</a></li>
                              <li><a href="{{route('frontend.author.course.create')}}"> {{ __('t.create-a-course') }}</a></li>
                            </ul>
                        </li>
                    @endif
                    
                    @guest
                        <li class="wsshopmyaccount clearfix">
                            <a href="{{route('frontend.auth.register')}}" class="wtxaccountlink">
                                <i class="icon-power"></i> {{ __('t.register') }}
                            </a>
                        </li>
                        
                        <li class="wsshopmyaccount clearfix">
                            <a href="{{route('frontend.auth.login')}}" class="wtxaccountlink">
                                <i class="icon-lock-open"></i> {{ __('t.login') }}
                            </a>
                        </li>
                    @endguest
                    
                    <li class="wsshopmyaccount clearfix">
                        <a href="{{route('frontend.blog')}}" class="wtxaccountlink">
                            <i class="icon-event"></i> {{ __('t.blog') }}
                        </a>
                    </li>
                    
                    <li class="wsshopmyaccount clearfix">
                        <a href="{{route('frontend.courses')}}" class="wtxaccountlink">
                            <i class="icon-book-open"></i> {{ __('t.courses') }}
                        </a>
                    </li>
                    
                </ul>
            </nav>
        </div>
        <!--Menu HTML Code--> 
    </div>
