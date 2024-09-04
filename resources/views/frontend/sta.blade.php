@extends('frontend.layouts.template')



@section('content')

    <section class="">

        <div class="">
            <style>
                .new-header-style{
                    border-bottom: 3px solid #f2f2f2;
                }
                .banner-heading{
                    margin-top: 60px;
                    margin-bottom: 70px;
                    position: relative;
                }
                .banner-heading:before{
                    content: '';
                    position: absolute;
                    right: 0;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 135px;
                    height: 172px;
                    background: url('/public/frontend/images/banner-b.png');
                    background-size: 100%;
                }
                .banner-heading img{
                    max-width: 100%;
                    width: 100%;
                }
                .banner-heading h2,
                .banner-heading h3 {
                    color: #FE7E00;
                    margin-bottom: 20px;
                    font-size: 30px;
                    font-weight: bold;
                    margin-top: 0;
                    margin-bottom: 50px;
                }
                .body-content-box {
                    margin-bottom: 40px;
                }
                .body-content-box h2,
                .body-content-box h3 {
                    font-size: 24px;
                    color: #FE7E00;
                    margin-top: 0;
                    margin-bottom: 5px;
                }
                .body-content-box p{
                    font-size: 18px;
                    color: #000;
                    margin-bottom: 30px;
                }
                .body-content-box ul{
                    margin-bottom: 70px;
                    padding-left: 24px;
                }
                .body-content-box ul li{
                    font-size: 18px;
                    color: #000;
                    list-style: disc;

                }




                .image-main-wrapper{
                    position: relative;
                }
                .image-main-wrapper img{
                    max-width: 100%;
                    width: 100%;
                }
                .buttons-wrapper{
                    position: absolute;
                    top: 38%;
                    right: 32%;
                    transform: translateX(40%);
                }
                .buttons-wrapper .btn-box{
                    margin-bottom: 15px;
                }
                .buttons-wrapper img{
                    max-width: 250px;
                }
                @media screen and (max-width: 1199px) {
                    .buttons-wrapper{
                        position: absolute;
                        top: 50%;
                        right: 22%;
                        transform: translateX(40%);
                    }
                }
                @media screen and (max-width: 767px) {
                    .buttons-wrapper img{
                        max-width: 170px;
                    }
                }
                @media screen and (max-width: 575px) {
                    .buttons-wrapper img{
                        max-width: 150px;
                    }
                }
                @media screen and (max-width: 480px) {
                    .buttons-wrapper img{
                        max-width: 100px;
                    }
                }
            </style>
            <div class="banner-heading">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h2><?php echo $content[$lang.'_title']; ?></h2>
                            <img src="<?php echo $base_url; ?>/public/uploads/<?php echo $content['banner_image']; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="body-content">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="body-content-box">
                                <?php echo $content[$lang.'_desc']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="image-main-wrapper">
                <img src="<?php echo (custom::is_mobile() ? $base_url . '/public/frontend/images/mobAppBgMobile.png' : $base_url . '/public/frontend/images/mobAppBg.png'); ?>" alt="">
                <div class="buttons-wrapper">
                    <div class="btn-box">
                        <a href="https://appgallery.huawei.com/#/app/C103329457" target="_blank"><img src="<?php echo $base_url; ?>/public/frontend/images/huawei.png" alt=""></a>
                    </div>
                    <div class="btn-box">
                        <a href="https://play.google.com/store/apps/details?id=comcom.key&hl=en" target="_blank"><img src="<?php echo $base_url; ?>/public/frontend/images/google.png" alt=""></a>
                    </div>
                    <div class="btn-box">
                        <a href="https://apps.apple.com/us/app/key-car-rental/id1282284664?ls=1" target="_blank"><img src="<?php echo $base_url; ?>/public/frontend/images/apple.png" alt=""></a>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection