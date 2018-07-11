<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSettings extends Model
{
    protected $fillable=[
        'site_name',
        'site_logo',
        'site_description',
        'site_favicon',
        'site_send_emails',
        'site_google_analytics',
        'site_currency_code',
        'site_currency_symbol',
        'site_currency_format',
        'site_keywords',

        'footer_facebook', 
        'footer_twitter', 
        'footer_instagram',

        'pricelist',

        'payment_enable_paypal',
        'payment_enable_stripe',
        'payment_enable_braintree',
        'payment_enable_pay_with_account_balance',
        'payment_enable_omise',
        
        'video_upload_location',
        'video_max_size',
        'video_allow_upload',
        'video_allow_youtube',
        'video_allow_vimeo',
        
        'earning_organic_sales_percentage',
        'earning_promo_sales_percentage',
        'earning_minimum_payout_amount',
        'earning_affiliate_sales_percentage',
        
        'receipt_address'
        
    ];

}
