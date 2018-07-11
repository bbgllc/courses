@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.security'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.security')])
    
    
    <section class="content-area bg-gray pt-5 pb-5">
        <div class="container">
            
            <div class="row mb-4">
                
                <div class="col-md-4">
                    @include('includes._user_account_sidebar')
                </div>
                
                <user-security :user="{{ auth()->user()->toJson() }}" inline-template v-cloak>
                    <div class="col-md-8">
                        <div class="card border-info">
                            <div class="card-body">
                                <h4 class="text-info mb-4">
                                    {{ __('t.password-update') }} 
                                </h4>
                                <!--
                                <loader></loader>
                                -->
                                
                                <form @submit.prevent="updatePassword()">
                                    
                                    <div class="form-group has-float-label  mb-4">
                                      <input v-model="form.old_password" autocomplete="off" type="password" 
                                        name="old_password" id="old_password" placeholder="{{ __('t.old-password') }}"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('old_password') }">
                                        {{ html()->label(__('t.old-password'))->for('old_password') }}
                                        <has-error :form="form" field="old_password"></has-error>
                                    </div>
                                    
                                    <div class="form-group has-float-label  mb-4">
                                      <input v-model="form.password" autocomplete="off" type="password" 
                                        name="password" id="password" placeholder="{{ __('t.new-password') }}"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                        {{ html()->label(__('t.new-password'))->for('password') }}
                                        <has-error :form="form" field="password"></has-error>
                                    </div>
                                    
                                    <div class="form-group has-float-label  mb-4">
                                      <input v-model="form.password_confirmation" autocomplete="off" type="password" 
                                        name="password_confirmation" id="password_confirmation" placeholder="{{ __('t.retype-new-password') }}"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('password_confirmation') }">
                                        {{ html()->label(__('t.retype-new-password'))->for('password_confirmation') }}
                                        <has-error :form="form" field="password_confirmation"></has-error>
                                    </div>
                                    
                                    <button :disabled="form.busy" type="submit" class="btn pull-right btn-info">
                                        <i class="fa fa-cog fa-spin" v-if="form.busy"></i> {{ __('t.update') }}
                                    </button>
                                </form>
                                
                                
                                  
                            </div>
                            
                            
                            
                        

                        </div>
                            
                        
                        
                    </div>
                </user-security>
            </div>

            
        </div>
    </section>

@endsection

