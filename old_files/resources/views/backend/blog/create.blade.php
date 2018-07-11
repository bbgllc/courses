@extends ('backend.layouts.app')

@section ('title', __('t.blog'))

@section('breadcrumb-links', '')

@section('content')


{{ Form::open(['route' => 'admin.blog.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) }}
    <div class="row">
        <div class="col-8">
            <div class="card">
                
                <div class="card-header border border-0 pt-4">
                    <ul class="nav nav-tabs card-header-tabs">
                        @foreach(LaravelLocalization::getLocalesOrder() as $k => $v )
                            @if( $k == env('APP_LOCALE') )
                                <li class="nav-item border border-0" role="presentation">
            						<a class="nav-link {{$loop->first ? 'active' : ''}}" href="#lang-{{$k}}" data-toggle="tab" role="tab">
            						    {{ $v['name'] }} 
        						    </a>
            					</li>
        					@endif
    					@endforeach
    					<li class="nav-item border border-0"> 
    					    <a class="nav-link">
    					        {{__('t.add-translation-after-save')}}
					        </a>
					    </li>
                    </ul>
                </div><!--card-header-->
                <div class="card-body">
                    <div class="tab-content border border-0" id="myTabContent">
                        @foreach(LaravelLocalization::getLocalesOrder() as $k => $v )
                            @if( $k == env('APP_LOCALE') )
                                <div class="tab-pane fade show {{$loop->first ? 'active' : ''}}" id="lang-{{$k}}" role="tabpanel" aria-labelledby="english-tab">
                                    @include('backend.blog.tabs._create_form')
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div><!--card-->
        </div>
        
        
        
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>
                    <strong>{{__('t.blog')}}</strong>
                    <small class="text-muted">{{__('t.post-meta-data')}}</small>
                </div><!--card-header-->
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::label('category', __('t.category'), ['class' => 'control-label']) }}
                        {{ Form::select("category", $categories, null, ['class' => 'form-control']) }}
                    </div><!--form control-->
                    
                    <div class="form-group">
                        <div class="form-group">
                            <select class="custom-select my-1 mr-sm-2" name="published" id="published">
                                <option value="0">{{__('t.unpublished')}}</option>
                                <option value="1">{{__('t.published')}}</option>
                            </select>
                        </div><!--form-group-->
                    </div><!--form-group-->

                </div><!--card-body-->
                
                <div class="card-footer">
                    <div class="row">
                        <div class="col">
                            {{ form_cancel(route('admin.blog.posts'), __('t.cancel')) }}
                        </div><!--col-->
        
                        <div class="col text-right">
                            {{ form_submit(__('t.save')) }}
                        </div><!--col-->
                    </div><!--row-->
                </div><!--card-footer-->
                
            </div><!--card-->
        </div>
    </div>
{{ Form::close() }}
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
            $('#summernote-{{$defaultLang}}').summernote({
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
           
        });
    </script>
    
    <script>
        
        $("#title-{{$defaultLang}}").keyup(function(){
			var str = sansAccent($(this).val());
			str = str.replace(/[^a-zA-Z0-9\s]/g,"");
			str = str.toLowerCase();
			str = str.replace(/\s/g,'-');
			$("#permalink-{{$defaultLang}}").val(str);        
		});
        
		
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