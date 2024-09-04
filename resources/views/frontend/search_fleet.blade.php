@extends('frontend.layouts.template')

@section('content')
    <style>
        body.fleet section.search_fleet-booking-sect.fleet .carsGrid .singleRow .bookDtlSec .bookPSec .col.bookFeature {
            width: 100% !important;
            padding: 0 20px;
        }
        body.fleet section.bookingSec .carsGrid .singleRow .bookDtlSec .bookPSec .col.bookBtn {
            bottom: 10px;
        }
    </style>

    <?php $siteSettings = custom::site_settings(); ?>
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
                    <?php echo custom::deliveryPickupTabsArea($lang);?>
                    <div class="searchBarSec">
                        <div class="serText_1">
                            @lang('labels.book')
                            <span>@lang('labels.car')</span>
                        </div>
                        @include('frontend/layouts/search_area')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bookingSec fleet search_fleet-booking-sect">
        <div class="container-md">
            <div class="containsData search-new-box <?php echo($siteSettings->website_cars_listing_style == 'grid' ? 'carsGrid' : 'carsList'); ?>">
                <?php foreach($car_models as $car_model){
                $items = [];
                $item_data = [
                    'item_id' => $car_model['ct_eng_title'] . " " . $car_model['eng_title'] . " " . $car_model['year'],
                    'item_name' => $car_model['ct_eng_title'] . " " . $car_model['eng_title'] . " " . $car_model['year']
                ];
                $items[] = $item_data;
                $event = 'begin_checkout';
                $event_data = [
                    'currency' => 'SAR',
                    'value' => '1',
                    'items' => $items
                ];
                custom::sendEventToGA4($event, $event_data);
                    ?>
                <div class="singleRow">
                    <div class="imgBox">
                        <div class="listViewCarImg">
                            <img src="<?php echo $base_url; ?>/public/uploads/<?php echo $car_model['image1']; ?>"
                                 alt="{{ $lang == 'eng' ? $car_model['image1_eng_alt'] : $car_model['image1_arb_alt'] }}" height="132" width="274"/>
                        </div>
                    </div>
                    <div class="bookDtlSec">
                        <div class="bookPSec">
                            <div class="col bookFeature <?php echo($car_model['min_age'] > 0 ? 'contains-min-age' : ''); ?>">
                                <h2><?php echo $car_model['ct_' . $lang . '_title']; ?> <?php echo $car_model[$lang . '_title']; ?> <?php echo $car_model['year']; ?>
                                    <span><?php echo \Lang::get('labels.or_similar'); ?></span>
                                </h2>
                                <h3><?php echo $car_model['cc_' . $lang . '_title']; ?></h3>
                                <div class="gridViewCarImg">
                                    <img src="<?php echo $base_url; ?>/public/uploads/<?php echo $car_model['image1']; ?>"
                                         alt="{{ $lang == 'eng' ? $car_model['image1_eng_alt'] : $car_model['image1_arb_alt'] }}">
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
                                @lang('labels.fleet_search_page_message')
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php } ?>
            </div>

        </div>

    </section>
@endsection