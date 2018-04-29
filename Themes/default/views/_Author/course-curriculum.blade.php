@extends('layouts.master')

@section('title', app_name() . ' | ' . $course->title)

@section('content')
    
    
    @include('includes._title_header', ['title' => $course->title])
    
    
    <section class="content-area bg-gray pt-5 pb-5">
        <div class="container">
            
            <div class="row mb-4">
                
                <div class="col-md-3">
                    @include('includes._author_course_sidebar')
                </div>
                
                
                <div class="col-md-9">
                    
                    <div class="card border-info">
                        <div class="card-body" style="min-height:250px;">
                            <h4 class="text-info mb-4">{{__('t.course-curriculum')}}</h4>
                            
                            <course-sections :course="{{$course}}" inline-template v-cloak>
                                <div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                            <draggable style="min-height: 5px;" v-model="sections" :options="{'handle': '.dragme', pull: 'true'}" @change="updateSectionSort">
                                                <transition-group name="list-complete">
                                                    <div class="card card-body bg-gray mb-4" v-for="(section,index) in sections" :key="section.id" style="border:1px solid #dddddd;">
                                                        <div :id="'section-heading-'+section.id" class="row clearfix" v-if="!editing || (editing && editing_section.id != section.id)" 
                                                            style="height:50px;"
                                                            @mouseover="showIcons(section.id)"
                                                            @mouseout="hideIcons(section.id)"
                                                        >
                                                            <div class="col-md-8">
                                                                <b>{{__('t.section')}} @{{section.sortOrder}}:</b> @{{section.title}}
                                                            </div>
                                                            <div class="col-md-4">
                                                                <span class="pull-right action_links" style="display:none;">
                                                                    <a href="#" @click.prevent="setEditing(section.id)">{{__('t.edit')}}</a>&nbsp;&nbsp;
                                                                    <a href="#" @click.prevent="destroy(section.id)">{{__('t.delete')}}</a>&nbsp;&nbsp;
                                                                    <i class="fa fa-bars dragme" style="cursor:move;"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row clearfix" v-if="editing && editing_section.id == section.id">
                                                            
                                                            <div class="col-md-12">
                                                                <form class="form-horizontal">
                                                                    <div class="form-group">
                                                                        <div class="col font-weight-bold">
                                                                            {{__('t.section')}} @{{index+1}}:
                                                                        </div>
                                                                        <div class="col">
                                                                            <input type="text" class="form-control" v-model="editing_section.title" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col font-weight-bold"></div>
                                                                        <div class="col">
                                                                            <label>{{__('t.what-will-students-learn')}}</label>
                                                                            <input type="text" class="form-control" v-model="editing_section.objective" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col font-weight-bold"></div>
                                                                        <div class="col">
                                                                            <button type="button" class="btn btn-success btn-sm" @click.prevent="update">
                                                                                {{__('t.save')}}
                                                                            </button>
                                                                            <a href="#" @click.prevent="getSections">{{__('t.cancel')}}</a>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        
                                                        <course-lessons course_slug="{{$course->slug}}" :section="section"></course-lessons>
                                                        
                                                    </div> <!--/ end well -->
                                                </transition-group>
                                            </draggable>
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6" v-if="!creating_lesson && !creating_section"> 
                                            <button class="btn btn-info btn-block" @click.prevent="creating_section = !creating_section">
                                                <i class="fa fa-plus-square"></i>
                                                {{__('t.add-section')}}
                                            </button>
                                        </div>
                                        <div class="col-md-6" v-if="!creating_lesson && !creating_section">     
                                            <button class="btn btn-info btn-block" @click.prevent="creating_lesson = !creating_lesson">
                                                <i class="fa fa-plus-square"></i>
                                                {{__('t.add-lesson')}}
                                            </button>   
                                        </div>
                                        
                                        
                                        
                                        <div class="col-md-12" v-if="creating_section"> 
                                            
                                            <form class="form-horizontal">
                                               
                                                <div class="form-group" :class="{'has-error' : err.title}">
                                                    <div class="col font-weight-bold">
                                                        {{__('t.new-section')}}:
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control" v-model="title" />
                                                        <small class="text-danger" v-if="err.title">@{{ err.title[0] }}</small>
                                                    </div>
                                                </div>
                                                <div class="form-group" :class="{'has-error' : err.objective}">
                                                    <div class="col font-weight-bold">
                                                        {{__('t.objective')}}:
                                                    </div>
                                                    <div class="col">
                                                        <label>{{__('t.what-will-students-learn')}}</label>
                                                        <input type="text" class="form-control" v-model="objective" />
                                                        <small class="text-danger" v-if="err.objective">@{{ err.objective[0] }}</small>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col font-weight-bold"></div>
                                                    <div class="col">
                                                        <button type="button" class="btn btn-success btn-sm" @click.prevent="save">{{__('t.save')}}</button>
                                                        <a href="#" @click.prevent="getSections">{{__('t.cancel')}}</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <div class="col-md-12" v-if="creating_lesson"> 
                                            
                                            <form class="form-horizontal">
                                               
                                                <div class="form-group" :class="{'has-error' : err.title}">
                                                    <div class="col font-weight-bold">
                                                        {{__('t.new-lesson')}}:
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control" v-model="lesson_title" placeholder="{{__('t.title')}}" />
                                                        <small class="text-danger" v-if="err.title">@{{ err.title[0] }}</small>
                                                    </div>
                                                </div>
                                                <div class="form-group" :class="{'has-error' : err.lesson_type}">
                                                    <div class="col font-weight-bold">
                                                        {{__('t.lesson-type')}}:
                                                    </div>
                                                    <div class="col">
                                                        <select class="form-control" v-model="lesson_type">
                                                            <option value="lecture">{{__('t.lecture')}}</option>
                                                            <option value="quiz">{{__('t.quiz')}}</option>
                                                        </select>
                                                        <small class="text-danger" v-if="err.lesson_type">@{{ err.lesson_type[0] }}</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group" :class="{'has-error' : err.description}" v-if="lesson_type && lesson_type !== 'quiz'">
                                                    <div class="col font-weight-bold">
                                                        {{__('t.description')}}:
                                                    </div>
                                                    <div class="col">
                                                        <label>{{__('t.what-is-this-lesson-about')}}</label>
                                                        <input type="text" class="form-control" v-model="lesson_description" />
                                                        <small class="text-danger" v-if="err.description">@{{ err.description[0] }}</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group" v-if="lesson_type && lesson_type !== 'quiz'">
                                                    <div class="col font-weight-bold">
                                                        {{trans('t.set-as-free-preview')}}:
                                                    </div>
                                                    <div class="col">
                                                        <select class="custom-select" id="inputGroupSelect01" v-model="preview_lesson">
                                                            <option value="1">{{trans('t.yes')}}</option>
                                                            <option value="0">{{trans('t.no')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <!--
                                                <div class="form-group" v-if="lesson_type && lesson_type !== 'quiz'">
                                                    <div class="col font-weight-bold"></div>
                                                    <div class="col">
                                                        <label class="custom-control custom-checkbox">
                                                          <input type="checkbox" class="custom-control-input" :value="preview_lesson" v-model="preview_lesson">
                                                          <span class="custom-control-indicator"></span>
                                                          <span class="custom-control-description">{{__('t.set-as-free')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                -->
                                                
                                                <div class="form-group">
                                                    <div class="col font-weight-boldt"></div>
                                                    <div class="col">
                                                        <button type="button" class="btn btn-success btn-sm" @click.prevent="storeLesson">{{__('t.save')}}</button>
                                                        <a href="#" @click.prevent="getSections">{{__('t.cancel')}}</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                </div>
                            </course-sections>
                            
                        </div>
                    </div>
                    
                    
                </div>
            </div>

            
        </div>
    </section>

@endsection

@push('after-scripts')
    <style type="text/css">
        .popover {
            min-width: 100px !important;
        }
    </style>
    <script>
   
        $(function () {
          $('[data-toggle="popover"]').popover();
        })
        
        $(document).on('click',".cancelPop", function () {
            $('[data-toggle="popover"]').popover('hide');
        });
        
    </script>
@endpush