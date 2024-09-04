@extends('frontend.layouts.template')
@section('content')
<link rel="stylesheet" href="<?php echo $base_url; ?>/public/plugins/watermark/watermarker.css?v=3">
<link rel="stylesheet" href="<?php echo $base_url; ?>/public/plugins/selfie/css/styles.css">
<section class="pricePageSec bookingSec humanLess">
    <div class="container">
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
                            <div class="bCenter unlock1">
                                <img src="<?php echo $car_image_path; ?>" alt="Car" width="274" height="132">
                            </div>
                            <div class="vehicleName unlockScreen">
                                <h4 id="carUpTitle">
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
                        <li class="active" id="get_car">
                            <div>
                                <span class="numb">01</span>
                                <span class="title">@lang('labels.get_vehicle')</span>
                                <i class="bullet"></i>
                            </div>
                        </li>
                        <li class="" id="select_car">
                            <div>
                                <span class="numb">02</span>
                                <span class="title">@lang('labels.select_car_plate')</span>
                                <i class="bullet"></i>
                            </div>
                        </li>
                        <li class="" id="inspection_car">
                            <div>
                                <span class="numb">03</span>
                                <span class="title">@lang('labels.do_inspection')</span>
                                <i class="bullet"></i>
                            </div>
                        </li>
                        <li class="" id="tam_issue">
                            <div>
                                <span class="numb">04</span>
                                <span class="title">@lang('labels.issue')</span>
                                <i class="bullet"></i>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="rightCol">

                <form class="getHumanLessCar" method="post" action="" onsubmit="return false;">
                    <input type="hidden" id="booking_id" name="booking_id" value="<?php echo $booking_id; ?>">
                    <input type="hidden" id="oasis_booking_id" name="oasis_booking_id" value="<?php echo $oasis_booking_id; ?>">
                    <input type="hidden" id="branchCode" name="branch" value="<?php echo $branch_code; ?>">
                    <input type="hidden" id="vehicleID" name="vehicle_id" value="">
                    <input type="hidden" id="carType" name="car_type" value="<?php echo $car_type; ?>">
                    <input type="hidden" id="carModel" name="car_model" value="<?php echo $car_model; ?>">
                    <input type="hidden" id="carPlate_no" name="car_plate" value="">
                    <input type="hidden" id="lastFuelTank" name="lastFuelTank" value="">
                    <input type="hidden" id="lastKm" name="lastKm" value="">
                    <input type="hidden" id="mobileNo" name="mobileNo" value="<?php echo $mobile_no; ?>">
                    <input type="hidden" id="correlation_id" name="correlation_id" value="">
                    <input type="hidden" id="contractNo" name="contractNo" value="">
                    <input type="hidden" id="changedPrice" name="changedPrice" value="0">
                    <input type="hidden" id="changedCdw" name="changedCdw" value="0.00">

                    <div class="wizard-1">
                        <div class="locateCar">
                            <h3>@lang('labels.locate_your_car')</h3>
                            <p>@lang('labels.follow_map_to_reach')</p>
                            <div class="car-parking">
                                <?php if($parking_area_image != ''){ ?>
                                    <img class="parking-img" src="<?php echo $base_url . '/public/uploads/'.$parking_area_image;?>">
                                <?php }else{ ?>
                                    <img class="parking-img" src="<?php echo $base_url . '/public/frontend/images/img-car.jpg';?>">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="btn-box">
                            <a class="backBtn redishButtonRound" href="<?php echo $lang_base_url; ?>/booking-detail/<?php echo custom::encode_with_jwt($booking_id); ?>">
                                <?php echo($lang == 'eng' ? 'Back' : 'إلغاء'); ?>
                            </a>
                            <a class="nextBtn redishButtonRound show_wiz2" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Next' : 'التالي'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="wizard-2" style="display: none;">
                        <div class="selectCar">
                            <div class="qr-holder">
                                <h3>@lang('labels.select_car_plate')</h3>
                                <div class="qr-code barCode">
                                    <img class="qrCode-img" src="<?php echo $base_url . '/public/frontend/images/img-qrcode.jpg';?>">
                                </div>
                            </div>
                            <div class="car-plates">
                                <div class="carModel">
                                    <h3 id="carUp"><?php echo ($lang == 'eng' ? $booking_detail->car_type_eng_title : $booking_detail->car_type_arb_title) . ' ' . ($lang == 'eng' ? $booking_detail->car_model_eng_title : $booking_detail->car_model_arb_title) . ' ' . $booking_detail->year; ?></h3>
                                    <a class="chng_car show_wizard_2_1" href="javascript:void(0);" >@lang('labels.change_car')</a>
                                </div>
                                <ul class="plate-list">
                                    <li>
                                        <input id="plate1" class="carPlate" type="radio" name="plate_no" value="1230">
                                        <label for="plate1" class="plateLable">
                                            <span class="car-color" style="background-color: #ffffff">White</span>
                                            <h3>CE-123</h3>
                                            <span class="dummy-checkbox"></span>
                                        </label>
                                    </li>
                                    <li>
                                        <input id="plate2" class="carPlate" type="radio" name="plate_no" value="1231">
                                        <label for="plate2" class="plateLable">
                                            <span class="car-color" style="background-color: #000000">Black</span>
                                            <h3>CE-123</h3>
                                            <span class="dummy-checkbox"></span>
                                        </label>
                                    </li>
                                    <li>
                                        <input id="plate3" class="carPlate" type="radio" name="plate_no" value="1232">
                                        <label for="plate3" class="plateLable">
                                            <span class="car-color" style="background-color: #afafaf">Gray</span>
                                            <h3>CE-123</h3>
                                            <span class="dummy-checkbox"></span>
                                        </label>
                                    </li>
                                    <li>
                                        <input id="plate4" class="carPlate" type="radio" name="plate_no" value="1233">
                                        <label for="plate4" class="plateLable">
                                            <span class="car-color" style="background-color: #ffeb00">Yellow</span>
                                            <h3>CE-123</h3>
                                            <span class="dummy-checkbox"></span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="btn-box">
                            <a class="backBtn redishButtonRound show_wiz1" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Back' : 'إلغاء'); ?>
                            </a>
                            <a class="nextBtn redishButtonRound show_wiz3" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Next' : 'التالي'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="wizard_2_1" style="display: none;">
                        <div class="change-car">
                            <a href="javascript:void(0);" class="back2Wiz2 back_to_wiz2">
                                @lang('labels.go_back')
                            </a>
                            <h3>@lang('labels.choose_another_car')</h3>
                            <!--<select name="select_car" class="filter-car" title="Select Car">
                                <option value="">Car 1</option>
                                <option value="">Car 2</option>
                                <option value="">Car 3</option>
                            </select>-->
                            <div class="car-list">
                                <div class="upgradedCars"></div>
                            </div>
                        </div>
                    </div>

                    <div class="wizard-3" style="display: none;">
                        <div class="car-inspect">
                            <h3>@lang('labels.car_inspection')</h3>
                            <p>@lang('labels.click_figure_to_upload')</p>

                            <div class="car-pictures">
                                <a class="" href="#showCarInspection" data-bs-toggle="modal" id="openCarInspection"></a>
                                <img id="getCarInsp" class="carInpec-img" src="<?php echo $base_url . '/public/frontend/images/img-carInspec.png';?>">
                            </div>
                            <div class="range-km">
                                <i class="km-reading"></i>
                                <span class="km-value" id="km-value">20,453 KM</span>
                            </div>
                            <div class="range-fuel">
                                <i class="fuel-meter"></i>
                                <div class="fuel-bar fuelHtml"></div>
                            </div>
                        </div>
                        <div class="btn-box">
                            <a class="backBtn redishButtonRound show_wiz2" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Back' : 'إلغاء'); ?>
                            </a>
                            <a class="nextBtn redishButtonRound show_wiz4" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Next' : 'التالي'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="wizard-4" style="display: none;">
                        <div class="tam-issue">
                            <h3>@lang('labels.issue_tam')</h3>
                            <span class="error-tam"></span>
                            <div class="tam-info" id="nationalID">
                                <label>@lang('labels.enter_your_id_version')</label>
                                <input type="text" name="id_no" id="id_no" value="" placeholder="@lang('labels.enter_id_version')">
                            </div>
                        </div>
                        <div class="btn-box">
                            <a class="backBtn redishButtonRound back_to_insp" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Back' : 'إلغاء'); ?>
                            </a>
                            <a class="nextBtn redishButtonRound btnTammAuth" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Next' : 'التالي'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="wizard-otp" style="display: none">
                        <div class="tam-issue">
                            <h3>@lang('labels.issue_tam')</h3>
                            <span class="error-tam"></span>
                            <div class="issueTamm-otp" id="tammOTP">
                                <label>@lang('labels.tamm_otp')</label>
                                <input type="text" name="tamm_otp" id="tamm_otp" value="" placeholder="@lang('labels.enter_otp')">
                                <a href="javascript:void(0);" class="show_wiz4" id="resendTammOtp">@lang('labels.resend_code')</a>
                            </div>
                        </div>
                        <div class="btn-box">
                            <a class="backBtn redishButtonRound back_to_insp" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Back' : 'إلغاء'); ?>
                            </a>
                            <a class="nextBtn redishButtonRound btnTammAuth" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Next' : 'التالي'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="wizard-id-version" style="display: none">
                        <div class="tam-issue">
                            <h3>@lang('labels.issue_tam')</h3>
                            <span class="error-tam"></span>
                            <div class="issueTamm-otp" id="tammOTP">
                                <label>@lang('labels.Please_enter_id_version')</label>
                                <input type="text" name="id_version" id="id_version" value="" placeholder="@lang('labels.enterIDVersion')">
                            </div>
                        </div>
                        <div class="btn-box">
                            <a class="backBtn redishButtonRound back_to_insp" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Back' : 'إلغاء'); ?>
                            </a>
                            <a class="nextBtn redishButtonRound btnTammAuth" href="javascript:void(0);">
                                <?php echo($lang == 'eng' ? 'Next' : 'التالي'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="wizard-unlock" style="display: none;">
                        <div class="unlockCar">
                            <h3>@lang('labels.unlock_car')</h3>
                            <p>@lang('labels.contract_opened')</p>
                            <p>@lang('labels.please_unlock_car')</p>

                            <div class="unlock2">
                                <img src="<?php echo $car_image_path; ?>" alt="Car" width="274" height="132">
                            </div>
                        </div>
                        <div class="btn-box">
                            <a class="nextBtn redishButtonRound btnUnlockCar" href="javascript:void(0);">
                                @lang('labels.btn_unlock_car')
                            </a>
                        </div>
                    </div>

                    <div class="wizard-done" style="display: none;">
                        <div class="yourDone">
                            <h3>@lang('labels.you_are_done')</h3>
                            <p>@lang('labels.enjoy_the_ride')</p>
                            <div class="unlock2 ride">
                                <img src="<?php echo $car_image_path; ?>" alt="Car" width="274" height="132">
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="modal-mobile modal fade" id="qrScanner" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-heading">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="">@lang('labels.select_car_plate')</h4>
                </div>
                <div class="modal-body text-center">
                    <canvas id="canvas_qr" hidden></canvas>
                    <div id="output" hidden>
                        <div id="outputMessage">@lang('labels.qr_not_detected')</div>
                        <div hidden><b>@lang('labels.car_plate_no'):</b> <span id="outputData"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var watermark_route = "{{$lang_base_url . '/watermark'}}";
        var clear_inspection_route = "{{$lang_base_url . '/clear-inspection'}}";
        var inspection_id = "{{$inspection_id}}";
        var clear_confirmation_title_language = "@lang('labels.are_you_sure_to_clear_all')";
        var ok_button_language = "@lang('labels.ok')";
        var cancel_button_language = "@lang('labels.cancel')";
        var inspection_mode = 'pickup';

        //for jsQR scanner
        var qr_error_message = "@lang('labels.qr_error_message')";
        var plate_no = '';
        var booking_id = '<?php echo $booking_id; ?>';
        var video = document.createElement("video");
        var canvasElement = document.getElementById("canvas_qr");
        var canvas = canvasElement.getContext("2d");
        var outputContainer = document.getElementById("output");
        var outputMessage = document.getElementById("outputMessage");
        var outputData = document.getElementById("outputData");
    </script>
    <script src="<?php echo $base_url; ?>/public/plugins/jsQR/docs/jsQR.js?v=8"></script>
    <script src="<?php echo $base_url; ?>/public/plugins/watermark/<?php echo (custom::is_mobile() ? 'watermark-mobile.js' : 'watermarker.js'); ?>"></script>
    <script src="<?php echo $base_url; ?>/public/plugins/selfie/js/script.js?v=9"></script>
    <script src="<?php echo $base_url; ?>/public/plugins/inspection/inspection.js?v=23"></script>
    <?php if(custom::is_mobile()){ ?>
    <script src="<?php echo $base_url; ?>/public/plugins/inspection/jquery.ui.touch-punch.min.js"></script>
    <?php } ?>
</section>
@endsection