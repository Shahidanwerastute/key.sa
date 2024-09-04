@extends('frontend.layouts.template')



@section('content')
    <style>
        .hp_form_field {
            font-family: latoregular;
            font-size: 14px !important;
            font-weight: 400;
            font-style: normal;
            color: rgb(134, 134, 134) !important;
            text-transform: none;
            text-decoration: none solid rgb(134, 134, 134);
            letter-spacing: 0px;
            word-spacing: 0px;
            line-height: 20px;
            text-align: start;
            vertical-align: baseline;
            direction: <?php echo ($lang == 'eng' ? 'ltr' : 'rtl'); ?>;
            background-color: rgb(255, 255, 255) !important;
            background-image: none;
            background-repeat: repeat;
            background-position: 0% 0%;
            background-attachment: scroll;
            opacity: 1;
            box-sizing: border-box;
            height: 29px !important;
            border-radius: 4px !important;
            padding-<?php echo ($lang == 'eng' ? 'left' : 'right'); ?>: 5px !important;
        }

        .hp_form_field::-webkit-input-placeholder {
            text-transform: capitalize !important;
        }

        input[type=radio] {
            opacity: 1 !important;
            margin-right: 10px !important;
            cursor: pointer;
        }
        .is-mada-card{
            background:url("https://kra.ced.sa/public/frontend/images/icon-mada-logo.png?v=0.3") no-repeat {{$lang == 'eng' ? 'right' : 'left'}} center;
            background-position-x: {{$lang == 'eng' ? '98' : '2'}}%;
        }
    </style>

    <?php

    $rejectedEmail = "abc@reject.com";

    if (Session::get('search_data')['is_delivery_mode'] == 1) {

        $from_branch_id = Session::get('search_data')['from_branch_id'];

    } else {

        $from_branch_id = 0;

    }

    if (Session::get('user_type') == "corporate_customer") {

        $customer_type_for_cc = "corporate_customer";

    } else {

        $customer_type_for_cc = "individual_customer";

    }

    if ($booking_info['is_delivery_mode'] == 4) {
        $booking_info['days'] = 30; // because 1 month is to be charged
    }



    ?>



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

    $qitaf_amount = Session::has('qitaf_amount') ? Session::get('qitaf_amount') : 0;
    $niqaty_amount = Session::has('niqaty_amount') ? Session::get('niqaty_amount') : 0;

    $redeem_discount = isset($booking_payment_details) && $booking_payment_details->redeem_discount_availed > 0 ? $booking_payment_details->redeem_discount_availed : 0;

    $pre_total_discount = 0;
    $post_total_discount = 0;

    if (Session::get('minus_discount') == true) {
        $is_promo_discount_on_total = (Session::has('is_promo_discount_on_total') ? 1 : 0);
        if ($is_promo_discount_on_total == 1) {
            $post_total_discount = $promo_discount_amount;
        } else {
            $pre_total_discount = $promo_discount_amount;
        }
    }

    if (Session::get('minus_discount') == true) {
        $payable_amount = ((Session::get('rent_per_day') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) - ($pre_total_discount * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']))) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + Session::get('dropoff_charges_amount') + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges + $delivery_charges;
    } else {
        $payable_amount = (Session::get('rent_per_day') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + Session::get('dropoff_charges_amount') + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges + $delivery_charges;
    }

    $vat_amount = (Session::get('vat_percentage') / 100) * $payable_amount;

    $payable_amount = $payable_amount + $vat_amount - $qitaf_amount - $niqaty_amount - $redeem_discount - $post_total_discount;

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
                                        <?php echo ($lang == 'eng' ? $pickup_branch_info->eng_title : $pickup_branch_info->arb_title) . ", " . ($lang == 'eng' ? $pickup_branch_info->city_eng_title : $pickup_branch_info->city_arb_title); ?>
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
                                        <img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/icon-location.png" alt="" >
                                        <?php echo ($lang == 'eng' ? $dropoff_branch_info->eng_title : $dropoff_branch_info->arb_title) . ", " . ($lang == 'eng' ? $dropoff_branch_info->city_eng_title : $dropoff_branch_info->city_arb_title); ?>
                                    </li>
                                    <li><img class="abImg"
                                             src="<?php echo $base_url; ?>/public/frontend/images/icon-calendar.png" alt="" > <?php echo date('d / m / Y', strtotime($booking_info['dropoff_date'])); ?>
                                    </li>
                                    <li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/icon-clock-time.png" > <?php echo $booking_info['dropoff_time']; ?>
                                    </li>
                                </ul>
                            </div>
                            <!--<div class="col bookFeature peddLftSet <?php echo($car_info->min_age > 0 ? 'contains-min-age' : ''); ?>">
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
                                        <li>1 @lang('labels.months')</li>
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
                                    <li><h4><?php echo Session::get('rent_per_day'); ?> @lang('labels.currency')</h4></li>
                                </ul>
                            </div>
                            <?php } ?>

                            <?php if ($cdw > 0 || $cdw_plus > 0 || $gps > 0 || $extra_driver > 0 || $baby_seat > 0) { ?>
                            <div class="col twoBig peddLftSet extra-services-new">
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

                            <?php if ($booking_info['is_delivery_mode'] != 4) { ?>
                            <div class="col twoBig peddLftSet bigstyle-class">
                                @if ($booking_info['is_delivery_mode'] == 2)
                                    <label><?php echo($lang == 'eng' ? 'Total per 1 Hour' : 'المجموع لكل ساعة'); ?></label>
                                @else
                                    <label><?php echo($lang == 'eng' ? 'Total per 1 day' : 'المجموع اليومي'); ?></label>
                                @endif
                                <ul>
                                    <li><h4>
                                            <span><?php echo number_format((Session::get('rent_per_day') + $cdw + $cdw_plus + $gps + $extra_driver + $baby_seat), 2); ?></span> @lang('labels.currency')
                                        </h4></li>
                                </ul>
                            </div>
                            <?php } ?>
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
                                    <h4><?php echo number_format(((Session::get('rent_per_day')) * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor), 2); ?> @lang('labels.currency')</h4>
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

                                <?php
                                if ($qitaf_amount > 0)
                                { ?>
                                <li>
                                    <label>@lang('labels.qitaf_redeem_availed')</label>
                                    <h4><?php echo $qitaf_amount; ?> @lang("labels.currency")</h4>
                                </li>
                                <?php }
                                ?>

                                <?php
                                if ($niqaty_amount > 0)
                                { ?>
                                <li>
                                    <label>@lang('labels.niqaty_redeem_availed')</label>
                                    <h4><?php echo $niqaty_amount; ?> @lang("labels.currency")</h4>
                                </li>
                                <?php }
                                ?>

                                <?php if ($redeem_discount > 0) { ?>
                                <li>
                                    <label>@lang('labels.discount_availed_on_redeem')</label>
                                    <h4><?php echo $redeem_discount; ?> <?php echo($lang == 'eng' ? 'SAR' : 'ريال سعودي'); ?></h4>
                                </li>
                                <?php } ?>
                                <?php if ($pre_total_discount != '' && $pre_total_discount > 0)
                                { ?>
                                <li class="discount_on_promo_auto">
                                    <label>@lang('labels.discount_on_promo')</label>
                                    <h4 class="discount"><?php echo number_format($pre_total_discount * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']), 2); ?>
                                        @lang('labels.currency')</h4>
                                </li>
                                <?php } ?>
                                <li class="discount_on_promo_code" style="display: none;">
                                    <label>@lang('labels.discount_on_promo_code')</label>
                                    <h4 class="discount">300 @lang('labels.currency')</h4>
                                </li>

                                <li>
                                    <label><?php echo($lang == 'eng' ? 'Total Amount' : 'المبلغ الإجمالي'); ?></label>
                                    <h4>
                                        <div id="total_amount"
                                             style="display: inline-block;"><?php echo number_format(((Session::get('rent_per_day') + $dropoff_charges - $pre_total_discount) * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges + $delivery_charges, 2); ?></div> @lang("labels.currency")
                                    </h4>
                                </li>

                                <?php if ($vat_amount > 0)
                                { ?>
                                <li>
                                    <label>@lang("labels.vat_applicable") (<?php echo Session::get('vat_percentage'); ?>
                                        %)</label>
                                    <h4><?php echo number_format($vat_amount, 2); ?> <?php echo($lang == 'eng' ? 'SAR' : 'ريال سعودي'); ?></h4>
                                </li>
                                <?php } ?>

                            </ul>
                        </div>
                    </div>
                    <div class="leftmostbottomsec">
                        <ul>
                            <li>
                                <h4>
                                    @lang('labels.you_pay_total')
                                    <span class="total_amount"><?php echo number_format($payable_amount, 2); ?>
                                        @lang('labels.currency')</span>
                                </h4>
                            </li>

                            <?php if ($post_total_discount != '' && $post_total_discount > 0)
                            { ?>
                            <li class="discount_on_promo_auto">
                                <label>@lang('labels.discount_on_promo')</label>
                                <h4 class="discount"><?php echo number_format($post_total_discount, 2); ?>
                                    @lang('labels.currency')</h4>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="rightCol">
                    <h1>
                        @lang('labels.payment')
                    </h1>
                    <p><?php echo($lang == 'eng' ? 'Please review the full booking details before confirming payment.' : 'الرجاء مراجعة تفاصيل الحجز قبل تاكيد الدفع'); ?> </p>
                    <br>
                    <h1> @lang('labels.personal_info')</h1>
                    <table border="0" class="table tableView">
                        <tr>
                            <th> @lang('labels.first_name'):</th>
                            <td><?php echo $payment_form_data['first_name']; ?></td>
                        </tr>
                        <tr>
                            <th> @lang('labels.last_name'):</th>
                            <td><?php echo $payment_form_data['last_name']; ?></td>
                        </tr>
                        <tr>
                            <th> @lang('labels.id_type'):</th>
                            <td><?php echo $customer_id_title; ?></td>
                        </tr>
                        <tr>
                            <th>@lang('labels.id_number'):</th>
                            <td><?php echo $payment_form_data['id_no']; ?></td>
                        </tr>
                        <tr>
                            <th>@lang('labels.mobile_no'):</th>
                            <td><?php echo $payment_form_data['mobile_no']; ?></td>
                        </tr>
                        <tr>
                            <th>@lang('labels.email_address'):</th>
                            <td><?php echo $payment_form_data['email']; ?></td>
                        </tr>
                    </table>
                    <br>
                <?php
                $siteSettings = custom::site_settings();
                if (Session::get('user_type') == 'corporate_customer') {
                    $user_type = 'corporate';
                    $getCorporateCustomerSettings = custom::loggedInUserProfileInnerInfo($user_type);
                    // $ccCompany = $getCorporateCustomerSettings['user_data']->cc_company;
                    $ccCompany = $siteSettings->cc_company;
                } else {
                    $user_type = 'individual';
                    $ccCompany = $siteSettings->cc_company;
                }
                ?>
                <!--<link rel="stylesheet" href="https://www.paytabs.com/theme/express_checkout/css/express.css">-->
                    <!--<link rel="stylesheet" href="http://key.ed.sa/paytab/payTabCss.css">-->
                    <!-- Button Code for PayTabs Express Checkout -->
                    <?php if($ccCompany == 'paytabs'){ ?>
                    <h1><?php echo($lang == 'eng' ? 'Payment Method' : 'الدفع'); ?></h1>
                    <script src="https://www.paytabs.com/express/express_checkout_v3.js"></script>
                    <div class="PT_express_checkout"></div>
                    <script type="text/javascript">
                        //		linktocss: "http://key.ed.sa/paytab/payTabCss.css",
                        Paytabs("#express_checkout").expresscheckout({
                            settings: {
                                merchant_id: "<?php echo $api_settings->paytabs_merchant_id; ?>",
                                secret_key: "<?php echo $api_settings->paytabs_secret_key; ?>",
                                amount: "<?php echo Session::get('total_amount_for_transaction'); ?>",
                                currency: "SAR",
                                title: "<?php echo $payment_form_data['first_name'] . ' ' . $payment_form_data['last_name']; ?>",
                                product_names: "<?php echo $car_info->car_type_eng_title . ' ' . $car_info->eng_title . ' ' . $car_info->year; ?>",
                                order_id: '<?php echo Session::get('booking_id') . '-' . $lang . '-' . $from_branch_id . '-' . $customer_type_for_cc; ?>',
                                url_redirect: "<?php echo $lang_base_url; ?>/booked",
                                display_customer_info: 0,
                                display_billing_fields: 0,
                                display_shipping_fields: 0,
                                language: "<?php echo($lang == 'eng' ? 'en' : 'ar'); ?>",
                                redirect_on_reject: 0,
                                is_tokenization: false,
                                style: {
                                    css: "custom",
                                    linktocss: "http://localhost/paytabs/payTabCss.css"
                                },
                                is_iframe: {
                                    load: "onbodyload", //onbodyload
                                    show: 1
                                }
                            },
                            customer_info: {
                                first_name: "<?php //echo $payment_form_data['first_name']; ?>", //we can also pre fill it
                                last_name: "<?php //echo $payment_form_data['last_name']; ?>",
                                phone_number: "<?php echo $payment_form_data['mobile_no']; ?>",
                                email_address: "<?php echo $payment_form_data['email']; ?>",
                                //email_address: "<?php echo $rejectedEmail; ?>",
                                country_code: ""
                            },
                            billing_address: {
                                full_address: "addr 1",
                                city: "city 1",
                                state: "state 1",
                                country: "BHR",
                                postal_code: "12345"
                            },
                            shipping_address: {
                                shipping_first_name: "",
                                shipping_last_name: "",
                                full_address_shipping: "",
                                city_shipping: "",
                                state_shipping: "",
                                country_shipping: "",
                                postal_code_shipping: ""
                            }
                        });
                    </script>
                    <?php }else if($ccCompany == 'sts'){
                    $apiSettings = custom::api_settings();
                    $securityToken = $apiSettings->sts_secret_key_web;
                    $merchantID = $apiSettings->sts_merchant_id_web;
                    $sts_payment_link = $apiSettings->sts_payment_link;

                    if (isset($Response_TransactionID)) {
                        $transaction_id = $Response_TransactionID;
                    } else {
                        $transaction_id = 'KEY' . str_pad(Session::get('booking_id'), 17, '0', STR_PAD_LEFT);
                    }
                    $get_ammount = number_format(Session::get('total_amount_for_transaction'), 2, '.', '');

                    $PaymentParams = [];
                    $paymentParameters = [];
                    $paymentParameters["TransactionID"] = $transaction_id;
                    $paymentParameters["MerchantID"] = $merchantID;
                    $paymentParameters["Amount"] = $get_ammount * 100;
                    $paymentParameters["CurrencyISOCode"] = "682";
                    $paymentParameters["MessageID"] = "1";
                    $paymentParameters["Quantity"] = "1";
                    $paymentParameters["Channel"] = "0";
                    $paymentParameters["ItemID"] = "130";
                    $paymentParameters["PaymentMethod"] = "1";
                    $paymentParameters["PaymentDescription"] = "Payment";
                    $paymentParameters["Language"] = $lang == "eng" ? "En" : "Ar";
                    $paymentParameters["ThemeID"] = "1000000001";

                    if ($lang == 'arb') {
                        $paymentParameters["ResponseBackURL"] = $base_url . "/cc-payment";
                    } else {
                        $paymentParameters["ResponseBackURL"] = $base_url . "/en/cc-payment";
                    }

                    $paymentParameters["Version"] = "2.0";
                    $paymentParameters["RedirectURL"] = $sts_payment_link;

                    $PaymentParams = $paymentParameters;
                    $PaymentParams["SecureHash"] = custom::generate_token(array(
                        'Amount' => $paymentParameters["Amount"],
                        'Channel' => $paymentParameters["Channel"],
                        'CurrencyISOCode' => $paymentParameters["CurrencyISOCode"],
                        'Language' => $paymentParameters["Language"],
                        'MerchantID' => $paymentParameters["MerchantID"],
                        'MessageID' => $paymentParameters["MessageID"],
                        'ThemeID' => $paymentParameters["ThemeID"],
                        'ItemID' => $paymentParameters["ItemID"],
                        'PaymentDescription' => urlencode($paymentParameters["PaymentDescription"]),
                        'PaymentMethod' => $paymentParameters["PaymentMethod"],
                        'Quantity' => $paymentParameters["Quantity"],
                        'ResponseBackURL' => $paymentParameters["ResponseBackURL"],
                        'TransactionID' => $paymentParameters["TransactionID"],
                        'Version' => $paymentParameters["Version"],
                    ));

                    $redirectURL = (String)$PaymentParams["RedirectURL"];
                    $amount = (String)$PaymentParams["Amount"];
                    $currencyCode = (String)$PaymentParams["CurrencyISOCode"];
                    $transactionID = (String)$PaymentParams["TransactionID"];
                    $merchantID = (String)$PaymentParams["MerchantID"];
                    $language = (String)$PaymentParams["Language"];
                    $messageID = (String)$PaymentParams["MessageID"];
                    $secureHash = (String)$PaymentParams["SecureHash"];
                    $themeID = (String)$PaymentParams["ThemeID"];
                    $ItemID = (String)$PaymentParams["ItemID"];
                    $PaymentDescription = (String)$PaymentParams["PaymentDescription"];
                    $responseBackURL = (String)$PaymentParams["ResponseBackURL"];
                    $channel = (String)$PaymentParams["Channel"];
                    $quantity = (String)$PaymentParams["Quantity"];
                    $version = (String)$PaymentParams["Version"];
                    $paymentMethod = (String)$PaymentParams["PaymentMethod"];
                    ?>

                    <h1>
                        <?php echo($lang == 'eng' ? 'Payment Method' : 'الدفع'); ?>
                        <img src="<?php echo $base_url;?>/public/frontend/images/payment_method_icon_<?php echo $lang; ?>.png"
                             style="width: 212px;margin-left: 10px;">
                    </h1>
                    <div class="container-fluid STS_express_checkout">
                        <?php if(isset($sts_error)){ ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $sts_error; ?>
                        </div>
                        <?php } ?>
                        <div class="creditCardForm">
                            <div class="payment">
                                <form action="<?php echo $redirectURL?>" name="redirectForm" id="stsPayOne"
                                      method="post">
                                    <input name="MerchantID" type="hidden" value="<?php echo $merchantID; ?>"/>
                                    <input name="Amount" type="hidden" value="<?php echo $amount; ?>"/>
                                    <input name="CurrencyISOCode" type="hidden" value="<?php echo $currencyCode?>"/>
                                    <input name="Language" type="hidden" value="<?php echo $language?>"/>
                                    <input name="MessageID" type="hidden" value="<?php echo $messageID?>"/>
                                    <input name="TransactionID" type="hidden" value="<?php echo $transactionID?>"/>
                                    <input name="ItemID" type="hidden" value="<?php echo $ItemID?>"/>
                                    <input name="ThemeID" type="hidden" value="<?php echo $themeID?>"/>
                                    <input name="ResponseBackURL" type="hidden" value="<?php echo $responseBackURL?>"/>
                                    <input name="Quantity" type="hidden" value="<?php echo $quantity?>"/>
                                    <input name="Channel" type="hidden" value="<?php echo $channel?>"/>
                                    <input name="Version" type="hidden" value="<?php echo $version?>"/>
                                    <input name="PaymentMethod" type="hidden" value="<?php echo $paymentMethod?>"/>
                                    <input name="PaymentDescription" type="hidden"
                                           value="<?php echo $PaymentDescription?>"/>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group owner">
                                                <label for="owner">@lang('labels.cardholders_full_name')</label>
                                                <input type="text" name="CardHolderName" class="form-control"
                                                       id="owner">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cardNumber">@lang('labels.credit_card_number')</label>
                                                <input type="text" name="CardNumber" class="form-control"
                                                       id="cardNumber" maxlength="16" onkeyup="check_if_mada_card($(this).val());">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group CVV">
                                                <label for="cvv">@lang('labels.cvc_number')</label>
                                                <input type="text" name="SecurityCode" class="form-control" id="cvv"
                                                       maxlength="3">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12">
                                            <label>@lang('labels.expiry_date')</label>
                                            <div class="form-group" id="expiration-date">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <select name="ExpiryDateMonth">
                                                            <?php
                                                            $startMonth = '1';
                                                            $endMonth = '12';
                                                            $currentMonth = date('m');
                                                            for($startMonth;$startMonth <= $endMonth;$startMonth++){
                                                            $startMonth = $startMonth < 10 ? '0' . $startMonth : $startMonth;
                                                            $monthName = custom::month_english($startMonth);
                                                            if ($lang == 'arb') {
                                                                $monthName = custom::month_arabic($monthName);
                                                            }
                                                            $selectedMonth = $startMonth == $currentMonth ? 'selected' : '';
                                                            ?>
                                                            <option
                                                                <?php echo $selectedMonth; ?> value="<?php echo $startMonth; ?>"><?php echo $startMonth; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="ExpiryDateYear">
                                                            <?php
                                                            $startYear = date('Y');
                                                            $endYear = $startYear + 10;
                                                            for($startYear;$startYear <= $endYear;$startYear++){
                                                            ?>
                                                            <option value="<?php echo substr($startYear, -2); ?>"><?php echo $startYear; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input name="SecureHash" type="hidden" value="<?php echo $secureHash ?>"/>
                                        <div class="col-md-12">
                                            <div class="form-group" id="pay-now">
                                                <input type="submit" class="btn yellowButton" id="confirm-purchase"
                                                       value="@lang('labels.pay_now') <?php echo number_format(Session::get('total_amount_for_transaction'), 2); ?> @lang('labels.currency_text')">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="hdn_booking_id"
                                           value="<?php echo Session::get('booking_id'); ?>">
                                </form>
                            </div>
                        </div>

                    </div>
                    <?php } elseif ($ccCompany == 'hyper_pay' && isset($hp_params)) { ?>
                    <?php
                    if (isset($hp_params['checkout_id']))
                    { ?>
                    <h1>
                        <?php echo($lang == 'eng' ? 'Payment Method' : 'الدفع'); ?>
                        <img src="<?php echo $base_url;?>/public/frontend/images/payment_method_icon_<?php echo $lang; ?>.png"
                             style="width: 212px;margin-left: 10px;">
                    </h1>

                    <style>
                        .wpwl-apple-pay-button{-webkit-appearance: -apple-pay-button !important;}
                    </style>

                    {{--if user is coming through apple pay--}}
                    @if (Session::get('payment_form_data')['isMada'] == 2)
                        <script>
                            var wpwlOptions = {
                                paymentTarget:"_top",
                                applePay: {
                                    displayName: "Key Car Rental",
                                    total: { label: "Key Car Rental" }
                                }
                            }
                        </script>
                    @else
                        <script>
                            var wpwlOptions = {
                                locale: "<?php echo ($lang == 'eng' ? 'en' : 'ar'); ?>",
                                paymentTarget:"_top",
                                numberFormatting: <?php echo ($lang == 'eng' ? 'true' : 'false'); ?>,
                                autofocus: 'card.number',
                                onReady: function () {
                                    $('.wpwl-button-pay').html('<?php echo Lang::get('labels.pay_now') . ' ' . number_format(Session::get('total_amount_for_transaction'), 2) . ' ' . Lang::get('labels.currency_text');  ?>');
                                    $('.wpwl-button-pay').closest(".wpwl-wrapper-submit").css({'margin-top': '20px'});
                                    $('.wpwl-button-pay').css({'border-color': '#FE7E00', 'background-color': '#FE7E00'});
                                    $('.wpwl-label-brand').css({'display': 'none'});
                                    $('.wpwl-wrapper-brand').css({'display': 'none'});
                                    $('.wpwl-control-cardHolder, .wpwl-control-expiry').addClass('hp_form_field');
                                }
                            }
                        </script>
                    @endif

                    <script src="{{rtrim($api_settings->hyper_pay_endpoint_url, '/')}}/v1/paymentWidgets.js?checkoutId={{$hp_params['checkout_id']}}"></script>

                    <form action="{{$lang_base_url.'/hp_check_payment_status'}}" class="paymentWidgets"
                          data-brands="{{custom::entity_id(Session::get('payment_form_data')['isMada'], $api_settings, true)}}"></form>
                    <?php } else { ?>
                    <div class="alert alert-danger">{{ucfirst($hp_params['message'])}}</div>
                    <?php }
                    ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
@endsection