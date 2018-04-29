@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.affiliate-earnings'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.affiliate-earnings')])
    
    @include('includes._user_revenue_top')

    <section class="content-area bg-gray pt-2 pb-2">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 mb-5">
                    <ul id="tabsJustified" class="nav nav-tabs" id="myTab" role="tablist">
                        @include('_User._myRevenueNav')
                    </ul>
                    <div id="myTabContent" class="card card-body border-top-0">
                        <div class="tab-content">
                            <div class="text-right">
                                {{__('t.lifetime')}}:
                                <span class="badge badge-success">
                                    {{Gabs::currency(auth()->user()->total_affiliate_earnings())}}
                                </span>
                            </div>
                            <hr />
                            @if($affiliate_earnings->count())
                            <table class="table table-sm table-striped">
                                <thead>
                                    <th>{{__('t.course')}}</th>
                                    <th>{{__('t.date')}}</th>
                                    <th>{{__('t.amount')}}</th>
                                    <th>{{__('t.your-earnings')}}</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach($affiliate_earnings as $affiliate_earning)
                                         <tr>
                                            <td>{{$affiliate_earning->course->title}}</td>
                                            <td>{{$affiliate_earning->created_at->toFormattedDateString()}}</td>
                                            <td>{{Gabs::currency($affiliate_earning->amount)}}</td>
                                            <td>{{Gabs::currency($affiliate_earning->affiliate_earning)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                               
                            </table>
                            
                            {!! $affiliate_earnings->links() !!}
                            
                            @else
                                <p>{{__('t.no-affiliate-earnings-yet')}}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

