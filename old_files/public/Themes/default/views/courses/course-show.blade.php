@extends('layouts.master')

@section('title', app_name() . ' | ' . $course->title )

@section('after-styles')

    <link href="//vjs.zencdn.net/5.19/video-js.min.css" rel="stylesheet">
@stop

@section('content')
    
    
    <div class="jumbotron jumbotron-fluid text-left paral-title paralsec">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="display-3">{{ $course->title }}</h1>
                    <p class="lead">{{ $course->subtitle }}</p>
                    <p>
                        
                        <stars :rating="{{$course->average_rating}}" size="14"></stars>
                        <span>
                            {{ __('t.average-from') }} {{$course->reviews->count()}} {{str_plural(__('t.review'), $course->reviews->count())}} &nbsp;<small><i class="fa fa-users"></i></small> 
                            {{ $course->students->count() }} enrolled {{ str_plural(__('trans.student'), $course->students->count()) }}
                        </span>
                    </p>
                    <p style="margin-bottom: 0px; font-size: 14px;">
                        {{ __('t.created-by') }}
                        <a href="{{ route('frontend.user', $course->author->username) }}">{{ $course->author->name }}</a>
                        
                        &nbsp; 
                        
                        <i class="fa fa-globe"></i> {{ $course->language }}
                        &nbsp;
                        <i class="fa fa-clock-o"></i> {{ __('t.last-updated') }} {{$course->updated_at->format('m/Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    
    <!-- HOW IT WORKS -->
    <section class="pt-3 bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="caption-heading">{{ __('t.course-description') }}</h5>
                    {!! $course->description !!}
                    
                    <h5 class="caption-heading">{{ __('t.course-content') }}</h5>
                    <div class="section-outline mb-4">
                        
                        @foreach($course->sections as $section)
                            <div class="bg-light mb-2">
                                <a data-toggle="collapse" href="#section-{{$section->id}}" class="collapsed" aria-expanded="false">
                                    <h4 class="tit-section xsm bg-light text-info"> 
                                        {{ __('t.section') }} {{$loop->iteration}}: {{ $section->title }}
                                        <span class="pull-right">
                                            <i class="fa fa-angle-down"></i>
                                        </span>
                                    </h4>
                                </a> 
                                <ul id="section-{{$section->id}}" class="section-list collapse" aria-expanded="false" style="height: 1px;">
                                    @foreach($section->lessons as $l)
                                        <li>
                                            <div class="count">
                                                @if($l->content_type == 'quiz')
                                                    <span class="fa fa-question-circle"></span>
                                                @else
                                                    <span class="fa fa-play-circle"></span>
                                                @endif
                                            </div> 
                                            <div class="list-body">
                                                <p><a href="{{route('frontend.course.play', ['course' => $course, 'lesson' => $l])}}" class="disabledx">{{$l->title}}</a></p> 
                                                <p class="mb-0 font-italicx text-muted">{{str_limit($l->description, 100)}}</p>
                                                <div class="data-lessons clearfix"></div>
                                            </div> 
                                            <a class="mc-btn-2">
                                                @if($l->content_type == 'quiz')
                                                    <i class="fa fa-question-circle"></i> {{ __('t.quiz') }}
                                                @elseif($l->content_type == 'article') 
                                                    <i class="fa fa-file-text-o"></i> {{ __('t.article') }}
                                                @else 
                                                    <i class="fa fa-play-circle"></i> {{ $l->minutes_seconds }}
                                                @endif
                                            </a>
                                            
                                        </li> 
                                    @endforeach
            		            </ul>
        		            </div>
    		            @endforeach
		            </div>
		            
		            <h5 class="caption-heading">{{ __('t.student-feedback') }}</h5>
		            <div class="row mb-4">
		                
		                <div class="col-sm-3">
		                    <div class="card bg-light mb-3" style="max-width: 100%;">
                                <div class="card-body text-center">
                                    <h1 style="margin: 0px;">{{ $course->average_rating }}</h1> 
                                    <p>
                                        <stars :rating="{{$course->average_rating}}" size="18"></stars> <br>
                                        {{ __('t.average-rating') }}
                                    </p>
                              </div>
                            </div>
	                    </div>
	                    
	                    <div class="col-sm-6">
	                        @foreach($course_ratings as $rating)
    	                        <div class="pull-left">
    	                            <div class="pull-left" style="width: 35px; line-height: 1;">
    	                                <div style="height: 20px; margin: 0px 0px 12px;">{{$rating['rating']}} <span class="fa fa-star"></span></div>
                                    </div> 
                                    <div class="pull-left" style="width: 220px;">
                                        <div class="progress rounded-0" style="height: 20px; margin: 0px 0px 8px; background: #d0d0d0;">
                                            <div role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" class="progress-bar bg-info" style="width: {{$rating['width']}}%;">
                                                <span class="sr-only">{{$rating['rating']}} {{ __('t.star-rating') }} (danger)</span>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="pull-right" style="margin-left: 10px;">{{$rating['total']}}</div>
                                </div> 
                            @endforeach
                        </div>
                        
		            </div>
		            
		            <div class="row">
                        <!--- COURSE REVIEW FORM --->
                        @if(auth()->check() && auth()->user()->canReviewCourse($course))
                            <div class="col-md-12">
                                <course-review-form :course="{{$course}}" user_can_review="{{ Auth::check() && Auth::user()->canReviewCourse($course) ? 'true':'false' }}"></course-review-form>
                            </div>
                        @endif
                        
                        @if(auth()->check())
                            <course-reviews :course="{{ $course }}" :auth_user="{{  Auth::user() }}"></course-reviews>
                        @else 
                            <course-reviews :course="{{ $course }}"></course-reviews>
                        @endif
                    </div>
                        
                </div>
                
                
                <!-- sidebar -->
                <div id="affix" class="col-md-4 sidebar-long">
                    <div class="card bg-light" style="width: 100%;">
                        <a href="#" data-toggle="modal" data-target="#previewCourse" data-backdrop="static" data-keyboard="false">
                            <div class=" {{ !$previews->isEmpty() ? 'video-thumbnail' : '' }}">
                                <img class="card-img-top" src="{{ $course->coverImage }}" alt="">
                            </div>
                        </a>
                        
                        <div class="card-body">
                            <h4 class="card-title">
                                <span class="price">{{Gabs::currency_string($course->final_price)}}</span>
                                @if(!is_null($global_coupon) && $course->price > 0)
                                    <span class="discount">{{Gabs::currency_string($course->price)}}</span>
                                    <small class="text-muted pull-right">{{ $course->discount_percent}}% {{ __('t.off') }}</small>
                                @endif
                            </h4>
                            @if(!is_null($global_coupon))
                                <p class="text-success text-center">
                                    {{ __('t.discount-expires-in') }}
                                    <countdown-timer deadline="{{ $global_coupon->expires->format('F d, Y') }}"></countdown-timer>
                                </p>
                            @endif
                            @if($course->price > 0)
                                <a href="{{ route('frontend.user.course.checkout', ['course' => $course, 'COUPON' =>$coupon_code]) }}" class="btn btn-block btn-danger btn-lg rounded-0">
                                    {{ __('t.buy-now') }}
                                </a>
                            @else 
                                <a href="{{route('frontend.course.enroll', $course)}}" class="btn btn-block btn-danger btn-lg rounded-0">
                                    {{ __('t.enroll-now') }}
                                </a>
                            @endif
                          
                            @auth()
                                <hr class="heading-sep" />
                                <course-bookmark inline-template slug="{{$course->slug}}" v-cloak>
                                    <div class="text-center">
                                        <a href="#" class="text-success" @click.prevent="addToWishlist()" v-show="!userHasBookmarked">
                                            <i class="fa fa-heart-o"></i> {{ __('t.add-to-wishlist') }}
                                        </a>
                                        <a href="#" class="text-danger" @click.prevent="addToWishlist()" v-show="userHasBookmarked">
                                            <i class="fa fa-heart"></i> {{ __('t.remove-from-wishlist') }}
                                        </a>
                                    </div>
                                </course-bookmark>
                            @endauth
                      </div>
                    </div>
                    
                    @auth
                        @if(config('site_settings.site_enable_affiliate') && $course->price > 0)
                            <div class="card bg-light mt-4 mb-4">
                                <div class="card-body bg-warning">
                                    <h6 class="pt-0"><b>{{ __('t.your-affiliate-link') }}</b></h6>
                                    <p>
                                        {!! __('t.affiliate-text', ['percentage' => round(config('site_settings.earning_affiliate_sales_percentage',1)) . '%' ]) !!}
                                    </p>
                                    <hr />
                                    <affiliate-link link="{{ URL::route('frontend.course.show', ['course' => $course, 'ref' =>auth()->user()->affiliate_id]) }}" inline-template v-cloak>
                                        <div class="form-group">
                                            <div class="input-group">
                                              <input type="text" class="form-control w-100" style="font-size:12px !important;" id="promoLink" :value="link">
                                              <span class="input-group-addon">
                                                  <a href="#" @click.prevent="copyToClipboard()">
                                                      <i class="fa fa-clipboard"></i>
                                                  </a>
                                              </span>
                                            </div>
                                            <small class="text-white">@{{copyStatus}}</small>
                                        </div>
                                    </affiliate-link>
                                </div>
                            </div>
                        
                        @endif
                    @endauth
                    
                    <div class="card bg-light mt-4 mb-4">
                        <div class="card-body">
                            <h6 class="pt-0">{{ __('t.course-numbers') }}</h6>
                            <ul class="fa-ul">
                                <li><i class="fa-li fa fa-file-video-o"></i> {{$course->total_hours}} {{str_plural('hour', $course->total_hours)}} {{ __('t.of-video-content') }}</li>
                                @if($course->total_articles > 0)
                                    <li><i class="fa-li fa fa-file-text-o"></i> {{$course->total_articles}} {{str_plural(__('t.article'), $course->total_articles)}}</li>
                                @endif
                                @if($course->total_attachments > 0)
                                    <li><i class="fa-li fa fa-paperclip"></i> {{$course->total_attachments}} {{str_plural(__('t.attachment'), $course->total_attachments)}}</li>
                                @endif
                                @if($course->total_quizzes > 0)
                                    <li><i class="fa-li fa fa-question-circle"></i> {{$course->total_quizzes}} {{str_plural(__('t.quiz'), $course->total_quizzes)}}</li>
                                @endif
                                <li><i class="fa-li fa fa-wifi"></i> {{ucfirst($course->level)}} Level</li>
                                <li>
                                    <i class="fa-li fa fa-folder-o"></i> 
                                    <a href="{{ route('frontend.courses', ['category' => $course->category->slug] ) }}">{{ $course->category->name }}</a>
                                </li>
                                <li>
                                    <i class="fa-li fa fa-users"></i> 
                                    {{ $course->students->count() }}
                                    {{ str_plural('Student', $course->students->count()) }}
                                </li>
                            </ul>
                        </div>
                    </div>
                
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="pt-0">{{ __('t.instructor-information') }}</h6>
                            <div class="media">
                                <img class="mr-3 rounded-circle" src="{{ $course->author->picture }}" width="150" alt="">
                                <div class="media-body">
                                    <ul class="fa-ul">
                                        <li><i class="fa-li fa fa-star"></i>{{ $course->author->average_rating() }} {{ __('t.average-rating') }}</li> 
                                        <li><i class="fa-li fa fa-comment"></i>{{ $course->author->total_reviews() }} {{ str_plural(__('t.review'), $course->author->total_reviews()) }}</li> 
                                        <li><i class="fa-li fa fa-users"></i> {{ $course->author->total_students() }} {{ str_plural(__('t.student'), $course->author->total_students()) }}</li> 
                                        <li><i class="fa-li fa fa-play-circle"></i> {{ $course->author->courses->count() }} {{ str_plural(__('t.course'), $course->author->courses->count()) }}</li> 
                                        @if(auth()->check() && auth()->id() != $course->author->id)
                                            <li>
                                                <i class="fa-li fa fa-envelope"></i> 
                                                <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-id="2" data-target="#sendMessage" class="sendMessage">
                                                    {{ __('t.message-me') }}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <p class="text-muted" style="font-size: 15px; font-weight: bold;">
		                        <a href="{{route('frontend.user', $course->author->username)}}">{{ $course->author->name }}</a> <br>{{ $course->author->tagline }}
                            </p>
                            
                            {!! str_limit($course->author->bio, 300)!!}
                            
                            <hr class="line-seperator" />
                            <a href="{{route('frontend.user', $course->author->username)}}" class="btn btn-block btn-info">{{ __('t.see-complete-profile') }}</a>
                            
                        </div>
                    </div>

                    
                </div>
                
                @if(!$previews->isEmpty())
                    <!-- Modal -->
                    <div class="modal fade" id="previewCourse">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content bg-dark">
                                <!-- Modal Header -->
                                <div class="modal-header bg-dark text-white">
                                    <h6 class="modal-title">Course Preview: <span class="text-info">{{ $course->title }}</span></h6>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>
                        
                                <!-- Modal body -->
                                <div class="modal-body p-0">
                                    <div align="center" class="video-js-box hu-css">
                                        <video id="previewPlayer" class="vjs-tech video-js vjs-big-play-centered vjs-16-9"
                                          controls preload="auto" width="100%" height="500"
                                          data-setup='{"techOrder": ["vimeo", "youtube", "html5"], "fluid": true, "preload": "auto", "autoplay": false, "playbackRates": [0.5, 0.75, 1, 1.5, 2] }'
                                          poster="">
                                          <source type="video/{{$previews[0]->provider}}" src="{{$previews[0]->video_path}}"></source>
                                        </video>
                                    </div>
                                </div>
                        
                                <!-- Modal footer -->
                                <div class="modal-footer p-0">
                                    <ul class="list-group w-100 bg-dark">
                                        @foreach($previews as $p)
                                            <a href="#" data-video="{{$p->video_path}}" data-type="{{$p->provider}}" 
                                                class="list-group-item list-group-item-action preview text-white {{ $loop->first ? 'bg-secondary' : 'bg-dark' }}">
                                                {{ $p->title }}
                                            </a>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                
            </div>
        </div>
    </section>

@endsection

@push('after-scripts')
    @include('_User.messenger._new_message', ['user' => $course->author])
    
    <!-- only render videojs if there are preview lessons -->
    @if(!$previews->isEmpty())
        <script src="//vjs.zencdn.net/5.19/video.min.js"></script>
        <script src="{{ URL::asset('js/player/Youtube.js') }}"></script>
        <script src="{{ URL::asset('js/player/Vimeo.js') }}"></script>
        <script src="{{ URL::asset('js/stickyfill.js') }}"></script>
        
        <script type="text/javascript">
            var player = videojs('previewPlayer');
            $('.preview').on("click", function () {
                $('.preview').removeClass('bg-secondary');
                $('.preview').addClass('bg-dark');
                $(this).removeClass('bg-dark');
                $(this).addClass('bg-secondary');
                var video_src = $(this).data('video');
                var video_type = $(this).data('type');
                player.src({ "type": "video/"+video_type, "src": video_src});
                player.play();
            });
            $('.close').on('click', function(){
                player.pause();
                player.currentTime(0);
                //player.src('');
            })
        </script>
        
        <script>
            $(document).ready(function(){
	           $('body').bind('contextmenu',function() { return false; });
	        });
	        
	        
	        
	        document.onkeydown = function(e) {
	            if(e.keyCode == 123) {
	             return false;
	            }
	            if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
	             return false;
	            }
	            if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
	             return false;
	            }
	            if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
	             return false;
	            }
	        
	            if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
	             return false;
	            }      
	         }
        </script>
    @endif
    
    
    <style type="text/css">
        .sidebar-long{
            position: -webkit-sticky;
            position: sticky;
            top: 0;
        }
        
        .sidebar-long:before,
        .sidebar-long:after {
            content: '';
            display: table;
        }
        .modal.fade .modal-dialog {
             -webkit-transform: scale(0.6);
             -moz-transform: scale(0.6);
             -ms-transform: scale(0.6);
             transform: scale(0.6);
             -webkit-transition: all 0.3s;
             -moz-transition: all 0.3s;
             transition: all 0.3s;
             top: 300px;
             opacity: 0;
        }
        
        .modal.fade.show .modal-dialog {
            -webkit-transform: scale(1);
            -moz-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
            -webkit-transform: translate3d(0, -600px, 0);
            transform: translate3d(0, -300px, 0);
            opacity: 1;
        }

    </style>
@endpush
