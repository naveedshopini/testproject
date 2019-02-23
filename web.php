<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\LanguageController;

/*
 * Global Routes
 * Routes that are used for frontend .
 */

// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap']);

//Route::view('lang/', 'welcome');

Route::get('/', 'GastController@index');

Auth::routes(['verify' => true]);

Route::get('/home', 'GastController@index')    
    ->name('home');

Route::get('/about/{slug}', 'GastController@pages')    
    ->name('pages');

Route::get('/help/{slug}', 'GastController@pages')    
    ->name('pages');

Route::get('/feature/{slug}', 'GastController@pages')    
    ->name('pages');

Route::get('contact-us', 'GastController@contactus')    
    ->name('pages');

Route::get('privacy-policy', 'GastController@privacy_policy')    
    ->name('privacy-policy');

Route::get('careers', 'GastController@careers')    
    ->name('pages');

Route::get('membership-plans', 'GastController@membership')
    ->name('membership');

Route::get('/success', 'GastController@success_register');

Route::get('announcements', 'GastController@announcements');
Route::get('announcement-details/{slug}', 'GastController@announcement_details');




Route::get('ajaxcall-rate-countries-list', 'GastController@rate_countries_list')
    ->name('ajaxcall-rate-countries-list');

Route::post('ajaxcall-rate-calculate', 'GastController@rate_calculates')
    ->name('ajaxcall-rate-calculate');


Route::get('/ajaxcall-shopping-directory/{id}', 'GastController@shopping_directory_ajaxcall');  
/*This controller are used for ajax to match user entered email*/
 Route::get('/user-email', 'AjaxController@get_user_email');
Route::get('/success', 'GastController@success_register');  

Route::get('ajaxcall-currency-country-list', 'GastController@currency_country_list')
    ->name('ajaxcall-currency-country-list');       

Route::post('ajaxcall-exchange-currency', 'GastController@exchange_currency')
    ->name('ajaxcall-exchange-currency');




////////////////////////////   HomeController   ///////////////////////////////////

Route::get('account-settings', 'HomeController@account_settings')
    ->name('account-settings');

Route::get('my-addresses', 'HomeController@addresses')
    ->name('my-addresses');
Route::get('profile-settings', 'HomeController@profile_settings')
    ->name('profile-settings');

Route::get('my-packages', 'HomeController@profile')
    ->name('my-packages');

Route::post('check_email', 'HomeController@check_email')
    ->name('check_email');

Route::post('contact-us/message-sent', 'HomeController@message_sent')
    ->name('contact-us/message-sent');

Route::post('check_login_email', 'HomeController@check_login_email')
    ->name('check_login_email'); 


//////////////////////       User\ProfileController  ///////////////////////////// 

Route::post('account-settings/profile-update', 'User\ProfileController@update_profile')
    ->name('account-settings/profile-update');

Route::post('check_old_password', 'User\ProfileController@check_old_password')
    ->name('check_old_password');

Route::post('account-settings/change-password', 'User\ProfileController@change_password')
    ->name('account-settings/change-password');

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 * These routes can not be hit if the password is expired
 */
Route::group(['middleware' => ['auth']], function () {
    Route::group(['namespace' => 'User', 'prefix' =>'',  'as' => 'user.'], function () {
        /*
         * User Dashboard Specific
         */
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        /*
         * User Account Specific
         */
        Route::get('account', [AccountController::class, 'index'])->name('account');

        Route::get('profile', [HomeController::class, 'profile'])->name('profile');
    });

});

/*Route::get('profile', function () {
  Route::get('profile', [HomeController::class, 'profile'])->name('profile');
})->middleware('verified');
*/







/*
 * Global Routes
 * Routes that are used for backend.
 */

Route::get('/backend', 'HomeController@adminlogin')    
    ->name('backend');

