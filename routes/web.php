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


/*Route::get('/', function () {

    return view('welcome');

});*/



/*Route::get('/', function () {

    return view('welcome');

});*/

//Clear Cache facade value:
Route::get('/clear', function () {
    $exitCode = Artisan::call('cache:clear');
    echo '<p>Cache cleared</p><br>';

    $exitCode = Artisan::call('view:clear');
    echo '<p>View cache cleared</p><br>';

    $exitCode = Artisan::call('route:clear');
    echo '<p>Route cache cleared</p><br>';

    $exitCode = Artisan::call('config:clear');
    echo '<p>Config cache cleared</p><br>';

    $exitCode = Artisan::call('config:cache');
    echo '<p>Config cached</p><br>';
});


Route::group(['middleware' => ['forseSSL']], function () {

    Route::group(['prefix' => 'admin'], function () {

        Auth::routes();

// For Admin Login

        Route::get('/', 'Admin\RegisterController@adminLoginPage');

        Route::get('logout', 'Admin\RegisterController@adminLogout');

        Route::get('login', 'Admin\RegisterController@adminLoginPage');

        Route::post('login', 'Admin\RegisterController@admin_login');

        // Route::get('register', 'Admin\RegisterController@admin_register');

        // Route::post('register', 'Admin\RegisterController@admin_registration');


        Route::group(['middleware' => ['admin']], function () {

            Route::get('reset-password', 'Admin\RegisterController@reset_password'); // loads the page

            Route::post('resetPassword', 'Admin\RegisterController@resetPassword'); // form submit action


            Route::get('welcome', 'Admin\DashboardController@blank_page');

            // Kashif work for admin inquiry jtable listing

            Route::post('inquiries/getAllInquiries', 'Admin\InquiryController@getAllInquiries');

            Route::post('inquiries/getInquiryDetail', 'Admin\InquiryController@getInquiryDetail');


            // for carrer listing

            Route::post('career/getAllCareers', 'Admin\CareerController@getAllCareers');

            Route::post('career/getCareerDetail', 'Admin\CareerController@getCareerDetail');


            // ====end====


            //Route::get('/', 'Admin\HomeController@index');

            //Route::get('login', 'Admin\HomeController@index');

            // For Admin Car Bookings Module JTable


            Route::match(['get', 'post'], 'bookings/export-booking', 'Admin\BookingsController@exportBooking');


            Route::get('bookings/export-users', 'Admin\BookingsController@exportUsers');


            Route::post('bookings/import-booking', 'Admin\BookingsController@importBooking');

            Route::post('bookings/import-customers', 'Admin\BookingsController@importCustomers');

            Route::post('bookings/import-loyalty', 'Admin\BookingsController@importCustomerLoyalty');

            Route::post('bookings/importCorporateInvoices', 'Admin\BookingsController@importCorporateInvoices');

            Route::post('bookings/importCorporateLeaseInvoices', 'Admin\BookingsController@importCorporateLeaseInvoices');

            Route::post('bookings/importSimahInfo', 'Admin\BookingsController@importSimahInfo');

            Route::post('bookings/importBlackListInfo', 'Admin\BookingsController@importBlackListInfo');

            // Meta Pages

            Route::get('meta_pages', 'Admin\MetaPageController@index');
            Route::post('save_metapages','Admin\MetaPageController@save');


            Route::get('booking-added-payments', 'Admin\BookingsController@booking_added_payments');

            Route::get('export-booking-added-payments', 'Admin\BookingsController@export_booking_added_payments');

            Route::post('bookings/getAllBookingAddedPayments', 'Admin\BookingsController@getAllBookingAddedPayments');

            Route::post('bookings/getAllActiveReservations', 'Admin\BookingsController@getAllActiveReservations');

            Route::post('bookings/getAllPendingBookings', 'Admin\BookingsController@getAllPendingBookings');

            Route::post('bookings/exportPendingBookings', 'Admin\BookingsController@exportPendingBookings');

            Route::post('bookings/exportPayLaterPendingBookings', 'Admin\BookingsController@exportPayLaterPendingBookings');


            Route::post('bookings/getAllReservationsForUser', 'Admin\BookingsController@getAllReservationsForUser');

            Route::post('bookings/getBookingEditHistory', 'Admin\BookingsController@getBookingEditHistory');

            Route::post('bookings/getSingleBookingInfo', 'Admin\BookingsController@getSingleBookingInfo');

            Route::post('bookings/getPaymentDetailsForBooking', 'Admin\BookingsController@getPaymentDetailsForBooking');

            Route::post('users/getSingleUserInfo', 'Admin\UserController@getSingleUserInfo');


            // For Admin Car Models Module JTable //

            // Car Categories

            Route::post('car_category/getAllCarCategories', 'Admin\CarCategoryController@getAllCarCategories');

            Route::post('car_category/saveCategory', 'Admin\CarCategoryController@saveCategory');

            Route::post('car_category/updateCategory', 'Admin\CarCategoryController@updateCategory');

            Route::post('car_category/deleteCategory', 'Admin\CarCategoryController@deleteCategory');


            // Car Groups

            Route::post('car_group/getAllCarGroups', 'Admin\CarGroupController@getAllCarGroups');

            Route::post('car_group/saveCarGroup', 'Admin\CarGroupController@saveCarGroup');

            Route::post('car_group/updateCarGroup', 'Admin\CarGroupController@updateCarGroup');

            Route::post('car_group/deleteCarGroup', 'Admin\CarGroupController@deleteCarGroup');


            // Car Types

            Route::post('car_type/getAllCarTypes', 'Admin\CarTypeController@getAllCarTypes');

            Route::post('car_type/saveCarType', 'Admin\CarTypeController@saveCarType');

            Route::post('car_type/updateCarType', 'Admin\CarTypeController@updateCarType');

            Route::post('car_type/deleteCarType', 'Admin\CarTypeController@deleteCarType');

            Route::post('car_type/getAllForDropdown', 'Admin\CarTypeController@getAllForDropdown');


            // Car Models

            Route::post('car_model/getAllCarModels', 'Admin\CarModelController@getAllCarModels');

            Route::post('car_model/saveCarModel', 'Admin\CarModelController@saveCarModel');

            Route::post('car_model/updateCarModel', 'Admin\CarModelController@updateCarModel');

            Route::post('car_model/deleteCarModel', 'Admin\CarModelController@deleteCarModel');

            Route::post('car_model/getAllModelsByType', 'Admin\CarModelController@getAllModelsByType');

            Route::post('car_model/getAllForDropdown', 'Admin\CarModelController@getAllForDropdown');


            // For Admin Branches Module JTable //

            // Regions

            Route::post('region/getAll', 'Admin\RegionController@getAll');

            Route::post('region/getAllForDropdown', 'Admin\RegionController@getAllForDropdown');

            Route::post('region/saveData', 'Admin\RegionController@saveData');

            Route::post('region/updateData', 'Admin\RegionController@updateData');

            Route::post('region/deleteData', 'Admin\RegionController@deleteData');


            // Survey Controller

            Route::get('survey', 'Admin\SurveyController@index');

            Route::get('survey/reports', 'Admin\SurveyController@reports');

            Route::get('survey/oasis-reports', 'Admin\SurveyController@oasis_survey_reports');

            Route::post('survey/getAllEmojis', 'Admin\SurveyController@getAllEmojis');

            Route::post('survey/saveData', 'Admin\SurveyController@saveData');

            Route::post('survey/updateData', 'Admin\SurveyController@updateData');

            Route::post('survey/deleteData', 'Admin\SurveyController@deleteData');

            Route::post('survey/getAllSurveyCategories', 'Admin\SurveyController@getAllSurveyCategories');

            Route::post('survey/saveSurveyCategoryData', 'Admin\SurveyController@saveSurveyCategoryData');

            Route::post('survey/updateSurveyCategoryData', 'Admin\SurveyController@updateSurveyCategoryData');

            Route::post('survey/deleteSurveyCategoryData', 'Admin\SurveyController@deleteSurveyCategoryData');

            Route::post('survey/getAllSurveyCategoryOptions', 'Admin\SurveyController@getAllSurveyCategoryOptions');

            Route::post('survey/deleteSurveyCategoryOptionData', 'Admin\SurveyController@deleteSurveyCategoryOptionData');

            Route::post('survey/updateSurveyCategoryOptionData', 'Admin\SurveyController@updateSurveyCategoryOptionData');

            Route::post('survey/saveSurveyCategoryOptionData', 'Admin\SurveyController@saveSurveyCategoryOptionData');

            Route::post('exportSurveyData', 'Admin\SurveyController@exportSUrveyData');

            Route::post('exportOasisSurveyData', 'Admin\SurveyController@exportOasisSurveyData');


            // car selling

            Route::get('car-selling/page-content', 'Admin\CarSellingController@index');

            Route::get('car-selling/manage-cars', 'Admin\CarSellingController@manage_cars');

            Route::get('car-selling/responses', 'Admin\CarSellingController@responses');

            Route::post('car-selling/getAllResponses', 'Admin\CarSellingController@getAllResponses');

            Route::post('car-selling/get_car_models_listing', 'Admin\CarSellingController@get_car_models_listing');

            Route::get('car-selling/exportData', 'Admin\CarSellingController@exportData');

            Route::get('car-selling/exportData', 'Admin\CarSellingController@exportData');

            Route::get('exportCampaignData', 'Admin\BookingsController@exportCampaignData');

            Route::match(['get', 'post'], 'export_cancelled_bookings', 'Admin\BookingsController@export_cancelled_bookings');

            Route::match(['get', 'post'], 'empty_corporate_invoices_from_db', 'Admin\BookingsController@empty_corporate_invoices_from_db');


            /*corporate sales*/

            Route::get('corporate-sales/page-content', 'Admin\CorporateSalesController@index');

            Route::get('corporate-sales/responses', 'Admin\CorporateSalesController@responses');

            Route::post('corporate-sales/getAllResponses', 'Admin\CorporateSalesController@getAllResponses');

            Route::get('corporate-sales/exportData', 'Admin\CorporateSalesController@exportData');


            // Cities

            Route::post('city/getAll', 'Admin\CityController@getAll');


            Route::post('city/getAllCities', 'Admin\RentingTypeController@getAllCities');

            Route::post('city/getAllCitiesById', 'Admin\RentingTypeController@getAllCitiesById');


            Route::post('city/getCitiesForRegion', 'Admin\CityController@getCitiesForRegion');

            Route::post('city/saveData', 'Admin\CityController@saveData');

            Route::post('city/updateData', 'Admin\CityController@updateData');

            Route::post('city/deleteData', 'Admin\CityController@deleteData');


            // Branches

            Route::post('branch/getAll', 'Admin\BranchController@getAll');

            Route::post('branch/getAllSchedule', 'Admin\BranchController@getAllSchedule');

            Route::post('branch/getAllScheduleDateRange', 'Admin\BranchController@getAllScheduleDateRange');

            Route::post('branch/getAllBranches', 'Admin\RentingTypeController@getAllBranches');

            Route::post('branch/getAllBranchesById', 'Admin\RentingTypeController@getAllBranchesById');

            Route::post('branch/getBranchesForCity', 'Admin\BranchController@getBranchesForCity');

            Route::post('branch/saveData', 'Admin\BranchController@saveData');

            Route::post('branch/updateData', 'Admin\BranchController@updateData');

            Route::post('branch/updateSchedule', 'Admin\BranchController@updateSchedule');

            Route::post('branch/updateScheduleDateRange', 'Admin\BranchController@updateScheduleDateRange');

            Route::post('branch/deleteData', 'Admin\BranchController@deleteData');


            Route::get('branches-bulk-options', 'Admin\BranchController@bulkOptionsBranches');

            Route::post('branch/saveBulk', 'Admin\BranchController@saveBulk');

            Route::post('branch/saveBulkBranchActive', 'Admin\BranchController@saveBulkBranchActive');


            Route::post('branch/getBranchDeliveryCoordinates', 'Admin\BranchController@getBranchDeliveryCoordinates');

            // general branch timings
            Route::get('branch/deactivate-general-timing-for-delivery-branches', 'Admin\BranchController@deactivate_general_timing_for_delivery_branches');
            Route::get('branch/activate-general-timing-for-delivery-branches', 'Admin\BranchController@activate_general_timing_for_delivery_branches');
            Route::post('branch/get-general-timing-for-delivery-branches', 'Admin\BranchController@get_general_timing_for_delivery_branches');
            Route::post('branch/update-general-timing-for-delivery-branches', 'Admin\BranchController@update_general_timing_for_delivery_branches');


            // For Admin Car Pricing Module JTable //

            // Pricing Controller

            Route::get('pricing', 'Admin\PricingController@index');

            Route::post('importCorporatePricing', 'Admin\PricingController@importCorporatePricing');

            Route::post('pricing/getAllCarModels', 'Admin\PricingController@getAllCarModels');

            Route::post('pricing/getAllPricesForModel', 'Admin\PricingController@getAllPricesForModel');


            // for pricing history listing

            Route::post('pricing/getPriceHistory', 'Admin\PricingController@getPriceHistory');


            Route::post('pricing/getAllExtrasForModel', 'Admin\PricingController@getAllExtrasForModel');

            Route::post('pricing/saveData', 'Admin\PricingController@saveData');

            Route::post('pricing/updateData', 'Admin\PricingController@updateData');

            Route::post('pricing/deleteData', 'Admin\PricingController@deleteData');


            // For cars availability

            Route::post('pricing/carsAvailability', 'Admin\PricingController@carsAvailability');

            Route::post('pricing/carsAvailabilityForCustomerType', 'Admin\PricingController@carsAvailabilityForCustomerType');

            Route::post('pricing/updateCarsAvailability', 'Admin\PricingController@updateCarsAvailability');


            // Limousine Charges
            Route::post('pricing/limousine_charges/getAll', 'Admin\LimousineChargesController@getAll');
            Route::post('pricing/limousine_charges/saveData', 'Admin\LimousineChargesController@saveData');
            Route::post('pricing/limousine_charges/updateData', 'Admin\LimousineChargesController@updateData');
            Route::post('pricing/limousine_charges/deleteData', 'Admin\LimousineChargesController@deleteData');

            // Dropoff Controller

            Route::get('dropoff_charges', 'Admin\DropoffChargesController@index');

            Route::post('dropoff_charges/getAll', 'Admin\DropoffChargesController@getAll');

            Route::post('dropoff_charges/saveData', 'Admin\DropoffChargesController@saveData');

            Route::post('dropoff_charges/updateData', 'Admin\DropoffChargesController@updateData');

            Route::post('dropoff_charges/deleteData', 'Admin\DropoffChargesController@deleteData');



            // Renting Type Controller

            Route::get('dropoff_charges', 'Admin\DropoffChargesController@index');

            Route::post('renting_type/getAllForDropdown', 'Admin\RentingTypeController@getAllForDropdown');

            Route::post('dropoff_charges/saveData', 'Admin\DropoffChargesController@saveData');

            Route::post('dropoff_charges/updateData', 'Admin\DropoffChargesController@updateData');

            Route::post('dropoff_charges/deleteData', 'Admin\DropoffChargesController@deleteData');


            // For Promotions and offers

            Route::get('promotions_offers', 'Admin\PromotionsController@index');

            Route::post('promotions_offers/getAll', 'Admin\PromotionsController@getAll');

            Route::post('promotions_offers/getSinlgeDetail', 'Admin\PromotionsController@getSinlgeDetail');

            Route::post('promotions_offers/saveData', 'Admin\PromotionsController@saveData');

            Route::post('promotions_offers/updateData', 'Admin\PromotionsController@updateData');

            Route::post('promotions_offers/deleteData', 'Admin\PromotionsController@deleteData');

            Route::post('promotions_offers/getPromotionHistory', 'Admin\PromotionsController@getPromotionHistory');

            Route::get('promotions_offers/export', 'Admin\PromotionsController@export');


            // For Loyalty Page

            Route::get('page/home', 'Admin\PageController@home');

            Route::get('page/home-slider', 'Admin\PageController@home_slider');

            Route::get('page/slider-sorting', 'Admin\PageController@slider_sorting');

            Route::post('page/sorting', 'Admin\PageController@sorting');


            Route::get('page/loyalty', 'Admin\PageController@loyalty');
            Route::post('loyalty-cards/getAll', 'Admin\PageController@getAllForLoyaltyCards');
            Route::post('loyalty-cards/saveData', 'Admin\PageController@saveDataForLoyaltyCards');
            Route::post('loyalty-cards/updateData', 'Admin\PageController@updateDataForLoyaltyCards');
            Route::post('loyalty-cards/deleteData', 'Admin\PageController@deleteDataForLoyaltyCards');
            Route::post('loyalty-reward-programs/getAll', 'Admin\PageController@getAllForLoyaltyRewardPrograms');
            Route::post('loyalty-reward-programs/saveData', 'Admin\PageController@saveDataForLoyaltyRewardPrograms');
            Route::post('loyalty-reward-programs/updateData', 'Admin\PageController@updateDataForLoyaltyRewardPrograms');
            Route::post('loyalty-reward-programs/deleteData', 'Admin\PageController@deleteDataForLoyaltyRewardPrograms');
            Route::post('loyalty-faqs/getAll', 'Admin\PageController@getAllForLoyaltyFaqs');
            Route::post('loyalty-faqs/saveData', 'Admin\PageController@saveDataForLoyaltyFaqs');
            Route::post('loyalty-faqs/updateData', 'Admin\PageController@updateDataForLoyaltyFaqs');
            Route::post('loyalty-faqs/deleteData', 'Admin\PageController@deleteDataForLoyaltyFaqs');

            Route::get('page/services', 'Admin\PageController@services');

            Route::get('page/about-us', 'Admin\PageController@about_us');

            Route::get('page/refer-and-earn', 'Admin\PageController@refer_and_earn');

            Route::get('page/change-points', 'Admin\PageController@change_points');

            Route::get('page/news', 'Admin\PageController@news');

            Route::get('page/faqs', 'Admin\PageController@faqs');

            Route::get('page/career', 'Admin\PageController@career');

            Route::get('page/refunds', 'Admin\PageController@refunds');
            Route::get('page/guar_refunds', 'Admin\PageController@guar_refunds');
            Route::get('page/sta', 'Admin\PageController@sta');

            Route::post('page/delete_loyalty_image', 'Admin\PageController@deleteLoyaltyImages');


            Route::get('page/program-rewards', 'Admin\PageController@program_awards');

            Route::get('page/program-rewards-sorting', 'Admin\PageController@program_awards_sorting');

            Route::post('page/program-rewards-update-sorting', 'Admin\PageController@program_awards_update_sorting');


            Route::get('page/location', 'Admin\PageController@location');

            Route::get('page/contactUs', 'Admin\PageController@contactUs');

            Route::get('page/contactUs', 'Admin\PageController@contactUs');

            Route::post('page/saveAdmin', 'Admin\PageController@saveAdmin');

            Route::get('admins/edit/{id}', 'Admin\AdminController@edit');

            Route::post('page/updateAdmin', 'Admin\PageController@updateAdmin');

            Route::post('page/deleteAdmin', 'Admin\PageController@deleteAdmin');

            Route::post('page/update', 'Admin\PageController@update');

            Route::post('page/ajaxUploadFile', 'Admin\PageController@ajaxUploadFile');


            // get home page listing for Jtable

            Route::post('page/getHomeSlider', 'Admin\PageController@getHomeSlider');

            Route::post('page/saveHomeSlider', 'Admin\PageController@saveHomeSlider');

            Route::post('page/updateHomeSlider', 'Admin\PageController@updateHomeSlider');

            Route::post('page/deleteHomeSlider', 'Admin\PageController@deleteHomeSlider');

            // get mobile home page listing for Jtable

            Route::post('page/getMobileSlider', 'Admin\PageController@getMobileSlider');

            Route::post('page/saveMobileSlider', 'Admin\PageController@saveMobileSlider');

            Route::post('page/updateMobileSlider', 'Admin\PageController@updateMobileSlider');

            Route::post('page/deleteMobileSlider', 'Admin\PageController@deleteMobileSlider');


            // For Listings in JTable

            Route::post('page/get_listing', 'Admin\PageController@get_listing');

            Route::post('page/delete_listing', 'Admin\PageController@delete_listing');

            Route::post('page/update_listing', 'Admin\PageController@update_listing');

            Route::post('page/save_listing', 'Admin\PageController@save_listing');


            // For Site Settings

            Route::get('settings', 'Admin\SettingsController@index');

            Route::get('terms-and-conditions', 'Admin\SettingsController@terms_and_conditions');

            Route::get('human-less-instructions', 'Admin\SettingsController@human_less_instructions');

            Route::post('settings/safeRoadApi', 'Admin\SettingsController@safeRoadApi');

            Route::get('safe-road-api', 'Admin\SettingsController@safe_road_api');

            Route::get('maintenance', 'Admin\SettingsController@maintenance');

            Route::get('api-settings', 'Admin\SettingsController@apiSettings');

            Route::get('loyalty-cards', 'Admin\SettingsController@loyalty_cards');

            Route::get('redeem-factors', 'Admin\SettingsController@redeem_factors');

            Route::get('renting-types', 'Admin\SettingsController@renting_types');

            Route::get('inquiry-and-department-types', 'Admin\SettingsController@inquiry_and_department_types');

            Route::get('user-rights', 'Admin\SettingsController@user_roles');

            Route::get('cronjobs', 'Admin\SettingsController@cronjobs');

            Route::get('site-settings', 'Admin\SettingsController@siteSettings');

            Route::get('sections', 'Admin\SettingsController@sections');


            Route::post('settings/get_listing', 'Admin\SettingsController@get_listing');

            Route::post('settings/add_listing', 'Admin\SettingsController@add_listing');

            Route::post('settings/update_listing', 'Admin\SettingsController@update_listing');

            Route::post('settings/delete_listing', 'Admin\SettingsController@delete_listing');


            //form department settings

            Route::post('settings/get_departments', 'Admin\SettingsController@get_departments');

            Route::post('settings/add_departments', 'Admin\SettingsController@add_departments');

            Route::post('settings/update_departments', 'Admin\SettingsController@update_departments');

            Route::post('settings/delete_departments', 'Admin\SettingsController@delete_departments');


            // For Site Labels

            Route::get('settings/site_labels', 'Admin\SettingsController@site_labels');


            Route::post('settings/smtp_settings', 'Admin\SettingsController@smtp_settings');

            Route::post('settings/api_settings', 'Admin\SettingsController@api_settings');

            Route::post('settings/site_settings', 'Admin\SettingsController@site_settings');

            Route::post('settings/save_maintenance_text', 'Admin\SettingsController@save_maintenance_text');

            Route::post('settings/social_links', 'Admin\SettingsController@social_links');

            Route::post('settings/save_terms_conditions', 'Admin\SettingsController@save_terms_conditions');

            Route::post('settings/save_humanless_instructions', 'Admin\SettingsController@save_humanless_instructions');


            Route::post('settings/get_loyalty_card_types', 'Admin\SettingsController@get_loyalty_card_types');

            Route::post('settings/delete_loyalty_card_type', 'Admin\SettingsController@delete_loyalty_card_type');

            Route::post('settings/update_loyalty_card_type', 'Admin\SettingsController@update_loyalty_card_type');

            Route::post('settings/add_loyalty_card_type', 'Admin\SettingsController@add_loyalty_card_type');


            Route::post('settings/get_renting_types', 'Admin\SettingsController@get_renting_types');

            Route::post('settings/delete_renting_type', 'Admin\SettingsController@delete_renting_type');

            Route::post('settings/update_renting_type', 'Admin\SettingsController@update_renting_type');

            Route::post('settings/add_renting_type', 'Admin\SettingsController@add_renting_type');


            Route::post('settings/userRights', 'Admin\SettingsController@userRights');

            Route::post('settings/updateUserRights', 'Admin\SettingsController@updateUserRights');

            Route::post('settings/verify_password', 'Admin\SettingsController@verify_password');
            Route::post('settings/decrypt_encrypt_data', 'Admin\SettingsController@decrypt_encrypt_data');


            Route::post('pricing/getAllCarModels', 'Admin\PricingController@getAllCarModels');

            Route::post('pricing/getAllPricesForModel', 'Admin\PricingController@getAllPricesForModel');


            // Redeem

            Route::get('redeem_setup', 'Admin\RedeemController@index');

            Route::post('redeem_setup/getAll', 'Admin\RedeemController@getAll');

            Route::post('redeem_setup/saveData', 'Admin\RedeemController@saveData');

            Route::post('redeem_setup/updateData', 'Admin\RedeemController@updateData');

            Route::post('redeem_setup/deleteData', 'Admin\RedeemController@deleteData');

            Route::post('redeem_setup/getAllModelsByType', 'Admin\RedeemController@getAllModelsByType');


            /*availability setup*/


            Route::get('availability', 'Admin\AvailabilityController@index');

            Route::post('availability/getAll', 'Admin\AvailabilityController@getAll');

            Route::post('availability/saveData', 'Admin\AvailabilityController@saveData');

            Route::post('availability/updateData', 'Admin\AvailabilityController@updateData');

            Route::post('availability/updateActiveStatus', 'Admin\AvailabilityController@updateActiveStatus');

            Route::post('availability/deleteData', 'Admin\AvailabilityController@deleteData');

            Route::post('availability/getAllModelsByType', 'Admin\AvailabilityController@getAllModelsByType');

            Route::post('availability/getAllCities', 'Admin\AvailabilityController@getAllCities');

            Route::get('availability/export', 'Admin\AvailabilityController@export_data');


            Route::get('booking-cancellation-reasons', 'Admin\BookingCancellationReasonController@index');

            Route::post('booking-cancellation-reasons/getAll', 'Admin\BookingCancellationReasonController@getAll');

            Route::post('booking-cancellation-reasons/saveData', 'Admin\BookingCancellationReasonController@saveData');

            Route::post('booking-cancellation-reasons/updateData', 'Admin\BookingCancellationReasonController@updateData');

            Route::post('booking-cancellation-reasons/deleteData', 'Admin\BookingCancellationReasonController@deleteData');

            Route::get('loyalty-programs', 'Admin\LoyaltyProgramController@index');

            Route::post('loyalty-programs/getAll', 'Admin\LoyaltyProgramController@getAll');

            Route::post('loyalty-programs/saveData', 'Admin\LoyaltyProgramController@saveData');

            Route::post('loyalty-programs/updateData', 'Admin\LoyaltyProgramController@updateData');

            Route::post('loyalty-programs/deleteData', 'Admin\LoyaltyProgramController@deleteData');

            Route::get('notifications', 'Admin\NotificationsController@index');

            Route::post('notifications/getAll', 'Admin\NotificationsController@getAll');

            Route::post('notifications/saveData', 'Admin\NotificationsController@saveData');

            Route::post('notifications/deleteData', 'Admin\NotificationsController@deleteData');

            Route::post('notifications/send_notification', 'Admin\NotificationsController@send_notification');


// For Admin Controllers

            Route::resource('dashboard', 'Admin\DashboardController');

            Route::resource('car_category', 'Admin\CarCategoryController');

            Route::resource('car_group', 'Admin\CarGroupController');

            Route::resource('car_type', 'Admin\CarTypeController');

            Route::resource('car_model', 'Admin\CarModelController');

            Route::resource('region', 'Admin\RegionController');

            Route::resource('city', 'Admin\CityController');

            Route::resource('branch', 'Admin\BranchController');

            Route::resource('individual_customer', 'Admin\IndividualCustomerController');


            Route::get('corporate_customer', 'Admin\CorporateCustomerController@index');

            Route::get('corporate_customer/add', 'Admin\CorporateCustomerController@add');

            Route::post('corporate_customer/save', 'Admin\CorporateCustomerController@save');

            Route::get('corporate_customer/edit/{id}', 'Admin\CorporateCustomerController@edit');

            Route::post('corporate_customer/update', 'Admin\CorporateCustomerController@update');

            Route::get('corporate_customer/view/{id}', 'Admin\CorporateCustomerController@view');

            Route::get('corporate_customer/delete/{id}', 'Admin\CorporateCustomerController@delete');


            Route::get('super_corporate_customer', 'Admin\SuperCorporateCustomerController@index');

            Route::get('super_corporate_customer/add', 'Admin\SuperCorporateCustomerController@add');

            Route::post('super_corporate_customer/save', 'Admin\SuperCorporateCustomerController@save');

            Route::get('super_corporate_customer/edit/{id}', 'Admin\SuperCorporateCustomerController@edit');

            Route::post('super_corporate_customer/update', 'Admin\SuperCorporateCustomerController@update');

            Route::get('super_corporate_customer/view/{id}', 'Admin\SuperCorporateCustomerController@view');

            Route::get('super_corporate_customer/delete/{id}', 'Admin\SuperCorporateCustomerController@delete');


            Route::post('corporate_customer/getAllForDropdown', 'Admin\CorporateCustomerController@getAllForDropdown');

            // corporate quotations
            Route::get('corporate_quotations/{id}', 'Admin\CorporateQuotationsController@index');
            Route::post('corporate_quotations/import', 'Admin\CorporateQuotationsController@importCorporateQuotations');

            Route::post('corporate_quotations/getCorporateQuotations', 'Admin\CorporateQuotationsController@getCorporateQuotations');
            Route::post('corporate_quotations/updateCorporateQuotation', 'Admin\CorporateQuotationsController@updateCorporateQuotation');
            Route::post('corporate_quotations/deleteCorporateQuotation', 'Admin\CorporateQuotationsController@deleteCorporateQuotation');

            Route::post('corporate_quotations/getCorporateQuotationPrices', 'Admin\CorporateQuotationsController@getCorporateQuotationPrices');
            Route::post('corporate_quotations/updateCorporateQuotationPrice', 'Admin\CorporateQuotationsController@updateCorporateQuotationPrice');
            Route::post('corporate_quotations/deleteCorporateQuotationPrice', 'Admin\CorporateQuotationsController@deleteCorporateQuotationPrice');


            Route::resource('admins', 'Admin\AdminController');

            Route::resource('bookings', 'Admin\BookingsController');

            Route::resource('roles', 'Admin\AdminRolesController');

            Route::resource('rights', 'Admin\RightsController');



            //  kashiffor inquiries

            Route::resource('inquiries', 'Admin\InquiryController');

            Route::resource('career', 'Admin\CareerController');

            Route::match(['get', 'post'], 'export_career', 'Admin\CareerController@export_career');

            Route::get('logs', 'Admin\SettingsController@get_logs');

            Route::get('notification', 'Admin\SettingsController@notification');

            Route::post('settings/send_notification', 'Admin\SettingsController@send_notification');


            Route::get('getCustomerForDataTable', 'Admin\IndividualCustomerController@getCustomerForDataTable');

            Route::post('getCustomerForJTable', 'Admin\IndividualCustomerController@getCustomerForJTable');

            Route::post('getCustomerDetailsForJTable', 'Admin\IndividualCustomerController@getCustomerDetailsForJTable');

            Route::post('updateCustomerForJTable', 'Admin\IndividualCustomerController@updateCustomerForJTable');

            Route::post('exportUsers', 'Admin\IndividualCustomerController@exportUsers');

            Route::post('exportCustomers', 'Admin\IndividualCustomerController@exportCustomers');


            Route::get('exported-files', 'Admin\BookingsController@exported_files');

            Route::get('pending-bookings', 'Admin\BookingsController@pending_bookings');

            Route::get('corporate-pay-later-bookings', 'Admin\BookingsController@paylater_bookings');

            Route::get('pricing-bulk-options', 'Admin\PricingController@bulkOptions');

            Route::get('pricing-bad-log', 'Admin\PricingController@badLog');

            Route::post('pricing/getGroupsForCategory', 'Admin\PricingController@getGroupsForCategory');

            Route::post('pricing/getTypeForGroups', 'Admin\PricingController@getTypeForGroups');

            Route::post('pricing/getModelsForType', 'Admin\PricingController@getModelsForType');

            Route::post('pricing/getCitiesForRegion', 'Admin\PricingController@getCitiesForRegion');

            Route::post('pricing/getBranchesForCity', 'Admin\PricingController@getBranchesForCity');

            Route::post('pricing/saveBulkPrice', 'Admin\PricingController@saveBulkPrice');

            Route::get('app-popup-promo-codes', 'Admin\SettingsController@view_app_popup_promo_codes');

            Route::get('app-popup-promo-codes/add', 'Admin\SettingsController@add_app_popup_promo_codes');

            Route::post('app-popup-promo-codes/save', 'Admin\SettingsController@save_app_popup_promo_codes');

            Route::get('app-popup-promo-codes/edit/{id}', 'Admin\SettingsController@edit_app_popup_promo_codes');

            Route::post('app-popup-promo-codes/update', 'Admin\SettingsController@update_app_popup_promo_codes');

            Route::get('app-popup-promo-codes/export/{id}', 'Admin\SettingsController@export_app_popup_promo_codes');

            Route::get('manage-bookings', 'Admin\BookingsController@manage_bookings');

            Route::get('export_manage_bookings', 'Admin\BookingsController@export_manage_bookings');

            Route::post('get_bookings_count_for_export_in_manage_bookings', 'Admin\BookingsController@get_bookings_count_for_export_in_manage_bookings');

            Route::post('search_manage_bookings', 'Admin\BookingsController@search_manage_bookings');

            Route::post('add_booking_payment_in_manage_bookings', 'Admin\BookingsController@add_booking_payment_in_manage_bookings');

            Route::post('update_booking_payment_in_manage_bookings', 'Admin\BookingsController@update_booking_payment_in_manage_bookings');

            Route::post('add_extended_booking_payment_in_manage_bookings', 'Admin\BookingsController@add_extended_booking_payment_in_manage_bookings');

            Route::post('update_extended_booking_payment_in_manage_bookings', 'Admin\BookingsController@update_extended_booking_payment_in_manage_bookings');

        });

    });

    Route::group(['middleware' => ['XSSProtection']], function () {

        // For Frontend CMS Pages For English Language

        /*Route::get('/', function () {

            return Redirect::to('home');

            //return Redirect::to('home');

        });



        Route::get('en/', function () {

            return Redirect::to('en/home');

        });*/

        Route::get('home', function () {

            return Redirect::to('/');

        });

        Route::get('en/home', function () {

            return Redirect::to('en/');

        });

        Route::get('/', 'Front\PageController@index');

        Route::get('en/', 'Front\PageController@index');


        Route::get('test_mail', 'Front\PageController@testEmailTemplates');

        Route::get('terms-and-conditions', 'Front\PageController@terms_and_conditions');

        Route::get('human-less-instructions', 'Front\PageController@human_less_instructions');

        Route::get('safe-road-api', 'Front\PageController@safe_road_api');


        Route::post('news-letter', 'Front\PageController@newsLetter');


        Route::get('print-booking/{id}', 'Front\PageController@generatePdf');

        Route::get('print-invoice/{id}', 'Front\PageController@print_invoice');

        Route::get('print-lease-invoice/{id}', 'Front\PageController@print_lease_invoice');


        Route::post('getSpecialCarDesc', 'Front\PageController@getSpecialCarDesc');


        Route::get('set_lang_session', 'Front\PageController@set_lang_session');

        Route::get('set_mobile_full_version', 'Front\PageController@set_mobile_full_version');

        Route::get('loyalty', 'Front\PageController@loyalty');

        Route::get('location', 'Front\PageController@location');

        Route::get('about-us', 'Front\PageController@about_us');

        Route::get('change-points', 'Front\PageController@change_points');

        Route::get('survey', 'Front\PageController@survey');

        Route::match(['get', 'post'], 'checkIfSurveyPendingToFill', 'Front\PageController@checkIfSurveyPendingToFill');

        Route::match(['get', 'post'], 'skipSurvey', 'Front\PageController@skipSurvey');

        Route::get('fleet/{category_name?}', 'Front\PageController@fleet')->name('fleet');





        Route::post('interestedInCorporateSales', 'Front\PageController@interestedInCorporateSales');

        Route::post('resendPayLaterInvoice', 'Front\PageController@resendPayLaterInvoice');

        Route::post('corporateCompany', 'Front\PageController@corporateCompany');

        Route::match(['get', 'post'], 'refunds', 'Front\PageController@refunds');
        Route::match(['get', 'post'], 'guar-refunds/{contract_number?}', 'Front\PageController@guar_refunds');

        /*Qitaf APIs*/
        Route::post('qitafSendOTP', 'Front\PageController@qitafSendOTP');
        Route::post('qitafRedeem', 'Front\PageController@qitafRedeem');

        /*Niqaty APIs*/
        Route::post('get_niqaty_redeem_options', 'Front\PageController@get_niqaty_redeem_options');
        Route::post('authorize_niqaty_redeem_request', 'Front\PageController@authorize_niqaty_redeem_request');
        Route::post('confirm_niqaty_redeem_request', 'Front\PageController@confirm_niqaty_redeem_request');

        /*Mobile services for Mokafaa*/
        Route::post('mokafaa_get_access_token', 'Front\PageController@mokafaa_get_access_token');
        Route::post('mokafaa_send_otp', 'Front\PageController@mokafaa_send_otp');
        Route::post('mokafaa_initiate_redeem_request', 'Front\PageController@mokafaa_initiate_redeem_request');

        /*Mobile services for ANB*/
        Route::post('anb_get_access_token', 'Front\PageController@anb_get_access_token');
        Route::post('anb_send_otp', 'Front\PageController@anb_send_otp');
        Route::post('anb_initiate_redeem_request', 'Front\PageController@anb_initiate_redeem_request');

        Route::match(['get', 'post'], 'hp_check_payment_status', 'Front\PageController@hp_check_payment_status');

        Route::match(['get', 'post'], 'add-payment', 'Front\PageController@add_payment');

        Route::get('verify/{type}/{user_id}', 'Front\PageController@verify');

        Route::match(['get', 'post'], 'hp_ipn', 'Front\PageController@hp_ipn');

        Route::match(['get', 'post'], 'hp_ipn_for_add_payment', 'Front\PageController@hp_ipn_for_add_payment');

        Route::match(['get', 'post'], 'applyCouponForAddPayment', 'Front\PageController@applyCouponForAddPayment');


// fleet load more pagination

        Route::post('fleetPagination', 'Front\PageController@getFleetPagination');

        Route::post('searchCarsPagination', 'Front\PageController@getSearchResultPagination');

        Route::get('getServerTime', 'Front\PageController@getServerTime');

        Route::get('share/{id}/{city?}/{branch?}', 'Front\PageController@printMapPopups');


        Route::get('services', 'Front\PageController@services');


        Route::get('program-rewards', 'Front\PageController@program_awards');

        Route::get('lease', 'Front\PageController@corporate_sales');


        Route::get('book-car', 'Front\PageController@book_car');

        Route::get('news', 'Front\PageController@news');


        Route::get('faqs', 'Front\PageController@faqs');

        Route::get('careers', 'Front\PageController@careers');

        Route::get('contact-us', 'Front\PageController@contact_us');

        Route::post('login', 'Front\PageController@login');

        Route::post('loginOnPayment', 'Front\PageController@loginOnPayment');

        Route::get('register', 'Front\PageController@register');

        Route::get('create-ind-user', 'Front\PageController@new_user_ind');

        Route::get('create-ind-login', 'Front\PageController@create_login_ind');

        Route::post('new_ind_user_signup', 'Front\PageController@new_ind_user_signup');

        Route::get('create-corp-user', 'Front\PageController@new_user_corp');

        Route::get('create-corp-login', 'Front\PageController@create_login_corp');

        Route::post('new_corp_user_signup', 'Front\PageController@new_corp_user_signup');

        Route::get('savePdf', 'Front\PageController@savePdf');

        Route::get('downloadPdf', 'Front\PageController@downloadPdf');

        Route::get('openPdf', 'Front\PageController@openPdf');

        Route::get('my-profile', 'Front\PageController@profile');

        Route::get('edit-my-profile', 'Front\PageController@edit_profile');

        Route::get('refer_and_earn', 'Front\PageController@refer_and_earn');

        Route::post('forgot_password', 'Front\PageController@forgot_password');

        Route::get('reset-password', 'Front\PageController@reset_password');

        Route::post('change_password', 'Front\PageController@change_password');

        Route::post('update_profile', 'Front\PageController@update_profile');

        Route::post('update_corporate_profile', 'Front\PageController@update_corporate_profile');

        Route::get('logout', 'Front\PageController@logout');

        Route::post('search-results', 'Front\PageController@main_search');

        Route::get('search-results', 'Front\PageController@main_search');

        Route::post('main_search_with_filter', 'Front\PageController@main_search_with_filter');

        Route::post('filterSellingCars', 'Front\PageController@filterSellingCars');

        Route::post('interestedInCar', 'Front\PageController@interestedInCar');


// kashif work 7 april 2017

        Route::post('fleet_serch_cat_filter', 'Front\PageController@fleet_serch_cat_filter');

        Route::post('search_cars_with_all_fields', 'Front\PageController@search_cars_with_all_fields');

        Route::get('fleet/booking/{id}', 'Front\PageController@fleetBooking');

//====


        Route::get('extra-services', 'Front\PageController@extra_services');

        Route::get('book-car', 'Front\PageController@book_car');

        Route::post('cancelBooking', 'Front\PageController@cancelBooking');


// kashif work start 3-30-2017

        Route::post('saveContactUs', 'Front\PageController@submitContactFrm');

        Route::post('saveChangePoints', 'Front\PageController@submitChangePoints');

        Route::post('saveCareerForm', 'Front\PageController@saveCareerForm');


        Route::post('redirectToExtraServicesPage', 'Front\PageController@redirectToExtraServicesPage');

        Route::post('payment', 'Front\PageController@payment');

        Route::get('payment', 'Front\PageController@payment');

        Route::post('book_now', 'Front\PageController@book_now');

        Route::post('book_now_for_corporate', 'Front\PageController@book_now_for_corporate');

        //Route::get('cc-payment', 'Front\PageController@cc_payment');

        Route::match(['get', 'post'], 'checkSessionBeforePayment', 'Front\PageController@checkSessionBeforePayment');

        Route::match(['get', 'post'], 'cc-payment', 'Front\PageController@cc_payment');

        Route::match(['get', 'post'], 'mada-payment', 'Front\PageController@mada_payment');

        Route::get('stsTransactionInquiry', 'Front\PageController@stsTransactionInquiry');

        Route::get('booking-done', 'Front\PageController@booking_done');

        Route::get('booking-detail/{id}', 'Front\PageController@booking_detail');

        Route::get('get-car/{id}', 'Front\HumanLessController@get_car');

        //Route::post('end-trip/{id}','Front\HumanLessController@end_trip');

        Route::match(['get', 'post'], 'end-trip/{id}', 'Front\HumanLessController@end_trip');

        Route::post('checkIfBookingExistWithRefNo', 'Front\PageController@checkIfBookingExistWithRefNo');

        Route::post('checkIfBookingExistWithRefNoStep2', 'Front\PageController@checkIfBookingExistWithRefNoStep2');

        Route::get('manage-booking/{id}', 'Front\PageController@manage_booking');

        //Route::post('booked', 'Front\PageController@booked');
        Route::match(['get', 'post'], 'booked', 'Front\PageController@booked');

        Route::get('verifySTSPayment', 'Front\PageController@verifySTSPayment');

        Route::post('save_extra_infor_after_reservation', 'Front\PageController@save_extra_infor_after_reservation');

        Route::post('create_login_find_user', 'Front\PageController@create_login_find_user');

        Route::get('my-bookings', 'Front\PageController@my_booking');

        Route::match(['get', 'post'], 'edit-booking/{booking_id?}', 'Front\PageController@edit_booking');

        Route::get('my-invoices', 'Front\PageController@my_invoices');

        Route::get('my-invoices-paginate', 'Front\PageController@my_invoices_paginate');

        Route::get('lease-invoices', 'Front\PageController@lease_invoices');

        Route::get('my-history', 'Front\PageController@my_history');

        Route::get('create-login', 'Front\PageController@create_login_step_2');


        Route::get('resendVerifyCode', 'Front\PageController@resendVerifyCode');

        Route::get('verifySmsCheck', 'Front\PageController@verifySmsCheck');

        Route::get('cancelBookingVerification', 'Front\PageController@cancelBookingVerification');

        Route::post('sendEditBookingOTP', 'Front\PageController@sendEditBookingOTP');


        Route::match(['get', 'post'], 'applyCoupon', 'Front\PageController@applyCoupon');

        Route::post('back', 'Front\PageController@removeBooking');

        Route::post('my_bookings_filter', 'Front\PageController@my_bookings_filter');

        Route::post('checkIfIdNoExist', 'Front\PageController@checkIfIdNoExist');

        Route::post('validateEmailAndIdNo', 'Front\PageController@validateEmailAndIdNo');

        Route::post('validateLoginEmail', 'Front\PageController@validateLoginEmail');

        Route::get('something-went-wrong', 'Front\PageController@something_went_wrong');

        Route::get('offer', 'Front\PageController@offer');

        Route::get('offer-detail/{id}', 'Front\PageController@offerDetail');

        Route::post('redirectToBookingPage', 'Front\PageController@redirectToBookingPage');

        Route::match(['get', 'post'], 'paytabsIPN', 'Front\PageController@paytabsIPN');

        Route::match(['get', 'post'], 'stsIPN', 'Front\PageController@stsIPN');

        Route::match(['get', 'post'], 'checkIfUserBlacklistedOrSimahBlock', 'Front\PageController@checkIfUserBlacklistedOrSimahBlock');

        Route::post('searchAreaBranchFilter', 'Front\PageController@searchAreaBranchFilter');

        Route::post('validateSaudiID', 'Front\PageController@validateSaudiID');

        Route::get('getLocationAndCheck', 'Front\PageController@getLocationAndCheck');

        Route::get('offers', 'Front\PageController@offers');

        Route::get('manageBookings', 'Front\PageController@manageBookings');

        Route::post('getBranchesByCity', 'Front\PageController@getBranchesByCity');

        Route::match(['get', 'post'], 'returnFromSadad', 'Front\PageController@returnFromSadad');

        Route::post('getCategoriesForEmoji', 'Front\PageController@getCategoriesForEmoji');

        Route::post('getOptionsForCategory', 'Front\PageController@getOptionsForCategory');

        Route::post('saveSurveyFeedback', 'Front\PageController@saveSurveyFeedback');

        Route::get('apps', 'Front\PageController@apps');

        Route::get('sta', 'Front\PageController@sta');

        Route::get('leasing', 'Front\PageController@mada');

        Route::get('Leasing', 'Front\PageController@mada');

        Route::get('oasis-survey', 'Front\PageController@oasisSurvey');

        Route::get('oasis-survey-new', 'Front\PageController@oasisSurveyNew');

        Route::get('limousine-oasis-survey', 'Front\PageController@oasisSurveyForLimousine');

        Route::post('saveOasisSurveyFeedback', 'Front\PageController@saveOasisSurveyFeedback');

        Route::post('saveOasisSurveyFeedbackForLimousine', 'Front\PageController@saveOasisSurveyFeedbackForLimousine');

        Route::post('getDriverInfo', 'Front\PageController@getDriverInfo');

        Route::get('car-selling', 'Front\PageController@sell_car');

        Route::post('getMoreSellingCars', 'Front\PageController@getMoreSellingCars');

        //Human less Mode

        Route::get('human-less-acknowledge', 'Front\HumanLessController@humanLessAcknowledge');

        Route::post('getCarPlates', 'Front\HumanLessController@getCarPlates');

        Route::post('getCarInfo', 'Front\HumanLessController@getCarInfo');

        Route::post('openContract', 'Front\HumanLessController@openContract');

        Route::post('issueTammOTP', 'Front\HumanLessController@issueTammOTP');

        Route::post('issueTammAuth', 'Front\HumanLessController@issueTammAuth');

        Route::post('cancelTammAuth', 'Front\HumanLessController@cancelTammAuth');

        Route::post('unlockCar', 'Front\HumanLessController@unlockCar');

        Route::post('changeCar', 'Front\HumanLessController@changeCar');

        Route::post('watermark', 'Front\HumanLessController@watermark')->name('watermark');

        Route::post('watermark_endTrip', 'Front\HumanLessController@watermark_endTrip')->name('watermark_endTrip');

        Route::post('clear-inspection', 'Front\HumanLessController@clearInspection')->name('clear-inspection');

        Route::get('pickup-inspection/{id}', 'Front\HumanLessController@pickUpInspection');

        Route::get('dropoff-inspection/{id}', 'Front\HumanLessController@dropOffInspection');

        Route::get('vehicle-inspection/{id}', 'Front\HumanLessController@vehicleInspection');

        Route::post('getContractBalance', 'Front\HumanLessController@getContractBalance');

        Route::post('addPayment', 'Front\HumanLessController@addPayment');

        Route::post('closeContract', 'Front\HumanLessController@closeContract');

        Route::post('sendDummyEmail', 'Front\HumanLessController@sendDummyEmail');


// URLs for redeem offer module

        Route::post('calculateRedeemPointsFromAmount', 'Front\PageController@calculateRedeemPointsFromAmount');

        Route::post('calculateRedeemAmountFromPoints', 'Front\PageController@calculateRedeemAmountFromPoints');


        Route::get('send_registration_link_to_walkin_customers', 'Front\PageController@send_registration_link_to_walkin_customers');

        Route::get('walkin-signup', 'Front\PageController@walkin_customer_signup');

        Route::get('confirm-booking', 'Front\PageController@confirm_walkin_booking');

        Route::post('saveWalkinBookingOnConfirm', 'Front\PageController@saveWalkinBookingOnConfirm');

        Route::post('fetch_nearest_delivery_branch', 'Front\PageController@fetch_nearest_delivery_branch');


        Route::get('stsPaymentPushNotification', 'Front\PageController@stsPaymentPushNotification');
        Route::post('stsPaymentPushNotification', 'Front\PageController@stsPaymentPushNotification');

// Frontend Routes For Arabic Language

        Route::group(['prefix' => 'en'], function () {


            Route::get('test_mail', 'Front\PageController@testEmailTemplates');

            Route::get('terms-and-conditions', 'Front\PageController@terms_and_conditions');

            Route::get('human-less-instructions', 'Front\PageController@human_less_instructions');

            Route::get('safe-road-api', 'Front\PageController@safe_road_api');


            Route::post('news-letter', 'Front\PageController@newsLetter');


            Route::get('print-booking/{id}', 'Front\PageController@generatePdf');

            Route::get('print-invoice/{id}', 'Front\PageController@print_invoice');

            Route::get('print-lease-invoice/{id}', 'Front\PageController@print_lease_invoice');


            Route::post('getSpecialCarDesc', 'Front\PageController@getSpecialCarDesc');


            Route::get('set_lang_session', 'Front\PageController@set_lang_session');

            Route::get('set_mobile_full_version', 'Front\PageController@set_mobile_full_version');

            Route::get('loyalty', 'Front\PageController@loyalty');

            Route::get('location', 'Front\PageController@location');

            Route::get('about-us', 'Front\PageController@about_us');

            Route::get('change-points', 'Front\PageController@change_points');

            Route::get('survey', 'Front\PageController@survey');

            Route::match(['get', 'post'], 'checkIfSurveyPendingToFill', 'Front\PageController@checkIfSurveyPendingToFill');

            Route::match(['get', 'post'], 'skipSurvey', 'Front\PageController@skipSurvey');

            Route::get('fleet/{category_name?}', 'Front\PageController@fleet')->name('fleet-ar');


// fleet load more pagination

            Route::post('fleetPagination', 'Front\PageController@getFleetPagination');

            Route::post('searchCarsPagination', 'Front\PageController@getSearchResultPagination');

            Route::get('getServerTime', 'Front\PageController@getServerTime');

            Route::get('share/{id}/{city?}/{branch?}', 'Front\PageController@printMapPopups');


            Route::get('services', 'Front\PageController@services');

            Route::get('program-rewards', 'Front\PageController@program_awards');

            Route::get('lease', 'Front\PageController@corporate_sales');

            Route::get('book-car', 'Front\PageController@book_a_car');

            Route::get('news', 'Front\PageController@news');

            Route::get('faqs', 'Front\PageController@faqs');

            Route::get('careers', 'Front\PageController@careers');

            Route::get('contact-us', 'Front\PageController@contact_us');

            Route::post('login', 'Front\PageController@login');

            Route::post('loginOnPayment', 'Front\PageController@loginOnPayment');

            Route::get('register', 'Front\PageController@register');

            Route::get('create-ind-user', 'Front\PageController@new_user_ind');

            Route::get('create-ind-login', 'Front\PageController@create_login_ind');

            Route::post('new_ind_user_signup', 'Front\PageController@new_ind_user_signup');

            Route::get('create-corp-user', 'Front\PageController@new_user_corp');

            Route::get('create-corp-login', 'Front\PageController@create_login_corp');

            Route::post('new_corp_user_signup', 'Front\PageController@new_corp_user_signup');

            Route::get('savePdf', 'Front\PageController@savePdf');

            Route::get('downloadPdf', 'Front\PageController@downloadPdf');

            Route::get('openPdf', 'Front\PageController@openPdf');

            Route::get('my-profile', 'Front\PageController@profile');

            Route::get('edit-my-profile', 'Front\PageController@edit_profile');

            Route::get('refer_and_earn', 'Front\PageController@refer_and_earn');

            Route::get('get-meta-data/{page}', 'Admin\MetaPageController@getMetaData')->name('getMetaData');


            Route::post('forgot_password', 'Front\PageController@forgot_password');

            Route::get('reset-password', 'Front\PageController@reset_password');

            Route::post('change_password', 'Front\PageController@change_password');

            Route::post('update_profile', 'Front\PageController@update_profile');

            Route::post('update_corporate_profile', 'Front\PageController@update_corporate_profile');

            Route::get('logout', 'Front\PageController@logout');

            Route::post('search-results', 'Front\PageController@main_search');

            Route::get('search-results', 'Front\PageController@main_search');

            Route::post('main_search_with_filter', 'Front\PageController@main_search_with_filter');

            Route::post('filterSellingCars', 'Front\PageController@filterSellingCars');

            Route::post('interestedInCar', 'Front\PageController@interestedInCar');


// kashif work 7 april 2017

            Route::post('fleet_serch_cat_filter', 'Front\PageController@fleet_serch_cat_filter');

            Route::post('search_cars_with_all_fields', 'Front\PageController@search_cars_with_all_fields');

            Route::get('fleet/booking/{id}', 'Front\PageController@fleetBooking');

//====


            Route::get('extra-services', 'Front\PageController@extra_services');

            Route::get('book-car', 'Front\PageController@book_car');

            Route::post('cancelBooking', 'Front\PageController@cancelBooking');


// kashif work start 3-30-2017

            Route::post('saveContactUs', 'Front\PageController@submitContactFrm');

            Route::post('saveChangePoints', 'Front\PageController@submitChangePoints');

            Route::post('saveCareerForm', 'Front\PageController@saveCareerForm');


            Route::post('redirectToExtraServicesPage', 'Front\PageController@redirectToExtraServicesPage');

            Route::post('payment', 'Front\PageController@payment');

            Route::get('payment', 'Front\PageController@payment');

            Route::post('book_now', 'Front\PageController@book_now');

            Route::post('book_now_for_corporate', 'Front\PageController@book_now_for_corporate');

            //Route::get('cc-payment', 'Front\PageController@cc_payment');

            Route::match(['get', 'post'], 'checkSessionBeforePayment', 'Front\PageController@checkSessionBeforePayment');

            Route::match(['get', 'post'], 'cc-payment', 'Front\PageController@cc_payment');

            Route::match(['get', 'post'], 'mada-payment', 'Front\PageController@mada_payment');

            Route::get('stsTransactionInquiry', 'Front\PageController@stsTransactionInquiry');

            Route::get('booking-done', 'Front\PageController@booking_done');

            Route::get('booking-detail/{id}', 'Front\PageController@booking_detail');

            Route::get('get-car/{id}', 'Front\HumanLessController@get_car');

            //Route::post('end-trip/{id}','Front\HumanLessController@end_trip');

            Route::match(['get', 'post'], 'end-trip/{id}', 'Front\HumanLessController@end_trip');

            Route::post('checkIfBookingExistWithRefNo', 'Front\PageController@checkIfBookingExistWithRefNo');

            Route::post('checkIfBookingExistWithRefNoStep2', 'Front\PageController@checkIfBookingExistWithRefNoStep2');

            Route::get('manage-booking/{id}', 'Front\PageController@manage_booking');

            //Route::post('booked', 'Front\PageController@booked');
            Route::match(['get', 'post'], 'booked', 'Front\PageController@booked');

            Route::get('verifySTSPayment', 'Front\PageController@verifySTSPayment');

            Route::post('save_extra_infor_after_reservation', 'Front\PageController@save_extra_infor_after_reservation');

            Route::post('create_login_find_user', 'Front\PageController@create_login_find_user');

            Route::get('my-bookings', 'Front\PageController@my_booking');

            Route::match(['get', 'post'], 'edit-booking/{booking_id?}', 'Front\PageController@edit_booking');

            Route::get('my-invoices', 'Front\PageController@my_invoices');

            Route::get('my-invoices-paginate', 'Front\PageController@my_invoices_paginate');

            Route::get('lease-invoices', 'Front\PageController@lease_invoices');

            Route::get('my-history', 'Front\PageController@my_history');

            Route::get('create-login', 'Front\PageController@create_login_step_2');


            Route::get('resendVerifyCode', 'Front\PageController@resendVerifyCode');

            Route::get('verifySmsCheck', 'Front\PageController@verifySmsCheck');

            Route::get('cancelBookingVerification', 'Front\PageController@cancelBookingVerification');

            Route::post('sendEditBookingOTP', 'Front\PageController@sendEditBookingOTP');

            Route::match(['get', 'post'], 'applyCoupon', 'Front\PageController@applyCoupon');

            Route::post('back', 'Front\PageController@removeBooking');

            Route::post('my_bookings_filter', 'Front\PageController@my_bookings_filter');

            Route::post('checkIfIdNoExist', 'Front\PageController@checkIfIdNoExist');

            Route::post('validateEmailAndIdNo', 'Front\PageController@validateEmailAndIdNo');

            Route::post('validateLoginEmail', 'Front\PageController@validateLoginEmail');

            Route::get('something-went-wrong', 'Front\PageController@something_went_wrong');

            Route::get('offer', 'Front\PageController@offer');

            Route::get('offer-detail/{id}', 'Front\PageController@offerDetail');

            Route::post('redirectToBookingPage', 'Front\PageController@redirectToBookingPage');

            Route::match(['get', 'post'], 'paytabsIPN', 'Front\PageController@paytabsIPN');

            Route::match(['get', 'post'], 'stsIPN', 'Front\PageController@stsIPN');

            Route::match(['get', 'post'], 'checkIfUserBlacklistedOrSimahBlock', 'Front\PageController@checkIfUserBlacklistedOrSimahBlock');

            Route::post('searchAreaBranchFilter', 'Front\PageController@searchAreaBranchFilter');

            Route::post('validateSaudiID', 'Front\PageController@validateSaudiID');

            Route::get('getLocationAndCheck', 'Front\PageController@getLocationAndCheck');

            Route::get('offers', 'Front\PageController@offers');

            Route::get('manageBookings', 'Front\PageController@manageBookings');
            Route::post('getBranchesByCity', 'Front\PageController@getBranchesByCity');
            Route::match(['get', 'post'], 'returnFromSadad', 'Front\PageController@returnFromSadad');

            Route::post('getCategoriesForEmoji', 'Front\PageController@getCategoriesForEmoji');

            Route::post('getOptionsForCategory', 'Front\PageController@getOptionsForCategory');

            Route::post('saveSurveyFeedback', 'Front\PageController@saveSurveyFeedback');

            Route::get('apps', 'Front\PageController@apps');

            Route::get('sta', 'Front\PageController@sta');

            Route::get('leasing', 'Front\PageController@mada');

            Route::get('Leasing', 'Front\PageController@mada');

            Route::get('oasis-survey', 'Front\PageController@oasisSurvey');

            Route::get('oasis-survey-new', 'Front\PageController@oasisSurveyNew');

            Route::get('limousine-oasis-survey', 'Front\PageController@oasisSurveyForLimousine');

            Route::post('saveOasisSurveyFeedback', 'Front\PageController@saveOasisSurveyFeedback');

            Route::post('saveOasisSurveyFeedbackForLimousine', 'Front\PageController@saveOasisSurveyFeedbackForLimousine');

            Route::post('getDriverInfo', 'Front\PageController@getDriverInfo');

            Route::get('car-selling', 'Front\PageController@sell_car');

            Route::post('getMoreSellingCars', 'Front\PageController@getMoreSellingCars');

            //Human less Mode

            Route::get('human-less-acknowledge', 'Front\HumanLessController@humanLessAcknowledge');

            Route::post('getCarPlates', 'Front\HumanLessController@getCarPlates');

            Route::post('getCarInfo', 'Front\HumanLessController@getCarInfo');

            Route::post('openContract', 'Front\HumanLessController@openContract');

            Route::post('issueTammOTP', 'Front\HumanLessController@issueTammOTP');

            Route::post('issueTammAuth', 'Front\HumanLessController@issueTammAuth');

            Route::post('cancelTammAuth', 'Front\HumanLessController@cancelTammAuth');

            Route::post('unlockCar', 'Front\HumanLessController@unlockCar');

            Route::post('changeCar', 'Front\HumanLessController@changeCar');

            Route::post('watermark', 'Front\HumanLessController@watermark')->name('watermark');

            Route::post('watermark_endTrip', 'Front\HumanLessController@watermark_endTrip')->name('watermark_endTrip');

            Route::post('clear-inspection', 'Front\HumanLessController@clearInspection')->name('clear-inspection');

            Route::get('pickup-inspection/{id}', 'Front\HumanLessController@pickUpInspection');

            Route::get('dropoff-inspection/{id}', 'Front\HumanLessController@dropOffInspection');

            Route::get('vehicle-inspection/{id}', 'Front\HumanLessController@vehicleInspection');

            Route::post('getContractBalance', 'Front\HumanLessController@getContractBalance');

            Route::post('addPayment', 'Front\HumanLessController@addPayment');

            Route::post('closeContract', 'Front\HumanLessController@closeContract');

            Route::post('sendDummyEmail', 'Front\HumanLessController@sendDummyEmail');


            // URLs for redeem offer module

            Route::post('calculateRedeemPointsFromAmount', 'Front\PageController@calculateRedeemPointsFromAmount');

            Route::post('calculateRedeemAmountFromPoints', 'Front\PageController@calculateRedeemAmountFromPoints');


            Route::get('send_registration_link_to_walkin_customers', 'Front\PageController@send_registration_link_to_walkin_customers');

            Route::get('walkin-signup', 'Front\PageController@walkin_customer_signup');

            Route::get('confirm-booking', 'Front\PageController@confirm_walkin_booking');

            Route::post('saveWalkinBookingOnConfirm', 'Front\PageController@saveWalkinBookingOnConfirm');

            Route::post('fetch_nearest_delivery_branch', 'Front\PageController@fetch_nearest_delivery_branch');

            /*corporate-sales*/

            Route::post('interestedInCorporateSales', 'Front\PageController@interestedInCorporateSales');

            Route::post('resendPayLaterInvoice', 'Front\PageController@resendPayLaterInvoice');

            Route::post('corporateCompany', 'Front\PageController@corporateCompany');

            Route::match(['get', 'post'], 'refunds', 'Front\PageController@refunds');
            Route::match(['get', 'post'], 'guar-refunds/{contract_number?}', 'Front\PageController@guar_refunds');

            /*Qitaf APIs*/
            Route::post('qitafSendOTP', 'Front\PageController@qitafSendOTP');
            Route::post('qitafRedeem', 'Front\PageController@qitafRedeem');

            /*Niqaty APIs*/
            Route::post('get_niqaty_redeem_options', 'Front\PageController@get_niqaty_redeem_options');
            Route::post('authorize_niqaty_redeem_request', 'Front\PageController@authorize_niqaty_redeem_request');
            Route::post('confirm_niqaty_redeem_request', 'Front\PageController@confirm_niqaty_redeem_request');

            /*Mobile services for Mokafaa*/
            Route::post('mokafaa_get_access_token', 'Front\PageController@mokafaa_get_access_token');
            Route::post('mokafaa_send_otp', 'Front\PageController@mokafaa_send_otp');
            Route::post('mokafaa_initiate_redeem_request', 'Front\PageController@mokafaa_initiate_redeem_request');

            /*Mobile services for ANB*/
            Route::post('anb_get_access_token', 'Front\PageController@anb_get_access_token');
            Route::post('anb_send_otp', 'Front\PageController@anb_send_otp');
            Route::post('anb_initiate_redeem_request', 'Front\PageController@anb_initiate_redeem_request');

            Route::match(['get', 'post'], 'hp_check_payment_status', 'Front\PageController@hp_check_payment_status');

            Route::match(['get', 'post'], 'add-payment', 'Front\PageController@add_payment');

            Route::get('verify/{type}/{user_id}', 'Front\PageController@verify');

            Route::match(['get', 'post'], 'applyCouponForAddPayment', 'Front\PageController@applyCouponForAddPayment');

        });


// URLs for cronjobs

        Route::get('cronjob/setDataCronJob', 'Front\ApiController@setDataCronJob');

        Route::get('cronjob/getBookingStatusCronJob', 'Front\ApiController@getBookingStatusCronJob');

        Route::get('cronjob/setCancelledBookingCollectionCronJob', 'Front\ApiController@setCancelledBookingCollectionCronJob');

        Route::get('cronjob/loyaltySyncCronJob', 'Front\ApiController@loyaltySyncCronJob');

        Route::get('cronjob/updateStatusToExpiredCronJob', 'Front\ApiController@updateStatusToExpiredCronJob');

        Route::get('cronjob/stsTransactionInquiry', 'Front\PageController@stsTransactionInquiry');

        Route::get('cronjob/stsInvoicesInquiry', 'Front\PageController@stsInvoicesInquiry');

        Route::get('cronjob/reverse_qitaf_for_temp_cancelled_expired_bookings_cronjob', 'Front\PageController@reverse_qitaf_for_temp_cancelled_expired_bookings_cronjob');

        Route::get('cronjob/check_fcm_token_status_cronjob', 'Front\PageController@check_fcm_token_status_cronjob');

        Route::get('cronjob/hp_check_payment_status_cronjob', 'Front\PageController@hp_check_payment_status_cronjob');

        Route::get('cronjob/hb_check_invoice_status_cronjob', 'Front\PageController@hb_check_invoice_status_cronjob');

        Route::get('cronjob/reverse_niqaty_for_temp_cancelled_expired_bookings_cronjob', 'Front\PageController@reverse_niqaty_for_temp_cancelled_expired_bookings_cronjob');

        Route::get('cronjob/sync_booking_added_payments_with_oasis_cronjob', 'Front\PageController@sync_booking_added_payments_with_oasis_cronjob');

        Route::get('cronjob/check_app_popup_promo_codes', 'Front\PageController@check_app_popup_promo_codes');

        Route::get('cronjob/send_booking_email_and_pdf_to_customers', 'Front\PageController@send_booking_email_and_pdf_to_customers');

        Route::get('cronjob/move_bookings_from_main_to_backup_tables', 'Front\PageController@move_bookings_from_main_to_backup_tables');

        Route::get('cronjob/reverse_mokafaa_for_temp_cancelled_expired_bookings_cronjob', 'Front\PageController@reverse_mokafaa_for_temp_cancelled_expired_bookings_cronjob');

        Route::get('cronjob/reverse_anb_for_temp_cancelled_expired_bookings_cronjob', 'Front\PageController@reverse_anb_for_temp_cancelled_expired_bookings_cronjob');

        Route::get('cronjob/subscribe_device_tokens_to_fcm_topic', 'Front\PageController@subscribe_device_tokens_to_fcm_topic');

        Route::group(['middleware' => ['customer']], function () {


            //  Route::get('/', 'Admin\HomeController@index');

        });
    });

});

