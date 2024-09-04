<!DOCTYPE HTML>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0", user-scalable=no">

    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta http-equiv="cache-control" content="max-age=0"/>

    <meta http-equiv="cache-control" content="no-cache"/>

    <meta http-equiv="expires" content="0"/>

    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT"/>

    <meta http-equiv="pragma" content="no-cache"/>

    <?php if(custom::show_smart_banner()) { ?>
    <meta name="apple-itunes-app" content="app-id=1282284664">

    <!-- Start SmartBanner configuration -->
    <meta name="smartbanner:title" content="Key Car Rental">
    <meta name="smartbanner:author" content="KEY Application">
    <meta name="smartbanner:price" content="FREE">
    <meta name="smartbanner:price-suffix-apple" content=" - On the App Store">
    <meta name="smartbanner:price-suffix-google" content=" - In Google Play">
    <meta name="smartbanner:icon-apple" content="https://kra.ced.sa/public/frontend/images/new_logos/Square.png">
    <meta name="smartbanner:icon-google" content="https://kra.ced.sa/public/frontend/images/new_logos/Square.png">
    <meta name="smartbanner:button" content="VIEW">
    <meta name="smartbanner:button-url-apple" content="https://apps.apple.com/pk/app/key-car-rental/id1282284664">
    <meta name="smartbanner:button-url-google"
          content="https://play.google.com/store/apps/details?id=comcom.key&hl=en_IN&gl=US">
    <meta name="smartbanner:enabled-platforms" content="android">
    <meta name="smartbanner:close-label" content="Close">
    <meta name="smartbanner:custom-design-modifier" content="ios">
    <!-- End SmartBanner configuration -->
    <?php } ?>

    <meta name="title" content="<?php if (isset($content[$lang . '_meta_title'])) echo $content[$lang . '_meta_title']?>">

    <meta name="description" content="<?php if (isset($content[$lang . '_meta_description'])) echo $content[$lang . '_meta_description']?>">

    <meta name="keywords" content="<?php if (isset($content[$lang . '_meta_keyword'])) echo $content[$lang . '_meta_keyword']?>">

    <script>
        var is_mobile = false;
        var edit_check_js = 0;
        <?php if(custom::is_mobile()){ ?>
            is_mobile = true;
        <?php } ?>
    </script>


    <?php
    /*
     * Ahsan
     * I'm removing rand(); function from assets file (CSS and JS) for only mobile site.
     * Now following variable is using for css and js versions instead of rand(); function.
     *
     * Defined in header and footer
     *
     * */
    // $manual_version = '0.2';
    $manual_version = rand();

    if ($_SERVER['SERVER_NAME'] == 'www.key.sa') { ?>

    <script type="text/javascript" src="//script.crazyegg.com/pages/scripts/0072/3897.js" async></script>

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

    <!--    <script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>-->

    <!--    <script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>-->



    <link rel="stylesheet" href="<?php echo $base_url; ?>/public/frontend/css/jquery-ui_1.12.1_themes.css">

    <link href="<?php echo $base_url; ?>/public/frontend/intTelInput/css/intlTelInput.css" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">

    <link href="<?php echo $base_url; ?>/public/frontend/css/hijri_calendar/jquery.calendars.picker.css" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" type="text/css" media="print" onload="this.media='all'; this.onload=null;">

    <link href="<?php echo $base_url; ?>/public/frontend/select2/css/select2.css" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">

    <link href="<?php echo $base_url; ?>/public/frontend/css/kitKatClock.css?v=<?php echo $manual_version; ?>" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">

    <?php if(custom::show_smart_banner()) { ?>
    <link href="<?php echo $base_url; ?>/jquery-smartbanner/smartbanner.min.css" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">
    <?php } ?>

    <link href="<?php echo $base_url; ?>/public/frontend/css/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">

    {{--    <link href="<?php echo $base_url; ?>/public/frontend/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">--}}
    <link href="<?php echo $base_url; ?>/public/frontend/css/bootstrap@5.3/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">

    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

    <link href="<?php echo $base_url; ?>/public/frontend/css/all.css?v=<?php echo $manual_version; ?>" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">


    <?php if ($lang == 'arb') // CSS For arabic

    { ?>

    <link href="<?php echo $base_url; ?>/public/frontend/css/rtl.css?v=<?php echo $manual_version; ?>" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">

    <?php } ?>

    <link href="<?php echo $base_url; ?>/public/frontend/css/mobile-version.css?v=<?php echo $manual_version; ?>" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">
    <link href="<?php echo $base_url; ?>/public/frontend/css/mobile-updated.css?v=<?php echo $manual_version; ?>" rel="stylesheet" type="text/css" media="print" onload="this.media='all'; this.onload=null;">


    <script src="<?php echo $base_url; ?>/public/frontend/js/jquery-3.7.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js" defer></script>

    <script src="<?php echo $base_url; ?>/public/frontend/js/jquery.lazy.min.js" defer></script>

    <style>

        .pac-container {

            z-index: 9000000000 !important;

        }

        /*body.is_mobile.homePage .homesearch .searchBarSec {
            padding-top: 40px;
        }*/



    </style>



    <script type="text/javascript"

            src="<?php echo $base_url; ?>/public/frontend/js/hijri_calendar/jquery.calendars.min.js" defer></script>

    <script type="text/javascript"

            src="<?php echo $base_url; ?>/public/frontend/js/hijri_calendar/jquery.calendars.islamic.min.js" defer></script>

    <script type="text/javascript"

            src="<?php echo $base_url; ?>/public/frontend/js/hijri_calendar/jquery.calendars.plus.min.js" defer></script>

    <script type="text/javascript"

            src="<?php echo $base_url; ?>/public/frontend/js/hijri_calendar/jquery.plugin.min.js" defer></script>

    <?php if (!custom::is_homepage() || true) { ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api->google_api_key_general; ?>&language=<?php echo ($lang == 'eng' ? 'En' : 'Ar'); ?>&region=SA&libraries=places,geometry" type="text/javascript"></script>

    <script type="text/javascript" src="<?php echo $base_url; ?>/public/frontend/js/hijri_calendar/jquery.calendars.picker.min.js" defer></script>

    <script src="<?php echo $base_url; ?>/public/frontend/js/KitKatClock.min.js" defer></script>

    <script src="<?php echo $base_url; ?>/public/frontend/js/jquery-ui_1.12.1.min.js"></script>

    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/i18n/jquery-ui-i18n.min.js" defer></script>

    <?php } ?>

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

        var logged_in_from_frontend = <?php echo(Session::get('logged_in_from_frontend') == true ? 1 : 0); ?>;

        var show_customer_popup_after_search = '<?php echo(Session::has('show_customer_popup_after_search') ? 1 : 0); ?>';

        var corporate_loyalty = <?php echo(custom::isCorporateLoyalty() ? 1 : 0); ?>;

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
</head>

<body class="<?php echo $lang; ?> <?php echo $home_class; ?> <?php echo($last_segment == 'contact-us' ? 'contUs' : ''); ?> <?php echo(custom::is_mobile() ? 'is_mobile' : ''); ?>" style="display: none;">

<?php if ($_SERVER['SERVER_NAME'] == 'www.key.sa')

{ ?>

<!-- Google Tag Manager (noscript) -->

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K8LWSV8"

                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<!-- End Google Tag Manager (noscript) -->

<?php } ?>





<div class="loaderSpiner">

    <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url; ?>/public/frontend/images/loaderRing.png" alt="Loading" height="196" width="196"/>

</div>

<!-- Modal -->


<?php $getSegment = $lang == 'eng'?Request::segment(2):Request::segment(1);
if(
isset($_REQUEST['mobile']) ||
(isset($_REQUEST['accessed_from_mobile']) && strtolower($_REQUEST['accessed_from_mobile']) == 'yes') ||
$getSegment == 'mada-payment'
){ ?>
<header></header>
<?php }else{ ?>
<header>

    <div class="logoMenuTop">

        <nav class="navbar navbar-default p-0">

            <div class="container maxWidth1330">

                <div class="navbar-header">

                    <div class="navbar-brand logo p-0">
                        <a href="<?php echo $lang_base_url; ?>">
                            <img src="<?php echo $base_url .'/public/uploads/'. $site->site_logo_mobile; ?>" alt="Logo" height="43" width="200"/>
                        </a>
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

            <!-- Collect the nav links, forms, and other content for toggling -->

            <div class="collapse navbar-collapse menuRight" id="bs-example-navbar-collapse-1">



                <ul class="nav navbar-nav navbar-right">

                    <li class="d-none d-md-block"><a href="javascript:void(0);"

                                                     class="hasDropEdSecondary"><?php echo($lang == 'eng' ? 'MENU' : 'القائمة'); ?></a>

                    </li>



                    <li class="hasDropEd">

                        <a href="javascript:void(0);">

                            <button type="button" class="menuIconT" id="mob-menu">

                                <span class="d-none">Toggle navigation</span>

                                <span class="icon-bar jw-icon-bar-1"></span>

                                <span class="icon-bar jw-icon-bar-2"></span>

                                <span class="icon-bar jw-icon-bar-3"></span>

                            </button>

                        </a>

                        <div class="dropDownEd">

                            <button type="button" class="menuIconT" id="closeTopMenu">
                                <span class="icon-bar"></span>

                                <span class="icon-bar"></span>

                            </button>


                            <div class="clearfix"></div>

                            <?php if (Session::get('logged_in_from_frontend') == true){ ?>
                            <ul class="user-menu">
                                <li>
                                    <a href="<?php echo $lang_base_url; ?>/my-profile"><?php echo Session::get('user_name'); ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo $lang_base_url; ?>/my-profile">@lang('labels.profile')</a>
                                </li>
                                <li>
                                    <a href="<?php echo $lang_base_url; ?>/logout">@lang('labels.logout')</a>
                                </li>
                            </ul>
                            <?php } ?>
                            <ul class="jw-mobile-version-side-bar-nav-list">

                                <li class="<?php echo($active_menu == 'home' ? 'active' : ''); ?>">
                                    <a href="<?php echo $lang_base_url; ?>">
                                        <span class="jw-nav-menu-icon jw-nav-menu-icon-home"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/home-icon-1.png" /></span>
                                        <span class="jw-nav-menu">@lang('labels.home')</span>
                                    </a>
                                </li>
                                <li class="<?php echo($active_menu == 'manage_booking' ? 'active' : ''); ?>">
                                    <a href="<?php echo $lang_base_url . '/manageBookings'; ?>">
                                        <span class="jw-nav-menu-icon jw-nav-menu-icon-booking"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/manage-booking-tab-mobile-1.png" /></span>
                                        <span class="jw-nav-menu">
                                        @lang('labels.manage_bookings')</span>
                                    </a>
                                </li>

                                <?php if (Session::get('logged_in_from_frontend') != true){ ?>
                                <li>
                                    <a href="javascript:void(0);" id="openLoginDDB" data-bs-toggle="modal" data-bs-target="#model-login">
                                        <span class="jw-nav-menu-icon jw-nav-menu-icon-user"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/user-login-icon-1.png" /></span>
                                        <span class="jw-nav-menu"><?php echo($lang == 'eng' ? 'User Login' : 'تسجيل دخول'); ?></span>
                                    </a>
                                </li>
                                <?php } ?>
                                <li class="<?php echo($active_menu == 'offers' ? 'active' : ''); ?>">
                                    <a href="<?php echo $lang_base_url . '/offers'; ?>">
                                        <span class="jw-nav-menu-icon jw-nav-menu-icon-offers"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/offers-icon-1.png?v=0.1" /></span>
                                        <span class="jw-nav-menu">
                                        @lang('labels.offers')</span>
                                    </a>
                                </li>
                                <li class="<?php echo($active_menu == 'fleet' ? 'active' : ''); ?>">
                                    <a href="<?php echo $lang_base_url . '/fleet'; ?>">
                                        <span class="jw-nav-menu-icon jw-nav-menu-icon-fleet"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/daily-icon-1-1.png" /></span>
                                        <span class="jw-nav-menu">
                                        @lang('labels.key_fleet')</span>
                                    </a>
                                </li>
                                <li class="jw-side-nav-phone">
                                    <a href="tel:<?php echo $site->site_phone; ?>">
                                        <span class="jw-nav-menu-icon jw-nav-menu-icon-phone"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/phone-icon-1.png" /></span>
                                        <span class="jw-nav-menu"><?php echo $site->site_phone; ?></span>
                                    </a>
                                </li>
                                <li class="full_version">
                                    <a id="webFullVersion" data-fullVersion="1" href="javascript:void(0);">
                                        <span class="jw-nav-menu-icon jw-nav-menu-icon-full_version"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/full-version-icon-1.png" /></span>
                                        <span class="jw-nav-menu"><?php echo ($lang == 'eng' ? 'Visit Full Version' : 'زيارة النسخة الكاملة'); ?></span>
                                    </a>
                                </li>
                                <?php
                                if ($site->site_language == 'both'){
                                $lagUrlStr = custom::changeUrlWithEnAr($segments);
                                ?>
                                <li class="lang">
                                    <a href="javascript:void(0);" id="<?php echo ($lang == 'eng' ? 'arb' : 'eng') . '||' . $lagUrlStr; ?>" class="changeLanguageMobile" title="@lang('labels.change_site_language')">
                                        <span class="lang"></span> <?php echo ($lang == 'eng' ? 'العربية' : 'English'); ?>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>

                            @if($site->refer_and_earn_option == 'on')
                                <div class="btn-share-and-earn-on-subscription-wrapper btn-share-and-earn-on-subscription-in-header">
                                    <a href="<?php echo $lang_base_url . '/my-profile'; ?>">{{($lang == 'eng' ? 'Share & Earn on Subscription' : 'شارك و اكسب مع حجوزات الاشتراك')}}</a>
                                </div>
                            @endif

                            <div class="btn-box jw-btn-box-1">
                                <h4><?php echo ($lang == 'eng' ? 'Download Our App' : 'Download Our App'); ?></h4>
                                <a href="https://play.google.com/store/apps/details?id=comcom.key&hl=en" target="_blank">
                                    <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url; ?>/public/frontend/images/new-images/app-store.png" alt="">
                                </a>
                                <a href="https://itunes.apple.com/us/app/key-car-rental/id1282284664?ls=1&mt=8" target="_blank">
                                    <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url; ?>/public/frontend/images/new-images/android.png" alt="">
                                </a>
                                <a href="https://appgallery.huawei.com/#/app/C103329457" target="_blank">
                                    <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo $base_url; ?>/public/frontend/images/new-images/Huawei.png" alt="">
                                </a>
                            </div>

                            <div class="jw-side-bar-lag-switch-wrapper">
                                <?php
                                if ($site->site_language == 'both'){
                                $lagUrlStr = custom::changeUrlWithEnAr($segments);
                                ?>

                                {{-- one switcher start--}}
                                <div class="topLang jw-topLang">
                                    <a href="javascript:void(0);" id="<?php echo ($lang == 'eng' ? 'arb' : 'eng') . '||' . $lagUrlStr; ?>" class="changeLanguageMobile" title="@lang('labels.change_site_language')">

                                        <img src="<?php echo $base_url; ?>/public/frontend/images/lang-icon-1-1.png" />
                                    </a>
                                </div>
                                {{-- one switcher end--}}
                                <?php } ?>

                            </div>

                            {{-- two switcher start--}}
<!--                            <div class="jw-multi-lang-switch">
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
                                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
                                </script>
                            </div>-->
                            {{-- two switcher end--}}
                        </div>

                    </li>

                </ul>

            </div>
            <?php
            if ($site->site_language == 'both'){
            $lagUrlStr = custom::changeUrlWithEnAr($segments);
            ?>

            <div class="topLang jw-topLang">
                <a href="javascript:void(0);" id="<?php echo ($lang == 'eng' ? 'arb' : 'eng') . '||' . $lagUrlStr; ?>" class="changeLanguageMobile" title="@lang('labels.change_site_language')">

                    <img src="<?php echo $base_url; ?>/public/frontend/images/lang-icon-1-1.png" />
                </a>
            </div>
            <?php } ?>

        </nav>

    <!--        <h3 class="wiz3" style="display: none;"><?php echo ($lang == 'eng'?'Select Dates':'اختر التواريخ ');?></h3>
        <h3 class="wiz4" style="display: none;"><?php echo ($lang == 'eng'?'Select Time':'اختر الوقت');?></h3>-->

    </div>

</header>
<?php } ?>
<script>
    <?php if (isset($_REQUEST['d']) && $_REQUEST['d'] == 1){ ?>
    $('#containsLoginDDB').addClass('open');
    <?php } ?>

    var is_delivery_mode = <?php echo (isset($_REQUEST['delivery']) ? 1 : 0); ?>;
</script>