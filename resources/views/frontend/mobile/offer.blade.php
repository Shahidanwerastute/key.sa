@extends('frontend.layouts.template')
@section('content')
    <section class="textBannerSec searchBarSec add">
        <div class="container <?php echo custom::addClass(); ?>">
            <?php //echo custom::deliveryPickupTabsArea($lang); ?>
            @include('frontend/mobile/layouts/search_area')
        </div>
    </section>
    <section class="bookingSec fleet">
        <div class="container">
            <?php foreach($car_models as $car_model){ ?>
            <div class="singleRow">
                <h2>
                    <?php echo $car_model['ct_'.$lang.'_title']; ?> <?php echo $car_model[$lang.'_title']; ?> <?php echo $car_model['year']; ?>
                </h2>
                <h3><?php echo $car_model['cc_'.$lang.'_title']; ?></h3>
                <div class="imgBox">
                    <img src="<?php echo $base_url; ?>/public/uploads/<?php echo $car_model['image1']; ?>" alt="car"/>
                </div>
                <div class="col bookFeature <?php echo ($car_model['min_age'] > 0 ? 'contains-min-age' : ''); ?>">
                    <h4>@lang('labels.features')</h4>
                    <ul>
                        <li><div class="spIconF person"></div>		<p><?php echo $car_model['no_of_passengers']; ?></p>		</li>
                        <li><div class="spIconF transmition"></div>	<p><?php echo ($car_model['transmission'] == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي')); ?></p>		</li>
                        <li><div class="spIconF door"></div>		<p><?php echo $car_model['no_of_doors']; ?></p>		</li>
                        <li><div class="spIconF bag"></div>			<p><?php echo $car_model['no_of_bags']; ?></p>		</li>
                        <?php if ($car_model['min_age'] > 0){ ?>
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
            </div>
            <?php } ?>
        </div>
    </section>
@endsection