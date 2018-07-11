@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.my-courses'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.my-purchase-history')])
    
    <section class="content-area bg-gray pt-2 pb-2">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 mb-5">
                    <ul id="tabsJustified" class="nav nav-tabs" id="myTab" role="tablist">
                        @include('_User._myNav')
                    </ul>
                    <div id="myTabContent" class="card card-body border-top-0">
                        <div class="tab-content">
                            <div id="purchase-history" class="{{ active_class(Active::checkUriPattern('*my-courses/purchases')) }} ">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <th></th>
                                        <th>{{ __('t.date') }}</th>
                                        <th>{{ __('t.coupon-code') }}</th>
                                        <th>{{ __('t.amount-paid') }}</th>
                                        <th>{{ __('t.payment-type') }}</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach($purchases as $purchase)
                                        <tr>
                                            <td>
                                                <a href="{{ route('frontend.course.show', $purchase->course) }}">
                                                    <img src="{{$purchase->course->cover_image}}" 
                                                        style="width: 120px; float: left; padding-right: 20px;" />
                                                    {{$purchase->course->title}}
                                                </a>
                                                <p class="text-muted">{{$purchase->course->subtitle}}</p>
                                            </td>
                                            <td>{{ date('F d, Y', strtotime($purchase->created_at))}}</td>
                                            <td>{{strToUpper($purchase->coupon ? $purchase->coupon->code : '')}}</td>
                                            <td>{{Gabs::currency($purchase->amount)}}</td>
                                            <td>{{strToUpper($purchase->payment_method)}}</td>
                                            <td>
                                                <a href="{{route('frontend.user.receipt.download', $purchase->course)}}" class="btn btn-outline-info btn-sm">
                                                    {{ __('t.receipt') }}
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                   
                                </table>
                                <span class="pull-right">{!! $purchases->links() !!}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

