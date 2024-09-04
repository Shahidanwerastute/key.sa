// International phone numbers plugin
$(".phone-primary, .phone-secondary").intlTelInput({
    //onlyCountries: ["sa", "eg"], initialCountry: "sa",
    initialCountry: "sa",
    excludeCountries: ["il"],
    nationalMode: false,
    separateDialCode: true,
    autoPlaceholder: "off",
    utilsScript: base_url + "/public/frontend/intTelInput/js/utils.js" // just for formatting/placeholders etc
});

$(document).ready(function () {
    $(document).on('focusout', '.phone-primary', function (event) {
        var countryData = $(this).intlTelInput("getSelectedCountryData");
        if (countryData.iso2 === 'sa') {
            var inputLengthMobile = $(this).val().length;
            if (inputLengthMobile !== 9) {
                $(this).css({"border": "1px solid red"});
                $('.submit_btn').attr('disabled', true);
            } else {
                $(this).css({
                    "border-color": "#afb0aa #e9eae4 #ebeae6",
                    "border-style": "solid",
                    "border-width": "1px"
                });
                $('.submit_btn').attr('disabled', false);
            }
        } else {
            $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
            $('.submit_btn').attr('disabled', false);
        }

        var intlNumber = $(".phone-primary").intlTelInput("getNumber");
        $('.intTelNo-primary').val(intlNumber);
    });
});

$(".phone-primary").on("countrychange", function (e, countryData) {
    var countryData = $(this).intlTelInput("getSelectedCountryData");
    if (countryData.iso2 === 'sa') {
        var inputLengthMobile = $(this).val().length;
        if (inputLengthMobile !== 9) {
            $(this).css({"border": "1px solid red"});
            $('.submit_btn').attr('disabled', true);
        } else {
            $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
            $('.submit_btn').attr('disabled', false);
        }
    } else {
        $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
        $('.submit_btn').attr('disabled', false);
    }

    var intlNumber = $(".phone-primary").intlTelInput("getNumber");
    $('.intTelNo-primary').val(intlNumber);
});


$(document).ready(function () {
    $(document).on('focusout', '.phone-secondary', function (event) {
        var countryData = $(this).intlTelInput("getSelectedCountryData");
        if (countryData.iso2 === 'sa') {
            var inputLengthMobile = $(this).val().length;
            if (inputLengthMobile !== 9) {
                $(this).css({"border": "1px solid red"});
                $('.submit_btn').attr('disabled', true);
            } else {
                $(this).css({
                    "border-color": "#afb0aa #e9eae4 #ebeae6",
                    "border-style": "solid",
                    "border-width": "1px"
                });
                $('.submit_btn').attr('disabled', false);
            }
        } else {
            $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
            $('.submit_btn').attr('disabled', false);
        }

        var intlNumber = $(".phone-secondary").intlTelInput("getNumber");
        $('.intTelNo-secondary').val(intlNumber);
    });
});

$(".phone-secondary").on("countrychange", function (e, countryData) {
    var countryData = $(this).intlTelInput("getSelectedCountryData");
    if (countryData.iso2 === 'sa') {
        var inputLengthMobile = $(this).val().length;
        if (inputLengthMobile !== 9) {
            $(this).css({"border": "1px solid red"});
            $('.submit_btn').attr('disabled', true);
        } else {
            $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
            $('.submit_btn').attr('disabled', false);
        }
    } else {
        $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
        $('.submit_btn').attr('disabled', false);
    }

    var intlNumber = $(".phone-secondary").intlTelInput("getNumber");
    $('.intTelNo-secondary').val(intlNumber);
});


if ($('.sidebar_main .submenu_trigger').hasClass('.current_section.submenu_trigger.act_section')) {
    $('.current_section.submenu_trigger.act_section').children('ul').css({"display": "block"});
}

$(document).ready(function () {

    $('.submit_ajax_form').click(function () {
        $('.ajax_form').submit();
    });
});

$(document).on('submit', '.ajax_form', function (e) {


    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }

    //e.preventDefault();
    $form = $(this);

    $form.find(':submit,:button').prop('disabled', true);
    buttonText = $form.find(':submit,:button').attr('value');
    $form.find(':submit,:button').attr('value', $form.find(':submit,:button').attr('temp-text'));

    btnText = $form.find('.btnText').html();
    $form.find('.btnText').html($form.find('.btnText').attr('temp-text'));


    $.ajax({
        type: $form.attr('method'),
        url: $form.attr('action'),
        data: new FormData(this),
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {
            if (result.success == 'false') {
                $("#alert-message-heading").html('Error');
                $("#alert-message").html(result.error);
                $form.find(':submit,:button').attr('value', buttonText);
                $form.find('.btnText').html(btnText);
                $form.find(':submit,:button').prop('disabled', false);
                $(".alert-message-button").click();
            } else {
                $form.find(':submit,:button').attr('value', buttonText);
                $form.find('.btnText').html(btnText);
                $form.find(':submit,:button').prop('disabled', false);
                $form.find('#file_upload-select').val('');
                $("#alert-message-heading").html('Success');
                $("#alert-message").html(result.success);
                $(".alert-message-button").click();
            }

        }
    });
});


$(document).on('click', '.jtable-toolbar-item-add-record', function (e) {
    $("form#jtable-create-form").attr("enctype", "multipart/form-data");
});


$(document).on('click', '.jtable-command-button.jtable-edit-command-button', function (e) {
    alert('edit');
    /*$("form#jtable-create-form").attr( "encoding", "multipart/form-data" );*/
    $("form#jtable-edit-form").attr("enctype", "multipart/form-data");
});


$(document).on('submit', '.settings_ajax_form', function (e) {
    if ($(this).hasClass('validate-form')) {
        if (validateForm() == false) {
            return false;
        }
    }
    $('#loader').show();
    $form = $(this);
    var url = $form.attr('action');
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        //data: $form.serialize(),
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            var message;
            if (response.status == true) {
                if (response.has_image == true) {

                    $("img#site_logo_image").attr("src", base_url + '/public/uploads/' + response.site_logo);
                    $("img#site_logo_mobile_image").attr("src", base_url + '/public/uploads/' + response.site_logo_mobile);
                    $("img#mobile_app_splash_screen_image").attr("src", base_url + '/public/uploads/' + response.mobile_app_splash_screen);

                    $("#old_site_logo").val(response.site_logo);
                    $("#old_site_logo_mobile").val(response.site_logo_mobile);
                    $("#old_mobile_app_splash_screen").val(response.mobile_app_splash_screen);

                }
                //alert(response.message);
            } else {
                //alert(response.message);
            }
            if (response.status == false) {
                message = 'Error';
            } else {
                message = 'Success';
            }

            $('#loader').hide();
            $form.find(':submit,:button').prop('disabled', false);
            $form.find('#file_upload-select').val('');
            $("#alert-message-heading").html(message);
            $("#alert-message").html(response.message);
            $(".alert-message-button").click();
        }
    });

});

