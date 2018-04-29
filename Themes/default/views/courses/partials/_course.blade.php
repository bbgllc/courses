<div class="col-sm-6 col-thumb {{ \Request::route()->getName() == 'frontend.courses' ? 'col-md-4' : 'col-md-3' }}">
  	
  	<div class="thumbnail ">
    	<div class="caption">
			<p class="author p-author">
			    @if($course->featured)
        			<a href="#" class="badge badge-success btn-pro-xs pull-right" data-toggle="tooltip" title="{{ __('t.featured') }}">
    					<i class="fa fa-shield" aria-hidden="true"></i>
        			</a>
    			@endif
    			<a href="{{route('frontend.user', $course->author->username)}}" class="myicon-right">
    				<img src="{{ $course->author->picture }}" width="20" height="20" class="rounded-circle img-avatar-shots">
				</a>
    			<a href="{{route('frontend.user', $course->author->username)}}" class="myicon-right text-decoration-none" title="">
					{{ str_limit($course->author->name, 25) }}
    			</a>
    			
			</p>
		</div><!-- /caption -->
    	<a class="position-relative btn-block course-img" href="{{route('frontend.course.show', $course)}}" data-toggle="popover" data-container="body" data-placement="right" id="{{$course->id}}">
    		<img src="{{ $course->thumbnail }}" class="image-url img-responsive btn-block pop">
		</a>	
		<div class="caption">
			<h1 class="title-shots">
				<a href="{{route('frontend.course.show', $course)}}" data-toggle="tooltip" title="{{$course->title}}" class="item-link" >
				    {{ str_limit($course->title, 25) }} 
				</a>
			</h1>
			<div class="profile-rating">
			    <a href="{{ route('frontend.courses', ['category' => $course->category->slug] ) }}" style="font-size: 0.8em;">
                    {{ $course->category->name }}
                </a>
            </div>
			<div class="profile-rating">
                <stars :rating="{{$course->average_rating}}" size="14"></stars> <span class="text-muted">({{$course->reviews->count()}})</span>
            </div>
            
		</div><!-- /caption -->
    	<div class="caption">
			<p class="actions">
			    @if(auth()->check() && !auth()->user()->canAccessCourse($course))
			        <course-bookmark inline-template slug="{{$course->slug}}" v-cloak>
			            <span>
    			            <a href="#" class="btn btn-sm pull-left btn-success btn-like likeButton" @click.prevent="addToWishlist()" v-show="!userHasBookmarked">
                                <i class="fa fa-heart-o" data-toggle="tooltip" data-placement="top" title="{{ __('t.add-to-wishlist') }}"></i>
                            </a>
                            <a href="#" class="btn btn-sm pull-left btn-danger btn-like likeButton" @click.prevent="addToWishlist()" v-show="userHasBookmarked">
                                <i class="fa fa-heart" data-toggle="tooltip" data-placement="top" title="{{ __('t.remove-from-wishlist') }}"></i>
                            </a>
        				</span>
    				</course-bookmark>
				@endif
				
    			<span class="pull-right">
    			    @if(!is_null($global_coupon) && $course->price > 0)
                        <span class="discount">
                            {{ Gabs::currency_string($course->price)}}
                        </span>
                    @endif
                    &nbsp;
                    
                    <span class="final_price">
                        {{ Gabs::currency_string($course->final_price) }}
                    </span>
    			    <!--
					<i class="fa fa-heart myicon-right"></i> <span class="like_count myicon-right strongSpan">22</span>
					<i class="fa fa-eye myicon-right"></i> <span class="myicon-right strongSpan">2.1k</span>
					<i class="fa fa-comment myicon-right"></i> <span class="myicon-right strongSpan">20</span>
					-->
				</span>
			</p>
		</div><!-- /caption -->		
    </div><!-- /thumbnail -->
</div>

<!-- POPUP -->
<div id="popover-course-{{$course->id}}" class="d-none border-info">
    <div class="card p-0 border-0">
        <div class="card-body p-0">
            <p class="text-muted mb-2 m">{{ __('t.last-updated') }}: {{$course->updated_at->format('m/Y')}}</p>
            <h5 class="mb-0">{{$course->title}}</h5>
            <p class="mb-0">{{$course->subtitle}}</p>
            <p class="text-info mb-1">
                <span class="text-muted">{{$course->category->parentCategory->name}} / </span>
                <a href="{{ route('frontend.courses', ['category' => $course->category->slug] ) }}">{{$course->category->name}}</a>
            </p>
            
            <ul class="list-inline">
                @if($course->total_hours > 0)
                    <li class="list-inline-item"><i class="fa fa-file-video-o"></i> {{$course->total_hours}} {{str_plural( __('t.hour'), $course->total_hours)}}</li>
                @endif
                @if($course->total_articles > 0)
                    <li class="list-inline-item"><i class="fa fa-file-text-o"></i> {{$course->total_articles}} {{str_plural(__('t.article'), $course->total_articles)}}</li>
                @endif
                @if($course->total_quizzes > 0)
                    <li class="list-inline-item"><i class="fa fa-question-circle"></i> {{$course->total_quizzes}} {{str_plural(__('t.quiz'), $course->total_quizzes)}}</li>
                @endif
                <li class="list-inline-item"><i class="fa fa-wifi"></i> {{ucfirst($course->level)}} {{ __('t.level') }}</li>
            </ul>
            <hr />
            {!! str_limit($course->description, 400) !!}
            <!--
            <hr />
            <a href="{{route('frontend.course.show', $course)}}" class="btn btn-danger btn-block">{{ __('t.course-details') }}</a>
            -->
        </div>    
    </div>
</div>