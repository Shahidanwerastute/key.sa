<!DOCTYPE HTML>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="x-ua-compatible" content="ie=edge">



    <meta http-equiv="cache-control" content="max-age=0"/>

    <meta http-equiv="cache-control" content="no-cache"/>

    <meta http-equiv="expires" content="0"/>

    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT"/>

    <meta http-equiv="pragma" content="no-cache"/>



    <meta name="title"

          content="<?php if (isset($content[$lang . '_meta_title'])) echo $content[$lang . '_meta_title']?>">

    <meta name="description"

          content="<?php if (isset($content[$lang . '_meta_description'])) echo $content[$lang . '_meta_description']?>">

    <meta name="keywords"

          content="<?php if (isset($content[$lang . '_meta_keyword'])) echo $content[$lang . '_meta_keyword']?>">

    <script>
        var is_mobile = false;
    </script>

    <?php

    // $manual_version = '0.1';
    $manual_version = rand();
    if ($_SERVER['SERVER_NAME'] == 'www.key.sa')

    { ?>

    <script type="text/javascript" src="//script.crazyegg.com/pages/scripts/0072/3897.js" async="async"></script>

    <?php } ?>



    <?php
    $site = custom::site_settings();
    $api = custom::api_settings();
    ?>

    <link rel="shortcut icon" type="image/png" href="<?php echo $base_url; ?>/public/frontend/images/favicon.png?v=0.1">

    <link rel="shortcut icon" type="image/png" href="<?php echo $base_url; ?>/public/frontend/images/favicon.ico?v=0.1">

    <title>
        <?php echo $site->site_title; ?>

        <?php if (isset($_REQUEST['car'])) echo($_REQUEST['car'] != '' ? ' | ' . str_replace('-', ' ', $_REQUEST['car']) : '');?>
        <?php if (isset($content[$lang . '_meta_title'])) echo($content[$lang . '_meta_title'] != '' ? ' | ' . $content[$lang . '_meta_title'] : '');?>
    </title>


    <link rel="stylesheet" href="<?php echo $base_url; ?>/public/frontend/css/jquery-ui_1.12.1_themes.css">

    <link href="<?php echo $base_url; ?>/public/frontend/intTelInput/css/intlTelInput.css" rel="stylesheet"

          type="text/css" media="all">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link href="<?php echo $base_url; ?>/public/frontend/css/hijri_calendar/jquery.calendars.picker.css"

          rel="stylesheet" type="text/css" media="all">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link href="<?php echo $base_url; ?>/public/frontend/select2/css/select2.css" rel="stylesheet" type="text/css" media="all">

    <link href="<?php echo $base_url; ?>/public/frontend/css/kitKatClock.css?v=<?php echo $manual_version; ?>" rel="stylesheet" type="text/css"

          media="all">

    <link href="<?php echo $base_url; ?>/public/frontend/css/jquery.mCustomScrollbar.css" rel="stylesheet"

          type="text/css" media="all">

    {{--    <link href="<?php echo $base_url; ?>/public/frontend/css/bootstrap.css" rel="stylesheet" type="text/css"--}}

    {{--          media="all">--}}
    <link href="<?php echo $base_url; ?>/public/frontend/css/bootstrap@5.3/css/bootstrap.min.css" rel="stylesheet" type="text/css"

          media="all">

    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

    <link href="<?php echo $base_url; ?>/public/frontend/css/all.css?v=<?php echo $manual_version; ?>" rel="stylesheet" type="text/css" media="all">
    <link href="<?php echo $base_url; ?>/public/frontend/css/updated-style.css?v=<?php echo $manual_version; ?>" rel="stylesheet" type="text/css" media="all">


    <?php if ($lang == 'arb') // CSS For arabic

    { ?>

    <link href="<?php echo $base_url; ?>/public/frontend/css/rtl.css?v=<?php echo $manual_version; ?>" rel="stylesheet" type="text/css" media="all">
    <link href="<?php echo $base_url; ?>/public/frontend/css/updated-rtl.css?v=<?php echo $manual_version; ?>" rel="stylesheet" type="text/css" media="all">
    <?php } ?>



    <script src="<?php echo $base_url; ?>/public/frontend/js/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script src="<?php echo $base_url; ?>/public/frontend/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="<?php echo $base_url; ?>/public/frontend/css/bootstrap@5.3/js/bootstrap.min.js"></script>
    <script src="<?php echo $base_url; ?>/public/frontend/intTelInput/js/intlTelInput.js"></script>
    <script src="<?php echo $base_url; ?>/public/frontend/intTelInput/js/intlTelInput.min.js"></script>
    <script src="<?php echo $base_url; ?>/public/frontend/intTelInput/js/utils.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="<?php echo $base_url; ?>/public/frontend/select2/js/select2.js"></script>
    <script src="<?php echo $base_url; ?>/public/frontend/select2/js/i18n/en.js"></script>
    <script src="<?php echo $base_url; ?>/public/frontend/select2/js/i18n/ar.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api->google_api_key_general; ?>&language=<?php echo ($lang == 'eng' ? 'En' : 'Ar'); ?>&region=SA&libraries=places,geometry" type="text/javascript"></script>



    <style>

        .my-lang {
            display: none;
        }

        select.goog-te-combo {
            direction: <?php echo ($lang == 'eng' ? 'ltr' : 'rtl');  ?>;
        }

        .pac-container {

            z-index: 9000000000 !important;

        }

        .select2-dropdown{
            z-index: 999999;
        }

        .custom-combobox {
            position: relative;
            display: inline-block;
        }
        .custom-combobox-toggle {
            position: absolute;
            top: 0;
            bottom: 0;
            margin-left: -1px;
            padding: 0;
        }
        .custom-combobox-input {
            margin: 0;
            padding: 5px 10px;
        }
        .ui-front {
            z-index: 99999;
        }
        .custom-combobox-toggle {
            position: absolute;
            top: 0;
            bottom: 0;
            margin-left: -1px;
            padding: 0;
            width: 25px;
            background: #FE7E00;
            cursor: pointer;
        }
        .custom-combobox-toggle:before{
            content: '';
            position: absolute;
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 7px solid white;
            top: 11px;
            right: 8px;
        }
        .arb .modal-dialog,.arb .custom-combobox-input {
            text-align: right;
        }
        .eng .custom-combobox-input{
            text-align: left;
        }
        .ui-widget.ui-widget-content.custom-scrol {
            max-height: 300px;
            overflow: auto;
            display: block;
        }

        .footFollowUs {
            display: none;
        }

        .subscription_options {
            width: 20% !important;
            padding-left: 16px !important;
        }

        .subscription_options button {
            width: 126px !important;
            padding: 0 !important;
            height: 32px !important;
            font-size: 12px;
        }

        .subscription_options button.active {
            border: 3px #FE7E00 solid;
        }

        .subscription_options small {
            font-size: 7px !important;
            line-height: 10px !important;
        }

        .subscription_options div {
            line-height: 10px !important;
        }

        section.bookingSec .singleRow .bookDtlSec .bookPSec .subscription_options {
            justify-content: center;
        }

        .payment_options_area_div .promoCodeArea{
            cursor: pointer;
        }

        <?php  if(!custom::is_mobile()){?>
           /* calender css*/
        .ui-datepicker {
            width: 384px;
            height: 235px;
        }
        .ui-datepicker .ui-datepicker-title {
            font-size: 16px;
        }
        .ui-datepicker th {
            font-size: 16px
        }
        .ui-datepicker table {
            font-size: 15px;
        }
        .ui-datepicker-today {
            border-radius: 33px;
        }
        <?php }  ?>

        header .topHeader .topHleft ul .key-awards img {
            max-width: 200px;
        }

    </style>



    <script type="text/javascript"

            src="<?php echo $base_url; ?>/public/frontend/js/hijri_calendar/jquery.calendars.js"></script>

    <script type="text/javascript"

            src="<?php echo $base_url; ?>/public/frontend/js/hijri_calendar/jquery.calendars.islamic.js"></script>

    <script type="text/javascript"

            src="<?php echo $base_url; ?>/public/frontend/js/hijri_calendar/jquery.calendars.plus.js"></script>

    <script type="text/javascript"

            src="<?php echo $base_url; ?>/public/frontend/js/hijri_calendar/jquery.plugin.js"></script>

    <script type="text/javascript"

            src="<?php echo $base_url; ?>/public/frontend/js/hijri_calendar/jquery.calendars.picker.js"></script>





    <script src="<?php echo $base_url; ?>/public/frontend/js/KitKatClock.js"></script>

    <script src="<?php echo $base_url; ?>/public/frontend/js/jquery-ui_1.12.1.js"></script>

    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/i18n/jquery-ui-i18n.min.js"></script>

    <script src='https://www.google.com/recaptcha/api.js?hl=<?php echo ($lang == 'eng' ? 'en' : 'ar'); ?>'></script>



    <?php if ($_SERVER['SERVER_NAME'] == 'www.key.sa')

    { ?>

<!-- Google Tag Manager -->

    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':

                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],

            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=

            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);

        })(window,document,'script','dataLayer','GTM-K8LWSV8');</script>

    <!-- End Google Tag Manager -->





    <!-- Global Site Tag (gtag.js) - Google Analytics -->

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-24417190-42"></script>

    <script>

        window.dataLayer = window.dataLayer || [];

        function gtag(){dataLayer.push(arguments)};

        gtag('js', new Date());



        gtag('config', 'UA-24417190-42');

    </script>





    <script type="text/javascript">

        setTimeout(function(){var a=document.createElement("script");

            var b=document.getElementsByTagName("script")[0];

            a.src=document.location.protocol+"//script.crazyegg.com/pages/scripts/0058/1641.js";

            a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);

    </script>

    <?php } ?>







    <?php

    $segments = Request::segments();

    $last_segment = end($segments);

    if ($last_segment == 'en' || $last_segment == '') {

        $last_segment = 'home';

    }else{

        $last_segment = end($segments);

    }

    $site = custom::site_settings();



    if ($last_segment == 'home' || $last_segment == 'en' || $last_segment = '')

    {

        $home_class = 'homePage';

    }else{

        $home_class = $active_menu;

    }

    ?>





    <script>

        var base_url = '<?php echo $base_url; ?>';

        var lang_base_url = '<?php echo $lang_base_url; ?>';

        var lang = '<?php echo $lang; ?>';

        var required_message = '<?php echo Lang::get('labels.required'); ?>';

        var valid_email = '<?php echo Lang::get('labels.enter_valid_email_msg'); ?>';

        var last_segment = '<?php echo end($segments);?>';

        var page = '<?php echo end($segments);?>';

        var loyalty_tried = '<?php echo Session::get('loyalty_tried');?>';

        var logged_in_from_frontend = '<?php echo(Session::get('logged_in_from_frontend') == true ? 1 : 0); ?>';

        var show_customer_popup_after_search = '<?php echo(Session::has('show_customer_popup_after_search') ? 1 : 0); ?>';

        var corporate_loyalty = '<?php echo(custom::isCorporateLoyalty() ? 1 : 0); ?>';

        var show_loyalty_popup_in_booking = false;

        var show_scroll_down = true;





        <?php if (isset($_REQUEST['show_terms']) && $_REQUEST['show_terms'] == 1)

        {

        $lang = (isset($_REQUEST['lang']) ? $_REQUEST['lang'] : 'eng');

        ?>

        $(document).ready(function () {

            $('#openTermsAndConditonsPopup').click();

        });

        <?php } ?>





    </script>

    <style>
        .ui-datepicker table {
            background-color: #fff;
        }
        .bookingStepsLink ul li:after {
            top: 19px;
        }

        .price .pricePageSec .leftCol .basicDetails .col.twoBig li,
        .payment .pricePageSec .leftCol .basicDetails .col.twoBig li,
        .cc-payment .pricePageSec .leftCol .basicDetails .col.twoBig li {
            display: flex;
            align-items: center;
            white-space: unset;
        }
        .price .extraOptList .rows-main-box .row {
            /*display: flex;
            align-items: center;*/
        }
        .price .extraOptList .rows-main-box .row div:nth-child(1) {
            padding: 0;
        }
        .price .extraOptList .rows-main-box .row div:nth-child(2) {
            padding-right: 0;
        }
        .price .pricePageSec .rightCol .extraOptList .rows-bg-color-box .row.finalPrice  h3 {
            text-align: center;

        }
        .arb.price .extraOptList .rows-main-box .row div:nth-child(2) {
            padding-right: 15px;
            padding-left: 0;
        }
        .payment .login-popup-sec input.edBtn.redishButtonRound {
            font-size: 14px;
        }

        .new-header-style .topHeader .topHleft ul {
            gap: 15px !important;
            display: flex;
            justify-content: start !important;
            flex-wrap: nowrap !important;
            align-items: center;
        }
        .goog-te-combo{
            border: 1px solid #ccc !important;
            border-radius: 5px !important;
        }
        @media screen and (max-width: 1200px) {
            header .topHeader .topHleft ul .key-awards img {
                max-width: 135px !important;
            }
            .new-header-style .topHeader .topHleft ul li.hasDropEd a {
                font-size: 12px !important;
            }
        }
        @media screen and (max-width: 1199px) {
            .new-header-style .topHeader .topLang a {
                font-size: 15px;
                padding: 0px 3px;
            }
            body.arb .bookingStepsLink ul li:after {
                left: -59px;
            }
            .arb .bookingStepsLink ul li > div:after {
                bottom: -26px;
            }
            .arb .rightCol .checkbox-per-day-box {
                width: 35%;
            }
            .payment .peyment-option-text-box .niqatyContent .BtnNtXT ,
            .payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 108%;
            }
            .cc-payment .pricePageSec .reservation-message h2,
            .cc-payment .pricePageSec .reservation-message .title {
                font-size: 24px;
                line-height: 27px;
            }
            .cc-payment .pricePageSec .reservation-message .code {
                margin-left: 53px;
            }
            .cc-payment .pricePageSec .reservation-message .code:before {
                width: 220px;
                height: 230px;
                background-size: contain;
            }
            .arb.cc-payment .pricePageSec .reservation-message .code {
                margin-right: 53px;
                margin-left: 0;
            }
        }
        @media screen and (max-width: 991px) {
            .bookingStepsLink ul li:after {
                left: 198px;
            }
            body.arb .bookingStepsLink ul li:after {
                left: -209px;
            }
            .arb .bookingStepsLink ul li > div:after {
                bottom: -10px;
            }
            .price .pricePageSec .pricePgWrapper {
                display: block;
                width: 100%;
            }
            .price .pricePageSec .pricePgWrapper .leftCol {
                display: block;
                margin-bottom: 25px;
                width: 97%;
            }
            .price .pricePageSec .pricePgWrapper .rightCol {
                display: block;
                width: 97%;
            }
            .price .pricePageSec .pricePgWrapper .leftCol .imgBox {
                text-align: center;
            }
            .price .pricePageSec .rightCol .extraOptList .row.finalPrice h3 {
                font-size: 20px;
                text-align: center;
            }
            .payment .pricePageSec .leftCol {
                width: 40%;
                margin: 5px;
            }
            .payment .pricePageSec .rightCol {
                width: 60%;
                margin: 5px;
            }
            .payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 109%;
            }
            .payment .peyment-option-text-box .promoCodeArea .BtnNtXT input.edBtn {
                font-size: 15px;
            }
            .payment .pricePageSec .leftCol .basicDetails .col.twoBig li {
                white-space: unset;
            }
            .arb.payment .peyment-option-text-box .qitafContent .proCdSec,
            .arb.payment .peyment-option-text-box .niqatyContent .proCdSec {
                font-size: 12px;
            }
            .arb.payment .peyment-option-text-box .forWebsite {
                width: 40%;
            }
            .cc-payment .pricePageSec .leftCol {
                width: 40%;
            }
            .cc-payment .pricePageSec .rightCol {
                width: 60%;
            }
            .cc-payment .price-wrapper-new-design.pricePageSec .leftCol {
                margin: 5px;
            }
            .cc-payment .price-wrapper-new-design.pricePageSec .rightCol {
                margin: 5px;
            }
            .arb.cc-payment .wpwl-label.wpwl-label-cvv {
                font-size: 13px;
            }
            .payment .peyment-option-text-box .forWebsite {
                width: 40%;
            }
            .cc-payment .pricePageSec .reservation-message .code:before {
                top: -38px;
                left: -53px;
                width: 133px;
                height: 139px;
            }
            .cc-payment .pricePageSec .reservation-message h2,
            .cc-payment .pricePageSec .reservation-message .title {
                font-size: 21px;
                line-height: 27px;
            }
            .cc-payment .pricePageSec .reservation-message .code {
                padding: 10px 20px 10px 110px;
                margin-left: 43px;
            }
            .cc-payment .pricePageSec .reservation-message .code p {
                font-size: 14px;
            }
            .cc-payment .pricePageSec .reservation-message .btn-box {
                display: flex;
            }
            .cc-payment .pricePageSec .reservation-message {
                padding: 80px 0 30px;
            }
            .arb.cc-payment .pricePageSec .reservation-message .code:before {
                top: -38px;
            }
            .arb.cc-payment .pricePageSec .reservation-message .code {
                padding: 10px 110px 10px 20px;
            }
            .payment .login-popup-sec input.edBtn.redishButtonRound {
                font-size: 11px;
                padding: 15px 0px;
            }
            .arb .searchBarSec .serFormArea ul li.pickup_mode_pickup,
            .arb .searchBarSec .serFormArea ul li.pickup_mode_dropoff  {
                padding: 0 20px !important;
            }
        }



        @media screen and (max-width: 767px) {

            _::-webkit-full-page-media, _:future, :root .payment .new-form-design input[type="text"],
            .payment .new-form-design input[type="email"],
            .payment .new-form-design input[type="password"],
            .payment .new-form-design select {
                box-shadow: none;
                border: 1px solid rgb(0 0 0 / 25%);

            }
            _::-webkit-full-page-media, _:future, :root .new-form-design .payFrmUserInfo .paymentOption.objects li,
            .peyment-option-text-box .forWebsite {
                box-shadow: 0 0px 0 2px #8C8B8B;
                outline: none;
            }
            _::-webkit-full-page-media, _:future, :root .new-form-design .payFrmUserInfo .paymentOption.objects li:hover,
            .new-form-design .payFrmUserInfo .paymentOption.objects li.active,
            .peyment-option-text-box .forWebsite:hover,
            .peyment-option-text-box .forWebsite.active {
                box-shadow: 0 0px 0 2px #FE7E00;
                outline: none;
            }
            .payment .peyment-option-text-box .forWebsite {
                width: 30%;
            }
            .new-header-style .topHeader .topHleft ul li.mobile-version-header-div {
                width:100%;
                text-align: end;
                margin-top: 10px;
            }
            header .topHeader .topHleft ul li.hasDropEd .dropDownEd {
                right: 0;
                transform: none !important;
                left: auto;
            }
            .bookingStepsLink ul li:after {
                content: none;
            }
            .bookingStepsLink ul li {
                width: 33%;
                text-align: center;
                margin: 0;
                padding: 10px 5px 0;
            }
            .bookingStepsLink ul li > div:after {
                bottom: -15px;
            }
            .bookingStepsLink ul li {
                font-size: 14px;
            }
            .arb header .topHeader .topHleft ul li.hasDropEd .dropDownEd {
                right:auto;
                left: 0;
            }
            .arb header .topHeader .topHleft ul li.manage-booking-container .dropDownEd {
                min-width: 250px;
                right: 50%;
                transform: translateX(50%) !important;
                left: auto;
                padding: 10px;
            }
            .arb header .topHeader .topHleft ul li.manage-booking-container .dropDownEd input:nth-child(1) {
                margin-bottom: 14px;
            }
            .arb .new-header-style .topHeader .topHleft ul li.manage-booking-container .dropDownEd input:nth-child(2) {
                padding: 5px 20px;
                font-size: 15px;
            }
            /*.arb .bookingStepsLink ul li > div:after {*/
            /*    bottom: -26px;*/
            /*}*/
            .arb .bookingStepsLink ul li > div:after {
                bottom: -26px;
            }
            .price .pricePageSec .rightCol .extraOptList .row.finalPrice h3 {
                font-size: 19px;
            }
            body.arb .pricePageSec .rightCol h1,
            body.arb .pricePageSec .rightCol .select-any-extra-text{
                padding-right: 10px;
            }
            .arb.price .price-wrapper-new-design.pricePageSec .rightCol .extraOptList .options-list .deFaultRow {
                padding: 10px 5px 10px 5px;
            }
            .arb .rightCol .checkbox-per-day-box {
                width:50%;
            }
            .payment .pricePageSec .leftCol {
                width: 100%;
                margin: 20px 0;
            }
            .payment .pricePageSec .rightCol {
                width: 100%;
                margin: 20px 0;
            }
            .payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 105.5%;
            }
            .payment .termsDiv a {
                font-size: 14px;
            }
            .arb.payment .peyment-option-text-box .forWebsite {
                width: 27%;
            }
            .arb.payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .arb.payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 106.5%;
            }
            .arb.payment .payment_options_help_text p {
                font-size: 14px;
            }
            .cc-payment .pricePageSec .leftCol {
                width: 100%;
                margin: 20px 0 !important;
            }
            .cc-payment .pricePageSec .rightCol {
                width: 100%;
                margin: 20px 0 !important;
            }
            .cc-payment .pricePageSec .reservation-message .code {
                margin: auto;
            }
            .cc-payment .pricePageSec .reservation-message .code {
                max-width: 300px;
            }
            .cc-payment .pricePageSec .reservation-message .code:before {
                top: -36px;
            }
            .cc-payment .pricePageSec .reservation-message h2,
            .cc-payment .pricePageSec .reservation-message .title {
                font-size: 20px;
            }
            .arb.cc-payment .pricePageSec .reservation-message .code {
                margin: auto;
            }
            .arb.cc-payment .pricePageSec .reservation-message .code {
                padding: 10px 110px 10px 20px;
            }
            .arb.cc-payment .pricePageSec .reservation-message .code:before {
                top: -41px;
            }
            .arb.cc-payment .pricePageSec .reservation-message .code {
                margin-right: 68px;
                margin-left: 0;
            }
        }
        @media screen and (max-width: 676px) {
            .payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 104.5%;
            }
            .payment .peyment-option-text-box .forWebsite {
                width: 40%;
            }
            .arb.payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .arb.payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 107.5%;
            }
        }
        @media screen and (max-width: 545px) {
            .bookingStepsLink ul li {
                font-size: 11px;
            }
            .bookingStepsLink ul li span {
                font-size: 19px;
            }
            .bookingStepsLink ul li > div:after {
                bottom: -28px;
            }
            .price .price-wrapper-new-design.pricePageSec .rightCol {
                padding: 20px 0px 20px 10px;
            }
            .price .price-wrapper-new-design .container {
                padding-right: 5px;
                padding-left: 5px;
            }
            .price .price-wrapper-new-design.pricePageSec .leftCol {
                margin: 10px 0;
            }
            .price .pricePageSec .pricePgWrapper .leftCol {
                width: 100%;
            }
            .price .pricePageSec .pricePgWrapper .rightCol {
                width: 100%;
            }
            .price .price-wrapper-new-design.pricePageSec .rightCol {
                margin: 10px 0;
            }
            .payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 107%;
            }
            .arb.payment .peyment-option-text-box .forWebsite {
                width: 40%;
            }
            .arb .bookingStepsLink {
                min-height: 75px;
            }
            .arb .bookingStepsLink ul li > div:after {
                bottom: -21px;
            }
        }
        @media screen and (max-width: 480px) {
            .peyment-option-text-box .niqatyContent .BtnNtXT,
            .peyment-option-text-box .qitafContent .BtnNtXT {
                bottom: -17px;
            }
            .payment .peyment-option-text-box .forWebsite {
                width: 45%;
            }
            .arb.payment .peyment-option-text-box .forWebsite {
                width: 47%;
            }
            .arb.payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .arb.payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 108.5%;
            }
            .arb.payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .arb.payment .peyment-option-text-box .qitafContent .BtnNtXT {
                left: -5px;
            }
            .arb .searchBarSec .serFormArea ul li.pickup_mode_pickup,
            .arb .searchBarSec .serFormArea ul li.pickup_mode_dropoff {
                width: 100% !important;
            }
            .arb .searchBarSec .serFormArea ul li.dropoff_time_sec,
            .arb .searchBarSec .serFormArea ul li.pickup_time_sec {
                width: 100% !important;
                padding: 0 20px;
            }
        }
        @media screen and (max-width: 425px) {
            header .topHeader .topHleft ul li.manage-booking-container .dropDownEd {
                min-width: 250px;
                right: 50%;
                transform: translateX(50%) !important;
                left: auto;
            }
            header .topHeader .topHleft ul li.manage-booking-container .dropDownEd input:nth-child(1) {
                margin-bottom: 14px;
            }
            .new-header-style .topHeader .topHleft ul li.manage-booking-container .dropDownEd input:nth-child(2) {
                font-size: 15px;
            }
            .price .pricePageSec .rightCol .extraOptList .row.finalPrice h3 {
                font-size: 13px;
            }
            .price .price-wrapper-new-design.pricePageSec .rightCol .extraOptList .options-list .deFaultRow img {
                margin-right: 10px;
            }
            .price .price-wrapper-new-design.pricePageSec .rightCol .extraOptList .options-list .deFaultRow {
                padding: 10px 5px 10px 15px;
            }
            .price .rightCol .checkbox-per-day-box {
                width: 25%;
            }
            .arb .rightCol .checkbox-per-day-box {
                width: 60%;
            }
            .price-wrapper-new-design .deFaultRow .checkbox-per-day-box p {
                justify-content: end;
                font-size: 9px;
            }
            .arb.price .price-wrapper-new-design.pricePageSec .rightCol .extraOptList .options-list .deFaultRow img {
                margin-left: 10px;
                margin-right: 0;
            }
            .payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 105%;
            }
            .payment .peyment-option-text-box .forWebsite {
                width: 60%;
            }
            .payment .peyment-option-text-box .qitafContent .proCdSec,
            .payment .peyment-option-text-box .niqatyContent .proCdSec {
                font-size: 12px;
            }
            .cc-payment .pricePageSec .reservation-message .btn-box {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            .cc-payment .pricePageSec .reservation-message .code {
                padding: 140px 20px 20px 20px;
                margin: auto;
                border-radius: 10px;
            }
            .cc-payment .pricePageSec .reservation-message .code:before {
                width: 108px;
                height: 100px;
                top: 17px;
                left: 50%;
                transform: translate(-50%);
            }
            .cc-payment .pricePageSec .reservation-message h2,
            .cc-payment .pricePageSec .reservation-message .title {
                text-align: center;
                display: block;
            }
            .cc-payment .pricePageSec .reservation-message .code p {
                text-align: center;
            }
            .arb.cc-payment .pricePageSec .reservation-message .code {
                margin: auto;
                padding: 140px 20px 20px 20px;
            }
            .arb.cc-payment .pricePageSec .reservation-message .code:before {
                left: 50%;
                top: 17px;
                right: auto;
                transform: translateX(-50%) scaleX(-1);
            }
            .payment input#couponCodeField::placeholder {
                font-size: 11px;
            }
        }
        @media screen and (max-width: 421px) {
            .bookingStepsLink ul li > div:after {
                bottom: -5px;
            }
        }
        @media screen and (max-width: 375px) {
            .payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 107%;
            }
            .arb.payment .peyment-option-text-box .forWebsite {
                width: 55%;
            }
            .arb.cc-payment .wpwl-button-pay {
                font-size: 13px;
            }
            .cc-payment .price-wrapper-new-design.pricePageSec .rightCol h1 {
                font-size: 18px;
            }
            .cc-payment .pricePageSec .rightCol h1 span {
                font-size: 13px;
            }
        }
        @media screen and (max-width: 325px) {
            header .topHeader .topHleft ul li.login-container .dropDownEd .grayishButton {
                min-width: 100%;
                float: unset;
                margin-right: 0;
            }
            .payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 107%;
            }
            .arb.payment .peyment-option-text-box .niqatyContent .BtnNtXT,
            .arb.payment .peyment-option-text-box .qitafContent .BtnNtXT {
                width: 110.5%;
            }
            .payment .login-popup-sec input.edBtn.redishButtonRound {
                font-size: 9px;
                padding: 10px 0px;
            }
        }
    </style>

    <script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Organization",
  "parentOrganization": "Key Car Rental",
  "name": "Key Car Rental",
  "alternateName": "Key Car Rental",
  "url": "https://www.key.sa",
  "description": "Key Car Rental was founded in 1982 with our first branch opening in Jeddah. Today the company has more than 50 branches around the Kingdom and has grown to become one of the leading car rental companies in Saudi Arabia.",
  "logo": "https://www.key.sa/public/uploads/site_logo-Key_Grey_FE7E00_11669012746.png",
  "address": {
    "@type": "PostalAddress",
    "name": "Key Car Rental",
    "addressLocality": "Albawadi Dist, Jeddah",
    "addressRegion": "Mecca",
    "addressCountry": "KSA",
    "streetAddress": "Madina Road"
  },
  "sameAs": [
    "https://www.facebook.com/KeyCarRental",
    "https://twitter.com/KeyCarRental",
    "https://www.linkedin.com/company/key-rent-a-car/",
    "https://www.instagram.com/keycarrental/"
  ],
  "contactPoint": [
    {
      "@type": "ContactPoint",
      "telephone": "+966-800-244-0204",
      "contactType": "customer service",
      "contactOption": "TollFree",
      "availableLanguage": [
        "English",
        "Arabic"
      ]
    }
  ]
}
</script>




