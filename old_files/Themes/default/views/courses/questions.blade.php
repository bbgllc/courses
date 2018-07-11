@extends('layouts.master')

@section('title', __('t.questions') . ' | ' . $course->title)

@section('content')
    
    @include('courses.partials._course_enrolled_header')
    
    <section class="page-control content-bar ps-container">
        <div class="container">
            @include('includes._course_dashboard_menu')
        </div>
    </section>

    
    <!-- HOW IT WORKS -->
    <section class="pt-4 bg-gray pb-4">
        <course-questions inline-template course_id="{{$course->id}}" course_slug="{{$course->slug}}" v-cloak>
            <div class="container">
                <div class="card border-info">
                    <div class="card-body">
                        
                        <span v-show="!showDetail" v-cloak>
                            <span v-if="!showCreate && !showEdit">
                                <div class="row">
                                    <div class="col-md-10 col-xs-12 col-sm-12">
                                        <form class="form-horizontal" @submit.prevent="fetchQuestions()">
                                            <div class="input-group">
                                                <input type="text" v-model="keyword" @keyup="fetchQuestions()" class="form-control" placeholder=" {{ __('t.search') }}...">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-info" type="submit">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2 col-xs-12 col-sm-12">
                                        <span class="pull-right">
                                            <a href="#" class="btn btn-info btn-md" @click.prevent="showCreate=true">
                                                {{ __('t.ask-a-question') }}
                                            </a>
                                        </span>
                                    </div>
                               </div>
                               
                                <div class="row">
                                    <div class="col-12 mt-4">
                                        <div class="alert" v-if="questions && questions.length == 0">
                                             {{ __('t.no-question-matches-search-results') }}
                                        </div>
                                        
                                        <div class="media" v-for="question in questions" v-if="questions && questions.length > 0">
                                            <img class="d-flex align-self-start mr-3" :src="question.user.image" alt="" width="75">
                                            <div class="media-body">
                                                <span class="pull-right" v-if="question.user.can_edit">
                                                    <a href="#" @click.prevent="fetchEditQuestion(question.id)"><i class="fa fa-edit"></i></a>
                                                    <a href="#" @click.prevent="deleteQuestion(question.id)"><i class="fa fa-trash"></i></a>
                                                </span>
                                                <h5 class="mt-0">
                                                    <a :href="'/courses/'+course_slug+'/learn/question/'+question.slug">
                                                        @{{question.title}}
                                                    </a>
                                                </h5>
                                                <p class="mb-0" style="font-size:12px;">
                                                    @{{question.user.full_name}} &nbsp;<span class="text-muted">@{{question.created_at_human}}</span>
                                                    
                                                    <span class="text-success" v-if="question.has_been_answered">
                                                        <i class="fa fa-star"style="color: #4caf50;"></i> {{ __('t.answered') }}
                                                    </span>
                                                </p>
                                                <p>
                                                    @{{strippedHTML(question.body) | truncate(100)}}
                                                    <span class="text-muted pull-right">
                                                        @{{question.answers.length}} @{{question.answers.length | pluralize( "response" )}} 
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <a href="" class="btn btn-secondary btn-block mt-4" v-if="questions.length > 0 && current_page < total_pages" @click.prevent="fetchMoreQuestions()">
                                            {{ __('t.load-more') }}
                                        </a>
                                    </div>
                                </div>
                            </span>
                            
                            <!-- create form -->
                            <div class="row" v-if="showCreate">
                                <div class="col-12">
                                    <form @submit.prevent="storeQuestion">
                                        <div class="form-group">
                                            <input class="form-control" v-model="title" placeholder="{{ __('t.question-title') }}" />
                                        </div>
                                        <div class="form-group">
                                            <quill-editor
                                                v-model="body"
                                                :options="editorOption"
                                                @ready="onEditorReady($event)">
                                            </quill-editor>
                                        </div>
                                        
                                        <div class="form-group">
                                            <span class="pull-right">
                                                <a href="#" @click.prevent="resetForm()">{{ __('t.cancel') }}</a>
                                                <button type="submit" :class="(title=='') || (body=='') ? 'btn btn-sm btn-info' : 'btn btn-sm btn-info'" :disabled="(title=='') || (body=='') ? 'disabled':null">
                                                    {{ __('t.ask-question') }}
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- edit form -->
                            <div class="row" v-if="showEdit">
                                <div class="col-12">
                                    <form @submit.prevent="updateQuestion(editQuestion.id)">
                                        <div class="form-group">
                                            <input class="form-control" v-model="editQuestion.title"/>
                                        </div>
                                        <div class="form-group">
                                            <quill-editor
                                                v-model="editQuestion.body"
                                                :options="editorOption"
                                                @ready="onEditorReady($event)">
                                            </quill-editor>
                                        </div>
                                        
                                        <div class="form-group">
                                            <span class="pull-right">
                                                <a href="#" @click.prevent="showEdit=false">{{ __('t.cancel') }}</a>
                                                <button type="submit" :class="(editQuestion.title=='') || (editQuestion.body=='') ? 'btn btn-sm btn-info' : 'btn btn-sm btn-info'" :disabled="editQuestion.title=='' || editQuestion.body=='' ? 'disabled':null">
                                                    {{ __('t.update-question') }}   
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </span>    
                        
                    </div>
                </div>
            </div>
        </course-questions>
    </section>

@endsection