$(document).on('submit', '.corporate_ajax_form', function (e) {
    if ($(this).hasClass('validate-form')) {
        if (validateForm() == false) {
            return false;
        }
    }
    $('#loader').show();
    $form = $(this);
    var url = $form.attr('action');
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        //data: $form.serialize(),
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            var message;
            if (response.status == true) {
                if (response.has_image == true) {
                    $("img#site_logo_image").attr("src", base_url + '/public/uploads/' + response.image_name);
                    $("#old_file").val(response.image_name);
                }
                //alert(response.message);
            } else {
                //alert(response.message);
            }
            if (response.status == false) {
                message = 'Error';
            } else {
                message = 'Success';
            }

            $('#loader').hide();
            $form.find(':submit,:button').prop('disabled', false);
            $form.find('#file_upload-select').val('');
            $("#alert-message-heading").html(message);
            $("#alert-message").html(response.message);
            $(".alert-message-button").click();

            // this code is to redirect to specific page, check in corporate customer save function.
            if (response.status === true) {
                if (typeof response.is_redirect !== undefined)
                    setTimeout(function () {
                        window.location.href = response.redirect_url;
                    }, 500);
            }
        }
    });

});


$(document).on('click', '.saveAvailabilityForm', function (e) {

    var car_model_id = $(this).attr('id');
    $form = $(this).parent(".md-card-toolbar-actions").parent(".md-card-toolbar").siblings("table.uk-table").children("tbody").children("form");
    var url = $form.attr('action');
    var branches = $('.branch_cb_' + car_model_id + ':checked').map(function () {
        return this.value;
    }).get();
    var is_indi_avail = $('.user_indi_cb_' + car_model_id + ':checked').map(function () {
        return this.value;
    }).get();
    var is_corp_avail = $('.user_corp_cb_' + car_model_id + ':checked').map(function () {
        return this.value;
    }).get();
    //var is_indi_avail = 1;
    //var is_corp_avail = 1;
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {
            car_model_id: car_model_id,
            branches: branches,
            is_indi_avail: is_indi_avail,
            is_corp_avail: is_corp_avail
        },
        success: function (response) {
            if (response.status == true) {
                $form.find(':submit,:button').prop('disabled', false);
                $form.find('#file_upload-select').val('');
                $("#alert-message-heading").html('Success');
                $("#alert-message").html(response.message);
                $(".alert-message-button").click();
            } else {
                $form.find(':submit,:button').prop('disabled', false);
                $form.find('#file_upload-select').val('');
                $("#alert-message-heading").html('Error');
                $("#alert-message").html('Failed to save data. Please try again.');
                $(".alert-message-button").click();
            }
        }
    });
});


$(document).on('change', '.checkAllcb', function (e) {
    var id = $(this).val();
    $('.branch_cb_' + id + ':checkbox').prop('checked', $(this).prop("checked"));
    //$("input:checkbox").prop('checked', $(this).prop("checked"));
});


function reInitDesignFix(Data) {

    // replace click event on some clickable elements

    // to make icheck label works
    $('.jtable_eng_desc').each(function (e) {

        CKEDITOR.replace(this.id, {toolbar: 'Full', width: '400px', height: '200px'});

    });

    $('.jtable_arb_desc').each(function (e) {

        CKEDITOR.replace(this.id, {language: 'ar', toolbar: 'Full', width: '400px', height: '200px'});

    });

    Data.form.find('.jtable-option-text-clickable').each(function () {

        var $thisTarget = $(this).prev().attr('id');

        $(this)

            .attr('data-click-target', $thisTarget)

            .off('click')

            .on('click', function (e) {

                e.preventDefault();

                $('#' + $(this).attr('data-click-target')).iCheck('toggle');

            })

    });

    // create selectize

    Data.form.find('select').each(function () {

        var $this = $(this);

        $this.after('<div class="selectize_fix"></div>')

            .selectize({

                dropdownParent: 'body',

                placeholder: 'Click here to select ...',

                onDropdownOpen: function ($dropdown) {

                    /*if ($this.attr('id') == 'Edit-region_id')

                     {

                     // Ajax call here

                     getRegionsThroughtAjax($this);

                     }*/

                    //alert($this.attr('id'));

                    $dropdown

                        .hide()

                        .velocity('slideDown', {

                            begin: function () {

                                $dropdown.css({'margin-top': '0'})

                            },

                            duration: 200,

                            easing: easing_swiftOut

                        })

                },

                onDropdownClose: function ($dropdown) {

                    $dropdown

                        .show()

                        .velocity('slideUp', {

                            complete: function () {

                                $dropdown.css({'margin-top': ''})

                            },

                            duration: 200,

                            easing: easing_swiftOut

                        })

                }

            });

    });

    // create icheck

    Data.form

        .find('input[type="checkbox"],input[type="radio"]')

        .each(function () {

            var $this = $(this);

            $this.iCheck({

                checkboxClass: 'icheckbox_md',

                radioClass: 'iradio_md',

                increaseArea: '20%'

            })

                .on('ifChecked', function (event) {

                    $this.parent('div.icheckbox_md').next('span').text('Active');

                })

                .on('ifUnchecked', function (event) {

                    $this.parent('div.icheckbox_md').next('span').text('Passive');

                })

        });

    // reinitialize inputs

    Data.form.find('.jtable-input').children('input[type="text"],input[type="password"],textarea').not('.md-input').each(function () {

        $(this).addClass('md-input');

        altair_forms.textarea_autosize();

    });

    altair_md.inputs();
    var is_delivery = Data.form.find('#Edit-is_delivery_branch').val();
    if (is_delivery == 'no') {
        $('#Edit-delivery_charges').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-delivery_charges').hide();
        $('#Edit-delivery_charges').attr('disabled', true);

        $('#Edit-delivery_coordinates').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-delivery_coordinates').hide();
        $('#Edit-delivery_coordinates').attr('disabled', true);

        $('#Edit-hours_for_delivery').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-hours_for_delivery').hide();
        $('#Edit-hours_for_delivery').attr('disabled', true);

        $('#Edit-capacity').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-capacity').hide();
        $('#Edit-capacity').attr('disabled', true);

        $('#Edit-eng_capacity_message').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-eng_capacity_message').hide();
        $('#Edit-eng_capacity_message').attr('disabled', true);

        $('#Edit-arb_capacity_message').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-arb_capacity_message').hide();
        $('#Edit-arb_capacity_message').attr('disabled', true);
    } else if (is_delivery == 'yes') {
        $('#Edit-delivery_charges').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-delivery_charges').show();
        $('#Edit-delivery_charges').attr('disabled', false);

        $('#Edit-delivery_coordinates').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-delivery_coordinates').show();
        $('#Edit-delivery_coordinates').attr('disabled', false);

        $('#Edit-hours_for_delivery').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-hours_for_delivery').show();
        $('#Edit-hours_for_delivery').attr('disabled', false);

        $('#Edit-capacity').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-capacity').show();
        $('#Edit-capacity').attr('disabled', false);

        $('#Edit-eng_capacity_message').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-eng_capacity_message').show();
        $('#Edit-eng_capacity_message').attr('disabled', false);

        $('#Edit-arb_capacity_message').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-arb_capacity_message').show();
        $('#Edit-arb_capacity_message').attr('disabled', false);
    }

    $('#Edit-delivery_coordinates').click(function () {
        var br_id;
        if ($('#polyline_map_picker').is(':hidden')) {
            $('#polyline_map_picker').show();
            if (typeof $('#Edit-id').val() !== 'undefined') {
                br_id = $('#Edit-id').val();
            } else {
                br_id = 0;
            }
            initMap(br_id);
        }
    });
}


$('.us3').locationpicker({
    location: {
        latitude: 21.2854,
        longitude: 39.2376
    },
    radius: 300,
    inputBinding: {
        latitudeInput: $('#Edit-map_latlng')
        //longitudeInput: $('#us3-lon'),
        // radiusInput: $('#us3-radius'),
        //locationNameInput: $('#us3-address')
    },
    enableAutocomplete: true,
    markerIcon: 'http://www.iconsdb.com/icons/preview/tropical-blue/map-marker-2-xl.png'
});
$('#Edit-map_latlng').on('shown.bs.modal', function () {
    $('.us3').locationpicker('autosize');
});


$(document).on('click', '#Edit-map_latlng', function (event) {
    var latlong = $('#Edit-map_latlng').val();

    var lat = '21.2854';
    var long = '39.2376';

    if ($('#Edit-map_latlng').data('isedit') == '1' && latlong != '') {
        var array = latlong.split(',');
        lat = array[0];
        long = array[1];
    }

    $('.us3').show();
    $('.us3').locationpicker({
        location: {
            'latitude': lat,
            'longitude': long
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        radius: 300,
        inputBinding: {
            latitudeInput: $(this).prev('input#Edit-map_latlng')
        },
        enableAutocomplete: true,
        onchanged: function (currentLocation, radius, isMarkerDropped) {

            $('#Edit-map_latlng').val(currentLocation.latitude + ", " + currentLocation.longitude);
        }
    });
});

// Edit-is_delivery_branch
$(document).on('change', '#Edit-is_delivery_branch', function (event) {
    var is_delivery_branch = $(this).val();
    if (is_delivery_branch == 'no') {
        $('#Edit-delivery_charges').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-delivery_charges').hide();
        $('#Edit-delivery_charges').attr('disabled', true);

        $('#Edit-delivery_coordinates').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-delivery_coordinates').hide();
        $('#Edit-delivery_coordinates').attr('disabled', true);

        $('#Edit-hours_for_delivery').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-hours_for_delivery').hide();
        $('#Edit-hours_for_delivery').attr('disabled', true);

        $('#Edit-capacity').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-capacity').hide();
        $('#Edit-capacity').attr('disabled', true);

        $('#Edit-eng_capacity_message').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-eng_capacity_message').hide();
        $('#Edit-eng_capacity_message').attr('disabled', true);

        $('#Edit-arb_capacity_message').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').hide();
        $('#Edit-arb_capacity_message').hide();
        $('#Edit-arb_capacity_message').attr('disabled', true);
    } else if (is_delivery_branch == 'yes') {
        $('#Edit-delivery_charges').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-delivery_charges').show();
        $('#Edit-delivery_charges').attr('disabled', false);

        $('#Edit-delivery_coordinates').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-delivery_coordinates').show();
        $('#Edit-delivery_coordinates').attr('disabled', false);

        $('#Edit-hours_for_delivery').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-hours_for_delivery').show();
        $('#Edit-hours_for_delivery').attr('disabled', false);

        $('#Edit-capacity').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-capacity').show();
        $('#Edit-capacity').attr('disabled', false);

        $('#Edit-eng_capacity_message').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-eng_capacity_message').show();
        $('#Edit-eng_capacity_message').attr('disabled', false);

        $('#Edit-arb_capacity_message').parent('.md-input-wrapper').parent('.jtable-input').parent('.jtable-input-field-container').show();
        $('#Edit-arb_capacity_message').show();
        $('#Edit-arb_capacity_message').attr('disabled', false);
    }
});

$(document).on('submit', '#importBookingForm', function () {
    $('#importBookingLoader').show();
    var url = $('#importBookingForm').attr('action');

    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
            $('#importBookingLoader').hide();
            if (response.status == true) {
                alert(response.msg);
            } else {
                alert(response.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#importBookingLoader').hide();
            alert('Some Error Occurred.');
        }
    });

});

$(document).on('submit', '#importCustomersForm', function () {
    $('#importCustomerLoader').show();
    var url = $('#importCustomersForm').attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
            $('#importCustomerLoader').hide();
            if (response.status == true) {
                alert(response.msg);
            } else {
                alert(response.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#importCustomerLoader').hide();
            console.log(XMLHttpRequest);
            alert("Error: " + XMLHttpRequest.responseText);
            //alert("Status: " + textStatus);
            //alert("Error: " + errorThrown);
            //alert('Some Error Occurred.');
        }
    });

});

$(document).on('submit', '#importCustomerLoyalty', function () {
    $('#importLoyaltyLoader').show();
    var url = $('#importCustomerLoyalty').attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
            $('#importLoyaltyLoader').hide();
            if (response.status == true) {
                alert(response.msg);
            } else {
                alert(response.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#importLoyaltyLoader').hide();
            alert('Some Error Occurred.');
        }
    });

});

$(document).on('submit', '#importCustomerSimahInfo', function () {
    $('#importSimahLoader').show();
    var url = $('#importCustomerSimahInfo').attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
            $('#importSimahLoader').hide();
            if (response.status == true) {
                alert(response.msg);
            } else {
                alert(response.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#importSimahLoader').hide();
            alert('Some Error Occurred.');
        }
    });

});

$(document).on('submit', '#importCustomerBlackListInfo', function () {
    $('#importBlackListLoader').show();
    var url = $('#importCustomerBlackListInfo').attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
            $('#importBlackListLoader').hide();
            if (response.status === true) {
                alert(response.msg);
            } else {
                alert(response.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#importBlackListLoader').hide();
            alert('Some Error Occurred.');
        }
    });

});

$(document).on('submit', '#importCorporateInvoices', function () {
    $('#importCorporateInvoicesLoader').show();
    var url = $('#importCorporateInvoices').attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
            $('#importCorporateInvoicesLoader').hide();
            if (response.status === true) {
                alert(response.msg);
            } else {
                alert(response.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#importCorporateInvoicesLoader').hide();
            alert('Some Error Occurred.');
        }
    });

});

$(document).on('submit', '#importCorporateLeaseInvoices', function () {
    $('#importCorporateLeaseInvoicesLoader').show();
    var url = $('#importCorporateLeaseInvoices').attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
            $('#importCorporateLeaseInvoicesLoader').hide();
            if (response.status === true) {
                alert(response.msg);
            } else {
                alert(response.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#importCorporateLeaseInvoicesLoader').hide();
            alert('Some Error Occurred.');
        }
    });

});


$(document).on('submit', '.export-booking', function () {
    $('#exportLoader').show();
    var url = $('.export-booking').attr('action');
    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
            $('#exportLoader').hide();
            if (response.status == true) {
                alert(response.msg);
            } else {
                alert(response.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#exportLoader').hide();
            alert('Some Error Occurred.');
        }
    });

});


$(document).on('submit', '.export-cancelled-booking', function () {
    $('#exportCancelledBookingsLoader').show();
    var url = $('.export-cancelled-booking').attr('action');
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    window.location.href = url + '?from_date=' + from_date + '&to_date=' + to_date;
    $('#exportCancelledBookingsLoader').hide();
});

$(document).on('submit', '.export-career', function () {
    $('#exportCareerLoader').show();
    var url = $('.export-career').attr('action');
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    window.location.href = url + '?from_date=' + from_date + '&to_date=' + to_date;
    $('#exportCareerLoader').hide();
});

$('.empty_corporate_invoices_from_db').click(function (e) {
    if (confirm('Are you sure to continue?')) {
        e.preventDefault();
        $('#empty_corporate_invoices_from_db_loader').show();
        var url = $(this).data('url');
        $.get(url, function (response) {
            $('#empty_corporate_invoices_from_db_loader').hide();
            alert(response);
        });
    }
});

/*
 alert($('input[type="checkbox"]:checked').map(
 function () {return this.value;}).get().join(","));

 var branches = $('.extraChargesCB:checked').map(function () {
 return this.value;
 }).get();*/
// sec_shift
$(document).on('click', '.checkbox-role', function (e) {
    if ($(this).is(':checked')) {
        $(this).next("input[type=hidden]").attr("disabled", "disabled");
    } else {
        $(this).next("input[type=hidden]").removeAttr("disabled");
        $(this).next("input[type=hidden]").val("0");
    }
});

$(document).on('click', '.saveUserRights', function (e) {

    var $role_id = $(this).attr('id');
    $form = $(this).parent(".md-card-toolbar-actions").parent(".md-card-toolbar").siblings("form");
    var url = $form.attr('action');
    var formData = $(this).parent(".md-card-toolbar-actions").parent(".md-card-toolbar").siblings("form.updateUserRights").serialize();

    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        dataType: "json",
        //data: new FormData($form),
        //dataType: "json",
        //cache: false,
        //contentType: false,
        //processData: false,
        success: function (response) {
            if (response.status == true) {
                $form.find(':submit,:button').prop('disabled', false);
                $form.find('#file_upload-select').val('');
                $("#alert-message-heading").html('Success');
                $("#alert-message").html(response.message);
                $(".alert-message-button").click();
            } else {
                $form.find(':submit,:button').prop('disabled', false);
                $form.find('#file_upload-select').val('');
                $("#alert-message-heading").html('Error');
                $("#alert-message").html('Failed to save data. Please try again.');
                $(".alert-message-button").click();
            }
        }
    });
});

$('.multiselect').selectize({
    plugins: {
        'remove_button': {
            label: ''
        }
    },
    maxItems: null,
    valueField: 'id',
    labelField: 'title',
    searchField: 'title',
    create: false,
    render: {
        option: function (data, escape) {
            return '<div class="option">' +
                '<span class="title">' + escape(data.title) + '</span>' +
                '</div>';
        },
        item: function (data, escape) {
            return '<div class="item"><a href="' + escape(data.url) + '" target="_blank">' + escape(data.title) + '</a></div>';
        }
    },
    onDropdownOpen: function ($dropdown) {
        $dropdown
            .hide()
            .velocity('slideDown', {
                begin: function () {
                    $dropdown.css({'margin-top': '0'})
                },
                duration: 200,
                easing: easing_swiftOut
            })
    },
    onDropdownClose: function ($dropdown) {
        $dropdown
            .show()
            .velocity('slideUp', {
                complete: function () {
                    $dropdown.css({'margin-top': ''})
                },
                duration: 200,
                easing: easing_swiftOut
            })
    }
});

