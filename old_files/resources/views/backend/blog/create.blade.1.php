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
                        <!--
                        <div class="checkbox">
                            <label class="switch switch-sm switch-3d switch-primary" for="published">
                            <input class="switch-input" type="checkbox" name="metadata[published]" id="published" value="true">
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <label for="published">{{__('t.published')}}</label>
                        </div>
                        -->
                    </div><!--form-group-->
                    <!--
                    <div class="form-group">
                        <div class="checkbox">
                            <label class="switch switch-sm switch-3d switch-primary" for="featured">
                            <input class="switch-input" type="checkbox" name="metadata[featured]" id="featured" value="true">
                                <span class="switch-label"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <label for="featured">{{__('t.featured')}}</label>
                        </div>
                    </div>
                    -->
            
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
    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script>
        
        var config = {
    		codeSnippet_theme: 'Monokai',
    		language: '{{ config('app.locale') }}',
    		height: 300,
    		filebrowserImageBrowseUrl: '/lfm?type=Images',
            filebrowserImageUploadUrl: '/lfm/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/lfm?type=Files',
            filebrowserUploadUrl: '/lfm/upload?type=Files&_token=',
    		toolbarGroups: [
    			{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
    			{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
    			{ name: 'links' },
    			{ name: 'insert' },
    			{ name: 'forms' },
    			{ name: 'tools' },
    			{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
    			{ name: 'others' },
    			//'/',
    			{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    			{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
    			{ name: 'styles' },
    			{ name: 'colors' }
    		]
    	};  
    	
            CKEDITOR.replace('editor-{{$defaultLang}}', config);
            
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