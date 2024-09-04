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
    // custom::dump($booking_info);
    // echo $rent_p_day_loya_vs_dis;die();
    ?>
    <section class="searchNbookSec">
        <div class="container-md <?php echo custom::addClass(); ?>">
            <?php //echo custom::deliveryPickupTabsArea($lang); ?>
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
    <?php
    $site_settings = custom::site_settings();
    if (($booking_info['is_delivery_mode'] == 1 || $booking_info['is_subscription_with_delivery_flow'] == 1) && $booking_info['delivery_charges'] > 0) {
        $delivery_charges = (float)$booking_info['delivery_charges'];
    } else {
        $delivery_charges = 0;
    }

    $parking_fee = $booking_info['parking_fee'];
    $tamm_charges_for_branch = $booking_info['tamm_charges_for_branch'];
    $waiting_extra_hours_charges = 0; // as we haven't done limousine mode for mobile version

    if (Session::get('vat_mode') == 'on' && Session::get('vat_percentage') > 0) {
        $vat_to_add = (Session::get('vat_percentage') / 100) * (($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch);
    }
    ?>
    <section class="pricePageSec">
        <div class="container">
            <div class="pricePgWrapper ">
                <div class="leftCol">
                    <div class="carSumery peddLftSet">
                        <div class="bookName">
                            <h2>
                                <?php echo ($lang == 'eng' ? $car_info->car_type_eng_title : $car_info->car_type_arb_title) . ' ' . ($lang == 'eng' ? $car_info->eng_title : $car_info->arb_title) . ' ' . $car_info->year; ?>
                            </h2>
                        </div>
                        <h3>
                            @if ($booking_info['is_delivery_mode'] == 2)
                                <?php echo $booking_info['hours_diff']; ?> @lang('labels.hours')
                            @elseif ($booking_info['is_delivery_mode'] == 4)
                                1 @lang('labels.months')
                            @else
                                <?php echo $booking_info['days']; ?> @lang('labels.days')
                            @endif
                                <span
                                    class="totalPriceWithVat"><?php echo number_format(($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $vat_to_add, 2); ?></span> @lang('labels.currency')
                        </h3>
                    </div>
                    <div class="car-details">
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

                        <div class="basicDetails">
                            <div class="col twoBig peddLftSet listing-bg-icon">
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
                            <div class="col twoBig peddLftSet listing-bg-icon">
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
                    <div class="extra-services-title">
                        <?php if ($lang == 'eng') { ?>
                            <h3>Select Any Extra Needed from below</h3>
                        <?php } else { ?>
                            <h3>اختر اي خدمات اضافية من الاسفل</h3>
                        <?php } ?>
                    </div>
                    <form action="<?php echo $lang_base_url; ?>/payment" method="post">
                        <div class="extraOptList">
                            <div class="options-list custom-border-bot">
                                <?php
                                $is_driver_available_for_this_branch = custom::is_driver_available_for_this_branch($booking_info['pickup_date'], $pickup_branch_info->id);

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
                                    <div class="extra-services">
                                        <div class="custom-cehck-box">
                                            <input id="<?php echo $input_id; ?>" name="<?php echo $field_name; ?>"
                                                   data-total_with_days="<?php echo($booking_info['is_delivery_mode'] == 4 ? round($extra_charge->price * 30, 2) : (int)$extra_charge->price * $multiply_factor); ?>"
                                                   value="<?php echo($booking_info['is_delivery_mode'] == 4 ? $extra_charge->price : (int)$extra_charge->price); ?>"
                                                   type="checkbox"
                                                   class="extraChargesCB">

                                            <label for="<?php echo $input_id; ?>" class="label-service">
                                                <?php echo $field_title; ?>
                                                <?php if ($field_name == 'cdw_plus') { ?>
                                                <a href="javascript:void(0);" data-bs-toggle="modal"
                                                   data-bs-target="#cdw_plus_model"
                                                   style="margin-<?php echo($lang == 'eng' ? 'left' : 'right'); ?>: 10px;">
                                                    <img src="https://kra.ced.sa/public/frontend/images/ques.png?v=0.1"
                                                         alt="?" style="transform: scaleX(<?php echo ($lang == 'eng' ? 1 : -1) ?>)" width="18" height="18">
                                                </a>
                                                <?php } ?>
                                                <span><?php echo($booking_info['is_delivery_mode'] == 4 ? round($extra_charge->price * 30, 2) : (int)$extra_charge->price); ?> @lang('labels.currency')
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
                                        </span>
                                            </label>

                                            <input type="hidden"
                                                   name="<?php echo $field_name; ?>_is_one_time_applicable_on_booking"
                                                   value="<?php echo $extra_charge->is_one_time_applicable_on_booking; ?>">
                                        </div>
                                    </div>

                                <?php } ?>
                            </div>
                            <!--start order summary-->
                            <div class="orderSummary">
                                <div class="summary-col">
                                    <h4>@lang('labels.rental_period')</h4>
                                    <span class="summ-price">
                                        @if ($booking_info['is_delivery_mode'] == 2)
                                            <?php echo $booking_info['hours_diff']; ?> @lang('labels.hours')
                                        @elseif ($booking_info['is_delivery_mode'] == 4)
                                            <?php echo $booking_info['subscribe_for_months']; ?> @lang('labels.months')
                                        @else
                                            <?php echo $booking_info['days']; ?> @lang('labels.days')
                                        @endif
                                    </span>
                                </div>
                                <?php if ($booking_info['is_delivery_mode'] != 4) { ?>
                                <div class="summary-col">
                                    <h4>
                                        @if ($booking_info['is_delivery_mode'] == 2)
                                            @lang('labels.rent_per_hour')
                                        @else
                                            @lang('labels.rent_per_day')
                                        @endif
                                    </h4>
                                    <span class="summ-price"><?php echo number_format($rent_p_day_loya_vs_dis, 2); ?> @lang('labels.currency')</span>
                                </div>
                                <?php } ?>
                                <div class="summary-col">
                                    @if ($booking_info['is_delivery_mode'] == 2)
                                        <h4>@lang('labels.total_rent_for_small') <?php echo $booking_info['hours_diff']; ?> @lang('labels.hours')</h4>
                                        <span class="summ-price"><?php echo number_format($rent_p_day_loya_vs_dis * 1, 2); ?> @lang('labels.currency')</span>
                                    @elseif ($booking_info['is_delivery_mode'] == 4)
                                        <h4>@lang('labels.total_rent_for_small') 1 @lang('labels.months')</h4>
                                        <span class="summ-price"><?php echo number_format($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']), 2); ?> @lang('labels.currency')</span>
                                    @else
                                        <h4>@lang('labels.total_rent_for_small') <?php echo $booking_info['days']; ?> @lang('labels.days')</h4>
                                        <span class="summ-price"><?php echo number_format($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']), 2); ?> @lang('labels.currency')</span>
                                    @endif
                                </div>

                                <!-- For Dropoff charges -->
                                <?php if ($dropoff_charges){ ?>
                                <div class="summary-col">
                                    <h4>@lang('labels.dropoff_charges') </h4>
                                    <span class="summ-price"><?php echo $dropoff_charges_price; ?> @lang('labels.currency')</span>
                                </div>
                                <?php } ?>

                            <!-- For Delivery charges -->
                                <?php if ($delivery_charges != 0)
                                { ?>
                                <div class="summary-col">
                                    <h4><?php echo($lang == 'eng' ? 'Delivery Charges' : 'رسوم خدمة توصيل السيارة'); ?> </h4>
                                    <span><?php echo $delivery_charges; ?> @lang('labels.currency')</span>
                                </div>
                                <?php } ?>
                                <div class="summary-col">
                                    @if ($booking_info['is_delivery_mode'] == 2)
                                        <h4>@lang('labels.total_extras_for') <?php echo $booking_info['hours_diff']; ?> @lang('labels.hours')</h4>
                                    @elseif ($booking_info['is_delivery_mode'] == 4)
                                        <h4>@lang('labels.total_extras_for') 1 @lang('labels.months')</h4>
                                    @else
                                        <h4>@lang('labels.total_extras_for') <?php echo $booking_info['days']; ?> @lang('labels.days')</h4>
                                    @endif
                                    <span class="summ-price"><i
                                                id="extrasFieldHere">0</i> @lang('labels.currency')</span>
                                </div>

                                <?php if ($parking_fee > 0)
                                { ?>
                                <div class="summary-col">
                                    <h4><?php echo($lang == 'eng' ? 'Parking Fee' : 'رسوم مواقف'); ?> </h4>
                                    <span><?php echo $parking_fee; ?> @lang('labels.currency')</span>
                                </div>
                                <?php } ?>

                                <?php if ($tamm_charges_for_branch > 0)
                                { ?>
                                <div class="summary-col">
                                    <h4>@lang('labels.tamm_charges') </h4>
                                    <span><?php echo $tamm_charges_for_branch; ?> @lang('labels.currency')</span>
                                </div>
                                <?php } ?>

                                <?php
                                if (Session::get('vat_mode') == 'on' && Session::get('vat_percentage') > 0){
                                ?>
                                <div class="summary-col finalPrice">
                                    <h4><strong><?php echo($lang == 'eng' ? 'Total' : 'المجموع'); ?></strong></h4>
                                    <span class="totalPrice"><?php echo number_format(($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch, 2); ?> @lang('labels.currency')</span>
                                </div>
                                <div class="summary-col">
                                    <h4>@lang("labels.vat_applicable") (<?php echo Session::get('vat_percentage'); ?>
                                        %)</h4>
                                    <span id="hasVat"><?php echo round($vat_to_add, 2); ?> @lang('labels.currency')</span>
                                </div>
                                <div class="summary-col finalPrice">
                                    <h4><strong>@lang('labels.grand_total') {{($booking_info['is_delivery_mode'] == 4 ? '(1 '.trans('labels.months').')' : '')}}</strong></h4>
                                    <span class="totalPriceWithVat"><?php echo number_format(($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $vat_to_add, 2); ?> @lang('labels.currency')</span>
                                </div>
                                <?php }else{ ?>
                                <div class="summary-col finalPrice">
                                    <h4><strong>@lang('labels.grand_total') {{($booking_info['is_delivery_mode'] == 4 ? '(1 '.trans('labels.months').')' : '')}}</strong></h4>
                                    <span class="totalPrice"><?php echo number_format(($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch, 2); ?> @lang('labels.currency')</span>
                                </div>
                                <?php } ?>
                            </div>

                            <!--end order summary-->
                        </div>
                        <div class="payment nextSteap">
                            <div class="btn_mobile_v">
                                <input type="submit" value="<?php echo ($lang == 'eng' ? 'GO TO PAYMENT' : 'الانتقال للدفع'); ?>" class="redishButtonRound"/>

                            </div>
                        </div>
                        <script>
                            $(document).ready(function () {
                                $('#btn_orderSummary').click(function () {
                                    $('.orderSummary').slideToggle("slow");
                                });
                            });
                        </script>

                        <input type="hidden" name="total_rent_hdn" id="rent_multip_day_hdn_help"
                               value="<?php echo ($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch; ?>">
                        <input type="hidden" class="totalPriceWithVatInput" name="totalPriceWithVat"
                               value="<?php echo number_format(($rent_p_day_loya_vs_dis * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + $dropoff_charges_price + $delivery_charges + $parking_fee + $tamm_charges_for_branch + $vat_to_add, 2); ?>">
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