function deleteAdmin(admin_id) {
    if (confirm("Are you sure you want to delete this admin user?")) {
        $.ajax({
            type: "POST",
            url: base_url + '/admin/page/deleteAdmin',
            dataType: "json",
            data: {admin_id: admin_id},
            success: function (response) {
                if (response.status == true) {
                    $('#' + admin_id + '_row').animate({width: 'toggle'}, 350);
                    //$('#'+admin_id+'_row').hide();
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            }
        });
    }
}


$(document).on('change', '#car_categories', function (e) {
    var category_id = $(this).val();
    var $select = $('select#car_groups').selectize();

    var selectize = $select[0].selectize;

    $.ajax({
        type: "POST",
        url: base_url + '/admin/pricing/getGroupsForCategory',
        data: {'category_id': category_id},
        dataType: "json",
        cache: false,
        //async:false,
        success: function (result) {
            //alert(result.dropdown_options);
            selectize.clearOptions();
            var option = result.dropdown_options.split(',');
            var opt;
            for (var i = 0; i < option.length; i++) {
                opt = option[i].split('|');
                selectize.addOption({
                    value: opt[0],
                    text: opt[1]
                });
            }

            //selectize.refreshOptions();
        }
    });
});

$(document).on('change', '#car_groups', function (e) {
    var group_id = $(this).val();
    var $select = $('select#car_types').selectize();

    var selectize = $select[0].selectize;

    $.ajax({
        type: "POST",
        url: base_url + '/admin/pricing/getTypeForGroups',
        data: {'group_id': group_id},
        dataType: "json",
        cache: false,
        //async:false,
        success: function (result) {
            //alert(result.dropdown_options);
            selectize.clearOptions();
            var option = result.dropdown_options.split(',');
            var opt;
            for (var i = 0; i < option.length; i++) {
                opt = option[i].split('|');
                selectize.addOption({
                    value: opt[0],
                    text: opt[1]
                });
            }

            //selectize.refreshOptions();
        }
    });
});

$(document).on('change', '#car_types', function (e) {
    var type_id = $(this).val();
    var $select = $('select#car_models').selectize();

    var selectize = $select[0].selectize;

    $.ajax({
        type: "POST",
        url: base_url + '/admin/pricing/getModelsForType',
        data: {'type_id': type_id},
        dataType: "json",
        cache: false,
        //async:false,
        success: function (result) {
            //alert(result.dropdown_options);
            selectize.clearOptions();
            var option = result.dropdown_options.split(',');
            var opt;
            for (var i = 0; i < option.length; i++) {
                opt = option[i].split('|');
                selectize.addOption({
                    value: opt[0],
                    text: opt[1]
                });
            }
            //selectize.refreshOptions();
        }
    });
});

// _branch_active
$(document).on('change', '#region', function (e) {
    var region_id = $(this).val();
    var $select = $('select#city').selectize();

    var selectize = $select[0].selectize;

    $.ajax({
        type: "POST",
        url: base_url + '/admin/pricing/getCitiesForRegion',
        data: {'region_id': region_id},
        dataType: "json",
        cache: false,
        //async:false,
        success: function (result) {
            //alert(result.dropdown_options);
            selectize.clearOptions();
            var option = result.dropdown_options.split(',');
            var opt;
            for (var i = 0; i < option.length; i++) {
                opt = option[i].split('|');
                selectize.addOption({
                    value: opt[0],
                    text: opt[1]
                });
            }
            //selectize.refreshOptions();
        }
    });
});


$(document).on('change', '#region_branch_active', function (e) {
    var region_id = $(this).val();
    var $select = $('select#city_branch_active').selectize();

    var selectize = $select[0].selectize;

    $.ajax({
        type: "POST",
        url: base_url + '/admin/pricing/getCitiesForRegion',
        data: {'region_id': region_id},
        dataType: "json",
        cache: false,
        //async:false,
        success: function (result) {
            //alert(result.dropdown_options);
            selectize.clearOptions();
            var option = result.dropdown_options.split(',');
            var opt;
            for (var i = 0; i < option.length; i++) {
                opt = option[i].split('|');
                selectize.addOption({
                    value: opt[0],
                    text: opt[1]
                });
            }
            //selectize.refreshOptions();
        }
    });
});


$(document).on('change', '#city', function (e) {
    var city_id = $(this).val();
    var $select = $('select#branch').selectize();

    var selectize = $select[0].selectize;

    $.ajax({
        type: "POST",
        url: base_url + '/admin/pricing/getBranchesForCity',
        data: {'city_id': city_id},
        dataType: "json",
        cache: false,
        //async:false,
        success: function (result) {
            //alert(result.dropdown_options);
            selectize.clearOptions();
            var option = result.dropdown_options.split(',');
            var opt;
            for (var i = 0; i < option.length; i++) {
                opt = option[i].split('|');
                selectize.addOption({
                    value: opt[0],
                    text: opt[1]
                });
            }
            //selectize.refreshOptions();
        }
    });
});


$(document).on('change', '#city_branch_active', function (e) {
    var city_id = $(this).val();
    var $select = $('select#branch_branch_active').selectize();

    var selectize = $select[0].selectize;

    $.ajax({
        type: "POST",
        url: base_url + '/admin/pricing/getBranchesForCity',
        data: {'city_id': city_id},
        dataType: "json",
        cache: false,
        //async:false,
        success: function (result) {
            //alert(result.dropdown_options);
            selectize.clearOptions();
            var option = result.dropdown_options.split(',');
            var opt;
            for (var i = 0; i < option.length; i++) {
                opt = option[i].split('|');
                selectize.addOption({
                    value: opt[0],
                    text: opt[1]
                });
            }
            //selectize.refreshOptions();
        }
    });
});


function validateForm() {
    var returnVal = true;

    $("input.required, select.required, textarea.required").each(function () {
        $(this).removeClass('md-input-danger');
        if ($(this).val() == '' || $(this).val() == null) {
            $(this).addClass('md-input-danger');
            returnVal = false;
        }
    });

    return returnVal;
}


$('.runCronJob').click(function (e) {
    e.preventDefault();
    altair_helpers.content_preloader_show();
    var url = $(this).data('url');
    $.get(url, function (response) {
        altair_helpers.content_preloader_hide();
        setTimeout(function() {
            alert(response);
        }, 500);
    });
});

/*$('.runCronJob').click(function (e) {
    e.preventDefault();
    $('#cronjobLoader').show();
    var url = $(this).data('url');
    $.ajax({
        type: "GET",
        url: url,
        cache: false,
        success: function (result) {
            $('#cronjobLoader').hide();
            alert(result);
        }
    });
});*/

$('#filter_pending_bookings').click(function (e) {
    e.preventDefault();
    var filter_date = $('#filter_date').val();
    if (filter_date !== '') {
        $('#PendingBookingsTable').jtable('load', {
            filter_date: filter_date
        });
    } else {
        alert('Please select a date to filter');
    }
});

$('#search_pending_bookings_by_customer').click(function (e) {
    e.preventDefault();
    var pending_search_keyword_customer = $('#pending_search_keyword_customer').val();
    var search_type = $('#search_type').val();

    $('#PendingBookingsTable').jtable('load', {
        pending_search_keyword_customer: pending_search_keyword_customer,
        search_type: search_type
    });

});

$('#search_pending_bookings_by_booking').click(function (e) {
    e.preventDefault();
    var pending_search_keyword_booking = $('#pending_search_keyword_booking').val();

    $('#PendingBookingsTable').jtable('load', {
        pending_search_keyword_booking: pending_search_keyword_booking
    });

});


$('#export_pending_bookings').click(function (e) {
    e.preventDefault();
    var filter_date = $('#filter_date').val();
    $.ajax({
        type: "POST",
        url: base_url + '/admin/bookings/exportPendingBookings',
        data: {'filter_date': filter_date},
        dataType: "json",
        cache: false,
        //async:false,
        success: function (result) {

        }
    });
});


$('#search_bookings_by_customer').click(function (e) {
    e.preventDefault();
    var search_keyword_customer = $('#search_keyword_customer').val();
    var search_type = $('#search_type').val();

    $('#BookingsTable').jtable('load', {
        search_keyword_customer: search_keyword_customer,
        search_type: search_type
    });

});

$('#search_bookings_by_booking').click(function (e) {
    e.preventDefault();
    var search_keyword_booking = $('#search_keyword_booking').val();

    $('#BookingsTable').jtable('load', {
        search_keyword_booking: search_keyword_booking
    });

});

$('#search_customer').click(function (e) {
    e.preventDefault();
    var search_keyword = $('#search_keyword').val();

    $('#individualCustomersTable').jtable('load', {
        search_keyword: search_keyword
    });

});

/*$('#polyline_map_picker').locationpicker({
 location: {
 latitude: 21.2854,
 longitude: 39.2376
 },
 radius: 300,
 inputBinding: {
 latitudeInput: $('#Edit-delivery_coordinates')
 //longitudeInput: $('#us3-lon'),
 // radiusInput: $('#us3-radius'),
 //locationNameInput: $('#us3-address')
 },
 enableAutocomplete: true,
 markerIcon: 'http://www.iconsdb.com/icons/preview/tropical-blue/map-marker-2-xl.png'
 });

 $('#Edit-delivery_coordinates').on('shown.bs.modal', function () {
 $('#polyline_map_picker').locationpicker('autosize');
 });


 $(document).on('click', '#Edit-delivery_coordinates', function (event) {

 var lat = '21.2854';
 var long = '39.2376';

 if ($('#Edit-delivery_coordinates').data('isedit') == '1') {
 var latlong = $('#Edit-delivery_coordinates').val();
 var array = latlong.split(',');
 lat = array[0];
 long = array[1];

 }

 $('#polyline_map_picker').show();
 $('#polyline_map_picker').locationpicker({
 location: {
 'latitude': lat,
 'longitude': long
 },
 mapTypeId: google.maps.MapTypeId.ROADMAP,
 radius: 300,
 inputBinding: {
 latitudeInput: $(this).prev('input#Edit-delivery_coordinates')
 },
 enableAutocomplete: true,
 onchanged: function (currentLocation, radius, isMarkerDropped) {

 $('#Edit-delivery_coordinates').val(currentLocation.latitude + ", " + currentLocation.longitude);
 }
 });
 });*/
// Edit-delivery_coordinates
/* $('#Edit-delivery_coordinates').click(function () {
 alert('clicked');
 $('#polyline_map_picker').show();
 initMap();
 });*/

$('#Edit-delivery_coordinates').click(function () {
    alert('clicked');
    //$('#polyline_map_picker').show();
    //initMap();
});
/*$(document).on('focusout', '#Edit-delivery_coordinate', function (event) {
 alert('now clicked');
 });*/


var poly;
var map;
var myMvcArr;
var length;
var m;
var point;
var valueBefore;
var valueAfter;
var ContainedArr;
/*
 */
var arr = [
    {lat: 41.679, lng: -87.22399999999999},
    {lat: 41.879, lng: -87.62400000000002},
    {lat: 39.740986355883564, lng: -86.143798828125},
    {lat: 39.93501296038254, lng: -82.94677734375},
    {lat: 41.45919537950706, lng: -81.617431640625},
    {lat: 42.30169032824449, lng: -82.99072265625},
    {lat: 42.932296019030574, lng: -85.62744140625},
    {lat: 42.98053954751643, lng: -87.890625},
    {lat: 41.9022770409637, lng: -87.659912109375}
];
var checkpoint = "41.599013054830216,-87.0556640625";


function initMap(branch_id) {
    map = new google.maps.Map(document.getElementById('polyline_map_picker'), {
        zoom: 7,
        center: {lat: 24.12670195868167, lng: 45.644073486328125},  // Center the map on Chicago, USA. // 24.12670195868167,45.644073486328125
        gestureHandling: 'greedy'
    });

    poly = new google.maps.Polyline({
        strokeColor: '#000000',
        strokeOpacity: 1.0,
        strokeWeight: 3
    });
    poly.setMap(map);


    // Add a listener for the click event
    map.addListener('click', addLatLng);

    if (branch_id !== 0) {
        getAjaxCoordinates(branch_id);
    }

    google.maps.Polygon.prototype.Contains = function (point) {
        // ray casting alogrithm http://rosettacode.org/wiki/Ray-casting_algorithm
        var crossings = 0,
            path = this.getPath();

        // for each edge
        for (var i = 0; i < path.getLength(); i++) {
            var a = path.getAt(i),
                j = i + 1;
            if (j >= path.getLength()) {
                j = 0;
            }
            var b = path.getAt(j);
            if (rayCrossesSegment(point, a, b)) {
                crossings++;
            }
        }

        // odd number of crossings?
        return (crossings % 2 == 1);

        function rayCrossesSegment(point, a, b) {
            var px = point.lng(),
                py = point.lat(),
                ax = a.lng(),
                ay = a.lat(),
                bx = b.lng(),
                by = b.lat();
            if (ay > by) {
                ax = b.lng();
                ay = b.lat();
                bx = a.lng();
                by = a.lat();
            }
            // alter longitude to cater for 180 degree crossings
            if (px < 0) {
                px += 360
            }
            ;
            if (ax < 0) {
                ax += 360
            }
            ;
            if (bx < 0) {
                bx += 360
            }
            ;

            if (py == ay || py == by) py += 0.00000001;
            if ((py > by || py < ay) || (px > Math.max(ax, bx))) return false;
            if (px < Math.min(ax, bx)) return true;

            var red = (ax != bx) ? ((by - ay) / (bx - ax)) : Infinity;
            var blue = (ax != px) ? ((py - ay) / (px - ax)) : Infinity;
            return (blue >= red);

        }

    };

}

function saveCoordinates() {
    var i = 0;
    myMvcArr = poly.getPath();
    length = myMvcArr.getLength();
    myMvcArr.forEach(function (entry) {
        m = myMvcArr.getAt(i);
        checkIfPointInside(m.lat(), m.lng(), arr);
        console.log(m.lat());
        console.log(m.lng());
        //console.log(entry);
        i++;
    });

}

function getAjaxCoordinates(branch_id) {

    $.ajax({
        type: "POST",
        url: base_url + '/admin/branch/getBranchDeliveryCoordinates',
        data: {branch_id: branch_id},
        dataType: "json",
        success: function (response) {
            if (response.status == true) {
                var coordin;
                var path = poly.getPath();
                var coordinates = response.coordinates.split('|');
                //after ajax call, loop here
                //console.log(coordinates);
                for (var i = 0; i < coordinates.length; i++) {

                    //alert(coordinates[i]);
                    //console.log(coordinates[i]);
                    coordin = coordinates[i].split(",");
                    //console.log(coordin[0]+","+coordin[1]);
                    path.push(new google.maps.LatLng(coordin[0], coordin[1]));
                }
                /* path.push(new google.maps.LatLng(42.44778143462245,-88.74755859375));
                 path.push(new google.maps.LatLng(42.13082130188811,-88.758544921875));
                 path.push(new google.maps.LatLng(41.60722821271717,-87.86865234375));
                 path.push(new google.maps.LatLng(42.261049162113856,-87.0556640625));*/
                //path.push(new google.maps.LatLng(41.679, -87.224));
                //path.push(new google.maps.LatLng(41.879, -87.624));
            }
        }
    });
}

function addLatLng(event) {
    //alert(valueBefore);
    var path = poly.getPath();

    // Because path is an MVCArray, we can simply append a new coordinate
    // and it will automatically appear.
    path.push(event.latLng);

    valueBefore = $('#Edit-delivery_coordinates').val();
    if (valueBefore == '') {
        valueAfter = event.latLng;
    } else {
        valueAfter = valueBefore + '|' + event.latLng;
    }
    $('#Edit-delivery_coordinates').val(valueAfter);
    //alert(valueAfter);

    // Add a new marker at the new plotted point on the polyline.
    /*var marker = new google.maps.Marker({
     position: event.latLng,
     title: '#' + path.getLength(),
     map: map
     });*/
}

function resetMap() {
    // code to clear the map coordinates here
    /*map = new google.maps.Map(document.getElementById('map'));
     google.maps.event.trigger(map, 'resize');*/
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 7,
        center: {lat: 41.879, lng: -87.624},  // Center the map on Chicago, USA.
        gestureHandling: 'greedy'
    });

    google.maps.event.trigger(map, 'resize');
}

