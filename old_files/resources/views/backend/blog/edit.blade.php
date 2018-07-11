@extends ('backend.layouts.app')

@section ('title', $post->title)

@section('breadcrumb-links', '')

@section('content')


    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header border border-0 pt-4">
                    <ul class="nav nav-tabs card-header-tabs">
                        @foreach(LaravelLocalization::getLocalesOrder() as $k => $v )
                            <li class="nav-item border border-0" role="presentation">
        						<a class="nav-link text-uppercase font-weight-bold {{$loop->first ? 'active' : ''}}" href="#lang-{{$k}}" data-toggle="tab" role="tab">
        						    <img src="/img/flags/{{$k}}.svg" width="15" class="rounded-circle" /> {{ $v['name'] }} 
    						    </a>
        					</li>
    					@endforeach
                    </ul>
                </div><!--card-header-->
                <div class="card-body">
                    <div class="tab-content border border-0" id="myTabContent">
                        @foreach(LaravelLocalization::getLocalesOrder() as $k => $v )
                            <div class="tab-pane fade show {{$loop->first ? 'active' : ''}}" id="lang-{{$k}}" role="tabpanel" aria-labelledby="english-tab">
                                @include('backend.blog.tabs._edit_form')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div><!--card-->
        </div>
        
        <div class="col-4">
            {!! Form::model($post, ['route' => ['admin.blog.updateMetadata', $post], 'method'=>'PUT', 'class' => 'form-horizontal']) !!}    
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>
                        <strong>{{__('t.blog')}}</strong>
                        <small class="text-muted">{{__('t.post-meta-data')}}</small>
                    </div><!--card-header-->
                    <div class="card-body">
                        <div class="form-group">
                            {{ Form::label('category', __('t.category'), ['class' => 'control-label']) }}
                            {{ Form::select("category", $categories, $post->category_id, ['class' => 'form-control']) }}
                        </div><!--form control-->
                        
                        <div class="form-group">
                            <select class="custom-select my-1 mr-sm-2" name="published" id="published">
                                <option value="0" {{ !$post->published ? 'selected' : null }}>{{__('t.unpublished')}}</option>
                                <option value="1" {{ $post->published ? 'selected' : null }}>{{__('t.published')}}</option>
                            </select>
                        </div><!--form-group-->
                        
                        <!--
                        <div class="form-group">
                            <div class="checkbox">
                                <label class="switch switch-text switch-pill switch-success">
                                    <input type="checkbox" class="switch-input" name="featured" {{ $post->featured ? 'checked' : null }} value="true">
                                    <span class="switch-label" data-on="{{__('t.yes')}}" data-off="{{__('t.off')}}"></span>
                                    <span class="switch-handle"></span>
                                </label>
                                <label for="featured">{{__('t.featured')}}?</label>
                            </div>
                        </div>
                        -->
    
                    </div><!--card-body-->
                    
                    <div class="card-footer">
                        <div class="row">
                            <div class="col">
                                {{ form_cancel(route('admin.blog.posts'), __('t.cancel'), 'btn btn-danger btn-md') }}
                            </div><!--col-->
            
                            <div class="col text-right">
                                <button type="submit" class="btn btn-md btn-success">{{__('t.save')}}</button>
                            </div><!--col-->
                        </div><!--row-->
                    </div><!--card-footer-->
                    
                </div><!--card-->
            {{ Form::close() }}
            
            <upload-image inline-template v-cloak post_id="{{$post->id}}" src="{{$post->image}}">
                <div class="card">
                    <div class="card-header">
                        {{__('t.featured-image')}}
                    </div>
                    <div class="card-body p-2">
                        <vue-dropzone ref="myVueDropzone" 
                            id="dropzone"
                            @vdropzone-success="uploadSuccessful"
                            @vdropzone-removed-file="removeFile"
                            :options="dropzoneOptions">
                    </div>
                </div>
            </upload-image>
        </div>
        
    </div>

@endsection


@push('after-scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
    
    <script>
        $(document).ready(function(){
            // Define function to open filemanager window
            var lfm = function(options, cb) {
                var route_prefix = (options && options.prefix) ? options.prefix : '/lfm';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };
            
            // Define LFM summernote button
            var LFMButton = function(context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="note-icon-picture"></i> ',
                    //tooltip: 'Insert image with filemanager',
                    click: function() {
                        lfm({type: 'image', prefix: '/lfm'}, function(url, path) {
                            context.invoke('insertImage', url);
                        });
                    }
                });
                return button.render();
            };
            @foreach(LaravelLocalization::getLocalesOrder() as $k => $v )
                $('#summernote-{{$k}}').summernote({
                    height: 200,
                    placeholder: 'Enter your content body here',
                    tabsize: 2,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['popovers', ['lfm']],
                        
                    ],
                    
                    buttons: {
                        lfm: LFMButton
                    }
                })
            @endforeach
        });
    </script>
    
    
    
    <script>
        
    	@foreach(LaravelLocalization::getLocalesOrder() as $k => $v )
            $("#title-{{$k}}").keyup(function(){
    			var str = sansAccent($(this).val());
    			str = str.replace(/[^a-zA-Z0-9\s]/g,"");
    			str = str.toLowerCase();
    			str = str.replace(/\s/g,'-');
    			$("#permalink-{{$k}}").val(str);        
    		});
        @endforeach
        
		w = "àâäçéèêëîïôöùûüÿÀÂÄÇÉÈÊËÎÏÔÖÙÛÜŸ".split("");
        w.push("Œ","œ");
        wo = "aaaceeeeiioouuuyAAACEEEEIIOOUUUY".split("");
        wo.push("OE","oe");
        
		function sansAccent(text){
          for(var i=0 ; i< w.length ; i++){
            text = text.replace( new RegExp(w[i],"g") , wo[i]);
          }
          return text;
        }
    </script>
@endpush