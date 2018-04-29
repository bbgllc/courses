
{{ Form::model($post, ['route' => ['admin.blog.update', $post], 'method'=>'PUT', 'class' => 'form-horizontal']) }}  
    <div class="form-group">
        {{ Form::label('title', __('t.title'), ['class' => 'control-label']) }}
        <input class="form-control" name="title" type="text" id="title-{{$k}}" autocomplete="off" 
            autofocus=true value="{{ !is_null($post->translate($k)) ? $post->translate($k)->title : null }}" />
    </div><!--form control-->
    
    <div class="form-group">
        {{ Form::label('slug', __('t.permalink'), ['class' => 'control-label']) }}
        <div class="input-group">
          <span class="input-group-addon" id="permalink-addon">{{ config('app.url') }}</span>
          <input type="text" class="form-control" name="slug" id="permalink-{{$k}}" 
            value="{{ !is_null($post->translate($k)) ? $post->translate($k)->slug : null }}" 
                aria-describedby="permalink-addon">
        </div>
     </div><!--form control-->
    
    <div class="form-group">
        {{ Form::label('intro', __('t.short-intro'), ['class' => 'control-label']) }}
        <textarea name="intro" rows="4" id="intro" class="form-control">{{ !is_null($post->translate($k)) ? $post->translate($k)->intro : null }}</textarea>
    </div><!--form control-->
    
    <!--
    <div class="form-group">
        {{ Form::label('body', __('t.body'), ['class' => 'control-label']) }}
        <textarea id="editor-{{$k}}" name="body" class="form-control">
            {{ !is_null($post->translate($k)) ? $post->translate($k)->body : null }}
        </textarea>
    </div>
    -->
    <div class="form-group">
        {{ Form::label('body', __('t.body'), ['class' => 'control-label']) }}
        <textarea id="summernote-{{$k}}" name="body">{{ !is_null($post->translate($k)) ? $post->translate($k)->body : null }}</textarea>
    </div>
    <div class="form-group">
        {{ Form::label('meta_description', __('t.meta-description'), ['class' => 'control-label']) }}
        <textarea name="meta_description" rows="4" id="meta" class="form-control">{{ !is_null($post->translate($k)) ? $post->translate($k)->meta_description : null }}</textarea>
    </div><!--form control-->
    
    <input type="hidden" name="lang" value="{{$k}}" />
    
    {{ form_submit( __('t.save') . ' ' . $v['name'] . ' ' .__('t.version'), 'btn btn-success btn-md pull-right' ) }}
{{ Form::close() }}
