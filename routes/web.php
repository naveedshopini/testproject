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

Route::post('selected-membership-plan', 'GastController@selected_membership_plan')
    ->name('selected-membership-plan');

Route::get('/success', 'GastController@success_register');

Route::get('announcements', 'GastController@announcements');
Route::get('announcement-details/{slug}', 'GastController@announcement_details');

Route::get('faqs', 'GastController@faqs');

Route::get('parcels', 'GastController@parcels'); 
Route::get('claim-parcel/{id}', 'HomeController@parcel_claim');
Route::post('parcelsClaim', 'HomeController@parcelsClaim');

/////////  Paypal Routes /////////////////
//Route::get('paypal', 'PaymentController@index');

Route::get('load-paypal', 'PaymentController@payWithpaypal_onRegisteration');

Route::post('paypal', 'PaymentController@payWithpaypal');
Route::get('paypal-status', 'PaymentController@getPaymentStatus');
Route::get('payment-success', 'PaymentController@paymentSuccess');
Route::get('payment-error', 'PaymentController@paymentError');
//       End Paypal
Route::get('paypal', 'PaymentController@index');
Route::post('paypal', 'PaymentController@payWithpaypal');
Route::get('status', 'PaymentController@getPaymentStatus');

//      Stripe Route    ///////
Route::get('stripe', 'PaymentController@stripe');
Route::post('stripe', 'PaymentController@stripePost')->name('stripe.post');


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

Route::post('check_login_email', 'GastController@check_login_email')
    ->name('check_login_email');

Route::post('check_email', 'GastController@check_email')
    ->name('check_email');
    
////////////////////////// SupportController /////////////////////
Route::get('support/tickets', 'SupportController@index')
    ->name('support/tickets');
    
Route::get('support/ticket/create', 'SupportController@create_ticket')
    ->name('support/ticket/create');

Route::post('support/ticket/save', 'SupportController@save_ticket')
    ->name('support/ticket/save');

Route::get('support/ticket/comments/{id}', 'SupportController@ticket_comments')
    ->name('support/ticket/comments/{id}');

Route::post('support/ticket/comments/reply', 'SupportController@reply_comment')
    ->name('support/ticket/comments/reply');

Route::get('support/ticket/mark-complete/{id}', 'SupportController@mark_complete')
    ->name('support/ticket/mark-complete');

Route::get('support/ticket/re-open/{id}', 'SupportController@re_open')
    ->name('support/ticket/re-open');


//////////////////////////////////////////////////////////////////
/////////////////////////reset password/////////////////////////////////////////////////


Route::get('thank-you/{slug}', 'HomeController@thankyou')
    ->name('than-kyou');
////////////////////////////   HomeController   ///////////////////////////////////

Route::get('my-addresses', 'HomeController@addresses')
    ->name('my-addresses');
Route::get('profile-settings', 'HomeController@profile_settings')
    ->name('profile-settings');

Route::get('my-packages', 'HomeController@profile')
    ->name('my-packages');

Route::get('Subscribe-Free-Plan/{id}', 'HomeController@subscribe_free_plan')
    ->name('Subscribe-Free-Plan');


Route::post('contact-us/message-sent', 'GastController@message_sent')
    ->name('contact-us/message-sent'); 



  //////////////////// BarCodeGenerator Controller ////////////////////////////////
    Route::get('/shippo-testing', 'ShipmentController@index');
        
///////////////////////////// Shipment Controller ////////////////////
    
Route::get('shippo-testing', 'ShipmentController@index')
    ->name('shippo-testing');

//////////////////////       User\ProfileController  ///////////////////////////// 

Route::post('account-settings/profile-update', 'User\ProfileController@update_profile')
    ->name('account-settings/profile-update');

Route::post('check_old_password', 'User\ProfileController@check_old_password')
    ->name('check_old_password');

Route::post('account-settings/change-password', 'User\ProfileController@change_password')
    ->name('account-settings/change-password');

Route::get('account-settings', 'User\ProfileController@account_settings')
    ->name('account-settings');

Route::post('account-settings/update', 'User\ProfileController@account_settings_update')
    ->name('account-settings/update');    

Route::post('/ajax-country-state','User\ProfileController@countryState');
Route::post('/ajax-state-city','User\ProfileController@stateCity');

Route::get('parcel-details','User\ProfileController@parcel_details');




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

       
    });

});

Route::get('profile', function () {
  Route::get('profile-settings', 'HomeController@profile_settings')
    ->name('profile-settings');
})->middleware('verified');

/*
 * Global Routes
 * Routes that are used for backend.
 */

Route::get('/backend', 'GastController@adminLogin')    
    ->name('backend');

