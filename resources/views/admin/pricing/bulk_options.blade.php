@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <a class="md-btn md-btn-primary md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light"
                           href="<?php echo custom::baseurl('admin/pricing'); ?>" title="Click To Go Back To Pricing Page">
                            Go Back
                        </a>
                    </div>

                </div>
                <div class="md-card-content">


                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2" id="bulk_add">
                            <div class="md-card">
                                <form action="<?php echo custom::baseurl('/'); ?>/admin/pricing/saveBulkPrice" method="post"
                                      class="settings_ajax_form validate-form" onsubmit="return false;">
                                    <div class="md-card-content">
                                        <h3 class="heading_a">Add Bulk Records</h3>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Car Category</label>
                                                        <select id="car_categories" data-md-selectize
                                                                data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                                                title="Select Car Category" name="car_category">
                                                            <option value="">Select...</option>
                                                            <?php foreach ($car_categories as $car_category)
                                                            { ?>
                                                            <option value="<?php echo $car_category->id; ?>"><?php echo $car_category->eng_title; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                            </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                        <label>Car Group</label>
                                                        <select id="car_groups" data-md-selectize
                                                                data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                                                title="Select Car Group" name="car_group">
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
                                                        <label>Car Type</label>
                                                        <select id="car_types" data-md-selectize
                                                                data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                                                title="Select Car Type" name="car_type">
                                                            <option value="">Select...</option>
                                                        </select>
                                                            </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                        <label>Car Model</label><br>
                                                        <select id="car_models" data-md-selectize
                                                                data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                                                title="Select Car Model" name="car_model[]" multiple>
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
                                                        <label>Renting Type</label>
                                                        <select id="" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}"
                                                                title="Select Renting Type" name="renting_type_id">
                                                            <?php foreach ($renting_types as $renting_type)
                                                            { ?>
                                                            <option value="<?php echo $renting_type->id; ?>"><?php echo $renting_type->type; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                            </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Price Value</label>
                                                            <input type="text" name="price" class="md-input required"><span
                                                                    class="md-input-bar"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled"><label>Applies
                                                                From</label><input type="text" name="applies_from"
                                                                                   class="md-input required" data-uk-datepicker="{format:'DD.MM.YYYY'}"><span
                                                                    class="md-input-bar"></span></div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled"><label>Applies
                                                                To</label><input type="text" name="applies_to"
                                                                                 class="md-input required" data-uk-datepicker="{format:'DD.MM.YYYY'}"><span
                                                                    class="md-input-bar"></span></div>
                                                    </div>
                                                </div>
                                            </div>
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
                                                                data-uk-tooltip="{pos:'top'}" title="Select Branch" name="branch_id">
                                                            <option value="">Select...</option>
                                                        </select>
                                                            </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Select User Type</label>
                                                        <select id="" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}" title="Select Customer Type" name="customer_type">
                                                            <option value="individual_customer">Individual Customer</option>
                                                            <option value="corporate_customer">Corporate Customer</option>
                                                        </select>
                                                            </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled">
                                                            <label>Select Charge Element</label>
                                                            <select id="" data-md-selectize data-md-selectize-bottom
                                                                    data-uk-tooltip="{pos:'top'}" title="Select User Type" name="charge_element">
                                                                <option value="Rent">Rent</option>
                                                                <option value="CDW">CDW</option>
                                                                <option value="GPS">GPS</option>
                                                                <option value="Extra Driver">Extra Driver</option>
                                                                <option value="Baby Seat">Baby Seat</option>
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


                        <div class="uk-width-medium-1-2" id="bulk_update" style="display: none;">
                            <div class="md-card">
                                <form action="<?php echo custom::baseurl('/'); ?>/admin/settings/smtp_settings" method="post"
                                      class="settings_ajax_form" onsubmit="return false;">
                                    <div class="md-card-content">
                                        <h3 class="heading_a">SMTP Settings</h3>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <select id="" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}"
                                                                title="Select Car Category">
                                                            <option value="">Select...</option>
                                                            <option value="a">Item A</option>
                                                            <option value="b">Item B</option>
                                                            <option value="c">Item C</option>
                                                        </select>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <select id="" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}" title="Select Car Group">
                                                            <option value="">Select...</option>
                                                            <option value="a">Item A</option>
                                                            <option value="b">Item B</option>
                                                            <option value="c">Item C</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <select id="" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}" title="Select Car Type">
                                                            <option value="">Select...</option>
                                                            <option value="a">Item A</option>
                                                            <option value="b">Item B</option>
                                                            <option value="c">Item C</option>
                                                        </select>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <select id="" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}" title="Select Car Model">
                                                            <option value="">Select...</option>
                                                            <option value="a">Item A</option>
                                                            <option value="b">Item B</option>
                                                            <option value="c">Item C</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <select id="" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}"
                                                                title="Select Renting Type">
                                                            <option value="">Select...</option>
                                                            <option value="a">Item A</option>
                                                            <option value="b">Item B</option>
                                                            <option value="c">Item C</option>
                                                        </select>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled"><label>Price
                                                                Value</label><input type="text" name="password"
                                                                                    class="md-input"><span
                                                                    class="md-input-bar"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled"><label>Applies
                                                                From</label><input type="text" name="password"
                                                                                   class="md-input"><span
                                                                    class="md-input-bar"></span></div>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <div class="md-input-wrapper md-input-filled"><label>Applies
                                                                To</label><input type="text" name="password"
                                                                                 class="md-input"><span
                                                                    class="md-input-bar"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <select id="" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}" title="Select Region">
                                                            <option value="">Select...</option>
                                                            <option value="a">Item A</option>
                                                            <option value="b">Item B</option>
                                                            <option value="c">Item C</option>
                                                        </select>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <select id="" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}" title="Select City">
                                                            <option value="">Select...</option>
                                                            <option value="a">Item A</option>
                                                            <option value="b">Item B</option>
                                                            <option value="c">Item C</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-form-row uk-width-1-1">
                                                <div class="uk-grid" data-uk-grid-margin="">
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <select id="" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}" title="Select Branch">
                                                            <option value="">Select...</option>
                                                            <option value="a">Item A</option>
                                                            <option value="b">Item B</option>
                                                            <option value="c">Item C</option>
                                                        </select>
                                                    </div>
                                                    <div class="uk-width-medium-1-1 uk-row-first">
                                                        <select id="" data-md-selectize data-md-selectize-bottom
                                                                data-uk-tooltip="{pos:'top'}" title="Select User Type">
                                                            <option value="">Select...</option>
                                                            <option value="a">Item A</option>
                                                            <option value="b">Item B</option>
                                                            <option value="c">Item C</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <br>

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