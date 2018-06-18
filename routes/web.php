<?php

/**
 * Global Routes
 * Routes that are used between both frontend and backend.
 */


// Switch between the included languages
Route::get('lang/{lang}', 'LanguageController@swap');

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['prefix' => LaravelLocalization::setLocale()], function(){
    Route::group(['middleware' => ['xss', 'localizationRedirect', 'localeViewPath', 'GabsLocale' ]], function(){
    
        Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
            include_route_files(__DIR__.'/frontend/');
        });
    });
});
/*
 * Backend Routes
 * Namespaces indicate folder structure
 */
Route::group(['prefix' => LaravelLocalization::setLocale()], function(){
    
    Route::group([
        'namespace' => 'Backend', 
        'prefix' => 'admin', 
        'as' => 'admin.', 
        'middleware' => ['admin', 'xss', 'installed', 'localeSessionRedirect', 'localize', 'localizationRedirect']], function () {
            /*
             * These routes need view-backend permission
             * (good if you want to allow more than one group in the backend,
             * then limit the backend features by different roles or permissions)
             *
             * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
             * These routes can not be hit if the password is expired
             */
            include_route_files(__DIR__.'/backend/');
    });
    
});

Route::get('/config-cache', function(){
	Artisan::call('config:cache');
});

Route::get('/paypal-test', function(){
    $ch = curl_init();
    $clientId = "AQYVHG3vLhtTAE3T12wf_TU0MhCOZrSoLIFBuhhV9aBda3pfwWF6VCT5M8iXzvVGInsMW2D7hr1MHsYK";
    $secret = "EHChLLKEhSom3zgnTR3T7Cbrymm1CqLqiteA7mPb_UrYxIVtg3B-HqkH-oTYJXU59xDwDgjVANS84v-Q";

    curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

    $result = curl_exec($ch);

    if(empty($result))die("Error: No response.");
    else
    {
        $json = json_decode($result);
        print_r($json);
    }

    curl_close($ch);
});

