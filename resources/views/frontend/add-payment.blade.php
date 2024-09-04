@extends('frontend.layouts.template')
@section('content')
    <section class="textBannerSec">
        <div class="container-md">
        </div>
    </section>
    <section class="myAccountSec">
        <div class="container-md">
            <div class="myAccountWrapper">
                <?php if (Session::get('logged_in_from_frontend') == true && $booking->type != 'guest')
                {
                if ($booking->type == "individual_customer")
                { ?>
                @include('frontend.layouts.profile_inner_section')
                <?php }elseif ($booking->type == "corporate_customer"){ ?>
                @include('frontend.layouts.corporate_profile_inner_section')
                <?php }
                } ?>
                <div class="myProfDetail addPaymentSec" style="display: none;">
                    <a href="{{$lang_base_url . '/add-payment?s=1&q=' . custom::encode_with_jwt($booking->id)}}">
                        <h3 class="addPaymentHeading"><?php echo($lang == 'eng' ? 'ADD PAYMENT' : 'إضافة الدفع'); ?>
                            <span>(<?php echo $booking->reservation_code; ?>)</span>
                        </h3>
                    </a>
                    @if(isset($_REQUEST['s']) && $_REQUEST['s'] == 1)
                        <div class="step-1">
                            <div class="addPaymentTabsSec">
 <ul class="nav nav-tabs" id="myTab" role="tablist">

                                    <?php if ($site_settings->show_pay_current_balance_option == 'yes') { ?>
                                        <li class="nav-item nav-item-pay-current-balance" role="presentation">
                                            <button class="nav-link active" onclick="$('#payment_type').val(1);" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                                                <?php echo($lang == 'eng' ? 'Pay Current Balance' : 'دفع الرصيد الحالي'); ?>
                                            </button>
                                        </li>
                                        <?php } ?>

                                        <?php if (($site_settings->show_pay_by_number_of_days_option == 'yes') && ($booking->booking_status != 'Completed with Overdue')) { ?>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" onclick="$('#payment_type').val(2);" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                                                <?php echo($lang == 'eng' ? 'Pay by Number of Days' : 'ادفع حسب عدد الأيام'); ?>
                                            </button>
                                        </li>
                                        <?php } ?>

                                </ul>


                                <div class="tab-content" id="myTabContent">
                                    <?php if ($site_settings->show_pay_current_balance_option == 'yes') { ?>
                                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <p class="addPaymentSubHeading"><?php echo($lang == 'eng' ? 'Your Current Pending Balance on Booking number' : 'رصيدك الحالي المعلق على رقم الحجز'); ?>
                                            (<?php echo $booking->reservation_code; ?>)</p>
                                        <div class="PayNowSec">
                                            <input type="text" value="<?php echo $balance_amount; ?>"
                                                   onkeypress="return isNumberKey(this, event);"
                                                   id="amount_to_pay_current_balance">
                                            <?php if ($balance_amount > 0) { ?>
                                            <button onclick="choose_payment_method();">
                                                <?php echo($lang == 'eng' ? 'Pay Now (SAR)' : 'ادفع الآن (ريال سعودي)'); ?>
                                            </button>

                                            <?php } ?>
                                        </div>
                                    </div>
                                        <?php } ?>

                                        <?php if (($site_settings->show_pay_by_number_of_days_option == 'yes') && ($booking->booking_status != 'Completed with Overdue')) { ?>
                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <p class="addPaymentSubHeading"><?php echo($lang == 'eng' ? 'Please Select the Number of Days you need to extend' : 'يرجى تحديد عدد الأيام التي تريد تمديدها'); ?></p>
                                        <div class="selectSecMain">
                                            <div class="selectSec">
                                                <div class="number-of-days">
                                                    <span class="minus">-</span>
                                                    <input type="text" class="form-control" value="1"
                                                           name="number_of_days"
                                                           data-booking_total_per_day="<?php echo $booking_total_per_day; ?>"
                                                           readonly/>
                                                    <span class="plus">+</span>
                                                </div>
                                            </div>
                                            <input class="txt-per-day" type="text"
                                                   value="<?php echo $booking_total_per_day; ?> <?php echo($lang == 'eng' ? 'SAR Per Day' : 'ريال سعودي في اليوم'); ?>"
                                                   readonly>
                                        </div>
                                        <div class="totalPaymentBtn"
                                             onclick="choose_payment_method();" style="cursor: pointer;">
                                            <div>
                                                <span><?php echo($lang == 'eng' ? 'Total Payment for' : 'المبلغ الإجمالي'); ?> </span>
                                                <span><span id="no_of_days">1</span> <?php echo($lang == 'eng' ? 'Days' : 'أيام'); ?> <span
                                                            id="amount_to_pay_by_days"><?php echo $booking_total_per_day; ?></span> <?php echo($lang == 'eng' ? 'SAR' : 'ريال سعودي'); ?></span>
                                            </div>
                                            <div><?php echo($lang == 'eng' ? 'Pay Now' : 'ادفع الآن'); ?></div>
                                        </div>
                                    </div>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="step-2" style="display: none;">
                            <div class="selectPaymenyMethodSec">
                                <p class="selectPaymentSubHeading"><?php echo($lang == 'eng' ? 'Please Select the Payment Method' : 'الرجاء تحديد طريقة الدفع'); ?></p>
                                <div class="new-form-design">
                                    <div class="payFrmUserInfo paymentMethods">
                                        <div class="paymentOption objects three">
                                            <ul>
                                                <?php if ($site_settings->mada == 1){ ?>
                                                <li class="p-method payment_options_divs">
                                                    <input id="CreditCardMada" name="payment_method" data-is-mada="1"
                                                           value="cc"
                                                           type="radio" checked>
                                                    <label for="CreditCardMada" style="display: block !important;">
                                                        <div class="imgBox">
                                                            <img src="<?php echo $base_url; ?>/public/frontend/images/icon-mada-logo.png"
                                                                 alt="Card">
                                                        </div>
                                                    </label>
                                                </li>
                                                <?php } ?>

                                                <?php if ($site_settings->cc == 1){ ?>
                                                <li class="p-method payment_options_divs">
                                                    <input id="CreditCard" name="payment_method" data-is-mada="0"
                                                           value="cc"
                                                           type="radio">
                                                    <label for="CreditCard">
                                                        <div class="imgBox">
                                                            <img src="<?php echo $base_url; ?>/public/frontend/images/icon-visa-logo.png"
                                                                 alt="Card">
                                                        </div>
                                                    </label>
                                                </li>
                                                <?php } ?>

                                                <?php if ($site_settings->amex == 1 && $site_settings->cc_company == 'hyper_pay'){ ?>
                                                <li class="p-method payment_options_divs">
                                                    <input id="CreditCardAmex" name="payment_method" data-is-mada="4"
                                                           value="cc"
                                                           type="radio">
                                                    <label for="CreditCardAmex">
                                                        <div class="imgBox">
                                                            <img src="<?php echo $base_url; ?>/public/frontend/images/amex.png?v=1.0"
                                                                 alt="Card" width="35" height="26">
                                                        </div>
                                                    </label>
                                                </li>
                                                <?php } ?>

                                                <?php if ($site_settings->stc_pay == 1 && $site_settings->cc_company == 'hyper_pay'){ ?>
                                                <li class="p-method payment_options_divs">
                                                    <input id="CreditCardSTCPay" name="payment_method" data-is-mada="3"
                                                           value="cc"
                                                           type="radio">
                                                    <label for="CreditCardSTCPay" style="display: block !important;">
                                                        <div class="imgBox">
                                                            <img src="<?php echo $base_url; ?>/public/frontend/images/stc-pay.png?v=1.0"
                                                                 alt="Card" width="35" height="26" style="width: 50px;">
                                                        </div>
                                                    </label>
                                                </li>
                                                <?php } ?>

                                                <?php if ($site_settings->apple_pay == 1 && $site_settings->cc_company == 'hyper_pay' && custom::is_ios_device()){ ?>
                                                <li class="p-method payment_options_divs">
                                                    <input id="CreditCardApplePay" name="payment_method"
                                                           data-is-mada="2"
                                                           value="cc"
                                                           type="radio">
                                                    <label for="CreditCardApplePay" style="display: block !important;">
                                                        <div class="imgBox">
                                                            <img src="<?php echo $base_url; ?>/public/frontend/images/apple-pay.png?v=0.1"
                                                                 alt="Card" width="35" height="26" style="width: 36px;">
                                                        </div>
                                                    </label>
                                                </li>
                                                <?php } ?>

                                                    @if($site_settings->show_pay_by_promo_code_in_extend_payment == 'yes')

                                                        <li class="p-method payment_options_divs" onclick="$('#couponModalonPayment').modal('show');" style="cursor: pointer;">

                                                                <div class="imgBox">
                                                                    <img src="<?php echo $base_url; ?>/public/frontend/images/coupon-icon.png"
                                                                         alt="Card" width="35" height="26"
                                                                         style="width: 50px;">
                                                                </div>
                                                        </li>
                                                    @endif

                                            </ul>
                                        </div>
                                    </div>
                                    <button class="btnGoToPayment"
                                            onclick="proceed_with_payment();"><?php echo($lang == 'eng' ? 'Go to Payment' : 'انتقل إلى الدفع') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input id="payment_type" value="" type="hidden">
                    @endif

                    @if(isset($_REQUEST['s']) && $_REQUEST['s'] == 3)
                        <div class="step-3">
                            <br>
                            <br>
                            <h1>
                                <?php echo($lang == 'eng' ? 'Payment Method' : 'الدفع'); ?>
                                <img src="<?php echo $base_url;?>/public/frontend/images/payment_method_icon_<?php echo $lang; ?>.png"
                                     style="width: 212px;margin-left: 10px;">
                            </h1>

                            @if (Session::get('add_payment.is_mada') == 2)
                                <script>
                                    var wpwlOptions = {
                                        paymentTarget: "_top",
                                        applePay: {
                                            displayName: "Key Car Rental",
                                            total: {label: "Key Car Rental"}
                                        }
                                    }
                                </script>
                            @else
                                <script>
                                    var wpwlOptions = {
                                        locale: "<?php echo($lang == 'eng' ? 'en' : 'ar'); ?>",
                                        paymentTarget: "_top",
                                        numberFormatting: <?php echo($lang == 'eng' ? 'true' : 'false'); ?>,
                                        autofocus: 'card.number',
                                        onReady: function () {
                                            $('.wpwl-button-pay').html('<?php echo Lang::get('labels.pay_now') . ' ' . number_format(Session::get('add_payment.amount'), 2) . ' ' . Lang::get('labels.currency_text');  ?>');
                                            $('.wpwl-button-pay').closest(".wpwl-wrapper-submit").css({'margin-top': '20px'});
                                            $('.wpwl-button-pay').css({
                                                'border-color': '#FE7E00',
                                                'background-color': '#FE7E00'
                                            });
                                            $('.wpwl-label-brand').css({'display': 'none'});
                                            $('.wpwl-wrapper-brand').css({'display': 'none'});
                                            $('.wpwl-control-cardHolder, .wpwl-control-expiry').addClass('hp_form_field');
                                        }
                                    }
                                </script>
                            @endif
                            <script src="{{rtrim($api_settings->hyper_pay_endpoint_url, '/')}}/v1/paymentWidgets.js?checkoutId={{Session::get('add_payment.checkout_id')}}"></script>
                            <form action="{{$lang_base_url.'/add-payment?s=4'}}" class="paymentWidgets"
                                  data-brands="{{custom::entity_id(Session::get('add_payment.is_mada'), $api_settings, true)}}"></form>
                        </div>
                    @endif

                    @if(isset($_REQUEST['s']) && $_REQUEST['s'] == 5)
                        <div class="step-4">
                            <div class="thankuMsgSec">
                                <img src="<?php echo $base_url; ?>/public/frontend/images/ico-hand.png">
                                <?php if ($lang == 'eng') { ?>
                                <p>Payment <span><?php echo $booking_added_payment->amount; ?> SAR</span> received for
                                    booking <?php echo $booking->reservation_code; ?></p>
                                <?php }else { ?>
                                <p>تم استلام <span><?php echo $booking_added_payment->amount; ?></span> ريال سعودي
                                    للحجز <?php echo $booking->reservation_code; ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- coupon modal code start -->
    <div class="modal fade couponModalonPayment" id="couponModalonPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="coupon_code_for_add_payment"
                           placeholder="{{$lang == 'eng' ? 'Coupon Code' : 'رمز الكوبون'}}" class="mb-3"/>
                    <div class="twoBtnEd">
                        <input type="button" class="redishButtonRound" id="applyCouponForAddPayment"
                               value="{{$lang == 'eng' ? 'APPLY' : 'يتقدم'}}"/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- coupon modal code end -->

    <script>
        $(document).ready(function () {

            $('.nav-tabs a:first').click();
            setTimeout(function() {
                if ($('.step-1').find('.nav-tabs').find('li').length == 1) {
                    $('.step-1').find('.nav-tabs').remove();
                }
                $('.addPaymentSec').show();
            }, 500);

            <?php if (session()->has('jc-message')) { ?>
            $.alert({
                title: 'Alert!',
                content: '<?php echo session()->get('jc-message'); ?>',
            });
            <?php
            session()->forget('jc-message');
            session()->save();
            ?>
            <?php } ?>

        });

        $(document).on('change', 'input[name="number_of_days"]', function () {
            $('#no_of_days').text($(this).val());
            $('#amount_to_pay_by_days').text(($(this).data('booking_total_per_day') * $(this).val()).toFixed(2));
        });

        function choose_payment_method() {
            var payment_type = $('#payment_type').val();
            var amount = (payment_type == 1 ? $('#amount_to_pay_current_balance').val() : $('#amount_to_pay_by_days').text());

            if (amount > 0) {
                $('.step-1').hide();
                $('.step-2').show();
            } else {
                $.alert({
                    title: 'Alert!',
                    content: '<?php echo($lang == 'eng' ? 'Amount must be greater than 0 to proceed!' : 'يجب أن يكون المبلغ أكبر من 0 للمتابعة!'); ?>',
                });
            }
        }

        function proceed_with_payment() {

            var payment_type = $('#payment_type').val();
            var amount = (payment_type == 1 ? $('#amount_to_pay_current_balance').val() : $('#amount_to_pay_by_days').text());

            if (amount > 0) {
                $('.loaderSpiner').show();
                $.ajax({
                    type: 'GET',
                    url: lang_base_url + '/add-payment?s=2',
                    data: {
                        'booking_id': '<?php echo $booking->id; ?>',
                        'number_of_days': (payment_type == 1 ? "" : $('input[name="number_of_days"]').val()),
                        'amount': amount,
                        'payment_method': $('input[name="payment_method"]:checked').val(),
                        'is_mada': $('input[name="payment_method"]:checked').data('is-mada'),
                    },
                    async: false,
                    dataType: 'JSON',
                    success: function (response) {
                        window.location.href = lang_base_url + '/add-payment?s=3';
                    }
                });
            } else {
                $.alert({
                    title: 'Alert!',
                    content: '<?php echo($lang == 'eng' ? 'Amount must be greater than 0 to proceed!' : 'يجب أن يكون المبلغ أكبر من 0 للمتابعة!'); ?>',
                });
            }
        }

        $(document).ready(function () {
            $('.minus').click(function () {
                var $input = $(this).parent().find('input');
                var count = parseInt($input.val()) - 1;
                count = count < 1 ? 1 : count;
                $input.val(count);
                $input.change();
                return false;
            });
            $('.plus').click(function () {
                var $input = $(this).parent().find('input');
                if ($input.val() < 30) {
                    $input.val(parseInt($input.val()) + 1);
                    $input.change();
                    return false;
                }
            });
        });

        function isNumberKey(txt, evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 46) {
                //Check if the text already contains the . character
                if (txt.value.indexOf('.') === -1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (charCode > 31 &&
                    (charCode < 48 || charCode > 57))
                    return false;
            }
            return true;
        }

        $(document).on('click', '#applyCouponForAddPayment', function() {
            var coupon_code_for_add_payment = $('#coupon_code_for_add_payment').val();
            var payment_type = $('#payment_type').val();
            if (coupon_code_for_add_payment) {
                $('.loaderSpiner').show();
                $('#applyCouponForAddPayment').attr('disabled', true);
                $.ajax({
                    type: 'GET',
                    url: lang_base_url + '/applyCouponForAddPayment',
                    data: {
                        'booking_id': '<?php echo $booking->id; ?>',
                        'coupon_code_for_add_payment': coupon_code_for_add_payment
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.status == true) {
                            $.ajax({
                                type: 'GET',
                                url: lang_base_url + '/add-payment?s=6',
                                data: {
                                    'booking_id': '<?php echo $booking->id; ?>',
                                    'number_of_days': (payment_type == 1 ? "" : $('input[name="number_of_days"]').val()),
                                    'amount': response.amount,
                                    'payment_method': 'PROMOCODE',
                                    'coupon_code_for_add_payment': coupon_code_for_add_payment
                                },
                                async: false,
                                dataType: 'JSON',
                                success: function (response) {
                                    $('.loaderSpiner').hide();
                                    $('#applyCouponForAddPayment').attr('disabled', false);
                                    $('#couponModalonPayment').modal('hide');
                                    $.alert('<?php echo ($lang == 'eng' ? 'Payment added successfully!' : 'تمت إضافة الدفع بنجاح!'); ?>');
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 5000);
                                }
                            });
                        } else {
                            $('.loaderSpiner').hide();
                            $('#applyCouponForAddPayment').attr('disabled', false);
                            $.alert({
                                title: '<?php echo ($lang == 'eng' ? 'Alert!' : 'يُحذًِر!'); ?>',
                                content: '<?php echo ($lang == 'eng' ? 'Invalid coupon code!' : 'رقم قسيمه غير صالح!'); ?>',
                            });
                        }
                    }
                });
            } else {
                $('.loaderSpiner').hide();
                $('#applyCouponForAddPayment').attr('disabled', false);
                $.alert({
                    title: '<?php echo ($lang == 'eng' ? 'Alert!' : 'يُحذًِر!'); ?>',
                    content: '<?php echo ($lang == 'eng' ? 'Coupon code required!' : 'رمز القسيمة مطلوب!'); ?>',
                });
            }
        });
    </script>
@endsection