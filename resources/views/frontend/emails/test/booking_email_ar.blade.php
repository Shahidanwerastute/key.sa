<?php if(isset($isPdf) && $isPdf == "pdf"){
    $pdfStyle = 0;
}else{
    $pdfStyle = 1;
}
$currency = "ريال سعودي";

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Key Rental</title>
    <link href="<?php echo custom::baseurl('/public/frontend/css/rtl.css'); ?>" rel="stylesheet" type="text/css" media="all">

    <link href="http://fonts.googleapis.com/css?family=Cairo:300,400,600,700,900|Lato:100,300,400,700,900" rel="stylesheet">
    <!--[if gte mso 10]><link href="http://fonts.googleapis.com/css?family=Cairo:300,400,600,700,900|Roboto+Condensed:300,400,700" rel="stylesheet"><![endif]-->
    <!--[if gte mso 10]>
    <style>
        body{
            font-size:13px;
            font-weight:400;
            line-height:16px;
        }
        body, table, td, a, h1, h2, h3 p{font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif !important;}
    </style>
    <![endif]-->
    <?php if($pdfStyle == 1){ ?>
    <style type="text/css">
        #outlook a{
            padding:0;
        }
        body{
            margin:0;
            padding:0;
            width:100% !important;
            -webkit-text-size-adjust:none;
        }
        img{
            border:none;
            font-size:14px;
            font-weight:bold;
            height:auto;
            line-height:100%;
            outline:none;
            text-decoration:none;
            text-transform:capitalize;
        }
        @media screen and (max-width: 679px) {
            body{
                font-size:13px;
                font-weight:400;
                line-height:16px;
                font-family: 'Lato', sans-serif !important;
            }
            .responsive-table {
                width: 100% !important;
            }
            .topbar,
            .thead,
            .bar-code,
            .tfoot{
                padding:14px 35px !important;
            }
            .intro{
                padding:27px 35px !important;
            }
            .col-4{
                padding:20px 35px !important;
            }
            .toyota,
            .two-col{
                padding:25px 35px !important;
            }
        }

        @media screen and (max-width: 599px){
            .topbar{
                padding:10px 15px !important;
            }
            .thead{ padding:10px 15px !important;}
            .thead h1{
                font-size:16px !important;
                line-height:18px !important;
            }
            .thead h1 span{
                font-size:14px !important;
                line-height:16px !important;
            }
            .logo{
                height:auto;
                width:126px !important;
            }
            .intro{
                text-align:center;
                padding:0 15px 20px !important;
            }
            .intro tr,
            .intro td{
                display:block;
                text-align:center !important;
            }
            .intro td,
            .bar-code td{ padding-top:20px !important;}
            .bar-code{
                padding:0 15px 20px !important;
            }
            .bar-code tr,
            .bar-code td,
            .toyota tr,
            .toyota td{
                display:block;
                text-align:center !important;
            }
            .bar-code td{ width:auto !important;}
            .toyota{ padding:20px 15px !important;}
            .bar-code img,
            .toyota img{
                float:none !important;
                display:inline-block;
            }
            .toyota img{ padding-top:20px;}
            .toyota h2 span,
            .toyota h2 strong{
                display:block;
            }

            .four-col > tr{
                display:block;
                width:auto !important;
            }

            .col-4{
                display:block;
                border-left:0 !important;
                width:auto !important;
                padding:10px 15px !important;
            }
            .col-4 table td{ padding-top:5px !important;}
            .col-4 table td h3{ margin:0 !important;}

            .two-column .two-col{
                width:auto !important;
                padding:20px 15px !important;
            }
            .two-column .two-col p strong{
                font-size:20px !important;
                line-height:24px !important;
            }
            .two-column tr,
            .two-column td{
                display:block;
                border:0 !important;
                text-align:center !important;
            }

            .tfoot{
                padding:0 15px 10px !important;
            }
            .tfoot .tr,
            .tfoot .col{
                display:block;
                overflow:hidden;
                text-align:center !important;
            }
            .tfoot .col{ padding-top:10px !important;}
        }
    </style>
    <?php } ?>
