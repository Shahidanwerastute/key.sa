<?php $getSegment = $lang == 'eng' ? Request::segment(2) : Request::segment(1);
if(isset($_REQUEST['mobile']) || $getSegment == 'mada-payment'){ ?>
<footer></footer>
<?php }else{ ?>
<footer xmlns="http://www.w3.org/1999/html">
    <?php
    $sessionVals = Session::get('search_data');
    $site_settings = custom::site_settings();
    $survey_mode = $site_settings->survey_mode;

    $api = custom::api_settings();

    ?>


    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#cancelBookingForm"
            id="cancelBookingPopup"></button>
    <!-- Modal FOr SMS API Verification -->
    <div class="modal fade" id="cancelBookingForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-mobile modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h4 class="modal-title" id="myModalLabel"><?php echo($lang == 'eng' ? 'Verify!' : 'تحقق!'); ?></h4>
                    <form class="cancelBookingverification" action="<?php echo $lang_base_url; ?>/create-login"
                          method="get" onsubmit="return false;">
                        <p class="cancelSmsVerification responseMsg"></p>
                        <input type="text" name="verification_code"
                               placeholder="<?php echo($lang == 'eng' ? 'ENTER VERIFICATION CODE' : 'ادخل رقم التحقق'); ?>"/>
                        <div class="twoBtnEd resendLink">
                            <input type="submit" class="redishButtonRound"
                                   value="<?php echo($lang == 'eng' ? 'VERIFY' : 'تحقق'); ?>"/>
                            <span>
                                <?php echo($lang == 'eng' ? 'Didn\'t receive code?' : 'لم يصلك الرمز؟'); ?>
                                <br/>
                                <a href="javascript:void(0);"
                                   class="resendVerifyCode"><?php echo($lang == 'eng' ? 'Resend' : 'إعادة إرسال'); ?></a>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <!-- Modal For Edit Booking OTP Verification -->
        <div class="modal fade" id="editBookingForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-mobile modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h4 class="modal-title" id="myModalLabel"><?php echo($lang == 'eng' ? 'Verify!' : 'تحقق!'); ?></h4>
                        <form class="editBookingverification" action="<?php echo $lang_base_url; ?>/create-login"
                              method="get" onsubmit="return false;">
                            <p class="editSmsVerification responseMsg"></p>
                            <input type="text" name="verification_code"
                                   placeholder="<?php echo($lang == 'eng' ? 'ENTER VERIFICATION CODE' : 'ادخل رقم التحقق'); ?>"/>
                            <div class="twoBtnEd resendLink">
                                <input type="submit" class="redishButtonRound"
                                       value="<?php echo($lang == 'eng' ? 'VERIFY' : 'تحقق'); ?>"/>
                                <span>
                                <?php echo($lang == 'eng' ? 'Didn\'t receive code?' : 'لم يصلك الرمز؟'); ?>
                                <br/>
                                <a href="javascript:void(0);"
                                   class="resendVerifyCode"><?php echo($lang == 'eng' ? 'Resend' : 'إعادة إرسال'); ?></a>
                            </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    {{--corporate saels--}}
    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#interestedCorporateSalesModal"
            id="interestedCorporateSales"></button>
    <div class="modal fade" id="interestedCorporateSalesModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title"
                        id="myModalLabel"><?php echo($lang == 'eng' ? 'Send Contact Request' : 'إرسال طلب الاتصال'); ?></h4>
                </div>
                <style>
                    body.arb #interestedCorporateSalesModal input[type=radio] {
                        visibility: hidden;
                    }

                    body.arb #interestedCorporateSalesModal input[type=radio]:not(old) + label,
                    body.arb #interestedCorporateSalesModal input[type=radio]:not(old) + label {
                        background-position: right 2px;
                        padding-left: 0;
                        padding-right: 28px;
                    }

                    body.arb #interestedCorporateSalesModal input[type=radio]:checked:not(old) + label,
                    body.arb #interestedCorporateSalesModal input[type=radio]:checked:not(old) + label {
                        background-position: right -46px;
                    }
                </style>
                <div class="modal-body">
                    <form action="<?php echo $lang_base_url; ?>/interestedInCorporateSales" method="post"
                          class="popFormStl interestedInCorpSales" onsubmit="return false;">
                        <p><?php echo($lang == 'eng' ? 'Please fill in your details below so we can contact you.' : 'يرجى ملء التفاصيل الخاصة بك أدناه حتى نتمكن من الاتصال بك'); ?></p>
                        <input type="text" placeholder="<?php echo($lang == 'eng' ? 'Full Name' : 'الاسم الكامل'); ?>"
                               name="name" class="required"/>
                        <input type="text" class="phone-popup number required" placeholder="@lang('labels.mobile')"/>
                        <input type="hidden" name="mobile" class="intTelNo">
                        <input type="text" class="phone-popup number required" data-second="second"
                               placeholder="@lang('labels.phone')"/>
                        <input type="hidden" name="phone" class="intTelNo2">
                        <input type="email" placeholder="@lang('labels.email')" name="email" class="required"/>
                        <input type="text" placeholder="@lang('labels.company')" name="company"/>
                        <input type="text" placeholder="@lang('labels.address')" name="address" class="required"/>
                        <input type="text" placeholder="@lang('labels.designation')" name="designation"/>
                        <textarea name="message" placeholder="@lang('labels.message')" class="required"></textarea>
                        <label style="color: #858585;"><?php echo $lang == 'eng' ? 'How you would like to be contacted' : 'كيف تود الاتصال بك'; ?></label>
                        <br>
                        <div style="display: inline-block; vertical-align: top; position: relative;">
                            <input id="edPhoneK" type="radio" name="contact_type" value="phone" checked="checked">
                            <label for="edPhoneK"
                                   style="color: #858585;"><?php echo $lang == 'eng' ? 'BY PHONE' : 'بواسطة الهاتف'; ?></label>
                        </div>
                        <div style="display: inline-block; vertical-align: top; position: relative;">
                            <input id="edEmailK" type="radio" name="contact_type" value="email">
                            <label for="edEmailK"
                                   style="color: #858585;"><?php echo $lang == 'eng' ? 'BY EMAIL' : 'بواسطة البريد الالكتروني'; ?></label>
                        </div>

                        <div class="g-recaptcha" data-sitekey="<?php echo $api->captcha_site_key;?>"></div>
                        <br>
                        <div class="BtnEd">
                            <input type="submit" class="redishButtonRound"
                                   value="<?php echo($lang == 'eng' ? 'Submit' : 'إرسال'); ?>"/>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--end corporate sales--}}

