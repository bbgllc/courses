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
                            <div class="card-body p-4">
                                <h4 class="text-info mb-4">
                                    {{__('t.announcements')}}
                                    <a href="{{route('frontend.author.announcements', $course)}}" class="btn btn-sm btn-danger pull-right">
                                        <i class="fa fa-times-circle"></i> {{__('t.cancel')}}
                                    </a>
                                </h4>
                                
                                <div class="clearfix"></div>
                                
                                
                                <author-create-announcement inline-template :courses="{{$courses}}" :course="{{$course}}" v-cloak>
                                    <span>
                                        <form @submit.prevent="saveAnnouncement">
                                            
                                            <div class="form-group has-float-label">
                                                <input v-model="form.title" autocomplete="off" type="text" name="title" id="title" placeholder="Enter course title"
                                                    class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                                                    {{ html()->label(__('t.subject'))->for('title') }}
                                                    <has-error :form="form" field="title"></has-error>
                                            </div>
                                            <div class="form-group">
                                                {{ html()->label(__('t.courses'))->for('courses') }}
                                                <selectize v-model="form.courses" :settings="settings"> 
                                                    <option v-for="c in courses" :value="c.id">@{{c.title}}</option>
                                                </selectize>
                                                <has-error :form="form" field="courses"></has-error>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label class="label-right">
                                                    {{__('t.body')}}
                                                </label>
                                                <div class="is-invalid">
                                                    <quill-editor
                                                        v-model="form.body"
                                                        :options="editorOption">
                                                    </quill-editor>
                                                </div>
                                                <has-error :form="form" field="body"></has-error>
                                            </div>
                                        
                                            <button :disabled="form.busy" type="submit" class="btn btn-block btn-info">
                                                {{__('t.send')}}
                                            </button>
                                        </form>
                                    </span>
                                </author-create-announcement>
                                
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