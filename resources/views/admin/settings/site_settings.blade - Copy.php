@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    {{--Site Settings--}}
                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/site_settings" method="post"
                              class="settings_ajax_form" enctype="multipart/form-data" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Site Settings</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Site Title</label>
                                                    <input type="text" class="md-input" name="site_title"
                                                           value="<?php echo $site->site_title; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Site Phone</label>
                                                    <input type="text" class="md-input" name="site_phone"
                                                           value="<?php echo $site->site_phone; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Admin Email</label>
                                                    <input type="text" class="md-input" name="admin_email"
                                                           value="<?php echo $site->admin_email; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Admin Phone</label>
                                                    <input type="text" class="md-input" name="admin_phone"
                                                           value="<?php echo $site->admin_phone; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>

                                <!--
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Booking Email</label><input type="text"
                                                                                                                                 class="md-input" name="booking_email" value="<?php echo $site->booking_email; ?>"><span
                                                            class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Forgot Password Email</label><input
                                                            type="text" class="md-input" name="forgot_password_email" value="<?php echo $site->forgot_password_email; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Contact Us Email</label><input
                                                            type="text" class="md-input" name="contact_us_email" value="<?php echo $site->contact_us_email; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Career Form Email</label><input
                                                            type="text" class="md-input" name="careers_email" value="<?php echo $site->careers_email; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Registration Form Email</label><input
                                                            type="text" class="md-input" name="registration_email" value="<?php echo $site->registration_email; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    -->


                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"
                                                     title="Increase this number whenever a new IOS build is uploaded to IOS app store so user always have to download latest build">
                                                    <label>IOS App Version</label><input
                                                            type="number" class="md-input" name="ios_version"
                                                            value="<?php echo $site->ios_version; ?>"><span
                                                            class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"
                                                     title="Increase this number whenever a new android build is uploaded to google play store so user always have to download latest build">
                                                    <label>Android App Version</label><input
                                                            type="number" class="md-input" name="android_version"
                                                            value="<?php echo $site->android_version; ?>"><span
                                                            class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Booking
                                                        cancellation in hours</label>
                                                <!--<input type="text" class="md-input" name="cancel_in_hours" value="<?php echo $site->cancel_in_hours; ?>">-->
                                                    <select class="md-input" name="cancel_in_hours">
                                                        <option value="">Select</option>
                                                        <option value="1" <?php echo($site->cancel_in_hours == "1" ? "selected" : ""); ?>>
                                                            1
                                                        </option>
                                                        <option value="2" <?php echo($site->cancel_in_hours == "2" ? "selected" : ""); ?>>
                                                            2
                                                        </option>
                                                        <option value="3" <?php echo($site->cancel_in_hours == "3" ? "selected" : ""); ?>>
                                                            3
                                                        </option>
                                                        <option value="4" <?php echo($site->cancel_in_hours == "4" ? "selected" : ""); ?>>
                                                            4
                                                        </option>
                                                        <option value="5" <?php echo($site->cancel_in_hours == "5" ? "selected" : ""); ?>>
                                                            5
                                                        </option>
                                                        <option value="6" <?php echo($site->cancel_in_hours == "6" ? "selected" : ""); ?>>
                                                            6
                                                        </option>
                                                        <option value="7" <?php echo($site->cancel_in_hours == "7" ? "selected" : ""); ?>>
                                                            7
                                                        </option>
                                                        <option value="8" <?php echo($site->cancel_in_hours == "8" ? "selected" : ""); ?>>
                                                            8
                                                        </option>
                                                        <option value="9" <?php echo($site->cancel_in_hours == "9" ? "selected" : ""); ?>>
                                                            9
                                                        </option>
                                                        <option value="10" <?php echo($site->cancel_in_hours == "10" ? "selected" : ""); ?>>
                                                            10
                                                        </option>
                                                    </select>
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Booking
                                                        cancellation percentage</label>
                                                    <input type="number" class="md-input" name="cancel_percentage"
                                                           value="<?php echo $site->cancel_percentage; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Reservation allowed
                                                        hours</label>
                                                    <input type="number" class="md-input"
                                                           name="reservation_before_hours"
                                                           value="<?php echo $site->reservation_before_hours; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Post booking
                                                        cancellation hours</label>
                                                    <input type="text" class="md-input"
                                                           name="post_booking_cancellation_hours"
                                                           value="<?php echo $site->post_booking_cancellation_hours; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Ignore Hours
                                                        Bronze</label>
                                                    <input type="text" class="md-input" name="ignore_hours_bronze"
                                                           value="<?php echo $site->ignore_hours_bronze; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Ignore Hours
                                                        Silver</label>
                                                    <input type="text" class="md-input" name="ignore_hours_silver"
                                                           value="<?php echo $site->ignore_hours_silver; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>


                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Ignore Hours
                                                        Golden</label>
                                                    <input type="text" class="md-input" name="ignore_hours_golden"
                                                           value="<?php echo $site->ignore_hours_golden; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Ignore Hours
                                                        Platinum</label>
                                                    <input type="text" class="md-input" name="ignore_hours_platinum"
                                                           value="<?php echo $site->ignore_hours_platinum; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Hours Before
                                                        Delivery</label>
                                                    <input type="text" class="md-input" name="hours_before_delivery"
                                                           value="<?php echo $site->hours_before_delivery; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first" style="display: none;">
                                                <div class="md-input-wrapper md-input-filled"><label>100 Loyalty Points
                                                        Equal To</label><input
                                                            type="text" class="md-input" name="points_vs_price"
                                                            value="<?php echo $site->points_vs_price; ?>"><span
                                                            class="md-input-bar "></span>
                                                </div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Value Added Tax %</label><input
                                                            type="text" class="md-input" name="vat_percentage"
                                                            value="<?php echo $site->vat_percentage; ?>"><span
                                                            class="md-input-bar "></span>
                                                </div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Silver Redeem Factor</label><input
                                                            type="text" class="md-input" name="silver_redeem_factor"
                                                            value="<?php echo $site->silver_redeem_factor; ?>"><span
                                                            class="md-input-bar "></span>
                                                </div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Golden Redeem Factor</label><input
                                                            type="text" class="md-input" name="golden_redeem_factor"
                                                            value="<?php echo $site->golden_redeem_factor; ?>"><span
                                                            class="md-input-bar "></span>
                                                </div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Platinum Redeem Factor</label><input
                                                            type="text" class="md-input" name="platinum_redeem_factor"
                                                            value="<?php echo $site->platinum_redeem_factor; ?>"><span
                                                            class="md-input-bar "></span>
                                                </div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Days For Redeem</label><input
                                                            type="text" class="md-input" name="days_for_redeem"
                                                            value="<?php echo $site->days_for_redeem; ?>"><span
                                                            class="md-input-bar "></span>
                                                </div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Walkin SMS Hours</label><input
                                                            type="text" class="md-input" name="walkin_sms_hours"
                                                            value="<?php echo $site->walkin_sms_hours; ?>"><span
                                                            class="md-input-bar "></span>
                                                </div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Walkin Discount %</label><input
                                                            type="text" class="md-input" name="walkin_discount_percent"
                                                            value="<?php echo $site->walkin_discount_percent; ?>"><span
                                                            class="md-input-bar "></span>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <?php

                                    $cc_checked = '';
                                    $sadad_checked = '';
                                    $cash_checked = '';
                                    $point_checked = '';
                                    if ($site->cc == '1') {
                                        $cc_checked = 'checked';
                                    } if ($site->sadad == '1') {
                                        $sadad_checked = 'checked';
                                    } if ($site->cash == '1') {
                                        $cash_checked = 'checked';
                                    } if ($site->points == '1') {
                                        $point_checked = 'checked';
                                    }
                                    ?>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <h5>Payment Options</h5>
                                            <div class="uk-width-medium-1-1 uk-row-first">
                                                <div class=" uk-width-1-1">
                                <span class="icheck-inline">
                        <input type="checkbox" id="cc" name="cc" value="1"
                               <?php echo $cc_checked; ?> data-md-icheck/>
                        <label for="cc" class="inline-label">CC</label>
                        </span>
                                                    <span class="icheck-inline">
                        <input type="checkbox" name="sadad" id="sadad" value="1"
                               <?php echo $sadad_checked; ?>  data-md-icheck/>
                        <label for="sadad" class="inline-label">Sadad</label>
                        </span>
                                                    <span class="icheck-inline">
                        <input type="checkbox" name="cash" id="cash" value="1"
                               <?php echo $cash_checked; ?>  data-md-icheck/>
                        <label for="cash" class="inline-label">Cash</label>
                        </span>
                                                    <span class="icheck-inline">
                        <input type="checkbox" name="points" id="points" value="1"
                               <?php echo $point_checked; ?>  data-md-icheck/>
                        <label for="points" class="inline-label">Redeem Points</label>
                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    $redeem_offer_mode_type = $site->redeem_offer_mode_type;
                                    $tabular_checked = '';
                                    $textual_checked = '';
                                    if ($redeem_offer_mode_type == 'tabular')
                                    {
                                        $tabular_checked = 'checked';
                                    } elseif ($redeem_offer_mode_type == 'textual')
                                    {
                                        $textual_checked = 'checked';
                                    }
                                    ?>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <h5>Redeem Offer Section Type</h5>
                                            <div class="uk-width-medium-1-1 uk-row-first">
                                                <div class=" uk-width-1-1">
                                                    <span class="icheck-inline">
                                        <input type="radio" name="redeem_offer_mode_type" id="radio_demo_inline_1" value="tabular" <?php echo $tabular_checked; ?>  data-md-icheck/>
                                        <label for="radio_demo_inline_1" class="inline-label">Tabular</label>
                                    </span>
                                                    <span class="icheck-inline">
                                        <input type="radio" name="redeem_offer_mode_type" id="radio_demo_inline_2" value="textual" <?php echo $textual_checked; ?>  data-md-icheck/>
                                        <label for="radio_demo_inline_2" class="inline-label">Textual</label>
                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if ($site->survey_on_off == 'on') {
                                        $survey_on_off = 'checked';
                                    } else {
                                        $survey_on_off = '';
                                    }

                                    if ($site->survey_mode == 'mandatory') {
                                        $survey_mode = 'checked';
                                    } else {
                                        $survey_mode = '';
                                    } ?>

                                    <?php
                                    if ($site->survey_mode_mobile == 'mandatory') {
                                        $survey_mode_mobile = 'checked';
                                    } else {
                                        $survey_mode_mobile = '';
                                    } ?>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-3 uk-row-first" style="width: 28.333%;">
                                                <input type="checkbox" data-switchery data-switchery-color="#2196F3"
                                                       name="survey_on_off" value="on" data-switchery-size="large"
                                                       id="survey_on_off" <?php echo $survey_on_off; ?>/>
                                                <label for="survey_on_off" class="inline-label">Survey Mode (Off /
                                                    On)</label>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first" style="width: 28.333%;">
                                                <input type="checkbox" data-switchery name="survey_mode"
                                                       value="mandatory" data-switchery-size="large"
                                                       id="survey_mode" <?php echo $survey_mode; ?>/>
                                                <label for="survey_mode" class="inline-label">Survey Mode For Website
                                                    (Optional / Mandatory)</label>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first" style="width: 28.333%;">
                                                <input type="checkbox" data-switchery data-switchery-color="#2196F3"
                                                       name="survey_mode_mobile" value="mandatory"
                                                       data-switchery-size="large"
                                                       id="survey_mode_mobile" <?php echo $survey_mode_mobile; ?>/>
                                                <label for="survey_mode_mobile" class="inline-label">Survey Mode For
                                                    Mobile (Optional / Mandatory)</label>
                                            </div>
                                        </div>
                                    </div>


                                    <?php
                                    if ($site->delivery_mode == 'on') {
                                        $delivery_mode = 'checked';
                                    } else {
                                        $delivery_mode = '';
                                    } ?>

                                    <?php
                                    if ($site->delivery_mode_mobile == 'on') {
                                        $delivery_mode_mobile = 'checked';
                                    } else {
                                        $delivery_mode_mobile = '';
                                    } ?>


                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-2 uk-row-first" style="width: 40%;">
                                                <input type="checkbox" data-switchery name="delivery_mode" value="on"
                                                       data-switchery-size="large"
                                                       id="delivery_mode" <?php echo $delivery_mode; ?>/>
                                                <label for="delivery_mode" class="inline-label">Delivery Mode For
                                                        Website (Off / On)</label>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first" style="width: 40%;">
                                                <input type="checkbox" data-switchery data-switchery-color="#2196F3"
                                                       name="delivery_mode_mobile" value="on"
                                                       data-switchery-size="large"
                                                       id="delivery_mode_mobile" <?php echo $delivery_mode_mobile; ?>/>
                                                <label for="delivery_mode_mobile" class="inline-label">Delivery Mode For
                                                        Mobile (Off / On)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if ($site->campaign_mode == 'on') {
                                        $campaign_mode = 'checked';
                                    } else {
                                        $campaign_mode = '';
                                    }

                                    if ($site->promo_coupon_mode == 'on') {
                                        $promo_coupon_mode = 'checked';
                                    } else {
                                        $promo_coupon_mode = '';
                                    }

                                    if ($site->pricing_api == 'on') {
                                        $pricing_api = 'checked';
                                    } else {
                                        $pricing_api = '';
                                    }

                                    ?>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-3 uk-row-first" style="width: 28.333%;">
                                                <input type="checkbox" data-switchery name="campaign_mode" value="on"
                                                       data-switchery-size="large"
                                                       id="campaign_mode" <?php echo $campaign_mode; ?>/>
                                                <label for="campaign_mode" class="inline-label">Campaign Mode (Off /
                                                    On)</label>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first" style="width: 28.333%;">
                                                <input type="checkbox" data-switchery name="promo_coupon_mode" value="on"
                                                       data-switchery-size="large"
                                                       id="promo_coupon_mode" <?php echo $promo_coupon_mode; ?>/>
                                                <label for="promo_coupon_mode" class="inline-label">Promo Coupon Mode (Off /
                                                    On)</label>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first" style="width: 28.333%;">
                                                <input type="checkbox" data-switchery name="pricing_api" value="on"
                                                       data-switchery-size="large"
                                                       id="pricing_api" <?php echo $pricing_api; ?>/>
                                                <label for="pricing_api" class="inline-label">Pricing API (Off /
                                                    On)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if ($site->vat_mode == 'on') {
                                        $vat_mode = 'checked';
                                    } else {
                                        $vat_mode = '';
                                    } ?>

                                    <?php
                                    if ($site->vat_mode_for_mobile == 'on') {
                                        $vat_mode_for_mobile = 'checked';
                                    } else {
                                        $vat_mode_for_mobile = '';
                                    } ?>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-3 uk-row-first" style="width: 40%;">
                                                <input type="checkbox" data-switchery data-switchery-color="#2196F3"
                                                       name="vat_mode" value="on"
                                                       data-switchery-size="large"
                                                       id="vat_mode" <?php echo $vat_mode; ?>/>
                                                <label for="vat_mode" class="inline-label">Value Added Tax (Off / On)</label>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first" style="width: 40%;">
                                                <input type="checkbox" data-switchery data-switchery-color="#2196F3"
                                                       name="vat_mode_for_mobile" value="on"
                                                       data-switchery-size="large"
                                                       id="vat_mode_for_mobile" <?php echo $vat_mode_for_mobile; ?>/>
                                                <label for="vat_mode_for_mobile" class="inline-label">Value Added Tax For Mobile (Off / On)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if ($site->redeem_offer_mode == 'on') {
                                        $redeem_offer_mode = 'checked';
                                    } else {
                                        $redeem_offer_mode = '';
                                    } ?>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-3 uk-row-first" style="width: 40%;">
                                                <input type="checkbox" data-switchery data-switchery-color="#2196F3"
                                                       name="redeem_offer_mode" value="on"
                                                       data-switchery-size="large"
                                                       id="redeem_offer_mode" <?php echo $redeem_offer_mode; ?>/>
                                                <label for="redeem_offer_mode" class="inline-label">Redeem Offer Mode (Off / On)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <h5>Site Logo</h5>
                                                <div class="md-input-wrapper md-input-filled">

                                                    {{--<input type="text" class="md-input">--}}
                                                    <div class="uk-form-file md-btn md-btn-primary">
                                                        Select
                                                        <input id="form-file" type="file" name="site_logo">
                                                    </div>

                                                    <span class="md-input-bar "></span>
                                                </div>

                                            </div>
                                            <?php if ($site->site_logo != '')
                                            { ?>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled">
                                                    {{--<input type="text" class="md-input">--}}
                                                    <img id="site_logo_image"
                                                         src="<?php echo custom::baseurl('/'); ?>/public/uploads/<?php echo $site->site_logo; ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>

                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $site->id; ?>">
                                    <input type="hidden" name="old_file" id="old_file"
                                           value="<?php echo $site->site_logo; ?>">
                                    <?php if (custom::rights(12, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" href="#" class="md-btn md-btn-primary">Update</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection