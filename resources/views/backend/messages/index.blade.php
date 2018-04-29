@extends ('backend.layouts.app')

@section ('title', __('t.system-messages'))

@section('breadcrumb-links', '')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fa fa-align-justify"></i>
        <strong>{{__('t.system-messages')}}</strong>
        <small class="text-muted">{{__('t.send-system-messages')}}</small>
        
        <div id="tri" class="btn-group btn-group-sm pull-right" role="group">
            <a href="{{route('admin.messages.index')}}?filter=" type="button" name="total" class="btn {{$filter=='all' ? ' btn-success active':' btn-info'}}">
                {{__('t.all-messages')}}
            </a>
            <a href="{{route('admin.messages.index')}}?filter=draft" type="button" name="total" class="btn {{$filter=='draft' ? ' btn-success active':'btn-info'}}">
                {{__('t.draft-messages')}}
            </a>
            <a href="{{route('admin.messages.index')}}?filter=sent" type="button" name="total" class="btn {{$filter=='sent' ? ' btn-success active':'btn-info'}}">
                {{__('t.sent-messages')}}
            </a>
            <a href="{{route('admin.messages.create')}}" class="btn btn-sm btn-success ml-1" data-toggle="tooltip" title="{{__('t.create-new')}}">
                <i class="fa fa-plus-circle"></i>
            </a>
        </div>
    </div><!--card-header-->
    <div class="card-body">

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>{{__('t.id')}}#</th>
                                <th>{{__('t.subject')}}</th>
                                <th>{{__('t.body')}}</th>
                                <th>{{__('t.status')}}</th>
                                <th>{{__('t.created-on')}}</th>
                                <th>{{__('t.sent-on')}}</th>
                                <th>{{__('t.recipients')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                            <tr>
                                <td>{{ $message->id }}</td>
                                <td>{{ $message->subject }}</td>
                                <td>{!! str_limit($message->body, 150)!!}</td>
                                <td>{{ $message->sent ? 'Sent':'Draft' }}</td>
                                <td>{{ $message->created_at }}</td>
                                <td>{{ $message->sent ? $message->updated_at : null }}</td>
                                <td>{{ $message->recipient_group }}</td>
                                <td>
                                    <a href="{{ route('admin.messages.edit', $message->id) }}" class="btn btn-sm btn-success edit">
                                        {{$message->sent ? trans('t.view'):trans('t.edit')}}
                                    </a>
                                    
                                    @if(!$message->sent)
                                        <a href="{{ route('admin.messages.send', $message->id) }}" class="btn btn-sm btn-success edit">{{trans('t.send')}}</a>
                                    @endif
                                    @if(!$message->sent)
                                        <a href="{{ route('admin.messages.destroy', $message) }}"
                                             data-method="delete"
                                             data-trans-button-cancel="{{__('t.cancel')}}"
                                             data-trans-button-confirm="{{__('t.delete')}}"
                                             data-trans-title="{{__('t.are-you-sure')}}"
                                             class="btn btn-sm btn-danger">
                                            {{ trans('t.delete') }}
                                        </a>
                                    @endif
                                        
                                    
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->



@endsection

@push('after-scripts')
    
@endpush