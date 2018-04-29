<div class="form-group">
    {{ Form::label('title', __('t.title'), ['class' => 'control-label']) }}
    <input class="form-control" name="title" type="text" id="title-{{$k}}" autocomplete="off" 
        autofocus=true value="{{ old('title') }}" />
</div><!--form control-->

<div class="form-group">
    {{ Form::label('slug', __('t.permalink'), ['class' => 'control-label']) }}
    <div class="input-group">
      <span class="input-group-addon" id="permalink-addon">{{ config('app.url') }}</span>
      <input type="text" class="form-control" name="slug" id="permalink-{{$k}}" value="{{ old('slug') }}" aria-describedby="permalink-addon">
    </div>
 </div><!--form control-->

<div class="form-group">
    {{ Form::label('intro', __('t.short-intro'), ['class' => 'control-label']) }}
    <textarea name="intro" rows="4" id="intro" class="form-control">{{ old('intro', '') }}</textarea>
</div><!--form control-->
<!--
<div class="form-group">
    {{ Form::label('body', __('t.body'), ['class' => 'control-label']) }}
    <textarea id="editor-{{$k}}" name="body" class="form-control">{{ old('body', '') }}</textarea>
</div>-->
<div class="form-group">
    {{ Form::label('body', __('t.body'), ['class' => 'control-label']) }}
    <textarea id="summernote-{{$k}}" name="body">{{ old('body', '') }}</textarea>
</div>
<div class="form-group">
    {{ Form::label('meta_description', __('t.meta-description'), ['class' => 'control-label']) }}
    <textarea name="meta_description" rows="4" id="meta" class="form-control">{!! old('meta_description', '') !!}</textarea>
</div><!--form control-->

<input type="hidden" name="lang" value="{{$k}}" />