<!--Modal for interested in car selling-->
    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#interestedInCar" id="openInterestedInCar"></button>
    <div class="modal fade" id="interestedInCar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title"
                        id="myModalLabel"><?php echo($lang == 'eng' ? 'Send Contact Request' : 'إرسال طلب الاتصال'); ?></h4>
                </div>
                <div class="modal-body">
                    <form action="<?php echo $lang_base_url; ?>/interestedInCar" method="post"
                          class="popFormStl interestedInCarForm" onsubmit="return false;">
                        <p><?php echo($lang == 'eng' ? 'Please fill in your details below so we can contact you.' : 'يرجى ملء التفاصيل الخاصة بك أدناه حتى نتمكن من الاتصال بك'); ?></p>
                        <input type="text" placeholder="<?php echo($lang == 'eng' ? 'Full Name' : 'الاسم الكامل'); ?>"
                               name="name" class="required"/>
                        <input type="text" class="phone-popup number required"/>
                        <input type="hidden" name="mobile_no" class="intTelNo">
                        <input type="email" placeholder="@lang('labels.email')" name="email"/>
                        <input type="hidden" id="interestedCarId" name="car_id" value="">
                        <br>
                        <div class="col-md-12">
                            <div class="g-recaptcha" data-sitekey="<?php echo $api->captcha_site_key;?>"></div>
                            <br>
                        </div>
                        <div class="BtnEd">
                            <input type="submit" class="redishButtonRound"
                                   value="<?php echo($lang == 'eng' ? 'Submit' : 'إرسال'); ?>"/>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#loginSmsVerify" id="openSMSVerifyPopup"></button>
    <!-- Modal FOr SMS API Verification -->
    <div class="modal fade" id="loginSmsVerify" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo($lang == 'eng' ? 'Verify!' : 'تحقق!'); ?></h4>
                </div>
                <div class="modal-body text-center">
                    <form class="verificationCheck" action="<?php echo $lang_base_url; ?>/create-login" method="get"
                          onsubmit="return false;">
                        <p class="smsVerification"></p>
                        <input type="text" name="verification_code"
                               placeholder="<?php echo($lang == 'eng' ? 'ENTER VERIFICATION CODE' : 'ادخل رقم التحقق'); ?>"/>
                        <div class="twoBtnEd resendLink">
                            <input type="submit" class="redishButtonRound"
                                   value="<?php echo($lang == 'eng' ? 'VERIFY' : 'تحقق'); ?>"/>
                            <p>
                                <?php echo($lang == 'eng' ? 'Didn\'t receive code?' : 'لم يصلك الرمز؟'); ?>

                                <br/>
                                <a href="javascript:void(0);"
                                   class="resendVerifyCode"><?php echo($lang == 'eng' ? 'Resend' : 'إعادة إرسال'); ?></a>
                            </p>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for cancel booking -->
    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#cancelBookingPopupCharge"
            id="cancelBookingConfirmCharge"></button>
    <div class="modal-mobile modal fade" id="cancelBookingPopupCharge" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h4 class="modal-title cancelTitle" id="myModalLabel"></h4>
                    <br/>
                    <div><p class="cancelMsg"></p></div>
                    <small class="acceptTnC">
                        <a href="javascript:void(0);" id="openTermsAndConditonsPopup" data-bs-toggle="modal"
                           data-bs-target="#term_n_Cond">
                            <b>*</b> <?php echo($lang == 'eng' ? 'Terms And Conditions' : 'الشروط والأحكام'); ?>
                        </a>
                    </small>
                    <div class="twoBtnEd">
                        <input type="button" data-bs-dismiss="modal" aria-label="Close"
                               class="redishButtonRound cancelBookingBtnConfirmCharge"
                               value="<?php echo($lang == 'eng' ? 'Confirm' : 'تأكيد'); ?>"/>
                        <br/>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelBookingReasonPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title cancelTitle" id="myModalLabel"></h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div>
                        <p>@lang('labels.select_booking_cancellation_reason')</p>
                        <select class="selectpicker" id="cancellation_reason">
                            <option value="">@lang('labels.select_booking_cancellation_reason')</option>
                            @foreach (custom::cancellation_reasons() as $country)
                                <option value="{{$country->id}}">{{$lang == 'eng' ? $country->cancellation_reason_en : $country->cancellation_reason_ar}}</option>
                            @endforeach
                        </select>
                        <br><br>
                    </div>
                    <div class="twoBtnEd">
                        <input type="button" class="redishButtonRound cancelBookingReasonBtn"
                               value="<?php echo($lang == 'eng' ? 'Confirm' : 'تأكيد'); ?>"/>
                        <br/>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for cancel booking -->
    <!--<button style="display: none;" data-bs-toggle="modal" data-bs-target="#cancelBookingPopupNoCharge"
            id="cancelBookingConfirmNoCharge"></button>
    <div class="modal fade" id="cancelBookingPopupNoCharge" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title cancelTitle" id="myModalLabel"></h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div><p class="cancelMsg"></p></div>
                    <div class="twoBtnEd">
                        <input type="button" class="redishButtonRound cancelBookingBtnConfirmNoCharge" value="Confirm"/>
                        <br/>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->

    <!-- Modal For Loyalty Confirmation On Search Bar -->
    <button style="display: none;" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#PopUpForLoyaltyConfirmation"
            id="OpenPopUpForLoyaltyConfirmation"></button>
    <div class="modal-mobile modal fade" id="PopUpForLoyaltyConfirmation" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" style="top:0;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <?php if(custom::is_mobile()){ ?>
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo($lang == 'eng' ? 'Attention' : 'إنتبه'); ?></h4>
                </div>
                <?php }else{ ?>
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title"
                        id="myModalLabel"><?php echo($lang == 'eng' ? 'Attention' : 'تنبيه'); ?></h4>
                </div>
                <?php } ?>
                <div class="modal-body text-center">
                    <div class="whiteBox1240">
                        <div class="leftSec">
                            <h1><?php echo($lang == 'eng' ? 'NEW <br> CUSTOMER ?' : 'عميل جديد؟'); ?></h1>
                            <p><?php echo($lang == 'eng' ? 'Start making reservations and enjoy the best services.' : 'ابدأ حجزك و تمتع بأفضل الخدمات'); ?></p>
                            <input type="button" class="edBtn"
                                   value="<?php echo($lang == 'eng' ? 'Continue' : 'الإستمرار'); ?>"
                                   data-bs-dismiss="modal"/>
                        </div>
                        <div class="rightSec ">
                            <?php echo($lang == 'eng' ? '<h1>ALREADY A <br>	CUSTOMER?</h1>' : '<h1>هل إستأجرت من المفتاح سابقا؟</h1>'); ?>
                            <p>
                                <?php echo($lang == 'eng' ?
                                    'If you are already a customer of key please enter your ID number and enjoy your membership benefits.' :
                                    'إذا كنت عميل لدى المفتاح يرجى إدخال رقم الهوية لكي تتمتع بمزايا عضويتك'); ?></p>
                            <div class="label-box">
                                <form action="<?php echo $lang_base_url; ?>/search-results" method="post"
                                      class="userLoyaltyApplyForm">
                                    <?php if(!custom::is_mobile()){ ?>
                                    <label><?php echo($lang == 'eng' ? 'ID Number *' : 'رقم الهوية *'); ?></label>
                                    <?php } ?>
                                    <div class="txt-field">
                                        <?php if(custom::is_mobile()){ ?>
                                        <input type="text"
                                               placeholder="<?php echo($lang == 'eng' ? 'ID Number' : 'رقم الهوية'); ?>"
                                               name="id_no_for_loyalty" class="id_no_for_loyalty" autocomplete="off">
                                        <?php }else{ ?>
                                        <input type="text"
                                               placeholder="<?php echo($lang == 'eng' ? 'WRITE' : 'اكتب هنا'); ?>"
                                               name="id_no_for_loyalty" class="id_no_for_loyalty" autocomplete="off">
                                        <?php } ?>
                                        <input type="hidden" name="from_region_id"
                                               value="<?php echo (isset($sessionVals['from_region_id']) ? $sessionVals['from_region_id'] : ""); ?>">
                                        <input type="hidden" name="from_city_id"
                                               value="<?php echo (isset($sessionVals['from_city_id']) ? $sessionVals['from_city_id'] : ""); ?>">
                                        <input type="hidden" name="from_branch_id"
                                               value="<?php echo (isset($sessionVals['from_branch_id']) ? $sessionVals['from_branch_id'] : ""); ?>">
                                        <input type="hidden" name="to_city_id"
                                               value="<?php echo (isset($sessionVals['to_city_id']) ? $sessionVals['to_city_id'] : ""); ?>">
                                        <input type="hidden" name="to_branch_id"
                                               value="<?php echo (isset($sessionVals['to_branch_id']) ? $sessionVals['to_branch_id'] : ""); ?>">
                                        <input type="hidden" name="pickup_date"
                                               value="<?php echo (isset($sessionVals['pickup_date']) ? $sessionVals['pickup_date'] : ""); ?>">
                                        <input type="hidden" name="pickup_time"
                                               value="<?php echo (isset($sessionVals['pickup_time']) ? $sessionVals['pickup_time'] : ""); ?>">
                                        <input type="hidden" name="dropoff_date"
                                               value="<?php echo (isset($sessionVals['dropoff_date']) ? $sessionVals['dropoff_date'] : ""); ?>">
                                        <input type="hidden" name="dropoff_time"
                                               value="<?php echo (isset($sessionVals['dropoff_time']) ? $sessionVals['dropoff_time'] : ""); ?>">
                                        <input type="hidden" name="from_branch_name"
                                               value="<?php echo(isset($sessionVals['from_branch_name']) ? $sessionVals['from_branch_name'] : ''); ?>">
                                        <input type="hidden" name="to_branch_name"
                                               value="<?php echo(isset($sessionVals['to_branch_name']) ? $sessionVals['to_branch_name'] : ''); ?>">
                                        <input type="hidden" name="is_delivery_mode"
                                               value="<?php echo (isset($sessionVals['is_delivery_mode']) ? $sessionVals['is_delivery_mode'] : ""); ?>">
                                        <input type="hidden" name="pickup_delivery_coordinate"
                                               value="<?php echo (isset($sessionVals['pickup_delivery_coordinate']) ? $sessionVals['pickup_delivery_coordinate'] : ""); ?>">
                                        <input type="hidden" name="dropoff_delivery_coordinate"
                                               value="<?php echo (isset($sessionVals['dropoff_delivery_coordinate']) ? $sessionVals['dropoff_delivery_coordinate'] : ""); ?>">
                                        <input type="hidden" name="delivery_charges"
                                               value="<?php echo (isset($sessionVals['delivery_charges']) ? $sessionVals['delivery_charges'] : ""); ?>">
                                        <input type="hidden" class="allIsOkForPickup" name="allIsOkForPickup"
                                               value="<?php echo(isset($sessionVals['allIsOkForPickup']) && $sessionVals['allIsOkForPickup'] != '' ? $sessionVals['allIsOkForPickup'] : 0); ?>">
                                        <input type="hidden" class="allIsOkForDropoff" name="allIsOkForDropoff"
                                               value="<?php echo(isset($sessionVals['allIsOkForDropoff']) && $sessionVals['allIsOkForDropoff'] != '' ? $sessionVals['allIsOkForDropoff'] : 0); ?>">
                                            <input type="hidden" id="book_for_hours" name="book_for_hours"
                                                   value="<?php echo(isset($sessionVals['book_for_hours']) && $sessionVals['book_for_hours'] != '' ? $sessionVals['book_for_hours'] : 0); ?>">
                                            <input type="hidden" id="subscribe_for_months" name="subscribe_for_months"
                                                   value="<?php echo(isset($sessionVals['subscribe_for_months']) && $sessionVals['subscribe_for_months'] != '' ? $sessionVals['subscribe_for_months'] : 0); ?>">
                                        <?php if (Session::get('offer_car_model_id') != ''){ ?>
                                        <input type="hidden" name="mod_id"
                                               value="<?php echo Session::get('offer_car_model_id'); ?>">
                                        <?php } ?>
                                            <input type="hidden" id="is_subscription_with_delivery_flow" name="is_subscription_with_delivery_flow" value="<?php echo(isset($sessionVals['is_subscription_with_delivery_flow']) && $sessionVals['is_subscription_with_delivery_flow'] != '' ? $sessionVals['is_subscription_with_delivery_flow'] : 0); ?>">
                                    </div>
                                    <input type="button"
                                           value="<?php echo($lang == 'eng' ? 'Continue' : 'الإستمرار'); ?>"
                                           class="edBtn applyUserLoyaltyBtn">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal WIth Form -->
    <button style="display: none;" data-bs-toggle="modal"
            data-bs-target="#<?php echo(custom::is_mobile() ? 'mobile-verify-alert' : 'PopUpForModalWithForm');?>"
            id="openModalWithForm"></button>
    <div class="modal-mobile modal fade"
         id="<?php echo(custom::is_mobile() ? 'mobile-verify-alert' : 'PopUpForModalWithForm');?>" tabindex="-1"
         role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <?php if(!custom::is_mobile()){ ?>
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title"
                        id="myModalLabel"><?php echo($lang == 'eng' ? 'Continue' : 'الإستمرار'); ?></h4>
                </div>
                <?php } ?>
                <div class="modal-body text-center">
                    <br/>
                    <div>
                        <p class="">
                            <?php echo($lang == 'eng' ? 'Please enter your email address or mobile no. or ID no. that you used with this reservation.' : 'الرجاء إدخال بريدك الإلكتروني أو رقم هاتفك الجوال أو رقم الهوية
التي استخدمتها في الحجز'); ?>
                        </p>
                    </div>

                    <input type="text" placeholder="@lang('labels.write')" name="email" id="email_field_for_manage"/>
                    <input type="hidden" name="resCode" value="" id="resCode"/>
                    <div class="twoBtnEd">
                        <br/>
                        <button type="button" class="redishButtonRound"
                                id="manageBookingSecondStep"><?php echo($lang == 'eng' ? 'Submit' : 'تنفيذ'); ?></button>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Modal -->
    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#siteUnderMaintenance"
            id="openSiteUnderMaintenancePopUp"></button>
    <div class="modal-mobile modal fade" id="siteUnderMaintenance" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo($lang == 'eng' ? 'Error' : 'خطأ'); ?></h4>
                </div>
                <div class="modal-body text-center">
                    <div>
                        <p><?php echo($lang == 'eng' ? $site_settings->maintenance_eng_desc : $site_settings->maintenance_arb_desc); ?></p>
                    </div>
                    <div class="twoBtnEd">
                        <input type="submit" class="redishButtonRound"
                               value="<?php echo($lang == 'eng' ? 'CLOSE' : 'إغلاق'); ?>" data-bs-dismiss="modal"/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#msgPopupNoRedirect"
            id="openMsgPopupNoRedirect"></button>
    <div class="modal-mobile modal fade" id="msgPopupNoRedirect" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" style="z-index: 999999;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!--<div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="myModalLabel"></h4>
                </div>-->
                <div class="modal-body text-center">
                    <br/>
                    <div><p class="responseMsg"></p></div>
                    <div class="twoBtnEd">
                        <br/>
                        <input type="submit" class="redishButtonRound"
                               value="<?php echo($lang == 'eng' ? 'CLOSE' : 'إغلاق'); ?>" data-bs-dismiss="modal"/>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="msgPopupNoRedirectForHourly" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title responseTitleForHourly" id="myModalLabel"></h4>
                    </div>
                    <div class="modal-body text-center">
                        <br/>
                        <div><p class="responseMsgForHourly"></p></div>
                        <div class="twoBtnEd">
                            <br/>
                            <input type="submit" class="redishButtonRound"
                                   value="<?php echo($lang == 'eng' ? 'CLOSE' : 'إغلاق'); ?>" data-bs-dismiss="modal"
                                   onclick="open_time_picker();"/>
                            <br/>
                            <br/>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Modal for survey -->
    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#surveyPopup" id="openSurveyPopup"></button>
    <div class="modal-mobile modal fade" id="surveyPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <h4 class="modal-title"
                        id="myModalLabel"><?php echo($lang == 'eng' ? 'Message' : 'الرسالة'); ?></h4>
                </div>
                <div class="modal-body text-center">
                    <div>
                        <p><?php echo($lang == 'eng' ? 'You have a pending survey and need to complete it before making any new reservations.' : 'لديك استبيان معلق وتحتاج إلى إكماله قبل إجراء أي حجوزات جديدة'); ?></p>
                    </div>
                    <br/>
                    <div class="twoBtnEd">
                        <?php if ($survey_mode == 'optional')
                        { ?>
                        <a id="skipSurvey">
                            <button class="redishButtonRound"><?php echo($lang == 'eng' ? 'SKIP' : 'تخطى'); ?></button>
                        </a>
                        <?php } ?>
                        <a href="<?php echo $lang_base_url . '/survey'; ?>">
                            <button class="redishButtonRound"><?php echo($lang == 'eng' ? 'Fill Survey' : 'يرجى ملء الاستبيان'); ?></button>
                        </a>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#msgPopupRedirect"
            id="openMsgPopupRedirect"></button>
    <div class="modal-mobile modal fade" id="msgPopupRedirect" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="myModalLabel"></h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div><p class="responseMsg"></p></div>
                    <div class="twoBtnEd">
                        <br/>
                        <a href="javascript:void(0);" id="OKBtn" class="redishButtonRound">
                            <input type="submit" class="redishButtonRound" value="OK"></a>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#showUserEmailMsg"
            id="openUserEmailMsgPopup"></button>
    <div class="modal-mobile modal fade" id="showUserEmailMsg" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="myModalLabel"></h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div><p class="responseMsg"></p></div>
                    <div class="twoBtnEd">
                        <br/>
                        <input type="submit" class="redishButtonRound" id="openUserEmailMsgPopupSubmitBtn" value="OK">
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal-mobile terms-condition modal fade" id="term_n_Cond" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog bigOne modal-dialog-centered" role="document">
            <div class="modal-content">
                <?php if(!custom::is_mobile()){ ?>
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo ($lang == 'eng' ? 'Terms & Conditions' : 'تطبق الشروط والأحكام'); ?></h4>
                </div>
                <?php }else{  ?>
                <div class="modal-header">
                    <a href="javascript:void(0);" class="btn-close" data-bs-dismiss="modal" aria-label="Close">close</a>
                    <h4><?php echo($lang == 'eng' ? 'Terms & Conditions' : 'تطبق الشروط والأحكام'); ?></h4>
                </div>
                <?php } ?>
                <div class="modal-body">
                    <br/>
                    <?php echo($lang == 'eng' ? $site_settings->eng_terms : $site_settings->arb_terms); ?>

                    <div class="twoBtnEd">
                        <p>&nbsp;</p>
                        <input class="redishButtonRound" value="<?php echo($lang == 'eng' ? 'CLOSE' : 'إغلاق'); ?>"
                               data-bs-dismiss="modal" type="submit">
                        <p>&nbsp;</p>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- CDW Plus Modal -->
        <div class="modal fade" id="cdw_plus_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog bigOne modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo ($lang == 'eng' ? 'Terms & Conditions' : 'تطبق الشروط والأحكام'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <br/>
                        <?php echo($lang == 'eng' ? $site_settings->eng_terms_for_cdw_plus : $site_settings->arb_terms_for_cdw_plus); ?>

                        <div class="twoBtnEd">
                            <p>&nbsp;</p>
                            <input class="redishButtonRound" value="<?php echo($lang == 'eng' ? 'CLOSE' : 'إغلاق'); ?>"
                                   data-bs-dismiss="modal" type="submit">
                            <p>&nbsp;</p>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Modal -->
    <style>
        body.arb.is_mobile .modal-mobile.hl_instructions .modal-content,
        body.arb.is_mobile .modal-mobile.hl_instructions .modal-header {
            text-align: right;
        }

        body.arb.is_mobile .modal-mobile.hl_instructions .close {
            right: auto !important;
            left: 20px !important;
            text-indent: 9999px !important;
        }
    </style>
    <div class="modal-mobile terms-condition hl_instructions modal fade" id="hl_instructions" tabindex="-1"
         role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog bigOne modal-dialog-centered" role="document">
            <div class="modal-content">
                <?php if(!custom::is_mobile()){ ?>
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel">Human Less Instructions</h4>
                </div>
                <?php }else{  ?>
                <div class="modal-header">
                    <a href="javascript:void(0);" class="btn-close" data-bs-dismiss="modal" aria-label="Close">close</a>
                    <h4>@lang("labels.hl_instructions")</h4>
                </div>
                <?php } ?>
                <div class="modal-body">
                    <p><?php echo($lang == 'eng' ? $site_settings->eng_hl_instructions : $site_settings->arb_hl_instructions); ?></p>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary btn-lg humanLessPopup" data-bs-toggle="modal"
            data-bs-target="#noHumanLessPopup" style="display: none;">Launch modal
    </button>
    <div class="modal-mobile terms-condition modal fade" id="noHumanLessPopup" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog bigOne modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="javascript:void(0);" class="btn-close" data-bs-dismiss="modal" aria-label="Close">close</a>
                    <h4>@lang("labels.key_human_less")</h4>
                </div>
                <div class="modal-body response">
                    <p class="response_msg"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <button type="button" class="btn btn-primary btn-lg openGoogleMapPopup" data-bs-toggle="modal"
            data-bs-target="#google_map_popup" style="display: none;">Launch modal
    </button>
    <div class="modal-mobile modal fade delLocMapPopUp" id="google_map_popup" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                    <h4 class="modal-title containsLableForMapPopup" id="myModalLabel">DELIVERY LOCATION</h4>
                </div>
                <div class="boxShadow">
                    <div class="modal-body" id="google_map_outer">
                        <div id="google_map_with_search" style="width: 100%;"></div>
                    </div>
                    <div class="modal-footer">
                        <h4 id="containsLocationDetails">
                            <strong id="containsAddress">232, Street Name, Bani Malik</strong>
                            <span id="containsCity">Jeddah, Saudi Arabia</span>
                        </h4>
                        <h4 id="noLocationSelectedMessage" style="display: none;color: #9f073f;">
                            <?php echo($lang == 'eng' ? 'Please select a location for delivery.' : 'Please select a location for delivery.'); ?>
                        </h4>
                        <h4 id="containsErrorMessage" style="display: none;color: #9f073f;">
                            <?php echo($lang == 'eng' ? 'Sorry but our delivery services are not available for the selected location.' : 'عفوا، خدمة التوصيل غير متاحة للمكان المختار'); ?>
                        </h4>
                        <input type="hidden" id="pop_type" value="">
                        <button type="button"
                                class="btn btn-primary mapConfirm"><?php echo($lang == 'eng' ? 'Confirm' : 'تأكيد'); ?></button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Car Inspection Modal-->
    <div class="modal-mobile modal fade" id="showCarInspection" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="">@lang('labels.car_inspection')</h4>
                </div>
                <div class="modal-body text-center">
                    <form action="" method="post" class="ajax_form">
                        <div class="faultCreating">
                            <div class="buttons">
                                <ul>
                                    <li id="drawBtn">
                                        <a href="#">
                                            <div class="imgBox">
                                                <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url . '/public/frontend/inspection/images/draw.png';?>"
                                                     height="31" width="31">
                                            </div>@lang('labels.draw')
                                        </a>
                                    </li>
                                    <li class="erase-all">
                                        <a href="#">
                                            <div class="imgBox">
                                                <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url . '/public/frontend/inspection/images/erase.png';?>"
                                                     height="31" width="31">
                                            </div>
                                            @lang('labels.erase')
                                        </a>
                                    </li>
                                    <li id="clearAll">
                                        <a href="#">
                                            <div class="imgBox">
                                                <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url . '/public/frontend/inspection/images/clearAll.png';?>"
                                                     height="31" width="31">
                                            </div>
                                            @lang('labels.clear_all')
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="popupDiv option shouldBeLft" style="display: none;">
                                <a class="close-icon close-inspection-popup" title="Close"
                                   href="javascript:void(0);"></a>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="imgBox">
                                                <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url . '/public/frontend/inspection/images/fade.png';?>"
                                                     height="50" width="50">
                                            </div>@lang('labels.fade')
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="imgBox">
                                                <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url . '/public/frontend/inspection/images/scratch.png';?>"
                                                     height="50" width="50">
                                            </div>@lang('labels.scratch')
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="imgBox">
                                                <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url . '/public/frontend/inspection/images/damage.png';?>"
                                                     height="50" width="50">
                                            </div>@lang('labels.damage')
                                        </a>
                                    </li>
                                    <li class="selfie">
                                        <a href="javascript:void(0);">
                                            <div class="imgBox">
                                                <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url . '/public/frontend/inspection/images/picture.png';?>"
                                                     height="50" width="50">
                                            </div>@lang('labels.picture')
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="inspection">
                                <div class="image" id="mainImgSrc">
                                    <?php $imageName = isset($inspection_mode) && $inspection_mode == 'dropOff' ? 'mainImage2.jpg' : 'mainImage.jpg'; ?>
                                    <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url . '/public/frontend/inspection/images/' . $imageName;?>"
                                         alt="" height="474" width="617"/>
                                </div>
                            </div>
                            <div class="editingButtons">
                                <button type="button"
                                        class="nextBtn redishButtonRound <?php echo (isset($end_trip) && $end_trip == 1) ? 'endTripInspection' : 'saveInspection' ?>">@lang('labels.done')</button>
                                <button type="button"
                                        class="backBtn redishButtonRound discard">@lang('labels.discard')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-mobile modal fade" id="inspection-picture" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="mySpCarModalLabel">@lang('labels.inspection_picture')</h4>
                </div>
                <div class="modal-body text-center">
                    <div class="app">
                        <a href="#" id="start-camera" class="visible">@lang('labels.touch_here_start_app')</a>
                        <video id="camera-stream" autoplay playsinline></video>
                        <img id="snap">
                        <p id="error-message"></p>
                        <div class="controls">
                            <a href="#" id="delete-photo" title="Delete Photo"
                               class="disabled">@lang('labels.start')</a>
                            <a href="#" id="take-photo" title="Take Photo">@lang('labels.save')</a>
                        </div>
                        <!-- Hidden canvas element. Used for taking snapshot of video. -->
                        <canvas id="canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--Car Inspection Modal-->
    <div class="modal-mobile modal fade" id="showPayExtras" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle">@lang('labels.attention')</h4>
                </div>
                <div class="modal-body text-center">
                    <div class="extra">
                        <h2>@lang('labels.pay_extra')</h2>
                        <p class="extrasDesc">@lang('labels.extras_desc')</p>
                    </div>
                    <div class="editingButtons">
                        <button type="button" class="nextBtn redishButtonRound show_et_wiz3">@lang('labels.ok')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-mobile modal fade" id="lockCarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle">@lang('labels.attention')</h4>
                </div>
                <div class="modal-body text-center">
                    <div class="extra">
                        <p class="extrasDesc">@lang('labels.wait_for_lock')</p>
                    </div>
                    <div class="editingButtons">
                        <button type="button" class="nextBtn redishButtonRound okToWait" data-bs-dismiss="modal"
                                aria-label="Close">@lang('labels.ok')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-mobile modal fade" id="unLockCarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle">@lang('labels.attention')</h4>
                </div>
                <div class="modal-body text-center">
                    <div class="extra">
                        <p class="extrasDesc">@lang('labels.wait_for_unlock')</p>
                    </div>
                    <div class="editingButtons">
                        <button type="button" class="nextBtn redishButtonRound okToWait" data-bs-dismiss="modal"
                                aria-label="Close">@lang('labels.ok')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-mobile modal fade" id="forcePayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle">@lang('labels.attention')</h4>
                </div>
                <div class="modal-body text-center">
                    <div class="extra">
                        <p class="extrasDesc">@lang('labels.force_payment_msg')</p>
                    </div>
                    <div class="editingButtons">
                        <button type="button" class="nextBtn redishButtonRound" data-bs-dismiss="modal"
                                aria-label="Close">@lang('labels.ok')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Special Car Description Modal-->
    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#showSpecialCarDescription"
            id="openSpecialCarDescription"></button>
    <div class="modal-mobile modal fade" id="showSpecialCarDescription" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="mySpCarModalLabel">@lang('labels.special_car_offer')</h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div><p class="specialCarDesc"></p></div>
                </div>
            </div>
        </div>
    </div>
    <!--Special Car Description Modal-->

    <div class="modal fade" id="paymentSessionConflict" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close redirectToHome" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="sessionConflictLabel">@lang('labels.message')</h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div><p class="sessionConflictDesc"></p></div>
                </div>
            </div>
        </div>
    </div>

    @if(Session::get('logged_in_from_frontend') == true)
        <input type="hidden" class="user_email_for_pending_survey" value="<?php echo Session::get('user_email'); ?>">
        <input type="hidden" class="user_id_no_for_pending_survey" value="<?php echo Session::get('user_id_no'); ?>">
    @endif

</footer>
<?php } ?>
<?php $site = custom::site_settings(); ?>

<div class="modal-mobile modal fade"
     id="<?php echo(custom::is_mobile() ? 'forGotPassLogn_mobile' : 'forGotPassLogn');?>" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <?php if(!custom::is_mobile()){ ?>
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title" id="myModalLabel">@lang('labels.reset_password')</h4>
            </div>
            <?php } ?>
            <div class="modal-body">
                <form action="<?php echo $lang_base_url; ?>/forgot_password" method="post"
                      class="popFormStl custom_submit" onsubmit="return false;">
                    <p>@lang('labels.forgot_password_popup_msg')</p>
                    <input type="email" placeholder="@lang('labels.email')" name="email" required/>
                    <div class="twoBtnEd">
                        <input type="submit" class="redishButtonRound" value="@lang('labels.reset_password')"/>
                        <a href="javascript:void(0);">
                            <input type="button" class="grayishButton" value="@lang('labels.cancel')"
                                   data-bs-dismiss="modal"/>
                        </a>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="model-login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <?php if (Session::get('logged_in_from_frontend') != true){ ?>
                <div id="containsLoginDDB">
                    <h2>@lang('labels.login_btn')</h2>
                    <form action="<?php echo $lang_base_url; ?>/login" method="post" id="login"
                          onsubmit="return false;">
                        <input type="hidden" name="bookingIDGetCar" id="bookingIDGetCar"
                               value="<?php echo(isset($booking_detail->id) ? base64_encode($booking_detail->id) : ''); ?>">
                        <input type="hidden" id="redirect_segment" name=""
                               value="<?php echo((isset($booking_detail->id) && $booking_detail->human_less_state == 'Acknowledged' && $booking_detail->booking_status == "Not Picked") ? 'get-car' : 'end-trip'); ?>">
                        <input autocomplete="off" type="text"
                               placeholder="<?php echo($lang == 'eng' ? 'WRITE (Email \ ID number)' : 'اكتب (البريد الإلكتروني / رقم الهوية)'); ?>"
                               name="username" id="loginUsername"/>
                        <input autocomplete="off" type="password" placeholder="@lang('labels.password')" name="password"
                               id="loginPassword"/>
                        <?php if ($site->maintenance_mode == 'on'){ ?>
                        <a href="javascript:void(0);" onclick="siteUnderMaintenance();"
                           style="display: inline-block;">@lang('labels.forgot_password')</a>
                        <?php }else{ ?>
                        <div class="forgot-pass">
                            <?php if(custom::is_mobile()){ ?>
                            <a href="javascript:void(0);" id="btn_forGotPassLogn"
                               style="display: inline-block;">@lang('labels.forgot_password')</a>
                            <?php }else{ ?>
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#forGotPassLogn"
                               style="display: inline-block;">@lang('labels.forgot_password')</a>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <?php if ($site->maintenance_mode == 'on'){ ?>
                        <input type="button" class="redishButtonRound" value="@lang('labels.login')"
                               onclick="siteUnderMaintenance();"/>
                        <input type="button" onclick="siteUnderMaintenance();" class="grayishButton"
                               value="@lang('labels.register')"/>
                        <?php }else{ ?>
                        <div class="btn-box">
                            <input type="submit" value="@lang('labels.login')"/>
                            <input type="button"
                                   onclick="document.location.href='<?php echo $lang_base_url . '/register'; ?>'"
                                   value="@lang('labels.register')"/>
                        </div>
                        <?php } ?>
                        <div class="clearfix"></div>
                    </form>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="modal-mobile modal fade" id="decison-alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p><?php echo($lang == 'eng' ? 'Are you going to drop off<br> at same location?' : 'هل سوف يتم التسليم في نفس الموقع');?></p>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" id="btn-yes"
                   class="btn-yes"><?php echo($lang == 'eng' ? 'Yes' : 'نعم');?></a>
                <a href="javascript:void(0);" id="btn-no" class="btn-no"><?php echo($lang == 'eng' ? 'No' : 'لا');?></a>
            </div>
        </div>
    </div>
</div>

<div class="modal-mobile modal fade" id="date-select-alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p><?php echo($lang == 'eng' ? 'Please select Pick up and Drop off date' : 'اختار تاريخ موقع الاستلام و التسليم');?></p>
                <button type="button" class="grayishButton" data-bs-dismiss="modal" style="margin-top: 10px;height: 30px;">@lang('labels.close')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-mobile modal fade" id="time-select-alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p><?php echo($lang == 'eng' ? 'Please select Pick up and Drop off time' : 'اختار وقت موقع الاستلام و التسليم');?></p>
                <button type="button" class="grayishButton" data-bs-dismiss="modal" style="margin-top: 10px;height: 30px;">@lang('labels.close')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-mobile modal fade" id="book_for_hours_select_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>@lang('labels.select_booking_hours')</p>
                <select class="selectpicker" onchange="select_book_for_hours(this.value);">
                    <option value="">@lang('labels.select_hours')</option>
                    <?php for ($i = 2; $i <= 5; $i++){ ?>
                    <option value="<?php echo $i; ?>" <?php echo (isset($sessionVals['book_for_hours']) && $sessionVals['book_for_hours'] == $i ? 'selected' : ''); ?>><?php echo $i; ?> @lang('labels.hours')</option>
                    <?php } ?>
                </select><br><br>
                <input type="button" class="grayishButton" value="@lang('labels.continue')" data-bs-dismiss="modal"/>
            </div>
        </div>
    </div>
</div>

<div class="modal-mobile modal fade" id="book_for_subscription_months_select_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-dialog-centered">
            <div class="modal-body">
                <p>@lang('labels.subscribe_for_months')</p>
                <select class="selectpicker" onchange="select_subscribe_for_months(this.value);">
                    <?php
                    $subscribe_for_months = [3,6,9,12];
                    foreach($subscribe_for_months as $for_month){
                    $selected = '';
                    if (isset($sessionVals['subscribe_for_months']) && $sessionVals['subscribe_for_months'] > 0 && $for_month == $sessionVals['subscribe_for_months']) {
                        $selected = 'selected';
                    } elseif ($for_month == 1) {
                        $selected = 'selected';
                    }
                        ?>
                    <option value="<?php echo $for_month; ?>" <?php echo $selected; ?>><?php echo $for_month; ?> @lang('labels.months')</option>
                    <?php } ?>
                </select><br><br>
                <input type="button" class="grayishButton" value="@lang('labels.continue')" data-bs-dismiss="modal"/>
            </div>
        </div>
    </div>
</div>

<div class="modal-mobile modal fade" id="registration-alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="new-customer">
                    <p>Create a new user account</p>
                    <br>
                    <a href="javascript:void(0);" class="btn-register">Register</a>
                </div>
                <div class="already-customer">
                    <p>Already a customer</p>
                    <br>
                    <a href="javascript:void(0);" class="btn-login">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
/*
 * Ahsan
 * I'm removing rand(); function from assets file (CSS and JS) for only mobile site.
 * Now following variable is using for css and js versions instead of rand(); function.
 *
 * Defined in header and footer
 *
 * */
// $manual_version = '5.0';
$manual_version = rand();
?>
<script src="<?php echo $base_url; ?>/public/frontend/js/jquery.mCustomScrollbar.concat.min.js" defer></script>
{{--<script src="<?php echo $base_url; ?>/public/frontend/js/bootstrap.min.js" defer></script>--}}
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="<?php echo $base_url; ?>/public/frontend/css/bootstrap@5.3/js/bootstrap.min.js" defer></script>


<!--<script src="<?php echo $base_url; ?>/public/frontend/intTelInput/js/intlTelInput.js"></script>-->
<script src="<?php echo $base_url; ?>/public/frontend/intTelInput/js/intlTelInput.min.js" defer></script>
<script src="<?php echo $base_url; ?>/public/frontend/intTelInput/js/utils.js" defer></script>
<script src="<?php echo $base_url; ?>/public/frontend/select2/js/select2.min.js" defer></script>
<script src="<?php echo $base_url; ?>/public/frontend/select2/js/i18n/<?php echo ($lang == 'eng' ? 'en' : 'ar'); ?>.js" defer></script>

<?php if(custom::show_smart_banner()) { ?>
    <script src="<?php echo $base_url; ?>/jquery-smartbanner/smartbanner.min.js" defer></script>
<?php } ?>

<!--<script src="<?php echo $base_url; ?>/public/frontend/js/lazyload.js"></script>-->
<!--<script src="<?php echo $base_url; ?>/public/frontend/js/jquery.lazy.min.js"></script>-->
<?php if (true || !custom::is_homepage()) { ?>
<script src="<?php echo $base_url; ?>/public/frontend/js/script.min.js" defer></script>
<script src="<?php echo $base_url; ?>/public/frontend/js/functions.js?v=<?php echo $manual_version; ?>" defer></script>
<?php } ?>
<script>
    $(function() {
        $('.lazy').lazy({
            delay: 100
        });
        setTimeout(function () {
            $('body').show();
        }, 1500);
    });

    // Custom Drop Down Function
    $('.hasDropEd').click(function () {
        $('.hasDropEd').removeClass('open');
        $(this).addClass('open');
    });

    // Custom Drop Down Function
    $('.hasDropEdSecondary').click(function () {
        $(this).parent().next('.hasDropEd').addClass('open');
    });

    $(document).mouseup(function (e)
    {
        var container = $(".hasDropEd");
        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            $('.hasDropEd').removeClass('open');
        }
    });/*=======  END  ======*/



    //      menu Close function
    $(document).on("click","#closeTopMenu",function() {
        $('header .logoMenuTop .hasDropEd').removeClass('open');
    });

    // Changing language
    $(document).on('click', '.changeLanguageMobile', function (e) {
        $('.loaderSpiner').show();
        var arr = $(this).attr('id').split('||');
        var lang = arr[0];
        var urlSegment = arr[1];

        $.ajax({
            type: 'GET',
            url: lang_base_url + '/set_lang_session?lang=' + lang,
            data: {lang: lang},
            async: false,
            success: function (response) {
                $('.loaderSpiner').hide();
                if (lang == 'eng') {
                    window.location.href = base_url + '/en/' + urlSegment;
                } else {
                    window.location.href = base_url + '/' + urlSegment;
                }
            }
        });
        /*$.get(lang_base_url + '/set_lang_session?lang='+lang, function( data ) {
         if (lang == 'eng') {
         window.location.href = base_url + '/en/' + urlSegment;
         } else {
         window.location.href = base_url + '/' + urlSegment;
         }
         });*/

        /*$.ajax({
         type: 'POST',
         url: lang_base_url + '/set_lang_session',
         dataType: "json",
         data: {lang: lang},
         success: function (response) {
         if (response.lang == 'eng') {
         window.location.href = base_url + '/en/' + urlSegment;
         } else {
         window.location.href = base_url + '/' + urlSegment;
         }
         }
         });*/

        /*if (lang == 'ar') {
         window.location.href = base_url + '/ar/' + urlSegment;
         } else {
         window.location.href = base_url + '/' + urlSegment;
         }*/
    });

    <?php //if (isset($method_of_payment) && $method_of_payment != 'CC')
    //{ ?>
    /*$.ajax({
        type: 'GET',
        url: base_url + '/cronjob/setDataCronJob',
        success: function (response) {

        }
    });*/
    <?php //} ?>

    /*$(document).on("click", ".btnSpecialCar", function () {
        var car_id = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/getSpecialCarDesc',
            dataType: "json",
            data: {car_id: car_id},
            async: false,
            success: function (response) {
                if (response.status == true) {
                    $('.specialCarDesc').html(response.desc);
                    $('#openSpecialCarDescription').click();
                }
            }
        });
    });*/

    //  searchButtons Function click active
    $('.searchButtons li').click(function () {

        if (!$(this).hasClass('active')) {
            $('#from_region_id').val('');
            $('#from_city_id').val('');
            $('#from_branch_id').val('');
            $('#to_city_id').val('');
            $('#to_branch_id').val('');
            $('#delivery_charges').val('');
            $('.from_branch_field_for_delivery').val('');
            $('.to_branch_field_for_delivery').val('');
            $('.from_branch_field_for_pickup').val('');
            $('.to_branch_field_for_pickup').val('');
            $('.pickup_delivery_coordinate').val('');
            $('.dropoff_delivery_coordinate').val('');
        }

        $('.searchButtons li').removeClass('active');
        $(this).addClass('active');


        if ($(this).attr('id') == 'delivery_tab') // If I am in delivery tab
        {
            $('.isDeliveryMode').val(1);
            $('.allIsOkForPickup').val(0);
            $('.allIsOkForDropoff').val(0);
            $('.pickup_mode_pickup, .pickup_mode_dropoff').addClass('hide');
            $('.delivery_mode_pickup, .delivery_mode_dropoff').removeClass('hide');

            $('.from_branch_field_for_delivery').attr('name', 'from_branch_name');
            $('.to_branch_field_for_delivery').attr('name', 'to_branch_name');
            $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').addClass('required-for-search');

            $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeAttr('name');
            $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeClass('required-for-search');


        } else if ($(this).attr('id') == 'pickup_tab') // If I am in pickup tab
        {
            $('.isDeliveryMode').val(0);
            $('.allIsOkForPickup').val(1);
            $('.allIsOkForDropoff').val(1);
            $('#delivery_charges').val('');
            $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');
            $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');

            $('.from_branch_field_for_pickup').attr('name', 'from_branch_name');
            $('.to_branch_field_for_pickup').attr('name', 'to_branch_name');
            $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').addClass('required-for-search');

            $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeAttr('name');
            $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeClass('required-for-search');

        }
    });

    function pickup_mode() {
        $('.isDeliveryMode').val(0);
        $('.allIsOkForPickup').val(1);
        $('.allIsOkForDropoff').val(1);
        $('#delivery_charges').val('');
        $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');
        $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');

        $('.from_branch_field_for_pickup').attr('name', 'from_branch_name');
        $('.to_branch_field_for_pickup').attr('name', 'to_branch_name');
        $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').addClass('required-for-search');

        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeAttr('name');
        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeClass('required-for-search');
    }

    function delivery_mode() {
        $('.isDeliveryMode').val(1);
        $('.allIsOkForPickup').val(0);
        $('.allIsOkForDropoff').val(0);
        $('.pickup_mode_pickup, .pickup_mode_dropoff').addClass('hide');
        $('.delivery_mode_pickup, .delivery_mode_dropoff').removeClass('hide');

        $('.from_branch_field_for_delivery').attr('name', 'from_branch_name');
        $('.to_branch_field_for_delivery').attr('name', 'to_branch_name');
        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').addClass('required-for-search');

        $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeAttr('name');
        $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeClass('required-for-search');
    }

    function hourly_mode() {
        $('.isDeliveryMode').val(2);
        $('.allIsOkForPickup').val(1);
        $('.allIsOkForDropoff').val(1);
        $('#delivery_charges').val('');
        $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');
        $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');

        $('.from_branch_field_for_pickup').attr('name', 'from_branch_name');
        $('.to_branch_field_for_pickup').attr('name', 'to_branch_name');
        $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').addClass('required-for-search');

        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeAttr('name');
        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeClass('required-for-search');
    }

    function monthly_mode() {
        $('.isDeliveryMode').val(3);
        $('.allIsOkForPickup').val(1);
        $('.allIsOkForDropoff').val(1);
        $('#delivery_charges').val('');
        $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');
        $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');

        $('.from_branch_field_for_pickup').attr('name', 'from_branch_name');
        $('.to_branch_field_for_pickup').attr('name', 'to_branch_name');
        $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').addClass('required-for-search');

        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeAttr('name');
        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeClass('required-for-search');
    }

    function weekly_mode() {
        $('.isDeliveryMode').val(5);
        $('.allIsOkForPickup').val(1);
        $('.allIsOkForDropoff').val(1);
        $('#delivery_charges').val('');
        $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');
        $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');

        $('.from_branch_field_for_pickup').attr('name', 'from_branch_name');
        $('.to_branch_field_for_pickup').attr('name', 'to_branch_name');
        $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').addClass('required-for-search');

        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeAttr('name');
        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeClass('required-for-search');
    }

    function subscription_mode() {
        $('.isDeliveryMode').val(4);
        $('.allIsOkForPickup').val(1);
        $('.allIsOkForDropoff').val(1);
        $('#delivery_charges').val('');
        $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');
        $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');

        $('.from_branch_field_for_pickup').attr('name', 'from_branch_name');
        $('.to_branch_field_for_pickup').attr('name', 'to_branch_name');
        $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').addClass('required-for-search');

        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeAttr('name');
        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeClass('required-for-search');
    }

    function subscription_with_delivery_flow_mode() {
        $('.isDeliveryMode').val(4);
        $('.allIsOkForPickup').val(0);
        $('.allIsOkForDropoff').val(0);
        $('.pickup_mode_pickup, .pickup_mode_dropoff').addClass('hide');
        $('.delivery_mode_pickup, .delivery_mode_dropoff').removeClass('hide');

        $('.from_branch_field_for_delivery').attr('name', 'from_branch_name');
        $('.to_branch_field_for_delivery').attr('name', 'to_branch_name');
        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').addClass('required-for-search');

        $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeAttr('name');
        $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeClass('required-for-search');
    }

</script>
<!--start pickup vs delivery mode for mobile-->
<?php if(isset($_REQUEST['pickup']) && $_REQUEST['pickup'] == 1){ ?>
<script>
    $(document).ready(function () {
        pickup_mode();
    });
</script>
<?php } ?>

<?php if(isset($_REQUEST['delivery']) && $_REQUEST['delivery'] == 1){ ?>
<script>
    $(document).ready(function () {
        delivery_mode();
    });
</script>
<?php } ?>

<?php if(isset($_REQUEST['hourly']) && $_REQUEST['hourly'] == 1){ ?>
<script>
    $(document).ready(function () {
        hourly_mode();
    });
</script>
<?php } ?>

<?php if(isset($_REQUEST['monthly']) && $_REQUEST['monthly'] == 1){ ?>
<script>
    $(document).ready(function () {
        monthly_mode();
    });
</script>
<?php } ?>


<?php if(isset($_REQUEST['weekly']) && $_REQUEST['weekly'] == 1){ ?>
<script>
    $(document).ready(function () {
        weekly_mode();
    });
</script>
<?php } ?>

<?php if(isset($_REQUEST['subscription']) && $_REQUEST['subscription'] == 1 && $site_settings->subscription_with_delivery_flow == 'off'){ ?>
<script>
    $(document).ready(function () {
        subscription_mode();
    });
</script>
<?php } ?>

<?php if(isset($_REQUEST['subscription']) && $_REQUEST['subscription'] == 1 && $site_settings->subscription_with_delivery_flow == 'on'){ ?>
<script>
    $(document).ready(function () {
        subscription_with_delivery_flow_mode();
    });
</script>
<?php } ?>

<script>
    function check_if_mada_card(card_number) {
        var mada_bins = ["440647", "440795", "446404", "457865", "457997", "539931", "558848", "557606", "417633", "468540", "468541", "468542", "468543", "446393", "409201", "458456", "484783", "462220", "455708", "455036", "486094", "486095", "486096", "440533", "489318", "489319", "445564", "410685", "406996", "432328", "428671", "428672", "428673", "446672", "543357", "434107", "407197", "407395", "412565", "431361", "521076", "588850", "529415", "535825", "543085", "524130", "554180", "549760", "524514", "529741", "537767", "535989", "536023", "513213", "520058", "585265", "588983", "588982", "589005", "531095", "530906", "532013", "422817", "422818", "422819", "428331", "483010", "483011", "483012", "589206", "419593", "439954", "530060", "531196"];
        if ($('#cardNumber').val().length > 5) {
            var starting_6_chars = card_number.substr(0, 6);
            if (mada_bins.indexOf(starting_6_chars) > -1) {
                $('#cardNumber').addClass('is-mada-card');
            } else {
                $('#cardNumber').removeClass('is-mada-card');
            }
        } else {
            $('#cardNumber').removeClass('is-mada-card');
        }
    }
</script>
<!--end pickup vs delivery mode for mobile-->

<script>
    $(document).ready(function () {
        <?php if (isset($flash_message)) { ?>
        $('.responseTitle').html((lang == 'eng' ? 'Success' : 'بنجاح'));
        $('.responseMsg').html('<?php echo $flash_message; ?>');
        $('#openMsgPopupNoRedirect').click();
        <?php } ?>

    });
</script>
</body>
</html>