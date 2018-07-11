<!DOCTYPE html>
<!-- HTML5 Hello world by kirupa - http://www.kirupa.com/html5/getting_your_feet_wet_html5_pg1.htm -->
<html lang="en-us" class="audio fullscreen no-touchevents video videoautoplay">

<head>
	<style class="vjs-styles-defaults">
	
	    .video-js {
	      width: 300px;
	      height: 150px;
	    }
	
	    .vjs-fluid {
	      padding-top: 56.25%
	    }
	  
	</style>
	<style class="vjs-styles-dimensions">
        .title-course a{
        	font-size: 22px !important;
        }
        .title-clip{
        	font-size: 18px;
        	font-weight: bold;
        }
    </style>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0, minimal-ui">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<title>{{ $course->title }}</title>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" 
        integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    
    <link rel="stylesheet" href="{{ themes('css/player/main.css') }}" media="all" />
    <link rel="stylesheet" href="{{ themes('css/player/style.css') }}" media="all" />
    <link rel="stylesheet" href="{{ themes('css/player/icon.css') }}" media="all" />
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="//vjs.zencdn.net/5.19/video-js.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="/css/player/youtube.css" media="all" />
    
    
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
	
	<style type="text/css">
		.video-container{
			width: 100vw; 
		    height: 56.25vw; /* 100/56.25 = 1.778 */
		    max-height: 100vh;
		    max-width: 177.78vh; /* 16/9 = 1.778 */
		    margin: auto;
		    top:0;bottom:0; /* vertical center */
		    left:0;right:0; /* horizontal center */
		}
		.w-100 {
		    width: 100% !important;
		    background: black;
		}
		
		.vjs-playback-rate .vjs-playback-rate-value{
			font-size:1em;
			line-height:3;
		}
		.vjs-playback-rate .vjs-menu{
			width:2em;
			left:0;
		}
		.vjs-menu li {
		    margin: 0;
		    padding: .2em 0;
		    line-height: 1.4em;
		    font-size: .86em;
		}
		.video-js .vjs-next-control,
		.video-js .vjs-previous-control,
		.video-js .vjs-completed-control,
		.vjs-download-resources-control{
		    cursor: pointer;
		    -webkit-box-flex: none;
		    -moz-box-flex: none;
		    -webkit-flex: none;
		    -ms-flex: none;
		    flex: none;
		 }
		.video-js .vjs-next-control:before{
		    content: "\f051";
		    font-family: 'fontawesome';
		    font-size: 1.3em;
			line-height: 2.2;
		}
		.video-js .vjs-previous-control:before{
		    content: "\f048";
		    font-family: 'fontawesome';
		    font-size: 1.3em;
    		line-height: 2.2;
		}
		.video-js .vjs-download-resources-control:before{
		    content: "\f0c6";
		    font-family: 'fontawesome';
		    font-size: 1.3em;
    		line-height: 2.2;
    		
		}
		.video-js .vjs-completed-control:before{
		    content: "\f05d";
		    font-family: 'fontawesome';
		    font-size: 1.3em;
    		line-height: 2.2;
    		
		}
		.side-menu-clip-title a{
			display:block;
			width: 100%;
			color: #d0d0d0;
		}
		.side-menu-clip-title a:hover{
			color: #fff;
			text-decoration: none;
		}
		[v-cloak]{
			display: none;
		}
		.video-js {
		    position: inherit !important;
		}
	</style>
</head>

