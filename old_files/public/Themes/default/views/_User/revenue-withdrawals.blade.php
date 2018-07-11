@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.withdrawals'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.withdrawals')])
    
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
                                <span class="badge badge-danger">
                                    - {{Gabs::currency(auth()->user()->total_withdrawals())}}
                                </span>
                            </div>
                            <hr />
                            
                            <h5>{{__('t.withdrawals-payouts')}}</h5>
                                <div class="card card-body bg-gray clearfix">
                                    <p>
                                        {!!__('t.submit-a-withdrawal-request', ['amount' => Gabs::currency(config('site_settings.earning_minimum_payout_amount')) ]) !!}
                                        
                                    </p>
                                    <form action="{{route('frontend.user.withdrawals')}}" class="form-inline">
                                        <input type="number" min="1" name="amount" placeholder="Amount" class="form-control mb-2 mr-sm-2 mb-sm-0"/>
                                        <input type="email" name="paypal_email" placeholder="PayPal email"  class="form-control mb-2 mr-sm-2 mb-sm-0"/>
                                        <input type="submit" {{Auth::user()->account_balance() < config('settings.minimumPayoutAmount') ? 'disabled':''}} value=" {{__('t.submit')}}" class="btn btn-info"/><br/>
                                        @if ($errors->has('amount'))
                                            <span class="text-danger">
                                                {{ $errors->first('amount') }}
                                            </span>
                                        @endif
                                        @if ($errors->has('paypal_email'))
                                            <span class="text-danger">
                                                {{ $errors->first('paypal_email') }}
                                            </span>
                                        @endif
                                        
                                    </form>    
                                    <hr />
                                </div>
                                    @if($withdrawals->count())
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <th> {{__('t.submitted')}}</th>
                                                <th> {{__('t.updated')}}</th>
                                                <th> {{__('t.amount')}}</th>
                                                <th> {{__('t.paypal-email')}}</th>
                                                <th> {{__('t.status')}}</th>
                                                <th> {{__('t.comments')}}</th>
                                                <th></th>
                                            </thead>
                                            <tbody>
                                                @foreach($withdrawals as $withdrawal)
                                                    <tr>
                                                        <td>{{ date('F d, Y', strtotime($withdrawal->created_at))}}</td>
                                                        <td>{{ date('F d, Y', strtotime($withdrawal->updated_at))}}</td>
                                                        <td>{{ Gabs::currency($withdrawal->amount) }}</td>
                                                        <td>{{ $withdrawal->paypal_email }}</td>
                                                        <td>
                                                            {!! $withdrawal->status == 'processed' ? '<span class="text-success">'.strToUpper($withdrawal->status).'</span>' : '<span class="text-warning">'.strToUpper($withdrawal->status).'</span>' !!}
                                                        </td>
                                                        <td>{{ $withdrawal->comment }}</td>
                                                        <td>
                                                            @if($withdrawal->status == 'pending')
                                                                <a href="{{route('frontend.user.withdrawals.destroy', $withdrawal)}}"
                                                                     data-method="delete"
                                                                     data-trans-button-cancel=" {{__('t.cancel')}}"
                                                                     data-trans-button-confirm=" {{__('t.delete')}}"
                                                                     data-trans-title=" {{__('t.are-you-sure')}}"
                                                                     class="btn btn-sm text-white btn-danger"><i class="fa fa-trash"></i>  {{__('t.delete')}}
                                                                 </a>
                                                            @else 
                                                                <i class="text-muted fa fa-lock fa-2x"></i>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {!! $withdrawals->links() !!}
                                    @endif    
                                </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