//Route::get('/backend-login', 'GastController@adminLogin')->name('backend-login');
Route::group(['namespace' => 'Admin', 'prefix' =>'backend',  'as' => 'admin.', 'middleware' => ['backend']], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     */

        Route::get('/backend', ['as' => 'admin.home', 'uses' => 'AdminController@admin']);
        Route::get('/admin-users', 'UserController@index');
        Route::get('/admin-users-add', 'UserController@create');
        Route::get('/user-edit/{id}','UserController@edit');
        Route::get('/user-vaddress-edit/{id}','UserController@vaddressAdd');
        Route::post('/user-vaddress-set','UserController@saveVaddress');
        Route::post('/user-updated/{id}','UserController@update');
        Route::post('/user-status','UserController@updateStatus');
        Route::post('/user-vaddress-status','UserController@vaddressStatus');

        //Users Routes
        Route::get('/cms-pages/add', 'PageController@create');
        Route::post('/cms-pages/save', 'PageController@store');
        Route::post('/cms-pages/update', 'PageController@update');
        Route::post('/page-added', 'PageController@store');
        Route::get('/admin-cms', 'PageController@index');
        Route::get('/page-delete/{slug}','PageController@destroy');
        Route::get('/profile','UserController@profile');
        Route::get('/page-edit/{id}','PageController@edit_page');
        Route::post('/cmspage-ajaxcall-change-status','PageController@update_status');

        //Testimonials Routes

            Route::get('/testimonial-view/{id}', 'TestimonialController@show');
            Route::get('/testimonial-edit/{id}', 'TestimonialController@edit');
            Route::post('/testimonial-updated', 'TestimonialController@update');
            Route::get('/testimonials', 'TestimonialController@index');
            Route::post('/testimonial-status','TestimonialController@updateStatus');
            Route::get('/testimonial/add', 'TestimonialController@create');
            Route::post('/testimonial/save', 'TestimonialController@store');
            Route::get('/testimonial-delete/{id}','TestimonialController@destroy');



        // end testimonials

        //Membership Plans Routes

            Route::get('/membership-plan-view/{id}', 'MembershipPlanController@show');
            Route::get('/membership-plan/add', 'MembershipPlanController@create');
            Route::post('/membership-plan/save', 'MembershipPlanController@store');
            Route::get('/membership-edit/{id}', 'MembershipPlanController@edit');
            Route::post('/membership-updated', 'MembershipPlanController@update');
            Route::get('/membership-plans', 'MembershipPlanController@index');
            Route::get('/membership-delete/{id}','MembershipPlanController@destroy');
            Route::post('/member-ajaxcall-change-status','MembershipPlanController@updateStatus');



        // end Membership Plans

        //Virtual Addresses Plans Routes

            Route::get('/virtual-address-view/{id}', 'VirtualAddressController@show');
            Route::get('/virtual-address/add', 'VirtualAddressController@create');
            Route::post('/virtual-address/save', 'VirtualAddressController@store');
            Route::get('/virtual-address-edit/{id}', 'VirtualAddressController@edit');
            Route::post('/virtual-address-updated', 'VirtualAddressController@update');
            Route::get('/virtual-addresses', 'VirtualAddressController@index');
            Route::get('/virtual-address-delete/{id}','VirtualAddressController@destroy');
            Route::post('/virtual-address-status','VirtualAddressController@updateStatus');
            Route::post('/ajax-country-state','VirtualAddressController@countryState');
            Route::post('/ajax-state-city','VirtualAddressController@stateCity');



        // end Virtual Addresses 

        // currency converter excel file
        // rate calculator for weight also excel file

        Route::get('/excel-uploads','ExcelHelperController@excel_uploads');
        Route::post('/excel-uploads/save','ExcelHelperController@excel_save');

             /*    Directory Routes    */
        Route::get('/directory', 'DirectoryController@index');
        Route::get('/directory/add', 'DirectoryController@create');
        Route::get('/directory-edit/{id}','DirectoryController@edit_directory');
        Route::get('/directory-delete/{id}','DirectoryController@destroy');
        Route::post('/directory/save', 'DirectoryController@store');
        Route::post('/directory/update','DirectoryController@update'); 
        Route::post('/directory-ajaxcall-change-status','DirectoryController@update_status');

              /*    Category Routes       */
        Route::get('/category', 'CategoryController@index');
        Route::get('/category/add', 'CategoryController@create');
        Route::get('/category-edit/{id}','CategoryController@edit_category');
        Route::post('/category/save', 'CategoryController@store');
        Route::get('/category-delete/{id}','CategoryController@destroy');
        Route::post('/category/update','CategoryController@update');
        Route::post('/category-ajaxcall-change-status','CategoryController@update_status');

         /*   Faqs Routes    */
        Route::get('/faq', 'FaqController@index');
        Route::get('/faq/add', 'FaqController@create');
        Route::get('/faq-edit/{id}','FaqController@edit_faq');
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

        //////////////////// Support Controller ////////////////////////////////
        Route::get('/tickets', 'SupportController@index');


        //////////////////// BarCodeGenerator Controller ////////////////////////////////
        Route::get('/barcode', 'BarCodeGenerator@index');
        Route::post('/barcode/generate', 'BarCodeGenerator@generate_barcode');


        
});

