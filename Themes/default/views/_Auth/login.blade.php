@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.login'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.access') ])
    
    <section class="content-area">
        <div class="container  mt-5">
            <div class="col-sm-12 col-xs-12 col-md-6 offset-md-3" style="max-width:500px; margin: 0 auto;">
                <div class="card bg-light">
                    <div class="card-body p-5 text-center">
                        <h4 class="card-title mb-4">{{__('t.login')}}</h4>
                        
                        {{ html()->form('POST', route('frontend.auth.login.post'))->open() }}
                            <div class="form-row">
                                <div class="form-group has-float-label col-md-12">
                                    <input type="email" name="email" id="email" placeholder="{{__('t.email') }}" autocomplete="off"
                                        required="required" class="form-control form-control-md {{$errors->has('email') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.email'))->for('email') }}
                                    <div class="invalid-feedback">{{$errors->first('email')}}</div>
                                </div>
                                
                                <div class="form-group has-float-label col-md-12">
                                    <input type="password" name="password" id="password" placeholder="{{__('t.password')}}" 
                                        required="required" class="form-control form-control-md {{$errors->has('password') ? 'is-invalid' : '' }}">
                                        {{ html()->label(__('t.password'))->for('password') }}
                                    <div class="invalid-feedback">{{$errors->first('password')}}</div>
                                </div>
                                
                                <div class="form-group col-md-12 text-left">
                                    <label class="custom-control custom-checkbox">
                                      <input type="checkbox" name="remember" value="1" class="custom-control-input">
                                      <span class="custom-control-indicator"></span>
                                      <span class="custom-control-description">
                                          {{__('t.remember-me')}}    
                                      </span>
                                    </label>
                                </div>
                                
                            </div>
                            
                            <div class="form-row">
                                <button type="submit" class="btn btn-secondary btn-block text-white mt-2">{{__('t.login')}}</button>
                            </div>
                            
                            <div class="form-row mt-2">
                                <div class="col">
                                    <a href="{{ route('frontend.auth.password.reset') }}">{{ __('t.forgot-password') }}</a>
                                </div><!--col-->
                            </div><!--row-->
                        {{ html()->form()->close() }}
                        
                        <!--
                        <div class="form-row">
                            <div class="row">
                                <div class="col text-center">
                                    {!! $socialiteLinks !!}
                                </div>
                            </div>
                        </div>
                        -->
                        @if(config('settings.enable_demo'))
                            <div class="form-row mt-4">
                                <div class="row">
                                    <div class="col-12 text-left">
                                        <h6>Login with these Demo Accounts</h6>
                                    </div>
                                    <code class="d-block w-100">
                                        <div class="col-12 text-left mb-2">
                                            Email: admin@educore.io <br />
                                            Password: password <br />
                                        </div>
                                        <div class="col-12 text-left mb-2">
                                            Email: lucy@educore.io <br />
                                            Password: password <br />
                                        </div>
                                        <div class="col-12 text-left">
                                            Email: john@educore.io <br />
                                            Password: password <br />
                                        </div>
                                    </code>
                                </div>
                            </div>
                        @endif
                        
                    </div>
                    <div class="card-footer text-center pt-5 pb-5">
                        <div class="line-heading text-muted">
                            {{__('t.not-yet-registered')}}
                        </div>
                        <a href="/register" class="btn btn-info btn-block text-white mt-2">{{__('t.sign-up')}}</a>
                    </div>
                </div> 
            </div>
        </div>
    </section>
    

@endsection