<body>
<div id="main">
	@include('includes.partials.ga')
	<section id="app" class="w-100 d-flex side-menu">
		
		@if( $lesson->content_type == 'article' || $lesson->lesson_type == 'quiz' )
	    	<div id="video-container" class="video-container w-100 h-100 bg-light">
		        <header class="video-header gradient-fade d-flex fade-out">
		            <div class="d-flex flex-grow-1 gradient-fade">
		                <div class="col pt-md pl-lg">
		                    <div class="title-course text-white" id="course-title">
		                    	<a class="ps-link-white" href="{{route('frontend.course.show', $course)}}">{{$course->title}}</a>
	                    	</div>
		                    <div class="title-clip text-white" id="module-clip-title">{{$lesson->section->title}}: {{$lesson->title}}</div>
		                </div>
		                <div class="d-flex align-items-start pt-tiny pr-sm">
		                    <div id="close-sidebar" class="d-flex button-with-floating-content">
		                    	<a href="{{route('frontend.course.show', $course)}}" class="ps-icon-button display-4 pt-3 font-weight-bold" style="font-size:1rem !important;">{{ __('t.back-to-dashboard') }}</a>
		                        <button id="side-menu-button" class="ps-icon-button hidden-md-down pl-md icon-side-close"></button>
		                        <button id="side-menu-button" class="ps-icon-button hidden-md-down pl-md icon-side-open" style="display:none;"></button>
	                        </div>
		                </div>
		            </div>
		        </header>
		        
	        	<div id="article" class="d-flex pt-5 bg-light">
		            <div class="p-4 h-100 mt-12 card card-body">
		            	<div class="card-body" style="width:100%; margin: 0 auto; height: 500px; overflow-y: scroll;">
		            		@if($lesson->content_type == 'article')
		            			{!! $lesson->article_body !!}
	            			@else 
	            				<user-quiz lesson="{{$lesson->id}}" inline-template v-cloak>
	            					<div>
	            						<div v-if="!show_attempts">
		            						<div v-if="showResults">
		            							<h5>
	                                                {{ __('t.quiz-results') }}: @{{totalCorrect}} {{ __('t.out-of') }} @{{questions.length}} - 
	                                                <span :class="percent >= 50 ? 'label label-success' : 'label label-danger'">@{{percent}}%</span>
	                                            </h5>
	                                            <hr />
	                                            <ul class="list-group" v-for="(question,index) in answeredQuestions">
	                                                <li class="list-group-item mb-2">
	                                                    <h6 style="color:#0d568a;font-weight:bold;margin-top:0;">{{ __('t.question') }} @{{index+1}}</h6> <span v-html="question.question.question"></span>
	                                                    <ol>
	                                                        <li v-for="answer in question.question.answers" style="margin-bottom:10px;">
	                                                            
	                                                            <i class="fa fa-times-circle-o" style="color:#ce0000;margin-left:-32px;padding-right:20px;" 
	                                                                v-if="answer.id == question.question.selectedAnswer.id && question.question.selectedAnswer.correct != 1"></i>
	                                                            
	                                                            <i class="fa fa-check-circle-o" style="color:#4caf50;margin-left:-32px;padding-right:20px;" 
	                                                                v-if="answer.id == question.question.selectedAnswer.id && question.question.selectedAnswer.correct == 1"></i>
	                                                                
	                                                            @{{answer.answer}} &nbsp; &nbsp; <span class="text-success" style="color:#4caf50;font-size:0.8em;" v-if="answer.correct==1">
                                                                <i class="fa fa-arrow-left"></i> {{ __('t.correct-answer') }}</span>
                                                                <p :style="answer.correct == 1 ? 'color:#4caf50; font-size:0.8em;' : 'color:#ce0000; font-size:0.8em;'" v-if="answer.explanation">
                                                                    {{ __('t.explanation') }}: <em>@{{answer.explanation}}</em>
                                                                </p>
	                                                        </li>
	                                                    </ol>
	                                                </li>
	                                            </ul>
		            						</div>
		            						<div v-else>
		            							<h5 v-if="questions">{{ __('t.question') }} @{{currentQuestionNumber}} {{ __('t.of') }} @{{questions.length}}</h5>
	                                            <hr />
	                                            <user-quiz-question v-if="currentQuestion" :question="currentQuestion" :is_last_question="isLastQuestion" inline-template @answer-chosen="storeAnswer">
	                                                <div>
	                                                    <div class="lead" v-html="question.question"></div>
	                                                    <div class="radio" v-for="answer in question.answers" :key="answer.id">
	                                                         <label :for="'answer-'+ answer.id">
	                                                             <input type="radio" name="btn" 
	                                                                style="margin-top:3px; margin-right:8px;"
	                                                                :id="'answer-'+ answer.id" @click="choose(answer)"> @{{answer.answer}}
	                                                         </label>
	                                                    </div>
	                                                    
	                                                    <button class="btn btn-primary btn-sm mt-4" @click.prevent="nextQuestion()" v-if="showNext">
	                                                    	<span v-if="is_last_question">{{ __('t.finish-quiz') }}</span>
	                                                    	<span v-else>{{ __('t.next-question') }}</span><i class="fa fa-angle-double-right"></i>
	                                                    </button>
	                                                    
	                                                </div>
	                                            </user-quiz-question>	
		            						</div>
	            						</div>
	            						<div v-else>
	            							<h5>
	            								{{ __('t.you-have-attempted-this-quiz') }}
	            								@{{quiz_attempts.length}} {{ __('t.times') }}
            								</h5>
	            							<hr />
	            							<p>{{ __('t.see-your-previous-attempt') }}</p>    
					                        <table class="table table-striped table-sm">
					                            <thead>
					                              <tr>
					                                <th>{{ __('t.date') }}</th>
					                                <th>{{ __('t.total-answered') }}</th>
					                                <th>{{ __('t.total-correct') }}</th>
					                                <th>{{ __('t.score') }}</th>
					                              </tr>
					                            </thead>
					                            <tbody>
				                                    <tr v-for="attempt in quiz_attempts">
				                                        <td>@{{attempt.created_at}}</td>
				                                        <td>@{{attempt.total_attempted}}</td>
				                                        <td>@{{attempt.total_correct}}</td>
				                                        <td>
				                                        	<span class="badge " :class="attempt.percent_correct >= 50 ? 'badge-success' : 'badge-danger'">
				                                        		@{{attempt.percent_correct}}%
			                                        		</span>
		                                        		</td>
				                                    </tr>                      
					                            </tbody>
					                        </table>
					                        <a href="#" class="btn btn-info btn-sm pull-right" @click.prevent="show_attempts = false">
					                        	{{ __('t.take-the-quiz-again') }}
				                        	</a>
	            						</div>
	            						
	            					</div>	
	            				</user-quiz>
	            			@endif
		            	</div>
		            	<div class="card-footer clearfix">
		            		@if(!is_null($previous_lesson))
			            		<a href="{{ route('frontend.course.play', [$course, $previous_lesson] )  }}">
			            			<i class="fa fa-step-backward fa-2x" data-toggle="tooltip" title="{{ __('t.previous') }}"></i>
			            		</a>
		            		@endif
		            		@if(!is_null($next_lesson))
			            		<a href="{{ route('frontend.course.play', [$course, $next_lesson] )  }}" class="pull-right">
			            			<i class="fa fa-step-forward fa-2x" data-toggle="tooltip" title="{{ __('t.next') }}"></i>
			            		</a>
		            		@endif
		            	</div>
		            </div>
		        </div>
		        
	        </div>
        @endif
		 @if($lesson->content_type == 'video')   
		    <div id="video-container" class="video-container d-flex w-100 h-100">
		    	
		        <header class="video-header gradient-fade d-flex fade-out">
		            <div class="d-flex flex-grow-1 gradient-fade">
		                <div class="col pt-md pl-lg">
		                    <div class="title-course text-white" id="course-title">
		                    	<a class="ps-link-white" href="{{route('frontend.course.show', $course)}}">{{$course->title}}</a>
	                    	</div>
		                    <div class="title-clip text-white" id="module-clip-title">{{$lesson->section->title}}: {{$lesson->title}}</div>
		                </div>
		                <div class="d-flex align-items-start pt-tiny pr-sm">
		                    <div id="close-sidebar" class="d-flex button-with-floating-content">
		                    	<a href="{{route('frontend.course.show', $course)}}" class="ps-icon-button display-4 pt-3 font-weight-bold" style="font-size:1rem !important;">{{ __('t.back-to-dashboard') }}</a>
		                        <button id="side-menu-button" class="ps-icon-button hidden-md-down pl-md icon-side-close"></button>
		                        <button id="side-menu-button" class="ps-icon-button hidden-md-down pl-md icon-side-open" style="display:none;"></button>
	                        </div>
		                </div>
		            </div>
		        </header>
			        
		        <div id="video" class="d-flexs flex-columnx">
		            <div id="vjs_video_3" tabindex="-1" oncontextmenu="return true" 
		            	style="display:none;background:url(/img/frontend/player.png);" 
		            	class="video-js-box player-container">
		                <video id="my-player" 
	                        class="vjs-tech video-js vjs-big-play-centered vjs-16-9" 
	                        controls
	                        preload="auto"
	                        width="640px" height="267px"
	                        data-setup='{"techOrder": ["vimeo", "youtube", "html5"], "fluid": true, "preload": "auto", "autoplay": true, "playbackRates": [0.5, 0.75, 1, 1.5, 2] }'
	                        poster="/img/frontend/player.png" >
	                        <source type="video/{{$lesson->video_provider}}" src="{{$lesson->video_link}}"></source>
	                        <p class="vjs-no-js">
								To view this video please enable JavaScript, and consider upgrading to a web browser that
							    <a href="http://videojs.com/html5-video-support/" target="_blank">
							      supports HTML5 video
							    </a>
							  </p>
	                    </video>
		            </div>
		        </div>
	    	</div>
	    @endif
	    
	    
	    
	    
	    <!---- SIDEBAR ---->
	    
	    	<aside id="side-menu" class="d-flex flex-column ps-color-bg-gray-04" tabindex="-1">
	    		<ul class="nav nav-tabs tabs side-menu-tab-buttons">
	    			<li id="button-table-of-contents-wrapper" class="active">
	    				<button id="button-table-of-contents" class="side-menu-tab-button active" data-toggle="tab" href="#tab-table-of-contents">
	    					{{ __('t.table-of-content') }}
    					</button>
    				</li>
    				<li id="button-notes-wrapper" class="">
    					<button id="button-notes" class="side-menu-tab-button" data-toggle="tab" href="#notes">
    						{{ __('t.downloadable-files') }}
    					</button>
					</li>
				</ul>
				<div class="tab-content clearfix">
					<div id="tab-table-of-contents" class="tab-pane fade in show active">
						@foreach($sections as $section)
							<section class="module">
								<header class="d-flex {{$section->id  != $lesson->section_id ? 'collapsed' : ''}}" role="button" tabindex="0" data-toggle="collapse" data-target="#section-{{$section->id}}">
									<div class="side-menu-module-progress d-flex justify-content-center align-items-center">
							            <div class="viz-container rounded-circle d-flex justify-content-center align-items-center">
							                <div class="progress-radial progress-{{ auth()->user()->percentSectionCompleted($section) }} d-flex justify-content-center align-items-center">
							                    <div class="overlay rounded-circle ps-type-tiny ps-color-white ps-color-bg-gray-06 d-flex justify-content-center align-items-center">{{$loop->iteration}}</div>
							                </div>
							            </div>
							        </div>
									<div class="pr-lg py-md side-menu-module-title">
										<h2 class="m-0 p-0 ps-color-white ps-type-sm ps-type-weight-medium">{{$section->title}}</h2>
										@if($section->minutes_seconds != 0)
											<div class="ps-color-gray-02 ps-type-tiny ps-type-weight-book side-menu-module-duration"><span class="icon-time pr-tiny ps-type-xs"></span>
									    		{{$section->minutes_seconds}}
									    	</div>
								    	@endif
								    </div>
			    					<div class="ps-color-gray-02 ps-type-lg align-self-center p-md icon-drop-down"></div>
			    				</header>
			    				<ul id="section-{{$section->id}}" class="collapse clips m-0 p-0 {{$section->id  == $lesson->section_id ? 'show' : ''}}">
			    					@foreach($section->lessons as $l)
									    <li class="d-flex ps-color-bg-gray-05 py-sm pr-lg mx-0 clip-bg side-menu-clip {{$l->uid !== $lesson->uid ? 'ps-color-gray-02' : 'ps-color-orange bg-dark' }} watched" tabindex="0">
									    	
									        <div class="d-flex side-menu-clip-progress justify-content-center align-items-center">
									            <div class="icon-complete-small {{ auth()->user()->hasCompletedLesson($l) ? 'ps-color-green' : 'ps-color-gray-02'}} {{$l->uid !== $lesson->uid ? '' : 'icon-play-small' }} ps-type-xs"></div>
									        </div>
									        <h3 class="m-0 p-0 ps-type-xs ps-type-weight-book side-menu-clip-title ps-type-ellipsis">
									        	<a href="{{route('frontend.course.play', ['course' => $course, 'lesson' => $l])}}" style="display:block; width:100%;">
									        		{{$l->title}}
									        	</a>
								        	</h3>
								        	
									        <div class="pl-tiny ps-type-xs ps-type-weight-book side-menu-clip-duration">
								        		@if($l->attachments->count())
						        					<i class="fa fa-paperclip"></i>
							        			@endif
							        			
									        	@if($l->content_type == 'video')
									        		{{$l->minutes_seconds}}
								        		@elseif($l->content_type == 'article')
								        			<i class="fa fa-file-text"></i>
							        			@else 
							        				<i class="fa fa-question-circle"></i>
							        			@endif 
							        			
							        		
									        </div>
									    </li>
								    @endforeach
								</ul>
							</section>
						@endforeach
						
					</div>
					
					
					<!-- Downloadable files here -->
					<div id="notes" class="tab-pane fade">
						<section class="module open">
							
							@foreach($attachments as $att_sec)
								<header class="d-flex  {{$att_sec->id == $lesson->section_id ? '': 'collapse'}}" role="button" tabindex="0" data-toggle="collapse" data-target="#downloadables-sec-{{$att_sec->id}}">
							        <div class="side-menu-module-progress d-flex justify-content-center align-items-center">
							            <div class="viz-container rounded-circle ps-color-bg-gray-05 d-flex justify-content-center align-items-center">
							            	<span class="ps-type-tiny ps-color-white">{{ $att_sec->sortOrder }}</span>
						            	</div>
							        </div>
							        <div class="pr-lg py-md side-menu-module-title">
							            <h2 class="m-0 p-0 ps-color-white ps-type-sm ps-type-weight-medium">{{ $att_sec->title }}</h2>
							        </div>
							        <div class="ps-color-gray-02 ps-type-lg align-self-center p-md icon-drop-down"></div>
							    </header>
							    <ul id="downloadables-sec-{{$att_sec->id}}" class="collapse clips m-0 p-0 {{$att_sec->id == $lesson->section_id ? 'show': ''}}">
							    	@foreach($att_sec->lessons as $att_les)
							    		@if($att_les->attachments->count())
							    			@foreach($att_les->attachments as $attachment)
										        <li class="d-flex ps-color-bg-gray-05 py-sm pr-lg mx-0 clip-bg side-menu-clip ps-color-gray-02 {{$att_les->id == $lesson->id ? 'bg-dark' : '' }}" tabindex="0">
										            <div class="d-flex side-menu-clip-progress justify-content-center align-items-center">
										                <div class="side-menu-clip-unwatched-icon ps-color-bg-gray-04 rounded-circle"></div>
										            </div>
										            <h3 class="m-0 p-0 ps-type-xs ps-type-weight-book side-menu-clip-title ps-type-ellipsis d-block">
										            	{{ $att_les->title }}
										            	<p class="mt-3">{{$attachment->filename}}</p>
									            	</h3>
										            <div class="pl-tiny ps-type-xs ps-type-weight-book side-menu-clip-duration" data-target="tooltip" title="{{ __('t.download')}}">
										            	<a href="{{route('frontend.course.attachment.download', ['course' => $course, 'attachment' => $attachment->key])}}" target="_blank" class="text-white">
										            		<i class="fa fa-paperclip"></i>	<i class="fa fa-download"></i>	
										            	</a>
									            	</div>
										        </li>
									        @endforeach
								        @endif
							        @endforeach
							    </ul>
						    @endforeach
					    </section>
					    
					    <!--
					    <section class="module open">
							<header class="d-flex" role="button" tabindex="0" data-toggle="collapse" data-target="#reviews">
						        <div class="side-menu-module-progress d-flex justify-content-center align-items-center">
						            <div class="viz-container rounded-circle ps-color-bg-gray-05 d-flex justify-content-center align-items-center"><span class="ps-type-tiny ps-color-white">1</span></div>
						        </div>
						        <div class="pr-lg py-md side-menu-module-title">
						            <h2 class="m-0 p-0 ps-color-white ps-type-sm ps-type-weight-medium">Course Reviews</h2>
						        </div>
						        <div class="ps-color-gray-02 ps-type-lg align-self-center p-md icon-drop-down"></div>
						    </header>
						    <ul id="reviews" class="collapse clips m-0 p-0">
						        <li class="d-flex ps-color-bg-gray-05 py-sm pr-lg mx-0 clip-bg side-menu-clip ps-color-gray-02" tabindex="0">
						            <div class="d-flex side-menu-clip-progress justify-content-center align-items-center">
						                <div class="side-menu-clip-unwatched-icon ps-color-bg-gray-04 rounded-circle"></div>
						            </div>
						            <h3 class="m-0 p-0 ps-type-xs ps-type-weight-book side-menu-clip-title ps-type-ellipsis">Introduction</h3>
						            <div class="pl-tiny ps-type-xs ps-type-weight-book side-menu-clip-duration">0m 31s</div>
						        </li>
						        <li class="d-flex ps-color-bg-gray-05 py-sm pr-lg mx-0 clip-bg side-menu-clip ps-color-gray-02" tabindex="0">
						            <div class="d-flex side-menu-clip-progress justify-content-center align-items-center">
						                <div class="side-menu-clip-unwatched-icon ps-color-bg-gray-04 rounded-circle"></div>
						            </div>
						            <h3 class="m-0 p-0 ps-type-xs ps-type-weight-book side-menu-clip-title ps-type-ellipsis">ASP.NET Identity Overview</h3>
						            <div class="pl-tiny ps-type-xs ps-type-weight-book side-menu-clip-duration">2m 50s</div>
						        </li>
						    </ul>
					    </section>
					    -->
					</div>
				</div>
				
			</aside>
			
	</section>
	
