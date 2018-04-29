<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 * Place them all in the 'theme' middleware group so that the theme is passed in dynamically to the frontend depending on what is chosen
 */
 /*
Route::domain('{account}.udemy-clone-fgneba.c9users.io')->group(function () {
    Route::get('/', 'HomeController@index')->name('index');
});
*/

Route::get('/messageforcurious', function(){
    return 'You are not permitted to access this page directly!';    
});

Route::get('/installer', 'InstallController@index')->name('site.install');

Route::group(['middleware'=>['theme', 'installed']], function() {
    Route::get('/', 'HomeController@index')->name('index');
    
    //Route::get('/', 'CourseController@index')->name('index');
    
    Route::get('cert/verify', 'HomeController@verifyCertificate')->name('verify.certificate');
    
    Route::get('contact', 'ContactController@index')->name('contact');
    Route::post('contact/send', 'ContactController@send')->name('contact.send');
    
    Route::get('course/{course}', 'CourseController@show')->name('course.show')->middleware('affiliateCookie');
    
    Route::get('courses', 'CourseController@index')->name('courses')->middleware('affiliateCookie');
    Route::get('courses/search', 'SearchController@search')->name('courses.search');
    
    
    Route::get('user/{username}', 'UserController@profile')->name('user');
    
    // course payment
    Route::post('courses/charge', 'User\Payments\StripePaymentController@charge')->name('course.charge.stripe');
    Route::post('courses/charge/paypal', 'User\Payments\PayPalPaymentController@charge')->name('course.charge.paypal');
    Route::get('courses/charge/paypal', 'User\Payments\PayPalPaymentController@paymentStatus')->name('course.charge.paypal.status');
    Route::post('courses/charge/account-balance', 'User\Payments\PayWithAccountBalanceController@charge')->name('course.charge.account_balance');
    Route::post('/courses/charge/braintree', 'User\Payments\BraintreePaymentController@charge')->name('courses.charge.braintree');
    //Route::post('/courses/charge/omise', 'Payments\OmisePaymentController@charge')->name('courses.charge.omise');
    Route::post('courses/charge/razorpay', 'User\Payments\RazorpayPaymentController@charge')->name('course.charge.razorpay');


        /*
         * These frontend controllers require the user to be logged in
         * All route names are prefixed with 'frontend.'
         * These routes can not be hit if the password is expired
         */
    
        Route::group(['middleware' => ['auth']], function () {
            
            Route::get('course/{course}/learn/v1/{lesson}', 'CourseController@play')->name('course.play');
            Route::get('course/{course}/attachment/{attachment}', 'CourseController@downloadAttachment')->name('course.attachment.download');
            Route::get('course/{course}/enroll', 'CourseController@enroll')->name('course.enroll');
            Route::get('course/{course}/learn/content', 'CourseController@content')->name('course.content');
            
            // Author routes
            Route::group(['namespace' => '_Author', 'as' => 'author.'], function () {
                Route::get('author/dashboard', 'AuthorDashboardController@dashboard')->name('dashboard');
                Route::get('author/create-course', 'AuthorCourseController@create')->name('course.create');
                Route::get('author/course/{course}/edit', 'AuthorCourseController@edit')->name('course.edit');
                Route::get('author/course/{course}/curriculum', 'AuthorCourseController@curriculum')->name('course.curriculum');
                Route::get('author/course/{course}/price-and-promotions', 'AuthorCouponController@index')->name('course.pricing');
                Route::get('author/course/{course}/destroy', 'AuthorCourseController@destroy')->name('course.destroy');
                
                
                Route::get('author/course/{course}/submit-for-review', 'AuthorCourseController@submitForReview')->name('submit.review');
                Route::get('author/course/{course}/admin-approval', 'AuthorCourseController@adminReview')->name('course.approval');
                
                // announcements
                Route::get('author/courses/{course}/announcements', 'AuthorAnnouncementController@index')->name('announcements');
                Route::get('author/courses/{course}/announcements/create', 'AuthorAnnouncementController@create')->name('announcements.create');
                
                
                
            });
            
            
            Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
                /*
                 * User Dashboard Specific
                 */
                Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        
                /*
                 * User Account Specific
                 */
                Route::get('account', 'AccountController@index')->name('account');
                Route::get('settings', 'SettingsController@index')->name('settings');
                Route::get('notifications', 'NotificationsController@index')->name('notifications');
                Route::get('notifications/{id}/mark-as-read', 'NotificationsController@markAsRead')->name('notifications.mark');
                Route::get('notifications/mark-all-as-read', 'NotificationsController@markAllAsRead')->name('notifications.mark.all');
                Route::get('account/security', 'AccountController@passwordUpdate')->name('account.security');
        
                /*
                 * User Profile Specific
                 */
                Route::patch('profile/update', 'ProfileController@update')->name('profile.update');
                
                
                // checkout page and routes
                Route::get('cart/course/{course}/checkout', 'Payments\PaymentController@checkout')->name('course.checkout');
                
                // Inbox
                Route::get('inbox', 'MessagingController@index')->name('inbox');
                Route::post('message/send', 'MessagingController@store')->name('message.send');
                
                // revenue / withdrawals
                Route::get('revenue/my-sales', 'UserRevenueController@sales')->name('revenue.mysales');
                Route::get('revenue/my-affiliate-earnings', 'UserRevenueController@affiliateEarnings')->name('revenue.myaffiliatesales');
                Route::get('revenue/my-withdrawals', 'UserRevenueController@withdrawals')->name('revenue.mywithdrawals');
                Route::get('revenue/my-transactions', 'UserRevenueController@transactions')->name('revenue.mytransactions');
    
                Route::get('revenue/allearnings/{username}/fetch', 'UserRevenueController@fetchAllEarning')->name('revenue.fetch');
                Route::get('revenue/withdrawals', 'UserRevenueController@requestWithdrawal')->name('withdrawals');
                Route::delete('revenue/withdrawal/{withdrawal}', 'UserRevenueController@deleteWithdrawal')->name('withdrawals.destroy');
                
                // My Courses
                Route::get('course/{course}/download-certificate', 'DashboardController@downloadCertificate')->name('certificate.download');
                Route::get('course/{course}/download-receipt', 'DashboardController@downloadReceipt')->name('receipt.download');
                Route::get('my-courses/learning', 'DashboardController@myCourses')->name('courses');
                Route::get('my-courses/wishlist', 'DashboardController@myWishlist')->name('wishlist');
                Route::get('my-courses/purchases', 'DashboardController@myPurchases')->name('purchases');
                Route::get('my-courses/certificates', 'DashboardController@myCertificates')->name('certificates');
                
                
                // questions
                Route::get('course/{course}/learn/questions', 'QuestionController@index')->name('questions.index');
                Route::get('courses/{course}/learn/question/{question}', 'QuestionController@show')->name('questions.show');
                
                // announcements
                Route::get('course/{course}/learn/announcements', 'AnnouncementController@index')->name('announcements.index');
                Route::get('course/{course}/learn/announcement/{announcement}', 'AnnouncementController@show')->name('announcements.show');
                
                
                
                
            });
            
            
        });
    
    
    
       // Route::group(['middleware' => ['localeSessionRedirect', 'localize', 'localizationRedirect']], function () {
            Route::get('blog', 'BlogController@index')->name('blog');
            Route::get('blog/category/{category}', 'BlogController@getByCategory')->name('blog.categories');
            Route::get('blog/article/{slug}', 'BlogController@show')->name('blog.show');
            Route::get('page/{slug}', 'BlogController@showPage')->name('page.show');
            
       // });

    
});
