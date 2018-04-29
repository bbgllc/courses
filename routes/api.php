<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// fetch courses and authors for autocomplete dropdown
Route::get('search/courses', 'Api\ApiController@fetchCourses');
Route::get('search/authors', 'Api\ApiController@fetchAuthors');

Route::get('braintree/token', 'Frontend\User\Payments\BraintreePaymentController@token');
Route::post('cert', 'Frontend\HomeController@verify');

Route::post('admin/videos/categories/sort_order', 'Backend\AdminCategoryController@orderCategories');


Route::get('categories', 'Api\ApiController@fetchCategories');
Route::get('subcategories/{category}', 'Api\ApiController@fetchSubcategories');
Route::get('languages', 'Api\ApiController@fetchLanguages');
Route::get('countries', 'Api\ApiController@fetchCountries');

Route::post('author/course/image/{id}', 'Frontend\_Author\AuthorCourseController@updateImage');
Route::get('author/course/{id}/fetchCourse', 'Frontend\_Author\AuthorCourseController@fetchCourse');
Route::post('user/{id}/avatar', 'Frontend\User\AccountController@uploadAvatar');

// admin post featured image upload

Route::post('admin/post/{id}/image', 'Backend\AdminBlogPostController@updateFeaturedImage');
Route::get('admin/image/{id}/removeImage', 'Backend\AdminBlogPostController@removeImage');

Route::post('admin/upload/logos', 'Backend\AdminSettingsController@uploadLogos');
Route::get('admin/logos/removeImage', 'Backend\AdminSettingsController@removeLogos');

Route::post('author/lesson/{id}/video/upload', 'Frontend\_Author\AuthorContentController@uploadVideo');
 // ATTACHMENT
Route::post('author/lesson/{lesson}/attachment/upload', 'Frontend\_Author\AuthorLessonController@uploadAttachment');
Route::delete('author/lesson/{lesson}/attachment/{attachment}/destroy', 'Frontend\_Author\AuthorLessonController@deleteAttachment');

// attachments
Route::post('attachments/{thread}/{user_id}/upload', 'Frontend\User\MessagingController@addAttachment');

// Reviews
Route::get('courses/{course}/reviews', 'Frontend\User\ReviewController@fetchReviews');

// coupon
Route::post('/courses/coupon', 'Frontend\User\Payments\PaymentController@applyCoupon');  

// coupon
Route::get('/category/{category}/fetchCourses', 'Frontend\HomeController@fetchCourses');  


/*
    |----------------------------------------------------------------------
    |	Installer
    |---------------------------------------------------------------------
*/
    Route::get('install/check-requirements', 'Frontend\InstallController@checkRequirements');
    Route::get('install/check-permissions', 'Frontend\InstallController@checkFolderPermissions');
    Route::post('install/save-env', 'Frontend\InstallController@writeEnv');
    Route::get('install/test-db', 'Frontend\InstallController@testDatabaseConnection');
    Route::get('install/load-data', 'Frontend\InstallController@loadData');
/*
    |----------------------------------------------------------------------
    |	Authentication Routes
    |---------------------------------------------------------------------
*/

Route::post('login', 'Api\Mobile\Auth\AuthenticationController@login');

Route::post('register', 'Api\Mobile\Auth\AuthenticationController@register');



