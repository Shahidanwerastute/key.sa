<!DOCTYPE html>
<html lang="<?php echo ($lang == 'arb' ? 'ar' : 'en'); ?>">
<head>
    <?php $site = custom::site_settings(); ?>
    <title><?php echo $site->site_title; ?> <?php if (isset($content[$lang . '_meta_title'])) echo($content[$lang . '_meta_title'] != '' ? ' | ' . $content[$lang . '_meta_title'] : '');?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta name="title"
          content="<?php if (isset($content[$lang . '_meta_title'])) echo $content[$lang . '_meta_title']?>">
    <meta name="description"
          content="<?php if (isset($content[$lang . '_meta_description'])) echo $content[$lang . '_meta_description']?>">
    <meta name="keywords"
          content="<?php if (isset($content[$lang . '_meta_keyword'])) echo $content[$lang . '_meta_keyword']?>">
    <!--bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/public/frontend/css/bootstrap@5.3/css/bootstrap.min.css">

    <!-- font-awsome -->
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>/public/frontend/css/fontawesome-free-6.5.1-web/css/all.css">
    <!-- custome CSS -->


        <style>
            @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');


            * {
                margin: 0%;
                padding: 0%;
                box-sizing: border-box;
            }


            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            p {
                margin-bottom: 0px;
            }

            body {

                font-family: 'Roboto', sans-serif;
                overflow-x: hidden;
            }



            .container{
                margin: 0 auto;
                max-width: 620px;
                width: 100%;
            }

            /*.arb header .header-inner-content .row {*/
            /*  display: flex;*/
            /*  justify-content: space-between;*/
            /*  flex-direction: row-reverse;*/
            /*}*/

            body.arb {
                direction: rtl;
            }

            .arb header .header-inner-content .phone-number{
                text-align: start;
            }

            .arb header .header-inner-content .web-link {
                text-align: start;
            }
            .arb header .header-inner-content a{
                margin-right: 5px;
            }

            header .header-inner-content {
                padding: 20px 20px;
                background-color: #ffffff;
                box-shadow: 2px 1px 9px 0px rgba(0,0,0,0.75);
                -webkit-box-shadow: 2px 1px 9px 0px rgba(0,0,0,0.75);
                -moz-box-shadow: 2px 1px 9px 0px rgba(0,0,0,0.75);

                /* box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px; */
            }


            header .header-inner-content .phone-number a {
                text-decoration: none;
                color: #70706f;
                font-size: 16px;
                font-weight: 500;
                margin-bottom: 0px;
            }

            header .header-inner-content .site-logo {
                height: 43px;
                aspect-ratio: auto 171 / 43;
                width: 171px;
            }

            header .header-inner-content .web-link a {
                color: #70706f;
                text-decoration: none;
                font-size: 16px;
                font-weight: 500;
            }

            .last-booking-section {
                padding: 30px 20px;
            }

            .last-booking-section h1 {
                text-align: center;
                font-size: 22px;
                font-weight: 500;
                color: #7d7d7c;
            }

            .last-booking-section p {
                text-align: center;
                text-transform: uppercase;
                color: #fe8b1a;
                font-size: 20px;
                line-height: 22px;
                font-weight: 500;
            }

            .rate-our-service-section .sandGrayBG{
                padding: 20px;
                background-color:#e1e1e1;

            }
            .rate-our-service-section p {
                text-align: center;
                font-size: 18px;
                font-weight: 500;
                color: #7d7d7c;
            }

            .rate-our-service-section .note{
                max-width: 450px;
                text-align: start;
                margin: auto;

            }
            .rate-our-service-section .note p {
                padding-top: 15px;
                text-align: start;
                text-decoration: underline;
                color: black;
                font-size: 16px;
                font-weight: 600;
            }

            .rate-our-service-section ul {
                padding: 0px;
                margin: 0px;
            }

            .rate-our-service-section ul li {
                list-style-type: none;
                color: #7d7d7c;
                font-weight: 500;
            }

            .rate-our-service-section .rate-main-box {
                max-width: 450px;
                width: 100%;
                margin: 20px auto;
                padding: 20px 25px 1px 25px;
                background: #fff;
                border-radius: 5px;
                box-shadow: 0 0 10px rgb(0 0 0 / 20%);
            }

            .rate-our-service-section .rate-main-box ul {
                padding: 0;
                margin: 0;
                list-style: none;
            }

            .rate-our-service-section .rate-main-box .title {
                font-size: 16px;
                color: #000;
                font-weight: 500;
                margin-right: 15px;
            }

            .arb .rate-our-service-section .radio-btn-box {
                display: flex;
                flex-direction: row-reverse;
            }
            .rate-our-service-section .rate-main-box .title.commit {
                margin-top: -58px;
            }


            .rate-our-service-section .rate-main-box ul li {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                justify-content: space-between;
                margin-bottom: 15px;
            }

            .rate-our-service-section .rate-main-box span:not(:last-child) {
                margin-right: 22px;
                display: inline-block;
            }

            .rate-our-service-section .rate-main-box [type="radio"]:checked, .rate-main-box [type="radio"]:not(:checked) {
                position: absolute;
                left: -9999px;
            }

            .rate-our-service-section input[type=checkbox]:not(old), input[type=radio]:not(old) {
                width: 28px;
                margin: 0;
                padding: 0;
                opacity: 0;
            }

            .rate-our-service-section .rate-main-box [type="radio"]:checked + label, .rate-main-box [type="radio"]:not(:checked) + label {
                position: relative;
                cursor: pointer;
                display: inline-block;
                color: #6c6c6c;
                z-index: 1;
                text-align: center;
                width: 25px;
                line-height: 35px;
                padding-left: 0;
                margin-left: 0;
            }



            .rate-our-service-section label {
                min-width: 24px;
                min-height: 24px;
            }



            .rate-our-service-section .rate-main-box [type="radio"]:checked + label:before, .rate-main-box [type="radio"]:not(:checked) + label:before {
                content: '';
                position: absolute;
                left: -5px;
                top: 0;
                width: 35px;
                height: 35px;
                background: #e5e5e5;
                z-index: -2;
                border-radius: 3px;
            }

            .rate-our-service-section .rate-main-box [type="radio"]:not(:checked) + label:after {
                opacity: 0;
                -webkit-transform: scale(0);
                transform: scale(0);
            }
            .rate-our-service-section .rate-main-box [type="radio"]:checked + label:after, .rate-main-box [type="radio"]:not(:checked) + label:after {
                content: '';
                width: 35px;
                height: 35px;
                background:#fe8b1a;
                position: absolute;
                top: 0;
                left: -5px;
                -webkit-transition: all 0.2s ease;
                transition: all 0.2s ease;
                color: #ffff;
                z-index: -1;
                border-radius: 3px;
            }

            .rate-our-service-section .rate-main-box [type="radio"]:checked + label {
                color: #fff;
            }

            .rate-our-service-section .rate-main-box textarea::placeholder{
                padding:5px
            }

            .rate-our-service-section  button{
                margin-top: -10px;
                padding: 2px 50px;
                border-radius: 10px;
                color: #ffff;
                border: transparent;
                background-color: #fe8b1a;
            }

            footer .footerCopyRight{
                padding: 5px 20px;
                background-color:#e1e1e1 ;
            }
            footer p{
                font-size: 13px;
            }

            footer span{
                font-size: 14px;
            }

            footer ul{
                display: inline-flex;
                padding: 0px;
                margin: 0px;
            }

            footer ul li {
                list-style: none;
                padding-right: 6px;
            }

            footer ul li a{
                text-decoration: none;
            }
            footer ul li a i{
                font-size: 16px;
                color: black;
            }


            footer{
                margin-top: 70px;
            }



            @media screen and (max-width: 365px) {
                .rate-our-service-section .rate-main-box .title.commit {
                    margin-top: 0px;
                }

            }













        </style>


    <script src="<?php echo $base_url; ?>/public/frontend/js/jquery-2.1.3.min.js"></script>

    <style>
        .container {
            /*max-width: 800px;*/
        }
        select {
            border-left: 0 none;
            border-right: 0 none;
            border-top: 0 none;
            min-height: 30px;
            padding-left: 5px;
            padding-right: 5px;
            text-align: <?php echo ($lang == 'arb' ? 'right' : 'left'); ?>;
            /* max-width: 281px; */
            width: 176px;
            word-break: break-word;
            white-space: normal;
        }
        .rate-our-service-section .rate-main-box {
            max-width: 650px;
        }
        .rate-our-service-section .note {
            margin-<?php echo ($lang == 'arb' ? 'right' : 'left'); ?>: 0px !important;
        }
        .arb .rate-our-service-section .rate-main-box [type="radio"]:checked, .arb .rate-main-box [type="radio"]:not(:checked) {
            left: unset;
        }
        .title {
            width: 49%;
        }
        .arb .rate-our-service-section p {
            font-size: 20px;
        }
        .arb .rate-our-service-section ul li {
            font-size: 20px;
        }
        .arb .rate-our-service-section .rate-main-box .title {
            font-size: 19px;
        }
        @media screen and (max-width: 767px){
           .phone-number, .web-link {
               display: none !important;
           }
            .logo-area {
                position: relative;
            }
            .langSwticherArea{
                position: absolute;
            }
        }
        @media screen and (min-width: 768px){
            .langSwticherArea{
                display: none;
            }
        }
        @media screen and (max-width: 714px){
            /*.rate-our-service-section .note {
                margin-<?php echo ($lang == 'arb' ? 'right' : 'left'); ?>: 0 !important;
            }*/
        }
        @media screen and (max-width: 620px){
            .title {
                width: 100%;
            }
            .rate-our-service-section .rate-main-box .title {
                margin-right: 0 !important;
            }
            .radio-btn-box{
                margin-top: 10px;
                margin-left: 5px;
            }
        }
    </style>
</head>

<?php
$segments = Request::segments();
$booking_id = end($segments);

if (isset($_GET['ref']) && $_GET['ref'] != '') {
    $url_part = '?ref=' . $_GET['ref'];
} else {
    $url_part = '';
}

if (isset($_GET['booking_status']) && $_GET['booking_status'] != '') {
    $url_part2 = '&booking_status=' . $_GET['booking_status'];
} else {
    $url_part2 = '';
}
?>

<body class="<?php echo $lang; ?>">
<!-- header -->
<header>
    <div class="container">
        <div class="header-inner-content">
            <div class="row align-items-center justify-content-md-between">
                <div class="col-md-4">
                    <div class="phone-number d-flex d-md-block justify-content-center">
                        <a href="tel:<?php echo $site->site_phone; ?>"><?php echo $site->site_phone; ?></a>
                    </div>
                </div>
                <div class="col-md-4 logo-area">
                    <div class="langSwticherArea">
                        <?php
                        if ($site->site_language == 'both')
                        { ?>
                        <a href="javascript:void(0);" onclick="changeLanguageForOasis('<?php echo $lang; ?>', 0);"> <img
                                    src="<?php echo $base_url; ?>/public/survey-assets/images/key-link.png"
                                    class="img-fluid ms-1" alt="key-link-logo" style="margin-top: 10px;"></a>
                        <?php }
                        ?>
                    </div>
                    <div class="site-logo mx-auto mx-md-0">
                        <a href="<?php echo $lang_base_url; ?>"> <img
                                    src="<?php echo $base_url; ?>/public/uploads/<?php echo $site->site_logo; ?>"
                                    class="img-fluid  " alt="stie-logo"></a>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="web-link d-md-flex justify-content-md-end d-flex d-md-block justify-content-center ">
                        <a href="<?php echo $lang_base_url; ?>">www.key.sa</a>
                        <?php
                        if ($site->site_language == 'both')
                        { ?>
                        <a href="javascript:void(0);" onclick="changeLanguageForOasis('<?php echo $lang; ?>', 0);"> <img
                                    src="<?php echo $base_url; ?>/public/survey-assets/images/key-link.png"
                                    class="img-fluid ms-1" alt="key-link-logo"></a>
                        <?php }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--End header  -->

