@extends ('backend.layouts.app')

@section ('title', __('t.site-settings'))

@section('breadcrumb-links', '')

@section('content')
    <site-settings inline-template :site_settings="{{$settings}}">
        
        <form class="form-horizontal">
            <div class="row">
                <div class="col-md-12" v-if="form.successful">
                    <alert-success :form="form" :message="trans('t.settings-updated')"></alert-success>
                </div>
                <div class="col-md-6">
                    <div class="card card-accent-secondary">
                        <div class="card-header">
                            <i class="fa fa-cogs"></i>
                            <strong>{{__('t.site-settings')}}</strong>
                            <small class="text-muted">{{__('t.manage-global-settings')}}</small>
                        </div><!--card-header-->
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.site-name')}}</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="form.site_name" class="form-control" 
                                        :class="{ 'is-invalid': form.errors.has('site_name') }">
                                        <has-error :form="form" field="site_name"></has-error>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.site-description')}}</label>
                                <div class="col-md-9">
                                    <textarea v-model="form.site_description" class="form-control" :class="{ 'is-invalid': form.errors.has('site_description') }"></textarea>
                                    <has-error :form="form" field="site_description"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.site-keywords')}}</label>
                                <div class="col-md-9">
                                    <textarea v-model="form.site_keywords" class="form-control" :class="{ 'is-invalid': form.errors.has('site_keywords') }"></textarea>
                                    <has-error :form="form" field="site_keywords"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Google Analytics</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="form.site_google_analytics" class="form-control" 
                                        placeholder="UA-XXXXXXXX"
                                        :class="{ 'is-invalid': form.errors.has('site_google_analytics') }">
                                        <has-error :form="form" field="site_google_analytics"></has-error>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.site-currency-code')}}</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="form.site_currency_code" class="form-control" 
                                        placeholder="USD"
                                        :class="{ 'is-invalid': form.errors.has('site_currency_code') }">
                                        <has-error :form="form" field="site_currency_code"></has-error>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.site-currency-symbol')}}</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="form.site_currency_symbol" class="form-control" 
                                        placeholder="$"
                                        :class="{ 'is-invalid': form.errors.has('site_currency_symbol') }">
                                        <has-error :form="form" field="site_currency_symbol"></has-error>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.site-currency-format')}}</label>
                                <div class="col-md-9">
                                    <select v-model="form.site_currency_format" class="form-control"
                                        :class="{ 'is-invalid': form.errors.has('site_currency_format') }">
                                        <option value="front">$10</option>
                                        <option value="back">10$</option>
                                    </select>
                                    <has-error :form="form" field="site_currency_format"></has-error>
                                </div>
                            </div>
                            <h4>{{__('t.social-settings')}}</h4>
                            <hr />
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.facebook-page-name')}}</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-facebook-square"></i>
                                        </span>
                                        <input type="text" class="form-control" 
                                            v-model="form.footer_facebook"
                                            :class="{ 'is-invalid': form.errors.has('footer_facebook') }"
                                            placeholder="John.Doe">
                                        <has-error :form="form" field="footer_facebook"></has-error>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.instagram-name')}}</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-instagram"></i>
                                        </span>
                                        <input type="text" class="form-control" 
                                            v-model="form.footer_instagram"
                                            :class="{ 'is-invalid': form.errors.has('footer_instagram') }"
                                            placeholder="John.Doe">
                                        <has-error :form="form" field="footer_instagram"></has-error>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.twitter-handle')}}</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-twitter-square"></i>
                                        </span>
                                        <input type="text" class="form-control" 
                                            v-model="form.footer_twitter"
                                            :class="{ 'is-invalid': form.errors.has('footer_twitter') }"
                                            placeholder="John.Doe">
                                        <has-error :form="form" field="footer_twitter"></has-error>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <h4>{{__('t.video-settings')}}</h4>
                            <hr />
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.video-allow-upload')}}</label>
                                <div class="col-md-9">
                                    <select class="custom-select my-1 mr-sm-2" v-model="form.video_allow_upload" 
                                        id="video_allow_upload">
                                        <option value="0">{{__('t.no')}}</option>
                                        <option value="1">{{__('t.yes')}}</option>
                                    </select>
                                </div>
                            </div><!--form-group-->
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.video-allow-youtube')}}</label>
                                <div class="col-md-9">
                                    <select class="custom-select my-1 mr-sm-2" v-model="form.video_allow_youtube" 
                                        id="video_allow_youtube">
                                        <option value="0">{{__('t.no')}}</option>
                                        <option value="1">{{__('t.yes')}}</option>
                                    </select>
                                </div>
                            </div><!--form-group-->
                            
                            <!--
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.video-allow-vimeo')}}</label>
                                <div class="col-md-9">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" v-model="form.video_allow_vimeo" value="1"
                							:checked="form.video_allow_vimeo == 1 ? 'checked' : null" 
                							class="custom-control-input" id="video_allow_vimeo">
                						<label class="custom-control-label" for="video_allow_vimeo">
                						 {{__('t.video-allow-vimeo')}}
                						</label>
            						</div>
            						
                                </div>
                            </div>
                            -->
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.video-upload-location')}}</label>
                                <div class="col-md-9">
                                    <select v-model="form.video_upload_location" class="form-control"
                                        :class="{ 'is-invalid': form.errors.has('video_upload_location') }">
                                        <option value="local">{{__('t.local-server')}}</option>
                                        <option value="s3">Amazon S3 Bucket</option>
                                    </select>
                                    <has-error :form="form" field="video_upload_location"></has-error>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.max-video-size')}}</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="form.video_max_size" class="form-control" 
                                        :class="{ 'is-invalid': form.errors.has('video_max_size') }">
                                        <has-error :form="form" field="video_max_size"></has-error>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" @click.prevent="saveSiteSettings()" class="btn btn-md btn-success pull-right">
                                <i class="fa fa-save"></i> {{__('t.save')}}
                            </button>
                        </div>
                    </div><!--card-->
                </div>
                
                
                
                <!-- Pricing and payment -->
                <div class="col-md-6">
                    <div class="card card-accent-secondary">
                        <div class="card-header">
                            <i class="fa fa-cogs"></i>
                            <strong>{{__('t.site-settings')}}</strong>
                        </div><!--card-header-->
                        <div class="card-body">
                            <h4>{{__('t.payment-settings')}}</h4>
                            <hr />
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.price-list')}}</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="form.pricelist" class="form-control"
                                        :class="{ 'is-invalid': form.errors.has('pricelist') }">
                                        <has-error :form="form" field="pricelist"></has-error>
                                        
                                        <span>{{__('t.separated-by-commas')}}</span>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.enable-paypal')}}</label>
                                <div class="col-md-9">
                                    <select class="custom-select my-1 mr-sm-2" v-model="form.payment_enable_paypal" 
                                        id="payment_enable_paypal">
                                        <option value="0">{{__('t.no')}}</option>
                                        <option value="1">{{__('t.yes')}}</option>
                                    </select>
                                    
                                </div>
                            </div><!--form-group-->
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.enable-stripe')}}</label>
                                <div class="col-md-9">
                                    <select class="custom-select my-1 mr-sm-2" v-model="form.payment_enable_stripe" 
                                        id="payment_enable_stripe">
                                        <option value="0">{{__('t.no')}}</option>
                                        <option value="1">{{__('t.yes')}}</option>
                                    </select>
                                </div>
                            </div><!--form-group-->
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.enable-braintree')}}</label>
                                <div class="col-md-9">
                                    <select class="custom-select my-1 mr-sm-2" v-model="form.payment_enable_braintree" 
                                        id="enable_braintree">
                                        <option value="0">{{__('t.no')}}</option>
                                        <option value="1">{{__('t.yes')}}</option>
                                    </select>
                                </div>
                            </div><!--form-group-->
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.enable-pay-with-account-balance')}}</label>
                                <div class="col-md-9">
                                    <select class="custom-select my-1 mr-sm-2" v-model="form.payment_enable_pay_with_account_balance" 
                                        id="payment_enable_pay_with_account_balance">
                                        <option value="0">{{__('t.no')}}</option>
                                        <option value="1">{{__('t.yes')}}</option>
                                    </select>
                                </div>
                            </div><!--form-group-->
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.enable-pay-with-razorpay')}}</label>
                                <div class="col-md-9">
                                    <select class="custom-select my-1 mr-sm-2" v-model="form.payment_enable_pay_with_razorpay" 
                                        id="payment_enable_pay_with_razorpay">
                                        <option value="0">{{__('t.no')}}</option>
                                        <option value="1">{{__('t.yes')}}</option>
                                    </select>
                                </div>
                            </div><!--form-group-->
                            
                            <h4>{{__('t.author-earnings')}}</h4>
                            <hr />
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.enable-affiliate')}}</label>
                                <div class="col-md-9">
                                    <select class="custom-select my-1 mr-sm-2" v-model="form.site_enable_affiliate" 
                                        id="site_enable_affiliate">
                                        <option value="0">{{__('t.no')}}</option>
                                        <option value="1">{{__('t.yes')}}</option>
                                    </select>
                                    
                                </div>
                            </div><!--form-group-->
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.affiliate-sales-percentage')}}</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="form.earning_affiliate_sales_percentage" class="form-control" 
                                        :class="{ 'is-invalid': form.errors.has('earning_affiliate_sales_percentage') }">
                                        <has-error :form="form" field="earning_affiliate_sales_percentage"></has-error>
                                    
                                </div>
                            </div><!--form-group-->
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.organic-sales-earnings')}}</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="form.earning_organic_sales_percentage" class="form-control" 
                                        :class="{ 'is-invalid': form.errors.has('earning_organic_sales_percentage') }">
                                        <has-error :form="form" field="earning_organic_sales_percentage"></has-error>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.author-promo-earnings')}}</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="form.earning_promo_sales_percentage" class="form-control" 
                                        :class="{ 'is-invalid': form.errors.has('earning_promo_sales_percentage') }">
                                        <has-error :form="form" field="earning_promo_sales_percentage"></has-error>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.minimum-payout-amount')}}</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="form.earning_minimum_payout_amount" class="form-control" 
                                        :class="{ 'is-invalid': form.errors.has('earning_minimum_payout_amount') }">
                                        <has-error :form="form" field="earning_minimum_payout_amount"></has-error>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">{{__('t.payment-receipt-address')}}</label>
                                <div class="col-md-9">
                                    <textarea v-model="form.receipt_address" class="form-control" :class="{ 'is-invalid': form.errors.has('receipt_address') }"></textarea>
                                    <has-error :form="form" field="receipt_address"></has-error>
                                </div>
                            </div>
                            
                            <h4>{{__('t.images')}}</h4>
                            <hr />
                            <upload-logos inline-template img_src="{{$settings->site_logo}}" 
                                url="/api/admin/upload/logos?type=site_logo"
                                msg="Choose site logo"
                                field="site_logo" v-cloak>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <span class="pull-left">
                                            <img class="img-responsive" style="max-width: 200px;" :src="src" /><br/>
                                            {{__('t.max-size')}}: 144x40px
                                        </span>
                                        
                                        <span v-if="saveStatus">
                                            <i class="fa fa-gear fa-spin"></i> @{{ saveStatus }}
                                        </span>
                                        <image-upload
                                            :class="['btn', 'btn-primary btn-sm mt-2 pull-right']"
                                            text="{{ __('t.choose-logo') }}"
                                            @imageuploaded="fileUploaded"
                                            @imageuploading="saveStatus=trans('t.processing')"
                                            extensions="png,jpeg,jpg,gif"
                                            :max-file-size="5242880"
                                            compress="50"
                                            :url="uploadURL">
                                        </image-upload>
                                        
                                    </div>
                                </div>
                            </upload-logos>
                            
                            <hr />
                            <upload-logos inline-template img_src="{{$settings->site_favicon}}" 
                                url="/api/admin/upload/logos?type=site_favicon"
                                msg="Choose site favicon"
                                field="site_favicon" v-cloak>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <span class="pull-left">
                                            <img class="img-responsive" style="max-width: 48px;" :src="src" /><br />
                                            {{__('t.max-size')}}: 48x48px | {{__('t.file-type')}}: .png or .ico
                                        </span>
                                        <span v-if="saveStatus">
                                            <i class="fa fa-gear fa-spin"></i> @{{ saveStatus }}
                                        </span>
                                        <image-upload
                                            :class="['btn', 'btn-primary btn-sm mt-2 pull-right']"
                                            text="{{ __('t.choose-favicon') }}"
                                            @imageuploaded="fileUploaded"
                                            @imageuploading="saveStatus=trans('t.processing')"
                                            extensions="png,jpeg,jpg,gif"
                                            :max-file-size="5242880"
                                            compress="50"
                                            :url="uploadURL">
                                        </image-upload>
                                        
                                    </div>
                                </div>
                            </upload-logos>
                                
                        </div>
                        <div class="card-footer">
                            <button type="button" @click.prevent="saveSiteSettings()" class="btn btn-md btn-success pull-right">
                                <i class="fa fa-save"></i> {{__('t.save')}}
                            </button>
                        </div>
                    </div><!--card-->
                </div>
                
            </div>
        </form>
    </site-settings>

@endsection

@section('modals')

    
@stop