</div>
	<script src="/js/frontend.js"></script>
    <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script> 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	
	<script src="//vjs.zencdn.net/5.19/video.min.js"></script>
    <script src="{{ URL::asset('js/player/Youtube.js') }}"></script>
    <script src="{{ URL::asset('js/player/Vimeo.js') }}"></script>
    
    
    @if($lesson->content_type == 'video')
        <script>
            var player = videojs('my-player');
            
            // when the video is ready
            player.ready(function(){
                //console.log('Ready!');
            });
            
            player.on('loadedmetadata', function() {
                //console.log(player);
            }, false);
            
            
            
            player.on('ended', function() {
                //markAsComplete();
                setTimeout(() => {
                	@if(!is_null($next_lesson))
                    	document.getElementById('next-lesson').click();
                    @elseif(auth()->user()->hasCompletedCourse($course))
                    	window.location.href = "{{route('frontend.course.content', $course)}}";
                	@endif
                }, 2000)
            });
            
            /*************************************************************/
           /* add custom button to videojs control						*/
          /*************************************************************/
          function addNewButton(data) {
		    var myPlayer = data.player,
		        controlBar,
		        newElement = document.createElement('button'),
		        newLink = document.createElement('a');
		
		    newElement.id = data.id;
		    newElement.className = 'vjs-control vjs-button ' + data.className;
			newElement.title = data.title;
		    //newLink.innerHTML = "<i class='fa " + data.icon + " line-height' title='"+ data.title +" aria-hidden='true'></i>";
		    newElement.appendChild(newLink);
		    controlBar = document.getElementsByClassName('vjs-control-bar')[0];
		    insertBeforeNode = document.getElementsByClassName('vjs-playback-rate')[0];
		    controlBar.insertBefore(newElement, insertBeforeNode);
		
		    return newElement;
		
		}
		
		@if(!is_null($previous_lesson))
			var btnPrevious = addNewButton({
			    player: player,
			    //icon: "fa-twitter",
			    id: 'previous-lesson',
			    title: 'Previous Lesson',
			    className: 'vjs-previous-control'
			});
			
			btnPrevious.onclick = function() {
				window.location.href = "{{ route('frontend.course.play', [$course, $previous_lesson] )  }}";
			};
		@endif
		
		/*
		var btnCompleted = addNewButton({
		    player: player,
		    id: 'mark-as-complete',
		    title: 'Mark Lesson as completed',
		    className: 'vjs-completed-control text-warning'
		});
		
		btnPrevious.onclick = function() {
			alert('Completed');
		};
		
		
		var btnUnCompleted = addNewButton({
		    player: player,
		    id: 'mark-as-uncompleted',
		    title: 'Mark Lesson as uncompleted',
		    className: 'vjs-completed-control text-success'
		});
		
		btnPrevious.onclick = function() {
			alert('Completed');
		};
		*/
		@if(!is_null($next_lesson))
			var btnNext = addNewButton({
			    player: player,
			    id: "next-lesson",
			    title: "{{ __('t.next') }}",
			    className: 'vjs-next-control'
			});
			
			btnNext.onclick = function() {
			    window.location.href = "{{ route('frontend.course.play', [$course, $next_lesson] )  }}";
			};
		@endif
		
		@if($lesson->attachments->count())
			var btnDownloadResources = addNewButton({
			    player: player,
			    id: 'download-resources',
			    title: "{{ __('t.download-attachments') }}",
			    className: 'vjs-download-resources-control text-warning'
			});
			
			btnDownloadResources.onclick = function() {
				
			};
		@endif
		
            
        </script>
    @endif
    
	<script>
		$(document).ready(function(){
			
			$('.player-container').show();
			
			// show-hide headers
            var i = null;
			$('#video-container').mousemove(function(){
			    clearTimeout(i);
				$('.video-header').css( "opacity", 1 );
				$('.control-bar').removeClass('fade-out');
				
				i = setTimeout(function(){
				    $('.video-header').css( "opacity", 0 );
				    $('.control-bar').addClass('fade-out');
				}, 2500)

			}).mouseleave(function() {
                clearTimeout(i);
                $('.video-header').css( "opacity", 0 );
			    $('.control-bar').addClass('fade-out');
            });
			
			
			$('.icon-side-open').on('click', function(){
				$('#side-menu').css("width", "22.5rem");
				$('.icon-side-open').hide();
				$('.icon-side-close').show();
			})
			$('.icon-side-close').on('click', function(){
				$('#side-menu').css("width", 0);
				$('.icon-side-close').hide();
				$('.icon-side-open').show();
			})
			
			
			// prevent right-clicks
			
			
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
		})
	</script>

</body>
</html>
