<?php
Route::group(['middleware' => ['XSSProtection']], function () {

    Route::get('ssl-pinning/services/test_func', 'Services\SSLPinningServicesController@test_func');

    Route::get('ssl-pinning/services/getAllCities', 'Services\SSLPinningServicesController@getAllCities');

    Route::get('ssl-pinning/services/getAllBranches', 'Services\SSLPinningServicesController@getAllBranches');

    Route::get('ssl-pinning/services/getBranchesBySearch', 'Services\SSLPinningServicesController@getBranchesBySearch');

    Route::get('ssl-pinning/services/getSavedBranches', 'Services\SSLPinningServicesController@getSavedBranches');

    Route::get('ssl-pinning/services/getCars', 'Services\SSLPinningServicesController@getCars');

    Route::post('ssl-pinning/services/login', 'Services\SSLPinningServicesController@login');

    Route::get('ssl-pinning/services/getUserInfo', 'Services\SSLPinningServicesController@getUserInfo');

    Route::post('ssl-pinning/services/registerNewCustomerAsUser', 'Services\SSLPinningServicesController@registerNewCustomerAsUser');

    Route::get('ssl-pinning/services/manageBooking', 'Services\SSLPinningServicesController@manageBooking');

    Route::get('ssl-pinning/services/getSingleBookingDetails', 'Services\SSLPinningServicesController@getSingleBookingDetails');

    Route::get('ssl-pinning/services/checkIfUserExistByIDno', 'Services\SSLPinningServicesController@checkIfUserExistByIDno');

    Route::get('ssl-pinning/services/forgot_password', 'Services\SSLPinningServicesController@forgot_password');

    Route::get('ssl-pinning/services/getDropdownsDataForRegistration', 'Services\SSLPinningServicesController@getDropdownsDataForRegistration');

    Route::get('ssl-pinning/services/printBooking', 'Services\SSLPinningServicesController@printBooking');

    Route::get('ssl-pinning/services/getBookingsForUser', 'Services\SSLPinningServicesController@getBookingsForUser');

    Route::get('ssl-pinning/services/getPageContent', 'Services\SSLPinningServicesController@getPageContent');

    Route::get('ssl-pinning/services/saveContactUsForm', 'Services\SSLPinningServicesController@saveContactUsForm');

    Route::get('ssl-pinning/services/getExtraCharges', 'Services\SSLPinningServicesController@getExtraCharges');

    Route::get('ssl-pinning/services/resendVerificationCodeForCreateLogin', 'Services\SSLPinningServicesController@resendVerificationCodeForCreateLogin');

    Route::get('ssl-pinning/services/checkIfBookingCancellable', 'Services\SSLPinningServicesController@checkIfBookingCancellable');

    Route::get('ssl-pinning/services/sendVerificationCodeForBookingCancellation', 'Services\SSLPinningServicesController@sendVerificationCodeForBookingCancellation');

    Route::get('ssl-pinning/services/cancelBooking', 'Services\SSLPinningServicesController@cancelBooking');

    Route::post('ssl-pinning/services/updateProfile', 'Services\SSLPinningServicesController@updateProfile');

    Route::get('ssl-pinning/services/applyCoupon', 'Services\SSLPinningServicesController@applyCoupon');

    Route::get('ssl-pinning/services/book', 'Services\SSLPinningServicesController@book');

    Route::post('ssl-pinning/services/getQueryString', 'Services\SSLPinningServicesController@getQueryString');

    Route::get('ssl-pinning/services/signup_after_booking', 'Services\SSLPinningServicesController@signup_after_booking');

    Route::get('ssl-pinning/services/payWithSadad', 'Services\SSLPinningServicesController@payWithSadad');

    Route::match(['get', 'post'], 'ssl-pinning/services/verifyPayment', 'Services\SSLPinningServicesController@verifyPayment');

    Route::get('ssl-pinning/services/sadad-confirmation', 'Services\SSLPinningServicesController@sadad_confirmation');

    Route::get('ssl-pinning/services/checkIfPointInsideOrOutside', 'Services\SSLPinningServicesController@checkIfPointInsideOrOutside');

    Route::get('ssl-pinning/services/checkSurveyPending', 'Services\SSLPinningServicesController@checkSurveyPending');

    Route::get('ssl-pinning/services/getOptionsForCategory', 'Services\SSLPinningServicesController@getOptionsForCategory');

    Route::get('ssl-pinning/services/saveSurveyFeedback', 'Services\SSLPinningServicesController@saveSurveyFeedback');

    Route::get('ssl-pinning/services/skipSurvey', 'Services\SSLPinningServicesController@skipSurvey');

    Route::get('ssl-pinning/services/saveTokenForDevice', 'Services\SSLPinningServicesController@saveTokenForDevice');

    Route::get('ssl-pinning/services/verifyUserForCampaign', 'Services\SSLPinningServicesController@verifyUserForCampaign');

    Route::get('ssl-pinning/services/saveCampaignData', 'Services\SSLPinningServicesController@saveCampaignData');

    Route::get('ssl-pinning/services/updateCorporateProfile', 'Services\SSLPinningServicesController@updateCorporateProfile');

    Route::get('ssl-pinning/services/getDriverInfo', 'Services\SSLPinningServicesController@getDriverInfo');

    Route::get('ssl-pinning/services/bookForCorporate', 'Services\SSLPinningServicesController@bookForCorporate');

    Route::get('ssl-pinning/services/checkIfRedeemable', 'Services\SSLPinningServicesController@checkIfRedeemable');

    Route::get('ssl-pinning/services/calculateRedeemPointsFromAmount', 'Services\SSLPinningServicesController@calculateRedeemPointsFromAmount');

    Route::get('ssl-pinning/services/calculateRedeemAmountFromPoints', 'Services\SSLPinningServicesController@calculateRedeemAmountFromPoints');

    Route::post('ssl-pinning/services/stsPaymentSuccess', 'Services\SSLPinningServicesController@stsPaymentSuccess');

    Route::get('ssl-pinning/services/updateStsAttempts', 'Services\SSLPinningServicesController@updateStsAttempts');

    Route::match(['get', 'post'], 'ssl-pinning/services/stsTransactionLog', 'Services\SSLPinningServicesController@stsTransactionLog');

    Route::get('ssl-pinning/services/checkException', 'Services\SSLPinningServicesController@checkException');

    /*Mobile services for Qitaf*/
    Route::get('ssl-pinning/services/qitafSendOTP', 'Services\SSLPinningServicesController@qitafSendOTP');
    Route::get('ssl-pinning/services/qitafRedeem', 'Services\SSLPinningServicesController@qitafRedeem');
    Route::get('ssl-pinning/services/clearQitafAfterBookingConfirmed', 'Services\SSLPinningServicesController@clearQitafAfterBookingConfirmed');

    /*Mobile services for Niqaty*/
    Route::get('ssl-pinning/services/get_niqaty_redeem_options', 'Services\SSLPinningServicesController@get_niqaty_redeem_options');
    Route::get('ssl-pinning/services/authorize_niqaty_redeem_request', 'Services\SSLPinningServicesController@authorize_niqaty_redeem_request');
    Route::get('ssl-pinning/services/confirm_niqaty_redeem_request', 'Services\SSLPinningServicesController@confirm_niqaty_redeem_request');
    Route::get('ssl-pinning/services/clear_niqaty_after_booking_confirmed', 'Services\SSLPinningServicesController@clear_niqaty_after_booking_confirmed');

    /*Mobile services for Mokafaa*/
    Route::get('ssl-pinning/services/mokafaa_get_access_token', 'Services\SSLPinningServicesController@mokafaa_get_access_token');
    Route::get('ssl-pinning/services/mokafaa_send_otp', 'Services\SSLPinningServicesController@mokafaa_send_otp');
    Route::get('ssl-pinning/services/mokafaa_initiate_redeem_request', 'Services\SSLPinningServicesController@mokafaa_initiate_redeem_request');
    Route::get('ssl-pinning/services/mokafaa_clear_after_booking_confirmed', 'Services\SSLPinningServicesController@mokafaa_clear_after_booking_confirmed');

    /*Mobile services for ANB*/
    Route::get('ssl-pinning/services/anb_get_access_token', 'Services\SSLPinningServicesController@anb_get_access_token');
    Route::get('ssl-pinning/services/anb_send_otp', 'Services\SSLPinningServicesController@anb_send_otp');
    Route::get('ssl-pinning/services/anb_initiate_redeem_request', 'Services\SSLPinningServicesController@anb_initiate_redeem_request');
    Route::get('ssl-pinning/services/anb_clear_after_booking_confirmed', 'Services\SSLPinningServicesController@anb_clear_after_booking_confirmed');

    /*Mobile services for Hyper Pay*/
    Route::get('ssl-pinning/services/hp_generate_checkout_id', 'Services\SSLPinningServicesController@hp_generate_checkout_id');
    Route::get('ssl-pinning/services/hp_check_payment_status', 'Services\SSLPinningServicesController@hp_check_payment_status');

    Route::get('ssl-pinning/services/booking_cancellation_reasons', 'Services\SSLPinningServicesController@booking_cancellation_reasons');
    Route::get('ssl-pinning/services/get_countries', 'Services\SSLPinningServicesController@get_countries');
    Route::get('ssl-pinning/services/loyalty_programs', 'Services\SSLPinningServicesController@loyalty_programs');
    Route::get('ssl-pinning/services/edit_booking', 'Services\SSLPinningServicesController@edit_booking');
    Route::get('ssl-pinning/services/check_is_edit_allowed_in_booking', 'Services\SSLPinningServicesController@check_is_edit_allowed_in_booking');

    Route::get('ssl-pinning/services/fetch_nearest_delivery_branch', 'Services\SSLPinningServicesController@fetch_nearest_delivery_branch');
    Route::get('ssl-pinning/services/mark_account_as_deleted', 'Services\SSLPinningServicesController@mark_account_as_deleted');
    Route::get('ssl-pinning/services/add_payment_for_booking', 'Services\SSLPinningServicesController@add_payment_for_booking');
    Route::get('ssl-pinning/services/hp_generate_checkout_id_for_add_payment', 'Services\SSLPinningServicesController@hp_generate_checkout_id_for_add_payment');
    Route::get('ssl-pinning/services/hp_check_payment_status_for_add_payment', 'Services\SSLPinningServicesController@hp_check_payment_status_for_add_payment');

    Route::get('ssl-pinning/services/get_promo_code_for_app_popup', 'Services\SSLPinningServicesController@get_promo_code_for_app_popup');

    Route::get('ssl-pinning/services/refer_and_earn', 'Services\SSLPinningServicesController@refer_and_earn');

    Route::get('ssl-pinning/services/apply_coupon_for_add_payment', 'Services\SSLPinningServicesController@apply_coupon_for_add_payment');

    Route::get('ssl-pinning/services/get_push_notifications', 'Services\SSLPinningServicesController@get_push_notifications');
    Route::get('ssl-pinning/services/get_home_slider_images', 'Services\SSLPinningServicesController@get_home_slider_images');

});