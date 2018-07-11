<!-- Modal -->
{!! Form::model($category, ['route' => ['admin.blog.categories.update', $category], 'method'=>'PUT', 'class' => 'form-horizontal editCategoryForm']) !!}
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ __('t.edit-category') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('t.close') }}">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <!--
            <div class="form-group">
                {!! Form::label("parent_category", __('t.parent-category')) !!}
                {!! Form::select("parent_category", $parentCategories, $category->parent_id, ['class'=>'form-control']) !!}
                @if ($errors->has('parent_category'))
                    <div class="text-danger"><small>{{ $errors->first('parent_category') }}</small></div>
                @endif
            </div>
	        -->
	        <div class="form-group">
                {!! Form::label("name", __('t.name')) !!}
                {!! Form::text("name", null, ['class'=>'form-control', 'required']) !!}
                @if ($errors->has('name'))
                    <div class="text-danger"><small>{{ $errors->first('name') }}</small></div>
                @endif
            </div>
            
            <div class="form-group">
                {!! Form::label("color", __('t.color-scheme')) !!}
                {!! Form::color("color", null, ['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('t.close') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('t.update') }}</button>
        </div>
    </div>
{!! Form::close() !!}
