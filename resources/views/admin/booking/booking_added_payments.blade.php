@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <div class="uk-grid" data-uk-grid-margin="">
                <div class="uk-width-1-2 uk-text-left">
                    <h4 class="heading_a uk-margin-bottom" style="margin-bottom: 0px !important;">Booking Added Payments</h4>
                </div>
            </div>
            <div class="uk-grid">
                <div class="uk-width-1-2 uk-text-left">
                    <span class="uk-text-muted uk-text-small">Payments not synced yet</span>
                    <h2 class="uk-margin-remove"><span
                                class="countUpMe"><?php echo $unsynced_bookings_count; ?></span></h2>
                </div>
                <div class="uk-width-1-2 uk-text-right">
                    <div class="uk-grid" data-uk-grid-margin="">
                        <div class="uk-width-1-1">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-large-4-6 uk-width-medium-4-6">
                                    <input class="md-input" type="text" name="search" placeholder="Search"
                                           title="Search (By Reservation Code OR Transaction Reference)"/>
                                </div>
                                <div class="uk-width-large-1-6 uk-width-medium-1-6">
                                    <button class="md-btn md-btn-primary md-btn-small" id="search">
                                        Search
                                    </button>
                                </div>
                                <div class="uk-width-large-1-6 uk-width-medium-1-6">
                                    <a href="javascript:void(0);" title="Export Data" id="export_booking_added_payments">
                                        <button class="md-btn md-btn-success md-btn-small" id="search">
                                            Export
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md-card">
                <div class="md-card-content">
                    <div id="BookingAddedPaymentsTable"></div>
                </div>
            </div>
        </div>
    </div>

@endsection