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
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo($lang == 'eng' ? 'Verify!' : 'تحقق!'); ?></h4>
                </div>
                <div class="modal-body text-center">
                    <form class="cancelBookingverification" action="<?php echo $lang_base_url; ?>/create-login"
                          method="get" onsubmit="return false;">
                        <p class="cancelSmsVerification responseMsg"></p>
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

        <!-- Modal For Edit Booking OTP Verification -->
        <div class="modal fade" id="editBookingForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo($lang == 'eng' ? 'Verify!' : 'تحقق!'); ?></h4>
                    </div>
                    <div class="modal-body text-center">
                        <form class="editBookingverification" action="<?php echo $lang_base_url; ?>/create-login"
                              method="get" onsubmit="return false;">
                            <p class="editSmsVerification responseMsg"></p>
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

    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#corporateCompanies"
            id="btnCorporateCompanies"></button>
    <!-- Modal to select corporate companies for super corporate user -->
    <div class="modal fade" id="corporateCompanies" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel">@lang('labels.select_company')</h4>
                </div>
                <div class="modal-body text-center">
                    <form class="corporateCompaniesForm" action="<?php echo $lang_base_url; ?>/corporateCompany"
                          method="post" onsubmit="return false;">

                        <input type="hidden" id="super_corporate_id" name="super_corporate_id"
                               value="{{Session::get('super_corporate_id')}}">
                        <input type="hidden" id="is_super_corporate" name="" value="0">
                        {{--<select name="corporate_company" class="searchable" id="corporate_company_id" style="width: 200px;">
                            <option value="">@lang('labels.select_company')</option>
                            @if(isset($companies))
                                @foreach($companies as $company)
                                    <option value="{{$company->id}}" {{Session::get('corporate_customer_id') == $company->id?'selected':''}}>{{$lang == 'eng'?$company->company_name_en:$company->company_name_ar}}</option>
                                @endforeach
                            @endif
                        </select>--}}
                        <div class="ui-widget">
                            <select name="corporate_company" class="combobox" id="corporate_company_id"
                                    style="width: 200px;">
                                <option value="">@lang('labels.select_company')</option>
                                @if(isset($companies))
                                    @foreach($companies as $company)
                                        <option value="{{$company->id}}" {{Session::get('corporate_customer_id') == $company->id?'selected':''}}>{{$lang == 'eng'?$company->company_name_en:$company->company_name_ar}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <br><br>
                        <div class="clearfix"></div>
                        <input type="submit" class="redishButtonRound" value="@lang('labels.change')"/>
                        <div class="clearfix"></div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="superCorpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog bigOne modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel">@lang('labels.alert')</h4>
                </div>
                <div class="modal-body">
                    <br/>
                    <p>@lang('labels.select_company_msg')</p>
                    <br/>
                    <input class="redishButtonRound" value="<?php echo($lang == 'eng' ? 'CLOSE' : 'إغلاق'); ?>"
                           data-bs-dismiss="modal" type="submit">
                    <p></p>
                    <div class="clearfix"></div>
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
{{--    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#interestedInCar" id="openInterestedInCar"></button>--}}
    <!-- Button trigger modal -->
        <button style="display: none;" data-bs-toggle="modal" data-bs-target="#interestedInCar" id="openInterestedInCar"></button>

        <!-- Modal -->
        <div class="modal fade" id="interestedInCar" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"
                            id="myModalLabel"><?php echo($lang == 'eng' ? 'Send Contact Request' : 'إرسال طلب الاتصال'); ?></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    <div class="modal fade" id="cancelBookingPopupCharge" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title cancelTitle" id="myModalLabel"></h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div><p class="cancelMsg"></p></div>
                    <small class="acceptTnC">
                        <a href="javascript:void(0);" id="openTermsAndConditonsPopup" data-bs-toggle="modal"
                           data-bs-target="#term_n_Cond"
                           style="text-decoration: none; color: #9f073f; font-size: 10px; margin: -10px 0 20px; display: block; "><b>*</b> <?php echo($lang == 'eng' ? 'Terms And Conditions' : 'الشروط والأحكام'); ?>
                        </a>
                    </small>
                    <div class="twoBtnEd">
                        <input type="button" class="redishButtonRound cancelBookingBtnConfirmCharge"
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
        <button style="display: none;"  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#PopUpForLoyaltyConfirmation" id="OpenPopUpForLoyaltyConfirmation">
        </button>

        <!-- Modal -->
        <div class="modal fade" id="PopUpForLoyaltyConfirmation" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title fs-5" id="myModalLabel"><?php echo($lang == 'eng' ? 'Attention' : 'تنبيه'); ?></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
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
                                        <label><?php echo($lang == 'eng' ? 'ID Number *' : 'رقم الهوية *'); ?></label>
                                        <div class="txt-field">
                                            <input type="text"
                                                   placeholder="<?php echo($lang == 'eng' ? 'WRITE' : 'اكتب هنا'); ?>"
                                                   name="id_no_for_loyalty" class="id_no_for_loyalty" autocomplete="off" title="this field is required">
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
                                                   value="<?php echo isset($sessionVals['from_branch_name']) ? $sessionVals['from_branch_name'] : ''; ?>">
                                            <input type="hidden" name="to_branch_name"
                                                   value="<?php echo isset($sessionVals['to_branch_name']) ? $sessionVals['to_branch_name'] : ''; ?>">
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
    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#PopUpForModalWithForm"
            id="openModalWithForm"></button>
    <div class="modal fade" id="PopUpForModalWithForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title"
                        id="myModalLabel"><?php echo($lang == 'eng' ? 'Continue' : 'الإستمرار'); ?></h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div>
                        <p class=""><?php echo($lang == 'eng' ? 'Please enter your email address or mobile no. or ID no. that you used with this reservation.' : 'الرجاء إدخال بريدك الإلكتروني أو رقم هاتفك الجوال أو رقم الهوية
التي استخدمتها في الحجز'); ?></p></div>

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
    <div class="modal fade" id="siteUnderMaintenance" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo($lang == 'eng' ? 'Error' : 'خطأ'); ?></h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div>
                        <p><?php echo($lang == 'eng' ? $site_settings->maintenance_eng_desc : $site_settings->maintenance_arb_desc); ?></p>
                    </div>
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


    <!-- Modal -->
    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#msgPopupNoRedirect"
            id="openMsgPopupNoRedirect"></button>
    <div class="modal fade" id="msgPopupNoRedirect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="myModalLabel"></h4>
                </div>
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
    <div class="modal fade" id="surveyPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
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
    <div class="modal fade" id="msgPopupRedirect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="myModalLabel"></h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div><p class="responseMsg"></p></div>
                    <div class="twoBtnEd">
                        <br/>
                        <a href="javascript:void(0);" id="OKBtn" class="redishButtonRound"><input type="submit"
                                                                                                  class="redishButtonRound"
                                                                                                  value="OK"></a>
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
    <div class="modal fade" id="showUserEmailMsg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
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
    <div class="modal fade" id="term_n_Cond" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog bigOne" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo ($lang == 'eng' ? 'Terms & Conditions' : 'تطبق الشروط والأحكام'); ?></h4>
                </div>
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

    <div class="terms-condition modal fade" id="hl_instructions" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog bigOne" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel">@lang("labels.hl_instructions")</h4>
                </div>
                <div class="modal-body">
                    <p><?php echo($lang == 'eng' ? $site_settings->eng_hl_instructions : $site_settings->arb_hl_instructions); ?></p>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary btn-lg humanLessPopup" data-bs-toggle="modal"
            data-bs-target="#noHumanLessPopup" style="display: none;">Launch modal
    </button>
    <div class="terms-condition modal fade" id="noHumanLessPopup" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog bigOne modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel">@lang("labels.key_human_less")</h4>
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
    <div class="modal fade delLocMapPopUp" id="google_map_popup" tabindex="-1" role="dialog"
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

    <!--Special Car Description Modal-->
    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#showSpecialCarDescription"
            id="openSpecialCarDescription"></button>
    <div class="modal fade" id="showSpecialCarDescription" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
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
                    <button type="button" class="btn-close redirectToHome" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="sessionConflictLabel">@lang('labels.message')</h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div><p class="sessionConflictDesc"></p></div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .nav-col h2, .map-col h2{
            color: #6f6f6e;
            font-size: 16px;
        }
        .nav-col ul li a{
            color: #000000;
            border: 1px solid black;
            border-radius: 5px;
            display: block;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 10px;
            padding: 5px 0;
            font-weight: 500;
            transition: ease all 0.5s;
        }
        .nav-col ul{
            height: 380px;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 5px;
        }
        .nav-col ul li a:hover, .nav-col ul li a.active {
            color: #ffffff;
            background: #fe7e00;
            border: 1px solid #fe7e00;
        }
        .g-map{
            height: 380px;
            /*background-color: #d4d4d4;*/
            border-radius: 5px;
        }

        .btn-success-custom, .btn-success-custom:hover {
            color: #ffffff !important;
            background: #fe7e00 !important;
            border: 1px solid #fe7e00 !important;
            margin-top: 20px !important;
            width: 100%;
        }

        .image-container {
            position: relative; /* Ensure the container is relative for absolute positioning */
            display: inline-block; /* or block, depending on your layout needs */
        }

        .image-container::after {
            content: ''; /* Required for pseudo-elements */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Adjust the opacity here (0.5 is 50% black) */
        }

        .nav-col ul::-webkit-scrollbar {
            width: 5px; /* Width of the scrollbar */
            height: 5px; /* Height of the scrollbar */
        }

        .nav-col ul::-webkit-scrollbar-track {
            background: #dfdfdf; /* Color of the scrollbar track */
        }

        .nav-col ul::-webkit-scrollbar-thumb {
            background: #fe7e00; /* Color of the scrollbar thumb */
        }

        .nav-col ul::-webkit-scrollbar-thumb:hover {
            background: #fe7e00; /* Color of the scrollbar thumb on hover */
        }
    </style>

        <!--Limousine Mode Pickup Branches Modal-->
        <div class="modal fade modal-lg" id="limousineModePickupBranchesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content pt-2 pb-2">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title"><?php echo ($lang == 'eng' ? 'Select Pickup Destination' : 'اختر موقع الاستلام'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="nav-col">
                                    <h2><?php echo ($lang == 'eng' ? 'Select Destination' : 'اختر الوجهة'); ?></h2>
                                    <ul>
                                        @foreach(custom::get_limousine_branches() as $limousine_branch)
                                            <li><a href="javascript:void(0);" onclick="showMapForLimousineBranch($(this), 'pickup', {{$limousine_branch->branch_id}});">{{$lang == 'eng' ? $limousine_branch->eng_branch_name : $limousine_branch->arb_branch_name}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="map-col">
                                    <h2><?php echo ($lang == 'eng' ? 'Select Location in Map' : 'الرجاء تحديد الموقع على الخريطة'); ?></h2>
                                    <div class="g-map" id="pickup_map">
                                        <div class="image-container">
                                            <img alt="placeholder" class="img-fluid" src="<?php echo custom::baseUrl('public/frontend/images/map-image-ksa.png'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="confirm-btn hide">
                                    <button type="button" class="btn btn-success-custom" onclick="closeThisPopupAndShowDropoffPopup();"><?php echo ($lang == 'eng' ? 'Confirm Location' : 'تأكيد الموقع'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Limousine Mode Pickup Branches Modal-->


        <!--Limousine Mode Dropoff Branches Modal-->
        <div class="modal fade modal-lg" id="limousineModeDropoffBranchesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content pt-2 pb-2">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h4 class="modal-title"><?php echo ($lang == 'eng' ? 'Select Dropoff Destination' : 'اختر موقع التسليم'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="nav-col">
                                    <h2><?php echo ($lang == 'eng' ? 'Select Destination' : 'اختر الوجهة'); ?></h2>
                                    <ul>
                                        @foreach(custom::get_limousine_branches() as $limousine_branch)
                                            <li><a href="javascript:void(0);" onclick="showMapForLimousineBranch($(this), 'dropoff', {{$limousine_branch->branch_id}});">{{$lang == 'eng' ? $limousine_branch->eng_branch_name : $limousine_branch->arb_branch_name}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="map-col">
                                    <h2><?php echo ($lang == 'eng' ? 'Select Location in Map' : 'الرجاء تحديد الموقع على الخريطة'); ?></h2>
                                    <div class="g-map" id="dropoff_map">
                                        <div class="image-container">
                                            <img alt="placeholder" class="img-fluid" src="<?php echo custom::baseUrl('public/frontend/images/map-image-ksa.png'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="confirm-btn hide">
                                    <button type="button" class="btn btn-success-custom" data-bs-dismiss="modal" aria-label="Close"><?php echo ($lang == 'eng' ? 'Confirm Location' : 'تأكيد الموقع'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Limousine Mode Dropoff Branches Modal-->




    <div class="footerLinks footerLinks-new-style">
        <div class="container-md">
            <div class="footer-links-row">
                <div class="quickLinkTxt">
                    @lang('labels.quick')
                    @lang('labels.links')
                </div>
                <ul>
                    <li><a href="<?php echo $lang_base_url . '/about-us'; ?>">@lang('labels.about_key_car_rental')</a></li>
                    <li><a href="<?php echo $lang_base_url . '/loyalty'; ?>">@lang('labels.loyalty_program')</a></li>
                    <li><a href="<?php echo $lang_base_url . '/services'; ?>">@lang('labels.services')</a></li>
                    <li><a href="<?php echo $lang_base_url . '/register'; ?>">@lang('labels.registration')</a></li>
                    <li><a href="<?php echo $lang_base_url . '/fleet'; ?>">@lang('labels.key_fleet')</a></li>
                    <li><a href="<?php echo $lang_base_url . '/careers'; ?>">@lang('labels.career')</a></li>
                    <li><a href="<?php echo $lang_base_url . '/lease'; ?>">@lang('labels.corporate_sales')</a></li>
                    <li><a href="<?php echo $lang_base_url . '/offers'; ?>">@lang('labels.program_awards_footer')</a></li>
                    <li><a href="<?php echo $lang_base_url . '/offers'; ?>">@lang('labels.program_awards_footer')</a></li>
                    <li><a href="<?php echo $lang_base_url . '/car-selling'; ?>">@lang('labels.car_selling')</a></li>
                    <li><a href="<?php echo $lang_base_url . '/location'; ?>">@lang('labels.location')</a></li>
                    <li><a href="<?php echo $lang_base_url . '/contact-us'; ?>">@lang('labels.contact_us')</a></li>
                <!--<li><a href="<?php echo $lang_base_url . '/book-car'; ?>">@lang('labels.book_a_car')</a></li>
                    <li><a href="<?php //echo $lang_base_url . '/news'; ?>">@lang('labels.news_center')</a></li>-->
                <!--<li><a href="<?php //echo $lang_base_url . '/faqs'; ?>">@lang('labels.frequently_asked_questions')</a></li>-->
                <!--<li><a href="<?php echo $lang_base_url . '/register'; ?>">@lang('labels.manage_bookings')</a></li>-->

                </ul>
            </div>


            <?php if ($site_settings->cc_company == 'hyper_pay') { ?>
        <!-- <div class="payment_methods_img" style="text-align: {{$lang == 'eng' ? 'right' : 'left'}};">
                    {{--<img src="{{custom::baseurl().'/public/frontend/images/key-payment-methods-'.$lang.'.jpg'}}">--}}
                    <img src="{{custom::baseurl().'/public/frontend/images/key-icon-'.$lang.'.png?v=1.2'}}" style="width: 320px;">
                </div>-->
            <?php } ?>
            <!--<div class="chatBtn">
                Chat Online
            </div>-->
            <?php
            if ($api->display_chat == 'yes') {
                echo $api->tawk_script;
            }
            ?>
        </div>

    </div>
        <style>
            /*.footerCopyRight .app-btns {
                float: <?php echo ($lang == 'eng' ? 'right' : 'left');  ?> !important;
                padding: 10px 0 0 20px !important;
            }

            .footerCopyRight .app-btns img {
                width: 88% !important;
            }*/
        </style>
    <div class="footerCopyRight">
        <div class="container-md">
            <div class="footer-content">
                <p class="copyText">@lang('labels.copyrights') <?php echo date('Y'); ?>. @lang('labels.all_rights_reserved').</p>
                <div class="footer-logos">
                    <img src="{{custom::baseurl().'/public/frontend/images/footer-logos.png?v=0.1'}}" alt="">
                </div>
            </div>
        <!--<ul>
                <li><a href="<?php echo $lang_base_url . '/careers'; ?>">@lang('labels.careers')</a></li>
            </ul>
            <ul class="btn-box app-btns">
                <li>
                    <a href="https://itunes.apple.com/us/app/key-car-rental/id1282284664?ls=1&amp;mt=8" target="_blank">
                        <img src="https://kra.ced.sa/public/frontend/images/icon-apple.png" alt="">
                    </a>
                </li>
                <li>
                    <a href="https://play.google.com/store/apps/details?id=comcom.key&amp;hl=en" target="_blank">
                        <img src="https://kra.ced.sa/public/frontend/images/icon-android.png" alt="">
                    </a>
                </li>
                <li>
                    <a href="https://appgallery.huawei.com/#/app/C103329457" target="_blank">
                        <img src="https://kra.ced.sa/public/frontend/images/icon-huawei.png?v=0.6" alt="">
                    </a>
                </li>
            </ul>
            <div class="footFollowUs">
                <span>@lang('labels.follow_us')</span>
                <ul>
                    <?php $social = custom::social_links(); ?>
                    <?php if($social->facebook_link != ""){ ?>
                    <li><a href="<?php echo $social->facebook_link; ?>" target="_blank" class="facebook"></a></li>
                    <?php } if($social->twitter_link != ""){ ?>
                    <li><a href="<?php echo $social->twitter_link; ?>" target="_blank" class="twitter"></a></li>
                    <?php } if($social->linkedin_link != ""){ ?>
                    <li><a href="<?php echo $social->linkedin_link; ?>" target="_blank" class="linkedin"></a></li>
                    <?php } if($social->instagram_link != ""){ ?>
                    <li><a href="<?php echo $social->instagram_link; ?>" target="_blank" class="instagram"></a></li>
                    <?php } if($social->youtube_link != ""){ ?>
                    <li><a href="<?php echo $social->youtube_link; ?>" target="_blank" class="youtube"></a></li>
                    <?php } ?>
                </ul>
            </div>-->
            <div class="clearfix"></div>
        </div>
    </div>

    @if(Session::get('logged_in_from_frontend') == true)
        <input type="hidden" class="user_email_for_pending_survey" value="<?php echo Session::get('user_email'); ?>">
        <input type="hidden" class="user_id_no_for_pending_survey" value="<?php echo Session::get('user_id_no'); ?>">
    @endif

</footer>
<?php }
// $manual_version = '5.0';
$manual_version = rand();
?>
<script>
    var write_lbl = '<?php echo trans('labels.write'); ?>';
    var select_lbl = '<?php echo trans('labels.select'); ?>';
    var company_name_lbl = '<?php echo ($lang == 'eng' ? 'Company Name' : 'اسم الشركة'); ?>';
    var job_title_lbl = '<?php echo ($lang == 'eng' ? 'Job Title' : 'المسمى الوظيفي'); ?>';
    var from_date_lbl = '<?php echo ($lang == 'eng' ? 'From Date' : 'من تاريخ'); ?>';
    var to_date_lbl = '<?php echo ($lang == 'eng' ? 'To Date' : 'إلى تاريخ'); ?>';
</script>


<script src="<?php echo $base_url; ?>/public/frontend/js/script.js?v=<?php echo $manual_version; ?>"></script>
<script src="<?php echo $base_url; ?>/public/frontend/js/functions.js?v=<?php echo $manual_version; ?>"></script>
<script>
    var daily_with_delivery_flow = '<?php echo $site_settings->daily_with_delivery_flow; ?>';
    var monthly_with_delivery_flow = '<?php echo $site_settings->monthly_with_delivery_flow; ?>';
    var weekly_with_delivery_flow = '<?php echo $site_settings->weekly_with_delivery_flow; ?>';
    var subscription_with_delivery_flow = '<?php echo $site_settings->subscription_with_delivery_flow; ?>';
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

        $('.subscription_with_delivery_flow_tabs').hide();
        $('#is_subscription_with_delivery_flow').val(0);

        $('.limousine_mode_sub_tabs').hide();
        $('.isLimousine').val(0);

        if (!$(this).hasClass('active')) {
            $('#from_region_id').val('');
            $('#from_city_id').val('');
            $('#from_branch_id').val('');
            $('#to_city_id').val('');
            $('#to_branch_id').val('');
            $('#delivery_charges').val('');
            $('.from_branch_field_for_delivery').val('');
            $('.from_branch_field_for_pickup').val('');
            $('.to_branch_field_for_pickup').val('');
            $('.pickup_delivery_coordinate').val('');
            $('.dropoff_delivery_coordinate').val('');
            // $('#book_for_hours').val('');
        }


        $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeAttr('name');
        $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeClass('required-for-search');
        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeAttr('name');
        $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeClass('required-for-search');
        $('.limousine_mode_pickup').find('input').removeAttr('name');
        $('.limousine_mode_pickup').find('input').removeClass('required-for-search');
        $('.limousine_mode_dropoff').find('input').removeAttr('name');
        $('.limousine_mode_dropoff').find('input').removeClass('required-for-search');

        $('.searchButtons li').removeClass('active');
        $(this).addClass('active');

        $('.li_for_others').show();
        $('.li_for_others').find('#datepicker_to').addClass('required-for-search');

        $('.li_for_hourly_only').hide();
        $('.li_for_hourly_only').find('#book_for_hours').removeClass('required-for-search');

        $('.li_for_subscription_only').hide();
        $('.li_for_subscription_only').find('#subscribe_for_months').removeClass('required-for-search');


        if ($(this).attr('id') == 'delivery_tab') // If I am in delivery tab or hourly renting tab
        {
            $('.isDeliveryMode').val(1);
            $('.allIsOkForPickup').val(0);
            $('.allIsOkForDropoff').val(0);
            $('.pickup_mode_pickup, .pickup_mode_dropoff').addClass('hide');
            $('.delivery_mode_pickup, .delivery_mode_dropoff').removeClass('hide');
            $('.limousine_mode_pickup, .limousine_mode_dropoff').addClass('hide');

            $('.from_branch_field_for_delivery').attr('name', 'from_branch_name');
            $('.to_branch_field_for_delivery').attr('name', 'to_branch_name');
            $('.from_branch_field_for_delivery').addClass('required-for-search');

            $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeAttr('name');
            $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeClass('required-for-search');


        } else if ($(this).attr('id') == 'limousine_mode_tab') {
            $('.limousine_mode_sub_tabs').show();
            $('.limousine_mode_sub_tabs .pick-up-button.round-trip').trigger('click');

            $('.isDeliveryMode').val(0);
            $('.isLimousine').val(1);

            $('.allIsOkForPickup').val(0);
            $('.allIsOkForDropoff').val(0);


            $('.pickup_mode_pickup, .pickup_mode_dropoff').addClass('hide');
            $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');
            $('.limousine_mode_pickup, .limousine_mode_dropoff').removeClass('hide');

            $('.limousine_mode_pickup').find('input').attr('name', 'from_branch_name');
            $('.limousine_mode_dropoff').find('input').attr('name', 'to_branch_name');

            <?php if (isset($sessionVals['isRoundTripForLimousine']) && $sessionVals['isRoundTripForLimousine'] == 0) {?>
                $('.limousine_mode_sub_tabs .pick-up-button.one-way').trigger('click');
            <?php } else { ?>
                $('.limousine_mode_sub_tabs .pick-up-button.round-trip').trigger('click');
            <?php } ?>

        } else {
            if ($(this).attr('id') == 'hourly_renting_tab') {
                $('.isDeliveryMode').val(2);

                $('.li_for_others').hide();
                $('.li_for_others').find('#datepicker_to').removeClass('required-for-search');

                $('.li_for_hourly_only').show();
                $('.li_for_hourly_only').find('#book_for_hours').addClass('required-for-search');

            } else if ($(this).attr('id') == 'subscription_renting_tab') {

                $('.isDeliveryMode').val(4);

                $('.li_for_others').hide();
                $('.li_for_others').find('#datepicker_to').removeClass('required-for-search');

                $('.li_for_subscription_only').show();
                $('.li_for_subscription_only').find('#subscribe_for_months').addClass('required-for-search');

            } else if ($(this).attr('id') == 'monthly_renting_tab') {

                $('.isDeliveryMode').val(3);
            } else if ($(this).attr('id') == 'weekly_renting_tab') {

                $('.isDeliveryMode').val(5);
            } else {
                $('.isDeliveryMode').val(0);
            }
            $('.allIsOkForPickup').val(1);
            $('.allIsOkForDropoff').val(1);
            $('#delivery_charges').val('');
            $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');
            $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');
            $('.limousine_mode_pickup, .limousine_mode_dropoff').addClass('hide');

            $('.from_branch_field_for_pickup').attr('name', 'from_branch_name');
            $('.to_branch_field_for_pickup').attr('name', 'to_branch_name');
            $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').addClass('required-for-search');

            $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeAttr('name');
            $('.from_branch_field_for_delivery').removeClass('required-for-search');

        }

        if ($(this).attr('id') == 'monthly_renting_tab') {
            // make calendar to be min 1 month gap
            $('.serFormArea [name="pickup_date"]').addClass('datepicker_from_for_monthly_tab');
            $('.serFormArea [name="dropoff_date"]').addClass('datepicker_to_for_monthly_tab');
            $('.serFormArea [name="pickup_date"]').removeClass('datepicker_from_for_weekly_tab');
            $('.serFormArea [name="dropoff_date"]').removeClass('datepicker_to_for_weekly_tab');
        } else if ($(this).attr('id') == 'weekly_renting_tab') {
            // make calendar to be min 1 week gap
            $('.serFormArea [name="pickup_date"]').addClass('datepicker_from_for_weekly_tab');
            $('.serFormArea [name="dropoff_date"]').addClass('datepicker_to_for_weekly_tab');
            $('.serFormArea [name="pickup_date"]').removeClass('datepicker_from_for_monthly_tab');
            $('.serFormArea [name="dropoff_date"]').removeClass('datepicker_to_for_monthly_tab');
        } else {
            // normal calendar
            $('.serFormArea [name="pickup_date"]').removeClass('datepicker_from_for_monthly_tab');
            $('.serFormArea [name="dropoff_date"]').removeClass('datepicker_to_for_monthly_tab');
            $('.serFormArea [name="pickup_date"]').removeClass('datepicker_from_for_weekly_tab');
            $('.serFormArea [name="dropoff_date"]').removeClass('datepicker_to_for_weekly_tab');
        }

        if (
            ($(this).attr('id') == 'pickup_tab' && daily_with_delivery_flow == 'on') ||
            ($(this).attr('id') == 'monthly_renting_tab' && monthly_with_delivery_flow == 'on') ||
            ($(this).attr('id') == 'weekly_renting_tab' && weekly_with_delivery_flow == 'on') ||
            ($(this).attr('id') == 'subscription_renting_tab' && subscription_with_delivery_flow == 'on')
        ) {
            $('.subscription_with_delivery_flow_tabs').show();

            <?php if (isset($sessionVals['is_subscription_with_delivery_flow']) && $sessionVals['is_subscription_with_delivery_flow'] == 1) {?>
            $('.subscription_with_delivery_flow_tabs .pick-up-button.delivery').trigger('click');
            <?php } else { ?>
            $('.subscription_with_delivery_flow_tabs .pick-up-button.pickup').trigger('click');
            <?php } ?>
        }
    });

    $('.subscription_with_delivery_flow_tabs .pick-up-button').click(function() {
       if ($(this).hasClass('pickup')) {
           $('.subscription_with_delivery_flow_tabs .pick-up-button.pickup').addClass('active');
           $('.subscription_with_delivery_flow_tabs .pick-up-button.delivery').removeClass('active');

           $('.allIsOkForPickup').val(1);
           $('.allIsOkForDropoff').val(1);
           $('#delivery_charges').val('');
           $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');
           $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');

           $('.from_branch_field_for_pickup').attr('name', 'from_branch_name');
           $('.to_branch_field_for_pickup').attr('name', 'to_branch_name');
           $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').addClass('required-for-search');

           $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').removeAttr('name');
           $('.from_branch_field_for_delivery').removeClass('required-for-search');

           $('#is_subscription_with_delivery_flow').val(0);

        } else if ($(this).hasClass('delivery')) {
           $('.subscription_with_delivery_flow_tabs .pick-up-button.delivery').addClass('active');
           $('.subscription_with_delivery_flow_tabs .pick-up-button.pickup').removeClass('active');

           $('.allIsOkForPickup').val(0);
           $('.allIsOkForDropoff').val(0);
           $('.pickup_mode_pickup, .pickup_mode_dropoff').addClass('hide');
           $('.delivery_mode_pickup, .delivery_mode_dropoff').removeClass('hide');

           $('.from_branch_field_for_delivery').attr('name', 'from_branch_name');
           $('.to_branch_field_for_delivery').attr('name', 'to_branch_name');
           $('.from_branch_field_for_delivery').addClass('required-for-search');

           $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeAttr('name');
           $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeClass('required-for-search');

           $('#is_subscription_with_delivery_flow').val(1);
       }
    });

    $('.limousine_mode_sub_tabs .pick-up-button').click(function() {
        if ($(this).hasClass('round-trip')) {
            $('.limousine_mode_sub_tabs .pick-up-button.round-trip').addClass('active');
            $('.limousine_mode_sub_tabs .pick-up-button.one-way').removeClass('active');
            $('.isRoundTripForLimousine').val(1);
            $('.li_for_others.dropoff_time_sec').show();
            $('.li_for_others.dropoff_time_sec').find('#datepicker_to').addClass('required-for-search');
        } else if ($(this).hasClass('one-way')) {
            $('.limousine_mode_sub_tabs .pick-up-button.round-trip').removeClass('active');
            $('.limousine_mode_sub_tabs .pick-up-button.one-way').addClass('active');
            $('.isRoundTripForLimousine').val(0);
            $('.li_for_others.dropoff_time_sec').hide();
            $('.li_for_others.dropoff_time_sec').find('#datepicker_to').removeClass('required-for-search');
        }
    });

    $(document).ready(function () {
        //$('#pickUpTime').timepicker({});
        //$('#dropOffTime').timepicker({});
        /*$('input.timepicker').timepicker({
            timeFormat: 'h:mm',
            interval: 30,
            minTime: '10:00',
            maxTime: '23:30',
            defaultTime: '10:30',
            startTime: '10:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });*/
        $('.custom-combobox-toggle').on('click', function (e) {
            e.preventDefault();
            $('.ui-menu').addClass('custom-scrol');
        });

        <?php if (isset($flash_message)) { ?>
            $('.responseTitle').html((lang == 'eng' ? 'Success' : 'بنجاح'));
            $('.responseMsg').html('<?php echo $flash_message; ?>');
            $('#openMsgPopupNoRedirect').click();
        <?php } ?>

    });

    function check_if_mada_card(card_number) {
        var mada_bins = ["440647","440795","446404","457865","457997","539931","558848","557606","417633","468540","468541","468542","468543","446393","409201","458456","484783","462220","455708","455036","486094","486095","486096","440533","489318","489319","445564","410685","406996","432328","428671","428672","428673","446672","543357","434107","407197","407395","412565","431361","521076","588850","529415","535825","543085","524130","554180","549760","524514","529741","537767","535989","536023","513213","520058","585265","588983","588982","589005","531095","530906","532013","422817","422818","422819","428331","483010","483011","483012","589206","419593","439954","530060","531196"];
        if ($('#cardNumber').val().length > 5)
        {
            var starting_6_chars = card_number.substr(0, 6);
            if (mada_bins.indexOf(starting_6_chars) > -1)
            {
                $('#cardNumber').addClass('is-mada-card');
            } else {
                $('#cardNumber').removeClass('is-mada-card');
            }
        } else {
            $('#cardNumber').removeClass('is-mada-card');
        }
    }

    function closeThisPopupAndShowDropoffPopup() {
        $('#limousineModePickupBranchesModal').modal('hide');
        $('#limousineModeDropoffBranchesModal').modal('show');
    }


</script>


</body>
</html>