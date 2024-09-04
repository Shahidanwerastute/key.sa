@extends('frontend.layouts.template')
@section('content')
    <section class="myAccountSec">
        <div class="myAccountWrapper">
            <?php if (Session::get("user_type") == "individual_customer")
                { ?>
                @include('frontend.mobile.layouts.profile_inner_section')
                <?php }elseif (Session::get("user_type") == "corporate_customer"){ ?>
                @include('frontend.mobile.layouts.corporate_profile_inner_section')
                <?php } ?>
            <div class="myProfDetail">
                <!--<form action="<?php //echo $lang_base_url; ?>/my_bookings_filter" method="post" class="myBookingSerBar" id="my_bookings_filtering" onsubmit="return false;">
                    <input type="text" placeholder="<?php //echo ($lang == 'eng' ? 'SEARCH HERE' : 'ابحث هنا'); ?>" class="search" id="key_name" name="key_name"/>
                    <input type="text" placeholder="<?php //echo ($lang == 'eng' ? 'SELECT DATE' : 'إختر تاريخ الإستلام'); ?>" class="calender" id="date" name="date"/>
                    <input type="hidden" name="records_for" value="current_bookings"/>
                    <button type="submit" class="redishButtonRound" style="min-width: 70px; !important;">{{--@lang('labels.filter')--}}</button>
                    <button type="button" id="reset_results" class="redishButtonRound" style="min-width: 70px; !important;">{{--@lang('labels.view_all')--}}</button>
                </form>-->
                <div id="old_records">
                <?php
                //echo "<pre>"; print_r($my_bookings); exit;
                if (count($my_bookings) > 0){
					$k=0;
                foreach ($my_bookings as $booking){
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

                if ($booking->image1 != '') {
                    $car_image_path = $base_url . '/public/uploads/' . $booking->image1;
                } else {
                    $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                }
                $from_city = custom::getFromToCityName($booking->city_from,$lang);
                $to_city = custom::getFromToCityName($booking->city_to,$lang);
                ?>
                <div id="myBookingRow_<?php echo $booking->id; ?>" class="myBookingRow <?php echo $highlightClass; ?>">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" id="dLabel<?php echo $k; ?>" aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel<?php echo $k; ?>">
                            <li><a href="<?php echo $lang_base_url; ?>/booking-detail/<?php echo custom::encode_with_jwt($booking->id); ?>"><?php echo ($lang == 'eng'?'Booking Details':'تفاصيل الحجز');?></a></li>
                            <?php if($booking->booking_status == "Not Picked"){
                            $site = custom::site_settings();
                            $post_booking_hours_from_db = $site->post_booking_cancellation_hours.' hours';
                            $time = date("Y-m-d H:i:s");
                            $pick_up_date = new \DateTime($booking->from_date);
                            $cancel_date = new \DateTime($time);
                            $start = new \Carbon\Carbon($booking->from_date);
                            $end = \Carbon\Carbon::now();
                            $difference = custom::getDateDifference($start, $end);

                            if (($pick_up_date->getTimestamp() > $cancel_date->getTimestamp()) ||
                            ($difference['minutes'] <= $site->post_booking_cancellation_hours*60)) {
                            ?>
                            <li>
                                <a id="bCancelBtn_<?php echo $booking->id; ?>" href="javascript:void(0);" onclick="cancelBooking(<?php echo $booking->id; ?>);">
                                    <?php if ($lang == "eng") echo "Cancel Booking"; else echo "إلغاء الحجز"; ?>
                                </a>
                            </li>
                            <?php } ?>

                            <?php if(custom::is_booking_editable($booking->id)){ ?>
                            <li>
                                <a class="bCancelBtn bEditBtn" data-bid="{{custom::encode_with_jwt($booking->id)}}" href="<?php echo $lang_base_url . "/edit-booking/" . custom::encode_with_jwt($booking->id);?>">@lang('labels.edit_booking')</a>
                            </li>
                            <?php } ?>

                            <?php } ?>

                            <?php if (custom::show_add_payment_option($booking->id)) { ?>
                            <li>
                            <a href="<?php echo $lang_base_url.'/add-payment?s=1&q='.custom::encode_with_jwt($booking->id) ?>">
                                Add Payment
                            </a>
                            </li>
                            <?php } ?>

                            <li><a href="javascript:void(0);"><?php echo ($lang == 'eng'?'Call Branch':'الإتصال بالفرع ');?></a></li>
                        </ul>
                    </div>
                    <a href="<?php echo $lang_base_url; ?>/booking-detail/<?php echo custom::encode_with_jwt($booking->id); ?>" class="bookingLink">
                        <div class="topRow">
                            <h4>
                                <?php echo '#'.$booking->reservation_code; ?>
                                <span id="statusMsg_<?php echo $booking->id; ?>"><?php echo $status_text; ?></span>
                            </h4>
                            <h2><?php echo ($lang == 'eng' ? $booking->car_type_eng_title : $booking->car_type_arb_title) . ' ' . ($lang == 'eng' ? $booking->car_eng_title : $booking->car_arb_title) . ' ' . $booking->year; ?>
                                <strong> <?php echo($lang == 'eng' ? $booking->car_category_eng_title : $booking->car_category_arb_title); ?> </strong>
                            </h2>
                        </div>
                        <div class="mBookingDTL">
                            <div class="col imgBox">
                                <div class="displayTable">
                                    <div class="disTableCell">
                                        <img src="<?php echo $car_image_path; ?>" alt="Car" height="132" width="274"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col twoBig">
                                <label>@lang('labels.pick_up')</label>
                                <ul>
                                    <li>
                                        <?php echo date('d M Y', strtotime($booking->from_date)).', '.date('H:i A', strtotime($booking->from_date)); ?>
                                    </li>
                                    <?php if ($booking->is_delivery_mode == 'yes'){ ?>
                                    <li title="<?php echo custom::getCleanLocationName($booking->pickup_delivery_lat_long, 'short')." (".$booking->pickup_delivery_lat_long.")"; ?>">
                                        <?php echo custom::getCleanLocationName($booking->pickup_delivery_lat_long, 'short')."(".$booking->pickup_delivery_lat_long.")"; ?>
                                    </li>
                                    <?php }else{ ?>
                                        <li title="<?php echo($lang == 'eng' ? $booking->branch_eng_from : $booking->branch_arb_from); ?>">
                                            <?php echo($lang == 'eng' ? $booking->branch_eng_from : $booking->branch_arb_from); ?>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <?php echo $from_city; ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="col twoBig">
                                <label>@lang('labels.drop_off')</label>
                                <ul>
                                    <li>
                                        <?php echo date('d M Y', strtotime($booking->to_date)).', '.date('H:i A', strtotime($booking->to_date)); ?>
                                    </li>
                                    <?php if ($booking->is_delivery_mode == 'yes'){ ?>
                                    <li title="<?php echo custom::getCleanLocationName($booking->dropoff_delivery_lat_long, 'short')." (".$booking->dropoff_delivery_lat_long.")"; ?>">
                                        <?php echo custom::getCleanLocationName($booking->dropoff_delivery_lat_long, 'short')."(".$booking->dropoff_delivery_lat_long.")"; ?>
                                    </li>
                                    <?php }else{ ?>
                                        <li title="<?php echo($lang == 'eng' ? $booking->branch_eng_to : $booking->branch_arb_to); ?>">
                                            <?php echo($lang == 'eng' ? $booking->branch_eng_to : $booking->branch_arb_to); ?>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <?php echo $to_city; ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </a>
                </div>
                <?php $k++; } }else{ ?>
                    <div class="noResultFound"><span>@lang('labels.no_record_found')</span></div>
                <?php } ?>
                </div>
                <div id="new_records" style="display: none;"></div>
            </div>
        </div>
    </section>
@endsection