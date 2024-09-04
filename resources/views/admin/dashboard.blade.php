@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-content"  style="min-height: 150px !important;">
                    <h3 class="heading_a uk-margin-bottom">Bookings Done</h3>
                    <div class="uk-overflow-container" style="min-height: 100px !important;">
            <div
                    class="uk-grid uk-grid-width-large-1-5 uk-grid-width-medium-1-2 uk-grid-medium uk-sortable sortable-handler hierarchical_show"
                    data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">

                            <span class="uk-text-muted uk-text-small">Today</span>
                            <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $bookings_count_for_today; ?></span></h2>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">

                            <span class="uk-text-muted uk-text-small">Yesterday</span>
                            <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $bookings_count_for_yesterday; ?></span></h2>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">

                            <span class="uk-text-muted uk-text-small">Last 7 days</span>
                            <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $bookings_count_for_last_week; ?></span></h2>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">

                            <span class="uk-text-muted uk-text-small">Last Month</span>
                            <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $bookings_count_for_last_month; ?></span></h2>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="md-card">
                        <div class="md-card-content">

                            <span class="uk-text-muted uk-text-small">Total</span>
                            <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $bookings_count_total; ?></span></h2>
                        </div>
                    </div>
                </div>
            </div>
                        </div>
                        </div>
                        </div>
            <div class="md-card">
                <div class="md-card-content"  style="min-height: 150px !important;">
                    <h3 class="heading_a uk-margin-bottom">Cancelled Bookings</h3>
                    <div class="uk-overflow-container" style="min-height: 100px !important;">
                        <div
                                class="uk-grid uk-grid-width-large-1-5 uk-grid-width-medium-1-2 uk-grid-medium uk-sortable sortable-handler hierarchical_show"
                                data-uk-sortable data-uk-grid-margin>
                            <div>
                                <div class="md-card">
                                    <div class="md-card-content">

                                        <span class="uk-text-muted uk-text-small">Today</span>
                                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $cancelled_count_for_today; ?></span></h2>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="md-card">
                                    <div class="md-card-content">

                                        <span class="uk-text-muted uk-text-small">Yesterday</span>
                                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $cancelled_count_for_yesterday; ?></span></h2>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="md-card">
                                    <div class="md-card-content">

                                        <span class="uk-text-muted uk-text-small">Last 7 days</span>
                                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $cancelled_count_for_last_week; ?></span></h2>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="md-card">
                                    <div class="md-card-content">

                                        <span class="uk-text-muted uk-text-small">Last Month</span>
                                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $cancelled_count_for_last_month; ?></span></h2>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="md-card">
                                    <div class="md-card-content">

                                        <span class="uk-text-muted uk-text-small">Total</span>
                                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $cancelled_count_total; ?></span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md-card">
                <div class="md-card-content"  style="min-height: 150px !important;">
                    <h3 class="heading_a uk-margin-bottom">Total Sales</h3>
                    <div class="uk-overflow-container" style="min-height: 100px !important;">
                        <div class="uk-grid uk-grid-width-large-1-5 uk-grid-width-medium-1-2 uk-grid-medium uk-sortable sortable-handler hierarchical_show"
                                data-uk-sortable data-uk-grid-margin>
                            <div>
                                <div class="md-card">
                                    <div class="md-card-content">

                                        <span class="uk-text-muted uk-text-small">Today</span>
                                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $sales_for_today; ?></span> SAR</h2>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="md-card">
                                    <div class="md-card-content">

                                        <span class="uk-text-muted uk-text-small">Yesterday</span>
                                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $sales_for_yesterday; ?></span> SAR</h2>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="md-card">
                                    <div class="md-card-content">

                                        <span class="uk-text-muted uk-text-small">Last 7 days</span>
                                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $sales_for_last_week; ?></span> SAR</h2>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="md-card">
                                    <div class="md-card-content">

                                        <span class="uk-text-muted uk-text-small">Last Month</span>
                                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $sales_for_last_month; ?></span> SAR</h2>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="md-card">
                                    <div class="md-card-content">

                                        <span class="uk-text-muted uk-text-small">Total</span>
                                        <h2 class="uk-margin-remove"><span class="countUpMe"><?php echo $sales_total; ?></span> SAR</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- circular charts -->
            <div class="uk-grid uk-grid-width-small-1-2 uk-grid-width-large-1-2 uk-grid-width-xlarge-1-2 uk-text-center uk-sortable sortable-handler"
                    id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin style="display: none;">
                <div>
                    <div class="md-card md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div>
                                <h3>
                                    Registered Users
                                </h3>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- circular charts -->
            <div class="uk-grid uk-grid-width-small-1-1 uk-grid-width-large-1-1 uk-grid-width-xlarge-1-1 uk-text-center uk-sortable sortable-handler" id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div id="piechart" style="width: auto; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
                <div class="uk-width-medium-1-1">
                    <div class="">
                        <div class="md-card-content">
                            <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
                                <div class="uk-width-medium-1-2">
                                    <div class="md-card">
                                        <div class="md-card-content">
                                            <div id="reg_users_chart" class="chartist"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="md-card">
                                        <div class="md-card-content">
                                            <div id="inquiries_chart" class="chartist"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
            <!-- tasks -->
            <!--<div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
                <div class="uk-width-medium-1-1">
                    <div class="md-card">
                        <div class="md-card-content" style="min-height: 150px !important;">
                            <h3 class="heading_a uk-margin-bottom">Latest Logs</h3>
                            <div class="uk-overflow-container">
                                <table id="dt_default" class="uk-table" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th class="uk-text-nowrap">User</th>
                                        <th class="uk-text-nowrap">Activity</th>
                                        <th class="uk-text-nowrap">Section</th>
                                        <th class="uk-text-nowrap uk-text-right">Activity On</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    /*foreach ($logs as $log)
                                    {
                                    if ($log->type == 'add') {
                                        $class = 'uk-badge uk-badge-primary';
                                    } elseif ($log->type == 'update') {
                                        $class = 'uk-badge uk-badge-success';
                                    } elseif ($log->type == 'delete') {
                                        $class = 'uk-badge uk-badge-danger';
                                    } elseif ($log->type == 'export') {
                                        $class = 'uk-badge uk-badge-warning';
                                    } elseif ($log->type == 'import') {
                                        $class = 'uk-badge';
                                    }*/
                                    ?>
                                    <tr class="uk-table-middle">
                                        <td class="uk-width-3-10 uk-text-nowrap"><?php //echo $log->user_name; ?></td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><span
                                                    class="<?php //echo $class; ?>"><?php //echo $log->message; ?></span>
                                        </td>
                                        <td class="uk-width-2-10 uk-text-nowrap"><?php //echo $log->section; ?></td>
                                        <td class="uk-width-2-10 uk-text-right uk-text-muted uk-text-small"><?php //echo date('d.m.Y\, h:i:s A', strtotime($log->created_at)); ?></td>
                                    </tr>
                                    <?php //} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->




        </div>
    </div>
@endsection