@extends('frontend.layouts.template')
@section('content')
    <style>
        input.backCalendar {
            background-position: <?php echo ($lang == 'arb' ? 'left' : 'right'); ?> center;
            background-repeat: no-repeat;
        }
        .backClock {
            height: 33px;
            width: 100%;
        }
        .editSubmit {
            transition: all 0.5s ease-in;
            background: #FE7E00;
            -webkit-box-shadow: 0 4px 4px rgba(248, 172, 25, 0.4);
            box-shadow: 0 4px 4px rgba(248, 172, 25, 0.4);
            color: #ffffff;
            cursor: pointer;
            font-size: 10px;
            font-weight: 400;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border: none;
            margin: 8px 0 25px;
        }
        .editSubmit:hover{
            background: #6D6E71;
        }
    </style>
    <section class="myAccountSec">
        <?php if (Session::get("user_type") == "individual_customer")
        { ?>
        @include('frontend.mobile.layouts.profile_inner_section')
        <?php }elseif (Session::get("user_type") == "corporate_customer"){ ?>
        @include('frontend.mobile.layouts.corporate_profile_inner_section')
        <?php } ?>
        <div class="container">
            <div class="myAccountWrapper">
                <div class="myProfDetail editBookingSec">
                    <h1><strong>@lang('labels.edit') </strong> @lang('labels.booking_single')</h1>
                    <div class="bookingDetailSec">
                        <div class="row">
                            <div class="col-md-12">
                                <label>@lang('labels.pickup_schedule')</label>
                                <br>
                                <small>@lang('labels.edit_booking_msg')</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" class="backCalendar required-for-search" placeholder="" name="pickup_date"
                                       id="datepicker_from"
                                       value="<?php echo date('d-m-Y', strtotime($booking_detail->from_date)); ?>"/>
                            </div>
                            <div class="col-md-2">
                                <select id="pickUpTime" name="pickup_time" class="backSandGrayPlus backClock required-for-search">
                                    <?php
                                    $start=strtotime('00:00');
                                    $end=strtotime('23:30');
                                    for ($i=$start;$i<=$end;$i = $i + 30*60){
                                    $timeInterval = date('H:i',$i);
                                    ?>
                                    <option value="<?php echo $timeInterval; ?>" <?php echo (date('H:i', strtotime($booking_detail->from_date)) == $timeInterval ? 'selected' : ''); ?>><?php echo $timeInterval; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="booking_id" value="<?php echo $booking_detail->id; ?>">
                            <div class="col-md-3" style="text-align: center;">
                                <button class="editSubmit" onclick="edit_booking();">@lang('labels.submit_btn')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection