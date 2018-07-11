@extends('layouts.master')

@section('title', app_name() . " | " . __('t.courses') )

@section('after-styles')

@stop

@section('content')
    
    
    @include('includes._title_header', ['title' => trans('t.courses') ])
    <section class="page-control p-2">
        <div class="container">
            <div class="row">
                <div class="page-info2 col-md-9">
        			@if (count(array_intersect(array_keys(request()->query()), array_keys($filters))))
                        <small><b>@lang('t.filtered-by'):</b></small>
                        @foreach($filters as $key => $value)
                            @if($value)
                                <span class="badge badge-pill badge-secondary p-2 font-weight-normal">
                                    {{ucfirst($key) . ': ' . ucfirst($value)}} 
                                    <a href="{{ route('frontend.courses', array_except(request()->query(), [$key, 'page'])) }}" class="text-white">
                                        <span class="fa fa-times"></span>
                                    </a> 
                                </span>
                            @endif
                        @endforeach
                        
                        @if(count($filters) > 1)
                            <span class="badge badge-pill badge-danger p-2">
                                @lang('t.all')
                                <a href="{{ route('frontend.courses') }}" class="text-white">
                                    <span class="fa fa-times"></span>
                                </a> 
                            </span>
                        @endif
                    @endif
        		</div>
        		<div class="page-view col-md-3">
        		    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if( isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] == 'recent_first')
        						@lang('t.recent-first')
        					@elseif( isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] == 'oldest_first')
        						@lang('t.oldest-first')
        					@elseif( isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] == 'price_asc')
        						@lang('t.price-asc')
        					@elseif( isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] == 'price_desc')
        						@lang('t.price-desc')
        					@elseif( isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] == 'highest_rating')
        						@lang('t.average-rating-desc')
        					@else
        						@lang('t.sort-results-by'):
        					@endif
                        </button>
                        <div class="dropdown-menu sortField" aria-labelledby="dropdownMenuButton">
                            @if(!isset($_REQUEST['sort_order']) || (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != 'recent_first'))
    							<a class="dropdown-item" href="{{ route('frontend.courses', array_merge(request()->query(), ['sort_order' => 'recent_first', 'page' => 1])) }}">
    								@lang('t.recent-first')
    							</a>
    						@endif
    						@if(!isset($_REQUEST['sort_order']) || (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != 'oldest_first'))
    							<a class="dropdown-item" href="{{ route('frontend.courses', array_merge(request()->query(), ['sort_order' => 'oldest_first', 'page' => 1])) }}">
    								@lang('t.oldest-first')
    							</a>
    						@endif
    						@if(!isset($_REQUEST['sort_order']) || (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != 'price_asc'))
    							<a class="dropdown-item" href="{{ route('frontend.courses', array_merge(request()->query(), ['sort_order' => 'price_asc', 'page' => 1])) }}">
    								@lang('t.price-asc')
    							</a>
    						@endif
    						@if(!isset($_REQUEST['sort_order']) || (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != 'price_desc'))
    							<a class="dropdown-item" href="{{ route('frontend.courses', array_merge(request()->query(), ['sort_order' => 'price_desc', 'page' => 1])) }}">
    								@lang('t.price-desc')
    							</a>
    						@endif
    						@if(!isset($_REQUEST['sort_order']) || (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != 'highest_rating'))
    							<a class="dropdown-item" href="{{ route('frontend.courses', array_merge(request()->query(), ['sort_order' => 'highest_rating', 'page' => 1])) }}">
    								@lang('t.average-rating-desc')
    							</a>
    						@endif
                        </div>
                    </div>
        		</div>
    		</div>
        </div>
    </section>
    
    <!-- HOW IT WORKS -->
    <section class="pt-5 bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="card border-infos mb-2 bg-light">
                        <div class="card-header p-2 font-weight-bold border-bottom-0">
                            @lang('t.refine-your-search')
                        </div>
                        <div class="card-body p-0">
                            <ul class="nav flex-column search-filters" id="search-filters">
                                @include('courses.partials._filters')
                            </ul>
                        </div>
                    </div>
                    
                </div>
            
                <div class="col-md-9">
                    <div class="tab-content">
                        <div id="course-list" style="display:none;">
                            
                            @if($courses->count())
                                <div class="infinite-scroll">
                                    @foreach($courses->chunk(3) as $collections)
                                        <div class="row">
                                            @each('courses.partials._course', $collections, 'course')
                                        </div>
                                    @endforeach
                                    
                                    {{ $courses->appends(request()->query())->links() }}
                                    
                                </div>
                                
                                <!--
                                <div class="col-md-12 text-center">
                                    <hr />
                                    <button class="btn btn-outline-secondary view-more-button">
                                        Load more...
                                    </button>
                                    {{-- $courses->render() --}}
                                </div>
                                -->
                            @else
                                <div class="col-md-12">
                                    @lang('t.no-courses-found')
                                </div>
                            @endif
                        </div>
                        <div id="loading" class="row">
                            @for($i=0; $i < 8; $i++)
                                <div class="col-md-3">
                                    <loader></loader>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>    
            </div> 
        </div>
    </section>

@endsection

@push('after-scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.9/jquery.jscroll.min.js"></script>
    <script type="text/javascript">
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<div class="col-md-12 text-center text-secondary"><i class="fa fa-circle-o-notch fa-spin fa-2x"></span></div>',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });
    </script>
    
    
    <script type="text/javascript">
       
       $("[data-toggle=popover]").each(function(i, obj) {

            $(this).popover({
                html: true,
                trigger: "hover",
                animation: true,
                content: function() {
                    var id = $(this).attr('id')
                    return $('#popover-course-' + id).html();
                }
            })
           
       });
       
       /*
        $("[data-toggle=popover]").each(function(i, obj) {

            $(this).popover({
                html: true,
                trigger: "manual",
                animation: true,
                content: function() {
                    var id = $(this).attr('id')
                    return $('#popover-course-' + id).html();
                }
            }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(this).siblings(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            }).on("mouseleave", function () {
                var _this = this;
                setTimeout(function () {
                    if (!$(".popover:hover").length) {
                        $(_this).popover("hide")
                    }
                }, 100);
            })
            
        });
        */
        
        $(document).ready(function(){
            
            setTimeout(function(){
                $('#loading').fadeOut('fast');
                //$('#course-list').removeClass('d-none');
                $('#course-list').fadeIn('slow');    
            }, 500)
        })
        
    </script>
    
    <style type="text/css">
        .modal.fade .modal-dialog {
             -webkit-transform: scale(0.6);
             -moz-transform: scale(0.6);
             -ms-transform: scale(0.6);
             transform: scale(0.6);
             -webkit-transition: all 0.3s;
             -moz-transition: all 0.3s;
             transition: all 0.3s;
             top: 400px;
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
