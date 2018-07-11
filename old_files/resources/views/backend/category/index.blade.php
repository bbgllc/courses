@extends ('backend.layouts.app')

@section ('title', __('t.course-categories'))

@push('after-styles')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.css" />
    <link rel="stylesheet" href="/css/nestable.css" />
@endpush

@section('breadcrumb-links', '')

@section('content')






<div class="card">
    <div class="card-header">
        <i class="fa fa-align-justify"></i>
        <strong>{{__('t.course-categories')}}</strong>
        <small class="text-muted">{{__('t.manage-course-categories')}}</small>
        
        <div class="btn-toolbar float-right" role="toolbar" data-toggle="tooltip" title="{{ __('t.add-new') }}">
            <a href="#" class="btn btn-sm btn-success ml-1" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#createCategoryModal" ><i class="fa fa-plus-circle"></i></a>
        </div><!--btn-toolbar-->
    </div><!--card-header-->
    <div class="card-body">
        <div class="dd">
            <menu id="nestable-menu" class="mt-0">
                <button type="button" class="btn btn-sm btn-success" data-action="expand-all">{{ __('t.expand-all') }}</button>
                <button type="button" class="btn btn-sm btn-danger"  data-action="collapse-all">{{ __('t.collapse-all') }}</button>
            </menu>
            <ol class="dd-list">
                @foreach($catArray as $v)
                    <li class="dd-item dd3-item" data-id="{{ $v->id }}">
                        <div class="dd-handle dd3-handle">Drag</div>
                        <div class="dd3-content">
                            {{ $v->name }}
                            <span class="pull-right">
                                <a href="{{ route('admin.course.categories.edit', $v) }}" class="edit">
                                    <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="{{__('t.edit') }}"></i>
                                </a>
                                @if(!$v->subcategoryCourses->count())
                                    <a href="{{ route('admin.course.category.destroy', $v) }}"
                                         data-method="delete"
                                         data-trans-button-cancel="{{__('t.cancel')}}"
                                         data-trans-button-confirm="{{__('t.delete')}}"
                                         data-trans-title="{{__('t.are-you-sure')}}"
                                         class="">
                                        <i class="fa fa-trash text-danger"></i>
                                     </a>
                                 @endif
                            </span>
                        </div>
                        <ol class="dd-list">
                            @foreach($v->subCategories as $sub)
                                <li class="dd-item clearfix" data-id="{{ $sub->id }}">
                                    <div class="dd-handle dd3-handle">Drag</div>
                                    <div class="dd3-content">
                                        {{ $sub->name }} <span class="badge badge-secondary">{{$sub->courses->count()}}</span> 
                                        <span class="pull-right">
                                            <a href="{{ route('admin.course.categories.edit', $sub) }}" class="edit">
                                                <i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="{{__('t.edit') }}"></i>
                                            </a> 
                                            @if(!$sub->courses->count())
                                                <a href="{{ route('admin.course.category.destroy', $sub) }}"
                                                     data-method="delete"
                                                     data-trans-button-cancel="{{__('t.cancel')}}"
                                                     data-trans-button-confirm="{{__('t.delete')}}"
                                                     data-trans-title="{{__('t.are-you-sure')}}"
                                                     class="">
                                                    <i class="fa fa-trash text-danger"></i>
                                                 </a>
                                             @endif
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>  

</div>



@endsection

@section('modals')

    <!-- Modal -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="CreateCategory" aria-hidden="true">
        <div class="modal-dialog modal-success" role="document">
            {!! Form::open(['route' => ['admin.course.categories.store'], 'method'=>'POST', 'class' => 'form-horizontal create-category-form']) !!}
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('t.create-category')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group">
                            {!! Form::label("parent_category", __('t.parent-category')) !!}
                            {!! Form::select("parent_category", $parentCategories, null, ['class'=>'form-control']) !!}
                            @if ($errors->has('parent_category'))
                                <div class="text-danger"><small>{{ $errors->first('parent_category') }}</small></div>
                            @endif
                        </div>
            	        
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
                        <button type="submit" class="btn btn-primary">{{ __('t.create') }}</button>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    
    <!-- Edit category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="EditCategory" aria-hidden="true">
        <div class="modal-dialog modal-success" role="document">
        
        </div>
    </div>

    <script>
        $(document).ready(function($){
            $('.edit').click(function(e){
				$('#editCategoryModal').modal('show', {backdrop: 'static'});
				e.preventDefault();
				href = $(this).attr('href');
				$.ajax({
					url: href,
					success: function(response){
						$('#editCategoryModal .modal-dialog').html(response);
					}
				});
			});
        });
			
	</script>
	
	
	
	
	
	
	<!----------- nestable ---------->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
    
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('.dd').nestable({ 
                 maxDepth: 2
            }).nestable('collapseAll');
            
            
            $('.dd').on('change', function() {
                //console.log($('.dd').nestable('serialize'));
                
                $.post('/api/admin/videos/categories/sort_order', {
                    _token : $('#_token').val(),
                    sortOrder : JSON.stringify($('.dd').nestable('serialize'))
                }, function(data){
                    console.log(data)
                })
            });
           
           
            // expand menu 
            $('#nestable-menu').on('click', function(e) {
                var target = $(e.target),
                    action = target.data('action');
                if(action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if(action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });
           
           
           
           
        });
    </script>
	

@stop