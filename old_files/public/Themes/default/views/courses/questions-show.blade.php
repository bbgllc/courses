@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.questions'))

@section('content')
    
    
    @include('courses.partials._course_enrolled_header')
    
    <section class="page-control content-bar ps-container">
        <div class="container">
            @include('includes._course_dashboard_menu')
        </div>
    </section>

    
    <!-- HOW IT WORKS -->
    <section class="pt-4 bg-gray pb-4">
        <div class="container">
            <div class="card border-info">
                <div class="card-body">
                    
                    <a href="{{route('frontend.user.questions.index', $course)}}">
                        <i class="fa fa-angle-double-left"></i> {{ __('t.back-to-questions') }}
                    </a>
                    <hr />
                    
                    <div class="media">
                        <img class="d-flex mr-3" src="{{ $question->user->picture }}" width="100" alt="">
                        <div class="media-body">
                            <h5 class="mt-0 mb-2">
                                {{$question->title}}
                            </h5>
                            <h6 class="mt-0">
                                {{ __('t.by') }} <a href="{{route('frontend.user', $question->user->username)}}">{{ $question->user->full_name }}</a> <span class="text-muted">{{$question->created_at->diffForHumans()}}</span>
                            </h6>
                            {!! $question->body !!}
                            
                            <hr />
                            <question-follow inline-template question_id="{{$question->id}}" v-cloak>
                                <span>
                                    <a href="" class="btn btn-sm btn-success" v-if="!user_is_following" @click.prevent="followQuestion">
                                        {{ __('t.follow') }}
                                    </a>
                                    <a href="" class="btn btn-sm btn-danger" v-if="user_is_following" @click.prevent="followQuestion">
                                        {{ __('t.unfollow') }}
                                    </a>
                                </span>
                            </question-follow>
                        </div>
                    </div> 
                </div>
            </div>
            
            <question-comments question_id="{{$question->id}}" inline-template v-cloak>
                <div class="card bg-light mt-4">
                    <div class="card-body">
                        <h6>{{ __('t.post-a-reply') }}</h6>
                        <div id="respond">
                            <form @submit.prevent="storeAnswer()">
                                <div class="form-group">
                                    <quill-editor
                                        v-model="body"
                                        :options="editorOption"
                                        @ready="onEditorReady($event)">
                                    </quill-editor>
                                </div>
                                <div class="form-submit pull-right">
                                    <input type="submit" value="{{ __('t.post-reply') }}" :disabled="body=='' ? 'disabled':null" :class="body=='' ? 'btn btn-info btn-sm' : 'btn btn-success btn-sm'">
                                </div>
                            </form>
                        </div>
                        <div class="clearfix"></div>
                        
                        <h6>{{ __('t.comments') }}</h6>
                        <div class="media mb-4 p-2" v-for="answer in answers" :class="answer.marked_as_answer && !showEdit ? 'bg-gray' : ''">
                            <img class="d-flex mr-3" :src="answer.user.image" alt="" width="70">
                            
                            <div class="media-body">
                                <h6 class="mt-0">
                                    <a :href="'/user/'+answer.user.username">
                                        @{{answer.user.full_name}}
                                    </a>
                                    <span class="text-muted">@{{answer.created_at_human}}</span>
                                    <span class="text-success pull-right" v-if="answer.marked_as_answer">{{ __('t.best-answer') }}</span>
                                </h6>
                                <span v-html="answer.description"></span>  
                                
                                <div v-if="showEdit && editAnswer.id == answer.id">
                                    <form @submit.prevent="updateAnswer(answer.id)">
                                        <quill-editor
                                            v-model="editAnswer.description"
                                            :options="editorOption"
                                            @ready="onEditorReady($event)">
                                        </quill-editor>
                                        <div class="form-submit mt-2">
                                            <input type="submit" value="{{ __('t.update') }}" :disabled="editAnswer.body=='' ? 'disabled':null" :class="editAnswer.body=='' ? 'btn btn-info btn-sm pull-right' : 'btn btn-success btn-sm pull-right'">
                                        </div>
                                    </form>
                                </div>
                                <div class="mt-2">
                                    <span class="pull-left" v-if="!answer.marked_as_answer && !showEdit">
                                        <a href="#" @click.prevent="markAsAnswer(answer.id)">
                                            {{ __('t.mark-as-answer') }}   
                                        </a>
                                    </span>
                                    <span class="pull-right" v-if="answer.user.can_edit">
                                        <a href="#" @click.prevent="fetchEditAnswer(answer.id)" v-if="answer.user.can_edit && !showEdit"><i class="fa fa-edit"></i></a>
                                        <a href="#" v-if="showEdit && editAnswer.id == answer.id" @click.prevent="showEdit=false">{{ __('t.cancel') }}</a> &nbsp; &nbsp;
                                        <a href="#" @click.prevent="deleteAnswer(answer.id)" v-if="answer.user.can_edit && !showEdit" class="text-danger"><i class="fa fa-trash"></i></a>
                                    </span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </question-comments>
        </div>
            
    </section>

@endsection