function clearCoordinates() {
    // code to clear the map coordinates here
    initMap();
}

$(document).on('click', '#clearMapData', function (event) {
    if (confirm("Are you sure you want to clear this map?")) {
        $('#Edit-delivery_coordinates').val('');
        initMap(0);
    }
});

$(document).ready(function () {
    //For numeric
    $(document).on('keydown', '.only-number', function (event) {
        // Allow only backspace and delete
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 107) {
            // let it happen, don't do anything

        } else {
            // Ensure that it is a number and stop the keypress
            if ((event.keyCode !== 9) && (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 106)) {
                event.preventDefault();
            } else {

                if ($.trim($(this).val()) == '') {
                    if (event.keyCode == 48) {
                        event.preventDefault();
                    }
                }
            }
        }
    });

    //For numeric
    $(document).on('keydown', '.only-numbers-with-decimal', function (event) {
        // only numbers, decimal point, delete and backspace
        if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 110 || event.keyCode == 190 || event.keyCode == 46 || event.keyCode == 8) {
            // let it happen, don't do anything

        } else {
            event.preventDefault();
        }
    });
});
$(document).on("keyup", "input[name='company_code']", function () {
    if ($(this).val().length !== 10) {
        $(this).siblings("label").text("");
        $(this).addClass('md-input-danger');
        $(this).siblings("label").html("Company Code <small style='color: red;'>* Must Contain Only 10 Characters</small>");
        $(":submit").attr("disabled", true);
    } else {
        $(this).removeClass('md-input-danger');
        $(this).siblings("label").text("Company Code");
        $(":submit").attr("disabled", false);
    }
});

// resetAdminPassword
$(document).on("click", "#resetAdminPassword", function () {
    var password = $('#admin_password').val();
    var confirm_password = $('#admin_confirm_password').val();
    if (validateForm() == true) {
        if (password !== confirm_password) {
            $("#alert-message-heading").html('Error');
            $("#alert-message").html('Password and confirm password didn\'t match.');
            $(".alert-message-button").click();
        } else {
            $.ajax({
                type: 'POST',
                url: base_url + '/admin/resetPassword',
                dataType: "json",
                data: {password: password, confirm_password: confirm_password},
                success: function (response) {
                    $("#alert-message-heading").html(response.title);
                    $("#alert-message").html(response.message);
                    $(".alert-message-button").click();
                    $('#admin_password').val('');
                    $('#admin_confirm_password').val('');
                }
            });
        }
    }
});

// Resend STS Pay Later Invoice
$(document).on("click", "#btnResendInvoice", function () {
    var bookingId = $(this).attr("data-bookingId");
    var total_sum = $(this).attr("data-total_sum");
    var payment_lang = $(this).attr("data-lang");
    $.ajax({
        type: 'POST',
        url: base_url + '/resendPayLaterInvoice',
        dataType: "json",
        data: {bookingId: bookingId, total_sum: total_sum, lang: payment_lang},
        success: function (response) {
            $("#alert-message-heading").html(response.title);
            $("#alert-message").html(response.message);
            $(".alert-message-button").click();
        }
    });
});

function deletLoyaltyImg(id, type) {
    if (confirm("are you sure you want to delete?")) {
        $.ajax({
            type: 'POST',
            url: base_url + '/admin/page/delete_loyalty_image',
            dataType: "json",
            data: {id: id, type: type},
            success: function (response) {
                $("#alert-message-heading").html(response.title);
                $("#alert-message").html(response.message);
                $(".alert-message-button").click();
                $("#" + type + "_big_img").remove();
            }
        });
    }

}

$('.kendo-date-picker').kendoDatePicker({
    format: "d-MM-yyyy"
});

$('.kendo-date-time-picker').kendoDateTimePicker({
    format: "yyyy-MM-dd HH:mm:ss"
});

$(document).on('click', '.decrypt_data', function() {
    var post_data = '';
    var encrypted_keys = ['username', 'password', 'paytabs_merchant_email', 'paytabs_merchant_id', 'paytabs_secret_key', 'sts_merchant_id_web', 'sts_secret_key_web', 'sts_payment_link', 'sts_payment_inquiry_link', 'sts_paylater_merchant_id', 'sts_paylater_secret_key', 'sts_paylater_send_invoice_link', 'sts_paylater_invoice_inquiry_link', 'sts_merchant_id_mobile', 'sts_secret_key_mobile', 'hyper_pay_endpoint_url', 'hyper_pay_bearer_token', 'hyper_pay_entity_id_master_visa', 'hyper_pay_entity_id_mada', 'hyper_pay_entity_id_apple_pay', 'hyper_bill_username', 'hyper_bill_password', 'hyper_bill_endpoint_url', 'oasis_api_url', 'qitaf_api_base_url', 'edit_booking_sync_api_url', 'unifonic_username', 'unifonic_password', 'unifonic_sender_id', 'unifonic_app_id', 'taqnyat_bearer_token', 'taqnyat_sender_id'];
    $.each(encrypted_keys, function (index, value) {
        post_data += value +'='+ $('input[name="' + value + '"]').val() + '||';
    });

    $.ajax({
        type: 'POST',
        url: base_url + '/admin/settings/decrypt_encrypt_data',
        data: { 'post_data': post_data, 'operation': 'decrypt' },
        beforeSend: function () {
            altair_helpers.content_preloader_show();
        },
        complete: function () {
            altair_helpers.content_preloader_hide();
        },
        success: function (response) {
            var data_array = response.split('||');
            $.each(data_array, function (index, value) {
                var data = value.split('=');
                $('input[name="' + data[0] + '"]').val(data[1]);
            });
            $('.decrypt_data').hide();
            $('.encrypt_data').show();
        }
    });

});

