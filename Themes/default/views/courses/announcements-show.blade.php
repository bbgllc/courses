@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.announcements'))

@section('after-styles')
    <style type="text/css">
        input, textarea {
          outline: none;
          border: none;
          display: block;
          margin: 0;
          padding: 0;
          -webkit-font-smoothing: antialiased;
          font-family: "PT Sans", "Helvetica Neue", "Helvetica", "Roboto", "Arial", sans-serif;
          font-size: 1rem;
          color: #555f77;
        }
        input::-webkit-input-placeholder, textarea::-webkit-input-placeholder {
          color: #ced2db;
        }
        input::-moz-placeholder, textarea::-moz-placeholder {
          color: #ced2db;
        }
        input:-moz-placeholder, textarea:-moz-placeholder {
          color: #ced2db;
        }
        input:-ms-input-placeholder, textarea:-ms-input-placeholder {
          color: #ced2db;
        }
        .comments {
          margin: 0 auto 0;
          max-width: 60.75rem;
          padding: 0 1.25rem;
        }
        
        .comment-wrap {
          margin-bottom: 1.25rem;
          display: table;
          width: 100%;
          min-height: 5.3125rem;
        }
        
        .photo {
          padding-top: 0.625rem;
          display: table-cell;
          width: 3.5rem;
        }
        .photo .avatar {
          height: 2.25rem;
          width: 2.25rem;
          border-radius: 50%;
          background-size: contain;
        }
        
        .comment-block {
          padding: 1rem;
          background-color: #fff;
          display: table-cell;
          vertical-align: top;
          border-radius: 0.1875rem;
          box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.08);
        }
        .comment-block textarea {
          width: 100%;
          resize: none;
        }
        
        .comment-text {
          margin-bottom: 1.25rem;
        }
        
        .bottom-comment {
          color: #acb4c2;
          font-size: 0.875rem;
        }
        
        .comment-date {
          float: left;
        }
        
        .comment-actions {
          float: right;
        }
        .comment-actions li {
          display: inline;
          margin: -2px;
          cursor: pointer;
        }
        .comment-actions li.complain {
          padding-right: 0.75rem;
          border-right: 1px solid #e1e5eb;
        }
        .comment-actions li.reply {
          padding-left: 0.75rem;
          padding-right: 0.125rem;
        }
        .comment-actions li:hover {
          color: #0095ff;
        }
    </style>
@endsection 

@section('content')
    
    @include('courses.partials._course_enrolled_header')
    
    <section class="page-control content-bar ps-container">
        <div class="container">
            @include('includes._course_dashboard_menu')
        </div>
    </section>

    
    <!-- HOW IT WORKS -->
    <section class="pt-4 pb-4">
        <div class="container">
            <a href="{{route('frontend.user.announcements.index', [$course])}}" class="mb-4">
                <i class="fa fa-angle-double-left"></i> {{ __('t.back-to-announcements') }}
            </a>
            <div class="card mb-4">
                <div class="card-body">
                    
                    <div class="media">
                        <img class="d-flex mr-3" src="{{ $course->author->picture }}" width="100" alt="">
                        <div class="media-body">
                            <h5 class="mt-0 mb-2">
                                {{$announcement->title}}
                                <span class="text-muted">{{$announcement->created_at->diffForHumans()}}</span>
                            </h5>
                            
                            {!! $announcement->body !!}
                            
                        </div>
                    </div> 
                </div>
                <div class="card-footer">
                    
                    <!-- comments -->
                    <announcement-comments :announcement_id="{{$announcement->id}}" inline-template v-cloak>
                            <div>
                                
                                <div class="comments">
                                    <h6>{{ __('t.comments') }}</h6>
                            		<div class="comment-wrap">
                        				<div class="photo">
                						    <div class="avatar" style="background-image: url('{{auth()->user()->picture}}')"></div>
                        				</div>
                        				<div class="comment-block">
                    						<form @submit.prevent="storeComment()">
            								    <textarea name="" id="" cols="30" rows="3" v-model="body" placeholder="{{ __('t.add-comment') }}..."></textarea>
            								    <div class="form-submit">
                                    <input type="submit" value="{{ __('t.post-comment') }}" class="btn btn-success btn-sm pull-right">
                                </div>
                    						</form>
                        				</div>
                            		</div>
                            
                            		<div class="comment-wrap" v-for="comment in comments">
                        				<div class="photo">
                    						<div class="avatar" :style="'background-image: url('+comment.user.image+')'"></div>
                        				</div>
                        				<div class="comment-block">
                    						<p class="comment-text">@{{ comment.description }}</p>
                    						<div class="bottom-comment">
                								<div class="comment-date">@{{ comment.user.full_name }} | @{{ comment.created_at_human }}</div>
                								<ul class="comment-actions" v-if="!showEdit || (showEdit && editComment.id != comment.id)">
            										<li class="complain">
            										    <a href="#" @click.prevent="fetchEditComment(comment.id)" v-if="comment.user.can_edit && !showEdit">
            										      {{ __('t.edit') }}  
            										    </a>
            										</li>
            										<li class="reply">
            										    <a href="#" @click.prevent="deleteComment(comment.id)" v-if="comment.user.can_edit && !showEdit" class="text-danger">{{ __('t.delete') }}</a>
            										</li>
                								</ul>
                								<div v-if="showEdit && editComment.id == comment.id">
                                    <form @submit.prevent="updateComment(comment.id)">
                                        <textarea class="comment-box" v-model="editComment.description"></textarea>
                                        <div class="mt-2">
                                            <a href="#" v-if="showEdit && editComment.id == comment.id" @click.prevent="showEdit=false">{{ __('t.cancel') }}</a>
                                            <input type="submit" value="{{ __('t.update') }}" class="btn btn-success btn-sm pull-right">
                                        </div>
                                    </form>
                                </div>
                    						</div>
                        				</div>
                            		</div>
                                </div>
                                
                                <div class="load-more text-center" v-if="comments.length > 0 && current_page < total_pages">
                                    <a href="" @click.prevent="fetchMoreComments()">
                                        <i class="fa fa-refresh"></i>
                                        {{ __('t.load-more') }}...
                                    </a>
                                </div>
                            </div>
                        
                    </announcement-comments>
                </div>
                
            </div>

        </div>
            
    </section>

@endsection


