@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.access'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.access')])
    
    <section class="content-area">
        <div class="container mt-5">
            <div class="col-sm-12 col-xs-12 col-md-6 offset-md-3" style="max-width:500px; margin: 0 auto;">
                <div class="card">
                    <div class="card-body p-5 text-center">
                        <h4 class="card-title mb-4">
                            {{ __('t.reset-password') }}
                        </h4>
                        
                        {{ html()->form('POST', route('frontend.auth.password.email.post'))->open() }}
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="form-row">
                                <div class="form-group has-float-label col-md-12">
                                    <input type="email" name="email" id="email" placeholder="{{__('t.email') }}" autocomplete="off" 
                                        required="required" class="form-control form-control-md {{$errors->has('email') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.email'))->for('email') }}
                                    <div class="invalid-feedback">{{$errors->first('email')}}</div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <button type="submit" class="btn btn-secondary btn-block text-white mt-2">
                                    {{ __('t.send-password-reset-link') }}
                                </button>
                            </div>
                        {{ html()->form()->close() }}
                        
                    </div>
                    <div class="card-footer text-center p-5">
                        <div class="line-heading text-muted">
                            {{__('t.did-you-remember-it')}}
                        </div>
                        <a href="/login" class="btn btn-info btn-block text-white mt-2">{{__('t.login')}}</a>
                    </div>
                </div> 
            </div>
        </div>
    </section>

@endsection

