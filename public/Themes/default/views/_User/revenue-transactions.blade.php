@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.transactions'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.transactions')])
    
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
                            <h5>{{ __('t.transaction-history') }}</h5>

                            <table class="table table-sm table-striped">
                                <thead>
                                    <th>{{__('t.date')}}</th>
                                    <th>{{__('t.transaction-id')}}</th>
                                    <th>{{__('t.type')}}</th>
                                    <th>{{__('t.details')}}</th>
                                    <th>{{__('t.amount')}}</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                         <tr>
                                            <td>{{$transaction->created_at->toFormattedDateString()}}</td>
                                            <td>{{$transaction->uuid}}</td>
                                            <td>{{$transaction->description}}</td>
                                            <td>{{$transaction->long_description}}</td>
                                            <td>{{Gabs::currency($transaction->amount)}}</td>
                                            <td>
                                                @if($transaction->type == 'credit')
                                                    <i class="fa fa-plus-circle text-success"></i>
                                                @else
                                                    <i class="fa fa-minus-circle text-danger"></i>
                                                @endif
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                               
                            </table>
                            
                            {!! $transactions->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

