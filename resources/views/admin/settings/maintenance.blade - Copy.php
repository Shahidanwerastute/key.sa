@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/save_maintenance_text" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Maintenance Mode Settings</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-form-row uk-width-1-3">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>English Content (For Website)</label>
                                            <textarea cols="30" rows="8" class="md-input no_autosize" name="maintenance_eng_desc"><?php echo $site->maintenance_eng_desc; ?></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-3" style="margin-top: 0;">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>Arabic Content (For Website)</label>
                                            <textarea cols="30" rows="8" class="md-input no_autosize" name="maintenance_arb_desc"><?php echo $site->maintenance_arb_desc; ?></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>
                                    <?php
                                    if ($site->maintenance_mode == 'on')
                                    {
                                        $maintenanceChecked = 'checked';
                                    }else{
                                        $maintenanceChecked = '';
                                    } ?>
                                    <div class="uk-form-row uk-width-1-3">
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-1 uk-row-first">
                                                <input type="checkbox" data-switchery data-switchery-color="#d32f2f" name="maintenance_mode" value="on" data-switchery-size="large" id="maintenance_mode" <?php echo $maintenanceChecked; ?>/>
                                                <label for="maintenance_mode" class="inline-label">Maintenance Mode  (For Website)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-form-row uk-width-1-3">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>English Content (For Mobile Apps)</label>
                                            <textarea cols="30" rows="8" class="md-input no_autosize" name="mobile_maintenance_eng_desc"><?php echo $site->mobile_maintenance_eng_desc; ?></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>
                                    <div class="uk-form-row uk-width-1-3">
                                        <div class="md-input-wrapper md-input-filled">
                                            <label>Arabic Content (For Mobile Apps)</label>
                                            <textarea cols="30" rows="8" class="md-input no_autosize" name="mobile_maintenance_arb_desc"><?php echo $site->mobile_maintenance_arb_desc; ?></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                    </div>
                                    <?php
                                    if ($site->mobile_maintenance_mode == 'on')
                                    {
                                        $mobileMaintenanceChecked = 'checked';
                                    }else{
                                        $mobileMaintenanceChecked = '';
                                    } ?>
                                    <div class="uk-form-row uk-width-1-3">
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-1 uk-row-first">
                                                <input type="checkbox" data-switchery data-switchery-color="#d32f2f" name="mobile_maintenance_mode" value="on" data-switchery-size="large" id="mobile_maintenance_mode" <?php echo $mobileMaintenanceChecked; ?>/>
                                                <label for="mobile_maintenance_mode" class="inline-label">Maintenance Mode (For Mobile Apps)</label>
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

                                    <input type="hidden" name="id" value="<?php echo $site->id; ?>">
                                    <?php if (custom::rights(24, 'edit'))
                                    { ?>
                                    <div class="uk-width-1-1">
                                        <button type="submit" class="md-btn md-btn-primary">Update</button>
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