@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.contact-us'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.contact-us') ])
    
    <section class="content-area">
        <div class="container  mt-5">
            <div class="col-sm-12 col-xs-12 col-md-6 offset-md-3" style="max-width:500px; margin: 0 auto;">
                <div class="card bg-light">
                    {{ html()->form('POST', route('frontend.contact.send'))->open() }}
                        <div class="card-body pt-5 pb-4 pl-5 pr-5 text-center">
                            <h4 class="card-title mb-4">{{__('t.get-in-touch')}}</h4>
                            
                            <div class="form-row">
                                <div class="form-group has-float-label col-md-12">
                                    <input type="text" name="name" id="name" placeholder="{{__('t.name') }}" 
                                        required="required" class="form-control form-control-md {{$errors->has('name') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.name'))->for('name') }}
                                    <div class="invalid-feedback">{{$errors->first('name')}}</div>
                                </div>
                                
                                <div class="form-group has-float-label col-md-12">
                                    <input type="email" name="email" id="email" placeholder="{{__('t.email') }}" 
                                        required="required" class="form-control form-control-md {{$errors->has('email') ? 'is-invalid' : '' }}">
                                    {{ html()->label(__('t.email'))->for('email') }}
                                    <div class="invalid-feedback">{{$errors->first('email')}}</div>
                                </div>
                                
                                <div class="form-group has-float-label col-md-12">
                                    <textarea name="message" id="message" placeholder="{{__('t.message') }}" 
                                        required="required" rows="6" class="form-control form-control-md {{$errors->has('message') ? 'is-invalid' : '' }}"></textarea>
                                    {{ html()->label(__('t.message'))->for('message') }}
                                    <div class="invalid-feedback">{{$errors->first('message')}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center pt-4 pb-4">
                            <button type="submit" class="btn btn-secondary btn-block text-white">
                                {{__('t.send')}}
                            </button>
                        </div>
                    {{ html()->form()->close() }}
                    
                </div> 
            </div>
        </div>
    </section>
    

@endsection

