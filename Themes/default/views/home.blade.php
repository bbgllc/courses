@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.home'))

@section('after-styles')
    <style type="text/css">
        .nav-tabs {
            border-bottom: 0px solid #ddd;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #000;
            font-size: 1rem;
            margin-right: 6px;
            margin-left: 6px;
            transition: all .2s;
            font-weight: 400;
            padding: 10px 15px;
        }
        
        .nav-tabs .nav-link.active{
            border-bottom: 1px solid #008cc9;
            text-decoration: none;
            background: transparent;
            border-bottom-width: 2px;
            font-weight: 400;
        }
        
        .nav-tabs .nav-link:hover{
            border-bottom: 1px solid #008cc9;
            border-bottom-width: 2px;
        }
        .home-hero{
            background-image: url(/img/frontend/homepage-image.jpg);
            background-color: rgba(0,0,0,1);
            height: 350px;
            min-height: 350px;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top 21% center;
            padding: 0;
            color: #fff;
            overflow: visible;
            z-index: 2;
        }
        
        .cover{
            background: rgba(0,0,0,.6);
            height: 100%;
            
        }
        
        .hero-text{
            padding: 5% 12%;
            width: 100%;
            height: 100%;
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 5%;
        }
        
        .hero-text h1{
            font-size: 38px;
        }
        .btn:focus{
            text-shadow: none !important;
            box-shadow: none !important;
        }
    </style>
@stop

@section('content')
    
    
    <!-- Jumbotron -->
    
    <div class="jumbotron jumbotron-fluid paral home-hero">
        <div class="cover">
            <div class="container hero-text">
                
                <h1 class="display-3">{{ __('t.learn-new-skill') }}</h1>
                <p class="lead">
                    {{ __('t.hero-small-text') }}
                </p>
                
                <p class="leadx mt-5">
                    <a class="btn btn-warning btn-lg font-weight-bold" href="/register" role="button">
                        {{ __('t.get-started') }}   
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    
    
    <!-- HOW IT WORKS -->
    <home inline-template :categories="{{$categories}}" v-cloak>
        <section class="pt-5 bg-gray">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tab-content">
                            <div id="course-list" class="text-centerx">
                                <div class="col-md-6 offset-md-3 text-center">
                                    <h3>{{ __('t.browse-our-top-courses') }}</h3>
                                    <hr />
                                </div>
                                
                                <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                                    <li class="nav-item" v-for="cat in categories">
                                        <a class="nav-link" :class="{'active' : category.id == cat.id }" :id="'cattab-'+cat.id" 
                                            @click.prevent="fetchCourses(cat)" href="">
                                            @{{ cat.name }}
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-contentx pt-4">
                                    <div class="tab-pane">
                                        <div class="row">
                                            <div class="col-sm-6 col-thumb col-md-3" v-if="courses" v-for="course in courses">
                                                <transition-group name="fade" mode="out-in">
                                                    <course global_coupon="{{ !is_null($global_coupon) }}" :key="course.id" :course="course"></course>
                                                </transition-group>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                               
                               
                                
                            </div>
                            
                            
                        </div>
                    </div>   
                    
                    <div class="col-md-6 offset-md-3 text-center">
                        <hr />
                        
                        <a class="btn btn-outline-secondary" :href="'/courses?category='+category.slug">
                            {{ __('t.browse-courses-in') }} @{{ category.name }}
                        </a>
                        <!--
                        <ul class="pager" v-if="courses.length > 0 && current_page < total_pages">
        				    <li>
        				        <a href="" rel="next" @click.prevent="fetchMoreCourses(cat_id)">
        				            <i class="fa fa-angle-right"></i>
    				            </a>
				            </li>
        				</ul>
        				-->
                        
                    </div>
                    
                </div> 
            </div>
        </section>
    </home>
@endsection

@push('after-scripts')
    
    <style type="text/css">
        .pager {
            padding-left: 0;
            margin: 20px 0;
            text-align: center;
            list-style: none;
        }
        .pager li {
            display: inline;
        }
        .pager li > a, .pager li > span {
            display: inline-block;
            padding: 10px 20px;
            background-color: #fff;
            border: 1px solid #FF5454;
            border-radius: 100px;
            color: #F40808;
        }
    </style>
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
        
        $(document).ready(function(){
            
            setTimeout(function(){
                $('#loading').fadeOut('fast');
                //$('#course-list').removeClass('d-none');
                $('#course-list').fadeIn('slow');    
            }, 500)
        })
        
    </script>
@endpush