<!-- main -->
<main>

    <section class="last-booking-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>{{($lang == 'eng' ? 'Your Last Booking' : 'اخر حجز')}}</h1>
                    <p><?php echo $contract_no; ?></p>
                </div>
            </div>
        </div>
    </section>

<?php
$questions = [];
if ($booking_status == 'O') {
    $questions = [
        'branch_employees_behavior_and_performance' => ($lang == 'eng' ? 'Branch employee’s behavior and performance' : 'تعامل وأداء موظف الفرع'),
        'the_quickness_and_efficiency_of_completing_your_rental_procedure' => ($lang == 'eng' ? 'The efficiency of completing your rental procedures' : 'كفاءة وسرعة إنهاء إجراءات التأجير'),
        'the_accuracy_of_the_rental_information_provided_to_you' => ($lang == 'eng' ? 'Has the rental policy been explained well to you?' : 'هل تم شرح سياسة التأجير لك؟')
    ];
} elseif ($booking_status == 'C') {
    $questions = [
        'branch_employees_behavior_and_performance' => ($lang == 'eng' ? 'Branch employee’s behavior and performance' : 'تعامل وأداء موظف الفرع'),
        'the_quickness_and_efficiency_of_completing_your_rental_procedure' => ($lang == 'eng' ? 'The efficiency of completing your rental procedures' : 'كفاءة وسرعة إنهاء إجراءات التأجير'),
        'the_safety_and_the_quality_of_the_vehicle_structure' => ($lang == 'eng' ? 'The safety and the quality of the vehicle structure externally and internally' : 'سلامة وجودة هيكل المركبة الخارجي و الداخلي'),
        'the_cleanliness_of_the_vehicle_externally_and_internally' => ($lang == 'eng' ? 'The cleanliness of the vehicle externally and internally' : 'نظافة المركبة الداخلية و الخارجية'),
        'how_likely_are_you_to_recommend_our_company' => ($lang == 'eng' ? 'How likely are you to recommend our company to a friend or relative?' : 'ما مدى احتمالية أن توصي بشركتنا لصديق أو قريب'),
        'your_experience_with_key' => ($lang == 'eng' ? 'Your experience with Key' : 'تجربتك مع المفتاح'),
    ];
}
?>

