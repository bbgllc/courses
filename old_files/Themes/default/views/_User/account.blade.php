@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.account-settings'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.account') ])
    
    
    <section class="content-area bg-gray pt-5 pb-5">
        <div class="container">
            
            <div class="row mb-4">
                
                <div class="col-md-4">
                    @include('includes._user_account_sidebar')
                </div>
                
                <user-profile-edit :user="{{ auth()->user()->toJson() }}" inline-template v-cloak>
                    <div class="col-md-8">
                        <div class="card border-info">
                            <div class="card-body">
                                <h4 class="text-info mb-4">
                                    {{ __('t.my-account') }}    
                                </h4>
                                
                                <form @submit.prevent="updateProfile()">
                                    
                                    <div class="form-group has-float-label  mb-4">
                                      <select v-model="form.country"
                                        id="country" placeholder="{{ __('t.country') }}"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('country') }">
                                        <option v-for="(country,key) in countries" :value="key">@{{country}}</option>
                                      </select>
                                        {{ html()->label(__('t.country'))->for('country') }}
                                        <has-error :form="form" field="country"></has-error>
                                    </div>
                                    
                                    <div class="form-group has-float-label  mb-4">
                                      <input v-model="form.first_name" autocomplete="off" type="text" 
                                        name="first_name" id="first_name" placeholder="{{ __('t.first-name') }}"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('first_name') }">
                                        {{ html()->label(__('t.first-name'))->for('first_name') }}
                                        <has-error :form="form" field="first_name"></has-error>
                                    </div>
                                    
                                    <div class="form-group has-float-label  mb-4">
                                      <input v-model="form.last_name" autocomplete="off" type="text" 
                                        name="last_name" id="last_name" placeholder="{{ __('t.last-name') }}"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                        {{ html()->label(__('t.last-name'))->for('last_name') }}
                                        <has-error :form="form" field="last_name"></has-error>
                                    </div>
                                    
                                    <div class="form-group has-float-label  mb-4">
                                      <input v-model="form.username" autocomplete="off" type="text" 
                                        name="username" id="username" placeholder="{{ __('t.username') }}"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('username') }">
                                        {{ html()->label(__('t.username'))->for('username') }}
                                        <has-error :form="form" field="username"></has-error>
                                    </div>
                                    
                                    <div class="form-group has-float-label  mb-4">
                                      <input v-model="form.headline" autocomplete="off" type="text" 
                                        name="headline" id="headline" placeholder="{{ __('t.headline') }}"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('headline') }">
                                        {{ html()->label(__('t.headline'))->for('headline') }}
                                        <has-error :form="form" field="headline"></has-error>
                                    </div>
                                    
                                    
                                    <div class="form-group mb-4">
                                        <label class="label-right">
                                            {{ __('t.biography') }}:
                                        </label>
                                        <div class="is-invalid">
                                            <quill-editor
                                                v-model="form.biography"
                                                :options="editorOption"
                                                @ready="onEditorReady($event)">
                                            </quill-editor>
                                        </div>
                                        <has-error :form="form" field="biography"></has-error>
                                    </div>
                                    
                                    <div class="form-group has-float-label  mb-4">
                                      <input v-model="form.web" autocomplete="off" type="text" 
                                        name="web" id="web" placeholder="http://www.mysite.com"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('web') }">
                                        {{ html()->label(__('t.website'))->for('web') }}
                                        <has-error :form="form" field="web"></has-error>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">https://www.facebook.com/</span>
                                            <input v-model="form.facebook" type="text" id="facebook" name="facebook"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('facebook') }">
                                        </div>
                                        <has-error :form="form" field="facebook"></has-error>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">https://www.linkedin.com/</span>
                                            <input v-model="form.linkedin" type="text" id="linkedin" name="linkedin"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('linkedin') }">
                                        </div>
                                        <has-error :form="form" field="linkedin"></has-error>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">https://www.twitter.com/</span>
                                            <input v-model="form.twitter" type="text" id="twitter" name="twitter"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('twitter') }">
                                        </div>
                                        <has-error :form="form" field="twitter"></has-error>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">https://www.github.com/</span>
                                            <input v-model="form.github" type="text" id="github" name="github"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('github') }">
                                        </div>
                                        <has-error :form="form" field="github"></has-error>
                                    </div>
                                    
                                    
                                    <button :disabled="form.busy" type="submit" class="btn pull-right btn-info" v-if="show_form_submit">
                                        <i class="fa fa-cog fa-spin" v-if="form.busy"></i> {{ __('t.update') }}
                                    </button>
                                </form>
                                
                                
                                <div class="clearfix"></div>
                                <hr />
                                <div class="col-12 p4">
                                    <h5 class="text-info">{{ __('t.profile-image') }}</h5>
                                    <div class="media">
                                        <a class="pull-left mr-4" href="#">
                                            <img class="media-object rounded-circle" width="150" :src="src">
                                        </a>
                                        
                                        <div class="media-body">
                                            <p v-if="uploading">
                                                <span class="fa fa-cog fa-spin"></span> {{ __('t.uploading') }}...
                                            </p>
                                            <div class="form-group">
                                                <image-upload
                                                    :class="['btn', 'btn-danger', 'btn-sm']"
                                                    text="{{ __('t.choose-image') }}..."
                                                    @imageuploaded="fileUploaded"
                                                    @imageuploading="uploading=true"
                                                    extensions="png,jpeg,jpg,gif"
                                                    :max-file-size="5242880"
                                                    compress="50"
                                                    :url="uploadURL">
                                                </image-upload>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                  
                            </div>
                            
                            
                            
                        

                        </div>
                            
                        
                        
                    </div>
                </user-profile-edit>
            </div>

            
        </div>
    </section>

@endsection

