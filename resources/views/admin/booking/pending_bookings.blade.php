@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-toolbar">
                    <h3 class="heading_b md-card-toolbar-heading-text"><a href="<?php echo custom::baseurl('admin/bookings'); ?>">Go Back</a></h3>
                </div>
                <div class="md-card-content">

                    <div class="uk-width-large-1-2 uk-width-medium-1-2 search_field" style="float: right;">
                        <div class="input-group">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-large-1-2 uk-width-medium-1-2">
                                    <label>Search By Customer Details</label>
                                    <input class="md-input" type="text" id="pending_search_keyword_customer" title="Empty the search field and click search to clear search."/>
                                </div>
                                <div class="uk-width-large-1-2 uk-width-medium-1-2">
                                    <div class="uk-input-group">
                                        <select name="search_type" id="search_type" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select search type">
                                            <option value="" >Select...</option>
                                            <option value="ind_id_no" >Individual ID</option>
                                            <option value="corp_id_no" >Corporate ID</option>
                                            <option value="mobile_no">Mobile</option>
                                            <option value="transaction_id">Transaction ID</option>
                                            <option value="company_code">Company Code</option>
                                        </select>
                                        <span class="uk-input-group-addon">
                                            <a class="md-btn md-btn-success md-btn-small  md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0);" id="search_pending_bookings_by_customer" title="Click to search bookings by customer details.">Search</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--<div class="uk-width-large-1-3 uk-width-medium-1-3 search_field" style="float: right;">
                        <div class="uk-input-group">
                            <label>Search By Customer Details</label>
                            <input class="md-input" type="text" id="pending_search_keyword_customer" title="Empty the search field and click search to clear search."/>
                            <span class="uk-input-group-addon">
                                <a class="md-btn md-btn-success md-btn-small  md-btn-wave-light waves-effect waves-button waves-light"
                                   href="javascript:void(0);" id="search_pending_bookings_by_customer"
                                   title="Click to search bookings by customer details.">Search</a>
                            </span>
                        </div>
                    </div>--}}

                    <div class="uk-width-large-1-2 uk-width-medium-1-2 search_field" style="float: right;">
                        <div class="uk-input-group">
                            <label>Search By Booking Details</label>
                            <input class="md-input" type="text" id="pending_search_keyword_booking" title="Empty the search field and click search to clear search."/>
                            <span class="uk-input-group-addon">
                                <a class="md-btn md-btn-success md-btn-small  md-btn-wave-light waves-effect waves-button waves-light"
                                   href="javascript:void(0);" id="search_pending_bookings_by_booking"
                                   title="Click to search bookings by booking details.">Search</a>
                            </span>
                        </div>
                    </div>


                    <div class="uk-width-large-1-2 uk-width-medium-1-2" style="float: right;">
                        <?php if(isset($_GET['paylater']) && $_GET['paylater'] != ''){ ?>
                            <form method="post" action="<?php echo custom::baseurl('admin/bookings/exportPayLaterPendingBookings'); ?>" target="_blank">
                        <?php }else{ ?>
                            <form method="post" action="<?php echo custom::baseurl('admin/bookings/exportPendingBookings'); ?>" target="_blank">
                            <?php } ?>
                            <div class="uk-input-group">
                                <label>Select Date To Filter</label>
                                <input class="md-input" type="text" id="filter_date" name="filter_date" data-uk-datepicker="{format:'DD-MM-YYYY'}"/>
                            <span class="uk-input-group-addon">
                                <a class="md-btn md-btn-success md-btn-small  md-btn-wave-light waves-effect waves-button waves-light"
                                   href="javascript:void(0);" id="filter_pending_bookings"
                                   title="Click to filter results by selected date">Filter</a>
                                <button type="submit" class="md-btn md-btn-primary md-btn-small  md-btn-wave-light waves-effect waves-button waves-light" title="Click to export records">Export</button>
                            </span>
                            </div>
                            <?php if(isset($_GET['paylater']) && $_GET['paylater'] != ''){ ?>
                                <input type="hidden" name="paylater" value="1">
                            <?php } ?>
                        </form>
                    </div>
                    <div class="md-card-content">
                        <h3 class="heading_a uk-margin-bottom">
                            <?php if(isset($_GET['paylater']) && $_GET['paylater'] != ''){
                                echo "Corporate (Pay Later) Pending Bookings";
                            }else{
                               echo "Pending Bookings";
                            }?>
                        </h3>
                        <div id="PendingBookingsTable" style="margin-top: 50px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection