<!doctype html>
<!--[if lte IE 9]>
<html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="en"> <!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>

    <link rel="icon" type="image/png" href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/favicon-16x16.png?v=0.1"
          sizes="16x16">

    <link rel="icon" type="image/png" href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/favicon-32x32.png?v=0.1"
          sizes="32x32">
    <?php $site = custom::site_settings(); ?>
    <title><?php echo $site->site_title; ?> | Admin</title>

    
    <!-- additional styles for plugins -->
    <!-- JQuery-UI -->

    <link rel="stylesheet" href="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/kendo-ui/styles/kendo.common-material.min.css">

    <link rel="stylesheet" href="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/kendo-ui/styles/kendo.material.min.css" id="kendoCSS">

    <link rel="stylesheet" href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/skins/jquery-ui/material/jquery-ui.min.css" id="kendoCSS">

    <!-- weather icons -->
    <link rel="stylesheet"
          href="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/weather-icons/css/weather-icons.min.css"
          media="all">
    <!-- metrics graphics (charts) -->
    <link rel="stylesheet"
          href="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/metrics-graphics/dist/metricsgraphics.css">
    <!-- chartist -->
    <link rel="stylesheet"
          href="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/chartist/dist/chartist.min.css">

    <!-- jTable -->
    <link rel="stylesheet" href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/skins/jtable/jtable.css">
    <!-- Import CSS file for validation engine (in Head section of HTML) -->
    <link href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/skins/jtable/validationEngine/validationEngine.jquery.css" rel="stylesheet" type="text/css" />

    <!-- uikit -->
    <link rel="stylesheet"
          href="<?php echo custom::baseurl('/'); ?>/public/admin/bower_components/uikit/css/uikit.almost-flat.min.css"
          media="all">

    <!-- flag icons -->
    <link rel="stylesheet" href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/icons/flags/flags.min.css" media="all">

    <!-- style switcher -->
    <link rel="stylesheet" href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/css/style_switcher.min.css"
          media="all">

    <!-- altair admin -->
    <link rel="stylesheet" href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/css/main.css" media="all">
    <link rel="stylesheet" href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/css/main.min.css" media="all">
    <link rel="stylesheet" href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/css/themes/custom-style.css"
          media="all">


    <!-- themes -->
    <link rel="stylesheet" href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/css/themes/themes_combined.min.css"
          media="all">

    <!-- IntTel Input Plugin -->
    <link href="<?php echo custom::baseurl('/'); ?>/public/frontend/intTelInput/css/intlTelInput.css" rel="stylesheet"
          type="text/css" media="all">

    <link rel="stylesheet" href="<?php echo custom::baseurl('/'); ?>/public/admin/assets/css/custom.css?v=0.2" media="all">

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDchnqLBy9Ralvo0EXnQASBgv2hsPQk1xo&language=En&libraries=geometry"
            type="text/javascript"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">



    <!-- matchMedia polyfill for testing media queries in JS -->
    <!--[if lte IE 9]>
    <script type="text/javascript"
            src="<?php echo custom::baseurl('/');?>/public/admin/bower_components/matchMedia/matchMedia.js"></script>
    <script type="text/javascript"
            src="<?php echo custom::baseurl('/');?>/public/admin/bower_components/matchMedia/matchMedia.addListener.js"></script>
    <link rel="stylesheet" href="<?php echo custom::baseurl('/');?>/public/admin/assets/css/ie.css" media="all">
    <![endif]-->
    <?php
    $segments = Request::segments();
    $url = custom::baseurl('/') . '/';
    Session::put('last_segment', end($segments));
    //define('base_url', $url);
    ?>
    <script>
        var base_url = '<?php echo custom::baseurl('/');?>';
        var last_segment = '<?php echo end($segments);?>';
    </script>

    <style>
        .dataTables_wrapper .uk-table td.details-control {
            padding: 0;
            width: 20px;
            vertical-align: middle;
        }

        .jconfirm-holder {
            width: 25% !important;
            margin-left: 490px !important;;
        }

        .is_password_protected {
            display: none;
        }

        .k-picker-wrap {
            display: flex;
            border: none;
        }
    </style>
    <?php // echo Analytics::render(); ?>
</head>
<body class=" sidebar_main_open sidebar_main_swipe">
<!-- main header -->
<header id="header_main">
    <div class="header_main_content">
        <nav class="uk-navbar">

            <!-- main sidebar switch -->
            <a href="#" id="sidebar_main_toggle" class="sSwitch sSwitch_left">
                <span class="sSwitchIcon"></span>
            </a>


            <div class="uk-navbar-flip">
                <ul class="uk-navbar-nav user_actions">
                    <li><a href="#" id="full_screen_toggle" class="user_action_icon uk-visible-large"><i
                                class="material-icons md-24 md-light">&#xE5D0;</i></a></li>

                    <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                        <a href="#" class="user_action_image"><img class="md-user-image"
                                                                   src="<?php echo custom::baseurl('/'); ?>/public/admin/assets/img/avatars/avatar_11_tn.png"
                                                                   alt=""/></a>
                        <div class="uk-dropdown uk-dropdown-small">
                            <ul class="uk-nav js-uk-prevent">
                                <li><a href="<?php echo custom::baseurl('/') . '/admin/reset-password'; ?>">Reset Password</a></li>
                                <li><a href="<?php echo custom::baseurl('/') . '/admin/logout'; ?>">Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="header_main_search_form">
        <i class="md-icon header_main_search_close material-icons">&#xE5CD;</i>
        <form class="uk-form uk-autocomplete" data-uk-autocomplete="{source:'data/search_data.json'}">
            <input type="text" class="header_main_search_input"/>
            <button class="header_main_search_btn uk-button-link"><i class="md-icon material-icons">&#xE8B6;</i>
            </button>
            {{--
            <script type="text/autocomplete">
                    <ul class="uk-nav uk-nav-autocomplete uk-autocomplete-results">
                        {{~items}}
                        <li data-value="{{ $item.value }}">
                            <a href="{{ $item.url }}">
                                {{ $item.value }}<br>
                                <span class="uk-text-muted uk-text-small">{{{ $item.text }}}</span>
                            </a>
                        </li>
                        {{/items}}
                    </ul>


            </script>
            --}}
        </form>
    </div>


</header><!-- main header end -->