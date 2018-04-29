@extends ('backend.layouts.app')

@section ('title', __('t.user-management'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fa fa-align-justify"></i>
        <strong>{{ __('t.user-management') }}</strong>
        <small class="text-muted">{{ __('t.active-users') }}</small>
        @include('backend.auth.user.includes.header-buttons')
    </div><!--card-header-->
    <div class="card-body">

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                        <tr>
                            <th>{{ __('t.last-name') }}</th>
                            <th>{{ __('t.first-name') }}</th>
                            <th>{{ __('t.email') }}</th>
                            <th>{{ __('t.confirmed') }}</th>
                            <th>{{ __('t.roles') }}</th>
                            <th>{{ __('t.other-permissions') }}</th>
                            <th>{{ __('t.social') }}</th>
                            <th>{{ __('t.last-updated') }}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{!! $user->confirmed_label !!}</td>
                                <td>{!! $user->roles_label !!}</td>
                                <td>{!! $user->permissions_label !!}</td>
                                <td>{!! $user->social_buttons !!}</td>
                                <td>{{ $user->updated_at->diffForHumans() }}</td>
                                <td>{!! $user->action_buttons !!}</td>
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
                    {!! $users->total() !!} {{ trans_choice('t.user-total', $users->total()) }}
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    {!! $users->render() !!}
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection
