@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.revenue-report'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.revenue-report')])
    
    @include('includes._user_revenue_top')
    <user-sales-chart inline-template>
        <section class="content-area bg-gray pt-2 pb-2">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12 mb-5">
                        <ul id="tabsJustified" class="nav nav-tabs" id="myTab" role="tablist">
                            @include('_User._myRevenueNav')
                        </ul>
                        <div id="myTabContent" class="card card-body border-top-0">
                            <div class="tab-content">
                                <div class="text-right clearfix">
                                    {{ __('t.lifetime-earnings') }}:
                                    <span class="badge badge-success">
                                        {{Gabs::currency(Auth::user()->total_earnings())}}  
                                    </span>
                                        &nbsp;&nbsp;
                                        {{ __('t.this-month') }}:
                                    <span class="badge badge-success">
                                        {{Gabs::currency(auth()->user()->sales_this_month())}}  
                                    </span>
                                </div>
                                
                                
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <strong>{{ __('t.sales-last-30-days') }}</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-control form-control-sm pull-right" v-model="period" @change="fetchSalesData()">
                                                        <option value="7">Last 7 days</option>
                                                        <option value="30">Last 30 days</option>
                                                        <option value="60">Last 60 days</option>
                                                        <option value="90">Last 90 days</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div><!--card-header-->
                                        <div class="card-block">
                                            <div class="row" v-if="!show_chart">
                                                <div class="col-md-6 offset-md-3 text-center">
                                                    <i class="fa fa-spin fa-cogs fa-2x text-muted"></i>
                                                </div>
                                            </div>
                                            <area-chart :data="chartData" :discrete="true" ytitle="Sales ($)" 
                                                :download="true" :curve="true" :legend="true" legend="top" label="Sales" :refresh="10"
                                                    v-if="show_chart"></area-chart>
                                            
                                        </div>
                                    </div>
                                
                                <hr />
                                
                                
                                @if(count($all_user_earnings))
                                    <div class="row" v-if="!showTable">
                                        <div class="col-md-6 offset-md-3 text-center">
                                            <i class="fa fa-spin fa-cogs fa-2x text-muted"></i>
                                        </div>
                                    </div>
                                    <vue-good-table
                                      title="{{ __('t.purchase-details') }}"
                                      :columns="columns"
                                      :rows="rows"
                                      :lineNumbers="false"
                                      :defaultSortBy="{field: 'created_at_formatted', type: 'asc'}"
                                      :globalSearch="true"
                                      :paginate="true"
                                      styleClass="table condensed table-bordered table-sm table-striped" v-if="showTable">
                                      <template slot="table-row" scope="props">
                                        <td>@{{ props.row.created_at_formatted }}</td>
                                        <td>@{{ props.row.purchased_by }}</td>
                                        <td>@{{ props.row.purchased_course }}</td>
                                        <td>@{{ props.row.purchase_coupon }}</td>
                                        <td>@{{ props.row.paid_amount }}</td>
                                        <td>@{{ props.row.your_earning }}</td>
                                        <!--
                                        <td class="fancy">@{{ props.row.age }}</td>
                                        <td>@{{ props.formattedRow.date }}</td>
                                        <td>@{{ props.index }}</td>
                                        -->
                                      </template>
                                    </vue-good-table>
                                @else 
                                    <p>
                                        {{ __('t.no-sales-yet') }}
                                        
                                    </p>
                                @endif
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </user-sales-chart>
@endsection

