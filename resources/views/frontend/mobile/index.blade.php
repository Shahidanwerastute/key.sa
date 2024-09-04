@extends('frontend.layouts.template')

@section('content')
    <?php //echo '<pre>';print_r($sliders);exit();
    $site = custom::site_settings();
    $segments = Request::segments();
    $last_segment = end($segments);
    if ($last_segment == 'home' || $last_segment == 'en' || $last_segment == '') {
        $home_class = 'homesearch';
    }else{
        $home_class = '';
    }
    ?>

    <section class="homeSlider" style="<?php echo (custom::is_homepage() ? 'z-index: 1;' : ''); ?>">
        <?php if(!isset($_REQUEST['pickup']) && !isset($_REQUEST['delivery']) && !isset($_REQUEST['hourly']) && !isset($_REQUEST['monthly']) && !isset($_REQUEST['weekly']) && !isset($_REQUEST['subscription'])){ ?>

        <?php
        $classes_array = [1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five'];

        $no = 1;
        if ($site->delivery_mode == 'on') $no++;
        if ($site->hourly_renting_mode == 'on') $no++;
        if ($site->monthly_renting_mode == 'on') $no++;
        if ($site->weekly_renting_mode == 'on') $no++;
        if ($site->subscription_renting_mode == 'on') $no++;

        ?>

        <div class="jw-home-screen" style="background-image: url('<?php echo $base_url; ?>/public/frontend/images/bg-home1-1.png');">
            <div id="homeSlider" class="carousel slide" data-bs-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <?php foreach ($sliders as $count => $slider){
                    $active = "";
                    $slider_url = "";
                    if ($count == 0) {
                        $active = "active";
                    }
                    $slider_url = $slider['url'];
                    ?>
                    <div class="carousel-item <?php echo $active; ?>" style="height:162px; text-align: center">
                        <!--	Link Here	-->
                        <?php if($slider_url != ''){ ?>
                        <a href="<?php echo $slider_url;?>" target="_blank" style="background-image: url(<?php echo custom::baseurl('/'); ?>/public/uploads/<?php echo $slider['image'];?>); "></a>
                        <?php }else{ ?>
                        <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="<?php echo custom::baseurl('/'); ?>/public/uploads/<?php echo $slider['image'];?>" alt="Slider" width="90%" height="100%" style=" border-radius: 10px"/>
                        <?php }
                        if ($slider['clickable'] == 0){ ?>
                    <!--	Slider Caption START Here	-->
                        <div class="carousel-caption  d-none d-md-block <?php echo ($slider['desc'] == '' ? 'hide' : ''); ?>">
                            <div class="container maxWidth1330">
                                <div>
                                    <?php echo $slider['desc']; ?>
                                </div>
                            </div>
                        </div>
                        <!--	Slider Caption END Here	-->
                        <?php }else{ ?>
                        <div class="btnbooking-holder">
                            <a href="<?php echo $lang_base_url; ?>/offer?r=<?php echo base64_encode($slider['car_model_id']) . '|' . base64_encode($slider['id']); ?>" class="btn-book-now"><?php echo ($lang == 'eng' ? 'Rent Now' : 'استأجر الآن'); ?></a>
                        </div>
                        <?php } ?>

                    </div>
                    <?php } ?>
                </div>
                <!-- Controls -->
                <?php if (count($sliders) > 1)
                { ?>
                <a class="left carousel-control" href="#homeSlider" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                </a>
                <a class="right carousel-control" href="#homeSlider" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                </a>
                <?php } ?>

            </div>

            <div class="<?php echo $home_class; ?> ">
                <?php //echo custom::deliveryPickupTabsArea($lang); ?>
                <div class="searchBarSec banner_btns <?php echo $lang; ?>">

                    <div class="home-tabs cls-<?php echo $classes_array[$no]; ?>">

                        <div class="booking-mode-tabs">
                            <div class="booking-mode-tabs-item jw-booking-mode-tabs-item-daily"><a href="<?php echo $lang_base_url; ?>/?pickup=1&t=pickup"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/daily-icon-1-1.png" /></a><span><?php echo($lang == 'eng' ? $site->daily_tab_title_eng : $site->daily_tab_title_arb);?></span></div>
                            <?php if($site->weekly_renting_mode == 'on'){ ?>
                            <div class="booking-mode-tabs-item jw-booking-mode-tabs-item-weekly"><a href="<?php echo $lang_base_url; ?>/?weekly=1&t=pickup"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/weekly-icon-1-1.png" /></a><span><?php echo($lang == 'eng' ? $site->weekly_tab_title_eng : $site->weekly_tab_title_arb);?></span></div>
                            <?php } ?>
                            <?php if($site->monthly_renting_mode == 'on'){ ?>
                            <div class="booking-mode-tabs-item jw-booking-mode-tabs-item-monthly"><a href="<?php echo $lang_base_url; ?>/?monthly=1&t=pickup"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/monthly-icon-1-1.png" /></a><span><?php echo($lang == 'eng' ? $site->monthly_tab_title_eng : $site->monthly_tab_title_arb);?></span></div>
                            <?php } ?>
                            <?php if($site->delivery_mode == 'on'){ ?>
                            <div class="booking-mode-tabs-item jw-booking-mode-tabs-item-delivery"><a href="<?php echo $lang_base_url; ?>/?delivery=1"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/car-delivery-mobile-1.png" /></a><span><?php echo($lang == 'eng' ? $site->delivery_tab_title_eng : $site->delivery_tab_title_arb);?></span></div>
                            <?php } ?>
                            <?php if($site->hourly_renting_mode == 'on'){ ?>
                            <div class="booking-mode-tabs-item jw-booking-mode-tabs-item-hourly"><a href="<?php echo $lang_base_url; ?>/?hourly=1"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/daily-rent-1.png" /></a><span><?php echo($lang == 'eng' ? $site->hourly_tab_title_eng : $site->hourly_tab_title_arb);?></span></div>
                            <?php } ?>

                            <?php if($site->subscription_renting_mode == 'on'){ ?>
                            <div class="booking-mode-tabs-item jw-booking-mode-tabs-item-subscription"><a href="<?php echo $lang_base_url; ?>/?subscription=1&t=pickup"><img src="<?php echo $base_url; ?>/public/frontend/images/new-images/subscription-icon-1.png" /></a><span><?php echo($lang == 'eng' ? $site->subscription_tab_title_eng : $site->subscription_tab_title_arb);?></span></div>
                            <?php } ?>
                        </div>

                        <div class="manage-booking-tab">
                            <a href="<?php echo $lang_base_url; ?>/manageBookings"><i class="mobile-tab-btn mobile-manage-booking-icon"></i><span><?php echo($lang == 'eng' ? 'Manage Booking' : 'إدارة الحجوزات');?></span></a>
                        </div>

                    </div>

                    <div class="app-btns">
                        <div class="select-rating-box download-apps-title">
                            <h4><?php echo ($lang == 'eng' ? 'Download Our App' : 'حمل تطبيق المفتاح'); ?></h4>
                        </div>
                        <div class="frame app-box-icons jw-app-box-icons">
                            <a href="https://itunes.apple.com/us/app/key-car-rental/id1282284664?ls=1&amp;mt=8" target="_blank">
                                <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="https://kra.ced.sa/public/frontend/images/new-images/app-store.png" alt="">
                            </a>
                            <a href="https://play.google.com/store/apps/details?id=comcom.key&amp;hl=en" target="_blank">
                                <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="https://kra.ced.sa/public/frontend/images/new-images/android.png" alt="">
                            </a>
                            <a href="https://appgallery.huawei.com/#/app/C103329457" target="_blank">
                                <img src=<?php echo custom::baseurl('/'); ?>/loader.png?v=0.1" class="lazy" data-src="https://kra.ced.sa/public/frontend/images/new-images/Huawei.png" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php }else{
            if(isset($_REQUEST['pickup']) || isset($_REQUEST['hourly']) || isset($_REQUEST['monthly']) || isset($_REQUEST['weekly']) || isset($_REQUEST['subscription'])){
                $bg_img = '/public/uploads/bg-booking.png';
            }else{
                $bg_img = '/public/uploads/bg2.jpg';
            }
            ?>
        <div class="bg-booking wiz1 wiz2" style="background-image: url(<?php echo custom::baseurl('/').$bg_img; ?>"></div>
        
           <div class="<?php echo custom::addClass(); ?>">
            <?php //echo custom::deliveryPickupTabsArea($lang); ?>
            <div class="searchBarSec">
                @include('frontend/mobile/layouts/search_area')
            </div>
        </div>
        <?php } ?>
        <!-- Below code is here because we need to hardcode the values for delivery mode and all ok only on home page, so on other pages we can use the selected values -->
        <!-- --------------------------------------- -->

    </section>
    <main id="main" class="mainHomeEd">
        <!--<div id="forScrollHelp"></div>-->
        <div class="container">

        </div>
    </main>



@endsection