Route::get('/backend-login', 'GastController@adminLogin')->name('backend-login');
Route::group(['namespace' => 'Admin', 'prefix' =>'backend',  'as' => 'admin.', 'middleware' => ['backend']], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     */

        Route::get('/backend', ['as' => 'admin.home', 'uses' => 'AdminController@admin']);
        Route::get('/admin-users', 'UserController@index');
        Route::get('/admin-users-add', 'UserController@create');
        Route::get('/cms-pages/add', 'PageController@create');
        Route::post('/cms-pages/save', 'PageController@store');
        Route::post('/cms-pages/update', 'PageController@update');
        Route::post('/page-added', 'PageController@store');
        Route::get('/admin-cms', 'PageController@index');
        Route::get('/page-delete/{slug}','PageController@destroy');
        Route::get('/profile','UserController@profile');
        Route::get('/page-edit/{id}','PageController@edit_page');
        Route::post('/ajaxcall-change-status','PageController@update_status');

        //Testimonials Routes

            Route::get('/testimonial-view/{id}', 'TestimonialController@show');
            Route::get('/testimonial-edit/{id}', 'TestimonialController@edit');
            Route::post('/testimonial-updated', 'TestimonialController@update');
            Route::get('/testimonials', 'TestimonialController@index');
            Route::post('/testimonial-status','TestimonialController@updateStatus');
            Route::get('/testimonial/add', 'TestimonialController@create');
            Route::post('/testimonial/save', 'TestimonialController@store');



        // end testimonials

        //Membership Plans Routes

            Route::get('/membership-plan-view/{id}', 'MembershipPlanController@show');
            Route::get('/membership-plan/add', 'MembershipPlanController@create');
            Route::post('/membership-plan/save', 'MembershipPlanController@store');
            Route::get('/membership-edit/{id}', 'MembershipPlanController@edit');
            Route::post('/membership-updated', 'MembershipPlanController@update');
            Route::get('/membership-plans', 'MembershipPlanController@index');
            Route::get('/membership-delete/{id}','MembershipPlanController@destroy');
            Route::post('/membership-status','MembershipPlanController@updateStatus');



        // end Membership Plans

        // currency converter excel file
        // rate calculator for weight also excel file

        Route::get('/excel-uploads','ExcelHelperController@excel_uploads');
        Route::post('/excel-uploads/save','ExcelHelperController@excel_save');

             /*Directory Routes*/
        Route::get('/directory', 'DirectoryController@index');
        Route::get('/directory/add', 'DirectoryController@create');
        Route::get('/directory-edit/{id}','DirectoryController@edit_directory');
        Route::get('/directory-delete/{id}','DirectoryController@destroy');
        Route::post('/directory/save', 'DirectoryController@store');
        Route::post('/directory/update','DirectoryController@update'); 
        Route::post('/directory-ajaxcall-change-status','DirectoryController@update_status');
              /*Category Routes*/
        Route::get('/category', 'CategoryController@index');
        Route::get('/category/add', 'CategoryController@create');
        Route::get('/category-edit/{id}','CategoryController@edit_category');
        Route::post('/category/save', 'CategoryController@store');
        Route::get('/category-delete/{id}','CategoryController@destroy');
        Route::post('/category/update','CategoryController@update');
        Route::post('/category-ajaxcall-change-status','CategoryController@update_status');

         /*Category Routes*/
        Route::get('/faq', 'FaqController@index');
        Route::get('/faq/add', 'FaqController@create');
        Route::get('/faq-edit/{id}','CategoryController@edit_category');
        Route::post('/faq/save', 'FaqController@store');
        Route::get('/faq-delete/{id}','FaqController@destroy');
        Route::post('/faq/update','FaqController@update');
        Route::post('/faq-ajaxcall-change-status','FaqController@update_status');

        ///////// News Controller /////////////////
        Route::get('/announcements', 'NewsController@index');
        Route::get('/announcement/create', 'NewsController@create');
        Route::post('/announcement/save', 'NewsController@store');
        Route::get('/announcement/edit/{id}', 'NewsController@edit');
        Route::post('/announcement/update', 'NewsController@update');
        Route::get('/announcement/delete/{id}', 'NewsController@delete');
        Route::post('/announcement/update-status', 'NewsController@updateStatus');
        
});

