@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.account-settings'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.account-settings')])
    
    
    <section class="content-area bg-gray pt-5 pb-5">
        <div class="container">
            
            <div class="row mb-4">
                
                <div class="col-md-4">
                    @include('includes._user_account_sidebar')
                </div>
                
                <user-settings :user="{{ auth()->user()->toJson() }}" :settings="{{auth()->user()->settings}}" inline-template v-cloak>
                    <div class="col-md-8">
                        <div class="card border-info">
                            <div class="card-body">
                                <h4 class="text-info mb-4">
                                    {{ __('t.update-account-settings') }}
                                </h4>
                                
                                <!-- Success Message -->
                                <div class="alert alert-success" v-if="form.successful">
                                    {{ __('t.your-settings-updated') }}
                                </div>
                                
                                <form @submit.prevent="updatePassword()">
                                    
                                    <div class="list-group">
                                        <div class="list-group-item">
                                            <div class="form-row form-group">
                                                <label class="col-md-9 control-label">
                                                    {{ __('t.notify-respond-to-my-question') }}
                                                </label>
                                                <div class="col-md-3">
                                                    <select class="form-control form-control-sm w-100" v-model="form.notify_when_question_responded" 
                                                        :class="{ 'is-invalid': form.errors.has('notify_when_question_responded') }">
                                                        <option value="true">{{ __('t.yes') }}</option>
                                                        <option value="false">{{ __('t.no') }}</option>
                                                    </select>
                                                    <has-error :form="form" field="notify_when_question_responded"></has-error>
                                                </div>
                                            </div>
                                            <div class="form-row form-group">
                                                <label class="col-md-9 control-label">
                                                    {{ __('t.notify-respond-to-my-question-i-follow') }}
                                                    
                                                </label>
                                                <div class="col-md-3">
                                                    <select class="form-control form-control-sm w-100" v-model="form.notify_when_question_i_am_following_responded" 
                                                        :class="{ 'is-invalid': form.errors.has('notify_when_question_i_am_following_responded') }">
                                                        <option value="true">{{ __('t.yes') }}</option>
                                                        <option value="false">{{ __('t.no') }}</option>
                                                    </select>
                                                    <has-error :form="form" field="notify_when_question_i_am_following_responded"></has-error>
                                                </div>
                                            </div>
                                            <div class="form-row form-group">
                                                <label class="col-md-9 control-label">
                                                    {{ __('t.notify-my-answer-marked-as-answered') }}
                                                    
                                                </label>
                                                <div class="col-md-3">
                                                    <select class="form-control form-control-sm w-100" v-model="form.notify_when_answer_marked_as_correct" 
                                                        :class="{ 'is-invalid': form.errors.has('notify_when_answer_marked_as_correct') }">
                                                        <option value="true">{{ __('t.yes') }}</option>
                                                        <option value="false">{{ __('t.no') }}</option>
                                                    </select>
                                                    <has-error :form="form" field="notify_when_answer_marked_as_correct"></has-error>
                                                </div>
                                            </div>
                                            <div class="form-row form-group">
                                                <label class="col-md-9 control-label">
                                                    {{ __('t.notify-when-question-i-follow-is-answered') }}
                                                    
                                                </label>
                                                <div class="col-md-3">
                                                    <select class="form-control form-control-sm w-100" v-model="form.notify_when_followed_question_is_answered" 
                                                        :class="{ 'is-invalid': form.errors.has('notify_when_followed_question_is_answered') }">
                                                        <option value="true">{{ __('t.yes') }}</option>
                                                        <option value="false">{{ __('t.no') }}</option>
                                                    </select>
                                                    <has-error :form="form" field="notify_when_followed_question_is_answered"></has-error>
                                                </div>
                                            </div>
                                            <div class="form-row form-group">
                                                <label class="col-md-9 control-label">
                                                    {{ __('t.notify-when-my-question-is-marked-as-answered') }}
                                                    
                                                </label>
                                                <div class="col-md-3">
                                                    <select class="form-control form-control-sm w-100" v-model="form.notify_when_my_question_is_marked_as_answered" 
                                                        :class="{ 'is-invalid': form.errors.has('notify_when_my_question_is_marked_as_answered') }">
                                                        <option value="true">{{ __('t.yes') }}</option>
                                                        <option value="false">{{ __('t.no') }}</option>
                                                    </select>
                                                    <has-error :form="form" field="notify_when_my_question_is_marked_as_answered"></has-error>
                                                </div>
                                            </div>
                                            <div class="form-row form-group">
                                                <label class="col-md-9 control-label">
                                                    {{ __('t.notify-when-new-answer') }}
                                                    
                                                </label>
                                                <div class="col-md-3">
                                                    <select class="form-control form-control-sm w-100" v-model="form.notify_when_new_announcement" 
                                                        :class="{ 'is-invalid': form.errors.has('notify_when_new_announcement') }">
                                                        <option value="true">{{ __('t.yes') }}</option>
                                                        <option value="false">{{ __('t.no') }}</option>
                                                    </select>
                                                    <has-error :form="form" field="notify_when_new_announcement"></has-error>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mt-2">
                                        <button :disabled="form.busy" type="submit" @click.prevent="updateSettings()" class="btn pull-right btn-info">
                                            <i class="fa fa-cog fa-spin" v-if="form.busy"></i> {{ __('t.update') }}
                                        </button>
                                    </div>
                                </form>
                                
                                
                                  
                            </div>
                            
                            
                            
                        

                        </div>
                            
                        
                        
                    </div>
                </user-settings>
            </div>

            
        </div>
    </section>

@endsection

