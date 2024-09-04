<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!-- If you delete this tag, the sky will fall on your head -->
	<meta name="viewport" content="width=device-width" />

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Key Rental</title>

	<link href="http://fonts.googleapis.com/css?family=Lato:400,700,900" rel="stylesheet">
	<style type="text/css">
		* {
			margin:0;
			padding:0;
		}
		* { font-family:'Lato', sans-serif;}

		img {
			max-width: 100%;
		}
		body {
			-webkit-font-smoothing:antialiased;
			-webkit-text-size-adjust:none;
			width: 100%!important;
			height: 100%;
		}


		/* -------------------------------------------
                PHONE
                For clients that support media queries.
                Nothing fancy.
        -------------------------------------------- */
		@media only screen and (max-width: 600px) {

			a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}

			div[class="column"] { width: auto!important; float:none!important;}

			table.social div[class="column"] {
				width:auto!important;
			}

		}
		/*@media only screen and (max-width: 480px) {
			.content table tbody tr td {
				display: block !important;
				padding: 0 0 15px !important;
				text-align: left !important;
				width: 100% !important;
			}
			.content table.genLbl tbody tr td {
				border-color: #fff;
				border-style: solid !important;
				border-width: 0 0 2px !important;
				padding: 15px 0 !important;
			}
			.content table.priceSec tbody tr td {
				border-color: #fff !important;
				border-style: solid !important;
				border-width: 0 0 2px !important;
				padding: 15px 0 !important;
			}
			.content table.genLbl tbody tr td .bCenter ,
			.content table.priceSec tbody tr td .bCenter {
				margin: 0 !important;
				max-width: inherit !important;
				padding: 0 15px !important;
				width: auto !important;
			}
		}*/
	</style>
</head>

<body bgcolor="#FFFFFF" style="background-color: #fff;" dir="rtl">

<!-- HEADER -->
<table class="head-wrap" bgcolor="#444444" style="margin: 0 auto;max-width: 680px;width: 100%;border-bottom: 2px solid #feab1d;">
	<tr>
		<td></td>
		<td class="header container" style="clear: both;display: block;margin: 0 auto;max-width: 600px">

			<div class="content" style="display: block;margin: 0 auto;max-width: 630px;padding: 15px;">
				<table bgcolor="" style=" width: 100%; ">
					<tr>
                        <?php
                        $path_noIcon = realpath('resources/views/frontend/emails/images/noIcon.png');
                        ?>

						<td align="right">
							<a href="javascript:void(0);" style="color: #fff; font-size: 12px; text-decoration: none;">
								<img src="<?php echo $message->embed($path_noIcon); ?>" style="display: inline-block;margin-bottom: -3px;margin-right: 5px;" alt="No." width="10" height="12">
                                <?php echo $contact_no; ?>
							</a>
						</td>
						<td align="left">
							<a href="<?php echo $lang_base_url; ?>" style="color: #F8AC1D; font-size: 12px; text-decoration: none">www.key.sa</a>
						</td>
					</tr>
				</table>
			</div>

		</td>
		<td></td>
	</tr>
</table><!-- /HEADER -->
<table class="head-wrap" bgcolor="#fff" style="box-shadow: 0 3px 6px -2px rgba(0, 0, 0, 0.4);margin: 0 auto;max-width: 680px;width: 100%;">
	<tr>
		<td></td>
		<td class="header container" style="clear: both;display: block;margin: 0 auto;max-width: 600px">
			<div class="content" style="display: block;margin: 0 auto;max-width: 630px;padding: 15px;">
				<table bgcolor="" style=" width: 100%; ">
					<tr>

                        <?php
                        $path_logo = realpath('resources/views/frontend/emails/images/logo.png');
                        ?>

						<td align="right">
							<a href="javascript:void(0);" style="">
								<img src="<?php echo $message->embed($path_logo);?>" alt="Logo" height="43" width="171" />
							</a>
						</td>

					</tr>
				</table>
			</div>
		</td>
		<td></td>
	</tr>
</table><!-- /HEADER Logo -->
<table class="head-wrap" style="margin: 0 auto;max-width: 680px;width: 100%;">
	<tr>
		<td></td>
		<td class="header container" style="clear: both;display: block;margin: 0 auto;max-width: 600px">
			<div class="content" style="display: block;margin: 0 auto;max-width: 630px;padding: 15px;">
				<table class="userInfo" style=" width: 100%; ">
					<tr>
						<td colspan="2" align="right" valign="top" style="padding: 25px 0px;">
							<p style="color:#AB1D46; font-size: 13px;line-height: 1.3;margin: 0;"> <?php echo (isset($gender) && $gender == 'female' ? 'عزيزتي' : 'عزيزي'); ?> <?php echo $name; ?>,</p>
							<p style="color: #858585;font-size: 13px;line-height: 1.3;margin: 0;"><?php echo $msg; ?></p><br />
							</td>
					</tr>
				</table>
			</div>
		</td>
		<td></td>
	</tr>
</table><!-- /User Info -->


<table class="head-wrap"  bgcolor="#2a2521" style="border-top: 2px solid #feab1d;margin: 0 auto;max-width: 680px;width: 100%;" >
	<tr>
		<td>&nbsp;</td>
		<td class="header container" style="clear: both;display: block;margin: 0 auto;max-width: 600px">
			<div class="content" style="display: block;margin: 0 auto;max-width: 630px;padding: 15px;" >
				<table class="" style=" width: 100%; " >
					<tr>
						<td align="right" >
							<p style="color: #fff; font-size: 9px; margin: 0;">COPYRIGHTS <?php echo date('Y'); ?>. ALL RIGHTS RESERVED.</p>
						</td>
						<td align="left" >
							<div class="footFollowUs">
								<span style="
									color: #fff;
									display: inline-block;
									font-size: 7px;
									margin: 6px 10px 0 0;
									vertical-align: top;
								">تابعونا</span>
								<ul style="display: inline-block;margin: 0;padding: 0 0 0 5px;vertical-align: top;">
									<?php $social = custom::social_links();

                                    $path_fb = realpath('resources/views/frontend/emails/images/facebook.png');
                                    $path_tw = realpath('resources/views/frontend/emails/images/twitter.png');
                                    $path_ln = realpath('resources/views/frontend/emails/images/linkdin.png');
                                    $path_ins = realpath('resources/views/frontend/emails/images/insta.png');
                                    $path_yt = realpath('resources/views/frontend/emails/images/youtube.png');
									?>
									<li style="display: inline-block;margin: 0;padding: 0 3px;">
										<a href="<?php echo $social->facebook_link; ?>" target="_blank" class="facebook" >
											<img src="<?php echo $message->embed($path_fb); ?>" alt="Facebook" height="16" width="16" />
										</a>
									</li>
									<li style="display: inline-block;margin: 0;padding: 0 3px;">
										<a href="<?php echo $social->twitter_link; ?>" target="_blank" class="twitter" >
											<img src="<?php echo $message->embed($path_tw); ?>" alt="Twitter" height="16" width="16" />
										</a>
									</li>
									<li style="display: inline-block;margin: 0;padding: 0 3px;">
										<a href="<?php echo $social->linkedin_link; ?>" target="_blank" class="linkedin">
											<img src="<?php echo $message->embed($path_ln); ?>" alt="Linkdin" height="16" width="16" />
										</a>
									</li>
									<li style="display: inline-block;margin: 0;padding: 0 3px;">
										<a href="<?php echo $social->instagram_link; ?>" class="instagram">
											<img src="<?php echo $message->embed($path_ins); ?>" alt="insta" height="16" width="16" />
										</a>
									</li>
									<li style="display: inline-block;margin: 0;padding: 0 3px;">
										<a href="<?php echo $social->youtube_link; ?>" class="youtube">
											<img src="<?php echo $message->embed($path_yt); ?>" alt="youTube" height="16" width="24" />
										</a>
									</li>
								</ul>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>

</body>
</html>