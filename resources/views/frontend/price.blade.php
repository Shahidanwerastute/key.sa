@extends('frontend.layouts.template')

@section('content')

    <?php
        $items = [];
        $item_data = [
            'item_id' => $car_info->car_type_eng_title . " " . $car_info->eng_title . ' ' . $car_info->year,
            'item_name' => $car_info->car_type_eng_title . " " . $car_info->eng_title . ' ' . $car_info->year
        ];
        $items[] = $item_data;
        $event = 'select_item';
        $event_data = [
            'items' => $items
        ];
        custom::sendEventToGA4($event, $event_data);
    ?>

    <style>
        .share-and-earn-on-subscription-discount-btn a {
            background-image: url(<?php echo $base_url; ?>/public/frontend/images/network-icon.png);
        }
        body.arb .share-and-earn-on-subscription-discount-btn a {
            background-image: url(<?php echo $base_url; ?>/public/frontend/images/network-icon-arb.png);
        }
        .pricePageSec {
            padding-bottom: 150px !important;
        }
    </style>
    <?php
    $siteSettings = custom::site_settings();
    $vat_to_add = 0;
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
                    <li class="active">
                        <div><span>02</span> @lang('labels.price_n_extras')</div>
                    </li>
                    <li>
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
                            <img src="<?php echo $car_image_path; ?>" alt="{{ $lang == 'eng' ? $car_info->image1_eng_alt : $car_info->image1_arb_alt }}" height="132"
                                 width="274"/>
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
                                             src="<?php echo $base_url; ?>/public/frontend/images/icon-location.png" alt="">
                                        <?php
                                        echo ($lang == 'eng' ? $pickup_branch_info->eng_title : $pickup_branch_info->arb_title) . ", " . ($lang == 'eng' ? $pickup_branch_info->city_eng_title : $pickup_branch_info->city_arb_title);
                                        ?>
                                    </li>
                                    <li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/icon-calendar.png" alt=""> <?php echo date('d / m / Y', strtotime($booking_info['pickup_date'])); ?>
                                    </li>
                                    <li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/icon-clock-time.png"> <?php echo $booking_info['pickup_time']; ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="col twoBig peddLftSet">
                                <label>@lang('labels.drop_off')</label>
                                <ul>
                                    <li title="JEDDAH AIRPORT, NORTH TERMINAL">
                                        <img class="abImg"
                                             src="<?php echo $base_url; ?>/public/frontend/images/icon-location.png" alt="">
                                        <?php
                                        echo ($lang == 'eng' ? $dropoff_branch_info->eng_title : $dropoff_branch_info->arb_title) . ", " . ($lang == 'eng' ? $dropoff_branch_info->city_eng_title : $dropoff_branch_info->city_arb_title);
                                        ?>
                                    </li>
                                    <li>
                                        <img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/icon-calendar.png" alt="">
                                        <?php echo date('d / m / Y', strtotime($booking_info['dropoff_date'])); ?>
                                    </li>
                                    <li>
                                        <img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/icon-clock-time.png"> <?php echo $booking_info['dropoff_time']; ?>
                                    </li>
                                </ul>
                            </div>
                            <!-- <div class="col bookFeature peddLftSet <?php echo($car_info->min_age > 0 ? 'contains-min-age' : ''); ?>">
                                <label>@lang('labels.features')</label>
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
                                <div class="clearfix"></div>
                            </div> -->
                            <div class="col twoBig peddLftSet bigstyle-class">
                                <label>@lang('labels.rental_period')</label>
                                <ul>
                                    <?php if ($booking_info['is_delivery_mode'] == 2) { ?>
                                        <li><?php echo $booking_info['hours_diff']; ?> @lang('labels.hours')</li>
                                    <?php } elseif ($booking_info['is_delivery_mode'] == 4) { ?>
                                        <li><?php echo $booking_info['subscribe_for_months']; ?> @lang('labels.months')</li>
                                    <?php } else { ?>
                                        <li><?php echo $booking_info['days']; ?> @lang('labels.days')</li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php if ($booking_info['is_delivery_mode'] != 4) { ?>
                                <div class="col twoBig peddLftSet bigstyle-class">
                                    <?php if ($booking_info['is_delivery_mode'] == 2) { ?>
                                        <label>@lang('labels.rent_per_hour')</label>
                                    <?php } elseif (isset($booking_info['isLimousine']) && $booking_info['isLimousine'] == 1) { ?>
                                        <label>@lang('labels.rent_per_trip')</label>
                                    <?php } else { ?>
                                        <label>@lang('labels.rent_per_day')</label>
                                    <?php } ?>
                                    <ul>
                                        <li>
                                            <h4><?php echo number_format($rent_p_day_loya_vs_dis, 2); ?> @lang('labels.currency')</h4>
                                        </li>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="rightCol">
                    <h1>
                        @lang('labels.price')
                        <span>@lang('labels.extras')</span>
                    </h1>
                    <div class="select-any-extra-text">
                        <p><?php echo($lang == 'eng' ? 'Add full insurance coverage to your booking.' : 'أضف تغطية تامينية شاملة لحجزك'); ?></p>
                    </div>
                    <form action="<?php echo $lang_base_url; ?>/payment" method="post">
                        <div class="extraOptList" style="display: none;">
                            <div class="options-list custom-border-bot">
                                <?php
                                $is_driver_available_for_this_branch = custom::is_driver_available_for_this_branch($booking_info['pickup_date'], $pickup_branch_info->id);
                                if ($extra_charges) {
                                    foreach ($extra_charges as $extra_charge){

                                    if ($extra_charge->charge_element == 'CDW') {
                                        $input_id = 'ldwIC';
                                        $label_for = 'ldwIC';
                                        $field_name = 'cdw';
                                        $image_name = 'ldwIC.png';
                                        $image_hover_name = 'ldwIC-hover.png?v=0.1';
                                        $field_title = Lang::get("labels.lost_damage");
                                    } elseif ($extra_charge->charge_element == 'CDW Plus') {
                                        $input_id = 'ldwICP';
                                        $label_for = 'ldwICP';
                                        $field_name = 'cdw_plus';
                                        $image_name = 'ldwIC.png';
                                        $image_hover_name = 'ldwIC-hover.png?v=0.1';
                                        $field_title = Lang::get("labels.cdw_plus");
                                    } elseif ($extra_charge->charge_element == 'GPS') {
                                        $input_id = 'gpsIC';
                                        $label_for = 'gpsIC';
                                        $field_name = 'gps';
                                        $image_name = 'kmIC.png';
                                        $image_hover_name = 'kmIC-hover.png?v=0.1';
                                        $field_title = Lang::get("labels.gps");
                                    } elseif ($extra_charge->charge_element == 'Extra Driver') {
                                        if (!$is_driver_available_for_this_branch) {
                                            continue;
                                        }
                                        $input_id = 'extraDriveIC';
                                        $label_for = 'extraDriveIC';
                                        $field_name = 'extra_driver';
                                        $image_name = 'extraDriveIC.png';
                                        $image_hover_name = 'extraDriveIC-hover.png?v=0.1';
                                        $field_title = Lang::get("labels.extra_driver");
                                    } elseif ($extra_charge->charge_element == 'Baby Seat') {
                                        $input_id = 'bcpsIC';
                                        $label_for = 'bcpsIC';
                                        $field_name = 'baby_seat';
                                        $image_name = 'bcpsIC.png';
                                        $image_hover_name = 'bcpsIC-hover.png?v=0.1';
                                        $field_title = Lang::get("labels.baby_protection");
                                    }

                                    if ($extra_charge->is_one_time_applicable_on_booking == 1) {
                                        $multiply_factor = 1;
                                    } else {
                                        $multiply_factor = ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']);
                                    }

                                    ?>
                                    <div class="deFaultRow custom-select-full-box">
                                        <div class="checkbox-box-div">
                                            <p class="checkBox">
                                            <div class="custom-cehck-box">
                                                <input id="<?php echo $input_id; ?>" name="<?php echo $field_name; ?>" data-total_with_days="<?php echo ($booking_info['is_delivery_mode'] == 4 ? round($extra_charge->price * 30, 2) : (int)$extra_charge->price * $multiply_factor); ?>"
                                                       value="<?php echo ($booking_info['is_delivery_mode'] == 4 ? $extra_charge->price : (int)$extra_charge->price); ?>" type="checkbox"
                                                       class="extraChargesCB">
                                                <label for="<?php echo $label_for; ?>"></label>
                                            </div>
                                            <img src="<?php echo $base_url; ?>/public/frontend/images/<?php echo $image_name; ?>"
                                                 alt="Car" height="38" width="38" class="normal"/>
                                            <img src="<?php echo $base_url; ?>/public/frontend/images/<?php echo $image_hover_name; ?>"
                                                 alt="Car" height="38" width="38" class="hover"/>
                                            <?php echo $field_title; ?>
                                            <?php if ($field_name == 'cdw_plus') { ?>
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#cdw_plus_model" style="margin-<?php echo ($lang == 'eng' ? 'left' : 'right'); ?>: 10px;">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/ques.png?v=0.1" alt="?" height="18" width="18" style="transform: scaleX(<?php echo ($lang == 'eng' ? '1' : '-1'); ?>)"/>
                                            </a>
                                            <?php } ?>
                                            </p>
                                        </div>
                                        <div class="checkbox-per-day-box">
                                            <p>
                                                <?php echo ($booking_info['is_delivery_mode'] == 4 ? round($extra_charge->price * 30, 2) : (int)$extra_charge->price); ?> @lang('labels.currency')
                                                @if ($extra_charge->is_one_time_applicable_on_booking == 0)
                                                    @if ($booking_info['is_delivery_mode'] == 2)
                                                        @lang('labels.per_hour')
                                                    @elseif ($booking_info['is_delivery_mode'] == 4)
                                                        (1 @lang('labels.months'))
                                                    @else
                                                        @lang('labels.per_day')
                                                    @endif
                                                @else
                                                    @lang('labels.per_booking')
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <input type="hidden" name="<?php echo $field_name; ?>_is_one_time_applicable_on_booking" value="<?php echo $extra_charge->is_one_time_applicable_on_booking; ?>">
                                    <?php }
                                } ?>
                            </div>
                            <div class="rows-main-box">
                                <div class="rows-bg-color-box">
                                    <div class="row bg_sandGrayDark">
                                        <div class="col-sm-9 col-9">
                                            <p>
                                                @if (isset($booking_info['isLimousine']) && $booking_info['isLimousine'] == 1)
                                                    <?php echo ($lang == 'eng' ? 'Total Rent For Trip' : 'إجمالي الإيجار للرحلة'); ?>
                                                @else
                                                    @lang('labels.total_rent_for_small') <?php echo($booking_info['is_delivery_mode'] == 2 ? $booking_info['hours_diff'] : ($booking_info['is_delivery_mode'] == 4 ? 1 : $booking_info['days'])); ?>
                                                    @if ($booking_info['is_delivery_mode'] == 2)
                                                        @lang('labels.hours')
                                                    @elseif ($booking_info['is_delivery_mode'] == 4)
                                                        @lang('labels.months')
                                                    @else
                                                        @lang('labels.days')
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-sm-3 col-3">
                                            <p><?php echo number_format($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']), 2); ?> @lang('labels.currency')</p>
                                        </div>
                                    </div>

                                    <!-- For Dropoff charges -->
                                    <?php if ($dropoff_charges){ ?>
                                    <div class="row bg_sandGrayDark">
                                        <div class="col-sm-9 col-9">
                                            <p>@lang('labels.dropoff_charges') </p>
                                        </div>
                                        <div class="col-sm-3 col-3">
                                            <p><?php echo $dropoff_charges_price; ?> @lang('labels.currency')</p>
                                        </div>
                                    </div>
                                    <?php } ?>

                                <!-- For Delivery charges -->
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
                                    ?>

                                    <?php if ($delivery_charges != 0)
                                    { ?>
                                    <div class="row bg_sandGrayDark">
                                        <div class="col-sm-9 col-9">
                                            <p><?php echo($lang == 'eng' ? 'Delivery Charges' : 'رسوم خدمة توصيل السيارة'); ?> </p>
                                        </div>
                                        <div class="col-sm-3 col-3">
                                            <p><?php echo $delivery_charges; ?> @lang('labels.currency')</p>
                                        </div>
                                    </div>
                                    <?php } ?>


                                    <div class="row bg_sandGrayDark">
                                        <div class="col-sm-9 col-9">
                                            <p>
                                                @if (isset($booking_info['isLimousine']) && $booking_info['isLimousine'] == 1)
                                                    <?php echo($lang == 'eng' ? 'Total Extras For Trip' : 'إجمالي الإضافات للرحلة'); ?>
                                                @else
                                                    @lang('labels.total_extras_for') <?php echo($booking_info['is_delivery_mode'] == 2 ? $booking_info['hours_diff'] : ($booking_info['is_delivery_mode'] == 4 ? 1 : $booking_info['days'])); ?>
                                                    @if ($booking_info['is_delivery_mode'] == 2)
                                                        @lang('labels.hours')
                                                    @elseif ($booking_info['is_delivery_mode'] == 4)
                                                        @lang('labels.months')
                                                    @else
                                                        @lang('labels.days')
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-sm-3 col-3">
                                            <p><span id="extrasFieldHere">0</span> @lang('labels.currency')</p>
                                        </div>
                                    </div>

                                    <?php if ($parking_fee > 0)
                                    { ?>
                                    <div class="row bg_sandGrayDark">
                                        <div class="col-sm-9 col-9">
                                            <p><?php echo($lang == 'eng' ? 'Parking Fee' : 'رسوم مواقف'); ?> </p>
                                        </div>
                                        <div class="col-sm-3 col-3">
                                            <p><?php echo $parking_fee; ?> @lang('labels.currency')</p>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <?php if ($tamm_charges_for_branch > 0)
                                    { ?>
                                    <div class="row bg_sandGrayDark">
                                        <div class="col-sm-9 col-9">
                                            <p>@lang('labels.tamm_charges') </p>
                                        </div>
                                        <div class="col-sm-3 col-3">
                                            <p><?php echo $tamm_charges_for_branch; ?> @lang('labels.currency')</p>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <?php if ($waiting_extra_hours_charges > 0)
                                    { ?>
                                    <div class="row bg_sandGrayDark">
                                        <div class="col-sm-9 col-9">
                                            <p>@lang('labels.limousine_extra_charges') </p>
                                        </div>
                                        <div class="col-sm-3 col-3">
                                            <p><?php echo $waiting_extra_hours_charges; ?> @lang('labels.currency')</p>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <?php
                                    if (Session::get('vat_mode') == 'on' && Session::get('vat_percentage') > 0){
                                    $vat_to_add = (Session::get('vat_percentage') / 100) * (($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges);
                                    ?>
                                    <div class="row finalPrice">
                                        <div class="col-sm-9 col-9">
                                            <p><strong><?php echo($lang == 'eng' ? 'Total' : 'المجموع'); ?></strong></p>
                                        </div>
                                        <div class="col-sm-3 col-3">
                                            <h3>
                                                <span class="totalPrice"><?php echo number_format(($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges, 2); ?></span> @lang('labels.currency')
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="row bg_sandGrayDark">
                                        <div class="col-sm-9 col-9">
                                            <p>@lang("labels.vat_applicable") (<?php echo Session::get('vat_percentage'); ?>
                                                %)</p>
                                        </div>
                                        <div class="col-sm-3 col-3">
                                            <p>
                                                <span id="hasVat"><?php echo round($vat_to_add, 2); ?></span> @lang('labels.currency')
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row finalPrice">
                                        <div class="col-sm-9 col-9">
                                            <p>
                                                <strong>
                                                    @lang('labels.grand_total')
                                                    {{($booking_info['is_delivery_mode'] == 4 ? '(1 '.trans('labels.months').')' : '')}}
                                                </strong>
                                            </p>
                                        </div>
                                        <div class="col-sm-3 col-3">
                                            <h3>
                                                <span class="totalPriceWithVat"><?php echo number_format(($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges + $vat_to_add, 2); ?></span> @lang('labels.currency')
                                            </h3>
                                        </div>
                                    </div>
                                    <?php }else{ ?>
                                    <div class="row finalPrice">
                                        <div class="col-sm-9 col-9">
                                            <p>
                                                <strong>
                                                    @lang('labels.grand_total')
                                                    {{($booking_info['is_delivery_mode'] == 4 ? '(1 '.trans('labels.months').')' : '')}}
                                                </strong>
                                            </p>
                                        </div>
                                        <div class="col-sm-3 col-3">
                                            <h3>
                                                <span class="totalPrice"><?php echo number_format(($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges, 2); ?></span> @lang('labels.currency')
                                            </h3>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>

                                <div class="row nextSteap">
                                    <div class="">
                                        <a href="javascript:void(0);">
                                            <input type="submit" value="@lang('labels.go_to_payment')"
                                                   class="redishButtonRound"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function () {
                                $('.extraOptList').show();
                            });
                        </script>

                        <input type="hidden" name="total_rent_hdn" id="rent_multip_day_hdn_help"
                               value="<?php echo ($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges; ?>">
                        <input type="hidden" class="totalPriceWithVatInput" name="totalPriceWithVat"
                               value="<?php echo number_format(($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges + $vat_to_add, 2); ?>">
                        <input type="hidden" id="vat_percentage" name="vat_percentage"
                               value="<?php echo(Session::get('vat_mode') == 'on' && Session::get('vat_percentage') > 0 ? Session::get('vat_percentage') : 0); ?>">
                        <input type="hidden" id="vat" name="vat"
                               value="<?php echo(Session::get('vat_mode') == 'on' && Session::get('vat_percentage') > 0 ? $vat_to_add : 0); ?>">

                        <?php
                        // started putting data to session to check
                        $car_extra_prices_data_to_check = [
                            'total_rent_hdn' => ($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges,
                            'totalPriceWithVat' => number_format(($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $waiting_extra_hours_charges + $vat_to_add, 2),
                            'vat_percentage' => (Session::get('vat_mode') == 'on' && Session::get('vat_percentage') > 0 ? Session::get('vat_percentage') : 0),
                            'vat' => (Session::get('vat_mode') == 'on' && Session::get('vat_percentage') > 0 ? $vat_to_add : 0),
                        ];
                        Session::put('car_extra_prices_data_to_check', $car_extra_prices_data_to_check);
                        Session::save();
                        // end putting data to session to check
                        ?>

                    </form>
                </div>
            </div>
        </div>
    </section>

    @if($siteSettings->refer_and_earn_option == 'on' && $booking_info['is_delivery_mode'] == 4)
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