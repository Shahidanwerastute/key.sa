@extends('frontend.layouts.template')

@section('content')
    <style>
        .Card_flex li{
            display: flex !important;
        }
        ul.Name_Id_Number{
            display: flex;
            align-items: center;
            min-height: 160px;
            justify-content: space-between;
        }
        ul.Name_Id_Number li{
            padding: 20px 15px;
            color: white;
            margin-top: 15px !important;
            font-size: 12px;
        }
        ul.Name_Id_Number li:first-child{
            padding-<?php echo ($lang == 'eng' ? 'right' : 'left'); ?>: 5px;
        }
        ul.Name_Id_Number li:last-child{
            padding-<?php echo ($lang == 'eng' ? 'left' : 'right'); ?>: 0px;
        }
    </style>
    <section class="textBannerSec">
        <div class="container-md">
        </div>
    </section>
    <section class="myAccountSec">
        <div class="container-md">
            <div class="myAccountWrapper">
                @include('frontend.layouts.corporate_profile_inner_section')
                <div class="myProfDetail">
                    <?php
                    $data = custom::loggedInUserProfileInnerInfo("corporate");
                    if ($data['user_data']) {
                        $user_data = $data['user_data'];
                        if ($user_data->membership_level == 'Bronze' || $user_data->membership_level == '') // For Silver Type
                        {
                            $label = ($lang == 'eng' ? 'Bronze Points' : 'نقاط برونزية');
                            $image_path = $base_url.'/public/frontend/images/bronze_card_img.png?v=0.1';
                        }elseif ($user_data->membership_level == 'Silver') // For Silver Type
                        {
                            $label = ($lang == 'eng' ? 'Silver Points' : 'مجموع النقاط');
                            $image_path = $base_url.'/public/frontend/images/silver_card_img.png?v=0.1';
                        }elseif ($user_data->membership_level == 'Golden') // For Golden Type
                        {
                            $label = ($lang == 'eng' ? 'Golden Points' : 'غولدنبوانتس');
                            $image_path = $base_url.'/public/frontend/images/golden_card_img.png?v=0.1';
                        }elseif ($user_data->membership_level == 'Platinum') // For Platinum Type
                        {
                            $label = ($lang == 'eng' ? 'Platinum Points' : 'النقاط البلاتينية');
                            $image_path = $base_url.'/public/frontend/images/platinum_card_img.png?v=0.1';
                        }
                    } else {
                        $label = ($lang == 'eng' ? 'Bronze Points' : 'نقاط برونزية');
                        $image_path = $base_url.'/public/frontend/images/bronze_card_img.png?v=0.1';
                    }
                    ?>
                    <ul class="col-loyalty">
                        <li class="cardType">
