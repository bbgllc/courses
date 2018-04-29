<div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
    <a href="{{ route('admin.auth.user.create') }}" class="btn btn-sm btn-success ml-1" data-toggle="tooltip" title="{{__('t.create-new')}}"><i class="fa fa-plus-circle"></i></a>
    
    @if(\Request::route()->getName() == 'admin.auth.user.index')
        <a href="{{ route('admin.auth.user.deleted') }}" class="btn btn-sm btn-danger ml-1" data-toggle="tooltip" 
            title="{{__('t.show-trash')}}">
            <i class="fa fa-trash"></i>
        </a>
    @endif
    
    @if(\Request::route()->getName() == 'admin.auth.user.deleted')
        <a href="{{ route('admin.auth.user.index') }}" class="btn btn-sm btn-info ml-1" data-toggle="tooltip" 
            title="{{__('t.show-active-users')}}">
            <i class="fa fa-users"></i>
        </a>
    @endif
</div><!--btn-toolbar-->
