@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/send_notification" method="post"
                              class="settings_ajax_form validate-form" onsubmit="return false;">
                            <input type="hidden" name="chunk_size" value="1000">
                            <div class="md-card-content">
                                <h3 class="heading_a">Send Mobile Notification</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled"><label>Notification
                                                        Title</label><input type="text" name="title"
                                                                            class="md-input required"><span
                                                            class="md-input-bar"></span></div>
                                            </div>

                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <h5>Notification Audience</h5>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class=" uk-width-1-1">
                                                            <span class="icheck-inline">
                                        <input type="radio" name="notification_audience" id="radio_demo_inline_1"
                                               value="android" data-md-icheck checked/>
                                        <label for="radio_demo_inline_1" class="inline-label">Android</label>
                                    </span>
                                                            <span class="icheck-inline">
                                        <input type="radio" id="radio_demo_inline_3" name="notification_audience"
                                               value="ios" data-md-icheck/>
                                        <label for="radio_demo_inline_3" class="inline-label">iOS</label>
                                    </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="uk-form-row uk-width-1-1">
                                        <div class="uk-grid" data-uk-grid-margin="">
                                            <div class="uk-width-medium-1-1 uk-row-first">
                                                <div class="md-input-wrapper md-input-filled">
                                                    <label>Notification Body</label>
                                                    <textarea cols="15" rows="8" class="md-input required"
                                                              name="body"></textarea>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="uk-form-row uk-width-1-1">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>Device Token&nbsp;&nbsp;<small style="color: red;">(Only to be used to send notification to a specific user)</small></label>
                                            <textarea cols="10" rows="3" class="md-input" name="token"></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>--}}
                                    <div class="uk-width-1-1">
                                        <button type="submit" class="md-btn md-btn-primary">Send</button>
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