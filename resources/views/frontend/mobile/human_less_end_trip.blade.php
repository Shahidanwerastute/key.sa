@extends('frontend.layouts.template')
@section('content')
<link rel="stylesheet" href="<?php echo $base_url; ?>/public/plugins/watermark/watermarker.css?v=3">
<link rel="stylesheet" href="<?php echo $base_url; ?>/public/plugins/selfie/css/styles.css">
<section class="pricePageSec bookingSec humanLess">
    <div class="container-md">
        <div class="pricePgWrapper ">
            <div class="leftCol">
                <div class="reservation-message">
                    <div class="heading-bar">
                        <a href="<?php echo $lang_base_url; ?>/my-bookings" class="back2Page">
                            @lang('labels.go_back')
                        </a>
                        <h3 class="detailHeading"><?php echo ($lang=='eng'?'Booking Details':'تفاصيل الحجز');?></h3>
                    </div>
                </div>
                <div class="booking-details">
                    <div class="detail-holder">
                        <?php
                        if ($booking_detail->car_image != '') {
                        $car_image_path = $base_url . '/public/uploads/' . $booking_detail->car_image;
                        } else {
                        $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                        }
                        $from_city = custom::getFromToCityName($booking_detail->from_city_id,$lang);
                        $to_city = custom::getFromToCityName($booking_detail->to_city_id,$lang);
                        ?>
                        <div class="col imgBox">
                            <div class="bCenter  unlock1">
                                <img src="<?php echo $car_image_path; ?>" alt="Car" width="274" height="132">
                            </div>
                            <div class="vehicleName unlockScreen">
                                <h4>
                                    <?php echo ($lang == 'eng' ? $booking_detail->car_type_eng_title : $booking_detail->car_type_arb_title) . ' ' . ($lang == 'eng' ? $booking_detail->car_model_eng_title : $booking_detail->car_model_arb_title) . ' ' . $booking_detail->year; ?>
                                </h4>
                            </div>
                        </div>
                        <div class="basicDetails">
                            <div class="col twoBig ">
                                <div class="bCenter">
                                    <label>@lang('labels.pick_up')</label>
                                    <ul>
                                        <li>
                                            <?php echo date('d M Y', strtotime($booking_detail->from_date)).', '.date('H:i A', strtotime($booking_detail->from_date)); ?>
                                        </li>
                                        <?php if ($booking_detail->is_delivery_mode == 'yes'){ ?>
                                        <li title="<?php echo custom::getCleanLocationName($booking_detail->pickup_delivery_lat_long, 'short')." (".$booking_detail->pickup_delivery_lat_long.")"; ?>">
                                            <?php echo custom::getCleanLocationName($booking_detail->pickup_delivery_lat_long, 'short')."(".$booking_detail->pickup_delivery_lat_long.")"; ?>
                                        </li>
                                        <?php }else{ ?>
                                        <li title="<?php echo($lang == 'eng' ? $booking_detail->branch_from_eng_title : $booking_detail->branch_from_arb_title); ?>">
                                            <?php echo($lang == 'eng' ? $booking_detail->branch_from_eng_title : $booking_detail->branch_from_arb_title); ?>
                                        </li>
                                        <?php } ?>
                                        <li>
                                            <?php echo $from_city; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col twoBig ">
                                <div class="bCenter">
                                    <label>@lang('labels.drop_off')</label>
                                    <ul>
                                        <li>
                                            <?php echo date('d M Y', strtotime($booking_detail->to_date)).', '.date('H:i A', strtotime($booking_detail->to_date)); ?>
                                        </li>
                                        <?php if ($booking_detail->is_delivery_mode == 'yes')
                                        { ?>
                                        <li title="<?php echo custom::getCleanLocationName($booking_detail->dropoff_delivery_lat_long, 'short')." (".$booking_detail->dropoff_delivery_lat_long.")"; ?>">
                                            <?php echo custom::getCleanLocationName($booking_detail->dropoff_delivery_lat_long, 'short')."(".$booking_detail->dropoff_delivery_lat_long.")"; ?>
                                        </li>
                                        <?php }else{ ?>
                                        <li title="<?php echo($lang == 'eng' ? $booking_detail->branch_to_eng_title : $booking_detail->branch_to_arb_title); ?>">
                                            <?php echo($lang == 'eng' ? $booking_detail->branch_to_eng_title : $booking_detail->branch_to_arb_title); ?>
                                        </li>
                                        <?php } ?>
                                        <li>
                                            <?php echo $to_city; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="getCarSteps">
                    <ul>
                        <li class="active" id="endTrip_inspection">
                            <div>
                                <span class="numb">01</span>
                                <span class="title">@lang('labels.do_inspection')</span>
                                <i class="bullet"></i>
                            </div>
                        </li>
                        <li class="" id="endTrip_payment">
                            <div>
                                <span class="numb">02</span>
                                <span class="title">@lang('labels.payment')</span>
                                <i class="bullet"></i>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="rightCol">

                <form class="endTripHumanLess" method="post" action="" onsubmit="return false;">
                    <input type="hidden" id="booking_id" name="booking_id" value="<?php echo $booking_id; ?>">
                    <input type="hidden" id="oasis_contract_no" name="oasis_contract_no" value="<?php echo $oasis_contract_no; ?>">
                    <input type="hidden" id="contractBalance" name="contractBalance" value="">
                    <input type="hidden" id="closing_branch" name="closing_branch" value="<?php echo $closing_branch; ?>">
                    <input type="hidden" id="kmIn" name="kmIn" value="<?php echo $km; ?>">
                    <input type="hidden" id="actualkm" name="actualkm" value="<?php echo $km; ?>">
                    <input type="hidden" id="fuelTankIn" name="fuelTankIn" value="<?php echo $fuel; ?>">
                    <input type="hidden" id="plate_no" name="plate_no" value="<?php echo $plate_no; ?>">
                    <input type="hidden" id="tammStatus" name="tammStatus" value="0">
                    <input type="hidden" id="closeType" name="closeType" value="<?php echo (isset($close_type)?$close_type:'H');?>">
                    <input type="hidden" id="transMethod" name="transMethod" value="<?php echo (isset($transMethod)?$transMethod:'');?>">
                    <input type="hidden" id="transReference" name="transReference" value="<?php echo (isset($transReference)?$transReference:'');?>">
                    <input type="hidden" id="accountCardNo" name="accountCardNo" value="<?php echo (isset($accountCardNo)?$accountCardNo:'');?>">
                </form>
                    <div class="et-wizard-1">
                        <div class="car-inspect">
                            <h3>@lang('labels.car_inspection')</h3>
                            <p>@lang('labels.click_figure_to_upload')</p>

                            <div class="car-pictures">
                                <a class="" href="#showCarInspection" data-bs-toggle="modal" id="openCarInspection"></a>
                                <img id="endTripInsp" class="carInpec-img" src="<?php echo $base_url . '/public/frontend/images/img-carInspec.png';?>">
                            </div>
                            <div class="range-km">
                                <i class="km-reading"></i>
                                <div class="numbers-row">
                                    <span class="km-value" id="km-value"><?php echo $km; ?></span>
                                    <input type="text" class="valid-km" data-pickupValue="<?php echo $km; ?>" name="kmValue" id="kmValue" value="<?php echo $km; ?>">
                                </div>
                            </div>
                            <div class="range-fuel">
                                <i class="fuel-meter"></i>
                                <div class="fuelRange" data-fuel="<?php echo $fuel>0?$fuel:1; ?>">
                                    <div id="fuel_tank"></div>
                                    <ul class="range-labels">
                                        <li>0</li>
                                        <li>1</li>
                                        <li>2</li>
                                        <li>3</li>
                                        <li>4</li>
                                        <li>5</li>
                                        <li>6</li>
                                        <li>7</li>
                                        <li>8</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="btn-box">
                            <a class="backBtn redishButtonRound" href="<?php echo $lang_base_url; ?>/booking-detail/<?php echo custom::encode_with_jwt($booking_id); ?>">
                                <?php echo($lang == 'eng' ? 'Back' : 'إلغاء'); ?>
                            </a>
                            <a class="nextBtn redishButtonRound show_et_wiz2" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Next' : 'التالي'); ?>
                            </a>
                        </div>
                    </div>
                    <!--STS payment response-->
                    <?php
                        if(isset($sts_error) && $sts_error == 'success'){
                            $on_sts_success = 'display:none;';
                        }else{
                            $on_sts_success = '';
                        }
                        $apiSettings = custom::api_settings();
                        $securityToken = $apiSettings->sts_secret_key_web;
                        $merchantID = $apiSettings->sts_merchant_id_web;
                        $sts_payment_link = $apiSettings->sts_payment_link;

                        if(isset($Response_TransactionID)){
                            $transaction_id = $Response_TransactionID;
                            $resp_amount = $amount;
                            $get_ammount = number_format($resp_amount, 2, '.', '');
                        }else{
                            $transaction_id = 'HLKEY'.str_pad($booking_id, 15, '0', STR_PAD_LEFT);
                            //amount should be from booking details
                            $resp_amount = '14676';
                            $get_ammount = number_format($resp_amount, 2, '.', '');
                        }

                        $PaymentParams = [];
                        $paymentParameters = [];
                        $paymentParameters["TransactionID"] = $transaction_id;
                        $paymentParameters["MerchantID"] = $merchantID;
                        $paymentParameters["Amount"] = $get_ammount;
                        $paymentParameters["CurrencyISOCode"] = "682";
                        $paymentParameters["MessageID"] = "1";
                        $paymentParameters["Quantity"] = "1";
                        $paymentParameters["Channel"] = "0";
                        $paymentParameters["ItemID"] = "130";
                        $paymentParameters["PaymentMethod"] = "1";
                        $paymentParameters["PaymentDescription"] = "Payment";
                        $paymentParameters["Language"] = $lang == "eng"?"En":"Ar";
                        $paymentParameters["ThemeID"] = "1000000001";

                        if($lang == 'arb'){
                            $paymentParameters["ResponseBackURL"] = $base_url."/end-trip/".base64_encode($booking_id);
                        } else {
                            $paymentParameters["ResponseBackURL"] = $base_url."/en/end-trip/".base64_encode($booking_id);
                        }

                        $paymentParameters["Version"] = "2.0";
                        $paymentParameters["RedirectURL"] = $sts_payment_link;

                        $PaymentParams = $paymentParameters;
                        $PaymentParams["SecureHash"] = custom::generate_token(array(
                            'Amount' => $paymentParameters["Amount"]*100,
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

                        $redirectURL = (String) $PaymentParams["RedirectURL"];
                        $amount = (String) $PaymentParams["Amount"];
                        $currencyCode = (String) $PaymentParams["CurrencyISOCode"];
                        $transactionID = (String) $PaymentParams["TransactionID"];
                        $merchantID = (String) $PaymentParams["MerchantID"];
                        $language = (String) $PaymentParams["Language"];
                        $messageID = (String) $PaymentParams["MessageID"];
                        $secureHash = (String) $PaymentParams["SecureHash"];
                        $themeID = (String) $PaymentParams["ThemeID"];
                        $ItemID = (String) $PaymentParams["ItemID"];
                        $PaymentDescription = (String) $PaymentParams["PaymentDescription"];
                        $responseBackURL = (String) $PaymentParams["ResponseBackURL"];
                        $channel = (String) $PaymentParams["Channel"];
                        $quantity = (String) $PaymentParams["Quantity"];
                        $version = (String) $PaymentParams["Version"];
                        $paymentMethod = (String) $PaymentParams["PaymentMethod"];
                    ?>
                    <?php if(isset($sts_error) && $sts_error != 'success'){ ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $sts_error; ?>
                    </div>
                    <?php } ?>
                    <div class="et-wizard-2" style="display: none;">
                        <div class="selectCar" id="stsForm">
                            <div class="qr-holder">
                                <h3>@lang('labels.pay_extra')</h3>
                                <p class="extras-desc">@lang('labels.closing_contract_balance') <?php echo $get_ammount; ?> @lang("labels.sar_hl")</p>
                            </div>
                            <div class="pay-extras">
                                <div class="paymentOption objects">
                                    <ul>
                                        <li>
                                            <input id="CreditCard" name="payment_method" value="cc" class="showHideOlpIdField" type="radio" checked="" autocomplete="off">
                                            <label for="CreditCard">
                                                <div class="imgBox">
                                                    <img src="<?php echo $base_url; ?>/public/frontend/images/ico-master.png" alt="Card" width="35" height="26">
                                                </div>
                                            </label>
                                        </li>
                                        <li style="">
                                            <input id="CreditCardMada" name="payment_method" value="cc" class="showHideOlpIdField" type="radio" autocomplete="off">
                                            <label for="CreditCardMada" style="display: block !important;">
                                                <div class="imgBox">
                                                    <img src="<?php echo $base_url; ?>/public/frontend/images/ico-visa.png" alt="Card" width="35" height="26">
                                                </div>
                                            </label>
                                        </li>
                                        <li>
                                            <input id="cash" name="payment_method" value="cc" class="showHideOlpIdField" type="radio" autocomplete="off">
                                            <label for="cash">
                                                <div class="imgBox">
                                                    <img src="<?php echo $base_url; ?>/public/frontend/images/ico-mada.png" alt="Mada" width="39" height="26">
                                                </div>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="STS_express_checkout">
                                    <div class="creditCardForm">
                                        <div class="payment add">
                                            <form action="<?php echo $redirectURL?>" class="<?php echo $transaction_id; ?>" name="redirectForm" id="stsPayOne" method="post">
                                                <input name="MerchantID" type="hidden" value="<?php echo $merchantID; ?>"/>
                                                <input name="Amount" type="hidden" value="<?php echo $amount*100; ?>"/>
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
                                                <input name="PaymentDescription" type="hidden" value="<?php echo $PaymentDescription?>"/>
                                                <div class="main-row">
                                                    <div class="form-group owner">
                                                        <input type="text" placeholder="@lang('labels.cardholders_full_name')" name="CardHolderName" class="form-control" id="owner">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" placeholder="@lang('labels.credit_card_number')" name="CardNumber" class="form-control" id="cardNumber" maxlength="16">
                                                    </div>
                                                    <div class="form-group CVV">
                                                        <input type="text" placeholder="@lang('labels.cvc_number')" name="SecurityCode" class="form-control" id="cvv" maxlength="3">
                                                    </div>
                                                    <div class="form-group add" id="expiration-date">
                                                        <div class="subcol">
                                                            <select name="ExpiryDateMonth">
                                                                <option value=""><?php echo ($lang == 'eng'?'Expiry Month':'شهر انتهاء الصلاحية');?></option>
                                                                <?php
                                                                $startMonth = '1';
                                                                $endMonth = '12';
                                                                $currentMonth = date('m');
                                                                for($startMonth;$startMonth<=$endMonth;$startMonth++){
                                                                $startMonth = $startMonth < 10?'0'.$startMonth:$startMonth;
                                                                $monthName = custom::month_english($startMonth);
                                                                if($lang == 'arb'){
                                                                    $monthName = custom::month_arabic($monthName);
                                                                }
                                                                $selectedMonth = $startMonth == $currentMonth?'selected':'';
                                                                ?>
                                                                <option <?php //echo $selectedMonth; ?> value="<?php echo $startMonth; ?>"><?php echo $startMonth; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="subcol">
                                                            <select name="ExpiryDateYear">
                                                                <option value=""><?php echo ($lang == 'eng'?'Expiry Year':'سنة انتهاء الصلاحية');?></option>
                                                                <?php
                                                                $startYear = date('Y');
                                                                $endYear = $startYear+10;
                                                                for($startYear;$startYear<=$endYear;$startYear++){
                                                                ?>
                                                                <option value="<?php echo substr($startYear, -2); ?>"><?php echo $startYear; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <input name="SecureHash" type="hidden" value="<?php echo $secureHash ?>"/>
                                                    <input type="submit" value="@lang("labels.pay_now") <?php echo $get_ammount; ?> @lang("labels.sar_hl")" class="edBtn redishButtonRound" id="submitPayment"/>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-box">
                            <a class="backBtn redishButtonRound show_et_wiz1" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Back' : 'إلغاء'); ?>
                            </a>
                            <?php
                            $site_settings = custom::site_settings();
                            if($site_settings->hl_skip_payment == 'on'){ ?>
                                <a class="nextBtn redishButtonRound " data-bs-target="<?php echo isset($sts_error) && $sts_error != 'success'?'#showPayExtras':'' ?>" data-bs-toggle="<?php echo isset($sts_error) && $sts_error != 'success'?'modal':'' ?>" href="javascript:void(0);" id="skip_or_next">
                                    @lang('labels.close_without_pay')
                                </a>
                            <?php }else{ ?>
                                <a class="nextBtn redishButtonRound " href="javascript:void(0);" id="forceToPay">
                                    <?php echo($lang == 'eng' ? 'Next' : 'التالي'); ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="et-wizard-3" style="display: none">
                        <div class="car-inspect">
                            <h3>@lang('labels.close_windows')</h3>
                            <div class="car-options">
                                <ul>
                                    <li>
                                        <input type="checkbox" id="drop_keys" name="drop_keys" value="">
                                        <label for="drop_keys">
                                            <img class="carInpec-img" src="<?php echo $base_url . '/public/frontend/images/ico-carKey.png';?>">
                                            @lang('labels.drop_keys')
                                        </label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="car_windows" name="car_windows" value="">
                                        <label for="car_windows">
                                            <img class="carInpec-img" src="<?php echo $base_url . '/public/frontend/images/ico-carWindow.png';?>">
                                            @lang('labels.close_car_windows')
                                        </label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="close_doors" name="close_doors" value="">
                                        <label for="close_doors">
                                            <img class="carInpec-img" src="<?php echo $base_url . '/public/frontend/images/ico-carDoor.png';?>">
                                            @lang('labels.close_doors')
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="btn-box">
                            <a class="backBtn redishButtonRound show_et_wiz2" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Back' : 'إلغاء'); ?>
                            </a>
                            <a class="nextBtn redishButtonRound show_et_wiz4" href="javascript:void(0);">
                                @lang('labels.btn_close_contract')
                            </a>
                        </div>
                    </div>

                    <div class="et-wizard-4" style="display: none">
                        <div class="closeContract">
                            <h3>@lang('labels.close_contract')</h3>
                            <p>@lang('labels.we_hope_you_enjoy_ride')</p>

                            <div class="closeContractImg">
                                <img src="<?php echo $car_image_path; ?>" alt="Car" width="274" height="132">
                            </div>
                        </div>
                    </div>

                    <div class="et-wizard-tamm" style="display: none">
                        <div class="closeContract">
                            <h3>@lang('labels.close_contract')</h3>
                            <div class="alert-icon"></div>
                            <p style="color:red;">@lang('labels.tamm_cancel_error')</p>
                            <div class="closeContractImg">
                                <img src="<?php echo $car_image_path; ?>" alt="Car" width="274" height="132">
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <script>
        var end_trip_inspection_route = "{{$lang_base_url . '/watermark_endTrip'}}";
        var clear_inspection_route = "{{$lang_base_url . '/clear-inspection'}}";
        var inspection_id = "{{$inspection_id}}";
        var clear_confirmation_title_language = "@lang('labels.are_you_sure_to_clear_all')";
        var ok_button_language = "@lang('labels.ok')";
        var cancel_button_language = "@lang('labels.cancel')";
        var inspection_mode = 'dropOff';
        <?php
        if(isset($sts_error) && $sts_error == 'success'){ ?>
            $('#endTrip_inspection').addClass('visited');
            $('#endTrip_inspection').removeClass('active');
            $('#endTrip_payment').addClass('active');
            $('.et-wizard-1').hide();
            $('.et-wizard-2').hide();
            $('.et-wizard-3').show();
            $('.et-wizard-4').hide();
        <?php }else if(isset($sts_error) && $sts_error != 'success'){ ?>
            $('#endTrip_inspection').addClass('visited');
            $('#endTrip_inspection').removeClass('active');
            $('#endTrip_payment').addClass('active');
            $('.et-wizard-1').hide();
            $('.et-wizard-2').show();
        <?php } ?>
    </script>
    <script src="<?php echo $base_url; ?>/public/plugins/watermark/<?php echo (custom::is_mobile() ? 'watermark-mobile.js' : 'watermarker.js'); ?>"></script>
    <script src="<?php echo $base_url; ?>/public/plugins/selfie/js/script.js"></script>
    <script src="<?php echo $base_url; ?>/public/plugins/inspection/inspection.js?v=10"></script>
    <?php if(custom::is_mobile()){ ?>
    <script src="<?php echo $base_url; ?>/public/plugins/inspection/jquery.ui.touch-punch.min.js"></script>
    <?php } ?>
</section>
@endsection