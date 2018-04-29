@extends('layouts.master')

@section('title', app_name() . ' | '.  __('t.checkout') )

@section('after-styles')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@stop

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.checkout')])

    <cart-checkout-form inline-template amount="{{$course->price}}" 
        account_balance="{{auth()->user()->account_balance()}}" 
        course="{{$course->id}}" promocode="{{$coupon_code}}" 
        enable_braintree="{{config('site_settings.payment_enable_braintree')}}" v-cloak>
            <section class="content-area bg-gray pt-5 pb-5">
                <div class="container">
                    
                    <div class="row mb-4">
                        @if ($message = Session::get('success'))
                            <div class="col-md-12">
                                <div class="alert alert-success alert-dismissable in clearfix">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {!! $message !!}
                                </div>
                                <?php Session::forget('success');?>
                            </div>
                        @endif
                        
                        @if ($message = Session::get('error'))
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissable in clearfix">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {!! $message !!}
                                </div>
                                <?php Session::forget('error');?>
                            </div>
                        @endif
                        
                        
                        <div class="col-md-5">
                            
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div v-if="!appliedCoupon">
                                        <form @submit.prevent="applyCoupon()" class="inline-form">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg" v-model="couponCode" required placeholder="{{ __('t.enter-coupon-code') }}">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="submit">{{ __('t.apply') }}</button>
                                                </span>
                                            </div><!-- /input-group -->
                                        </form>
                                        <span class="text-danger" v-if="couponStatus">@{{couponStatus}}</span>
                                    </div>
                                    <div v-if="appliedCoupon">
                                        {{ __('t.apply-coupon') }}: <span class="badge badge-info">@{{appliedCoupon}}</span>  
                                        <a href="#" @click.prevent="removeCoupon">
                                            <i class="fa fa-times text-danger"></i> 
                                            {{ __('t.remove-coupon') }}
                                        </a>
                                    </div>
                                    
                                    <hr class="line-separator" />
                                    
                                    <h5>{{__('t.course')}}</h5>
                                    <div class="media">
                                        <img class="rounded mr-1" src="{{$course->cover_image}}" width="50" alt="">
                                        <div class="media-body">
                                            <h6 class="mt-0 mb-0">{{ $course->title }}</h6>
                                            <small class="text-muted">{{ __('t.by') }} {{$course->author->name}}</small>
                                        </div>
                                    </div>
                                    
                                    <hr class="line-separator" />
                                    <h5>
                                        {{ __('t.amount-due') }}: 
                                        <span class="badge badge-success">
                                            @if(config('site_settings.site_currency_format') == 'front')
                                                {!! config('site_settings.site_currency_symbol') !!}@{{price}}
                                            @else 
                                                @{{price}}{!! config('site_settings.site_currency_symbol') !!}
                                            @endif
                                        </span>
                                        
                                        <span class="text-info" v-if="oldPrice" style="text-decoration: line-through; margin-left: 10px;">
                                            @if(config('site_settings.site_currency_format') == 'front')  
                                                {!! config('site_settings.site_currency_symbol') !!}@{{oldPrice}}
                                            @else
                                                @{{oldPrice}}{!! config('site_settings.site_currency_symbol') !!}
                                            @endif
                                        </span>
                                    </h5>
                                </div>
                            </div>
                            
                            <!-- Show demo credit card if demo mode is enabled -->
                            @if(config('settings.enable_demo'))
                                <div class="card bg-light mt-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 text-left">
                                                <h6>Use these test credit card numbers</h6>
                                            </div>
                                            <code class="d-block w-100">
                                                <div class="col-12 text-left mb-2">
                                                    <b>Card No:</b> 4242 4242 4242 4242<br />
                                                    <b>Exp:</b> 12/22 <br />
                                                    <b>CVV:</b> 123 <br />
                                                </div>
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                        </div>
                        
                        <div class="col-md-7">
                            
                            <div class="card bg-light">
                                <div class="card-body p-5">
                                    
                                    <div class="col-md-12">
                                        <div class="card card-body rounded-0 text-center" v-if="price==0">
                                            <p>{{ __('t.coupon-returns-zero-price') }}</p>
                                            <a href="{{route('frontend.course.enroll', $course)}}" class="btn btn-danger btn-block">
                                                {{ __('t.enroll-now') }}
                                            </a>
                                        </div>
                                        
                                        <div v-show="price > 0">
                                            @if(config('site_settings.payment_enable_stripe'))
                                                <h5 class="text-uppercase text-center text-info">{{ __('t.pay-with-credit-card') }}</h5>
                                                <hr class="line-separator" />
                                                <div class="stripe-errors alert alert-danger alert-dismissable d-none">
                    								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    								<span></span>
                    							</div>
                                                <form id="checkout-form" action="{{ route('frontend.course.charge.stripe') }}" method="post">
                                                    <div class="form-group">
                                                        <div class="card-js stripe" data-stripe="true" data-icon-colour="#259d6d"></div>
                                                        <input type="hidden" name="course" value="{{ $course->id }}">
                                                        <input type="hidden" id="applied-code" class="applied_code" :value="appliedCoupon" name='coupon'>
                                                        <input type="hidden" class="amount" name="amount" :value="price">
                                                        {{ csrf_field() }}
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit" id="checkout-btn" class="btn btn-danger btn-block font-weight-bold">
                                                            <i class="fa fa-lock"></i> 
                                                            {{ __('t.complete-payment') }}
                                                        </button>
                                                    </div>
                                                    
                                                </form>
                                                <img src="/img/frontend/secure-stripe-payment-logo.png" width="150" class="mb-4 pull-right"/>
                                                
                                                
                                                <div class="clearfix"></div>
                                            @endif
                                            
                                            @if(config('site_settings.payment_enable_braintree'))
                                                <h5 class="text-uppercase text-center text-info">{{ __('t.pay-with-credit-card') }}</h5>
                                                <hr class="line-separator" />
                                                <form action="{{route('frontend.courses.charge.braintree')}}" method="POST" v-if="loaded">
                                                    <!--<input type="hidden" :value="csrfToken" />-->
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="course" value="{{ $course->id }}">
                                                    <input type="hidden" id="applied-code" class="applied_code" :value="appliedCoupon" name='coupon'>
                                                    <input type="hidden" class="amount" name="amount" :value="price">
                                                    <div id="dropin"></div>
                                                    <button type="submit" class="btn btn-info btn-block" v-if="showSubmitButton">
                    	                                <i class="fa fa-lock"></i> {{ __('t.complete-payment') }}
                	                                </button>
                                                    
                                                </form>
                                            @endif
                                            
                                            @if(config('site_settings.payment_enable_paypal'))
                                            
                                                <h5 class="text-uppercase text-center text-info mt-4">{{ __('t.pay-with-paypal') }}</h5>
                                                <hr class="line-separator" />
                                                <form method="POST" id="payment-form" role="form" action="{{ route('frontend.course.charge.paypal') }}" >
                        	                        {{ csrf_field() }}
                        	                        <div class="col-md-6">
                                                    	<input type="hidden" name="course" value="{{ $course->id }}">
                                                    	<input type="hidden" id="applied-code" class="applied_code" :value="appliedCoupon" name="coupon">
                        				      			<input type="hidden" id="amount" class="amount" name="amount" :value="price">
                                                    </div>
                        	                        <div class="form-group">
                        	                            <button id="paypal-button" type="submit" class="btn btn-primary btn-block font-weight-bold">
                        	                                <i class="fa fa-paypal"></i> {{ __('t.go-to-paypal') }}
                    	                                </button>
                        	                        </div>
                        	                    </form>
                                            @endif
                                            
                                            @if(config('site_settings.payment_enable_pay_with_razorpay'))
                                                <razorpay-form :price="price" :applied_coupon="appliedCoupon" inline-template>
                                                    <div class="mt-4">
                                                        <h5 class="text-uppercase text-center text-info">{{ __('t.pay-with-razorpay') }}</h5>
                                                        <hr class="line-separator" />
                                                        <form method="POST" id="razorpayform" action="{{ route('frontend.course.charge.razorpay') }}">
                                                            {{ csrf_field() }}
                                                            <div class="col-md-6">
                                                                <input type="hidden" name="razorpay_payment_id" value="" id="razorpay_payment_id">
                                                            	<input type="hidden" name="course" value="{{ $course->id }}">
                                                            	<input type="hidden" id="applied-code" class="applied_code" :value="coupon" name="coupon">
                                				      			<input type="hidden" id="amount" class="amount" name="amount" :value="price">
                                                            </div>
                                                            <div class="form-group">
                                	                            <button id="razorpay-button" type="submit" class="btn btn-danger btn-block font-weight-bold">
                                	                                <i class="fa fa-money"></i> {{ __('t.go-to-razorpay') }}
                            	                                </button>
                                	                        </div>
                                                        </form>
                                                    </div>
                                                </razorpay-form>
                                            @endif
                                            
                                            @if(config('site_settings.payment_enable_pay_with_account_balance'))
                                                <div v-if="account_balance >= price" class="mt-4">
                                                    
                                                    <h5 class="text-uppercase text-center text-info">{{ __('t.pay-with-account-balance') }}</h5>
                                                    <hr class="line-separator" />
                                                    <form method="POST" id="payment-form" role="form" action="{{ route('frontend.course.charge.account_balance') }}" >
                            	                        {{ csrf_field() }}
                            	                        <div class="col-md-6">
                                                        	<input type="hidden" name="course" value="{{ $course->id }}">
                                                        	<input type="hidden" id="applied-code" class="applied_code" :value="appliedCoupon" name="coupon">
                            				      			<input type="hidden" id="amount" class="amount" name="amount" :value="price">
                                                        </div>
                            	                        <div class="form-group">
                            	                            <button id="paypal-button" type="submit" class="btn btn-secondary btn-block font-weight-bold">
                            	                                <i class="fa fa-money"></i> 
                            	                                {{ __('t.pay') }} 
                            	                                
                            	                                @if(config('site_settings.site_currency_format') == 'front')  
                                                                    {!! config('site_settings.site_currency_symbol') !!}@{{price}}
                                                                @else
                                                                    @{{price}}{!! config('site_settings.site_currency_symbol') !!}
                                                                @endif
                            	                                {{ __('t.from-my-account-balance') }}
                        	                                </button>
                            	                        </div>
                            	                    </form>
                                                </div>
                                            @endif
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
        
                </div>
            </section>
    </cart-checkout-form>

@endsection

@push('after-scripts')
    <link href="{{ URL::asset('/css/payment/cardjs.css') }}" rel="stylesheet">
	<script src="https://js.stripe.com/v2/"></script>
	
	<style type="text/css">
        .card-js input:focus{
            box-shadow: none !important;
        }
        .card-js input, .card-js select {
            color: #676767;
            font-size: 15px;
            font-weight: 300;
            height: 50px;
            box-shadow: none;
            background-color: #FFFFFF;
        }
        .card-js .card-number-wrapper .card-type-icon {
            height: 28px;
            width: 32px;
            top: 15px;
        }
        .card-js .icon {
            top: 15px;
        }
    </style>
    
    <script>
        /*============================
	    STRIPE PAYMENT
	    =============================*/
        $(function() {
            Stripe.setPublishableKey("{{config('services.stripe.key')}}");
            
		    $("#checkout-btn").click(function(e) {
		        e.preventDefault()
		        var form = $("#checkout-form");
		        var submit = form.find("button");
		        var submitInitialText = submit.text();
		        submit.attr("disabled", "disabled").html("<i class='fa fa-gear fa-spin'></i> {{ __('t.processing') }}...");
		        Stripe.card.createToken(form, function(status, response) {
		            if(response.error) {
		      	        $('.stripe-errors').removeClass('d-none');
		      	        $('.stripe-errors span').text(response.error.message);
		                form.find(".stripe-errors").text(response.error.message).show();
		                submit.removeAttr("disabled");
		                submit.text(submitInitialText);
		            } else {
		                form.append($("<input type='hidden' name='token'>").val(response.id));
		                form.submit();
		            }
		        });
		    });
		  
		});
		
		// paypal button click event
		$('#paypal-button').click(() => {
		    $('#paypal-button').attr('disabled', 'disabled');
		    $('#paypal-button').html("<i class='fa fa-gear fa-spin'></i> {{ __('t.we-are-taking-you-to-paypal') }}");
		    $('#paypal-button').parents('form').submit()
		})
    </script>
@endpush
