@extends('admin.layouts.template')

@section('content')
    <?php //print_r(custom::api_settings());exit(); ?>
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-2">
                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/smtp_settings" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">SMTP Settings</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Server</label><input type="text" name="server" class="md-input" value="<?php echo $smtp->server; ?>"><span class="md-input-bar"></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Port</label><input type="text" name="port" class="md-input" value="<?php echo $smtp->port; ?>"><span class="md-input-bar"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Username</label><input type="text" name="username" class="md-input" value="<?php echo $smtp->username; ?>"><span class="md-input-bar"></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Password</label><input type="text" name="password" class="md-input" value="<?php echo $smtp->password; ?>"><span class="md-input-bar"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $smtp->id; ?>">
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

                    {{--Site Settings--}}
                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/site_settings" method="post" class="settings_ajax_form" enctype="multipart/form-data" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Site Settings</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Site Title</label>
                                                    <input type="text" class="md-input" name="site_title" value="<?php echo $site->site_title; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Site Phone</label>
                                                    <input type="text" class="md-input" name="site_phone" value="<?php echo $site->site_phone; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Admin Email</label>
                                                    <input type="text" class="md-input" name="admin_email" value="<?php echo $site->admin_email; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Admin Phone</label>
                                                    <input type="text" class="md-input" name="admin_phone" value="<?php echo $site->admin_phone; ?>">
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
                                                <div class="md-input-wrapper md-input-filled"><label>100 Loyalty Points Equal To</label><input
                                                            type="text" class="md-input" name="points_vs_price" value="<?php echo $site->points_vs_price; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Booking cancellation in hours</label>
                                                    <!--<input type="text" class="md-input" name="cancel_in_hours" value="<?php echo $site->cancel_in_hours; ?>">-->
                                                    <select class="md-input" name="cancel_in_hours">
                                                        <option value="">Select</option>
                                                        <option value="1" <?php echo ($site->cancel_in_hours == "1" ? "selected" : ""); ?>>1</option>
                                                        <option value="2" <?php echo ($site->cancel_in_hours == "2" ? "selected" : ""); ?>>2</option>
                                                        <option value="3" <?php echo ($site->cancel_in_hours == "3" ? "selected" : ""); ?>>3</option>
                                                        <option value="4" <?php echo ($site->cancel_in_hours == "4" ? "selected" : ""); ?>>4</option>
                                                        <option value="5" <?php echo ($site->cancel_in_hours == "5" ? "selected" : ""); ?>>5</option>
                                                        <option value="6" <?php echo ($site->cancel_in_hours == "6" ? "selected" : ""); ?>>6</option>
                                                        <option value="7" <?php echo ($site->cancel_in_hours == "7" ? "selected" : ""); ?>>7</option>
                                                        <option value="8" <?php echo ($site->cancel_in_hours == "8" ? "selected" : ""); ?>>8</option>
                                                        <option value="9" <?php echo ($site->cancel_in_hours == "9" ? "selected" : ""); ?>>9</option>
                                                        <option value="10" <?php echo ($site->cancel_in_hours == "10" ? "selected" : ""); ?>>10</option>
                                                    </select>
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Booking cancellation percentage</label>
                                                    <input type="number" class="md-input" name="cancel_percentage" value="<?php echo $site->cancel_percentage; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Reservation allowed hours</label>
                                                    <input type="number" class="md-input" name="reservation_before_hours" value="<?php echo $site->reservation_before_hours; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Post booking cancellation hours</label>
                                                    <input type="text" class="md-input" name="post_booking_cancellation_hours" value="<?php echo $site->post_booking_cancellation_hours; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Ignore Hours Silver</label>
                                                    <input type="text" class="md-input" name="ignore_hours_silver" value="<?php echo $site->ignore_hours_silver; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>


                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Ignore Hours Golden</label>
                                                    <input type="text" class="md-input" name="ignore_hours_golden" value="<?php echo $site->ignore_hours_golden; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Ignore Hours Platinum</label>
                                                    <input type="text" class="md-input" name="ignore_hours_platinum" value="<?php echo $site->ignore_hours_platinum; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>


                                        </div>
                                    </div>

                                    <?php
                                    if ($site->maintenance_mode == 'on')
                                    {
                                        $maintenanceChecked = 'checked';
                                    }else{
                                        $maintenanceChecked = '';
                                    } ?>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <input type="checkbox" data-switchery data-switchery-color="#d32f2f" name="maintenance_mode" value="on" data-switchery-size="large" id="maintenance_mode" <?php echo $maintenanceChecked; ?>/>
                                                <label for="maintenance_mode" class="inline-label">Maintenance Mode</label>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    $site_lang = $site->site_language;
                                    $both_checked = '';
                                    $eng_checked = '';
                                    $arb_checked = '';
                                    if ($site_lang == 'both')
                                    {
                                        $both_checked = 'checked';
                                    } elseif ($site_lang == 'eng')
                                    {
                                        $eng_checked = 'checked';
                                    } elseif ($site_lang == 'arb')
                                    {
                                        $arb_checked = 'checked';
                                    }
                                    ?>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <h5>Site Language</h5>
                                            <div class="uk-width-medium-1-1 uk-row-first">
                                                <div class=" uk-width-1-1">
                                                <span class="icheck-inline">
                                        <input type="radio" id="radio_demo_inline_3" name="site_language" value="both" <?php echo $both_checked; ?> data-md-icheck/>
                                        <label for="radio_demo_inline_3" class="inline-label">Both</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="site_language" id="radio_demo_inline_1" value="eng" <?php echo $eng_checked; ?>  data-md-icheck/>
                                        <label for="radio_demo_inline_1" class="inline-label">English</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio" name="site_language" id="radio_demo_inline_2" value="arb" <?php echo $arb_checked; ?>  data-md-icheck/>
                                        <label for="radio_demo_inline_2" class="inline-label">Arabic</label>
                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <?php

                                    $cc_checked = '';
                                    $sadad_checked = '';
                                    $cash_checked = '';
                                    $point_checked = '';
                                    if ($site->cc == '1')
                                    {
                                        $cc_checked = 'checked';
                                    } if ($site->sadad == '1')
                                    {
                                        $sadad_checked = 'checked';
                                    } if ($site->cash == '1')
                                    {
                                        $cash_checked = 'checked';
                                    } if ($site->points == '1')
                                    {
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
                        <label for="points" class="inline-label">Points</label>
                        </span>
                                </div>
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
                                                    <img id="site_logo_image" src="<?php echo custom::baseurl('/'); ?>/public/uploads/<?php echo $site->site_logo; ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>

                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $site->id; ?>">
                                    <input type="hidden" name="old_file" id="old_file" value="<?php echo $site->site_logo; ?>">
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
                    {{--Loyalty card settings--}}
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div id="LoyaltyCardsTable" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-medium-1-2">
                    <div class="md-card" style="display: none;">
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
                                </div>
                            </div>
                    </div>
                    {{--API Settings--}}
                    <div class="md-card" style="margin-top: 0 !important;">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/api_settings" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">API Settings</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Mailchimp API Key</label>
                                                    <input type="text" class="md-input" name="mailchimp_api_key" value="<?php echo $api->mailchimp_api_key; ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Mailchimp List ID</label>
                                                    <input type="text" class="md-input" name="mailchimp_list_id" value="<?php echo $api->mailchimp_list_id; ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Paytabs Merchant ID</label>
                                                    <input type="text" class="md-input" name="paytabs_merchant_id" value="<?php echo $api->paytabs_merchant_id; ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Paytabs Secret Key</label>
                                                    <input type="text" class="md-input" name="paytabs_secret_key" value="<?php echo $api->paytabs_secret_key; ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-2">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>Tawk API Script</label>
                                            <textarea cols="30" rows="8" class="md-input no_autosize" name="tawk_script"><?php echo $api->tawk_script; ?></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-2">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>Tawk API Instructions:</label>
                                            <ul style="font-size: 12px;line-height: 20px;">
                                                <li>Step 1: Go to <a href="https://www.tawk.to" target="_blank">https://www.tawk.to</a> and create an account and login. If you already have an account than login to your tawk account at <a href="https://dashboard.tawk.to/login" target="_blank">dashboard.tawk.to/login</a>.</li>
                                                <li>Step 2: Go to Admin from the top menu.</li>
                                                <li>Step 3: Copy the code from textarea under Widget Code and paste it to this Tawk API Script area.</li>
                                            </ul>
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>
                                    <?php
                                    if ($api->display_chat == 'yes')
                                    {
                                        $chatChecked = 'checked';
                                    }else{
                                        $chatChecked = '';
                                    } ?>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <input type="checkbox" data-switchery name="display_chat" value="yes" data-switchery-size="large" id="display_chat" <?php echo $chatChecked; ?>/>
                                                <label for="display_chat" class="inline-label">Display Chat?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $api->id; ?>">
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
                    {{--Maintenance Messages--}}
                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/save_maintenance_text" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Maintenance Mode Text</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-2">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>English Content</label>
                                            <textarea cols="30" rows="8" class="md-input no_autosize" name="maintenance_eng_desc"><?php echo $site->maintenance_eng_desc; ?></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-2" style="margin-top: 0;">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>Arabic Content</label>
                                            <textarea cols="30" rows="8" class="md-input no_autosize" name="maintenance_arb_desc"><?php echo $site->maintenance_arb_desc; ?></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $site->id; ?>">
                                    <?php if (custom::rights(12, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" class="md-btn md-btn-primary">Update</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{--Social Links--}}
                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/social_links" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Social Links</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Facebook</label><input
                                                            type="text" class="md-input" name="facebook_link" value="<?php echo $social->facebook_link; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Twitter</label><input
                                                            type="text" class="md-input" name="twitter_link" value="<?php echo $social->twitter_link; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Linkedin</label><input
                                                            type="text" class="md-input" name="linkedin_link" value="<?php echo $social->linkedin_link; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Instagram</label>
                                                    <input type="text" class="md-input" name="instagram_link" value="<?php echo $social->instagram_link; ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-width-medium-1-2 uk-row-first">
                                            <div class="md-input-wrapper md-input-filled"><label>Youtube</label>
                                                <input type="text" class="md-input" name="youtube_link" value="<?php echo $social->youtube_link; ?>">
                                                <span class="md-input-bar "></span>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $social->id; ?>">
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
                    {{--Renting type settings--}}
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div id="RentingTypeTable" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--Inquiry Type settings--}}
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div id="InquiryTypesTable" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--Depatment settings --}}
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div id="DepartmentTable" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{--Site Sections settings--}}
                    <div class="md-card" style="display: block;">
                        <div class="md-card-content">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-form-row uk-width-1-1">
                                    <div class="uk-grid" data-uk-grid-margin="">
                                        <div id="SiteSectionsTable" style="width: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
            {{--User Roles settings--}}
            <div class="md-card">
                <div class="md-card-content">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-form-row uk-width-1-1">
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div id="UserRolesTable" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md-card">
                <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/save_terms_conditions" method="post" class="settings_ajax_form" onsubmit="return false;">
                    <div class="md-card-content">
                        <h3 class="heading_a">Terms & Conditions</h3>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <label>English Content</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="8" class="md-input" name="eng_terms" id="terms_eng"><?php echo $site->eng_terms; ?></textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <label>Arabic Content</label>
                                <div class="uk-form-row">
                                    <textarea cols="30" rows="8" class="md-input" name="arb_terms" id="terms_arb"><?php echo $site->arb_terms; ?></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $site->id; ?>">
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
@endsection