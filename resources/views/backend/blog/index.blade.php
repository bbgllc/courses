@extends ('backend.layouts.app')

@section ('title', __('t.blog'))

@section('breadcrumb-links', '')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fa fa-align-justify"></i>
        <strong>{{__('t.blog')}}</strong>
        <small class="text-muted">{{__('t.manage-blog-posts-and-pages')}}</small>
        
        <div class="btn-toolbar float-right" role="toolbar" data-toggle="tooltip" title="{{__('t.create-new')}}">
            <a href="{{route('admin.blog.create')}}" class="btn btn-sm btn-success ml-1">
                <i class="fa fa-plus-circle"></i>
            </a>
        </div><!--btn-toolbar-->
    </div><!--card-header-->
    <div class="card-body">

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('t.title')}}</th>
                                <th>{{__('t.slug')}}</th>
                                <th>{{__('t.category')}}</th>
                                <th>{{__('t.translations')}}</th>
                                <th>{{__('t.status')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->slug }}</td>
                                    <td>{{ $post->category->name }}</td>
                                    <td class="text-uppercase">
                                        @foreach( $post->translations as $translation )
                                            <span class="badge badge-primary">
                                                {{ $translation->locale }}
                                            </span>
                                        @endforeach
                                        
                                    
                                    </td>
                                    <td>
                                        {!! $post->published ? '<span class="badge badge-success">'.__('t.published').'</span>' : '<span class="badge badge-warning">'.__('t.draft').'</span>' !!}</td>
                                    <td>
                                        <a href="{{route('admin.blog.edit', $post)}}" class="btn btn-sm btn-info text-white">
                                            {{__('t.edit')}}
                                        </a>
                                        <a href="{{ route('admin.blog.destroy', $post) }}"
                                             data-method="delete"
                                             data-trans-button-cancel="{{__('t.cancel')}}"
                                             data-trans-button-confirm="{{__('t.delete')}}"
                                             data-trans-title="{{__('t.are-you-sure')}}"
                                             class="btn btn-sm btn-danger text-white">
                                            {{__('t.delete')}}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->
        
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    {{-- $posts->links() --}}
                </div>
            </div><!--col-->
            
            
            
            
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->

@endsection

@section('modals')

@stop