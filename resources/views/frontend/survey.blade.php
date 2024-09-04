<!DOCTYPE HTML>
<html lang="en">
<head>
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
    <?php $site = custom::site_settings(); ?>
    <link rel="shortcut icon" type="image/png" href="<?php echo $base_url; ?>/public/frontend/images/favicon.png?v=0.1">
    <link rel="shortcut icon" type="image/png" href="<?php echo $base_url; ?>/public/frontend/images/favicon.ico?v=0.1">
    <title><?php echo $site->site_title; ?> <?php if (isset($content[$lang . '_meta_title'])) echo($content[$lang . '_meta_title'] != '' ? ' | ' . $content[$lang . '_meta_title'] : '');?></title>
    <link href="<?php echo $base_url; ?>/public/frontend/css/bootstrap.css" rel="stylesheet" type="text/css"
          media="all">
    <link href="<?php echo $base_url; ?>/public/frontend/css/all.css?v=<?php echo rand(); ?>" rel="stylesheet" type="text/css" media="all">
    <?php if ($lang == 'arb') // CSS For arabic
    { ?>
    <link href="<?php echo $base_url; ?>/public/frontend/css/rtl.css?v=<?php echo rand(); ?>" rel="stylesheet" type="text/css" media="all">
    <?php } ?>
    <link href="<?php echo $base_url; ?>/public/frontend/css/feedback.css?v=<?php echo rand(); ?>" rel="stylesheet" type="text/css" media="all">
    <script src="<?php echo $base_url; ?>/public/frontend/js/jquery-2.1.3.min.js"></script>
    <?php
    $segments = Request::segments();
    $booking_id = end($segments);
    $site = custom::site_settings();

    if (isset($_GET['ref']) && $_GET['ref'] != '') {
        $url_part = '?ref=' . $_GET['ref'];
    } else {
        $url_part = '';
    }
    ?>
</head>
<body class="<?php echo $lang; ?>">
<div class="loaderSpiner">
    <img src="<?php echo $base_url; ?>/public/frontend/images/loaderRing.png" alt="Loading" height="196" width="196"/>
