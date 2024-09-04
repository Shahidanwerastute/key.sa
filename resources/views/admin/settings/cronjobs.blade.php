@extends('admin.layouts.template')


@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="md-card">
                        <div class="md-card-content">
                            <h3 class="heading_a">Cronjobs <span style="float: right;"><img src="http://kra.ced.sa/public/admin/assets/images/ajax-loader.gif" alt="Loader" height="24" width="24" class="md-card-loader" id="cronjobLoader" style="display: none;"></span></h3>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/setDataCronJob" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Sync Bookings From Site Database To Oracle Database)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/getBookingStatusCronJob" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Sync Bookings Status From Oracle Database To Site Database)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/setCancelledBookingCollectionCronJob" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Sync Cancelled Bookings From Site Database To Oracle Database)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/updateStatusToExpiredCronJob" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Update And Sync Expired Bookings In Site & Oracle Database)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/stsTransactionInquiry?cronjob=1" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Update STS Payment Booking Status Via STS Inquiry API)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/stsInvoicesInquiry?cronjob=1" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Update STS Pay Later Booking Status Via STS Invoice Inquiry API)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/reverse_qitaf_for_temp_cancelled_expired_bookings_cronjob" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Reverse Qitaf Redeem Requests)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/hp_check_payment_status_cronjob" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Update Hyper Pay Payment Booking Status Via Hyper Pay Inquiry API)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/hb_check_invoice_status_cronjob" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Update Hyper Pay Pay Later Booking Status Via Hyper Pay Invoice Inquiry API)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/reverse_niqaty_for_temp_cancelled_expired_bookings_cronjob" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Reverse Niqaty Redeem Requests)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/sync_booking_added_payments_with_oasis_cronjob" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Sync Booking Added Payment With OASIS)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/check_app_popup_promo_codes"
                                                    class="md-btn md-btn-danger runCronJob">Run
                                            </button>
                                            <small style="margin-left: 20px;">(This Will Check App Popup Promo Codes And Clear Them For Further Usage)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/send_booking_email_and_pdf_to_customers"
                                                    class="md-btn md-btn-danger runCronJob">Run
                                            </button>
                                            <small style="margin-left: 20px;">(This Will Generate PDFs And Send Booking Emails)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/move_bookings_from_main_to_backup_tables"
                                                    class="md-btn md-btn-danger runCronJob">Run
                                            </button>
                                            <small style="margin-left: 20px;">(This Will Move Bookings From Main Tables To Backup Tables)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/reverse_mokafaa_for_temp_cancelled_expired_bookings_cronjob" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Reverse Mokafaa Redeem Requests)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/reverse_anb_for_temp_cancelled_expired_bookings_cronjob" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Reverse ANB Redeem Requests)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div class="uk-width-medium-1-1 uk-row-first">
                                            <button data-url="<?php echo custom::baseurl('/'); ?>/cronjob/subscribe_device_tokens_to_fcm_topic" class="md-btn md-btn-danger runCronJob">Run</button>
                                            <small style="margin-left: 20px;">(This Will Subscribe The Old/Existing FCM Tokens To Topic, <b>1000 tokens in 1 hit<b>)</small>
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
@endsection