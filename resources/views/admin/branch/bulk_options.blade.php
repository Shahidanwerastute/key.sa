@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <a class="md-btn md-btn-primary md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light"
                           href="<?php echo custom::baseurl('admin/branch'); ?>" title="Click To Go Back To Pricing Page">
                            Go Back
                        </a>
                    </div>

                </div>
                <div class="md-card-content">


                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="md-card">
                                <form action="<?php echo custom::baseurl('/'); ?>/admin/branch/saveBulk" method="post"
                                      class="settings_ajax_form validate-form" onsubmit="return false;">
                                    <div class="md-card-content">
                                        <h3 class="heading_a">Add Branch Schedule Bulk Records</h3>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Select Region</label>
                                                        <select id="region" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}" title="Select Region" name="region_id">
                                                            <option value="">Select...</option>
                                                            <?php foreach ($regions as $region)
                                                            { ?>
                                                            <option value="<?php echo $region->id; ?>"><?php echo $region->eng_title; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                            </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Select City</label>
                                                        <select id="city" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}" title="Select City" name="city_id">
                                                            <option value="">Select...</option>
                                                        </select>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Select Branch</label><br>
                                                        <select id="branch" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}" title="Select Branch" name="branch_id[]" multiple>
                                                            <option value="">Select...</option>
                                                        </select>
                                                            </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>First Shift Opening/Closing Time</label>
                                                            <div class="uk-form-row">
                                                                <div class="uk-grid" data-uk-grid-margin>
                                                                    <div class="uk-width-medium-1-2">
                                                                        <input class="md-input label-fixed required" name="first_shift_opening_time" type="text" id="" data-uk-timepicker>
                                                                    </div>
                                                                    <div class="uk-width-medium-1-2">
                                                                        <input class="md-input label-fixed required" name="first_shift_closing_time" type="text" id="" data-uk-timepicker>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Second Shift Opening/Closing Time</label>
                                                            <div class="uk-form-row">
                                                                <div class="uk-grid" data-uk-grid-margin>
                                                                    <div class="uk-width-medium-1-2">
                                                                        <input class="md-input label-fixed" name="second_shift_opening_time" type="text" id="" data-uk-timepicker>
                                                                    </div>
                                                                    <div class="uk-width-medium-1-2">
                                                                        <input class="md-input label-fixed" name="second_shift_closing_time" type="text" id="" data-uk-timepicker>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Select Off Day(s)</label><br>
                                                            <select id="branch" data-md-selectize data-md-selectize-bottom
                                                                    data-uk-tooltip="{pos:'top'}" class="required" title="Select Off Day(s)" name="off_days[]" multiple>
                                                                <option value="">Select...</option>
                                                                <option value="Monday">Monday</option>
                                                                <option value="Tuesday">Tuesday</option>
                                                                <option value="Wednesday">Wednesday</option>
                                                                <option value="Thursday">Thursday</option>
                                                                <option value="Friday">Friday</option>
                                                                <option value="Saturday">Saturday</option>
                                                                <option value="Sunday">Sunday</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <br>
                                            <div class="uk-width-1-1">
                                                <button type="submit" class="md-btn md-btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="md-card">
                                <form action="<?php echo custom::baseurl('/'); ?>/admin/branch/saveBulkBranchActive" method="post"
                                      class="settings_ajax_form validate-form" onsubmit="return false;">
                                    <div class="md-card-content">
                                        <h3 class="heading_a">Add Branch Active/Inactive Bulk Records</h3>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Select Region</label>
                                                            <select id="region_branch_active" data-md-selectize data-md-selectize-bottom
                                                                    data-uk-tooltip="{pos:'top'}" title="Select Region" name="region_id">
                                                                <option value="">Select...</option>
                                                                <?php foreach ($regions as $region)
                                                                { ?>
                                                                <option value="<?php echo $region->id; ?>"><?php echo $region->eng_title; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Select City</label>
                                                            <select id="city_branch_active" data-md-selectize data-md-selectize-bottom
                                                                    data-uk-tooltip="{pos:'top'}" title="Select City" name="city_id">
                                                                <option value="">Select...</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Select Branch</label><br>
                                                            <select id="branch_branch_active" data-md-selectize data-md-selectize-bottom
                                                                    data-uk-tooltip="{pos:'top'}" title="Select Branch" name="branch_id[]" multiple>
                                                                <option value="">Select...</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="uk-form-row uk-width-1-1">
                                                        <div class="uk-grid" data-uk-grid-margin>
                                                            <div class="uk-width-medium-1-2 uk-row-first">
                                                                <input type="checkbox" data-switchery data-switchery-color="#d32f2f" name="status" value="active" data-switchery-size="large" id="branch_active" />
                                                                <label for="branch_active" class="inline-label"><strong>In-Active / Active</strong></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <br>
                                            <div class="uk-width-1-1">
                                                <button type="submit" class="md-btn md-btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection