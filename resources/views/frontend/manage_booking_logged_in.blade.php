@extends('frontend.layouts.template')

@section('content')
    <style>
        .bookingDetailSec .sixBoxStr .col.subTtl p strong {
            color: #ffffff;
        }
    </style>
    <?php
    if($booking_detail->booking_status == 'Walk in'){
        $discount_to_show = $booking_detail->discount_price."%";
        $discount_to_minus = 0;

    }else{
        $discount_to_show = number_format($booking_detail->discount_price * $booking_detail->no_of_days, 2)." ".($lang == 'eng' ? 'SAR' : 'ريال سعودي');
        $discount_to_minus = $booking_detail->discount_price;
    }

    ?>
    <section class="textBannerSec">
        <div class="container-md">
        </div>
    </section>
    <section class="myAccountSec">
        <div class="container-md">
            <div class="myAccountWrapper">
                <?php if (Session::get("user_type") == "individual_customer")
                { ?>
                @include('frontend.layouts.profile_inner_section')
                <?php }elseif (Session::get("user_type") == "corporate_customer"){ ?>
                @include('frontend.layouts.corporate_profile_inner_section')
                <?php } ?>
                <div class="myProfDetail">
                    <a href="<?php echo $lang_base_url; ?>/my-bookings" class="back2Page"><img
                                src="<?php echo $base_url; ?>/public/frontend/images/goBack.png" alt="<--" height="26"
                                width="26"/> @lang('labels.go_back')</a>
                    <?php if ($lang == 'eng')
                    { ?>
                    <h1><strong>@lang('labels.my') </strong> @lang('labels.bookings')</h1>
                    <?php }else{ ?>
                    <h1>حجوزاتي</h1>
                    <?php } ?>
                    <div class="bookingDetailSec">
                        <?php
                        if ($booking_detail->booking_status == 'Not Picked') {
                            $highlightClass = 'notPicked';
                            if ($lang == 'eng')
                                $status_text = 'NOT PICKED';
                            else
                                $status_text = 'لم يتم الإستلام';
                        } elseif ($booking_detail->booking_status == 'Picked') {
                            $highlightClass = 'pickedUp';
                            if ($lang == 'eng')
                                $status_text = 'PICKED UP';
                            else
                                $status_text = 'تم الإستلام';
                        } elseif ($booking_detail->booking_status == 'Completed' || $booking_detail->booking_status == 'Completed with Overdue') {
                            $highlightClass = 'completed';
                            if ($booking_detail->booking_status == 'Completed') {
                                if ($lang == 'eng')
                                    $status_text = 'COMPLETED';
                                else
                                    $status_text = 'عقد مغلق';
                            } elseif ($booking_detail->booking_status == 'Completed with Overdue') {
                                if ($lang == 'eng')
                                    $status_text = 'COMPLETED WITH OVERDUE';
                                else
                                    $status_text = 'عقد مغلق مع تأخير';
                            }
                        } elseif ($booking_detail->booking_status == 'Cancelled' || $booking_detail->booking_status == 'Expired') {
                            $highlightClass = 'cancelled';
                            if ($booking_detail->booking_status == 'Cancelled') {
                                if ($lang == 'eng')
                                    $status_text = 'CANCELLED';
                                else
                                    $status_text = 'ملغي';
                            } elseif ($booking_detail->booking_status == 'Expired') {
                                if ($lang == 'eng')
                                    $status_text = 'EXPIRED';
                                else
                                    $status_text = 'منتهي';
                            }
                        }elseif ($booking_detail->booking_status == 'Walk in') {
                            $highlightClass = 'pickedUp';

                            if ($lang == 'eng')
                                $status_text = 'WALK IN';
                            else
                                $status_text = 'حجز عن طريق الفرع';

                        }
                        ?>
                        <div id="myBookingRow_<?php echo $booking_detail->id; ?>"
                             class="myBookingRow <?php echo $highlightClass; ?>">
                            <div class="topName">
                                <h4>@lang('labels.your_reservation')
                                    <span> <?php echo $booking_detail->reservation_code; ?> </span>
                                </h4>
                            </div>
                            <div class="topOptions">
                                <h3 id="statusMsg"><?php echo $status_text; ?></h3>
                                <div class="buttonsOpt">
                                    <?php
                                    if($booking_detail->booking_status == "Not Picked")
                                    {
                                    $site = custom::site_settings();
                                    $post_booking_hours_from_db = $site->post_booking_cancellation_hours.' hours';
                                    $time = date("Y-m-d H:i:s");
                                    $pick_up_date = new \DateTime($booking_detail->from_date);
                                    $cancel_date = new \DateTime($time);
                                    $humanless_date = new \DateTime($time);
                                    $start = new \Carbon\Carbon($booking_detail->from_date);
                                    $end = \Carbon\Carbon::now();
                                    $difference = custom::getDateDifference($start, $end);
                                    if (($pick_up_date->getTimestamp() > $cancel_date->getTimestamp()) || ($difference['minutes'] <= $site->post_booking_cancellation_hours*60)) {
                                    ?>

                                    <a id="bCancelBtn_<?php echo $booking_detail->id; ?>" class="bCancelBtn"
                                       href="javascript:void(0);"
                                       onclick="cancelBooking(<?php echo $booking_detail->id; ?>);">
                                        <button class="grayishButton"><img
                                                    src="<?php echo $base_url; ?>/public/frontend/images/cancel.png"
                                                    alt="X"
                                                    height="14"
                                                    width="15"/> <?php echo($lang == 'eng' ? 'Cancel' : 'إلغاء'); ?>
                                        </button>
                                    </a>
                                    <?php } ?>

                                        <?php if(custom::is_booking_editable($booking_detail->id)){ ?>
                                        <a class="bCancelBtn bEditBtn"
                                           data-bid="{{custom::encode_with_jwt($booking_detail->id)}}"
                                           href="<?php echo $lang_base_url . "/edit-booking/" . custom::encode_with_jwt($booking_detail->id);?>">
                                            <button class="grayishButton">
                                            <img
                                                    src="<?php echo $base_url; ?>/public/frontend/images/edit.png"
                                                    alt="E" height="14"
                                                    width="15"/><?php if ($lang == "eng") echo "Edit"; else
                                                    echo "تعديل"; ?></button>
                                        </a>
                                        <?php } ?>

                                    <?php } ?>

                                        <?php if (custom::show_add_payment_option($booking_detail->id)) { ?>
                                        <a href="<?php echo $lang_base_url.'/add-payment?s=1&q='.custom::encode_with_jwt($booking_detail->id) ?>">
                                            <button class="grayishButton"><?php echo ($lang == 'eng' ? 'Add Payment' : 'إضافة دفعة'); ?></button>
                                        </a>
                                        <?php } ?>

                                    <a href="<?php echo $lang_base_url . '/print-booking/'.custom::encode_with_jwt($booking_detail->reservation_code.'||EDxjrybEuppO'); ?>"
                                       target="_blank">
                                        <button class="grayishButton"><img
                                                    src="<?php echo $base_url; ?>/public/frontend/images/print.png"
                                                    alt="P"
                                                    height="14"
                                                    width="15"/><?php echo($lang == 'eng' ? 'Print' : 'طباعة'); ?>
                                        </button>
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php
                        $site = custom::site_settings();
                        $post_booking_hours_from_db = $site->post_booking_cancellation_hours.' hours';
                        $time = date("Y-m-d H:i:s");
                        $pick_up_date = new \DateTime($booking_detail->from_date);
                        $cancel_date = new \DateTime($time);
                        $humanless_date = new \DateTime($time);

                        if($site->human_less_mode == 'on' && $booking_detail->human_less_state == 'Acknowledged' && $booking_detail->booking_status == "Not Picked" && ($pick_up_date->getTimestamp() > $humanless_date->getTimestamp())){ ?>
                            <div class="section-hl">
                                <form method="get" class="getYourCarForm" action="<?php echo $lang_base_url; ?>/get-car/<?php echo base64_encode($booking_detail->id); ?>" >
                                    <div class="acceptTnC">
                                        <input id="checkbox1" type="checkbox" class="accept_terms" name="" value="1">
                                        <label for="checkbox1">@lang("labels.i_accept_the")
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#term_n_Cond">@lang("labels.terms_and_conditions"). *</a>
                                        </label>
                                    </div>

                                    <div class="hl-instructions">
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#hl_instructions">@lang("labels.hl_instructions")</a>
                                    </div>

                                    <div class="getCar" >
                                        <input type="submit" class="btn_getCar redishButton" value="@lang('labels.btn_get_your_car')" >
                                    </div>
                                </form>
                            </div>
                        <?php } if($site->human_less_mode == 'on' && $booking_detail->human_less_state == 'Car Taken' && $booking_detail->booking_status == "Picked" && ($pick_up_date->getTimestamp() > $humanless_date->getTimestamp())){ ?>
                            <div class="section-hl">
                                <a href="<?php echo $lang_base_url; ?>/end-trip/<?php echo base64_encode($booking_detail->id); ?>" class="btn_endTrip">@lang("labels.end_trip")</a>
                            </div>
                            <?php }  ?>
                            <style>
                                .col-4.status {
                                    background-color: #eff0e0;
                                    margin-bottom: 3px;
                                    text-align: center;
                                    color: black;
                                    font-weight: 600;
                                    padding: 5px 0px;
                                }
                                .col-4.status span{
                                    color: #fe7e00;
                                    padding-left: 10px;
                                }

                            </style>
                                <?php
                                    $delivery_booking_status = custom::delivery_booking_statuses($booking_detail->delivery_booking_status, $lang);
                                    if ($delivery_booking_status) {
                                        echo '<div class="col-4 status">';
                                        echo ($lang == 'eng' ? 'Delivery Status' : 'حالة التسليم') . ': <span> ' . $delivery_booking_status . ' </span>';
                                        echo '</div>';
                                    }
                                ?>
                        <div class="sixBoxStr">

                            <div class="col carSumery">
                                <div class="bCenter">
                                    <div class="bookName">
                                        <h2><?php echo ($lang == 'eng' ? $booking_detail->car_type_eng_title : $booking_detail->car_type_arb_title) . ' ' . ($lang == 'eng' ? $booking_detail->car_model_eng_title : $booking_detail->car_model_arb_title) . ' ' . $booking_detail->year; ?>
                                            <span> @lang('labels.or_similar') </span></h2>
                                    </div>
                                    <h3><?php echo($lang == 'eng' ? $booking_detail->car_category_eng_title : $booking_detail->car_category_arb_title); ?></h3>
                                    <ul class="userInfo">
                                        <li>
                                            <strong><?php echo $booking_detail->first_name . ' ' . $booking_detail->last_name; ?></strong>
                                        </li>
                                        <li><strong>@lang('labels.id'):</strong> <?php echo custom::maskText($booking_detail->id_no, 4); ?>
                                        </li>
                                        <li>
                                            <strong><?php if ($lang == "eng") echo "M:"; else echo "التليفون المحمول:"; ?></strong> <?php echo custom::maskText($booking_detail->mobile_no, 6); ?>
                                        </li>
                                        <li>
                                            <strong><?php if ($lang == "eng") echo "E:"; else echo "البريد الإلكتروني:"; ?></strong> <?php echo custom::maskText($booking_detail->email, 3); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col twoBig ">
                                <div class="bCenter">
                                    <label>@lang('labels.pick_up')</label>
                                    <ul>
                                        <?php if ($booking_detail->is_delivery_mode == 'yes')
                                        { ?>
                                        <li title="<?php echo custom::getCleanLocationName($booking_detail->pickup_delivery_lat_long, 'short')." (".$booking_detail->pickup_delivery_lat_long.")"; ?>">
                                            <img class="abImg"
                                                 src="<?php echo $base_url; ?>/public/frontend/images/location.png"
                                                 alt="" width="13" height="18">
                                            <?php echo custom::getCleanLocationName($booking_detail->pickup_delivery_lat_long, 'short')."<br>(".$booking_detail->pickup_delivery_lat_long.")"; ?>
                                        </li>
                                        <?php }else{ ?>
                                        <li title="<?php echo($lang == 'eng' ? $booking_detail->branch_from_eng_title : $booking_detail->branch_from_arb_title); ?>">
                                            <img class="abImg"
                                                 src="<?php echo $base_url; ?>/public/frontend/images/location.png"
                                                 alt="" width="13" height="18">
                                            <?php echo($lang == 'eng' ? $booking_detail->branch_from_eng_title : $booking_detail->branch_from_arb_title); ?>
                                        </li>
                                            <?php } ?>
                                        <li><img class="abImg"
                                                 src="<?php echo $base_url; ?>/public/frontend/images/calendar.png"
                                                 alt="" width="16"
                                                 height="18"> <?php echo date('d / m / Y', strtotime($booking_detail->from_date)); ?>
                                        </li>
                                        <li><img class="abImg"
                                                 src="<?php echo $base_url; ?>/public/frontend/images/clock.png" alt=""
                                                 width="18"
                                                 height="18"> <?php echo date('H:i A', strtotime($booking_detail->from_date)); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col twoBig ">
                                <div class="bCenter">
                                    <label>@lang('labels.drop_off')</label>
                                    <ul>
                                        <?php if ($booking_detail->is_delivery_mode == 'yes')
                                        { ?>
                                        <li title="<?php echo custom::getCleanLocationName($booking_detail->dropoff_delivery_lat_long, 'short')." (".$booking_detail->dropoff_delivery_lat_long.")"; ?>">
                                            <img class="abImg"
                                                 src="<?php echo $base_url; ?>/public/frontend/images/location.png"
                                                 alt="" width="13" height="18">
                                            <?php echo custom::getCleanLocationName($booking_detail->dropoff_delivery_lat_long, 'short')."<br>(".$booking_detail->dropoff_delivery_lat_long.")"; ?>
                                        </li>
                                        <?php }else{ ?>
                                        <li title="<?php echo($lang == 'eng' ? $booking_detail->branch_to_eng_title : $booking_detail->branch_to_arb_title); ?>">
                                            <img class="abImg"
                                                 src="<?php echo $base_url; ?>/public/frontend/images/location.png"
                                                 alt="" width="13" height="18">
                                            <?php echo($lang == 'eng' ? $booking_detail->branch_to_eng_title : $booking_detail->branch_to_arb_title); ?>
                                        </li>
                                            <?php } ?>
                                        <li><img class="abImg"
                                                 src="<?php echo $base_url; ?>/public/frontend/images/calendar.png"
                                                 alt="" width="16"
                                                 height="18"> <?php echo date('d / m / Y', strtotime($booking_detail->to_date)); ?>
                                        </li>
                                        <li><img class="abImg"
                                                 src="<?php echo $base_url; ?>/public/frontend/images/clock.png" alt=""
                                                 width="18"
                                                 height="18"> <?php echo date('H:i A', strtotime($booking_detail->to_date)); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <?php
                            if ($booking_detail->car_image != '') {
                                $car_image_path = $base_url . '/public/uploads/' . $booking_detail->car_image;
                            } else {
                                $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                            }
                            ?>
                            <div class="col imgBox">
                                <div class="bCenter">
                                    <img src="<?php echo $car_image_path; ?>" alt="{{ $lang == 'eng' ? $booking_detail->car_image_eng_alt : $booking_detail->car_image_arb_alt}}" width="274" height="132">
                                </div>
                            </div>

                            <div class="col extraPrice ">
                                <div class="bCenter">
                                    <label class="<?php echo custom::hide_only_for_limousine($booking_detail->is_limousine); ?>">@lang('labels.extra')</label>
                                    <ul class="<?php echo custom::hide_only_for_limousine($booking_detail->is_limousine); ?>">
                                    <?php if ($booking_detail->cdw_price > 0 || $booking_detail->cdw_plus_price > 0 || $booking_detail->gps_price > 0 || $booking_detail->extra_driver_price > 0 || $booking_detail->baby_seat_price > 0){ ?>
                                        <?php if ($booking_detail->cdw_price > 0)
                                        { ?>
                                        <li>
                                            @lang('labels.lost_damage')
                                            <p><?php echo $booking_detail->cdw_price; ?> @lang('labels.currency')
                                                x <?php echo $booking_detail->no_of_days; ?> <?php echo ($booking_detail->is_delivery_mode == 'hourly' ? trans('labels.hours') : trans('labels.days')); ?></p>
                                        </li>
                                        <?php } ?>

                                        <?php if ($booking_detail->cdw_plus_price > 0)
                                        { ?>
                                        <li>
                                            @lang('labels.cdw_plus')
                                            <p><?php echo $booking_detail->cdw_plus_price; ?> @lang('labels.currency')
                                                x <?php echo $booking_detail->no_of_days; ?> <?php echo ($booking_detail->is_delivery_mode == 'hourly' ? trans('labels.hours') : trans('labels.days')); ?></p>
                                        </li>
                                        <?php } ?>
                                        <?php if ($booking_detail->gps_price > 0)
                                        { ?>
                                        <li>
                                            @lang('labels.gps')
                                            <p><?php echo $booking_detail->gps_price; ?> @lang('labels.currency')
                                                x <?php echo $booking_detail->no_of_days; ?> <?php echo ($booking_detail->is_delivery_mode == 'hourly' ? trans('labels.hours') : trans('labels.days')); ?></p>
                                        </li>
                                        <?php } ?>

                                        <?php if ($booking_detail->extra_driver_price > 0)
                                        { ?>
                                        <li>
                                            @lang('labels.extra_driver')
                                            <p><?php echo $booking_detail->extra_driver_price; ?> @lang('labels.currency')
                                                x <?php echo $booking_detail->no_of_days; ?> <?php echo ($booking_detail->is_delivery_mode == 'hourly' ? trans('labels.hours') : trans('labels.days')); ?></p>
                                        </li>
                                        <?php } ?>

                                        <?php if ($booking_detail->baby_seat_price > 0)
                                        { ?>
                                        <li>
                                            @lang('labels.baby_protection')
                                            <p><?php echo $booking_detail->baby_seat_price; ?> @lang('labels.currency')
                                                x <?php echo $booking_detail->no_of_days; ?> <?php echo ($booking_detail->is_delivery_mode == 'hourly' ? trans('labels.hours') : trans('labels.days')); ?></p>
                                        </li>
                                        <?php } ?>
                                        <?php if ($booking_detail->dropoff_charges > 0)
                                        { ?>
                                        <li>
                                            @lang('labels.dropoff_charges')
                                            <p><?php echo $booking_detail->dropoff_charges; ?> @lang('labels.currency') </p>
                                        </li>
                                        <?php } ?>
                                            <?php if ($booking_detail->delivery_charges > 0)
                                            { ?>
                                            <li>
                                                <?php echo ($lang == 'eng' ? 'Delivery Charges' : 'رسوم خدمة توصيل السيارة'); ?>
                                                <p><?php echo $booking_detail->delivery_charges; ?> @lang('labels.currency') </p>
                                            </li>
                                            <?php } ?>

                                    <?php }else{ ?>
                                        <li>
                                            <p><?php echo($lang == 'eng' ? 'No Extras Services Used.' : 'لا يوجد اي إضافات'); ?></p>
                                        </li>
                                    <?php } ?>
                                        <?php if ($booking_detail->vat_applied > 0)
                                            { ?>
                                        <br>
                                        <li>
                                            <?php echo ($lang == 'eng' ? 'VAT Applied' : 'ضريبة القيمة المضافة'); ?>
                                            <p><?php echo $booking_detail->vat_applied; ?> @lang('labels.currency') </p>
                                        </li>
                                            <?php } ?>
                                        <?php if ($booking_detail->redeem_points > 0)
                                        { ?>
                                        <li>
                                            <?php echo ($lang == 'eng' ? 'Redeem Points Used' : 'عدد النقاط المستخدمة'); ?>
                                            <p>
                                                <?php echo $booking_detail->redeem_points; ?> <?php echo ($lang == 'eng' ? 'Points' : 'نقطة'); ?>
                                                    (
                                                    <?php echo number_format($booking_detail->redeem_discount_availed, 2); ?>
                                                    <?php echo ($lang == 'eng' ? 'SAR' : 'ريال سعودي'); ?>
                                                    <?php echo ($lang == 'eng' ? 'Discounted' : 'خصم'); ?>
                                                    )
                                            </p>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="col bookFeature <?php echo ($booking_detail->min_age > 0 ? 'contains-min-age' : ''); ?>">
                                <div class="bCenter">
                                    <label>@lang('labels.features')</label>
                                    <ul>
                                        <li>
                                            <div class="spIconF person"></div>
                                            <p><?php echo $booking_detail->no_of_passengers; ?></p></li>
                                        <li>
                                            <div class="spIconF transmition"></div>
                                            <p><?php echo($booking_detail->transmission == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي')); ?></p>
                                        </li>
                                        <li>
                                            <div class="spIconF door"></div>
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
                            </div>
                            <?php if ($booking_detail->cdw_price > 0 || $booking_detail->cdw_plus_price > 0 || $booking_detail->gps_price > 0 || $booking_detail->extra_driver_price > 0 || $booking_detail->baby_seat_price > 0) {
                                $includingExtras = '( ' . ($lang == 'eng' ? 'Including extra charges' : 'متضمن سعر الإضافات') . ' )';
                                $excludingExtras = '( ' . ($lang == 'eng' ? 'Excluding extra charges' : 'غير متضمن سعر الإضافات') . ' )';
                            } else {
                                $includingExtras = '';
                                $excludingExtras = '';
                            }?>
                            <?php
                            $total_rent_for_all_days = $booking_detail->rent_price * $booking_detail->no_of_days;
                            $total_rent_after_subtracting_discount = $total_rent_for_all_days - $discount_to_minus;
                            $sadad = '<img src="' . $base_url . '/public/frontend/images/sadatFFF.png" alt="Sadat" height="13" width="54"/>';
                            ?>
                            <?php if ($booking_detail->discount_price > 0){ ?>
                                    <!-- If any discount is applied -->
                            <div class="col subTtl grayBox <?php echo custom::hide_only_for_limousine($booking_detail->is_limousine); ?>">
                                <div class="bCenter">
                                    <p><?php echo($lang == 'eng' ? 'Rent rate before discount' : 'سعر الايجار قبل الخصم'); ?> <?php echo $booking_detail->original_rent; ?> @lang('labels.currency')</p>
                                    <p><?php echo ($booking_detail->is_delivery_mode == 'hourly' ? trans('labels.rent_per_hour') : trans('labels.rent_per_day')); ?> <?php echo $booking_detail->rent_price; ?></p>
                                    <p>@lang('labels.total_rent_for_capital') <?php echo $booking_detail->no_of_days; ?> <?php echo ($booking_detail->is_delivery_mode == 'hourly' ? trans('labels.hours') : trans('labels.days')); ?></p>
                                    <p><span><?php echo number_format($total_rent_for_all_days, 2); ?>
                                            @lang('labels.currency')</span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $excludingExtras; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col subTtl yellowBox <?php echo custom::hide_only_for_limousine($booking_detail->is_limousine); ?>">
                                <div class="bCenter">
                                    <p><?php if ($lang == "eng") echo "DISCOUNT APPLIED"; else echo "خصم تطبيق"; ?></p>
                                    <p>
                                        <span><?php echo $discount_to_show; ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="col subTtl yellowBox <?php echo custom::hide_only_for_limousine($booking_detail->is_limousine); ?>">
                                <div class="bCenter">
                                    <p><?php echo($lang == 'eng' ? 'TOTAL PAID' : 'مجموع المدفوع'); ?></p>
                                    <p>
                                        <strong><?php echo number_format($booking_detail->total_sum, 2); ?> @lang('labels.currency')</strong>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $includingExtras; ?>
                                    </p>
                                    <p>
                                        <span class="buy"><?php echo($lang == 'eng' ? 'BY' : 'عن'); ?> <?php echo($booking_detail->payment_method == 'Sadad' ? $sadad : '<b>' . strtoupper($booking_detail->payment_method) . '</b>'); ?> </span>
                                    </p>
                                </div>
                            </div>
                            <!-- If any discount is applied -->
                            <?php } else { ?>
                                    <!-- If no discount applied -->
                            <div class="col subTtl full yellowBox <?php echo custom::hide_only_for_limousine($booking_detail->is_limousine); ?>">
                                <div class="bCenter">
                                    <p><?php echo($lang == 'eng' ? 'TOTAL PAID' : 'مجموع المدفوع'); ?></p>
                                    <p>
                                        <strong><?php echo number_format($booking_detail->total_sum, 2); ?> @lang('labels.currency')</strong>
                                    </p>
                                    <p>
                                        <span class="buy"><?php echo($lang == 'eng' ? 'BY' : 'عن'); ?> <?php echo($booking_detail->payment_method == 'Sadad' ? $sadad : '<b>' . strtoupper($booking_detail->payment_method) . '</b>'); ?> </span>
                                    </p>
                                </div>
                            </div>
                            <!-- If no discount applied -->
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="confirmDel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title"
                        id="myModalLabel"><?php if ($lang == "eng") echo "Confirm"; else echo "تؤكد"; ?></h4>
                </div>
                <div class="modal-body confmDelfrm text-center">
                    <br/>
                    <p><?php if ($lang == "eng") echo "Are you sure you want to delete your booking?"; else echo "هل تريد بالتأكيد حذف الحجز؟"; ?></p>
                    <form action="javascript:void(0);" method="get">
                        <input type="email" required placeholder="ENTER YOUR EMAIL ADDRESS"/>
                        <div class="twoBtnEd">
                            <input type="submit" class="redishButtonRound"
                                   value="<?php if ($lang == "eng") echo 'Yes'; else echo 'نعم فعلا'; ?>"/>
                            <input type="button" class="grayishButton"
                                   value="<?php if ($lang == "eng") echo 'No'; else echo 'لا'; ?>"
                                   data-bs-dismiss="modal"/>
                            <div class="clearfix"></div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection