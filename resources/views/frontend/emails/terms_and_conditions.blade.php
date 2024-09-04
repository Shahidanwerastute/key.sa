<?php if ($show_terms) // if being used for showing terms and conditions in mobile app
{ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Terms and Conditions | Key Car Rental</title>
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
                    	<td style="padding:14px 33px 14px 50px; background:#444; border-bottom:2px solid #f1af1d;" class="topbar">
                        	<table border="0" cellpadding="0" cellspacing="0" width="100%">
                            	<tr>
									<?php $path_icon = custom::baseurl('/resources/views/frontend/emails/images/ico-tell.png'); ?>
                                	<td><img src="<?php if(isset($message)){ echo $message->embed($path_icon); } else{ echo $path_icon;} ?>" alt="" width="10" height="12" style="display:inline-block; margin:0; vertical-align:middle;">&nbsp;&nbsp;<a href="tel:<?php echo custom::site_phone(); ?>" style="color:#fff; font-family: 'Lato', sans-serif; font-size:12px; font-weight:400; cursor:pointer; text-decoration:none;"><?php echo custom::site_phone(); ?></a></td>
                                    <td style="text-align:right;"><a href="<?php echo custom::baseurl('/'); ?>" style="font-size:12px; color:#f8ac1d; font-family: 'Lato', sans-serif; text-decoration:none;">www.key.sa</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td style=" line-height: 1.3; padding: 20px;">
                    		<?php echo $terms; ?>
                    	</td>
                    </tr>
                    <tr>

						<?php
						$path_fb = custom::baseurl('/resources/views/frontend/emails/images/ico-facebook.png');
						$path_tw = custom::baseurl('/resources/views/frontend/emails/images/ico-twitter.png');
						$path_li = custom::baseurl('/resources/views/frontend/emails/images/ico-linkedin.png');
						$path_inst = custom::baseurl('/resources/views/frontend/emails/images/ico-instagram.png');
						$path_yt = custom::baseurl('/resources/views/frontend/emails/images/ico-youtube.png');

						?>
                    	<td style="border-top:2px solid #f8ab1d; background:#2a2521; padding:14px 36px 16px 52px;" class="tfoot">
                        	<table border="0" cellpadding="0" cellspacing="0" width="100%">
                            	<tr class="tr">
                                	<td style="color:#fff; font-size:9px; line-height:14px; font-weight:700; font-family: 'Lato', sans-serif;" class="col"><?php echo ($lang == 'en' ? 'COPYRIGHTS' : 'حقوق الملكية'); ?> <?php echo date('Y'); ?>.<?php echo ($lang == 'en' ? ' ALL RIGHTS RESERVED.' : 'جميع الحقوق محفوظة.'); ?>.</td>
                                    <td style="text-align:right;" class="col">
                                    	<table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        	<tr>
                                            	<td style="font-size:8px; line-height:14px; padding-right:10px; color:#fff;"><?php echo ($lang == 'en' ? 'FOLLOW  US' : 'تابعونا'); ?></td>
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
<?php } elseif (!$show_terms) { ?> <!-- if being used for mobile app sadad return URL -->
<ul>
	<li>
		Success: <?php echo $content['success']; ?>
	</li>
	<li>
		Message: <?php echo $content['message']; ?>
	</li>
</ul>
<?php } ?>