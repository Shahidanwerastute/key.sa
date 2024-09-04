<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Key Rental</title>
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
</head>
<body style="margin:0; padding:0; font-family: 'Lato', sans-serif; font-size:13px; font-weight:400; line-height:16px; color:#858585; background:#fff;">
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
                    <td style="padding:14px 33px 14px 50px; background:#444; border-bottom:2px solid #FE7E00;" class="topbar">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td><img src="{{custom::get_base64_path('/resources/views/frontend/emails/images/ico-tell.png')}}" alt="" width="10" height="12" style="display:inline-block; margin:0; vertical-align:middle;">&nbsp;&nbsp;<a href="tel:<?php echo custom::site_phone(); ?>" style="color:#fff; font-family: 'Lato', sans-serif; font-size:12px; font-weight:400; cursor:pointer; text-decoration:none;"><?php echo custom::site_phone(); ?></a></td>
                                <td style="text-align:right;"><a href="<?php echo custom::baseurlNew('/') ?>" style="font-size:12px; color:#FE7E00; font-family: 'Lato', sans-serif; text-decoration:none;">www.key.sa</a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:14px 33px 14px 50px;" class="thead">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td><a href="#" style="display:inline-block; height:auto; cursor:pointer; text-decoration:none;"><img src="{{custom::get_base64_path('/resources/views/frontend/emails/images/logo.png')}}" alt="logo" width="171" height="43" class="logo"></a></td>

                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:14px 33px 12px 52px; background:#E1E1E1;" class="bar-code">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">

                            <tr>

                                <td style="vertical-align: top;  padding: 15px 0px; font-size:12px; font-family: 'Lato', sans-serif;">City Branch</td>
                                <td style="font-size:12px; color:black; font-family: 'Lato', sans-serif;"><p style="display:block; margin:0; font-size:16px; line-height:20px; font-family: 'Lato', sans-serif; font-weight:400;"><?php echo $locations->c_eng_title; ?></p></td>

                            </tr>

                            <tr>
                                <td style="vertical-align: top;  padding: 15px 0px; font-size:12px; width:30%; font-family: 'Lato', sans-serif;">Branch Name: </td>
                                <td style="font-size:12px; color:black; font-family: 'Lato', sans-serif;"><p style="display:block; margin:0; font-size:16px; line-height:20px; font-family: 'Lato', sans-serif; font-weight:400;"><?php echo $locations->eng_title; ?></p></td>

                            </tr>

                            <?php if($locations->address_line_1 != ""){ ?>
                            <tr>
                                <td style="vertical-align: top;  padding: 15px 0px; font-size:12px; font-family: 'Lato', sans-serif;">Address: </td>
                                <td style="font-size:12px; color:black; font-family: 'Lato', sans-serif;"><p style="display:block; margin:0; font-size:16px; line-height:20px; font-family: 'Lato', sans-serif; font-weight:400;"><?php echo $locations->address_line_1; ?></p></td>
                            </tr>
                            <?php } ?>

                            <tr>
                                <td style="vertical-align: top;  padding: 15px 0px; font-size:12px; font-family: 'Lato', sans-serif;">Opening Hours: </td>

                                <td style="font-size:12px; color:black; font-family: 'Lato', sans-serif;"><p style="display:block; margin:0; font-size:16px; line-height:20px; font-family: 'Lato', sans-serif; font-weight:400;"><?php echo $locations->opening_hours; ?></p></td>

                            </tr>

                            <?php if($locations->phone1 != ""){ ?>
                            <tr>
                                <td style="vertical-align: top;  padding: 15px 0px; font-size:12px; font-family: 'Lato', sans-serif;">Phone: </td>
                                <td style="font-size:12px; color:black; font-family: 'Lato', sans-serif;"><p style="display:block; margin:0; font-size:16px; line-height:20px; font-family: 'Lato', sans-serif; font-weight:400;"><?php echo $locations->phone1; ?></p></td>

                            </tr>
                            <?php } ?>

                            <?php if($locations->mobile != ""){ ?>
                            <tr>

                                <td style="vertical-align: top;  padding: 15px 0px; font-size:12px; font-family: 'Lato', sans-serif;">Mobile: </td>
                                <td style="font-size:12px; color:black; font-family: 'Lato', sans-serif;"><p style="display:block; margin:0; font-size:16px; line-height:20px; font-family: 'Lato', sans-serif; font-weight:400;"><?php echo $locations->mobile; ?></p></td>

                            </tr>
                            <?php } ?>

                            <?php if($locations->email != ""){ ?>
                            <tr>

                                <td style="vertical-align: top;  padding: 15px 0px; font-size:12px; font-family: 'Lato', sans-serif;">Email</td>
                                <td style="font-size:12px; color:black; font-family: 'Lato', sans-serif;"><p style="display:block; margin:0; font-size:16px; line-height:20px; font-family: 'Lato', sans-serif; font-weight:400;"><?php echo $locations->email; ?></p></td>

                            </tr>
                            <?php } ?>

                            <?php if($locations->branch_photo != ""){ ?>
                            <tr>

                                <td style="vertical-align: top;  padding: 15px 0px; font-size:12px; font-family: 'Lato', sans-serif;">Branch Photo</td>
                                <td style="font-size:12px; color:black; font-family: 'Lato', sans-serif;">
                                    <a href="<?php echo custom::baseUrl() . '/public/uploads/'.$locations->branch_photo;?>" target="_blank">
                                        <img style="width: 417px;height: 312px;" class="parking-img" src="{{custom::get_base64_path('/public/uploads/'.$locations->branch_photo)}}">
                                    </a>
                                </td>

                            </tr>

                            <?php } ?>

                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="border-top:2px solid #FE7E00; background:#2a2521; padding:14px 36px 16px 52px;" class="tfoot">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr class="tr">
                                <td style="color:#fff; font-size:9px; line-height:14px; font-weight:700; font-family: 'Lato', sans-serif;" class="col">COPYRIGHTS <?php echo date('Y'); ?>. ALL RIGHTS RESERVED.</td>
                                <td style="text-align:right;" class="col">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">

                                        <?php $social = custom::social_links(); ?>
                                        <tr>
                                            <td style="font-size:8px; line-height:14px; padding-right:10px; color:#fff;">FOLLOW  US</td>
                                            <?php if($social->facebook_link != ""){ ?>
                                            <td><a href="<?php echo $social->facebook_link; ?>" style="display:inline-block; height:auto;"><img src="{{custom::get_base64_path('/resources/views/frontend/emails/images/ico-facebook.png')}}" width="9" height="16" alt="facebook" style="display:block;"></a></td>
                                            <?php } if($social->twitter_link != ""){ ?>
                                            <td><a href="<?php echo $social->twitter_link; ?>" style="display:inline-block; height:auto;"><img src="{{custom::get_base64_path('/resources/views/frontend/emails/images/ico-twitter-new-x.png')}}" width="15" height="13" alt="twitter" style="display:block;"></a></td>
                                            <?php } if($social->linkedin_link != ""){ ?>
                                            <td><a href="<?php echo $social->linkedin_link; ?>" style="display:inline-block; height:auto;"><img src="{{custom::get_base64_path('/resources/views/frontend/emails/images/ico-linkedin.png')}}" width="14" height="14" alt="linkedin" style="display:block;"></a></td>
                                            <?php } if($social->instagram_link != ""){ ?>
                                            <td><a href="<?php echo $social->instagram_link; ?>" style="display:inline-block; height:auto;"><img src="{{custom::get_base64_path('/resources/views/frontend/emails/images/ico-instagram.png')}}" width="15" height="14" alt="instagram" style="display:block;"></a></td>
                                            <?php } if($social->youtube_link != ""){ ?>
                                            <td><a href="<?php echo $social->youtube_link; ?>" style="display:inline-block; height:auto;"><img src="{{custom::get_base64_path('/resources/views/frontend/emails/images/ico-youtube.png')}}" width="21" height="14" alt="youtube" style="display:block;"></a></td>
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