var dt = new Date();
var current_year = dt.getFullYear();
var current_month = dt.getMonth() + 1;
var current_date = dt.getDate();
// Function for turning off auto-complete attribute in all form fields
$(document).ready(function () {
    if ((corporate_loyalty == 1 || logged_in_from_frontend == 0) && show_customer_popup_after_search == 1 && last_segment === 'search-results' && show_loyalty_popup_in_booking === true && loyalty_tried != 'yes') {
        $('#OpenPopUpForLoyaltyConfirmation').click();
    }
    $('form,input,select,textarea').attr("autocomplete", "off");
});
$('#corporateCompanies').on('shown.bs.modal', function () {
    $(document).off('focusin.modal');
});
$(document).ready(function () {
    if (lang == 'arb') {
        var my_dir = 'rtl';
        var my_lang = 'ar';
    } else {
        var my_dir = 'ltr';
        var my_lang = 'en';
    }
    $(".multiselect").select2({dir: my_dir, language: my_lang});

});

$(document).ready(function () {
    if (lang == 'arb') {
        var my_dir = 'rtl';
        var my_lang = 'ar';
    } else {
        var my_dir = 'ltr';
        var my_lang = 'en';
    }
    // need to include language files with JS i18n to make language work here, see footer scripts.
    $(".searchable").select2({
        dir: my_dir,
        language: my_lang
    });
});

// From and to Datepickers
$(document).ready(function () {
    $('#datepicker_from').datepicker({
        dateFormat: "dd-mm-yy",
        minDate: 0,
        changeMonth: false,
        changeYear: false,
        onSelect: function (date) {
            var date2 = $('#datepicker_from').datepicker('getDate');
            date2.setDate(date2.getDate());
            //$('#dt2').datepicker('setDate', date2);
            //sets minDate to dt1 date + 1
            if ($('#datepicker_to').hasClass('datepicker_to_for_monthly_tab'))
            {
                var startDate = $('#datepicker_from').datepicker('getDate');
                startDate.setDate(date2.getDate() + 30);
                $('#datepicker_to').datepicker('option', 'minDate', startDate);
            } else if ($('#datepicker_to').hasClass('datepicker_to_for_weekly_tab'))
            {
                var startDate = $('#datepicker_from').datepicker('getDate');
                startDate.setDate(date2.getDate() + 7);
                $('#datepicker_to').datepicker('option', 'minDate', startDate);
            } else {
                $('#datepicker_to').datepicker('option', 'minDate', date2);
            }
        }
    });
    $('#datepicker_to').datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: false,
        changeYear: false,
        beforeShow: function () {
            var date2 = $('#datepicker_from').datepicker('getDate');
            date2.setDate(date2.getDate());
            //$('#dt2').datepicker('setDate', date2);
            //sets minDate to dt1 date + 1
            if ($('#datepicker_to').hasClass('datepicker_to_for_monthly_tab'))
            {
                var startDate = $('#datepicker_from').datepicker('getDate');
                startDate.setDate(date2.getDate() + 30);
                $('#datepicker_to').datepicker('option', 'minDate', startDate);
            } else if ($('#datepicker_to').hasClass('datepicker_to_for_weekly_tab'))
            {
                var startDate = $('#datepicker_from').datepicker('getDate');
                startDate.setDate(date2.getDate() + 7);
                $('#datepicker_to').datepicker('option', 'minDate', startDate);
            } else {
                $('#datepicker_to').datepicker('option', 'minDate', date2);
            }
        },
        onClose: function () {
            var dt1 = $('#datepicker_from').datepicker('getDate');
            var dt2 = $('#datepicker_to').datepicker('getDate');
            /*if (dt2 <= dt1) {
             var minDate = $('#dt2').datepicker('option', 'minDate');
             $('#dt2').datepicker('setDate', minDate);
             }*/
        }
    });

    $("#datepicker_mobile_id").datepicker({
        minDate: 0,
        numberOfMonths: [1, 1],
        beforeShowDay: function (date) {
            var date1 = $.datepicker.parseDate("dd-mm-yy", $('#datepicker_from').val());
            var date2 = $.datepicker.parseDate("dd-mm-yy", $('#datepicker_to').val());
            return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
        },
        onSelect: function (dateText, inst) {
            var date1 = $.datepicker.parseDate("dd-mm-yy", $('#datepicker_from').val());
            var date2 = $.datepicker.parseDate("dd-mm-yy", $('#datepicker_to').val());
            var selectedDate = $.datepicker.parseDate("dd-mm-yy", dateText);

            if (lang === 'eng') {
                var get_date_format = $.datepicker.formatDate("dd M yy", selectedDate, {
                    monthNamesShort: $.datepicker.regional["en"].monthNamesShort
                });
            } else {
                $.datepicker.regional['ar'] = {monthNames: ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"]}
                var get_date_format = $.datepicker.formatDate("dd MM yy", selectedDate, {
                    monthNames: $.datepicker.regional["ar"].monthNames
                });
            }

            if (!date1 || date2) {
                $('#datepicker_from').val(dateText);
                $('#datepicker_to').val("");
                $("#pickup_date_gry_bx").text(get_date_format);
                $("#dropoff_date_gry_bx").text("");
                $(this).datepicker();
            } else if (selectedDate < date1) {
                $('#datepicker_from').val(dateText);
                $('#datepicker_to').val("");
                $("#pickup_date_gry_bx").text(get_date_format);
                $("#dropoff_date_gry_bx").text("");
                $(this).datepicker();
            } else {
                $('#datepicker_to').val(dateText);
                $("#dropoff_date_gry_bx").text(get_date_format);
                $(this).datepicker();
            }
        }
    });


    $("#mob-menu").on('click', function () {
        $('body').addClass('indexMenu')
    });

    $("#closeTopMenu").on('click', function () {
        $('body').removeClass('indexMenu')
    });

    $(".select_pickup_time").on('click', function () {
        $('.select_pickup_time').removeClass('selected');
        $(this).addClass('selected');
        var get_pickup_time = $(this).attr('data-pickUpTime');
        $("#pickup_time_gry_bx").text(get_pickup_time);
        $("#pickUpTime_hdn").val(get_pickup_time);
    });

    $(".select_dropoff_time").on('click', function () {
        $('.select_dropoff_time').removeClass('selected');
        $(this).addClass('selected');
        var get_dropoff_time = $(this).attr('data-dropOffTime');
        $("#dropoff_time_gry_bx").text(get_dropoff_time);
        $("#dropOffTime_hdn").val(get_dropoff_time);
    });

    $(".datepicker_past_date").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(current_year - 10, current_month, current_date),
        minDate: new Date(1950, 6, 12),
        yearRange: "c-70:+0"
    });
    $(".dob_dp").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(current_year - 10, current_month, current_date),
        minDate: new Date(1950, 6, 12),
        yearRange: "c-70:+0"
    });
    $(".datepicker_near_past_date").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(current_year, current_month, current_date - 1),
        minDate: new Date(1970, 6, 12),
        yearRange: "c-70:+0"
    });
    $(".datepicker_future_date").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        minDate: new Date()
    });
    if ($('#datepicker_from').val() != '') {
        $('#datepicker_to').datepicker('option', 'minDate', $('#datepicker_from').val());
    }

    if (lang === 'arb') {
        $(function () {
            $('#datepicker_from').datepicker("option", $.datepicker.regional['ar']);
            $('#datepicker_to').datepicker("option", $.datepicker.regional['ar']);
            $(".datepicker_past_date").datepicker("option", $.datepicker.regional['ar']);
            $(".datepicker_near_past_date").datepicker("option", $.datepicker.regional['ar']);
            $(".datepicker_future_date").datepicker("option", $.datepicker.regional['ar']);
            $("#datepicker_mobile_id").datepicker("option", $.datepicker.regional['ar']);
            $(".dob_dp").datepicker("option", $.datepicker.regional['ar']);

            $('#datepicker_from').datepicker('option', 'dateFormat', 'dd-mm-yy');
            $('#datepicker_to').datepicker('option', 'dateFormat', 'dd-mm-yy');
            $('.datepicker_past_date').datepicker('option', 'dateFormat', 'dd-mm-yy');
            $('.datepicker_near_past_date').datepicker('option', 'dateFormat', 'dd-mm-yy');
            $('.datepicker_future_date').datepicker('option', 'dateFormat', 'dd-mm-yy');
            $('#datepicker_mobile_id').datepicker('option', 'dateFormat', 'dd-mm-yy');

            $("#datepicker_from, #datepicker_to, #datepicker_mobile_id, .dob_dp").datepicker("option", "monthNames", ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"]);

            //$('#datepicker_from').datepicker("option", "dayNamesMin", ["1", "2", "3", "4", "5", "6", "7"]);

        });
    } else {
        $(function () {
            $('#datepicker_from').datepicker("option", $.datepicker.regional['en-GB']);
            $('#datepicker_to').datepicker("option", $.datepicker.regional['en-GB']);
            $(".datepicker_past_date").datepicker("option", $.datepicker.regional['en-GB']);
            $(".datepicker_near_past_date").datepicker("option", $.datepicker.regional['en-GB']);
            $(".datepicker_future_date").datepicker("option", $.datepicker.regional['en-GB']);
            $("#datepicker_mobile_id").datepicker("option", $.datepicker.regional['en-GB']);
            $(".dob_dp").datepicker("option", $.datepicker.regional['en-GB']);

            $('#datepicker_from').datepicker('option', 'dateFormat', 'dd-mm-yy');
            $('#datepicker_to').datepicker('option', 'dateFormat', 'dd-mm-yy');
            $('.datepicker_past_date').datepicker('option', 'dateFormat', 'dd-mm-yy');
            $('.datepicker_near_past_date').datepicker('option', 'dateFormat', 'dd-mm-yy');
            $('.datepicker_future_date').datepicker('option', 'dateFormat', 'dd-mm-yy');
            $('#datepicker_mobile_id').datepicker('option', 'dateFormat', 'dd-mm-yy');
        });
// en-GB
    }
});

// Function for keith-wood date-picker
$(document).ready(function () {

    //$('#datepicker_from').datepicker('option', 'minDate', new Date());
    //$('#datepicker_to').datepicker('option', 'minDate', new Date());
    $(".custom_calendar").calendarsPicker();

    $('#selectCalendar').change(function () {
        $('.custom_calendar').val('');
        calendar = $.calendars.instance($(this).val());
        var convert = function (value) {
            return (!value || typeof value != 'object' ? value :
                calendar.fromJD(value.toJD()));
        };
        $('.custom_calendar').each(function () {
            var current = $(this).calendarsPicker('option');
            $(this).calendarsPicker('option', {
                calendar: calendar,
                onSelect: null,
                onChangeMonthYear: null,
                dateFormat: 'dd-mm-yyyy', // only for hijri calendar
                changeYear: true,
                defaultDate: convert(current.defaultDate),
                minDate: convert(current.minDate),
                maxDate: convert(current.maxDate)
            }).calendarsPicker('option',
                {
                    onSelect: current.onSelect,
                    onChangeMonthYear: current.onChangeMonthYear
                });
        });
    });

});

// get page name from url

var path = window.location.pathname;
var page = path.split("/").pop();
var page = path.split("/").pop();
//==========

// International phone numbers plugin
$(".phone, .phone-popup").intlTelInput({
    //onlyCountries: ["sa", "eg"], initialCountry: "sa",
    initialCountry: "sa",
    excludeCountries: ["il"],
    nationalMode: false,
    separateDialCode: true,
    autoPlaceholder: "off",
    formatOnDisplay: false,
    utilsScript: base_url + "/public/frontend/intTelInput/js/utils.js" // just for formatting/placeholders etc
});

$(document).ready(function () {

    $(document).on('focusout', '.phone', function (event) {

        var countryData = $(this).intlTelInput("getSelectedCountryData");
        if (countryData.iso2 == 'sa') {
            var inputLengthMobile = $(this).val().length;
            if (inputLengthMobile != 9) {
                $(this).css({"border": "1px solid red"});
                show_bs_tooltip($(this), 'This filed must contain only 9 characters' );
                //$('.id_no').append('hello');
                $('.submit_btn').attr('disabled', true);
            } else {
                hide_bs_tooltip($(this));
                $(this).css({
                    "border-color": "#afb0aa #e9eae4 #ebeae6",
                    "border-style": "solid",
                    "border-width": "1px"
                });
                $('.submit_btn').attr('disabled', false);
            }
        } else {
            hide_bs_tooltip($(this));
            $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
            $('.submit_btn').attr('disabled', false);
        }

        /* var countryData = $(".phone").intlTelInput("getSelectedCountryData");
         if (countryData.iso2 == 'sa')
         {
         var inputLengthMobile = $(this).val().length;
         if (inputLengthMobile != 9)
         {
         $(".phone").attr("data-original-title", 'This filed must contain only 9 characters');
         $(".phone").tooltip('show');
         $(".phone").css({"border": "1px solid red"});
         $('.submitBtn').attr('disabled', true);
         $('.bookNowBtn').attr('disabled', true);
         }else{
         $(".phone").css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width" : "1px"});
         $(".phone").tooltip('hide');
         $('.submitBtn').attr('disabled', false);
         $('.bookNowBtn').attr('disabled', false);
         }
         }
         var isValid = $(".phone").intlTelInput("isValidNumber");
         var error = $(".phone").intlTelInput("getValidationError");
         if (error == intlTelInputUtils.validationError.TOO_SHORT) {
         // the number is too short
         } else if (error == intlTelInputUtils.validationError.TOO_LONG) {
         // the number is too short
         } else if (error == intlTelInputUtils.validationError.TOO_LONG) {
         // the number is too short
         }*/

        var intlNumber = $(".phone").intlTelInput("getNumber");
        $('.intTelNo').val(intlNumber);
        //alert(intlNumber);
        //alert(countryData.name);
        //alert(countryData.dialCode);
        // sa
    });

});
$(".phone").on("countrychange", function (e, countryData) {
    var countryData = $(this).intlTelInput("getSelectedCountryData");
    if (countryData.iso2 == 'sa') {
        var inputLengthMobile = $(this).val().length;
        if (inputLengthMobile != 9) {
            $(this).css({"border": "1px solid red"});
            show_bs_tooltip($(this), 'This filed must contain only 9 characters');
            //$('.id_no').append('hello');
            $('.submit_btn').attr('disabled', true);
        } else {
            hide_bs_tooltip($(this));
            $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
            $('.submit_btn').attr('disabled', false);
        }
    } else {
        hide_bs_tooltip($(this));
        $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
        $('.submit_btn').attr('disabled', false);
    }

    /* if (countryData.iso2 == 'sa')
     {
     var inputLengthMobile = $(this).val().length;
     if (inputLengthMobile != 10)
     {
     $(".phone").attr("data-original-title", 'This filed must contain only 10 characters');
     $(".phone").tooltip('show');
     $(".phone").css({"border": "1px solid red"});
     $('.submitBtn').attr('disabled', true);
     $('.bookNowBtn').attr('disabled', true);
     }else{
     $(".phone").css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width" : "1px"});
     $(".phone").tooltip('hide');
     $('.submitBtn').attr('disabled', false);
     $('.bookNowBtn').attr('disabled', false);
     }
     }else{
     $(".phone").css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width" : "1px"});
     $(".phone").tooltip('hide');
     $('.submitBtn').attr('disabled', false);
     $('.bookNowBtn').attr('disabled', false);
     }
     var isValid = $(".phone").intlTelInput("isValidNumber");
     var error = $(".phone").intlTelInput("getValidationError");
     if (error == intlTelInputUtils.validationError.TOO_SHORT) {
     // the number is too short
     } else if (error == intlTelInputUtils.validationError.TOO_LONG) {
     // the number is too short
     } else if (error == intlTelInputUtils.validationError.TOO_LONG) {
     // the number is too short
     }*/

    var intlNumber = $(".phone").intlTelInput("getNumber");
    $('.intTelNo').val(intlNumber);
    //alert(intlNumber);
    //alert(countryData.name);
    //alert(countryData.dialCode);
    // sa
});

// Changing language
$(document).on('click', '.changeLanguage', function (e) {
    $('.loaderSpiner').show();
    var arr = $(this).attr('id').split('||');
    var lang = arr[0];
    var urlSegment = arr[1];

    $.ajax({
        type: 'GET',
        url: lang_base_url + '/set_lang_session?lang=' + lang,
        data: {lang: lang},
        async: false,
        success: function (response) {
            $('.loaderSpiner').hide();
            if (lang == 'eng') {
                window.location.href = base_url + '/en/' + urlSegment;
            } else {
                window.location.href = base_url + '/' + urlSegment;
            }
        }
    });
    /*$.get(lang_base_url + '/set_lang_session?lang='+lang, function( data ) {
     if (lang == 'eng') {
     window.location.href = base_url + '/en/' + urlSegment;
     } else {
     window.location.href = base_url + '/' + urlSegment;
     }
     });*/

    /*$.ajax({
     type: 'POST',
     url: lang_base_url + '/set_lang_session',
     dataType: "json",
     data: {lang: lang},
     success: function (response) {
     if (response.lang == 'eng') {
     window.location.href = base_url + '/en/' + urlSegment;
     } else {
     window.location.href = base_url + '/' + urlSegment;
     }
     }
     });*/

    /*if (lang == 'ar') {
     window.location.href = base_url + '/ar/' + urlSegment;
     } else {
     window.location.href = base_url + '/' + urlSegment;
     }*/
});


// Changing language
$(document).on('click', '#webFullVersion', function (e) {
    $('.loaderSpiner').show();
    var fullVersion = $(this).attr('data-fullVersion');

    $.ajax({
        type: 'GET',
        url: lang_base_url + '/set_mobile_full_version',
        data: {full_version: fullVersion},
        async: false,
        success: function (response) {
            location.reload();
        }
    });
});

$(document).on('click', '#serchhomeBtn', function (e) {
    if (is_mobile) {
        if (
            $('#pickUpTime_hdn').val() === '' ||
            ($('.isDeliveryMode').val() !== '2' && $('.isDeliveryMode').val() !== '4' && $('#dropOffTime_hdn').val() === '') ||
            ($('.isDeliveryMode').val() === '2' && ($('#book_for_hours').val() === '' || $('#book_for_hours').val() === "0")) ||
            ($('.isDeliveryMode').val() === '4' && ($('#subscribe_for_months').val() === '' || $('#subscribe_for_months').val() === "0"))
        ) {
            if ($('.isDeliveryMode').val() === '2') {
                if ($('#pickUpTime_hdn').val() === '') {
                    $('#time-select-alert').find('p').text(lang === 'eng' ? 'Please select Pick up time' : 'نرجو تحديد وقت التسليم');
                } else if ($('#book_for_hours').val() === '' || $('#book_for_hours').val() === "0") {
                    $('#time-select-alert').find('p').text(lang === 'eng' ? 'Please select booking hours' : 'الرجاء تحديد ساعات الحجز');
                }
            } else if ($('.isDeliveryMode').val() === '4') {
                if ($('#pickUpTime_hdn').val() === '') {
                    $('#time-select-alert').find('p').text(lang === 'eng' ? 'Please select Pick up time' : 'نرجو تحديد وقت التسليم');
                } else if ($('#subscribe_for_months').val() === '' || $('#subscribe_for_months').val() === "0") {
                    $('#time-select-alert').find('p').text(lang === 'eng' ? 'Please select subscription months' : 'الرجاء تحديد ساعات الحجز');
                }
            } else {
                $('#time-select-alert').find('p').text(lang === 'eng' ? 'Please select Pick up and Drop off time' : 'اختار وقت موقع الاستلام و التسليم');
            }
            $('#time-select-alert').modal('show');
            return false;
        } else {
            $('.serFormArea').submit();
        }
    } else {

        if (validateGenericForm() === true) {

            if ($('.allIsOkForPickup').val() == 1 && $('.allIsOkForDropoff').val() == 1) {
                $('.serFormArea').submit();
            } else {
                if (lang == 'eng') {
                    var error = 'Error';
                    if ($('.allIsOkForPickup').val() == 0 && $('.allIsOkForDropoff').val() == 0) {
                        var msg = 'Sorry but our delivery services are not available for the selected pickup and return location.';
                    } else if ($('.allIsOkForPickup').val() == 0 && $('.allIsOkForDropoff').val() == 1) {
                        var msg = 'Sorry but our delivery services are not available for the selected pickup location.';
                    } else if ($('.allIsOkForPickup').val() == 1 && $('.allIsOkForDropoff').val() == 0) {
                        var msg = 'Sorry but our delivery services are not available for the selected return location.';
                    }
                } else {
                    var error = 'خطأ';
                    if ($('.allIsOkForPickup').val() == 0 && $('.allIsOkForDropoff').val() == 0) {
                        var msg = 'عفوا، خدمة التوصيل غير متاحة للمكان المختار';
                    } else if ($('.allIsOkForPickup').val() == 0 && $('.allIsOkForDropoff').val() == 1) {
                        var msg = 'عفوا، خدمة التوصيل غير متاحة للمكان المختار';
                    } else if ($('.allIsOkForPickup').val() == 1 && $('.allIsOkForDropoff').val() == 0) {
                        var msg = 'عفوا، خدمة التوصيل غير متاحة للمكان المختار';
                    }
                }
                $('.responseTitle').html(error);
                $('.responseMsg').html(msg);
                $('#openMsgPopupNoRedirect').click();
            }
        }
    }
});

function validateGenericForm() {
    var returnVal = true;
    var is_company_selected = $('#corporate_company_id').val();
    var is_super_corporate = $('#is_super_corporate').val();
    $("input.required-for-search, select.required-for-search").each(function () {
        if ($(this).val() == '') {
            // alert($(this).attr('name'));
            show_bs_tooltip($(this), required_message);
            returnVal = false;
        } else if (is_super_corporate === "1" && (is_company_selected === "" || is_company_selected < 0)) {
            returnVal = false;
            $('#superCorpModal').modal('show');
        }
    });
    return returnVal;
}

// For New User Signup
$(document).on('submit', '.getYourCarForm', function (e) {

    if (typeof $('.accept_terms').val() !== typeof undefined) {

        if ($('.accept_terms').is(':checked')) {
            return true;
        } else {
            if (lang == "eng") {
                var Msg = 'Please accept the terms and conditions to proceed.';
                var msgTitle = 'Message';
            } else {
                var Msg = 'الرجاء الموافقة على الشروط والأحكام';
                var msgTitle = 'الرسالة';
            }
            $('.responseTitle').html(msgTitle);
            $('.responseMsg').html(Msg);
            $('#openMsgPopupNoRedirect').click();
            return false;
        }
    }

});

$(document).on('click', '.getCarGuest', function (e) {
    if (typeof $('.accept_terms').val() !== typeof undefined) {

        if ($('.accept_terms').is(':checked')) {
            $('#model-login').modal('show');
        } else {
            if (lang == "eng") {
                var Msg = 'Please accept the terms and conditions to proceed.';
                var msgTitle = 'Message';
            } else {
                var Msg = 'الرجاء الموافقة على الشروط والأحكام';
                var msgTitle = 'الرسالة';
            }
            $('.responseTitle').html(msgTitle);
            $('.responseMsg').html(Msg);
            $('#openMsgPopupNoRedirect').click();
            return false;
        }
    }
});

$(document).on('submit', '.signup_form_new_user', function (e) {
    if (validateForm() == true) {
        var $form = $(this);

        var $formData = new FormData(this);
        var method = $form.attr('method');
        var url = $form.attr('action');
        $('.loaderSpiner').show();

        var email = $('#email_required_for_validation').val();
        var id_no = $('#id_no_required_for_validation').val();
        var id_type = $('.id_type').val();

        // ajax call here
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/validateEmailAndIdNo',
            dataType: "json",
            data: {email: email, id_no: id_no},
            success: function (response) {
                if (response.status == false) {
                    if (lang == "eng") {
                        var error = 'Error';
                    } else {
                        var error = 'خطأ';
                    }
                    $('.responseTitle').html(error);
                    $('.responseMsg').html(response.message);
                    $('.loaderSpiner').hide();
                    $('#openMsgPopupNoRedirect').click();
                } else {
                    $.ajax({
                        type: method,
                        url: url,
                        dataType: "json",
                        //data: $form.serialize(),
                        data: $formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.something_went_wrong == true) {
                                //window.location.href = lang_base_url + '/something-went-wrong';
                                if (lang == "eng") {
                                    var error = 'Error';
                                } else {
                                    var error = 'خطأ';
                                }

                                $('.responseTitle').html(error);
                                $('.responseMsg').html(response.error_message);
                                $('.loaderSpiner').hide();
                                $('#openMsgPopupNoRedirect').click();
                            } else {
                                $('.loaderSpiner').hide();
                                $('.responseTitle').html(response.title);
                                $('.responseMsg').html(response.message);
                                if (response.redirectURL != '') {
                                    redUrl = lang_base_url + '/' + response.redirectURL;
                                    $("#OKBtn").attr("href", redUrl);
                                    $('#openMsgPopupRedirect').click();
                                } else {
                                    $('#openMsgPopupNoRedirect').click();
                                }
                            }
                        }
                    });
                }

            }
        });
    }

});

