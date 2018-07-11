
<script>
    
    import Form from 'vform'
    
    export default {
        data: function () {
            return {
                form: new Form({
                    site_name: '',
                    site_logo: '',
                    site_description:'',
                    site_favicon:'',
                    site_send_emails:'',
                    site_google_analytics:'',
                    site_currency_code: '',
                    site_currency_symbol: '',
                    site_currency_format: '',
                    site_keywords: '',
                    site_enable_affiliate: '',
            
                    footer_facebook: '', 
                    footer_twitter: '', 
                    footer_instagram: '',

                    pricelist: '',
            
                    payment_enable_paypal: '',
                    payment_enable_stripe: '',
                    payment_enable_braintree: '',
                    payment_enable_pay_with_account_balance: '',
                    payment_enable_pay_with_razorpay: '',
                    payment_enable_omise: '',
                    
                    video_upload_location: '',
                    video_max_size: '',
                    video_allow_upload: '',
                    video_allow_youtube: '',
                    video_allow_vimeo: '',
                    
                    earning_organic_sales_percentage: '',
                    earning_promo_sales_percentage: '',
                    earning_minimum_payout_amount: '',
                    earning_affiliate_sales_percentage: '',
                    receipt_address: ''
                    
                }),
            }
        },    
        
        components: {
            
        },
        
        props: [
            'site_settings'
        ],
        
        methods: {
            
            saveSiteSettings(){
                if(this.settings('enable_demo')){
                    swal('Not allowed in Demo')
                    return;
                }
                this.form.post('/api/admin/settings/update')
                    .then(({ data }) => {
                        //this.form.reset();
                    })
            },
        },
        
        mounted() {
            this.form.site_name = this.site_settings.site_name
            this.form.site_logo = this.site_settings.site_logo
            this.form.site_description = this.site_settings.site_description
            this.form.site_favicon = this.site_settings.site_favicon
            this.form.site_send_emails = this.site_settings.site_send_emails
            this.form.site_google_analytics = this.site_settings.site_google_analytics
            this.form.site_currency_code = this.site_settings.site_currency_code
            this.form.site_currency_symbol = this.site_settings.site_currency_symbol
            this.form.site_currency_format = this.site_settings.site_currency_format
            this.form.site_keywords = this.site_settings.site_keywords
            this.form.site_enable_affiliate = this.site_settings.site_enable_affiliate
            
            this.form.footer_facebook = this.site_settings.footer_facebook 
            this.form.footer_twitter = this.site_settings.footer_twitter 
            this.form.footer_instagram = this.site_settings.footer_instagram

            this.form.pricelist = this.site_settings.pricelist
    
            this.form.payment_enable_paypal = this.site_settings.payment_enable_paypal
            this.form.payment_enable_stripe = this.site_settings.payment_enable_stripe
            this.form.payment_enable_braintree = this.site_settings.payment_enable_braintree
            this.form.payment_enable_pay_with_account_balance = this.site_settings.payment_enable_pay_with_account_balance
            this.form.payment_enable_omise = this.site_settings.payment_enable_omise
            this.form.payment_enable_pay_with_razorpay = this.site_settings.payment_enable_pay_with_razorpay
            
            this.form.video_upload_location = this.site_settings.video_upload_location
            this.form.video_max_size = this.site_settings.video_max_size
            this.form.video_allow_upload = this.site_settings.video_allow_upload
            this.form.video_allow_youtube = this.site_settings.video_allow_youtube
            this.form.video_allow_vimeo = this.site_settings.video_allow_vimeo
            
            this.form.earning_organic_sales_percentage = this.site_settings.earning_organic_sales_percentage
            this.form.earning_promo_sales_percentage = this.site_settings.earning_promo_sales_percentage
            this.form.earning_minimum_payout_amount = this.site_settings.earning_minimum_payout_amount
            this.form.earning_affiliate_sales_percentage = this.site_settings.earning_affiliate_sales_percentage
            
            this.form.receipt_address = this.site_settings.receipt_address
            
        }
        
    }
</script>

