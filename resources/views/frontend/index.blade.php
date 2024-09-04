@extends('frontend.layouts.template')

@section('content')
    <?php //echo '<pre>';print_r($sliders);exit();
    $site = custom::site_settings(); ?>
    <section class="homeSlider">
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
                <div class="carousel-item <?php echo $active; ?>">
                    <!--	Link Here	-->
                    <?php if($slider_url != ''){ ?>
                    <a href="<?php echo $slider_url;?>" target="_blank">
                    <img src="<?php echo custom::baseurl('/'); ?>/public/uploads/<?php echo ($slider['image'] == null || $slider['image'] == '' ?'ab-ab-Riyadh_214941562281495184060.jpg':$slider['image']);?>" alt="<?php echo $slider['alt'];?>" height="541" width="1500"/>
                    </a>
                    <?php }else{ ?>
                    <img src="<?php echo custom::baseurl('/'); ?>/public/uploads/<?php echo $slider['image'];?>" alt="<?php echo $slider['alt'];?>" height="541" width="1500"/>
                    <?php }
                    if ($slider['clickable'] == 0)
                    { ?>
                    <!--	Slider Caption START Here	-->
                    <div class="carousel-caption <?php echo ($slider['desc'] == '' ? 'hide' : ''); ?>">
                        <div class="container maxWidth1330">
                            <div>
                                <?php echo $slider['desc']; ?>
                            </div>
                        </div>
                    </div>
                    <!--	Slider Caption END Here	-->
                    <?php }else{ ?>
						<a href="<?php echo $lang_base_url; ?>/offer?r=<?php echo base64_encode($slider['car_model_id']) . '|' . base64_encode($slider['id']); ?>" class="btn-book-now"><?php echo ($lang == 'eng' ? 'Rent Now' : 'استأجر الآن'); ?></a>
						<?php } ?>

                </div>
                <?php } ?>
            </div>
            <!-- Controls -->
            <?php if (count($sliders) > 1)
                { ?>
{{--            <a class="left carousel-control" href="#homeSlider" role="button" data-slide="prev">--}}
{{--                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>--}}
{{--            </a>--}}
{{--            <a class="right carousel-control" href="#homeSlider" role="button" data-slide="next">--}}
{{--                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>--}}
{{--            </a>--}}

            <button class="carousel-control-prev left carousel-control" type="button" data-bs-target="#homeSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next right carousel-control" type="button" data-bs-target="#homeSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
            </button>
            <?php } ?>

        </div>
        <div class="container-md <?php echo custom::addClass(); ?>">
            <div class="search-main-container-new-design">
                <?php echo custom::deliveryPickupTabsArea($lang); ?>
                <div class="searchBarSec">
                    <div class="serText_1">
                        <img src="<?php echo custom::baseurl('/'); ?>/public/frontend/images/search2.png" height="16" width="16"
                             alt="Search"/>
                        @lang('labels.search')
                        <span>@lang('labels.for_a_fleet')</span>
                    </div>
                    @include('frontend/layouts/search_area')
                </div>
            </div>
        </div>

        <!-- Below code is here because we need to hardcode the values for delivery mode and all ok only on home page, so on other pages we can use the selected values -->
        <!-- --------------------------------------- -->
        <?php /*$site = custom::site_settings(); */?><!--
        <script>

            var delivery_mode = '<?php /*echo $site->delivery_mode; */?>';
            if (delivery_mode == 'off')
            {
                $(".isDeliveryMode").val(0);
                $(".allIsOkForPickup").val(1);
                $(".allIsOkForDropoff").val(1);

                $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');
                $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');

                $(".from_branch_field_for_pickup").attr("name", "from_branch_name");
                $(".to_branch_field_for_pickup").attr("name", "to_branch_name");
                $(".from_branch_field_for_pickup, .to_branch_field_for_pickup").addClass("required-for-search");

                $(".from_branch_field_for_delivery, .to_branch_field_for_delivery").removeAttr("name");
                $(".from_branch_field_for_delivery, .to_branch_field_for_delivery").removeClass("required-for-search");
            } else if (delivery_mode == 'on')
            {
                $('.isDeliveryMode').val(1);
                $('.allIsOkForPickup').val(0);
                $('.allIsOkForDropoff').val(0);

                $('.pickup_mode_pickup, .pickup_mode_dropoff').addClass('hide');
                $('.delivery_mode_pickup, .delivery_mode_dropoff').removeClass('hide');

                $('.from_branch_field_for_delivery').attr('name', 'from_branch_name');
                $('.to_branch_field_for_delivery').attr('name', 'to_branch_name');
                $('.from_branch_field_for_delivery, .to_branch_field_for_delivery').addClass('required-for-search');

                $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeAttr('name');
                $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeClass('required-for-search');
            }
        </script>-->
        <!-- --------------------------------------- -->
        



    </section>
    <main id="main" class="mainHomeEd home-new-changing-box">
        <div id="forScrollHelp"></div>
        <div class="container-md">
            <div class="three-colHome">
                <a href="<?php echo $home_content['b1_' . $lang . '_url']; ?>">
                    <div class="col">
                        <div class="imgBg" style="background-image: url(<?php echo custom::baseurl('/'); ?>/public/uploads/<?php echo $home_content['b1_image']; ?>);">
                            <div class="caption">
                                <h2>
                                    <span><?php echo $home_content['b1_' . $lang . '_small_title']; ?></span> <?php echo $home_content['b1_' . $lang . '_title']; ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="<?php echo $home_content['b2_' . $lang . '_url']; ?>">
                    <div class="col p-30">
                        <div class="imgBg" style="background-image: url(<?php echo custom::baseurl('/'); ?>/public/uploads/<?php echo $home_content['b2_image']; ?>);">
                            <div class="caption">
                                <h2><?php echo $home_content['b2_' . $lang . '_title']; ?>
                                    <span><?php echo $home_content['b2_' . $lang . '_small_title']; ?></span></h2>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="<?php echo $home_content['b3_' . $lang . '_url']; ?>">
                    <div class="col">
                        <div class="imgBg" style="background-image: url(<?php echo custom::baseurl('/'); ?>/public/uploads/<?php echo $home_content['b3_image']; ?>);">
                            <div class="caption">
                                <h2>
                                    <span><?php echo $home_content['b3_' . $lang . '_small_title']; ?></span> <?php echo $home_content['b3_' . $lang . '_title']; ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </div>
        <div class="container">
            <article class="intro">
                <div class="img-holder">
                    <?php
                    if ($lang == 'eng') {
                        echo '<img src="' . custom::baseurl('/') . '/public/frontend/images/logo-identity.png?v=0.1" alt="">';
                    } else {
                        echo '<img src="' . custom::baseurl('/') . '/public/frontend/images/logo-identity_arb.png?v=0.1" alt="">';
                    }
                    ?>

                </div>

                <div class="desc">
                    <?php echo $home_content[$lang . '_home_des']; ?>
                </div>
                <div class="bookRefHome">
                    <h2>@lang('labels.manage') <!-- <span>@lang('labels.my_booking')</span> --> </h2>
                    <div class="bkForm">
                       <!-- <label>@lang('labels.booking_reference')</label> -->
                        <input type="text" placeholder="@lang('labels.type')" class="booking_reference_no_for_home">
                        <?php if ($site->maintenance_mode == 'on')
                        { ?>
                        <input type="button" class="subBtnTv" onclick="siteUnderMaintenance();">
                        <?php }else{ ?>
                        <input type="button" class="subBtnTv manageBookingFromHome" value="<?php echo ($lang == 'eng' ? 'Search' : 'البحث'); ?>" placeholder="<?php echo ($lang == 'eng' ? 'Search' : 'البحث'); ?>">
                        <?php } ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </article>
        </div>
    </main>
    @include('frontend/layouts/footer_social_block')
@endsection
