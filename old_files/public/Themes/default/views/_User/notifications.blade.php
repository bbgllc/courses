@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.notifications'))

@section('after-styles')
    <style type="text/css">
        .unread-notification{
            background: #e9ebef;
        }
    </style>
@stop

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.notifications')])
    
    
    <section class="content-area bg-gray pt-5 pb-5">
        <div class="container">
            
            <div class="row mb-4">
                
                <div class="col-md-4">
                    @include('includes._user_account_sidebar')
                </div>
                
                <div class="col-md-8">
                    <div class="card border-info">
                        <div class="card-body">
                            <h4 class="text-info mb-4">
                                {{ __('t.notifications') }} 
                            </h4>
                            <div class="text-right mb-2 border border-info border-top-0 border-left-0 border-right-0">
                                <a href="{{route('frontend.user.notifications.mark.all')}}">
                                    {{ __('t.mark-all-as-read') }}
                                </a>
                            </div>
                            
                            <ul class="list-group" style="max-height:600px; overflow-y:scroll;">
                                @foreach(auth()->user()->notifications as $notification)
                                  @include('_User.notifications.'.snake_case(class_basename($notification->type)))
                              	@endforeach
                            </ul>
                              
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </section>

@endsection

