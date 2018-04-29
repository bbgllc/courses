@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.access'))

@section('content')
    
    @include('includes._title_header', ['title' => __('t.access')])
    
    
    
    <section class="content-area">
        <div class="container  mt-5">
            <div class="col-sm-12 col-xs-12 col-md-4 offset-md-4" style="max-width:500px; margin: 0 auto;">
                <div class="card">
                    <div class="card-body p-5 text-center">
                        <h4 class="card-title mb-4">
                            {{ __('t.reset-password') }}
                        </h4>
                        
                        {{ html()->form('POST', route('frontend.auth.password.reset'))->open() }}
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            
                            {{ html()->hidden('token', $token) }}
                            <div class="form-row">
                                <div class="form-group has-float-label col-md-12">
                                    <input type="email" name="email" id="email" placeholder="{{__('t.email') }}" autocomplete="off" 
                                        required="required" class="form-control form-control-lg {{$errors->has('email') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.email'))->for('email') }}
                                    <div class="invalid-feedback">{{$errors->first('email')}}</div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group has-float-label col-md-12">
                                    <input type="password" name="password" id="password" placeholder="{{__('t.password') }}" 
                                        required="required" class="form-control form-control-lg {{$errors->has('password') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.password'))->for('password') }}
                                    <div class="invalid-feedback">{{$errors->first('password')}}</div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group has-float-label col-md-12">
                                    <input type="password_confirmation" name="password_confirmation" id="password_confirmation" placeholder="{{__('t.password-confirmation') }}" 
                                        required="required" class="form-control form-control-lg {{$errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.password-confirmation'))->for('password_confirmation') }}
                                    <div class="invalid-feedback">{{$errors->first('password_confirmation')}}</div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <button type="submit" class="btn btn-secondary btn-block text-white mt-2">
                                    {{ __('t.reset-password') }}
                                </button>
                            </div>
                        {{ html()->form()->close() }}
                        
                    </div>
                </div> 
            </div>
        </div>
    </section>

@endsection

