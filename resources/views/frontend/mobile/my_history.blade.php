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
                    <input type="hidden" name="records_for" value="history_bookings"/>
                    <button type="submit" class="redishButtonRound" style="min-width: 70px; !important;">{{--@lang('labels.filter')--}}</button>
                    <button type="button" id="reset_results" class="redishButtonRound" style="min-width: 70px; !important;">{{--@lang('labels.view_all')--}}</button>
                </form>-->
                <div class="row">
                    <div class="col-md-12">
                        <div id="old_records">
                        <?php
                        if (count($my_history) > 0){
                        foreach ($my_history as $booking){
                        if ($booking->booking_status == 'Completed' || $booking->booking_status == 'Completed with Overdue') {
                            $highlightClass = 'completed';
                            if ($booking->booking_status == 'Completed')
                            {
                                if ($lang == 'eng')
                                    $status_text = 'COMPLETED';
                                else
                                    $status_text = 'عقد مغلق';
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
                        }

                        $from_city = custom::getFromToCityName($booking->city_from,$lang);
                        $to_city = custom::getFromToCityName($booking->city_to,$lang);
                        ?>

                        <div class="myBookingRow <?php echo $highlightClass; ?>">
                            <div class="topRow">
                                <h4>
                                    <?php echo '#'.$booking->reservation_code; ?>
                                    <span id="statusMsg_<?php echo $booking->id; ?>"><?php echo $status_text; ?></span>
                                </h4>
                                <h2><?php echo ($lang == 'eng' ? $booking->car_type_eng_title : $booking->car_type_arb_title) . ' ' . ($lang == 'eng' ? $booking->car_eng_title : $booking->car_arb_title) . ' ' . $booking->year; ?>
                                    <strong> <?php echo($lang == 'eng' ? $booking->car_category_eng_title : $booking->car_category_arb_title); ?> </strong>
                                </h2>
                            </div>
                            <div class="topOptions">
                                <div class="buttonsOpt">
                                    <a href="<?php echo $lang_base_url.'/print-booking/'.custom::encode_with_jwt($booking->reservation_code.'||EDxjrybEuppO'); ?>" target="_blank">
                                        <button class="grayishButton">
                                            <img src="<?php echo $base_url; ?>/public/frontend/images/print.png" alt="P" height="14" width="15"/>
                                            @lang('labels.print')
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <?php
                            if ($booking->image1 != '') {
                                $car_image_path = $base_url . '/public/uploads/' . $booking->image1;
                            } else {
                                $car_image_path = $base_url . '/public/frontend/images/no_image_available.jpg';
                            }
                            ?>
                            <a href="javascript:void(0);" class="bookingLink">
                                <div class="mBookingDTL">
                                    <div class="col imgBox">
                                        <div class="displayTable">
                                            <div class="disTableCell">
                                                <img src=<?php echo $car_image_path; ?> alt="Car" height="132" width="274"/>
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
                                                <?php echo custom::getCleanLocationName($booking->pickup_delivery_lat_long, 'short')."<br>(".$booking->pickup_delivery_lat_long.")"; ?>
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
                                                <?php echo custom::getCleanLocationName($booking->dropoff_delivery_lat_long, 'short')."<br>(".$booking->dropoff_delivery_lat_long.")"; ?>
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

                        <?php } }else{ ?>
                            <div class="noResultFound"><span>@lang('labels.no_record_found')</span></div>
                        <?php } ?>
                            </div>
                        <div id="new_records" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection