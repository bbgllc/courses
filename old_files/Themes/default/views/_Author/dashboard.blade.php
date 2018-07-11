@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.instructor-dashboard'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.instructor-dashboard')])
    
    <author-courses inline-template v-cloak>
        <div>
            <div class="col-md-12 col-xs-12 pt-1 pb-1 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="text-muted mb-0">{{__('t.total-revenue')}}</p>
                            <h5 class="mb-2">{{Gabs::currency(auth()->user()->total_earnings())}}</h5>
                        </div>
                        
                        <div class="col-md-3">
                            <p class="text-muted mb-0">{{__('t.average-rating')}}</p>
                            <h5 class="mb-2">{{auth()->user()->average_rating()}}</h5>
                        </div>
                        
                        <div class="col-md-3">
                            <p class="text-muted mb-0">{{__('t.total-students')}}</p>
                            <h5 class="mt-0">{{auth()->user()->total_students()}}</h5>
                        </div>
                        
                        <div class="col-md-3">
                            <p class="text-muted mb-0">{{__('t.unanswered-questions')}}</p>
                            <h5 class=" mt-0">{{ $unanswered_questions }}</h5>
                        </div>
                        
                    </div>
                </div>
            </div>
            <section class="content-area bg-gray pt-5 pb-5">
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-5">
                            <div class="input-group">
                              <input type="text" class="form-control form-control-lg" v-model="q" @keyup="fetchCourses()" placeholder="{{__('t.search-for')}}..." aria-label="{{__('t.search-for')}}...">
                              <span class="input-group-btn">
                                <button class="btn btn-info" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                              </span>
                            </div>
                        </div>
                        <div class="col"></div>
                        <div class="col-2">
                            <select class="form-control form-control-md ui dropdown" v-model="sort_by" @change="fetchCourses()">
                                <option value>{{__('t.sort-by')}}...</option>
                                <option value="title_asc">{{__('t.title-az')}}</option>
                                <option value="title_desc">{{__('t.title-za')}}</option>
                                <option value="newest_first">{{__('t.newest-first')}}</option>
                                <option value="oldest_first">{{__('t.oldest-first')}}</option>
                            </select>
                        </div>
                        
                        <div class="col-2">
                            <a href="{{route('frontend.author.course.create')}}" class="btn btn-success btn-block">
                                <i class="fa fa-plus-circle"></i> {{__('t.new-course')}}
                            </a>
                        </div>
                        
                    </div>
                    
                    <div class="product-list-view">
                        <div class="single-item-list" v-for="course in courses">
                            <div class="item-img">
                                <img :src="course.cover_image" :alt="course.title" class="img-responsive">
                            </div>
                            <div class="item-content" :class="{ 'bg-gray' : course.status_code == 'Draft' }">
                                <div class="item-info">
                                    <div class="item-title">
                                        <h3><a href="#">@{{course.title}}</a></h3>
                                        <p class="mt-0">@{{course.subtitle}}</p>
                                        <span class="text-muted">
                                            {{__('t.by')}} <a href="#">@{{course.author.name}}</a> - @{{course.author.tagline}}
                                        </span>
                                        
                                        <div>
                                            <a :href="'/author/course/'+course.slug+'/curriculum'" class="btn btn-outline-secondary btn-sm p-1">
                        						{{__('t.manage')}}
                        					</a>
                        					
                        					<a  :href="'/course/'+course.slug" class="btn btn-outline-info btn-sm p-1" v-if="course.status_code != 'Draft'">
                        						 {{__('t.dashboard')}}
                        					</a>
                                        </div>
                                        
                                    </div>
                                    <div class="item-sale-info">
                                        <div class="price" v-if="course.access == 'Free'">
                                            {{ __('t.free') }}
                                        </div>
                                        <div class="price" v-if="course.access != 'Free'">
                                            @if(config('site_settings.site_currency_format') == 'front')
                                                {{ config('site_settings.site_currency_symbol') }}@{{course.final_price}}
                                            @else
                                                @{{course.final_price}}{{config('site_settings.site_currency_symbol')}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="item-profile">
                                    <div class="profile-rating-info">
                                        <ul v-if="course.status_code != 'Draft'">
                                            <li>
                                                <span v-html="course.status"></span>
                                            </li>
                                            <li>
                                                <i class="fa fa-users" aria-hidden="true"></i>
                                                @{{course.total_students}} @{{ course.total_students | pluralize(trans('t.student')) }}
                                            </li>
                                            <li>
                                                <i class="fa fa-money" aria-hidden="true"></i> 
                                                @if(config('site_settings.site_currency_format') == 'front')
                                                    {{ config('site_settings.site_currency_symbol') }}@{{course.total_sales}}
                                                @else
                                                    @{{course.total_sales}}{{config('site_settings.site_currency_symbol')}}
                                                @endif 
                                                {{__('t.total-earnings')}}
                                            </li>
                                            <li>
                                                <i class="fa fa-money" aria-hidden="true"></i> 
                                                @if(config('site_settings.site_currency_format') == 'front')
                                                    {{ config('site_settings.site_currency_symbol') }}@{{course.sales_this_month}}
                                                @else
                                                    @{{course.sales_this_month}}{{config('site_settings.site_currency_symbol')}}
                                                @endif
                                                {{__('t.earned-this-month')}}
                                            </li>
                                            <li><i class="fa fa-wifi" aria-hidden="true"></i> @{{course.level | capitalize}}</li>
                                            <li><stars :rating="course.average_rating" size="14"></stars> (@{{course.total_reviews}})</li>
                                        </ul>
                                        <ul v-else>
                                            <li>
                                                <span v-html="course.status"></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    
                </div>
            </section>
        </div>
    </author-courses>

@endsection

