@extends('frontend.layouts.template')
@section('content')
    <style>
        .payFrmUserInfo .formFields li {
            padding-bottom: 13px;
        }

        .payFrmUserInfo .formFields li label {
            padding-bottom: 0;
        }

        .paymentOption ul li.p-method {
            width: 20% !important;
        }

        .paymentOption ul li .imgBox img {
            max-height: 28px;
        }

        @media (min-width: 992px) {
            .payLoginFrm input:first-of-type {
                width: 80%;
                width: calc(100% - 180px);
            }

            .payLoginFrm input.edBtn {
                position: static;
                min-width: 110px;
            }
        }

        @media (max-width: 991px) {
            .payLoginFrm input:first-of-type {
                width: 90%;
            }
        }

        @media (max-width: 480px) {
            .payLoginFrm input:first-of-type {
                width: 100%;
            }
        }
    </style>
    <section class="searchNbookSec">
        <div class="container-md <?php echo custom::addClass(); ?>">
            <div class="bookingStepsLink">
                <ul>
                    <li>
                        <div><span>01</span> @lang('labels.select_a_vehicle')</div>
                    </li>
                    <li>
                        <div><span>02</span> @lang('labels.price_n_extras')</div>
                    </li>
                    <li class="active">
                        <div><span>03</span> @lang('labels.payment')</div>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <?php
    if ($minus_discount == true) {
        $payable_amount = ((Session::get('rent_per_day') * $booking_info['days']) - ($promo_discount_amount * $booking_info['days'])) + ($cdw * $booking_info['days']) + ($cdw_plus * $booking_info['days']) + ($gps * $booking_info['days']) + ($extra_driver * $booking_info['days']) + ($baby_seat * $booking_info['days']) + Session::get('dropoff_charges_amount');
    } else {
        $payable_amount = Session::get('rent_per_day') * $booking_info['days'] + ($cdw * $booking_info['days']) + ($cdw_plus * $booking_info['days']) + ($gps * $booking_info['days']) + ($extra_driver * $booking_info['days']) + ($baby_seat * $booking_info['days']) + Session::get('dropoff_charges_amount');
    }
    ?>
    <section class="pricePageSec">
        <div class="container">
            <div class="pricePgWrapper ">
                <div class="leftCol">
                    <div class="carSumery peddLftSet">
                        <div class="bookName">
                            <h2>
                                <?php echo($lang == 'eng' ? $car_info->car_type_eng_title : $car_info->car_type_arb_title); ?> <?php echo ($lang == 'eng' ? $car_info->eng_title : $car_info->arb_title) . ' ' . $car_info->year; ?>
                            </h2>
                        </div>
                        <h3><?php echo $booking_info['days']; ?> @lang('labels.days') <span
                                    class="totalPriceWithVat"><?php echo number_format($payable_amount + Session::get('vat'), 2); ?></span> @lang('labels.currency')
                        </h3>
                    </div>
                    <div class="imgBox peddLftSet">
                        <?php
                        if ($car_info->image1 != '') {
                            $car_image_path = $base_url . '/public/uploads/' . $car_info->image1;
                        } else {
                            $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                        }
                        ?>
                        <img src="<?php echo $car_image_path; ?>" alt="Car" height="132" width="274"/>
                    </div>
                    <div class="car-details">
                        <div class="basicDetails">
                            <div class="col twoBig peddLftSet">
                                <label>@lang('labels.pick_up')</label>
                                <ul>
                                    <li>
                                        <?php echo date('d M Y', strtotime($booking_info['pickup_date'])) . ', ' . $booking_info['pickup_time']; ?>
                                    </li>
                                    <li title="JEDDAH AIRPORT, NORTH TERMINAL">
                                        <?php echo($lang == 'eng' ? $pickup_branch_info->eng_title : $pickup_branch_info->arb_title); ?>
                                    </li>
                                    <li>
                                        <?php echo($lang == 'eng' ? $pickup_branch_info->city_eng_title : $pickup_branch_info->city_arb_title); ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="col twoBig peddLftSet">
                                <label>@lang('labels.drop_off')</label>
                                <ul>
                                    <li>
                                        <?php echo date('d M Y', strtotime($booking_info['dropoff_date'])) . ', ' . $booking_info['dropoff_time']; ?>
                                    </li>
                                    <li title="JEDDAH AIRPORT, NORTH TERMINAL">
                                        <?php
                                        echo($lang == 'eng' ? $dropoff_branch_info->eng_title : $dropoff_branch_info->arb_title);
                                        ?>
                                    </li>
                                    <li>
                                        <?php echo($lang == 'eng' ? $dropoff_branch_info->city_eng_title : $dropoff_branch_info->city_arb_title); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rightCol">
                    <!--start order summary-->
                    <a href="javascript:void(0);"
                       id="btn_orderSummary"><?php echo($lang == 'eng' ? 'Order Summary' : 'ملخص الطلب');?></a>
                    <div class="orderSummary" style="display:none;">
                        <div class="summary-col">
                            <h4>@lang('labels.rental_period')</h4>
                            <span class="summ-price"><?php echo $booking_info['days']; ?> @lang('labels.days')</span>
                        </div>
                        <div class="summary-col">
                            <h4>@lang('labels.rent_per_day')</h4>
                            <span class="summ-price"
                                  id="rent_per_day_span"><?php echo number_format(Session::get('rent_per_day'), 2); ?> @lang('labels.currency')</span>
                        </div>
                        <?php if ($cdw > 0 || $cdw_plus > 0 || $gps > 0 || $extra_driver > 0 || $baby_seat > 0){ ?>
                        <div class="summary-col summary-col-with-label">
                            <label>@lang('labels.extra_services')</label>
                            <ul>
                                <?php if ($cdw > 0)
                                {?>
                                <li>
                                    <h4>@lang("labels.lost_damage")</h4>
                                    <span><?php echo $cdw; ?> @lang('labels.currency')</span>
                                </li>
                                <?php } ?>

                                <?php if ($cdw_plus > 0)
                                {?>
                                <li>
                                    <h4>@lang("labels.cdw_plus")</h4>
                                    <span><?php echo $cdw_plus; ?> @lang('labels.currency')</span>
                                </li>
                                <?php } ?>

                                <?php if ($gps > 0)
                                {?>
                                <li>
                                    <h4>@lang("labels.gps")</h4>
                                    <span><?php echo $gps; ?> @lang('labels.currency')</span>
                                </li>
                                <?php } ?>

                                <?php if ($extra_driver > 0)
                                {?>
                                <li>
                                    <h4>@lang("labels.extra_driver")</h4>
                                    <span><?php echo $extra_driver; ?> @lang('labels.currency')</span>
                                </li>
                                <?php } ?>

                                <?php if ($baby_seat > 0)
                                {?>
                                <li>
                                    <h4>@lang("labels.baby_protection")</h4>
                                    <span><?php echo $baby_seat; ?> @lang('labels.currency')</span>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php } ?>

                        <div class="summary-col">
                            <h4><?php echo($lang == 'eng' ? 'Total per 1 day' : 'المجموع اليومي'); ?></h4>
                            <span class="summ-price"
                                  id="total_per_1_day"><?php echo number_format((Session::get('rent_per_day') + $cdw + $cdw_plus + $gps + $extra_driver + $baby_seat), 2); ?> @lang('labels.currency')</span>
                        </div>

                        <?php if (Session::get('dropoff_charges_amount') > 0){
                        $dropoff_charges = Session::get('dropoff_charges_amount');
                        ?>
                        <div class="summary-col">
                            <h4>@lang('labels.dropoff_charges')</h4>
                            <span class="summ-price"><?php echo Session::get('dropoff_charges_amount'); ?> @lang('labels.currency')</span>
                        </div>
                        <?php }else {
                            $dropoff_charges = 0;
                        } ?>
                        <div class="">
                            <ul>
                                <li class="summary-col">
                                    <h4>@lang("labels.total_rent_for_capital") <?php echo $booking_info['days']; ?> @lang("labels.days")</h4>
                                    <span class="summ-price"
                                          id="rent_m_days_span"><?php echo number_format(((Session::get('rent_per_day') + $cdw + $cdw_plus + $gps + $extra_driver + $baby_seat) * $booking_info['days']), 2); ?> @lang("labels.currency")</span>
                                </li>
                                <li class="containsRedeemDiscount summary-col" style="display: none;">
                                    <h4><?php echo($lang == 'eng' ? 'Discount availed on redeem' : 'سيتم تطبيق خصم نقاط الولاء'); ?></h4>
                                    <span class="summ-price" id="discount_on_redeem">0</span>
                                </li>
                                <?php if (Session::get('coupon_applied') != true)
                                {
                                if (isset($promo_discount) && $promo_discount_amount > 0)
                                { ?>
                                <li class="discount_on_promo_auto summary-col">
                                    <h4>@lang("labels.discount_on_promo")</h4>
                                    <span class="discount summ-price"><?php echo number_format($promo_discount_amount * $booking_info['days'], 2); ?> @lang("labels.currency")</span>
                                </li>
                                <?php }
                                }
                                ?>

                                <?php if (Session::get('coupon_applied') != true) {
                                    $styling = 'style="display: none;"';
                                } else {
                                    $styling = '';
                                }?>
                                <li class="discount_on_promo_code summary-col" <?php echo $styling; ?>>
                                    <h4>@lang("labels.discount_on_promo_code") </h4>
                                    <span class="discount summ-price"><?php echo(Session::get('coupon_applied') == true ? number_format(Session::get('promo_discount_amount') * $booking_info['days'], 2) : ''); ?> @lang("labels.currency")</span>
                                </li>

                                <li class="summary-col finalPrice">
                                    <h4><?php echo($lang == 'eng' ? 'Total Amount' : 'المبلغ الإجمالي'); ?></h4>
                                    <span class="summ-price"
                                          id="total_amount"><?php echo number_format(((Session::get('rent_per_day') + $cdw + $cdw_plus + $gps + $extra_driver + $baby_seat + $dropoff_charges - $promo_discount_amount) * $booking_info['days']), 2); ?> @lang("labels.currency")</span>
                                </li>

                                <?php if (Session::get('vat') > 0)
                                { ?>
                                <li class="summary-col">
                                    <h4>@lang("labels.vat_applicable") (<?php echo Session::get('vat_percentage'); ?>
                                        %)</h4>
                                    <span class="summ-price"
                                          id="show_vat_applied"><?php echo number_format(Session::get('vat'), 2); ?> @lang("labels.sar")</span>
                                </li>
                                <?php } ?>


                                <li class="summary-col finalPrice">
                                    <h4 class="<?php echo($lang == 'eng' ? '' : 'totalWithVat'); ?>"> @lang("labels.you_pay_total")</h4>
                                    <span class="summ-price"><i
                                                id="showTotalAmount"><?php echo number_format($payable_amount + Session::get('vat'), 2); ?></i>@lang("labels.sar")</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--end order summary-->
                    <div class="payLoginFrm">
                        <form action="#" method="post" class="innerWrapFrm">
                            <label><?php echo($lang == 'eng' ? 'Search For Driver Details' : 'البحث عن مستخدم سابق'); ?> </label>
                            <input type="text"
                                   placeholder="<?php echo($lang == 'eng' ? 'WRITE (Email \ ID number \ Mobile No) To Search' : 'اكتب (البريد الإلكتروني / رقم الهوية)'); ?>"
                                   id="get_driver_by"/>
                            <input type="button" class="edBtn redishButtonRound" onclick="getDriverDetails();"
                                   id="getDriverInfo" value="<?php echo($lang == 'eng' ? 'Find Driver' : 'البحث'); ?>"/>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                    <form method="post" action="<?php echo $lang_base_url; ?>/book_now_for_corporate"
                          class="bookNowForCorporateForm">
                        <div class="payFrmUserInfo">
                            <ul class="formFields">
                                <li class="oneRow">
                                    <input type="text" placeholder="@lang('labels.first_name') *" name="first_name"
                                           class="required" maxlength="20"/>
                                    <input type="text" placeholder="@lang('labels.last_name') *" name="last_name"
                                           class="required" maxlength="20"/>
                                </li>
                                <li class="phone-field">
                                    <input type="text" placeholder="@lang('labels.mobile_no'). *"
                                           class="phone required mobile_no number"/>
                                    <input type="hidden" name="mobile_no" class="intTelNo">
                                </li>
                                <li>
                                    <input type="email" name="email"
                                           placeholder="<?php echo($lang == 'eng' ? 'Email Address' : 'البريد الإلكتروني'); ?> *"
                                           class="required customer_email"/>
                                </li>
                                <li class="oneRow">
                                    <select id="id_type_at_checkout" class="selectpicker required id_type"
                                            name="id_type" onchange="ValidationsWithIdType($(this).val(), 0);">
                                        <option value=""
                                                selected><?php echo($lang == 'eng' ? 'ID Type' : 'نوع الهوية'); ?> *
                                        </option>
                                        <?php
                                        foreach ($id_types as $id_type){
                                        ?>
                                        <option value="<?php echo $id_type->ref_id; ?>"><?php echo($lang == 'eng' ? $id_type->eng_title : $id_type->arb_title); ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="text" name="id_no"
                                           placeholder="<?php echo($lang == 'eng' ? 'ID Number' : 'رقم الهوية'); ?> *"
                                           class="required customer_id_no id_no"/>
                                </li>
                                <li id="contains_sponsor_name" style="display: none;">
                                    <input type="text" placeholder="@lang('labels.sponsor')" name="sponsor"
                                           id="sponsorName" disabled/>
                                </li>
                                <li>
                                    <select id="gender" name="gender" class="required">
                                        <option value=""><?php echo($lang == 'eng' ? 'Gender' : 'الجنس'); ?> *</option>
                                        <option value="male"><?php echo($lang == "eng" ? "Male" : "ذكر"); ?></option>
                                        <option value="female"><?php echo($lang == "eng" ? "Female" : "انثى"); ?></option>
                                    </select>
                                </li>
                                <li>
                                    <input type="text"
                                           placeholder="<?php echo($lang == 'eng' ? 'License Number' : 'رقم الرخصة'); ?> *"
                                           name="license_no" class="required"/>
                                </li>
                                <?php if(custom::isCorporateLoyalty()) { ?>
                                <li>
                                    <input type="text"
                                           placeholder="<?php echo($lang == 'eng' ? 'Agent Emp Number' : 'رقم الموظف'); ?> *"
                                           name="agent_emp_number" class="required"/>
                                </li>
                                <li>&nbsp;</li>
                                <?php } ?>

                            </ul>
                            <?php if ($site_settings->cc_company == 'hyper_pay') { ?>
                            <h5 style="text-align: center;"><?php echo($lang == 'eng' ? 'Billing Information' : 'معلومات الفاتورة'); ?></h5>
                            <?php } ?>
                            <ul class="formFields">
                                <!-- Fields needed for Hyper Pay only -->
                                <?php if ($site_settings->cc_company == 'hyper_pay') { ?>
                                <li>
                                    <input type="text" class="required" name="address_street"
                                           placeholder="@lang('labels.address_street')">
                                </li>
                                <li class="oneRow">
                                    <input type="text" class="required" name="address_city" placeholder="@lang('labels.address_city')">
                                    <input type="text" class="required" name="address_state" placeholder="@lang('labels.address_state')">
                                </li>
                                <li class="oneRow">
                                    <select class="selectpicker required" name="address_country">
                                        <option value="" selected>@lang('labels.address_country')</option>
                                        <?php
                                        foreach ($address_countries as $address_country)
                                        {
                                        if ($address_country->country_code == 'SA') {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        ?>
                                        <option value="<?php echo $address_country->country_code; ?>" <?php echo $selected; ?>><?php echo($lang == 'eng' ? ucwords($address_country->eng_country) : $address_country->arb_country); ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                    <input type="text" class="required number" name="address_post_code"
                                           placeholder="@lang('labels.address_post_code')">
                                </li>
                                <?php } ?>

                                <li class="acceptTnC">
                                    <input id="checkbox1" type="checkbox" class="accept_terms" name="checkbox"
                                           value="1">
                                    <label for="checkbox1">@lang("labels.i_accept_the")
                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                           data-bs-target="#term_n_Cond">@lang("labels.terms_and_conditions"). *</a>
                                    </label>
                                </li>
                            </ul>
                            <?php if (Session::get('coupon_applied') != true){

                            if (isset($promo_discount) && $promo_discount_amount > 0){ ?>

                            <span class="discountAutoAppliedMsg">@lang("labels.discount_message")</span>

                            <?php } } ?>
                        </div>
                        <div class="payFrmUserInfo">
                            <div class="paymentOption heading">
                                <ul>
                                    <li>
                                        <h2>
                                            @lang("labels.payment") @lang("labels.method")
                                        </h2>
                                    </li>
                                    <li style="text-align: center;">
                                        <?php if (Session::get('error_message_payment') != '') {
                                            echo '<span style="color: red;font-weight: bold;">';
                                            echo Session::get('error_message_payment');
                                            echo '</span>';
                                            Session::forget('error_message_payment');
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="paymentOption objects">
                                <?php
                                $payment_method_ok = false;
                                $site_settings = custom::site_settings();
                                ?>
                                <ul>
                                    <?php if ($site_settings->mada == 1){ ?>
                                    <li class="p-method">
                                        <input id="CreditCardMada" name="payment_method" data-is-mada="1" value="cc"
                                               class="showHideOlpIdField" type="radio" checked>
                                        <label for="CreditCardMada" style="display: block !important;">
                                            <div class="imgBox" style="margin-top: 10px;">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/icon-mada-logo.png?v=1.0"
                                                     alt="Card" width="35" height="26">
                                            </div>
                                            <p><?php echo $lang == 'eng' ? "mada" : "بطاقة مدى"; ?></p>
                                        </label>
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->cc == 1){ ?>
                                    <li class="p-method">
                                        <input id="CreditCard" name="payment_method" data-is-mada="0" value="cc"
                                               class="showHideOlpIdField" type="radio">
                                        <label for="CreditCard">
                                            <div class="imgBox" style="margin-top: 8px;">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/ico-visa-master.png?v=1.1"
                                                     alt="Card" width="35" height="26">
                                            </div>
                                            <p><?php echo $lang == 'eng' ? "Credit Card" : "البطاقة الإئتمانية"; ?></p>
                                        </label>
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->amex == 1 && $site_settings->cc_company == 'hyper_pay'){ ?>
                                    <li class="p-method">
                                        <input id="CreditCardAmex" name="payment_method" data-is-mada="4" value="cc"
                                               class="showHideOlpIdField" type="radio">
                                        <label for="CreditCardAmex">
                                            <div class="imgBox" style="margin-top: 3px;">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/amex.png?v=1.0"
                                                     alt="Card" width="35" height="26">
                                            </div>
                                            <p><?php echo $lang == 'eng' ? "Amex" : "Amex"; ?></p>
                                        </label>
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->stc_pay == 1 && $site_settings->cc_company == 'hyper_pay'){ ?>
                                    <li class="p-method">
                                        <input id="CreditCardSTCPay" name="payment_method" data-is-mada="3" value="cc"
                                               class="showHideOlpIdField" type="radio">
                                        <label for="CreditCardSTCPay" style="display: block !important;">
                                            <div class="imgBox" style="margin-top: 10px;">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/stc-pay.png?v=1.0"
                                                     alt="Card" width="35" height="26" style="width: 50px;">
                                            </div>
                                            <p><?php echo $lang == 'eng' ? "STC Pay" : "STC Pay"; ?></p>
                                        </label>
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->cash == 1)
                                    { ?>
                                    <li class="p-method">
                                        <input id="cash" name="payment_method" data-is-mada="0" value="cash"
                                               class="showHideOlpIdField"
                                               type="radio">
                                        <label for="cash" style="display: block !important;">
                                            <div class="imgBox" style="margin-top: 4px;">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/paymentOption_4.png?v=1.0"
                                                     alt="Cash" width="39" height="26">
                                            </div>
                                            <p>@lang("labels.cash")</p>
                                        </label>
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($corporate_customer_info->corporate_credit == 1)
                                    { ?>
                                    <li class="p-method">
                                        <input id="points" name="payment_method" data-is-mada="0"
                                               value="corporate_credit" type="radio">
                                        <label for="points" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/corporate_credit.png?v=1.1"
                                                     alt="Points">
                                            </div>
                                            <p><?php echo($lang == 'eng' ? 'Corporate Credit' : 'حساب إئتماني'); ?></p>
                                        </label>
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($corporate_customer_info->pay_later == 1)
                                    { ?>
                                    <li class="p-method">
                                        <input id="payLater" name="payment_method" data-is-mada="0" value="pay_later"
                                               type="radio">
                                        <label for="payLater" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/corporate_credit.png?v=1.1"
                                                     alt="Points">
                                            </div>
                                            <p>@lang('labels.pay_later')</p>
                                        </label>
                                    </li>
                                <?php $payment_method_ok = true; ?>
                                <?php } ?>
                            </div>

                            <?php if ($payment_method_ok) { ?>
                            <button type="button"
                                    class="payNBtn bookNowBtnForCorporate submitBtn submit_btn license_validate_btn">
                                @lang('labels.pay_now')
                                <span class="totalPriceWithVat"><?php echo $booking_info['days']; ?> @lang('labels.days') <?php echo number_format($payable_amount + Session::get('vat'), 2); ?> @lang('labels.currency')</span>
                            </button>
                            <?php } ?>

                        </div>
                        <!--When working on promo code apply, fill these fields please using jquery-->
                        <?php if (Session::get('coupon_applied') != true) {
                            if (isset($promo_discount)) {
                                $promotionId = $promo_discount->id;
                            } else {
                                $promotionId = 0;
                            }
                        } else {
                            $promotionId = Session::get('promotion_id');
                        }
                        ?>
                        <input type="hidden" name="total_rent_after_discount_on_promo"
                               id="total_rent_after_discount_on_promo"
                               value="<?php echo $total_amount_after_discount; ?>">
                        <input type="hidden" name="discount_amount_per_day" id="discount_amount_per_day"
                               value="<?php echo $promo_discount_amount; ?>">
                        <input type="hidden" name="promotion_id" id="promotion_id" value="<?php echo $promotionId; ?>">
                        <input type="hidden" name="isMada" id="isMada" value="1">
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $('#btn_orderSummary').click(function () {
                    $('.orderSummary').slideToggle("slow");
                });
                $('#loginFormOnPayment').click(function () {
                    $('.hiddenLoginForm').slideToggle("slow");
                });
            });
        </script>

    </section>
@endsection