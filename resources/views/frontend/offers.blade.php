@extends('frontend.layouts.template')

@section('content')
    <section class="textBannerSec">
        <div class="container-md">
            <h1><?php echo($lang == 'eng' ? 'Offers' : 'عروض'); ?></h1>
        </div>
    </section>
    <section class="standardPageSec BannerTextSty offersPage">
        <div class="container-md">
            <div class="row">

                <?php if (count($sliders) > 0)
                {
                foreach ($sliders as $slider){ ?>
                <div class="col-md-12 gapBottom">
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
                <h2 style="text-align: center;margin-top: 100px;"><?php echo($lang == 'eng' ? 'No Offers Available.' : 'لا يوجد عروض حالياً'); ?></h2>
                <?php } ?>

            </div>
        </div>
    </section>

@endsection