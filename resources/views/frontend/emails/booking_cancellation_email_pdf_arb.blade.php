<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Key Car Rental</title>
    <style type="text/css">
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
            font-size: 13px;
        }

        /*@font-face {
            font-family: 'DroidArabicKufi-Regular';
            src: url("https://www.key.sa/public/frontend/fonts/DroidArabicKufi_gdi.eot");
            src: url("https://www.key.sa/public/frontend/fonts/DroidArabicKufi_gdi.eot?#iefix") format("embedded-opentype"), url("https://www.key.sa/public/frontend/fonts/DroidArabicKufi_gdi.woff") format("woff"), url("https://www.key.sa/public/frontend/fonts/DroidArabicKufi_gdi.ttf") format("truetype"), url("https://www.key.sa/public/frontend/fonts/DroidArabicKufi_gdi.svg#DroidArabicKufi-Regular") format("svg");
            font-weight: 400;
            font-style: normal;
            font-stretch: normal;
            unicode-range: U+0020-FEFC;
        }*/

        body {
            /*font-family: 'DroidArabicKufi-Regular','Lato', sans-serif;*/
            direction: ltr;
        }
    </style>
</head>

<body>
<div style="margin:0 auto; text-align:center;">
    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td height="124" align="center" valign="middle"
                style="background:url({{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-top-bg.jpg')}}) repeat-x;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="25%" align="center">
                            <table width="80%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="70%">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="right" valign="top"
                                                    style="font-size:24px; color:#fe7c00; font-weight:bold;">الحجز
                                                </td>
                                            </tr>
                                            <?php if ($booking_content['type'] == "corporate_customer") {
                                                $type_txt = "حجز شركات";
                                            } else {
                                                $type_txt = "حجز أفراد";
                                            }?>
                                            <tr>
                                                <td align="right" valign="top"
                                                    style=" font-weight:bold; color:#494948;">{{$type_txt}}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="30%">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                        <?php
                        if ((isset($booking_content['ic_loyalty_card_type']) && $booking_content['ic_loyalty_card_type'] == 'Platinum') || (isset($booking_content['icg_loyalty_card_type']) && $booking_content['icg_loyalty_card_type'] == 'Platinum')) {
                            $loyality_card_image = custom::get_base64_path('/public/frontend/images/platinum_card_img.png');
                        } elseif ((isset($booking_content['ic_loyalty_card_type']) && $booking_content['ic_loyalty_card_type'] == 'Golden') || (isset($booking_content['icg_loyalty_card_type']) && $booking_content['icg_loyalty_card_type'] == 'Golden')) {
                            $loyality_card_image = custom::get_base64_path('/public/frontend/images/golden_card_img.png');
                        } elseif ((isset($booking_content['ic_loyalty_card_type']) && $booking_content['ic_loyalty_card_type'] == 'Silver') || (isset($booking_content['icg_loyalty_card_type']) && $booking_content['icg_loyalty_card_type'] == 'Silver')) {
                            $loyality_card_image = custom::get_base64_path('/public/frontend/images/silver_card_img.png');
                        } elseif ((isset($booking_content['ic_loyalty_card_type']) && $booking_content['ic_loyalty_card_type'] == 'Bronze') || (isset($booking_content['icg_loyalty_card_type']) && $booking_content['icg_loyalty_card_type'] == 'Bronze')) {
                            $loyality_card_image = custom::get_base64_path('/public/frontend/images/bronze_card_img.png');
                        } else {
                            $loyality_card_image = '';
                        }
                        ?>
                        <td width="50%" align="center">
                            <table width="80%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" valign="top"></td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top"><img
                                                src="{{$loyality_card_image}}" width="114"
                                                height="60" alt=""/></td>
                                </tr>
                            </table>
                        </td>
                        <td width="25%" align="center"><img
                                    src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-logo.jpg')}}"
                                    width="155" height="59"/></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="50" align="center" valign="top">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="40%" align="center">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="color:#494948;">معلومات التواصل مع فرع التسليم</td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top" style="height:5px;"></td>
                                </tr>
                                <tr>
                                    <td align="center"
                                        style="font-weight:bold; color:#494948;unicode-bidi: plaintext;"><?php echo $booking_content['branch_mobile']; ?></td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top" style="height:5px;"></td>
                                </tr>
                                <tr>
                                    <td align="center"
                                        style="color:#fe7c00;"><?php echo $booking_content['branch_from_arb_title']; ?></td>
                                </tr>
                            </table>
                        </td>
                        <td width="10%">&nbsp;</td>
                        <td width="50%" align="right" valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <?php
                                if ($booking_content['gender'] != '') {
                                    if ($booking_content['gender'] == 'female') {
                                        $gender = 'عزيزتي';
                                    } else {
                                        $gender = 'عزيزي';
                                    }
                                } else {
                                    if ($booking_content['icg_gender'] == 'female') {
                                        $gender = 'عزيزتي';
                                    } else {
                                        $gender = 'عزيزي';
                                    }
                                }
                                ?>
                                <tr>
                                    <td align="right" valign="top" style="color:#494948; direction:rtl;"><?php echo $gender; ?> <span
                                                style="color:#fe7c00;"><?php echo ($booking_content['first_name'] != '' ? $booking_content['first_name'] : $booking_content['icg_first_name']) . " " . ($booking_content['last_name'] != '' ? $booking_content['last_name'] : $booking_content['icg_last_name']); ?></span>
                                        ,
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top" style="height:5px;"></td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top" style="color:#494948;">
                                        شكرا لحجزكم مع شركة المفتاح، مرفق لكم تفاصيل الحجز
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="middle" bgcolor="#e1e1e0" style="padding:14px 0;">
                <table width="95%" border="0" cellspacing="0" cellpadding="0">

                    <?php $company_name = ($booking_content['type'] == "corporate_customer" ? $booking_content['company_name_en'] : ""); ?>
                    @if($company_name)
                        <tr>
                            <td align="right" style="font-weight:bold;">
                                {{$company_name}}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td align="center" valign="top" style="font-weight:bold;">رقم الحجز</td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" style="height:5px;"></td>
                    </tr>
                    <tr>
                        <td align="center" valign="top"
                            style="color:#fe7c00; font-weight:bold;"><?php echo $booking_content['reservation_code']; ?></td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" style="height:15px;"></td>
                    </tr>
                    <tr>
                        <td align="right" valign="top">
                            <ul style="direction:rtl; margin:0;">
                                <li>سيتم حجز مبلغ تأمين بقيمة ٣٠٠٠ ريال سعودي فقط لمواطنين مجلس التعاون الخليجي و حاملين تأشيرة الزيارة، سيتم إرجاع مبلغ التأمين بعد ١٠ ايام من تاريخ ارجاع السيارة وعند التأكد من عدم وجود اي مخالفة مرورية.</li>
                                <li>سیتم خصم 25 % من قیمة إیجار الیوم الأول في حالة إلغاء الحجز قبل 6ساعات من تاریخ الإستلام</li>
                                <li>في حالة الإلغاء بعد وقت الاستلام أو عدم إستلام السیارة، سیتم خصم یوم واحد من قیمة الإیجار</li>
                                <li>قد يتطلب بطاقة إئتمانية بحسب سياسة الشركة</li>
                                <li>في حالة وجود خروج وعودة يتم إضافة قيمة التفويض الدولي في حالة رغبتكم بفتح العقد (رسوم إضافية)</li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top"
                style="background:url({{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-shadow-bg.jpg')}}) repeat-x; height:10px"></td>
        </tr>
        <tr>
            <td align="right" valign="top" style="height:15px;"></td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="45%" align="center" valign="top">
                            <img src="{{custom::get_base64_path('/public/uploads/' . $booking_content["car_image"])}}"
                                 style="display:inline-block;" width="365" height="193">
                        </td>
                        <td width="10%" align="center" valign="top">&nbsp;</td>
                        <td width="45%" align="right" valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="right" valign="top"><span
                                                style="color:#fe7c00; font-weight:bold; font-size:20px;"><?php echo $booking_content['car_type_arb_title'] . " " . $booking_content['car_model_arb_title'] . " " . $booking_content['year']; ?></span><br>
                                        او مشابه
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top" style="height:5px;"></td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top" style="height:5px;"></td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top" style="height:5px;"></td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top"><span
                                                style="color:#fe7c00;"><?php echo $booking_content['first_name'] . " " . $booking_content['last_name']; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top" style="height:10px;"></td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="right">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                           style="direction:rtl;">
                                                        <tbody>
                                                        <?php if($booking_content['id_no'] != ""){ ?>
                                                        <tr>
                                                            <td align="right" style=""><span
                                                                        style="color:#000;font-weight:bold;">رقم بطاقة الهوية:</span>
                                                                <a style="color:#494948;text-decoration:none; cursor:default;"><?php echo custom::maskText($booking_content['id_no'], 4); ?></a>
                                                            </td>
                                                        </tr>
                                                        <?php }else{ ?>
                                                        <tr>
                                                            <td align="right" style=""><span
                                                                        style="color:#000;font-weight:bold;">رقم بطاقة الهوية:</span>
                                                                <a style="color:#494948;text-decoration:none; cursor:default;"><?php echo custom::maskText($booking_content['icg_id_no'], 4); ?></a>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php if($booking_content['mobile_no'] != ""){ ?>
                                                        <tr>
                                                            <td align="right" style=""><span
                                                                        style="color:#000;font-weight:bold;">رقم الجوال:</span>
                                                                <a style="color:#494948;text-decoration:none; cursor:default;"><?php echo custom::maskText($booking_content['mobile_no'], 6); ?></a>
                                                            </td>
                                                        </tr>
                                                        <?php }else{ ?>
                                                        <tr>
                                                            <td align="right" style=""><span
                                                                        style="color:#000;font-weight:bold;">رقم الجوال:</span>
                                                                <a style="color:#494948;text-decoration:none; cursor:default;"><?php echo custom::maskText($booking_content['icg_mobile_no'], 6); ?></a>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php if($booking_content['email'] != ""){ ?>
                                                        <tr>
                                                            <td align="right" style=""><span
                                                                        style="color:#000;font-weight:bold;">البريد الإلكتروني:</span>
                                                                <a style="color:#494948;text-decoration:none; cursor:default;"><?php echo custom::maskText($booking_content['email'], 3); ?></a>
                                                            </td>
                                                        </tr>
                                                        <?php }else{ ?>
                                                        <tr>
                                                            <td align="right" style=""><span
                                                                        style="color:#000;font-weight:bold;">البريد الإلكتروني:</span>
                                                                <a style="color:#494948;text-decoration:none; cursor:default;"><?php echo custom::maskText($booking_content['icg_email'], 3); ?></a>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign="top">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="right" valign="top" style="height:15px;"></td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="49%" align="center" valign="top"
                            style="background: #e1e1e1;border-top-right-radius: 20px;border-bottom-right-radius: 20px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <?php
                                    $arrDate = explode(" ", $booking_content['from_date']);
                                    $fromDate = date('d / m / Y', strtotime($arrDate[0]));
                                    $fromTime = date('H:i A', strtotime($arrDate[1]));

                                    $arrDate = explode(" ", $booking_content['to_date']);
                                    $toDate = date('d / m / Y', strtotime($arrDate[0]));
                                    $toTime = date('H:i A', strtotime($arrDate[1]));

                                    if ($booking_content['is_delivery_mode'] == 'yes') {
                                        $from_branch = custom::getCleanLocationName($booking_content['pickup_delivery_lat_long'], 'short') . "<br>(" . $booking_content['pickup_delivery_lat_long'] . ")";
                                        $to_branch = custom::getCleanLocationName($booking_content['dropoff_delivery_lat_long'], 'short') . "<br>(" . $booking_content['dropoff_delivery_lat_long'] . ")";
                                        $to_branch = 'يتم التسليم في اقرب فرع';
                                    } else {
                                        $from_branch = $booking_content['branch_from_arb_title'];
                                        $to_branch = $booking_content['branch_to_arb_title'];
                                    }

                                    ?>
                                    <td>
                                        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="right"
                                                    style="color:#fe7c00; font-weight:bold; font-size:20px;">
                                                    التسليم
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top" style="height:10px;"></td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                <?php echo $to_branch; ?>
                                                            </td>
                                                            <td width="10" align="right" valign="middle">&nbsp;</td>
                                                            <td width="25" align="right" valign="middle"><img
                                                                        src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-location-icon.jpg')}}"
                                                                        alt="" width="25" height="28"/></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                <?php echo $toDate; ?>
                                                            </td>
                                                            <td width="10" align="right" valign="middle">&nbsp;</td>
                                                            <td width="25" align="right" valign="middle"><img
                                                                        src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-calender-icon.jpg')}}"
                                                                        alt="" width="25" height="28"/></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                <?php echo $toTime; ?>
                                                            </td>
                                                            <td width="10" align="right" valign="middle">&nbsp;</td>
                                                            <td width="25" align="right" valign="middle"><img
                                                                        src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-clock-icon.jpg')}}"
                                                                        alt="" width="25" height="28"/></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2%" align="center" valign="top">&nbsp;</td>
                        <td width="49%" align="center" valign="top"
                            style="background: #e1e1e1;border-top-left-radius: 20px;border-bottom-left-radius: 20px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="right"
                                                    style="color:#fe7c00; font-weight:bold; font-size:20px;">
                                                    الإستلام
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top" style="height:10px;"></td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                <?php echo $from_branch; ?>
                                                            </td>
                                                            <td width="10" align="right" valign="middle">&nbsp;</td>
                                                            <td width="25" align="right" valign="middle"><img
                                                                        src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-location-icon.jpg')}}"
                                                                        alt="" width="25" height="28"/></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                <?php echo $fromDate; ?>
                                                            </td>
                                                            <td width="10" align="right" valign="middle">&nbsp;</td>
                                                            <td width="25" align="right" valign="middle"><img
                                                                        src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-calender-icon.jpg')}}"
                                                                        alt="" width="25" height="28"/></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                <?php echo $fromTime; ?>
                                                            </td>
                                                            <td width="10" align="right" valign="middle">&nbsp;</td>
                                                            <td width="25" align="right" valign="middle"><img
                                                                        src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-clock-icon.jpg')}}"
                                                                        alt="" width="25" height="28"/></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top" height="15"></td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="49%" align="center" valign="top"
                            style="background: #e1e1e1;border-top-right-radius: 20px;border-bottom-right-radius: 20px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height="130" valign="top">
                                        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="right"
                                                    style="color:#fe7c00; font-weight:bold; font-size:20px;">
                                                    المواصفات
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="25" align="right" valign="top">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="center" valign="top">&nbsp;</td>
                                                            <td align="center" valign="top"><img
                                                                        src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-bag.jpg')}}"
                                                                        width="35" height="31"/></td>
                                                            <td align="center" valign="top">&nbsp;</td>
                                                            <td align="center" valign="top"><img
                                                                        src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-doors.jpg')}}"
                                                                        width="35" height="31"/></td>
                                                            <td align="center" valign="top">&nbsp;</td>
                                                            <td align="center" valign="top"><img
                                                                        src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-gearbox.jpg')}}"
                                                                        alt="" width="35" height="31"/></td>
                                                            <td align="center" valign="top">&nbsp;</td>
                                                            <td align="center" valign="top"><img
                                                                        src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-passengers.jpg')}}"
                                                                        width="35" height="31"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" valign="top">&nbsp;</td>
                                                            <td align="center"
                                                                valign="top"><?php echo $booking_content['no_of_bags']?></td>
                                                            <td align="center" valign="top">&nbsp;</td>
                                                            <td align="center"
                                                                valign="top"><?php echo $booking_content['no_of_doors']?></td>
                                                            <td align="center" valign="top">&nbsp;</td>
                                                            <td align="center"
                                                                valign="top"><?php echo($booking_content['transmission'] == 'Auto' ? 'اتوماتيك' : 'عادي'); ?></td>
                                                            <td align="center" valign="top">&nbsp;</td>
                                                            <td align="center"
                                                                valign="top"><?php echo $booking_content['no_of_passengers']?></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2%" align="center" valign="top">&nbsp;</td>
                        <td width="49%" align="center" valign="top"
                            style="background: #e1e1e1;border-top-left-radius: 20px;border-bottom-left-radius: 20px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height="130" valign="top">
                                        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="right"
                                                    style="color:#fe7c00; font-weight:bold; font-size:20px;">التكاليف
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top" style="height:10px;"></td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    <table width="95%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <?php if ($booking_content['is_delivery_mode'] == 'subscription') { ?>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">تأجير 1 شهور
                                                                <strong><?php echo number_format($booking_content['rent_price'], 2); ?>
                                                                    ريال سعودي</strong>
                                                            </td>
                                                            <?php } else { ?>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">تأجير
                                                                for <?php echo $booking_content['no_of_days']?> <?php echo($booking_content['is_delivery_mode'] == 'hourly' ? 'ساعات' : 'أيام'); ?>
                                                                <strong><?php echo number_format($booking_content['rent_price'], 2); ?>
                                                                    ريال سعودي</strong>
                                                            </td>
                                                            <?php } ?>
                                                        </tr>
                                                        <?php
                                                        $total_extras = 0;
                                                        if (round($booking_content['cdw_price']) > 0) {
                                                            $total_extras += round($booking_content['cdw_price'] * $booking_content['no_of_days']);
                                                        }
                                                        if (round($booking_content['cdw_plus_price']) > 0) {
                                                            $total_extras += round($booking_content['cdw_plus_price'] * $booking_content['no_of_days']);
                                                        }
                                                        if (round($booking_content['extra_driver_price']) > 0) {
                                                            $total_extras += round($booking_content['extra_driver_price'] * $booking_content['no_of_days']);
                                                        }
                                                        if (round($booking_content['baby_seat_price']) > 0) {
                                                            $total_extras += round($booking_content['baby_seat_price'] * $booking_content['no_of_days']);
                                                        }
                                                        if (round($booking_content['dropoff_charges']) > 0) {
                                                            $total_extras += round($booking_content['dropoff_charges'] * $booking_content['no_of_days']);
                                                        }
                                                        ?>
                                                        <?php if ($total_extras > 0) { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                إجمالي الإضافات لـ {{$booking_content['no_of_days']}} أيام
                                                                <strong>{{$total_extras}} ريال سعودي</strong></td>
                                                        </tr>
                                                        <?php } ?>

                                                        <?php if(round($booking_content['delivery_charges']) > 0) { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                رسوم خدمة توصيل السيارة
                                                                <strong><?php echo $booking_content['delivery_charges']; ?>
                                                                    ريال سعودي</strong></td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php if ($booking_content['parking_fee'] > 0) { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                رسوم مواقف
                                                                <strong><?php echo $booking_content['parking_fee']; ?>
                                                                    ريال سعودي</strong></td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php if ($booking_content['tamm_charges_for_branch'] > 0) { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                رسوم تفويض (تم)
                                                                <strong><?php echo $booking_content['tamm_charges_for_branch']; ?>
                                                                    ريال سعودي</strong></td>
                                                        </tr>
                                                        <?php } ?>

                                                        <?php if ($booking_content['waiting_extra_hours_charges'] > 0) { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                تكلفة الساعات الإضافية
                                                                <strong><?php echo $booking_content['waiting_extra_hours_charges']; ?>
                                                                    ريال سعودي</strong></td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php if ($booking_content['booking_status'] == 'Cancelled' || $booking_content['booking_status'] == 'Expired') { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                رسوم الإلغاء
                                                                <strong><?php echo($booking_content['cancel_charges'] > 0 ? number_format($booking_content['cancel_charges'], 2) . ' ريال سعودي' : 'None'); ?></strong>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php if (round($booking_content['gps_price']) > 0) { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                كيلومتر مفتوح
                                                                <strong>
                                                                    (<?php echo $booking_content['gps_price']; ?> ريال سعودي
                                                                    x <?php echo $booking_content['no_of_days']?> <?php echo($booking_content['is_delivery_mode'] == 'hourly' ? 'ساعات' : 'أيام'); ?>
                                                                    )
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php } elseif ($booking_content['is_delivery_mode'] == "hourly") { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                الكيلومتر:
                                                                <strong>
                                                                    مجانا 250 الكيلومتر
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php } elseif ($booking_content['km'] != "") { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                الكيلومتر:
                                                                <strong>
                                                                    مجانا <?php echo $booking_content['km']; ?> كم يوميا
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php if ($booking_content['redeem_points'] > 0)
                                                        { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                عدد النقاط المستخدمة
                                                                <strong><?php echo $booking_content['redeem_points']; ?>
                                                                    نقطة
                                                                    (<?php echo number_format($booking_content['redeem_discount_availed'], 2); ?>
                                                                    ريال سعودي خصم)
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php }?>
                                                        <?php if ($booking_content['qitaf_amount'] > 0)
                                                        { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                المبلغ المستخدم قطاف
                                                                <strong>
                                                                    <?php echo $booking_content['qitaf_amount']; ?> ريال سعودي
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php }?>
                                                        <?php if ($booking_content['niqaty_amount'] > 0)
                                                        { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                المبلغ المستخدم نقاتي
                                                                <strong>
                                                                    <?php echo $booking_content['niqaty_amount']; ?> ريال سعودي
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php }?>
                                                        <?php if ($booking_content['mokafaa_amount'] > 0)
                                                        { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                مبلغ مكافأة المستخدم
                                                                <strong>
                                                                    <?php echo $booking_content['mokafaa_amount']; ?> ريال سعودي
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php }?>
                                                        <?php if ($booking_content['anb_amount'] > 0)
                                                        { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                مبلغ البنك العربي المستخدم
                                                                <strong>
                                                                    <?php echo $booking_content['anb_amount']; ?> ريال سعودي
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php }?>
                                                        <?php if ($booking_content['discount_price'] > 0)
                                                        { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                تطبيق الخصم
                                                                <strong>
                                                                    <?php echo $booking_content['discount_price']; ?> ريال سعودي
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <?php }?>

                                                        <?php if ($booking_content['vat_applied'] > 0) { ?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                ضريبة القيمة المضافة
                                                                <strong><?php echo number_format($booking_content['vat_applied'], 2); ?>
                                                                    ريال سعودي</strong></td>
                                                        </tr>
                                                        <?php } ?>
                                                        <?php if ($booking_content['booking_status'] == 'Cancelled' || $booking_content['booking_status'] == 'Expired') {
                                                            $cancelTxt = 'المبلغ المرتجع';
                                                            $totalSum = number_format($booking_content['total_sum'] - $booking_content['cancel_charges'], 2);
                                                        } else {
                                                            if ($booking_content['payment_method'] == 'Cash' || $booking_content['payment_method'] == 'Corporate Credit') {
                                                                $cancelTxt = 'المبلغ المطلوب';
                                                            } else {
                                                                $cancelTxt = 'مجموع المدفوع';
                                                            }
                                                            $totalSum = number_format($booking_content['total_sum'], 2);
                                                        }?>
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000; direction:rtl;">
                                                                {{$cancelTxt}}
                                                                <strong>{{$totalSum}} ريال سعودي</strong></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <?php if ($booking_content['discount_price'] > 0) { ?>
        <tr>
            <td align="center" valign="top" height="15"></td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="49%" align="center" valign="top"
                            style="background: #FE8510;border-top-right-radius: 20px;border-bottom-right-radius: 20px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height="118">
                                        <table width="95%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center" valign="top"
                                                    style="color:#000000; font-size:24px; font-weight:bold;">
                                                    {{$cancelTxt}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" valign="top"
                                                    style="color:#ffffff; font-size:26px; font-weight:bold; direction:rtl;">
                                                    {{$totalSum}} ريال سعودي
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" valign="top"
                                                    style="color:#000000; font-size:24px; font-weight:bold; direction:rtl">
                                                    <?php if ($booking_content['payment_method'] == "Corporate Credit") {
                                                        echo "Corporate Agreement";
                                                    } else {
                                                        echo $booking_content['payment_method'];
                                                    }

                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2%" align="center" valign="top">&nbsp;</td>
                        <td width="49%" align="center" valign="top"
                            style="background: #e1e1e1;border-top-left-radius: 20px;border-bottom-left-radius: 20px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height="120">
                                        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="right">
                                                    <table width="95%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="right" valign="middle"
                                                                style="color:#000;font-size: 26px;direction:rtl;">
                                                                تطبيق الخصم
                                                                <strong><?php echo $booking_content['discount_price']; ?> ريال سعودي</strong>
                                                            </td>
                                                        </tr>
                                                        <!--                                                            <tr>
                                                                                                                        <td align="right" valign="middle"
                                                                                                                            style="color:#000; direction:rtl;">إجمالي
                                                                                                                            الإضافات<strong> لمدة 1 يوم 0 ريال سعودي</strong></td>
                                                                                                                    </tr>-->
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top" height="15"></td>
        </tr>
        <?php } else { ?>
        <tr>
            <td align="center" valign="top">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="top" bgcolor="#fe8610">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="top" bgcolor="#fe8610">
                <table width="60%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" valign="top" style="color:#000000; font-size:25px; font-weight:bold;">
                            {{$cancelTxt}}
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" height="10"></td>
                    </tr>
                    <tr>
                        <td align="center" valign="top"
                            style="color:#ffffff; font-size:30px; font-weight:bold; direction:rtl;">
                            {{$totalSum}} ريال سعودي
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" height="10"></td>
                    </tr>
                    <tr>
                        <td align="center" valign="top"
                            style="color:#000000; font-size:25px; font-weight:bold; direction:rtl;">
                            <?php if ($booking_content['payment_method'] == "Corporate Credit") {
                                echo "Corporate Agreement";
                            } else {
                                echo $booking_content['payment_method'];
                            }

                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top" bgcolor="#fe8610">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="top">&nbsp;</td>
        </tr>
        <?php } ?>

        <?php $social = custom::social_links(); ?>
        <tr>
            <td align="center" valign="top" bgcolor="#e1e1e0">
                <table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="38%" align="right">
                            <table width="80%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <?php if($social->instagram_link != ""){ ?>
                                    <td align="center" valign="middle">
                                        <a href="{{$social->instagram_link}}" target="_blank"><img
                                                    src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-instragram.jpg')}}"
                                                    width="28" height="26"/></a>
                                    </td>
                                    <td align="center" valign="middle">&nbsp;</td>
                                    <?php } ?>
                                    <?php if($social->linkedin_link != ""){ ?>
                                    <td align="center" valign="middle">
                                        <a href="{{$social->linkedin_link}}" target="_blank"><img
                                                    src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-linkedin.jpg')}}"
                                                    width="28" height="26"/></a>
                                    </td>
                                    <td align="center" valign="middle">&nbsp;</td>
                                    <?php } ?>
                                    <?php if($social->twitter_link != ""){ ?>
                                    <td align="center" valign="middle"><a href="{{$social->twitter_link}}" target="_blank"><img
                                                    src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-twitter.jpg')}}"
                                                    width="28" height="26"/></a></td>
                                    <td align="center" valign="middle">&nbsp;</td>
                                    <?php } ?>
                                    <?php if($social->facebook_link != ""){ ?>
                                    <td align="center" valign="middle">
                                        <a href="{{$social->facebook_link}}" target="_blank"><img
                                                    src="{{custom::get_base64_path('/resources/views/frontend/emails/images/booking-confirmation/t-facebook.jpg')}}"
                                                    width="28" height="26"/></a></td>
                                    <td align="center" valign="middle">&nbsp;</td>
                                    <?php } ?>
                                    <td align="center" valign="middle"><strong>تابعونا</strong></td>
                                </tr>
                            </table>
                        </td>
                        <td width="2%">&nbsp;</td>
                        <td width="60%" height="40" align="right" style="direction:rtl;">حقوق الملكية حقوق الملكية <?php echo date('Y'); ?> . جميع الحقوق محفوظة
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
