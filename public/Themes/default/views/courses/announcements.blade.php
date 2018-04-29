@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.announcements'))

@section('content')
    
    
    @include('courses.partials._course_enrolled_header')
    
    <section class="page-control content-bar ps-container">
        <div class="container">
            @include('includes._course_dashboard_menu')
        </div>
    </section>

    
    <!-- HOW IT WORKS -->
    <section class="pt-4 bg-gray pb-4">
        <announcements inline-template course_id="{{$course->id}}" course_slug="{{$course->slug}}" v-cloak>
            <div class="container">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <form class="form-horizontal" @submit.prevent="fetchAnnouncements()">
                                    <div class="input-group">
                                        <input type="text" v-model="keyword" @keyup="fetchAnnouncements()" class="form-control" placeholder="{{ __('t.search') }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-info" type="submit">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                       </div>
                       
                        <div class="row">
                            <div class="col-12 mt-4">
                                <div class="alert" v-if="announcements && announcements.length == 0">
                                    {{ __('t.no-announcements-found') }}
                                </div>
                                
                                <div class="media" v-for="announcement in announcements" v-if="announcements && announcements.length > 0">
                                    <img class="d-flex align-self-start mr-3" :src="announcement.user_image" alt="" width="75">
                                    <div class="media-body">
                                        <h5 class="mt-0">
                                            <a :href="'/course/'+course_slug+'/learn/announcement/'+announcement.slug">
                                                @{{announcement.title}}
                                            </a>
                                        </h5>
                                        <p class="mb-0" style="font-size:12px;">
                                            @{{announcement.user_name}} &nbsp;<span class="text-muted">@{{announcement.created_at_human}}</span>
                                        </p>
                                        <p>
                                            @{{strippedHTML(announcement.body) | truncate(150)}}
                                            <a :href="'/courses/'+course_slug+'/learn/announcement/'+announcement.slug">
                                                {{ __('t.read-more') }} <i class="fa fa-angle-double-right"></i>
                                            </a>
                                        </p>
                                        
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <a href="" class="mt-4" v-if="announcements.length > 0 && current_page < total_pages" @click.prevent="fetchMoreAnnouncements()">
                                        <i class="fa fa-refresh"></i> {{ __('t.load-more') }}...
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </announcements>
    </section>

@endsection


