@extends('frontend.layouts.template')
@section('content')
    <style>
        .addEqualSign {
            position: relative;
        }

        .addEqualSign:after {
            content: '=';
            position: absolute;
        <?php echo ($lang == 'eng' ? 'left' : 'right'); ?>: 98 %;
            top: 36px;
        }

        .my-lable {
            font-family: 'latoregular';
            color: #9f073f;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
            width: 100%;
        }

        .forInput input[type="text"] {
            margin-bottom: 5px;
        }

        .paymentOption ul li.p-method {
            width: 20% !important;
        }

        .payFrmUserInfo .paymentOption.objects li p {
            display: block;
        {{($lang == 'eng' ? 'padding-left' : 'padding-right')}}: 28 px;
        }

        .paymentOption ul li .imgBox img {
            max-height: 28px;
        }

        <?php  if(!custom::is_mobile()){?>
            body.arb.payment .payLoginFrm {
                margin-left: 0;
        }
        body.arb.payment .payLoginFrm .innerWrapFrm input.edBtn {
            width: 100%;
        }
        <?php }  ?>

        .payment_options_area_div .promoCodeArea {
            display: flex;
            align-content: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .payment_options_area_div .redeem-options-row-1,
        .payment_options_area_div .redeem-options-row-2 {
            display: flex;
            gap: 24px;
        }

        .payment_options_area_div .redeem-options-row-1.section-with-one-option .promoCodeArea,
        .payment_options_area_div .redeem-options-row-2.section-with-one-option .promoCodeArea {
            width: 100% !important;
        }

        .payment_options_area_div .redeem-options-row-1.section-with-two-option .promoCodeArea,
        .payment_options_area_div .redeem-options-row-2.section-with-two-option .promoCodeArea {
            width: 50% !important;
        }

        .payment_options_area_div .redeem-options-row-1.section-with-three-option .promoCodeArea,
        .payment_options_area_div .redeem-options-row-2.section-with-three-option .promoCodeArea {
            width: 33% !important;
        }

        .peyment-option-text-box .redeem-options-row-1.section-with-one-option .promoCodeArea .BtnNtXT input.edBtn,
        .peyment-option-text-box .redeem-options-row-2.section-with-one-option .promoCodeArea .BtnNtXT input.edBtn{
            /*width: 98.5%!important;*/
        }
        .peyment-option-text-box .redeem-options-row-1.section-with-two-option .promoCodeArea .BtnNtXT input.edBtn,
        .peyment-option-text-box .redeem-options-row-2.section-with-two-option .promoCodeArea .BtnNtXT input.edBtn
        {
            /*width: 98.5% !important;*/
        }
        .peyment-option-text-box .redeem-options-row-1.section-with-three-option .promoCodeArea .BtnNtXT input.edBtn,
        .peyment-option-text-box .redeem-options-row-2.section-with-three-option .promoCodeArea .BtnNtXT input.edBtn
        {

        }
        .share-and-earn-on-subscription-discount-btn a {
            background-image: url(<?php echo $base_url; ?>/public/frontend/images/network-icon.png);
        }
        body.arb .share-and-earn-on-subscription-discount-btn a {
            background-image: url(<?php echo $base_url; ?>/public/frontend/images/network-icon-arb.png);
        }
        .pricePageSec {
            padding-bottom: 150px !important;
        }
        @media only screen and (max-width: 1499px) {
            .peyment-option-text-box .redeem-options-row-1.section-with-two-option .promoCodeArea .BtnNtXT input.edBtn,
            .peyment-option-text-box .redeem-options-row-2.section-with-two-option .promoCodeArea .BtnNtXT input.edBtn
            {
                /*width: 99.3% !important;*/
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
    $site_settings = custom::site_settings();
    if ($booking_info['is_delivery_mode'] == 4) {
        $booking_info['days'] = 30; // because 1 month is to be charged
    }
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
            <div class="pricePgWrapper">
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
                                             style="display: inline-block;"><?php echo number_format(((Session::get('rent_per_day') + $dropoff_charges - $promo_discount_amount) * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $parking_fee + $tamm_charges_for_branch + $delivery_charges, 2); ?></div> @lang("labels.currency")
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
                                        $payable_amount = ((Session::get('rent_per_day') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) - ($promo_discount_amount * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']))) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + Session::get('dropoff_charges_amount') + $parking_fee + $tamm_charges_for_branch + $delivery_charges;

                                    } else {
                                        $payable_amount = Session::get('rent_per_day') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + Session::get('dropoff_charges_amount') + $parking_fee + $tamm_charges_for_branch + $delivery_charges;
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
                    {{--<h3 class="bigPrice">
                        900 SR
                        <span>Total</span>
                    </h3>--}}

                    <?php if (Session::get('logged_in_from_frontend') == '' || Session::get('logged_in_from_frontend') != true)
                    {
                        if (!Session::has('customer_id_no_for_loyalty')) {
                        ?>


                    <div class="login-popup-sec">
                        <input type="button" class="edBtn redishButtonRound" value="<?php echo ($lang == 'eng' ? 'If You Have an Account Click here to sign in' : 'إذا كان لديك حساب انقر هنا لتسجيل الدخول'); ?>" data-bs-toggle="modal" data-bs-target="#login-modal"/>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="login-modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"><?php echo ($lang == 'eng' ? 'If You Have an Account, Sign in here' : 'إذا كان لديك حساب انقر هنا لتسجيل الدخول'); ?></h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="payLoginFrm">
                                        <form action="<?php echo $lang_base_url; ?>/loginOnPayment" method="post" class="innerWrapFrm"
                                              id="loginOnPayment"
                                              onsubmit="return false;">
                                            <label>@lang("labels.already_register") @lang("labels.save_time") <a
                                                        href="javascript:void(0);"> @lang("labels.log_in")</a> </label>
                                            <input type="text"
                                                   placeholder="<?php echo($lang == 'eng' ? 'WRITE (Email \ ID number)' : 'اكتب (البريد الإلكتروني / رقم الهوية)'); ?>"
                                                   name="username" id="loginUsernameOnPayment"/>
                                            <input type="password" placeholder="@lang('labels.password')" name="password"
                                                   id="loginPasswordOnPayment"/>
                                            <?php if ($site_settings->maintenance_mode == 'on')
                                            { ?>
                                            <input type="button" class="edBtn redishButtonRound" value="@lang('labels.login')"
                                                   onclick="siteUnderMaintenance();"/>
                                            <?php }else{ ?>
                                            <input type="submit" class="edBtn redishButtonRound" value="@lang('labels.login')"/>
                                            <?php } ?>
                                            <div class="clearfix"></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }
                    }?>
                    <!-- </div> -->

                    <form method="post" action="<?php echo $lang_base_url; ?>/book_now" class="bookNowForm"
                          onsubmit="return false;">
                        <div class="payFrmUserInfo custom-border-bot">
                            <div class="">
                                <p style="font-size: 16px;font-weight: 700;color: rgba(0, 0, 0, 0.5);"><?php echo ($lang == 'eng' ? 'Personal Information' : 'معلومات شخصية'); ?></p>
                            </div>
                            <ul class="formFields">
                                <li>
                                    <input type="text" placeholder="@lang('labels.first_name') *" name="first_name"
                                           class="required"
                                           value="<?php echo(!empty($user_info) ? $user_info->first_name : ''); ?>"
                                           maxlength="20"/>
                                </li>
                                <li>
                                    <input type="text" placeholder="@lang('labels.last_name') *" name="last_name"
                                           class="required" maxlength="20"
                                           value="<?php echo(!empty($user_info) ? $user_info->last_name : ''); ?>"/>
                                </li>
                                <li>
                                    <?php
                                    if (!empty($user_info) && $user_info->id_no !== '') {
                                        $disabled_id_number = 'disabled';
                                        $disabled_id_number_style = 'style="background-color: lightgrey;';
                                        $hidden_id_number_field = '<input type="hidden" name="id_type" value="' . $user_info->id_type . '">';
                                    } else {
                                        $disabled_id_number = '';
                                        $disabled_id_number_style = '';
                                        $hidden_id_number_field = '';
                                    }

                                    ?>
                                    <select class="selectpicker required id_type" name="id_type"
                                            onchange="showIdVersionField($(this).val());" <?php echo $disabled_id_number; ?> <?php echo $disabled_id_number_style; ?>>
                                        <option value="" selected>@lang('labels.id_type') *</option>
                                        <?php
                                        foreach ($id_types as $id_type)
                                        {
                                        if (!empty($user_info) && ($id_type->ref_id == $user_info->id_type)) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        ?>
                                        <option value="<?php echo $id_type->ref_id; ?>" <?php echo $selected; ?>><?php echo($lang == 'eng' ? $id_type->eng_title : $id_type->arb_title); ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                    <?php echo $hidden_id_number_field; ?>
                                </li>
                                <?php
                                if ((!empty($user_info) && $user_info->id_type == '68') || (!empty($user_info) && $user_info->id_type == '243')) {
                                    $id_version_style = '';
                                    $class = 'required';
                                } else {
                                    $id_version_style = 'style="display: none;"';
                                    $class = '';
                                }

                                if ((!empty($user_info) && $user_info->id_type == '68') || (!empty($user_info) && $user_info->id_type == '29596')) {
                                    $numberClass = 'number';
                                } else {
                                    $numberClass = '';
                                }
                                ?>
                                <li>
                                    <?php
                                    if ((!empty($user_info) && $user_info->id_no !== '')) // if user is logged in or coming by filling in data
                                    { ?>
                                    <input type="text" id="id_no_required_for_validation" value="{{$user_info->id_no}}"
                                           name="id_no" readonly
                                           style="background-color: lightgray;">
                                    <?php } else { ?>
                                    <input type="text" placeholder="@lang("labels.id_number") *" name="id_no"
                                           id="id_no_required_for_validation"
                                           class="required customer_id_no id_no <?php echo $numberClass; ?>"
                                           value="<?php echo(!empty($user_info) ? $user_info->id_no : ''); ?>"/>
                                    <?php }
                                    ?>
                                </li>
                                <?php
                                if ((!empty($user_info) && $user_info->id_type == '68')) {
                                    $sponsor_style = '';
                                    $sponsor_class = 'required';
                                } else {
                                    $sponsor_style = 'style="display: none;"';
                                    $sponsor_class = '';
                                }
                                ?>
                                <li class="sponsor" <?php echo $sponsor_style; ?>>
                                    <input type="text" placeholder="@lang('labels.sponsor') *" name="sponsor"
                                           value="<?php echo(!empty($user_info) ? $user_info->sponsor : ''); ?>"
                                           class=" <?php echo $sponsor_class; ?>" id="sponsor_field"/>
                                </li>

                                <li class="phone-field">
                                    <input type="text"
                                           value="<?php echo(!empty($user_info) ? $user_info->mobile_no : ''); ?>"
                                           class="phone required mobile_no number" placeholder="@lang("labels.mobile_no"). *"/>
                                    <input type="hidden" name="mobile_no" class="intTelNo"
                                           value="<?php echo(!empty($user_info) ? $user_info->mobile_no : ''); ?>">
                                </li>
                                <li>
                                    <input type="email" class="required checkEmailValid customer_email"
                                           id="email_required_for_validation"
                                           placeholder="@lang("labels.email_address") * "
                                           value="<?php echo(!empty($user_info) ? $user_info->email : ''); ?>"
                                           name="email" <?php echo(!empty($user_info) && $user_info->email != '' ? '' : ''); ?> />
                                    <input type="hidden" id="old_email_address"
                                           value="<?php echo(!empty($user_info) && $user_info->email != '' ? $user_info->email : ''); ?>">


                                </li>
                                <li>
                                    <?php
                                    if ((!empty($user_info) && $user_info->license_no !== '')) // if user is logged in or coming by filling in data
                                    { ?>
                                    <input type="text" class="required validate_license_number" name="license_no"
                                           value="{{$user_info->license_no}}" readonly
                                           style="background-color: lightgray;">
                                    <?php } else { ?>
                                    <input type="text" placeholder="@lang('labels.driving_license_number') *" name="license_no"
                                           value="<?php echo(!empty($user_info) ? $user_info->license_no : ''); ?>"
                                           class="required validate_license_number"/>
                                    <?php }
                                    ?>
                                </li>
                            </ul>
                            <?php if ($site_settings->cc_company == 'hyper_pay') { ?>
                            <p style="margin-top: -20px;"><?php echo($lang == 'eng' ? 'Billing Information' : 'معلومات الفاتورة'); ?></p>
                            <?php } ?>
                            <ul class="formFields" style="margin-top: -20px;">
                                <!-- Fields needed for Hyper Pay only -->
                                <?php if ($site_settings->cc_company == 'hyper_pay') { ?>
                                <li>
                                    <input type="text" class="required" name="address_street" placeholder="@lang("labels.address_street")"
                                           value="<?php echo(!empty($user_info) ? $user_info->address_street : ''); ?>">
                                </li>
                                <li>
                                    <input type="text" class="required" name="address_city" placeholder="@lang("labels.address_city")"
                                           value="<?php echo(!empty($user_info) ? $user_info->address_city : ''); ?>">
                                </li>
                                <li>
                                    <input type="text" class="required" name="address_state" placeholder="@lang("labels.address_state")"
                                           value="<?php echo(!empty($user_info) ? $user_info->address_state : ''); ?>">
                                </li>
                                <li>
                                    <select class="selectpicker required" name="address_country">
                                        <option value="" selected>@lang("labels.address_country")</option>
                                        <?php
                                        foreach ($address_countries as $address_country)
                                        {
                                        if (!empty($user_info) && ($address_country->country_code == $user_info->address_country)) {
                                            $selected = 'selected';
                                        } elseif ($address_country->country_code == 'SA') {
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
                                    <input type="text" class="required number" name="address_post_code" placeholder="@lang("labels.address_post_code")"
                                           value="<?php echo(!empty($user_info) ? $user_info->address_post_code : ''); ?>">
                                </li>
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



                        <?php if (Session::get('coupon_applied') != true) {
                            $styling = 'style="display: none;"';
                        } else {
                            $styling = '';
                        }?>

                        <?php
                        if ($site_settings->promo_coupon_mode == 'off') {
                            $backendStyle = 'style="display: none;"';
                        } else {
                            $backendStyle = '';
                        }
                        ?>

                        @if ($booking_info['is_delivery_mode'] != 2)
                            <div class="promoCodeArea promoCodeSection custom-border-bot" <?php echo $backendStyle; ?>>
                                <div class="proCdSec">
                                    <div class="promo-code-text">
                                        <h4><?php echo ($lang == 'eng' ? 'Promo Code' : 'الرمز الترويجي'); ?></h4>
                                    </div>
                                    <?php if (Session::get('coupon_applied') != true)
                                    { ?>
                                    <div class="coupen-code-area">
                                        <input type="text" placeholder="<?php echo($lang == 'eng' ? 'ENTER PROMO CODE' : 'ادخل الرمز الترويجي'); ?>" name="promo_code" id="couponCodeField"/>
                                        <div class="BtnNtXT">
                                            <input type="button" value="@lang("labels.apply")" class="edBtn redishButtonRound" id="applyCouponCodeBtn"/>
                                        </div>
                                    </div>
                                        <?php } ?>

                                    <div class="codeApproved" <?php echo $styling; ?>>
                                        @lang("labels.promo_code") <strong> "<span
                                                    id="promoCodeHere"><?php echo(Session::get('coupon_code') != '' ? Session::get('coupon_code') : ''); ?></span>"
                                        </strong> <span>@lang("labels.applied")</span>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                        @endif

                        @if ($site_settings->show_loyalty_programs_section == 'yes')
                            @include('frontend/layouts/loyalty_programs_section_with_checkboxes')
                        @endif

                        <div class="payment_options_area_div custom-border-bot">
                            <div class="p_method_lbl">
                                <h2>
                                    @lang("labels.payment") @lang("labels.method")
                                </h2>
                            </div>
                            <div class="peyment-option-text-box">

                                <div class="redeem-options-row-1 {{($site_settings->qitaf == 'on' ? 'section-with-two-option' : 'section-with-one-option')}}">
                                    @if ($site_settings->qitaf == 'on')
                                        @include('frontend/layouts/qitaf')
                                    @endif

                                    @include('frontend/layouts/key_redeem')
                                </div>

                                <?php
                                $numbers_mapping = array("one","two","three");
                                $count = 0;
                                if ($site_settings->niqaty == 'on') $count++;
                                if ($site_settings->anb == 'on') $count++;
                                if ($site_settings->mokafaa == 'on') $count++;
                                ?>

                                @if ($count > 0)
                                    <div class="redeem-options-row-2 section-with-{{$numbers_mapping[$count-1]}}-option">
                                        @if ($site_settings->niqaty == 'on')
                                            @include('frontend/layouts/niqaty')
                                        @endif
                                        @if ($site_settings->anb == 'on')
                                            @include('frontend/layouts/anb')
                                        @endif
                                        @if ($site_settings->mokafaa == 'on')
                                            @include('frontend/layouts/mokafaa')
                                        @endif
                                    </div>
                                @endif

                            </div>
                            <div class="payment_options_help_text">
                                <p>
                                    <?php if ($lang == 'eng') { ?>
                                        Note: if you need to remove selection please click on search in top<br>(Qitaf, Niqaty, Mokafaa & ANB points will be reversed after 1:30 hours)
                                    <?php } else { ?>
                                        ملاحظة: في حالة الرغبة في تغيير الاختيار يمكنكم الضغط على البحث في خانة البحث في الأعلى
                                        <br>
                                        (سوف يتم إرجاع نقاط قطاف، نقاطي، مكافأة و البنك العربي في خلال ساعة و نصف)
                                    <?php } ?>
                                </p>
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
                            <div class="paymentOption objects three">
                                <?php $payment_method_ok = false; ?>
                                <ul>
                                    <?php if ($site_settings->mada == 1){ ?>
                                    <li class="p-method payment_options_divs">
                                        <input id="CreditCardMada" name="payment_method" data-is-mada="1" value="cc"
                                               class="showHideOlpIdField" type="radio" checked>
                                        <label for="CreditCardMada" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/icon-mada-logo.png" alt="Card">
                                            </div>
                                        </label>
                                        <!--<p><?php echo $lang == 'eng' ? "mada" : "بطاقة مدى"; ?></p> -->
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->cc == 1){ ?>
                                    <li class="p-method payment_options_divs">
                                        <input id="CreditCard" name="payment_method" data-is-mada="0" value="cc"
                                               class="showHideOlpIdField" type="radio">
                                        <label for="CreditCard">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/icon-visa-logo.png" alt="Card">
                                            </div>
                                        </label>
                                    <!--<p><?php echo $lang == 'eng' ? "Credit Card" : "البطاقة الإئتمانية"; ?></p> -->
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->amex == 1 && $site_settings->cc_company == 'hyper_pay'){ ?>
                                    <li class="p-method payment_options_divs">
                                        <input id="CreditCardAmex" name="payment_method" data-is-mada="4" value="cc"
                                               class="showHideOlpIdField" type="radio">
                                        <label for="CreditCardAmex">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/amex.png?v=1.0"
                                                     alt="Card" width="35" height="26">
                                            </div>
                                        </label>
                                    <!--<p><?php echo $lang == 'eng' ? "Amex" : "Amex"; ?></p> -->
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->stc_pay == 1 && $site_settings->cc_company == 'hyper_pay'){ ?>
                                    <li class="p-method payment_options_divs">
                                        <input id="CreditCardSTCPay" name="payment_method" data-is-mada="3" value="cc"
                                               class="showHideOlpIdField" type="radio">
                                        <label for="CreditCardSTCPay" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/stc-pay.png?v=1.0"
                                                     alt="Card" width="35" height="26" style="width: 50px;">
                                            </div>
                                        </label>
                                        <p><?php echo $lang == 'eng' ? "STC Pay" : "STC Pay"; ?></p>
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->apple_pay == 1 && $site_settings->cc_company == 'hyper_pay' && custom::is_ios_device()){ ?>
                                    <li class="p-method payment_options_divs">
                                        <input id="CreditCardApplePay" name="payment_method" data-is-mada="2" value="cc"
                                               class="showHideOlpIdField" type="radio">
                                        <label for="CreditCardApplePay" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/apple-pay.png?v=0.1"
                                                     alt="Card" width="35" height="26" style="width: 40px;">
                                            </div>
                                        </label>
                                        <p><?php echo $lang == 'eng' ? "Apple Pay" : "Apple Pay"; ?></p>
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->cash == 1)
                                    { ?>
                                    <li class="p-method payment_options_divs">
                                        <input id="cash" name="payment_method" data-is-mada="0" value="cash"
                                               class="showHideOlpIdField"
                                               type="radio">
                                        <label for="cash" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/paymentOption_4.png?v=1.0"
                                                     alt="Cash" width="39" height="26">
                                            </div>
                                        </label>
                                    <!--<p>@lang("labels.cash")</p> -->
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->sadad == 1)
                                    { ?>
                                    <li class="p-method payment_options_divs" style="min-width: inherit; width: auto;">
                                        <input id="sadad" name="payment_method" data-is-mada="0" value="sadad"
                                               class="showHideOlpIdField"
                                               type="radio">
                                        <label for="sadad" style="display: block !important;">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/paymentOption_3.png?v=1.0"
                                                     alt="Sadad" width="58" height="26">
                                            </div>
                                        </label>
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>

                                    <?php if ($site_settings->points == 1)
                                    { ?>
                                    <li class="p-method payment_options_divs">
                                        <input id="points" name="payment_method" data-is-mada="0" value="points"
                                               class="showHideOlpIdField" type="radio">
                                        <label for="points">
                                            <div class="imgBox">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/paymentOption_5.png?v=1.0"
                                                     alt="Points" width="31" height="26">
                                            </div>
                                        </label>
                                        <p>@lang("labels.loyalty_points")</p>
                                    </li>
                                    <?php $payment_method_ok = true; ?>
                                    <?php } ?>
                                </ul>
                                <input type="text" name="olp_id" class="olpIdField"
                                       placeholder="<?php echo($lang == 'eng' ? 'Enter your SADAD ID HERE' : 'أدخل حساب سداد هنا'); ?>"
                                       style="display: none;">
                            </div>
                            <div class="acceptTnC termsDiv" style="width: 100% !important;">
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
                                       class="redishButtonRound payNBtn bookNowBtn submitBtn submit_btn license_validate_btn"
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
                            'total_rent_after_discount_on_promo' => $total_amount_after_discount + Session::get('vat'),
                            'original_total_rent_after_discount_on_promo' => $total_amount_after_discount + Session::get('vat'),
                            'payable_amount' => $payable_amount + Session::get('vat'),
                            'discount_amount_per_day' => $promo_discount_amount,
                            'redeem_discount_availed' => 0,
                        ];
                        Session::put('car_payment_data_to_check', $car_payment_data_to_check);
                        Session::save();
                        // end putting data to session to check
                        ?>

                        <input type="hidden" name="total_rent_after_discount_on_promo"
                               id="total_rent_after_discount_on_promo"
                               value="<?php echo $total_amount_after_discount + Session::get('vat'); ?>">
                        <input type="hidden" name="original_total_rent_after_discount_on_promo"
                               id="original_total_rent_after_discount_on_promo"
                               value="<?php echo $total_amount_after_discount + Session::get('vat'); ?>">
                        <input type="hidden" id="payable_amount"
                               value="<?php echo $payable_amount + Session::get('vat'); ?>">
                        <input type="hidden" name="discount_amount_per_day" id="discount_amount_per_day"
                               value="<?php echo $promo_discount_amount; ?>">
                        <input type="hidden" name="promotion_id" id="promotion_id" value="<?php echo $promotionId; ?>">
                        <input type="hidden" name="redeem_points_used" id="redeem_points_used" value="0">
                        <input type="hidden" name="redeem_discount_availed" id="redeem_discount_availed" value="0">
                        <input type="hidden" name="isMada" id="isMada" value="1">
                    </form>
                </div>
            </div>
        </div>

        <?php if (!empty($user_info) && $user_info->id_no != '')
        { ?>
        <script>
            $.get(base_url + '/cronjob/loyaltySyncCronJob?from_mobile=1&user_id_no=<?php echo $user_info->id_no; ?>', function (data) {
            });
        </script>
        <?php } ?>

    </section>

    @if($site_settings->refer_and_earn_option == 'on' && $booking_info['is_delivery_mode'] == 4)
        @php $refer_and_earn_data = custom::refer_and_earn_data_simple($lang); @endphp
        <section class="share-and-earn-on-subscription-discount-section">
            <div class="share-and-earn-on-subscription-discount-btn">
                <a href="javascript:void(0);" id="share_and_earn_btn">
                    {{($lang == 'eng' ? 'Share & Earn on Subscription' : 'شارك و اكسب مع حجوزات الاشتراك')}}
                    <br /> {{$refer_and_earn_data['share_and_earn_button_amount_text']}}</a>
            </div>
        </section>
    @endif
@endsection