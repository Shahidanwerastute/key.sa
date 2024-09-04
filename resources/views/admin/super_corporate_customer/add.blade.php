@extends('admin.layouts.template')
@section('content')
    <style>                                                                                                                                                                            .intl-tel-input .selected-flag {
            height: 30px
        }
        .flag-container {
            margin-top: 11px
        }
    </style>
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="md-card">
                        <div class="md-card-toolbar">
                            <h3 class="heading_b md-card-toolbar-heading-text">
                                <a href="<?php echo custom::baseurl('admin/super_corporate_customer'); ?>">Go Back</a>
                            </h3>
                        </div>
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/super_corporate_customer/save" method="post" class="corporate_ajax_form validate-form" enctype="multipart/form-data" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Add Super Corporate Customer</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first" style="width: 28.333%;">
                                                <input type="checkbox" data-switchery name="active_status" value="active" data-switchery-size="large" id="active_status"/>
                                                <label for="active_status" class="inline-label">Status (Inactive / Active)</label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Name</label>
                                                    <input type="text" class="md-input required" name="primary_name">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Email</label>
                                                    <input type="email" class="md-input required" name="primary_email">
                                                    <span class="md-input-bar "></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label style="">Phone</label>
                                                    <input type="text" style="height: 30px; width: 100%; margin-top: 12px !important;" class="required only-number phone-primary">
                                                    <input type="hidden" name="primary_phone" class="intTelNo-primary">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Username</label>
                                                    <input type="email" class="md-input required" name="username">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Password</label>
                                                    <input type="text" class="md-input required" name="password">
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-width-1-1">
                                        <button type="submit" class="md-btn md-btn-primary submit_btn">Save</button>
                                        <span style="display: inline-block;vertical-align: middle;margin-left: 14px;">
                                            <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif" alt="Loader" height="24" width="24" class="md-card-loader" id="loader" style="display: none;">
                                        </span>
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