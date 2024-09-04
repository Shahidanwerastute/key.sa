@extends('frontend.layouts.template')

@section('content')
    <style>

        .subscription_options button.active strong {
            color: #FE7E00 !important;
        }


        .bookPSec .subscription_options button {
            margin: 3px;
        }

        .arb.book-car section.bookingSec .singleRow .bookDtlSec .bookPSec .less-price .del-price:after {
            content: none;
        }

        .arb.book-car section.bookingSec .singleRow .bookDtlSec .bookPSec .less-price .del-price,
        .arb.book-car section.bookingSec .singleRow .bookDtlSec .bookPSec .less-price .del-price .sar_ar {
            text-decoration: line-through;
        }

        @media screen and (max-width: 991px) {
            .book-car .carsGrid .singleRow {
                width: 45%;
            }

            .arb.book-car .carsGrid .singleRow {
                width: 46%;
            }

        }

        @media screen and (max-width: 767px) {
            .book-car .new-header-style .topHeader .topLang a {
                font-size: 12px;
            }
        }

        @media screen and (max-width: 676px) {
            .book-car .carsGrid .singleRow,
            .arb.book-car .carsGrid .singleRow {
                width: 60%;
            }
        }

        @media screen and (max-width: 525px) {
            .book-car .carsGrid .singleRow,
            .arb.book-car .carsGrid .singleRow {
                width: 80%;
            }
        }

        @media screen and (max-width: 425px) {
            .book-car .carsGrid .singleRow,
            .arb.book-car .carsGrid .singleRow {
                width: 100%;
            }
        }

    </style>
    <section class="textBannerSec">
        <div class="container-md">
            <h1>
            <?php echo($lang == 'eng' ? 'Best Deals' : 'أفضل العروض'); ?>
            <!--<strong>s</strong>-->
                <!--<span>login </span>-->
            </h1>
            <p><?php echo($lang == 'eng' ? 'Get the best price for the latest cars models from Key.' : 'أحصل على أفضل سعر لأحدث السيارات من المفتاح.'); ?></p>
            <div class="container-md <?php echo custom::addClass(); ?>">
                <div class="search-main-container-new-design">
                    <?php echo custom::deliveryPickupTabsArea($lang); ?>
                    <div class="searchBarSec">
                        <div class="serText_1">
                            <img src="<?php echo custom::baseurl('/'); ?>/public/frontend/images/search2.png"
                                 height="16" width="16"
                                 alt="Search"/>
                            @lang('labels.search')
                            <span>@lang('labels.for_a_fleet')</span>
                        </div>
                        @include('frontend/layouts/search_area')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bookingSec fleet">
        <div class="container-md">
            <div class="containsData search-new-box carsGrid">
            <?php foreach($car_models as $car_model){ ?>

            <!--                Single Row Start-->
                <div class="singleRow">
                    <div class="imgBox">
                        <div class="listViewCarImg">
                            <img src="<?php echo $base_url; ?>/public/uploads/<?php echo $car_model['image1']; ?>"
                                 alt="car">
                        </div>
                    </div>
                    <div class="bookDtlSec">
                        <div class="bookPSec">
                            <div class="col bookFeature <?php echo($car_model['min_age'] > 0 ? 'contains-min-age' : ''); ?>"
                                 style="height: 355px;">
                                <h2><?php echo $car_model['ct_' . $lang . '_title']; ?> <?php echo $car_model[$lang . '_title']; ?> <?php echo $car_model['year']; ?>
                                    <span><?php echo \Lang::get('labels.or_similar'); ?></span></h2>
                                <h3><?php echo $car_model['cc_' . $lang . '_title']; ?></h3>
                                <div class="gridViewCarImg">
                                    <img src="<?php echo $base_url; ?>/public/uploads/<?php echo $car_model['image1']; ?>" alt="car">
                                </div>
                                <ul>
                                    <li>
                                        <div class="spIconF person"></div>
                                        <p><?php echo $car_model['no_of_passengers']; ?></p></li>
                                    <li>
                                        <div class="spIconF transmition"></div>
                                        <p><?php echo($car_model['transmission'] == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي')); ?></p>
                                    </li>
                                    <li>
                                        <div class="spIconF door"></div>
                                        <p><?php echo $car_model['no_of_doors']; ?></p></li>
                                    <li>
                                        <div class="spIconF bag"></div>
                                        <p><?php echo $car_model['no_of_bags']; ?></p></li>
                                    <?php if ($car_model['min_age'] > 0)
                                    { ?>
                                    <li>
                                        <div class="spIconF minAge"></div>
                                        <p><?php echo $car_model['min_age']; ?></p>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="col bookBtn">
                                <?php if ($show_book_now_btn) { ?>
                                <a href="<?php echo $lang_base_url.'/offer?r='.base64_encode($car_model['id']) . '|' . base64_encode($promotion_id); ?>"><input type="button" class="edBtn" value="@lang('labels.book_now_btn')" ></a>
                                <?php } else { ?>
                                @lang('labels.fleet_search_page_message')
                                <?php } ?>
                            </div>

                            <div class="clearfix"></div>

                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>

                <!--                Single Row End-->
                <?php } ?>
            </div>

        </div>

    </section>
@endsection