@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.messages'))

@section('after-styles')
    @include('_User.messenger._styles')
@stop


@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.messages')])
    
    <inbox first_thread="{{$firstThread ? $firstThread->id : null}}" auth_user="{{auth()->id()}}" inline-template v-cloak>
        <section class="content-area bg-white pt-5 pb-5">
            <div class="container main_section">
                
                <div class="row mb-4">
                    <div class="col-md-3 chat_sidebar pr-0">
                        <div id="custom-search-input">
                            <div class="input-group col-md-12 p-0">
                                <input type="text" v-model="keyword" class="search-query form-control form-control-lg" @keydown="handleSearchInput" placeholder="{{__('keyword')}}..." />
                                <button class="btn btn-danger" type="{{__('submit')}}">
                                    <span class="fa fa-search"></span>
                                </button>
                            </div>
                        </div>
                        <div class="member_list">
                            <transition-group name="user-appear">
                                <ul class="list-unstyled" v-cloak v-for="thread in threads" :key="thread.id">
                                    <a href="#" @click.prevent="getMessages(thread.id)">
                                        <li class="left clearfix" :class="{'active' : thread.id == messages.id  }">
                                            <span class="chat-img pull-left">
                                                <img :src="thread.recipient.picture" alt="Image" class="img-circle">
                                            </span>
                                            <div class="chat-body clearfix">
                                                <div class="header_sec pb-0">
                                                    <span class="message-sender">@{{thread.recipient.name}}</span> 
                                                    <span class="pull-right sent-at">@{{thread.created_at_human}}</span>
                                                    <span v-if="messages.isUnread" class="message-icon icon-envelope secondary pull-right"></span>
                                                </div>
                                                <div class="contact_sec">
                                                   <p class="message-summary">@{{thread.latestMessage}}</p>
                                                </div>
                                            </div>
                                        </li>
                                    </a>
                                </ul>
                            </transition-group>
                        </div>
                    </div>
                    
                    <div class="col-md-7 message_section">
                        <div class="row">
        		            <div class="new_message_head">
        		                <div class="pull-left">
        		                    <button v-if="Object.keys(messages).length">
                        		         <i class="fa fa-comments" aria-hidden="true"></i> 
                        		         {{__('chatting-with')}} <a :href="'/user/'+messages.chatting_with_user.username"> @{{messages.chatting_with_user.full_name}}</a>
                    		        </button>
		                        </div>
    		                </div><!--new_message_head-->
        		 
        		            <div id="chat_area" class="chat_area newsfeed">
            		            <ul class="list-unstyled" v-chat-scroll>
            		                <center>
            		                    <loader v-show="loading"></loader>
            		                </center>
            		                
            		                <transition-group name="message-appear">
            				            <li class="left clearfix" :key=message.id :class="{ 'admin_chatx' : messages.creator == message.creator.id }" v-for="message in messages.messages">
                                            <span class="chat-img1 pull-left">
                                                <img :src="message.user.picture" alt="Avatar" class="img-circle">
                                            </span>
                                            <div class="chat-body1 clearfix">
                                                <p style="margin-bottom:0; padding-bottom:0;">
                                                    <span class="message-sender">@{{message.user.name}} </span>
                                                    <span style="font-size:0.9em; color:#a9a9a9;float:right;">
                                                        @{{message.created_at_human}}
                                                    </span>
                                                </p>
                                                <p>@{{message.body}}</p>
                                            </div>
                                        </li>
                                    </transition-group>
            		            </ul>
        		            </div><!--chat_area-->
                            <div class="message_write">
                                <span class="text-danger">
                                    @{{err}}
                                </span>
                	            <textarea class="form-control" v-model="body"
                    	            @keydown="handleMessageInput"
                    	            required
                    	            placeholder="{{__('write-your-message-here')}}..." style="resize:none;"> 
                	            </textarea>
                        		<div class="clearfix"></div>
                            	<div class="chat_bottom">
                            	    <div class="row">
                            	        <div class="col-md-8">
                            	            <div class="row">
                            	                <file-uploader :thread_id="messages.id" 
                            	                    :auth_user="auth_user" 
                            	                    @file-uploaded="getMessages(messages.id)"
                            	                    v-if="Object.keys(messages).length > 0"></file-uploader>
                                    	    </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" @click.prevent="send()" class="pull-right btn btn-sm btn-success">
                                                 {{__('send')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                    		</div>
        		        </div>
                    </div>
                    
                    <div class="col-sm-2 message_section" style="border-left:0px;">
                        <div class="row">
                            <div class="new_message_head">
        		                <div class="pull-left">
        		                    {{__('attachments')}}
		                        </div>
    		                </div>
    		                <div class="chat_area newsfeed" style="height:460px; padding:10px;">
    		                    <ul class="list-group">
    		                        <li class="list-group-item clearfix" v-for="attachment in attachments" style="padding:5px;">
    		                            <a :href="attachment.fullPath" target="_blank" style="display:block;">
    		                                @{{attachment.title}}
		                                </a>
		                                <p style="font-size:9px; color:#999; margin-bottom:0;">
		                                    @{{attachment.user}} - @{{attachment.created_at_human}}
		                                </p>
    		                        </li>
    		                    </ul>
    		                    
    		                </div>
		                </div>
                    </div>
                    
                    
                </div>
            </div>
        </section>
    </inbox>

@endsection

