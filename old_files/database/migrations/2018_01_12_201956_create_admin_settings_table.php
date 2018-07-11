<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_name')->default('My Site');
            $table->string('site_logo')->nullable();
            $table->text('site_description')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('site_google_analytics')->nullable();
            $table->string('site_currency_code')->default('USD');
            $table->string('site_currency_symbol')->default('$');
            $table->boolean('site_enable_affiliate')->default(true);
            $table->enum('site_currency_format', ['front', 'back'])->default('front');
            $table->text('site_keywords')->nullable();
            $table->string('footer_facebook')->nullable(); 
            $table->string('footer_twitter')->nullable();
            $table->string('footer_instagram')->nullable();
            $table->string('pricelist')->default('10,20,30,40,50,60,70,80,90,100');
            $table->boolean('payment_enable_paypal')->default(true);
            $table->boolean('payment_enable_stripe')->default(true);
            $table->boolean('payment_enable_braintree')->default(false);
            $table->boolean('payment_enable_pay_with_razorpay')->default(false);
            $table->boolean('payment_enable_pay_with_account_balance')->default(true);
            $table->boolean('payment_enable_omise')->default(false);
            
            
            $table->enum('video_upload_location', ['s3', 'local'])->default('local');
            $table->integer('video_max_size')->default(100);
            $table->boolean('video_allow_upload')->default(true);
            $table->boolean('video_allow_youtube')->default(false);
            $table->boolean('video_allow_vimeo')->default(false);
            $table->decimal('earning_organic_sales_percentage', 8,2)->default(40);
            $table->decimal('earning_promo_sales_percentage', 8,2)->default(75);
            $table->decimal('earning_minimum_payout_amount', 8,2)->default(50);
            $table->decimal('earning_affiliate_sales_percentage', 8,2)->default(30);
            $table->integer('earning_affiliate_cookie_lifetime')->default(1440);
            
            
            
            $table->text('receipt_address')->nullable();
            
            $table->timestamps();
        });
        
        $settings = new App\Models\AdminSettings;
        $settings->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_settings');
    }
}