<!--                            <img src="<?php echo $image_path; ?>" alt="Card" height="150" width="240"/>-->
                                <div class="loyalty_card_img" style="background-image: url('<?php echo $image_path; ?>');min-height: 160px;min-width: 240px;background-size: 100%;background-repeat: no-repeat;">
                                    <ul class="Name_Id_Number">
                                        <li><?php echo ($lang == 'eng' ? $user_data->company_name_en : $user_data->company_name_ar); ?></li>
                                        <li><?php echo $user_data->company_code; ?></li>
                                    </ul>
                                </div>
                        </li>
                        <?php if (isset($user_data->loyalty_points)) { ?>
                        <div class="col-points">
                            <h3><?php echo $label; ?></h3>
                            <p><?php echo $user_data->loyalty_points; ?></p>
                        </div>
                        <?php } ?>
                    </ul>
                    <h1>
                        <strong><?php echo($lang == 'eng' ? 'My' : 'ملفي'); ?> </strong> <?php echo($lang == 'eng' ? 'Profile' : 'الشخصي'); ?>
                    </h1>
                    <div class="row noFloatingRow">
                        <div class="col-lg-12 col-md-12 isNoFloat">
                            <div class="row noFloatingRow">
                                <div class="col-md-4 isNoFloat marginBtm20">
                                    <label><?php echo ($lang == "eng" ? "Company Name En" : "اسم الشركة بالإنجليزي") ?></label>
                                    <p><?php echo $user_data->company_name_en; ?></p>
                                </div>
                                <div class="col-md-4 isNoFloat marginBtm20">
                                    <label><?php echo ($lang == "eng" ? "Company Name Ar" : "اسم الشركة بالعربي") ?></label>
                                    <p><?php echo $user_data->company_name_ar; ?></p>
                                </div>
                                <div class="col-md-4 isNoFloat marginBtm20">
                                    <label><?php echo ($lang == "eng" ? "Company Code" : "رقم الشركة") ?></label>
                                    <p><?php echo $user_data->company_code; ?></p>
                                </div>
                                <div class="col-md-3 isNoFloat marginBtm20">
                                    <label><?php echo ($lang == "eng" ? "Primary Contact Name" : "اسم المسؤول الأول") ?></label>
                                    <p><?php echo $user_data->primary_name; ?></p>
                                </div>
                                <div class="col-md-3 isNoFloat marginBtm20">
                                    <label><?php echo ($lang == "eng" ? "Primary Position" : "منصب المسؤول الأول") ?></label>
                                    <p><?php echo $user_data->primary_position; ?></p>
                                </div>
                                <div class="col-md-3 isNoFloat marginBtm20">
                                    <label><?php echo ($lang == "eng" ? "Primary Email" : "ايميل المسؤول الأول") ?></label>
                                    <p><?php echo $user_data->primary_email; ?></p>
                                </div>
                                <div class="col-md-3 isNoFloat marginBtm20">
                                    <label><?php echo ($lang == "eng" ? "Primary Phone" : "رقم هاتف المسؤول الأول") ?></label>
                                    <?php
                                    if ($lang == 'arb') {
                                        $mobileFieldStyle = 'style="direction: ltr; text-align: right;"';
                                    } else {
                                        $mobileFieldStyle = '';
                                    }
                                    ?>
                                    <p <?php echo $mobileFieldStyle; ?>><?php echo $user_data->primary_phone; ?></p>
                                </div>
                                <?php if ($user_data->secondary_name != "")
                                    { ?>
                                <div class="col-md-3 isNoFloat marginBtm20">
                                    <label><?php echo ($lang == "eng" ? "Secondary Contact Name" : "اسم المسؤول الثاني") ?></label>
                                    <p><?php echo $user_data->secondary_name; ?></p>
                                </div>
                                    <?php } ?>
                                <?php if ($user_data->secondary_position != "")
                                { ?>
                                <div class="col-md-3 isNoFloat marginBtm20">
                                    <label><?php echo ($lang == "eng" ? "Secondary Position" : "منصب المسؤول الثاني") ?></label>
                                    <p><?php echo $user_data->secondary_position; ?></p>
                                </div>
                                <?php } ?>
                                <?php if ($user_data->secondary_email != "")
                                { ?>
                                <div class="col-md-3 isNoFloat marginBtm20">
                                    <label><?php echo ($lang == "eng" ? "Secondary Email" : "ايميل المسؤول الثاني") ?></label>
                                    <p><?php echo $user_data->secondary_email; ?></p>
                                </div>
                                <?php } ?>
                                <?php if ($user_data->secondary_phone != "")
                                { ?>
                                <div class="col-md-3 isNoFloat marginBtm20">
                                    <label><?php echo ($lang == "eng" ? "Secondary Phone" : "رقم هاتف المسؤول الثاني") ?></label>
                                    <?php
                                    if ($lang == 'arb') {
                                        $mobileFieldStyle = 'style="direction: ltr; text-align: right;"';
                                    } else {
                                        $mobileFieldStyle = '';
                                    }
                                    ?>
                                    <p <?php echo $mobileFieldStyle; ?>><?php echo $user_data->secondary_phone; ?></p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="paymentOption">
                            <ul>
                                <li style="width: auto;">
                                    <div class="imgBox"><img
                                                src="<?php echo $base_url; ?>/public/frontend/images/paymentOption_1.png"
                                                alt="Method" width="25" height="26"></div>
                                        <span><?php echo($lang == 'eng' ? 'PAYMENT METHOD(s)' : 'طريقة الدفع او السداد'); ?></span>
                                </li>
                                <li style="width: auto;">

                                    <p>
                                        <?php
                                        $payment_methods = "";
                                        if ($user_data->credit_card == 1) {
                                            $payment_methods .= Lang::get('labels.credit_card').'&nbsp;&nbsp;&nbsp;&nbsp;';
                                        }
                                        if ($user_data->cash == 1) {
                                            $payment_methods .= Lang::get('labels.cash').'&nbsp;&nbsp;&nbsp;&nbsp;';
                                        }
                                        if ($user_data->corporate_credit == 1) {
                                            $payment_methods .= ($lang == "eng" ? "Corporate Credit" : "حساب إئتماني").'&nbsp;&nbsp;&nbsp;&nbsp;';
                                        }
                                        if ($user_data->pay_later == 1) {
                                            $payment_methods .= ($lang == "eng" ? "Pay Later" : "Pay Later").'&nbsp;&nbsp;&nbsp;&nbsp;';
                                        }
                                        echo $payment_methods;
                                        ?>

                                    </p>
                                </li>
                            </ul>


                        </div>
                    </div>
                </ul>
            </div>
        </div>

    </section>
@endsection