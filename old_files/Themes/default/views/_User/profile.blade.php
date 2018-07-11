@extends('layouts.master')

@section('title', app_name() . ' | ' . $user->name)

@section('after-styles')
	<style type="text/css">
	    .popover {min-width: 400px;}
	</style>
@stop

@section('content')
    
    @include('includes._title_header', ['title' =>  $user->name ] )
    
    <section class="content-area bg-gray pt-5 pb-5">
        <div class="container">
            
            <div class="row mb-4">
                
                <div class="col-md-3">
                    <div class="profile-card bg-light hovercard">
                        <!--
    	                <span class="title premium-member">
                            <figcaption>
								<a class="tg-usericon tg-iconfeatured" href="#">
									<em class="tg-usericonholder">
										<i class="fa fa-diamond"></i>
										<span>Trending</span>
									</em>
								</a>
								<a class="tg-usericon tg-iconvarified" href="#">
									<em class="tg-usericonholder">
										<i class="fa fa-shield"></i>
										<span>Elite</span>
									</em>
								</a>
							</figcaption>
						</span>
						-->
                        <div class="cardheader" style="background:#333b4c;"></div>
                        <div class="avatar">
                            <img src="{{ $user->picture }}">
                        </div>
                        <div class="info">
                            <div class="title font-weight-bold">
                                <a style="font-size: 15px;">
                                    {{$user->name}}
                                </a>
                            </div>
                            <div class="desc">{{$user->tagline}}</div>
                        </div>

                        <div class="or-spacer" style="margin-top:10px;margin-bottom:0px;">
                            <div class="mask"></div>
                        </div>
                        
                        <div class="bottom">
                            @if($user->web)
                                <a class="btn btn-info btn-sm" rel="publisher" href="{{$user->web}}" target="_blank">
                                    <i class="fa fa-globe"></i>
                                </a>
                            @endif
                            @if($user->twitter)
                                <a class="btn btn-info btn-twitter btn-sm" href="https://www.twitter.com/{{$user->twitter}}" target="_blank">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            @endif
                            @if($user->github)
                                <a class="btn btn-info btn-sm" rel="publisher" href="https://www.github.com/{{$user->github}}" target="_blank">
                                    <i class="fa fa-github"></i>
                                </a>
                            @endif
                            @if($user->facebook)
                                <a class="btn btn-info btn-sm" rel="publisher" href="https://www.facebook.com/{{$user->facebook}}" target="_blank">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            @endif
                            @if($user->linkedin)
                                <a class="btn btn-info btn-sm" rel="publisher" href="https://www.linkedin.com/{{$user->linkedin}}" target="_blank">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            @endif
                        </div>
                            
                    </div>
                    
                    <div class="card bg-light">
                        <div class="card-body">
                            <ul class="fa-ul">
                                <li><i class="fa-li fa fa-star"></i>{{$user->average_rating()}} {{ __('t.average-rating') }}</li> 
                                <li><i class="fa-li fa fa-comment"></i> {{$user->total_reviews()}} {{str_plural(__('t.review'), $user->total_reviews())}}</li> 
                                <li><i class="fa-li fa fa-users"></i> {{$user->total_students()}} {{str_plural(__('t.student'), $user->total_students())}}</li> 
                                <li><i class="fa-li fa fa-play-circle"></i> {{$user->courses->count()}} {{ __('t.courses') }}</li> 
                                @if(auth()->check() && auth()->user()->id != $user->id)
                                    <li>
                                        <i class="fa-li fa fa-envelope"></i> 
                                        <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-id="2" data-target="#sendMessage" class="sendMessage">
                                            {{ __('t.contact') }} {{$user->name}}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                </div>
                
                <div class="col-md-9">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="text-info">{{ __('t.about') }} {{$user->name}}</h5>
                            {!! $user->bio !!}
                              
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-uppercase text-center">{{ __('t.courses-taught-by', ['user' => $user->name]) }} </h6>
                    <hr>
                </div>
                @if($courses->count())
                    @each('courses.partials._course', $courses, 'course')
                @else
                    <div class="col-12 text-center">
                        {{ __('t.no-courses-found') }}
                    </div>
                @endif        
            </div>

            
        </div>
    </section>

@endsection

@push('after-scripts')
    
    @include('_User.messenger._new_message')
    
@endpush
