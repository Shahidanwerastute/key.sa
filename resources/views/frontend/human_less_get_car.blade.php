@extends('frontend.layouts.template')
@section('content')
    <?php $vat_to_add = 0; ?>
    <section class="searchNbookSec">
        <div class="container-md">
            <?php //echo custom::deliveryPickupTabsArea($lang); ?>
            <div class="bookingStepsLink getCarSteps">
                <ul>
                    <li class="active" id="get_car">
                        <div>
                            <span class="numb">01</span> @lang('labels.get_vehicle')
                        </div>
                    </li>
                    <li class="" id="select_car">
                        <div>
                            <span class="numb">02</span> @lang('labels.select')
                        </div>
                    </li>
                    <li class="" id="inspection_car">
                        <div>
                            <span class="numb">03</span> @lang('labels.do')
                        </div>
                    </li>
                    <li class="" id="tam_issue">
                        <div>
                            <span class="numb">04</span> @lang('labels.issue')
                        </div>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="pricePageSec humanLessPage">
        <div class="container-md">
            <div class="pricePgWrapper ">
                <div class="leftCol">
                    <div class="imgBox peddLftSet">
                        <?php
                        if ($booking_detail->car_image != '') {
                            $car_image_path = $base_url . '/public/uploads/' . $booking_detail->car_image;
                        } else {
                            $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                        }

                        $from_city = custom::getFromToCityName($booking_detail->from_city_id,$lang);
                        $to_city = custom::getFromToCityName($booking_detail->to_city_id,$lang);
                        ?>
                        <img src="<?php echo $car_image_path; ?>" alt="Car" height="132" width="274"/>
                    </div>
                    <div class="carSumery peddLftSet">
                        <div class="bookName">
                            <h2>
                                <?php echo($lang == 'eng' ? $booking_detail->car_type_eng_title : $booking_detail->car_type_arb_title); ?> <?php echo ($lang == 'eng' ? $booking_detail->car_model_eng_title : $booking_detail->car_model_arb_title) . ' ' . $booking_detail->year; ?>
                            </h2>
                        </div>
                        <h3><?php echo($lang == 'eng' ? $booking_detail->car_category_eng_title : $booking_detail->car_category_arb_title); ?></h3>
                    </div>
                    <div class="basicDetails">
                        <div class="col twoBig peddLftSet">
                            <label>@lang('labels.pick_up')</label>
                            <ul>
                                <li title="JEDDAH AIRPORT, NORTH TERMINAL">
                                    <img class="abImg"
                                         src="<?php echo $base_url; ?>/public/frontend/images/location.png" alt=""
                                         width="13" height="18">
                                    <?php
                                    echo ($lang == 'eng' ? $booking_detail->branch_from_eng_title : $booking_detail->branch_from_arb_title) . ", " . $from_city;
                                    ?>
                                </li>
                                <li><img class="abImg"
                                         src="<?php echo $base_url; ?>/public/frontend/images/calendar.png" alt=""
                                         width="16"
                                         height="18"> <?php echo date('d / m / Y', strtotime($booking_detail->from_date)); ?>
                                </li>
                                <li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/clock.png"
                                         alt="" width="18" height="18"> <?php echo date('H:i A', strtotime($booking_detail->from_date)); ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col twoBig peddLftSet">
                            <label>@lang('labels.drop_off')</label>
                            <ul>
                                <li title="JEDDAH AIRPORT, NORTH TERMINAL">
                                    <img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/location.png" alt="" width="13" height="18">
                                    <?php
                                    echo ($lang == 'eng' ? $booking_detail->branch_to_eng_title : $booking_detail->branch_to_arb_title) . ", " . $to_city;
                                    ?>
                                </li>
                                <li>
                                    <img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/calendar.png" alt="" width="16" height="18">
                                    <?php echo date('d / m / Y', strtotime($booking_detail->to_date)); ?>
                                </li>
                                <li>
                                    <img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/clock.png" alt="" width="18" height="18"> <?php echo date('H:i A', strtotime($booking_detail->to_date)); ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col bookFeature peddLftSet <?php echo($booking_detail->min_age > 0 ? 'contains-min-age' : ''); ?>">
                            <label>@lang('labels.features')</label>
                            <ul>
                                <li>
                                    <div class="spIconF person"></div>
                                    <p><?php echo $booking_detail->no_of_passengers; ?></p></li>
                                <li>
                                    <div class="spIconF transmition"></div>
                                    <p><?php echo($booking_detail->transmission == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي')); ?></p>
                                <li>
                                    <div class="spIconF door"></div>
                                    <p>
                                    <p><?php echo $booking_detail->no_of_doors; ?></p></li>
                                <li>
                                    <div class="spIconF bag"></div>
                                    <p><?php echo $booking_detail->no_of_bags; ?></p></li>
                                <?php if ($booking_detail->min_age > 0)
                                { ?>
                                <li>
                                    <div class="spIconF minAge"></div>
                                    <p><?php echo $booking_detail->min_age; ?></p>
                                </li>
                                <?php } ?>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col twoBig peddLftSet">
                            <label>@lang('labels.rental_period')</label>
                            <ul>
                                <li><?php echo $booking_detail->no_of_days; ?> @lang('labels.days')</li>
                            </ul>
                        </div>
                        <div class="col twoBig peddLftSet">
                            <label><?php echo($lang == 'eng' ? 'TOTAL PAID' : 'مجموع المدفوع'); ?></label>
                            <ul>
                                <li>
                                    <h4><?php echo $booking_detail->total_sum; ?> @lang('labels.currency')</h4>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="rightCol">
                    <h1>
                        <?php echo ($lang=='eng'?'Booking Details':'تفاصيل الحجز');?>
                        <a class="myBookings redishButton" href="<?php echo $lang_base_url; ?>/booking-detail/<?php echo custom::encode_with_jwt($booking_id); ?>" >@lang('labels.go_back')</a>
                    </h1>
                    <?php //echo '<pre>'; print_r($booking_detail);?>
                    <form class="getHumanLessCar" method="post" action="" onsubmit="return false;">
                        <input type="hidden" id="booking_id" name="booking_id" value="<?php echo base64_encode($booking_id); ?>">
                        <input type="hidden" id="carPlate_no" name="car_plate" value="">
                        <div class="wizard-1">
                            <div class="locateCar">
                                <h3>@lang('labels.locate_your_car')</h3>
                                <p><?php echo ($lang == 'eng'?'Follow that map to reach your car':'Follow that map to reach your car');?></p>
                                <div class="car-parking">
                                    <img class="parking-img" src="<?php echo $base_url . '/public/frontend/images/img-car.jpg';?>">
                                </div>
                            </div>
                            <div class="btn-box">
                                <a class="backBtn redishButton " href="<?php echo $lang_base_url; ?>/booking-detail/<?php echo custom::encode_with_jwt($booking_id); ?>">
                                    <?php echo($lang == 'eng' ? 'Back' : 'شارك'); ?>
                                </a>
                                <a class="nextBtn redishButton  show_wiz2" href="javascript:void(0);">
                                    <?php echo($lang == 'eng' ? 'Next' : 'طباعة'); ?>
                                </a>
                            </div>
                        </div>

                        <div class="wizard-2" style="display: none;">
                            <div class="selectCar">
                                <div class="qr-holder">
                                    <h3>@lang('labels.select_car_plate')</h3>
                                    <div class="qr-code">
                                        <img class="qrCode-img" src="<?php echo $base_url . '/public/frontend/images/img-qrcode.jpg';?>">
                                    </div>
                                </div>
                                <div class="car-plates">
                                    <div class="carModel">
                                        <h3><?php echo ($lang == 'eng' ? $booking_detail->car_type_eng_title : $booking_detail->car_type_arb_title) . ' ' . ($lang == 'eng' ? $booking_detail->car_model_eng_title : $booking_detail->car_model_arb_title) . ' ' . $booking_detail->year; ?></h3>
                                        <a class="chng_car redishButton show_wizard_2_1" href="javascript:void(0);" >@lang('labels.change_car')</a>
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
                                <a class="backBtn redishButton  show_wiz1" href="javascript:void(0);">
                                    <?php echo($lang == 'eng' ? 'Back' : 'شارك'); ?>
                                </a>
                                <a class="nextBtn redishButton  show_wiz3" href="javascript:void(0);">
                                    <?php echo($lang == 'eng' ? 'Next' : 'طباعة'); ?>
                                </a>
                            </div>
                        </div>

                        <div class="wizard_2_1" style="display: none;">
                            <div class="change-car">
                                <a href="javascript:void(0);" class="back2Wiz2 back_to_wiz2">
                                    @lang('labels.go_back')
                                </a>
                                <h3>@lang('labels.choose_another_car')</h3>
                                <select name="select_car" class="filter-car" title="Select Car">
                                    <option value="">Car 1</option>
                                    <option value="">Car 2</option>
                                    <option value="">Car 3</option>
                                </select>
                                <div class="car-list">
                                    <div class="upgradedCars">
                                        <div class="singleRow mobileView" style="">
                                            <div class="p-holder">
                                                <div class="imgBox">
                                                    <img src="http://kra.ced.sa/public/uploads/ab-LexusES350-2016-1492595345.png" alt="car" height="132" width="274">
                                                </div>
                                                <div class="bookName">
                                                    <h2>LEXUS ES 350 2016<span>OR SIMILAR</span></h2>
                                                    <h3>Premium</h3>
                                                </div>
                                            </div>
                                            <div class="btn-holder">
                                                <a href="javascript:void(0);" class="pick-up" data-bs-toggle="modal" data-bs-target="#carDescPopup1">View details</a>
                                                <div class="edBtn select_back_to_wiz2" data-car_id="123">
                                                    <div class="col rentPDay">
                                                        <h4>Rent Per Day</h4>
                                                        <span class="less-price"><span class="del-price">752.94 SAR</span><br> 14.82% Off</span>
                                                    </div>
                                                    <div class="col totalRent">
                                                        <h4>Starting from</h4>
                                                        <p>641.35 SAR</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-mobile modal fade booking-modal" id="carDescPopup1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center">
                                                            <div class="bookPSec">
                                                                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                                                <h2>LEXUS ES 350 2016<span>OR SIMILAR</span></h2>
                                                                <h3>Premium</h3>
                                                            </div>
                                                            <div class="col bookFeature ">
                                                                <h4>Features</h4>
                                                                <ul>
                                                                    <li>
                                                                        <div class="spIconF person"></div>
                                                                        <p>5</p></li>
                                                                    <li>
                                                                        <div class="spIconF transmition"></div>
                                                                        <p>Auto</p>
                                                                    </li>
                                                                    <li>
                                                                        <div class="spIconF door"></div>
                                                                        <p>4</p></li>
                                                                    <li>
                                                                        <div class="spIconF bag"></div>
                                                                        <p>2</p>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col rentPDay"><h4>Rent Per Day</h4>
                                                                <span class="less-price"><span class="del-price">752.94 SAR</span><br> 14.82% Off</span>
                                                                <p>641.35 SAR</p>
                                                            </div>
                                                            <div class="col totalRent">
                                                                <h4>Total Rent For  1 DAYS</h4>
                                                                <p>641.35 SAR</p>
                                                                <h4 style="color: #4B4B4B;">(*VAT is not included) </h4>
                                                            </div>
                                                        </div>
                                                        <div class="bookDtlSec">
                                                            <div class="col bookBtn">
                                                                <a href="javascript:void(0);" class="select_back_to_wiz2" data-car_id="123">
                                                                    @lang('labels.select')
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="singleRow mobileView" style="">
                                            <div class="p-holder">
                                                <div class="imgBox">
                                                    <img src="http://kra.ced.sa/public/uploads/ab-LexusES350-2016-1492595345.png" alt="car" height="132" width="274">
                                                </div>
                                                <div class="bookName">
                                                    <h2>LEXUS ES 350 2016<span>OR SIMILAR</span></h2>
                                                    <h3>Premium</h3>
                                                </div>
                                            </div>
                                            <div class="btn-holder">
                                                <a href="javascript:void(0);" class="pick-up" data-bs-toggle="modal" data-bs-target="#carDescPopup2">View details</a>
                                                <div class="edBtn select_back_to_wiz2" data-car_id="123">
                                                    <div class="col rentPDay">
                                                        <h4>Rent Per Day</h4>
                                                        <span class="less-price"><span class="del-price">752.94 SAR</span><br> 14.82% Off</span>
                                                    </div>
                                                    <div class="col totalRent">
                                                        <h4>Starting from</h4>
                                                        <p>641.35 SAR</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-mobile modal fade booking-modal" id="carDescPopup2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center">
                                                            <div class="bookPSec">
                                                                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                                                <h2>LEXUS ES 350 2016<span>OR SIMILAR</span></h2>
                                                                <h3>Premium</h3>
                                                            </div>
                                                            <div class="col bookFeature ">
                                                                <h4>Features</h4>
                                                                <ul>
                                                                    <li>
                                                                        <div class="spIconF person"></div>
                                                                        <p>5</p></li>
                                                                    <li>
                                                                        <div class="spIconF transmition"></div>
                                                                        <p>Auto</p>
                                                                    </li>
                                                                    <li>
                                                                        <div class="spIconF door"></div>
                                                                        <p>4</p></li>
                                                                    <li>
                                                                        <div class="spIconF bag"></div>
                                                                        <p>2</p>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col rentPDay"><h4>Rent Per Day</h4>
                                                                <span class="less-price"><span class="del-price">752.94 SAR</span><br> 14.82% Off</span>
                                                                <p>641.35 SAR</p>
                                                            </div>
                                                            <div class="col totalRent">
                                                                <h4>Total Rent For  1 DAYS</h4>
                                                                <p>641.35 SAR</p>
                                                                <h4 style="color: #4B4B4B;">(*VAT is not included) </h4>
                                                            </div>
                                                        </div>
                                                        <div class="bookDtlSec">
                                                            <div class="col bookBtn">
                                                                <a href="javascript:void(0);" class="select_back_to_wiz2" data-car_id="123">
                                                                    @lang('labels.select')
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="singleRow mobileView" style="">
                                            <div class="p-holder">
                                                <div class="imgBox">
                                                    <img src="http://kra.ced.sa/public/uploads/ab-LexusES350-2016-1492595345.png" alt="car" height="132" width="274">
                                                </div>
                                                <div class="bookName">
                                                    <h2>LEXUS ES 350 2016<span>OR SIMILAR</span></h2>
                                                    <h3>Premium</h3>
                                                </div>
                                            </div>
                                            <div class="btn-holder">
                                                <a href="javascript:void(0);" class="pick-up" data-bs-toggle="modal" data-bs-target="#carDescPopup3">View details</a>
                                                <div class="edBtn select_back_to_wiz2" data-car_id="123">
                                                    <div class="col rentPDay">
                                                        <h4>Rent Per Day</h4>
                                                        <span class="less-price"><span class="del-price">752.94 SAR</span><br> 14.82% Off</span>
                                                    </div>
                                                    <div class="col totalRent">
                                                        <h4>Starting from</h4>
                                                        <p>641.35 SAR</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-mobile modal fade booking-modal" id="carDescPopup3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body text-center">
                                                            <div class="bookPSec">
                                                                <a href="javascript:void(0)" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                                                                <h2>LEXUS ES 350 2016<span>OR SIMILAR</span></h2>
                                                                <h3>Premium</h3>
                                                            </div>
                                                            <div class="col bookFeature ">
                                                                <h4>Features</h4>
                                                                <ul>
                                                                    <li>
                                                                        <div class="spIconF person"></div>
                                                                        <p>5</p></li>
                                                                    <li>
                                                                        <div class="spIconF transmition"></div>
                                                                        <p>Auto</p>
                                                                    </li>
                                                                    <li>
                                                                        <div class="spIconF door"></div>
                                                                        <p>4</p></li>
                                                                    <li>
                                                                        <div class="spIconF bag"></div>
                                                                        <p>2</p>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col rentPDay"><h4>Rent Per Day</h4>
                                                                <span class="less-price"><span class="del-price">752.94 SAR</span><br> 14.82% Off</span>
                                                                <p>641.35 SAR</p>
                                                            </div>
                                                            <div class="col totalRent">
                                                                <h4>Total Rent For  1 DAYS</h4>
                                                                <p>641.35 SAR</p>
                                                                <h4 style="color: #4B4B4B;">(*VAT is not included) </h4>
                                                            </div>
                                                        </div>
                                                        <div class="bookDtlSec">
                                                            <div class="col bookBtn">
                                                                <a href="javascript:void(0);" class="select_back_to_wiz2" data-car_id="123">
                                                                    @lang('labels.select')
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wizard-3" style="display: none;">
                            <div class="car-inspect">
                                <h3>@lang('labels.car_inspection')</h3>
                                <p><?php echo ($lang == 'eng'?'Please click on the car figure to upload pictures':'Please click on the car figure to upload pictures');?></p>

                                <div class="car-pictures">
                                    <a class="" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#showCarInspection" id="openCarInspection"></a>
                                    <img class="carInpec-img" src="<?php echo $base_url . '/public/frontend/images/img-carInspec.png';?>">
                                </div>
                                <div class="range-km">
                                    <i class="km-reading"></i>
                                    <span class="km-value" id="km-value">20,453 KM</span>
                                </div>
                                <div class="range-fuel">
                                    <i class="fuel-meter"></i>
                                    <div class="range">
                                        <img src="<?php echo $base_url . '/public/frontend/images/fuel-range-img.png';?>" class="fuel-range" alt="fuel-range">
                                    </div>
                                </div>
                            </div>
                            <div class="btn-box">
                                <a class="backBtn redishButton  show_wiz2" href="javascript:void(0);">
                                    <?php echo($lang == 'eng' ? 'Back' : 'شارك'); ?>
                                </a>
                                <a class="nextBtn redishButton  show_wiz4" href="javascript:void(0);">
                                    <?php echo($lang == 'eng' ? 'Next' : 'طباعة'); ?>
                                </a>
                            </div>
                        </div>

                        <div class="wizard-4" style="display: none;">
                            <div class="tam-issue">
                                <h3>@lang('labels.issue_tam')</h3>
                                <span class="error-tam"></span>
                                <div class="tam-info">
                                    <label>@lang('labels.enter_your_id_version')</label>
                                    <input type="text" name="id_no" value="" placeholder="@lang('labels.enter_id_version')">
                                </div>
                            </div>
                            <div class="btn-box">
                                <a class="backBtn redishButton  show_wiz3" href="javascript:void(0);">
                                    <?php echo($lang == 'eng' ? 'Back' : 'شارك'); ?>
                                </a>
                                <a class="nextBtn redishButton  show_wiz5" href="javascript:void(0);">
                                    <?php echo($lang == 'eng' ? 'Next' : 'طباعة'); ?>
                                </a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection