@extends('frontend.layouts.template')

@section('content')
    <style>
        .lp-wrapper {
            background-color: #ffffff;
            padding: 60px 0;
            border-top: 1px solid #e7e7e7;
        }

        .lp-heading, .lp-sub-heading {
            text-align: center;
            padding-bottom: 10px;
        }

        .lp-heading h1 {
            font-size: 24px;
            color: #FE7E00;
            font-weight: 700;
            margin: 0;
        }

        .lp-sub-heading h2 {
            font-size: 16px;
            margin: 0;
        }

        .lp-keycard-box {
            min-height: 475px;
            border: 1px solid #d8d8d8;
            border-radius: 40px;
            margin-bottom: 30px;
            cursor: pointer;
            transition: transform .2s;
        }

        .lp-keycard-box:hover {
            transform: scale(1.06);
        }

        .lp-pt-40 {
            padding-top: 40px;
        }

        .lp-pt-80 {
            padding-top: 80px;
        }

        .lp-pt-20 {
            padding-top: 20px;
        }

        .lp-card-heading {
            background-color: #FE7E00;
            border-radius: 40px 40px 170px 170px;
            padding: 30px 20px 80px 20px;
            text-align: center;
        }

        .lp-card-heading h2 {
            font-size: 20px;
            color: #ffffff;
            margin-top: 0;
        }

        .lp-card-image {
            text-align: center;
            padding: 20px 20px 10px 20px;
            margin-top: -95px;
        }

        .lp-card-image img {
            max-width: 100%;
        }

        .lp-card-content {
            padding: 0 20px 20px 20px;
        }

        .lp-card-content h2 {
            font-size: 16px;
            color: #FE7E00;
            margin: 0;
            text-align: center;
            font-weight: 700;
            min-height: 55px;
            padding-bottom: 15px;
        }

        .lp-card-content ul {
            padding-right: 25px;
        }

        .lp-card-content ul li {
            list-style-type: disc;
            color: #6f6f6e;
        }

        .lp-card-content a {
            background-color: #6f6f6e;
            border-radius: 30px;
            color: #ffffff;
            padding: 0 70px;
        }

        .lp-readmore {
            text-align: center;
            padding-top: 20px;
        }

        .lp-readmore-orange {
            text-align: center;
            background-color: #FE7E00;
            border-radius: 30px;
            padding: 0 70px;
            color: #ffffff;
        }

        .lp-brand-logos {
            background-color: #f2f2f2;
            border-radius: 30px;
            padding: 20px 30px;
            text-align: center;
        }

        .readmore-btn a {
            color: white;
        }

        .readmore-btn {
            text-align: center;
            margin-top: 30px;
        }

        .heading-right {
            text-align: right !important;
        }

        .lp-list-content ul li {
            list-style-type: disc;
            color: #000000;
            font-size: 18px;
        }

        .lp-list-content ul {
            padding-right: 25px;
        }

        .lp-data-table thead th, .lp-data-table tbody td {
            text-align: center;
            font-size: 16px;
            padding: 12px !important;
        }

        .lp-data-table tbody td img {
            max-width: 25px;
        }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f2f2f2 !important;
        }

        .highlight-first {
            border-top: 2px solid #FF8316 !important;
            border-left: 2px solid #FF8316 !important;
            border-right: 2px solid #FF8316 !important;
        }

        .highlight-last {
            border-bottom: 2px solid #FF8316 !important;
            border-left: 2px solid #FF8316 !important;
            border-right: 2px solid #FF8316 !important;
        }

        .highlight {
            border-left: 2px solid #FF8316 !important;
            border-right: 2px solid #FF8316 !important;
        }

        /* Decorations */
        .col-sm-2 {
            text-align: center;
        }

        /*Back to top button - START*/
        #loyalty-back-to-top-button {
            display: inline-block;
            background-color: #FF9800;
            width: 50px;
            height: 50px;
            text-align: center;
            border-radius: 4px;
            position: fixed;
        {{--    bottom: 30px;--}}
        {{--<?php echo ($lang == 'eng' ? 'right' : 'left');  ?>: 10px;--}}
            inset-inline-end: 10px;
            inset-block-end: 141px;
            transition: background-color .3s,
            opacity .5s, visibility .5s;
            opacity: 0;
            visibility: hidden;
            z-index: 1000;
            text-decoration: none;
        }

        #loyalty-back-to-top-button::after {
            content: "\f077";
            font-family: FontAwesome;
            font-weight: normal;
            font-style: normal;
            font-size: 2em;
            line-height: 50px;
            color: #fff;
        }

        #loyalty-back-to-top-button:hover {
            cursor: pointer;
            background-color: #333;
        }

        #loyalty-back-to-top-button:active {
            background-color: #555;
        }

        #loyalty-back-to-top-button.show {
            opacity: 1;
            visibility: visible;
        }

        .panel-title > a {
            display: block;
            position: relative;
            padding-left: 50px;
        }

        .panel-title > a:after {
            content: "\f078"; /* fa-chevron-down */
            font-family: 'FontAwesome';
            position: absolute;
            left: 0;
        }

        .panel-title > a[aria-expanded="true"]:after {
            content: "\f077"; /* fa-chevron-up */
        }

        .panel, .panel-default > .panel-heading {
            background: #FFFFFF !important;
            box-shadow: 0px 5px 16px rgba(8, 15, 52, 0.06) !important;
            border-radius: 18px !important;
            border: none !important;
        }

        .panel-default > .panel-heading {
            box-shadow: none !important;
        }

        .panel-group .panel {
            margin-bottom: 20px !important;
            padding: 30px;
        }

        .panel-group .panel-heading + .panel-collapse > .panel-body, .panel-group .panel-heading + .panel-collapse > .list-group {
            border-top: none !important;
        }

        .lp-active-panel {
            border: 2px solid #FF8316 !important;
        }

        .loyalty-faq {
            color: #FF8316 !important;
        }

        .panel-title > a[aria-expanded="true"]:after {
            background-color: #FF8316;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            color: white;
            padding: 3px 7px;
        }

        a {
            text-decoration: none !important;
        }

        .lp-faq {
            margin-top: 40px;
        }

        .lp-pb-60 {
            padding-bottom: 60px;
        }

        .lp-bg-art {
            background: url("{{$base_url . '/public/frontend/images/faq-section-top-right-bg.png'}}") no-repeat top right, url("{{$base_url . '/public/frontend/images/faq-section-btm-left-bg.png'}}") no-repeat bottom left;

        }

        .eng .lp-card-content ul {
            padding-left: 25px;
            padding-right: unset;
        }

        .eng .heading-right {
            text-align: left !important;
        }

        .eng .panel-title > a {
            padding-left: unset;
            padding-right: 50px;
        }

        .eng .panel-title > a:after {
            left: unset;
            right: 0;
        }

        .eng .panel-title > a[aria-expanded="true"]:after {
            padding: 5px 8px;
        }

        @media (max-width: 1440px) {
            .lp-bg-art {
                background-size: 7%;
            }
        }

        @media (min-width: 500px) {
            #loyalty-back-to-top-button {
                margin: 30px;
            }
        }

        /*Back to top button - END*/

        @media (min-width: 768px) {
            .ten-columns {
                display: flex;
                align-items: center;
            }

            .ten-columns div {
                border-left: 1px solid #b0b0b0;
            }

            .ten-columns div:last-child {
                border-left: unset;
            }

            .ten-columns > .col-sm-2 {
                width: 20%;
            }

            .ten-columns > .col-sm-2 > img {
                max-width: 100%;
            }

            .eng .ten-columns div {
                border-right: 1px solid #b0b0b0;
                border-left: unset;
            }

            .eng .ten-columns div:last-child {
                border-right: unset;
            }
        }

        @media (max-width: 767px) {
            .ten-columns {
                display: flex;
                align-items: center;
            }

            .ten-columns > .col-sm-2 {
                width: 20%;
            }

            .ten-columns > .col-sm-2 > img {
                max-width: 100%;
            }

            .lp-bg-art {
                background: unset !important;
            }

        }

        @media (max-width: 575px) {
            .lp-data-table thead th, .lp-data-table tbody td {
                font-size: 12px;
            }
        }
    </style>

    <!-- Back to top button -->
    <a id="loyalty-back-to-top-button"></a>

    <section class="lp-wrapper">
        <div class="container-md">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="lp-heading">
                        <h1>{{ $content[$lang . '_title'] }}</h1>
                    </div>
                    <div class="lp-sub-heading">
                        <h2>{{ $content[$lang . '_sub_title'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="row lp-pt-40">
                @foreach($cards as $card)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                        <div class="lp-keycard-box">
                            <div class="lp-card-heading">
                                <h2>{{$lang == 'eng' ? $card->eng_title : $card->arb_title}}</h2>
                            </div>
                            <div class="lp-card-image">
                                <img src="{{$base_url . '/public/uploads/' . $card->image}}"
                                     alt="{{$lang == 'eng' ? $card->eng_title : $card->arb_title}}">
                            </div>
                            <div class="lp-card-content">
                                {!! $lang == 'eng' ? $card->eng_description : $card->arb_description !!}
                                <div class="lp-readmore">
                                    <a href="javascript:void(0);"
                                       onclick="show_benefits({{$card->id}});">{{($lang == 'eng' ? 'Learn More' : 'اقرأ أكثر')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row lp-pt-40">
                <div class="col-lg-12 col-md-12">
                    <div class="lp-heading">
                        <h1>{{ $content[$lang . '_reward_programs_heading'] }}</h1>
                    </div>
                </div>
            </div>
            <div class="row lp-pt-20">
                <div class="col-lg-12 col-md-12">
                    <div class="lp-brand-logos">
                        <div class="row ten-columns justify-content-center" style="row-gap: 45px;">
                            @foreach($reward_programs as $reward_program)
                                <div class="col-sm-2">
                                    <img src="{{$base_url . '/public/uploads/' . $reward_program->image}}"
                                         alt="{{$lang == 'eng' ? $reward_program->eng_title : $reward_program->arb_title}}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="readmore-btn">
                        <a href="{{$lang_base_url . '/program-rewards'}}"
                           class="lp-readmore-orange">{{($lang == 'eng' ? 'Learn More' : 'اقرأ أكثر')}}</a>
                    </div>
                </div>
            </div>
            <div class="row lp-pt-80">
                <div class="col-lg-12 col-md-12">
                    <div class="lp-heading heading-right">
                        <h1>{{ $content[$lang . '_terms_and_conditions_heading'] }}</h1>
                    </div>
                    <div class="lp-list-content">
                        {!! $content[$lang . '_terms_and_conditions_description'] !!}
                    </div>
                </div>
            </div>
            <div class="row lp-pt-80" id="cards-section">
                <div class="col-lg-12 col-md-12">
                    <div class="lp-heading">
                        <h1>{{ $content[$lang . '_table_heading'] }}</h1>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="lp-data-table lp-pt-40">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th style="width: 28%;"></th>
                                @foreach($cards as $card)
                                    <th style="width: 18%;" class="{{$card->id . '_1'}}">
                                        {{$lang == 'eng' ? $card->eng_title : $card->arb_title}}
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="text-align: {{$lang == 'eng' ? 'left' : 'right'}};">{{$lang == 'eng' ? 'Be A Member When' : 'الحصول على العضوية'}}</td>
                                @foreach($cards as $card)
                                    <td class="{{$card->id . '_9'}}">
                                        {{$lang == 'eng' ? $card->eng_be_a_member_when : $card->arb_be_a_member_when}}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: {{$lang == 'eng' ? 'left' : 'right'}};">{{$lang == 'eng' ? 'Website & App Discount' : 'خصم الموقع والتطبيق'}}</td>
                                @foreach($cards as $card)
                                    <td class="{{$card->id . '_3'}}">
                                        {{$card->website_and_app_discount}}%
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: {{$lang == 'eng' ? 'left' : 'right'}};">{{$lang == 'eng' ? 'Branches Discount' : 'خصم الفروع'}}</td>
                                @foreach($cards as $card)
                                    <td class="{{$card->id . '_2'}}">
                                        {{$card->branch_discount}}%
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: {{$lang == 'eng' ? 'left' : 'right'}};">{{$lang == 'eng' ? 'Kilometers' : 'كيلومترات'}}</td>
                                @foreach($cards as $card)
                                    <td class="{{$card->id . '_6'}}">
                                        {{$card->kilometers}}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: {{$lang == 'eng' ? 'left' : 'right'}};">{{$lang == 'eng' ? 'Extra Hours' : 'ساعات إضافية'}}</td>
                                @foreach($cards as $card)
                                    <td class="{{$card->id . '_5'}}">
                                        {{$card->extra_hours}}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: {{$lang == 'eng' ? 'left' : 'right'}};">{{$lang == 'eng' ? 'Exemption of Inter-Regional Drop off Charges' : 'إعفاء رسوم التسليم بين المناطق'}}</td>
                                @foreach($cards as $card)
                                    <td class="{{$card->id . '_7'}}">
                                        @if(($lang == 'eng' && $card->eng_exemption_of_inter_regional_delivery_charges) || ($lang == 'arb' && $card->arb_exemption_of_inter_regional_delivery_charges))
                                            {!! $lang == 'eng' ? $card->eng_exemption_of_inter_regional_delivery_charges : $card->arb_exemption_of_inter_regional_delivery_charges !!}
                                        @else
                                            <img src="<?php echo $base_url;?>/public/frontend/images/no.png"
                                                 alt="{{$lang == 'eng' ? $card->eng_title : $card->arb_title}}">
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: {{$lang == 'eng' ? 'left' : 'right'}};">{{$lang == 'eng' ? 'Free Travel Permit for Gulf Countries' : 'تصريح سفر مجاني لدول الخليج'}}</td>
                                @foreach($cards as $card)
                                    <td class="{{$card->id . '_8'}}">
                                        <img src="<?php echo $base_url;?>/public/frontend/images/{{$card->has_travel_permit_for_the_gulf_countries}}.png"
                                             alt="{{$lang == 'eng' ? $card->eng_title : $card->arb_title}}">
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: {{$lang == 'eng' ? 'left' : 'right'}};">{{$lang == 'eng' ? 'Has Key Rewards' : 'مكافآت برنامج الولاء المفاتيح'}}</td>
                                @foreach($cards as $card)
                                    <td class="{{$card->id . '_4'}}">
                                        <img src="<?php echo $base_url;?>/public/frontend/images/{{$card->has_key_rewards}}.png"
                                             alt="{{$lang == 'eng' ? $card->eng_title : $card->arb_title}}">
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: {{$lang == 'eng' ? 'left' : 'right'}};">{{$lang == 'eng' ? 'Points Expiry In' : 'انتهاء صلاحية النقاط'}}</td>
                                @foreach($cards as $card)
                                    <td class="{{$card->id . '_11'}}">
                                        {{$lang == 'eng' ? $card->eng_points_expiry_in : $card->arb_points_expiry_in}}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="text-align: {{$lang == 'eng' ? 'left' : 'right'}};">{{$lang == 'eng' ? 'Maintain Membership Level' : 'الحفاظ على مستوى العضوية'}}</td>
                                @foreach($cards as $card)
                                    <td class="{{$card->id . '_10'}}">
                                        {{$lang == 'eng' ? $card->eng_maintain_membership_level : $card->arb_maintain_membership_level}}
                                    </td>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="lp-pt-20 lp-pb-60 lp-bg-art">
        <div class="container-md">
            <div class="row" id="faqs-section">
                <div class="col-lg-12 col-md-12">
                    <div class="lp-heading">
                        <h1>{{ $content[$lang . '_faqs_heading'] }}</h1>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="lp-faq faqs-accordion-wrapper">


                        <div class="accordion" id="accordionExample">
                            @foreach($faqs as $faq)



                                @if($loop->index == 0)

                                    <div class="accordion-item lp-active-panel">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button loyalty-faq-btn" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{$loop->index + 1}}" aria-expanded="true"
                                                    aria-controls="collapse{{$loop->index + 1}}">
                                                {!! $lang == 'eng' ? $faq->eng_question : $faq->arb_question !!}
                                            </button>
                                        </h2>
                                        <div id="collapse{{$loop->index + 1}}" class="accordion-collapse collapse show"
                                             data-bs-parent="#accordionExample">
                                            <div class="accordion-body">{!! $lang == 'eng' ? $faq->eng_answer : $faq->arb_answer !!}</div>
                                        </div>
                                    </div>

                                @else

                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed loyalty-faq-btn" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{$loop->index + 1}}" aria-expanded="false"
                                                    aria-controls="collapse{{$loop->index + 1}}">
                                                {!! $lang == 'eng' ? $faq->eng_question : $faq->arb_question !!}
                                            </button>
                                        </h2>
                                        <div id="collapse{{$loop->index + 1}}" class="accordion-collapse collapse"
                                             data-bs-parent="#accordionExample">
                                            <div class="accordion-body">{!! $lang == 'eng' ? $faq->eng_answer : $faq->arb_answer !!}</div>
                                        </div>
                                    </div>


                                @endif

                            @endforeach
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