function validateForm() {

    var isError = false;
    $(".signup_form_new_user input.required, .signup_form_new_user input.required-optional, .signup_form_new_user select.required").each(function () {
        if ($(this).val() == '') {
            show_bs_tooltip($(this), required_message);
            //returnVal = false;
            isError = true;
        } else {
            hide_bs_tooltip($(this));
            //returnVal = true;
        }
    });

    if (typeof $('.accept_terms').val() !== typeof undefined) {

        if ($('.accept_terms').is(':checked')) {
            //returnVal = true;
        } else {
            if (lang == "eng") {
                var Msg = 'Please accept the terms and conditions to proceed.';
                var msgTitle = 'Message';
            } else {
                var Msg = 'الرجاء الموافقة على الشروط والأحكام';
                var msgTitle = 'الرسالة';
            }
            $('.responseTitle').html(msgTitle);
            $('.responseMsg').html(Msg);
            $('#openMsgPopupNoRedirect').click();
            //returnVal = false;
            isError = true;
        }
    }

    if (typeof $('.extra_info').val() !== typeof undefined) {
        if ($('.extra_info').is(':checked')) {
            $("input.required-optional, select.required-optional").each(function () {

                if ($(this).val() == '') {
                    show_bs_tooltip($(this), required_message);
                    //returnVal = false;
                    isError = true;
                } else {
                    hide_bs_tooltip($(this));
                    //returnVal = true;
                }
            });
        }
    }

    if (typeof $('.id_no').val() !== typeof undefined) {

        if ($('.id_no').val() == '') {
            show_bs_tooltip($('.id_no'), required_message);
            //returnVal = false;
            isError = true;
        } else {
            hide_bs_tooltip($('.id_no'));
            //returnVal = true;
        }
    }

    /*if (typeof $('.id_no').val() !== typeof undefined) {
     var id_type = $('.id_type').val();
     var id_no_length = $(".id_no").val().length;
     if (id_no_length == 0) {
     $(".id_no").attr("data-original-title", required_message);
     $(".id_no").tooltip('show');
     isError = true;
     } else if ((id_type == '243' || id_type == '68') && id_no_length != 10) {
     $(".id_no").attr("data-original-title", 'This field must contain only 10 characters');
     $(".id_no").tooltip('show');
     isError = true;
     } else {
     $(".id_no").tooltip('hide');
     }
     }*/

    if (isError)
        return false;
    else
        return true;
}

/*$(document).ready(function () {
 $('.responseTitle').html('test');
 $('#responseMsg').html('test');
 $('#openMsgPopup').click();
 });*/

$(".contact_us_frm").submit(function (e) {
    e.preventDefault();
    $('.loaderSpiner').show();
    var url = $(this).attr('action');
    $.ajax({
        type: "POST",
        dataType: "json",
        data: new FormData(this),
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (lang == "eng") {
                var success = 'Success';
                var error = 'Error';
            } else {
                var success = 'بنجاح';
                var error = 'خطأ';
            }

            $('.loaderSpiner').hide();
            if (response.captcha == true) {
                if (response.success) {
                    $('.responseTitle').html(success);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                    $('.contact_us_frm')[0].reset();
                } else {
                    $('.responseTitle').html(error);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }
            } else {
                $('.responseTitle').html(error);
                $('.responseMsg').html(response.message);
                $('#openMsgPopupNoRedirect').click();
            }

        }
    });
});

$(document).on('submit', '.change_points_form', function () {
    if (validationPoints_form()) {
        $('.loaderSpiner').show();
        $form = $(this);
        $.ajax({
            type: "POST",
            url: $form.attr('action'),
            data: new FormData(this),
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (lang === "eng") {
                    var success = 'Success';
                    var error = 'Error';
                } else {
                    var success = 'بنجاح';
                    var error = 'خطأ';
                }
                $('.loaderSpiner').hide();
                if (response.captcha === true) {
                    if (response.success === true) {
                        $('.responseTitle').html(success);
                        $('.responseMsg').html(response.message);
                        $('#openMsgPopupNoRedirect').click();
                        $('.change_points_form')[0].reset();
                    } else {
                        $('.responseTitle').html(error);
                        $('.responseMsg').html(response.message);
                        $('#openMsgPopupNoRedirect').click();
                    }
                } else {
                    $('.responseTitle').html(error);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }
            }
        });
    } else {
        return false;
    }
});

function validationPoints_form() {
    $('form input').attr("data-original-title", "");

    var name = $('#name').val();
    var email = $('#email').val();
    var id_number = $('#id_number').val();
    var mobile = $('#mobile').val();

    if (lang === 'eng') {
        var required_msg = "Please fill out this field.";
        var email_msg = "Please enter valid email address";
        var id_number_msg = "Please enter valid Id number";
        var mobile_msg = "Please enter 9 Digit valid mobile number without plus sign";
        var Msg = 'Please accept the terms and conditions to proceed.';
        var msgTitlte = 'Message';
    } else {
        var required_msg = "الرجاء تعبئة الخانة";
        var email_msg = "يرجى إدخال عنوان بريد إلكتروني صالح";
        var id_number_msg = "يرجى إدخال رقم الهوية صالح";
        var mobile_msg = "يرجى إدخال رقم الجوال المكون من 9 رقم دون علامة زائدة ";
        var Msg = 'الرجاء الموافقة على الشروط والأحكام';
        var msgTitlte = 'الرسالة';
    }
    if (email !== '' && !validateEmail(email)) {
        show_bs_tooltip($('#email'), email_msg);
        $('#email').focus();

    } else if (mobile !== '' && !isMobileValid(mobile)) {

        show_bs_tooltip($('#mobile'), mobile_msg);
        $('#mobile').focus();

    } else if (id_number !== '' && !isIDValid(id_number)) {

        show_bs_tooltip($('#id_number'), id_number_msg );
        $('#id_number').focus();

    } else if (!name) {

        show_bs_tooltip($('#name'), required_msg);
        $('#name').focus();

    } else if (!email) {

        show_bs_tooltip($('#email'), required_msg);
        $('#email').focus();

    } else if (!id_number) {

        show_bs_tooltip($('#id_number'), required_msg);
        $('#id_number').focus();

    } else if (!mobile) {

        show_bs_tooltip($('#mobile'),required_msg);
        $('#mobile').focus();

    } else if (!$('.accept_terms').is(':checked')) {

        $('.responseTitle').html(msgTitlte);
        $('.responseMsg').html(Msg);
        $('#openMsgPopupNoRedirect').click();

    } else {
        return true;
    }
}

function isMobileValid(userInput) {
    var s = userInput;
    if (/^[0-9]{1,12}$/.test(s) && s.length === 9)
        return true;
    else
        return false;
}

function isIDValid(userInput) {
    var s = userInput;
    if (/^[0-9]{1,12}$/.test(s) && s.length === 10)
        return true;
    else
        return false;
}

$(".careerForm").submit(function (e) {
    e.preventDefault();
    $('.loaderSpiner').show();
    var url = $(this).attr('action');
    $.ajax({
        type: "POST",
        dataType: "json",
        data: new FormData(this),
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            $('.loaderSpiner').hide();
            if (lang == "eng") {
                var success = 'Success';
                var error = 'Error';
            } else {
                var success = 'بنجاح';
                var error = 'خطأ';
            }
            if (response.captcha == true) {
                if (response.success) {
                    $('.responseTitle').html(success);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                    $('.careerForm')[0].reset();
                } else {
                    $('.responseTitle').html(error);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }
            } else {
                $('.responseTitle').html(error);
                $('.responseMsg').html(response.message);
                $('#openMsgPopupNoRedirect').click();
            }
        }
    });
});
// === end kashif work

$(document).ready(function () {
    //For numeric
    $(document).on('keydown', '.number', function (event) {
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
});

// For Login
$(document).on('submit', '#login', function (e) {
    var submit_form = true;
    if ($('#loginUsername').val() == '') {
        show_bs_tooltip($('#loginUsername'), required_message);
        submit_form = false;
    } else {
        hide_bs_tooltip($('#loginUsername'));
    }

    if ($('#loginPassword').val() == '') {
        show_bs_tooltip($('#loginPassword'), required_message);
        submit_form = false;
    } else {
        hide_bs_tooltip($('#loginPassword'));
    }

    if (submit_form == true) {
        $('.loaderSpiner').show();
        var redirect_segment = $('#redirect_segment').val();
        var $form = $(this);
        var method = $form.attr('method');
        var url = $form.attr('action');
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            //data: $form.serialize(),
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.something_went_wrong === true) {
                    //window.location.href = lang_base_url + '/something-went-wrong';
                    if (lang === "eng") {
                        var error = 'Error';
                    } else {
                        var error = 'خطأ';
                    }
                    if (lang === "eng") {
                        var errorMessage = 'Sorry we can\'t log you into the system.';
                    } else {
                        var errorMessage = 'عذرا لا يمكن إنشاء حساب';
                    }
                    $('.responseTitle').html(error);
                    $('.responseMsg').html(errorMessage);
                    $('.loaderSpiner').hide();
                    $('#openMsgPopupNoRedirect').click();
                } else {
                    if (response.status === false) {
                        $('.responseTitle').html(response.title);
                        $('.responseMsg').html(response.message);
                        $('.loaderSpiner').hide();
                        $('#openMsgPopupNoRedirect').click();
                    } else {
                        if (response.is_humanLess === true) {
                            window.location.href = lang_base_url + '/' + redirect_segment + '/' + response.bookingIDGetCar;
                        }/* else if (response.is_super_corporate === true) {
                            $('#is_super_corporate').val('1');
                            $('#containsLoginDDB').removeClass('open');
                            $('.loaderSpiner').hide();
                            // $('#btnCorporateCompanies').click();
                            window.location.href = lang_base_url + '/home';
                        }*/ else {
                            window.location.href = lang_base_url + '/my-profile';
                        }
                        /*if (response.has_profile == true)
                        {
                            window.location.href = lang_base_url + '/my-profile';
                        }else{
                            window.location.href = lang_base_url;
                        }*/

                    }
                }
            }
        });
    }

});

// For Login At Payment Page
$(document).on('submit', '#loginOnPayment', function (e) {

    var submit_form = true;
    if ($(this).find('#loginUsernameOnPayment').val() == '') {
        show_bs_tooltip($(this).find('#loginUsernameOnPayment'), required_message);
        submit_form = false;
    } else {
        hide_bs_tooltip($(this).find('#loginUsernameOnPayment'));
    }

    if ($(this).find('#loginPasswordOnPayment').val() == '') {
        show_bs_tooltip($(this).find('#loginPasswordOnPayment'), required_message);
        submit_form = false;
    } else {
        hide_bs_tooltip($(this).find('#loginPasswordOnPayment'));
    }

    if (submit_form == true) {
        $('.loaderSpiner').show();
        var $form = $(this);
        var method = $form.attr('method');
        var url = $form.attr('action');
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            //data: $form.serialize(),
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.something_went_wrong == true) {
                    //window.location.href = lang_base_url + '/something-went-wrong';
                    if (lang == "eng") {
                        var error = 'Error';
                    } else {
                        var error = 'خطأ';
                    }

                    if (lang == "eng") {
                        var errorMessage = 'Sorry we can\'t log you into the system';
                    } else {
                        var errorMessage = 'عذرا لا يمكن إنشاء حساب';
                    }
                    $('.responseTitle').html(error);
                    $('.responseMsg').html(errorMessage);
                    $('.loaderSpiner').hide();
                    $('#openMsgPopupNoRedirect').click();
                } else {
                    if (response.status == false) {
                        $('.responseTitle').html(response.title);
                        $('.responseMsg').html(response.message);
                        $('.loaderSpiner').hide();
                        $('#openMsgPopupNoRedirect').click();
                    } else {
                        if (response.has_profile == true) {
                            window.location.href = lang_base_url + '/payment';
                        } else {
                            window.location.href = lang_base_url;
                        }
                    }
                }
            }
        });
    }

});

$(document).on('submit', '.custom_submit', function (e) {
    var $form = $(this);
    var method = $form.attr('method');
    var url = $form.attr('action');
    $('.loaderSpiner').show();
    $.ajax({
        type: method,
        url: url,
        dataType: "json",
        //data: $form.serialize(),
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (lang == "eng") {
                var success = 'Success';
                var error = 'Error';
            } else {
                var success = 'بنجاح';
                var error = 'خطأ';
            }
            $('.loaderSpiner').hide();
            if (response.status == false) {
                $('.responseTitle').html(error);
                $('.responseMsg').html(response.message);
                $('#openMsgPopupNoRedirect').click();
            } else {
                $('.responseTitle').html(success);
                $('.responseMsg').html(response.message);
                $('#openMsgPopupNoRedirect').click();
            }
        }
    });
});

$(document).on('submit', '.changePassword', function (e) {

    var password = $('.password').val();
    var confirm_password = $('.confirm_password').val();

    if ($('.password').val() == '') {
        show_bs_tooltip($('.password'), required_message);
    } else {
        hide_bs_tooltip($('.password'));
    }

    if ($('.confirm_password').val() == '') {
        show_bs_tooltip($('.confirm_password'), required_message);
    } else {
        hide_bs_tooltip($('.confirm_password'));
    }

    if ($('.password').val() != '' && $('.confirm_password').val() != '') {
        var $form = $(this);
        var method = $form.attr('method');
        var url = $form.attr('action');
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            //data: $form.serialize(),
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status == true) {
                    $('.responseTitle').html(response.title);
                    $('.responseMsg').html(response.message);
                    redUrl = lang_base_url + '/home';
                    $("#OKBtn").attr("href", redUrl);
                    $('#openMsgPopupRedirect').click();
                } else {
                    $('.responseTitle').html(response.title);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }
            }
        });
    }
});

function showIdVersionField(val) {
    $("input[name='license_no']").val('');
    if (val == '68' || val == '243') {
        $("input[name='license_no']").attr('readonly', true);
        /*$('.id_version').attr('disabled', false);
         $('.id_version').addClass('required');
         $('.id_version').removeClass('readonly');*/
        //$('.idVersionNo').show();
    } else {
        $("input[name='license_no']").attr('readonly', false);
        /*$('.id_version').attr('disabled', true);
         $('.id_version').removeClass('required');
         $('.id_version').addClass('readonly');*/
        //$('.idVersionNo').hide();
    }

    // For sponsor field
    if (val == '68') {
        $('#sponsor_field').attr('disabled', false);
        $('#sponsor_field').addClass('required-optional');
        $('#sponsor_field').removeClass('readonly');
        $('.sponsor').show();
    } else {
        $('#sponsor_field').attr('disabled', true);
        $('#sponsor_field').removeClass('required-optional');
        $('#sponsor_field').addClass('readonly');
        $('.sponsor').hide();
    }

    // For validations point no 78 from sheet
    /*if (val == '243') {
     $('.id_no').val('1');
     } else if (val == '68') {
     $('.id_no').val('2');
     } else {
     $('.id_no').val('');
     }*/

    if (val == '243' || val == '68') {
        $('.id_no').addClass('number');
        $('.validate_license_number').addClass('number');
    } else {
        $('.id_no').removeClass('number');
        $('.validate_license_number').removeClass('number');
    }

    if (val == '243') {
        $(".nationality").val('16');
        //$(".nationality_container").hide();
    } else {
        $(".nationality").val('');
        //$(".nationality_container").show();
    }

}

// For Profile Edit
$(document).on('submit', '.edit_profile_form', function (e) {
    returnVal = true;
    // var id_type = $('.id_type').val();
    // var id_no_length = $(".id_no").val().length;
    $("input.required, select.required").each(function () {
        if ($(this).parent("div").is(':visible') && $(this).val() == '') {
            show_bs_tooltip($(this), required_message);
            returnVal = false;
        }
    });

    /*if (id_no_length == 0) {
        $(".id_no").attr("data-original-title", required_message);
        $(".id_no").tooltip('show');
        returnVal = false;
    } else if ((id_type == '243' || id_type == '68') && id_no_length != 10) {
        $(".id_no").attr("data-original-title", 'This filed must contain only 10 characters');
        $(".id_no").tooltip('show');
        returnVal = false;
    } else {
        $(".id_no").tooltip('hide');
    }*/

    if (returnVal == true) {
        $('.loaderSpiner').show();
        var $form = $(this);
        var method = $form.attr('method');
        var url = $form.attr('action');
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            //data: $form.serialize(),
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {

                if (lang == "eng") {
                    var success = 'Success';
                    var error = 'Error';
                } else {
                    var success = 'بنجاح';
                    var error = 'خطأ';
                }

                $('.loaderSpiner').hide();
                if (response.status == true) {
                    $('.responseTitle').html(success);
                    $('.responseMsg').html(response.message);
                    if (response.redirectURL !== '') {
                        redUrl = lang_base_url + '/' + response.redirectURL;
                        $("#OKBtn").attr("href", redUrl);
                        $('#openMsgPopupRedirect').click();
                    } else {
                        $('#openMsgPopupNoRedirect').click();
                    }
                } else {
                    $('.responseTitle').html(error);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }

            }
        });
    }

});

// For Profile Edit
$(document).on('submit', '.edit_corporate_profile_form', function (e) {
    returnVal = true;
    $("input.required, select.required").each(function () {
        if ($(this).parent("div").is(':visible') && $(this).val() == '') {
            show_bs_tooltip($(this), required_message);
            returnVal = false;
        }
    });

    if (returnVal == true) {
        $('.loaderSpiner').show();
        var $form = $(this);
        var method = $form.attr('method');
        var url = $form.attr('action');
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            //data: $form.serialize(),
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {

                if (lang == "eng") {
                    var success = 'Success';
                    var error = 'Error';
                } else {
                    var success = 'بنجاح';
                    var error = 'خطأ';
                }

                $('.loaderSpiner').hide();
                if (response.status == true) {
                    $('.responseTitle').html(success);
                    $('.responseMsg').html(response.message);
                    if (response.redirectURL !== '') {
                        redUrl = lang_base_url + '/' + response.redirectURL;
                        $("#OKBtn").attr("href", redUrl);
                        $('#openMsgPopupRedirect').click();
                    } else {
                        $('#openMsgPopupNoRedirect').click();
                    }
                } else {
                    $('.responseTitle').html(error);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }

            }
        });
    }
});

// For Profile Edit
$(document).on('submit', '#mainSearchForm', function (e) {

    $("#mainSearchForm input.required").each(function () {
        if ($(this).val() == '') {
            show_bs_tooltip($(this), required_message);
            returnVal = false;
        } else {
            hide_bs_tooltip($(this));
            returnVal = true;
        }
    });

    if (returnVal == true) {
        var $form = $(this);
        var method = $form.attr('method');
        var url = $form.attr('action');
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            //data: $form.serialize(),
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (lang == "eng") {
                    var success = 'Success';
                    var error = 'Error';
                } else {
                    var success = 'بنجاح';
                    var error = 'خطأ';
                }
                if (response.status == true) {
                    $('.responseTitle').html(success);
                    $('.responseMsg').html(response.message);
                    if (response.redirectURL !== '') {
                        redUrl = lang_base_url + '/' + response.redirectURL;
                        $("#OKBtn").attr("href", redUrl);
                        $('#openMsgPopupRedirect').click();
                    } else {
                        $('#openMsgPopupNoRedirect').click();
                    }
                } else {
                    $('.responseTitle').html(error);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }

            }
        });
    }

});

function main_search_with_filter(category_id) {

    $('.moreRecordsDiv').show();
    $('.noRecordFoundDiv').hide();
    isAlreadyLoadMoreCalled = false;
    isNoMoreResults = false;
    offset = 10;

    $('.shotingLink').children('ul').children('li').removeClass('active');

    $('.shotingLink').children('ul').children('li').find('a[data-category-id="' + category_id + '"]').parent().addClass('active');

    $('.loaderSpiner').show();
    var url = lang_base_url + '/main_search_with_filter';
    $.ajax({
        type: 'POST',
        url: url,
        //data: $form.serialize(),
        //data: new FormData(this),
        data: {category_id: category_id, language: lang},
        //cache: false,
        //contentType: false,
        //processData: false,
        success: function (response) {
            var resultCount = $(response).filter('.singleRow').length;
            if (resultCount < 10) {
                $('.moreRecordsDiv').hide();
                isNoMoreResults = true;
            }

            $('.containsData').html('');
            $('.containsData').html(response);
            $('.loaderSpiner').hide();
        }
    });
}

/*function redirectToExtraServicesPage(car_model_id) {
 var url = lang_base_url + '/redirectToExtraServicesPage';
 $.ajax({
 type: 'POST',
 url: url,
 data: {car_model_id: car_model_id},
 success: function (response) {
 window.location.href = lang_base_url + '/extra-services';
 }
 });
 }*/

$(document).on('click', '.extraChargesCB', function (e) {
    // removing active class from all checkboxes
    $('.extraChargesCB').parents('.deFaultRow').removeClass('active');

    var elem_id = $(this).attr('id');
    if (elem_id === 'ldwIC') {
        $('#ldwICP').prop('checked', false);
    } else if (elem_id === 'ldwICP') {
        $('#ldwIC').prop('checked', false);
    }

    var total = parseFloat($('#rent_multip_day_hdn_help').val());
    var vat_percentage = parseFloat($('#vat_percentage').val());
    var extrasAmount = 0;
    $(".extraChargesCB:checked").each(function () {
        $(this).parents('.deFaultRow').addClass('active');
        total += parseFloat($(this).data("total_with_days"));
        extrasAmount += parseInt($(this).data("total_with_days"));
    });
    var vat = (vat_percentage / 100) * total; // calculating VAT on Total Amount
    $('.total_rent').val(parseFloat(total).toFixed(2));
    $('.totalPrice').html(parseFloat(total).toFixed(2));
    $('#extrasFieldHere').html(parseInt(extrasAmount).toFixed(2));

    $('.totalPriceWithVat').html(parseFloat(total + vat).toFixed(2));
    $('.totalPriceWithVatInput').html(parseFloat(total + vat).toFixed(2));
    $('#hasVat').html(parseFloat(vat).toFixed(2));
    $('#vat').val(parseFloat(vat).toFixed(2));
});

$(document).on('click', '.bookNowBtn', function () {

    var allOkToSubmit = true;
    if (lang == "eng") {
        var Msg = 'Please accept the terms and conditions to proceed.';
        var msgTitlte = 'Message';
    } else {
        var Msg = 'الرجاء الموافقة على الشروط والأحكام';
        var msgTitlte = 'الرسالة';

    }

    $(".bookNowForm input.required, .bookNowForm select.required").each(function () {
        if ($(this).val() == '') {
            show_bs_tooltip($(this), required_message);
            this.scrollIntoView(false);
            allOkToSubmit = false;
        }
    });

    if (!$('.accept_terms').is(':checked')) {
        $('.responseTitle').html(msgTitlte);
        $('.responseMsg').html(Msg);
        $('#openMsgPopupNoRedirect').click();
        this.scrollIntoView(false);
        allOkToSubmit = false;
    }

    if ($("input[name=payment_method]:checked").length > 0) {
        // Do your stuff here
    } else {
        allOkToSubmit = false;
    }

    if (allOkToSubmit === true) {
        $('.loaderSpiner').show();
        var customer_email_for_survey = $('.customer_email').val();
        var customer_id_no_for_survey = $('.customer_id_no').val();
        var url = lang_base_url + '/checkIfSurveyPendingToFill';
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'JSON',
            data: {email: customer_email_for_survey, id_no: customer_id_no_for_survey, last_segment: last_segment},
            success: function (responseCheckIfSurveyPendingToFill) {
                if (responseCheckIfSurveyPendingToFill.status === true) {
                    $('.loaderSpiner').hide();
                    $('#openSurveyPopup').click();
                } else {
                    var email = $('#email_required_for_validation').val();
                    var id_no = $('#id_no_required_for_validation').val();
                    var id_type = $('.id_type').val();
                    var old_email_address = $('#old_email_address').val();
                    $('.loaderSpiner').show();
                    $.ajax({
                        type: 'POST',
                        url: lang_base_url + '/checkIfUserBlacklistedOrSimahBlock',
                        dataType: "json",
                        data: {id_no: id_no, id_type: id_type},
                        success: function (responseCheckIfUserBlacklistedOrSimahBlock) {
                            if (responseCheckIfUserBlacklistedOrSimahBlock.status == false) {
                                if (lang == "eng") {
                                    var error = 'Error';
                                } else {
                                    var error = 'خطأ';
                                }
                                $('.responseTitle').html(error);
                                $('.responseMsg').html(responseCheckIfUserBlacklistedOrSimahBlock.message);
                                $('.loaderSpiner').hide();
                                $('#openMsgPopupNoRedirect').click();
                            } else {
                                $.ajax({
                                    type: 'POST',
                                    url: lang_base_url + '/validateEmailAndIdNo',
                                    dataType: "json",
                                    data: {email: email, id_no: id_no},
                                    success: function (responseValidateEmailAndIdNo) {
                                        if (responseValidateEmailAndIdNo.status == false) {
                                            if (lang == "eng") {
                                                var error = 'Error';
                                            } else {
                                                var error = 'خطأ';
                                            }
                                            $('.responseTitle').html(error);
                                            $('.responseMsg').html(responseValidateEmailAndIdNo.message);
                                            $('.loaderSpiner').hide();
                                            $('#openMsgPopupNoRedirect').click();
                                        } else {
                                            if (old_email_address == "" || (old_email_address != "" && email != old_email_address)) {
                                                $.ajax({
                                                    type: 'POST',
                                                    url: lang_base_url + '/validateLoginEmail',
                                                    dataType: "json",
                                                    data: {email: email, id_no: id_no},
                                                    success: function (responseValidateLoginEmail) {
                                                        if (responseValidateLoginEmail.status == false) {
                                                            $('.responseTitle').html('Warning');
                                                            $('.responseMsg').html(responseValidateLoginEmail.message);
                                                            $('.loaderSpiner').hide();
                                                            $('#openUserEmailMsgPopup').click();
                                                        } else {
                                                            $('.bookNowForm').removeAttr('onsubmit');
                                                            $('.bookNowForm').submit();
                                                        }
                                                    }
                                                });
                                            } else {
                                                $('.bookNowForm').removeAttr('onsubmit');
                                                $('.bookNowForm').submit();
                                            }

                                        }

                                    }
                                });
                            }
                        }
                    });
                }
            }
        });
    }
});

