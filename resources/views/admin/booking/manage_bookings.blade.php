@extends('admin.layouts.template')

@section('content')

    <style>
        .icheck-inline {
            margin-top: 8px;
        }
    </style>

    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin="">
                <div class="uk-width-1-2 uk-text-left">
                    <h4 class="heading_a uk-margin-bottom" style="margin-bottom: 0px !important;">Manage Bookings</h4>
                </div>
            </div>
            <div class="uk-grid" id="section-1" style="margin-top: 40px;">
                <div class="uk-width-medium-1-1">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-6">
                            <input type="text" class="kendo-date-picker" id="from_date" placeholder="From Date">
                        </div>
                        <div class="uk-width-medium-1-6">
                            <input type="text" class="kendo-date-picker" id="to_date" placeholder="To Date">
                        </div>
                        <div class="uk-width-medium-2-6" style="width: 25%;">
                                    <span class="icheck-inline">
                                        <input type="radio" name="export_type" id="export_type" value="bookings"
                                               data-md-icheck checked/>
                                        <label for="export_type" class="inline-label">Bookings</label>
                                    </span>
                            <span class="icheck-inline">
                                        <input type="radio" name="export_type" id="export_type" value="extended_bookings"
                                               data-md-icheck/>
                                        <label for="export_type" class="inline-label">Extended Bookings</label>
                                    </span>
                        </div>
                        <div class="uk-width-medium-1-6">
                            <label for="count_type">Count Type</label><br>
                            <select class="md-input" id="count_type" name="count_type">
                                <option value="-">-</option>
                                <option value="Registration">Registration</option>
                                <option value="Canceled">Canceled</option>
                                <option value="Expired">Expired</option>
                                <option value="Corporate">Corporate</option>
                                <option value="Inquiry">Inquiry</option>
                            </select>
                        </div>
                        <div class="uk-width-medium-1-6">
                            <a class="md-btn md-btn-primary md-btn-small" href="javascript:void(0);" onclick="get_bookings_count_for_export_in_manage_bookings();">
                                Count
                            </a>
                            <a class="md-btn md-btn-success md-btn-small" href="javascript:void(0);" onclick="export_manage_bookings();">
                                Export
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="uk-grid" id="section-2" style="margin-top: 40px;">
                <div class="uk-width-medium-1-1">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-large-2-6 uk-width-medium-2-6">
                            <input class="md-input" type="text" id="search" placeholder="Search"
                                   title="Search (By Reservation Code OR Added Payment Booking ID)"/>
                        </div>
                        <div class="uk-width-large-3-6 uk-width-medium-3-6">
                                    <span class="icheck-inline">
                                        <input type="radio" name="search_type" id="search_type" value="bookings"
                                               data-md-icheck checked/>
                                        <label for="search_type" class="inline-label">Bookings</label>
                                    </span>
                            <span class="icheck-inline">
                                        <input type="radio" name="search_type" id="search_type" value="extended_bookings"
                                               data-md-icheck/>
                                        <label for="search_type" class="inline-label">Extended Bookings</label>
                                    </span>
                            <span class="icheck-inline">
                                        <input type="radio" name="search_type" id="search_type" value="resync"
                                               data-md-icheck/>
                                        <label for="search_type" class="inline-label">Re-Sync</label>
                                    </span>
                        </div>
                        <div class="uk-width-large-1-6 uk-width-medium-1-6">
                            <a class="md-btn md-btn-primary md-btn-small" href="javascript:void(0);" onclick="search_manage_bookings();">
                                Search
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="uk-grid">
                <div class="uk-width-medium-1-1">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-large-1-1 uk-width-medium-1-1" id="response-html">



                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection