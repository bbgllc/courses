<li class="nav-item">
    <a href="/revenue/my-sales" class="nav-link small font-weight-bold text-uppercase {{ active_class(Active::checkUriPattern('*my-sales'))}}">
        {{ __('t.sales-revenue') }}
    </a>
</li>
@if(config('site_settings.site_enable_affiliate'))
    <li class="nav-item">
        <a href="/revenue/my-affiliate-earnings" class="nav-link small font-weight-bold text-uppercase {{ active_class(Active::checkUriPattern('*my-affiliate*'))}}">
            {{ __('t.affiliate-earnings') }}
        </a>
    </li>
@endif
<li class="nav-item">
    <a href="/revenue/my-withdrawals" class="nav-link small font-weight-bold text-uppercase {{ active_class(Active::checkUriPattern('*my-withdrawals'))}}">
        {{ __('t.withdrawals') }}    
    </a>
</li>
<li class="nav-item">
    <a href="/revenue/my-transactions" class="nav-link small font-weight-bold text-uppercase {{ active_class(Active::checkUriPattern('*my-transactions'))}}">
        {{ __('t.transactions') }}  
    </a>
</li>
