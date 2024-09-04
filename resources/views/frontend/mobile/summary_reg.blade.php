@extends('frontend.layouts.template')
@section('content')

    <?php
    $items = [];
    $item_data = [
        'item_id' => $car_info->car_type_eng_title . " " . $car_info->eng_title . ' ' . $car_info->year,
        'item_name' => $car_info->car_type_eng_title . " " . $car_info->eng_title . ' ' . $car_info->year
    ];
    $items[] = $item_data;
    $event = 'purchase';
    $event_data = [
        'transaction_id' => $booking_details->reservation_code,
        'currency' => 'SAR',
        'value' => Session::get('total_amount_for_transaction'),
        'items' => $items
    ];
    custom::sendEventToGA4($event, $event_data);
    ?>

    <style>
        body.arb .printBtn .edBtn {
            background-position: 85% center;
        }
    </style>
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
    <?php
    if (isset($booking_payment_details) && $booking_payment_details->redeem_discount_availed > 0){
        $redeem_discount = $booking_payment_details->redeem_discount_availed;
    }else{
        $redeem_discount = 0;
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

    $site_settings = custom::site_settings();
    if ((Session::get('search_data')['is_delivery_mode'] == 1 || Session::get('search_data')['is_subscription_with_delivery_flow'] == 1) && Session::get('search_data')['delivery_charges'] > 0) {
        $delivery_charges = (float)Session::get('search_data')['delivery_charges'];
    } else {
        $delivery_charges = 0;
    }

    $parking_fee = Session::get('search_data')['parking_fee'];
    $tamm_charges_for_branch = Session::get('search_data')['tamm_charges_for_branch'];
    $dropoff_charges = Session::has('dropoff_charges_amount') && Session::get('dropoff_charges_amount') > 0 ? Session::get('dropoff_charges_amount') : 0;
    $qitaf_amount = Session::has('qitaf_amount') ? Session::get('qitaf_amount') : 0;
    $niqaty_amount = Session::has('niqaty_amount') ? Session::get('niqaty_amount') : 0;

    if ($booking_info['is_delivery_mode'] == 4) {
        $booking_info['days'] = 30; // because 1 month is to be charged
    }

    if (Session::get('minus_discount') == true) {
        $payable_amount = ((Session::get('rent_per_day') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) - ($pre_total_discount * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']))) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges + $parking_fee + $tamm_charges_for_branch + $delivery_charges;
    } else {
        $payable_amount = (Session::get('rent_per_day') * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor) + $dropoff_charges + $parking_fee + $tamm_charges_for_branch + $delivery_charges;
    }

    ?>
    <section class="pricePageSec">
        <div class="container">
            <div class="pricePgWrapper ">
                <div class="leftCol">
                    <?php if ($UserExistWithEmail == true || Session::get('logged_in_from_frontend') == true){ ?>
                    <div class="reservation-message">
                        <div class="booking-detail">
                            <h3>
                                @lang('labels.your_payment')
                                <?php if(isset($booking_invoice_details) && $method_of_payment == 'Paylater'){ ?>
                                <span>@lang('labels.is_pending')</span>
                                <?php }else{ ?>
                                <span>@lang('labels.processed')</span>
                                <?php } ?>
                            </h3>
                            <strong class="title">#<?php echo $booking_details->reservation_code; ?></strong>
                            <?php if(isset($booking_invoice_details) && $method_of_payment == 'Paylater'){ ?>
                            <p>
                                <?php if($lang == "eng"){ ?>
                                Reservation has been received and an invoice is sent to you via email and sms. The invoice will expire in <?php echo $expiry_period; ?> hours.
                                <?php }else{ ?>
                                الحجز، ستصلك الفاتورة عبر البريد الإلكتروني تنتهي صلاحيتها خلال <?php echo $expiry_period; ?> ساعات.
                                <?php } ?>
                            </p>
                            <?php }else{ ?>
                            <p>
                                <?php if($lang == "eng"){ ?> Reservation has been received. You will shortly receive a confirmation via email and sms.
                                <?php }else{ ?>
                                تم استلام الحجز سوف تتلقى قريبا تأكيدا عبر البريد الإلكتروني و رسالة نصية.
                                <?php } ?>
                            </p>
                            <?php } ?>
                            <?php if ($UserExistWithEmail != true && Session::get('logged_in_from_frontend') != true){ ?>
                            <h3 class="resNo">
                                @if($lang == "eng")Your Reservation: @else الحجز الخاص بك :@endif
                                <span><?php echo $booking_details->reservation_code; ?></span>
                            </h3>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="booking-details">
                    	<div class="detail-holder">
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
                                    <span class="totalPriceWithVat"><?php echo number_format($payable_amount + Session::get('vat') - $qitaf_amount - $niqaty_amount - $redeem_discount - $post_total_discount, 2); ?></span> @lang('labels.currency')</h3>
							</div>
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
											<?php echo date('d M Y', strtotime($booking_info['pickup_date'])).', '.$booking_info['pickup_time']; ?>
										</li>
										<li title="JEDDAH AIRPORT, NORTH TERMINAL">
											<?php echo ($lang == 'eng' ? $pickup_branch_info->eng_title : $pickup_branch_info->arb_title); ?>
										</li>
										<li>
											<?php echo ($lang == 'eng' ? $pickup_branch_info->city_eng_title : $pickup_branch_info->city_arb_title); ?>
										</li>
									</ul>
								</div>
								<div class="col twoBig peddLftSet">
									<label>@lang('labels.drop_off')</label>
									<ul>
										<li>
											<?php echo date('d M Y', strtotime($booking_info['dropoff_date'])).', '.$booking_info['dropoff_time']; ?>
										</li>
										<li title="JEDDAH AIRPORT, NORTH TERMINAL">
											<?php
											echo ($lang == 'eng' ? $dropoff_branch_info->eng_title : $dropoff_branch_info->arb_title);
											?>
										</li>
										<li>
											<?php echo ($lang == 'eng' ? $dropoff_branch_info->city_eng_title : $dropoff_branch_info->city_arb_title); ?>
										</li>
									</ul>
								</div>
							</div>
                   		</div>
                    </div>
                    <div class="col bookFeature peddLftSet <?php echo($car_info->min_age > 0 ? 'contains-min-age' : ''); ?>">
                        <label>@lang('labels.features')</label>
                        <ul>
							<li>
								<div class="spIconF person"></div>
								<p><?php echo $car_info->no_of_passengers; ?></p></li>
							<li>
								<div class="spIconF transmition"></div>
								<p><?php echo($car_info->transmission == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي')); ?></p>
							<li>
								<div class="spIconF door"></div>
								<p>
								<p><?php echo $car_info->no_of_doors; ?></p></li>
							<li>
								<div class="spIconF bag"></div>
								<p><?php echo $car_info->no_of_bags; ?></p></li>
							<?php if ($car_info->min_age > 0)
							{ ?>
							<li>
								<div class="spIconF minAge"></div>
								<p><?php echo $car_info->min_age; ?></p>
							</li>
							<?php } ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="totalWdisValu add peddLftSet">
                        <ul>
                            <li>
                                <h4>@lang('labels.total_rent_for_capital')
                                    @if ($booking_info['is_delivery_mode'] == 2)
                                        <?php echo $booking_info['hours_diff']; ?> @lang('labels.hours')
                                        @elseif ($booking_info['is_delivery_mode'] == 1)
                                        1 @lang('labels.months')
                                    @else
                                        <?php echo $booking_info['days']; ?> @lang('labels.days')
                                    @endif
                                </h4>
                                <span><?php echo number_format(((Session::get('rent_per_day')) * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days'])) + ($cdw * $cdw_multiply_factor) + ($cdw_plus * $cdw_plus_multiply_factor) + ($gps * $gps_multiply_factor) + ($extra_driver * $extra_driver_multiply_factor) + ($baby_seat * $baby_seat_multiply_factor), 2); ?> @lang('labels.currency')</span>
                            </li>
                            <?php if (isset($booking_payment_details) && $booking_payment_details->redeem_discount_availed > 0){ ?>
                            <li>
                                <h4>Discount availed on redeem</h4>
                                <span><?php echo $booking_payment_details->redeem_discount_availed; ?> <?php echo ($lang == 'eng' ? 'SAR' : 'ريال سعودي'); ?></span>
                            </li>
                            <?php } ?>
                            <?php if ($pre_total_discount != '' && $pre_total_discount > 0){ ?>
                            <li class="discount_on_promo_auto">
                                <h4>@lang('labels.discount_on_promo')</h4>
                                <span class="discount"><?php echo number_format($pre_total_discount * ($booking_info['is_delivery_mode'] == 2 ? 1 : $booking_info['days']), 2); ?> @lang('labels.currency')</span>
                            </li>
                            <?php } ?>
                            <li class="discount_on_promo_code" style="display: none;">
                                <h4>@lang('labels.discount_on_promo_code')</h4>
                                <span class="discount">300 @lang('labels.currency')</span>
                            </li>

                            <?php if (Session::get('vat') > 0){ ?>
                            <li>
                                <h4>@lang("labels.vat_applicable") (<?php echo Session::get('vat_percentage'); ?> %)</h4>
                                <span><?php echo number_format(Session::get('vat'), 2); ?> <?php echo ($lang == 'eng' ? 'SAR' : 'ريال سعودي'); ?></span>
                            </li>
                            <?php } ?>

                            <li>
                                <h4>@lang('labels.you_pay_total')</h4>
                                <span class="total_amount">
                                    <?php echo number_format($payable_amount+Session::get('vat') - $qitaf_amount - $niqaty_amount - $post_total_discount, 2); ?>
                                    @lang('labels.currency')
                                </span>
                            </li>

                            <?php if ($post_total_discount != '' && $post_total_discount > 0){ ?>
                            <li class="discount_on_promo_auto">
                                <h4>@lang('labels.discount_on_promo')</h4>
                                <span class="discount"><?php echo number_format($post_total_discount, 2); ?> @lang('labels.currency')</span>
                            </li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>
                <div class="rightCol">
                    <div class="btn-holder">
                        <?php
                        /*$user_type = Session::get("user_type");
                        if ($user_type == "corporate_customer") {
                            $user_data = $this->page->getSingle('corporate_customer', array('id' => Session::get('corporate_customer_id')));
                        } else {
                            $user_data = "";
                        }*/
                        ?>
                        <a href="<?php echo $lang_base_url . '/print-booking/' . custom::encode_with_jwt($booking_details->reservation_code.'||EDxjrybEuppO'); ?>" data-booking-id="<?php echo $booking_details->id; ?>" class="edBtn printBtn printReceipt" target="_blank">
                            @lang('labels.print')
                        </a>
                        <!--<a href="#" data-booking-id="<?php //echo $booking_details->id; ?>" class="edBtn shareBtn printReceipt" target="_blank">
                            <?php //echo($lang == 'eng' ? 'Share' : 'شارك'); ?>
                        </a>-->
                    </div>
                    <div class="sumRegArea">
                        <!-- Form Start -->
                        <form action="<?php echo $lang_base_url; ?>/save_extra_infor_after_reservation"
                              class="save_extra_infor_after_reservation" method="post" onsubmit="return false;">
                            <?php if ($UserExistWithEmail != true && Session::get('logged_in_from_frontend') != true)
                            { ?>
                            <div class="checkBoxFun">
                                <div class="absCheckBox" id="checkTwo">
                                    <input id="checkbox2" class="want_to_register" name="want_to_register" value="1"
                                           type="checkbox"><label
                                            for="checkbox2">&nbsp;</label>
                                </div>
                                <p>
                                    @if($lang == "eng") Enter Password to <strong>Register</strong> @else  أادخل رقم سري
                                    لإنشاء حساب@endif<br/>
                                    {{--@if($lang == "eng") your account based on all the filled information. @else حسابك على أساس جميع المعلومات شغلها. @endif--}}
                                    @if($lang == "eng") One Step to create your account with Key. @else خطوة واحدة
                                    لإنشاء حساب في موقع المفتاح. @endif
                                </p>
                            </div>
                            <div class="sumRegFrmSec grayScale" id="formTwo">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>@lang('labels.password')</label>
                                        <input class="required-second" placeholder="WRITE HERE" type="password"
                                               name="password">
                                    </div>
                                    <div class="col-sm-6">
                                        <label>@lang('labels.confirm_password')</label>
                                        <input class="required-second" placeholder="WRITE HERE" type="password"
                                               name="confirm_password">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 btnsSec">
                                <input type="submit" class="edBtn grayishButton license_validate_btn"
                                       value="<?php echo($lang == 'eng' ? 'Submit' : 'إنشاء'); ?>"
                                       id="btn-submit" disabled/>
                                <a href="<?php echo $lang_base_url; ?>/home"><input type="button"
                                                                                    class="edBtn grayishButton"
                                                                                    value="<?php echo($lang == 'eng' ? 'No Thanks' : 'تجاهل'); ?>"/></a>
                            </div>
                            <?php } ?>

                            <div class="checkBoxFun" style="display: none;">
                                <div class="absCheckBox" id="checkOne">
                                    <input id="checkbox1" class="avoid_waiting" name="avoid_waiting" value="1"
                                           type="checkbox"><label
                                            for="checkbox1">&nbsp;</label>
                                </div>
                                <p>
                                    <strong>@if($lang == "eng")Avoid @else تجنب @endif</strong> @if($lang == "eng")
                                        waiting at the pickup. @else انتظار، إلى، ال التعريف، بيك اب.@endif<br/>
                                    @if($lang == "eng")Fill up the information now @else املأ المعلومات الآن @endif
                                    <span>(Optional)</span>:
                                </p>
                            </div>
                            <div class="sumRegFrmSec grayScale" id="formOne" style="display: none;">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>@lang('labels.job_title') *</label>
                                        <select name="job_title" class="required-first">
                                            <option value="" selected>@lang('labels.select')</option>
                                            <?php
                                            foreach ($job_titles as $job_title)
                                            { ?>
                                            <option value="<?php echo $job_title->oracle_reference_number; ?>"><?php echo($lang == 'eng' ? $job_title->eng_title : $job_title->arb_title); ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>@lang('labels.sponsor') *</label>
                                        <input type="text" placeholder="@lang('labels.write')" name="sponsor"
                                               class="required-first"/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>@lang('labels.street_address') *</label>
                                        <input type="text" placeholder="@lang('labels.write')" name="street_address"
                                               class="required-first"/>
                                    </div>

                                    <div class="col-sm-6">
                                        <label>@lang('labels.district_address') *</label>
                                        <input type="text" placeholder="@lang('labels.write')" name="district_address"
                                               class="required-first"/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>@lang("labels.id_expiry") *</label>
                                        <div class="sub-row">
                                            <select id="selectCalendar" class="selectpicker required-first"
                                                    name="id_date_type">
                                                <option value="gregorian">@lang('labels.gregorian')</option>
                                                <option value="islamic">@lang('labels.islamic')</option>
                                            </select>
                                            <div class="id_expiry_date_field">
                                                <input type="text" class="custom_calendar required-first"
                                                       name="id_expiry_date"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>@lang('labels.id_country') *</label>
                                        <select name="id_country" class="required-first id_country">
                                            <option value="" selected>@lang('labels.select')</option>
                                            <?php
                                            foreach ($countries as  $country)
                                            {
                                            ?>
                                            <option value="<?php echo $country->oracle_reference_number; ?>"><?php echo($lang == 'eng' ? $country->eng_country : $country->arb_country); ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-6">
                                        <label>@lang('labels.date_of_birth')</label>
                                        <input type="text" class="calender required-first" placeholder="SELECT"
                                               name="dob"/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>@lang('labels.driving_license_number')</label>
                                        <input type="text" class="required-first" placeholder="WRITE HERE"
                                               name="license_no"/>
                                    </div>

                                    <div class="col-sm-6">
                                        <label>@lang('labels.driving_license_expiry_date')</label>
                                        <input type="text" class="datepicker_future_date required-first"
                                               placeholder="SELECT"
                                               name="license_expiry_date"/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>@lang('labels.license_country') *</label>
                                        <select name="license_country" class="required-first license_country">
                                            <option value="" selected>@lang('labels.select')</option>
                                            <?php
                                            foreach ($countries as  $country)
                                            { ?>
                                            <option value="<?php echo $country->oracle_reference_number; ?>"><?php echo($lang == 'eng' ? $country->eng_country : $country->arb_country); ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-6">
                                        <label>@lang('labels.copy_of_id_card')
                                            <span style="font-size: 85%;">&nbsp;(<?php echo($lang == 'eng' ? 'Only Images or PDF are allowed' : 'تقبل فقط صور و PDF'); ?>
                                                )</span></label>
                                        <div class="fileUploader">
                                            <input type="text" class="showFileName" placeholder="UPLOAD IMAGE" disabled>
                                            <input type="button" class="edBtn showFileType" value="UPLOAD"/>
                                            <input type="file" class="edBtn attachFile required-first id_image"
                                                   name="id_image" accept="application/pdf, image/*">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label>@lang('labels.copy_of_driving_license')
                                            <span style="font-size: 85%;">&nbsp;(<?php echo($lang == 'eng' ? 'Only Images or PDF are allowed' : 'تقبل فقط صور و PDF'); ?>
                                                )</span></label>
                                        <div class="fileUploader">
                                            <input type="text" class="showFileName" placeholder="UPLOAD IMAGE" disabled>
                                            <input type="button" class="edBtn showFileType" value="UPLOAD"/>
                                            <input type="file" class="edBtn attachFile required-first license_image"
                                                   name="license_image" accept="application/pdf, image/*">
                                        </div>
                                    </div>
                                    <?php
                                    if (Session::get('payment_form_data')['id_type'] == '243') { ?>
                                    <input type="hidden" name="nationality" value="16">
                                    <?php } else { ?>
                                    <div class="col-sm-6 nationality_container">
                                        <label>@lang('labels.nationality')</label>
                                        <select name="nationality" class="required-first nationality">
                                            <option value="" selected>Select</option>
                                            <?php
                                            foreach ($nationalities as $nationality)
                                            { ?>
                                            <option value="<?php echo $nationality->oracle_reference_number; ?>"><?php echo($lang == 'eng' ? $nationality->eng_country_name : $nationality->arb_country_name); ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <?php }
                                    ?>

                                </div>
                            </div>

                        </form>
                        <!-- Form End -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="acRegistered" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title" id="myModalLabel">@lang('labels.success')</h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <p>@if($lang == "eng") Your email has been registered as your account. @else تم تسجيل بريدك
                        الإلكتروني كحسابك.  @endif</p>
                    <div class="twoBtnEd">
                        <br/>
                        <input type="submit" class="redishButtonRound" value="@lang('labels.close')"
                               data-bs-dismiss="modal"/>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $.ajax({
            type: 'GET',
            url: base_url + '/cronjob/setDataCronJob',
            success: function (response) {

            }
        });

        $("input[name='id_no_for_loyalty']").val("");
    </script>
    <?php if (isset($guest_user_id_no))
    { ?>
    <script>
        $.get(base_url + '/cronjob/loyaltySyncCronJob?from_mobile=1&user_id_no=<?php echo $guest_user_id_no; ?>', function (data) {
        });
    </script>
    <?php } ?>

    <?php
    if (Session::get('logged_in_from_frontend') == true) {
        custom::clearAllSessionsFromCheckout();
    } else {
        custom::clearSessionsFromCheckoutForGuest();
    }
    ?>
@endsection