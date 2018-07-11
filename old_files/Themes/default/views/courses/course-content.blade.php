@extends('layouts.master')

@section('title', app_name() . ' | ' . $course->title)

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
            
            <div class="row">   
                <div class="col-12">
                    <h4 class="text-info">
                        {{ __('t.course-content') }}
                    </h4>
                    <hr class=""/>
                </div>
                
                <course-content inline-template :course="{{$course}}" v-cloak>
                    <div class="col-md-12" v-if="course">
                        <h5 class="caption-heading">
                            <form class="form-horizontal" @submit.prevent="fetchSections()">
                                <div class="input-group">
                                    <input type="text" v-model="keyword" @keyup="fetchSections()" class="form-control" placeholder="{{ __('t.search') }}...">
                                    <div class="input-group-btn">
                                        <button class="btn btn-info" type="submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </h5>
                        <div class="section-outline mb-4">
                            
                            <div v-for="(section,index) in sections" v-if="section.lessons.length > 0" class="bg-light mb-4">
                                <a data-toggle="collapse" :href="'#section-'+section.id" 
                                    class="section-title collapsed" aria-expanded="false">
                                    <h4 class="tit-section xsmx bg-light mt-2 h6"> 
                                        {{ __('t.section') }} @{{section.sortOrder}}: @{{ section.title }}
                                        <div class="pull-right">
                                            <percent-section-completed inline-template :section="section.id" 
                                                :percent_completed="section.percent_completed" v-cloak>
                                                <span class="h6 text-success">
                                                    @{{percent_section_completed}}% {{ __('t.completed') }}
                                                </span>
                                            </percent-section-completed>
                                            <i class="fa fa-angle-down"></i>
                                        </div>
                                    </h4>
                                </a>
                                
                                <ul :id="'section-'+section.id" class="section-list collapse" aria-expanded="false" style="height: 1px;">
                                    
                                    <span v-for="lesson in section.lessons">
                                        <mark-as-complete inline-template :lesson="lesson" :course="course" v-cloak
                                            :hasCompletedLesson="lesson.user_completed">
                                            <li>
                                                <div class="count">
                                                    <span v-if="userHasCompleted" class="fa fa-check-circle text-success"></span>
                                                
                                                    <span v-if="! userHasCompleted" class="fa fa-circle-o"></span>
                                                </div> 
                                                <div class="list-body">
                                                    <p><a :href="'/course/'+course.slug+'/learn/v1/'+lesson.uid">@{{lesson.title}}</a></p> 
                                                    <p class="mb-0 font-italicx text-muted">@{{lesson.description}}</p>
                                                    <div class="data-lessons clearfix"></div>
                                                </div> 
                                                
                                                <a href="#" class="mc-btn-2">
                                                    <span v-if="lesson.content_type == 'quiz'">
                                                        <i class="fa fa-question-circle"></i> {{ __('t.quiz') }}
                                                    </span>
                                                    <span v-if="lesson.content_type == 'article'">
                                                        <i class="fa fa-file-text-o"></i> {{ __('t.article') }} 
                                                    </span>
                                                    <span v-if="lesson.content_type == 'video'">
                                                        <i class="fa fa-play-circle"></i> @{{ lesson.minutes_seconds }}
                                                    </span>
                                                    <!--
                                                        &nbsp;&nbsp;
                                                    <span class="fa fa-check-circle text-success" v-if="userHasCompleted"
                                                        data-toggle="tooltip" title="{{ __('t.toggle-completion') }}"
                                                        @click.prevent="markAsCompleted()">
                                                    </span>
                                                        
                                                    <span class="fa fa-circle-o" v-if="!userHasCompleted"
                                                        data-toggle="tooltip" title="{{ __('t.toggle-completion') }}"
                                                        @click.prevent="markAsCompleted()">
                                                    </span>
                                                    -->
                                                </a>
                                            </li>
                                        </mark-as-complete>
                                    </span>
            		            </ul>
        		            </div>
        		            
    		            </div>
    		            
                    </div>
                </course-content>
            </div>
        </div>
    </section>

@endsection


