@extends('admin.layouts.template')
@section('content')
    <style>                                                                                                                                                                            .intl-tel-input .selected-flag {
            height: 30px
        }

        .flag-container {
            margin-top: 11px
        }
        .options label {
            /*font-size: 10px;*/
        }
    </style>
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <div class="md-card-toolbar-actions">
                                @if($customer->has_price_with_quotation == 'Yes' && custom::rights(60, 'view'))
                                    <a class="md-btn md-btn-success md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo custom::baseurl('admin/corporate_quotations/' . $id); ?>" title="View Corporate Quotations">
                                        Corporate Quotations
                                    </a>
                                @endif
                            </div>
                            <h3 class="heading_b md-card-toolbar-heading-text"><a
                                        href="<?php echo custom::baseurl('admin/corporate_customer'); ?>">Go Back</a></h3>
                        </div>
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/corporate_customer/update" method="post"
                              class="corporate_ajax_form validate-form" enctype="multipart/form-data"
                              onsubmit="return false;">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="uid" value="<?php echo $customer->uid; ?>">
                            <div class="md-card-content">
                                <h3 class="heading_a">Edit Corporate Customer</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid options" data-uk-grid-margin="">
                                            <?php
                                            if ($customer->active_status == 'active') {
                                                $active_status = 'checked';
                                            } else {
                                                $active_status = '';
                                            }
                                            if ($customer->lease_invoices == '1') {
                                                $lease_invoices = 'checked';
                                            } else {
                                                $lease_invoices = '';
                                            }

                                            if ($customer->has_price_with_quotation == 'Yes') {
                                                $has_price_with_quotation = 'checked';
                                            } else {
                                                $has_price_with_quotation = '';
                                            }

                                            if ($customer->has_limousine_option == 'Yes') {
                                                $has_limousine_option = 'checked';
                                            } else {
                                                $has_limousine_option = '';
                                            }
                                            ?>

                                            <div class="uk-width-medium-1-4 uk-row-first">
                                                <input type="checkbox" data-switchery name="active_status" value="active" data-switchery-size="large" id="active_status" <?php echo $active_status; ?> />
                                                <label for="active_status" class="inline-label">Status (Inactive / Active)</label>
                                            </div>

                                            <div class="uk-width-medium-1-4 uk-row-first">
                                                <input type="checkbox" data-switchery name="lease_invoices" value="1" data-switchery-size="large" id="lease_invoices" <?php echo $lease_invoices; ?> />
                                                <label for="lease_invoices" class="inline-label">Lease Invoices (Inactive / Active)</label>
                                            </div>

                                                @if(custom::rights(60, 'view'))
                                                    <div class="uk-width-medium-1-4 uk-row-first">
                                                        <input type="checkbox" data-switchery name="has_price_with_quotation" value="Yes" data-switchery-size="large" id="has_price_with_quotation" <?php echo $has_price_with_quotation; ?> />
                                                        <label for="has_price_with_quotation" class="inline-label">Has Price With Quotation? (No / Yes)</label>
                                                    </div>
                                                @endif

                                                <div class="uk-width-medium-1-4 uk-row-first">
                                                    <input type="checkbox" data-switchery name="has_limousine_option" value="Yes" data-switchery-size="large" id="has_limousine_option" <?php echo $has_limousine_option; ?>/>
                                                    <label for="has_limousine_option" class="inline-label">Has Limousine Option? (No / Yes)</label>
                                                </div>

                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Company Code</label>
                                                    <input type="text" class="md-input required only-number" name="company_code" value="<?php echo $customer->company_code; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Eng Company
                                                        Name</label>
                                                    <input type="text" class="md-input required" name="company_name_en"
                                                           value="<?php echo $customer->company_name_en; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Arb Company
                                                        Name</label>
                                                    <input type="text" class="md-input required" name="company_name_ar"
                                                           value="<?php echo $customer->company_name_ar; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Primary Contact
                                                        Name</label>
                                                    <input type="text" class="md-input required" name="primary_name"
                                                           value="<?php echo $customer->primary_name; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Secondary Contact
                                                        Name</label>
                                                    <input type="text" class="md-input" name="secondary_name"
                                                           value="<?php echo $customer->secondary_name; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Primary
                                                        Position</label>
                                                    <input type="text" class="md-input required" name="primary_position"
                                                           value="<?php echo $customer->primary_position; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Secondary
                                                        Position</label>
                                                    <input type="text" class="md-input" name="secondary_position"
                                                           value="<?php echo $customer->secondary_position; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Primary
                                                        Email</label>
                                                    <input type="email" class="md-input required" name="primary_email"
                                                           value="<?php echo $customer->primary_email; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Secondary
                                                        Email</label>
                                                    <input type="email" class="md-input" name="secondary_email"
                                                           value="<?php echo $customer->secondary_email; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Primary
                                                        Phone</label>
                                                    <input type="text"
                                                           style="height: 30px; width: 100%; margin-top: 12px !important;"
                                                           class="required only-number phone-primary"
                                                           value="<?php echo $customer->primary_phone; ?>">
                                                    <input type="hidden" name="primary_phone" class="intTelNo-primary" value="<?php echo $customer->primary_phone; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Secondary
                                                        Phone</label>
                                                    <input type="text"
                                                           style="height: 30px; width: 100%; margin-top: 12px !important;"
                                                           class="only-number phone-secondary"
                                                           value="<?php echo $customer->secondary_phone; ?>">
                                                    <input type="hidden" name="secondary_phone" class="intTelNo-secondary" value="<?php echo $customer->secondary_phone; ?>">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label>Membership Level</label>
                                                    <select id="select_demo_5" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select Membership Level" name="membership_level">
                                                        <option value="Bronze" <?php echo($customer->membership_level == "Bronze" ? "selected" : ""); ?>>Bronze</option>
                                                        <option value="Silver" <?php echo($customer->membership_level == "Silver" ? "selected" : ""); ?>>Silver</option>
                                                        <option value="Golden" <?php echo($customer->membership_level == "Golden" ? "selected" : ""); ?>>Golden</option>
                                                        <option value="Platinum" <?php echo($customer->membership_level == "Platinum" ? "selected" : ""); ?>>Platinum</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php

                                    $cc_checked = '';
                                    $cash_checked = '';
                                    $corporate_credit_checked = '';
                                    $pay_later_checked = '';
                                    $call_center_checked = '';
                                    if ($customer->credit_card == 1) {
                                        $cc_checked = 'checked';
                                    }
                                    if ($customer->cash == 1) {
                                        $cash_checked = 'checked';
                                    }
                                    if ($customer->corporate_credit == 1) {
                                        $corporate_credit_checked = 'checked';
                                    }
                                    if ($customer->pay_later == 1) {
                                        $pay_later_checked = 'checked';
                                    }
                                    if ($customer->call_center == 1) {
                                        $call_center_checked = 'checked';
                                    }
                                    ?>

                                    <?php

                                    $cc_company_type = $customer->cc_company;

                                    $paytabs_checked = '';

                                    $sts_checked = '';
                                    $hp_checked = '';

                                    if ($cc_company_type == 'paytabs')

                                    {

                                        $paytabs_checked = 'checked';

                                    } elseif ($cc_company_type == 'sts')

                                    {

                                        $sts_checked = 'checked';

                                    } elseif ($cc_company_type == 'hyper_pay')

                                    {

                                        $hp_checked = 'checked';

                                    }
                                    ?>
                                    <div class="uk-form-row uk-width-1-1" style="display: none;">
                                        <div class="uk-form-row">
                                            <h5>Credit Card Company</h5>
                                            <div>
                                                <span class="icheck-inline">
                                                    <input type="radio" name="cc_company" id="radio_demo_inline_1" value="paytabs" <?php echo $paytabs_checked; ?> data-md-icheck/>
                                                    <label for="radio_demo_inline_1" class="inline-label">PayTabs</label>
                                                </span>
                                                <span class="icheck-inline">
                                                    <input type="radio" name="cc_company" id="radio_demo_inline_2" value="sts" <?php echo $sts_checked; ?> data-md-icheck/>
                                                    <label for="radio_demo_inline_2" class="inline-label">STS</label>
                                                </span>
                                                <span class="icheck-inline">
                                                    <input type="radio" name="cc_company" id="radio_demo_inline_3" value="hyper_pay" <?php echo $hp_checked; ?> data-md-icheck/>
                                                    <label for="radio_demo_inline_3" class="inline-label">Hyper Pay</label>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <h5>Payment Options</h5>
                                                <div class="uk-width-1-1">
                                                    <span class="icheck-inline" style="display: none;">
                                                        <input type="checkbox" id="credit_card" name="credit_card" value="1" data-md-icheck <?php echo $cc_checked; ?>/>
                                                        <label for="credit_card" class="inline-label">Credit Card</label>
                                                    </span>
                                                    <span class="icheck-inline" style="display: none;">
                                                        <input type="checkbox" name="cash" id="cash" value="1" data-md-icheck <?php echo $cash_checked; ?>/>
                                                        <label for="cash" class="inline-label">Cash</label>
                                                    </span>
                                                    <span class="icheck-inline">
                                                        <input type="checkbox" name="corporate_credit" id="corporate_credit" value="1" data-md-icheck <?php echo $corporate_credit_checked; ?>/>
                                                        <label for="corporate_credit" class="inline-label">Corporate Credit</label>
                                                    </span>
                                                    <span class="icheck-inline">
                                                        <input type="checkbox" name="pay_later" id="pay_later" value="1" data-md-icheck <?php echo $pay_later_checked; ?>/>
                                                        <label for="pay_later" class="inline-label">Pay Later</label>
                                                    </span>
                                                    <span class="icheck-inline">
                                                        <input type="checkbox" name="call_center" id="call_center" value="1" data-md-icheck <?php echo $call_center_checked; ?>/>
                                                        <label for="call_center" class="inline-label">Call Center</label>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first" id="div-expiry-period">
                                                <h5>Pay Later Invoice Expiry Period (Hours)</h5>
                                                <div>
                                                <span class="icheck-inline">
                                                    <input type="number" min="0" name="expiry_period" class="md-input" id="expiry_period" value="<?php echo $customer->expiry_period; ?>" />
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-width-1-1 corporate-users-area">

                                        <?php
                                        $uids = explode(',', $customer->uid);
                                        foreach ($uids as $uid) {
                                        $user_detail = custom::getSingle('users', ['id' => $uid]);
                                        ?>
                                        <div class="uk-form-row uk-width-1-1">
                                            <div class="uk-grid" data-uk-grid-margin="">
                                                <div class="uk-width-medium-1-2 uk-row-first">
                                                    <div class="md-input-wrapper md-input-filled">
                                                        <label>Username</label>
                                                        <input type="email" class="md-input required"
                                                               name="username[]" value="{{$user_detail->email}}"
                                                               disabled>
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }
                                        ?>
                                    </div>

                                    @if(count($uids) <= 2)
                                        <div class="uk-form-row uk-width-1-1 add-more">
                                            <a href="javascript:void(0);"
                                               style="float: right;color: red;font-weight: bold;"
                                               onclick="addMore();">+</a>
                                        </div>
                                    @endif

                                    <div class="uk-width-1-1">
                                        <button type="submit" class="md-btn md-btn-primary">Update</button>
                                        <span style="display: inline-block;vertical-align: middle;margin-left: 14px;"><img
                                                    src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                                                    alt="Loader" height="24" width="24" class="md-card-loader"
                                                    id="loader" style="display: none;"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection