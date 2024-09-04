@extends('frontend.layouts.template')

@section('content')

    <?php

    $search_data = Session::get('search_data');
    $search_term = '';

    if (!isset($search_data['isLimousine'])) {
        $search_data['isLimousine'] = 0;
    }

    if (!isset($search_data['isRoundTripForLimousine'])) {
        $search_data['isRoundTripForLimousine'] = 0;
    }

    if ($search_data['isLimousine'] == 1) {
        $search_term = 'Limousine - ' . ($search_data['isRoundTripForLimousine'] == 1 ? 'Round Trip' : 'One Way');
    } elseif ($search_data['isLimousine'] == 0) {
        if ($search_data['is_delivery_mode'] == 0) {
            $search_term = 'Daily - ' . ($search_data['is_subscription_with_delivery_flow'] == 1 ? 'Delivery' : 'Pickup');
        } elseif ($search_data['is_delivery_mode'] == 1) {
            $search_term = 'Delivery - ' . ($search_data['is_subscription_with_delivery_flow'] == 1 ? 'Delivery' : 'Pickup');
        } elseif ($search_data['is_delivery_mode'] == 2) {
            $search_term = 'Hourly - ' . ($search_data['is_subscription_with_delivery_flow'] == 1 ? 'Delivery' : 'Pickup');
        } elseif ($search_data['is_delivery_mode'] == 3) {
            $search_term = 'Monthly - ' . ($search_data['is_subscription_with_delivery_flow'] == 1 ? 'Delivery' : 'Pickup');
        } elseif ($search_data['is_delivery_mode'] == 4) {
            $search_term = 'Subscription - ' . ($search_data['is_subscription_with_delivery_flow'] == 1 ? 'Delivery' : 'Pickup');
        } elseif ($search_data['is_delivery_mode'] == 5) {
            $search_term = 'Weekly - ' . ($search_data['is_subscription_with_delivery_flow'] == 1 ? 'Delivery' : 'Pickup');
        }
    }

    $event = 'search';
    $event_data = [
        'search_term' => $search_term
    ];
    custom::sendEventToGA4($event, $event_data);
    ?>

    <style>

        .subscription_options button.active strong {
            color: #FE7E00 !important;
        }

        <?php  if(!custom::is_mobile()){?>

           .bookPSec .subscription_options button{
            margin: 3px;
        }
        .arb.book-car section.bookingSec .singleRow .bookDtlSec .bookPSec .less-price .del-price:after {
            content: none;
        }
        .arb.book-car section.bookingSec .singleRow .bookDtlSec .bookPSec .less-price .del-price,
        .arb.book-car section.bookingSec .singleRow .bookDtlSec .bookPSec .less-price .del-price .sar_ar{
            text-decoration: line-through;
        }

        .share-and-earn-on-subscription-discount-btn a {
            background-image: url(<?php echo $base_url; ?>/public/frontend/images/network-icon.png);
        }
        body.arb .share-and-earn-on-subscription-discount-btn a {
            background-image: url(<?php echo $base_url; ?>/public/frontend/images/network-icon-arb.png);
        }
        .containsData.searchLoadMorePage {
            padding-bottom: 100px;
        }

        @media screen and (max-width: 991px) {
            .book-car .carsGrid .singleRow {
                width: 45%;
            }
            .arb.book-car .carsGrid .singleRow {
                width: 46%;
            }

        }
        @media screen and (max-width: 767px) {
            .book-car .new-header-style .topHeader .topLang a {
                font-size: 12px;
            }
        }
        @media screen and (max-width: 676px) {
            .book-car .carsGrid .singleRow,
            .arb.book-car .carsGrid .singleRow {
                width: 60%;
            }
        }
        @media screen and (max-width: 525px) {
            .book-car .carsGrid .singleRow,
            .arb.book-car .carsGrid .singleRow {
                width: 80%;
            }
        }
        @media screen and (max-width: 425px) {
            .book-car .carsGrid .singleRow,
            .arb.book-car .carsGrid .singleRow {
                width: 100%;
            }
        }

        <?php }  ?>
    </style>
    <?php
    $sessionVals = Session::get('search_data');
    $siteSettings = custom::site_settings();
    $isRangeOn = $siteSettings->date_range_mode == 'on' ? true : false;
    $startRange = date('d-m-Y', strtotime($siteSettings->start_date));
    $endRange = date('d-m-Y', strtotime($siteSettings->end_date));
    if ($isRangeOn) {
        $dateRangeHours = $startRange . ' - ' . $endRange . ': ' . $from_branch_info->opening_hours_date_range;
    } else {
        $dateRangeHours = '';
    }
    $is_delivery_mode = isset($sessionVals['is_delivery_mode'])?$sessionVals['is_delivery_mode']:0;
    ?>
    <section class="searchNbookSec">


        <div class="container-md <?php echo custom::addClass(); ?>">
            <div class="search-main-container-new-design">
                <?php echo custom::deliveryPickupTabsArea($lang);?>
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
                    <li class="active">
                        <div><span>01</span> @lang('labels.select_a_vehicle')</div>
                    </li>
                    <li>
                        <div><span>02</span> @lang('labels.price_n_extras')</div>
                    </li>
                    <li>
                        <div><span>03</span> @lang('labels.payment')</div>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="shotingLink">

                <div class="row">
                    <div class="col-lg-4">
                        <select class="select-box-style" onchange="main_search_with_filter($(this).val());">
                            <option value="0"><?php echo($lang == 'eng' ? 'ALL' : 'الكل'); ?></option>
                            <?php foreach ($categories as $category){ ?>
                            <option value="<?php echo $category->id; ?>"><?php echo($lang == 'eng' ? $category->eng_title : $category->arb_title); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <select class="select-box-style" onchange="main_search_with_filter($(this).val());">
                            <option disabled
                                    selected><?php echo($lang == 'eng' ? 'Price Filtering' : 'ترتيب بالسعر'); ?></option>
                            <option value="-1"><?php echo($lang == 'eng' ? 'Sort from lowest to highest price' : 'ترتيب من الارخص إلى الأغلى'); ?></option>
                            <option value="-2"><?php echo($lang == 'eng' ? 'Sort from highest to lowest price' : 'ترتيب من الأغلى إلى الأرخص'); ?></option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <div class="checkbox-available">
                            <label><?php echo($lang == 'eng' ? 'Show Available Cars Only?' : 'إظهار السيارات المتوفرة فقط؟'); ?></label>
                            <input type="checkbox" name="show_available_cars_only" class="form-check-input form-control">
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </section>
    <section class="bookingSec">
        <div class="container-md">
            <?php

            if ($pickup_time_is_ok == true && $dropoff_time_is_ok == true && $no_of_hours_are_fine == true && $difference_is_fine == true && $pickup_dropoff_are_ahead_of_current_time == true && $delivery_slots_are_ok == true && $monthly_date_diff_is_fine == true && $weekly_date_diff_is_fine == true){

            /*if (true){*/

            ?>
            <div class="containsData searchLoadMorePage search-new-box <?php echo ($siteSettings->website_cars_listing_style == 'grid' ? 'carsGrid' : 'carsList'); ?>">
                <?php if ($cars){
                    echo '<script>
                            show_loyalty_popup_in_booking = true;
                          </script>';
                    $noRecordStyle = "style='display:block;'";
                    echo custom::searchResultPageHtml($cars, $base_url, $lang_base_url, $lang);
                } else {
                $noRecordStyle = "style='display:none;'";
                ?>
                <?php if (isset($from_fleet_no_result) && $from_fleet_no_result == true){ ?>
                <div class="noResultFound"><span>@lang('labels.car_not_available')</span></div>
                <?php }else{ ?>
                <div class="noResultFound"><span>@lang('labels.no_record_found')</span></div>
                <?php } ?>
                <?php }?>
            </div>
            <?php if ($display_scroll_button == false) {
                echo '<script>
                            show_scroll_down = false;
                          </script>';
            } ?>
            <?php if ($display_scroll_button == true){ ?>
<!--            <div class="moreRecordsDiv" <?php echo $noRecordStyle; ?>>
                <button class="loadMore" onclick="loadMoreSearchCars();">@lang('labels.load_more')</button>
            </div>-->
            <?php } ?>

            <div class="noRecordFoundDiv" style="display: none;">
                <div class="noResultFound"><span>No more record found</span></div>
            </div>

            <?php } elseif ($pickup_dropoff_are_ahead_of_current_time == false){ ?>
            <div class="noResultFound">
                <span><?php echo($lang == 'eng' ? 'Pickup/Dropoff time should be ahead of current date/time.'
                        : 'وقت أو تاريخ الإستلام / التسليم يجب ان يكون بعد الوقت / التاريخ الحالي'); ?></span>
            </div>
            <?php } elseif ($no_of_hours_are_fine == false){ ?>
            <div class="noResultFound">
                <span><?php echo($lang == 'eng' ? 'Pickup time must be ' . $days_allowed . ' hour(s) ahead.'
                        : ' وقت/تاريخ الإستلام يجب ان يكون بعد ' . $days_allowed . ' ساعة من الوقت الحالي '); ?></span>
            </div>
            <?php }elseif ($difference_is_fine == false){ ?>
            <div class="noResultFound">
                <span><?php echo($lang == 'eng' ? 'The dropoff time should be ahead of pickup time.' : 'وقت/تاريخ التسليم يجب ان يكون بعد وقت/تاريخ الإستلام'); ?></span>
            </div>
            <?php } elseif ($pickup_time_is_ok == false && $dropoff_time_is_ok == true){ ?>
            <div class="noResultFound">
                <span><?php
                    echo($lang == 'eng' ?
                        'The pickup branch is close at the selected pickup time. <br> Working Hours: ' . $from_branch_info->opening_hours . ' <br> ' . $dateRangeHours . ' ' :
                        ' فرع الاستلام غير متوفر في الوقت المختار <br> ساعات العمل: ' . $from_branch_info->opening_hours . ' <br> ' . $dateRangeHours . ' '); ?></span>
            </div>
            <?php }elseif ($pickup_time_is_ok == true && $dropoff_time_is_ok == false){ ?>
            <div class="noResultFound">
                <span><?php echo($lang == 'eng' ? 'The dropoff branch is close at the selected dropoff time. <br> Working Hours: ' . $to_branch_info->opening_hours . ' <br> ' . $dateRangeHours . '' : ' فرع التسليم غير متوفر في الوقت المختار <br> ساعات العمل: ' . $to_branch_info->opening_hours . ''); ?></span>
            </div>

            <?php }elseif ($pickup_time_is_ok == false && $dropoff_time_is_ok == false){ ?>
            <div class="noResultFound">
                <span><?php echo($lang == 'eng' ? 'The pickup and dropoff branches are closed at the selected pickup and dropoff time. <br> Pickup Branch Working Hours: ' . $from_branch_info->opening_hours . ' <br> Dropoff Branch Working Hours: ' . $to_branch_info->opening_hours . ' <br> ' . $dateRangeHours . '  ' :
                        'فرع الاستلام و التسليم مغلق في الوقت المختار (ساعات العمل)'); ?></span>
            </div>
            <?php } else if ($delivery_slots_are_ok == false){ ?>
            <div class="noResultFound">
                <span><?php echo($lang == 'eng' ? $from_branch_info->eng_capacity_message : $from_branch_info->arb_capacity_message); ?></span>
            </div>
                <?php } else if ($monthly_date_diff_is_fine == false){ ?>
                <div class="noResultFound">
                    <span><?php echo $monthly_date_diff_is_fine_msg; ?></span>
                </div>
                <?php } else if ($weekly_date_diff_is_fine == false) { ?>
                <div class="noResultFound">
                    <span><?php echo $weekly_date_diff_is_fine_msg; ?></span>
                </div>
                <?php } ?>
        </div>
    </section>

    @if($siteSettings->refer_and_earn_option == 'on' && $is_delivery_mode == 4)
        @php $refer_and_earn_data = custom::refer_and_earn_data_simple($lang); @endphp
        <section class="share-and-earn-on-subscription-discount-section">
            <div class="share-and-earn-on-subscription-discount-btn">
                <a href="javascript:void(0);" id="share_and_earn_btn">
                    {{($lang == 'eng' ? 'Share & Earn on Subscription' : 'شارك و اكسب مع حجوزات الاشتراك')}}
                    <br /> {{$refer_and_earn_data['share_and_earn_button_amount_text']}}</a>
            </div>
        </section>
    @endif

    <!--To only run on the live website-->
    @if($_SERVER['HTTP_HOST'] == 'www.key.sa')
        <script>
            $('.loaderSpiner').show();

            setTimeout(function () {
                $('.loaderSpiner').hide();
            }, 2000);
        </script>
    @endif

@endsection