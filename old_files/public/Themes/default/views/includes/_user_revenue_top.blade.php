<div class="col-md-12 col-xs-12 pt-3 pb-3 mb-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col">
                <p class="text-muted mb-0">{{ __('t.total-revenue') }}</p>
                <h5 class="mb-2">{{Gabs::currency(auth()->user()->total_earnings())}}</h5>
            </div>
            <div class="col">
                <p class="text-muted mb-0">{{ __('t.this-month-earnings') }}</p>
                <h5 class="mt-0">{{Gabs::currency(auth()->user()->sales_this_month())}}</h5>
            </div>
            <div class="col">
                <p class="text-muted mb-0">{{ __('t.withdrawals') }}</p>
                <h5 class="mt-0">{{Gabs::currency(auth()->user()->total_withdrawals())}}</h5>
            </div>
            <div class="col">
                <p class="text-muted mb-0">{{ __('t.affiliate-earnings') }}</p>
                <h5 class="mt-0">{{Gabs::currency(auth()->user()->total_affiliate_earnings())}}</h5>
            </div>
            <div class="col">
                <p class="text-muted mb-0">{{ __('t.account-balance') }}</p>
                <h5 class="mt-0">{{Gabs::currency(auth()->user()->account_balance())}}</h5>
            </div>
            
        </div>
    </div>
</div>