<!-- rate-our-service-section -->
    <section class="rate-our-service-section">
        <div class="container">

            <div class="sandGrayBG">
                <div class="row">
                    <div class="col-12">
                        <?php if($filled_status == 1){ ?>
                        <p><?php echo $message; ?></p>
                        <?php }else{ ?>
                        <p>{{($lang == 'eng' ? 'Please rate our service' : 'يرجى تقييم خدماتنا')}}</p>
                        <div class="note">
                            <p><?php echo($lang == 'eng' ? 'Note:' : 'ملاحظة:'); ?></p>
                            <ul>
                                <li><?php echo($lang == 'eng' ? '(5) is the highest rate, (1) is the lowest rate' : '(5) لأعلى تقييم، (1) لأقل تقييم'); ?></li>
                            </ul>
                        </div>
                        <form id="saveOasisSurveyFeedback" method="POST"
                              action="<?php echo $lang_base_url . '/saveOasisSurveyFeedback'; ?>"
                              onsubmit="return false;">
                            <input type="hidden" class="survey_type" value="oasis_survey">
                            <input type="hidden" name="contract_no" class="contract_no"
                                   value="<?php echo $contract_no; ?>">
                            <input type="hidden" name="booking_status" class="booking_status"
                                   value="<?php echo $booking_status; ?>">
                            <input type="hidden" name="questions_count" id="questions_count"
                                   value="<?php echo count($questions); ?>">

                            <div class="rate-main-box">
                                <div class="rate-radio-box">
                                    <ul>

                                        <?php if ($booking_status == 'C') { ?>
                                        <li>
                                            <div class="title"><?php echo($lang == 'eng' ? 'Purpose of renting' : 'سبب الاستئجار'); ?> *</div>
                                            <div class="radio-btn-box">
                                                <select name="purpose_of_renting">
                                                    <option value=""><?php echo ($lang == 'eng' ? 'Select' : 'اختر'); ?></option>
                                                    <option value="<?php echo ($lang == 'eng' ? 'Personal' : 'شخصي'); ?>"><?php echo ($lang == 'eng' ? 'Personal' : 'شخصي'); ?></option>
                                                    <option value="<?php echo ($lang == 'eng' ? 'Business trip' : 'رحلة عمل'); ?>"><?php echo ($lang == 'eng' ? 'Business trip' : 'رحلة عمل'); ?></option>
                                                    <option value="<?php echo ($lang == 'eng' ? 'Travel' : 'سفر'); ?>"><?php echo ($lang == 'eng' ? 'Travel' : 'سفر'); ?></option>
                                                    <option value="<?php echo ($lang == 'eng' ? 'Other' : 'أخرى'); ?>"><?php echo ($lang == 'eng' ? 'Other' : 'أخرى'); ?></option>
                                                </select>
                                            </div>
                                        </li>
                                        <?php } ?>

                                        <?php
                                        $i = 1;
                                        foreach ($questions as $key => $value) { ?>
                                        <li>
                                            <div class="title"><?php echo $value; ?> *</div>
                                            <div class="radio-btn-box">
                                            <span>
                                                <input type="radio" id="question-<?php echo $i; ?>-option-1" name="question-<?php echo $i; ?>" value="<?php echo $key; ?>-1">
                                                <label for="question-<?php echo $i; ?>-option-1">1</label>
                                            </span>
                                                <span>
                                                <input type="radio" id="question-<?php echo $i; ?>-option-2" name="question-<?php echo $i; ?>" value="<?php echo $key; ?>-2">
                                                <label for="question-<?php echo $i; ?>-option-2">2</label>
                                            </span>
                                                <span>
                                                <input type="radio" id="question-<?php echo $i; ?>-option-3" name="question-<?php echo $i; ?>" value="<?php echo $key; ?>-3">
                                                <label for="question-<?php echo $i; ?>-option-3">3</label>
                                            </span>
                                                <span>
                                                <input type="radio" id="question-<?php echo $i; ?>-option-4" name="question-<?php echo $i; ?>" value="<?php echo $key; ?>-4">
                                                <label for="question-<?php echo $i; ?>-option-4">4</label>
                                            </span>
                                                <span>
                                                <input type="radio" id="question-<?php echo $i; ?>-option-5" name="question-<?php echo $i; ?>" value="<?php echo $key; ?>-5">
                                                <label for="question-<?php echo $i; ?>-option-5">5</label>
                                            </span>
                                            </div>
                                        </li>
                                        <?php $i++; } ?>

                                        <?php if ($booking_status == 'C') { ?>
                                        <li>
                                            <div class="title"><?php echo($lang == 'eng' ? 'Suggestions or opinions you would like to share with us' :
                                                    'اقتراحات أو آراء تود إخبارنا بها'); ?> *</div>
                                            <div class="radio-btn-box">
                                                <select name="suggestion_or_opinion_you_would_like_to_share" onchange="$('.comment_field').show();">
                                                    <option value=""><?php echo ($lang == 'eng' ? 'Select' : 'اختر'); ?></option>
                                                    <option value="<?php echo ($lang == 'eng' ? 'Employee' : 'الموظف'); ?>"><?php echo ($lang == 'eng' ? 'Employee' : 'الموظف'); ?></option>
                                                    <option value="<?php echo ($lang == 'eng' ? 'Branch' : 'الفرع'); ?>"><?php echo ($lang == 'eng' ? 'Branch' : 'الفرع'); ?></option>
                                                    <option value="<?php echo ($lang == 'eng' ? 'Car condition' : 'الأعطال'); ?>"><?php echo ($lang == 'eng' ? 'Car condition' : 'الأعطال'); ?></option>
                                                    <option value="<?php echo ($lang == 'eng' ? 'Cleanliness' : 'النظافة'); ?>"><?php echo ($lang == 'eng' ? 'Cleanliness' : 'النظافة'); ?></option>
                                                    <option value="<?php echo ($lang == 'eng' ? 'Delay in picking up or dropping off' : 'تأخير الاستلام والتسليم'); ?>"><?php echo ($lang == 'eng' ? 'Delay in picking up or dropping off' : 'تأخير الاستلام والتسليم'); ?></option>
                                                    <option value="<?php echo ($lang == 'eng' ? 'Others' : 'أخرى'); ?>"><?php echo ($lang == 'eng' ? 'Others' : 'أخرى'); ?></option>
                                                </select>
                                            </div>
                                        </li>
                                            <li class="comment_field" style="display: none;">
                                                <div class="title"><?php echo($lang == 'eng' ? 'Comment' : 'الملاحظات'); ?></div>
                                                <div class="radio-btn-box">
                                                    <textarea name="comment" id="comment" cols="" rows=""></textarea>
                                                </div>
                                            </li>
                                        <?php } ?>

                                    </ul>
                                </div>

                            </div>

                            <div class="col-12">
                                <button class=" mx-auto d-block"
                                        type="submit"><?php echo($lang == 'eng' ? 'Submit' : 'إرسال'); ?></button>
                            </div>
                        </form>
                        <?php } ?>

                    </div>
                </div>

            </div>
        </div>
    </section>