$(document).on('click', '#openUserEmailMsgPopupSubmitBtn', function (e) {
    $('.loaderSpiner').show();
    $('.bookNowForm').removeAttr('onsubmit');
    $('.bookNowForm').submit();
});

function validateBookingForm() {
    var returnVal = true;
    $("input.required, select.required").each(function () {
        if ($(this).val() == '') {
            show_bs_tooltip($(this), required_message);
            returnVal = false;
        }
    });

    if (lang == "eng") {
        var Msg = 'Please accept the terms and conditions to proceed.';
        var msgTitlte = 'Message';
    } else {
        var Msg = 'الرجاء الموافقة على الشروط والأحكام';
        var msgTitlte = 'الرسالة';

    }
    if (!$('.accept_terms').is(':checked')) {
        $('.responseTitle').html(msgTitlte);
        $('.responseMsg').html(Msg);
        $('#openMsgPopupNoRedirect').click();
        returnVal = false;
    }
    return returnVal;
}

// For New User Signup
$(document).on('submit', '.save_extra_infor_after_reservation', function (e) {
    //alert('here');
    if (validateExtraInfoFormAfterBooking() == true) {
        $('.loaderSpiner').show();
        var $form = $(this);
        var method = $form.attr('method');
        var url = $form.attr('action');
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            //data: $form.serialize(),
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $('.loaderSpiner').hide();
                $('.responseTitle').html(response.title);
                $('.responseMsg').html(response.message);
                if (response.redirectURL != '') {
                    redUrl = lang_base_url + '/' + response.redirectURL;
                    $("#OKBtn").attr("href", redUrl);
                    $('#openMsgPopupRedirect').click();
                } else {
                    $('#openMsgPopupNoRedirect').click();
                }

            }
        });
    }

});

function validateExtraInfoFormAfterBooking() {

    var isError = false;

    if ($('.avoid_waiting').is(':checked')) {
        $("input.required-first, select.required-first").each(function () {

            if ($(this).val() == '') {
                show_bs_tooltip($(this), required_message);
                //returnVal = false;
                isError = true;
            } else {
                hide_bs_tooltip($(this));
                //returnVal = true;
            }
        });
    }

    if ($('.want_to_register').is(':checked')) {
        $("input.required-second, select.required-second").each(function () {

            if ($(this).val() == '') {
                show_bs_tooltip($(this), required_message);
                //returnVal = false;
                isError = true;
            } else {
                hide_bs_tooltip($(this));
                //returnVal = true;
            }
        });
    }

    if (isError)
        return false;
    else
        return true;
}

var apply_cancel_charges = "";
var cancel_booking_id = 0;

function cancelBooking(bookingId) {
    cancel_booking_id = bookingId;
    // get time from server and pickup
    $.ajax({
        type: "GET",
        url: lang_base_url + '/getServerTime',
        dataType: "json",
        data: {bookingId: bookingId},
        cache: false,
        success: function (responseData) {
            apply_cancel_charges = responseData.apply_cancel_charges;
            if (lang == "eng") {
                var msgTitlte = 'Message';
            } else {
                var msgTitlte = 'الرسالة';
            }
            if (responseData.allowed == 1) {

                $('.cancelTitle').html(msgTitlte);
                $('.cancelMsg').html(responseData.message);
                $('#cancelBookingConfirmCharge').click();

            } else {
                if (lang == "eng") {
                    var sorry = 'Sorry';
                } else {
                    var sorry = 'معذرة';
                }
                $('.responseTitle').html(sorry);
                $('.responseMsg').html(responseData.message);
                $('#openMsgPopupNoRedirect').click();
            }
        }
    });
}


$(document).on('click', '.cancelBookingBtnConfirmCharge', function (e) {
    e.preventDefault();
    $('#cancelBookingPopupCharge').modal('hide');
    $('#cancelBookingReasonPopup').modal('show');
    return false;
});

$(document).on('click', '.cancelBookingReasonBtn', function (e) {
    e.preventDefault();
    if ($('#cancellation_reason').val() == "")
    {
        show_bs_tooltip($('#cancellation_reason'), required_message);
    } else {
        hide_bs_tooltip($('#cancellation_reason'));
        $('#cancelBookingReasonPopup').modal('hide');
        //following line is not working so we close the popup using the popup id
        //$('.close').click();
        $('.loaderSpiner').show();
        // here check verification sms code

        $.ajax({
            type: "GET",
            url: lang_base_url + "/cancelBookingVerification?cancelBookingId=" + cancel_booking_id,
            dataType: "json",
            cache: false,
            async: false,
            success: function (data) {
                $('.loaderSpiner').hide();
                if (data.status === true) {
                    $('.cancelSmsVerification').html(data.message);
                    $('#cancelBookingPopup').click();
                    window.stop();
                    return false;
                } else {
                    $('.smsVerification').html(data.message);
                    $('#openSMSVerifyPopup').click();
                    window.stop();
                    return false;
                }
            }
        });
        return false;
    }
});

$(".cancelBookingBtnConfirmCharge_bk").click(function (e) {


});

$(document).on('submit', '.cancelBookingverification', function () {
    $('.loaderSpiner').show();
    var url = lang_base_url + "/verifySmsCheck";

    var $form = $(this);

    var method = $form.attr('method');
    $.ajax({
        type: method,
        url: url,
        dataType: "json",
        data: $form.serialize(),
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.status == true) {
                cancelBookingFun(cancel_booking_id, apply_cancel_charges);
            } else {
                $('.loaderSpiner').hide();
                $('.cancelSmsVerification').html(response.message);
            }
        }
    });
});

$(document).on('submit', '.corporateCompaniesForm', function (e) {
    e.preventDefault();
    $('.loaderSpiner').show();
    var $form = $(this);
    var url = $form.attr('action');
    var method = $form.attr('method');
    var superCorporateId = $('#super_corporate_id').val();
    var corporateCompanyId = $('#corporate_company_id').val();
    if (corporateCompanyId === "") {
        $('.loaderSpiner').hide();
        $('#superCorpModal').modal('show');
    } else {
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            data: {super_corporate_id: superCorporateId, corporate_company_id: corporateCompanyId},
            cache: false,
            success: function (response) {
                if (response.status === true) {
                    window.location.href = lang_base_url + '/home';
                } else {
                    $('.loaderSpiner').hide();
                    alert('Error');
                }
            }
        });
    }
});

function cancelBookingFun(bookingId, apply_charges) {
    var url = lang_base_url + '/cancelBooking';
    var cancellation_reason = $('#cancellation_reason').val();
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {id: bookingId, apply_cancel_charges: apply_charges, cancellation_reason: cancellation_reason},
        cache: false,
        success: function (response) {
            $('.close').click();
            $('.responseTitle').html(response.title);
            $('.responseMsg').html(response.updateMsg);
            if (response.isUpdate) {

// hitting cronjob here to sync all cancelled bookings
                $.ajax({
                    type: "GET",
                    url: base_url + '/cronjob/setCancelledBookingCollectionCronJob',
                    cache: false,
                    success: function (response) {

                    }
                });

                if (lang == "eng") {
                    var sorry = 'Sorry';
                } else {
                    var sorry = 'معذرة';

                }
                if (lang == "eng") {
                    var cancelStatus = 'CANCELLED';
                } else {
                    var cancelStatus = 'ملغي';

                }
                $('.loaderSpiner').hide();
                $('#cancelBookingForm').modal('hide');
                $('#openMsgPopupNoRedirect').click();
                window.stop();
                $('#statusMsg_' + bookingId + '').html(cancelStatus);
                $('#myBookingRow_' + bookingId + '').addClass('cancelled');
                $('#bCancelBtn_' + bookingId + '').remove();
            }
        }
    });

}

// For New User Signup
$(document).on('submit', '#create_individual_login', function (e) {
    if (validateCreateUserForm() == true) {
        $('.loaderSpiner').show();
        var $form = $(this);
        var method = $form.attr('method');
        var url = $form.attr('action');
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            //data: $form.serialize(),
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.something_went_wrong == true) {
                    window.location.href = lang_base_url + '/something-went-wrong';
                } else {
                    $('.loaderSpiner').hide();
                    if (response.status == false) {
                        $('.responseTitle').html(response.title);
                        $('.responseMsg').html(response.message);
                        $('#openMsgPopupNoRedirect').click();
                    } else {
                        $('.smsVerification').html(response.message);
                        $('#openSMSVerifyPopup').click();
                        /*redUrl = lang_base_url + '/' + response.redirectURL;
                         window.location.href = redUrl;*/
                    }
                }

            }
        });
    }

});

function validateCreateUserForm() {

    var isError = false;
    $("#create_individual_login input.required, #create_individual_login select.required").each(function () {
        if ($(this).val() == '') {
            show_bs_tooltip($(this), required_message);
            isError = true;
        } else {
            hide_bs_tooltip($(this));
        }
    });

    if (isError)
        return false;
    else
        return true;
}

$(document).on('submit', '.verificationCheck', function () {
    var url = lang_base_url + "/verifySmsCheck";

    $('.loaderSpiner').show();
    var $form = $(this);

    var method = $form.attr('method');
    $.ajax({
        type: method,
        url: url,
        dataType: "json",
        data: $form.serialize(),
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            $('.loaderSpiner').hide();
            if (response.status == true) {
                redUrl = lang_base_url + '/' + response.redirectURL;
                window.location.href = redUrl;
            } else {
                $('.smsVerification').html(response.message);
            }

        }
    });

});

$(document).on('click', '.resendVerifyCode', function () {

    var booking_id = 0;
    if (cancel_booking_id != "") booking_id = cancel_booking_id;

    $('.loaderSpiner').show();
    $.ajax({
        type: "GET",
        url: lang_base_url + "/resendVerifyCode",
        dataType: "json",
        data: {cancelBookingId: booking_id},
        cache: false,
        //contentType: false,
        //processData: false,
        success: function (response) {
            $('.loaderSpiner').hide();
            if (response.status) {
                //$('.responseTitle').html(response.title);
                $('.responseMsg').html(response.message);
                $('.smsVerification').html(response.message);
                //$('#openMsgPopupNoRedirect').click();
            }

        }
    });

});

// For Applying coupon code
$(document).on('click', '#applyCouponCodeBtn', function (e) {
    if ($('#couponCodeField').val() == '') {
        show_bs_tooltip($('#couponCodeField'), required_message);
    } else {
        hide_bs_tooltip($('#couponCodeField'));
        $('.loaderSpiner').show();
        var coupon = $('#couponCodeField').val();
        var id_no = $('[name="id_no"]').val();
        $.ajax({
            type: "POST",
            url: lang_base_url + '/applyCoupon',
            dataType: "json",
            data: {coupon: coupon, id_no : id_no},
            success: function (response) {
                $('.loaderSpiner').hide();
                if (response.status == true) {
                    $('#promotion_id').val(response.promotion_id);
                    $('#showTotalAmount').html(response.total_amount);
                    $('#discount_amount_per_day').val(response.promo_discount_amount);
                    $('#rent_per_day_span').html(response.rent_per_day);
                    $('#rent_m_days_span').html(response.rent_m_days);
                    $('#total_rent_after_discount_on_promo').val(response.total_amount_after_discount);
                    $('.discountAutoAppliedMsg').hide();
                    $('#couponCodeField').hide();
                    $('#applyCouponCodeBtn').hide();
                    $('.discount_on_promo_auto').hide();
                    if (response.is_promo_applied_for_extras == 0) {
                        $('.discount_on_promo_code').show();
                    }
                    $('.codeApproved').show();
                    $('.discount').html(response.discount_multipl_days);
                    $('#total_per_1_day').html(response.total_per_1_day);
                    $('#total_amount').html(response.total_amount);
                    $('#showTotalAmount').html(response.total_amount_with_vat);
                    $('.TotalAmountWithVatForMobile').html(response.total_to_be_paid_used_for_mobile); // being used for mobile views only
                    $('.TotalAmountWithVatForMobileWithDays').html(response.total_to_be_paid_with_days_used_for_mobile); // being used for mobile views only
                    $('#promoCodeHere').html(coupon);
                    $('#show_vat_applied').html(response.vat_after_promo_apply); // to show how much vat is after coupon discount. We have also saved it in session.
                    $('.redeemApplySection').hide(); // When coupon applied successfully, don't show redeem section
                } else {
                    var title = (lang == "eng" ? 'Error' : 'خطأ'), message = '';

                    if (response.apply_status == false) {
                        message = (lang == "eng" ? 'The coupon you have entered is not applied. You already have greater discount.' : 'لا يمكن استخدام الرمز الترويجي المدخل لانه لديك خصم اكبر من خصم الرمز');
                    } else {
                        if (response.message) {
                            message = response.message;
                        } else {
                            message = (lang == "eng" ? 'The coupon you have entered in invalid.' : 'رقم القسيمة غير صحيح');
                        }
                    }

                    $('.responseTitle').html(title);
                    $('.responseMsg').html(message);
                    $('#openMsgPopupNoRedirect').click();
                }

            }
        });
    }

});

// applyCouponCodeBtn
// Manage booking at home
$(document).on('click', '.manageBookingFromHome', function (e) {
    if ($(this).siblings('.booking_reference_no_for_home').val() == '') {
        show_bs_tooltip($(this).siblings('.booking_reference_no_for_home'), required_message);
    } else {
        hide_bs_tooltip($(this).siblings('.booking_reference_no_for_home'));
        //$('.loaderSpiner').show();
        var booking_reference_no = $(this).siblings('.booking_reference_no_for_home').val();
        $.ajax({
            type: "POST",
            url: lang_base_url + '/checkIfBookingExistWithRefNo',
            dataType: "json",
            data: {booking_ref_no: booking_reference_no},
            success: function (response) {
                $('.loaderSpiner').hide();
                if (response.status == true) {
                    //window.location.href = lang_base_url + '/manage-booking/' + response.record_id;
                    $('#resCode').val(booking_reference_no);
                    $('#openModalWithForm').click();
                } else {
                    $('.responseTitle').html(response.title);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }

            }
        });
    }
});

// Manage booking at home
$(document).on('click', '#manageBookingSecondStep', function (e) {
    if ($('#email_field_for_manage').val() == '') {
        show_bs_tooltip($('#email_field_for_manage'), required_message);
    } else {
        hide_bs_tooltip($('#email_field_for_manage'));
        //$('.loaderSpiner').show();
        var email_field_for_manage = $('#email_field_for_manage').val();
        var resCode = $('#resCode').val();
        $.ajax({
            type: "POST",
            url: lang_base_url + '/checkIfBookingExistWithRefNoStep2',
            dataType: "json",
            data: {booking_ref_no: resCode, email_field_for_manage: email_field_for_manage},
            success: function (response) {
                $('.loaderSpiner').hide();
                if (response.status == true) {
                    window.location.href = lang_base_url + '/manage-booking/' + response.record_id;
                } else {
                    $('.responseTitle').html(response.title);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }

            }
        });
    }
});

function subscribe_news_letter(email) {
    $('.loaderSpiner').show();

    if (lang == "eng") {
        var error = 'Error';
        var emailMsg = 'Please enter the email address';
        var validEmailMsg = 'Please enter a valid email address';
    } else {
        var error = 'خطأ';
        var emailMsg = 'الرجاء إدخال البريد الإلكترونى';
        var validEmailMsg = 'الرجاء إدخال بريد إلكترونى صحيح';

    }

    if (email == "") {
        $('.loaderSpiner').hide();
        $('.responseTitle').html(error);
        $('.responseMsg').html(emailMsg);
        $('#openMsgPopupNoRedirect').click();
        return false;
    }
    if (!validateEmail(email)) {
        $('.loaderSpiner').hide();
        $('.responseTitle').html(error);
        $('.responseMsg').html(validEmailMsg);
        $('#openMsgPopupNoRedirect').click();
        return false;
    }

    var url = $(".newsLetter").attr("action");
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {news_letter: email},
        success: function (response) {
            $('.loaderSpiner').hide();

            if (response.success == true) {
                $('.responseTitle').html(response.title);
                $('.responseMsg').html(response.message);
                $('#openMsgPopupNoRedirect').click();
            } else {
                $('.responseTitle').html(response.title);
                $('.responseMsg').html(response.message);
                $('#openMsgPopupNoRedirect').click();
            }

        }
    });

}

// validate email for news letter
function validateEmail(email) {
    var atpos = email.indexOf("@");
    var dotpos = email.lastIndexOf(".");
    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= email.length) {
        return false;
    } else {
        return true;
    }
}

// kashif work 7 april 2017
// these variables Also used for load more function
var isAlreadyLoadMoreCalled = false;
var isNoMoreResults = false;
var offset = 10;

function fleet_serch_cat_filter(category_id, c_url, text_for_mobile) {
    // window.history.pushState('category_change', '', c_url);
    $('.moreRecordsDiv').show();
    $('.noRecordFoundDiv').hide();
    isAlreadyLoadMoreCalled = false;
    isNoMoreResults = false;
    offset = 10;

    $('.shotingLink').children('ul').children('li').removeClass('active');

    $('.shotingLink').children('ul').children('li').find('a[data-categoryid="' + category_id + '"]').parent().addClass('active');

    setCatIdInHiddenField();

    $('.loaderSpiner').show();
    var formData = $('.fleetSerBar').serialize();
    var url = lang_base_url + '/search_cars_with_all_fields';
    $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        success: function (response) {
            var resultCount = $(response).filter('.singleRow').length;
            if (resultCount < 10) {
                $('.moreRecordsDiv').hide();
                isNoMoreResults = true;
            }

            $('.containsData').html('');
            $('.containsData').html(response);
            if (text_for_mobile !== false) {
                $('#fleet_text').val(text_for_mobile);
            }
            $('.loaderSpiner').hide();
        }
    });
}

function search_cars_with_all_fields() {
    $('.loadMore').hide();
    isAlreadyLoadMoreCalled = true;
    isNoMoreResults = true;
    offset = 10;

    setCatIdInHiddenField();

    $('.loaderSpiner').show();
    var formData = $('.fleetSerBar').serialize();
    var url = lang_base_url + '/search_cars_with_all_fields';
    $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        success: function (response) {

            var resultCount = $(response).filter('.singleRow').length;
            if (resultCount >= 10) {
                $('.loadMore').show();
                isAlreadyLoadMoreCalled = false;
                isNoMoreResults = false;
            }

            $('.containsData').html('');
            $('.containsData').html(response);
            $('.loaderSpiner').hide();
        }
    });
}

function setCatIdInHiddenField() {
    $('.shotingLink ul li').each(function () {
        if ($(this).hasClass('active')) {
            var cat_id = $(this).children('a').data('categoryid');
            $("#searchcatId").val(cat_id);
        }
    });
}

function setBranchIdInHiddenField(bid) {
    $("#searchBranchId").val(bid);
    search_cars_with_all_fields();
}

function loadMoreCars() {

    if (!isAlreadyLoadMoreCalled && !isNoMoreResults) {
        $('.moreRecordsDiv').show();
        isAlreadyLoadMoreCalled = true;
        $('.loaderSpiner').show();
        var data = $(".fleetSerBar").serializeArray();

        data.push({name: 'offset', value: offset});
        $.ajax({
            type: "POST",
            url: lang_base_url + "/fleetPagination",
            data: data,
            dataType: "json",
            success: function (response) {
                $('.loaderSpiner').hide();
                if (response != "") {
                    $('.fleetLoadMorePage').append(response);

                    offset += 10;
                } else {
                    $('.moreRecordsDiv').hide();
                    $('.noRecordFoundDiv').show();
                    isNoMoreResults = true;
                }
                isAlreadyLoadMoreCalled = false;
            }
        });
    }
}

if (page == "fleet") {
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 50) {
            //alert("near bottom!");
            loadMoreCars();
        }
    });
}

// load more cars on search result page
function loadMoreSearchCars() {

    if (!isAlreadyLoadMoreCalled && !isNoMoreResults) {

        $('.moreRecordsDiv').show();

        isAlreadyLoadMoreCalled = true;
        $('.loaderSpiner').show();
        var formData = $(".serFormArea").serializeArray();
        var cat_id = "";
        $('.shotingLink ul li').each(function () {
            if ($(this).hasClass('active')) {
                cat_id = $(this).children('a').data('category-id');
            }
        });
        if (is_mobile) {
            cat_id = $('.catId_mob_search').find(":selected").val();
        }
        formData.push({name: 'offset', value: offset});
        formData.push({name: 'cat_id', value: cat_id});

        $.ajax({
            type: "POST",
            url: lang_base_url + "/searchCarsPagination",
            data: formData,
            dataType: "json",
            success: function (response) {

                $('.loaderSpiner').hide();

                if (response != "") {
                    $('.searchLoadMorePage').append(response);

                    offset += 10;
                } else {
                    $('.moreRecordsDiv').hide();
                    $('.noRecordFoundDiv').show();
                    isNoMoreResults = true;
                }
                isAlreadyLoadMoreCalled = false;
                if ($('input[name="show_available_cars_only"]').is(':checked')) {
                    $('.sold_out').hide();
                } else {
                    $('.sold_out').show();
                }
            }
        });
    }

}

// hide and display load more btn if record less than 10 or greater
/*var searchCount = $('.searchLoadMorePage').find('.singleRow').length;
 var fleetCount = $('.fleetLoadMorePage').find('.singleRow').length;
 if(searchCount >=10 || fleetCount >=10){
 $('.loadMore').show();
 }else{
 isAlreadyLoadMoreCalled = true;
 isNoMoreResults = true;
 $('.loadMore').hide();
 }*/

if (page == "search-results") {
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 50) {
            //alert("near bottom!");
            // loadMoreSearchCars();
        }
    });
}

// For My Bookings Filtering
$(document).on('submit', '#my_bookings_filtering', function (e) {
    var $form = $(this);
    var method = $form.attr('method');
    var url = $form.attr('action');
    $('.loaderSpiner').show();
    $.ajax({
        type: method,
        url: url,
        dataType: "json",
        //data: $form.serialize(),
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            //alert(response.html);
            $('#new_records').html(response.html);
            $('#old_records').hide();
            $('#new_records').show();
            $('.loaderSpiner').hide();

        }
    });

});

// For Resetting My Bookings Filtering
$(document).on('click', '#reset_results', function (e) {
    $('#key_name').val('');
    $('#date').val('');
    $('#old_records').show();
    $('#new_records').hide();
    /*    var $form = $(this).closest('form');
     var method = $form.attr('method');
     var url = $form.attr('action');
     alert(url);
     //$('.loaderSpiner').show();
     $.ajax({
     type: method,
     url: url,
     dataType: "json",
     data: {lang: lang},
     success: function (response) {
     //alert(response.html);
     $('#new_records').html(response.html);
     $('#old_records').hide();
     $('#new_records').show();
     $('.loaderSpiner').hide();

     }
     });*/

});

function checkIfFieldEmpty(val) {
    if (val == '') {
        show_bs_tooltip($('.id_no_for_loyalty'));
        return false;
    } else {
        hide_bs_tooltip($('.id_no_for_loyalty'));
        return true;
    }
}

// For My Bookings Filtering
$(document).on('click', '.applyUserLoyaltyBtn', function (e) {
    var user_id_no = $('.id_no_for_loyalty').val();
    var error = '';
    var resp_msg = '';
    if (checkIfFieldEmpty(user_id_no) == true) {
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/checkIfIdNoExist',
            dataType: "json",
            data: {user_id_no: user_id_no},
            success: function (response) {
                if (response.status == false) {
                    if (lang == "eng") {
                        error = 'Error';
                        resp_msg = 'Sorry. We could not find this ID no in our system.';
                    } else {
                        error = 'خطأ';
                        resp_msg = 'لم يتم العثور على سائق بالهوية المدخلة';
                    }
                    $('.loaderSpiner').hide();
                    $('.responseTitle').html(error);
                    $('.responseMsg').html(resp_msg);
                    $('#openMsgPopupNoRedirect').click();
                } else {
                    // syncing customer loylaty details
                    $.get(base_url + '/cronjob/loyaltySyncCronJob?from_mobile=1&user_id_no=' + user_id_no, function (data) {
                        $('.loaderSpiner').hide();
                        $('.userLoyaltyApplyForm').submit();
                    });
                }

            }
        });

    }
});

// email_required_for_validation
$(document).on('keyup', '#email_required_for_validation', function (event) {
    $('#emailLoginMsg').show();
});

$(document).on('click', '#openLoginDDB', function (event) {
    $('.hasDropEd').removeClass('open');
    $('#containsLoginDDB').addClass('open');
});

// Stoping user after checkout to use back button
/*$(document).ready(function () {
    if (last_segment == 'booked' || last_segment == 'booking-done') {
        /!*history.pushState(null, null, document.URL);
         window.addEventListener('popstate', function () {
         history.pushState(null, null, document.URL);
         });*!/
        (function (global) {

            if (typeof (global) === "undefined") {
                throw new Error("window is undefined");
            }

            var _hash = "!";
            var noBackPlease = function () {
                global.location.href += "#";

                // making sure we have the fruit available for juice....
                // 50 milliseconds for just once do not cost much (^__^)
                global.setTimeout(function () {
                    global.location.href += "!";
                }, 50);
            };

            // Earlier we had setInerval here....
            global.onhashchange = function () {
                if (global.location.hash !== _hash) {
                    global.location.hash = _hash;
                }
            };

            global.onload = function () {

                noBackPlease();

                // disables backspace on page except on input fields and textarea..
                document.body.onkeydown = function (e) {
                    var elm = e.target.nodeName.toLowerCase();
                    if (e.which === 8 && (elm !== 'input' && elm !== 'textarea')) {
                        e.preventDefault();
                    }
                    // stopping event bubbling up the DOM tree..
                    e.stopPropagation();
                };

            };

        })(window);
    }
});*/

function siteUnderMaintenance() {
    $('#openSiteUnderMaintenancePopUp').click();
}

$(document).on('click', '.btn-booking', function (event) {
    var branch_id = $(this).data('branch_id');
    $.ajax({
        type: 'POST',
        url: lang_base_url + '/redirectToBookingPage',
        dataType: "json",
        data: {lang: lang, branch_id: branch_id},
        success: function (response) {
            window.location.href = lang_base_url + '/book-car';
        }
    });
});

$(document).on('focusout', '.checkEmailValid', function (event) {
    var email = $(this).val();
    if (email != '') {
        if (isEmail(email)) {
            $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
            $('.submit_btn').attr('disabled', false);
            // email border original
        } else {
            // border red and disable submit button
            $(this).css({"border": "1px solid red"});
            $('.submit_btn').attr('disabled', true);
        }
    }
});

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

/*    $(document).on('blur', '.id_no', function (event) {
 var id_type = $('.id_type').val();
 var id_no_length = $(".id_no").val().length;
 if ((id_type == '243' || id_type == '68') && id_no_length != 10) {
 //alert('field length not correct');
 //$(".id_no").css({"border": "1px solid red"});
 $(".id_no").attr("data-original-title", 'This filed must contain only 10 characters');
 $(".id_no").tooltip('show');
 //$('.id_no').append('hello');
 //$('.submitBtn').attr('disabled', true);
 //$(this).closest('form').di
 // submitBtn
 // bookNowBtn
 $('.submit_btn').attr('disabled', true);
 } else {
 $(".id_no").tooltip('hide');
 //$(".id_no").css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width" : "1px"});
 //$('.submitBtn').attr('disabled', false);
 $('.submit_btn').attr('disabled', false);
 }
 });*/

/*$(document).on('blur', '.id_no', function (event) {
 var id_type = $('.id_type').val();
 var id_no = $(".id_no").val();
 var id_no_length = $(".id_no").val().length;
 if ((id_type == '243' || id_type == '68') && id_no_length != 10) {
 $(".id_no").css({"border": "1px solid red"});
 $(".id_no").attr("data-original-title", 'This filed must contain only 10 characters');
 $(".id_no").tooltip('show');
 //$('.id_no').append('hello');
 $('.submit_btn').attr('disabled', true);
 } else {
 $(".id_no").tooltip('destroy');
 $(".id_no").tooltip('hide');
 $(".id_no").css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width" : "1px"});
 $('.submit_btn').attr('disabled', false);
 }
 });*/

function validateIDNoField(val) {
    /* var id_type = val;
     var id_no = $(".id_no").val();
     var id_no_length = $(".id_no").val().length;
     if (id_no != '' || id_no.length !== 0)
     {
     if ((id_type == '243' || id_type == '68') && id_no_length != 10) {
     $(".id_no").css({"border": "1px solid red"});
     $(".id_no").attr("data-original-title", 'This filed must contain only 10 characters');
     $(".id_no").tooltip('show');
     //$('.id_no').append('hello');
     $('.submit_btn').attr('disabled', true);
     } else {
     $(".id_no").tooltip('destroy');
     $(".id_no").tooltip('hide');
     $(".id_no").css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width" : "1px"});
     $('.submit_btn').attr('disabled', false);
     }
     }*/
}

function hideShowLicenseField(val) {
    /*if (val == 'female') {
        $('.license_no').removeClass('required');
        $('.license_no_for_hide_show').hide();
    } else {
        $('.license_no').addClass('required');
        $('.license_no_for_hide_show').show();
    }*/
// license_no_for_hide_show
}

//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 500;  //time in ms, 5 second for example

//on keyup, start the countdown
$('.filterBranchesFromForPickup').on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(filterSearchAreaFromFieldForPickup, doneTypingInterval);
});

$('.filterBranchesToForPickup').on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(filterSearchAreaToFieldForPickup, doneTypingInterval);
});

//on keydown, clear the countdown
$('.filterBranchesFromForPickup, .filterBranchesToForPickup').on('keydown', function () {
    clearTimeout(typingTimer);
});

function filterSearchAreaFromFieldForPickup() {
    var filter;
    var branchesArray = [];
    var citiesArray = [];
    filter = $('#myInputFromForPickup').val();
    $.ajax({
        type: 'POST',
        url: lang_base_url + '/searchAreaBranchFilter',
        dataType: "json",
        data: {filter: filter},
        success: function (response) {
            if (is_mobile) {
                if (filter != "") {
                    $('.fleetDropDown#pickUp').slideDown('slow');
                } else {
                    clossingFun();
                }
            }

            branchesArray = response.branches.split(',');
            citiesArray = response.cities.split(',');
            $('#myULFromForPickup').find('.filterDataFromForPickup').css("display", "none"); // hiding all branches
            $('#myULFromForPickup').find('.filterDataParentFrom').css("display", "none"); // hiding all cities
            for (i = 0; i < citiesArray.length; i++) {
                $("#from_city_" + citiesArray[i]).css("display", "block");
            }
            for (i = 0; i < branchesArray.length; i++) {
                $("#from_branch_" + branchesArray[i]).css("display", "block");
            }
        }
    });
}

function filterSearchAreaToFieldForPickup() {
    var filter;
    var branchesArray = [];
    var citiesArray = [];
    filter = $('#myInputToForPickup').val();
    $.ajax({
        type: 'POST',
        url: lang_base_url + '/searchAreaBranchFilter',
        dataType: "json",
        data: {filter: filter},
        success: function (response) {

            if (is_mobile) {
                if (filter != "") {
                    $('.fleetDropDown#dropOff').slideDown('slow');
                } else {
                    clossingFun();
                }
            }

            branchesArray = response.branches.split(',');
            citiesArray = response.cities.split(',');
            $('#myULToForPickup').find('.filterDataTo').css("display", "none");
            $('#myULToForPickup').find('.filterDataParentTo').css("display", "none");
            for (i = 0; i < citiesArray.length; i++) {
                $("#to_city_" + citiesArray[i]).css("display", "block");
            }
            for (i = 0; i < branchesArray.length; i++) {
                $("#to_branch_" + branchesArray[i]).css("display", "block");
            }
        }
    });
}

function validateMobileSearch() {
    var required_msg = 'This field is required.';
    var returnVal = true;
    $("input.required-for-search, select.required-for-search").each(function () {
        if ($(this).val() === '') {
            show_bs_tooltip($(this), required_msg);
            returnVal = false;
        }
    });
    return returnVal;
}

$(document).on('click', '#btn_forGotPassLogn', function (event) {
    $('#model-login').modal('hide');
    $('#forGotPassLogn_mobile').modal('show');
});

function set_from_city_branch_also(city_val, city_text, branch_val) {

    /*var city_val = "3|1";
    var city_text = 'TAIF CITY';
    var branch_val = 33;*/
    var called_from_also = true;
    $("#fromCities").val(city_val);
    mob_from_cities_change(city_val, city_text, called_from_also, branch_val);

}

function mob_from_cities_change(city_ddb_val, city_ddb_text, called_from_also, branch_val) {
    $('.loaderSpiner').show();
    $('#myInputFromForPickup').val('');
    $('#myInputFromForPickup').removeClass('required-for-search');
    $('#myInputToForPickup').removeClass('required-for-search');
    var getVal = city_ddb_val.split('|');
    var getText = city_ddb_text;
    $('#from_city_ylw_bx').text(getText);
    var city_id;
    var region_id;
    city_id = getVal[0];
    region_id = getVal[1];
    $('#from_region_id').val(region_id);
    $('#from_city_id').val(city_id);
    $('#to_city_id').val(city_id);
    $.ajax({
        type: 'POST',
        url: lang_base_url + '/getBranchesByCity',
        data: {city_id: city_id, is_delivery_mode: is_delivery_mode},
        success: function (response) {
            $('#fromBranches').html(response);
            if (called_from_also === true) {
                $("#fromBranches").val(branch_val);
            }
            $('.loaderSpiner').hide();
        }
    });
}

function set_to_city_branch_also(city_val, city_text, branch_val) {
    var called_to_also = true;
    $("#toCities").val(city_val);
    mob_to_cities_change(city_val, city_text, called_to_also, branch_val);

}

function mob_to_cities_change(city_ddb_val, city_ddb_text, called_to_also, branch_val) {
    $('.loaderSpiner').show();
    $('#myInputToForPickup').val('');
    $('#myInputToForPickup').removeClass('required-for-search');
    var getVal = city_ddb_val.split('|');
    var getText = city_ddb_text;
    $('#to_city_ylw_bx').text(getText);
    var city_id;
    city_id = getVal[0];
    $('#to_city_id').val(city_id);
    $.ajax({
        type: 'POST',
        url: lang_base_url + '/getBranchesByCity',
        data: {city_id: city_id, is_delivery_mode: is_delivery_mode},
        success: function (response) {
            $('#toBranches').html(response);
            if (called_to_also === true) {
                $("#toBranches").val(branch_val);
            }
            $('.loaderSpiner').hide();
        }
    });
}

$(document).on('change', '#fromCities', function (event) {
    var getVal = $(this).val();
    var getText = $('option:selected', this).text();
    mob_from_cities_change(getVal, getText, false, 0);
    $('#fromBranches').parent('.field').show();
    $('.wiz1Next').parents('.btn-next').show();
});

$(document).on('change', '#fromBranches', function (event) {
    $('#myInputFromForPickup').val('');
    var branch_id = $(this).val();
    var getText = $('option:selected', this).text();
    $('#from_branch_ylw_bx').text(getText);
    $('#from_branch_id').val(branch_id);
    $('#to_branch_id').val(branch_id);
});

$(document).on('change', '#toCities', function (event) {
    var getVal = $(this).val();
    var getText = $('option:selected', this).text();
    mob_to_cities_change(getVal, getText, false, 0);
    $('#toCities').parent('.field').show();
});

$(document).on('change', '#toBranches', function (event) {
    $('#myInputToForPickup').val('');
    var branch_id = $(this).val();
    var getText = $('option:selected', this).text();
    $('#to_branch_ylw_bx').text(getText);
    $('#to_branch_id').val(branch_id);
});

$(document).on('change', '.id_type', function (event) {
    $('.id_no').val('');
    $('.validate_license_number').val('');
    if ($(this).val() == '243' || $(this).val() == '68') {
        $(this).addClass('number');
    } else {
        $(this).removeClass('number');
    }
    $(".id_no").css({
        "border-color": "#afb0aa #e9eae4 #ebeae6",
        "border-style": "solid",
        "border-width": "1px"
    });
    hide_bs_tooltip($(".id_no"));
    $('.submit_btn').attr('disabled', false);
});

$(document).on('blur', '.validate_license_number', function (event) {
    var id_type = $('.id_type').val();
    var license_no_length = $(this).val().length;

    /*if (id_type == '243' || id_type == '68')
     {
     $(this).addClass('number');
     }else{
     $(this).removeClass('number');
     }*/

    if ((id_type == '243' || id_type == '68') && license_no_length != 10) {
        $(this).css({"border": "1px solid red"});
        show_bs_tooltip($(this), 'This filed must contain only 10 characters');
        $('.submit_btn').attr('disabled', true);
    } else {
        $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
        hide_bs_tooltip($(this));
        $('.submit_btn').attr('disabled', false);
    }
});

$(document).on('blur', '.id_no', function (event) {

    var id_no = $(this).val();
    var id_type = $('.id_type').val();
    var id_no_length = $(this).val().length;

    if ((id_type == '243' || id_type == '68')) {
        if (id_no_length !== 10) {
            $(".id_no").css({"border": "1px solid red"});
            $(".id_no").append("<b>This filed must contain only 10 characters</b>");
            show_bs_tooltip($(".id_no"), 'This filed must contain only 10 characters');
            $('.submit_btn').attr('disabled', true);
        } else {
            hide_bs_tooltip($(".id_no"));
            $(".id_no").css({
                "border-color": "#afb0aa #e9eae4 #ebeae6",
                "border-style": "solid",
                "border-width": "1px"
            });
            $('.submit_btn').attr('disabled', false);
        }

        if (id_no != '' && id_no_length == 10) {
            $.ajax({
                type: 'POST',
                url: lang_base_url + '/validateSaudiID',
                dataType: "json",
                data: {id_no: id_no},
                success: function (response) {
                    if (response.status == false) {
                        $(".id_no").css({"border": "1px solid red"});
                        show_bs_tooltip($(".id_no"), 'ID number you entered is not valid.');
                        $('.submit_btn').attr('disabled', true);
                    } else {
                        $(".id_no").css({
                            "border-color": "#afb0aa #e9eae4 #ebeae6",
                            "border-style": "solid",
                            "border-width": "1px"
                        });
                        hide_bs_tooltip($(".id_no"));
                        $('.submit_btn').attr('disabled', false);
                    }
                }
            });
        }
    }

});

$(document).on('click', '.showHideOlpIdField', function (event) {
// olpIdField
    var payment_type = $(this).val();
    if (payment_type == 'sadad') {
        $('.olpIdField').addClass('required');
        $('.olpIdField').show();
    } else {
        $('.olpIdField').removeClass('required');
        $('.olpIdField').hide();
    }

});

$(document).on("keyup", "input[name='license_no']", function (event) {
    var license_no_length = $(this).val().length;
    if (license_no_length > 19) {
        $(this).css({"border": "1px solid red"});
        show_bs_tooltip($(this), 'This filed can contain max 19 characters.');
        $('.license_validate_btn').attr('type', 'button');
    } else {
        hide_bs_tooltip($(this));
        $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
        $('.license_validate_btn').attr('type', 'submit');
    }

});

function openGoogleMapPopup(lat, long, branch_id, type, delivery_charges) {
    var lbl;
    var cnf;
    marker = '';
    if (type == 'dropoff') {
        var from_branch_id = $('#from_branch_id').val();
        var to_branch_id = $('#to_branch_id').val();
        if (from_branch_id !== '' && from_branch_id !== branch_id) {
            if (lang == "eng") {
                var msg = 'The returning city should be the same.';
                var error = 'Error';
            } else {
                var msg = 'تسليم السيارة يجب ان يكون في نفس المدينة';
                var error = 'خطأ';
            }

            $('.responseTitle').html(error);
            $('.responseMsg').html(msg);
            $('#openMsgPopupNoRedirect').click();
            return false;
        }
    }

    // setting popup header text
    if (type == 'dropoff') {
        if (lang == "eng") {
            lbl = 'Return location';
            cnf = 'Confirm';
        } else {
            lbl = 'مكان التسليم';
            cnf = 'تأكيد';
        }
        $('.mapConfirm').text(cnf);
        $('.containsLableForMapPopup').text(lbl);
    } else {
        if (lang == "eng") {
            lbl = 'Delivery location';
            cnf = 'Select location';
        } else {
            lbl = 'مكان الإستلام';
            cnf = 'اختر الموقع';
        }
        $('.mapConfirm').text(cnf);
        $('.containsLableForMapPopup').text(lbl);
    }

    $('#noLocationSelectedMessage').hide();
    $('#containsLocationDetails').hide();
    $('#containsErrorMessage').hide();
    $('#containsAddress').text('');
    $('#containsCity').text('');
    if (type == 'pickup') {
        $('#delivery_charges').val(parseFloat(delivery_charges));
    }
    var map_lat = parseFloat(lat);
    var map_long = parseFloat(long);
    $('.openGoogleMapPopup').click();
    $('#pop_type').val(type);
    initAutocompleteMap(map_lat, map_long, branch_id, type);

    setTimeout(function () {
        center = map.getCenter();
        google.maps.event.trigger(map, 'resize');
        map.setCenter(center);

    }, 500);
}

var map;
var my_center;
var marker;

function initAutocompleteMap(map_lat, map_long, branch_id, type) {
    /*var map_lat = -33.8688;
     var map_long = 151.2195;
     if (lat != '')
     {
     map_lat = lat;
     }
     if (long != '')
     {
     map_long = long;
     }*/
    /*if (navigator.geolocation) {
     navigator.geolocation.getCurrentPosition(function (position) {
     my_center = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
     });
     } else {*/
    my_center = new google.maps.LatLng(map_lat, map_long);
    //}

    map = new google.maps.Map(document.getElementById('google_map_with_search'), {
        center: my_center,
        zoom: 14,
        mapTypeId: 'roadmap',
        gestureHandling: 'greedy'
    });

    // code to generate marker on popup open
    var del_coords;
    var popup_type = $('#pop_type').val();
    if (popup_type == 'pickup') {
        del_coords = $('.pickup_delivery_coordinate').val();
    } else if (popup_type == 'dropoff') {
        del_coords = $('.dropoff_delivery_coordinate').val();
    }
    if (del_coords) {
        del_coords = del_coords.split(',');
        var my_my_center = new google.maps.LatLng(del_coords[0], del_coords[1]);
        map.setCenter({lat: del_coords[0], lng: del_coords[1]});
        placeMarker(my_my_center, map);
    }

    /*
     if (navigator.geolocation) {
     navigator.geolocation.getCurrentPosition(function (position) {
     initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
     map.setCenter(initialLocation);
     });
     }*/

    google.maps.event.addListener(map, 'click', function (event) {

        $('.loaderSpiner').show();
        // placing marker on map
        var clicked_lat_long = placeMarker(event.latLng, map);
        var clicked_lat_long_arr = clicked_lat_long.split(',');

        // getting nearest branch depending upon clicked lat long on map
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/fetch_nearest_delivery_branch',
            dataType: 'JSON',
            data: {'current_latitude': clicked_lat_long_arr[0], 'current_longitude': clicked_lat_long_arr[1]},
            success: function (response) {

                $('.loaderSpiner').hide();

                if (response.status == true) {

                    // putting values in pickup fields
                    $("#from_region_id").val(response.branch.region_id);
                    $("#from_city_id").val(response.branch.city_id);
                    $("#from_branch_id").val(response.branch.branch_id);

                    // putting values in dropoff fields
                    $("#to_city_id").val(response.branch.city_id);
                    $("#to_branch_id").val(response.branch.branch_id);

                    $('#delivery_charges').val(parseFloat(response.branch.branch_delivery_charges));

                    getLocationAndCheckIfInArea(clicked_lat_long, response.branch.branch_id, type);
                } else {
                    alert(response.message);
                }
            }
        });
    });

    // Create the search box and link it to the UI element.
    $('#google_map_outer').append('<input type="text" class="mapSerPopup controls" id="take_input" placeholder="Search" />');
    var input = document.getElementById('take_input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function () {
        searchBox.setBounds(map.getBounds());
    });

    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function () {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        // Clear out the old markers.
        markers.forEach(function (marker) {
            marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function (place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });

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

    $("#google_map_with_search").css({"height": "100%", "width": "99%"});

    setTimeout(function () {
        // alert('width changed');
        $("#google_map_with_search").css({"height": "99%", "width": "100%"});
    }, 1000);

}

/*if (lang == "eng") {
 var success = 'Success';
 var error = 'Error';
 } else {
 var success = 'بنجاح';
 var error = 'خطأ';
 }
 $('.loaderSpiner').hide();
 if (response.status == false) {
 $('.responseTitle').html(error);
 $('.responseMsg').html(response.message);
 $('#openMsgPopupNoRedirect').click();
 } else {
 $('.responseTitle').html(success);
 $('.responseMsg').html(response.message);
 $('#openMsgPopupNoRedirect').click();
 }*/

function getLocationAndCheckIfInArea(lat_long, branch_id, type) {
    var ajaxResponse;
    //alert(lat_long);
    //alert(branch_id);
    //alert(url);
    // send ajax call here and check if point lies in the branch area or not
    //$('.loaderSpiner').show();
    var post_string = "?lat_long=" + lat_long + "&branch_id=" + branch_id + "&t=" + Math.random();
    var url = lang_base_url + '/getLocationAndCheck' + post_string;
    ajaxResponse = sendHttpAjaxRequest(url);
    if (ajaxResponse !== false) {
        var branch_coordinates = ajaxResponse.branch_coordinates;
        var selected_lat_long = ajaxResponse.selected_lat_long;
        var return_status = ajaxResponse.return_status;
        var location_details = ajaxResponse.location_details;

        var cordSelected = selected_lat_long.split(",");
        var cords = branch_coordinates.split("|");

        if (type == 'pickup') {
            $('.pickup_delivery_coordinate').val(selected_lat_long);
            $('.dropoff_delivery_coordinate').val(selected_lat_long);
        } else if (type == 'dropoff') {
            $('.dropoff_delivery_coordinate').val(selected_lat_long);
        }

        checkIfPointInsideOrOutside(cordSelected[0], cordSelected[1], cords, location_details, type, branch_id);

    } else {

    }

}

function sendHttpAjaxRequest(url) {
    var responseArr;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {
            if (xmlhttp.status == 200) {
                //document.getElementById("myDiv").innerHTML = xmlhttp.responseText;
                //alert(xmlhttp.responseText);
                responseArr = JSON.parse(xmlhttp.responseText);
            } else if (xmlhttp.status == 400) {
                responseArr = false;
            } else {
                responseArr = false;
            }
        }
    };

    xmlhttp.open("GET", url, false);
    xmlhttp.send();
    return responseArr;
}

function checkIfPointInsideOrOutside(latPoint, longPoint, ContainedArr, location_details, type, branch_id) {
    var htmlText = "";
    var address = "";
    $('#pop_type').val(type);
    $('#containsAddress').text(location_details.address);
    if (location_details.city != '') {
        htmlText += location_details.city + ", ";
    }
    if (location_details.state != '') {
        htmlText += location_details.state + ", ";
    }
    if (location_details.country != '') {
        htmlText += location_details.country + ", ";
    }
    $('#containsCity').text(htmlText);

    if (location_details.address != "") {
        address = location_details.address + ", ";
    } else {
        address = "";
    }

    var completeAddress = address + htmlText;
    if (completeAddress == "") {
        completeAddress = (type == 'pickup' ? $('#from_branch_' + branch_id).find('a').text() : $('#to_branch_' + branch_id).find('a').text());
    }

    var polygonCoords = [];
    for (var i = 0; i < ContainedArr.length; i++) {
        var coords = ContainedArr[i].split(",");
        var newLatLng = new google.maps.LatLng(coords[0], coords[1]);
        polygonCoords.push(newLatLng);
    }
    var point = new google.maps.LatLng(latPoint, longPoint);
    var polygon = new google.maps.Polygon({path: polygonCoords});
    if (polygon.Contains(point)) { // if inside the area

        // setting values in pickup and dropoff fields
        if (type == 'pickup') {
            // .from_branch_field_for_delivery
            $('.from_branch_field_for_delivery').val(completeAddress.replace(/,\s*$/, ""));
            // $('.to_branch_field_for_delivery').val(completeAddress.replace(/,\s*$/, ""));
        } else if (type == 'dropoff') {
            // .to_branch_field_for_delivery
            // $('.to_branch_field_for_delivery').val(completeAddress.replace(/,\s*$/, ""));
        }
        if (type == 'pickup') {
            $('.allIsOkForPickup').val(1);
            $('.allIsOkForDropoff').val(1);
        } else {
            $('.allIsOkForDropoff').val(1);
        }
        $('#containsErrorMessage').hide();
        $('#noLocationSelectedMessage').hide();
        $('#containsLocationDetails').show();
    } else { // if outside the area
        $('#containsErrorMessage').show();
        $('#noLocationSelectedMessage').hide();
        $('#containsLocationDetails').hide();
        if (type == 'pickup') {
            $('.allIsOkForPickup').val(0);
            $('.allIsOkForDropoff').val(0);
        } else {
            $('.allIsOkForDropoff').val(0);
        }
    }
}

// mapConfirm
$(document).on('click', '.mapConfirm', function (e) {
    var pop_type = $('#pop_type').val();

    if (pop_type == 'pickup') {
        var del_coords = $('.pickup_delivery_coordinate').val();
        var inside_or_outside = $('.allIsOkForPickup').val();
    } else if (pop_type == 'dropoff') {
        var del_coords = $('.dropoff_delivery_coordinate').val();
        var inside_or_outside = $('.allIsOkForDropoff').val();
    }
    if (del_coords == '') {
        $('#noLocationSelectedMessage').show();
        $('#containsErrorMessage').hide();
        $('#containsLocationDetails').hide();
    } else {
        if (inside_or_outside == 1) {
            $('#noLocationSelectedMessage').hide();
            $('#containsErrorMessage').hide();
            $('#containsLocationDetails').show();
            $('#google_map_popup').modal('hide');

            // toBid_1
            if (1 == 2 && pop_type == 'pickup') { // remove 1==2 to open popup again as soon as user selects pickup location for delivery
                setTimeout(function () {
                    var from_bid = $('#from_branch_id').val();
                    var delivery_charges = $('#delivery_charges').val();
                    var pickup_delivery_coordinate = $('.pickup_delivery_coordinate').val();
                    pickup_delivery_coordinate = pickup_delivery_coordinate.split(',');
                    //$('.toBid_'+from_bid+' a').click();
                    openGoogleMapPopup(pickup_delivery_coordinate[0], pickup_delivery_coordinate[1], from_bid, 'dropoff', delivery_charges)
                }, 500);
            }

            //alert('model can be closed');
        } else if (inside_or_outside == 0) {
            $('#containsErrorMessage').show();
            $('#noLocationSelectedMessage').hide();
            $('#containsLocationDetails').hide();
            //alert('model can not be closed');
        }
    }
});

function placeMarker(location) {
    if (marker) {
        marker.setPosition(location);
    } else {
        marker = new google.maps.Marker({
            position: location,
            map: map
        });
    }
    var marker_lat = marker.getPosition().lat();
    var marker_lng = marker.getPosition().lng();
    return marker_lat + ',' + marker_lng;
}

$(document).ready(function () {
    if (logged_in_from_frontend === 1) { // to check and open popup on every page if user is logged in as we are having his email and id no in hidden field in footer
        var customer_email = $('.user_email_for_pending_survey').val();
        var customer_id_no = $('.user_id_no_for_pending_survey').val();
        var url = lang_base_url + '/checkIfSurveyPendingToFill';
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'JSON',
            data: {email: customer_email, id_no: customer_id_no, last_segment: last_segment},
            success: function (response) {
                if (response.status === true) {
                    $('#openSurveyPopup').click();
                    $('#skipSurvey').data('customer-id', response.customerId);
                } else {
                    console.log('no survey to fill');
                }
            }
        });
    }
});

$(document).on('click', '#skipSurvey', function () {
    var customer_id = $(this).data('customer-id');
    $('#surveyPopup').modal('toggle');
    var url = lang_base_url + '/skipSurvey';
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'JSON',
        data: {customer_id: customer_id},
        success: function (response) {

        }
    });
});

//on keyup, start the countdown
$('.filterBranchesFromForDelivery').on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(filterSearchAreaFromFieldForDelivery, doneTypingInterval);
});

$('.filterBranchesToForDelivery').on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(filterSearchAreaToFieldForDelivery, doneTypingInterval);
});

//on keydown, clear the countdown
$('.filterBranchesFromForDelivery, .filterBranchesToForDelivery').on('keydown', function () {
    clearTimeout(typingTimer);
});

function filterSearchAreaFromFieldForDelivery() {
    var filter;
    var branchesArray = [];
    var citiesArray = [];
    filter = $('#myInputFromForDelivery').val();
    $.ajax({
        type: 'POST',
        url: lang_base_url + '/searchAreaBranchFilter',
        dataType: "json",
        data: {filter: filter},
        success: function (response) {
            branchesArray = response.branches.split(',');
            citiesArray = response.cities.split(',');
            $('#myULFromForDelivery').find('.filterDataFromForDelivery').css("display", "none"); // hiding all branches
            $('#myULFromForDelivery').find('.filterDataParentFrom').css("display", "none"); // hiding all cities
            for (i = 0; i < citiesArray.length; i++) {
                $("#from_city_" + citiesArray[i]).css("display", "block");
            }
            for (i = 0; i < branchesArray.length; i++) {
                $("#from_branch_" + branchesArray[i]).css("display", "block");
            }
        }
    });
}

function filterSearchAreaToFieldForDelivery() {
    var filter;
    var branchesArray = [];
    var citiesArray = [];
    filter = $('#myInputToForDelivery').val();
    $.ajax({
        type: 'POST',
        url: lang_base_url + '/searchAreaBranchFilter',
        dataType: "json",
        data: {filter: filter},
        success: function (response) {
            branchesArray = response.branches.split(',');
            citiesArray = response.cities.split(',');
            $('#myULToForDelivery').find('.filterDataTo').css("display", "none");
            $('#myULToForDelivery').find('.filterDataParentTo').css("display", "none");
            for (i = 0; i < citiesArray.length; i++) {
                $("#to_city_" + citiesArray[i]).css("display", "block");
            }
            for (i = 0; i < branchesArray.length; i++) {
                $("#to_branch_" + branchesArray[i]).css("display", "block");
            }
        }
    });
}

//on keyup, start the countdown
$(document).on("keyup", "input[name='id_no']", function (event) {
    var id_no = $(this).val();
    var id_type = $("select[name='id_type'] option:selected").val();
    if (id_type == '68' || id_type == '243') {
        $("input[name='license_no']").attr('readonly', true);
        $("input[name='license_no']").css({"background-color": "#e9eae4"});
        $("input[name='license_no']").val('');
        $("input[name='license_no']").val(id_no);
    } else {
        $("input[name='license_no']").attr('readonly', false);
        $("input[name='license_no']").css({"background-color": "#ffffff"});
    }
});

$(".corporate-phone-primary, .corporate-phone-secondary").intlTelInput({
    //onlyCountries: ["sa", "eg"], initialCountry: "sa",
    initialCountry: "sa",
    excludeCountries: ["il"],
    nationalMode: false,
    separateDialCode: true,
    autoPlaceholder: "off",
    formatOnDisplay: false,
    utilsScript: base_url + "/public/frontend/intTelInput/js/utils.js" // just for formatting/placeholders etc
});
$(document).on('focusout', '.corporate-phone-primary', function (event) {
    var countryData = $(this).intlTelInput("getSelectedCountryData");
    if (countryData.iso2 == 'sa') {
        var inputLengthMobile = $(this).val().length;
        if (inputLengthMobile != 9) {
            $(this).css({"border": "1px solid red"});
            show_bs_tooltip($(this), 'This filed must contain only 9 characters');
            $('.submit_btn').attr('disabled', true);
        } else {
            hide_bs_tooltip($(this));
            $(this).css({
                "border-color": "#afb0aa #e9eae4 #ebeae6",
                "border-style": "solid",
                "border-width": "1px"
            });
            $('.submit_btn').attr('disabled', false);
        }
    } else {
        hide_bs_tooltip($(this));
        $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
        $('.submit_btn').attr('disabled', false);
    }
    var intlNumber = $(".corporate-phone-primary").intlTelInput("getNumber");
    $('.primary_inttelno').val(intlNumber);
});
$(".corporate-phone-primary").on("countrychange", function (e, countryData) {
    var countryData = $(this).intlTelInput("getSelectedCountryData");
    if (countryData.iso2 == 'sa') {
        var inputLengthMobile = $(this).val().length;
        if (inputLengthMobile != 9) {
            $(this).css({"border": "1px solid red"});
            show_bs_tooltip($(this), 'This filed must contain only 9 characters');
            //$('.id_no').append('hello');
            $('.submit_btn').attr('disabled', true);
        } else {
            hide_bs_tooltip($(this));
            $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
            $('.submit_btn').attr('disabled', false);
        }
    } else {
        hide_bs_tooltip($(this));
        $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
        $('.submit_btn').attr('disabled', false);
    }
    var intlNumber = $(".corporate-phone-primary").intlTelInput("getNumber");
    $('.primary_inttelno').val(intlNumber);
});

$(document).on('focusout', '.corporate-phone-secondary', function (event) {
    var countryData = $(this).intlTelInput("getSelectedCountryData");
    if (countryData.iso2 == 'sa') {
        var inputLengthMobile = $(this).val().length;
        if (inputLengthMobile != 9) {
            $(this).css({"border": "1px solid red"});
            show_bs_tooltip($(this), 'This filed must contain only 9 characters');
            $('.submit_btn').attr('disabled', true);
        } else {
            hide_bs_tooltip($(this));
            $(this).css({
                "border-color": "#afb0aa #e9eae4 #ebeae6",
                "border-style": "solid",
                "border-width": "1px"
            });
            $('.submit_btn').attr('disabled', false);
        }
    } else {
        hide_bs_tooltip($(this));
        $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
        $('.submit_btn').attr('disabled', false);
    }
    var intlNumber = $(".corporate-phone-secondary").intlTelInput("getNumber");
    $('.secondary_inttelno').val(intlNumber);
});
$(".corporate-phone-secondary").on("countrychange", function (e, countryData) {
    var countryData = $(this).intlTelInput("getSelectedCountryData");
    if (countryData.iso2 == 'sa') {
        var inputLengthMobile = $(this).val().length;
        if (inputLengthMobile != 9) {
            $(this).css({"border": "1px solid red"});
            show_bs_tooltip($(this), 'This filed must contain only 9 characters');
            //$('.id_no').append('hello');
            $('.submit_btn').attr('disabled', true);
        } else {
            hide_bs_tooltip($(this));
            $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
            $('.submit_btn').attr('disabled', false);
        }
    } else {
        hide_bs_tooltip($(this));
        $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
        $('.submit_btn').attr('disabled', false);
    }
    var intlNumber = $(".corporate-phone-secondary").intlTelInput("getNumber");
    $('.secondary_inttelno').val(intlNumber);
});

$(document).on('click', '.bookNowBtnForCorporate', function () {
    var mobile_field = $(".mobile_no");
    var allOkToSubmit = true;
    if (lang == "eng") {
        var Msg = 'Please accept the terms and conditions to proceed.';
        var msgTitlte = 'Message';
    } else {
        var Msg = 'الرجاء الموافقة على الشروط والأحكام';
        var msgTitlte = 'الرسالة';

    }
    $("input.required, select.required").each(function () {
        if ($(this).parent("li").is(':visible') && $(this).val() == '') {
            show_bs_tooltip($(this), required_message);
            this.scrollIntoView(false);
            allOkToSubmit = false;
        }
    });

    $("input").each(function () {
        if ($(this).attr('type') === 'email' && $(this).val() != '') {
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test($(this).val())) {
                show_bs_tooltip($(this), valid_email);
                this.scrollIntoView(false);
                allOkToSubmit = false;
            }
        }
    });

    if (!$('.accept_terms').is(':checked')) {
        $('.responseTitle').html(msgTitlte);
        $('.responseMsg').html(Msg);
        $('#openMsgPopupNoRedirect').click();
        this.scrollIntoView(false);
        allOkToSubmit = false;
    }

    if (mobile_field.val() == '') {
        show_bs_tooltip(mobile_field, required_message);
        this.scrollIntoView(false);
        allOkToSubmit = false;
    } else {
        hide_bs_tooltip(mobile_field);
    }

    if ($("input[name=payment_method]:checked").length > 0) {
        // Do your stuff here
    } else {
        allOkToSubmit = false;
    }

    if (allOkToSubmit === true) {
        $('.loaderSpiner').show();
        $('.bookNowForCorporateForm').submit(); // should remove this line when survey thing is done
        // check if survey pending
        /*        var customer_email_for_survey = $('.customer_email').val();
                var customer_id_no_for_survey = $('.customer_id_no').val();
                var url = lang_base_url + '/checkIfSurveyPendingToFill';
                $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: 'JSON',
                    data: {email: customer_email_for_survey, id_no: customer_id_no_for_survey, last_segment: last_segment},
                    success: function (responseCheckIfSurveyPendingToFill) {
                        if (responseCheckIfSurveyPendingToFill.status === true) {
                            $('.loaderSpiner').hide();
                            $('#openSurveyPopup').click();
                        }else{
                            $('.bookNowForCorporateForm').submit();
                        }
                        }
                    });*/
    } else {
        return false;
    }
});

function ValidationsWithIdType(id_type, update_license_also) {
    var id_no = $("input[name='id_no']").val();
    var id_no_length = $("input[name='id_no']").val().length;

// 68 iqama, 243 saudi id
    if ((id_type == '68' || id_type == '243') && update_license_also == 1) // it will be coming from end function getDriverInfo
    {
        $("input[name='license_no']").val(id_no);
    } else if ((id_type == '68' || id_type == '243') && update_license_also == 0) {
        $("input[name='license_no']").val('');
    }

    if (id_type == '68') {
        $('#sponsorName, #sponsorNo').attr('disabled', false);
        $('#sponsorName, #sponsorNo').addClass('required');
        $('#sponsorName, #sponsorNo').removeClass('readonly');
        $('#contains_sponsor_name, #contains_sponsor_no').show();
    } else {
        $('#sponsorName, #sponsorNo').attr('disabled', true);
        $('#sponsorName, #sponsorNo').removeClass('required');
        $('#sponsorName, #sponsorNo').addClass('readonly');
        $('#contains_sponsor_name, #contains_sponsor_no').hide();
    }

    if (id_type == '68' || id_type == '243') {
        $("input[name='license_no']").attr('readonly', true);
        $("input[name='id_no']").addClass('number');
        $("input[name='license_no']").addClass('number');
    } else {
        $("input[name='license_no']").attr('readonly', false);
        $("input[name='id_no']").removeClass('number');
        $("input[name='license_no']").removeClass('number');
    }


    if ((id_type == '243' || id_type == '68') && id_no_length != 10) {
        show_bs_tooltip($("input[name='id_no']"), 'This filed must contain only 10 characters');
    } else {
        hide_bs_tooltip($("input[name='id_no']"));
    }
}

// For getting driver info at corporate checkout page
//$(document).on('click', '#getDriverInfo', function (e) {
function getDriverDetails() {
    var get_driver_by = $('#get_driver_by').val();
    var error_text = '';
    if (get_driver_by == '') {
        show_bs_tooltip($('#get_driver_by'), required_message);
    } else {
        hide_bs_tooltip($('#get_driver_by'));
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/getDriverInfo',
            dataType: "json",
            data: {get_driver_by: get_driver_by},
            success: function (response) {
                $('.loaderSpiner').hide();
                if (response.status == false) {
                    if (lang == "eng") {
                        var error = 'Error';
                        error_text = 'No Driver Found Against This Search.';
                    } else {
                        var error = 'خطأ';
                        error_text = 'لم يتم العثور على سائق بالهوية المدخلة';
                    }
                    $('.responseTitle').html(error);
                    $('.responseMsg').html(error_text);
                    $('#openMsgPopupNoRedirect').click();
                } else {
                    // getting all values from JSON and putting them in respective fields
                    $.each(response.data, function (key, val) {
                        var $el = $('[name="' + key + '"]'),
                            type = $el.attr('type');

                        switch (type) {
                            case 'checkbox':
                                $el.attr('checked', 'checked');
                                break;
                            case 'radio':
                                $el.filter('[value="' + val + '"]').attr('checked', 'checked');
                                break;
                            default:
                                $el.val(val);
                        }
                    });
                    ValidationsWithIdType(response.data.id_type, 1);
                    $(".phone").intlTelInput("setNumber", "+" + response.data.mobile_no_seperated.original_no);
                }

            }
        });
    }
}

//});

function filterSellingCars(car_brand_id, car_year) {
    $('.loaderSpiner').show();
    var url = lang_base_url + '/filterSellingCars';
    $.ajax({
        type: 'POST',
        url: url,
        data: {car_brand_id: car_brand_id, car_year: car_year},
        success: function (response) {
            $('.search-results-here').html('');
            $('.search-results-here').html(response);
            $('.loaderSpiner').hide();
        }
    });
}

var brand_id = year = "";
$(document).on('click', '.carSellingFilterByBrand', function() {
    brand_id = $(this).data('id');
    filterSellingCars(brand_id, year);
    $('.carSellingFilterByBrand').find('img').removeClass('active');
    $(this).find('img').addClass('active');
});

$(document).on('click', '.carSellingFilterByYear', function() {
    year = $(this).data('year');
    filterSellingCars(brand_id, year);
    $('.carSellingFilterByYear').find('p').removeClass('active');
    $(this).find('p').addClass('active');
});

function interested_in_buying(car_id) {

    $.ajax({
        type: 'GET',
        url: base_url + '/carSellingFormEventForGtag',
        data: {car_id: car_id},
        success: function (response) {
            console.log('Logged to GTAG!');
        }
    });

    $('#interestedCarId').val(car_id);
    $('#openInterestedInCar').click();
}

function interested_in_corporate_sales() {
    $('#interestedCorporateSales').click();
}


/*corporate sales form submit*/
$(document).on('submit', '.interestedInCorpSales', function (e) {
    var allOk = true;
    $(".interestedInCorpSales input.required,.interestedInCorpSales textarea.required").each(function () {
        if ($(this).val() == '') {
            $(this).attr("data-placement", "right");
            show_bs_tooltip($(this), required_message);
            allOk = false;
        } else {
            hide_bs_tooltip($(this));
        }
    });

    if (allOk) {
        var $form = $(this);
        var method = $form.attr('method');
        var url = $form.attr('action');
        $('.loaderSpiner').show();
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            data: $form.serialize(),
            cache: false,
            success: function (response) {
                $('.loaderSpiner').hide();

                if (!response.captcha) {
                    $('.responseTitle').html(response.title);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                } else {
                    $('#interestedCorporateSalesModal').modal('hide');
                    $('.interestedInCorpSales')[0].reset();
                    $('.responseTitle').html(response.title);
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }

            }
        });
    }
});

/*end*/


$(document).on('submit', '.interestedInCarForm', function (e) {
    var allOk = true;
    $(".interestedInCarForm input.required").each(function () {
        if ($(this).val() == '') {
            $(this).attr("data-placement", "right");
            show_bs_tooltip($(this), required_message);
            allOk = false;
        } else {
            hide_bs_tooltip($(this));
        }
    });

    if (allOk) {
        var $form = $(this);
        var method = $form.attr('method');
        var url = $form.attr('action');
        $('.loaderSpiner').show();
        $.ajax({
            type: method,
            url: url,
            dataType: "json",
            //data: $form.serialize(),
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#interestedInCar').modal('hide');
                $('.interestedInCarForm')[0].reset();
                $('.loaderSpiner').hide();
                $('.responseTitle').html(response.title);
                $('.responseMsg').html(response.message);
                $('#openMsgPopupNoRedirect').click();
            }
        });
    }
});

$(document).on('keyup', '.phone-popup', function (event) {
    var countryData = $(this).intlTelInput("getSelectedCountryData");
    if (countryData.iso2 == 'sa') {
        var inputLengthMobile = $(this).val().length;
        if (inputLengthMobile != 9) {
            $(this).css({"border": "1px solid red"});
            show_bs_tooltip($(this), 'This filed must contain only 9 characters');
            $(".interestedInCarForm :submit").prop("disabled", true);
        } else {
            hide_bs_tooltip($(this));
            $(this).css({
                "border-color": "#afb0aa #e9eae4 #ebeae6",
                "border-style": "solid",
                "border-width": "1px"
            });
            $(".interestedInCarForm :submit").prop("disabled", false);
        }
    } else {
        hide_bs_tooltip($(this));
        $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
        $(".interestedInCarForm :submit").prop("disabled", false);
    }

    /*this was old code*/
    /*var intlNumber = $(".phone-popup").intlTelInput("getNumber");*/

    /*to handle if there are two phone number fields in single form also if more than one forms on whole page*/
    var intlNumber = $(this).intlTelInput("getNumber");
    var second = $(this).data('second');
    if (second == 'second') {
        $('.intTelNo2').val(intlNumber);
    } else {
        $('.intTelNo').val(intlNumber);
    }

});

$(".phone-popup").on("countrychange", function (e, countryData) {
    var countryData = $(this).intlTelInput("getSelectedCountryData");
    if (countryData.iso2 == 'sa') {
        var inputLengthMobile = $(this).val().length;
        if (inputLengthMobile != 9) {
            $(this).css({"border": "1px solid red"});
            show_bs_tooltip($(this), 'This filed must contain only 9 characters');
            $(".interestedInCarForm :submit").prop("disabled", true);
        } else {
            hide_bs_tooltip($(this));
            $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
            $(".interestedInCarForm :submit").prop("disabled", false);
        }
    } else {
        hide_bs_tooltip($(this));
        $(this).css({"border-color": "#afb0aa #e9eae4 #ebeae6", "border-style": "solid", "border-width": "1px"});
        $(".interestedInCarForm :submit").prop("disabled", false);
    }

    /*var intlNumber = $(".phone-popup").intlTelInput("getNumber");*/
    var intlNumber = $(this).intlTelInput("getNumber");
    $('.intTelNo').val(intlNumber);
});

$('#loadMoreCars').on("click", function (e) {
    var elem = $(this);
    var offset = elem.data('offset');
    var limit = elem.data('limit');
    var url = lang_base_url + '/getMoreSellingCars';
    $('.loaderSpiner').show();
    $.ajax({
        type: 'POST',
        url: url,
        dataType: "json",
        data: {offset: offset, limit: limit},
        success: function (response) {
            $('.loaderSpiner').hide();
            $('#loadMoreCars').data('offset', response.offset);
            $(".search-results-here").children('.row').last('.car-to-sell').append(response.html);
            if (response.show_load_more == false) {
                $('#loadMoreCars').hide();
            }
        }
    });
});

// For Applying redeem offer, get points from amount
/*$(document).on('click', '#calculateRedeemPointsFromAmountBtn', function (e) {
    var amount_to_redeem = $('#amount_to_redeem').val();
    var total_points = $('#total_points').val();
    var original_total_rent_after_discount_on_promo = $('#original_total_rent_after_discount_on_promo').val();
    if (lang == 'arb') {
        var currency = 'ريال سعودي';
    } else {
        var currency = 'SAR';
    }
    if (amount_to_redeem == '')
    {
        $('.responseTitle').html('Error');
        $('.responseMsg').html('Please enter a valid amount to redeem.');
        $('#openMsgPopupNoRedirect').click();
    }else{
        var url = lang_base_url + '/calculateRedeemPointsFromAmount';
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: url,
            dataType: "json",
            data: {total_points: total_points, amount_to_redeem: amount_to_redeem},
            success: function (response) {
                if (response.status == false)
                {
                    $('.responseTitle').html('Error');
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                    $('#total_rent_after_discount_on_promo').val(parseFloat(original_total_rent_after_discount_on_promo).toFixed(2));
                    $('#showTotalAmount').html(parseFloat(original_total_rent_after_discount_on_promo).toFixed(2));
                    $('.containsRedeemDiscount').hide();
                } else if (response.status == true) // if redeem applied successfully
                {
                    //$('#redeemSection').hide();
                    //$('#calculateRedeemPointsFromAmountBtn').attr('disabled', true);
                    var total_redeemed_amount = response.total_redeemed_amount; // coming in response from controller
                    var total_rent_after_discount_on_promo = $('#total_rent_after_discount_on_promo').val();
                    var total_rent_after_discount_on_promo_minus_redeem = total_rent_after_discount_on_promo-total_redeemed_amount;
                    $('#total_rent_after_discount_on_promo').val(parseFloat(total_rent_after_discount_on_promo_minus_redeem).toFixed(2));
                    $('#showTotalAmount').html(parseFloat(total_rent_after_discount_on_promo_minus_redeem).toFixed(2));
                    $('.containsRedeemDiscount').show();
                }
                $('#showPointsAgainstAmount').html('You can redeem <strong>'+response.total_redeemed_points+'</strong> points against entered amount.');
                $('#redeem_points_used').val(response.total_redeemed_points);
                $('#discount_on_redeem').html(response.total_redeemed_amount+' '+currency);
                $('#redeem_discount_availed').val(response.total_redeemed_amount);
                $('.loaderSpiner').hide();
            }
        });
    }
});*/

// For Applying redeem offer, get amount from points
/*$(document).on('click', '#calculateRedeemAmountFromPointsBtn', function (e) {
    var points_to_redeem = $('#points_to_redeem').val();
    var total_points = $('#total_points').val();
    var original_total_rent_after_discount_on_promo = $('#original_total_rent_after_discount_on_promo').val();
    if (lang == 'arb') {
        var currency = 'ريال سعودي';
    } else {
        var currency = 'SAR';
    }
    if (points_to_redeem == '')
    {
        $('.responseTitle').html('Error');
        $('.responseMsg').html('Please enter valid number of points to redeem.');
        $('#openMsgPopupNoRedirect').click();
    }else{
        var url = lang_base_url + '/calculateRedeemAmountFromPoints';
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: url,
            dataType: "json",
            data: {total_points: total_points, points_to_redeem: points_to_redeem},
            success: function (response) {
                if (response.status == false)
                {
                    $('.responseTitle').html('Error');
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                    $('#total_rent_after_discount_on_promo').val(parseFloat(original_total_rent_after_discount_on_promo).toFixed(2));
                    $('#showTotalAmount').html(parseFloat(original_total_rent_after_discount_on_promo).toFixed(2));
                    $('.containsRedeemDiscount').hide();
                } else if (response.status == true) // if redeem applied successfully
                {
                    //$('#redeemSection').hide();
                    //$('#calculateRedeemAmountFromPointsBtn').attr('disabled', true);
                    var total_redeemed_amount = response.total_redeemed_amount; // coming in response from controller
                    var total_rent_after_discount_on_promo = $('#total_rent_after_discount_on_promo').val();
                    var total_rent_after_discount_on_promo_minus_redeem = total_rent_after_discount_on_promo-total_redeemed_amount;
                    $('#total_rent_after_discount_on_promo').val(parseFloat(total_rent_after_discount_on_promo_minus_redeem).toFixed(2));
                    $('#showTotalAmount').html(parseFloat(total_rent_after_discount_on_promo_minus_redeem).toFixed(2));
                    $('.containsRedeemDiscount').show();
                }
                $('#showAmountAgainstPoints').html('You can redeem <strong>'+response.total_redeemed_amount+'</strong> SAR against entered points.');
                $('#redeem_points_used').val(response.total_redeemed_points);
                $('#discount_on_redeem').html(response.total_redeemed_amount+' '+currency);
                $('#redeem_discount_availed').val(response.total_redeemed_amount);
                $('.loaderSpiner').hide();
            }
        });
    }
});*/

$(document).on('keydown', '.amount_or_points', function (event) {
    var type = $(this).attr('id'); // amount_to_redeem, points_to_redeem
    if (type == 'amount_to_redeem') {
        $('#conversion_type').val('amount_to_points');
        $('#points_to_redeem').val('');
    } else if (type == 'points_to_redeem') {
        $('#conversion_type').val('points_to_amount');
        $('#amount_to_redeem').val('');
    }
});

//on keyup, start the countdown
$('.amount_or_points').on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(calculateRedeem, doneTypingInterval);
});

function calculateRedeem() {
    var url;
    var value_to_convert;
    var conversion_type = $('#conversion_type').val(); // amount_to_points, points_to_amount
    var total_points = $('#total_points').val();
    var customer_redeem_loyalty_type = $('#customer_redeem_loyalty_type').val();
    var original_total_rent_after_discount_on_promo = $('#original_total_rent_after_discount_on_promo').val();
    var currency = (lang == 'arb' ? 'ريال سعودي' : 'SAR');
    if (conversion_type == 'amount_to_points') {
        value_to_convert = $('#amount_to_redeem').val();
        url = lang_base_url + '/calculateRedeemPointsFromAmount';
    } else if (conversion_type == 'points_to_amount') {
        value_to_convert = $('#points_to_redeem').val();
        url = lang_base_url + '/calculateRedeemAmountFromPoints';
    }
    if (value_to_convert == '') {
        $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
        $('.responseMsg').html((lang == 'eng' ? 'Please provide valid input to redeem.' : 'الرجاء إدخال قيمة صحيحة ليتم الحساب'));
        $('#openMsgPopupNoRedirect').click();
    } else {
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: url,
            dataType: "json",
            data: {
                total_points: total_points,
                value_to_convert: value_to_convert,
                conversion_type: conversion_type,
                customer_redeem_loyalty_type: customer_redeem_loyalty_type
            },
            success: function (response) {
                if (response.status == false) {
                    $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                    $('#applyRedeemBtn').hide();
                } else if (response.status == true) // if redeem applied successfully
                {
                    $('#applyRedeemBtn').show();
                }
                $('.loaderSpiner').hide();
                if (conversion_type == 'amount_to_points') {
                    $('#points_to_redeem').val(response.total_redeemed_points);
                } else if (conversion_type == 'points_to_amount') {
                    $('#amount_to_redeem').val(response.total_redeemed_amount);
                }
            }
        });
    }
}

$(document).on('click', '#calculateRedeemBtn', function (e) {
    var url;
    var value_to_convert;
    var conversion_type = $('#conversion_type').val(); // amount_to_points, points_to_amount
    var total_points = $('#total_points').val();
    var customer_redeem_loyalty_type = $('#customer_redeem_loyalty_type').val();
    var original_total_rent_after_discount_on_promo = $('#original_total_rent_after_discount_on_promo').val();
    var currency = (lang == 'arb' ? 'ريال سعودي' : 'SAR');
    if (conversion_type == 'amount_to_points') {
        value_to_convert = $('#amount_to_redeem').val();
        url = lang_base_url + '/calculateRedeemPointsFromAmount';
    } else if (conversion_type == 'points_to_amount') {
        value_to_convert = $('#points_to_redeem').val();
        url = lang_base_url + '/calculateRedeemAmountFromPoints';
    }
    if (value_to_convert == '') {
        $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
        $('.responseMsg').html((lang == 'eng' ? 'Please provide valid input to redeem.' : 'الرجاء إدخال قيمة صحيحة ليتم الحساب'));
        $('#openMsgPopupNoRedirect').click();
    } else {
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: url,
            dataType: "json",
            data: {
                total_points: total_points,
                value_to_convert: value_to_convert,
                conversion_type: conversion_type,
                customer_redeem_loyalty_type: customer_redeem_loyalty_type
            },
            success: function (response) {
                if (response.status == false) {
                    $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                    $('#applyRedeemBtn').hide();
                } else if (response.status == true) // if redeem applied successfully
                {
                    $('#applyRedeemBtn').show();
                }
                $('.loaderSpiner').hide();
                if (conversion_type == 'amount_to_points') {
                    $('#points_to_redeem').val(response.total_redeemed_points);
                } else if (conversion_type == 'points_to_amount') {
                    $('#amount_to_redeem').val(response.total_redeemed_amount);
                }
            }
        });
    }
});

$(document).on('click', '#applyRedeemBtn', function (e) {
    var url;
    var value_to_convert;
    var conversion_type = $('#conversion_type').val(); // amount_to_points, points_to_amount
    var total_points = $('#total_points').val();
    var customer_redeem_loyalty_type = $('#customer_redeem_loyalty_type').val();
    var original_total_rent_after_discount_on_promo = $('#original_total_rent_after_discount_on_promo').val();
    var total_field_amount = $('#total_amount').html();
    var vat_field_amount_old = $('#show_vat_applied').html();
    total_field_amount = total_field_amount.replace(/,/g, "");
    vat_field_amount_old = vat_field_amount_old.replace(/,/g, "");
    var payable_amount = $('#payable_amount').val();
    var currency = (lang == 'arb' ? 'ريال سعودي' : 'SAR');
    if (conversion_type == 'amount_to_points') {
        value_to_convert = $('#amount_to_redeem').val();
        url = lang_base_url + '/calculateRedeemPointsFromAmount';
    } else if (conversion_type == 'points_to_amount') {
        value_to_convert = $('#points_to_redeem').val();
        url = lang_base_url + '/calculateRedeemAmountFromPoints';
    }
    if (value_to_convert == '') {
        $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
        $('.responseMsg').html((lang == 'eng' ? 'Please provide valid input to redeem.' : 'الرجاء إدخال قيمة صحيحة ليتم الحساب'));
        $('#openMsgPopupNoRedirect').click();
    } else {
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: url,
            dataType: "json",
            data: {
                total_points: total_points,
                value_to_convert: value_to_convert,
                conversion_type: conversion_type,
                customer_redeem_loyalty_type: customer_redeem_loyalty_type
            },
            success: function (response) {
                if (response.status == false) {
                    $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                    $('#total_rent_after_discount_on_promo').val(parseFloat(original_total_rent_after_discount_on_promo).toFixed(2));
                    $('#showTotalAmount').html(parseFloat(original_total_rent_after_discount_on_promo).toFixed(2));
                    $('.containsRedeemDiscount').hide();
                    $('#applyRedeemBtn').hide();
                    $('#all_ok_to_redeem').val('no');
                } else if (response.status == true) // if redeem applied successfully
                {
                    var total_redeemed_amount = response.total_redeemed_amount; // coming in response from controller
                    var total_rent_after_discount_on_promo = $('#total_rent_after_discount_on_promo').val();
                    var total_rent_after_discount_on_promo_minus_redeem = total_rent_after_discount_on_promo - total_redeemed_amount;
                    $('#total_rent_after_discount_on_promo').val(parseFloat(total_rent_after_discount_on_promo_minus_redeem).toFixed(2));

                    $('.containsRedeemDiscount').show();
                    $('#applyRedeemBtn').show();
                    $('#all_ok_to_redeem').val('yes');
                    $('.redeemApplySection').addClass('active');
                    $('.payment_options_area_div').find('input[type="button"]').hide();
                    $('.promoCodeSection').hide(); // When redeem applied successfully, don't show coupon section
                    // calcualte vat on new amount and update #show_vat_applied field
                    // var vat_applied_new_val = (15 / 100) * (parseFloat(total_field_amount - response.total_redeemed_amount).toFixed(2));
                    // $('#show_vat_applied').html(parseFloat(vat_applied_new_val).toFixed(2));
                    // var difference_between_old_and_new_vat = vat_field_amount_old - vat_applied_new_val;
                    $('#showTotalAmount').html(parseFloat(payable_amount - total_redeemed_amount).toFixed(2));
                }
                $('#redeem_points_used').val(response.total_redeemed_points);
                $('#discount_on_redeem').html(response.total_redeemed_amount + ' ' + currency);
                // $('#total_amount').html(parseFloat(total_field_amount - response.total_redeemed_amount).toFixed(2));
                $('#redeem_discount_availed').val(response.total_redeemed_amount);
                $('.loaderSpiner').hide();
                if (conversion_type == 'amount_to_points') {
                    $('#points_to_redeem').val(response.total_redeemed_points);
                } else if (conversion_type == 'points_to_amount') {
                    $('#amount_to_redeem').val(response.total_redeemed_amount);
                }
            }
        });
    }

    /*for walkin registeration*/
    $(".walkin_register").click(function () {
        $(this).attr('disabled', true);
    });

});
/*start STS payment form validation*/
$(document).on('submit', '#stsPayOne', function () {
    if (validationSts()) {
        return true;
    } else {
        return false;
    }
});

function validationSts() {
    $('form input').attr("data-original-title", "");

    var owner = $('#owner').val();
    var cvv = $('#cvv').val();
    var cardNumber = $('#cardNumber').val();

    if (lang === 'eng') {
        var required_msg = "Please fill out this field.";
        var card_msg = "Please enter your valid credit card number.";
        var cvv_msg = "Please enter your valid cvv number";
    } else {
        var required_msg = "الرجاء تعبئة الخانة";
        var card_msg = "الرجاء ادخال رقم بطاقة صحيح";
        var cvv_msg = "الرجاء إدخال رمز تحقق صحيح";
    }
    if (cvv !== '' && !isCvvValid(cvv)) {

        show_bs_tooltip($('#cvv'), cvv_msg);
        $('#cvv').focus();

    } else if (cardNumber !== '' && !isCardValid(cardNumber)) {

        show_bs_tooltip($('#cardNumber'), card_msg);
        $('#cardNumber').focus();

    } else if (!owner) {

        show_bs_tooltip($('#owner'), required_msg);
        $('#owner').focus();

    } else if (!cardNumber) {

        show_bs_tooltip($('#cardNumber'), required_msg);
        $('#cardNumber').focus();

    } else if (!cvv) {

        show_bs_tooltip($('#cvv'), required_msg);
        $('#cvv').focus();

    } else if (!check_session()) {

        return false;

    } else {
        return true;
    }
}

function check_session() {
    var session = false;
    var booking_id = $('#hdn_booking_id').val();
    $('.loaderSpiner').show();
    $.ajax({
        type: 'POST',
        data: {booking_id: booking_id},
        dataType: "json",
        url: lang_base_url + '/checkSessionBeforePayment?b_id=' + booking_id,
        cache: false,
        async: false,
        success: function (response) {
            if (response.status === false) {
                $('.loaderSpiner').hide();
                $('#sessionConflictLabel').text(response.title);
                $('.sessionConflictDesc').text(response.message);
                $('#paymentSessionConflict').modal('show');
                session = false;
            } else {
                $('.loaderSpiner').hide();
                $('#paymentSessionConflict').modal('hide');
                session = true;
            }
        }
    });
    return session;
}

function isCvvValid(userInput) {
    var c = userInput;
    if (/^[0-9]{1,12}$/.test(c) && c.length === 3)
        return true;
    else
        return false;
}

function isCardValid(number) {
    var regex = new RegExp("^[0-9]{13,16}$");
    if (!regex.test(number))
        return false;

    return true;
}

$('.redirectToHome').on('click', function () {
    window.location.href = lang_base_url;
});
/*end STS payment form validation*/

/*Start Human less functions*/
$('.show_wiz1').on('click', function () {
    //$('.loaderSpiner').show();
    $('#get_car').removeClass('visited');
    $('#get_car').addClass('active');
    $('#select_car').removeClass('active');
    $('#select_car').removeClass('visited');
    $('.wizard-1').show();
    $('.wizard-2').hide();
});

$('.show_wiz2').on('click', function () {
    $('.loaderSpiner').show();
    $('.plate-list').html('');
    var booking_id = $('#booking_id').val();
    var branchCode = $('#branchCode').val();
    var carType = $('#carType').val();
    var carModel = $('#carModel').val();

    $.ajax({
        type: "POST",
        dataType: "json",
        data: {booking_id: booking_id, branchCode: branchCode, carType: carType, carModel: carModel},
        url: lang_base_url + '/getCarPlates',
        success: function (response) {

            if (response.status === true) {
                $('.plate-list').html(response.results);
                $('#carPlate_no').val('');
                $('#get_car').removeClass('active');
                $('#get_car').addClass('visited');
                $('#select_car').removeClass('visited');
                $('#select_car').addClass('active');
                $('#inspection_car').removeClass('active');
                $('#inspection_car').removeClass('visited');
                $('.wizard-1').hide();
                $('.wizard-2').show();
                $('.wizard-3').hide();
                $('.loaderSpiner').hide();
            } else {
                $('.response_msg').text(response.message);
                $('.loaderSpiner').hide();
                $('.humanLessPopup').click();
            }

        }
    });
});

$('.show_wizard_2_1').on('click', function () {
    $('.loaderSpiner').show();
    var booking_id = $('#booking_id').val();
    var branch_code = $('#branchCode').val();
    var car_type = $('#carType').val();

    $.ajax({
        type: "POST",
        dataType: "json",
        data: {booking_id: booking_id, branch_code: branch_code, car_type: car_type},
        url: lang_base_url + '/changeCar',
        success: function (response) {

            if (response.status === true) {
                $('.upgradedCars').html(response.results);
                $('#select_car').addClass('active');
                $('#inspection_car').removeClass('active');
                $('.wizard-1').hide();
                $('.wizard-2').hide();
                $('.wizard_2_1').show();
                $('.wizard-3').hide();
                $('.loaderSpiner').hide();
            } else {
                $('.response_msg').text(response.message);
                $('.loaderSpiner').hide();
                $('.humanLessPopup').click();
            }

        }
    });

});

function upgradeCar(carType, carModel, carName, carImage, rentPerDay, newCDWPrice) {
    $('.loaderSpiner').show();
    $('.plate-list').html('');
    var booking_id = $('#booking_id').val();
    var branchCode = $('#branchCode').val();
    $('#changedPrice').val(rentPerDay);
    $('#changedCdw').val(newCDWPrice);
    var carImageTag = '<img src="' + carImage + '" alt="Car" width="274" height="132">';

    $.ajax({
        type: "POST",
        dataType: "json",
        data: {booking_id: booking_id, branchCode: branchCode, carType: carType, carModel: carModel},
        url: lang_base_url + '/getCarPlates',
        success: function (response) {

            if (response.status === true) {
                $('.plate-list').html(response.results);
                $('#carPlate_no').val('');
                $('#get_car').removeClass('active');
                $('#get_car').addClass('visited');
                $('#select_car').removeClass('visited');
                $('#select_car').addClass('active');
                $('#inspection_car').removeClass('active');
                $('#inspection_car').removeClass('visited');
                $('.wizard-1').hide();
                $('.wizard-2').show();
                $('.wizard-3').hide();
                $('.wizard_2_1').hide();
                $('.unlock1').html(carImageTag);
                $('.unlock2').html(carImageTag);
                $('#carUp').text(carName);
                $('#carUpTitle').text(carName);
                $('#carType').val(carType);
                $('#carModel').val(carModel);
                $('.loaderSpiner').hide();
            } else {
                $('.response_msg').text(response.message);
                $('.loaderSpiner').hide();
                $('.humanLessPopup').click();
            }

        }
    });
}

$('.back_to_wiz2').on('click', function () {
    $('#carPlate_no').val('');
    $('.wizard-2').show();
    $('.wizard_2_1').hide();
});

$('.select_back_to_wiz2').on('click', function () {
    var car_id = $(this).data('car_id');
    $('.loaderSpiner').show();
    //$('.plate-list').html('');
    //var booking_id = $('#booking_id').val();
    //show popup for extra payment when there is difference btw new price and old hidden price
    //$('.extraPaymentPopup').show();
    /*$.ajax({
        type: "POST",
        dataType: "json",
        data: {booking_id:booking_id},
        url: lang_base_url+'/getCarPlates',
        success: function (response) {

            if (response.status === true) {
                $('.plate-list').html(response.results);
                $('#carPlate_no').val('');
                $('#get_car').removeClass('active');
                $('#get_car').addClass('visited');
                $('#select_car').removeClass('visited');
                $('#select_car').addClass('active');
                $('#inspection_car').removeClass('active');
                $('#inspection_car').removeClass('visited');
                $('.wizard-1').hide();
                $('.wizard-2').show();
                $('.wizard-3').hide();
                $('.loaderSpiner').hide();
            }else{
                $('.response_msg').text(response.message);
                $('.loaderSpiner').hide();
                $('.humanLessPopup').click();
            }

        }
    });*/
});

$('.show_wiz3').on('click', function () {
    var getPlateNo = $('#carPlate_no').val();
    var booking_id = $('#booking_id').val();

    if (getPlateNo === '') {
        $('.response_msg').text('Please select car plate to proceed.');
        $('.humanLessPopup').click();
    } else {
        selectCarPlate(booking_id, getPlateNo);
    }
});

function selectCarPlate(booking_id, plate_no) {
    $('.loaderSpiner').show();
    $.ajax({
        type: "POST",
        dataType: "json",
        data: {plate_no: plate_no, booking_id: booking_id},
        url: lang_base_url + '/getCarInfo',
        success: function (response) {

            if (response.status === true) {
                $('#select_car').removeClass('active');
                $('#select_car').addClass('visited');
                $('#inspection_car').removeClass('visited');
                $('#inspection_car').addClass('active');
                $('#tam_issue').removeClass('visited');
                $('#tam_issue').removeClass('active');
                $('.wizard-1').hide();
                $('.wizard-2').hide();
                $('.wizard-3').show();
                $('.wizard-4').hide();
                $('.fuelHtml').html(response.fuel_html);
                $('#lastFuelTank').val(response.lastFuelTank);
                $('#lastKm').val(response.lastKm);
                $('#km-value').text(response.lastKm);
                $('#vehicleID').val(response.vehicleId);
                $('.loaderSpiner').hide();
            } else {
                $('.loaderSpiner').hide();
                $('.response_msg').text(response.message);
                $('.humanLessPopup').click();
            }

        }
    });
}

$('.show_wiz4').on('click', function () {
    show_wiz4();
});

$('.back_to_insp').on('click', function () {
    step3_inspection();
});

function step3_inspection() {
    $('#inspection_car').removeClass('visited');
    $('#inspection_car').addClass('active');
    $('#tam_issue').removeClass('active');
    $('.wizard-1').hide();
    $('.wizard-2').hide();
    $('.wizard-3').show();
    $('.wizard-4').hide();
    $('.wizard-otp').hide();
}

//also using in pick-up inspection response
function show_wiz4() {
    $('.loaderSpiner').show();
    var booking_id = $('#booking_id').val();
    $('#inspection_car').addClass('visited');
    $('#inspection_car').removeClass('active');
    $('#tam_issue').addClass('active');

    /*For local testing*/
    //$('.wizard-1').hide();
    //$('.wizard-2').hide();
    //$('.wizard-3').hide();
    //$('.wizard-otp').hide();
    //$('.wizard-4').show();
    //$('.wizard-unlock').show();
    /*For local testing*/

    $.ajax({
        type: "POST",
        dataType: "json",
        data: {booking_id: booking_id},
        url: lang_base_url + '/issueTammOTP',
        success: function (response) {

            if (response.status === true) {
                $('#correlation_id').val(response.correlation_id);
                $('.wizard-1').hide();
                $('.wizard-2').hide();
                $('.wizard-3').hide();
                $('.wizard-4').hide();
                $('.wizard-otp').show();
                $('.loaderSpiner').hide();

            } else if (response.status === false && response.returnCode === 'invalidID') {
                $('.wizard-3').hide();
                $('.wizard-4').hide();
                $('.wizard-otp').hide();
                $('.wizard-ID').show();
                $('.loaderSpiner').hide();
            } else {
                $('.response_msg').text(response.message);
                $('.loaderSpiner').hide();
                $('.humanLessPopup').click();
            }

        }
    });
}

$('.btnIssueTamm').on('click', function () {
    var booking_id = $('#booking_id').val();
    var id_no = $('#id_no').val();

    if (id_no === '') {
        $('.response_msg').text('Please enter your valid National ID to proceed.');
        $('.humanLessPopup').click();
    } else {
        $('.loaderSpiner').show();
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {booking_id: booking_id, id_no: id_no},
            url: lang_base_url + '/issueTammOTP',
            success: function (response) {

                if (response.status === true) {
                    $('#correlation_id').val(response.correlation_id);
                    $('.wizard-4').hide();
                    $('.wizard-otp').show();
                    $('.loaderSpiner').hide();

                } else {
                    $('.response_msg').text(response.message);
                    $('.loaderSpiner').hide();
                    $('.humanLessPopup').click();
                }

            }
        });
    }

});

$('.btnTammAuth').on('click', function () {
    var booking_id = $('#booking_id').val();
    var id_no = $('#id_no').val();
    var tamm_otp = $('#tamm_otp').val();
    var vehicle_id = $('#vehicleID').val();
    var correlation_id = $('#correlation_id').val();
    var id_version = $('#id_version').val();
    var changedPrice = $('#changedPrice').val();
    var changedCdw = $('#changedCdw').val();

    if (tamm_otp === '') {
        $('.response_msg').text('Please enter OTP to proceed.');
        $('.humanLessPopup').click();
    } else {
        $('.loaderSpiner').show();
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                id_no: id_no,
                tamm_otp: tamm_otp,
                booking_id: booking_id,
                vehicle_id: vehicle_id,
                correlation_id: correlation_id,
                id_version: id_version,
                changedPrice: changedPrice,
                changedCdw: changedCdw
            },
            url: lang_base_url + '/issueTammAuth',
            success: function (response) {

                if (response.status === true) {
                    $('.unlock1').hide();
                    $('.wizard-otp').hide();
                    $('.wizard-id-version').hide();
                    $('.wizard-4').hide();
                    $('.wizard-unlock').show();
                    $('.loaderSpiner').hide();
                } else if (response.status === false && response.returnCode === 'invalidID') {
                    $('#id_no').val('');
                    $('.wizard-3').hide();
                    $('.wizard-otp').hide();
                    $('.wizard-4').show();
                    $('.response_msg').text(response.message);
                    $('.loaderSpiner').hide();
                    $('.humanLessPopup').click();
                } else if (response.status === false && response.returnCode === 'invalidIDVersion') {
                    $('#id_version').val('');
                    $('.wizard-3').hide();
                    $('.wizard-4').hide();
                    $('.wizard-otp').hide();
                    $('.wizard-id-version').show();
                    $('.response_msg').text(response.message);
                    $('.loaderSpiner').hide();
                    $('.humanLessPopup').click();
                } else {
                    $('.response_msg').text(response.message);
                    $('.loaderSpiner').hide();
                    $('.humanLessPopup').click();
                }

            }
        });
    }

});

$('.btnUnlockCar').on('click', function () {
    //$('.loaderSpiner').show();
    $('#unLockCarModal').modal('show');
    var booking_id = $('#booking_id').val();
    var vehicleID = $('#vehicleID').val();

    $.ajax({
        type: "POST",
        dataType: "json",
        data: {booking_id: booking_id, vehicleID: vehicleID},
        url: lang_base_url + '/unlockCar',
        success: function (response) {
            if (response.status === true) {
                $('.unlock1').hide();
                $('.wizard-unlock').hide();
                $('.getCarSteps').hide();
                $('.wizard-done').show();
                $('#unLockCarModal').modal('hide');
                $('.loaderSpiner').hide();
            } else {
                $('.response_msg').text(response.message);
                $('#unLockCarModal').modal('hide');
                $('.loaderSpiner').hide();
                $('.humanLessPopup').click();
            }
        }
    });
});

$('.saveInspection-bk').on('click', function () {
    var booking_id = $('#booking_id').val();
    var getPlateNo = $('#carPlate_no').val();
    var lastFuelTank = $('#lastFuelTank').val();
    var lastKm = $('#lastKm').val();

    $('.loaderSpiner').show();
    $.ajax({
        type: "POST",
        dataType: "json",
        data: {getPlateNo: getPlateNo, booking_id: booking_id, lastKm: lastKm, lastFuelTank: lastFuelTank},
        url: lang_base_url + '/pickUpInspection',
        success: function (response) {

            if (response.status === true) {

                $('#showCarInspection').modal('hide');
                $('.loaderSpiner').hide();

            } else {
                $('#showCarInspection').modal('hide');
                $('.response_msg').text(response.message);
                $('.loaderSpiner').hide();
                $('.humanLessPopup').click();
            }

        }
    });
});

/*$(document).on('click', '.carPlate', function (e) {
    e.stopPropagation();
    e.preventDefault();
    $('.plateLable').removeClass('selected');
    var plate_no = '';
    plate_no = $(this).val();
    //alert(plate_no);
    $(this).next().addClass('selected');
    $('#carPlate_no').val(plate_no);
});*/

$(document).on('click', '.plateLable', function (e) {
    e.stopPropagation();
    e.preventDefault();
    $('.plateLable').removeClass('selected');
    var plate_no = '';
    plate_no = $(this).siblings('input').val();
    //alert(plate_no);
    $(this).addClass('selected');
    $('#carPlate_no').val(plate_no);
});

$(document).on('click', '#drawBtn', function (e) {
    e.preventDefault();
    $(".popupDiv").fadeIn("slow");
});

$(document).on('click', '.okToWait', function (e) {
    e.preventDefault();
    $('.loaderSpiner').show();
});

$(document).on('click', '.close-inspection-popup', function (e) {
    e.preventDefault();
    $(".popupDiv").fadeOut("slow");
});

//start end trip functions
$('.show_et_wiz1').on('click', function () {
    $('#endTrip_inspection').addClass('visited');
    $('#endTrip_inspection').addClass('active');
    $('#endTrip_payment').removeClass('visited');
    $('#endTrip_payment').removeClass('active');
    $('.et-wizard-1').show();
    $('.et-wizard-2').hide();
    $('.alert-danger').hide();
});
$('.show_et_wiz2').on('click', function () {
    var booking_id = $('#booking_id').val();
    var contract_no = $('#oasis_contract_no').val();
    var closing_branch = $('#closing_branch').val();
    var kmIn = $('#kmIn').val();
    var actual_km = $('#actualkm').val();
    var fuelTankIn = $('#fuelTankIn').val();
    var btn_text = '';
    var km_text = '';
    if (lang === 'eng') {
        btn_text = 'Next';
        km_text = 'Please increase km value';
    } else {
        btn_text = 'التالي';
        km_text = 'الرجاء إضافة ال كم';
    }

    if (compareKmVal(actual_km, kmIn)) {
        $('.loaderSpiner').show();
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                booking_id: booking_id,
                contract_no: contract_no,
                closing_branch: closing_branch,
                kmIn: kmIn,
                fuelTankIn: fuelTankIn
            },
            url: lang_base_url + '/getContractBalance',
            success: function (response) {

                if (response.status === true) {
                    if ($.isNumeric(response.balance)) {
                        $('#endTrip_inspection').addClass('visited');
                        $('#endTrip_inspection').removeClass('active');
                        $('#endTrip_payment').addClass('active');
                        $('#contractBalance').val(response.balance);
                        $('#kmIn').val(response.kmIn);
                        $('#fuelTankIn').val(response.fuelTankIn);
                        $('#stsForm').html(response.formHtml);
                        $('.et-wizard-1').hide();
                        $('.et-wizard-2').show();
                        $('.et-wizard-3').hide();
                        $('.loaderSpiner').hide();
                        if (parseFloat(response.balance) < 0) {
                            $('#skip_or_next').addClass('show_et_wiz3_2');
                            $('#skip_or_next').text(btn_text);
                            $('#skip_or_next').css("padding", "15px 20px");
                        } else if (parseFloat(response.balance) > 0) {
                            //$('#showPayExtras').modal('show');
                            $('#skip_or_next').attr('data-toggle', 'modal');
                            $('#skip_or_next').attr('data-target', '#showPayExtras');
                        }
                    } else {
                        $('.response_msg').text(response.balance);
                        $('.loaderSpiner').hide();
                        $('.humanLessPopup').click();
                    }

                } else {
                    $('.response_msg').text(response.message);
                    $('.loaderSpiner').hide();
                    $('.humanLessPopup').click();
                }
            }
        });
    } else {
        $('.response_msg').text(km_text);
        $('.loaderSpiner').hide();
        $('.humanLessPopup').click();
        return false;
    }
});

$(document).on('click', '.show_et_wiz3_2', function () {
    $('#showPayExtras').modal('hide');
    $('.et-wizard-2').hide();
    $('.et-wizard-3').show();
    $('.et-wizard-4').hide();
});

$('.show_et_wiz3').on('click', function () {
    $('#showPayExtras').modal('hide');
    $('.et-wizard-2').hide();
    $('.et-wizard-3').show();
    $('.et-wizard-4').hide();
});

//function to show next step after STS payment success
$('#forceToPay').on('click', function () {
    var transReference = $('#transReference').val();
    if (transReference === '') {
        $('#forcePayment').modal('show');
        return false;
    } else {
        $('.et-wizard-2').hide();
        $('.et-wizard-3').show();
        $('.et-wizard-4').hide();
    }
});

$('.show_et_wiz4').on('click', function () {
    //$('.loaderSpiner').show();

    if (lang === 'eng') {
        var keys_msg = "Please Drop Keys Inside The Car";
        var windows_msg = "Please close the car windows";
        var doors_msg = "Please close the car doors";
    } else {
        var keys_msg = "الرجاء وضع المفتاح داخل السيارة";
        var windows_msg = "الرجاء إغلاق نوافذ السيارة";
        var doors_msg = "الرجاء إغلاق أبواب السيارة";
    }

    if (!$('#drop_keys').is(':checked')) {
        $('.response_msg').text(keys_msg);
        $('.loaderSpiner').hide();
        $('.humanLessPopup').click();
    } else if (!$('#car_windows').is(':checked')) {
        $('.response_msg').text(windows_msg);
        $('.loaderSpiner').hide();
        $('.humanLessPopup').click();
    } else if (!$('#close_doors').is(':checked')) {
        $('.response_msg').text(doors_msg);
        $('.loaderSpiner').hide();
        $('.humanLessPopup').click();
    } else {
        $('#lockCarModal').modal('show');
        var booking_id = $('#booking_id').val();
        var contract_no = $('#oasis_contract_no').val();
        var closing_branch = $('#closing_branch').val();
        var kmIn = $('#kmIn').val();
        var fuelTankIn = $('#fuelTankIn').val();
        var plate_no = $('#plate_no').val();
        var tammStatus = $('#tammStatus').val();
        var closeType = $('#closeType').val();
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                booking_id: booking_id,
                contract_no: contract_no,
                closing_branch: closing_branch,
                kmIn: kmIn,
                fuelTankIn: fuelTankIn,
                tammStatus: tammStatus,
                closeType: closeType
            },
            url: lang_base_url + '/closeContract',
            success: function (response) {

                if (response.status === true) {
                    $('.unlock1').hide();
                    $('.et-wizard-3').hide();
                    $('.et-wizard-4').show();
                    $('.loaderSpiner').hide();
                    $('#lockCarModal').modal('hide');
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {booking_id: booking_id, plate_no: plate_no},
                        url: lang_base_url + '/cancelTammAuth',
                        success: function (response) {
                            if (response.status === true) {
                                $('.et-wizard-4').hide();
                                $('.et-wizard-tamm').show();
                            }
                        }
                    });
                } else {
                    $('.response_msg').text(response.message);
                    $('.loaderSpiner').hide();
                    $('#lockCarModal').modal('hide');
                    $('.humanLessPopup').click();
                }

            }
        });
    }

});

$("#kmValue").change(function (event) {
    event.preventDefault();
    var km_pickup_value = $(this).attr('data-pickupValue');
    var km_value = $(this).val();
    if (compareKmVal(km_pickup_value, km_value)) {
        $("#kmValue").val(km_value);
        $("#kmIn").val(km_value);
    } else {
        $("#kmValue").val(km_pickup_value);
        $("#kmIn").val(km_pickup_value);
    }
});

function compareKmVal(actualVal, newVal) {
    var compare = false;
    if (parseInt(newVal) > parseInt(actualVal)) {
        compare = true;
    }
    return compare;
}

$(function () {
    $(".numbers-row").append('<div class="inc button">+</div><div class="dec button">-</div>');
    $(".button").on("click", function () {
        var $button = $(this);
        var numberFormat = '';
        var actualValue = $('#actualkm').val();
        var oldValue = $button.parent().find("input").val();
        if ($button.text() === "+") {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            // Don't allow decrementing below zero
            if (oldValue > 0 && actualValue < oldValue) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = parseFloat(actualValue);
            }
        }
        //numberFormat = $('#mynum').number(newVal);
        $("#kmIn").val(newVal);
        $("#kmValue").val(newVal);
    });
});
var sheet = document.createElement('style'),
    $rangeInput = $('.range1 input'),
    prefs = ['webkit-slider-runnable-track', 'moz-range-track', 'ms-track'];

document.body.appendChild(sheet);

var getTrackStyle = function (el) {
    var curVal = el.value,
        val = (curVal - 1) * 14.333333334,
        style = '';

    // Set active label
    $('.range-labels li').removeClass('active selected');

    var curLabel = $('.range-labels').find('li:nth-child(' + curVal + ')');

    curLabel.addClass('active selected');
    curLabel.prevAll().addClass('selected');

    // Change background gradient
    for (var i = 0; i < prefs.length; i++) {
        style += '.range1 {background: linear-gradient(to right, #F4AA22 0%, #F4AA22 ' + val + '%, #fff ' + val + '%, #fff 100%)}';
        style += '.range1 input::-' + prefs[i] + '{background: linear-gradient(to right, #F4AA22 0%, #F4AA22 ' + val + '%, #b2b2b2 ' + val + '%, #b2b2b2 100%)}';
    }

    return style;
};

$rangeInput.on('input', function () {
    sheet.textContent = getTrackStyle(this);
});

// Change input value on label click
$('.range-labels li').on('click', function () {
    var index = $(this).index();
    var selText = $(this).text();
    $('#fuelTankIn').val(selText);
    $rangeInput.val(index + 1).trigger('input');

});

//check alphanumeric string
function checkAlphanumericString(string) {
    //regex for alphanumeric string without spaces.
    if (string.match(/^[a-zA-Z0-9]*$/)) {
        return true;
    } else {
        return false;
    }
}

$(function () {
    $("#fuel_tank").slider({
        range: "min",
        value: $("#fuelTankIn").val(),
        min: 0,
        max: 8,
        slide: function (event, ui) {
            $("#fuelTankIn").val(ui.value);
        }
    });
    $("#fuelTankIn").val($("#fuel_tank").slider("value"));
});
/*End Human less functions*/

$(function () {
    $.widget("custom.combobox", {
        _create: function () {
            this.wrapper = $("<span>")
                .addClass("custom-combobox")
                .insertAfter(this.element);

            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        },

        _createAutocomplete: function () {
            var selected = this.element.children(":selected"),
                value = selected.val() ? selected.text() : "";

            this.input = $("<input>")
                .appendTo(this.wrapper)
                .val(value)
                .attr("title", "")
                .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
                .autocomplete({
                    delay: 0,
                    minLength: 0,
                    source: $.proxy(this, "_source")
                })
                .tooltip({
                    classes: {
                        "ui-tooltip": "ui-state-highlight"
                    }
                });

            this._on(this.input, {
                autocompleteselect: function (event, ui) {
                    ui.item.option.selected = true;
                    this._trigger("select", event, {
                        item: ui.item.option
                    });
                },

                autocompletechange: "_removeIfInvalid"
            });
        },

        _createShowAllButton: function () {
            var input = this.input,
                wasOpen = false;

            $("<a>")
                .attr("tabIndex", -1)
                .appendTo(this.wrapper)
                .button({
                    icons: {
                        primary: "ui-icon-triangle-1-s"
                    },
                    text: false
                })
                .removeClass("ui-corner-all")
                .addClass("custom-combobox-toggle ui-corner-right")
                .on("mousedown", function () {
                    wasOpen = input.autocomplete("widget").is(":visible");
                })
                .on("click", function () {
                    input.trigger("focus");

                    // Close if already visible
                    if (wasOpen) {
                        return;
                    }

                    // Pass empty string as value to search for, displaying all results
                    input.autocomplete("search", "");
                });
        },

        _source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response(this.element.children("option").map(function () {
                var text = $(this).text();
                if (this.value && (!request.term || matcher.test(text)))
                    return {
                        label: text,
                        value: text,
                        option: this
                    };
            }));
        },

        _removeIfInvalid: function (event, ui) {

            // Selected an item, nothing to do
            if (ui.item) {
                return;
            }

            // Search for a match (case-insensitive)
            var value = this.input.val(),
                valueLowerCase = value.toLowerCase(),
                valid = false;
            this.element.children("option").each(function () {
                if ($(this).text().toLowerCase() === valueLowerCase) {
                    this.selected = valid = true;
                    return false;
                }
            });

            // Found a match, nothing to do
            if (valid) {
                return;
            }

            // Remove invalid value
            this.input
                .val("")
                .attr("title", value + " didn't match any item")
                .tooltip("open");
            this.element.val("");
            this._delay(function () {
                this.input.tooltip("close").attr("title", "");
            }, 2500);
            this.input.autocomplete("instance").term = "";
        },

        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }
    });

    // $(".combobox").combobox();
    $("#toggle").on("click", function () {
        $(".combobox").toggle();
    });

    $(document).ready(function() {
        $('#corporate_company_id').select2({
            dropdownParent: $('#corporateCompanies')
        });
    });

});

function booking_hours_diff_not_good(max_allowed_hours) {
    var title = (lang == 'eng' ? 'Error' : 'خطأ');
    var message = (lang == 'eng' ? 'You can only take this car for max ' + max_allowed_hours + ' hours.' :
        'يمكنك فقط أخذ هذه السيارة لمدة ' + max_allowed_hours + ' ساعات كحد أقصى، نرجو تعديل التاريخ/الوقت');
    $('.responseTitleForHourly').html(title);
    $('.responseMsgForHourly').html(message);
    $('#msgPopupNoRedirectForHourly').modal('show');
}

function open_time_picker()
{
    // alert();
    // open timepicker here
}

function showQitafModal() {
    $('#qitafModal').modal('show');
}

function qitafSendOTP() {
    var qitaf_mobile_number = $('#qitaf_mobile_number').val();
    if (qitaf_mobile_number == '') {
        show_bs_tooltip($('#qitaf_mobile_number'), required_message);
    } else {
        hide_bs_tooltip($('#qitaf_mobile_number'));
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/qitafSendOTP',
            dataType: "json",
            data: {qitaf_mobile_number: qitaf_mobile_number},
            success: function (response) {
                setTimeout(function () {
                    $('.loaderSpiner').hide();
                    if (response.status == true) {
                        $('#qitafModal').modal('hide');
                        $('#qitafOTPModal').modal('show');
                    } else {
                        $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                        $('.responseMsg').html(response.message);
                        $('#openMsgPopupNoRedirect').click();
                    }
                }, 500);
            }
        });
    }
}

function qitafSendRedeemRequest() {
    var qitaf_otp = $('#qitaf_otp').val();
    var qitaf_amount = $('#qitaf_amount').val();
    var qitaf_mobile_number = $('#qitaf_mobile_number').val();
    if (qitaf_otp == '' || qitaf_amount == '') {
        if (qitaf_otp == '') {
            show_bs_tooltip($('#qitaf_otp'), required_message);
        } else {
            hide_bs_tooltip($('#qitaf_otp'));
        }
        if (qitaf_amount == '') {
            show_bs_tooltip($('#qitaf_amount'), required_message);
        } else {
            hide_bs_tooltip($('#qitaf_amount'));
        }
    } else {
        hide_bs_tooltip($('#qitaf_otp'));
        hide_bs_tooltip($('#qitaf_amount'));
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/qitafRedeem',
            dataType: "json",
            data: {qitaf_otp: qitaf_otp, qitaf_amount: qitaf_amount, qitaf_mobile_number: qitaf_mobile_number},
            success: function (response) {
                setTimeout(function () {
                    $('.loaderSpiner').hide();
                    if (response.status == true) {
                        $('#qitafOTPModal').modal('hide');
                        $('.qitafArea').find('.BtnNtXT').hide();
                        $('.payFrmUserInfo.paymentMethods').find('.paymentOption.heading, .paymentOption.objects').hide();
                        // $('.payFrmUserInfo.paymentMethods').find('.paymentOption.objects').hide();
                        $('.bookNowBtn').hide();

                        $('.niqatyArea').hide();
                        $('.mokafaaArea').hide();
                        $('.anbArea').hide();
                        $('.keyArea').hide();

                        var payment_method;
                        if (response.amount_remaining) {
                            payment_method = $("#CreditCard");
                        } else {
                            payment_method = $("#cash");
                        }
                        if (payment_method.length) {
                            payment_method.click();
                            $('.bookNowBtn').val((lang == 'eng' ? 'Book Now' : 'احجز الآن'));
                            $('.bookNowBtn').show();
                        }

                        $('.qitafArea .proCdSec .qitafMsg').html(response.text_to_show);
                        $('.TotalAmountWithVatForMobileWithDays').html(response.total_payable_amount_after_qitaf);

                    } else {
                        $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                        $('.responseMsg').html(response.message);
                        $('#openMsgPopupNoRedirect').click();
                    }
                }, 500);
            }
        });
    }
}

$(document).on('click', 'input[name="payment_method"]', function(){
    var isMada = $(this).data('is-mada');
    $('#isMada').val(isMada);
});

$(document).ready(function () {
    if ($("input[name='payment_method']").length) {
        $("input[name='payment_method']:first").click();
    }
});

function edit_booking() {
    $('.loaderSpiner').show();
    var booking_id = $('.editBookingSec').find('[name="booking_id"]').val();
    var pickup_date = $('.editBookingSec').find('[name="pickup_date"]').val();
    var pickup_time = $('.editBookingSec').find('[name="pickup_time"]').val();
    $.ajax({
        type: 'POST',
        url: lang_base_url + '/edit-booking/' + btoa(booking_id),
        data: {booking_id: booking_id, pickup_date: pickup_date, pickup_time: pickup_time},
        dataType: 'JSON',
        success: function (response) {
            $('.loaderSpiner').hide();
            $('.responseTitle').html(response.title);
            $('.responseMsg').html(response.message);
            $('#openMsgPopupNoRedirect').click();
            if (response.status == true) {
                setTimeout(function () {
                    window.location.href = lang_base_url + '/manage-booking/' + response.booking_id;
                }, 2000);
            }
        }
    });
}

function show_niqaty_modal() {
    $('#niqatyModal').modal('show');
}

function get_niqaty_redeem_options() {
    var niqaty_mobile_number = $('#niqaty_mobile_number').val();
    if (niqaty_mobile_number == '') {
        show_bs_tooltip($('#niqaty_mobile_number'), required_message);
    } else {
        hide_bs_tooltip($('#niqaty_mobile_number'));
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/get_niqaty_redeem_options',
            dataType: "json",
            data: {niqaty_mobile_number: niqaty_mobile_number},
            success: function (response) {
                setTimeout(function () {
                    $('.loaderSpiner').hide();
                    if (response.status == true) {
                        $('#niqatyModal').modal('hide');
                        $('#niqatyRedeemOptionsModal').find('#niqatyRedeemOptions').html(response.message);
                        $('#niqatyRedeemOptionsModal').modal('show');
                    } else {
                        $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                        $('.responseMsg').html(response.message);
                        $('#openMsgPopupNoRedirect').click();
                    }
                }, 500);
            }
        });
    }
}

function authorize_niqaty_redeem_request() {
    if ($('.niqaty-redeem-option:checked').length > 0) {
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/authorize_niqaty_redeem_request',
            dataType: "json",
            data: $('.niqaty-redeem-option:checked').data(),
            success: function (response) {
                setTimeout(function () {
                    $('.loaderSpiner').hide();
                    if (response.status == true) {
                        $('#niqatyRedeemOptionsModal').modal('hide');
                        $('#niqatyOTPModal').modal('show');
                        $('#niqaty_transaction_reference_number').val(response.message);
                        $('#request_data').val(response.request_data);
                    } else {
                        $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                        $('.responseMsg').html(response.message);
                        $('#openMsgPopupNoRedirect').click();
                    }
                }, 500);
            }
        });
    } else {
        $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
        $('.responseMsg').html('You must select any of the available redeem options to proceed!');
        $('#openMsgPopupNoRedirect').click();
    }
}

function confirm_niqaty_redeem_request() {
    var niqaty_otp = $('#niqaty_otp').val();
    if (niqaty_otp == '') {
        show_bs_tooltip($('#niqaty_otp'), required_message);
    } else {
        hide_bs_tooltip($('#niqaty_otp'));

        var mobile_number = $('#niqaty_mobile_number').val();
        var transaction_reference_number = $('#niqaty_transaction_reference_number').val();
        var request_data = $('#request_data').val();

        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/confirm_niqaty_redeem_request',
            dataType: "json",
            data: {'otp': niqaty_otp, 'mobile': mobile_number, 'transactionReference': transaction_reference_number, 'request_data': request_data},
            success: function (response) {
                setTimeout(function () {
                    $('.loaderSpiner').hide();
                    if (response.status == true) {
                        $('#niqatyOTPModal').modal('hide');
                        $('.niqatyArea').find('.BtnNtXT').hide();
                        $('.payFrmUserInfo.paymentMethods').find('.paymentOption.heading, .paymentOption.objects').hide();
                        // $('.payFrmUserInfo.paymentMethods').find('.paymentOption.objects').hide();
                        $('.bookNowBtn').hide();

                        $('.qitafArea').hide();
                        $('.mokafaaArea').hide();
                        $('.anbArea').hide();
                        $('.keyArea').hide();

                        var payment_method;
                        if (response.amount_remaining) {
                            payment_method = $("#CreditCard");
                        } else {
                            payment_method = $("#cash");
                        }
                        if (payment_method.length) {
                            payment_method.click();
                            $('.bookNowBtn').val((lang == 'eng' ? 'Book Now' : 'احجز الآن'));
                            $('.bookNowBtn').show();
                        }

                        $('.niqatyArea .proCdSec .niqatyMsg').html(response.text_to_show);
                        $('.TotalAmountWithVatForMobileWithDays').html(response.total_payable_amount_after_niqaty);

                    } else {
                        $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                        $('.responseMsg').html(response.message);
                        $('#openMsgPopupNoRedirect').click();
                    }
                }, 500);

            }
        });
    }
}

$(".refundForm").submit(function (e) {
    e.preventDefault();
    $('.loaderSpiner').show();
    var url = $(this).attr('action');
    $.ajax({
        type: "POST",
        dataType: "json",
        data: new FormData(this),
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            $('.loaderSpiner').hide();
            grecaptcha.reset();

            var title = (lang == 'eng' ? 'Error' : 'خطأ');
            if (response.status == true) {
                title = (lang == 'eng' ? 'Success' : 'بنجاح');
                $('.refundForm')[0].reset();
            }
            $('.responseTitle').html(title);
            $('.responseMsg').html(response.message);
            $('#openMsgPopupNoRedirect').click();
        }
    });
});


$(".fixIBANText").on("keydown keyup change", function(){
    var value = $(this).val();
    if (value.substring(0, 2) !== 'SA') {
        $(this).val('SA');
    }
});

$(document).on('click', 'input[name="do_you_have_experience"]', function() {
    if ($(this).val() == 1) {
        $('.append_here').show();
    } else {
        $('.append_here').hide();
    }
});

function add_experience_section_for_career_page() {

    var all_fields_filled = true;
    var company_names = $("input[name='company_name[]']").map(function(){return $(this).val();}).get();
    var job_titles = $("input[name='job_title[]']").map(function(){return $(this).val();}).get();
    var from_dates = $("input[name='from_date[]']").map(function(){return $(this).val();}).get();
    var to_dates = $("input[name='to_date[]']").map(function(){return $(this).val();}).get();

    for (var i = 0; i < $("input[name='company_name[]']").length; i++) {
        if (company_names[i] == '' || job_titles[i] == '' || from_dates[i] == '' || to_dates[i] == '') {
            all_fields_filled = false;
        }
    }

    if (all_fields_filled) {
        var html = '<div class="col-md-3 col-sm-6 isNoFloat"><label>'+company_name_lbl+'</label><input type="text" name="company_name[]" placeholder="'+write_lbl+'" required="" autocomplete="off"></div><div class="col-md-3 col-sm-6 isNoFloat"><label>'+job_title_lbl+'</label><input type="text" name="job_title[]" placeholder="'+write_lbl+'" required="" autocomplete="off"></div><div class="col-md-3 col-sm-6 isNoFloat"><label>'+from_date_lbl+'</label><input type="text" name="from_date[]" placeholder="'+select_lbl+'" class="career_page_experience_datepicker" required="" autocomplete="off"></div><div class="col-md-3 col-sm-6 isNoFloat"><label>'+to_date_lbl+'</label><input type="text" name="to_date[]" placeholder="'+select_lbl+'" class="career_page_experience_datepicker" required="" autocomplete="off"></div>';
        $('.append_here').append(html);

        init_career_page_experience_datepicker();
    }

}

function init_career_page_experience_datepicker() {
    $(".career_page_experience_datepicker").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        minDate: new Date(1970, 6, 12),
        maxDate: new Date(current_year, current_month - 1, current_date - 1),
        yearRange: "c-70:+0"
    });

    if (lang == 'arb') {
        $(".career_page_experience_datepicker").datepicker("option", $.datepicker.regional['ar']);
    } else {
        $(".career_page_experience_datepicker").datepicker("option", $.datepicker.regional['en-GB']);
    }
}

init_career_page_experience_datepicker();

function delete_account(id_no) {
    if (confirm('Are you sure you want to delete your account at KEY?')) {
        $('.loaderSpiner').show();
        $.ajax({
            type: 'GET',
            url: base_url + '/services/mark_account_as_deleted?k=ItykVex546VBeiXabxlExlyzErtc313&lang=' + lang + '&id_number=' + id_no,
            data: {lang: lang},
            async: false,
            dataType: 'JSON',
            success: function (response) {
                alert(response.message);
                if (response.status == 1) {
                    window.location.href = lang_base_url + '/logout';
                } else {
                    $('.loaderSpiner').hide();
                }
            }
        });
    }
}

$(document).on('click', '.subscription_options button', function(e) {
    e.preventDefault();
    eraseCookie('scroll_to_section_for_car');
    var subscribe_for_months = $(this).data('subscribe_for_months');
    var car_id = $(this).data('car_id');
    $('#subscribe_for_months option[value="'+subscribe_for_months+'"]').attr("selected", "selected");
    $('input#subscribe_for_months').val(subscribe_for_months);
    $('.loaderSpiner').show();
    setCookie('scroll_to_section_for_car', car_id, 1);
    setTimeout(function () {
        $('.serFormArea').submit();
    }, 1000);
});

$(document).ready(function() {
    var scroll_to_section_for_car = getCookie('scroll_to_section_for_car');
    if (scroll_to_section_for_car && $('#section_for_car_' + scroll_to_section_for_car).length) {
        $('html, body').animate({
            scrollTop: $('#section_for_car_' + scroll_to_section_for_car).offset().top
        }, 1000);
        setTimeout(function() {
            eraseCookie('scroll_to_section_for_car');
        }, 5000);
    }
});

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

$(document).on('click', 'input[name="show_available_cars_only"]', function() {
    $('.loaderSpiner').show();
    if ($(this).is(':checked')) {
        $('.sold_out').hide();
    } else {
        $('.sold_out').show();
    }
    setTimeout(function() {
        $('.loaderSpiner').hide();
    }, 500);
});

$(document).on('click', '.loyalty_programs_divs', function() {
    $('.loyalty_programs_divs').removeClass('active');
    $(this).addClass('active');
});

$(document).on('click', '.payment_options_divs', function() {
    $('.payment_options_divs').removeClass('active');
    $(this).addClass('active');
});

var my_invoices_paginate = 2;
$(document).on('click', '.my_invoices_paginate', function() {
    var user_id = $(this).data('user_id');
    $.ajax({
        type: 'GET',
        url: lang_base_url + '/my-invoices-paginate',
        data: {'user_id': user_id, 'page': my_invoices_paginate},
        dataType: 'JSON',
        beforeSend: function () {
            $('.loaderSpiner').show();
        },
        complete: function () {
            $('.loaderSpiner').hide();
        },
        success: function (response) {
            $('#corp-invoices').find('tbody').append(response.html);
            if (response.show_load_more_btn === false) {
                $('.load-more-btn').hide();
            }
            my_invoices_paginate++;
        }
    });
});

function show_benefits(card_id) {

    $('#cards-section table tr th').removeClass('highlight-first');
    $('#cards-section table tr td').removeClass('highlight');
    $('#cards-section table tr td').removeClass('highlight-last');

    setTimeout(function() {
        $('html, body').animate({
            scrollTop: $('#cards-section').offset().top
        }, 1000);
        $('.' + card_id + '_1').addClass('highlight-first');
        $('.' + card_id + '_2').addClass('highlight');
        $('.' + card_id + '_3').addClass('highlight');
        $('.' + card_id + '_4').addClass('highlight');
        $('.' + card_id + '_5').addClass('highlight');
        $('.' + card_id + '_6').addClass('highlight');
        $('.' + card_id + '_7').addClass('highlight');
        $('.' + card_id + '_8').addClass('highlight');
        $('.' + card_id + '_9').addClass('highlight');
        $('.' + card_id + '_10').addClass('highlight');
        $('.' + card_id + '_11').addClass('highlight-last');
    }, 100);
}

var btn = $('#loyalty-back-to-top-button');

$(window).scroll(function() {
    if ($(window).scrollTop() > 300) {
        btn.addClass('show');
    } else {
        btn.removeClass('show');
    }
});

btn.on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({scrollTop:0}, '300');
});

$(document).ready(function() {
    if ($('.loyalty-faq:first').length) {
        $('.loyalty-faq:first').click();
    }
});

$(document).on('click', '.loyalty-faq', function() {
    var ele = $(this);
    $('#faqs-section').find('.panel').removeClass('lp-active-panel');
    setTimeout(function() {
        ele.parents('.panel').addClass('lp-active-panel');
    }, 200);
});

$(document).on('click', '.loyalty-faq-btn', function() {
    var ele = $(this);
    $('#faqs-section').find('.accordion-item').removeClass('lp-active-panel');
    setTimeout(function() {
        ele.parents('.accordion-item').addClass('lp-active-panel');
    }, 200);
});

var mokafaa_access_token = '';
var mokafaa_mobile_number = '';
var mokafaa_otp_token = '';

function mokafaa_get_access_token() {
    $.ajax({
        type: 'POST',
        url: lang_base_url + '/mokafaa_get_access_token',
        dataType: "json",
        beforeSend: function () {
            $('.loaderSpiner').show();
        },
        complete: function () {
            $('.loaderSpiner').hide();
        },
        success: function (response) {
            if (response.status == true) {
                mokafaa_access_token = response.data.access_token;
                $('#mokafaaModal').modal('show');
            } else {
                $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                $('.responseMsg').html(response.message);
                $('#openMsgPopupNoRedirect').click();
            }
        }
    });
}

function mokafaa_send_otp() {
    mokafaa_mobile_number = $('#mokafaa_mobile_number').val();
    if (mokafaa_mobile_number == '') {
        show_bs_tooltip($('#mokafaa_mobile_number'), required_message);
    } else {
        hide_bs_tooltip($('#mokafaa_mobile_number'));
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/mokafaa_send_otp',
            dataType: "json",
            data: {access_token: mokafaa_access_token, mobile_number: mokafaa_mobile_number},
            beforeSend: function () {
                $('.loaderSpiner').show();
            },
            complete: function () {
                $('.loaderSpiner').hide();
            },
            success: function (response) {
                if (response.status == true) {
                    mokafaa_otp_token = response.data.otp_token;
                    $('#mokafaaModal').modal('hide');
                    $('#mokafaaOTPModal').modal('show');
                } else {
                    $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }
            }
        });
    }
}

function mokafaa_initiate_redeem_request() {
    var mokafaa_otp = $('#mokafaa_otp').val();
    var mokafaa_amount = $('#mokafaa_amount').val();
    if (mokafaa_otp == '' || mokafaa_amount == '') {
        if (mokafaa_otp == '') {
            show_bs_tooltip($('#mokafaa_otp'), required_message);
        } else {
            hide_bs_tooltip($('#mokafaa_otp'));
        }
        if (mokafaa_amount == '') {
            show_bs_tooltip($('#mokafaa_amount'), required_message);
        } else {
            hide_bs_tooltip($('#mokafaa_amount'));
        }
    } else {
        hide_bs_tooltip($('#mokafaa_otp'));
        hide_bs_tooltip($('#mokafaa_amount'));
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/mokafaa_initiate_redeem_request',
            dataType: "json",
            data: {access_token: mokafaa_access_token, mobile_number: mokafaa_mobile_number, otp_token: mokafaa_otp_token, mokafaa_amount: mokafaa_amount, otp_code: mokafaa_otp},
            beforeSend: function () {
                $('.loaderSpiner').show();
            },
            complete: function () {
                $('.loaderSpiner').hide();
            },
            success: function (response) {
                if (response.status == true) {
                    $('#mokafaaOTPModal').modal('hide');
                    $('.mokafaaArea').find('.BtnNtXT').hide();
                    $('.payFrmUserInfo.paymentMethods').find('.paymentOption.heading, .paymentOption.objects').hide();
                    // $('.payFrmUserInfo.paymentMethods').find('.paymentOption.objects').hide();
                    $('.bookNowBtn').hide();

                    $('.qitafArea').hide();
                    $('.niqatyArea').hide();
                    $('.anbArea').hide();
                    $('.keyArea').hide();

                    var payment_method;
                    if (response.amount_remaining) {
                        payment_method = $("#CreditCard");
                    } else {
                        payment_method = $("#cash");
                    }
                    if (payment_method.length) {
                        payment_method.click();
                        $('.bookNowBtn').val((lang == 'eng' ? 'Book Now' : 'احجز الآن'));
                        $('.bookNowBtn').show();
                    }

                    $('.mokafaaArea .proCdSec .mokafaaMsg').html(response.text_to_show);
                    $('.TotalAmountWithVatForMobileWithDays').html(response.total_payable_amount_after_mokafaa);

                } else {
                    $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }
            }
        });
    }
}

/////////////////////////////////

var anb_access_token = '';
var anb_mobile_number = '';
var anb_otp_token = '';

function anb_get_access_token() {
    $.ajax({
        type: 'POST',
        url: lang_base_url + '/anb_get_access_token',
        dataType: "json",
        beforeSend: function () {
            $('.loaderSpiner').show();
        },
        complete: function () {
            $('.loaderSpiner').hide();
        },
        success: function (response) {
            if (response.status == true) {
                anb_access_token = response.data.access_token;
                $('#anbModal').modal('show');
            } else {
                $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                $('.responseMsg').html(response.message);
                $('#openMsgPopupNoRedirect').click();
            }
        }
    });
}

function anb_send_otp() {
    anb_mobile_number = $('#anb_mobile_number').val();
    if (anb_mobile_number == '') {
        show_bs_tooltip($('#anb_mobile_number'), required_message);
    } else {
        hide_bs_tooltip($('#anb_mobile_number'));
        $('.loaderSpiner').show();
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/anb_send_otp',
            dataType: "json",
            data: {access_token: anb_access_token, mobile_number: anb_mobile_number},
            beforeSend: function () {
                $('.loaderSpiner').show();
                $('#sameOTPMessage').hide();
            },
            complete: function () {
                $('.loaderSpiner').hide();
            },
            success: function (response) {
                if (response.status == true) {
                    anb_otp_token = response.data.otp_token;
                    $('#anbModal').modal('hide');
                    $('#anbOTPModal').modal('show');
                    if (response.message) {
                        $('#sameOTPMessage').show();
                    }
                } else {
                    $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }
            }
        });
    }
}

function anb_initiate_redeem_request() {
    var anb_otp = $('#anb_otp').val();
    var anb_amount = $('#anb_amount').val();
    if (anb_otp == '' || anb_amount == '') {
        if (anb_otp == '') {
            show_bs_tooltip($('#anb_otp'), required_message);
        } else {
            hide_bs_tooltip($('#anb_otp'));
        }
        if (anb_amount == '') {
            show_bs_tooltip($('#anb_amount'), required_message);
        } else {
            hide_bs_tooltip($('#anb_amount'));
        }
    } else {
        hide_bs_tooltip($('#anb_otp'));
        hide_bs_tooltip($('#anb_amount'));
        $.ajax({
            type: 'POST',
            url: lang_base_url + '/anb_initiate_redeem_request',
            dataType: "json",
            data: {access_token: anb_access_token, mobile_number: anb_mobile_number, otp_token: anb_otp_token, anb_amount: anb_amount, otp_code: anb_otp},
            beforeSend: function () {
                $('.loaderSpiner').show();
            },
            complete: function () {
                $('.loaderSpiner').hide();
            },
            success: function (response) {
                if (response.status == true) {
                    $('#anbOTPModal').modal('hide');
                    $('.anbArea').find('.BtnNtXT').hide();
                    $('.payFrmUserInfo.paymentMethods').find('.paymentOption.heading, .paymentOption.objects').hide();
                    // $('.payFrmUserInfo.paymentMethods').find('.paymentOption.objects').hide();
                    $('.bookNowBtn').hide();

                    $('.qitafArea').hide();
                    $('.niqatyArea').hide();
                    $('.mokafaaArea').hide();
                    $('.keyArea').hide();

                    var payment_method;
                    if (response.amount_remaining) {
                        payment_method = $("#CreditCard");
                    } else {
                        payment_method = $("#cash");
                    }
                    if (payment_method.length) {
                        payment_method.click();
                        $('.bookNowBtn').val((lang == 'eng' ? 'Book Now' : 'احجز الآن'));
                        $('.bookNowBtn').show();
                    }

                    $('.anbArea .proCdSec .anbMsg').html(response.text_to_show);
                    $('.TotalAmountWithVatForMobileWithDays').html(response.total_payable_amount_after_anb);

                } else {
                    $('.responseTitle').html((lang == 'eng' ? 'Error' : 'خطأ'));
                    $('.responseMsg').html(response.message);
                    $('#openMsgPopupNoRedirect').click();
                }
            }
        });
    }
}

$('#anbModal').on('hidden.bs.modal', function (e) {
    $(this).find("input").val('');
});

$('#anbOTPModal').on('hidden.bs.modal', function (e) {
    $(this).find("input").val('');
});

$('#mokafaaModal').on('hidden.bs.modal', function (e) {
    $(this).find("input").val('');
});

$('#mokafaaOTPModal').on('hidden.bs.modal', function (e) {
    $(this).find("input").val('');
});

function show_bs_tooltip(elem, message = '') {
    elem.attr('title', (message ? message : required_message));
    var tooltip = new bootstrap.Tooltip(elem);
    tooltip.show();
}

function hide_bs_tooltip(elem) {
    var tooltip = new bootstrap.Tooltip(elem);
    tooltip.hide();
}

$(document).on('click', '#share_and_earn_btn', function() {
    if (logged_in_from_frontend == 1) {
        window.location.href = lang_base_url + '/my-profile';
    } else {
        if (is_mobile) {
            $('#model-login').modal('show');
        } else {
            $('.hasDropEd').removeClass('open');
            $('#containsLoginDDB').addClass('open');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
});

var arabicLanguages = [];
arabicLanguages[""] = "اختار اللغة";
arabicLanguages['zh-CN'] = "صينى";
arabicLanguages['fr'] = "فرنسي";
arabicLanguages['ru'] = "الروسية";
arabicLanguages['es'] = "الأسبانية";
arabicLanguages['ur'] = "الأردية";

var englishLanguages = [];
englishLanguages[""] = "Select Language";
englishLanguages['zh-CN'] = "Chinese";
englishLanguages['fr'] = "French";
englishLanguages['ru'] = "Russian";
englishLanguages['es'] = "Spanish";
englishLanguages['ur'] = "Urdu";

$(document).ready(function() {
    if (lang == 'arb') {
        setTimeout(function () {
            var select_options = $('.my-lang').find('select.goog-te-combo').find('option');
            select_options.each(function () {
                // $(this).text(arabicLanguages[this.value]);
                $(this).text(englishLanguages[this.value]);
            });
        }, 2000);

        setTimeout(function () {
            $('.my-lang').show();
        }, 5000);
    } else {
        setTimeout(function () {
            var select_options = $('.my-lang').find('select.goog-te-combo').find('option');
            select_options.each(function () {
                $(this).text(englishLanguages[this.value]);
            });
        }, 2000);

        setTimeout(function () {
            $('.my-lang').show();
        }, 5000);
    }
});

$(document).on('change', 'select.goog-te-combo', function() {
    if (lang == 'arb') {
        var select_options = $('.my-lang').find('select.goog-te-combo').find('option');
        select_options.each(function () {
            $(this).text(arabicLanguages[this.value]);
        });
    }
});

$(document).on('click', '.bEditBtn', function(e) {
    e.preventDefault();

    var bid = $(this).data("bid");
    $.ajax({
        type: "POST",
        url: lang_base_url + "/sendEditBookingOTP",
        dataType: "json",
        data: {"bid": bid},
        beforeSend: function () {
            $('.loaderSpiner').show();
        },
        complete: function () {
            $('.loaderSpiner').hide();
        },
        success: function (response) {
            if (response.status == true) {
                $('#editBookingForm').modal('show');
            } else {
                alert(response.message);
            }
        }
    });
});

$(document).on('submit', '.editBookingverification', function () {
    $('.loaderSpiner').show();
    var url = lang_base_url + "/verifySmsCheck";

    var $form = $(this);

    var method = $form.attr('method');
    $.ajax({
        type: method,
        url: url,
        dataType: "json",
        data: $form.serialize(),
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.status == true) {
                window.location.href = lang_base_url + "/edit-booking/" + response.bid;
            } else {
                alert(response.message);
            }
        }
    });
});

function showMapForLimousineBranch(ele, type, branch_id) {
    if (type == 'pickup') {
        $('#limousineModePickupBranchesModal').find('.nav-col').find('a').removeClass('active');
    } else if (type == 'dropoff') {
        $('#limousineModeDropoffBranchesModal').find('.nav-col').find('a').removeClass('active');
    }
    ele.addClass('active');
    var selectedLocation = ele.text();
    $('.loaderSpiner').show();
    var branch_delivery_coordinates = [];
    $.ajax({
        type: 'GET',
        url: base_url + '/get_branch_delivery_coordinates',
        dataType: "json",
        data: {'branch_id': branch_id},
        success: function (response) {
            $('.loaderSpiner').hide();
            if (response.status == true) {
                var coordinates = response.coordinates.split('|');
                for (var i = 0; i < coordinates.length; i++) {
                    var coordin = coordinates[i].split(",");
                    var latitude = parseFloat(coordin[0]);
                    var longitude = parseFloat(coordin[1]);
                    branch_delivery_coordinates.push({ lat: latitude, lng: longitude });
                }
                var selector = (type == 'pickup' ? 'pickup_map' : 'dropoff_map');
                $('#'+selector).html('');
                initMap(branch_delivery_coordinates, latitude, longitude, type, selector, selectedLocation, branch_id);
            }
        }
    });
}

function initMap(polygonCoords, center_lat, center_long, type, selector, selectedLocation, branch_id) {

    var limousine_map = new google.maps.Map(document.getElementById(selector), {
        zoom: 11,
        center: { lat: center_lat, lng: center_long },
        gestureHandling: 'greedy'
    });

    var limousine_validCoords = polygonCoords.filter(coord => typeof coord.lat === 'number' && typeof coord.lng === 'number');

    var limousine_polygon = new google.maps.Polygon({
        paths: limousine_validCoords,
        strokeColor: '#000000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillOpacity: 0, // Set fillOpacity to 0 to make the polygon transparent,
    });

    limousine_polygon.setMap(limousine_map);

    // Adjust map bounds to fit the polygon
    var limousine_bounds = new google.maps.LatLngBounds();
    limousine_validCoords.forEach(coord => limousine_bounds.extend(coord));
    limousine_map.fitBounds(limousine_bounds);

    limousine_polygon.addListener('click', function(event) {
        $('.loaderSpiner').show();
        var clickedLat = event.latLng.lat();
        var clickedLng = event.latLng.lng();
        var selected_lat_long = clickedLat + ',' + clickedLng;

        if (type == 'pickup') {
            if (limousine_pickup_marker) {
                limousine_pickup_marker.setPosition(event.latLng);
            } else {
                limousine_pickup_marker = new google.maps.Marker({
                    position: event.latLng,
                    map: limousine_map
                });
            }
        } else {
            if (limousine_dropoff_marker) {
                limousine_dropoff_marker.setPosition(event.latLng);
            } else {
                limousine_dropoff_marker = new google.maps.Marker({
                    position: event.latLng,
                    map: limousine_map
                });
            }

        }

        $.ajax({
            type: 'POST',
            url: lang_base_url + '/fetch_nearest_delivery_branch',
            dataType: 'JSON',
            data: {'branch_id': branch_id, 'current_latitude': clickedLat, 'current_longitude': clickedLng, 'isLimousine': 1},
            success: function (response) {
                $('.loaderSpiner').hide();
                if (response.status == true) {
                    if (type == 'pickup') {
                        // putting values in pickup fields
                        $("#from_region_id").val(response.branch.region_id);
                        $("#from_city_id").val(response.branch.city_id);
                        $("#from_branch_id").val(response.branch.branch_id);

                        // putting values in dropoff fields
                        // $("#to_city_id").val(response.branch.city_id);
                        // $("#to_branch_id").val(response.branch.branch_id);

                        $('.pickup_delivery_coordinate').val(selected_lat_long);
                        // $('.dropoff_delivery_coordinate').val(selected_lat_long);

                        $('.limousine_mode_pickup').find('.from_branch_field_for_pickup').val(selectedLocation.replace(/,\s*$/, ""));
                        // $('.limousine_mode_dropoff').find('.to_branch_field_for_pickup').val(selectedLocation.replace(/,\s*$/, ""));

                        $('.allIsOkForPickup').val(1);
                        // $('.allIsOkForDropoff').val(1);

                        $('input[name="from_branch_name"]').val(selectedLocation.replace(/,\s*$/, ""));
                        // $('input[name="to_branch_name"]').val(selectedLocation.replace(/,\s*$/, ""));

                        $('#limousineModePickupBranchesModal').find('.confirm-btn').removeClass('hide');

                    } else if (type == 'dropoff') {
                        // putting values in dropoff fields
                        $("#to_city_id").val(response.branch.city_id);
                        $("#to_branch_id").val(response.branch.branch_id);
                        $('.dropoff_delivery_coordinate').val(selected_lat_long);
                        $('.limousine_mode_dropoff').find('.to_branch_field_for_pickup').val(selectedLocation.replace(/,\s*$/, ""));
                        $('.allIsOkForDropoff').val(1);
                        $('#limousineModeDropoffBranchesModal').find('.confirm-btn').removeClass('hide');
                        $('input[name="to_branch_name"]').val(selectedLocation.replace(/,\s*$/, ""));
                    }

                } else {
                    alert(response.message);
                }
            }
        });
    });

    var limousine_pickup_marker;
    var limousine_dropoff_marker;
    limousine_map.addListener('click', function(event) {
        if (type == 'pickup') {
            $('.allIsOkForPickup').val(0);
            $('.allIsOkForDropoff').val(0);
            $('#limousineModePickupBranchesModal').find('.confirm-btn').addClass('hide');
            $('#limousineModeDropoffBranchesModal').find('.confirm-btn').addClass('hide');

            if (limousine_pickup_marker) {
                limousine_pickup_marker.setPosition(event.latLng);
            } else {
                limousine_pickup_marker = new google.maps.Marker({
                    position: event.latLng,
                    map: limousine_map
                });
            }

        } else {
            $('.allIsOkForDropoff').val(0);
            $('#limousineModeDropoffBranchesModal').find('.confirm-btn').addClass('hide');

            if (limousine_dropoff_marker) {
                limousine_dropoff_marker.setPosition(event.latLng);
            } else {
                limousine_dropoff_marker = new google.maps.Marker({
                    position: event.latLng,
                    map: limousine_map
                });
            }

        }
    });
}
