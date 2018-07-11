@extends('layouts.master')

@section('title', app_name() . ' | Update Password')

@section('content')
    
    
    @include('includes._title_header', ['title' => 'Login'])
    
    <section class="content-area">
        <div class="container  mt-5">
            <div class="col-sm-12 col-xs-12 col-md-4 offset-md-4" style="max-width:500px; margin: 0 auto;">
                <div class="card">
                    <div class="card-body p-5 text-center">
                        <h4 class="card-title mb-4">
                            {{ __('labels.frontend.passwords.expired_password_box_title') }}
                        </h4>
                        
                        {{ html()->form('PATCH', route('frontend.auth.password.expired.update'))->open() }}
                            
                            <div class="form-row">
                                <div class="form-group has-float-label col-md-12">
                                    <input type="email" name="email" id="email" placeholder="{{__('validation.attributes.frontend.email') }}" 
                                        required="required" class="form-control form-control-lg {{$errors->has('email') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('validation.attributes.frontend.email'))->for('email') }}
                                    <div class="invalid-feedback">{{$errors->first('email')}}</div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group has-float-label col-md-12">
                                    <input type="password" name="password" id="old_password" placeholder="{{__('validation.attributes.frontend.old_password') }}" 
                                        required="required" class="form-control form-control-lg {{$errors->has('old_password') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('validation.attributes.frontend.old_password'))->for('old_password') }}
                                    <div class="invalid-feedback">{{$errors->first('old_password')}}</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group has-float-label col-md-12">
                                    <input type="password" name="password" id="password" placeholder="{{__('validation.attributes.frontend.password') }}" 
                                        required="required" class="form-control form-control-lg {{$errors->has('password') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('validation.attributes.frontend.password'))->for('password') }}
                                    <div class="invalid-feedback">{{$errors->first('password')}}</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group has-float-label col-md-12">
                                    <input type="password_confirmation" name="password_confirmation" id="password_confirmation" placeholder="{{__('validation.attributes.frontend.password_confirmation') }}" 
                                        required="required" class="form-control form-control-lg {{$errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('validation.attributes.frontend.password_confirmation'))->for('password_confirmation') }}
                                    <div class="invalid-feedback">{{$errors->first('password_confirmation')}}</div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <button type="submit" class="btn btn-secondary btn-block text-white mt-2">
                                    {{ __('labels.frontend.passwords.update_password_button') }}
                                </button>
                            </div>
                        {{ html()->form()->close() }}
                        
                    </div>
                </div> 
            </div>
        </div>
    </section>

@endsection