</main>
<!-- end-main -->

<footer>
    <div class="container">
        <div class="footerCopyRight">
            <div class="row  align-items-center justify-content-md-between text-center text-md-start">
                <div class="col-md-6 pt-2 pt-md-0">
                    <p>@lang('labels.copyrights') <?php echo date('Y'); ?>. @lang('labels.all_rights_reserved').</p>
                </div>
                <div class="col-md-6 pt-2 pt-md-0 d-md-flex justify-content-md-end align-items-md-center ">
                    <span class="me-3">@lang('labels.follow_us')</span>
                    <ul>
                        <?php $social = custom::social_links(); ?>
                        <li><a href="<?php echo $social->instagram_link; ?>" target="_blank"> <i class="fa-brands fa-instagram"></i>
                            </a></li>
                        <li><a href="<?php echo $social->linkedin_link; ?>" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
                        </li>
                        <li><a href="<?php echo $social->twitter_link; ?>" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                        </li>
                        <li><a href="<?php echo $social->facebook_link; ?>" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                        </li>
                        <li><a href="<?php echo $social->youtube_link; ?>" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<script>
    var base_url = '<?php echo $base_url; ?>';
    var lang = '<?php echo $lang; ?>';
    var lang_base_url = '<?php echo $lang_base_url; ?>';
    var required_message = '<?php echo Lang::get('labels.required'); ?>';
    var url_part = '<?php echo $url_part . $url_part2; ?>';
</script>
<!-- bootstrap js file -->
<script src="<?php echo $base_url; ?>/public/frontend/css/bootstrap@5.3/js/bootstrap.min.js"></script>
<script src="<?php echo $base_url; ?>/public/frontend/css/bootstrap@5.3/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $base_url; ?>/public/frontend/js/survey_script.js?v=<?php echo rand(); ?>"></script>
</body>
</html>