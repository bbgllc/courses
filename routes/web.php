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

Route::get('/config-clear', function(){
	Artisan::call('config:clear');
});

Route::get('/migrate-seed', function(){
    Artisan::call('migrate', [
    '--force' => true,
]);
});

Route::get('/db-seed', function(){
    Artisan::call('db:seed', [
    '--force' => true,
]);
});

Route::get('/key-gen', function(){
    Artisan::call('key:generate');
});

Route::get('/passport-install', function(){
    Artisan::call('passport:install');
});