</div>
<div class="small_630">
    <header>
        <div class="topHeader">
            <div class="container-fluid ">
                <div class="topHleft">
                    <ul>
                        <li>
                            <a href="tel:<?php echo $site->site_phone; ?>"><img
                                        src="<?php echo $base_url; ?>/public/frontend/images/noIcon.png" alt="No."
                                        height="12" width="10"/> <?php echo $site->site_phone; ?></a>
                        </li>
                    </ul>
                </div>
                <?php
                if ($site->site_language == 'both')
                { ?>
                <div class="topLang">
                    <span class="website"><a href="http://www.key.sa">www.key.sa</a></span>
                    <a href="javascript:void(0);" onclick="changeLanguage('<?php echo $lang; ?>');"
                       title="@lang('labels.change_site_language')">
                        <?php echo($lang == 'eng' ? 'العربية' : 'English'); ?>
                    </a>
                </div>
                <?php }
                ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="logoMenuTop small_630">
            <div class="container-fluid text-center">
                <div class="navbar-brand"><a href="<?php echo $lang_base_url; ?>"><img
                                src="<?php echo $base_url; ?>/public/uploads/<?php echo $site->site_logo; ?>" alt="Logo"
                                height="43" width="171"/></a></div>
            </div>
        </div>
    </header>
    <section class="searchNbookSec carDetails hasCarDetails">
        <div class="container-fluid noDelivery text-center">
            <h1 class="heading">{{($lang == 'eng' ? 'Your Last Booking' : 'الحجز الأخير')}}</h1>
            <p class="dated">{{ date('l, H:i A', strtotime($booking_detail->created_at)) }}</p>
            <div class="imgBox"><img src="<?php echo $base_url; ?>/public/uploads/<?php echo $booking_detail->car_image; ?>" alt="Car"
                                     height="132" width="274"/></div>
            <h1 class="title">{{($lang == 'eng' ? $booking_detail->car_type_eng_title : $booking_detail->car_type_arb_title)}} {{($lang == 'eng' ? $booking_detail->car_model_eng_title : $booking_detail->car_model_arb_title)}}</h1>
            <p class="reg">{{($lang == 'eng' ? 'RESERVATION NUMBER' : 'رقم الحجز')}}</p>
            <p class="reg_no">{{$booking_detail->reservation_code}}</p>
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="row carLocations">
                        <div class="col-xs-6">
                            <p class="status">{{($lang == 'eng' ? 'Pickup' : 'الإستلام')}}</p>
                            <p><strong>{{date('j M', strtotime($booking_detail->from_date))}}
                                    | {{date('H:i A', strtotime($booking_detail->from_date))}}</strong></p>
                            <p class="location">{{($lang == 'eng' ? $booking_detail->branch_from_eng_title : $booking_detail->branch_from_arb_title)}}
                                , {{($lang == 'eng' ? $booking_detail->city_from_eng_title : $booking_detail->city_from_arb_title)}}</p>
                        </div>
                        <div class="col-xs-6">
                            <p class="status">{{($lang == 'eng' ? 'Drop off' : 'التسليم')}}</p>
                            <p><strong>{{date('j M', strtotime($booking_detail->to_date))}}
                                    | {{date('H:i A', strtotime($booking_detail->to_date))}}</strong></p>
                            <p class="location">{{($lang == 'eng' ? $booking_detail->branch_to_eng_title : $booking_detail->branch_to_arb_title)}}
                                , {{($lang == 'eng' ? $booking_detail->city_to_eng_title : $booking_detail->city_to_arb_title)}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="mainFeedBk">
        <section class="sandGrayBG">
            <div class="container-fluid">
                <p>{{($lang == 'eng' ? 'Please rate our service' : 'يرجى تقييم خدماتنا')}}</p>
                <ul class="emojiList">
                    <?php foreach ($emojis as $emoji)
                    { ?>
                    <li>
                        <a href="javascript:void(0);" data-emoji-id="<?php echo $emoji->id; ?>"
                           data-emoji-title="<?php echo($lang == 'eng' ? $emoji->eng_title : $emoji->arb_title); ?>"
                           class="getCategories"
                           onclick="$('.emoji_id').val($(this).data('emoji-id'));$('.emoji_desc').val($(this).data('emoji-title'));">
                            <i class="feedBackFace <?php echo $emoji->class_name; ?>"></i>
                            <span><?php echo($lang == 'eng' ? $emoji->eng_title : $emoji->arb_title); ?></span>
                        </a>
                    </li>
                    <?php }?>
                </ul>
                <div class="emojiCat categories_list"></div>
            </div>
        </section>
    </div>
    <div class="fb_Reviews text-center hide options_list">
        <section class="searchNbookSec TextBox">
            <h1 class="heading">
                <a href="javascript:void(0);" onclick="reset();"><img
                            src="<?php echo $base_url; ?>/public/frontend/images/goBack_survey.png" alt="Back" height=""
                            width=""/></a>
                <span class="contains_question">What Can we improve?</span>
            </h1>
            <p class="pWith">
                <span class="contains_category">Car</span>
            </p>
        </section>
        <section class="sandGrayBG">
            <div class="container-fluid">

            </div>
        </section>
    </div>

    <!-- Modal -->
    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#msgPopupNoRedirect"
            id="openMsgPopupNoRedirect"></button>
    <div class="modal fade" id="msgPopupNoRedirect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="myModalLabel"></h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div><p class="responseMsg"></p></div>
                    <div class="twoBtnEd">
                        <br/>
                        <input type="submit" class="redishButtonRound"
                               value="<?php echo($lang == 'eng' ? 'CLOSE' : 'إغلاق'); ?>" data-bs-dismiss="modal"/>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button style="display: none;" data-bs-toggle="modal" data-bs-target="#msgPopupRedirect"
            id="openMsgPopupRedirect"></button>
    <div class="modal fade" id="msgPopupRedirect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h4 class="modal-title responseTitle" id="myModalLabel"></h4>
                </div>
                <div class="modal-body text-center">
                    <br/>
                    <div><p class="responseMsg"></p></div>
                    <div class="twoBtnEd">
                        <br/>
                        <a href="javascript:void(0);" id="OKBtn" class="redishButtonRound"><input type="submit"
                                                                                                  class="redishButtonRound"
                                                                                                  value="<?php echo ($lang == 'eng' ? 'OK' : 'إغلاق'); ?>"></a>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <form id="feedback_form">
            <input type="hidden" class="survey_type" value="website_survey">
            <input type="hidden" name="customer_id" class="customer_id"
                   value="<?php echo $customer_id_for_survey; ?>">
            <input type="hidden" name="booking_id" class="booking_id"
                   value="<?php echo $booking_id_for_survey; ?>">
            <input type="hidden" name="emoji_id" class="emoji_id" value="">
            <input type="hidden" name="emoji_desc" class="emoji_desc" value="">
            <input type="hidden" name="category_id" class="category_id" value="">
            <input type="hidden" name="category_desc" class="category_desc" value="">
            <input type="hidden" name="question_desc" class="question_desc" value="">
            <input type="hidden" name="option_id" class="option_id" value="">
            <input type="hidden" name="answer_desc" class="answer_desc" value="">
        </form>
        <div class="footerCopyRight">
            <div class="container-md">
                <p class="copyText">@lang('labels.copyrights') <?php echo date('Y'); ?>
                    . @lang('labels.all_rights_reserved').</p>
                <div class="footFollowUs">
                    <span>@lang('labels.follow_us')</span>
                    <ul>
                        <?php $social = custom::social_links(); ?>
                        <li><a href="<?php echo $social->facebook_link; ?>" target="_blank" class="facebook"></a></li>
                        <li><a href="<?php echo $social->twitter_link; ?>" target="_blank" class="twitter"></a></li>
                        <li><a href="<?php echo $social->linkedin_link; ?>" target="_blank" class="linkedin"></a></li>
                        <li><a href="<?php echo $social->instagram_link; ?>" target="_blank" class="instagram"></a></li>
                        <li><a href="<?php echo $social->youtube_link; ?>" target="_blank" class="youtube"></a></li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <script>
            var base_url = '<?php echo $base_url; ?>';
            var lang = '<?php echo $lang; ?>';
            var lang_base_url = '<?php echo $lang_base_url; ?>';
            var required_message = '<?php echo Lang::get('labels.required'); ?>';
            var url_part = '<?php echo $url_part; ?>';
        </script>
        <script src="<?php echo $base_url; ?>/public/frontend/js/bootstrap.min.js"></script>
        <script src="<?php echo $base_url; ?>/public/frontend/js/survey_script.js?v=<?php echo rand(); ?>"></script>
    </footer>
</div>
</body>
</html>