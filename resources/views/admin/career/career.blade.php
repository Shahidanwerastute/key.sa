@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <?php if (custom::rights(57, 'view')) { ?>
                <form action="{{custom::baseurl('admin/export_career')}}" method="post"
                      class="export-career btnSecRightEd " onsubmit="return false;" style="margin: 5px;">
                    <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                         alt="Loader" height="24" width="24" class="md-card-loader"
                         id="exportCareerLoader"
                         style="display: none;"/>
                    <input type="text" class="kendo-date-picker" id="from_date" placeholder="From Date">
                    <input type="text" class="kendo-date-picker" id="to_date" placeholder="To Date">

                    <button type="submit"
                            class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                            title="Export To Excel" style="min-width: 50px !important;">
                        Export
                    </button>
                </form>
            <?php } ?>
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                    </div>
                    <h3 class="heading_b md-card-toolbar-heading-text">Career</h3>

                </div>
                <div class="md-card-content">
                    <div id="careerTable"></div>
                </div>
            </div>
        </div>
    </div>
@endsection