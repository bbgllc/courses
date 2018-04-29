@extends ('backend.layouts.app')

@section ('title', __('t.system-messages'))

@section('breadcrumb-links', '')

@section('content')
    
    {!! Form::model($message, ['route' => ['admin.messages.update', $message->id], 'method'=>'PUT', 'class' => 'form-horizontal']) !!}
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i>
                <strong>{{__('t.system-messages')}}</strong>
                <small class="text-muted">{{__('t.send-system-messages')}}</small>
                
                @if($message->sent)
                    <span class="badge badge-success pull-right">
                        {{__('t.sent')}}
                    </span>
                @else 
                    <span class="badge badge-warning pull-right">
                        {{__('t.draft')}}
                    </span>
                @endif
            </div>
            <div class="card-body">
                <div class="form-group">
                    {!! Form::label("subject", __('t.subject')) !!}
                    {!! Form::text("subject", null, ['class'=>'form-control', 'required']) !!}
                    @if ($errors->has('subject'))
                        <div class="text-danger"><small>{{ $errors->first('subject') }}</small></div>
                    @endif
                </div>
                
                <div class="form-group">
                    {!! Form::label("recipient_group", __('t.send-to')) !!}
                    {{ Form::select('recipient_group', 
                        array('everyone' => trans('t.everyone'), 
                              'admins' => trans('t.administrators'), 
                              'authors' => trans('t.authors'), 
                              'inactive-users' => trans('t.inactive-users'), 
                              'students' => trans('t.all-students'),  
                              'selected-users' => trans('t.selected-users')), null, 
                              ['class' => 'form-control', 'id'=>'recipient_group']) }}
                    
                </div>
                <div class="form-group recipient-list d-none">
                    {{ Form::label('recipients', trans('t.recipients')) }}
                    {{ Form::text('recipients', null, ['class' => '', 'id' => 'recipients', 'autocomplete' => 'off']) }}
                </div><!--form control-->
                
                <!--
                <div class="form-group">
                    {{ Form::label('body', trans('t.body')) }}
                    <textarea id="my-editor" name="body" class="form-control">{!! old('body', $message->body) !!}</textarea>
                </div>
                -->
                <div class="form-group">
                    {{ Form::label('body', __('t.body'), ['class' => 'control-label']) }}
                    <textarea id="summernote-message" name="body">{!! old('body', $message->body) !!}</textarea>
                </div>
                        
            </div>
            
            <div class="card-footer">
                <a href="{{route('admin.messages.index')}}" class="btn btn-danger">{{ __('t.close') }}</a>
                @if(!$message->sent)
                    <button type="submit" class="btn btn-primary pull-right">{{ __('t.update') }}</button>
                @endif
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
            $('#summernote-message').summernote({
                height: 200,
                placeholder: 'Enter message here',
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
        $("#recipient_group").on('change', function(e){
            var v = $('#recipient_group').val();
            var s = $('#recipients').selectize();
            var control = s[0].selectize;
 
            if(v == 'selected-users'){
                $('.recipient-list').removeClass('d-none');
            } else {
                control.clear();
                $('.recipient-list').addClass('d-none');
            }
        })
        
        // tags
        
        var users = [
            @foreach ($users as $user)
                { 
                    name: "{{$user->name}}", 
                    id: "{{$user->id}}"
                },
            @endforeach
        ];


        $( document ).ready(function() {
            $('#recipients').selectize({
                plugins: ['remove_button', 'restore_on_backspace'],
                delimiter: ',',
                persist: false,
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                options: users,
                
            });
            
            if($('#recipient_group').val() == 'selected-users'){
                $('.recipient-list').removeClass('hidden');
            }
        });
        
    </script>
@endpush