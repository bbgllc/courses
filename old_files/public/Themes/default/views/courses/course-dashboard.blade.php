@extends('layouts.master')

@section('title', app_name() . ' | ' . $course->title )

@section('after-styles')


@stop

@section('content')
    
    @include('courses.partials._course_enrolled_header')
    
    <section class="page-control content-bar ps-container">
        <div class="container">
            @include('includes._course_dashboard_menu')
        </div>
    </section>

    
    <!-- HOW IT WORKS -->
    <section class="pt-4 bg-gray">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h4 class="text-info">
                        {{ __('t.recent-activities') }}
                    </h4>
                    <hr class=""/>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5>
                                {{ __('t.recent-questions') }}
                            </h5>
                            <hr class="mb-2 mt-0"/>
                            @if($recent_questions->count())
                                @foreach($recent_questions as $q)
                                    <div class="media mb-3">
                                        <img class="align-self-centerx mr-3" src="{{ $q->user->picture }}" width="50" alt="...">
                                        <div class="media-body">
                                            <h6 class="mt-0 mb-0">{{ $q->user->name }}</h6>
                                            <p><a href="{{ route('frontend.user.questions.show', [$course, $q]) }}">{{ $q->title }}</a></p>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <p class="mt-4">
                                    <a href="{{ route('frontend.user.questions.index', $course) }}">
                                        {{ __('t.browse-all-questions') }}
                                    </a>
                                </p>
                            @else
                                <p>{{ __('t.no-recent-questions') }}</p>
                            @endif
                            
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5>
                                {{ __('t.recent-announcements') }}
                            </h5>
                            <hr class="mb-2 mt-0"/>
                            @if($recent_announcements->count())
                                @foreach($recent_announcements as $a)
                                    <div class="media mb-3">
                                        <img class="align-self-centerx mr-3" src="{{ $course->author->picture }}" width="50" alt="...">
                                        <div class="media-body">
                                            <h6 class="mt-0 mb-0">{{ $course->author->name }}</h6>
                                            <p><a href="{{ route('frontend.user.announcements.show', [$course, $a]) }}">{{ $a->title }}</a></p>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <p class="mt-4">
                                    <a href="{{ route('frontend.user.announcements.index', $course) }}">{{ __('t.browse-all-announcements') }}</a>
                                </p>
                            @else
                                <p>{{ __('t.no-recent-announcements') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">   
                <div class="col-12">
                    <h4 class="text-info">
                        {{ __('t.course-info') }}
                    </h4>
                    <hr class=""/>
                </div>
                
                <!-- sidebar -->
                <div class="col-md-4">
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="pt-0">{{ __('t.course-numbers') }}:</h6>
                            <ul class="fa-ul">
                                <li><i class="fa-li fa fa-file-video-o"></i> {{$course->total_hours}} {{str_plural(__('t.hour'), $course->total_hours)}} {{ __('t.of-video-content') }}</li>
                                @if($course->total_articles > 0)
                                    <li><i class="fa-li fa fa-file-text-o"></i> {{$course->total_articles}} {{str_plural(__('t.article'), $course->total_articles)}}</li>
                                @endif
                                @if($course->total_attachments > 0)
                                    <li><i class="fa-li fa fa-paperclip"></i> {{$course->total_attachments}} {{str_plural( __('t.attachment'), $course->total_attachments)}}</li>
                                @endif
                                @if($course->total_quizzes > 0)
                                    <li><i class="fa-li fa fa-question-circle"></i> {{$course->total_quizzes}} {{str_plural(__('t.quiz'), $course->total_quizzes)}}</li>
                                @endif
                                <li><i class="fa-li fa fa-wifi"></i> {{ucfirst($course->level)}} {{ __('t.level') }}</li>
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
                    
                    <div class="card bg-light">
                        <div class="card-body mb-3">
                            <h6 class="pt-0">{{ __('t.instructor-information') }}</h6>
                            <div class="media">
                                <img class="mr-3 rounded-circle" src="{{ $course->author->picture }}" width="150" alt="">
                                <div class="media-body">
                                    <ul class="fa-ul">
                                        <li><i class="fa-li fa fa-star"></i>{{ $course->author->average_rating() }} {{ __('t.average-rating') }}</li> 
                                        <li><i class="fa-li fa fa-comment"></i>{{ $course->author->total_reviews() }} {{ str_plural(__('t.review'), $course->author->total_reviews()) }}</li> 
                                        <li><i class="fa-li fa fa-users"></i> {{ $course->author->total_students() }} {{ str_plural(__('t.student'), $course->author->total_students()) }}</li> 
                                        <li><i class="fa-li fa fa-play-circle"></i> {{ $course->author->courses->count() }} {{ str_plural(__('t.course'), $course->author->courses->count()) }}</li> 
                                        <li>
                                            <i class="fa-li fa fa-envelope"></i> 
                                            <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-id="2" data-target="#sendMessage" class="sendMessage">
                                                {{ __('t.message-me') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <p class="text-muted" style="font-size: 15px; font-weight: bold;">
		                        <a href="{{route('frontend.user', $course->author->username)}}">{{ $course->author->name }}</a> <br>
		                        {{ $course->author->tagline }}
                            </p>
                            
                            {!! str_limit($course->author->bio, 300)!!}
                            
                            <hr class="line-seperator" />
                            <a href="{{route('frontend.user', $course->author->username)}}" class="btn btn-block btn-info">{{ __('t.see-complete-profile') }}</a>
                      </div>
                    </div>
                    
                </div>
                
                <div class="col-md-8">
                    <h5 class="caption-heading">{{ __('t.course-description') }}</h5>
                    {!! $course->description !!}
                    
                    
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
		            
		            <div class="row mb-4">
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
                
            </div>
        </div>
    </section>

@endsection


@push('after-scripts')
    
    @include('_User.messenger._new_message', ['user' => $course->author])
    
@endpush