</head>
<body class="arb" style="margin:0; direction: rtl; padding:0; font-family: 'Lato', sans-serif; font-size:13px; font-weight:400; line-height:16px; color:#858585; background:#fff;">
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-family: 'Lato', sans-serif;">
    <tr>
        <td align="center">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="680">
                <tr>
                    <td align="center" valign="top" width="680">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" style="width:680px;" class="responsive-table">
                <tr>
                    <td style="padding:14px 33px 14px 50px; background:#444; border-bottom:2px solid #f1af1d;" class="topbar">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <?php
                            $path_icon = custom::baseurl('/resources/views/frontend/emails/images/ico-tell.png');
                            ?>
                            <tr>
                                <td><img src="<?php if(isset($message)){ echo $message->embed($path_icon); } else{ echo $path_icon;} ?>" alt="" width="10" height="12" style="display:inline-block; margin:0; vertical-align:middle;">&nbsp;&nbsp;<a href="tel:<?php echo custom::site_phone(); ?>" style="color:#fff; font-family: 'Lato', sans-serif; font-size:12px; font-weight:400; cursor:pointer; text-decoration:none;"><?php echo custom::site_phone(); ?></a></td>
                                <td style="text-align:left;"><a href="www.key.sa" style="font-size:12px; color:#f8ac1d; font-family: 'Lato', sans-serif; text-decoration:none;">www.key.sa</a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:14px 33px 14px 50px;" class="thead">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <?php
                            $path_logo = custom::baseurl('/resources/views/frontend/emails/images/logo.png?v=' . rand());
                            $path_divider = custom::baseurl('/resources/views/frontend/emails/images/divider.png');
                            ?>
                            <tr>
                                <td><a href="#" style="display:inline-block; height:auto; cursor:pointer; text-decoration:none;"><img src="<?php if(isset($message)){ echo $message->embed($path_logo); } else{ echo $path_logo;} ?>" alt="logo" width="171" height="43" class="logo"></a></td>
                                <td style="text-align:left;">
                                    <h1 style="margin:0;font-family: 'Lato', sans-serif; font-size:22px; font-weight:400; line-height:22px; color:#f8ac1d;"> الحجز  <p style="display:block; font-size:18px; line-height:18px; color:#666; font-family:300; margin:0;"> <?php echo ($booking_content['booking_status'] == 'Cancelled' || $booking_content['booking_status'] == 'Expired' ? 'إلغاء' : 'تأكيد'); ?> </p></h1></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <?php
                    if ($booking_content['gender'] != '')
                    {
                        if ($booking_content['gender'] == 'female')
                        {
                            $gender = 'عزيزتي';
                        }else{
                            $gender = 'عزيزي';
                        }
                    }else{
                        if ($booking_content['icg_gender'] == 'female')
                        {
                            $gender = 'عزيزتي';
                        }else{
                            $gender = 'عزيزي';
                        }
                    }
                    ?>
                    <td style="padding:27px 33px 26px 52px; background:url(<?php if(isset($message)){ echo $message->embed($path_divider); } else{ echo $path_divider;} ?>) repeat-x; border-top:1px solid #ccc;" class="intro">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td>
                                    <p style="margin:0 0 5px; color:#b8193d; font-family: 'Lato', sans-serif;font-weight:bold;"> <?php echo $gender; ?> <?php echo ($booking_content['first_name'] != '' ? $booking_content['first_name'] : $booking_content['icg_first_name'])." ".($booking_content['last_name'] != '' ? $booking_content['last_name'] : $booking_content['icg_last_name']); ?>,</p>
                                    <p style="margin:0; width:280px; font-family: 'Lato', sans-serif;">شكرا لحجزكم مع شركة المفتاح، مرفق لكم تفاصيل الحجز</p>
                                </td>
                                <td style="text-align:left;">
                                    <p style="margin:0; color:#b8193d; font-family: 'Lato', sans-serif;font-weight:bold;"> معلومات التواصل مع فرع التسليم </p>
                                    <p style="margin:0;"><strong style="display:block; color:#444; font-size:17px; line-height:20px;"><a href="tel:<?php echo $booking_content['branch_mobile']; ?>" style="color:#444; cursor:default; text-decoration:none;"><?php echo $booking_content['branch_mobile']; ?></a></strong></p>
                                    <p style="margin:0; color:#444; font-family: 'Lato', sans-serif;"><?php echo $booking_content['branch_from_arb_title']; ?> </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:14px 33px 12px 52px; background:#eff0e0;" class="bar-code">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <?php
                            //$path_captcha= custom::baseurl('/resources/views/frontend/emails/images/captcha.png');
                            $barcode = custom::generateBarcodeImage($booking_content['reservation_code'], 'code');
                            ?>
                            <tr>
                                <td style="font-size:12px; width:50%; font-family: 'Lato', sans-serif;"> رقم الحجز  <p style="display:block; margin:0; font-size:16px; line-height:20px; font-family: 'Lato', sans-serif; font-weight:400;"><a href="javascript:void(0);" style=" color:#b8193d; text-decoration:none; cursor:default;"><?php echo $booking_content['reservation_code']; ?></a></p></td>
                                <td style="width:50%; text-align:left;"><img src="<?php echo $barcode; ?>" width="227" height="61" alt="bar code" style="display:inline-block;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:25px 33px 27px 54px" class="toyota">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td>
                                    <h2 style="margin:0 0 10px; font-size:24px; font-family: 'Lato', sans-serif; line-height:24px; color:#b8193d;"><?php echo $booking_content['car_type_arb_title']." ".$booking_content['car_model_arb_title']." ".$booking_content['year']; ?> <strong style="font-size:14px; font-weight:400; color:#858585;"> او مشابه </strong><p style="display:block; margin:0; font-size:11px; line-height:14px; color:#666;"><?php echo $booking_content['car_category_arb_title']; ?></p></h2>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr><td style="color:#b8193d; font-weight:bold; font-family: 'Lato', sans-serif;"><?php echo $booking_content['first_name']." ".$booking_content['last_name']; ?></td></tr>
                                        <?php if($booking_content['id_no'] !=""){ ?>
                                            <tr><td style="font-family: 'Lato', sans-serif;"><span style="color:#b8193d;font-weight:bold;">رقم بطاقة الهوية:</span> <a href="#" style="color:#666; text-decoration:none; cursor:default;"><?php echo custom::maskText($booking_content['id_no'], 4); ?></a></td></tr>
                                        <?php }else{ ?>
                                            <tr><td style="font-family: 'Lato', sans-serif;"><span style="color:#b8193d;font-weight:bold;">رقم بطاقة الهوية:</span> <a href="#" style="color:#666; text-decoration:none; cursor:default;"><?php echo custom::maskText($booking_content['icg_id_no'], 4); ?></a></td></tr>

                                        <?php } ?>
                                        <?php if($booking_content['mobile_no'] !=""){ ?>
                                            <tr><td style="font-family: 'Lato', sans-serif;"><span style="color:#b8193d;font-weight:bold;">رقم الجوال:</span> <a href="tel:<?php echo custom::maskText($booking_content['mobile_no'], 6); ?>" style="color:#666; text-decoration:none; cursor:pointer;"><?php echo custom::maskText($booking_content['mobile_no'], 6); ?></a></td></tr>
                                        <?php }else{ ?>
                                            <tr><td style="font-family: 'Lato', sans-serif;"><span style="color:#b8193d;font-weight:bold;">رقم الجوال:</span> <a href="tel:<?php echo custom::maskText($booking_content['icg_mobile_no'], 6); ?>" style="color:#666; text-decoration:none; cursor:pointer;"><?php echo custom::maskText($booking_content['icg_mobile_no'], 6); ?></a></td></tr>
                                        <?php } ?>
                                        <?php if($booking_content['email'] !=""){ ?>
                                            <tr><td style="font-family: 'Lato', sans-serif;"><span style="color:#b8193d;font-weight:bold;">البريد الإلكتروني:</span> <a href="mailto:<?php echo custom::maskText($booking_content['email'], 3); ?>" style="color:#666; text-decoration:none; cursor:pointer;"><?php echo custom::maskText($booking_content['email'], 3); ?></a></td></tr>
                                        <?php }else{ ?>
                                            <tr><td style="font-family: 'Lato', sans-serif;"><span style="color:#b8193d;font-weight:bold;">البريد الإلكتروني:</span> <a href="mailto:<?php echo custom::maskText($booking_content['icg_email'], 3); ?>" style="color:#666; text-decoration:none; cursor:pointer;"><?php echo custom::maskText($booking_content['icg_email'], 3); ?></a></td></tr>

                                        <?php } ?>
                                    </table>
                                </td>
                                <?php
                                $path_car = custom::baseurl('/public/uploads/'.$booking_content["car_image"]);
                                ?>
                                <td style="text-align:left;"><img src="<?php if(isset($message)){ echo $message->embed($path_car); } else{ echo $path_car;} ?>" width="255" height="123" alt="Car" style="display:inline-block;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="background:#eff0e0;" class="four-col">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td style="background:#eff0e0; width:50%; border-bottom:2px solid #fff; padding:18px 54px 20px;" class="col-4">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">

                                        <?php
                                        $path_ico1= custom::baseurl('/resources/views/frontend/emails/images/ico1.png');
                                        $path_ico2= custom::baseurl('/resources/views/frontend/emails/images/ico2.png');
                                        $path_ico3= custom::baseurl('/resources/views/frontend/emails/images/ico3.png');

                                        $arrDate = explode(" ",$booking_content['from_date']);
                                        $fromDate = date('d / m / Y', strtotime($arrDate[0]));
                                        $fromTime = date( 'H:i A', strtotime( $arrDate[1] ) );

                                        $arrDate = explode(" ",$booking_content['to_date']);
                                        $toDate = date('d / m / Y', strtotime($arrDate[0]));
                                        $toTime = date( 'H:i A', strtotime( $arrDate[1] ) );

                                        ?>

                                        <tr><td><h3 style="color:#b8193d; margin:0; font-size:12px; line-height:16px; text-transform:uppercase; font-weight:bold; font-family: 'Lato', sans-serif;"> الإستلام</h3></td></tr>
                                        <tr><td style="padding-top:16px; font-family: 'Lato', sans-serif; font-weight:bold; color:#444; font-size:11px; line-height:14px;"><img src="<?php if(isset($message)){ echo $message->embed($path_ico1); } else{ echo $path_ico1;} ?>" width="9" height="12" alt="" style="display:inline-block; vertical-align:middle;">&nbsp;&nbsp;&nbsp; <?php echo $booking_content['branch_from_arb_title']; ?></td></tr>
                                        <tr><td style="padding-top:12px; font-family: 'Lato', sans-serif; font-weight:bold; color:#b8193d; font-size:11px; line-height:14px;"><img src="<?php if(isset($message)){ echo $message->embed($path_ico2); } else{ echo $path_ico2;} ?>" width="11" height="12" alt="" style="display:inline-block; vertical-align:middle;">&nbsp;&nbsp;&nbsp; <?php echo $fromDate; ?></td></tr>
                                        <tr><td style="padding-top:12px; font-family: 'Lato', sans-serif; font-weight:bold; color:#b8193d; font-size:11px; line-height:14px;"><img src="<?php if(isset($message)){ echo $message->embed($path_ico3); } else{ echo $path_ico3;} ?>" width="12" height="12" alt="" style="display:inline-block; vertical-align:middle;">&nbsp;&nbsp;&nbsp; <?php echo $fromTime; ?></td></tr>
                                    </table>
                                </td>
                                <td style="background:#eff0e0; width:50%; border-bottom:2px solid #fff; border-left:2px solid #fff; padding:18px 54px 20px;" class="col-4">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr><td><h3 style="color:#b8193d; margin:0; font-size:12px; line-height:16px; text-transform:uppercase; font-weight:bold; font-family: 'Lato', sans-serif;"> التسليم </h3></td></tr>
                                        <tr><td style="padding-top:16px; font-family: 'Lato', sans-serif; font-weight:bold; color:#444; font-size:11px; line-height:14px;"><img src="<?php if(isset($message)){ echo $message->embed($path_ico1); } else{ echo $path_ico1;} ?>" width="9" height="12" alt="" style="display:inline-block; vertical-align:middle;">&nbsp;&nbsp;&nbsp; <?php echo $booking_content['branch_to_arb_title']; ?></td></tr>
                                        <tr><td style="padding-top:12px; font-family: 'Lato', sans-serif; font-weight:bold; color:#b8193d; font-size:11px; line-height:14px;"><img src="<?php if(isset($message)){ echo $message->embed($path_ico2); } else{ echo $path_ico2;} ?>" width="11" height="12" alt="" style="display:inline-block; vertical-align:middle;">&nbsp;&nbsp;&nbsp; <?php echo $toDate; ?></td></tr>
                                        <tr><td style="padding-top:12px; font-family: 'Lato', sans-serif; font-weight:bold; color:#b8193d; font-size:11px; line-height:14px;"><img src="<?php if(isset($message)){ echo $message->embed($path_ico3); } else{ echo $path_ico3;} ?>" width="12" height="12" alt="" style="display:inline-block; vertical-align:middle;">&nbsp;&nbsp;&nbsp; <?php echo $toTime; ?></td></tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="background:#eff0e0; width:50%; border-bottom:2px solid #fff; padding:18px 54px 28px" class="col-4">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr><td><h3 style="color:#b8193d; margin:0 0 12px; font-size:12px; line-height:16px; text-transform:uppercase; font-weight:bold; font-family: 'Lato', sans-serif;"> التكاليف</h3></td></tr>

                                        <?php if(round($booking_content['cdw_price']) > 0){ ?>
                                            <tr><td> @lang('labels.lost_damage') <strong style="color:#444;">(<?php echo $booking_content['cdw_price']; ?> <?php echo $currency; ?>) x <?php echo $booking_content['no_of_days']?> أيام</strong></td></tr>
                                        <?php } ?>
                                        <?php if(round($booking_content['gps_price']) > 0){ ?>
                                            <tr><td> @lang('labels.gps') <strong style="color:#444;">(<?php echo $booking_content['gps_price']; ?> <?php echo $currency; ?> x <?php echo $booking_content['no_of_days']?> أيام </strong></td></tr>
                                        <?php } ?>
                                        <?php if(round($booking_content['extra_driver_price']) > 0){ ?>
                                            <tr><td> @lang('labels.extra_driver') <strong style="color:#444;">(<?php echo $booking_content['extra_driver_price']; ?> <?php echo $currency; ?>) x <?php echo $booking_content['no_of_days']?> أيام </strong></td></tr>
                                        <?php } ?>
                                        <?php if(round($booking_content['baby_seat_price']) > 0){ ?>
                                            <tr><td> @lang('labels.baby_protection') <strong style="color:#444;">(<?php echo $booking_content['baby_seat_price']; ?> <?php echo $currency; ?>) x <?php echo $booking_content['no_of_days']?> أيام </strong></td></tr>
                                        <?php } ?>
                                        <?php if(round($booking_content['dropoff_charges']) > 0){ ?>
                                            <tr><td> رسوم خدمة توصيل السيارة <strong style="color:#444;">(<?php echo $booking_content['dropoff_charges']; ?> <?php echo $currency; ?>) x <?php echo $booking_content['no_of_days']?> أيام </strong></td></tr>
                                        <?php } ?>
                                                <!-- New -->
                                        <tr><td>RENT <strong style="color:#444;">(<?php echo number_format($booking_content['rent_price'], 2); ?> <?php echo $currency; ?>) x <?php echo $booking_content['no_of_days']?> DAYS</strong></td></tr>
                                    </table>
                                </td>
                                <td style="background:#eff0e0; width:50%; border-bottom:2px solid #fff; border-left:2px solid #fff; padding:18px 54px 28px" class="col-4">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr><td><h3 style="color:#b8193d; margin:0 0 20px; font-size:12px; line-height:16px; text-transform:uppercase; font-weight:bold; font-family: 'Lato', sans-serif;"> المواصفات </h3></td></tr>
                                        <tr>
                                            <td style="width:100%; font-size:10px; font-weight:bold;">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">

                                                    <?php
                                                    $path_ico4= custom::baseurl('/resources/views/frontend/emails/images/ico4.png');
                                                    $path_ico5= custom::baseurl('/resources/views/frontend/emails/images/ico5.png');
                                                    $path_ico6= custom::baseurl('/resources/views/frontend/emails/images/ico6.png');
                                                    $path_ico7= custom::baseurl('/resources/views/frontend/emails/images/ico7.png');

                                                    ?>
                                                    <tr>
                                                        <td style="width:25%;"><p style="margin:0 0 10px;"><img src="<?php if(isset($message)){ echo $message->embed($path_ico4); } else{ echo $path_ico4;} ?>" width="16" height="17" alt="" style="display:block;"></p> <?php echo $booking_content['no_of_passengers']?> </td>
                                                        <td style="width:25%;"><p style="margin:0 0 10px;"><img src="<?php if(isset($message)){ echo $message->embed($path_ico5); } else{ echo $path_ico5;} ?>" width="18" height="18" alt="" style="display:block;"></p> <?php echo ($booking_content['transmission'] == 'Auto' ? 'اتوماتيك' : 'عادي'); ?></td>
                                                        <td style="width:25%;"><p style="margin:0 0 10px;"><img src="<?php if(isset($message)){ echo $message->embed($path_ico6); } else{ echo $path_ico6;} ?>" width="19" height="17" alt="" style="display:block;"></p> <?php echo $booking_content['no_of_doors']?> </td>
                                                        <td style="width:25%;"><p style="margin:0 0 10px;"><img src="<?php if(isset($message)){ echo $message->embed($path_ico7); } else{ echo $path_ico7;} ?>" width="22" height="18" alt="" style="display:block;"></p> <?php echo $booking_content['no_of_bags']?> </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="two-column">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <?php if ($booking_content['discount_price'] > 0)
                                { ?>
                                <td style="background:#666; width:49.8%; padding:24px 53px 23px;" class="two-col">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color:#fff; font-size:13px; line-height:14px; font-weight:bold; font-family: 'Lato', sans-serif;">
                                                    <p style="margin:0;">سعر الايجار قبل الخصم <?php echo number_format($booking_content['original_rent'], 2); ?> <?php echo $currency; ?></p>
                                                    <p style="margin:0;">تطبيق الخصم</p>
                                                    <p style="margin:0;"><strong style="font-size:25px; line-height:25px; font-weight:900;"><?php echo number_format($booking_content['discount_price'], 2); ?> <?php echo $currency; ?></strong></p>
                                                </td>
                                            </tr>
                                    </table>
                                </td>
                                <?php } ?>
                                <td style="background:#f8ab1d; border-left:2px solid #fff; width:50%; padding:24px 53px 23px; font-size:13px; margin:0; line-height:14px; font-weight:bold; color:#fff;" class="two-col">
                                    <?php
                                    $path_sadad = custom::baseurl('/resources/views/frontend/emails/images/img-sadad.png');

                                    ?>

                                    <p style="margin:0;"> مجموع المدفوع </p>
                                    <p style="margin:0;"><strong style="display:block; font-size:40px; line-height:40px; font-weight:900; color:#9F063F;"><?php echo number_format($booking_content['total_sum'], 2); ?> <?php echo $currency; ?></strong></p>
                                    <p style="margin:0;"><span style="font-size:10px; font-weight:bold; display:inline-block;">عن</span>
                                        <?php if($booking_content['payment_method'] == "Sadad"){ ?>
                                        <img src="<?php if(isset($message)){ echo $message->embed($path_sadad); } else{ echo $path_sadad;} ?>" width="48" height="14" alt=""></p>
                                <?php }else{
                                    echo $booking_content['payment_method'];
                                }
                                ?>


                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border-top:2px solid #f8ab1d; background:#2a2521; padding:14px 36px 16px 52px;" class="tfoot">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr class="tr">
                                <td style="color:#fff; font-size:9px; line-height:14px; font-weight:700; font-family: 'Lato', sans-serif;" class="col">حقوق الملكية <?php echo date('Y'); ?>. جميع الحقوق محفوظة </td>
                                <td style="text-align:left;" class="col">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <?php
                                        $path_fb = custom::baseurl('/resources/views/frontend/emails/images/ico-facebook.png');
                                        $path_tw = custom::baseurl('/resources/views/frontend/emails/images/ico-twitter.png');
                                        $path_li = custom::baseurl('/resources/views/frontend/emails/images/ico-linkedin.png');
                                        $path_inst = custom::baseurl('/resources/views/frontend/emails/images/ico-instagram.png');
                                        $path_yt = custom::baseurl('/resources/views/frontend/emails/images/ico-youtube.png');

                                        ?>

                                        <?php $social = custom::social_links(); ?>
                                        <tr>
                                            <td style="font-size:8px; line-height:14px; padding-right:10px; color:#fff;"> تابعونا </td>
                                            <?php if($social->facebook_link != ""){ ?>
                                                <td><a href="<?php echo $social->facebook_link; ?>" style="display:inline-block; height:auto;"><img src="<?php if(isset($message)){ echo $message->embed($path_fb); } else{ echo $path_fb;} ?>" width="9" height="16" alt="facebook" style="display:block;"></a></td>
                                            <?php } if($social->twitter_link != ""){ ?>
                                                <td><a href="<?php echo $social->twitter_link; ?>" style="display:inline-block; height:auto;"><img src="<?php if(isset($message)){ echo $message->embed($path_tw); } else{ echo $path_tw;} ?>" width="15" height="13" alt="twitter" style="display:block;"></a></td>
                                            <?php } if($social->linkedin_link != ""){ ?>
                                                <td><a href="<?php echo $social->linkedin_link; ?>" style="display:inline-block; height:auto;"><img src="<?php if(isset($message)){ echo $message->embed($path_li); } else{ echo $path_li;} ?>" width="14" height="14" alt="linkedin" style="display:block;"></a></td>
                                            <?php } if($social->instagram_link != ""){ ?>
                                                <td><a href="<?php echo $social->instagram_link; ?>" style="display:inline-block; height:auto;"><img src="<?php if(isset($message)){ echo $message->embed($path_inst); } else{ echo $path_inst;} ?>" width="15" height="14" alt="instagram" style="display:block;"></a></td>
                                            <?php } if($social->youtube_link != ""){ ?>
                                                <td><a href="<?php echo $social->youtube_link; ?>" style="display:inline-block; height:auto;"><img src="<?php if(isset($message)){ echo $message->embed($path_yt); } else{ echo $path_yt;} ?>" width="21" height="14" alt="youtube" style="display:block;"></a></td>
                                            <?php } ?>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
</table>
</body>
</html>