@extends('layouts.master')

@section('title', $course->title . ' | ' . __('t.course-landing-page'))

@section('content')
    
    
    @include('includes._title_header', ['title' => $course->title])
    
    
    <section class="content-area bg-gray pt-5 pb-5">
        <div class="container">
            
            <div class="row mb-4">
                
                <div class="col-md-3">
                    @include('includes._author_course_sidebar')
                </div>
                
                
                <div class="col-md-9">
                    
                    <author-edit-course inline-template :course="{{json_encode($course)}}">
                        <div class="card border-info">
                            <div class="card-body">
                                <h4 class="text-info mb-4">{{__('t.course-landing-page')}}</h4>
                                
                                <form @submit.prevent="updateCourse" @keydown="form.onKeydown($event)">
                                    <div class="form-row mb-3">
                                        <div class="form-group col">
                                            <select class="form-control ui dropdown" 
                                                :disabled="form.published || form.approved ? true : false"
                                                v-model="parent_category" @change="fetchSubcategories">
                                                <option value>{{__('t.select-category')}}</option>
                                                <option v-for="category in categories" :value="category.id">@{{category.name}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col">
                                            <select class="form-control ui dropdown is-invalid" 
                                                :disabled="form.published || form.approved ? true : false"
                                                :class="{ 'is-invalid': form.errors.has('category') }" v-model="form.category">
                                                <option value>{{__('t.select-subcategory')}}</option>
                                                <option v-for="subcategory in subcategories" :value="subcategory.id">@{{subcategory.name}}</option>
                                            </select>
                                            <has-error :form="form" field="category"></has-error>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group has-float-label  mb-4">
                                      <input v-model="form.title" @keyup="generateSlug" autocomplete="off" type="text" 
                                        :disabled="form.published || form.approved ? true : false"
                                        name="title" id="title" placeholder="{{__('t.course-title')}}"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                                        {{ html()->label(__('t.title'))->for('title') }}
                                        <has-error :form="form" field="title"></has-error>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">{{config('app.url')}}courses/</span>
                                            <input v-model="form.slug" type="text" id="slug" name="slug"
                                                :disabled="form.published || form.approved ? true : false"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('slug') }">
                                        </div>
                                        <has-error :form="form" field="slug"></has-error>
                                    </div>
                                    
                                    <div class="form-group has-float-label mb-4">
                                      <input v-model="form.subtitle" @keyup="generateSlug" autocomplete="off" type="text" name="subtitle" id="subtitle" 
                                            placeholder="{{__('t.learn-how-to')}}..."
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('subtitle') }">
                                        {{ html()->label(__('t.subtitle'))->for('subtitle') }}
                                        <has-error :form="form" field="subtitle"></has-error>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <label class="label-right">
                                            {{__('t.course-description')}} ({{__('t.minimum-50')}}):
                                        </label>
                                        <div class="is-invalid">
                                            <quill-editor
                                                v-model="form.description"
                                                :options="editorOption"
                                                @ready="onEditorReady($event)">
                                            </quill-editor>
                                        </div>
                                        <has-error :form="form" field="description"></has-error>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <select class="form-control ui dropdown" v-model="form.level">
                                                <option value>{{__('t.choose-level')}}</option>
                                                <option value="beginner">{{__('t.beginner-level')}}</option>
                                                <option value="intermediate">{{__('t.intermediate-level')}}</option>
                                                <option value="advanced">{{__('t.advanced-level')}}</option>
                                            </select>
                                            <has-error :form="form" field="level"></has-error>
                                        </div>
                                        
                                        <div class="form-group col">
                                            <select class="form-control ui dropdown" v-model="form.language">
                                                <option value>{{__('t.language-of-instruction')}}</option>
                                                <option v-for="(language,key) in languages" :value="language">@{{language}}</option>
                                            </select>
                                            <has-error :form="form" field="language"></has-error>
                                        </div>
                                    </div>
                                    <button :disabled="form.busy" type="submit" class="btn pull-right btn-info">{{__('t.update')}}</button>
                                </form>
                                
                                <div class="clearfix"></div>
                                <hr />
                                <div class="row">
                                    <div class="col">
                                        <h5 class="text-info">{{__('t.course-image')}}</h5>
                                        <div class="media">
                                            <a class="pull-left mr-4" href="#">
                                                <img class="media-object" width="350px" :src="src">
                                                <p v-if="uploading">
                                                    <span class="fa fa-cog fa-spin"></span> {{__('t.uploading')}}...
                                                </p>
                                            </a>
                                            
                                            <div class="media-body">
                                                <h5 class="media-heading">
                                                    {{__('t.image-specifications')}}
                                                </h5>
                                                <p>
                                                    {{__('t.image-specifications-text')}}
                                                </p>
                                                <div class="form-group">
                                                    <image-upload
                                                        :class="['btn', 'btn-danger']"
                                                        text="{{__('t.choose-image')}}..."
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
                    </author-edit-course>
                    
                    
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