// End of ForceSSL middleware


/*this route is send custom sms for hitting jsut link

## we want to bypass force ssl so here define with en keyword fro english ##

*/

Route::get('en/custom_sms', 'Front\PageController@send_custom_sms');

/*this route is send custom sms for hitting jsut link*/

Route::get('custom_sms', 'Front\PageController@send_custom_sms');


// URLs for walkin module

Route::match(['get', 'post'], 'en/register_walkin', 'Front\PageController@register_walkin'); // not sure how they will hit the api url


Route::match(['get', 'post'], 'register_walkin', 'Front\PageController@register_walkin'); // not sure how they will hit the api url


// URL for Set Oasis Survey

Route::get('setOasisSurveyPendingToFill', 'Front\PageController@setOasisSurveyPendingToFill');

Route::get('setOasisSurveyPendingToFillLink', 'Front\PageController@setOasisSurveyPendingToFillLink');


// URLs for OASIS pricing APIs

Route::get('api/closeOasisPricing', 'Front\PageController@closeOasisPricing');

Route::get('api/setOasisPricing', 'Front\PageController@setOasisPricing');


// URL for OASIS to update booking_status in booking table

Route::get('api/updateBookingStatus', 'Front\PageController@updateBookingStatus');

// Booking Availability API link
Route::get('api/booking_availability_api', 'Front\PageController@booking_availability_api');

Route::get('api/mark_app_popup_promo_as_used', 'Front\ApiController@mark_app_popup_promo_as_used');

// Human Less Mode URLs for OASIS

Route::get('humanLess_sendAcknowledgeSMS', 'Front\HumanLessController@humanLess_sendAcknowledgeSMS');


//////////////////////*-* Web Services *-*///////////////////////////

Route::group(['middleware' => ['XSSProtection']], function () {

    Route::get('services/test_func', 'Services\ServicesController@test_func');

    Route::get('services/getAllCities', 'Services\ServicesController@getAllCities');

    Route::get('services/getAllBranches', 'Services\ServicesController@getAllBranches');

    Route::get('services/getBranchesBySearch', 'Services\ServicesController@getBranchesBySearch');

    Route::get('services/getSavedBranches', 'Services\ServicesController@getSavedBranches');

    Route::get('services/getCars', 'Services\ServicesController@getCars');

    Route::post('services/login', 'Services\ServicesController@login');

    Route::get('services/getUserInfo', 'Services\ServicesController@getUserInfo');

    Route::post('services/registerNewCustomerAsUser', 'Services\ServicesController@registerNewCustomerAsUser');

    Route::get('services/manageBooking', 'Services\ServicesController@manageBooking');

    Route::get('services/getSingleBookingDetails', 'Services\ServicesController@getSingleBookingDetails');

    Route::get('services/checkIfUserExistByIDno', 'Services\ServicesController@checkIfUserExistByIDno');

    Route::get('services/forgot_password', 'Services\ServicesController@forgot_password');

    Route::get('services/getDropdownsDataForRegistration', 'Services\ServicesController@getDropdownsDataForRegistration');

    Route::get('services/printBooking', 'Services\ServicesController@printBooking');

    Route::get('services/getBookingsForUser', 'Services\ServicesController@getBookingsForUser');

    Route::get('services/getPageContent', 'Services\ServicesController@getPageContent');

    Route::get('services/saveContactUsForm', 'Services\ServicesController@saveContactUsForm');

    Route::get('services/getExtraCharges', 'Services\ServicesController@getExtraCharges');

    Route::get('services/resendVerificationCodeForCreateLogin', 'Services\ServicesController@resendVerificationCodeForCreateLogin');

    Route::get('services/checkIfBookingCancellable', 'Services\ServicesController@checkIfBookingCancellable');

    Route::get('services/sendVerificationCodeForBookingCancellation', 'Services\ServicesController@sendVerificationCodeForBookingCancellation');

    Route::get('services/cancelBooking', 'Services\ServicesController@cancelBooking');

    Route::post('services/updateProfile', 'Services\ServicesController@updateProfile');

    Route::get('services/applyCoupon', 'Services\ServicesController@applyCoupon');

    Route::get('services/book', 'Services\ServicesController@book');

    Route::post('services/getQueryString', 'Services\ServicesController@getQueryString');

    Route::get('services/signup_after_booking', 'Services\ServicesController@signup_after_booking');

    Route::get('services/payWithSadad', 'Services\ServicesController@payWithSadad');

    Route::match(['get', 'post'], 'services/verifyPayment', 'Services\ServicesController@verifyPayment');

    Route::get('services/sadad-confirmation', 'Services\ServicesController@sadad_confirmation');

    Route::get('services/checkIfPointInsideOrOutside', 'Services\ServicesController@checkIfPointInsideOrOutside');

    Route::get('services/checkSurveyPending', 'Services\ServicesController@checkSurveyPending');

    Route::get('services/getOptionsForCategory', 'Services\ServicesController@getOptionsForCategory');

    Route::get('services/saveSurveyFeedback', 'Services\ServicesController@saveSurveyFeedback');

    Route::get('services/skipSurvey', 'Services\ServicesController@skipSurvey');

    Route::get('services/saveTokenForDevice', 'Services\ServicesController@saveTokenForDevice');

    Route::get('services/verifyUserForCampaign', 'Services\ServicesController@verifyUserForCampaign');

    Route::get('services/saveCampaignData', 'Services\ServicesController@saveCampaignData');

    Route::get('services/updateCorporateProfile', 'Services\ServicesController@updateCorporateProfile');

    Route::get('services/getDriverInfo', 'Services\ServicesController@getDriverInfo');

    Route::get('services/bookForCorporate', 'Services\ServicesController@bookForCorporate');

    Route::get('services/checkIfRedeemable', 'Services\ServicesController@checkIfRedeemable');

    Route::get('services/calculateRedeemPointsFromAmount', 'Services\ServicesController@calculateRedeemPointsFromAmount');

    Route::get('services/calculateRedeemAmountFromPoints', 'Services\ServicesController@calculateRedeemAmountFromPoints');

    Route::post('services/stsPaymentSuccess', 'Services\ServicesController@stsPaymentSuccess');

    Route::get('services/updateStsAttempts', 'Services\ServicesController@updateStsAttempts');

    Route::match(['get', 'post'], 'services/stsTransactionLog', 'Services\ServicesController@stsTransactionLog');

    Route::get('services/checkException', 'Services\ServicesController@checkException');

    /*Mobile services for Qitaf*/
    Route::get('services/qitafSendOTP', 'Services\ServicesController@qitafSendOTP');
    Route::get('services/qitafRedeem', 'Services\ServicesController@qitafRedeem');
    Route::get('services/clearQitafAfterBookingConfirmed', 'Services\ServicesController@clearQitafAfterBookingConfirmed');

    /*Mobile services for Niqaty*/
    Route::get('services/get_niqaty_redeem_options', 'Services\ServicesController@get_niqaty_redeem_options');
    Route::get('services/authorize_niqaty_redeem_request', 'Services\ServicesController@authorize_niqaty_redeem_request');
    Route::get('services/confirm_niqaty_redeem_request', 'Services\ServicesController@confirm_niqaty_redeem_request');
    Route::get('services/clear_niqaty_after_booking_confirmed', 'Services\ServicesController@clear_niqaty_after_booking_confirmed');

    /*Mobile services for Mokafaa*/
    Route::get('services/mokafaa_get_access_token', 'Services\ServicesController@mokafaa_get_access_token');
    Route::get('services/mokafaa_send_otp', 'Services\ServicesController@mokafaa_send_otp');
    Route::get('services/mokafaa_initiate_redeem_request', 'Services\ServicesController@mokafaa_initiate_redeem_request');
    Route::get('services/mokafaa_clear_after_booking_confirmed', 'Services\ServicesController@mokafaa_clear_after_booking_confirmed');

    /*Mobile services for ANB*/
    Route::get('services/anb_get_access_token', 'Services\ServicesController@anb_get_access_token');
    Route::get('services/anb_send_otp', 'Services\ServicesController@anb_send_otp');
    Route::get('services/anb_initiate_redeem_request', 'Services\ServicesController@anb_initiate_redeem_request');
    Route::get('services/anb_clear_after_booking_confirmed', 'Services\ServicesController@anb_clear_after_booking_confirmed');

    /*Mobile services for Hyper Pay*/
    Route::get('services/hp_generate_checkout_id', 'Services\ServicesController@hp_generate_checkout_id');
    Route::get('services/hp_check_payment_status', 'Services\ServicesController@hp_check_payment_status');

    Route::get('services/booking_cancellation_reasons', 'Services\ServicesController@booking_cancellation_reasons');
    Route::get('services/get_countries', 'Services\ServicesController@get_countries');
    Route::get('services/loyalty_programs', 'Services\ServicesController@loyalty_programs');
    Route::get('services/edit_booking', 'Services\ServicesController@edit_booking');
    Route::get('services/check_is_edit_allowed_in_booking', 'Services\ServicesController@check_is_edit_allowed_in_booking');

    Route::get('services/fetch_nearest_delivery_branch', 'Services\ServicesController@fetch_nearest_delivery_branch');
    Route::get('services/mark_account_as_deleted', 'Services\ServicesController@mark_account_as_deleted');
    Route::get('services/add_payment_for_booking', 'Services\ServicesController@add_payment_for_booking');
    Route::get('services/hp_generate_checkout_id_for_add_payment', 'Services\ServicesController@hp_generate_checkout_id_for_add_payment');
    Route::get('services/hp_check_payment_status_for_add_payment', 'Services\ServicesController@hp_check_payment_status_for_add_payment');

    Route::get('services/get_promo_code_for_app_popup', 'Services\ServicesController@get_promo_code_for_app_popup');

    Route::get('services/refer_and_earn', 'Services\ServicesController@refer_and_earn');

    Route::get('services/apply_coupon_for_add_payment', 'Services\ServicesController@apply_coupon_for_add_payment');

    Route::get('services/get_push_notifications', 'Services\ServicesController@get_push_notifications');
    Route::get('services/get_home_slider_images', 'Services\ServicesController@get_home_slider_images');

});

include 'ssl-pinning-routes.php';

Route::post('setOasisCustomer', 'Front\PageController@setOasisCustomer');

Route::post('update_customer', 'Front\PageController@update_customer');

Route::get('setOasisCustomer', 'Front\PageController@setOasisCustomer');

Route::get('update_customer', 'Front\PageController@update_customer');

Route::get('testPDF', 'Front\PageController@testPDF');

Route::get('api/create_corporate_invoice', 'Front\PageController@create_corporate_invoice_api');
Route::get('api/update_corporate_invoice', 'Front\PageController@update_corporate_invoice_api');

Route::get('api/login', 'Front\PageController@login_api');

Route::get('api/update_delivery_booking_status', 'Front\PageController@update_delivery_booking_status');

Route::get('get_branch_delivery_coordinates', 'Front\PageController@get_branch_delivery_coordinates');

Route::get('api/set_utilization', 'Front\PageController@set_utilization');

Route::get('setOasisLimoSurveyPendingToFillLink', 'Front\PageController@setOasisLimoSurveyPendingToFillLink');

Route::get('carSellingFormEventForGtag', 'Front\PageController@carSellingFormEventForGtag');