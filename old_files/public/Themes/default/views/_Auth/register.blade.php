@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.register'))

@section('content')
    
    @include('includes._title_header', ['title' => __('t.register')])
    
    <section class="content-area">
        <div class="container  mt-5">
            <div class="col-sm-12 col-xs-12 col-md-6 offset-md-3" style="max-width:500px; margin: 0 auto;">
                <div class="card bg-light">
                    <div class="card-body p-5 text-center">
                        <h4 class="card-title mb-4">{{__('t.sign-up')}}</h4>
                        
                        {{ html()->form('POST', route('frontend.auth.register.post'))->open() }}
                            <div class="form-row">
                                <div class="form-group has-float-label col-md-12">
                                    <select name="country" id="country" placeholder="{{__('t.country')}}" value="{{old('country')}}"
                                        class="form-control form-control-md {{$errors->has('country') ? 'is-invalid' : '' }}">
                                        <option value></option>
                                        @foreach($countries as $k=>$v)
                                            <option value="{{$k}}">{{ $v }}</option>
                                        @endforeach
                                    </select>
                                    {{ html()->label(__('t.country'))->for('country') }}
                                    <div class="invalid-feedback">{{$errors->first('country')}}</div>
                                </div>
                                
                                <div class="form-group has-float-label col-md-12">
                                    <input type="text" name="username" id="username" placeholder="{{__('t.username')}}" value="{{old('username')}}"
                                        required="required" class="form-control form-control-md {{$errors->has('username') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.username'))->for('username') }}
                                    <div class="invalid-feedback">{{$errors->first('username')}}</div>
                                </div>
                                <div class="form-group has-float-label col-md-12">
                                    <input type="text" name="first_name" id="first_name" placeholder="{{ __('t.first-name') }}" 
                                        value="{{old('first_name')}}" required="required" class="form-control form-control-md {{$errors->has('first_name') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.first-name'))->for('first_name') }}
                                    <div class="invalid-feedback">{{$errors->first('first_name')}}</div>
                                </div>
                                
                                <div class="form-group has-float-label col-md-12">
                                    <input type="text" name="last_name" id="last_name" placeholder="{{__('t.last-name') }}" 
                                        value="{{old('last_name')}}" required="required" class="form-control form-control-md {{$errors->has('last_name') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.last-name'))->for('last_name') }}
                                    <div class="invalid-feedback">{{$errors->first('last_name')}}</div>
                                </div>
                                
                                <div class="form-group has-float-label col-md-12">
                                    <input type="email" name="email" id="email" placeholder="{{__('t.email') }}" autocomplete="off" 
                                        value="{{old('email')}}" required="required" class="form-control form-control-md {{$errors->has('email') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.email'))->for('email') }}
                                    <div class="invalid-feedback">{{$errors->first('email')}}</div>
                                </div>
                                
                                <div class="form-group has-float-label col-md-12">
                                    <input type="password" name="password" id="password" placeholder="{{__('t.password')}}" 
                                        required="required" class="form-control form-control-md {{$errors->has('password') ? 'is-invalid' : '' }}">
                                        {{ html()->label(__('t.password'))->for('password') }}
                                    <div class="invalid-feedback">{{$errors->first('password')}}</div>
                                </div>
                                
                                <div class="form-group has-float-label col-md-12">
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="{{__('t.password-confirmation')}}" 
                                        required="required" class="form-control form-control-md {{$errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.password-confirmation'))->for('password_confirmation') }}
                                    <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                                </div>
                                
                            </div>
                            
                            @if(config('access.captcha.registration'))
                                <div class="form-row">
                                    <div class="col">
                                        {!! Captcha::display() !!}
                                        {{ html()->hidden('captcha_status', 'true') }}
                                    </div><!--col-->
                                </div>
                            @endif
                            
                            <div class="form-row">
                                <button type="submit" class="btn btn-secondary btn-block text-white mt-2">{{__('t.register')}}</button>
                            </div>
                        {{ html()->form()->close() }}
                        <!--
                        <div class="form-row">
                            <div class="row">
                                <div class="col text-center mt-4">
                                    <div class="line-heading text-muted">
                                        Social Login
                                    </div>
                                    {!! $socialiteLinks !!}
                                </div>
                            </div>
                        </div>
                        -->
                        
                    </div>
                    <div class="card-footer text-center p-5">
                        <div class="line-heading text-muted">
                            {{__('t.already-registered')}}
                        </div>
                        <a href="/login" class="btn btn-info btn-block text-white mt-2">{{__('t.login')}}</a>
                    </div>
                </div> 
            </div>
        </div>
    </section>

@endsection

@push('after-scripts')
    @if (config('access.captcha.registration'))
        {!! Captcha::script() !!}
    @endif
@endpush
