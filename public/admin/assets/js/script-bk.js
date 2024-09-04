$(document).ready(function () {
    //Prepare jTable
    $('#BookingsTable').jtable({
        title: '',
        paging: true, //Enable paging
        sorting: true, //Enable sorting
        defaultSorting: 'id DESC',
        selecting: true, //Enable selecting
        multiselect: true, //Allow multiple selecting
        selectingCheckboxes: true, //Show checkboxes on first column
        pageSize: 10, //Set page size (default: 10)
        selectOnRowClick: false, //Enable this to only select using checkboxes
        openChildAsAccordion: true, //Enable this line to show child tabes as accordion style
        actions: {
            listAction: base_url + '/admin/bookings/getAllActiveReservations?tbl=in'
        },
        fields: {
            id: {
                key: true,
                create: false,
                edit: false,
                list: false
            },
            PersonalInfo: {
                title: '',
                width: '2%',
                sorting: false,
                edit: false,
                create: false,
                display: function (PersonalInfo) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-userinfo.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="User Personal Information" />');
                    //Open child table when user clicks the image
                    $img.click(function () {
                        //alert('here');
                        $('#BookingsTable').jtable('openChildTable',
                            $img.closest('tr'),
                            {
                                title: '<h3 style="margin-top: 20px;">User Detail</h3>',
                                actions: {
                                    listAction: base_url + '/admin/users/getSingleUserInfo?booking_id=' + PersonalInfo.record.id + '&user_type=' + PersonalInfo.record.type
                                },
                                fields: {
                                    DisplayCW: {
                                        title: '',
                                        edit: false,
                                        create: false,
                                        display: function (data) {
                                            var html;
                                            var dob = '';
                                            var id_exp_date = '';
                                            var license_exp_date = '';
                                            var idImage = '';
                                            var license_id_type_title = '';
                                            var nationality_title = '';
                                            var licenseImage = '';
                                            var id_country = '';
                                            var license_country = '';
                                            var job_title = '';
                                            var sponsor = '';
                                            var street_address = '';
                                            var district_address = '';
                                            var user_type = data.record.user_type;
                                            if (user_type == 'individual_customer' || user_type == 'guest') {
                                                if (user_type == 'individual_customer')
                                                    userType = 'Registered User (Individual)';
                                                else
                                                    userType = 'Guest User';
                                                if (data.record.dob != '0000-00-00' || data.record.dob != '1970-01-01')
                                                    dob = data.record.dob;
                                                if (data.record.id_expiry_date != '0000-00-00' || data.record.id_expiry_date != '1970-01-01')
                                                    id_exp_date = data.record.id_expiry_date;
                                                if (data.record.license_expiry_date != '0000-00-00' || data.record.license_expiry_date != '1970-01-01')
                                                    license_exp_date = data.record.license_expiry_date;
                                                if (data.record.nationality_title != null)
                                                    nationality_title = data.record.nationality_title;
                                                if (data.record.license_id_type_title != null)
                                                    license_id_type_title = data.record.license_id_type_title;

                                                if (data.record.id_country != null)
                                                    id_country = data.record.id_country;
                                                if (data.record.license_country != null)
                                                    license_country = data.record.license_country;
                                                if (data.record.job_title != null)
                                                    job_title = data.record.job_title;
                                                if (data.record.sponsor != null)
                                                    sponsor = data.record.sponsor;
                                                if (data.record.street_address != null)
                                                    street_address = data.record.street_address;
                                                if (data.record.district_address != null)
                                                    district_address = data.record.district_address;

                                                if (data.record.license_image != '')
                                                    licenseImage = '<img src="' + base_url + '/public/uploads/' + data.record.license_image + '" width="30" height="30" style="max-width: 30px;" title="Click to open in new window" />';
                                                if (data.record.id_image != '')
                                                    idImage = '<img src="' + base_url + '/public/uploads/' + data.record.id_image + '" width="30" height="30" style="max-width: 30px;" title="Click to open in new window" />';

                                                html = '<div class="md-card uk-margin-medium-bottom"><div class="md-card-content"><div class="uk-overflow-container"><table class="uk-table"><div class="md-card-toolbar"><div class="md-card-toolbar-actions"><a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)" title="Print User Information">Print</a></div><h2 class="heading_b md-card-toolbar-heading-text">User Personal Information</h2></div><tbody><tr><td width="30%">User Type</td><td>' + userType + '</td></tr><tr><td>Name</td><td>' + data.record.first_name + ' ' + data.record.last_name + '</td></tr><tr><td>Email</td><td>' + data.record.email + '</td></tr><tr><td>Mobile</td><td>' + data.record.mobile_no + '</td></tr><tr><td>ID Type</td><td>' + data.record.id_type_title + ', ' + data.record.id_version + '</td></tr><tr><td>ID Number</td><td>' + data.record.id_no + '</td></tr><tr><td>Nationality</td><td>' + nationality_title + '</td></tr>' +
                                                    '<tr><td colspan="2"><strong>User Personal Information (Optional)</strong></td></tr>' + '<tr><td>Job Title</td><td>' + job_title + '</td></tr> <tr><td>Sponsor</td><td>' + sponsor + '</td></tr> <tr><td>Street Address</td><td>' + street_address + '</td></tr> <tr><td>District Address</td><td>' + district_address + '</td></tr> <tr><td>DOB</td><td>' + dob + '</td></tr><tr><td>ID Expiry Date</td><td>' + id_exp_date + ' (' + data.record.id_date_type + ') ' + '</td></tr><tr><td>ID Country</td><td>' + id_country + '</td></tr><tr><td>ID Card Copy</td><td><a href="' + base_url + '/public/pdf/' + data.record.id_image + '" target="_blank">Click here</a></td></tr><tr><td>Driving License No.</td><td>' + data.record.license_no + '</td></tr><tr><td>License Country</td><td>' + license_country + '</td></tr><tr><td>Driving License ID Type.</td><td>' + license_id_type_title + '</td></tr><tr><td>License Expiry Date</td><td>' + license_exp_date + '</td></tr><tr><td>Driving License Copy</td><td><a href="' + base_url + '/public/pdf/' + data.record.license_image + '" target="_blank">Click here</a></td></tr></tbody></table></div></div></div>';
                                            } else if (user_type == 'corporate_customer') {
                                                var sponsor = 'N/A';
                                                if (data.record.sponsor != null && data.record.sponsor != '') {
                                                    sponsor = data.record.sponsor;
                                                }
                                                html = '<div class="md-card uk-margin-medium-bottom">' +
                                                    '<div class="md-card-content"><div class="uk-overflow-container">' +
                                                    '<table class="uk-table"><div class="md-card-toolbar">' +
                                                    '<div class="md-card-toolbar-actions">' +
                                                    '<a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)" title="Print User Information">Print</a>' +
                                                    '</div>' +
                                                    '<h2 class="heading_b md-card-toolbar-heading-text">User Information</h2>' +
                                                    '</div>' +
                                                    '<tbody>' +
                                                    '' +
                                                    '<tr>' +
                                                    '<td width="30%">User Type</td>' +
                                                    '<td>Registered User (Corporate)</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td>Company Name</td>' +
                                                    '<td>' + data.record.company_name_en + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td>Company Code</td>' +
                                                    '<td>' + data.record.company_code + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td>Active Status</td>' +
                                                    '<td>' + data.record.active_status + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td colspan="2">' +
                                                    '<strong>Primary Contact Information</strong>' +
                                                    '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td>Contact Name</td>' +
                                                    '<td>' + data.record.primary_name + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td>Contact Position</td>' +
                                                    '<td>' + data.record.primary_position + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td>Contact Email</td>' +
                                                    '<td>' + data.record.primary_email + '</td>' +
                                                    '</tr>' +
                                                    '<tr><td>Contact Number.</td>' +
                                                    '<td>' + data.record.primary_phone + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td colspan="2">' +
                                                    '<strong>Secondary Contact Information</strong>' +
                                                    '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td>Contact Name</td>' +
                                                    '<td>' + data.record.secondary_name + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td>Contact Position</td><td>' + data.record.secondary_position + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td>Contact Email</td>' +
                                                    '<td>' + data.record.secondary_email + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td>Contact Number.</td>' +
                                                    '<td>' + data.record.secondary_phone + '</td>' +
                                                    '</tr>' +
                                                    '<tr>' +
                                                    '<td colspan="2">' +
                                                    '<strong>Driver Information</strong>' +
                                                    '</td>' +
                                                    '</tr>' +
                                                    '<tr><td>Name</td><td>' + data.record.first_name + ' ' + data.record.last_name + '</td></tr>' +
                                                    '<tr><td>Id Type</td><td>' + data.record.eng_id_type + '</td></tr>' +
                                                    '<tr><td>ID Number</td><td>' + data.record.id_no + '</td></tr>' +
                                                    '<tr><td>Email</td><td>' + data.record.email + '</td></tr>' +
                                                    '<tr><td>Mobile No.</td><td>' + data.record.mobile_no + '</td></tr>' +
                                                    '<tr><td>Gender</td><td>' + data.record.gender + '</td></tr>' +
                                                    '<tr><td>License Number</td><td>' + data.record.license_no + '</td></tr>' +
                                                    '<tr><td>Sponsor</td><td>' + sponsor + '</td></tr>' +
                                                    '</tbody>' +
                                                    '</table>' +
                                                    '</div>' +
                                                    '</div>' +
                                                    '</div>';
                                            }

                                            return html;
                                        }
                                    }
                                },
                                rowInserted: function (event, data) {
                                }
                            }, function (data) { //opened handler
                                data.childTable.jtable('load');
                            });
                    });
                    //Return image to show on the person row
                    return $img;
                }
            },
            Bookings: {
                title: '',
                width: '2%',
                sorting: false, //Enable sorting
                edit: false,
                create: false,
                display: function (PersonalInfo) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-other-bookings.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Customer Other Bookings" />');
                    //Open child table when user clicks the image
                    $img.click(function () {
                        //alert('here');
                        $('#BookingsTable').jtable('openChildTable',
                            $img.closest('tr'),
                            {
                                sorting: true,
                                defaultSorting: 'id DESC',
                                title: '<h3 style="margin-top: 20px;">Other Bookings</h3>',
                                actions: {
                                    listAction: base_url + '/admin/bookings/getAllReservationsForUser?booking_id=' + PersonalInfo.record.id + '&user_type=' + PersonalInfo.record.type + '&jtSorting=id'
                                },
                                fields: {
                                    type_of_booking: {
                                        edit: false,
                                        create: false,
                                        title: 'Type',
                                        width: '5%',
                                        display: function (data) {
                                            var type_of_booking;
                                            if (data.record.type == 'corporate_customer') {
                                                type_of_booking = 'Corporate';
                                            } else if (data.record.type == 'individual_customer') {
                                                type_of_booking = 'Individual';
                                            } else if (data.record.type == 'guest') {
                                                type_of_booking = 'Guest';
                                            }
                                            return '<span>' + type_of_booking + '</span>';
                                        }
                                    },
                                    is_delivery_mode: {
                                        edit: false,
                                        create: false,
                                        title: 'Delivery / Pickup',
                                        width: '5%',
                                        display: function (data) {
                                            var pickup_or_delivery;
                                            if (data.record.is_delivery_mode == 'yes') {
                                                pickup_or_delivery = 'Delivery';
                                            } else {
                                                pickup_or_delivery = 'Pickup';
                                            }
                                            return '<span>' + pickup_or_delivery + '</span>';
                                        }
                                    },
                                    booking_source: {
                                        edit: false,
                                        create: false,
                                        title: 'Booking Source',
                                        width: '5%',
                                        display: function (data) {
                                            var source;
                                            if (data.record.booking_source === 'ios') {
                                                source = 'Mobile / IOS';
                                            } else if (data.record.booking_source === 'android') {

                                                source = 'Mobile / Android';
                                            } else if (data.record.booking_source === 'mobile') {

                                                source = 'Mobile Website';
                                            } else {
                                                source = 'Website';
                                            }
                                            return '<span>' + source + '</span>';
                                        }
                                    },
                                    reservation_code: {
                                        title: 'Reservation Code',
                                        width: '5%'
                                    },
                                    car_eng_title: {
                                        title: 'Car Model',
                                        width: '5%'
                                    },
                                    branch_eng_from: {
                                        title: 'From Location',
                                        width: '5%'
                                    },
                                    branch_eng_to: {
                                        title: 'To Location',
                                        width: '5%'
                                    },
                                    from_date: {
                                        title: 'From Date',
                                        width: '5%'
                                    },
                                    to_date: {
                                        title: 'To Date',
                                        width: '5%'
                                    },
                                    total_sum: {
                                        title: 'Paid Amount',
                                        width: '5%'
                                    },
                                    payment_method: {
                                        title: 'Payment Method',
                                        width: '5%'
                                    },
                                    app_version: {
                                        title: 'App Version',
                                        width: '10%',
                                        display: function (data) {
                                            var appVersion;
                                            if (data.record.booking_source === 'ios' && data.record.app_version != '' && data.record.app_version != null) {
                                                appVersion = 'I - ' + data.record.app_version;
                                            } else if (data.record.booking_source === 'android' && data.record.app_version != '' && data.record.app_version != null) {

                                                appVersion = 'A - ' + data.record.app_version;
                                            } else {
                                                appVersion = 'N/A';
                                            }
                                            return '<span>' + appVersion + '</span>';
                                        }
                                    }
                                },
                                rowInserted: function (event, data) {
                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                }
                            }, function (data) { //opened handler
                                data.childTable.jtable('load');
                            });
                    });
                    //Return image to show on the person row
                    return $img;
                }
            },
            RemainingInfo: {
                title: '',
                width: '2%',
                sorting: false,
                edit: false,
                create: false,
                display: function (PersonalInfo) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-detail.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Booking Detail" />');
                    //Open child table when user clicks the image
                    $img.click(function () {
                        //alert('here');
                        $('#BookingsTable').jtable('openChildTable',
                            $img.closest('tr'),
                            {
                                title: '<h3 style="margin-top: 20px;">Booking Detail</h3>',
                                actions: {
                                    listAction: base_url + '/admin/bookings/getSingleBookingInfo?booking_id=' + PersonalInfo.record.id + '&user_type=' + PersonalInfo.record.type
                                },
                                fields: {
                                    DisplayCW: {
                                        title: '',
                                        edit: false,
                                        create: false,
                                        display: function (data) {
                                            var pickup_or_delivery_type;
                                            if (data.record.type == 'corporate_customer') {
                                                user_type = 'Corporate Customer';
                                            } else if (data.record.type == 'individual_customer') {
                                                user_type = 'Individual Customer';
                                            } else if (data.record.type == 'guest') {
                                                user_type = 'Guest';
                                            }
                                            if (data.record.is_delivery_mode == 'yes') {
                                                pickup_or_delivery_type = 'Delivery';
                                            } else {
                                                pickup_or_delivery_type = 'Pickup';
                                            }

                                            return '<div class="md-card uk-margin-medium-bottom"><div class="md-card-content"><div class="uk-overflow-container"><table class="uk-table"><div class="md-card-toolbar"><div class="md-card-toolbar-actions"><a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)" title="Print Booking Details">Print</a></div><h2 class="heading_b md-card-toolbar-heading-text">Booking Details</h2></div><tbody><tr><td width="30%">Car Model</td><td>' + data.record.car_eng_title + ' ' + data.record.car_model_year + '</td></tr><tr><td>From Location</td><td>' + data.record.branch_eng_from + ', ' + data.record.eng_city_from + ', ' + data.record.eng_region_from + '</td></tr><tr><td>To Location</td><td>' + data.record.branch_eng_to + ',' + data.record.eng_city_to + ', ' + data.record.eng_region_to + '</td></tr><tr><td>From Date</td><td>' + data.record.from_date + '</td></tr><tr><td>To Date</td><td>' + data.record.to_date + '</td></tr><tr><td>Car Model Oracle #</td><td>' + data.record.oracle_ref_no + '</td></tr><tr><td>Booking Status</td><td>' + data.record.booking_status + '</td></tr><tr><td>Sync</td><td>' + data.record.sync + '</td></tr><tr><td>Synced At</td><td>' + data.record.synced_at + '</td></tr><tr><td>User Type</td><td>' + user_type + '</td></tr><tr><td>Delivery / Pickup</td><td>' + pickup_or_delivery_type + '</td></tr> <tr><td>Pickup Coordinates</td><td>' + data.record.pickup_delivery_lat_long + '</td></tr><tr><td>Return Coordinates</td><td>' + data.record.dropoff_delivery_lat_long + '</td></tr><tr><td>Pickup Coordinates Details</td><td>' + data.record.pickup_delivery_location_details + '</td></tr><tr><td>Return Coordinates Details</td><td>' + data.record.dropoff_delivery_location_details + '</td></tr><tr><td>Created At</td><td>' + data.record.created_at + '</td></tr><tr><td>Edited At</td><td>' + data.record.updated_at + '</td></tr></tbody></table></div></div></div>';
                                        }
                                    }
                                },
                                rowInserted: function (event, data) {
                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                }
                            }, function (data) { //opened handler
                                data.childTable.jtable('load');
                            });
                    });
                    //Return image to show on the person row
                    return $img;
                }
            },
            PaymentDetails: {
                title: '',
                width: '2%',
                sorting: false,
                edit: false,
                create: false,
                display: function (PersonalInfo) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-payment-detail.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Payment Information" />');
                    //Open child table when user clicks the image
                    $img.click(function () {
                        //alert('here');
                        $('#BookingsTable').jtable('openChildTable',
                            $img.closest('tr'),
                            {
                                title: '<h3 style="margin-top: 20px;">Payment Detail</h3>',
                                actions: {
                                    listAction: base_url + '/admin/bookings/getPaymentDetailsForBooking?booking_id=' + PersonalInfo.record.id + '&user_type=' + PersonalInfo.record.type
                                },
                                fields: {
                                    DisplayCW: {
                                        title: '',
                                        edit: false,
                                        create: false,
                                        display: function (data) {
                                            var html;
                                            var vat_text = '';
                                            if (data.record.vat_applied > 0) {
                                                vat_text += '<tr><td><strong><i>VAT Applied (' + data.record.vat_percentage + ' %)</i></strong></td><td><strong><i>' + data.record.vat_applied + ' SAR</i></strong></td></tr>';
                                            }
                                            var user_type = data.record.type;
                                            html = '<div class="md-card uk-margin-medium-bottom"><div class="md-card-content"><div class="uk-overflow-container"><table class="uk-table"><div class="md-card-toolbar"><div class="md-card-toolbar-actions"><a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)" title="Print Payment Details">Print</a></div><h2 class="heading_b md-card-toolbar-heading-text">Payment Details</h2></div><tbody>';
                                            if (data.record.payment_method != null) {
                                                html += '<tr><td width="30%"><strong><i>Payment Method</i></strong></td><td><strong><i>' + data.record.payment_method + '<i></i></strong></td></tr>';
                                                if (data.record.payment_method == 'Credit Card') {
                                                    html += '<tr><td width="30%"><strong><i>Transaction No.</i></strong></td><td><strong><i>' + data.record.transaction_id + '<i></i></strong></td></tr>';
                                                }

                                                if (data.record.payment_method == 'Sadad') {
                                                    html += '<tr><td width="30%"><strong><i>Transaction No.</i></strong></td><td><strong><i>' + data.record.s_transaction_id + '<i></i></strong></td></tr>';
                                                    html += '<tr><td width="30%"><strong><i>Invoice ID.</i></strong></td><td><strong><i>' + data.record.s_invoice_id + '<i></i></strong></td></tr>';
                                                }
                                            }
                                            html += '<tr><td>Rental Amount (Rent Per Day x Days)</td><td>' + data.record.rent_price + ' x ' + data.record.no_of_days + ' = ' + parseFloat(data.record.rent_price).toFixed(2) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                            if (parseInt(data.record.cdw_price) > 0 || parseInt(data.record.gps_price) > 0 || parseInt(data.record.extra_driver_price) > 0 || parseInt(data.record.baby_seat_price) > 0) {
                                                html += '<tr><td colspan="2"><strong><i>Extra Services</i></strong></td></tr>';
                                                if (data.record.cdw_price != null && parseInt(data.record.cdw_price) > 0) {
                                                    html += '<tr><td>CDW</td><td>' + data.record.cdw_price + ' SAR' + ' x ' + data.record.no_of_days + ' = ' + parseFloat(data.record.cdw_price).toFixed(2) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                                }
                                                if (data.record.gps_price != null && parseInt(data.record.gps_price) > 0) {
                                                    html += '<tr><td>GPS</td><td>' + data.record.gps_price + ' SAR' + ' x ' + data.record.no_of_days + ' = ' + parseFloat(data.record.gps_price).toFixed(2) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                                }
                                                if (data.record.extra_driver_price != null && parseInt(data.record.extra_driver_price) > 0) {
                                                    html += '<tr><td>Extra Driver</td><td>' + data.record.extra_driver_price + ' SAR' + ' x ' + data.record.no_of_days + ' = ' + parseFloat(data.record.extra_driver_price).toFixed(2) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                                }
                                                if (data.record.baby_seat_price != null && parseInt(data.record.baby_seat_price) > 0) {
                                                    html += '<tr><td>Baby Car Protection Seat</td><td>' + data.record.baby_seat_price + ' SAR' + ' x ' + data.record.no_of_days + ' = ' + parseFloat(data.record.baby_seat_price).toFixed(2) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                                }
                                            }

                                            if (data.record.is_delivery_mode == 'yes' && (data.record.delivery_charges != null && parseInt(data.record.delivery_charges) > 0)) {
                                                html += '<tr><td>Delivery Charges</td><td>' + data.record.delivery_charges + ' SAR</td></tr>';
                                            }

                                            if (data.record.dropoff_charges != null && parseInt(data.record.dropoff_charges) > 0) {
                                                html += '<tr><td>Dropoff Charges</td><td>' + data.record.dropoff_charges + ' SAR</td></tr>';
                                            }

                                            if (data.record.loyalty_card_id != null && parseInt(data.record.loyalty_card_id) > 0) {
                                                html += '<tr><td>Loyalty Card Used</td><td>' + data.record.loyalty_card_used + '</td></tr>';
                                            }

                                            if (data.record.promotion_offer_id != null && parseInt(data.record.promotion_offer_id) > 0) {
                                                html += '<tr><td colspan="2"><strong><i>Promotion Offer</i></strong></td></tr>';
                                                if (data.record.promotion_offer_id != null && parseInt(data.record.promotion_offer_id) > 0) {
                                                    html += '<tr><td>Promotion Offer Used</td><td>' + data.record.eng_title + ', ' + data.record.type + '</td></tr>';
                                                }
                                                if (data.record.promotion_code_used != null) {
                                                    html += '<tr><td>Promo Code Used</td><td>' + data.record.promotion_code_used + '</td></tr>';
                                                }
                                            }

                                            if (data.record.redeem_points != null) {
                                                html += '<tr><td>Redeem Points Used</td><td>' + data.record.redeem_points + ' Points (' + data.record.redeem_discount_availed + ' SAR Discounted)</td></tr>';
                                            }

                                            if (data.record.discount_price != null && parseInt(data.record.discount_price) > 0) {
                                                if(data.record.walkin_cronjob == '1'){
                                                    html += '<tr><td><strong>Discounted Amount</strong></td><td>'+data.record.discount_price+'%'+'</td></tr>';
                                                }else{
                                                    html += '<tr><td><strong>Discounted Amount</strong></td><td>' + data.record.discount_price + ' SAR' + ' x ' + data.record.no_of_days + ' = ' + parseInt(data.record.discount_price) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                                }

                                            }
                                            html += vat_text;
                                            html += '<tr><td><b><i>Total Amount Paid</i></b></td><td><strong><i>' + data.record.total_sum + ' SAR</i></strong></td></tr>';

                                            if (data.record.booking_status == 'Cancelled') {
                                                html += '<tr><td colspan="2"><strong><i>Cancellation Details</i></strong></td></tr>';
                                                if (data.record.cancel_time != null) {
                                                    html += '<tr><td>Cancellation Time</td><td>' + data.record.cancel_time + '</td></tr>';
                                                }
                                                if (data.record.cancel_charges != null && parseInt(data.record.cancel_charges) > 0) {
                                                    html += '<tr><td>Cancellation Charges</td><td>' + data.record.cancel_charges + ' SAR</td></tr>';
                                                }
                                                var refundableAmount = parseFloat(data.record.total_sum) - parseFloat(data.record.cancel_charges);
                                                html += '<tr><td><strong>Refundable Amount</strong></td><td><strong>' + refundableAmount + ' SAR</strong></td></tr>';
                                            }
                                            html += '</tbody></table></div></div></div>';
                                            return html;
                                        }
                                    }
                                },
                                rowInserted: function (event, data) {
                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                }
                            }, function (data) { //opened handler
                                data.childTable.jtable('load');
                            });
                    });
                    //Return image to show on the person row
                    return $img;
                }
            },
            type_of_booking: {
                edit: false,
                create: false,
                title: 'Type',
                width: '5%',
                display: function (data) {
                    var type_of_booking;
                    if (data.record.type == 'corporate_customer') {
                        type_of_booking = 'Corporate';
                    } else if (data.record.type == 'individual_customer') {
                        type_of_booking = 'Individual';
                    } else if (data.record.type == 'guest') {
                        type_of_booking = 'Guest';
                    }
                    return '<span>' + type_of_booking + '</span>';
                }
            },
            is_delivery_mode: {
                edit: false,
                create: false,
                title: 'Delivery / Pickup',
                width: '5%',
                display: function (data) {
                    var pickup_or_delivery;
                    if (data.record.is_delivery_mode == 'yes') {
                        pickup_or_delivery = 'Delivery';
                    } else {
                        pickup_or_delivery = 'Pickup';
                    }
                    return '<span>' + pickup_or_delivery + '</span>';
                }
            },
            booking_source: {
                edit: false,
                create: false,
                title: 'Booking Source',
                width: '5%',
                display: function (data) {
                    var source;
                    if (data.record.booking_source === 'ios') {
                        source = 'Mobile / IOS';
                    } else if (data.record.booking_source === 'android') {

                        source = 'Mobile / Android';
                    } else if (data.record.booking_source === 'mobile') {

                        source = 'Mobile Website';
                    } else {
                        source = 'Website';
                    }
                    return '<span>' + source + '</span>';
                }
            },
            reservation_code: {
                title: 'Reservation Code',
                width: '10%'
            },
            oracle_reference_number: {
                title: 'Oasis Contract No.',
                width: '10%',
                display: function (data) {
                    if (data.record.oracle_reference_number != '' && data.record.oracle_reference_number != null) {
                        return data.record.oracle_reference_number;
                    } else {
                        return 'N/A';
                    }
                }
            },
            car_eng_title: {
                title: 'Car Model',
                width: '10%',
                display: function (data) {
                    var car_title = data.record.car_type_eng_title + ' ' + data.record.car_eng_title;
                    return '<span>' + car_title + '</span>';
                }
            },
            branch_eng_from: {
                title: 'From Location',
                width: '10%'
            },
            branch_eng_to: {
                title: 'To Location',
                width: '10%'
            },
            from_date: {
                title: 'From Date',
                width: '10%'
            },
            to_date: {
                title: 'To Date',
                width: '10%'
            },
            booking_status: {
                title: 'Booking Status',
                width: '10%'
            },
            sync: {
                title: 'Sync Status',
                width: '10%'
            },
            app_version: {
                title: 'App Version',
                width: '10%',
                display: function (data) {
                    var appVersion;
                    if (data.record.booking_source === 'ios' && data.record.app_version != '' && data.record.app_version != null) {
                        appVersion = 'I - ' + data.record.app_version;
                    } else if (data.record.booking_source === 'android' && data.record.app_version != '' && data.record.app_version != null) {

                        appVersion = 'A - ' + data.record.app_version;
                    } else {
                        appVersion = 'N/A';
                    }
                    return '<span>' + appVersion + '</span>';
                }
            }
        },
        //Register to selectionChanged event to hanlde events
        selectionChanged: function () {
            //Get all selected rows
            var $selectedRows = $('#BookingsTable').jtable('selectedRows');
            var bk_ids = [];
            //$('#BookingsTable').empty();
            if ($selectedRows.length > 0) {
                //Show selected rows
                $selectedRows.each(function () {
                    var record = $(this).data('record-key');
                    bk_ids.push(record);
                });
                var ids = bk_ids.join(',');
                $('#hdn_bk_ids').val(ids);
            } else {
                //No rows selected
                $('#hdn_bk_ids').val('');
            }
        },
    });
    $('#BookingsTable').jtable('load');

    $('#inquiryTable').jtable({
        title: '',
        paging: true, //Enable paging
        sorting: true, //Enable sorting
        defaultSorting: 'id DESC',
        pageSize: 10, //Set page size (default: 10)
        actions: {
            listAction: base_url + '/admin/inquiries/getAllInquiries?tbl=in',
        },
        fields: {
            id: {
                key: true,
                create: false,
                edit: false,
                list: false
            },
            RemainingInfo: {
                title: '',
                width: '2%',
                sorting: false,
                edit: false,
                create: false,
                display: function (Info) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/inquiry-detail.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Detail" />');
                    //Open child table when user clicks the image
                    $img.click(function () {
                        $('#inquiryTable').jtable('openChildTable',
                            $img.closest('tr'),
                            {
                                title: '<h3 style="margin-top: 20px;">Detail</h3>',
                                actions: {
                                    listAction: base_url + '/admin/inquiries/getInquiryDetail?id=' + Info.record.id,
                                },
                                fields: {
                                    DisplayDetail: {
                                        title: '',
                                        edit: false,
                                        create: false,
                                        display: function (data) {
                                            return '<div class="md-card uk-margin-medium-bottom"><div class="md-card-content"><div class="uk-overflow-container"><table class="uk-table"><div class="md-card-toolbar"><div class="md-card-toolbar-actions"></div><h2 class="heading_b md-card-toolbar-heading-text">Message</h2></div><tbody><tr><td>' + data.record.message + '</td></tr></tbody></table></div></div></div>';
                                        }
                                    }
                                },
                                rowInserted: function (event, data) {
                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                }
                            }, function (data) { //opened handler
                                data.childTable.jtable('load');
                            });
                    });
                    //Return image to show on the person row
                    return $img;
                }
            },
            name: {
                title: 'Name',
                width: '10%'
            },
            email: {
                title: 'Email',
                width: '10%'
            },
            mobile: {
                title: 'Mobile',
                width: '10%'
            },
            eng_country: {
                title: 'Country',
                width: '10%'
            },
            inquiry_type_title: {
                title: 'Inquiry Type',
                width: '10%'
            },
            created_at: {
                title: 'Received At',
                width: '10%'
            }
        }
    });
    $('#inquiryTable').jtable('load');
    // career Jtable
    $('#careerTable').jtable({
        title: '',
        paging: true, //Enable paging
        sorting: true, //Enable sorting
        defaultSorting: 'id DESC',
        pageSize: 10, //Set page size (default: 10)
        actions: {
            listAction: base_url + '/admin/career/getAllCareers?tbl=cr',
        },
        fields: {
            id: {
                key: true,
                create: false,
                edit: false,
                list: false
            },
            RemainingInfo: {
                title: '',
                width: '2%',
                sorting: false,
                edit: false,
                create: false,
                display: function (Info) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/career-detail.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Detail" />');
                    //Open child table when user clicks the image
                    $img.click(function () {
                        $('#careerTable').jtable('openChildTable',
                            $img.closest('tr'),
                            {
                                title: '<h3 style="margin-top: 20px;">Detail</h3>',
                                actions: {
                                    listAction: base_url + '/admin/career/getCareerDetail?id=' + Info.record.id,
                                },
                                fields: {
                                    DisplayDetail: {
                                        title: '',
                                        edit: false,
                                        create: false,
                                        display: function (data) {
                                            var html = "";
                                            html = '<div class="md-card uk-margin-medium-bottom">' +
                                                '<div class="md-card-content">' +
                                                '<div class="uk-overflow-container">' +
                                                '<table class="uk-table"><div class="md-card-toolbar">' +
                                                '<div class="md-card-toolbar-actions">' +
                                                '<a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)" title="Print User Information">Print</a></div><h2 class="heading_b md-card-toolbar-heading-text">' +
                                                'Career Detail' +
                                                '</h2></div><tbody><tr>' +
                                                '<td>Nationality' +
                                                '</td><td>' + data.record.nationality + '</td></tr>' +
                                                '<tr><td>Department</td><td>' + data.record.eng_title + '</td>' +

                                                '<tr><td>City</td><td>' + data.record.city + '</td>' +
                                                '<tr><td>Education</td><td>' + data.record.qualification + '</td>' +
                                                '<tr><td>Company Name</td><td>' + data.record.company_name + '</td>' +
                                                '<tr><td>Job Title</td><td>' + data.record.job_title + '</td>' +
                                                '<tr><td>From Date</td><td>' + data.record.from_date + '</td>' +
                                                '<tr><td>To Date</td><td>' + data.record.to_date + '</td>' +
                                                '<tr><td>Language(s)</td><td>' + data.record.language + '</td>'

                                            ;
                                            if (data.record.cv !== '') {
                                                html += '</tr><tr><td>CV</td><td><a href="' + base_url + '/public/uploads/' + data.record.cv + '" download>Download</a> </td>';
                                            }
                                            html += '</tr></tbody>' +
                                                '</table>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>';
                                            return html;
                                        }
                                    }
                                },
                                rowInserted: function (event, data) {
                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                }
                            }, function (data) { //opened handler
                                data.childTable.jtable('load');
                            });
                    });
                    //Return image to show on the person row
                    return $img;
                }
            },
            name: {
                title: 'Name',
                width: '10%'
            },
            email: {
                title: 'Email',
                width: '10%'
            },
            mobile: {
                title: 'Mobile',
                width: '10%'
            },
            dob: {
                title: 'Date of birth',
                width: '10%'
            },
            created_at: {
                title: 'Received At',
                width: '10%'
            }
        }
    });
    $('#careerTable').jtable('load');

    // For Cars Table
    $(function () {
        // crud table
        altair_crud_table_for_car_models.init();
    });
    altair_crud_table_for_car_models = {
        init: function () {
            $('#CarsTable').jtable({
                title: '<h3>Car Categories</h3>',
                paging: true, //Enable paging
                sorting: true, //Enable sorting
                defaultSorting: 'eng_title ASC',
                pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    data.deleteConfirmMessage = 'Are you sure to delete car category " ' + data.record.eng_title + ' " ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: carCategoryActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    CarGroups: {
                        title: 'Car Groups',
                        width: '2%',
                        paging: true, //Enable paging
                        sorting: true, //Enable sorting
                        defaultSorting: 'id DESC',
                        pageSize: 10, //Set page size (default: 10)
                        edit: false,
                        create: false,
                        display: function (CategoryData) {
                            //Create an image that will be used to open child table
                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/car-group.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see car groups" />');
                            //Open child table when user clicks the image
                            $img.click(function () {
                                //alert('here');
                                $('#CarsTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: '<h3>Car Groups</h3>',
                                        paging: true, //Enable paging
                                        sorting: true, //Enable sorting
                                        defaultSorting: 'eng_title ASC',
                                        pageSize: 10, //Set page size (default: 10)
                                        deleteConfirmation: function (CategoryData) {
                                            CategoryData.deleteConfirmMessage = 'Are you sure to delete car group " ' + CategoryData.record.eng_title + ' " ?';
                                        },
                                        formCreated: function (event, data) {
                                            reInitDesignFix(data);
                                        },
                                        actions: carGroupActions(CategoryData),
                                        fields: {
                                            car_category_id: {
                                                type: 'hidden',
                                                create: true,
                                                edit: true,
                                                list: false,
                                                defaultValue: CategoryData.record.id
                                            },
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            CarTypes: {
                                                title: 'Car Types',
                                                width: '2%',
                                                sorting: false,
                                                edit: false,
                                                create: false,
                                                display: function (CarGroupData) {
                                                    //Create an image that will be used to open child table
                                                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/car-type.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see car types" />');
                                                    //Open child table when user clicks the image
                                                    $img.click(function () {
                                                        $('#CarsTable').jtable('openChildTable',
                                                            $img.closest('tr'),
                                                            {
                                                                title: '<h3>Car Types</h3>',
                                                                paging: true, //Enable paging
                                                                sorting: true, //Enable sorting
                                                                defaultSorting: 'eng_title ASC',
                                                                pageSize: 10, //Set page size (default: 10)
                                                                deleteConfirmation: function (CarGroupData) {
                                                                    CarGroupData.deleteConfirmMessage = 'Are you sure to delete car type " ' + CarGroupData.record.eng_title + ' " ?';
                                                                },
                                                                formCreated: function (event, data) {
                                                                    reInitDesignFix(data);
                                                                },
                                                                actions: carTypeActions(CarGroupData),
                                                                fields: {
                                                                    car_group_id: {
                                                                        type: 'hidden',
                                                                        create: true,
                                                                        edit: true,
                                                                        list: false,
                                                                        defaultValue: CarGroupData.record.id
                                                                    },
                                                                    id: {
                                                                        key: true,
                                                                        create: false,
                                                                        edit: false,
                                                                        list: false
                                                                    },
                                                                    // car models from here
                                                                    CarModels: {
                                                                        title: 'Car Models',
                                                                        width: '2%',
                                                                        sorting: false,
                                                                        edit: false,
                                                                        create: false,
                                                                        display: function (CarTypeData) {
                                                                            //Create an image that will be used to open child table
                                                                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/car-model.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see car models" />');
                                                                            //Open child table when user clicks the image
                                                                            $img.click(function () {
                                                                                $('#CarsTable').jtable('openChildTable',
                                                                                    $img.closest('tr'),
                                                                                    {
                                                                                        title: '<h3>Car Models</h3>',
                                                                                        paging: true, //Enable paging
                                                                                        sorting: true, //Enable sorting
                                                                                        defaultSorting: 'id ASC',
                                                                                        pageSize: 10, //Set page size (default: 10)
                                                                                        deleteConfirmation: function (CarTypeData) {
                                                                                            CarTypeData.deleteConfirmMessage = 'Are you sure to delete car model " ' + CarTypeData.record.eng_title + ' " ?';
                                                                                        },
                                                                                        formCreated: function (event, data) {

                                                                                            UploadedFile = "";

                                                                                            data.form.attr('enctype', 'multipart/form-data');

                                                                                            $("#FileUpload1").uploadFile({

                                                                                                url: base_url + "/admin/page/ajaxUploadFile",

                                                                                                fileName: "file",

                                                                                                showProgress: true,

                                                                                                multiple: false,

                                                                                                onSuccess: function (files, data, xhr) {

                                                                                                    //UploadedFile = data;
                                                                                                    $('#image1_id').val(data);

                                                                                                }

                                                                                            });
                                                                                            reInitDesignFix(data);
                                                                                        },
                                                                                        actions: carModelActions(CarTypeData),
                                                                                        fields: {
                                                                                            car_type_id: {
                                                                                                type: 'hidden',
                                                                                                create: true,
                                                                                                edit: true,
                                                                                                list: false,
                                                                                                defaultValue: CarTypeData.record.id
                                                                                            },
                                                                                            id: {
                                                                                                key: true,
                                                                                                create: false,
                                                                                                edit: false,
                                                                                                list: false
                                                                                            },
                                                                                            eng_title: {
                                                                                                title: 'Eng Title',
                                                                                                width: '40%'
                                                                                            },
                                                                                            arb_title: {
                                                                                                title: 'Arb Title',
                                                                                                width: '40%'
                                                                                            },
                                                                                            year: {
                                                                                                title: 'Model Year',
                                                                                                width: '10%'
                                                                                            },
                                                                                            transmission: {
                                                                                                title: 'Transmission Type',
                                                                                                width: '10%',
                                                                                                options: {
                                                                                                    'Auto': 'Automatic',
                                                                                                    'Manual': 'Manual'
                                                                                                }
                                                                                            },
                                                                                            no_of_bags: {
                                                                                                title: 'No Of Bags',
                                                                                                width: '10%'
                                                                                            },
                                                                                            no_of_passengers: {
                                                                                                title: 'No Of Passengers',
                                                                                                width: '10%'
                                                                                            },
                                                                                            no_of_doors: {
                                                                                                title: 'No Of Doors',
                                                                                                width: '5%'
                                                                                            },
                                                                                            min_age: {
                                                                                                title: 'Minimum Age',
                                                                                                width: '5%'
                                                                                            },
                                                                                            eng_description: {
                                                                                                title: 'Eng Description',
                                                                                                type: 'textarea',
                                                                                                width: '10%'
                                                                                            },
                                                                                            arb_description: {
                                                                                                title: 'Arb Description',
                                                                                                type: 'textarea',
                                                                                                width: '10%'
                                                                                            },
                                                                                            image1: {
                                                                                                title: 'Car Image',
                                                                                                width: '5%',
                                                                                                list: false,
                                                                                                input: function (data) {

                                                                                                    if (data.formType === "edit" && data.record.image1 != '' && data.record.image1 != null) {
                                                                                                        var htmlCode = '<input id="image1_id" type="hidden" name="image1" value="' + data.record.image1 + '">';
                                                                                                        htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                                                                                                        htmlCode += '<div><img width="459" src="' + base_url + '/public/uploads/' + data.record.image1 + '"/></div>';
                                                                                                    }
                                                                                                    else {
                                                                                                        //add case
                                                                                                        var htmlCode = '<input id="image1_id" type="hidden" name="image1" value="">';
                                                                                                        htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                                                                                                    }

                                                                                                    return htmlCode;
                                                                                                }
                                                                                            },
                                                                                            oracle_reference_number: {
                                                                                                title: 'Oracle Reference #',
                                                                                                width: '10%'
                                                                                            },
                                                                                            car_category_in_oasis: {
                                                                                                title: 'Car Category in OASIS',
                                                                                                width: '5%'
                                                                                            },
                                                                                            active_status: {
                                                                                                title: 'Active Status',
                                                                                                width: '10%',
                                                                                                options: {
                                                                                                    '0': 'In-Active',
                                                                                                    '1': 'Active'
                                                                                                },
                                                                                                display: function (CarTypeData) {
                                                                                                    if (CarTypeData.record.active_status == '1') {
                                                                                                        return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                                                                    }
                                                                                                    else {
                                                                                                        return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                                                                    }
                                                                                                }
                                                                                            },
                                                                                            booking_days_limit: {
                                                                                                title: 'Bookiing Days Limit',
                                                                                                width: '5%'
                                                                                            },
                                                                                        },
                                                                                        rowInserted: function (event, CarTypeData) {
                                                                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                                                                        },
                                                                                        formSubmitting: function (event, CarTypeData) {

                                                                                            //if(typeof UploadedFile == "undefined") UploadedFile = "";
                                                                                            //$("#FileUpload").html('<input type="hidden" id="thumb_img" name="image1" value="' + UploadedFile + '">');
                                                                                        }
                                                                                    }, function (CarTypeData) { //opened handler
                                                                                        CarTypeData.childTable.jtable('load');
                                                                                    });
                                                                            });
                                                                            return $img;
                                                                        }
                                                                    },
                                                                    eng_title: {
                                                                        title: 'Eng Title',
                                                                        width: '40%'
                                                                    },
                                                                    arb_title: {
                                                                        title: 'Arb Title',
                                                                        width: '40%'
                                                                    }
                                                                    /*,
                                                                     oracle_reference_number: {
                                                                     title: 'Oracle Reference #',
                                                                     width: '40%',
                                                                     list: false
                                                                     }*/
                                                                },
                                                                rowInserted: function (event, CarGroupData) {
                                                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                                                }
                                                            }, function (CarGroupData) { //opened handler
                                                                CarGroupData.childTable.jtable('load');
                                                            });
                                                    });
                                                    //Return image to show on the person row
                                                    return $img;
                                                }
                                            },

                                            eng_title: {
                                                title: 'Eng Title',
                                                width: '40%'
                                            },
                                            arb_title: {
                                                title: 'Arb Title',
                                                width: '40%'
                                            }
                                        },
                                        rowInserted: function (event, CategoryData) {
                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                        }
                                    }, function (CategoryData) { //opened handler
                                        CategoryData.childTable.jtable('load');
                                    });
                            });
                            //Return image to show on the person row
                            return $img;
                        }
                    },
                    eng_title: {
                        title: 'Eng Title',
                        width: '40%'
                    },
                    arb_title: {
                        title: 'Arb Title',
                        width: '20%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
// For Branches Table
    $(function () {
        // crud table
        altair_crud_table_for_branches.init();
    });
    altair_crud_table_for_branches = {
        init: function () {
            $('#BranchesTable').jtable({
                title: '<h3>Regions</h3>',
                sorting: true, //Enable sorting
                defaultSorting: 'id DESC',
                paging: true, //Enable paging
                pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    data.deleteConfirmMessage = 'Are you sure to delete region " ' + data.record.eng_title + ' " ?';
                },
                formCreated: function (event, data) {
                    data.form.find('input[name="eng_title"]').addClass('validate[required]');
                    data.form.find('input[name="arb_title"]').addClass('validate[required]');
                    reInitDesignFix(data);
                },
                //Validate form when it is being submitted
                formSubmitting: function (event, data) {
                    return data.form.validationEngine('validate');
                },
                //Dispose validation logic when form is closed
                formClosed: function (event, data) {
                    data.form.validationEngine('hide');
                    data.form.validationEngine('detach');
                },
                actions: regionActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    Cities: {
                        title: 'Cities',
                        width: '2%',
                        sorting: false,
                        edit: false,
                        create: false,
                        display: function (RegionData) {
                            //Create an image that will be used to open child table
                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-engine-city.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see all cities under this region" />');
                            //Open child table when user clicks the image
                            $img.click(function () {
                                //alert('here');
                                $('#BranchesTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: '<h3>Cities</h3>',
                                        sorting: true, //Enable sorting
                                        defaultSorting: 'id DESC',
                                        paging: true, //Enable paging
                                        pageSize: 10, //Set page size (default: 10)
                                        deleteConfirmation: function (RegionData) {
                                            RegionData.deleteConfirmMessage = 'Are you sure to delete car group " ' + RegionData.record.eng_title + ' " ?';
                                        },
                                        formCreated: function (event, data) {
                                            data.form.find('input[name="eng_title"]').addClass('validate[required]');
                                            data.form.find('input[name="arb_title"]').addClass('validate[required]');
                                            data.form.attr('enctype', 'multipart/form-data');

                                            $("#uploadFileForCity").uploadFile({

                                                url: base_url + "/admin/page/ajaxUploadFile",

                                                fileName: "file",

                                                showProgress: true,

                                                multiple: false,

                                                onSuccess: function (files, data, xhr) {

                                                    //UploadedFile = data;
                                                    $('#image1_id_city').val(data);


                                                }

                                            });
                                            reInitDesignFix(data);
                                        },
                                        //Validate form when it is being submitted
                                        formSubmitting: function (event, data) {
                                            return data.form.validationEngine('validate');
                                        },
                                        //Dispose validation logic when form is closed
                                        formClosed: function (event, data) {
                                            data.form.validationEngine('hide');
                                            data.form.validationEngine('detach');
                                        },
                                        actions: cityActions(RegionData),
                                        fields: {
                                            region_id: {
                                                type: 'hidden',
                                                create: true,
                                                edit: true,
                                                list: false,
                                                defaultValue: RegionData.record.id
                                            },
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            Branches: {
                                                title: 'Branches',
                                                width: '2%',
                                                sorting: false,
                                                edit: false,
                                                create: false,
                                                display: function (CityData) {
                                                    //Create an image that will be used to open child table
                                                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-engine-branch.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see branches under this city" />');
                                                    //Open child table when user clicks the image
                                                    $img.click(function () {
                                                        //alert('here');
                                                        $('#BranchesTable').jtable('openChildTable',
                                                            $img.closest('tr'),
                                                            {
                                                                title: '<h3>Branches</h3>',
                                                                sorting: true, //Enable sorting
                                                                defaultSorting: 'id DESC',
                                                                paging: true, //Enable paging
                                                                pageSize: 10, //Set page size (default: 10)
                                                                deleteConfirmation: function (CityData) {
                                                                    CityData.deleteConfirmMessage = 'Are you sure to delete car type " ' + CityData.record.eng_title + ' " ?';
                                                                },
                                                                formCreated: function (event, data) {
                                                                    reInitDesignFix(data);
                                                                },
                                                                actions: branchActions(CityData),
                                                                fields: {
                                                                    city_id: {
                                                                        type: 'hidden',
                                                                        create: true,
                                                                        edit: true,
                                                                        list: false,
                                                                        defaultValue: CityData.record.id
                                                                    },
                                                                    id: {
                                                                        key: true,
                                                                        create: false,
                                                                        edit: false,
                                                                        list: false
                                                                    },
                                                                    Schedule: {
                                                                        title: '',
                                                                        width: '2%',
                                                                        sorting: false,
                                                                        edit: false,
                                                                        create: false,
                                                                        display: function (branchData) {
                                                                            //Create an image that will be used to open child table
                                                                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-engine-branch-schedule.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see opening / closing schedule for this branch" />');
                                                                            //Open child table when user clicks the image
                                                                            $img.click(function () {
                                                                                //alert('here');
                                                                                $('#BranchesTable').jtable('openChildTable',
                                                                                    $img.closest('tr'),
                                                                                    {
                                                                                        title: '<h3>Opening / Closing Schedule</h3>',
                                                                                        sorting: true, //Enable sorting
                                                                                        defaultSorting: 'id DESC',
                                                                                        paging: true, //Enable paging
                                                                                        pageSize: 10, //Set page size (default: 10)
                                                                                        deleteConfirmation: function (branchData) {
                                                                                            branchData.deleteConfirmMessage = 'Are you sure to delete car type " ' + branchData.record.eng_title + ' " ?';
                                                                                        },
                                                                                        formCreated: function (event, data) {
                                                                                            reInitDesignFix(data);
                                                                                        },
                                                                                        actions: {
                                                                                            listAction: base_url + '/admin/branch/getAllSchedule?branch_id=' + branchData.record.id,
                                                                                            updateAction: base_url + '/admin/branch/updateSchedule'
                                                                                        },
                                                                                        fields: {
                                                                                            branch_id: {
                                                                                                type: 'hidden',
                                                                                                create: true,
                                                                                                edit: true,
                                                                                                list: false,
                                                                                                defaultValue: branchData.record.id
                                                                                            },
                                                                                            id: {
                                                                                                key: true,
                                                                                                create: false,
                                                                                                edit: false,
                                                                                                list: false
                                                                                            },
                                                                                            day: {
                                                                                                create: false,
                                                                                                edit: false,
                                                                                                title: 'Day',
                                                                                                width: '5%'
                                                                                            },
                                                                                            opening_time: {
                                                                                                title: 'Opening Time',
                                                                                                width: '5%',
                                                                                                input: function (data) {
                                                                                                    if (data.record) {
                                                                                                        var openingTime = data.record.opening_time;
                                                                                                        return '<input class="md-input" value=' + openingTime + ' name="opening_time" type="text" id="" data-uk-timepicker>';
                                                                                                    } else {
                                                                                                        return '<input class="md-input" name="opening_time" type="text" id="" data-uk-timepicker>';
                                                                                                    }
                                                                                                }
                                                                                            },
                                                                                            closing_time: {
                                                                                                title: 'Closing Time',
                                                                                                width: '5%',
                                                                                                input: function (data) {
                                                                                                    if (data.record) {
                                                                                                        var closingTime = data.record.closing_time;
                                                                                                        return '<input class="md-input" value=' + closingTime + ' name="closing_time" type="text" id="" data-uk-timepicker>';
                                                                                                    } else {
                                                                                                        return '<input class="md-input" name="closing_time" type="text" id="" data-uk-timepicker>';
                                                                                                    }
                                                                                                }
                                                                                            },
                                                                                            sec_shift: {
                                                                                                title: 'Has second shift?',
                                                                                                width: '5%',
                                                                                                input: function (data) {
                                                                                                    var checked = '';
                                                                                                    console.log(data);
                                                                                                    if (data.formType === "edit") {
                                                                                                        if (data.record.sec_shift == 'yes') {
                                                                                                            checked = 'checked';
                                                                                                        }
                                                                                                    }
                                                                                                    return '<input type="checkbox" ' + checked + ' data-switchery data-switchery-size="large" id="sec_shift" name="sec_shift" class="sec_shift" value="yes" />';
                                                                                                },
                                                                                                display: function (data) {
                                                                                                    if (data.record.sec_shift == 'yes') {
                                                                                                        return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                                                                    }
                                                                                                    else {
                                                                                                        return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                                                                    }
                                                                                                }
                                                                                            },
                                                                                            sec_shift_opening_time: {
                                                                                                title: 'Second Shift Opening Time',
                                                                                                width: '5%',
                                                                                                input: function (data) {
                                                                                                    if (data.record) {
                                                                                                        var sec_shift_opening_time = data.record.sec_shift_opening_time;
                                                                                                        return '<input class="md-input" value=' + sec_shift_opening_time + ' name="sec_shift_opening_time" type="text" id="" data-uk-timepicker>';
                                                                                                    } else {
                                                                                                        return '<input class="md-input" name="sec_shift_opening_time" type="text" id="" data-uk-timepicker>';
                                                                                                    }
                                                                                                }
                                                                                            },
                                                                                            sec_shift_closing_time: {
                                                                                                title: 'Second Shift Closing Time',
                                                                                                width: '5%',
                                                                                                input: function (data) {
                                                                                                    if (data.record) {
                                                                                                        var sec_shift_closing_time = data.record.sec_shift_closing_time;
                                                                                                        return '<input class="md-input" value=' + sec_shift_closing_time + ' name="sec_shift_closing_time" type="text" id="" data-uk-timepicker>';
                                                                                                    } else {
                                                                                                        return '<input class="md-input" name="sec_shift_closing_time" type="text" id="" data-uk-timepicker>';
                                                                                                    }
                                                                                                }
                                                                                            },
                                                                                            closed_day: {
                                                                                                title: 'Closing Day',
                                                                                                width: '5%',
                                                                                                options: {
                                                                                                    'No': 'No',
                                                                                                    'Yes': 'Yes'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        rowInserted: function (event, branchData) {
                                                                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                                                                        }
                                                                                    }, function (branchData) { //opened handler
                                                                                        branchData.childTable.jtable('load');
                                                                                    });
                                                                            });
                                                                            //Return image to show on the person row
                                                                            return $img;
                                                                        }
                                                                    },
                                                                    eng_title: {
                                                                        title: 'Eng Title',
                                                                        width: '40%'
                                                                    },
                                                                    arb_title: {
                                                                        title: 'Arb Title',
                                                                        width: '40%'
                                                                    },
                                                                    phone1: {
                                                                        title: 'Phone 1',
                                                                        width: '10%'
                                                                    },
                                                                    phone2: {
                                                                        title: 'Phone 2',
                                                                        width: '10%'
                                                                    },
                                                                    email: {
                                                                        title: 'Email',
                                                                        width: '10%'
                                                                    },
                                                                    mobile: {
                                                                        title: 'Branch Mobile No.',
                                                                        width: '10%'
                                                                    },
                                                                    agent_name: {
                                                                        title: 'Agent Name',
                                                                        width: '10%'
                                                                    },
                                                                    opening_hours: {
                                                                        title: 'Opening Hours',
                                                                        width: '10%'
                                                                    },
                                                                    address_line_1: {
                                                                        title: 'English Address',
                                                                        width: '10%'
                                                                    },
                                                                    address_line_2: {
                                                                        title: 'Arabic Address',
                                                                        type: 'textarea',
                                                                        width: '10%'
                                                                    },

                                                                    //map picker goes here
                                                                    map_latlng: {
                                                                        title: 'Map Coordinates',
                                                                        width: '10%',
                                                                        list: false,
                                                                        input: function (data) {
                                                                            if (data.record) {
                                                                                return '<input data-isedit="1" type="text" id="Edit-map_latlng" name="map_latlng" class="md-input" value="' + data.record.map_latlng + '" /><div class="us3" style="display: none; width: 100%; height: 400px;"></div>';
                                                                            } else {
                                                                                return '<input data-isedit="" type="text" id="Edit-map_latlng" name="map_latlng" class="md-input" value="" /><div class="us3" style="display: none; width: 100%; height: 400px;"></div>';
                                                                            }
                                                                        }
                                                                    },
                                                                    //=====


                                                                    prefix: {
                                                                        title: 'Branch Prefix',
                                                                        width: '10%'
                                                                    },
                                                                    oracle_reference_number: {
                                                                        title: 'Oracle Reference #',
                                                                        width: '10%'
                                                                    },
                                                                    is_airport: {
                                                                        title: 'Is Airport',
                                                                        width: '10%',
                                                                        options: {'0': 'No', '1': 'Yes'},
                                                                        display: function (data) {
                                                                            if (data.record.is_airport == '1') {
                                                                                return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                                            }
                                                                            else {
                                                                                return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                                            }
                                                                        }
                                                                    },
                                                                    is_delivery_branch: {
                                                                        title: 'Is Delivery Branch',
                                                                        width: '10%',
                                                                        options: {'no': 'No', 'yes': 'Yes'},
                                                                        display: function (data) {
                                                                            if (data.record.is_delivery_branch == 'yes') {
                                                                                return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                                            }
                                                                            else {
                                                                                return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                                            }
                                                                        }
                                                                    },
                                                                    delivery_charges: {
                                                                        title: 'Delivery Charges',
                                                                        width: '10%',
                                                                        input: function (data) {
                                                                            if (data.record != "" && typeof data.record !== 'undefined') {
                                                                                return '<input type="text" id="Edit-delivery_charges" name="delivery_charges" class="md-input" value="' + data.record.delivery_charges + '" />';
                                                                            } else {
                                                                                return '<input style="display: none;" type="text" id="Edit-delivery_charges" name="delivery_charges" class="md-input" value="" />';
                                                                            }
                                                                        }
                                                                    },
                                                                    capacity_mode: {
                                                                        title: 'Capacity Mode',
                                                                        width: '10%',
                                                                        options: {'off': 'Off', 'on': 'On'},
                                                                        display: function (data) {
                                                                            if (data.record.capacity_mode == 'on') {
                                                                                return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                                            }
                                                                            else {
                                                                                return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                                            }
                                                                        }
                                                                    },
                                                                    hours_for_delivery: {
                                                                        title: 'Hours For Delivery',
                                                                        width: '10%',
                                                                        input: function (data) {
                                                                            if (data.record != "" && typeof data.record !== 'undefined') {
                                                                                return '<input type="text" id="Edit-hours_for_delivery" name="hours_for_delivery" class="md-input" value="' + data.record.hours_for_delivery + '" />';
                                                                            } else {
                                                                                return '<input style="display: none;" type="text" id="Edit-hours_for_delivery" name="hours_for_delivery" class="md-input" value="" />';
                                                                            }
                                                                        }
                                                                    },
                                                                    capacity: {
                                                                        title: 'Capacity',
                                                                        width: '10%',
                                                                        input: function (data) {
                                                                            if (data.record != "" && typeof data.record !== 'undefined') {
                                                                                return '<input type="text" id="Edit-capacity" name="capacity" class="md-input" value="' + data.record.capacity + '" />';
                                                                            } else {
                                                                                return '<input style="display: none;" type="text" id="Edit-capacity" name="capacity" class="md-input" value="" />';
                                                                            }
                                                                        }
                                                                    },

                                                                    reservation_before_hours: {
                                                                        title: 'Reservation Before Hours',
                                                                        width: '10%',
                                                                        input: function (data) {
                                                                            if (data.record != "" && typeof data.record !== 'undefined') {
                                                                                return '<input type="text" id="Edit-capacity" name="reservation_before_hours" class="md-input" value="' + data.record.reservation_before_hours + '" />';
                                                                            } else {
                                                                                return '<input style="display: none;" type="text" id="Edit-capacity" name="reservation_before_hours" class="md-input" value="" />';
                                                                            }
                                                                        }
                                                                    },

                                                                    hours_before_delivery: {
                                                                        title: 'Hours Before Delivery',
                                                                        width: '10%',
                                                                        input: function (data) {
                                                                            if (data.record != "" && typeof data.record !== 'undefined') {
                                                                                return '<input type="text" id="Edit-capacity" name="hours_before_delivery" class="md-input" value="' + data.record.hours_before_delivery + '" />';
                                                                            } else {
                                                                                return '<input style="display: none;" type="text" id="Edit-capacity" name="hours_before_delivery" class="md-input" value="" />';
                                                                            }
                                                                        }
                                                                    },

                                                                    eng_capacity_message: {
                                                                        title: 'Eng Capacity Message',
                                                                        width: '10%',
                                                                        input: function (data) {
                                                                            if (data.record != "" && typeof data.record !== 'undefined') {
                                                                                return '<input type="text" id="Edit-eng_capacity_message" name="eng_capacity_message" class="md-input" value="' + data.record.eng_capacity_message + '" />';
                                                                            } else {
                                                                                return '<input style="display: none;" type="text" id="Edit-eng_capacity_message" name="eng_capacity_message" class="md-input" value="" />';
                                                                            }
                                                                        }
                                                                    },
                                                                    arb_capacity_message: {
                                                                        title: 'Arb Capacity Message',
                                                                        width: '10%',
                                                                        input: function (data) {
                                                                            if (data.record != "" && typeof data.record !== 'undefined') {
                                                                                return '<input type="text" id="Edit-arb_capacity_message" name="arb_capacity_message" class="md-input" value="' + data.record.arb_capacity_message + '" />';
                                                                            } else {
                                                                                return '<input style="display: none;" type="text" id="Edit-arb_capacity_message" name="arb_capacity_message" class="md-input" value="" />';
                                                                            }
                                                                        }
                                                                    },
                                                                    //delivery location map points picker goes here
                                                                    delivery_coordinates: {
                                                                        title: 'Delivery Coordinates',
                                                                        width: '10%',
                                                                        list: false,
                                                                        input: function (data) {
                                                                            if (data.record != "" && typeof data.record !== 'undefined') {
                                                                                return '<input data-isedit="1" type="text" id="Edit-delivery_coordinates" name="delivery_coordinates" class="md-input" value="' + data.record.delivery_coordinates + '" data-branch_id=""/><a id="clearMapData">Clear Map</a><div id="polyline_map_picker" style="display: none; width: 100%; height: 400px;"></div>';
                                                                            } else {
                                                                                return '<input style="display: none;" data-isedit="" type="text" id="Edit-delivery_coordinates" name="delivery_coordinates" class="md-input" value="" data-branch_id=""/><div id="polyline_map_picker" style="display: none; width: 100%; height: 400px;"></div>';
                                                                            }
                                                                        }
                                                                    },
                                                                    active_status: {
                                                                        title: 'Active Status',
                                                                        width: '10%',
                                                                        options: {'0': 'In-Active', '1': 'Active'},
                                                                        display: function (data) {
                                                                            if (data.record.active_status == '1') {
                                                                                return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                                            }
                                                                            else {
                                                                                return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                                            }
                                                                        }
                                                                    }
                                                                },
                                                                rowInserted: function (event, CityData) {
                                                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                                                }
                                                            }, function (CityData) { //opened handler
                                                                CityData.childTable.jtable('load');
                                                            });
                                                    });
                                                    //Return image to show on the person row
                                                    return $img;
                                                }
                                            },
                                            eng_title: {
                                                title: 'Eng Title',
                                                width: '40%'
                                            },
                                            arb_title: {
                                                title: 'Arb Title',
                                                width: '40%'
                                            },
                                            image1: {
                                                title: 'Image',
                                                width: '5%',
                                                list: false,
                                                input: function (data) {

                                                    /*if (data.formType === "edit") {
                                                     if (data.record.image1 != '' && data.record.image1 != null) {
                                                     UploadedFile = data.record.image1;
                                                     //alert(UploadedFile);
                                                     }
                                                     }*/

                                                    if (data.formType === "edit" && data.record.image1 != '' && data.record.image1 != null) {
                                                        var htmlCode = '<input id="image1_id_city" type="hidden" name="image1" value="' + data.record.image1 + '">';
                                                        htmlCode += '<div id="uploadFileForCity" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(1080 x 603 pixels)</p></div>';
                                                        htmlCode += '<div><img width="459" src="' + base_url + '/public/uploads/' + data.record.image1 + '"/></div>';
                                                    }
                                                    else {
                                                        //add case
                                                        var htmlCode = '<input id="image1_id_city" type="hidden" name="image1" value="">';
                                                        htmlCode += '<div id="uploadFileForCity" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(1080 x 603 pixels)</p></div>';
                                                    }

                                                    return htmlCode;
                                                }
                                            }
                                        },
                                        rowInserted: function (event, RegionData) {
                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                        }
                                    }, function (RegionData) { //opened handler
                                        RegionData.childTable.jtable('load');
                                    });
                            });
                            //Return image to show on the person row
                            return $img;
                        }
                    },
                    eng_title: {
                        title: 'Eng Title',
                        width: '40%'
                    },
                    arb_title: {
                        title: 'Arb Title',
                        width: '20%'
                    },
                    oracle_reference_number: {
                        title: 'Oracle Reference #',
                        width: '10%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };

    // For Pricing Structure
    $(function () {
        // crud table
        altair_crud_table_for_pricing.init();
    });
    altair_crud_table_for_pricing = {
        init: function () {
            $('#PricingTable').jtable({
                title: '<h3>All Car Models</h3>',
                sorting: true,
                paging: true, //Enable paging
                pageSize: 10, //Set page size (default: 10)
                defaultSorting: 'sort_col ASC',
                deleteConfirmation: function (pricingData) {
                    pricingData.deleteConfirmMessage = 'Are you sure to delete this pricing ?';
                },
                formCreated: function (event, PriceData) {
                    reInitDesignFix(PriceData);
                },
                /*actions: {
                    listAction: base_url + '/admin/pricing/getAllCarModels',
                    updateAction: base_url + '/admin/car_model/updateCarModel'
                },*/
                actions: carPriceIndexPageActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    Prices: {
                        title: '',
                        width: '2%',
                        sorting: false,
                        edit: false,
                        create: false,
                        display: function (PriceData) {
                            //Create an image that will be used to open child table
                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/car-price.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see car pricing under this car model" />');
                            //Open child table when user clicks the image
                            $img.click(function () {
                                //alert('here');
                                $('#PricingTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: '<h3>Pricings</h3>',
                                        sorting: true,
                                        //paging: true, //Enable paging
                                        //pageSize: 10, //Set page size (default: 10)
                                        deleteConfirmation: function (PriceData) {
                                            PriceData.deleteConfirmMessage = 'Are you sure to delete package price for " ' + PriceData.record.eng_title + ' ' + PriceData.record.year + ' " ?';
                                        },
                                        formCreated: function (event, data) {
                                            if((data.formType == "edit" && data.record.customer_type != "Corporate") || data.formType == "create")
                                                data.form.find("[name=company_code]").parents('.jtable-input-field-container:first').hide();
                                            data.form.find("[name=customer_type]").change(function(){
                                                if(this.value == "Corporate")
                                                    data.form.find("[name=company_code]").parents('.jtable-input-field-container:first').show();
                                                else
                                                    data.form.find("[name=company_code]").parents('.jtable-input-field-container:first').hide();
                                            });
                                            data.form.find('input[name="applies_from"]').addClass('validate[required]');
                                            reInitDesignFix(data);
                                        },
                                        //Validate form when it is being submitted
                                        formSubmitting: function (event, data) {
                                            return data.form.validationEngine('validate');
                                        },
                                        //Dispose validation logic when form is closed
                                        formClosed: function (event, data) {
                                            data.form.validationEngine('hide');
                                            data.form.validationEngine('detach');
                                        },
                                        actions: carPriceActions(PriceData),
                                        fields: {
                                            car_model_id: {
                                                type: 'hidden',
                                                create: true,
                                                edit: true,
                                                list: false,
                                                defaultValue: PriceData.record.id
                                            },
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },


                                            History: {
                                                title: '',
                                                width: '2%',
                                                sorting: false,
                                                edit: false,
                                                create: false,
                                                display: function (Data) {
                                                    //Create an image that will be used to open child table
                                                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/history.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see car pricing history" />');
                                                    //Open child table when user clicks the image
                                                    $img.click(function () {
                                                        //alert('here');
                                                        $('#PricingTable').jtable('openChildTable',
                                                            $img.closest('tr'),
                                                            {
                                                                title: '<h3>History</h3>',
                                                                sorting: true,
                                                                //paging: true, //Enable paging
                                                                //pageSize: 10, //Set page size (default: 10)

                                                                actions: {
                                                                    listAction: base_url + '/admin/pricing/getPriceHistory?id=' + Data.record.id,

                                                                },
                                                                fields: {
                                                                    car_model_id: {
                                                                        type: 'hidden',
                                                                        create: true,
                                                                        edit: true,
                                                                        list: false,
                                                                        defaultValue: Data.record.id
                                                                    },
                                                                    id: {
                                                                        key: true,
                                                                        create: false,
                                                                        edit: false,
                                                                        list: false
                                                                    },
                                                                    renting_type: {
                                                                        title: 'Renting Type',
                                                                        width: '10%'
                                                                    },
                                                                    price: {
                                                                        title: 'Price Value',
                                                                        width: '10%'
                                                                    },
                                                                    applies_from: {
                                                                        title: 'Applies From',
                                                                        width: '10%',
                                                                        type: 'date',
                                                                        displayFormat: 'yy-mm-dd'
                                                                    },
                                                                    applies_to: {
                                                                        title: 'Applies To',
                                                                        width: '10%',
                                                                        type: 'date',
                                                                        displayFormat: 'yy-mm-dd'
                                                                    },
                                                                    region: {
                                                                        title: 'Region',
                                                                        width: '10%'
                                                                    },
                                                                    city: {
                                                                        title: 'City',
                                                                        width: '10%'
                                                                    },
                                                                    branch: {
                                                                        title: 'Branch',
                                                                        width: '10%',
                                                                        dependsOn: 'city_id'
                                                                    },
                                                                    customer_type: {
                                                                        title: 'User Type',
                                                                        width: '10%'
                                                                    }
                                                                },
                                                                rowInserted: function (event, PriceData) {
                                                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                                                }
                                                            }, function (PriceData) { //opened handler
                                                                PriceData.childTable.jtable('load');
                                                            });
                                                    });
                                                    //Return image to show on the person row
                                                    return $img;
                                                }
                                            },


                                            renting_type_id: {
                                                title: 'Renting Type',
                                                width: '10%',
                                                options: base_url + '/admin/renting_type/getAllForDropdown'
                                            },
                                            price: {
                                                title: 'Price Value',
                                                width: '10%'
                                            },
                                            applies_from: {
                                                title: 'Applies From',
                                                width: '10%',
                                                type: 'date',
                                                displayFormat: 'yy-mm-dd'
                                            },
                                            applies_to: {
                                                title: 'Applies To',
                                                width: '10%',
                                                type: 'date',
                                                displayFormat: 'yy-mm-dd'
                                            },
                                            region_id: {
                                                title: 'Region',
                                                width: '10%',
                                                options: base_url + '/admin/region/getAllForDropdown'
                                            },
                                            city_id: {
                                                title: 'City',
                                                width: '10%',
                                                dependsOn: 'region_id', //Countries depends on continentals. Thus, jTable builds cascade dropdowns!
                                                options: function (data) {
                                                    if (data.source == 'list') {
                                                        //Return url of all countries for optimization.
                                                        //This method is called for each row on the table and jTable caches options based on this url.
                                                        return base_url + '/admin/city/getAllCities';
                                                    }
                                                    if (data.dependedValues.region_id != null) {
                                                        $.ajax({
                                                            type: "POST",
                                                            url: base_url + '/admin/city/getAllCitiesById?region_id=' + data.dependedValues.region_id,
                                                            dataType: "json",
                                                            cache: false,
                                                            success: function (result) {
                                                                var $cityDDB = $('select#Edit-city_id').selectize();
                                                                var selectizeCityDDB = $cityDDB[0].selectize;
                                                                //selectizeCityDDB.refreshOptions();
                                                                selectizeCityDDB.clear();
                                                                selectizeCityDDB.clearOptions();
                                                                selectizeCityDDB.load(function (callback) {
                                                                    callback(result);
                                                                    if (data.source == 'edit') {
                                                                        //alert(data.record.city_id);
                                                                        selectizeCityDDB.setValue(data.record.city_id)
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                }
                                                //list: false
                                            },
                                            branch_id: {
                                                title: 'Branch',
                                                width: '10%',
                                                dependsOn: 'city_id', //Countries depends on continentals. Thus, jTable builds cascade dropdowns!
                                                options: function (data) {
                                                    if (data.source == 'list') {
                                                        //Return url of all countries for optimization.
                                                        //This method is called for each row on the table and jTable caches options based on this url.
                                                        return base_url + '/admin/branch/getAllBranches';
                                                    }
                                                    if (data.dependedValues.city_id != null) {
                                                        $.ajax({
                                                            type: "POST",
                                                            url: base_url + '/admin/branch/getAllBranchesById?city_id=' + data.dependedValues.city_id,
                                                            dataType: "json",
                                                            cache: false,
                                                            success: function (result) {
                                                                var $branchDDB = $('select#Edit-branch_id').selectize();
                                                                var selectizeBranchDDB = $branchDDB[0].selectize;
                                                                //selectizeCityDDB.refreshOptions();
                                                                selectizeBranchDDB.clear();
                                                                selectizeBranchDDB.clearOptions();
                                                                selectizeBranchDDB.load(function (callback) {
                                                                    callback(result);
                                                                    if (data.source == 'edit') {
                                                                        //alert(data.record.branch_id);
                                                                        selectizeBranchDDB.setValue(data.record.branch_id)
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                }
                                            },
                                            customer_type: {
                                                title: 'User Type',
                                                width: '10%',
                                                options: {
                                                    '': 'Click here to select ...',
                                                    'Individual': 'Individual',
                                                    'Corporate': 'Corporate'
                                                },
                                                display: function (data) {
                                                    return '<span>' + data.record.customer_type + '</span>';
                                                }
                                            },

                                            company_code:{
                                                title: 'Company Code',
                                                width: '10%',
                                                options: base_url + '/admin/corporate_customer/getAllForDropdown'
                                            },

                                            charge_element: {
                                                type: 'hidden',
                                                create: true,
                                                edit: true,
                                                list: false,
                                                defaultValue: 'Rent'
                                            }
                                        },
                                        rowInserted: function (event, PriceData) {
                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                        }
                                    }, function (PriceData) { //opened handler
                                        PriceData.childTable.jtable('load');
                                    });
                            });
                            //Return image to show on the person row
                            return $img;
                        }
                    },
                    Extras: {
                        title: '',
                        width: '2%',
                        sorting: false,
                        edit: false,
                        create: false,
                        display: function (PriceData) {
                            //Create an image that will be used to open child table
                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/car-extra-charges.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see extra charges under this car model" />');
                            //Open child table when user clicks the image
                            $img.click(function () {
                                //alert('here');
                                $('#PricingTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: '<h3>Extra Charges</h3>',
                                        sorting: true,
                                        //paging: true, //Enable paging
                                        //pageSize: 10, //Set page size (default: 10)
                                        deleteConfirmation: function (PriceData) {
                                            PriceData.deleteConfirmMessage = 'Are you sure to delete extra charges for " ' + PriceData.record.eng_title + ' ' + PriceData.record.year + ' " ?';
                                        },
                                        formCreated: function (event, PriceData) {
                                            reInitDesignFix(PriceData);
                                        },
                                        actions: carExtraChargesActions(PriceData),
                                        fields: {
                                            car_model_id: {
                                                type: 'hidden',
                                                create: true,
                                                edit: true,
                                                list: false,
                                                defaultValue: PriceData.record.id
                                            },
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            charge_element: {
                                                title: 'Service Type',
                                                width: '10%',
                                                options: {
                                                    'CDW': 'CDW',
                                                    'GPS': 'GPS',
                                                    'Extra Driver': 'Extra Driver',
                                                    'Baby Seat': 'Baby Seat'
                                                },
                                                display: function (data) {
                                                    return '<span>' + data.record.charge_element + '</span>';
                                                }
                                            },
                                            renting_type_id: {
                                                title: 'Renting Type',
                                                width: '10%',
                                                options: base_url + '/admin/renting_type/getAllForDropdown'
                                            },
                                            price: {
                                                title: 'Price Value',
                                                width: '10%'
                                            },
                                            applies_from: {
                                                title: 'Applies From',
                                                width: '10%',
                                                type: 'date',
                                                displayFormat: 'yy-mm-dd'
                                            },
                                            applies_to: {
                                                title: 'Applies To',
                                                width: '10%',
                                                type: 'date',
                                                displayFormat: 'yy-mm-dd'
                                            },
                                            region_id: {
                                                title: 'Region',
                                                width: '10%',
                                                options: base_url + '/admin/region/getAllForDropdown'
                                            },
                                            city_id: {
                                                title: 'City',
                                                width: '10%',
                                                dependsOn: 'region_id', //Countries depends on continentals. Thus, jTable builds cascade dropdowns!
                                                options: function (data) {
                                                    if (data.source == 'list') {
                                                        //Return url of all countries for optimization.
                                                        //This method is called for each row on the table and jTable caches options based on this url.
                                                        return base_url + '/admin/city/getAllCities';
                                                    }
                                                    if (data.dependedValues.region_id != null) {
                                                        $.ajax({
                                                            type: "POST",
                                                            url: base_url + '/admin/city/getAllCitiesById?region_id=' + data.dependedValues.region_id,
                                                            dataType: "json",
                                                            cache: false,
                                                            success: function (result) {
                                                                var $cityDDB = $('select#Edit-city_id').selectize();
                                                                var selectizeCityDDB = $cityDDB[0].selectize;
                                                                //selectizeCityDDB.refreshOptions();
                                                                selectizeCityDDB.clear();
                                                                selectizeCityDDB.clearOptions();
                                                                selectizeCityDDB.load(function (callback) {
                                                                    callback(result);
                                                                    if (data.source == 'edit') {
                                                                        //alert(data.record.city_id);
                                                                        selectizeCityDDB.setValue(data.record.city_id)
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                }
                                                //list: false
                                            },
                                            branch_id: {
                                                title: 'Branch',
                                                width: '10%',
                                                dependsOn: 'city_id', //Countries depends on continentals. Thus, jTable builds cascade dropdowns!
                                                options: function (data) {
                                                    if (data.source == 'list') {
                                                        //Return url of all countries for optimization.
                                                        //This method is called for each row on the table and jTable caches options based on this url.
                                                        return base_url + '/admin/branch/getAllBranches';
                                                    }
                                                    if (data.dependedValues.city_id != null) {
                                                        $.ajax({
                                                            type: "POST",
                                                            url: base_url + '/admin/branch/getAllBranchesById?city_id=' + data.dependedValues.city_id,
                                                            dataType: "json",
                                                            cache: false,
                                                            success: function (result) {
                                                                var $branchDDB = $('select#Edit-branch_id').selectize();
                                                                var selectizeBranchDDB = $branchDDB[0].selectize;
                                                                //selectizeCityDDB.refreshOptions();
                                                                selectizeBranchDDB.clear();
                                                                selectizeBranchDDB.clearOptions();
                                                                selectizeBranchDDB.load(function (callback) {
                                                                    callback(result);
                                                                    if (data.source == 'edit') {
                                                                        //alert(data.record.branch_id);
                                                                        selectizeBranchDDB.setValue(data.record.branch_id)
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                }
                                            },
                                            customer_type: {
                                                title: 'User Type',
                                                width: '10%',
                                                options: {
                                                    '': 'Click here to select ...',
                                                    'Individual': 'Individual',
                                                    'Corporate': 'Corporate'
                                                },
                                                display: function (data) {
                                                    return '<span>' + data.record.customer_type + '</span>';
                                                }
                                            }
                                        },
                                        rowInserted: function (event, PriceData) {
                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                        },
                                    }, function (PriceData) { //opened handler
                                        PriceData.childTable.jtable('load');
                                    });
                            });
                            //Return image to show on the person row
                            return $img;
                        }
                    },
                    Availability: {
                        title: '',
                        width: '2%',
                        sorting: false,
                        edit: false,
                        create: false,
                        display: function (PriceData) {
                            //Create an image that will be used to open child table
                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/car-availability.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see availability under this model" />');
                            //Open child table when user clicks the image
                            $img.click(function () {
                                $('#PricingTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: '<h3>Availability</h3>',
                                        sorting: true,
                                        //paging: true, //Enable paging
                                        //pageSize: 10, //Set page size (default: 10)
                                        deleteConfirmation: function (PriceData) {
                                            PriceData.deleteConfirmMessage = 'Are you sure to delete this ?';
                                        },
                                        formCreated: function (event, data) {
                                            reInitDesignFix(data);
                                        },
                                        actions: {
                                            listAction: base_url + '/admin/pricing/carsAvailability?model_id=' + PriceData.record.id,
                                        },
                                        fields: {
                                            CarsAvailability: {
                                                title: '',
                                                edit: false,
                                                create: false,
                                                display: function (data) {
                                                    return data.record;
                                                }
                                            }
                                        },
                                        rowInserted: function (event, PriceData) {
                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                        }
                                    }, function (PriceData) { //opened handler
                                        PriceData.childTable.jtable('load');
                                    });
                            });
                            //Return image to show on the person row
                            return $img;
                        }
                    },
                    type_eng_title: {
                        edit: false,
                        title: 'Car Type',
                        width: '40%'
                    },
                    eng_title: {
                        edit: false,
                        title: 'Model Title',
                        width: '40%'
                    },
                    year: {
                        edit: false,
                        title: 'Model Year',
                        width: '20%'
                    },
                    sort_col: {
                        title: 'Sort Column',
                        width: '40%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
    // For Dropoff Charges Table
    $(function () {
        // crud table
        altair_crud_table_for_dropoff_charges.init();
    });
    altair_crud_table_for_dropoff_charges = {
        init: function () {
            $('#DropoffTable').jtable({
                title: '<h3>Regions</h3>',
                sorting: true,
                paging: true, //Enable paging
                pageSize: 10, //Set page size (default: 10)
                defaultSorting: 'id DESC',
                deleteConfirmation: function (data) {
                    data.deleteConfirmMessage = 'Are you sure to delete region " ' + data.record.eng_title + ' " ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: {
                    listAction: base_url + '/admin/region/getAll'
                },
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    Cities: {
                        title: 'Cities',
                        width: '2%',
                        sorting: false,
                        edit: false,
                        create: false,
                        display: function (RegionData) {
                            //Create an image that will be used to open child table
                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-engine-city.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see all cities under this region" />');
                            //Open child table when user clicks the image
                            $img.click(function () {
                                //alert('here');
                                $('#DropoffTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: '<h3>' + RegionData.record.eng_title + ' - Cities</h3>',
                                        sorting: true,
                                        paging: true, //Enable paging
                                        pageSize: 10, //Set page size (default: 10)
                                        defaultSorting: 'id DESC',
                                        deleteConfirmation: function (RegionData) {
                                            RegionData.deleteConfirmMessage = 'Are you sure to delete car group " ' + RegionData.record.eng_title + ' " ?';
                                        },
                                        formCreated: function (event, data) {
                                            reInitDesignFix(data);
                                        },
                                        actions: {
                                            listAction: base_url + '/admin/city/getAll?region_id=' + RegionData.record.id
                                        },
                                        fields: {
                                            region_id: {
                                                type: 'hidden',
                                                create: true,
                                                edit: true,
                                                list: false,
                                                defaultValue: RegionData.record.id
                                            },
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            DropoffCharges: {
                                                title: 'Dropoff Charges',
                                                width: '2%',
                                                sorting: false,
                                                edit: false,
                                                create: false,
                                                display: function (CityData) {
                                                    //Create an image that will be used to open child table
                                                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/dropoff-charges.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see dropoff charges under this city" />');
                                                    //Open child table when user clicks the image
                                                    $img.click(function () {
                                                        $('#DropoffTable').jtable('openChildTable',
                                                            $img.closest('tr'),
                                                            {
                                                                title: '<h3>' + CityData.record.eng_title + ' - Dropoff Charges</h3>',
                                                                sorting: true,
                                                                //paging: true, //Enable paging
                                                                //pageSize: 10, //Set page size (default: 10)
                                                                deleteConfirmation: function (CityData) {
                                                                    CityData.deleteConfirmMessage = 'Are you sure to delete car type " ' + CityData.record.eng_title + ' " ?';
                                                                },
                                                                formCreated: function (event, data) {
                                                                    data.form.find('input[name="applies_from"]').addClass('validate[required]');
                                                                    reInitDesignFix(data);
                                                                },
                                                                //Validate form when it is being submitted
                                                                formSubmitting: function (event, data) {
                                                                    return data.form.validationEngine('validate');
                                                                },
                                                                //Dispose validation logic when form is closed
                                                                formClosed: function (event, data) {
                                                                    data.form.validationEngine('hide');
                                                                    data.form.validationEngine('detach');
                                                                },

                                                                actions: dropoffActions(CityData),
                                                                fields: {
                                                                    region_id: {
                                                                        type: 'hidden',
                                                                        create: true,
                                                                        edit: true,
                                                                        list: false,
                                                                        defaultValue: CityData.record.region_id
                                                                    },
                                                                    city_from: {
                                                                        type: 'hidden',
                                                                        create: true,
                                                                        edit: true,
                                                                        list: false,
                                                                        defaultValue: CityData.record.id
                                                                    },
                                                                    id: {
                                                                        key: true,
                                                                        create: false,
                                                                        edit: false,
                                                                        list: false
                                                                    },
                                                                    city_to: {
                                                                        title: 'City To',
                                                                        width: '10%',
                                                                        options: base_url + '/admin/city/getCitiesForRegion?from_city_id=' + CityData.record.id

                                                                    },
                                                                    price: {
                                                                        title: 'Charges',
                                                                        width: '10%'
                                                                    },
                                                                    applies_from: {
                                                                        title: 'Valid From',
                                                                        width: '10%',
                                                                        type: 'date',
                                                                        displayFormat: 'yy-mm-dd'
                                                                    },
                                                                    applies_to: {
                                                                        title: 'Valid To',
                                                                        width: '10%',
                                                                        type: 'date',
                                                                        displayFormat: 'yy-mm-dd'
                                                                    }
                                                                },
                                                                rowInserted: function (event, CityData) {
                                                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                                                }
                                                            }, function (CityData) { //opened handler
                                                                CityData.childTable.jtable('load');
                                                            });
                                                    });
                                                    //Return image to show on the person row
                                                    return $img;
                                                }
                                            },
                                            eng_title: {
                                                title: 'City Name',
                                                width: '40%'
                                            }
                                        },
                                        rowInserted: function (event, RegionData) {
                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                        }
                                    }, function (RegionData) { //opened handler
                                        RegionData.childTable.jtable('load');
                                    });
                            });
                            //Return image to show on the person row
                            return $img;
                        }
                    },
                    eng_title: {
                        title: 'Region Name',
                        width: '40%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
    // For Promotions and Offer JTable
    $(function () {
        // crud table
        altair_crud_table_for_promotions.init();
    });
    altair_crud_table_for_promotions = {
        init: function () {
            $('#PromotionsTable').jtable({
                title: '<h3>Car Models</h3>',
                sorting: true,
                paging: true, //Enable paging
                pageSize: 10, //Set page size (default: 10)
                defaultSorting: 'id DESC',
                deleteConfirmation: function (modelData) {
                    modelData.deleteConfirmMessage = 'Are you sure to delete this promotion ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: {
                    listAction: base_url + '/admin/pricing/getAllCarModels'
                },
                fields: {
                    Promotions: {
                        title: '',
                        width: '2%',
                        sorting: false,
                        edit: false,
                        create: false,
                        display: function (PromotionsData) {
                            //Create an image that will be used to open child table
                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/promotions.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see car promotion offers under this car model" />');
                            //Open child table when user clicks the image
                            $img.click(function () {
                                $('#PromotionsTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: '<h3>' + PromotionsData.record.eng_title + ' ' + PromotionsData.record.year + ' - ' + 'Promotions & Offers</h3>',
                                        sorting: true,
                                        //paging: true, //Enable paging
                                        //pageSize: 10, //Set page size (default: 10)
                                        deleteConfirmation: function (PromotionsData) {
                                            PromotionsData.deleteConfirmMessage = 'Are you sure to delete promotion offer for " ' + PromotionsData.record.eng_title + ' ' + PromotionsData.record.year + ' " ?';
                                        },
                                        formCreated: function (event, data) {

                                            //UploadedFile = "";

                                            data.form.attr('enctype', 'multipart/form-data');

                                            $("#FileUpload1").uploadFile({

                                                url: base_url + "/admin/page/ajaxUploadFile",

                                                fileName: "file",

                                                showProgress: true,

                                                multiple: false,

                                                onSuccess: function (files, data, xhr) {

                                                    //UploadedFile = data;
                                                    $('#image1_id').val(data);


                                                }

                                            });
                                            $("#FileUpload2").uploadFile({

                                                url: base_url + "/admin/page/ajaxUploadFile",

                                                fileName: "file",

                                                showProgress: true,

                                                multiple: false,

                                                onSuccess: function (files, data, xhr) {

                                                    //UploadedFile = data;
                                                    $('#image2_id').val(data);


                                                }

                                            });
                                            data.form.find('input[name="applies_from"]').addClass('validate[required]');
                                            reInitDesignFix(data);
                                        },

                                        //Dispose validation logic when form is closed
                                        formClosed: function (event, data) {
                                            data.form.validationEngine('hide');
                                            data.form.validationEngine('detach');
                                        },

                                        actions: promotionActions(PromotionsData),
                                        fields: {
                                            car_model_id: {
                                                type: 'hidden',
                                                create: true,
                                                edit: true,
                                                list: false,
                                                defaultValue: PromotionsData.record.id
                                            },
                                            ////// Promotions history
                                            PromotionsHistory: {
                                                title: '',
                                                width: '2%',
                                                sorting: false,
                                                edit: false,
                                                create: false,
                                                display: function (Data) {
                                                    //Create an image that will be used to open child table
                                                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/promotion-history.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see promotion history" />');
                                                    //Open child table when user clicks the image
                                                    $img.click(function () {
                                                        //alert('here');
                                                        $('#PromotionsTable').jtable('openChildTable',
                                                            $img.closest('tr'),
                                                            {
                                                                title: '<h3>History</h3>',
                                                                sorting: true,
                                                                //paging: true, //Enable paging
                                                                //pageSize: 10, //Set page size (default: 10)

                                                                actions: {
                                                                    listAction: base_url + '/admin/promotions_offers/getPromotionHistory?id=' + Data.record.id,

                                                                },
                                                                fields: {
                                                                    car_model_id: {
                                                                        type: 'hidden',
                                                                        create: true,
                                                                        edit: true,
                                                                        list: false,
                                                                        defaultValue: Data.record.id
                                                                    },
                                                                    id: {
                                                                        key: true,
                                                                        create: false,
                                                                        edit: false,
                                                                        list: false
                                                                    },
                                                                    eng_title: {
                                                                        title: 'Eng Title',
                                                                        width: '10%'
                                                                    },
                                                                    arb_title: {
                                                                        title: 'Arb Title',
                                                                        width: '10%',
                                                                        list: false
                                                                    },
                                                                    type: {
                                                                        title: 'Promotion Type',
                                                                        width: '10%',
                                                                        options: {
                                                                            'Fixed Price by Using Coupon': 'Fixed Discount Rate Coupon',
                                                                            'Fixed Price Auto Apply': 'Fixed Discount Rate Auto Apply',
                                                                            'Percentage by Using Coupon': 'Percentage by Using Coupon',
                                                                            'Percentage Auto Apply': 'Percentage Auto Apply',
                                                                            'Fixed Daily Rate Coupon': 'Fixed Daily Rate Coupon',
                                                                            'Fixed Daily Rate Auto Apply': 'Fixed Daily Rate Auto Apply'
                                                                        },

                                                                        display: function (data) {
                                                                            return '<span>' + data.record.type + '</span>';
                                                                        }
                                                                    },
                                                                    renting_type: {
                                                                        title: 'Renting Type',
                                                                        width: '10%'
                                                                    },
                                                                    discount: {
                                                                        title: "Amount / Discount",
                                                                        width: '10%'
                                                                    },
                                                                    applies_from: {
                                                                        title: 'Applies From',
                                                                        width: '10%',
                                                                        type: 'date',
                                                                        displayFormat: 'yy-mm-dd'
                                                                    },
                                                                    applies_to: {
                                                                        title: 'Applies To',
                                                                        width: '10%',
                                                                        type: 'date',
                                                                        displayFormat: 'yy-mm-dd'
                                                                    },
                                                                    region: {
                                                                        title: 'Region',
                                                                        width: '10%'
                                                                    },
                                                                    city: {
                                                                        title: 'City',
                                                                        width: '10%'
                                                                    },
                                                                    branch: {
                                                                        title: 'Branch',
                                                                        width: '10%',
                                                                        dependsOn: 'city_id'
                                                                    },
                                                                    customer_type: {
                                                                        title: 'User Type',
                                                                        width: '10%'
                                                                    }
                                                                },
                                                                rowInserted: function (event, PromotionsData) {
                                                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                                                }
                                                            }, function (PromotionsData) { //opened handler
                                                                PromotionsData.childTable.jtable('load');
                                                            });
                                                    });
                                                    //Return image to show on the person row
                                                    return $img;
                                                }
                                            },
                                            Details: {
                                                title: '',
                                                width: '1%',
                                                sorting: false,
                                                edit: false,
                                                create: false,
                                                display: function (PromotionsData) {
                                                    //Create an image that will be used to open child table
                                                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/promotion-detail.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see offer details" />');
                                                    //Open child table when user clicks the image
                                                    $img.click(function () {
                                                        $('#PromotionsTable').jtable('openChildTable',
                                                            $img.closest('tr'),
                                                            {
                                                                title: '<h4>Promotion Detail</h4>',
                                                                actions: {
                                                                    listAction: base_url + '/admin/promotions_offers/getSinlgeDetail?promo_id=' + PromotionsData.record.id
                                                                },
                                                                fields: {
                                                                    DisplayCW: {
                                                                        title: '<h5>Detail</h5>',
                                                                        edit: false,
                                                                        create: false,
                                                                        display: function (PromotionsDetail) {
                                                                            var html, user_type;
                                                                            if (PromotionsDetail.record.customer_type == 'Corporate') {
                                                                                user_type = 'Corporate Customer';
                                                                            } else if (PromotionsDetail.record.customer_type == 'Individual') {
                                                                                user_type = 'Individual Customer';
                                                                            }
                                                                            html = '<div class="md-card uk-margin-medium-bottom"><div class="md-card-content"><div class="uk-overflow-container"><table class="uk-table"><div class="md-card-toolbar"><div class="md-card-toolbar-actions"><a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)" title="Print Payment Details">Print</a></div><h2 class="heading_b md-card-toolbar-heading-text">Promotion Details</h2></div><tbody>';
                                                                            if (PromotionsDetail.record.eng_title != null) {
                                                                                html += '<tr><td width="30%">Promotion Eng Title</td><td>' + PromotionsDetail.record.eng_title + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.arb_title != null) {
                                                                                html += '<tr><td>Promotion Arb Title</td><td>' + PromotionsDetail.record.arb_title + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.type != null) {
                                                                                html += '<tr><td>Promotion Type</td><td>' + PromotionsDetail.record.type + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.promo_code != null) {
                                                                                html += '<tr><td>Coupon Code</td><td>' + PromotionsDetail.record.promo_code + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.discount != null) {
                                                                                html += '<tr><td>Discount</td><td>' + PromotionsDetail.record.discount + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.applies_from != null) {
                                                                                html += '<tr><td>Applies From</td><td>' + PromotionsDetail.record.applies_from + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.applies_to != null) {
                                                                                html += '<tr><td>Applies To</td><td>' + PromotionsDetail.record.applies_to + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.region != null) {
                                                                                html += '<tr><td>Region</td><td>' + PromotionsDetail.record.region + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.city != null) {
                                                                                html += '<tr><td>City</td><td>' + PromotionsDetail.record.city + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.branch != null) {
                                                                                html += '<tr><td>Branch</td><td>' + PromotionsDetail.record.branch + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.customer_type != "") {
                                                                                html += '<tr><td>User Type</td><td>' + user_type + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.created_at != null) {
                                                                                html += '<tr><td>Created At</td><td>' + PromotionsDetail.record.created_at + '</td></tr>';
                                                                            }
                                                                            if (PromotionsDetail.record.updated_at != null) {
                                                                                html += '<tr><td>Updated At</td><td>' + PromotionsDetail.record.updated_at + '</td></tr>';
                                                                            }
                                                                            html += '</tbody></table></div></div></div>';
                                                                            return html;
                                                                        }
                                                                    }
                                                                },
                                                                rowInserted: function (event, CarTypeData) {
                                                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                                                }
                                                            }, function (CarTypeData) { //opened handler
                                                                CarTypeData.childTable.jtable('load');
                                                            });
                                                    });
                                                    return $img;
                                                }
                                            },
                                            id: {
                                                title: 'ID',
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: true
                                            },
                                            eng_title: {
                                                title: 'Eng Title',
                                                width: '10%'
                                            },
                                            arb_title: {
                                                title: 'Arb Title',
                                                width: '10%',
                                                list: false
                                            },
                                            type: {
                                                title: 'Promotion Type',
                                                width: '10%',
                                                options: {
                                                    'Fixed Price by Using Coupon': 'Fixed Discount Rate Coupon',
                                                    'Fixed Price Auto Apply': 'Fixed Discount Rate Auto Apply',
                                                    'Percentage by Using Coupon': 'Percentage by Using Coupon',
                                                    'Percentage Auto Apply': 'Percentage Auto Apply',
                                                    'Fixed Daily Rate Coupon': 'Fixed Daily Rate Coupon',
                                                    'Fixed Daily Rate Auto Apply': 'Fixed Daily Rate Auto Apply'
                                                },

                                                display: function (data) {
                                                    return '<span>' + data.record.type + '</span>';
                                                }
                                            },
                                            renting_type_id: {
                                                title: 'Renting Type',
                                                width: '10%',
                                                options: base_url + '/admin/renting_type/getAllForDropdown'
                                            },
                                            discount: {
                                                title: "Amount / Discount",
                                                width: '10%'
                                            },
                                            code: {
                                                title: 'Coupon Code',
                                                width: '10%',
                                                list: false
                                            },
                                            applies_from: {
                                                title: 'Applies From',
                                                width: '10%',
                                                type: 'date',
                                                displayFormat: 'yy-mm-dd'
                                            },
                                            applies_to: {
                                                title: 'Applies To',
                                                width: '10%',
                                                type: 'date',
                                                displayFormat: 'yy-mm-dd'
                                            },
                                            region_id: {
                                                title: 'Region',
                                                width: '10%',
                                                options: base_url + '/admin/region/getAllForDropdown'
                                            },
                                            city_id: {
                                                title: 'City',
                                                width: '10%',
                                                dependsOn: 'region_id', //Countries depends on continentals. Thus, jTable builds cascade dropdowns!
                                                options: function (data) {
                                                    if (data.source == 'list') {
                                                        //Return url of all countries for optimization.
                                                        //This method is called for each row on the table and jTable caches options based on this url.
                                                        return base_url + '/admin/city/getAllCities';
                                                    }
                                                    if (data.dependedValues.region_id != null) {
                                                        $.ajax({
                                                            type: "POST",
                                                            url: base_url + '/admin/city/getAllCitiesById?region_id=' + data.dependedValues.region_id,
                                                            dataType: "json",
                                                            cache: false,
                                                            success: function (result) {
                                                                var $cityDDB = $('select#Edit-city_id').selectize();
                                                                var selectizeCityDDB = $cityDDB[0].selectize;
                                                                //selectizeCityDDB.refreshOptions();
                                                                selectizeCityDDB.clear();
                                                                selectizeCityDDB.clearOptions();
                                                                selectizeCityDDB.load(function (callback) {
                                                                    callback(result);
                                                                    if (data.source == 'edit') {
                                                                        //alert(data.record.city_id);
                                                                        selectizeCityDDB.setValue(data.record.city_id)
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                }
                                                //list: false
                                            },
                                            branch_id: {
                                                title: 'Branch',
                                                width: '10%',
                                                dependsOn: 'city_id', //Countries depends on continentals. Thus, jTable builds cascade dropdowns!
                                                options: function (data) {
                                                    if (data.source == 'list') {
                                                        //Return url of all countries for optimization.
                                                        //This method is called for each row on the table and jTable caches options based on this url.
                                                        return base_url + '/admin/branch/getAllBranches';
                                                    }
                                                    if (data.dependedValues.city_id != null) {
                                                        $.ajax({
                                                            type: "POST",
                                                            url: base_url + '/admin/branch/getAllBranchesById?city_id=' + data.dependedValues.city_id,
                                                            dataType: "json",
                                                            cache: false,
                                                            success: function (result) {
                                                                var $branchDDB = $('select#Edit-branch_id').selectize();
                                                                var selectizeBranchDDB = $branchDDB[0].selectize;
                                                                //selectizeCityDDB.refreshOptions();
                                                                selectizeBranchDDB.clear();
                                                                selectizeBranchDDB.clearOptions();
                                                                selectizeBranchDDB.load(function (callback) {
                                                                    callback(result);
                                                                    if (data.source == 'edit') {
                                                                        //alert(data.record.branch_id);
                                                                        selectizeBranchDDB.setValue(data.record.branch_id)
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                }
                                            },
                                            customer_type: {
                                                title: 'User Type',
                                                width: '10%',
                                                options: {
                                                    '': 'Click here to select ...',
                                                    'Individual': 'Individual',
                                                    'Corporate': 'Corporate'
                                                },
                                                display: function (data) {
                                                    return '<span>' + data.record.customer_type + '</span>';
                                                }
                                            },

                                            display_on_home: {
                                                title: 'Show on Home',
                                                width: '10%',
                                                options: {'0': 'No', '1': 'Yes'},
                                                display: function (data) {
                                                    if (data.record.display_on_home == '1') {
                                                        return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                    }
                                                    else {
                                                        return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                    }
                                                }
                                            },

                                            display_on_offer: {
                                                title: 'Show on Offer Page',
                                                width: '10%',
                                                options: {'0': 'No', '1': 'Yes'},
                                                display: function (data) {
                                                    if (data.record.display_on_offer == '1') {
                                                        return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                    }
                                                    else {
                                                        return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                    }
                                                }
                                            },
                                            image1: {
                                                title: 'Eng Image',
                                                width: '5%',
                                                list: false,
                                                input: function (data) {

                                                    /*if (data.formType === "edit") {
                                                     if (data.record.image1 != '' && data.record.image1 != null) {
                                                     UploadedFile = data.record.image1;
                                                     //alert(UploadedFile);
                                                     }
                                                     }*/

                                                    if (data.formType === "edit" && data.record.image1 != '' && data.record.image1 != null) {
                                                        var htmlCode = '<input id="image1_id" type="hidden" name="image1" value="' + data.record.image1 + '">';
                                                        htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(1500 x 541 pixels)</p></div>';
                                                        htmlCode += '<div><img width="459" src="' + base_url + '/public/uploads/' + data.record.image1 + '"/></div>';
                                                    }
                                                    else {
                                                        //add case
                                                        var htmlCode = '<input id="image1_id" type="hidden" name="image1" value="">';
                                                        htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(1500 x 541 pixels)</p></div>';
                                                    }

                                                    return htmlCode;
                                                }
                                            },
                                            image2: {
                                                title: 'Arb Image',
                                                width: '5%',
                                                list: false,
                                                input: function (data) {

                                                    if (data.formType === "edit" && data.record.image2 != '' && data.record.image2 != null) {
                                                        var htmlCode = '<input id="image2_id" type="hidden" name="image2" value="' + data.record.image2 + '">';
                                                        htmlCode += '<div id="FileUpload2" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select_1" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(1500 x 541 pixels)</p></div>';
                                                        htmlCode += '<div><img width="459" src="' + base_url + '/public/uploads/' + data.record.image2 + '"/></div>';
                                                    }
                                                    else {
                                                        //add case
                                                        var htmlCode = '<input id="image2_id" type="hidden" name="image2" value="">';
                                                        htmlCode += '<div id="FileUpload2" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select_2" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(1500 x 541 pixels)</p></div>';
                                                    }

                                                    return htmlCode;
                                                }
                                            },
                                            eng_home_offer_desc: {
                                                title: 'English Home Offer Desc',
                                                width: '10%',
                                                type: 'textarea',
                                                inputClass: 'eng_desc',
                                                list: false
                                            },
                                            arb_home_offer_desc: {
                                                title: 'Arabic Home Offer Desc',
                                                width: '10%',
                                                type: 'textarea',
                                                inputClass: 'arb_desc',
                                                list: false
                                            }

                                        },
                                        rowInserted: function (event, PromotionsData) {
                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                        },
                                        formSubmitting: function (event, data) {

                                            //if(typeof UploadedFile == "undefined") UploadedFile = "";
                                            //$("#FileUpload").html('<input type="hidden" id="thumb_img" name="image1" value="' + UploadedFile + '">');
                                            for (instance in CKEDITOR.instances) {
                                                CKEDITOR.instances[instance].updateElement();
                                            }

                                            return data.form.validationEngine('validate');
                                        }

                                    }, function (PromotionsData) { //opened handler
                                        PromotionsData.childTable.jtable('load');
                                    });
                            });
                            //Return image to show on the person row
                            return $img;
                        }
                    },
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        title: 'Id',
                        list: true
                    },
                    type_eng_title: {
                        title: 'Model Type',
                        width: '10%'
                    },
                    eng_title: {
                        title: 'Model Title',
                        width: '10%'
                    },
                    year: {
                        title: 'Model Year',
                        width: '10%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
// For Loyalty Listing
    $(function () {
        // crud table
        LoyaltyCardsTable.init();
    });
    LoyaltyCardsTable = {
        init: function () {
            $('#LoyaltyCardsTable').jtable({
                title: '<h3>Loyalty Cards</h3>',
                sorting: true,
                loadingAnimationDelay: 3000,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: loyaltyCardTypeActions(),
                fields: {
                    id: {
                        title: 'Id',
                        key: true,
                        create: false,
                        edit: false,
                        list: true
                    },
                    loyalty_type: {
                        title: 'Loyalty Type',
                        width: '10%',
                        options: {'Bronze': 'Bronze', 'Silver': 'Silver', 'Golden': 'Golden', 'Platinum': 'Platinum'}
                    },
                    renting_type_id: {
                        title: 'Renting Type',
                        width: '10%',
                        list: false,
                        options: base_url + '/admin/renting_type/getAllForDropdown'
                    },
                    renting_type: {
                        title: 'Renting Type',
                        width: '10%',
                        create: false,
                        edit: false
                    },
                    customer_type: {
                        title: 'Customer Type',
                        width: '10%',
                        options: {
                            'individual_customer': 'Individual Customer',
                            'corporate_customer': 'Corporate Customer'
                        }
                    },
                    discount_percent: {
                        title: 'Discount Percent',
                        width: '10%'
                    },
                    active_status: {
                        title: 'Active Status',
                        width: '10%',
                        options: {'1': 'Active', '0': 'In-Active'}
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
    // For Renting Type Listing
    $(function () {
        // crud table
        RentingTypeTable.init();
    });
    RentingTypeTable = {
        init: function () {
            $('#RentingTypeTable').jtable({
                title: '<h3>Renting Types</h3>',
                sorting: true,
                loadingAnimationDelay: 3000,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: rentingTypeActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        title: 'Id',
                        list: true
                    },
                    oracle_reference_number: {
                        title: 'Oracle Reference #',
                        width: '10%'
                    },
                    type: {
                        title: 'Renting Type',
                        width: '10%'
                    },

                    from_days: {
                        title: 'From Days',
                        width: '10%'
                    },
                    to_days: {
                        title: 'To Days',
                        width: '10%'
                    },
                    sort: {
                        title: 'Sort Index',
                        width: '10%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
    // For Site Sections
    $(function () {
        // crud table
        SiteSectionsTable.init();
    });
    SiteSectionsTable = {
        init: function () {
            $('#SiteSectionsTable').jtable({
                title: '<h3>Site Sections</h3>',
                sorting: true,
                loadingAnimationDelay: 3000,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: {
                    listAction: base_url + '/admin/settings/get_listing?tbl=setting_site_section',
                    deleteAction: base_url + '/admin/settings/delete_listing?tbl=setting_site_section',
                    updateAction: base_url + '/admin/settings/update_listing?tbl=setting_site_section',
                    createAction: base_url + '/admin/settings/add_listing?tbl=setting_site_section&action=save_rights_for_section'
                },
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: true
                    },
                    name: {
                        title: 'Section',
                        width: '10%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
    // For User Roles Settings
    $(function () {
        // crud table
        UserRolesTable.init();
    });
    UserRolesTable = {
        init: function () {
            $('#UserRolesTable').jtable({
                title: '<h3>User Roles</h3>',
                sorting: false,
                loadingAnimationDelay: 3000,
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: userRoleActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    UserRights: {
                        title: '',
                        width: '2%',
                        sorting: false,
                        edit: false,
                        create: false,
                        display: function (roleData) {
                            //Create an image that will be used to open child table
                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/user-rights.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to manage user rights" />');
                            //Open child table when user clicks the image
                            $img.click(function () {

                                $('#UserRolesTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: '<h3>User Rights</h3>',
                                        sorting: true,
                                        //paging: true, //Enable paging
                                        //pageSize: 10, //Set page size (default: 10)
                                        deleteConfirmation: function (roleData) {
                                            roleData.deleteConfirmMessage = 'Are you sure to delete this ?';
                                        },
                                        formCreated: function (event, roleData) {
                                            reInitDesignFix(roleData);
                                        },
                                        actions: {
                                            listAction: base_url + '/admin/settings/userRights?role_id=' + roleData.record.id
                                        },
                                        fields: {
                                            UserRights: {
                                                title: '',
                                                edit: false,
                                                create: false,
                                                display: function (data) {
                                                    return data.record;
                                                }
                                            }
                                        },
                                        rowInserted: function (event, roleData) {
                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                        }
                                    }, function (roleData) { //opened handler
                                        roleData.childTable.jtable('load');
                                        /*roleData.childTable.jtable('load', undefined, function () {
                                                altair_md.checkbox_radio('.checkbox-role, .emptyrcb');
                                        });*/
                                        //alert('here');
                                    });
                            });
                            //Return image to show on the person row
                            return $img;
                        }
                    },
                    name: {
                        title: 'Role',
                        width: '10%'
                    }
                }
            }).jtable('load');
            //$('#UserRolesTable').jtable('load', undefined, function(){ altair_md.checkbox_radio('input[type="checkbox"]'); });
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };

    $(function () {
        // crud table
        InquiryTypesTable.init();
    });
    InquiryTypesTable = {
        init: function () {
            $('#InquiryTypesTable').jtable({
                title: '<h3>Inquiry Types</h3>',
                sorting: true,
                loadingAnimationDelay: 3000,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: inquiryTypeActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    eng_title: {
                        title: 'Eng Title',
                        width: '10%'
                    },
                    arb_title: {
                        title: 'Arb Title',
                        width: '10%'
                    },
                    email: {
                        title: 'Email',
                        width: '10%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };

    // for department settings
    $(function () {
        // crud table
        DepartmentTable.init();
    });
    DepartmentTable = {
        init: function () {
            $('#DepartmentTable').jtable({
                title: '<h3>Departments</h3>',
                sorting: true,
                loadingAnimationDelay: 3000,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: departmentActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    eng_title: {
                        title: 'Eng Title',
                        width: '10%'
                    },
                    arb_title: {
                        title: 'Arb Title',
                        width: '10%'
                    },
                    email: {
                        title: 'Email',
                        width: '10%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };

    // for loyalty km
    $(function () {
        // crud table
        LoyaltyKmTable.init();
    });
    LoyaltyKmTable = {
        init: function () {
            $('#LoyaltyKmTable').jtable({
                title: '<h3>Loyalty K.M</h3>',
                sorting: true,
                loadingAnimationDelay: 3000,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: LoyaltyKmActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    loyalty_type: {
                        create: false,
                        edit: false,
                        title: 'Loyalty Type',
                        width: '10%',
                        options: {'Bronze': 'Bronze', 'Silver': 'Silver', 'Golden': 'Golden', 'Platinum': 'Platinum'}
                    },
                    km: {
                        title: 'Allowed K.M',
                        width: '10%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };

    // For News Listing
    $(function () {
        // crud table
        NewsListing.init();
    });
    NewsListing = {
        init: function () {
            $('#NewsListTable').jtable({
                title: '<h3>News Listing</h3>',
                sorting: true,
                loadingAnimationDelay: 3000,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {

                    UploadedFile = "";

                    data.form.attr('enctype', 'multipart/form-data');

                    $("#FileUpload1").uploadFile({

                        url: base_url + "/admin/page/ajaxUploadFile",

                        fileName: "file",

                        showProgress: true,

                        multiple: false,

                        onSuccess: function (files, data, xhr) {

                            //UploadedFile = data;
                            $('#image1_id').val(data);

                        }

                    });

                    reInitDesignFix(data);
                },
                actions: newsListingActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    eng_title: {
                        title: 'Eng Title',
                        width: '10%'
                    },
                    arb_title: {
                        title: 'Arb Title',
                        width: '10%'
                    },
                    eng_short_desc: {
                        title: 'Eng Short Description',
                        width: '10%',
                        type: 'textarea',
                        inputClass: 'eng_desc',
                        list: false
                    },
                    arb_short_desc: {
                        title: 'Arb Short Description',
                        width: '10%',
                        type: 'textarea',
                        inputClass: 'arb_desc',
                        list: false
                    },
                    eng_desc: {
                        title: 'Eng Description',
                        width: '10%',
                        type: 'textarea',
                        inputClass: 'eng_desc',
                        list: false
                    },
                    arb_desc: {
                        title: 'Arb Description',
                        width: '10%',
                        type: 'textarea',
                        inputClass: 'arb_desc',
                        list: false
                    },
                    active_status: {
                        title: 'Active Status',
                        width: '10%',
                        options: {'0': 'In-Active', '1': 'Active'},
                        display: function (data) {
                            if (data.record.active_status == '1') {
                                return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                            }
                            else {
                                return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                            }
                        }
                    },
                    sort: {
                        title: 'Sort Index',
                        width: '5%',
                        list: false,
                        create: false,
                        edit: false
                    },

                    image1: {
                        title: 'Image',
                        width: '5%',
                        list: false,
                        input: function (data) {

                            if (data.formType === "edit" && data.record.image1 != '' && data.record.image1 != null) {
                                var htmlCode = '<input id="image1_id" type="hidden" name="image1" value="' + data.record.image1 + '">';
                                htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                                htmlCode += '<div><img width="459" src="' + base_url + '/public/uploads/' + data.record.image1 + '"/></div>';
                            }
                            else {
                                //add case
                                var htmlCode = '<input id="image1_id" type="hidden" name="image1" value="">';
                                htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                            }

                            return htmlCode;
                        }
                    }
                },
                formSubmitting: function (event, data) {
                    //if(typeof UploadedFile == "undefined") UploadedFile = "";
                    //$("#FileUpload").html('<input type="hidden" id="thumb_img" name="image1" value="' + UploadedFile + '">');
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
    // For News Listing


    //for program awards

    $(function () {
        // crud table
        programAwardsListing.init();
    });
    programAwardsListing = {
        init: function () {
            $('#programAwardsListTable').jtable({
                title: '<h3>Programs Rewards Listing</h3>',
                sorting: true,
                loadingAnimationDelay: 3000,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {

                    UploadedFile = "";

                    data.form.attr('enctype', 'multipart/form-data');

                    $("#FileUpload1").uploadFile({

                        url: base_url + "/admin/page/ajaxUploadFile",

                        fileName: "file",

                        showProgress: true,

                        multiple: false,

                        onSuccess: function (files, data, xhr) {

                            //UploadedFile = data;
                            $('#image1_id').val(data);

                        }

                    });

                    reInitDesignFix(data);
                },
                actions: programAwardsListingActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    eng_description: {
                        title: 'Eng Description',
                        width: '10%',
                        type: 'textarea',
                        inputClass: 'eng_desc',
                        list: false
                    },
                    arb_description: {
                        title: 'Arb Description',
                        width: '10%',
                        type: 'textarea',
                        inputClass: 'arb_desc',
                        list: false
                    },
                    sort: {
                        title: 'Sort Index',
                        width: '5%',
                        list: false,
                        create: false,
                        edit: false
                    },
                    image: {
                        title: 'Image',
                        width: '5%',
                        display: function (data) {
                            return '<div><img width="300" src="' + base_url + '/public/uploads/' + data.record.image + '" alt="image1"/></div>';
                        },
                        input: function (data) {

                            if (data.formType === "edit" && data.record.image != '' && data.record.image != null) {
                                var htmlCode = '<input id="image1_id" type="hidden" name="image" value="' + data.record.image + '">';
                                htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                                htmlCode += '<div><img width="459" src="' + base_url + '/public/uploads/' + data.record.image + '"/></div>';
                            }
                            else {
                                //add case
                                var htmlCode = '<input id="image1_id" type="hidden" name="image" value="">';
                                htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                            }

                            return htmlCode;
                        }
                    },
                    active_status: {
                        title: 'Active Status',
                        width: '10%',
                        options: {'0': 'In-Active', '1': 'Active'},
                        display: function (data) {
                            if (data.record.active_status == '1') {
                                return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                            }
                            else {
                                return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                            }
                        }
                    },
                },
                formSubmitting: function (event, data) {
                    //if(typeof UploadedFile == "undefined") UploadedFile = "";
                    //$("#FileUpload").html('<input type="hidden" id="thumb_img" name="image1" value="' + UploadedFile + '">');
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
    // For program awards

    /*for corporate sales listing*/

    $(function () {
        // crud table
        corporateSalesListTable.init();
    });
    corporateSalesListTable = {
        init: function () {
            $('#corporateSalesListTable').jtable({
                title: '<h3>Corporate Sales Listing</h3>',
                sorting: true,
                loadingAnimationDelay: 3000,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {

                    UploadedFile = "";

                    data.form.attr('enctype', 'multipart/form-data');

                    $("#FileUpload1").uploadFile({

                        url: base_url + "/admin/page/ajaxUploadFile",

                        fileName: "file",

                        showProgress: true,

                        multiple: false,

                        onSuccess: function (files, data, xhr) {

                            //UploadedFile = data;
                            $('#image1_id').val(data);

                        }

                    });

                    reInitDesignFix(data);
                },
                actions: corporateSalesListingActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    eng_description: {
                        title: 'Eng Description',
                        width: '10%',
                        type: 'textarea',
                        inputClass: 'eng_desc',
                        list: false
                    },
                    arb_description: {
                        title: 'Arb Description',
                        width: '10%',
                        type: 'textarea',
                        inputClass: 'arb_desc',
                        list: false
                    },
                    sort: {
                        title: 'Sort Index',
                        width: '5%',
                        list: false,
                        create: false,
                        edit: false
                    },
                    image: {
                        title: 'Image',
                        width: '5%',
                        display: function (data) {
                            return '<div><img width="300" src="' + base_url + '/public/uploads/' + data.record.image + '" alt="image1"/></div>';
                        },
                        input: function (data) {

                            if (data.formType === "edit" && data.record.image != '' && data.record.image != null) {
                                var htmlCode = '<input id="image1_id" type="hidden" name="image" value="' + data.record.image + '">';
                                htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                                htmlCode += '<div><img width="459" src="' + base_url + '/public/uploads/' + data.record.image + '"/></div>';
                            }
                            else {
                                //add case
                                var htmlCode = '<input id="image1_id" type="hidden" name="image" value="">';
                                htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                            }

                            return htmlCode;
                        }
                    },
                    active_status: {
                        title: 'Active Status',
                        width: '10%',
                        options: {'0': 'In-Active', '1': 'Active'},
                        display: function (data) {
                            if (data.record.active_status == '1') {
                                return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                            }
                            else {
                                return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                            }
                        }
                    },
                },
                formSubmitting: function (event, data) {
                    //if(typeof UploadedFile == "undefined") UploadedFile = "";
                    //$("#FileUpload").html('<input type="hidden" id="thumb_img" name="image1" value="' + UploadedFile + '">');
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
    /*end*/


    // For Home page slider listing
    $(function () {

        HomeSliderListing.init();
    });
    HomeSliderListing = {
        init: function () {
            $('#HomeSliderListTable').jtable({
                title: '<h3>Slider Listing</h3>',
                sorting: true,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {

                    UploadedFile = "";

                    data.form.attr('enctype', 'multipart/form-data');

                    $("#FileUpload1").uploadFile({

                        url: base_url + "/admin/page/ajaxUploadFile",

                        fileName: "file",

                        showProgress: true,

                        multiple: true,

                        onSuccess: function (files, data, xhr) {

                            //UploadedFile = data;
                            $('#image1_id').val(data);

                        }

                    });

                    reInitDesignFix(data);
                },
                actions: homeSliderListingActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    eng_slider_text: {
                        title: 'Eng Description',
                        type: 'textarea',
                        inputClass: 'eng_desc',
                        width: '10%',
                        list: false
                    },
                    arb_slider_text: {
                        title: 'Arb Description',
                        type: 'textarea',
                        inputClass: 'arb_desc',
                        width: '10%',
                        list: false
                    },
                    image1: {
                        title: 'Image',
                        width: '5%',
                        list: true,
                        display: function (data) {
                            return '<div><img width="300" src="' + base_url + '/public/uploads/' + data.record.image1 + '" alt="image1"/></div>';
                        },
                        input: function (data) {
                            if (data.formType === "edit" && data.record.image1 != '' && data.record.image1 != null) {
                                var htmlCode = '<input id="image1_id" type="hidden" name="image1" value="' + data.record.image1 + '">';
                                htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                                htmlCode += '<div><img width="459" src="' + base_url + '/public/uploads/' + data.record.image1 + '"/></div>';
                            }
                            else {
                                //add case
                                var htmlCode = '<input id="image1_id" type="hidden" name="image1" value="">';
                                htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                            }

                            return htmlCode;
                        }
                    },
                    eng_url: {
                        title: 'Eng URL',
                        width: '10%',
                        list: false
                    },
                    arb_url: {
                        title: 'Arb URL',
                        width: '10%',
                        list: false
                    },
                    is_active: {
                        title: 'Active Status',
                        width: '10%',
                        input: function (data) {
                            var checked = '';
                            console.log(data);
                            if (data.formType === "edit") {
                                if (data.record.is_active == '1') {
                                    checked = 'checked';
                                }
                            }
                            return '<input type="checkbox" ' + checked + ' data-switchery data-switchery-size="large" id="is_active" name="is_active" value="1" />';
                        },
                        display: function (data) {
                            if (data.record.is_active == '1') {
                                return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                            }
                            else {
                                return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                            }
                        }
                    },
                },
                formSubmitting: function (event, data) {
                    //if(typeof UploadedFile == "undefined") UploadedFile = "";
                    //$("#FileUpload").html('<input type="hidden" id="thumb_img" name="image1" value="' + UploadedFile + '">');
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
    // For Home page slider listing

    $(function () {
        // crud table
        FaqsListTable.init();
    });
    FaqsListTable = {
        init: function () {
            $('#FaqsListTable').jtable({
                title: '<h3>FAQs Listing</h3>',
                sorting: true,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: faqListingActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    eng_question: {
                        title: 'Eng Question <span style="color: red;">*</span>',
                        type: 'textarea',
                        inputClass: 'eng_ques',
                        width: '10%'
                    },
                    arb_question: {
                        title: 'Arb Question <span style="color: red;">*</span>',
                        type: 'textarea',
                        inputClass: 'arb_ques',
                        width: '10%',
                        list: false
                    },
                    eng_answer: {
                        title: 'Eng Answer <span style="color: red;">*</span>',
                        width: '10%',
                        type: 'textarea',
                        inputClass: 'eng_ans',
                        list: false
                    },
                    arb_answer: {
                        title: 'Arb Answer ',
                        width: '10%',
                        type: 'textarea',
                        inputClass: 'arb_ans',
                        list: false
                    },
                    active_status: {
                        title: 'Active Status',
                        width: '10%',
                        options: {'0': 'In-Active', '1': 'Active'},
                        display: function (data) {
                            if (data.record.active_status == '1') {
                                return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                            }
                            else {
                                return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                            }
                        }
                    }
                },
                formSubmitting: function (event, data) {
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };

    $(function () {
        // crud table
        altair_crud_table_for_car_selling_models.init();
    });
    altair_crud_table_for_car_selling_models = {
        init: function () {
            $('#CarsSellingTable').jtable({
                title: '<h3>Car Brands</h3>',
                paging: true, //Enable paging
                sorting: true, //Enable sorting
                defaultSorting: 'eng_title ASC',
                pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    data.deleteConfirmMessage = 'Are you sure to delete car category " ' + data.record.eng_title + ' " ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: carSellingCategoryActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    SellingModels: {
                        title: 'Cars',
                        width: '2%',
                        paging: true, //Enable paging
                        sorting: true, //Enable sorting
                        defaultSorting: 'id DESC',
                        pageSize: 10, //Set page size (default: 10)
                        edit: false,
                        create: false,
                        display: function (SellingCategoryData) {
                            //Create an image that will be used to open child table
                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/car-group.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see cars" />');
                            //Open child table when user clicks the image
                            $img.click(function () {
                                //alert('here');
                                $('#CarsSellingTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: '<h3>Cars</h3>',
                                        paging: true, //Enable paging
                                        sorting: true, //Enable sorting
                                        defaultSorting: 'eng_title ASC',
                                        pageSize: 10, //Set page size (default: 10)
                                        deleteConfirmation: function (SellingCategoryData) {
                                            SellingCategoryData.deleteConfirmMessage = 'Are you sure to delete car group " ' + SellingCategoryData.record.eng_title + ' " ?';
                                        },
                                        formCreated: function (event, data) {
                                            UploadedFile = "";
                                            data.form.attr('enctype', 'multipart/form-data');
                                            $("#FileUpload1").uploadFile({
                                                url: base_url + "/admin/page/ajaxUploadFile",
                                                fileName: "file",
                                                showProgress: true,
                                                multiple: false,
                                                onSuccess: function (files, data, xhr) {
                                                    $('#image1_id').val(data);
                                                }
                                            });
                                            reInitDesignFix(data);
                                        },
                                        formSubmitting: function (event, data) {
                                            updateAllMessageForms();
                                        },
                                        actions: carSellingModelsActions(SellingCategoryData),
                                        fields: {
                                            car_brand_id: {
                                                type: 'hidden',
                                                create: true,
                                                edit: true,
                                                list: false,
                                                defaultValue: SellingCategoryData.record.id
                                            },
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            eng_title: {
                                                title: 'Eng Title',
                                                width: '10%'
                                            },
                                            arb_title: {
                                                title: 'Arb Title',
                                                width: '10%'
                                            },
                                            year: {
                                                title: 'Model Year',
                                                width: '10%'
                                            },
                                            eng_car_desc: {
                                                title: 'Eng Description',
                                                type: 'textarea',
                                                inputClass: 'eng_desc',
                                                width: '10%',
                                                list: false
                                            },
                                            arb_car_desc: {
                                                title: 'Arb Description',
                                                type: 'textarea',
                                                inputClass: 'arb_desc',
                                                width: '10%',
                                                list: false
                                            },
                                            image1: {
                                                title: 'Image',
                                                width: '5%',
                                                list: true,
                                                display: function (data) {
                                                    return '<div><img width="300" src="' + base_url + '/public/uploads/' + data.record.image1 + '" alt="image here"/></div>';
                                                },
                                                input: function (data) {
                                                    if (data.formType === "edit" && data.record.image1 != '' && data.record.image1 != null) {
                                                        var htmlCode = '<input id="image1_id" type="hidden" name="image1" value="' + data.record.image1 + '">';
                                                        htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                                                        htmlCode += '<div><img width="459" src="' + base_url + '/public/uploads/' + data.record.image1 + '"/></div>';
                                                    }
                                                    else {
                                                        //add case
                                                        var htmlCode = '<input id="image1_id" type="hidden" name="image1" value="">';
                                                        htmlCode += '<div id="FileUpload1" class="uk-file-upload"><a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a><p class="uk-text-muted uk-text-small uk-margin-small-bottom"></p><p style="color:red; font-size:12px">(459 x 542 pixels)</p></div>';
                                                    }

                                                    return htmlCode;
                                                }
                                            },
                                            active_status: {
                                                title: 'Active Status',
                                                width: '10%',
                                                options: {
                                                    '0': 'In-Active',
                                                    '1': 'Active'
                                                },
                                                display: function (SellingCategoryData) {
                                                    if (SellingCategoryData.record.active_status == '1') {
                                                        return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                    }
                                                    else {
                                                        return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                    }
                                                }
                                            }
                                        },
                                        rowInserted: function (event, SellingCategoryData) {
                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                        }
                                    }, function (SellingCategoryData) { //opened handler
                                        SellingCategoryData.childTable.jtable('load');
                                    });
                            });
                            //Return image to show on the person row
                            return $img;
                        }
                    },
                    eng_title: {
                        title: 'Eng Title',
                        width: '40%'
                    },
                    arb_title: {
                        title: 'Arb Title',
                        width: '20%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };

// For Bad Logs
    $(function () {
        // crud table
        BadLogsTable.init();
    });
    BadLogsTable = {
        init: function () {
            $('#BadLogsTable').jtable({
                title: '<h3>Bad Logs</h3>',
                sorting: true,
                loadingAnimationDelay: 3000,
                //paging: true, //Enable paging
                //pageSize: 10, //Set page size (default: 10)
                deleteConfirmation: function (data) {
                    //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: {
                    listAction: base_url + '/admin/settings/get_listing?tbl=car_price_bad_log',
                    deleteAction: base_url + '/admin/settings/delete_listing?tbl=car_price_bad_log',
                },
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    created_at: {
                        title: 'Created At',
                        width: '10%'
                    },
                    car_model_id: {
                        title: 'Car Model ID',
                        width: '10%'
                    },
                    charge_element: {
                        title: 'Charge Element',
                        width: '10%'
                    },
                    renting_type_id: {
                        title: 'Renting Type ID',
                        width: '10%'
                    },
                    price: {
                        title: 'Price',
                        width: '10%'
                    },
                    applies_from: {
                        title: 'Applies From',
                        width: '10%'
                    },
                    applies_to: {
                        title: 'Applies To',
                        width: '10%'
                    },
                    region_id: {
                        title: 'Region ID',
                        width: '10%'
                    },
                    branch_id: {
                        title: 'Branch ID',
                        width: '10%'
                    },
                    customer_type: {
                        title: 'Customer Type',
                        width: '10%'
                    },
                    type: {
                        title: 'Type',
                        width: '10%'
                    }

                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };
});


$(document).ready(function () {
    $('#PendingBookingsTable').jtable({
        title: '',
        paging: true, //Enable paging
        sorting: true, //Enable sorting
        defaultSorting: 'id DESC',
        selecting: false, //Enable selecting
        multiselect: false, //Allow multiple selecting
        selectingCheckboxes: false, //Show checkboxes on first column
        pageSize: 10, //Set page size (default: 10)
        selectOnRowClick: false, //Enable this to only select using checkboxes
        openChildAsAccordion: true, //Enable this line to show child tabes as accordion style
        actions: {
            listAction: base_url + '/admin/bookings/getAllPendingBookings'
        },
        fields: {
            id: {
                key: true,
                create: false,
                edit: false,
                list: false
            },
            PersonalInfo: {
                title: '',
                width: '2%',
                sorting: false,
                edit: false,
                create: false,
                display: function (PersonalInfo) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-userinfo.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="User Personal Information" />');
                    //var parentTable = $("#PendingBookingsTable");
                    //Open child table when user clicks the image
                    $img.click(function () {
                        /*var tr = $(this).parents("tr"),
                            isChildRowOpen = parentTable.jtable("isChildRowOpen", tr );

                        if( isChildRowOpen ){
                            $( parentTable.jtable("getChildRow", tr ) ).slideUp();
                            return;
                        }*/
                        //alert('here');
                        $('#PendingBookingsTable').jtable('openChildTable',
                            $img.closest('tr'),
                            {
                                title: '<h3 style="margin-top: 20px;">User Detail</h3>',
                                actions: {
                                    listAction: base_url + '/admin/users/getSingleUserInfo?booking_id=' + PersonalInfo.record.id + '&user_type=' + PersonalInfo.record.type
                                },
                                fields: {
                                    DisplayCW: {
                                        title: '',
                                        edit: false,
                                        create: false,
                                        display: function (data) {
                                            var html;
                                            var dob = '';
                                            var id_exp_date = '';
                                            var license_exp_date = '';
                                            var idImage = '';
                                            var license_id_type_title = '';
                                            var nationality_title = '';
                                            var licenseImage = '';
                                            var id_country = '';
                                            var license_country = '';
                                            var job_title = '';
                                            var sponsor = '';
                                            var street_address = '';
                                            var district_address = '';
                                            var user_type = data.record.user_type;
                                            if (user_type == 'individual_customer' || user_type == 'guest') {
                                                if (user_type == 'individual_customer')
                                                    userType = 'Registered User (Individual)';
                                                else
                                                    userType = 'Guest User';
                                                if (data.record.dob != '0000-00-00' || data.record.dob != '1970-01-01')
                                                    dob = data.record.dob;
                                                if (data.record.id_expiry_date != '0000-00-00' || data.record.id_expiry_date != '1970-01-01')
                                                    id_exp_date = data.record.id_expiry_date;
                                                if (data.record.license_expiry_date != '0000-00-00' || data.record.license_expiry_date != '1970-01-01')
                                                    license_exp_date = data.record.license_expiry_date;
                                                if (data.record.nationality_title != null)
                                                    nationality_title = data.record.nationality_title;
                                                if (data.record.license_id_type_title != null)
                                                    license_id_type_title = data.record.license_id_type_title;

                                                if (data.record.id_country != null)
                                                    id_country = data.record.id_country;
                                                if (data.record.license_country != null)
                                                    license_country = data.record.license_country;
                                                if (data.record.job_title != null)
                                                    job_title = data.record.job_title;
                                                if (data.record.sponsor != null)
                                                    sponsor = data.record.sponsor;
                                                if (data.record.street_address != null)
                                                    street_address = data.record.street_address;
                                                if (data.record.district_address != null)
                                                    district_address = data.record.district_address;

                                                if (data.record.license_image != '')
                                                    licenseImage = '<img src="' + base_url + '/public/uploads/' + data.record.license_image + '" width="30" height="30" style="max-width: 30px;" title="Click to open in new window" />';
                                                if (data.record.id_image != '')
                                                    idImage = '<img src="' + base_url + '/public/uploads/' + data.record.id_image + '" width="30" height="30" style="max-width: 30px;" title="Click to open in new window" />';

                                                html = '<div class="md-card uk-margin-medium-bottom"><div class="md-card-content"><div class="uk-overflow-container"><table class="uk-table"><div class="md-card-toolbar"><div class="md-card-toolbar-actions"><a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)" title="Print User Information">Print</a></div><h2 class="heading_b md-card-toolbar-heading-text">User Personal Information</h2></div><tbody><tr><td width="30%">User Type</td><td>' + userType + '</td></tr><tr><td>Name</td><td>' + data.record.first_name + ' ' + data.record.last_name + '</td></tr><tr><td>Email</td><td>' + data.record.email + '</td></tr><tr><td>Mobile</td><td>' + data.record.mobile_no + '</td></tr><tr><td>ID Type</td><td>' + data.record.id_type_title + ', ' + data.record.id_version + '</td></tr><tr><td>ID Number</td><td>' + data.record.id_no + '</td></tr><tr><td>Nationality</td><td>' + nationality_title + '</td></tr>' +
                                                    '<tr><td colspan="2"><strong>User Personal Information (Optional)</strong></td></tr>' + '<tr><td>Job Title</td><td>' + job_title + '</td></tr> <tr><td>Sponsor</td><td>' + sponsor + '</td></tr> <tr><td>Street Address</td><td>' + street_address + '</td></tr> <tr><td>District Address</td><td>' + district_address + '</td></tr> <tr><td>DOB</td><td>' + dob + '</td></tr><tr><td>ID Expiry Date</td><td>' + id_exp_date + ' (' + data.record.id_date_type + ') ' + '</td></tr><tr><td>ID Country</td><td>' + id_country + '</td></tr><tr><td>ID Card Copy</td><td><a href="' + base_url + '/public/pdf/' + data.record.id_image + '" target="_blank">Click here</a></td></tr><tr><td>Driving License No.</td><td>' + data.record.license_no + '</td></tr><tr><td>License Country</td><td>' + license_country + '</td></tr><tr><td>Driving License ID Type.</td><td>' + license_id_type_title + '</td></tr><tr><td>License Expiry Date</td><td>' + license_exp_date + '</td></tr><tr><td>Driving License Copy</td><td><a href="' + base_url + '/public/pdf/' + data.record.license_image + '" target="_blank">Click here</a></td></tr></tbody></table></div></div></div>';
                                            } else if (user_type == 'corporate_customer') {
                                                html = '<div class="md-card uk-margin-medium-bottom"><div class="md-card-content"><div class="uk-overflow-container"><table class="uk-table"><div class="md-card-toolbar"><div class="md-card-toolbar-actions"><a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)" title="Print User Information">Print</a></div><h2 class="heading_b md-card-toolbar-heading-text">User Information</h2></div><tbody>' +
                                                    '' +
                                                    '<tr><td width="30%">User Type</td><td>Registered User (Corporate)</td></tr><tr><td>Company Name</td><td>' + data.record.company_name_en + '</td></tr><tr><td>Company Code</td><td>' + data.record.company_code + '</td></tr><tr><td>City</td><td>' + data.record.city + '</td></tr><tr><td>Active Status</td><td>' + data.record.active_status + '</td></tr>' +
                                                    '<tr><td colspan="2"><strong>Primary Contact Information</strong></td></tr>' + '<tr><td>Contact Name</td><td>' + data.record.primary_name + '</td></tr><tr><td>Contact Position</td><td>' + data.record.primary_position + '</td></tr><tr><td>Contact Email</td><td>' + data.record.primary_email + '</td></tr><tr><td>Contact Number.</td><td>' + data.record.primary_phone + '</td></tr>' +
                                                    '<tr><td colspan="2"><strong>Secondary Contact Information</strong></td></tr>' + '<tr><td>Contact Name</td><td>' + data.record.secondary_name + '</td></tr><tr><td>Contact Position</td><td>' + data.record.secondary_position + '</td></tr><tr><td>Contact Email</td><td>' + data.record.secondary_email + '</td></tr><tr><td>Contact Number.</td><td>' + data.record.secondary_phone + '</td></tr>' +
                                                    '</tbody></table></div></div></div>';
                                            }

                                            return html;
                                        }
                                    }
                                },
                                rowInserted: function (event, data) {
                                }
                            }, function (data) { //opened handler
                                data.childTable.jtable('load');
                            });
                    });
                    //Return image to show on the person row
                    return $img;
                }
            },
            RemainingInfo: {
                title: '',
                width: '2%',
                sorting: false,
                edit: false,
                create: false,
                display: function (PersonalInfo) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-detail.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Booking Detail" />');
                    //var parentTable = $("#PendingBookingsTable");
                    //Open child table when user clicks the image
                    $img.click(function () {
                        /*var tr = $(this).parents("tr"),
                            isChildRowOpen = parentTable.jtable("isChildRowOpen", tr );

                        if( isChildRowOpen ){
                            $( parentTable.jtable("getChildRow", tr ) ).slideUp();
                            return;
                        }*/
                        //alert('here');
                        $('#PendingBookingsTable').jtable('openChildTable',
                            $img.closest('tr'),
                            {
                                title: '<h3 style="margin-top: 20px;">Booking Detail</h3>',
                                actions: {
                                    listAction: base_url + '/admin/bookings/getSingleBookingInfo?booking_id=' + PersonalInfo.record.id + '&user_type=' + PersonalInfo.record.type
                                },
                                fields: {
                                    DisplayCW: {
                                        title: '',
                                        edit: false,
                                        create: false,
                                        display: function (data) {
                                            if (data.record.type == 'corporate_customer') {
                                                user_type = 'Corporate Customer';
                                            } else if (data.record.type == 'individual_customer') {
                                                user_type = 'Individual Customer';
                                            } else if (data.record.type == 'guest') {
                                                user_type = 'Guest';
                                            }
                                            return '<div class="md-card uk-margin-medium-bottom"><div class="md-card-content"><div class="uk-overflow-container"><table class="uk-table"><div class="md-card-toolbar"><div class="md-card-toolbar-actions"><a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)" title="Print Booking Details">Print</a></div><h2 class="heading_b md-card-toolbar-heading-text">Booking Details</h2></div><tbody><tr><td width="30%">Car Model</td><td>' + data.record.car_eng_title + ' ' + data.record.car_model_year + '</td></tr><tr><td>From Location</td><td>' + data.record.branch_eng_from + ', ' + data.record.eng_city_from + ', ' + data.record.eng_region_from + '</td></tr><tr><td>To Location</td><td>' + data.record.branch_eng_to + ',' + data.record.eng_city_to + ', ' + data.record.eng_region_to + '</td></tr><tr><td>From Date</td><td>' + data.record.from_date + '</td></tr><tr><td>To Date</td><td>' + data.record.to_date + '</td></tr><tr><td>Car Model Oracle #</td><td>' + data.record.oracle_ref_no + '</td></tr><tr><td>Booking Status</td><td>' + data.record.booking_status + '</td></tr><tr><td>Sync</td><td>' + data.record.sync + '</td></tr><tr><td>Synced At</td><td>' + data.record.synced_at + '</td></tr><tr><td>User Type</td><td>' + user_type + '</td></tr><tr><td>Created At</td><td>' + data.record.created_at + '</td></tr><tr><td>Edited At</td><td>' + data.record.updated_at + '</td></tr></tbody></table></div></div></div>';
                                        }
                                    }
                                },
                                rowInserted: function (event, data) {
                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                }
                            }, function (data) { //opened handler
                                data.childTable.jtable('load');
                            });
                    });
                    //Return image to show on the person row
                    return $img;
                }
            },
            PaymentDetails: {
                title: '',
                width: '2%',
                sorting: false,
                edit: false,
                create: false,
                display: function (PersonalInfo) {
                    //Create an image that will be used to open child table
                    var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-payment-detail.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Payment Information" />');
                    //var parentTable = $("#PendingBookingsTable");
                    //Open child table when user clicks the image
                    $img.click(function () {
                        /*var tr = $(this).parents("tr"),
                            isChildRowOpen = parentTable.jtable("isChildRowOpen", tr );

                        if( isChildRowOpen ){
                            $( parentTable.jtable("getChildRow", tr ) ).slideUp();
                            return;
                        }*/
                        //alert('here');
                        $('#PendingBookingsTable').jtable('openChildTable',
                            $img.closest('tr'),
                            {
                                title: '<h3 style="margin-top: 20px;">Payment Detail</h3>',
                                actions: {
                                    listAction: base_url + '/admin/bookings/getPaymentDetailsForBooking?booking_id=' + PersonalInfo.record.id + '&user_type=' + PersonalInfo.record.type
                                },
                                fields: {
                                    DisplayCW: {
                                        title: '',
                                        edit: false,
                                        create: false,
                                        display: function (data) {
                                            var html;
                                            var vat_text = '';
                                            if (data.record.vat_applied > 0) {
                                                vat_text += '<tr><td><strong><i>VAT Applied (' + data.record.vat_percentage + ' %)</i></strong></td><td><strong><i>' + data.record.vat_applied + ' SAR</i></strong></td></tr>';
                                            }
                                            var user_type = data.record.type;
                                            html = '<div class="md-card uk-margin-medium-bottom"><div class="md-card-content"><div class="uk-overflow-container"><table class="uk-table"><div class="md-card-toolbar"><div class="md-card-toolbar-actions"><a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)" title="Print Payment Details">Print</a></div><h2 class="heading_b md-card-toolbar-heading-text">Payment Details</h2></div><tbody>';
                                            if (data.record.payment_method != null) {
                                                html += '<tr><td width="30%"><strong><i>Payment Method</i></strong></td><td><strong><i>' + data.record.payment_method + '<i></i></strong></td></tr>';
                                                if (data.record.payment_method == 'Credit Card') {
                                                    html += '<tr><td width="30%"><strong><i>Transaction No.</i></strong></td><td><strong><i>' + data.record.transaction_id + '<i></i></strong></td></tr>';
                                                }
                                                if (data.record.payment_method == 'Sadad') {
                                                    html += '<tr><td width="30%"><strong><i>Transaction No.</i></strong></td><td><strong><i>' + data.record.s_transaction_id + '<i></i></strong></td></tr>';
                                                    html += '<tr><td width="30%"><strong><i>Invoice ID.</i></strong></td><td><strong><i>' + data.record.s_invoice_id + '<i></i></strong></td></tr>';
                                                }
                                            }
                                            html += '<tr><td>Rental Amount (Rent Per Day x Days)</td><td>' + data.record.rent_price + ' x ' + data.record.no_of_days + ' = ' + parseFloat(data.record.rent_price).toFixed(2) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                            if (parseInt(data.record.cdw_price) > 0 || parseInt(data.record.gps_price) > 0 || parseInt(data.record.extra_driver_price) > 0 || parseInt(data.record.baby_seat_price) > 0) {
                                                html += '<tr><td colspan="2"><strong><i>Extra Services</i></strong></td></tr>';
                                                if (data.record.cdw_price != null && parseInt(data.record.cdw_price) > 0) {
                                                    html += '<tr><td>CDW</td><td>' + data.record.cdw_price + ' SAR' + ' x ' + data.record.no_of_days + ' = ' + parseFloat(data.record.cdw_price).toFixed(2) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                                }
                                                if (data.record.gps_price != null && parseInt(data.record.gps_price) > 0) {
                                                    html += '<tr><td>GPS</td><td>' + data.record.gps_price + ' SAR' + ' x ' + data.record.no_of_days + ' = ' + parseFloat(data.record.gps_price).toFixed(2) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                                }
                                                if (data.record.extra_driver_price != null && parseInt(data.record.extra_driver_price) > 0) {
                                                    html += '<tr><td>Extra Driver</td><td>' + data.record.extra_driver_price + ' SAR' + ' x ' + data.record.no_of_days + ' = ' + parseFloat(data.record.extra_driver_price).toFixed(2) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                                }
                                                if (data.record.baby_seat_price != null && parseInt(data.record.baby_seat_price) > 0) {
                                                    html += '<tr><td>Baby Car Protection Seat</td><td>' + data.record.baby_seat_price + ' SAR' + ' x ' + data.record.no_of_days + ' = ' + parseFloat(data.record.baby_seat_price).toFixed(2) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                                }
                                            }

                                            if (data.record.is_delivery_mode == 'yes' && (data.record.delivery_charges != null && parseInt(data.record.delivery_charges) > 0)) {
                                                html += '<tr><td>Delivery Charges</td><td>' + data.record.delivery_charges + ' SAR</td></tr>';
                                            }

                                            if (data.record.dropoff_charges != null && parseInt(data.record.dropoff_charges) > 0) {
                                                html += '<tr><td>Dropoff Charges</td><td>' + data.record.dropoff_charges + ' SAR</td></tr>';
                                            }

                                            if (data.record.loyalty_card_id != null && parseInt(data.record.loyalty_card_id) > 0) {
                                                html += '<tr><td>Loyalty Card Used</td><td>' + data.record.loyalty_card_used + '</td></tr>';
                                            }

                                            if (data.record.promotion_offer_id != null && parseInt(data.record.promotion_offer_id) > 0) {
                                                html += '<tr><td colspan="2"><strong><i>Promotion Offer</i></strong></td></tr>';
                                                if (data.record.promotion_offer_id != null && parseInt(data.record.promotion_offer_id) > 0) {
                                                    html += '<tr><td>Promotion Offer Used</td><td>' + data.record.eng_title + ', ' + data.record.type + '</td></tr>';
                                                }
                                                if (data.record.promotion_code_used != null) {
                                                    html += '<tr><td>Promo Code Used</td><td>' + data.record.promotion_code_used + '</td></tr>';
                                                }
                                            }

                                            if (data.record.redeem_points != null) {
                                                html += '<tr><td>Redeem Points Used</td><td>' + data.record.redeem_points + ' Points (' + data.record.redeem_discount_availed + ' SAR Discounted)</td></tr>';
                                            }

                                            if (data.record.discount_price != null && parseInt(data.record.discount_price) > 0) {
                                                if(data.record.walkin_cronjob == '1'){
                                                    html += '<tr><td><strong>Discounted Amount</strong></td><td>'+data.record.discount_price+'%'+'</td></tr>';
                                                }else{
                                                    html += '<tr><td><strong>Discounted Amount</strong></td><td>' + data.record.discount_price + ' SAR' + ' x ' + data.record.no_of_days + ' = ' + parseInt(data.record.discount_price) * parseInt(data.record.no_of_days) + ' SAR</td></tr>';
                                                }

                                            }

                                            html += vat_text;
                                            html += '<tr><td><b><i>Total Amount Paid</i></b></td><td><strong><i>' + data.record.total_sum + ' SAR</i></strong></td></tr>';

                                            if (data.record.booking_status == 'Cancelled') {
                                                html += '<tr><td colspan="2"><strong><i>Cancellation Details</i></strong></td></tr>';
                                                if (data.record.cancel_time != null) {
                                                    html += '<tr><td>Cancellation Time</td><td>' + data.record.cancel_time + '</td></tr>';
                                                }
                                                if (data.record.cancel_charges != null && parseInt(data.record.cancel_charges) > 0) {
                                                    html += '<tr><td>Cancellation Charges</td><td>' + data.record.cancel_charges + ' SAR</td></tr>';
                                                }
                                                var refundableAmount = parseFloat(data.record.total_sum) - parseFloat(data.record.cancel_charges);
                                                html += '<tr><td><strong>Refundable Amount</strong></td><td><strong>' + refundableAmount + ' SAR</strong></td></tr>';
                                            }
                                            html += '</tbody></table></div></div></div>';
                                            return html;
                                        }
                                    }
                                },
                                rowInserted: function (event, data) {
                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                }
                            }, function (data) { //opened handler
                                data.childTable.jtable('load');
                            });
                    });
                    //Return image to show on the person row
                    return $img;
                }
            },
            type_of_booking: {
                edit: false,
                create: false,
                title: 'Type',
                width: '5%',
                display: function (data) {
                    var type_of_booking;
                    if (data.record.type == 'corporate_customer') {
                        type_of_booking = 'Corporate';
                    } else if (data.record.type == 'individual_customer') {
                        type_of_booking = 'Individual';
                    } else if (data.record.type == 'guest') {
                        type_of_booking = 'Guest';
                    }
                    return '<span>' + type_of_booking + '</span>';
                }
            },
            is_delivery_mode: {
                edit: false,
                create: false,
                title: 'Delivery / Pickup',
                width: '5%',
                display: function (data) {
                    var pickup_or_delivery;
                    if (data.record.is_delivery_mode == 'yes') {
                        pickup_or_delivery = 'Delivery';
                    } else {
                        pickup_or_delivery = 'Pickup';
                    }
                    return '<span>' + pickup_or_delivery + '</span>';
                }
            },
            booking_source: {
                edit: false,
                create: false,
                title: 'Booking Source',
                width: '5%',
                display: function (data) {
                    var source;
                    if (data.record.booking_source === 'ios') {
                        source = 'Mobile / IOS';
                    } else if (data.record.booking_source === 'android') {

                        source = 'Mobile / Android';
                    } else if (data.record.booking_source === 'mobile') {

                        source = 'Mobile Website';
                    } else {
                        source = 'Website';
                    }
                    return '<span>' + source + '</span>';
                }
            },
            reservation_code: {
                title: 'Reservation Code',
                width: '10%'
            },
            oracle_reference_number: {
                title: 'Oasis Contract No.',
                width: '10%',
                display: function (data) {
                    if (data.record.oracle_reference_number != '' && data.record.oracle_reference_number != null) {
                        return data.record.oracle_reference_number;
                    } else {
                        return 'N/A';
                    }
                }
            },
            car_eng_title: {
                title: 'Car Model',
                width: '10%',
                display: function (data) {
                    var car_title = data.record.car_type_eng_title + ' ' + data.record.car_eng_title+' ('+data.record.car_model_year+')';
                    return '<span>' + car_title + '</span>';
                }
            },
            branch_eng_from: {
                title: 'From Location',
                width: '10%'
            },
            branch_eng_to: {
                title: 'To Location',
                width: '10%'
            },
            from_date: {
                title: 'From Date',
                width: '10%'
            },
            to_date: {
                title: 'To Date',
                width: '10%'
            },
            booking_status: {
                title: 'Booking Status',
                width: '10%'
            },
            sync: {
                title: 'Sync Status',
                width: '10%'
            },
            app_version: {
                title: 'App Version',
                width: '10%',
                display: function (data) {
                    var appVersion;
                    if (data.record.booking_source === 'ios' && data.record.app_version != '' && data.record.app_version != null) {
                        appVersion = 'I - ' + data.record.app_version;
                    } else if (data.record.booking_source === 'android' && data.record.app_version != '' && data.record.app_version != null) {

                        appVersion = 'A - ' + data.record.app_version;
                    } else {
                        appVersion = 'N/A';
                    }
                    return '<span>' + appVersion + '</span>';
                }
            }
        },
        //Register to selectionChanged event to hanlde events
        selectionChanged: function () {
            //Get all selected rows
            var $selectedRows = $('#PendingBookingsTable').jtable('selectedRows');
            var bk_ids = [];
            //$('#PendingBookingsTable').empty();
            if ($selectedRows.length > 0) {
                //Show selected rows
                $selectedRows.each(function () {
                    var record = $(this).data('record-key');
                    bk_ids.push(record);
                });
                var ids = bk_ids.join(',');
                $('#hdn_bk_ids').val(ids);
            } else {
                //No rows selected
                $('#hdn_bk_ids').val('');
            }
        },
    });
    $('#PendingBookingsTable').jtable('load');
});


$(document).ready(function () {
    $(function () {
        // crud table for surveys
        altair_crud_table_for_surveys.init();
    });
    altair_crud_table_for_surveys = {
        init: function () {
            $('#SurveyTable').jtable({
                title: '<h3>Emojis</h3>',
                sorting: true,
                paging: true, //Enable paging
                pageSize: 10, //Set page size (default: 10)
                defaultSorting: 'id DESC',
                deleteConfirmation: function (data) {
                    data.deleteConfirmMessage = 'Are you sure to delete " ' + data.record.eng_title + ' " ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: surveyActions(),
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    SurveyCategories: {
                        title: 'Categories',
                        width: '2%',
                        sorting: false,
                        edit: false,
                        create: false,
                        display: function (EmojiData) {
                            //Create an image that will be used to open child table
                            var $img = $('<img src="' + EmojiData.record.icon_url + '" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see categories under this emoji" />');
                            //Open child table when user clicks the image
                            $img.click(function () {
                                $('#SurveyTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: '<h3>' + EmojiData.record.eng_title + ' - Categories</h3>',
                                        sorting: true,
                                        //paging: true, //Enable paging
                                        //pageSize: 10, //Set page size (default: 10)
                                        deleteConfirmation: function (EmojiData) {
                                            EmojiData.deleteConfirmMessage = 'Are you sure to delete " ' + EmojiData.record.eng_title + ' " ?';
                                        },
                                        formCreated: function (event, data) {
                                            data.form.find('input[name="applies_from"]').addClass('validate[required]');
                                            reInitDesignFix(data);
                                        },
                                        //Validate form when it is being submitted
                                        formSubmitting: function (event, data) {
                                            return data.form.validationEngine('validate');
                                        },
                                        //Dispose validation logic when form is closed
                                        formClosed: function (event, data) {
                                            data.form.validationEngine('hide');
                                            data.form.validationEngine('detach');
                                        },

                                        actions: surveyCategoryActions(EmojiData),
                                        fields: {
                                            emoji_id: {
                                                type: 'hidden',
                                                create: true,
                                                edit: true,
                                                list: false,
                                                defaultValue: EmojiData.record.id
                                            },
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            SurveyCategoriesOptions: {
                                                title: 'Options',
                                                width: '2%',
                                                sorting: false,
                                                edit: false,
                                                create: false,
                                                display: function (EmojiCategoryData) {
                                                    //Create an image that will be used to open child table
                                                    var $img = $('<img src="http://wfarm3.dataknet.com/static/resources/icons/set8/40367e2026af.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="Click to see options for this category question" />');
                                                    //Open child table when user clicks the image
                                                    $img.click(function () {
                                                        $('#SurveyTable').jtable('openChildTable',
                                                            $img.closest('tr'),
                                                            {
                                                                title: '<h3>' + EmojiCategoryData.record.eng_title + ' - Options</h3>',
                                                                sorting: true,
                                                                //paging: true, //Enable paging
                                                                //pageSize: 10, //Set page size (default: 10)
                                                                deleteConfirmation: function (EmojiCategoryData) {
                                                                    EmojiCategoryData.deleteConfirmMessage = 'Are you sure to delete " ' + EmojiCategoryData.record.eng_title + ' " ?';
                                                                },
                                                                formCreated: function (event, data) {
                                                                    data.form.find('input[name="applies_from"]').addClass('validate[required]');
                                                                    reInitDesignFix(data);
                                                                },
                                                                //Validate form when it is being submitted
                                                                formSubmitting: function (event, data) {
                                                                    return data.form.validationEngine('validate');
                                                                },
                                                                //Dispose validation logic when form is closed
                                                                formClosed: function (event, data) {
                                                                    data.form.validationEngine('hide');
                                                                    data.form.validationEngine('detach');
                                                                },

                                                                actions: surveyCategoryOptionsActions(EmojiCategoryData),
                                                                fields: {
                                                                    category_id: {
                                                                        type: 'hidden',
                                                                        create: true,
                                                                        edit: true,
                                                                        list: false,
                                                                        defaultValue: EmojiCategoryData.record.id
                                                                    },
                                                                    id: {
                                                                        key: true,
                                                                        create: false,
                                                                        edit: false,
                                                                        list: false
                                                                    },
                                                                    eng_title: {
                                                                        title: 'Eng Title',
                                                                        width: '10%'

                                                                    },
                                                                    arb_title: {
                                                                        title: 'Arb Title',
                                                                        width: '10%'
                                                                    },
                                                                    value: {
                                                                        title: 'Value',
                                                                        width: '10%'
                                                                    },
                                                                    publish: {
                                                                        title: 'Publish / Unpublish',
                                                                        width: '10%',
                                                                        input: function (data) {
                                                                            var checked = '';
                                                                            if (data.formType === "edit") {
                                                                                if (data.record.publish == 'yes') {
                                                                                    checked = 'checked';
                                                                                }
                                                                            }
                                                                            return '<input type="checkbox" ' + checked + ' data-switchery data-switchery-size="large" id="publish" name="publish" value="yes" />';
                                                                        },
                                                                        display: function (data) {
                                                                            if (data.record.publish == 'yes') {
                                                                                return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                                            }
                                                                            else {
                                                                                return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                                            }
                                                                        }
                                                                    },
                                                                    sort_col: {
                                                                        title: 'Sort Column',
                                                                        width: '10%'

                                                                    }
                                                                },
                                                                rowInserted: function (event, EmojiCategoryData) {
                                                                    $(".jtable-main-container").css({"margin-bottom": "40px"});
                                                                }
                                                            }, function (EmojiCategoryData) { //opened handler
                                                                EmojiCategoryData.childTable.jtable('load');
                                                            });
                                                    });
                                                    //Return image to show on the person row
                                                    return $img;
                                                }
                                            },
                                            eng_title: {
                                                title: 'Eng Title',
                                                width: '10%'

                                            },
                                            arb_title: {
                                                title: 'Arb Title',
                                                width: '10%'
                                            },
                                            eng_question: {
                                                title: 'Eng Question Desc',
                                                width: '10%'
                                            },
                                            arb_question: {
                                                title: 'Arb Question Desc',
                                                width: '10%'
                                            },
                                            is_other_type: {
                                                title: 'Is Other Type ?',
                                                width: '10%',
                                                input: function (data) {
                                                    var checked = '';
                                                    if (data.formType === "edit") {
                                                        if (data.record.is_other_type == 'yes') {
                                                            checked = 'checked';
                                                        }
                                                    }
                                                    return '<input type="checkbox" ' + checked + ' data-switchery data-switchery-size="large" id="is_other_type" name="is_other_type" value="yes" />';
                                                },
                                                display: function (data) {
                                                    if (data.record.is_other_type == 'yes') {
                                                        return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                    }
                                                    else {
                                                        return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                    }
                                                }
                                            },
                                            publish: {
                                                title: 'Publish / Unpublish',
                                                width: '10%',
                                                input: function (data) {
                                                    var checked = '';
                                                    if (data.formType === "edit") {
                                                        if (data.record.publish == 'yes') {
                                                            checked = 'checked';
                                                        }
                                                    }
                                                    return '<input type="checkbox" ' + checked + ' data-switchery data-switchery-size="large" id="publish" name="publish" value="yes" />';
                                                },
                                                display: function (data) {
                                                    if (data.record.publish == 'yes') {
                                                        return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                                    }
                                                    else {
                                                        return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                                    }
                                                }
                                            },
                                            sort_col: {
                                                title: 'Sort Column',
                                                width: '10%'

                                            }
                                        },
                                        rowInserted: function (event, EmojiData) {
                                            $(".jtable-main-container").css({"margin-bottom": "40px"});
                                        }
                                    }, function (EmojiData) { //opened handler
                                        EmojiData.childTable.jtable('load');
                                    });
                            });
                            //Return image to show on the person row
                            return $img;
                        }
                    },
                    eng_title: {
                        title: 'Eng Title',
                        width: '40%'
                    },
                    arb_title: {
                        title: 'Arb Title',
                        width: '40%'
                    },
                    sort_col: {
                        title: 'Sort Column',
                        width: '40%'
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };


    // for individual customers
    $(function () {
        // crud table
        individualCustomersTable.init();
    });
    individualCustomersTable = {
        init: function () {
            $('#individualCustomersTable').jtable({
                title: '<h3>Individual Customers</h3>',
                sorting: true,
                loadingAnimationDelay: 3000,
                paging: true, //Enable paging
                pageSize: 50, //Set page size (default: 10)
                defaultSorting: 'id DESC',
                deleteConfirmation: function (data) {
                    data.deleteConfirmMessage = 'Are you sure to delete this ?';
                },
                formCreated: function (event, data) {
                    reInitDesignFix(data);
                },
                actions: individualCustomersActions(),
                fields: {
                    Details: {
                        title: 'Details',
                        width: '2%',
                        sorting: false,
                        edit: false,
                        create: false,
                        display: function (PersonalInfo) {
                            //Create an image that will be used to open child table
                            var $img = $('<img src="' + base_url + '/public/admin/key_backend_images/booking-userinfo.png" class="actionImg" width="30" height="30" style="max-width: 30px;" title="CLick To See User Details" />');
                            //Open child table when user clicks the image
                            $img.click(function () {
                                //alert('here');
                                $('#individualCustomersTable').jtable('openChildTable',
                                    $img.closest('tr'),
                                    {
                                        title: '<h3 style="margin-top: 20px;">User Detail</h3>',
                                        actions: {
                                            listAction: base_url + '/admin/getCustomerDetailsForJTable?customer_id=' + PersonalInfo.record.id
                                        },
                                        fields: {
                                            Info: {
                                                title: '',
                                                edit: false,
                                                create: false,
                                                display: function (data) {
                                                    var html;
                                                    var dob = '';
                                                    var id_exp_date = '';
                                                    var license_exp_date = '';
                                                    var idImage = '';
                                                    var license_id_type_title = '';
                                                    var nationality_title = '';
                                                    var licenseImage = '';
                                                    var id_country = '';
                                                    var license_country = '';
                                                    var job_title = '';
                                                    var sponsor = '';
                                                    var street_address = '';
                                                    var district_address = '';
                                                    var user_type = '';
                                                    if (data.record.uid > 0)
                                                        userType = 'Registered User (Individual)';
                                                    else
                                                        userType = 'Guest User';
                                                    if (data.record.dob != '0000-00-00' || data.record.dob != '1970-01-01')
                                                        dob = data.record.dob;
                                                    if (data.record.id_expiry_date != '0000-00-00' || data.record.id_expiry_date != '1970-01-01')
                                                        id_exp_date = data.record.id_expiry_date;
                                                    if (data.record.license_expiry_date != '0000-00-00' || data.record.license_expiry_date != '1970-01-01')
                                                        license_exp_date = data.record.license_expiry_date;
                                                    if (data.record.nationality_title != null)
                                                        nationality_title = data.record.nationality_title;
                                                    if (data.record.license_id_type_title != null)
                                                        license_id_type_title = data.record.license_id_type_title;

                                                    if (data.record.id_country != null)
                                                        id_country = data.record.id_country;
                                                    if (data.record.license_country != null)
                                                        license_country = data.record.license_country;
                                                    if (data.record.job_title != null)
                                                        job_title = data.record.job_title;
                                                    if (data.record.sponsor != null)
                                                        sponsor = data.record.sponsor;
                                                    if (data.record.street_address != null)
                                                        street_address = data.record.street_address;
                                                    if (data.record.district_address != null)
                                                        district_address = data.record.district_address;

                                                    if (data.record.license_image != '')
                                                        licenseImage = '<img src="' + base_url + '/public/uploads/' + data.record.license_image + '" width="30" height="30" style="max-width: 30px;" title="Click to open in new window" />';
                                                    if (data.record.id_image != '')
                                                        idImage = '<img src="' + base_url + '/public/uploads/' + data.record.id_image + '" width="30" height="30" style="max-width: 30px;" title="Click to open in new window" />';

                                                    html = '<div class="md-card uk-margin-medium-bottom">' +
                                                        '<div class="md-card-content">' +
                                                        '<div class="uk-overflow-container">' +
                                                        '<table class="uk-table">' +
                                                        '<div class="md-card-toolbar">' +
                                                        '<div class="md-card-toolbar-actions">' +
                                                        '</div>' +
                                                        '<h2 class="heading_b md-card-toolbar-heading-text">User Personal Information</h2>' +
                                                        '</div>' +
                                                        '<tbody>' +
                                                        '<tr>' +
                                                        '<td width="30%">User Type</td>' + '<td>' + userType + '</td>' +
                                                        '</tr>' +
                                                        '<tr>' +
                                                        '<td width="30%">DB ID</td>' + '<td>' + data.record.id + '</td>' +
                                                        '</tr>' +
                                                        '<tr>' +
                                                        '<td>Name</td>' + '<td>' + data.record.first_name + ' ' + data.record.last_name + '</td>' +
                                                        '</tr>' +
                                                        '<tr>' +
                                                        '<td>Email</td>' + '<td>' + data.record.email + '</td>' +
                                                        '</tr>' +
                                                        '<tr>' +
                                                        '<td>Mobile</td><td>' + data.record.mobile_no + '</td>' +
                                                        '</tr>' +
                                                        '<tr>' +
                                                        '<td>ID Type</td><td>' + data.record.id_type_name + ', ' + data.record.id_version + '</td>' +
                                                        '</tr>' +
                                                        '<tr>' +
                                                        '<td>ID Number</td><td>' + data.record.id_no + '</td>' +
                                                        '</tr>' +
                                                        '<tr>' +
                                                        '<td>Nationality</td><td>' + nationality_title + '</td></tr>' +
                                                        '<tr><td colspan="2"><strong>User Personal Information (Optional)</strong></td></tr>' +
                                                        '<tr>' +
                                                        '<td>Job Title</td><td>' + job_title + '</td></tr>' +
                                                        '<tr>' +
                                                        '<td>Sponsor</td><td>' + sponsor + '</td></tr>' +
                                                        '<tr>' +
                                                        '<td>Street Address</td><td>' + street_address + '</td></tr>' +
                                                        '<tr>' +
                                                        '<td>District Address</td><td>' + district_address + '</td></tr>' +
                                                        '<tr>' +
                                                        '<td>DOB</td><td>' + dob + '</td></tr>' +
                                                        '<tr>' +
                                                        '<td>ID Expiry Date</td><td>' + id_exp_date + ' (' + data.record.id_date_type + ') ' + '</td></tr>' +
                                                        '<tr>' +
                                                        '<td>ID Country</td><td>' + id_country + '</td></tr>' +
                                                        '<tr>' +
                                                        '<td>ID Card Copy</td><td><a href="' + base_url + '/public/pdf/' + data.record.id_image + '" target="_blank">Click here</a></td></tr>' +
                                                        '<tr>' +
                                                        '<td>Driving License No.</td><td>' + data.record.license_no + '</td></tr>' +
                                                        '<tr>' +
                                                        '<td>License Country</td><td>' + license_country + '</td></tr>' +
                                                        '<tr>' +
                                                        '<td>Driving License ID Type.</td><td>' + license_id_type_title + '</td></tr>' +
                                                        '<tr>' +
                                                        '<td>License Expiry Date</td><td>' + license_exp_date + '</td></tr>' +
                                                        '<tr>' +
                                                        '<td>Driving License Copy</td><td><a href="' + base_url + '/public/pdf/' + data.record.license_image + '" target="_blank">Click here</a></td>' +
                                                        '</tr>' +
                                                        '</tbody>' +
                                                        '</table>' +
                                                        '</div>' +
                                                        '</div>' +
                                                        '</div>';
                                                    return html;
                                                }
                                            }
                                        },
                                        rowInserted: function (event, data) {
                                        }
                                    }, function (data) { //opened handler
                                        data.childTable.jtable('load');
                                    });
                            });
                            //Return image to show on the person row
                            return $img;
                        }
                    },
                    id: {
                        title: 'DB ID',
                        width: '5%',
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    name: {
                        title: 'Name',
                        width: '5%',
                        edit: false,
                        create: false,
                        display: function (PersonalInfo) {
                            return PersonalInfo.record.first_name + ' ' + PersonalInfo.record.last_name;
                        }
                    },
                    email: {
                        title: 'Email',
                        width: '5%',
                        edit: false,
                        create: false
                    },
                    id_no: {
                        title: 'ID No.',
                        width: '5%'
                    },
                    mobile_no: {
                        title: 'Mobile No.',
                        width: '5%'
                    },
                    loyalty_card_type: {
                        title: 'Loyalty Level',
                        width: '5%',
                        edit: false,
                        create: false
                    },
                    loyalty_points: {
                        title: 'Loyalty Points',
                        width: '5%',
                        edit: false,
                        create: false
                    }
                }
            }).jtable('load');
            // change buttons visual style in ui-dialog
            $('.ui-dialog-buttonset')
                .children('button')
                .attr('class', '')
                .addClass('md-btn md-btn-flat')
                .off('mouseenter focus');
            $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
        }
    };

});

function updateAllMessageForms() {
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
}

$(function () {
    // crud table
    CarsSellingResponsesTable.init();
});
CarsSellingResponsesTable = {
    init: function () {
        $('#CarsSellingResponsesTable').jtable({
            title: '<h3>Car Selling Responses</h3>',
            sorting: true,
            defaultSorting: 'created_at DESC',
            paging: true, //Enable paginglling
            pageSize: 10, //Set page size (default: 10)
            deleteConfirmation: function (data) {
                //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                data.deleteConfirmMessage = 'Are you sure to delete this ?';
            },
            formCreated: function (event, data) {
                reInitDesignFix(data);
            },
            actions: carsSellingResponsesActions(),
            fields: {
                id: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                car_id: {
                    title: 'Car Model',
                    width: '10%',
                    display: function (data) {
                        return data.record.brand_title + ' ' + data.record.eng_title + ' (' + data.record.year + ')';
                    }
                },
                name: {
                    title: 'Contact Name',
                    width: '10%'
                },
                mobile_no: {
                    title: 'Contact Mobile No',
                    width: '10%'
                },
                email: {
                    title: 'Contact Email',
                    width: '10%'
                },
                created_at: {
                    title: 'Received At',
                    width: '10%'
                }/*,
                image: {
                    title: 'Car Image',
                    width: '10%',
                    display: function (data) {
                        return '<a href="' + base_url + '/public/uploads/' + data.record.image1 + '" target="_blank" title="Click image to see"><img width="459" src="' + base_url + '/public/uploads/' + data.record.image1 + '"/></a>';
                    }
                },*/
            }
        }).jtable('load');
        // change buttons visual style in ui-dialog
        $('.ui-dialog-buttonset')
            .children('button')
            .attr('class', '')
            .addClass('md-btn md-btn-flat')
            .off('mouseenter focus');
        $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
    }
};


/*corporate sales */

$(function () {
    // crud table
    corporateSalesResponseTable.init();
});

corporateSalesResponseTable = {
    init: function () {
        $('#corporateSalesResponseTable').jtable({
            title: '<h3>Corporate Sales Responses</h3>',
            sorting: true,
            defaultSorting: 'created_at DESC',
            paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            deleteConfirmation: function (data) {
                //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                data.deleteConfirmMessage = 'Are you sure to delete this ?';
            },
            formCreated: function (event, data) {
                reInitDesignFix(data);
            },
            actions:  corporateSalesResponsesActions(),
            fields: {
                id: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                name: {
                    title: 'Name',
                    width: '10%'
                },
                designation: {
                    title: 'Designation',
                    width: '10%'
                },
                email: {
                    title: 'Email',
                    width: '10%'
                },
                company: {
                    title: 'Company',
                    width: '10%'
                },
                phone: {
                    title: 'Phone',
                    width: '10%'
                },
                mobile: {
                    title: 'Mobile',
                    width: '10%'
                },
                address: {
                    title: 'Address',
                    width: '10%'
                },
                message: {
                    title: 'Message',
                    width: '10%'
                },
                created_at: {
                    title: 'Received At',
                    width: '10%'
                }
            }
        }).jtable('load');
        // change buttons visual style in ui-dialog
        $('.ui-dialog-buttonset')
            .children('button')
            .attr('class', '')
            .addClass('md-btn md-btn-flat')
            .off('mouseenter focus');
        $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
    }
};

$(function () {
    // crud table
    RedeemSetupTable.init();
});
RedeemSetupTable = {
    init: function () {
        $('#RedeemSetupTable').jtable({
            title: '<h3>Redeem Setup</h3>',
            sorting: true,
            defaultSorting: 'created_at ASC',
            paging: false, //Enable paging
            deleteConfirmation: function (data) {
                //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                data.deleteConfirmMessage = 'Are you sure to delete this ?';
            },
            formCreated: function (event, data) {
                data.form.find('input[name="applies_from"]').addClass('validate[required]');
                reInitDesignFix(data);
            },
            //Validate form when it is being submitted
            formSubmitting: function (event, data) {
                return data.form.validationEngine('validate');
            },
            //Dispose validation logic when form is closed
            formClosed: function (event, data) {
                data.form.validationEngine('hide');
                data.form.validationEngine('detach');
            },
            actions: redeemSetupCarTypeActions(),
            fields: {
                id: {
                    key: true,
                    create: false,
                    edit: false,
                    list: true,
                    title: '#',
                    width: '5%'
                },
                region_title: {
                    create: false,
                    edit: false,
                    list: true,
                    title: 'Region',
                    width: '10%'
                },
                car_type_title: {
                    create: false,
                    edit: false,
                    list: true,
                    title: 'Car Type',
                    width: '10%'
                },
                car_model_title: {
                    create: false,
                    edit: false,
                    list: true,
                    title: 'Car Model',
                    width: '10%'
                },
                region_id: {
                    list: false,
                    title: 'Region',
                    width: '10%',
                    options: base_url + '/admin/region/getAllForDropdown'
                },
                car_type_id: {
                    list: false,
                    title: 'Car Type',
                    width: '10%',
                    options: base_url + '/admin/car_type/getAllForDropdown'
                },
                car_model_id: {
                    list: false,
                    title: 'Car Model',
                    width: '10%',
                    dependsOn: 'car_type_id',
                    options: function (data) {
                        if (data.dependedValues.car_type_id != null) {
                            $.ajax({
                                type: "POST",
                                url: base_url + '/admin/redeem_setup/getAllModelsByType?car_type_id=' + data.dependedValues.car_type_id,
                                dataType: "json",
                                cache: false,
                                success: function (result) {
                                    var $cityDDB = $('select#Edit-car_model_id').selectize();
                                    var selectizeCityDDB = $cityDDB[0].selectize;
                                    selectizeCityDDB.clear();
                                    selectizeCityDDB.clearOptions();
                                    selectizeCityDDB.load(function (callback) {
                                        callback(result);
                                        if (data.source == 'edit') {
                                            selectizeCityDDB.setValue(data.record.car_model_id)
                                        }
                                    });
                                }
                            });
                        }
                    }
                    //list: false
                },
                applies_from: {
                    title: 'Applies From',
                    width: '10%',
                    type: 'date',
                    displayFormat: 'yy-mm-dd'
                },
                applies_to: {
                    title: 'Applies To',
                    width: '10%',
                    type: 'date',
                    displayFormat: 'yy-mm-dd'
                },
                no_of_cars_present: {
                    title: 'No. Of Cars',
                    width: '10%'
                },
                percentage_of_open_contracts: {
                    title: '% Of Open Contracts',
                    width: '10%'
                },
                type_of_redeem: {
                    title: 'Redeem Type',
                    width: '10%',
                    options: {
                        'Amount': 'Amount',
                        'Percentage': 'Percentage'
                    }
                },
                percentage_of_points_usable: {
                    title: 'Part Of Total Amount Usable',
                    width: '10%'
                },
                active_status: {
                    title: 'Status',
                    width: '10%',
                    options: {
                        'active': 'Active',
                        'inactive': 'In-Active'
                    },
                    display: function (data) {
                        if (data.record.active_status == 'active') {
                            return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                        }
                        else {
                            return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                        }
                    }
                }
            }
        }).jtable('load');
        // change buttons visual style in ui-dialog
        $('.ui-dialog-buttonset')
            .children('button')
            .attr('class', '')
            .addClass('md-btn md-btn-flat')
            .off('mouseenter focus');
        $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
    }
};


/*availability setup*/

$(function () {
    // crud table
    availabilitySetupTable.init();
});
availabilitySetupTable = {
    init: function () {
        $('#availabilitySetupTable').jtable({
            title: '<h3>Booking Availability Setup</h3>',
            sorting: true,
            defaultSorting: 'created_at ASC',
            paging: false, //Enable paging
            deleteConfirmation: function (data) {
                //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                data.deleteConfirmMessage = 'Are you sure to delete this ?';
            },
            formCreated: function (event, data) {
                data.form.find('input[name="applies_from"]').addClass('validate[required]');
                reInitDesignFix(data);
            },
            //Validate form when it is being submitted
            formSubmitting: function (event, data) {
                return data.form.validationEngine('validate');
            },
            //Dispose validation logic when form is closed
            formClosed: function (event, data) {
                data.form.validationEngine('hide');
                data.form.validationEngine('detach');
            },
            actions: availabilitySetupActions(),
            fields: {
                id: {
                    key: true,
                    create: false,
                    edit: false,
                    list: true,
                    title: '#',
                    width: '5%'
                },
                region_title: {
                    create: false,
                    edit: false,
                    list: true,
                    title: 'Region',
                    width: '10%'
                },
                city: {
                    create: false,
                    edit: false,
                    list: true,
                    title: 'City',
                    width: '10%'
                },
                car_type_title: {
                    create: false,
                    edit: false,
                    list: true,
                    title: 'Car Type',
                    width: '10%'
                },
                car_model_title: {
                    create: false,
                    edit: false,
                    list: true,
                    title: 'Car Model',
                    width: '10%'
                },
                region_id: {
                    list: false,
                    title: 'Region',
                    width: '10%',
                    options: base_url + '/admin/region/getAllForDropdown'
                },
                city_id: {
                    list: false,
                    title: 'City',
                    width: '10%',
                    dependsOn: 'region_id',
                    options: function (data) {
                        if (data.dependedValues.region_id != null) {
                            $.ajax({
                                type: "POST",
                                url: base_url + '/admin/availability/getAllCities?region_id=' + data.dependedValues.region_id,
                                dataType: "json",
                                cache: false,
                                success: function (result) {
                                    var $cityDDB = $('select#Edit-city_id').selectize();
                                    var selectizeCityDDB = $cityDDB[0].selectize;
                                    selectizeCityDDB.clear();
                                    selectizeCityDDB.clearOptions();
                                    selectizeCityDDB.load(function (callback) {
                                        callback(result);
                                        if (data.source == 'edit') {
                                            selectizeCityDDB.setValue(data.record.city_id)
                                        }
                                    });
                                }
                            });
                        }
                    }
                    //list: false
                },
                car_type_id: {
                    list: false,
                    title: 'Car Type',
                    width: '10%',
                    options: base_url + '/admin/car_type/getAllForDropdown'
                },
                car_model_id: {
                    list: false,
                    title: 'Car Model',
                    width: '10%',
                    dependsOn: 'car_type_id',
                    options: function (data) {
                        if (data.dependedValues.car_type_id != null) {
                            $.ajax({
                                type: "POST",
                                url: base_url + '/admin/availability/getAllModelsByType?car_type_id=' + data.dependedValues.car_type_id,
                                dataType: "json",
                                cache: false,
                                success: function (result) {
                                    var $cityDDB = $('select#Edit-car_model_id').selectize();
                                    var selectizeCityDDB = $cityDDB[0].selectize;
                                    selectizeCityDDB.clear();
                                    selectizeCityDDB.clearOptions();
                                    selectizeCityDDB.load(function (callback) {
                                        callback(result);
                                        if (data.source == 'edit') {
                                            selectizeCityDDB.setValue(data.record.car_model_id)
                                        }
                                    });
                                }
                            });
                        }
                    }
                },
                booking_per_day: {
                    title: 'Bookings Per Day',
                    width: '10%'
                },

                active_status: {
                    title: 'Status',
                    width: '10%',
                    options: {
                        'active': 'Active',
                        'inactive': 'In-Active'
                    },
                    display: function (data) {
                        if (data.record.active_status == 'active') {
                            return '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                        }
                        else {
                            return '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                        }
                    }
                }
            }
        }).jtable('load');
        // change buttons visual style in ui-dialog
        $('.ui-dialog-buttonset')
            .children('button')
            .attr('class', '')
            .addClass('md-btn md-btn-flat')
            .off('mouseenter focus');
        $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
    }
};

/*end availability setup*/

$(function () {
    // crud table
    RedeemFactorTable.init();
});
RedeemFactorTable = {
    init: function () {
        $('#RedeemFactorTable').jtable({
            title: '<h3>Redeem Factors</h3>',
            sorting: true,
            defaultSorting: 'id ASC',
            paging: false, //Enable paging
            deleteConfirmation: function (data) {
                //data.deleteConfirmMessage = 'Are you sure to delete <br> " ' + data.record.eng_title + ' " ?';
                data.deleteConfirmMessage = 'Are you sure to delete this ?';
            },
            formCreated: function (event, data) {
                reInitDesignFix(data);
            },
            actions: redeemFactorActions(),
            fields: {
                id: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                from_points: {
                    title: 'From Points',
                    width: '10%'
                },
                to_points: {
                    title: 'To Points',
                    width: '10%'
                },
                points_per_riyal: {
                    title: 'Points Per Riyal',
                    width: '10%'
                }
            }
        }).jtable('load');
        // change buttons visual style in ui-dialog
        $('.ui-dialog-buttonset')
            .children('button')
            .attr('class', '')
            .addClass('md-btn md-btn-flat')
            .off('mouseenter focus');
        $('#AddRecordDialogSaveButton,#EditDialogSaveButton,#DeleteDialogButton').addClass('md-btn-flat-primary');
    }
};