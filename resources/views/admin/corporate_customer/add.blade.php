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
                            <h3 class="heading_b md-card-toolbar-heading-text"><a
                                        href="<?php echo custom::baseurl('admin/corporate_customer'); ?>">Go Back</a></h3>
                        </div>
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/corporate_customer/save" method="post"
                              class="corporate_ajax_form validate-form" enctype="multipart/form-data"
                              onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Add Corporate Customer</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid options" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-4 uk-row-first">
                                                <input type="checkbox" data-switchery name="active_status" value="active" data-switchery-size="large" id="active_status"/>
                                                <label for="active_status" class="inline-label">Status (Inactive / Active)</label>
                                            </div>
                                            <div class="uk-width-medium-1-4 uk-row-first">
                                                <input type="checkbox" data-switchery name="lease_invoices" value="1" data-switchery-size="large" id="lease_invoices"/>
                                                <label for="lease_invoices" class="inline-label">Lease Invoices (Inactive / Active)</label>
                                            </div>

                                            @if(custom::rights(60, 'view'))
                                                <div class="uk-width-medium-1-4 uk-row-first">
                                                    <input type="checkbox" data-switchery name="has_price_with_quotation" value="Yes" data-switchery-size="large" id="has_price_with_quotation"/>
                                                    <label for="has_price_with_quotation" class="inline-label">Has Price With Quotation? (No / Yes)</label>
                                                </div>
                                            @endif

                                            <div class="uk-width-medium-1-4 uk-row-first">
                                                <input type="checkbox" data-switchery name="has_limousine_option" value="Yes" data-switchery-size="large" id="has_limousine_option"/>
                                                <label for="has_limousine_option" class="inline-label">Has Limousine Option? (No / Yes)</label>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Company Code</label>
                                                    <input type="text" class="md-input required only-number" name="company_code">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Eng Company
                                                        Name</label>
                                                    <input type="text" class="md-input required" name="company_name_en">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Arb Company
                                                        Name</label>
                                                    <input type="text" class="md-input required" name="company_name_ar">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Primary Contact
                                                        Name</label>
                                                    <input type="text" class="md-input required" name="primary_name">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Secondary Contact
                                                        Name</label>
                                                    <input type="text" class="md-input" name="secondary_name">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Primary
                                                        Position</label>
                                                    <input type="text" class="md-input required"
                                                           name="primary_position">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Secondary
                                                        Position</label>
                                                    <input type="text" class="md-input" name="secondary_position">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Primary
                                                        Email</label>
                                                    <input type="email" class="md-input required" name="primary_email">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Secondary
                                                        Email</label>
                                                    <input type="email" class="md-input" name="secondary_email">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label style="">Primary Phone</label>
                                                    <input type="text" style="height: 30px; width: 100%; margin-top: 12px !important;" class="required only-number phone-primary">
                                                    <input type="hidden" name="primary_phone" class="intTelNo-primary">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label>Secondary Phone</label>
                                                    <input type="text" style="height: 30px; width: 100%; margin-top: 12px !important;" class="only-number phone-secondary">
                                                    <input type="hidden" name="secondary_phone" class="intTelNo-secondary">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2">
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label>Membership Level</label>
                                                    <select id="select_demo_5" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select Membership Level" name="membership_level" class="required">
                                                        <option value="Bronze" selected>Bronze</option>
                                                        <option value="Silver">Silver</option>
                                                        <option value="Golden">Golden</option>
                                                        <option value="Platinum">Platinum</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-1" style="display: none;">
                                        <div class="uk-form-row">
                                            <h5>Credit Card Company</h5>
                                            <div>
                                                <span class="icheck-inline">
                                                    <input type="radio" name="cc_company" id="radio_demo_inline_1" value="paytabs"  data-md-icheck/>
                                                    <label for="radio_demo_inline_1" class="inline-label">PayTabs</label>
                                                </span>
                                                <span class="icheck-inline">
                                                    <input type="radio" name="cc_company" id="radio_demo_inline_2" value="sts" data-md-icheck/>
                                                    <label for="radio_demo_inline_2" class="inline-label">STS</label>
                                                </span>
                                                <span class="icheck-inline">
                                                    <input type="radio" name="cc_company" id="radio_demo_inline_3" value="hyper_pay" checked data-md-icheck/>
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
                                                        <input type="checkbox" id="credit_card" name="credit_card" value="1" data-md-icheck/>
                                                        <label for="credit_card" class="inline-label">Credit Card</label>
                                                    </span>
                                                    <span class="icheck-inline" style="display: none;">
                                                        <input type="checkbox" name="cash" id="cash" value="1" data-md-icheck/>
                                                        <label for="cash" class="inline-label">Cash</label>
                                                    </span>
                                                    <span class="icheck-inline">
                                                        <input type="checkbox" name="corporate_credit" id="corporate_credit" value="1" data-md-icheck/>
                                                        <label for="corporate_credit" class="inline-label">Corporate Credit</label>
                                                    </span>
                                                    <span class="icheck-inline">
                                                        <input type="checkbox" name="pay_later" id="pay_later" value="1" data-md-icheck/>
                                                        <label for="pay_later" class="inline-label">Pay Later</label>
                                                    </span>
                                                    <span class="icheck-inline">
                                                        <input type="checkbox" name="call_center" id="call_center" value="1" data-md-icheck/>
                                                        <label for="call_center" class="inline-label">Call Center</label>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first" id="div-expiry-period">
                                                <h5>Pay Later Invoice Expiry Period (Hours)</h5>
                                                <div>
                                                <span class="icheck-inline">
                                                    <input type="number" min="0" name="expiry_period" class="md-input" id="expiry_period" value="" />
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-width-1-1 corporate-users-area">
                                        <div class="uk-form-row uk-width-1-1">
                                            <div class="uk-grid" data-uk-grid-margin="">
                                                <div class="uk-width-medium-1-4 uk-row-first">
                                                    <div class="md-input-wrapper"><label>Username</label>
                                                        <input type="email" class="md-input required" name="username[]">
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                </div>
                                                <div class="uk-width-medium-1-4 uk-row-first">
                                                    <div class="md-input-wrapper"><label>Password</label>
                                                        <input type="text" class="md-input required" name="password[]">
                                                        <span class="md-input-bar "></span>
                                                    </div>
                                                </div>
                                                <div class="uk-width-medium-1-4 uk-row-first">
                                                    <label for="is_email_verified">Is Email Verified?</label><br>
                                                    <select class="md-input required" id="is_email_verified" name="is_email_verified[]">
                                                        <option value="0">NO</option>
                                                        <option value="1">YES</option>
                                                    </select>
                                                </div>
                                                <div class="uk-width-medium-1-4 uk-row-first">
                                                    <label for="is_phone_verified">Is Phone Verified?</label><br>
                                                    <select class="md-input required" id="is_phone_verified" name="is_phone_verified[]">
                                                        <option value="0">NO</option>
                                                        <option value="1">YES</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-1 add-more">
                                        <a href="javascript:void(0);" style="float: right;color: red;font-weight: bold;" onclick="addMore();">+</a>
                                    </div>

                                    <div class="uk-width-1-1">
                                        <button type="submit" class="md-btn md-btn-primary submit_btn">Save</button>
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