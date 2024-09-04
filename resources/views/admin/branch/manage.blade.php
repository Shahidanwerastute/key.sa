@extends('admin.layouts.template')

@section('content')

    <?php $site_settings = custom::site_settings(); ?>

    <div id="page_content">
        <div id="page_content_inner">

            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        @if ($site_settings->general_timings_for_delivery_branches == 1)
                            <a class="md-btn md-btn-danger md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo custom::baseurl('admin/branch/deactivate-general-timing-for-delivery-branches'); ?>" title="Deactivate General Timing For Delivery Branches">
                                Deactivate
                            </a>
                        @else
                            <a class="md-btn md-btn-success md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo custom::baseurl('admin/branch/activate-general-timing-for-delivery-branches'); ?>" title="Activate General Timing For Delivery Branches">
                                Activate
                            </a>
                        @endif
                    </div>
                    <h3 class="heading_b md-card-toolbar-heading-text">General Opening / Closing Timings For Delivery Branches</h3>
                </div>
                <div class="md-card-content">
                    <div id="GeneralTimingForDeliveryBranchesTable"></div>
                </div>
            </div>

            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <a class="md-btn md-btn-primary md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo custom::baseurl('admin/branches-bulk-options'); ?>" title="Click To Go To Bulk Option Page">
                            Bulk Options
                        </a>
                        <a class="md-btn md-btn-danger md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo custom::baseurl('admin/branch?only_limousine=1'); ?>" title="Click To Go To Bulk Option Page">
                            Limousine Branches
                        </a>
                    </div>
                    <h3 class="heading_b md-card-toolbar-heading-text">All Branches</h3>

                </div>
                <div class="md-card-content">
                    <div id="BranchesTable"></div>
                </div>
            </div>

        </div>
    </div>

    <script>
        var only_limousine_branches = 0;
    </script>

@endsection