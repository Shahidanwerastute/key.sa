@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-content">
                    <h3 class="heading_a">Add a Category</h3>
                    <form action="<?php echo base_url(); ?>admin/category/action" method="post" onsubmit="return false"
                          class="ajax_form">
                        <div class="uk-grid" data-uk-grid-margin>

                            <input type="hidden" name="form_type" value="save">
                            <div class="uk-width-medium-1-2">
                                <select id="select_demo_5" data-md-selectize data-md-selectize-bottom
                                        data-uk-tooltip="{pos:'top'}" title="Select with tooltip" name="parent_id">
                                    <option value="">Select Parent Category</option>
                                    <option value="0">Top</option>
                                    <?php
                                    getCategoriesOptionListing();
                                    /*if($categories)
                                    {
                                        foreach($categories as $category)
                                        {
                                           echo '<option value="'.$category->id.'">'.$category->eng_name.'</option>';
                                        }
                                    }*/
                                    ?>

                                </select>
                            </div>
                            <div class="uk-width-medium-1-4">
                                <input type="checkbox" data-switchery data-switchery-size="large" checked
                                       id="switch_demo_large" name="active"/>
                                <label for="switch_demo_large" class="inline-label">Active</label>

                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>English Name</label>
                                    <input type="text" class="md-input" value="" name="eng_name"/>
                                </div>

                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Arabic Name</label>
                                    <input type="text" class="md-input" value="" name="arb_name"/>
                                </div>

                            </div>

                            <div class="uk-width-medium-1-2">

                                <div class="uk-form-row">
                                    <label>English Description</label>
                                    <textarea cols="30" rows="4" class="md-input" name="eng_content"></textarea>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">

                                <div class="uk-form-row">
                                    <label>Arabic Description</label>
                                    <textarea cols="30" rows="4" class="md-input" name="arb_content"></textarea>
                                </div>
                            </div>


                            <div class="uk-width-large-1-1">
                                <h3 class="heading_a">
                                    Upload Category Image

                                </h3>
                                <div class="uk-grid">
                                    <div class="uk-width-1-1">
                                        <div id="file_upload-drop" class="uk-file-upload">
                                            <p class="uk-text">Drop file to upload</p>
                                            <p class="uk-text-muted uk-text-small uk-margin-small-bottom">or</p>
                                            <a class="uk-form-file md-btn">choose file<input id="file_upload-select"
                                                                                             type="file"
                                                                                             name="image"></a>
                                        </div>
                                        <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                            <div class="uk-progress-bar" style="width:0">0%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="uk-width-large-1-1">

                                <span class="uk-input-group-addon"><a class="md-btn submit_ajax_form"
                                                                      href="javascript:void(0);">Save</a></span>
                            </div>

                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
    @endsection