<course-header inline-template :course="{{$course}}" v-cloak>
    <div class="jumbotron jumbotron-fluid text-md-left paral-title paralsec pt-4 pb-4" v-if="course">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    @if(auth()->user()->hasCompletedCourse($course))
                        <a :href="'/course/{{$course->slug}}/learn/v1/'+first_lesson.uid">
                            <div class="video-thumbnail">
                                <img class="card-img-top" src="{{ $course->coverImage }}" alt="">
                            </div>
                        </a>
                    @else
                        <a :href="'/course/{{$course->slug}}/learn/v1/'+last_watched.uid" v-if="last_watched" >
                            <div class="video-thumbnail">
                                <img class="card-img-top" src="{{ $course->coverImage }}" alt="">
                            </div>
                        </a>
                        <a :href="'/course/{{$course->slug}}/learn/v1/'+first_lesson.uid" v-else >
                            <div class="video-thumbnail">
                                <img class="card-img-top" src="{{ $course->coverImage }}" alt="">
                            </div>
                        </a>
                    @endif
                </div>
                <div class="col-md-8">
                    <h1 class="display-3">{{ $course->title }}</h1>
                    <p class="lead">{{ $course->subtitle }}</p>
                        <div class="row">
                            <div class="col-md-10">
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" 
                                        aria-valuenow="{{ auth()->user()->percentCompleted($course) }}" 
                                        aria-valuemin="0" aria-valuemax="100" 
                                        style="width:{{ auth()->user()->percentCompleted($course) }}%">
                                      <span class="sr-only">{{ auth()->user()->percentCompleted($course) }}% {{ __('t.completed') }}</span>
                                    </div> 
                                </div>
                                <p style="margin-bottom:10px;">
                                    <span class="font-weight-bold">
                                        {{ auth()->user()->percentCompleted($course) }}% {{ __('t.completed') }}.
                                    </span>
                                    @if(auth()->user()->hasCompletedCourse($course))
                                        <span class="font-weight-bold pull-right">
                                            <a href="{{route('frontend.user.certificate.download', $course)}}" class="text-white">
                                                {{ __('t.download-course-certificate') }}
                                            </a>
                                        </span>
                                    @else
                                        <span class="font-weight-bold">
                                            </span> {{ __('t.you-will-receive-certificate') }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-2 d-none d-md-block">
                                @if(!auth()->user()->hasCompletedCourse($course))
                                    <i class="fa fa-trophy fa-3x" aria-hidden="true" style="margin-top:-20px;"></i>
                                @else
                                    <span class="fa-stack fa-lg" style="margin-top:-20px;">
                                        <i class="fa fa-trophy fa-3x fa-stack-2x"></i>
                                        <i class="fa fa-check fa-stack-1x text-success"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="col">
                                @if(auth()->user()->hasCompletedCourse($course))
                                    <a :href="'/course/{{$course->slug}}/learn/v1/'+first_lesson.uid" 
                                        class="btn btn-md btn-warning rounded-0">
                                        {{ __('t.restart-course') }}
                                    </a>
                                @else
                                    <span v-if="last_watched" v-cloak>
                                        <a :href="'/course/{{$course->slug}}/learn/v1/'+last_watched.uid" 
                                            class="btn btn-md btn-warning rounded-0">
                                            {{ __('t.continue-watching') }}
                                        </a>
                                    </span>
                                    <span v-else v-cloak>
                                        <a :href="'/course/{{$course->slug}}/learn/v1/'+first_lesson.uid" 
                                            class="btn btn-md btn-warning rounded-0">
                                            {{ __('t.start-course') }}
                                        </a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    <p v-if="user_rating" v-cloak>
                        <stars :rating="user_rating.rating" size="14"></stars>
                        <span> {{ __('t.your-rating') }}</span>
                    </p>
                    <p v-else v-cloak>
                        <stars :rating="0" size="14"></stars>
                        <span> {{ __('t.you-have-not-rated') }}</span>
                       
                    </p>
                </div>
            </div>
        </div>
    </div>
</course-header>