@extends('layouts.master')

@section('title', app_name() . ' | ' . $course->title)

@section('after-styles')
    <style type="text/css">
        .vodal-dialog{ background: transparent !important;}
    </style>
@stop

@section('content')
    
    
    @include('includes._title_header', ['title' => $course->title])
    
    
    <section class="content-area bg-gray pt-5 pb-5">
        <div class="container">
            
            <div class="row mb-4">
                
                <div class="col-md-3">
                    @include('includes._author_course_sidebar')
                </div>
                
                
                <div class="col-md-9">
                    
                    <div class="card border-info">
                        <div class="card-body" style="min-height:250px;">
                            <h4 class="text-info mb-4">{{__('t.pricing-and-coupons')}}</h4>
                            
                            
                            <author-coupons :course_id="{{$course->id}}" inline-template v-cloak>
				                <div>
				                    
				                    <div class="row mb-4">
				                        <div class="col">
                                            <span class="text-success pull-right">
                                                @{{ saveStatus }}
                                            </span>
                                            
                                            <form class="form-inline" @submit.prevent="updatePrice">
                                                <label class="mr-sm-2" for="inlineFormCustomSelectPref">{{__('t.course-price')}}</label>
                                                <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="inlineFormCustomSelectPref" v-model="course.price">
                                                    <option value="0">{{__('t.free')}}</option>
                                                    @foreach($prices as $k => $v)
                                                        <option value="{{$v}}">
                                                            {{ Gabs::currency($v) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-info">{{__('t.save')}}</button>
                                            </form>
                                    
                                            <hr />
                                    
                                            <div class="col-12 clearfix" v-if="!showCreateForm">
                                                <button class="btn btn-info pull-right" :disabled="savedCoursePrice > 0 && course.approved==1 && course.published==1 ? null:'disabled'" @click.prevent="showCreateForm=true">
                                                    {{__('t.create-new-coupon')}}
                                                </button>
                                            </div>
                                    
                                            <form @submit.prevent="createCoupon()" class="form-horizontal" v-if="showCreateForm">
                                                
                                                <div class="form-row mb-4">
                                                    <div class="col-6">
                                                        <label for="code">{{__('t.code')}}</label>
                                                        <input type="text" class="form-control" id="code" 
                                                            :class="{ 'is-invalid': form.errors.has('code') }" v-model="form.code">
                                                        <has-error :form="form" field="code"></has-error>
                                                    </div>
                                                    
                                                    <div class="col-6">
                                                        <label for="code">{{__('t.discount-percent')}}</label>
                                                        <vue-slider ref="slider" 
                                                            :max=100 
                                                            v-model="form.percent"
                                                            tooltip="hover"
                                                            height=20
                                                            :interval=5
                                                            :formatter="form.percent+'% OFF'">
                                                        </vue-slider>
                                                        {{__('t.new-price')}}: 
                                                        <b>
                                                            @if(config('site_settings.site_currency_format') == 'front')
                                                                {{config('site_settings.site_currency_symbol')}}@{{(course.price - (course.price * (form.percent/100))).toFixed(2) }}
                                                            @else
                                                                @{{(course.price - (course.price * (form.percent/100))).toFixed(2) }}{{config('site_settings.site_currency_symbol')}} 
                                                            @endif
                                                        </b>
                                                        <has-error :form="form" field="percent"></has-error>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="form-row">
                                                    <div class="col-6">
                                                        <label for="code">{{__('t.number-of-coupons')}}</label>
                                                        <input type="number" name="quantity" class="form-control" 
                                                            :class="{ 'is-invalid': form.errors.has('quantity') }" v-model="form.quantity" />
                                                        <has-error :form="form" field="quantity"></has-error>
                                                    </div>
                                                    
                                                    <div class="col-6">
                                                        <label for="code">{{__('t.expiry-date-optional')}}</label>
                                                        <datepicker v-model="form.expires" 
                                                            placeholder="Optional" 
                                                            input-class="form-control"
                                                            format="yyyy-MM-dd">
                                                        </datepicker>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="form-row mt-4">
                                                    <div class="col-12">
                                                        <span class="pull-right">
                                                            <a href="#" @click.prevent="showCreateForm=false">{{__('t.cancel')}}</a>
                                                            <button type="submit" class="btn btn-success">{{__('t.save')}}</button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="row">
                                        <div class="table-responsive" v-if="savedCoursePrice > 0 && course.approved==1 && course.published==1">
                                            <table class="table table-striped table-sm">
                                                <thead>
                                                    <th>{{__('t.code')}}</th>
                                                    <th>{{__('t.link')}}</th>
                                                    <th>{{__('t.percent-off')}}</th>
                                                    <th>{{__('t.final-price')}}</th>
                                                    <th>{{__('t.quantity')}}</th>
                                                    <th>{{__('t.quantity-remaining')}}</th>
                                                    <th>{{__('t.expires')}}</th>
                                                    <th>{{__('t.status')}}</th>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="coupon in coupons">
                                                        <td>@{{coupon.code}}</td>
                                                        <td>
                                                            <button @click.prevent="getLink(coupon.link)" :disabled="coupon.exhausted ? 'disabled':null" class="btn btn-success btn-sm">
                                                                {{__('t.get-link')}}
                                                            </button>
                                                        </td>
                                                        <td>@{{coupon.percent}}%</td>
                                                        <td>
                                                            @if(config('site_settings.site_currency_format') == 'front')
                                                                {{config('site_settings.site_currency_symbol')}}@{{coupon.finalPrice }}
                                                            @else
                                                                @{{coupon.finalPrice }}{{config('site_settings.site_currency_symbol')}} 
                                                            @endif

                                                        
                                                        </td>
                                                        <td>@{{coupon.quantity}}</td>
                                                        <td>@{{coupon.quantity - coupon.totalUsed}}</td>
                                                        <td>@{{coupon.expires}}</td>
                                                        <td>
                                                            
                                                            <button :class="coupon.active ? 'btn btn-sm btn-success':'btn btn-sm btn-danger'" @click.prevent="toggleActive(coupon.id, coupon.active)">
                                                                @{{ coupon.active ? 'Active' : 'Inactive' }}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </div>
                                    </div>
                                        
                                    <vodal :show="showModal" 
                                        animation="flip" 
                                        @hide="showModal=false"
                                        :width="800"
                                        :height="150"
                                        :close-button=false
                                        :duration="301">
                                        <div class="card bg-light">
                                            <div class="card-header">
                                                {{__('t.copy-coupon-link')}}
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                      <input type="text" class="form-control" id="couponLink" :value="couponLink">
                                                      <span class="input-group-addon">
                                                          <a href="#" @click.prevent="copyToClipboard()">
                                                              <i class="fa fa-clipboard"></i>
                                                          </a>
                                                      </span>
                                                    </div>
                                                    <small class="text-success">@{{copyStatus}}</small>
                                                </div>
                                            </div>
                                            <div class="card-footer clearfix">
                                                <button class="btn btn-danger btn-sm pull-right" @click="showModal = false">{{__('t.close')}}</button>
                                            </div>
                                            
                                        </div>
                                    </vodal>
				                
				                </div>
				            </author-coupons>
                            
                            
                            
                            
                            
                            
                        </div>
                    </div>
                    
                    
                </div>
            </div>

            
        </div>
    </section>

@endsection

@push('after-scripts')
    <style type="text/css">
        .popover {
            min-width: 100px !important;
        }
    </style>
    <script>
   
        $(function () {
          $('[data-toggle="popover"]').popover();
        })
        
        $(document).on('click',".cancelPop", function () {
            $('[data-toggle="popover"]').popover('hide');
        });
        
    </script>
@endpush