@extends('admin.layouts.template')

@section('content')
    <style>
        .search_field .md-input-filled > label,
        .search_field .md-input-focus > label {
            top: 2px;
            font-size: 12px;
        }

        .to_be_notified {
            height: 10px;
            width: 10px;
            background-color: red;
            border-radius: 50%;
            display: inline-block;
        }
    </style>
    <div id="page_content">
        <div id="page_content_inner">

            <?php if (isset($_GET['history']) && $_GET['history'] != "") {
                session()->put('bhl', '');
                session()->put('bHistory', 'history');
                $url = custom::baseurl('admin/bookings');

            } else if (isset($_GET['b']) && $_GET['b'] == "human_less") {
                session()->put('bHistory', '');
                session()->put('bhl', 'human_less');
                $url = custom::baseurl('admin/bookings?b=human_less');
            } else {
                session()->put('bHistory', '');
                session()->put('bhl', '');
                $url = custom::baseurl('admin/bookings?history=history');
            }
            ?>

            <div class="md-card">
                <div class="md-card-toolbar add" style="text-align: center;">
                    <?php if(!isset($_GET['history'])){ ?>
                    <?php if (custom::rights(2, 'view'))
                    { ?>
                    <form class="btnSecRightEd" method="post" onsubmit="return false;" id="importBookingForm"
                          action="{{custom::baseurl('admin/bookings/import-booking')}}" enctype="multipart/form-data">
                        <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                             alt="Loader" height="24" width="24" class="md-card-loader" id="importBookingLoader"
                             style="display: none;"/>
                        <input type="file" name="import_booking" id="importFile">
                        <button type="submit"
                                class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                title="Import Bookings To Database">
                            Import Bookings
                        </button>
                    </form>
                    <?php } ?>
                    <?php if (custom::rights(3, 'view'))
                    { ?>
                    <form class="btnSecRightEd" method="post" onsubmit="return false;" id="importCustomersForm"
                          action="{{custom::baseurl('admin/bookings/import-customers')}}" enctype="multipart/form-data">
                        <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                             alt="Loader" height="24" width="24" class="md-card-loader" id="importCustomerLoader"
                             style="display: none;"/>
                        <input type="file" name="import_customer" id="importFileCustomer">
                        <button type="submit"
                                class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                title="Import Customers To Database">
                            Import Customers
                        </button>
                    </form>
                    <?php } ?>
                    <?php if (custom::rights(4, 'view'))
                    { ?>
                    <form class="btnSecRightEd" method="post" onsubmit="return false;" id="importCustomerLoyalty"
                          action="{{custom::baseurl('admin/bookings/import-loyalty')}}" enctype="multipart/form-data">
                        <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                             alt="Loader" height="24" width="24" class="md-card-loader" id="importLoyaltyLoader"
                             style="display: none;"/>
                        <input type="file" name="import_loyalty" id="importLoyaltyInfo">
                        <button type="submit"
                                class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                title="Import Customer Loyalty To Database">
                            Import Loyalty
                        </button>
                    </form>
                    <?php } ?>
                    <?php if (custom::rights(22, 'view'))
                    { ?>
                    <form class="btnSecRightEd" method="post" onsubmit="return false;" id="importCustomerSimahInfo"
                          action="{{custom::baseurl('admin/bookings/importSimahInfo')}}" enctype="multipart/form-data">
                        <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                             alt="Loader" height="24" width="24" class="md-card-loader" id="importSimahLoader"
                             style="display: none;"/>
                        <input type="file" name="import_simah" id="importSimahInfo">
                        <button type="submit"
                                class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                title="Import Customer Simah Info To Database">
                            Import Simah Info
                        </button>
                    </form>
                    <?php } ?>
                    <?php if (custom::rights(50, 'view'))
                    { ?>
                    <form class="btnSecRightEd" method="post" onsubmit="return false;" id="importCorporateInvoices"
                          action="{{custom::baseurl('admin/bookings/importCorporateInvoices')}}"
                          enctype="multipart/form-data">
                        <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                             alt="Loader" height="24" width="24" class="md-card-loader"
                             id="importCorporateInvoicesLoader" style="display: none;"/>
                        <input type="file" name="import_corp_invoices" id="importCorporateInvoicesInfo">
                        <button type="submit"
                                class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                title="Import Corporate Invoices Info To Database">
                            Import Invoices
                        </button>
                    </form>
                    <?php } ?>
                    <?php if (custom::rights(52, 'view'))
                    { ?>
                    <form class="btnSecRightEd" method="post" onsubmit="return false;" id="importCorporateLeaseInvoices"
                          action="{{custom::baseurl('admin/bookings/importCorporateLeaseInvoices')}}"
                          enctype="multipart/form-data">
                        <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                             alt="Loader" height="24" width="24" class="md-card-loader"
                             id="importCorporateLeaseInvoicesLoader" style="display: none;"/>
                        <input type="file" name="import_corp_invoices" id="importCorporateLeaseInvoicesInfo">
                        <button type="submit"
                                class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                title="Import Corporate Invoices Info To Database">
                            Import Lease Invoices
                        </button>
                    </form>
                    <?php } ?>
                    <?php if (custom::rights(49, 'view'))
                    { ?>
                    <form class="btnSecRightEd" method="post" onsubmit="return false;" id="importCustomerBlackListInfo"
                          action="{{custom::baseurl('admin/bookings/importBlackListInfo')}}"
                          enctype="multipart/form-data">
                        <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                             alt="Loader" height="24" width="24" class="md-card-loader" id="importBlackListLoader"
                             style="display: none;"/>
                        <input type="file" name="import_black_list" id="importBlackListInfo">
                        <button type="submit"
                                class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                title="Import Customer Black List Info To Database">
                            Import Black List
                        </button>
                    </form>
                    <?php } ?>
                    <?php if (custom::rights(5, 'view'))
                    { ?>
                    <form action="{{custom::baseurl('admin/bookings/export-booking')}}" method="post"
                          class="export-booking btnSecRightEd" onsubmit="return false;">
                        <input type="hidden" id="hdn_bk_ids" name="booking_ids" value="">
                        {{--<input type="submit" value="Export Data" class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" title="Export To Excel">--}}
                        <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                             alt="Loader" height="24" width="24" class="md-card-loader" id="exportLoader"
                             style="display: none;"/>
                        <button type="submit"
                                class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                title="Export To Excel">
                            Export Data
                        </button>
                    </form>
                    <form action="{{custom::baseurl('admin/export_cancelled_bookings')}}" method="post"
                          class="export-cancelled-booking btnSecRightEd" onsubmit="return false;">
                        <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                             alt="Loader" height="24" width="24" class="md-card-loader"
                             id="exportCancelledBookingsLoader"
                             style="display: none;"/>
                        <input type="text" class="kendo-date-picker" id="from_date" placeholder="From Date">
                        <input type="text" class="kendo-date-picker" id="to_date" placeholder="To Date">
                        <button type="submit"
                                class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                title="Export To Excel">
                            Export Cancelled Bookings
                        </button>
                    </form>
                    <?php } ?>
                        <?php if (custom::rights(56, 'view'))
                        { ?>
                        <div class="btnSecRightEd">
                            <img src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/images/ajax-loader.gif"
                                 alt="Loader" height="24" width="24" class="md-card-loader" id="empty_corporate_invoices_from_db_loader"
                                 style="display: none;"/>
                            <button type="button"
                                    class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light empty_corporate_invoices_from_db"
                                    title="Empty Corporate Invoices From DB" data-url="{{custom::baseurl('admin/empty_corporate_invoices_from_db')}}">
                                Empty Corporate Invoices From DB
                            </button>
                        </div>
                        <?php } ?>
                    <?php } ?>

                    <?php if (!isset($_GET['history']))
                    { ?>
                        <div class="uk-grid">
                            <div class="uk-width-1-1 uk-text-left">
                                <span class="uk-text-muted uk-text-small">Bookings not synced yet</span>
                                <h2 class="uk-margin-remove"><span
                                            class="countUpMe"><?php echo $null_bookings_count; ?></span></h2>
                            </div>
                        </div>
                        <div class="uk-grid">
                            <div class="uk-width-1-1 uk-text-left">
                                <span class="uk-text-muted uk-text-small">Email & PDF pending to be sent</span>
                                <h2 class="uk-margin-remove"><span
                                            class="countUpMe"><?php echo $bookings_count; ?></span></h2>
                            </div>
                        </div>
                    <?php } ?>

                    <h3 class="heading_b md-card-toolbar-heading-text">

                        <?php if (isset($_GET['history']) && $_GET['history'] != "") {
                            echo "History Bookings";
                        } else {
                            echo "Active Booking";
                        }  ?>
                        &nbsp;&nbsp;
                        <a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                           href="<?php echo custom::baseurl('admin/bookings?history=history'); ?>" title="History">
                            <?php if (isset($_GET['history']) && $_GET['history'] != "") {
                                echo "Active Booking";
                            } else {
                                echo "History";
                            }  ?></a>
                        <a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                           href="<?php echo custom::baseurl('admin/pending-bookings'); ?>"
                           title="View Pending Bookings">Pending</a>
                        <?php if (custom::rights(46, 'view')) { ?>
                        <a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                           href="<?php echo custom::baseurl('admin/pending-bookings?paylater=1'); ?>"
                           title="View Corporate Pay Later Bookings">Corporate Pay Later</a>
                        <?php } ?>
                        <a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                           href="<?php echo custom::baseurl('admin/exported-files'); ?>" title="View Exported Files">Exported
                            Files</a>
                        <a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                           href="<?php echo custom::baseurl('admin/exportCampaignData'); ?>"
                           title="Export Campaign Data" target="_blank">Export Campaign Data</a>
                        <a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                           href="<?php echo custom::baseurl('admin/bookings?b=human_less'); ?>"
                           title="Human Less Bookings" target="_blank">Human Less</a>

                    </h3>
                    <div class="uk-width-large-1-2 uk-width-medium-1-2 search_field" style="float: right;">
                        <div class="input-group">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-large-1-2 uk-width-medium-1-2">
                                    <label>Search By Customer Details</label>
                                    <input class="md-input" type="text" id="search_keyword_customer"
                                           title="Empty the search field and click search to clear search."/>
                                </div>
                                <div class="uk-width-large-1-2 uk-width-medium-1-2">
                                    <div class="uk-input-group">
                                        <select name="search_type" id="search_type" data-md-selectize
                                                data-md-selectize-bottom data-uk-tooltip="{pos:'top'}"
                                                title="Select search type">
                                            <option value="">Select...</option>
                                            <option value="ind_id_no">Individual ID</option>
                                            <option value="corp_id_no">Corporate ID</option>
                                            <option value="mobile_no">Mobile</option>
                                            <option value="transaction_id">Transaction ID</option>
                                            <option value="company_code">Company Code</option>
                                        </select>
                                        <span class="uk-input-group-addon">
					                            <a class="md-btn md-btn-success md-btn-small  md-btn-wave-light waves-effect waves-button waves-light"
                                                   href="javascript:void(0);" id="search_bookings_by_customer"
                                                   title="Click to search bookings by customer details.">Search</a>
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-width-large-1-3 uk-width-medium-1-3 search_field" style="float: right;">
                        <div class="uk-input-group">
                            <label>Search By Booking Details</label>
                            <input class="md-input" type="text" id="search_keyword_booking"
                                   title="Empty the search field and click search to clear search."/>
                            <span class="uk-input-group-addon">
                                <a class="md-btn md-btn-success md-btn-small  md-btn-wave-light waves-effect waves-button waves-light"
                                   href="javascript:void(0);" id="search_bookings_by_booking"
                                   title="Click to search bookings by booking details.">Search</a>
                            </span>
                        </div>
                    </div>

                    <?php if(isset($_GET['history']) && $_GET['history'] != ""){ ?>
                    <span style="color: red; display: inline-block; margin: 12px auto 0;" class="historyBooking">Currently you are viewing history bookings</span>
                    <?php } ?>
                </div>
                <div class="md-card-content">
                    <div id="BookingsTable"></div>
                </div>
            </div>

        </div>
    </div>

@endsection