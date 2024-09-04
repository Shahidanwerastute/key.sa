@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">

            <?php if (isset($_REQUEST['expired'])) { ?>
                <div class="md-card">
                    <div class="md-card-toolbar">
                        <div class="md-card-toolbar-actions">
                            <a class="md-btn md-btn-success md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo custom::baseurl('admin/pricing'); ?>" title="View Active Car Prices">
                                View Active Car Prices
                            </a>
                        </div>
                        <h3 class="heading_b md-card-toolbar-heading-text">Expired Prices</h3>
                    </div>
                    {{--<div class="md-card-toolbar">

                    </div>--}}
                    <br>
                    <div class="md-card-content">
                        <div id="ExpiredPricingTable"></div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="md-card">
                    <div class="md-card-toolbar">

                        <div class="md-card-toolbar-actions">


                            <?php if (custom::rights(45, 'add'))
                            { ?>

                            <form class="btnSecRightEd" method="post" onsubmit="return false;" id="importBookingForm"
                                  action="{{custom::baseurl('admin/importCorporatePricing')}}" enctype="multipart/form-data" style="display: inline; margin-right: 5em;">
                                <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif" alt="Loader" height="24" width="24" class="md-card-loader" id="importBookingLoader" style="display: none;"/>
                                <input type="file" name="import_booking" id="importFile">
                                <button type="submit"
                                        class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light"
                                        title="Import Bookings To Database">
                                    Import Corporate Pricing
                                </button>
                            </form>


                            <?php } if (custom::rights(18, 'add'))
                            { ?>

                            <a class="md-btn md-btn-primary md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo custom::baseurl('admin/pricing-bad-log'); ?>" title="Click To Go To Pricing Bad Logs">
                                Pricing Bad Logs
                            </a>
                            <a class="md-btn md-btn-primary md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo custom::baseurl('admin/pricing-bulk-options'); ?>" title="Click To Go To Bulk Option Page">
                                Bulk Options
                            </a>
                            <?php } ?>

                                <a class="md-btn md-btn-danger md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo custom::baseurl('admin/pricing?expired=1'); ?>" title="View Expired Car Prices">
                                    View Expired Car Prices
                                </a>
                        </div>

                        <h3 class="heading_b md-card-toolbar-heading-text">Pricing</h3>

                    </div>
                    {{--<div class="md-card-toolbar">

                    </div>--}}
                    <br>
                    <div class="md-card-content">
                        <div id="PricingTable"></div>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>

@endsection