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
    $qitaf_amount = Session::has('qitaf_amount') ? Session::get('qitaf_amount') : 0;
    $niqaty_amount = Session::has('niqaty_amount') ? Session::get('niqaty_amount') : 0;

    if (isset($booking_payment_details) && $booking_payment_details->redeem_discount_availed > 0) {
        $redeem_discount = $booking_payment_details->redeem_discount_availed;
    } else {
        $redeem_discount = 0;
    }

    if (Session::get('dropoff_charges_amount') > 0) {
        $dropoff_charges = Session::get('dropoff_charges_amount');
    } else {
        $dropoff_charges = 0;
    }

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
        $payable_amount = ((Session::get('rent_per_day') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) - ($pre_total_discount * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']))) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + Session::get('dropoff_charges_amount') + $parking_fee + $tamm_charges_for_branch + $delivery_charges;
    } else {
        $payable_amount = (Session::get('rent_per_day') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + Session::get('dropoff_charges_amount') + $parking_fee + $tamm_charges_for_branch + $delivery_charges;
    }

    $vat_amount = (Session::get('vat_percentage') / 100) * $payable_amount;

    $payable_amount = $payable_amount + $vat_amount - $qitaf_amount - $niqaty_amount - $redeem_discount - $post_total_discount;

    ?>

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
    <section class="pricePageSec">
        <div class="container-md">
            <div class="pricePgWrapper ">
                <div class="leftCol add">
                    <div class="carSumery peddLftSet">
                        <div class="bookName">
                            <h2>
                                <?php echo($lang == 'eng' ? $car_info->car_type_eng_title : $car_info->car_type_arb_title); ?> <?php echo ($lang == 'eng' ? $car_info->eng_title : $car_info->arb_title) . ' ' . $car_info->year; ?>
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
                            <span class="totalPriceWithVat"><?php echo number_format($payable_amount, 2); ?></span> @lang('labels.currency')
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
                            <span id="rent_per_day_span"><?php echo number_format(Session::get('rent_per_day'), 2); ?> @lang('labels.currency')</span>
                        </div>
                        <?php } ?>
                        <?php if ($cdw > 0 || $cdw_plus > 0 || $gps > 0 || $extra_driver > 0 || $baby_seat > 0) { ?>
                        <div class="summary-col">
                            <label>@lang('labels.extra_services')</label>
                            <ul>
                                <?php if ($cdw > 0){?>
                                <li>
                                    <h4>@lang("labels.lost_damage")</h4>
                                    <span><?php echo $booking_info['is_delivery_mode'] == 4 ? round($cdw * 30, 2) : $cdw; ?> @lang('labels.currency')</span>
                                </li>
                                <?php } ?>

                                <?php if ($cdw_plus > 0){?>
                                <li>
                                    <h4>@lang("labels.cdw_plus")</h4>
                                    <span><?php echo $booking_info['is_delivery_mode'] == 4 ? round($cdw_plus * 30, 2) : $cdw_plus; ?> @lang('labels.currency')</span>
                                </li>
                                <?php } ?>

                                <?php if ($gps > 0){?>
                                <li>
                                    <h4>@lang("labels.gps")</h4>
                                    <span><?php echo $booking_info['is_delivery_mode'] == 4 ? round($gps * 30, 2) : $gps; ?> @lang('labels.currency')</span>
                                </li>
                                <?php } ?>

                                <?php if ($extra_driver > 0){?>
                                <li>
                                    <h4>@lang("labels.extra_driver")</h4>
                                    <span><?php echo $booking_info['is_delivery_mode'] == 4 ? round($extra_driver * 30, 2) : $extra_driver; ?> @lang('labels.currency')</span>
                                </li>
                                <?php } ?>

                                <?php if ($baby_seat > 0){?>
                                <li>
                                    <h4>@lang("labels.baby_protection")</h4>
                                    <span><?php echo $booking_info['is_delivery_mode'] == 4 ? round($baby_seat * 30, 2) : $baby_seat; ?> @lang('labels.currency')</span>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php } ?>

                        <?php if ($booking_info['is_delivery_mode'] != 4) { ?>
                        <div class="summary-col">
                            @if ($booking_info['is_delivery_mode'] == 2)
                                <h4><?php echo($lang == 'eng' ? 'Total per 1 Hour' : 'المجموع لكل ساعة'); ?></h4>
                            @else
                                <h4><?php echo($lang == 'eng' ? 'Total per 1 day' : 'المجموع اليومي'); ?></h4>
                            @endif
                            <span class="summ-price"
                                  id="total_per_1_day"><?php echo number_format((Session::get('rent_per_day') + $cdw + $cdw_plus + $gps + $extra_driver + $baby_seat), 2); ?> @lang('labels.currency')</span>
                        </div>
                        <?php } ?>

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
                        <div class="totalWdisValu peddLftSet">
                            <ul>
                                <li class="summary-col">
                                    <h4>@lang("labels.total_rent_for_capital") <?php echo($booking_info['is_delivery_mode'] == 2 ? $booking_info['hours_diff'] : $booking_info['days']); ?> @lang("labels.days")</h4>
                                    <span id="rent_m_days_span"><?php echo number_format(((Session::get('rent_per_day')) * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor), 2); ?> @lang("labels.currency")</span>
                                </li>

                                <?php
                                if ($parking_fee > 0)
                                { ?>
                                <li class="summary-col">
                                    <h4><?php echo($lang == 'eng' ? 'Parking Fee' : 'رسوم مواقف'); ?></h4>
                                    <label><?php echo $parking_fee; ?> @lang("labels.currency")</label>
                                </li>
                                <?php }
                                ?>

                                <?php
                                if ($tamm_charges_for_branch > 0)
                                { ?>
                                <li class="summary-col">
                                    <h4>@lang('labels.tamm_charges')</h4>
                                    <label><?php echo $tamm_charges_for_branch; ?> @lang("labels.currency")</label>
                                </li>
                                <?php }
                                ?>

                                <?php
                                if ($qitaf_amount > 0)
                                { ?>
                                <li class="summary-col">
                                    <h4>@lang('labels.qitaf_redeem_availed')</h4>
                                    <label><?php echo $qitaf_amount; ?> @lang("labels.currency")</label>
                                </li>
                                <?php }
                                ?>

                                <?php
                                if ($niqaty_amount > 0)
                                { ?>
                                <li class="summary-col">
                                    <h4>@lang('labels.niqaty_redeem_availed')</h4>
                                    <label><?php echo $niqaty_amount; ?> @lang("labels.currency")</label>
                                </li>
                                <?php }
                                ?>

                                <li class="containsRedeemDiscount summary-col" style="display: none;">
                                    <h4><?php echo($lang == 'eng' ? 'Discount availed on redeem' : 'سيتم تطبيق خصم نقاط الولاء'); ?></h4>
                                    <span id="discount_on_redeem">0</span>
                                </li>
                                <?php if (Session::get('coupon_applied') != true){
                                if (isset($promo_discount) && $pre_total_discount > 0){ ?>
                                <li class="discount_on_promo_auto summary-col">
                                    <h4>@lang("labels.discount_on_promo")</h4>
                                    <span class="discount"><?php echo number_format($pre_total_discount * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']), 2); ?>@lang("labels.currency")</span>
                                </li>
                                <?php } } ?>
                                <?php if (Session::get('coupon_applied') != true) {
                                    $styling = 'style="display: none;"';
                                } else {
                                    $styling = '';
                                } ?>
                                <li class="discount_on_promo_code summary-col" <?php echo $styling; ?> >
                                    <h4>@lang("labels.discount_on_promo_code") </h4>
                                    <span class="discount"><?php echo(Session::get('coupon_applied') == true ? number_format(Session::get('promo_discount_amount') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']), 2) : ''); ?> @lang("labels.currency")</span>
                                </li>
                                <li class="summary-col finalPrice">
                                    <h4><?php echo($lang == 'eng' ? 'Total Amount' : 'المبلغ الإجمالي'); ?></h4>
                                    <span id="total_amount"><?php echo number_format(((Session::get('rent_per_day') + $dropoff_charges - $pre_total_discount) * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $parking_fee + $tamm_charges_for_branch, 2); ?> @lang("labels.currency")</span>
                                </li>
                                <?php if ($vat_amount > 0){ ?>
                                <li class="summary-col">
                                    <h4>@lang("labels.vat_applicable") (<?php echo Session::get('vat_percentage'); ?>
                                        %)</h4>
                                    <span id="show_vat_applied"><?php echo number_format($vat_amount, 2); ?> @lang("labels.sar")</span>
                                </li>
                                <?php } ?>
                                <li class="summary-col finalPrice">
                                    <h4 class="<?php echo($lang == 'eng' ? '' : 'totalWithVat'); ?>">@lang("labels.you_pay_total")</h4>
                                    <span><?php echo number_format($payable_amount, 2); ?>@lang("labels.sar")</span>
                                </li>

                                <?php if (isset($promo_discount) && $post_total_discount > 0)
                                { ?>
                                <li class="summary-col finalPrice">
                                    <h4>@lang("labels.discount_on_promo")</h4>
                                    <span><?php echo number_format($post_total_discount, 2); ?>@lang("labels.sar")</span>
                                </li>
                                <?php } ?>

                            </ul>
                        </div>
                    </div>
                    <!--end order summary-->
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
                    <h4>
                        <?php echo($lang == 'eng' ? 'Payment Method' : 'الدفع'); ?>
                        <img src="<?php echo $base_url;?>/public/frontend/images/payment_method_icon_<?php echo $lang; ?>.png"
                             style="width: 212px;margin-left: 10px;">
                    </h4>
                    <div class="STS_express_checkout">
                        <?php if(isset($sts_error)){ ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $sts_error; ?>
                        </div>
                        <?php } ?>
                        <div class="creditCardForm">
                            <div class="payment add">
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
                                    <div class="main-row">
                                        <div class="form-group owner">
                                            <input type="text" placeholder="@lang('labels.cardholders_full_name')"
                                                   name="CardHolderName" class="form-control" id="owner">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" placeholder="@lang('labels.credit_card_number')"
                                                   name="CardNumber" class="form-control" id="cardNumber"
                                                   maxlength="16" onkeyup="check_if_mada_card($(this).val());">
                                        </div>
                                        <div class="form-group CVV">
                                            <input type="text" placeholder="@lang('labels.cvc_number')"
                                                   name="SecurityCode" class="form-control" id="cvv" maxlength="3">
                                        </div>
                                        <div class="form-group add" id="expiration-date">
                                            <div class="subcol">
                                                <select name="ExpiryDateMonth">
                                                    <option value=""><?php echo($lang == 'eng' ? 'Expiry Month' : 'شهر انتهاء الصلاحية');?></option>
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
                                                        <?php //echo $selectedMonth; ?> value="<?php echo $startMonth; ?>"><?php echo $startMonth; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="subcol">
                                                <select name="ExpiryDateYear">
                                                    <option value=""><?php echo($lang == 'eng' ? 'Expiry Year' : 'سنة انتهاء الصلاحية');?></option>
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
                                        <input name="SecureHash" type="hidden" value="<?php echo $secureHash ?>"/>
                                        <div class="form-group" id="pay-now">
                                            <input type="submit" class="btn yellowButton" id="confirm-purchase"
                                                   value="@lang('labels.pay_now') <?php echo number_format(Session::get('total_amount_for_transaction'), 2); ?> @lang('labels.currency_text')">
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
                    <h4>
                        <?php echo($lang == 'eng' ? 'Payment Method' : 'الدفع'); ?>
                        <img src="<?php echo $base_url;?>/public/frontend/images/payment_method_icon_<?php echo $lang; ?>.png"
                             style="width: 212px;margin-left: 10px;">
                    </h4>

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
    <script>
        $(document).ready(function () {
            $('#btn_orderSummary').click(function () {
                $('.orderSummary').slideToggle("slow");
            });
        });
    </script>
@endsection