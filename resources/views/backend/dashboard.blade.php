@extends('backend.layouts.app')

@push('after-styles')
    <script src="https://www.gstatic.com/charts/loader.js"></script>
@endpush
@section('content')
    <admin-sales-chart inline-template v-cloak>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-accent-success">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>{{ __('t.sales-last-30-days') }}</h5>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control form-control-sm pull-right" v-model="period" @change="fetchSalesData()">
                                    <option value="7">{{ __('t.last-number-days', ['number' => 7]) }}</option>
                                    <option value="30">{{ __('t.last-number-days', ['number' => 30]) }}</option>
                                    <option value="60">{{ __('t.last-number-days', ['number' => 60]) }}</option>
                                    <option value="90">{{ __('t.last-number-days', ['number' => 90]) }}</option>
                                    <option value="180">{{ __('t.last-number-days', ['number' => 180]) }}</option>
                                </select>
                            </div>
                        </div>
                    </div><!--card-header-->
                    <div class="card-block">
                        
                        <area-chart :data="chartData" :discrete="true" ytitle="Sales ($)" 
                            :download="true" :curve="true" :legend="true" legend="top" label="Sales" :refresh="10"
                                v-if="show_chart"></area-chart>
                        
                    </div><!--card-block-->
                </div><!--card-->
            </div><!--col-->
            <div class="col-md-6">
                <div class="card card-accent-success">
                    <div class="card-header">
                        <h5>
                            {{__('t.where-are-my-users')}} 
                            <span class="pull-right">
                                <i class="fa fa-circle" :class="online > 0 ? 'text-success' : 'text-secondary'"></i> @{{online}} {{ __('t.online')}}
                            </span>
                        </h5>
                        
                    </div>
                    <div class="card-body">
                        <geo-chart :data="countryStats"></geo-chart>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="row">
                    
                    <!-- Sales Figures -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card text-white bg-success">
                                    <div class="card-body pb-0">
                                        <h4 class="mb-0">
                                            @if(config('site_settings.site_currency_format') == 'front')
                                                {{config('site_settings.site_currency_symbol')}}@{{total_sales}}
                                            @else
                                                @{{total_sales}}{{config('site_settings.site_currency_symbol')}}
                                            @endif
                                        </h4>
                                        <p> @{{ trans('t.platform-sales-in-the-last') }} @{{period}} @{{ trans('t.days') }}</p>
                                        <hr />
                                        <h4 class="mb-0">
                                            @if(config('site_settings.site_currency_format') == 'front')
                                                {{config('site_settings.site_currency_symbol')}}@{{total_earnings}}
                                            @else
                                                @{{total_earnings}}{{config('site_settings.site_currency_symbol')}}
                                            @endif
                                        </h4>
                                        <p> @{{ trans('t.platform-earnings-in-the-last') }} @{{period}} @{{ trans('t.days') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card text-white bg-success">
                                    <div class="card-body pb-0">
                                        <h4 class="mb-0">
                                            @if(config('site_settings.site_currency_format') == 'front')
                                                {{config('site_settings.site_currency_symbol')}}@{{lifetime_sales}}
                                            @else
                                                @{{lifetime_sales}}{{config('site_settings.site_currency_symbol')}}
                                            @endif
                                        </h4>
                                        <p>@{{ trans('t.all-time-sales') }}</p>
                                        <hr />
                                        <h4 class="mb-0">
                                            @if(config('site_settings.site_currency_format') == 'front')
                                                {{config('site_settings.site_currency_symbol')}}@{{lifetime_earnings}}
                                            @else
                                                @{{lifetime_earnings}}{{config('site_settings.site_currency_symbol')}}
                                            @endif
                                        </h4>
                                        <p>@{{ trans('t.all-time-platform-earnings') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <!-- Others Figures -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-accent-warning">
                                    <div class="card-body p-2 clearfix">
                                      <i class="fa fa-money bg-warning p-4 font-2xl mr-3 float-left"></i>
                                      <div class="h5 text-primary mb-0 mt-2">{{ $withdrawals->count() }}</div>
                                      <div class="text-muted text-uppercase font-weight-bold font-xs">{{ __('t.withdrawals-pending-processing') }}</div>
                                    </div>
                                    <div class="card-footer px-3 py-2">
                                      <a class="font-weight-bold font-xs btn-block text-muted" href="{{ route('admin.finance.withdrawals') }}">
                                          {{ __('t.details') }} <i class="fa fa-angle-right float-right font-lg"></i>
                                      </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card card-accent-warning">
                                    <div class="card-body p-2 clearfix">
                                      <i class="fa fa-book bg-warning p-4 font-2xl mr-3 float-left"></i>
                                      <div class="h5 text-primary mb-0 mt-2">{{ $courses->count() }}</div>
                                      <div class="text-muted text-uppercase font-weight-bold font-xs">{{ __('t.courses-pending-approval') }}</div>
                                    </div>
                                    <div class="card-footer px-3 py-2">
                                      <a class="font-weight-bold font-xs btn-block text-muted" href="{{ route('admin.course.courses') }}">
                                          {{ __('t.details') }} <i class="fa fa-angle-right float-right font-lg"></i>
                                      </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

            
            <div class="col-md-6">
                <div class="card card-accent-danger clearfix">
                    <div class="card-header clearfix">
                        <i class="fa fa-warning text-danger"></i> {{__('t.system-messages')}}
                        <a href="/admin/remove-demo"
                             data-method="delete"
                             data-trans-button-cancel="{{__('t.cancel')}}"
                             data-trans-button-confirm="{{__('t.delete')}}"
                             data-trans-title="This will delete all courses in your database. It is intended for removing demo courses that came with this script. Are you sure?"
                             class="btn btn-danger pull-right">
                            Delete demo courses
                        </a>
                    </div>
                    
                    <div class="card-body">
                        <div v-if="messages.length">
                            <p>{{ __('t.system-messages-require-attention') }}</p>
                            <hr />
                            <ul class="fa-ul">
                              <li v-for="message in messages">
                                  <i class="fa-li fa fa-times text-danger"></i> @{{message.message}}
                                  <span v-if="message.feature == 'installer'">
                                      <a href="{{route('admin.installer.remove')}}">Click here to remove</a>
                                  </span>
                                  <hr/>
                              </li>
                            </ul>
                        </div>
                        <div v-else>
                            <p>{{ __('t.no-system-messages') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            
            
        </div><!--row-->
    </admin-sales-chart>
@endsection
