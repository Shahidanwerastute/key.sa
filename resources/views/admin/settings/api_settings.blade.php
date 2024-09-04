@extends('admin.layouts.template')

@section('content')

    <style>
        .update_btn {
            display: none;
        }
    </style>

    <div id="page_content" class="is_password_protected">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <button type="button" class="md-btn md-btn-success decrypt_data" style="float: right;">Decrypt Data</button>
                    <button type="button" class="md-btn md-btn-danger encrypt_data" style="float: right;display: none;">Encrypt Data</button>
                    <br><br><br>
                    {{--SMTP Settings--}}
                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/smtp_settings" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">SMTP Settings</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-3 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Server</label><input type="text" name="server" class="md-input" value="<?php echo $smtp->server; ?>"><span class="md-input-bar"></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label for="encryption">Encryption Type</label>
                                                    <select name="encryption" id="select_demo_5" data-md-selectize data-md-selectize-bottom>
                                                        <option value="tls" <?php echo($smtp->encryption == "tls" ? "selected" : ""); ?>>
                                                            TLS
                                                        </option>
                                                        <option value="ssl" <?php echo($smtp->encryption == "ssl" ? "selected" : ""); ?>>
                                                            SSL
                                                        </option>
                                                    </select>
                                                    <span class="md-input-bar"></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Port</label><input type="text" name="port" class="md-input" value="<?php echo $smtp->port; ?>"><span class="md-input-bar"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Username</label><input type="text" name="username" class="md-input" value="<?php echo custom::encrypt($smtp->username); ?>"><span class="md-input-bar"></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Password</label><input type="password" name="password" class="md-input" value="<?php echo custom::encrypt($smtp->password); ?>"><span class="md-input-bar"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $smtp->id; ?>">
                                    <?php if (custom::rights(12, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" href="#" class="md-btn md-btn-primary update_btn">Update</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{--API Settings--}}
                    <div class="md-card">
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
                                    </div>
                                    <div class="uk-form-row uk-width-1-2">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-1 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Paytabs Merchant Email</label>
                                                    <input type="text" class="md-input" name="paytabs_merchant_email" value="<?php echo custom::encrypt($api->paytabs_merchant_email); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-1 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Paytabs Merchant ID</label>
                                                    <input type="text" class="md-input" name="paytabs_merchant_id" value="<?php echo custom::encrypt($api->paytabs_merchant_id); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-1 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Paytabs Secret Key</label>
                                                    <input type="text" class="md-input" name="paytabs_secret_key" value="<?php echo custom::encrypt($api->paytabs_secret_key); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-2">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>Paytabs API Instructions:</label>
                                            <ol style="font-size: 14px;line-height: 22px;">
                                                <li>Go to <a href="https://www.paytabs.com" target="_blank">https://www.paytabs.com</a> and create an account and login. If you already have an account than login to your paytabs account at <a href="https://www.paytabs.com/login" target="_blank">https://www.paytabs.com/login</a>.</li>
                                                <li>From the top, get to My Profile. You will find your Merchant Email there.</li>
                                                <li>You can see MID at the top, its your Merchant ID.</li>
                                                <li>In sidebar, go to PayTabs Services > E-commerce Plugins & API. You will find your Secret Awfar there.</li>
                                                <li>From the top, go to My Profile > Edit Profile. In "IPN Listener URL" field, put <?php echo custom::baseurl('/paytabsIPN'); ?> and save.</li>
                                            </ol>
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                    </div>
                                    <div class="uk-form-row uk-width-1-2" title="This field is disabled for now. If you need to edit it, please go to database table setting_api_settings and directly edit there as it was being blocked by server when submitting form.">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>Tawk API Script</label>
                                            <!--the cloudflair is blocking it so we disabled it-->
                                            <textarea disabled="disabled" cols="30" rows="8" class="md-input no_autosize" name="tawk_script"><?php echo $api->tawk_script; ?></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-2">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>Tawk API Instructions:</label>
                                            <ul style="font-size: 14px;line-height: 22px;">
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

                                    <?php
                                    if ($api->api_on_off == 'on')
                                    {
                                        $api_on_off = 'checked';
                                    }else{
                                        $api_on_off = '';
                                    } ?>

                                    <?php
                                    if ($api->walkin_api_on_off == 'on')
                                    {
                                        $walkin_api_on_off = 'checked';
                                    }else{
                                        $walkin_api_on_off = '';
                                    }
                                    ?>

                                    <?php
                                    if ($api->hyper_pay_test_mode == 'INTERNAL')
                                    {
                                        $hyper_pay_test_mode = 'checked';
                                    }else{
                                        $hyper_pay_test_mode = '';
                                    }
                                    ?>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Google API Key (General)</label><input
                                                            type="text" class="md-input" name="google_api_key_general" value="<?php echo $api->google_api_key_general; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Google API Key (For Website Delivery Module)</label><input
                                                            type="text" class="md-input" name="google_api_key_for_delivery" value="<?php echo $api->google_api_key_for_delivery; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-1 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>FCM Key</label><input
                                                            type="text" class="md-input" name="fcm_key" value="<?php echo $api->fcm_key; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Oasis API URL</label><input
                                                            type="text" class="md-input" name="oasis_api_url" value="<?php echo custom::encrypt($api->oasis_api_url); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Qitaf API Base URL</label><input
                                                            type="text" class="md-input" name="qitaf_api_base_url" value="<?php echo custom::encrypt($api->qitaf_api_base_url); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Edit Booking Sync API URL</label><input
                                                            type="text" class="md-input" name="edit_booking_sync_api_url" value="<?php echo custom::encrypt($api->edit_booking_sync_api_url); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first">
                                                <input type="checkbox" data-switchery data-switchery-color="#2196F3" name="api_on_off" value="on" data-switchery-size="large" id="api_on_off" <?php echo $api_on_off; ?>/>
                                                <label for="api_on_off" class="inline-label">Oasis Apis (Off/On)</label>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first">
                                                <input type="checkbox" data-switchery data-switchery-color="#2196F3" name="walkin_api_on_off" value="on" data-switchery-size="large" id="walkin_api_on_off" <?php echo $walkin_api_on_off; ?>/>
                                                <label for="walkin_api_on_off" class="inline-label">Walkin Api (Off/On)</label>
                                            </div>
                                            <div class="uk-width-medium-1-3 uk-row-first">
                                                <input type="checkbox" data-switchery name="display_chat" value="yes" data-switchery-size="large" id="display_chat" <?php echo $chatChecked; ?>/>
                                                <label for="display_chat" class="inline-label">Display Chat?</label>
                                            </div>
                                        </div>
                                    </div>



                                    <input type="hidden" name="id" value="<?php echo $api->id; ?>">
                                    <?php if (custom::rights(12, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" href="#" class="md-btn md-btn-primary update_btn">Update</button>
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
                                    <?php if (custom::rights(25, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" href="#" class="md-btn md-btn-primary update_btn">Update</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{--ReCaptcha--}}
                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/api_settings" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">ReCaptcha Keys</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Captcha Site Key</label><input
                                                            type="text" class="md-input" name="captcha_site_key" value="<?php echo $api->captcha_site_key; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Captcha Secret Key</label>
                                                    <input type="text" class="md-input" name="captcha_secret_key" value="<?php echo $api->captcha_secret_key; ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="id" value="<?php echo $api->id; ?>">
                                    <?php if (custom::rights(25, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" href="#" class="md-btn md-btn-primary update_btn">Update</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/api_settings" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Unifonic API Settings</h3>
                                <div class="uk-grid" data-uk-grid-margin>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-3">
                                                <div class="md-input-wrapper md-input-filled"><label>Username</label><input
                                                            type="text" class="md-input" name="unifonic_username" value="<?php echo custom::encrypt($api->unifonic_username); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-3">
                                                <div class="md-input-wrapper md-input-filled"><label>Password</label>
                                                    <input type="text" class="md-input" name="unifonic_password" value="<?php echo custom::encrypt($api->unifonic_password); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-3">
                                                <div class="md-input-wrapper md-input-filled"><label>APP Sender Name</label>
                                                    <input type="text" class="md-input" name="unifonic_sender_id" value="<?php echo custom::encrypt($api->unifonic_sender_id); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-3">
                                                <div class="md-input-wrapper md-input-filled"><label>APP ID</label>
                                                    <input type="text" class="md-input" name="unifonic_app_id" value="<?php echo custom::encrypt($api->unifonic_app_id); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="id" value="<?php echo $api->id; ?>">
                                    <?php if (custom::rights(25, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" href="#" class="md-btn md-btn-primary update_btn">Update</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/api_settings" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Taqnyat API Settings</h3>
                                <div class="uk-grid" data-uk-grid-margin>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-3">
                                                <div class="md-input-wrapper md-input-filled"><label>Sender ID</label><input
                                                            type="text" class="md-input" name="taqnyat_sender_id" value="<?php echo custom::encrypt($api->taqnyat_sender_id); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-2-3">
                                                <div class="md-input-wrapper md-input-filled"><label>Bearer Token</label>
                                                    <input type="text" class="md-input" name="taqnyat_bearer_token" value="<?php echo custom::encrypt($api->taqnyat_bearer_token); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="id" value="<?php echo $api->id; ?>">
                                    <?php if (custom::rights(25, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" href="#" class="md-btn md-btn-primary update_btn">Update</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/api_settings" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">STS PayOne API Settings</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Merchant ID (Website)</label>
                                                    <input type="text" class="md-input" name="sts_merchant_id_web" value="<?php echo custom::encrypt($api->sts_merchant_id_web); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Secret Key (Website)</label>
                                                    <input type="text" class="md-input" name="sts_secret_key_web" value="<?php echo custom::encrypt($api->sts_secret_key_web); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Payment Link (Website)</label>
                                                    <input type="text" class="md-input" name="sts_payment_link" value="<?php echo custom::encrypt($api->sts_payment_link); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Payment Inquiry Link (Website)</label>
                                                    <input type="text" class="md-input" name="sts_payment_inquiry_link" value="<?php echo custom::encrypt($api->sts_payment_inquiry_link); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Merchant ID (Corporate PayLater)</label>
                                                    <input type="text" class="md-input" name="sts_paylater_merchant_id" value="<?php echo custom::encrypt($api->sts_paylater_merchant_id); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Secret Key (Corporate PayLater)</label>
                                                    <input type="text" class="md-input" name="sts_paylater_secret_key" value="<?php echo custom::encrypt($api->sts_paylater_secret_key); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Send Invoice Link (Corporate PayLater)</label>
                                                    <input type="text" class="md-input" name="sts_paylater_send_invoice_link" value="<?php echo custom::encrypt($api->sts_paylater_send_invoice_link); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Invoice Inquiry Link (Corporate PayLater)</label>
                                                    <input type="text" class="md-input" name="sts_paylater_invoice_inquiry_link" value="<?php echo custom::encrypt($api->sts_paylater_invoice_inquiry_link); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Merchant ID (Mobile)</label>
                                                    <input type="text" class="md-input" name="sts_merchant_id_mobile" value="<?php echo custom::encrypt($api->sts_merchant_id_mobile); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Secret Key (Mobile)</label>
                                                    <input type="text" class="md-input" name="sts_secret_key_mobile" value="<?php echo custom::encrypt($api->sts_secret_key_mobile); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <input type="hidden" name="id" value="<?php echo $api->id; ?>">
                                    <?php if (custom::rights(25, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" href="#" class="md-btn md-btn-primary update_btn">Update</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/api_settings" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Hyper Pay API Settings</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Endpoint URL (Trailing slash in mandatory)</label>
                                                    <input type="text" class="md-input" name="hyper_pay_endpoint_url" value="<?php echo custom::encrypt($api->hyper_pay_endpoint_url); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Bearer Token</label>
                                                    <input type="text" class="md-input" name="hyper_pay_bearer_token" value="<?php echo custom::encrypt($api->hyper_pay_bearer_token); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-3">
                                                <div class="md-input-wrapper md-input-filled"><label>Entity ID (Master Card, Visa)</label>
                                                    <input type="text" class="md-input" name="hyper_pay_entity_id_master_visa" value="<?php echo custom::encrypt($api->hyper_pay_entity_id_master_visa); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-3">
                                                <div class="md-input-wrapper md-input-filled"><label>Entity ID (Mada)</label>
                                                    <input type="text" class="md-input" name="hyper_pay_entity_id_mada" value="<?php echo custom::encrypt($api->hyper_pay_entity_id_mada); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-3">
                                                <div class="md-input-wrapper md-input-filled"><label>Entity ID (Apple Pay)</label>
                                                    <input type="text" class="md-input" name="hyper_pay_entity_id_apple_pay" value="<?php echo custom::encrypt($api->hyper_pay_entity_id_apple_pay); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-1">
                                                <input type="checkbox" data-switchery data-switchery-color="#2196F3" name="hyper_pay_test_mode" value="INTERNAL" data-switchery-size="large" id="hyper_pay_test_mode" <?php echo $hyper_pay_test_mode; ?>/>
                                                <label for="hyper_pay_test_mode" class="inline-label">Hyper Pay API Mode (Sandbox / Live)</label>
                                                <br><small>Note: Before changing this mode, make sure above endpoint url and other settings are also changed according to this <b>Sandbox / Live</b> mode</small>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $api->id; ?>">
                                    <?php if (custom::rights(25, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" href="#" class="md-btn md-btn-primary update_btn">Update</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/api_settings" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Hyper Bill API Settings <small>(Being used for Hyper Pay Corporate Invoicing)</small></h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Hyper Bill Username</label>
                                                    <input type="text" class="md-input" name="hyper_bill_username" value="<?php echo custom::encrypt($api->hyper_bill_username); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Hyper Bill Password</label>
                                                    <input type="text" class="md-input" name="hyper_bill_password" value="<?php echo custom::encrypt($api->hyper_bill_password); ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Endpoint URL (Trailing slash in mandatory)</label>
                                                    <input type="text" class="md-input" name="hyper_bill_endpoint_url" value="<?php echo custom::encrypt($api->hyper_bill_endpoint_url); ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $api->id; ?>">
                                    <?php if (custom::rights(25, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" href="#" class="md-btn md-btn-primary update_btn">Update</button>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/api_settings" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Human Less API Settings</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Human Less API URL</label>
                                                    <input type="text" class="md-input" name="human_less_api" value="<?php echo $api->human_less_api; ?>"><span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <!--<div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>Safe Road API URL</label>
                                                    <input type="text" class="md-input" name="safe_road_api" value="<?php //echo $api->safe_road_api; ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>-->
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>TAMM API URL</label>
                                                    <input type="text" class="md-input" name="tamm_otp_url" value="<?php echo $api->tamm_otp_url; ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <!--<div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled"><label>TAMM AUTH URL</label>
                                                    <input type="text" class="md-input" name="tamm_auth_url" value="<?php //echo $api->tamm_auth_url; ?>">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>-->
                                        </div>
                                    </div>

                                    <input type="hidden" name="id" value="<?php echo $api->id; ?>">
                                    <?php if (custom::rights(25, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" href="#" class="md-btn md-btn-primary update_btn">Update</button>
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