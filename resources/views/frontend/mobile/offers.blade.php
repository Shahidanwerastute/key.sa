@extends('frontend.layouts.template')
@section('content')
    <section class="standardPageSec BannerTextSty offersPage">
        <div class="container-md">
            <div class="row">
               <!-- <div class="col-md-6 col-12 col">
                    <a href="#">
                        <img src="https://www.key.sa/public/uploads/ab-00-key-BTS-1500x5411691913112.png" alt="">

                    </a>
                </div>
                <div class="col-sm-6 col-xs-12 col">
                    <a href="#">
                        <img src="<?php //echo $base_url; ?>/public/frontend/images/banner2.png" alt="">
                    </a>
                </div>
                <div class="col-sm-6 col-xs-12 col">
                    <a href="#">
                        <img src="<?php //echo $base_url; ?>/public/frontend/images/banner3.png" alt="">
                    </a>
                </div>
                <div class="col-sm-6 col-xs-12 col">
                    <a href="#">
                        <img src="<?php //echo $base_url; ?>/public/frontend/images/banner4.png" alt="">
                    </a>
                </div> -->

                <?php if (count($sliders) > 0){
                foreach ($sliders as $slider){ ?>
                <div class="col-md-6 col-12 col">
                    <?php if ($slider['clickable'] == 1) { ?>
                    <a href="<?php echo $lang_base_url; ?>/offer?r=<?php echo base64_encode($slider['car_model_id']) . '|' . base64_encode($slider['id']); ?>">
                        <img src="<?php echo custom::baseurl('/'); ?>/public/uploads/<?php echo $slider['image'];?>"
                             alt="Audi"
                             height="640" width="1920"/>
                    </a>
                    <?php } else { ?>
                    <img src="<?php echo custom::baseurl('/'); ?>/public/uploads/<?php echo $slider['image'];?>"
                         alt="Audi"
                         height="640" width="1920"/>
                    <?php } ?>

                </div>
                <?php }
                } else { ?>
                    <h2 style="text-align: center;margin-top: 100px;"><?php echo ($lang == 'eng' ? 'No Offers Available.' : 'لا يوجد عروض حالياً'); ?></h2>
                <?php } ?>

            </div>
        </div>
    </section>
@endsection