</head>

<body class="<?php echo $lang; ?> <?php echo $home_class; ?> <?php echo($last_segment == 'contact-us' ? 'contUs' : ''); ?>">

<?php if ($_SERVER['SERVER_NAME'] == 'www.key.sa')

{ ?>

<!-- Google Tag Manager (noscript) -->

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K8LWSV8"

                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<!-- End Google Tag Manager (noscript) -->

<?php } ?>





<div class="loaderSpiner">

    <img src="<?php echo $base_url; ?>/public/frontend/images/loaderRing.png" alt="Loading" height="196" width="196"/>

</div>

<!-- Modal -->

<div class="modal fade" id="forGotPassLogn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                <h4 class="modal-title" id="myModalLabel">@lang('labels.reset_password')</h4>

            </div>

            <div class="modal-body">

                <form action="<?php echo $lang_base_url; ?>/forgot_password" method="post"

                      class="popFormStl custom_submit" onsubmit="return false;">

                    <p>@lang('labels.forgot_password_popup_msg')</p>

                    <input type="email" placeholder="@lang('labels.email')" name="email" required/>

                    <div class="twoBtnEd">

                        <input type="submit" class="redishButtonRound" value="@lang('labels.reset_password')"/>

                        <a href="javascript:void(0);"><input type="button" class="grayishButton"

                                                             value="@lang('labels.cancel')" data-bs-dismiss="modal"/></a>

                        <div class="clearfix"></div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
<?php $getSegment = $lang == 'eng'?Request::segment(2):Request::segment(1);
if(isset($_REQUEST['mobile']) || $getSegment == 'mada-payment'){ ?>
<header></header>
<?php }else{ ?>
<header class="new-header-style">

    <div class="logoMenuTop">

        <nav class="navbar navbar-default">

            <div class="container-md">

                <div class="row">
                    <div class="col-lg-4 col-sm-12 col-12">
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="logo-menu-box">
                            <div class="collapse navbar-collapse menuRight" id="bs-example-navbar-collapse-1">

                                <ul class="nav navbar-nav navbar-right">

                                <!-- <li class="hidden-xs"><a href="javascript:void(0);" class="hasDropEdSecondary"><?php echo($lang == 'eng' ? 'MENU' : 'القائمة'); ?></a></li> -->

                                    <li class="hasDropEd">

                                        <a href="javascript:void(0);">

                                            <button type="button" class="menuIconT">
                                                <img src="<?php echo custom::baseurl('public/frontend/images/menu.png'); ?>" alt="">
                                                <!-- <span class="sr-only">Toggle navigation</span>

                                                <span class="icon-bar"></span>

                                                <span class="icon-bar"></span>

                                                <span class="icon-bar"></span> -->

                                            </button>

                                        </a>

                                        <div class="dropDownEd">

                                            <button type="button" class="menuIconT" id="closeTopMenu">
                                                {{--<span class="sr-only">Toggle navigation</span>

                                                <span class="icon-bar"></span>

                                                <span class="icon-bar"></span>

                                                <span class="icon-bar"></span>

                                                <span class="icon-bar"></span>--}}
                                                <img src="<?php echo custom::baseurl('public/frontend/images/close.png'); ?>" alt="">
                                            </button>

                                            <ul>

                                                <?php if (Session::get('logged_in_from_frontend') != true){ ?>
                                                <li><a href="javascript:void(0);" id="openLoginDDB"><span style="display: inline-block;width: 32px;text-align: center;"><img src="<?php echo $base_url; ?>/public/frontend/images/userIcon.png" alt="User" height="15"  width="10"/></span><?php echo($lang == 'eng' ? 'User Login' : 'تسجيل دخول'); ?> </a></li>
                                                <?php } ?>
                                                <li class="<?php echo($active_menu == 'home' ? 'active' : ''); ?>"><a

                                                            href="<?php echo $lang_base_url; ?>"><span

                                                                class="iconEd home"></span>@lang('labels.home')</a></li>

                                                <li class="<?php echo($active_menu == 'book-car' ? 'active' : ''); ?>"><a

                                                            href="<?php echo $lang_base_url . '/book-car'; ?>"><span

                                                                class="iconEd bookCar"></span>@lang('labels.book_a_car')</a></li>

                                                <li class="<?php echo($active_menu == 'car-selling' ? 'active' : ''); ?>"><a

                                                            href="<?php echo $lang_base_url . '/car-selling'; ?>"><span

                                                                class="iconEd carSell"></span>@lang('labels.car_selling')</a></li>

                                                <li class="<?php echo($active_menu == 'fleet' ? 'active' : ''); ?>"><a

                                                            href="<?php echo $lang_base_url . '/fleet'; ?>"><span

                                                                class="iconEd fleet"></span>@lang('labels.key_fleet')</a></li>

                                                <li class="<?php echo($active_menu == 'location' ? 'active' : ''); ?>"><a

                                                            href="<?php echo $lang_base_url . '/location'; ?>"><span

                                                                class="iconEd location"></span>@lang('labels.location')</a></li>

                                                <li class="<?php echo($active_menu == 'loyalty' ? 'active' : ''); ?>"><a

                                                            href="<?php echo $lang_base_url . '/loyalty'; ?>"><span

                                                                class="iconEd loyality"></span><div style="width: 150px;display: inline-block;vertical-align: middle;">@lang('labels.loyalty_program')</div></a></li>

                                                <li class="<?php echo($active_menu == 'services' ? 'active' : ''); ?>"><a

                                                            href="<?php echo $lang_base_url . '/services'; ?>"><span

                                                                class="iconEd services"></span> @lang('labels.services')</a></li>



                                                <li class="<?php echo($active_menu == 'offers' ? 'active' : ''); ?>"><a

                                                            href="<?php echo $lang_base_url . '/offers'; ?>"><span

                                                                class="iconEd offers"></span> @lang('labels.offers')</a></li>



                                                <li class="<?php echo($active_menu == 'program_awards' ? 'active' : ''); ?> hide"><a

                                                            href="<?php echo $lang_base_url . '/program-rewards'; ?>"><span

                                                                class="iconEd program_awards"></span> @lang('labels.program_awards')</a></li>

                                                <li class="<?php echo($active_menu == 'careers' ? 'active' : ''); ?>"><a

                                                            href="<?php echo $lang_base_url . '/careers'; ?>"><span

                                                                class="iconEd career"></span>@lang('labels.career')</a></li>

                                                <li class="<?php echo($active_menu == 'about-us' ? 'active' : ''); ?>"><a

                                                            href="<?php echo $lang_base_url . '/about-us'; ?>"><span

                                                                class="iconEd abtUs"></span>@lang('labels.about_us')</a></li>

                                                <li class="contact <?php echo($active_menu == 'contact-us' ? 'active' : ''); ?>"><a

                                                            href="<?php echo $lang_base_url . '/contact-us'; ?>"><span

                                                                class="iconEd contact"></span>@lang('labels.contact_us')</a></li>



                                            <!--<li class="<?php //echo ($active_menu == 'loyalty' ? 'active' : ''); ?>"><a href="<?php //echo $lang_base_url.'/loyalty'; ?>"><span class="iconEd loyality"></span>@lang('labels.loyalty')</a></li>

								<li class="<?php //echo ($active_menu == 'news' ? 'active' : ''); ?>"><a href="<?php //echo $lang_base_url.'/news'; ?>"><span class="iconEd newsCnt"></span>@lang('labels.news_center')</a></li>

								<li class="<?php //echo ($active_menu == 'faqs' ? 'active' : ''); ?>"><a href="<?php //echo $lang_base_url.'/faqs'; ?>"><span class="iconEd faq"></span>@lang('labels.faq')</a></li>-->

                                                <?php

                                                if ($site->site_language == 'both'){

                                                $lagUrlStr = custom::changeUrlWithEnAr($segments, (isset($returnToHome)));

                                                ?>

                                                <li class="lang">

                                                    {{-- one switcher start--}}
                                                    <div class="topLang">
                                                        <a href="javascript:void(0);"

                                                           id="<?php echo ($lang == 'eng' ? 'arb' : 'eng') . '||' . $lagUrlStr; ?>"

                                                           class="changeLanguage" title="@lang('labels.change_site_language')"><span

                                                                    class="iconEd lang"></span> <?php echo ($lang == 'eng' ? 'العربية' : 'English'); ?>

                                                        </a>
                                                    </div>

                                                    {{-- one switcher end--}}

                                                    {{-- two switcher start--}}
<!--                                                    <div>
                                                        <div id="google_translate_element_1" class="my-lang"></div>
                                                        <script type="text/javascript">
                                                            function googleTranslateElementInit() {
                                                                new google.translate.TranslateElement({
                                                                    pageLanguage: "<?php echo ($lang == 'eng' ? 'en' : 'ar');  ?>",
                                                                    includedLanguages: 'ur,fr,zh-CN,es,ru,',
                                                                    layout: google.translate.TranslateElement.InlineLayout.DROPDOWN,
                                                                    autoDisplay: false,
                                                                    attribution: false // Disable the "Powered by Google Translate" attribution
                                                                }, 'google_translate_element_1');

                                                                var $googleDiv = $("#google_translate_element_1 .skiptranslate");
                                                                var $googleDivChild = $("#google_translate_element_1 .skiptranslate div");
                                                                $googleDivChild.next().remove();

                                                                $googleDiv.contents().filter(function(){
                                                                    return this.nodeType === 3 && $.trim(this.nodeValue) !== '';
                                                                }).remove();
                                                            }
                                                        </script>
                                                        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit&sl=<?php echo ($lang == 'eng' ? 'en' : 'ar');  ?>">
                                                        </script>
                                                    </div>-->
                                                    {{-- two switcher end--}}

                                                </li>

                                                <?php }

                                                ?>



                                            </ul>

                                        </div>

                                    </li>

                                </ul>

                            </div>
                            <div class="navbar-header">

                                <div class="navbar-brand"><a href="<?php echo $lang_base_url; ?>"><img src="<?php echo $base_url; ?>/public/uploads/<?php echo $site->site_logo; ?>" alt="Logo" height="52"/></a>

                                    <?php

                                    if(Session::get('olp_id_message') != '')

                                    {

                                        echo '<span style="margin-left: 360px;color: red;">';

                                        echo Session::get('olp_id_message');

                                        echo '</span>';

                                        Session::forget('olp_id_message');

                                    }

                                    ?>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-sm-12 col-12">


                        <div class="topHeader" style="float:{{$lang == 'eng' ? 'right' : 'left'}};">

                            <div class="topHleft">

                                <ul>

                                    {{--Manage Booking--}}
                                    <li class="hasDropEd manage-booking-container">

                                        <a href="javascript:void(0);"><img src="<?php echo $base_url; ?>/public/frontend/images/manage-booking.png" alt="User"/> <?php echo($lang == 'eng' ? 'Manage Booking' : 'إدارة الحجوزات');?> </a>

                                        <div class="dropDownEd">

                                            <input class="booking_reference_no_for_home" placeholder="<?php echo($lang == 'eng' ? 'Type Booking #' : 'ادخل الحجز #'); ?>" autocomplete="off" type="text">

                                            <?php if ($site->maintenance_mode == 'on')

                                            { ?>

                                            <input type="button" class="subBtnTv" onclick="siteUnderMaintenance();">

                                            <?php }else{ ?>

                                            <input type="button" class="subBtnTv manageBookingFromHome" placeholder="search" value="<?php echo($lang == 'eng' ? 'Search' : 'البحث'); ?>">

                                            <?php } ?>

                                        </div>

                                    </li>

                                    {{--Login Start--}}
                                    <?php if (Session::get('logged_in_from_frontend') != true)

                                    { ?>

                                    <li class="hasDropEd active login-container" id="containsLoginDDB">

                                        <a href="javascript:void(0);"><img src="<?php echo $base_url; ?>/public/frontend/images/icon-login-register.png" alt="User"/> @lang('labels.user_login')</a>
                                        <style>
                                            #login input::placeholder{
                                                text-transform: unset !important;
                                            }
                                        </style>
                                        <div class="dropDownEd">

                                            <form action="<?php echo $lang_base_url; ?>/login" method="post" id="login" onsubmit="return false;">

                                                <input type="text" placeholder="<?php echo($lang == 'eng' ? 'Type (Email \ ID number)' : 'اكتب (البريد الإلكتروني / رقم الهوية)'); ?>" name="username" id="loginUsername"/>

                                                <input type="password" placeholder="@lang('labels.password')" name="password" id="loginPassword"/>

                                                <?php if ($site->maintenance_mode == 'on')

                                                { ?>

                                                <a href="javascript:void(0);" onclick="siteUnderMaintenance();" style="display: inline-block;text-transform: capitalize;">@lang('labels.forgot_password')</a>

                                                <?php }else{ ?>

                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#forGotPassLogn" style="display: inline-block;text-transform: capitalize;">@lang('labels.forgot_password')</a>

                                                <?php } ?>

                                                <div class="clearfix"></div>

                                                <?php if ($site->maintenance_mode == 'on')

                                                { ?>

                                                <input type="button" class="redishButtonRound" value="@lang('labels.login')" style="text-transform: capitalize;" onclick="siteUnderMaintenance();"/>

                                                <input type="button" onclick="siteUnderMaintenance();" class="grayishButton" value="@lang('labels.register')" style="text-transform: capitalize;"/>

                                                <?php }else{ ?>

                                                <input type="submit" class="redishButtonRound" style="text-transform: capitalize;" value="@lang('labels.login')"/>

                                                <input type="button" onclick="document.location.href='<?php echo $lang_base_url . '/register'; ?>'" class="grayishButton" value="@lang('labels.register')" style="text-transform: capitalize;"/>

                                                <?php } ?>

                                                <div class="clearfix"></div>

                                            </form>

                                        </div>

                                    </li>

                                    <?php }else{ ?>

                                    <li class="hasDropEd">

                                        <a href="javascript:void(0);"><img src="<?php echo $base_url; ?>/public/frontend/images/loginUser.png" alt="User"/> <?php echo Session::get('user_name'); ?></a>

                                        <div class="dropDownEd listItems">

                                            <ul>
                                                @if(Session::has('is_super') && Session::get('is_super') == true)
                                                    <li><a href="" data-bs-toggle="modal" data-bs-target="#corporateCompanies" >@lang('labels.switch_company')</a></li>
                                                @endif
                                                <li><a href="<?php echo $lang_base_url; ?>/my-profile">@lang('labels.profile')</a></li>

                                                <li><a href="<?php echo $lang_base_url; ?>/logout">@lang('labels.logout')</a></li>

                                            </ul>

                                        </div>

                                    </li>

                                    <?php } ?>
                                    {{--Login End--}}

                                    {{--Lang Switcher--}}
                                    <?php
                                    if ($site->site_language == 'both'){

                                    $lagUrlStr = custom::changeUrlWithEnAr($segments, (isset($returnToHome)));

                                    ?>
                                    <li class="mobile-version-header-div text-center">

                                        {{-- one switcher start--}}
                                        <div class="topLang">

                                            <?php if(custom::checkIfMobielTabOrPC() == 'mobile' || custom::checkIfMobielTabOrPC() == 'tablet' ){ ?>
                                            <a href="javascript:void(0);" id="webFullVersion"  class="mobile_version" data-fullVersion="0" >
                                                <?php echo($lang == 'eng' ? 'Mobile Version' : 'اصدار المحمول'); ?>
                                            </a>
                                            <?php } ?>

                                            <a href="javascript:void(0);" id="<?php echo ($lang == 'eng' ? 'arb' : 'eng') . '||' . $lagUrlStr; ?>" class="changeLanguage" title="@lang('labels.change_site_language')">
                                                <img src="<?php echo $base_url; ?>/public/frontend/images/lang-icon.png" alt=""><strong translate="no"><?php echo($lang == 'eng' ? 'العربية' : 'English'); ?></strong>
                                            </a>

                                        </div>
                                        {{-- one switcher end--}}

                                        {{-- two switcher start--}}
<!--                                        <div>
                                            <div id="google_translate_element_2" class="my-lang"></div>
                                            <script type="text/javascript">
                                                function googleTranslateElementInit() {
                                                    new google.translate.TranslateElement({
                                                        pageLanguage: "<?php echo ($lang == 'eng' ? 'en' : 'ar');  ?>",
                                                        includedLanguages: 'ur,fr,zh-CN,es,ru,',
                                                        layout: google.translate.TranslateElement.InlineLayout.DROPDOWN,
                                                        autoDisplay: false,
                                                        attribution: false // Disable the "Powered by Google Translate" attribution
                                                    }, 'google_translate_element_2');

                                                    var $googleDiv = $("#google_translate_element_2 .skiptranslate");
                                                    var $googleDivChild = $("#google_translate_element_2 .skiptranslate div");
                                                    $googleDivChild.next().remove();

                                                    $googleDiv.contents().filter(function(){
                                                        return this.nodeType === 3 && $.trim(this.nodeValue) !== '';
                                                    }).remove();
                                                }
                                            </script>
                                            <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit&sl=<?php echo ($lang == 'eng' ? 'en' : 'ar');  ?>">
                                            </script>
                                        </div>-->
                                        {{-- two switcher end--}}

                                    </li>
                                    <?php } ?>

<!--                                    <li class="key-awards">
                                        <img src="<?php echo $base_url; ?>/public/frontend/images/KEY-Awards.png" alt="KEY Awards">
                                    </li>-->

                                </ul>

                            </div>



                        </div>

                    </div>
                </div>



            </div>

        </nav>

    </div>

</header>
<?php } ?>
<script>

    <?php if (isset($_REQUEST['d']) && $_REQUEST['d'] == 1)

    { ?>

    $('#containsLoginDDB').addClass('open');

    <?php } ?>

</script>




