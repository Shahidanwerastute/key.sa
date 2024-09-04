@extends('admin.layouts.template')
@section('content')
    <?php //print_r(custom::api_settings());exit(); ?>
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="md-card">
                        <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/save_humanless_instructions" method="post" class="settings_ajax_form" onsubmit="return false;">
                            <div class="md-card-content">
                                <h3 class="heading_a">Human Less Instructions</h3>
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-width-medium-1-2">
                                        <label>English Content</label>
                                        <div class="uk-form-row">
                                            <textarea cols="30" rows="8" class="md-input eng_desc" name="eng_hl_instructions" id="eng_hl_instructions"><?php echo $site->eng_hl_instructions; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-2">
                                        <label>Arabic Content</label>
                                        <div class="uk-form-row">
                                            <textarea cols="30" rows="8" class="md-input arb_desc" name="arb_hl_instructions" id="arb_hl_instructions"><?php echo $site->arb_hl_instructions; ?></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $site->id; ?>">
                                    <?php if (custom::rights(47, 'edit'))
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