Route::group(['middleware' => ['auth:api', 'xss']], function () {
    
    Route::post('details', 'Api\Mobile\Auth\AuthenticationController@getDetails');
    Route::post('logout', 'Api\Mobile\Auth\AuthenticationController@logout');
    
    /*
    |----------------------------------------------------------------------
    |	Backend routes
    |---------------------------------------------------------------------
    */
    Route::get('admin/dashboard/fetch_admin_sales_data', 'Backend\DashboardController@fetchAdminSalesChartData');
    Route::post('admin/settings/update', 'Backend\AdminSettingsController@update');
    Route::get('admin/courses/fetchCourses', 'Backend\AdminCourseController@getCoursesData');
    Route::get('admin/finance/all_transactions', 'Backend\AdminTransactionController@getTransactions');
    Route::delete('admin/course/courses/{id}/destroy', 'Backend\AdminCourseController@destroy');
    
    /*
    |----------------------------------------------------------------------
    |	Frontend user routes 
    |---------------------------------------------------------------------
    */
    // reviews
    Route::post('courses/{course}/review', 'Frontend\User\ReviewController@store');
    Route::post('reviews/{id}/reply', 'Frontend\User\ReviewController@reply');
    
    // Course dashboard header information
    Route::get('courses/{course}/fetch-header-information', 'Api\ApiController@fetchCourseDashboardInformation');
    
    // fetch course content
    Route::get('courses/{course}/fetch-course-content', 'Api\ApiController@fetchCourseContent');
    
    // mark lesson as complete
    Route::get('lesson/{lesson}/get-complete-status', 'Frontend\User\LessonController@getCompletionStatus');
    Route::post('lesson/{lesson}/mark-as-complete', 'Frontend\User\LessonController@markAsComplete');
    
    // quizzes
    Route::get('user/quiz/{lesson}/questions', 'Frontend\User\LessonController@fetchQuestions');
    Route::post('user/quiz/{leson}/saveAttempt', 'Frontend\User\LessonController@saveAttempt');

    // user sales dashboard data
    Route::get('dashboard/fetch_sales_data', 'Frontend\User\UserSalesDashboardController@fetchSalesChartData');
    Route::get('dashboard/fetch_sales_table_data', 'Frontend\User\UserSalesDashboardController@getUserSalesTable');
    
    
    
    /*
    |----------------------------------------------------------------------
    |	Frontend author routes 
    |---------------------------------------------------------------------
    */
    
    // courses
    Route::get('author/fetchCourses', 'Frontend\_Author\AuthorDashboardController@fetchAuthorCourses');
    
    Route::post('author/course', 'Frontend\_Author\AuthorCourseController@store');
    Route::put('author/course/{course}/update', 'Frontend\_Author\AuthorCourseController@update');
    
    Route::post('author/courses/{id}/updateImage', 'Frontend\_Author\AuthorCourseController@updateImage');
    Route::put('author/courses/{id}/updatePrice', 'Frontend\_Author\AuthorCourseController@updatePrice');
    
    // SECTIONS -- API
    Route::get('author/{course}/fetchSections', 'Frontend\_Author\AuthorSectionController@fetchSections');
    Route::get('author/section/{id}', 'Frontend\_Author\AuthorSectionController@fetchSection');
    Route::put('author/section/{section}', 'Frontend\_Author\AuthorSectionController@updateSection');
    Route::post('author/add_lesson/{course}/lesson', 'Frontend\_Author\AuthorLessonController@storeLesson');
    Route::post('author/{course}/section', 'Frontend\_Author\AuthorSectionController@storeSection');
    Route::delete('author/section/{id}', 'Frontend\_Author\AuthorSectionController@destroy');
    Route::put('author/sections/draggable', 'Frontend\_Author\AuthorSectionController@updateDraggable');

    // LESSONS api
    Route::get('author/sections/{section}/lessons', 'Frontend\_Author\AuthorLessonController@fetchLessons');
    Route::get('author/lesson/{id}/fetch', 'Frontend\_Author\AuthorLessonController@fetchLesson');
    Route::put('author/lessons/{id}', 'Frontend\_Author\AuthorLessonController@update');
    Route::delete('author/lesson/{id}', 'Frontend\_Author\AuthorLessonController@destroy');
    Route::put('author/update_draggable_lesson', 'Frontend\_Author\AuthorLessonController@updateDraggable');
    
    Route::post('author/lesson/article/create', 'Frontend\_Author\AuthorContentController@createArticle');
    Route::get('author/lesson/content/{id}', 'Frontend\_Author\AuthorContentController@edit');
    Route::put('author/lesson/article/{id}', 'Frontend\_Author\AuthorContentController@updateArticle');
    
    Route::post('author/lesson/{id}/embed/create', 'Frontend\_Author\AuthorContentController@embedVideo');
    Route::put('author/lesson/embed/{id}', 'Frontend\_Author\AuthorContentController@updateEmbedVideo');
    
    
    //quiz
    Route::post('author/quiz/{lesson_id}/question', 'Frontend\_Author\AuthorQuizController@saveQuestion');
    Route::put('author/quiz/{question_id}/update', 'Frontend\_Author\AuthorQuizController@updateQuestion');
    Route::delete('author/quiz/{question_id}', 'Frontend\_Author\AuthorQuizController@deleteQuestion');
    
    Route::post('author/question/{id}/create_answer', 'Frontend\_Author\AuthorQuizController@storeAnswer');
    Route::delete('author/answer/{id}/destroy', 'Frontend\_Author\AuthorQuizController@destroyAnswer');
    Route::delete('author/quiz_question/{id}/destroy', 'Frontend\_Author\AuthorQuizController@destroyQuestion');
    
    // coupons
    Route::get('author/course/{id}/coupons', 'Frontend\_Author\AuthorCouponController@fetchCoupons');
    Route::post('author/course/coupon', 'Frontend\_Author\AuthorCouponController@store');
    Route::put('author/coupon/{id}/activate', 'Frontend\_Author\AuthorCouponController@activate');
    
    
    // Messaging
    
    Route::get('threads', 'Frontend\User\MessagingController@fetchThreads');
    Route::get('thread/{id}/messages', 'Frontend\User\MessagingController@fetchThreadMessages');
    Route::put('thread/{id}/message', 'Frontend\User\MessagingController@update');
    Route::put('thread/markAsRead/{id}', 'Frontend\User\MessagingController@markThreadAsRead');
    
    
    // User Account Settings
    Route::put('update-profile', 'Frontend\User\AccountController@updateProfile');
    Route::post('update-password', 'Frontend\User\AccountController@updatePassword');
    Route::post('update-settings', 'Frontend\User\SettingsController@updateSettings');
    
    // bookmark courses
    Route::post('courses/{course}/bookmark', 'Frontend\CourseController@bookmark');
    Route::get('courses/{course}/get-wishlist-status', 'Frontend\CourseController@getBookmarkStatus');
    
    // Questions
    Route::get('questions/{id}/get_questions', 'Frontend\User\QuestionController@fetchQuestions');
    Route::get('questions/{id}/get_edit_question', 'Frontend\User\QuestionController@fetchQuestion');
    Route::post('questions/{id}/store_question', 'Frontend\User\QuestionController@storeQuestion');
    Route::put('questions/{id}/update_question', 'Frontend\User\QuestionController@updateQuestion');
    Route::delete('questions/{id}/delete_question', 'Frontend\User\QuestionController@deleteQuestion');
    
    // answers
    Route::get('questions/{id}/get_answers', 'Frontend\User\QuestionController@fetchAnswers');
    Route::get('questions/answer/{id}/get_edit_answer', 'Frontend\User\QuestionController@fetchAnswer');
    Route::post('questions/{id}/store_answer', 'Frontend\User\QuestionController@storeAnswer');
    Route::put('questions/answer/{id}/update_answer', 'Frontend\User\QuestionController@updateAnswer');
    Route::delete('questions/answer/{id}/delete_answer', 'Frontend\User\QuestionController@deleteAnswer');
    Route::put('answers/{answer}/mark_as_answer', 'Frontend\User\QuestionController@markAsAnswer');
    
    // follow question
    Route::get('question/{id}/follow', 'Frontend\User\QuestionController@follow');
    Route::get('question/{id}/get-follow-status', 'Frontend\User\QuestionController@getFollowStatus');
    
    // announcement
    Route::post('author/courses/{course}/announcements', 'Frontend\_Author\AuthorAnnouncementController@store');
    // Announcement comments
    Route::get('announcements/{course}/get_announcements', 'Frontend\User\AnnouncementController@fetchAnnouncements');
    Route::get('announcements/{id}/get_comments', 'Frontend\User\AnnouncementController@fetchComments');
    Route::post('announcements/{id}/store_comment', 'Frontend\User\AnnouncementController@storeComment');
    Route::put('announcements/comment/{id}/update_comment', 'Frontend\User\AnnouncementController@updateComment');
    Route::get('announcements/comment/{id}/get_edit_comment', 'Frontend\User\AnnouncementController@fetchComment');
    Route::delete('announcements/comment/{id}/delete_comment', 'Frontend\User\AnnouncementController@deleteComment');

});
