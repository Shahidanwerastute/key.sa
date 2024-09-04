@extends('admin.layouts.template')

@section('content')
    <?php
    if ($site->maintenance_mode == 'on')
    {
        $maintenanceChecked = 'checked';
    }else{
        $maintenanceChecked = '';
    }

    if ($site->mobile_maintenance_mode == 'on')
    {
        $mobile_maintenance_mode = 'checked';
    }else{
        $mobile_maintenance_mode = '';
    }

    if ($site->mobile_maintenance_mode_for_android == 'on') {
        $mobile_maintenance_mode_for_android = 'checked';
    } else {
        $mobile_maintenance_mode_for_android = '';
    }

    if ($site->mobile_maintenance_mode_for_ios == 'on') {
        $mobile_maintenance_mode_for_ios = 'checked';
    } else {
        $mobile_maintenance_mode_for_ios = '';
    }

    if ($site->mobile_maintenance_mode_for_huawei == 'on') {
        $mobile_maintenance_mode_for_huawei = 'checked';
    } else {
        $mobile_maintenance_mode_for_huawei = '';
    }

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


    <div id="page_content">
        <div id="page_content_inner">

            <h4 class="heading_a uk-margin-bottom">Maintenance Mode Settings</h4>
            <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/save_maintenance_text" method="post" class="settings_ajax_form uk-form-stacked" onsubmit="return false;">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-3 uk-width-medium-1-1">
                        <div class="md-card">
                            <div class="md-card-content">
                                <label class="uk-form-label">For Website</label>
                                <div class="uk-form-row">
                                    <label for="settings_page_description">English Content</label>
                                    <textarea cols="30" rows="6" class="md-input no_autosize" name="maintenance_eng_desc"><?php echo $site->maintenance_eng_desc; ?></textarea>
                                </div>
                                <div class="uk-form-row">
                                    <label for="settings_page_description">Arabic Content</label>
                                    <textarea cols="30" rows="6" class="md-input no_autosize" name="maintenance_arb_desc"><?php echo $site->maintenance_arb_desc; ?></textarea>
                                </div>
                                <div class="uk-form-row">
                                    <div class="md-list-content">
                                        <div class="uk-float-right">
                                            <input type="checkbox" data-switchery data-switchery-color="#d32f2f"
                                                   name="maintenance_mode" value="on" id="maintenance_mode" <?php echo $maintenanceChecked; ?>/>
                                        </div>
                                        <span class="md-list-heading">Maintenance Mode</span>
                                        <span class="uk-text-muted uk-text-small">(Off / On)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-width-large-1-3 uk-width-medium-1-1">
                        <div class="md-card">
                            <div class="md-card-content">
                                <label class="uk-form-label">For Mobile</label>
                                <div class="uk-form-row">
                                    <label for="settings_page_description">English Content</label>
                                    <textarea cols="30" rows="6" class="md-input no_autosize" name="mobile_maintenance_eng_desc"><?php echo $site->mobile_maintenance_eng_desc; ?></textarea>
                                </div>
                                <div class="uk-form-row">
                                    <label for="settings_page_description">Arabic Content</label>
                                    <textarea cols="30" rows="6" class="md-input no_autosize" name="mobile_maintenance_arb_desc"><?php echo $site->mobile_maintenance_arb_desc; ?></textarea>
                                </div>
                                <div class="uk-form-row">
                                    <div class="md-list-content">
                                        <div class="uk-float-right">
                                            <input type="checkbox" data-switchery data-switchery-color="#d32f2f"
                                                   name="mobile_maintenance_mode" value="on" id="mobile_maintenance_mode" <?php echo $mobile_maintenance_mode; ?>/>
                                        </div>
                                        <span class="md-list-heading">Generic Maintenance Mode</span>
                                        <span class="uk-text-muted uk-text-small">(Off / On)</span>
                                    </div>
                                </div>
                                <div class="uk-form-row">
                                    <div class="md-list-content">
                                        <div class="uk-float-right">
                                            <input type="checkbox" data-switchery data-switchery-color="#d32f2f"
                                                   name="mobile_maintenance_mode_for_android" value="on" id="mobile_maintenance_mode_for_android" <?php echo $mobile_maintenance_mode_for_android; ?>/>
                                        </div>
                                        <span class="md-list-heading">Android Maintenance Mode</span>
                                        <span class="uk-text-muted uk-text-small">(Off / On)</span>
                                    </div>
                                </div>
                                <div class="uk-form-row">
                                    <div class="md-list-content">
                                        <div class="uk-float-right">
                                            <input type="checkbox" data-switchery data-switchery-color="#d32f2f"
                                                   name="mobile_maintenance_mode_for_ios" value="on" id="mobile_maintenance_mode_for_ios" <?php echo $mobile_maintenance_mode_for_ios; ?>/>
                                        </div>
                                        <span class="md-list-heading">iOS Maintenance Mode</span>
                                        <span class="uk-text-muted uk-text-small">(Off / On)</span>
                                    </div>
                                </div>
                                <div class="uk-form-row">
                                    <div class="md-list-content">
                                        <div class="uk-float-right">
                                            <input type="checkbox" data-switchery data-switchery-color="#d32f2f"
                                                   name="mobile_maintenance_mode_for_huawei" value="on" id="mobile_maintenance_mode_for_huawei" <?php echo $mobile_maintenance_mode_for_huawei; ?>/>
                                        </div>
                                        <span class="md-list-heading">Huawei Maintenance Mode</span>
                                        <span class="uk-text-muted uk-text-small">(Off / On)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-width-large-1-3 uk-width-medium-1-2">
                        <div class="md-card">
                            <div class="md-card-content">
                                <div class="uk-form-row">
                                    <label for="settings_admin_email" class="uk-form-label">Site Language(s)</label>
                                    <div>
                                        <span class="icheck-inline">
                                            <input type="radio" id="radio_demo_inline_1" name="site_language" value="both" <?php echo $both_checked; ?> data-md-icheck/>
                                        <label for="radio_demo_inline_1" class="inline-label">Both</label>
                                    </span>
                                        <span class="icheck-inline">
                                        <input type="radio" name="site_language" id="radio_demo_inline_2" value="eng" <?php echo $eng_checked; ?>  data-md-icheck/>
                                        <label for="radio_demo_inline_2" class="inline-label">English</label>
                                    </span>
                                        <span class="icheck-inline">
                                        <input type="radio" name="site_language" id="radio_demo_inline_3" value="arb" <?php echo $arb_checked; ?>  data-md-icheck/>
                                        <label for="radio_demo_inline_3" class="inline-label">Arabic</label>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $site->id; ?>">

                </div>
                <?php if (custom::rights(12, 'edit'))
                { ?>
                <div class="md-fab-wrapper">
                    <button type="submit" class="md-fab md-fab-primary">
                        <i class="material-icons">&#xE161;</i>
                    </button>
                </div>
                <?php } ?>

            </form>

        </div>
    </div>
@endsection