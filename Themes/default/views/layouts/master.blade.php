<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <!--
        <meta charset="utf-8">
        -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        <meta name="csrf-token" content="{{ csrf_token() }}"/>
        <meta name="description" content="@yield('description', config('site_settings.site_description'))">
        <meta name="keywords" content="{{config('site_settings.site_keywords') }}">
        
        <title>
            @yield('title', app_name())
        </title>
        
        <link rel="icon" href="{{ config('site_settings.site_favicon') }}" type="image/x-icon" />
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
        <!-- Styles -->
        <link rel="stylesheet" href="{{ themes('css/vendor/bootstrap.min.css') }}"/>
        <link rel="stylesheet" href="{{ themes('css/vendor/semanticui-dropdown.css') }}"/>
        <link rel="stylesheet" href="{{ themes('css/vendor/semanticui-transition.css') }}"/>
        <link rel="stylesheet" href="{{ themes('css/vendor/bootstrap-float-label.css') }}"/>
       
        <!-- styles loaded from the specific theme -->
        <link rel="stylesheet" href="{{ themes('css/vendor/fontawesome-all.min.css') }}" media="all" />
        <link rel="stylesheet" href="{{ themes('css/overrides.css') }}" media="all" />
        <link rel="stylesheet" href="{{ themes('css/fox.css') }}" media="all" />
        <link rel="stylesheet" href="{{ themes('css/app.css') }}">
        <link rel="stylesheet" href="{{ themes('css/nav.css') }}" media="all" />
        <link rel="stylesheet" href="/css/typeahead.css" media="all" />
        <link rel="stylesheet" href="/css/jquery-sticky-alert.css" media="all" />
        <link rel="stylesheet" href="/public/css/frontend.css" media="all" />
        <link rel="stylesheet" href="{{ themes('css/vendor/simple-line-icons.css') }}" />
        @yield('after-styles')
        <link rel="stylesheet" href="{{ themes('css/custom.css') }}" media="all" />
        <style type="text/css">
            body{
                background-color: #f7f8fa;
                background: url('/img/bg.png') repeat;
            }
            .btn:focus{
                outline: none !important;
                box-shadow:none !important;
            }
        </style>
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
        
        <script>
            //See https://laracasts.com/discuss/channels/vue/use-trans-in-vuejs
            window.trans = @php
                // copy all translations from /resources/lang/CURRENT_LOCALE/* to global JS variable
                $lang_files = File::files(resource_path() . '/lang/' . App::getLocale());
                $trans = [];
                foreach ($lang_files as $f) {
                    $filename = pathinfo($f)['filename'];
                    $trans[$filename] = trans($filename);
                }
                //$trans['adminlte_lang_message'] = trans('adminlte_lang::message');
                echo json_encode($trans);
            @endphp
            
            window.stg = @php
                $settinigs = [
                    'video_allow_upload' => config('site_settings.video_allow_upload'),
                    'video_allow_youtube' => config('site_settings.video_allow_youtube'),
                    'video_allow_vimeo' => config('site_settings.video_allow_vimeo'),
                    'vms' => config('site_settings.video_max_size'),
                    'enable_demo' => config('settings.enable_demo'),
                    'site_name' => config('site_settings.site_name'),
                    'img' => config('site_settings.site_logo'),
                    'rzk' => config('services.razorpay.key'),
                ];
                
                echo json_encode($settinigs);
            @endphp
        </script>
        
    </head>
    
    
    
    <body>
        @include('includes.partials.ga')
        
        <div class="wsmenucontainer clearfix">
            <div id="app">
                <!-- START THEME-SPECIFIC LAYOUTS HERE -->
                
                <div id="main" class="main clearfix">
                    <div id="alert-container" class="text-center mb-0 d-none d-sm-block" role="alert"></div>
                    
                    <div class="alert-messages">
                        @include('includes.partials.messages')
                    </div>
                    
                    @include('includes.partials.logged-in-as')
                    
                    <!-- Navigation -->
                    @include('includes.navigation')
                    
                    <!-- Content -->
                    @yield('content')
                    
                </div><!--/ End Main -->
                
            </div>    
                
            <!-- Begin Footer -->
            
            <footer id="footer" class="footer footer-main">
                <div class="container">
                    <div class="row text-center">
                        
                        <div class="col-md-12 mb-4">
            				<ul class="list-inline">
            				    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <li class="list-inline-item">
    	        			            <a class="link-footer lang {{app()->getLocale() == $localeCode ? 'active' : '' }}" rel="alternate" hreflang="{{ $localeCode }}" 
    	        			                href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
    	        			                <img src="/img/flags/{{$localeCode}}.svg" width="12" class="rounded-circle" /> 
    	        			                {{-- $properties['name'] --}}
    	        			                {{ __('menus.language-picker.langs.'.$localeCode) }}
            			                </a>
            			            </li>
                                @endforeach
                                
            				</ul>
            			</div>
            			
                        <div class="col-md-12">
            				<a href="/">
            					<img src="{{config('site_settings.site_logo')}}" width="90" class="footer-logo" />
            				</a>
            			    <p class="desc-footer">
            			        {{ __('t.footer-text', ['site' => config('site_settings.site_name')])}}   
            			    </p>
            			</div>
            			
            			<div class="col-md-12 margin-tp-xs">
            			    <!--
    				        <strong class="number-shots">510</strong> Shots - 
        				    <strong class="number-shots">1.4k</strong> Designers
        				    -->
            				<ul class="list-inline">
            				    @if(config('site_settings.footer_twitter'))
							        <li class="list-inline-item">
							            <a href="https://twitter.com/{{config('site_settings.footer_twitter')}}" target="_blank" class="ico-social">
							            <i class="fa fa-twitter-square"></i></a>
						            </li>
					            @endif
					            @if(config('site_settings.footer_facebook'))
		    					    <li class="list-inline-item">
		    					        <a href="https://facebook.com/{{config('site_settings.footer_facebook')}}" target="_blank" class="ico-social">
	    					            <i class="fa fa-facebook-square"></i></a>
    					            </li>
					            @endif
					            @if(config('site_settings.footer_instagram'))
    					            <li class="list-inline-item">
		    					        <a href="https://instagram.com/{{config('site_settings.footer_instagram')}}" target="_blank" class="ico-social">
	    					            <i class="fa fa-instagram"></i></a>
    					            </li>
					            @endif
				            </ul>
            			</div>
            			
            			
            			
            			<div class="col-md-12">
            				<ul class="list-inline">
            				    @foreach($footer_pages as $f_page)
		        			        <li class="list-inline-item">
		        			            <a class="link-footer" href="{{ route('frontend.page.show', $f_page->slug) }}">
		        			                {{ $f_page->title }}
	        			                </a>
	        			            </li>
	        			        @endforeach
        	        			<li class="list-inline-item">
        	        			    <a class="link-footer" href="{{ route('frontend.blog') }}">
        	        			        {{ __('t.blog') }}
        	        			    </a>
    	        			    </li>
    	        			    <li class="list-inline-item">
        	        			    <a class="link-footer" href="/contact">
        	        			        {{ __('t.contact-us') }}
        	        			    </a>
    	        			    </li>
    	        			    <li class="list-inline-item">
        	        			    <a class="link-footer" href="{{ route('frontend.verify.certificate') }}">
        	        			        {{ __('t.verify-certificate') }}
        	        			    </a>
    	        			    </li>
            				</ul>
            			</div>
            			
            			
            			
            			
            			<div class="col-md-12">
            				<p>© {{ config('site_settings.site_name') }} - {{ __('t.all-right-reserved') }} - {{ \Carbon\Carbon::now()->year }}</p>
            			</div>
            			
                    </div>
                </div>
            </footer>
            
            
            <!--/ End Footer -->
                
                
                
            
            
            <!-- Scripts -->
            @stack('before-scripts')
            <script src="/js/frontend.js"></script>
            <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script>
              
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
            
            <script src="{{ themes('js/nav.js') }}"></script>
           
            <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/components/dropdown.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/components/transition.min.js"></script>
            <script src="/js/typeahead.js"></script>
            <script src="/js/jquery-sticky-alert.js"></script>
            
            <script>
                // fadeout notification bar
                $(document).ready(function() {
            		setTimeout(function() {
            			$('.alert-messages').fadeOut("slow", function(){
            				$(this).remove();
            			})
            		}, 4500);
                });
                
                
                $('.ui.dropdown').dropdown();
                
                $('body').on('mouseenter mouseleave','.dropdown',function(e){
                  var _d=$(e.target).closest('.dropdown');_d.addClass('show');
                  setTimeout(function(){
                    _d[_d.is(':hover')?'addClass':'removeClass']('show');
                  },300);
                });
                
                $(function () {
                  $('[data-toggle="tooltip"]').tooltip()
                })
                
                
            </script>
            
            
            <!-- Search autocomplete-->
            <script>
        	    // courses
                var courses = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: '/api/search/courses?search=%QUERY%',
                        wildcard: '%QUERY%',
                        cache:false
                    },
                    
                });
                
                // authors
                var authors = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: '/api/search/authors?search=%QUERY%',
                        wildcard: '%QUERY%',
                        cache:false
                    },
                    
                });
                
                $('.main-search-field').typeahead('destroy');
                
                $('.main-search-field').typeahead(
                    {
                        hint: false,
                        highlight: true,
                        minLength: 1
                    }, 
                    {
                        name: 'courses',
                        display: 'title',
                        //limit: 6,
                        source: courses,
                        templates: {
                            header: '<h5 class="result-title">{{__("t.courses")}}</h5>',
                            suggestion: function(el){
                                return  '<a href="/course/'+el.slug + '">' +
                                            '<div class="tt-suggestion tt-selectable media">'+
                                                '<div class="pull-left">' +
                                                        '<img src="'+el.thumbnail+'" class="media-object rounded mr-2" style="width:40px">'+
                                                '</div>'+
                                                '<div class="media-body">'+
                                                    '<h4 class="media-heading">'+el.title+'</h4>'+
                                                    '<p class="desc">'+el.subtitle+'</p>'+
                                                '</div>'+
                                            '</div>' +
                                        '</a>'
                            }
            
                        },
                        
                    },
                   
                    {
                        name: 'authors',
                        display: 'name',
                        //limit: 4,
                        source: authors,
                        templates: {
                            header: '<h5 class="result-title">{{__("t.authors")}}</h5>',
                            suggestion: function(el){
                                 return '<a href="/user/'+el.username + '">' +
                                            '<div class="tt-suggestion tt-selectable media">'+
                                                '<div class="pull-left">' +
                                                    '<img src="'+el.picture+'" class="media-object rounded mr-2" style="width:40px">'+
                                                '</div>'+
                                                '<div class="media-body">'+
                                                    '<h4 class="media-heading">'+el.name+'</h4>'+
                                                    '<p class="desc">'+el.tagline+'</p>'+
                                                '</div>'+
                                            '</div>'+
                                        '</a>'
                            }
                        }
                    }
                );
                
        	</script>
        	
        	@if(!is_null($global_coupon))
        	    <script type="text/javascript">
        	        // notification bar
                    $(document).ready(function() {
                    	$('#alert-container').stickyalert({
                    		barColor: '#330200', // alert background color
                    		barFontColor: '#FFF', // text font color
                    		barFontSize: '1rem', // text font size
                    		barText: 'Get <span class="font-weight-bold text-warning">{{$global_coupon->percent}}% OFF</span> all courses. Offer ends <span class="font-weight-bold text-warning">{{ $global_coupon->expires->format("d-F-Y") }}.</span> Browse our Library now.', // the text to display, linked with barTextLink
                    		barTextLink: "{{route('frontend.courses')}}", // url for anchor
                    		cookieRememberDays: '1', // in days
                    		displayDelay: '0' // in milliseconds, 3 second default
                    	});
                    });
        	    </script>
        	@endif
            
            @stack('after-scripts')
            @include('includes.partials.ga')
        </div>
    </body>
</html>
