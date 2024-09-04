@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <?php if (isset($_REQUEST['expired'])) { ?>
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <a class="md-btn md-btn-primary md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo custom::baseurl('admin/promotions_offers'); ?>" title="View Current Promotions">
                            Current Promotions
                        </a>
                    </div>
                </div>
                <div class="md-card-content">
                    <div id="ExpiredPromotionsTable"></div>
                </div>
            </div>
            <?php } else { ?>
            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <a class="md-btn md-btn-primary md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light export-coupons" data-promotion-id="0" href="javascript:void(0)" title="Export All Coupons To Excel">
                            Export All Coupons
                        </a>
                        <a class="md-btn md-btn-danger md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="<?php echo custom::baseurl('admin/promotions_offers?expired=1'); ?>" title="View Expired Promotions">
                            View Expired Promotions
                        </a>
                    </div>
                </div>
                <div class="md-card-content">
                    <div id="CurrentPromotionsTable"></div>
                </div>
            </div>
            <?php } ?>

        </div>
    </div>

@endsection