$(document).on('click', '.encrypt_data', function() {
    var post_data = '';
    var encrypted_keys = ['username', 'password', 'paytabs_merchant_email', 'paytabs_merchant_id', 'paytabs_secret_key', 'sts_merchant_id_web', 'sts_secret_key_web', 'sts_payment_link', 'sts_payment_inquiry_link', 'sts_paylater_merchant_id', 'sts_paylater_secret_key', 'sts_paylater_send_invoice_link', 'sts_paylater_invoice_inquiry_link', 'sts_merchant_id_mobile', 'sts_secret_key_mobile', 'hyper_pay_endpoint_url', 'hyper_pay_bearer_token', 'hyper_pay_entity_id_master_visa', 'hyper_pay_entity_id_mada', 'hyper_pay_entity_id_apple_pay', 'hyper_bill_username', 'hyper_bill_password', 'hyper_bill_endpoint_url', 'oasis_api_url', 'qitaf_api_base_url', 'edit_booking_sync_api_url', 'unifonic_username', 'unifonic_password', 'unifonic_sender_id', 'unifonic_app_id', 'taqnyat_bearer_token', 'taqnyat_sender_id'];
    $.each(encrypted_keys, function (index, value) {
        post_data += value +'='+ $('input[name="' + value + '"]').val() + '||';
    });

    $.ajax({
        type: 'POST',
        url: base_url + '/admin/settings/decrypt_encrypt_data',
        data: { 'post_data': post_data, 'operation': 'encrypt' },
        beforeSend: function () {
            altair_helpers.content_preloader_show();
        },
        complete: function () {
            altair_helpers.content_preloader_hide();
        },
        success: function (response) {
            var data_array = response.split('||');
            $.each(data_array, function (index, value) {
                var data = value.split('=');
                $('input[name="' + data[0] + '"]').val(data[1]);
            });
            $('.encrypt_data').hide();
            $('.update_btn').show();
        }
    });

});

var users_count = $('.corporate-users-area').find('.uk-form-row').length;
function addMore() {
    var html = '<div class="uk-form-row uk-width-1-1"><div class="uk-grid" data-uk-grid-margin=""><div class="uk-width-medium-1-4 uk-row-first"><div class="md-input-wrapper"><label>Username</label><input type="email" class="md-input required" name="username[]"><span class="md-input-bar "></span></div></div><div class="uk-width-medium-1-4 uk-row-first"><div class="md-input-wrapper"><label>Password</label><input type="text" class="md-input required" name="password[]"><span class="md-input-bar "></span></div></div><div class="uk-width-medium-1-4 uk-row-first"><label for="is_email_verified">Is Email Verified?</label><br><select class="md-input required" id="is_email_verified" name="is_email_verified[]"><option value="0">NO</option><option value="1">YES</option></select></div><div class="uk-width-medium-1-4 uk-row-first"><label for="is_phone_verified">Is Phone Verified?</label><br><select class="md-input required" id="is_phone_verified" name="is_phone_verified[]"><option value="0">NO</option><option value="1">YES</option></select></div></div></div>';
    $('.corporate-users-area').append(html);
    users_count++;
    if (users_count === 3) {
        $('.add-more').hide();
    }
}

function export_manage_bookings() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var export_type = $('#export_type:checked').val();
    var url = base_url + '/admin/export_manage_bookings?from_date=' + from_date + '&to_date=' + to_date + '&export_type=' + export_type;
    window.location.href = url;
}

function get_bookings_count_for_export_in_manage_bookings() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var export_type = $('#export_type:checked').val();
    var count_type = $('#count_type').val();

    $.ajax({
        type: 'POST',
        url: base_url + '/admin/get_bookings_count_for_export_in_manage_bookings',
        data: { 'from_date': from_date, 'to_date': to_date, 'export_type': export_type, 'count_type': count_type },
        beforeSend: function () {
            altair_helpers.content_preloader_show();
        },
        complete: function () {
            altair_helpers.content_preloader_hide();
        },
        success: function (response) {
            alert(response);
        }
    });

}

function search_manage_bookings() {
    var search = $('#search').val();
    var search_type = $('#search_type:checked').val();

    if (search.trim()) {
        $.ajax({
            type: 'POST',
            url: base_url + '/admin/search_manage_bookings',
            data: { 'search': search, 'search_type': search_type },
            beforeSend: function () {
                altair_helpers.content_preloader_show();
            },
            complete: function () {
                altair_helpers.content_preloader_hide();
            },
            success: function (response) {
                $('#response-html').html(response);
            }
        });
    }
}

$(document).on('click', '.add_booking_payment', function() {
    $('#open_manage_booking_add_booking_form').click();
})

$(document).on('click', '.edit_booking_payment', function() {
    var data = $(this).data();
    $('#manage_booking_edit_booking_form').find('[name="booking_id"]').val(data.booking_id);
    $('#manage_booking_edit_booking_form').find('[name="trans_date"]').val(data.trans_date);
    $('#open_manage_booking_edit_booking_form').click();
})

$(document).on('click', '.add_booking_added_payment', function() {
    $('#open_manage_booking_add_added_booking_form').click();
})

$(document).on('click', '.edit_booking_added_payment', function() {
    var data = $(this).data();
    $('#manage_booking_edit_added_booking_form').find('[name="id"]').val(data.id);
    $('#manage_booking_edit_added_booking_form').find('[name="transaction_created_at"]').val(data.transaction_created_at);
    if (data.amount > 0) {
        $('#manage_booking_edit_added_booking_form').find('[name="amount"]').val(data.amount);
        $('#manage_booking_edit_added_booking_form').find('[name="amount"]').hide();
    } else {
        $('#manage_booking_edit_added_booking_form').find('[name="amount"]').val("");
        $('#manage_booking_edit_added_booking_form').find('[name="amount"]').show();
    }
    $('#open_manage_booking_edit_added_booking_form').click();
})

$(document).on('click', '.resync_booking', function() {
    var id = $(this).data('id');
    $.ajax({
        type: 'GET',
        url: base_url + '/cronjob/setDataCronJob',
        data: { 'bid': id, 'force_resync': 1 },
        beforeSend: function () {
            altair_helpers.content_preloader_show();
        },
        complete: function () {
            altair_helpers.content_preloader_hide();
        },
        success: function (response) {
            window.location.reload();
        }
    });
})

function show_message(title, message) {
    $("#alert-message-heading").html(title);
    $("#alert-message").html(message);
    $(".alert-message-button").click();
}