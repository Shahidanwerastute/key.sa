@extends('frontend.layouts.template')
@section('content')
    <style>
        .refer-and-earn-page-wrapper {
            text-align: center;
            padding: 28px;
            background-image: url(<?php echo $base_url; ?>/public/frontend/images/refer-page-bg.png);
            background-repeat: no-repeat;
            background-position: bottom right;
            padding-bottom: 250px;
        }
        .arb .refer-and-earn-page-wrapper {
            background-image: url(<?php echo $base_url; ?>/public/frontend/images/refer-page-bg-arb.png);
            background-position: bottom left;
        }
        .refer-and-earn-page-inner-wrapper {

        }
        .refer-and-earn-page-inner-wrapper h2 {
            font-size: 25px;
            font-weight: 600;
            line-height: 30px;
            color: #000000;
        }
        .refer-and-earn-page-inner-wrapper p {
            font-size: 16px;
            font-weight: 500;
            line-height: 18px;
            color: #000000;
        }
        .refer-and-earn-bold-txt  {
            font-size: 17px;
            font-weight: 600;
        }
        .how-it-works-btn {
            font-size: 15px;
            font-weight: 600;
            line-height: 18px;
            color: #FF7F00;
            border: 1px solid #FF7F00;
            border-radius: 5px;
            padding: 5px;
            cursor: pointer;
        }
        .refer-offcanvas {
            height: 85vh !important;
            border-radius: 30px 30px 0 0;
        }
        .referCarousel {
            height: calc(100% - 80px);
            overflow: hidden;
            position: relative;
        }
        .referCarousel .carousel-indicators {
            background: white;
            width: 100%;
            margin: 0;
        }
        .referCarousel .carousel-inner {
            overflow-y: scroll;
            height: 100%;
        }
        .refer-offcanvas  .offcanvas-header .btn-close {
            opacity: 1;
        }
        .carousel-indicators [data-bs-target] {
            opacity: 1;
            background-color: #757575;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
            margin-left: 5px;
        }
        .carousel-indicators .active {
            background-color: #FF7F00;
        }
        .btn-done-custom {
            --bs-btn-close-bg: none;
            display: block;
            width: 218px;
            margin: auto;
            height: auto;
            line-height: 38px !important;
            float: unset !important;
            background-color: #FF7F00;
            color: white;
            opacity: 1;
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translate(-50%);
        }
        .refer-offcanvas .offcanvas-header {
            padding-bottom: 0;
        }
        .refer-offcanvas .offcanvas-body {
            padding-top: 0;
            padding-left: 0;
            padding-right: 0;
        }
        .referCarouselText {
            min-height: 164px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 24px;
            margin-top: 20px;
        }
        .refer-code-input-wrapper {
            position: relative;
            margin-top: 15px;
        }
        .refer-code-input-wrapper input {
            width: 100%;
            border: none;
            background: #F0F0F0;
            border-radius: 10px;
            height: 46px;
            padding-block: 10px;
            padding-inline-end: 40px;
            padding-inline-start: 15px;

        }
        .refer-code-input-wrapper img {
            position: absolute;
            inset-inline-end: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        .quick-share-label {
            position: relative;
            margin-top: 30px;
        }
        .quick-share-label p  {
            width: 84px;
            font-size: 14px;
            font-weight: 500;
            line-height: normal;
            display: flex;
            align-items: center;
            margin: auto;
            padding: 0 4px;
        }
        .quick-share-label p:before {
            content: "";
            border-bottom: 1px solid black;
            width: calc((100vw - 56px) / 2 - 42px);
            display: block;
            position: absolute;
            left: 0;
        }
        .quick-share-label p:after {
            content: "";
            border-bottom: 1px solid black;
            width: calc((100vw - 56px) / 2 - 42px);
            display: block;
            position: absolute;
            right: 0;
        }
        .quick-share-btns-wrapper {
            padding-top: 10px;
        }
        .quick-share-btns-inner-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .quick-share-btns-inner-wrapper-row-2 {
            justify-content: space-evenly;
        }
        .refer-page-btn-done {
            margin-top: 30px;
        }
        .refer-page-btn-done a {
            width: 176px;
            line-height: 41px;
            border-radius: 10px;
            background: #FF7F00;
            color: #fff;
            display: block;
            margin: auto;
            box-shadow: 0 4px 4px rgb(0 0 0 / 25%);
        }
        .referCarouselText p {
            color: #757575;
            font-size: 20px;
            font-weight: 500;
            line-height: 24px !important;
            margin-bottom: 12px;
        }
        .carousel-item-text-left-aligned .referCarouselText{
            align-items: flex-start;
        }
        .referCarouselText p {
            text-align: start;
        }
    </style>
    <section class="refer-and-earn-page-wrapper">
        <div class="refer-and-earn-page-inner-wrapper">
            <h2>{{$data['top_heading']}}</h2>
            <p>{!! $data['top_description'] !!}</p>
            <a class="how-it-works-btn" data-bs-toggle="offcanvas" data-bs-target="#referOffcanvas" aria-controls="referOffcanvas">{{($lang == 'eng' ? 'How it works?' : 'كيف تعمل؟')}}</a>

            <!-- offcanvas starts here -->
            <div class="offcanvas offcanvas-bottom refer-offcanvas" tabindex="-1" id="referOffcanvas" aria-labelledby="referOffcanvasLabel">
                <div class="offcanvas-header">
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body small">

                    <!-- carousel starts here -->
                    <div id="referCarouselCaptions" class="carousel slide referCarousel"  data-bs-ride="carousel" >
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#referCarouselCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#referCarouselCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#referCarouselCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="5000">
                                <h2>{{$data['how_it_works'][0]['title']}}</h2>
                                <img src="<?php echo $base_url; ?>/public/frontend/images/refer-and-earn-img-share.png" alt="" />
                                <div class="referCarouselText">
                                    {!! $data['how_it_works'][0]['description'] !!}
                                </div>
                            </div>
                            <div class="carousel-item carousel-item-text-left-aligned" data-bs-interval="5000">
                                <h2>{{$data['how_it_works'][1]['title']}}</h2>
                                <img src="<?php echo $base_url; ?>/public/frontend/images/refer-and-earn-img-earn.png" alt="" />
                                <div class="referCarouselText">
                                    {!! $data['how_it_works'][1]['description'] !!}
                                </div>

                            </div>
                            <div class="carousel-item" data-bs-interval="5000">
                                <h2>{{$data['how_it_works'][2]['title']}}</h2>
                                <img src="<?php echo $base_url; ?>/public/frontend/images/refer-and-earn-img-friend-earn.png" alt="" />
                                <div class="referCarouselText">
                                    {!! $data['how_it_works'][2]['description'] !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- carousel ends here  -->
                    <button type="button" class="btn-close btn-done-custom" data-bs-dismiss="offcanvas" aria-label="Close">{{($lang == 'eng' ? 'Done' : 'إغلاق')}}</button>
                </div>
            </div>
            <!-- offcanvas starts here -->
            <div class="refer-code-input-wrapper">
                <a href="javascript:void(0);" class="copy" data-content="{{$data['coupon_code']}}"><img src="<?php echo $base_url; ?>/public/frontend/images/copy.png" ></a>
                <input value="{{$data['coupon_code']}}" class="refer-code-input" />
            </div>
            <!-- quick share -->
            <div class="quick-share-label">
                <p>{{($lang == 'eng' ? 'Quick Share' : 'مشاركة من خلال')}}</p>
            </div>
            <div class="quick-share-btns-wrapper">
                <div class="quick-share-btns-inner-wrapper">

                    <a target="_blank" href="whatsapp://send?text={{$data['share_message']}}"><img src="<?php echo $base_url; ?>/public/frontend/images/whatsapp-img.png" /></a>

                    <a target="_blank" href="https://twitter.com/intent/tweet/?text={{$data['share_message']}}&url={{url()->current()}}"><img src="<?php echo $base_url; ?>/public/frontend/images/twitter-img.png" /></a>

                    <a target="_blank" href="javascript:void(0);"><img src="<?php echo $base_url; ?>/public/frontend/images/sms-img.png" /></a>

                </div>
                <div class="quick-share-btns-inner-wrapper quick-share-btns-inner-wrapper-row-2">

                    <a target="_blank" href="https://facebook.com/sharer/sharer.php?u={{url()->current()}}" target="_blank" rel="noopener">
                        <img src="<?php echo $base_url; ?>/public/frontend/images/facebook-img.png" />
                    </a>

                    <a target="_blank" href="https://t.me/share/url?url={{url()->current()}}&text={{$data['share_message']}}"><img src="<?php echo $base_url; ?>/public/frontend/images/telegram-img.png" /></a>

                </div>
            </div>

            <div class="refer-page-btn-done">
                <a href="<?php echo $lang_base_url . '/my-profile'; ?>">{{($lang == 'eng' ? 'Done' : 'إغلاق')}}</a>
            </div>
        </div>
    </section>

<script>
    $(document).on('click', '.copy', function () {
        navigator.clipboard.writeText($(this).data('content'));
        $.alert('Copied!');
    });
</script>
@endsection