<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\AdminSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot( UrlGenerator $url)
    {

        /*
         * Application locale defaults for various components
         *
         * These will be overridden by LocaleMiddleware if the session local is set
         */

        /*
         * setLocale for php. Enables ->formatLocalized() with localized values for dates
         */
        setlocale(LC_TIME, config('app.locale_php'));
        
        
        /*
         * setLocale to use Carbon source locales. Enables diffForHumans() localized
         */
        //Carbon::setLocale(config('app.locale'));

        /*
         * Set the session variable for whether or not the app is using RTL support
         * For use in the blade directive in BladeServiceProvider
         */
         /*
        if (! app()->runningInConsole()) {
            if (config('locale.languages')[config('app.locale')][2]) {
                session(['lang-rtl' => true]);
            } else {
                session()->forget('lang-rtl');
            }
        }*/

        if(env('FORCE_HTTPS')){
            $this->app['request']->server->set('HTTPS', true);
            $url->forceScheme('https');
        }
        
        // load settings
        if(\Storage::disk('installPath')->exists('DO_NOT_TOUCH/site_installed.key')){
            if (Schema::hasTable('admin_settings')) {
                $settings = AdminSettings::first()->toArray();
                
                foreach ($settings as $key => $value) {
                    \Config::set('site_settings.'.$key, $value);
                }
            }
        }
        
        Validator::extend('no_spaces_allowed', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });

        // Set the default string length for Laravel5.4
        // https://laravel-news.com/laravel-5-4-key-too-long-error
        Schema::defaultStringLength(191);

        // Set the default template for Pagination to use the included Bootstrap 4 template
        \Illuminate\Pagination\AbstractPaginator::defaultView('pagination::bootstrap-4');
        \Illuminate\Pagination\AbstractPaginator::defaultSimpleView('pagination::simple-bootstrap-4');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Sets third party service providers that are only needed on local/testing environments
         */
        if ($this->app->environment() != 'production') {
            /**
             * Loader for registering facades.
             */
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();

            /*
             * Load third party local aliases
             */
            $loader->alias('Debugbar', \Barryvdh\Debugbar\Facade::class);
        }
    }
}
