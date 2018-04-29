@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.verify-certificate'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.verify-certificate') ])
    
    <section class="content-area">
        <verify-certificate inline-template v-cloak>
            <div class="container  mt-5">
                <div class="col-sm-12 col-xs-12 col-md-6 offset-md-3" style="max-width:500px; margin: 0 auto;">
                    <div class="card bg-light">
                        <div class="card-body p-5 text-center">
                            <form @submit.prevent="verify">
                                <h4 class="card-title mb-4">{{__('t.enter-certificate-details')}}</h4>
                                
                                <div class="form-row">
                                    <div class="form-group has-float-label col-md-12">
                                        <input type="email" v-model="form.email" id="email" 
                                            placeholder="{{__('t.owner-email') }}" 
                                            :class="{ 'is-invalid': form.errors.has('email') }"
                                            class="form-control form-control-md">
                                        {{ html()->label(__('t.owner-email'))->for('email') }}
                                        <has-error :form="form" field="email"></has-error>
                                    </div>
                                    <div class="form-group has-float-label col-md-12">
                                        <input type="text" v-model="form.username" id="username" 
                                            placeholder="{{__('t.owner-username') }}" 
                                            :class="{ 'is-invalid': form.errors.has('username') }"
                                            class="form-control form-control-md">
                                        {{ html()->label(__('t.owner-username'))->for('username') }}
                                        <has-error :form="form" field="username"></has-error>
                                    </div>
                                    <div class="form-group has-float-label col-md-12">
                                        <input type="text" v-model="form.certificate_number" id="cert_no" 
                                            placeholder="{{__('t.certificate-number') }}" 
                                            :class="{ 'is-invalid': form.errors.has('certificate_number') }"
                                            class="form-control form-control-md">
                                        {{ html()->label(__('t.certificate-number'))->for('cert_no') }}
                                        <has-error :form="form" field="certificate_number"></has-error>
                                    </div>
                                    
                                </div>
                                
                                <div class="form-row">
                                    <button type="submit" class="btn btn-success btn-block text-white mt-2">
                                        {{__('t.verify')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center pt-3 pb-3" v-if="msg">
                            <div class="line-heading text-muted">
                                Search Results
                            </div>
                            <div class="alert alert-danger">
                                @{{msg}}
                            </div>
                            
                        </div>
                        <div class="card-footer text-center pt-3 pb-3" v-if="results.certificate_number">
                            <div class="line-heading text-muted">
                                Search Results
                            </div>
                            <div class="text-left">
                                <div class="alert alert-success">
                                    {{__('t.we-found-a-hit')}}
                                </div>
                                <ul class="list-unstyled">
                                    <li>
                                        <span class="font-weight-bold">{{__('t.awarded-to')}}</span>: 
                                        @{{results.awarded_to}}
                                    </li>
                                    <li>
                                        <span class="font-weight-bold">{{__('t.certificate-number')}}</span>: 
                                        @{{results.certificate_number}}
                                    </li>
                                    <li>
                                        <span class="font-weight-bold">{{__('t.course')}}</span>: 
                                        @{{results.course}}
                                    </li>
                                    <li>
                                        <span class="font-weight-bold">{{__('t.taught-by')}}</span>: 
                                        @{{results.author}}
                                    </li>
                                    <li>
                                        <span class="font-weight-bold">{{__('t.date-obtained')}}</span>: 
                                        @{{results.date_obtained}}
                                    </li>
                                </ul>
                                
                            </div>
                            
                        </div>
                    </div> 
                </div>
            </div>
        </verify-certificate>
    </section>
    

@endsection

