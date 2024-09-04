<?php
$social = custom::social_links();
$site = custom::site_settings();
?>

<style>
    body.contact-us .social-block {
        margin-top: 0;
    }
</style>

<section class="social-block social-block-new-design">
    <div class="container-md">
        <div class="social-block-row-box">
            <div class="col">
                <strong class="title">@lang('labels.follow_our')</strong>
                <h3>@lang('labels.social_media')</h3>
                <ul class="social-networks">
                    <?php if($social->facebook_link != ""){ ?>
                    <li><a href="<?php echo $social->facebook_link; ?>" target="_blank" class="facebook"></a></li>
                    <?php } if($social->twitter_link != ""){ ?>
                    <li><a href="<?php echo $social->twitter_link; ?>" target="_blank" class="twitter"></a></li>
                    <?php } if($social->linkedin_link != ""){ ?>
                    <li><a href="<?php echo $social->linkedin_link; ?>" target="_blank" class="linkedin"></a></li>
                    <?php } if($social->instagram_link != ""){ ?>
                    <li><a href="<?php echo $social->instagram_link; ?>" target="_blank" class="instagram"></a></li>
                    <?php } if($social->youtube_link != ""){ ?>
                    <li><a href="<?php echo $social->youtube_link; ?>" target="_blank" class="youtube"></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col">
            <!-- <strong class="title">@lang('labels.subscribe_to_our')</strong>
            <h3>@lang('labels.newsletter')</h3>
            <form class="newsLetter" action="<?php echo $lang_base_url. '/news-letter'; ?>" method="post" onsubmit="return false;">
                <input id="news_letter" type="email" placeholder="@lang('labels.email')" name="news_letter" />
                <input type="button" onclick="subscribe_news_letter($('#news_letter').val());" class="btn-contact" value="@lang('labels.subscribe')" />
            </form> -->
                <div class="contact-info-box">
                    <div class="call-us-number">
                    <!-- <strong class="title <?php echo ($lang == '' ? '' : ''); ?>">@lang('labels.contact_24_7')</strong>-->
                        <div class="title"><?php echo ($lang == 'eng' ? 'Call Us' : 'اتصل بنا'); ?></div>
                        <h3>@lang('labels.toll_free')</h3>
                    </div>
                    <div class="contact-details">
                        <a href="tel:<?php echo $site->site_phone; ?>" class="tel"><?php echo $site->site_phone; ?></a>
                    <!-- <span class="or">@lang('labels.or')</span>
                    <a href="<?php echo $lang_base_url.'/contact-us'; ?>" class="btn-contact">@lang('labels.request_a_call')</a> -->
                    </div>
                </div>
            </div>
            <div class="col app-icon-section">
                <div class="download-app-main-box">
                    <div class="down-load-app-text">
                        <div class="title"><?php echo ($lang == 'eng' ? 'Download' : 'تحميل'); ?></div>
                        <h3><?php echo ($lang == 'eng' ? 'Our Apps' : 'تطبيقاتنا'); ?></h3>
                    </div>
                    <ul class="btn-box app-btns">
                        <li>
                            <a href="https://itunes.apple.com/us/app/key-car-rental/id1282284664?ls=1&amp;mt=8" target="_blank">
                                <img src="https://kra.ced.sa/public/frontend/images/icon-apple.png" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="https://play.google.com/store/apps/details?id=comcom.key&amp;hl=en" target="_blank">
                                <img src="https://kra.ced.sa/public/frontend/images/icon-android.png" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="https://appgallery.huawei.com/#/app/C103329457" target="_blank">
                                <img src="https://kra.ced.sa/public/frontend/images/icon-huawei.png?v=0.6" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
