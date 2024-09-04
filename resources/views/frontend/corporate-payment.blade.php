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

        .payFrmUserInfo .paymentOption.objects li p {
            display: block;
        {{($lang == 'eng' ? 'padding-left' : 'padding-right')}}: 28px;
        }

        .paymentOption ul li .imgBox img {
            max-height: 28px;
        }
        body.arb .payLoginFrm .innerWrapFrm input{
            width: 80%;
        }
        body.arb .intl-tel-input .phone{
            direction: rtl;
        }

        <?php  if(!custom::is_mobile()){?>
            body.arb.payment .payLoginFrm {
            margin-left: 0;
        }
        /*body.arb.payment .payLoginFrm .innerWrapFrm input.edBtn {
            width: 100%;
        }*/
        <?php }  ?>

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

    <?php
    $site_settings = custom::site_settings();
    if ((Session::get('search_data')['is_delivery_mode'] == 1 || Session::get('search_data')['is_subscription_with_delivery_flow'] == 1) && Session::get('search_data')['delivery_charges'] > 0) {
        $delivery_charges = (float)Session::get('search_data')['delivery_charges'];
    } else {
        $delivery_charges = 0;
    }
    $parking_fee = Session::get('search_data')['parking_fee'];
    $tamm_charges_for_branch = Session::get('search_data')['tamm_charges_for_branch'];
    $limousine_extra_charges = custom::get_limousine_extra_charges();
    $waiting_extra_hours = $limousine_extra_charges['waiting_extra_hours'];
    $waiting_extra_hours_charges = $limousine_extra_charges['waiting_extra_hours_charges'];
    $site_settings = custom::site_settings();
    if ($booking_info['is_delivery_mode'] == 4) {
        $booking_info['days'] = 30; // because 1 month is to be charged
    }

    $isLimousine = (isset(Session::get('search_data')['isLimousine']) ? Session::get('search_data')['isLimousine'] : 0);
    ?>

    <section class="searchNbookSec">
        <div class="container-md <?php echo custom::addClass(); ?>">
            <div class="search-main-container-new-design">
                <?php echo custom::deliveryPickupTabsArea($lang); ?>
                <div class="searchBarSec">
                    <div class="serText_1">
                        @lang('labels.book')
                        <span>@lang('labels.car')</span>
                    </div>
                    @include('frontend/layouts/search_area')
                </div>
            </div>
            <div class="bookingStepsLink">
                <ul>
                    {{--<li class="prev"><span>01</span> @lang('labels.booking_criteria')</li>--}}
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
    <section class="pricePageSec price-wrapper-new-design">
        <div class="container-md">
            <div class="pricePgWrapper ">
                <div class="leftCol">
                    <div class="leftColTopSec">
                        <div class="imgBox peddLftSet">
                            <?php
                            if ($car_info->image1 != '') {
                                $car_image_path = $base_url . '/public/uploads/' . $car_info->image1;
                            } else {
                                $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                            }
                            ?>
                            <img src="<?php echo $car_image_path; ?>" alt="{{ $lang == 'eng' ? $car_info->image1_eng_alt : $car_info->image1_arb_alt }}" height="132" width="274"/>
                        </div>
                        <div class="carSumery peddLftSet">
                            <div class="bookName">
                                <h2><?php echo($lang == 'eng' ? $car_info->car_type_eng_title : $car_info->car_type_arb_title); ?> <?php echo ($lang == 'eng' ? $car_info->eng_title : $car_info->arb_title) . ' ' . $car_info->year; ?>
                                    <span>@lang('labels.or_similar')</span></h2>
                                <div class="helpBox">
                                    <a class="click" href="javascript:void(0);">?</a>
                                    <p class="popTextP"><?php echo($lang == 'eng' ? $car_info->eng_description : $car_info->arb_description); ?></p>
                                </div>
                            </div>
                            <h3><?php echo($lang == 'eng' ? $car_info->car_category_eng_title : $car_info->car_category_arb_title); ?></h3>
                        </div>
                        <div class="basicDetails">
                            <div class="col twoBig peddLftSet">
                                <label>@lang('labels.pick_up')</label>
                                <ul>
                                    <li title="JEDDAH AIRPORT, NORTH TERMINAL">
                                        <img class="abImg"
                                             src="<?php echo $base_url; ?>/public/frontend/images/icon-location.png" alt="" >
                                        <?php

                                        echo ($lang == 'eng' ? $pickup_branch_info->eng_title : $pickup_branch_info->arb_title) . ", " . ($lang == 'eng' ? $pickup_branch_info->city_eng_title : $pickup_branch_info->city_arb_title); ?>
                                    </li>
                                    <li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/icon-calendar.png" alt="" > <?php echo date('d / m / Y', strtotime($booking_info['pickup_date'])); ?>
                                    </li>
                                    <li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/icon-clock-time.png" > <?php echo $booking_info['pickup_time']; ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="col twoBig peddLftSet">
                                <label>@lang('labels.drop_off')</label>
                                <ul>
                                    <li title="JEDDAH AIRPORT, NORTH TERMINAL">
                                        <img class="abImg"
                                             src="<?php echo $base_url; ?>/public/frontend/images/icon-location.png" alt="" >
                                        <?php echo ($lang == 'eng' ? $dropoff_branch_info->eng_title : $dropoff_branch_info->arb_title) . ", " . ($lang == 'eng' ? $dropoff_branch_info->city_eng_title : $dropoff_branch_info->city_arb_title); ?>
                                    </li>
                                    <li><img class="abImg"
                                             src="<?php echo $base_url; ?>/public/frontend/images/icon-calendar.png" alt="" > <?php echo date('d / m / Y', strtotime($booking_info['dropoff_date'])); ?>
                                    </li>
                                    <li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/icon-clock-time.png" alt=""> <?php echo $booking_info['dropoff_time']; ?>
                                    </li>
                                </ul>
                            </div>
                        <!-- <div class="col bookFeature peddLftSet <?php echo($car_info->min_age > 0 ? 'contains-min-age' : ''); ?>">
                            <label>@lang('labels.features')</label>
                            <ul>
                                <ul>
                                    <li>
                                        <div class="spIconF person"></div>
                                        <p><?php echo $car_info->no_of_passengers; ?></p></li>
                                    <li>
                                        <div class="spIconF transmition"></div>
                                        <p><?php echo($car_info->transmission == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي')); ?></p>
                                    <li>
                                        <div class="spIconF door"></div>
                                        <p>
                                        <p><?php echo $car_info->no_of_doors; ?></p></li>
                                    <li>
                                        <div class="spIconF bag"></div>
                                        <p><?php echo $car_info->no_of_bags; ?></p></li>
                                    <?php if ($car_info->min_age > 0)
                        { ?>
                                <li>
                                    <div class="spIconF minAge"></div>
                                    <p><?php echo $car_info->min_age; ?></p>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </ul>
                            <div class="clearfix"></div>
                        </div> -->
                            <div class="col twoBig peddLftSet bigstyle-class">
                                <label>@lang('labels.rental_period')</label>
                                <ul>
                                    @if ($booking_info['is_delivery_mode'] == 2)
                                        <li style="color: #444444;"><?php echo $booking_info['hours_diff']; ?> @lang('labels.hours')</li>
                                    @elseif ($booking_info['is_delivery_mode'] == 4)
                                        <li><?php echo $booking_info['subscribe_for_months']; ?> @lang('labels.months')</li>
                                    @else
                                        <li style="color: #444444;"><?php echo $booking_info['days']; ?> @lang('labels.days')</li>
                                    @endif
                                </ul>
                            </div>
                            <?php if ($booking_info['is_delivery_mode'] != 4) { ?>
                            <div class="col twoBig peddLftSet bigstyle-class">
                                @if ($booking_info['is_delivery_mode'] == 2)
                                    <label>@lang('labels.rent_per_hour')</label>
                                @elseif (isset($booking_info['isLimousine']) && $booking_info['isLimousine'] == 1)
                                    <label>@lang('labels.rent_per_trip')</label>
                                @else
                                    <label>@lang('labels.rent_per_day')</label>
                                @endif
                                <ul>
                                    <li><h4>
                                            <span id="rent_per_day_span"><?php echo number_format(Session::get('rent_per_day'), 2); ?></span> @lang('labels.currency')
                                        </h4></li>
                                </ul>
                            </div>
                            <?php } ?>

                            <?php if ($cdw > 0 || $cdw_plus > 0 || $gps > 0 || $extra_driver > 0 || $baby_seat > 0) { ?>
                            <div class="col twoBig peddLftSet bigstyle-class extra-services-new">
                                <label>@lang('labels.extra_services')</label>
                                <ul>
                                    <?php if ($cdw > 0)
                                    {?>
                                    <li>
                                        @lang("labels.lost_damage")
                                        <h4><?php echo $booking_info['is_delivery_mode'] == 4 ? round($cdw * 30, 2) : $cdw; ?> @lang('labels.currency')</h4>
                                    </li>
                                    <?php } ?>

                                    <?php if ($cdw_plus > 0)
                                    {?>
                                    <li>
                                        @lang("labels.cdw_plus")
                                        <h4><?php echo $booking_info['is_delivery_mode'] == 4 ? round($cdw_plus * 30, 2) : $cdw_plus; ?> @lang('labels.currency')</h4>
                                    </li>
                                    <?php } ?>

                                    <?php if ($gps > 0)
                                    {?>
                                    <li>
                                        @lang("labels.gps")
                                        <h4><?php echo $booking_info['is_delivery_mode'] == 4 ? round($gps * 30, 2) : $gps; ?> @lang('labels.currency')</h4>
                                    </li>
                                    <?php } ?>

                                    <?php if ($extra_driver > 0)
                                    {?>
                                    <li>
                                        @lang("labels.extra_driver")
                                        <h4><?php echo $booking_info['is_delivery_mode'] == 4 ? round($extra_driver * 30, 2) : $extra_driver; ?> @lang('labels.currency')</h4>
                                    </li>
                                    <?php } ?>

                                    <?php if ($baby_seat > 0)
                                    {?>
                                    <li>
                                        @lang("labels.baby_protection")
                                        <h4><?php echo $booking_info['is_delivery_mode'] == 4 ? round($baby_seat * 30, 2) : $baby_seat; ?> @lang('labels.currency')</h4>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php } ?>

                            @if ($booking_info['is_delivery_mode'] != 4)
                                <div class="col twoBig peddLftSet bigstyle-class">
                                    @if ($booking_info['is_delivery_mode'] == 2)
                                        <label><?php echo($lang == 'eng' ? 'Total per 1 Hour' : 'المجموع لكل ساعة'); ?></label>
                                    @elseif (isset($booking_info['isLimousine']) && $booking_info['isLimousine'] == 1)
                                        <label><?php echo($lang == 'eng' ? 'Total For Trip' : 'المجموع للرحلة'); ?></label>
                                    @else
                                        <label><?php echo($lang == 'eng' ? 'Total per 1 day' : 'المجموع اليومي'); ?></label>
                                    @endif
                                    <ul>
                                        <li><h4>
                                                <span id="total_per_1_day"><?php echo number_format((Session::get('rent_per_day') + $cdw + $cdw_plus + $gps + $extra_driver + $baby_seat), 2); ?></span> @lang('labels.currency')
                                            </h4></li>
                                    </ul>
                                </div>
                            @endif

                            <?php if (Session::get('dropoff_charges_amount') > 0)
                            {
                            $dropoff_charges = Session::get('dropoff_charges_amount');
                            ?>
                            <div class="col twoBig peddLftSet bigstyle-class">
                                <label>@lang('labels.dropoff_charges')</label>
                                <ul>
                                    <li>
                                        <h4><?php echo Session::get('dropoff_charges_amount'); ?> @lang('labels.currency')</h4>
                                    </li>
                                </ul>
                            </div>
                            <?php }else {
                                $dropoff_charges = 0;
                            } ?>

                        </div>
                    </div>
                    <div class="leftColBottomSec">
                        <div class="totalWdisValu peddLftSet">
                            <ul>
                                <li>
                                    <label>
                                        @lang("labels.total_rent_for_capital")
                                        @if ($booking_info['is_delivery_mode'] == 2)
                                            <?php echo $booking_info['hours_diff']; ?> @lang("labels.hours")
                                        @elseif ($booking_info['is_delivery_mode'] == 4)
                                            1 @lang("labels.months")
                                        @elseif (isset($booking_info['isLimousine']) && $booking_info['isLimousine'] == 1)
                                            <?php echo($lang == 'eng' ? 'Trip' : 'رحلة'); ?>
                                        @else
                                            <?php echo $booking_info['days']; ?> @lang("labels.days")
                                        @endif
                                    </label>
                                    <h4 id="rent_m_days_span">
                                        <?php echo number_format(((Session::get('rent_per_day') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor)), 2); ?> @lang("labels.currency")
                                    </h4>
                                </li>

                                <?php
                                if ($delivery_charges > 0)
                                { ?>
                                <li>
                                    <label><?php echo($lang == 'eng' ? 'Delivery Charges' : 'رسوم خدمة توصيل السيارة'); ?></label>
                                    <h4><?php echo $delivery_charges; ?> @lang("labels.currency")</h4>
                                </li>
                                <?php }
                                ?>

                                <?php
                                if ($parking_fee > 0)
                                { ?>
                                <li>
                                    <label><?php echo($lang == 'eng' ? 'Parking Fee' : 'رسوم مواقف'); ?></label>
                                    <h4><?php echo $parking_fee; ?> @lang("labels.currency")</h4>
                                </li>
                                <?php }
                                ?>

                                <?php
                                if ($tamm_charges_for_branch > 0)
                                { ?>
                                <li>
                                    <label>@lang('labels.tamm_charges')</label>
                                    <h4><?php echo $tamm_charges_for_branch; ?> @lang("labels.currency")</h4>
                                </li>
                                <?php }
                                ?>


                                <?php
                                if ($waiting_extra_hours_charges > 0)
                                { ?>
                                <li>
                                    <label>@lang('labels.limousine_extra_charges')</label>
                                    <h4><?php echo $waiting_extra_hours_charges; ?> @lang("labels.currency")</h4>
                                </li>
                                <?php }
                                ?>

                                <li class="containsRedeemDiscount" style="display: none;">
                                    <label><?php echo($lang == 'eng' ? 'Discount availed on redeem' : 'سيتم تطبيق خصم نقاط الولاء'); ?></label>
                                    <h4 id="discount_on_redeem">0</h4>
                                </li>
                                <?php if (Session::get('coupon_applied') != true)
                                {
                                if (isset($promo_discount) && $promo_discount_amount > 0)
                                { ?>
                                <li class="discount_on_promo_auto">
                                    <label>@lang("labels.discount_on_promo")</label>
                                    <h4 class="discount"><?php echo number_format($promo_discount_amount * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']), 2); ?>
                                        @lang("labels.currency")</h4>
                                </li>
                                <?php }
                                }
                                ?>

                                <?php if (Session::get('coupon_applied') != true) {
                                    $styling = 'style="display: none;"';
                                } else {
                                    $styling = '';
                                }?>
                                <li class="discount_on_promo_code" <?php echo $styling; ?>>
                                    <label>@lang("labels.discount_on_promo_code") </label>
                                    <h4 class="discount"><?php echo(Session::get('coupon_applied') == true ? number_format(Session::get('promo_discount_amount') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']), 2) : ''); ?>
                                        @lang("labels.currency")</h4>
                                </li>

                                <li>
                                    <label><?php echo($lang == 'eng' ? 'Total Amount' : 'المبلغ الإجمالي'); ?></label>
                                    <h4>
                                        <div id="total_amount"
                                             style="display: inline-block;"><?php echo number_format(((Session::get('rent_per_day') + $dropoff_charges - $promo_discount_amount) * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges + $delivery_charges, 2); ?></div> @lang("labels.currency")
                                    </h4>
                                </li>

                                <?php if (Session::get('vat') > 0)
                                { ?>
                                <li>
                                    <label>@lang("labels.vat_applicable") (<?php echo Session::get('vat_percentage'); ?>
                                        %)</label>
                                    <h4>
                                        <div id="show_vat_applied"
                                             style="display: inline-block;"><?php echo number_format(Session::get('vat'), 2); ?></div>
                                        @lang("labels.sar")</h4>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="leftmostbottomsec">
                        <ul>
                            <li>
                                <h4 class="<?php echo($lang == 'eng' ? '' : 'totalWithVat'); ?>">
                                    @lang("labels.you_pay_total")
                                    <?php
                                    if ($minus_discount == true) {
                                        $payable_amount = ((Session::get('rent_per_day') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) - ($promo_discount_amount * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']))) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + Session::get('dropoff_charges_amount') + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges + $delivery_charges;

                                    } else {
                                        $payable_amount = Session::get('rent_per_day') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + Session::get('dropoff_charges_amount') + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges + $delivery_charges;
                                    }
                                    ?>
                                    <span><i id="showTotalAmount"><?php echo number_format($payable_amount + Session::get('vat'), 2); ?></i>
                                        @lang("labels.sar")</span>
                                </h4>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="rightCol new-form-design">
                    <h1>
                        @lang("labels.payment")
                        <span> @lang("labels.summary")</span>
                    </h1>
                    <?php
                    $driverFrmStl = '';
                    $corpIndiLoyal = false;
                    if (custom::isCorporateLoyalty() && session::get('customer_id_no_for_loyalty') != '') {
                        //this is when corporate is logged in and in the popup he enters individual id number
                        $driverFrmStl = 'display:none;';
                        $corpIndiLoyal = true;
                    }

                    if ($isLimousine == 1) {
                        $driverFrmStl = 'display:none;';
                        $corpIndiLoyal = false;
                    }

                    ?>
                    <div style="<?php echo $driverFrmStl; ?>" class="payLoginFrm custom-border-bot">
                        <form action="#" method="post" class="innerWrapFrm">
                            <label><?php echo($lang == 'eng' ? 'Search For Driver Details' : 'البحث عن مستخدم سابق'); ?> </label>
                            <input type="text"
                                   placeholder="<?php echo($lang == 'eng' ? 'WRITE (Email \ ID number \ Mobile No) To Search' : 'اكتب (البريد الإلكتروني / رقم الهوية)'); ?>"
                                   id="get_driver_by"/>
                            <input type="button" class="edBtn redishButtonRound finder-new-btn" onclick="getDriverDetails();"
                                   id="getDriverInfo" value="<?php echo($lang == 'eng' ? 'Find Driver' : 'البحث'); ?>"/>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                    <form method="post" action="<?php echo $lang_base_url; ?>/book_now_for_corporate"
                          class="bookNowForCorporateForm">
                        <div class="payFrmUserInfo custom-border-bot">
                            <div class="">
                                <p style="font-size: 16px;font-weight: 700;color: rgba(0, 0, 0, 0.5);"><?php echo ($lang == 'eng' ? 'Personal Information' : 'معلومات شخصية'); ?></p>
                            </div>
                            <ul class="formFields">
                                <li>
                                    <input type="text" placeholder="@lang('labels.first_name') *" name="first_name"
                                           class="required" maxlength="20"/>
                                </li>
                                <li>
                                    <input type="text" placeholder="@lang('labels.last_name') *" name="last_name"
                                           class="required" maxlength="20"/>
                                </li>
                                <?php if ($isLimousine == 1) { ?>
                                <input type="hidden" name="id_type" value="243">
                                <?php } else { ?>
                                <li>
                                    <select id="id_type_at_checkout" class="selectpicker required id_type"
                                            name="id_type" onchange="ValidationsWithIdType($(this).val(), 0);">
                                        <option value="" selected><?php echo($lang == 'eng' ? 'ID Type' : 'نوع الهوية'); ?> *</option>
                                        <?php
                                        foreach ($id_types as $id_type)
                                        {
                                        ?>
                                        <option value="<?php echo $id_type->ref_id; ?>"><?php echo($lang == 'eng' ? $id_type->eng_title : $id_type->arb_title); ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </li>
                                <?php } ?>
                                <?php if ($isLimousine == 1) { ?>
                                <input type="hidden" name="id_no" value="<?php echo Session::get('user_company_code'); ?>">
                                <?php } else { ?>
                                <li>
                                    <input type="text" name="id_no" placeholder="<?php echo($lang == 'eng' ? 'ID Number' : 'رقم الهوية'); ?> *"
                                           class="required customer_id_no id_no"/>
                                </li>
                                <?php } ?>
                                <li id="contains_sponsor_name" style="display: none;">
                                    <input type="text" name="sponsor" id="sponsorName" placeholder="@lang('labels.sponsor')" disabled/>
                                </li>
                                <li class="phone-field">
                                    <input type="text" class="phone required mobile_no number" placeholder="@lang('labels.mobile_no'). *"/>
                                    <input type="hidden" name="mobile_no" class="intTelNo">
                                </li>
                                <?php if ($isLimousine == 1) { ?>
                                <input type="hidden" name="gender" value="<?php echo($lang == "eng" ? "Male" : "ذكر"); ?>">
                                <?php } else { ?>
                                <li>
                                    <select id="gender" name="gender" class="required">
                                        <option value=""><?php echo($lang == 'eng' ? 'Gender' : 'الجنس'); ?> *</option>
                                        <option value="male"><?php echo($lang == "eng" ? "Male" : "ذكر"); ?></option>
                                        <option value="female"><?php echo($lang == "eng" ? "Female" : "انثى"); ?></option>
                                    </select>
                                </li>
                                <?php } ?>
                                <li>
                                    <input type="email" name="email" placeholder="@lang('labels.email_address') *"
                                           class="required customer_email"/>
                                </li>
                                <?php if ($isLimousine == 0) { ?>
                                <li>
                                    <input type="text" name="license_no" class="required" placeholder="<?php echo($lang == 'eng' ? 'License Number' : 'رقم الرخصة'); ?> *"/>
                                </li>
                                <?php } else { ?>
                                <li>
                                    <input type="hidden" name="license_no" value="<?php echo Session::get('user_company_code'); ?>">
                                </li>
                                <li>
                                    <input type="text" name="flight_number" placeholder="<?php echo($lang == 'eng' ? 'Flight Number' : 'رقم الرحلة'); ?>"/>
                                </li>
                                <li>
                                    <input type="text" name="limousine_cost_center" placeholder="<?php echo($lang == 'eng' ? 'Cost Center' : 'مركز التكلفة'); ?>" maxlength="14"/>
                                </li>
                                <?php } ?>
                                <?php if(custom::isCorporateLoyalty()) { ?>
                                <li>
                                    <input type="text" name="agent_emp_number" class="required" placeholder="<?php echo($lang == 'eng' ? 'Agent Emp Number' : 'رقم الموظف'); ?> *"/>
                                </li>
                                <li>&nbsp;</li>
                                <?php } ?>
                            </ul>
                            <?php if ($site_settings->cc_company == 'hyper_pay') { ?>
                            <p style="margin-top: -20px;"><?php echo($lang == 'eng' ? 'Billing Information' : 'معلومات الفاتورة'); ?></p>
                            <?php } ?>
                            <ul class="formFields billingInfo" style="padding-bottom: 0;">
                                <!-- Fields needed for Hyper Pay only -->
                                <?php if ($site_settings->cc_company == 'hyper_pay') { ?>
                                <li>
                                    <input type="text" name="address_street" placeholder="@lang('labels.address_street')">
                                </li>
                                <li>
                                    <input type="text" name="address_city" placeholder="@lang('labels.address_city')">
                                </li>
                                <li>
                                    <input type="text" name="address_state" placeholder="@lang('labels.address_state')">
                                </li>
                                <li>
                                    <select class="selectpicker" name="address_country">
                                        <option value="" selected>@lang("labels.address_country")</option>
                                        <?php
                                        foreach ($address_countries as $address_country)
                                        {
                                        if ($address_country->country_code == 'SA') {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        ?>
                                        <option value="<?php echo $address_country->country_code; ?>" <?php echo $selected; ?>><?php echo($lang == 'eng' ? strtoupper($address_country->eng_country) : $address_country->arb_country); ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </li>
                                <li>
                                    <input type="text" class="number" name="address_post_code" placeholder="@lang("labels.address_post_code")">
                                </li>
                                <li>&nbsp;</li>
                                <?php } ?>
                            </ul>
                            <?php if (Session::get('coupon_applied') != true)
                            {
                            if (isset($promo_discount) && $promo_discount_amount > 0)
                            { ?>
                            <span class="discountAutoAppliedMsg">@lang("labels.discount_message")</span>
                            <?php }
                            }
                            ?>
                        </div>

                        <div class="">
                            <div class="p_method_lbl">
                                <h2>
                                    @lang("labels.payment") @lang("labels.method")
                                </h2>
                            </div>
                        </div>

                        <div class="payFrmUserInfo paymentMethods">
                            <div class="paymentOption heading">
                                <ul>
                                    <li style="text-align: center;"><?php
                                        if (Session::get('error_message_payment') != '') {
                                            echo '<span style="color: red;font-weight: bold;">';
                                            echo Session::get('error_message_payment');
                                            echo '</span>';
                                            Session::forget('error_message_payment');
                                        }
                                        ?>

                                        @if(isset($hyper_pay_transaction_error))
                                            <p style="padding: 0;font-size: 13px;color: red;">{!! $hyper_pay_transaction_error !!}</p>
                                        @endif
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
                                    <li class="p-method payment_options_divs isRequired">
                                        <input id="CreditCardMada" name="payment_method" data-is-mada="1" value="cc"
                                               class="showHideOlpIdField" type="radio" checked>
                                        <label for="CreditCardMada" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/icon-mada-logo.png"
                                                     alt="Card" width="35" height="26">
                                            </div>
                                        </label>
                                    <!--                                        <p><?php echo $lang == 'eng' ? "mada" : "بطاقة مدى"; ?></p>-->
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->cc == 1){ ?>
                                    <li class="p-method payment_options_divs isRequired">
                                        <input id="CreditCard" name="payment_method" data-is-mada="0" value="cc"
                                               class="showHideOlpIdField" type="radio">
                                        <label for="CreditCard">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/icon-visa-logo.png"
                                                     alt="Card" width="35" height="26">
                                            </div>
                                        </label>
                                    <!--                                        <p><?php echo $lang == 'eng' ? "Credit Card" : "البطاقة الإئتمانية"; ?></p>-->
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->amex == 1 && $site_settings->cc_company == 'hyper_pay'){ ?>
                                    <li class="p-method payment_options_divs isRequired">
                                        <input id="CreditCardAmex" name="payment_method" data-is-mada="4" value="cc"
                                               class="showHideOlpIdField" type="radio">
                                        <label for="CreditCardAmex">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/amex.png?v=1.0"
                                                     alt="Card" width="35" height="26">
                                            </div>
                                        </label>
                                    <!--                                        <p><?php echo $lang == 'eng' ? "Amex" : "Amex"; ?></p>-->
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->stc_pay == 1 && $site_settings->cc_company == 'hyper_pay'){ ?>
                                    <li class="p-method payment_options_divs isRequired">
                                        <input id="CreditCardSTCPay" name="payment_method" data-is-mada="3" value="cc"
                                               class="showHideOlpIdField" type="radio">
                                        <label for="CreditCardSTCPay" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/stc-pay.png?v=1.0"
                                                     alt="Card" width="35" height="26" style="width: 50px;">
                                            </div>
                                        </label>
                                    <!--                                        <p><?php echo $lang == 'eng' ? "STC Pay" : "STC Pay"; ?></p>-->
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->cash == 1)
                                    { ?>
                                    <li class="p-method payment_options_divs isRequired">
                                        <input id="cash" name="payment_method" data-is-mada="0" value="cash"
                                               class="showHideOlpIdField"
                                               type="radio">
                                        <label for="cash" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/paymentOption_4.png?v=1.0"
                                                     alt="Cash" width="39" height="26">
                                            </div>
                                        </label>
                                    <!--                                        <p>@lang("labels.cash")</p>-->
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($corporate_customer_info->corporate_credit == 1)
                                    { ?>
                                    <li class="p-method payment_options_divs">
                                        <input id="points" name="payment_method" data-is-mada="0" value="corporate_credit" type="radio">
                                        <label for="points" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/corporate_credit.png?v=1.1"
                                                     alt="Points">
                                            </div>
                                        </label>
                                    <!--                                        <p><?php echo($lang == 'eng' ? 'Corporate Credit' : 'حساب إئتماني'); ?></p>-->
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($corporate_customer_info->pay_later == 1)
                                    { ?>
                                    <li class="p-method payment_options_divs">
                                        <input id="payLater" name="payment_method" data-is-mada="0" value="pay_later" type="radio">
                                        <label for="payLater" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/corporate_credit.png?v=1.1"
                                                     alt="Points">
                                            </div>
                                        </label>
                                    <!--                                        <p>@lang('labels.pay_later')</p>-->
                                    </li>
                                <?php $payment_method_ok = true; ?>
                                <?php } ?>
                            </div>
                            <div class="acceptTnC termsDiv" style="width: 100% !important; margin-top: 30px;">
                                <div class="custom-cehck-box">
                                    <input id="checkbox1" type="checkbox" class="accept_terms" name="checkbox" value="1">
                                    <label for="checkbox1">@lang("labels.agreed_on") </label>
                                </div>
                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                   data-bs-target="#term_n_Cond">@lang("labels.including_policy"). *</a>
                            </div>
                            <div class="paymentOption">
                                <?php if ($payment_method_ok) { ?>
                                <input type="button"
                                       class="redishButtonRound payNBtn bookNowBtnForCorporate submitBtn submit_btn license_validate_btn"
                                       value="@lang('labels.pay_now')"/>
                                <?php } ?>
                            </div>
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

                        <?php
                        // started putting data to session to check
                        $car_payment_data_to_check = [
                            'total_rent_after_discount_on_promo' => $total_amount_after_discount,
                            'discount_amount_per_day' => $promo_discount_amount,
                        ];
                        Session::put('car_payment_data_to_check', $car_payment_data_to_check);
                        Session::save();
                        // end putting data to session to check
                        ?>

                        <input type="hidden" name="total_rent_after_discount_on_promo"
                               id="total_rent_after_discount_on_promo"
                               value="<?php echo $total_amount_after_discount; ?>">
                        <input type="hidden" name="discount_amount_per_day" id="discount_amount_per_day"
                               value="<?php echo $promo_discount_amount; ?>">
                        <input type="hidden" name="promotion_id" id="promotion_id" value="<?php echo $promotionId; ?>">
                        <input type="hidden" name="isMada" id="isMada" value="1">
                    </form>


                    <?php if ($corpIndiLoyal) {
                        echo '<script>
                           $("#get_driver_by").val("' . session::get('customer_id_no_for_loyalty') . '");
                           $(document).ready(function(){
                           getDriverDetails();
                           });
                        </script>';
                    }?>

                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).on('click', '.payment_options_divs', function() {

            if ($(this).hasClass('isRequired')) {
                $('.billingInfo').find('input').addClass('required');
                addAsteriskToPlaceholders();
            } else {
                $('.billingInfo').find('input').removeClass('required');
                removeAsteriskFromPlaceholders();
            }
        });

        function addAsteriskToPlaceholders() {
            $('.billingInfo input').each(function() {
                var currentPlaceholder = $(this).attr('placeholder');
                if (currentPlaceholder && !currentPlaceholder.endsWith('*')) {
                    $(this).attr('placeholder', currentPlaceholder + '*');
                }
            });
        }

        // Function to remove asterisk (*) from placeholders
        function removeAsteriskFromPlaceholders() {
            $('.billingInfo input').each(function() {
                var currentPlaceholder = $(this).attr('placeholder');
                if (currentPlaceholder && currentPlaceholder.endsWith('*')) {
                    $(this).attr('placeholder', currentPlaceholder.slice(0, -1));
                    var $tooltipElement = $(this);
                    var tooltipInstance = bootstrap.Tooltip.getInstance($tooltipElement[0]);

                    if (tooltipInstance) {
                        tooltipInstance.hide();
                        tooltipInstance.dispose();
                    }
                }
            });
        }
    </script>
@endsection