@extends('frontend.layouts.template')

@section('content')
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
                <div class="myProfDetail booking-details-screen">
                    <?php if ($lang == 'eng')
                    { ?>
                        <h1><strong>@lang('labels.my') </strong> @lang('labels.bookings')</h1>
                    <?php }else{ ?>
                        <h1>حجوزاتي</h1>
                        <?php } ?>
                    <form action="<?php echo $lang_base_url; ?>/my_bookings_filter" method="post" class="myBookingSerBar" id="my_bookings_filtering" onsubmit="return false;">
                        <input type="text" placeholder="<?php echo ($lang == 'eng' ? 'SEARCH HERE' : 'ابحث هنا'); ?>" class="search" id="key_name" name="key_name"/>
                        <input type="text" placeholder="<?php echo ($lang == 'eng' ? 'SELECT DATE' : 'إختر تاريخ الإستلام'); ?>" class="calender" id="date" name="date"/>
                        <input type="hidden" name="records_for" value="current_bookings"/>
                        <button type="submit" class="redishButtonRound" style="min-width: 70px; !important;">@lang('labels.filter')</button>
                        <button type="button" id="reset_results" class="redishButtonRound" style="min-width: 70px; !important;">@lang('labels.view_all')</button>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="old_records">
                            <?php
                            //echo "<pre>"; print_r($my_bookings); exit;
                            if (count($my_bookings) > 0)
                            {
                            foreach ($my_bookings as $booking)
                            {
                            if ($booking->booking_status == 'Not Picked') {
                                $highlightClass = 'notPicked';
                                if ($lang == 'eng')
                                    $status_text = 'NOT PICKED';
                                else
                                    $status_text = 'لم يتم الإستلام';

                            } elseif ($booking->booking_status == 'Picked') {
                                $highlightClass = 'pickedUp';

                                if ($lang == 'eng')
                                    $status_text = 'PICKED UP';
                                else
                                    $status_text = 'تم الإستلام';

                            } elseif ($booking->booking_status == 'Completed' || $booking->booking_status == 'Completed with Overdue') {
                                $highlightClass = 'completed';
                                if ($booking->booking_status == 'Completed')
                                {
                                    if ($lang == 'eng')
                                        $status_text = 'COMPLETED';
                                    else
                                        $status_text = 'عقد مغلق ';
                                } elseif ($booking->booking_status == 'Completed with Overdue')
                                {
                                    if ($lang == 'eng')
                                        $status_text = 'COMPLETED WITH OVERDUE';
                                    else
                                        $status_text = 'عقد مغلق مع تأخير';
                                }

                            } elseif ($booking->booking_status == 'Cancelled' || $booking->booking_status == 'Expired') {
                                $highlightClass = 'cancelled';

                                if ($booking->booking_status == 'Cancelled')
                                    {
                                        if ($lang == 'eng')
                                            $status_text = 'CANCELLED';
                                        else
                                            $status_text = 'ملغي';
                                    } elseif ($booking->booking_status == 'Expired')
                                    {
                                        if ($lang == 'eng')
                                            $status_text = 'EXPIRED';
                                        else
                                            $status_text = 'منتهي';
                                    }
                            }elseif ($booking->booking_status == 'Walk in') {
                                    $highlightClass = 'pickedUp';

                                    if ($lang == 'eng')
                                        $status_text = 'WALK IN';
                                    else
                                        $status_text = 'حجز عن طريق الفرع';

                                }
                            ?>


                            <div id="myBookingRow_<?php echo $booking->id; ?>"
                                 class="myBookingRow <?php echo $highlightClass; ?>">
                                <div class="topName">
                                    <h2><?php echo ($lang == 'eng' ? $booking->car_type_eng_title : $booking->car_type_arb_title) . ' ' . ($lang == 'eng' ? $booking->car_eng_title : $booking->car_arb_title) . ' ' . $booking->year; ?>
                                        <span> @lang('labels.or_similar') </span>
                                        <strong> <?php echo($lang == 'eng' ? $booking->car_category_eng_title : $booking->car_category_arb_title); ?> </strong>
                                    </h2>
                                </div>
                                <div class="topOptions">

                                    <h3 id="statusMsg_<?php echo $booking->id; ?>"><?php echo $status_text; ?></h3>
                                    <div class="seprator"> |</div>
                                    <h4>@lang('labels.your_reservation')
                                        <span> <?php echo $booking->reservation_code; ?> </span></h4>
                                    <div class="buttonsOpt">
                                        <?php if($booking->booking_status == "Not Picked"){ ?>

                                       <?php
                                        $site = custom::site_settings();
                                        $post_booking_hours_from_db = $site->post_booking_cancellation_hours.' hours';
                                        $time = date("Y-m-d H:i:s");
                                        $pick_up_date = new \DateTime($booking->from_date);
                                        $cancel_date = new \DateTime($time);
                                        $start = new \Carbon\Carbon($booking->from_date);
                                        $end = \Carbon\Carbon::now();
                                        $difference = custom::getDateDifference($start, $end);
                                        if (($pick_up_date->getTimestamp() > $cancel_date->getTimestamp()) || ($difference['minutes'] <= $site->post_booking_cancellation_hours*60)) {
                                        ?>
                                        <a id="bCancelBtn_<?php echo $booking->id; ?>" class="bCancelBtn"
                                           href="javascript:void(0);"
                                           onclick="cancelBooking(<?php echo $booking->id; ?>);">
                                            <button class="grayishButton"><img
                                                        src="<?php echo $base_url; ?>/public/frontend/images/cancel.png"
                                                        alt="X"
                                                        height="14"
                                                        width="15"/> <?php if ($lang == "eng") echo "Cancel"; else
                                                    echo "إلغاء"; ?>
                                            </button>
                                        </a>
                                        <?php } ?>

                                            <?php if(custom::is_booking_editable($booking->id)){ ?>
                                            <a class="bCancelBtn bEditBtn"
                                               data-bid="{{custom::encode_with_jwt($booking->id)}}"
                                               href="<?php echo $lang_base_url . "/edit-booking/" . custom::encode_with_jwt($booking->id);?>">
                                                <button class="grayishButton">
                                                <img
                                                        src="<?php echo $base_url; ?>/public/frontend/images/edit.png"
                                                        alt="E" height="14"
                                                        width="15"/><?php if ($lang == "eng") echo "Edit"; else
                                                        echo "تعديل"; ?></button>
                                            </a>
                                            <?php } ?>

                                        <?php } ?>

                                            <?php if (custom::show_add_payment_option($booking->id)) { ?>
                                            <a href="<?php echo $lang_base_url.'/add-payment?s=1&q='.custom::encode_with_jwt($booking->id) ?>">
                                                <button class="grayishButton"><?php echo ($lang == 'eng' ? 'Add Payment' : 'إضافة دفعة'); ?></button>
                                            </a>
                                            <?php } ?>

                                        <a href="<?php echo $lang_base_url.'/print-booking/'.custom::encode_with_jwt($booking->reservation_code.'||EDxjrybEuppO') ?>" target="_blank">
                                            <button class="grayishButton"><img
                                                        src="<?php echo $base_url; ?>/public/frontend/images/print.png"
                                                        alt="P"
                                                        height="14" width="15"/>@lang('labels.print')
                                            </button>
                                        </a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <?php
                                if ($booking->image1 != '') {
                                    $car_image_path = $base_url . '/public/uploads/' . $booking->image1;
                                } else {
                                    $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                                }
                                ?>
                                <div class="clearfix"></div>
                                <a href="<?php echo $lang_base_url; ?>/booking-detail/<?php echo custom::encode_with_jwt($booking->id); ?>"
                                   class="bookingLink">
                                    <div class="mBookingDTL">
                                        <div class="col imgBox">
                                            <div class="displayTable">
                                                <div class="disTableCell">
                                                    <img src="<?php echo $car_image_path; ?>" alt="{{ $lang == 'eng' ? $booking->image1_eng_alt : $booking->image1_arb_alt }}" height="132"
                                                         width="274"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col twoBig">
                                            <label>@lang('labels.pick_up')</label>
                                            <ul>
                                                <?php if ($booking->is_delivery_mode == 'yes')
                                                { ?>
                                                <li title="<?php echo custom::getCleanLocationName($booking->pickup_delivery_lat_long, 'short')." (".$booking->pickup_delivery_lat_long.")"; ?>">
                                                    <img class="abImg"
                                                         src="<?php echo $base_url; ?>/public/frontend/images/location.png"
                                                         alt="" width="13" height="18">
                                                    <?php echo custom::getCleanLocationName($booking->pickup_delivery_lat_long, 'short')."<br>(".$booking->pickup_delivery_lat_long.")"; ?>
                                                </li>
                                                <?php }else{ ?>
                                                    <li title="<?php echo($lang == 'eng' ? $booking->branch_eng_from : $booking->branch_arb_from); ?>">
                                                        <img class="abImg"
                                                             src="<?php echo $base_url; ?>/public/frontend/images/location.png"
                                                             alt="" height="18"
                                                             width="13"/><?php echo($lang == 'eng' ? $booking->branch_eng_from : $booking->branch_arb_from); ?>
                                                    </li>
										<?php } ?>

                                                <li><img class="abImg"
                                                         src="<?php echo $base_url; ?>/public/frontend/images/calendar.png"
                                                         alt="" height="18"
                                                         width="16"/> <?php echo date('d / m / Y', strtotime($booking->from_date)); ?>
                                                </li>
                                                <li><img class="abImg"
                                                         src="<?php echo $base_url; ?>/public/frontend/images/clock.png"
                                                         alt="" height="18"
                                                         width="18"/> <?php echo date('H:i A', strtotime($booking->from_date)); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col twoBig">
                                            <label>@lang('labels.drop_off')</label>
                                            <ul>
                                                <?php if ($booking->is_delivery_mode == 'yes')
                                                { ?>
                                                <li title="<?php echo custom::getCleanLocationName($booking->dropoff_delivery_lat_long, 'short')." (".$booking->dropoff_delivery_lat_long.")"; ?>">
                                                    <img class="abImg"
                                                         src="<?php echo $base_url; ?>/public/frontend/images/location.png"
                                                         alt="" width="13" height="18">
                                                    <?php echo custom::getCleanLocationName($booking->dropoff_delivery_lat_long, 'short')."<br>(".$booking->dropoff_delivery_lat_long.")"; ?>
                                                </li>
                                                <?php }else{ ?>
                                                    <li title="<?php echo($lang == 'eng' ? $booking->branch_eng_to : $booking->branch_arb_to); ?>">
                                                        <img class="abImg"
                                                             src="<?php echo $base_url; ?>/public/frontend/images/location.png"
                                                             alt="" height="18"
                                                             width="13"/><?php echo($lang == 'eng' ? $booking->branch_eng_to : $booking->branch_arb_to); ?>
                                                    </li>
										<?php } ?>

                                                <li><img class="abImg"
                                                         src="<?php echo $base_url; ?>/public/frontend/images/calendar.png"
                                                         alt="" height="18"
                                                         width="16"/> <?php echo date('d / m / Y', strtotime($booking->to_date)); ?>
                                                </li>
                                                <li><img class="abImg"
                                                         src="<?php echo $base_url; ?>/public/frontend/images/clock.png"
                                                         alt="" height="18"
                                                         width="18"/> <?php echo date('H:i A', strtotime($booking->to_date)); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col small features <?php echo ($booking->min_age > 0 ? 'contains-min-age' : ''); ?>">
                                            <label>@lang('labels.features')</label>
                                            <ul>
                                                <li>
                                                    <div class="spIconF person"></div>
                                                    <p><?php echo $booking->no_of_passengers; ?></p></li>
                                                <li>
                                                    <div class="spIconF transmition"></div>
                                                    <p><?php echo($booking->transmission == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي')); ?></p>
                                                </li>
                                                <li>
                                                    <div class="spIconF door"></div>
                                                    <p><?php echo $booking->no_of_doors; ?></p></li>
                                                <li>
                                                    <div class="spIconF bag"></div>
                                                    <p><?php echo $booking->no_of_bags; ?></p></li>
                                                <?php if ($booking->min_age > 0)
                                                { ?>
                                                <li>
                                                    <div class="spIconF minAge"></div>
                                                    <p><?php echo $booking->min_age; ?></p>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="col colorBg">
                                            <div class="displayTable">
                                                <div class="disTableCell">
                                                    <p>@lang('labels.total_rent_for_capital') <?php echo $booking->no_of_days; ?> <?php echo ($booking->is_delivery_mode == 'hourly' ? trans('labels.hours') : trans('labels.days')); ?></p>
                                                    <strong class="bigText"><?php echo number_format($booking->total_sum, 2); ?>
                                                        @lang('labels.currency')</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <?php }
                            }else{ ?>
                            <div class="noResultFound"><span>@lang('labels.no_record_found')</span></div>
                            <?php }
                            ?>
                            </div>
                            <div id="new_records" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection