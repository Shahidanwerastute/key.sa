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
		@media only screen and (max-width: 480px) {
			/*.content table tbody tr td {
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
			}*/
		}
	</style>
</head>

<body bgcolor="#FFFFFF" style="background-color: #fff;">
<?php //echo "<pre>"; print_r($booking_content); exit; ?>
<!-- HEADER -->
<table class="head-wrap" bgcolor="#444444" style="margin: 0 auto;max-width: 680px;width: 100%;border-bottom: 2px solid #feab1d;">
	<tr>
		<td></td>
		<td class="header container" style="clear: both;display: block;margin: 0 auto;max-width: 600px">

			<div class="content" style="display: block;margin: 0 auto;max-width: 630px;padding: 15px;">
				<table bgcolor="" style=" width: 100%; ">
					<tr>
                        <?php
                        $path_no = custom::baseurl('/resources/views/frontend/emails/images/noIcon.png');
                        ?>

						<td align="left">
							<a href="javascript:void(0);" style="color: #fff; font-size: 12px; text-decoration: none;">
								<img src="<?php if(isset($message)){ echo $message->embed($path_no); } else{ echo $path_no;} ?>" style="display: inline-block;margin-bottom: -3px;margin-right: 5px;" alt="No." width="10" height="12"> <?php echo custom::site_phone(); ?>
							</a>
						</td>
						<td align="right">
							<a href="<?php echo $data['lang_base_url']; ?>" style="color: #F8AC1D; font-size: 12px; text-decoration: none">www.key.sa</a>
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
						<td align="left">
							<a href="javascript:void(0);" style="">

                                <?php
                                $path_logo = custom::baseurl('/resources/views/frontend/emails/images/logo.png?v=' . rand());
                                ?>

								<img src="<?php if(isset($message)){ echo $message->embed($path_logo); } else {echo $path_logo; } ?>" alt="Logo" height="43" width="171" />
							</a>
						</td>
						<td align="right" style="color: #F8AC1D; font-size: 12px;">
							<h2 style="
							    color: #f8ac1d;
								font-size: 22px;
								font-weight: normal;
								line-height: 1;
								margin: 0;">BOOKING
							</h2>
							<h3 style="color: #666666;
								font-size: 18px;
								font-weight: lighter;
								line-height: 1;
								margin: 0;
								">CONFIRMATION
							</h3>
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
						<td align="left" valign="top" style="padding-right: 10px; width: 50%;">

                            <?php

                            //icg_first_name
                            ?>

							<p style="color:#AB1D46; font-size: 13px; margin-bottom: 3px;line-height: 1.2;">Dear <?php echo $booking_content['first_name']." ".$booking_content['last_name']; ?>,</p>
							<p style="color: #858585;font-size: 13px;line-height: 1.3;margin: 0;">Thank you for booking with Key Car Rental, Please find your reservation details below. 	</p>
						</td>
						<td align="right" valign="top" style="padding-left: 10px; width: 50%;">
							<p style="color:#AB1D46; font-size: 13px; margin-bottom: 3px;line-height: 1.2;">PICKUP BRANCH CONTACT DETAIL</p>
							<p style="color: #444444;font-size: 17px;line-height: 1.3;margin: 0;"><?php echo $booking_content['branch_mobile']; ?></p>
							<p style="color: #858585;font-size: 13px;line-height: 1.3;margin: 0;"><strong style="color: #444444;"><?php echo $booking_content['branch_from_eng_title']; ?> </strong></p>
						</td>
					</tr>
				</table>
			</div>
		</td>
		<td></td>
	</tr>
</table><!-- /User Info -->
<table class="head-wrap" bgcolor="#eff0e0" style="margin: 0 auto;max-width: 680px;width: 100%;" >
	<tr>
		<td></td>
		<td class="header container" style="clear: both;display: block;margin: 0 auto;max-width: 600px">
			<div class="content" style="display: block;margin: 0 auto;max-width: 630px;padding: 15px;">
				<table class="userInfo" style=" width: 100%; ">
					<tr>
						<td align="left" style="padding-right: 10px; width: 50%;">
							<p style="color: #858585;font-size: 12px;line-height: 1; margin: 0">RESERVATION NUMBER</p>
							<p style="color:#AB1D46; font-size: 16px;line-height: 1;"><?php echo $booking_content['reservation_code']; ?></p>
						</td>
						<td align="right" style="padding-left: 10px; width: 50%;">

                            <?php
                            $path_barCode = custom::baseurl('/resources/views/frontend/emails/images/barCode.png');

                            ?>

							<img src="<?php if(isset($message)){ echo $message->embed($path_barCode); } else { echo $path_barCode;} ?>" alt="Bar Code" height="61" width="227" />
						</td>
					</tr>
				</table>
			</div>
		</td>
		<td></td>
	</tr>
</table><!-- /RESERVATION NUMBER -->
<table class="head-wrap" bgcolor="#fff" style="margin: 0 auto;max-width: 680px;width: 100%;" >
	<tr>
		<td></td>
		<td class="header container" style="clear: both;display: block;margin: 0 auto;max-width: 600px">
			<div class="content" style="display: block;margin: 0 auto;max-width: 630px;padding: 15px;">
				<table class="carInfo" style=" width: 100%; ">
					<tr>
						<td align="left" style="padding-right: 10px; width: 50%;">
							<h2 style="color: #ab1d46;font-size: 24px;text-transform: uppercase;"><?php echo $booking_content['car_model_eng_title']." ".$booking_content['year']; ?>
								<span style="color: #858585;font-size: 60%;">OR SIMILAR</span>
							</h2>
							<h3 style="color: #666666;font-size: 11px;font-weight: bold;margin: 0 0 15px;text-transform: uppercase;"><?php echo $booking_content['car_category_eng_title']; ?></h3>
							<p style="color: #666666;font-size: 13px;line-height: 1;margin: 0 0 2px;">
								<span style="color: #a3003f;"><?php echo $booking_content['first_name']." ".$booking_content['last_name']; ?></span>
							</p>
                            <?php if($booking_content['id_no'] !=""){ ?>
							<p style="color: #666666;font-size: 13px;line-height: 1;margin: 0 0 2px;">
								<span style="color: #a3003f;">ID:</span> <?php echo custom::maskText($booking_content['id_no'], 4); ?>
							</p>
                            <?php } ?>
                            <?php if($booking_content['mobile_no'] !=""){ ?>
							<p style="color: #666666;font-size: 13px;line-height: 1;margin: 0 0 2px;">
								<span style="color: #a3003f;">M:</span> <?php echo custom::maskText($booking_content['mobile_no'], 6); ?>
							</p>
                            <?php } ?>
                            <?php if($booking_content['email'] !=""){ ?>
							<p style="color: #666666;font-size: 13px;line-height: 1;margin: 0 0 2px;">
								<span style="color: #a3003f;">E:</span> <?php echo custom::maskText($booking_content['email'], 3); ?>
							</p>
                            <?php } ?>
						</td>
						<td align="right" style="padding-left: 10px; width: 50%;">
                            <?php
                            $path_car = custom::baseurl('/public/uploads/'.$booking_content["car_image"]);
                            ?>
							<img src="<?php if(isset($message)){ echo $message->embed($path_car); } else { echo $path_car;} ?>" alt="Car" height="132" width="274" />
						</td>
					</tr>
				</table>
			</div>
		</td>
		<td></td>
	</tr>
</table><!-- /Car Information-->
<table class="head-wrap" bgcolor="#eff0e0" style="border-bottom: 2px solid #fff;margin: 0 auto;max-width: 680px;width: 100%;" cellspacing="0" cellpadding="0" >
	<tr>
		<td></td>
		<td class="header container" style="clear: both;display: block;margin: 0 auto;max-width: 600px">
			<div class="content"  style="display: block;margin: 0 auto;max-width: 630px;padding: 0px;">
				<table class="genLbl locPDoFF" cellspacing="0" cellpadding="0" style=" width: 100%; ">
					<tr>
						<td align="left"  style="padding-left: 10px; width: 50%; border-right: 2px solid #fff;">
							<div class="bCenter" style="
								display: inline-block;
								max-width: 240px;
								min-height: 115px;
								text-align: left;
								width: 100%;
							">
								<label style="
								    color: #9f063f;
									display: block;
									font-size: 12px;
									font-weight: bold;
									line-height: 1.3;
									margin: 0 0 15px;
									text-transform: uppercase;
									width: 100%;
								">Pick Up</label>
								<ul>
									<li title="<?php echo $booking_content['branch_from_eng_title']; ?>" style="
										color: #444444;
										display: block;
										font-size: 11px;
										font-weight: bold;
										list-style-type: n;
										margin: 0 0 6px;
										padding: 0;
										width: 100%;
									">
                                        <?php
                                         $path_loc = custom::baseurl('/resources/views/frontend/emails/images/location.png');
                                        ?>
										<img class="abImg" src="<?php if(isset($message)) { echo $message->embed($path_loc); } else { echo $path_loc; } ?>" style="height: auto;margin: 0 6px -5px 0px;width: 13px;" alt="" width="13" height="18">
										<?php echo $booking_content['branch_from_eng_title']; ?>
									</li>
                                    <?php $arrDate = explode(" ",$booking_content['from_date']);
                                    $fromDate = date('d / m / Y', strtotime($arrDate[0]));
                                    $fromTime = date( 'H:i A', strtotime( $arrDate[1] ) );
                                    ?>
                                    <?php

                                  $path = custom::baseurl('/resources/views/frontend/emails/images/calendar.png');
                                  $path_clock = custom::baseurl('/resources/views/frontend/emails/images/clock.png');
                                    ?>
									<li style="
										color: #9f063f;
										display: block;
										font-size: 11px;
										font-weight: bold;
										list-style-type: n;
										margin: 0 0 6px;
										padding: 0;
										width: 100%;
									"><img class="abImg" src="<?php if(isset($message)) { echo $message->embed($path); } else { echo $path; } ?>" style="height: auto;margin:0 6px -5px 0px;width: 13px;"  alt="" width="16" height="18"> <?php echo $fromDate; ?></li>
									<li style="
										color: #9f063f;
										display: block;
										font-size: 11px;
										font-weight: bold;
										list-style-type: n;
										margin: 0 0 6px;
										padding: 0;
										width: 100%;
									"><img class="abImg" src="<?php if(isset($message)) { echo $message->embed($path_clock); } else { echo $path_clock; } ?>" style="height: auto;margin:0 6px -5px 0px;width: 13px;"  alt="" width="18" height="18"> <?php echo $fromTime; ?></li>
								</ul>
							</div>
						</td>
						<td style="padding-left: 10px; width: 50%;">
							<div class="bCenter" style="
								display: inline-block;
								max-width: 240px;
								min-height: 115px;
								margin: 15px 20px;
								text-align: left;
								width: 100%;
							">
								<label style="
								    color: #9f063f;
									display: block;
									font-size: 12px;
									font-weight: bold;
									line-height: 1.3;
									margin: 0 0 15px;
									text-transform: uppercase;
									width: 100%;
								">Drop Off</label>
								<ul>
									<li title="<?php echo $booking_content['branch_from_eng_title']; ?>" style="
										color: #444444;
										display: block;
										font-size: 11px;
										font-weight: bold;
										list-style-type: n;
										margin: 0 0 6px;
										padding: 0;
										width: 100%;
									">
                                        <?php
                                        $path = custom::baseurl('/resources/views/frontend/emails/images/location.png');
                                        ?>

										<img class="abImg" src="<?php if(isset($message)) { echo $message->embed($path); } else { echo $path; } ?>" style="height: auto;margin:0 6px -5px 0px;width: 13px;"  alt="" width="13" height="18">
                                        <?php echo $booking_content['branch_to_eng_title']; ?>
									</li>

                                    <?php $arrDate = explode(" ",$booking_content['to_date']);
                                    $toDate = date('d / m / Y', strtotime($arrDate[0]));
                                    $toTime = date( 'H:i A', strtotime( $arrDate[1] ) );
                                    ?>
                                    <?php
                                    $path_calender = custom::baseurl('/resources/views/frontend/emails/images/calendar.png');
                                    $path_clock = custom::baseurl('/resources/views/frontend/emails/images/clock.png');

                                    ?>
									<li style="
										color: #9f063f;
										display: block;
										font-size: 11px;
										font-weight: bold;
										list-style-type: n;
										margin: 0 0 6px;
										padding: 0;
										width: 100%;
									"><img class="abImg" src="<?php if(isset($message)) { echo $message->embed($path_calender); } else { echo $path_calender; } ?>" style="height: auto;margin:0 6px -5px 0px;width: 13px;"  alt="" width="16" height="18"> <?php echo $toDate; ?></li>
									<li style="
										color: #9f063f;
										display: block;
										font-size: 11px;
										font-weight: bold;
										list-style-type: n;
										margin: 0 0 6px;
										padding: 0;
										width: 100%;
									"><img class="abImg" src="<?php if(isset($message)) { echo $message->embed($path_clock); } else { echo $path_clock; } ?>" style="height: auto;margin:0 6px -5px 0px;width: 13px;"  alt="" width="18" height="18"> <?php echo $toTime; ?></li>
								</ul>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</td>
		<td></td>
	</tr>
</table><!-- /Pick n Drop -->
<table class="head-wrap" bgcolor="#eff0e0" style="border-bottom: 2px solid #fff;margin: 0 auto;max-width: 680px;width: 100%;"  cellspacing="0" cellpadding="0" >
	<tr>
		<td></td>
		<td class="header container" style="clear: both;display: block;margin: 0 auto;max-width: 600px">
			<div class="content"  style="display: block;margin: 0 auto;max-width: 630px;padding: 0px;">
				<table class="genLbl" cellspacing="0" cellpadding="0" style=" width: 100%; ">
					<tr>
						<td align="left" style="padding-left: 10px; width: 50%; border-right: 2px solid #fff;" class="extras">
							<div class="bCenter" style="
								display: inline-block;
								max-width: 240px;
								min-height: 115px;
								text-align: left;
								width: 100%;
							">
								<label style="
								    color: #9f063f;
									display: block;
									font-size: 12px;
									font-weight: bold;
									line-height: 1.3;
									margin: 0 0 15px;
									text-transform: uppercase;
									width: 100%;
								">EXTRA</label>
								<ul>
                                    <?php if(round($booking_content['ldw_price']) != 0){ ?>
									<li style="color: #868686;display: block;font-size: 13px;list-style-type: none;margin: 0;
										padding: 0;
										width: 100%;
									">
										Loss Damage Waiver
										<p style="color: #333326;display: inline;font-size: 13px;line-height: 1;margin: 0;"><?php echo $booking_content['ldw_price']; ?> SR x <?php echo $booking_content['no_of_days']?> DAYS</p>
									</li>
                                    <?php } ?>
                                    <?php if(round($booking_content['gps_price']) != 0){ ?>
									<li style="
									    color: #868686;
										display: block;
										font-size: 13px;
										list-style-type: none;
										margin: 0;
										padding: 0;
										width: 100%;
									">
										GPS
										<p style="color: #333326;display: inline;font-size: 13px;line-height: 1;margin: 0;"><?php echo $booking_content['gps_price']; ?> SR x <?php echo $booking_content['no_of_days']?> DAYS</p>
									</li>
                                    <?php } ?>
                                    <?php if(round($booking_content['extra_driver_price']) != 0){ ?>
									<li style="
									    color: #868686;
										display: block;
										font-size: 13px;
										list-style-type: none;
										margin: 0;
										padding: 0;
										width: 100%;
									">
										Extra driver
										<p style="color: #333326;display: inline;font-size: 13px;line-height: 1;margin: 0;"><?php echo $booking_content['extra_driver_price']; ?> SR x <?php echo $booking_content['no_of_days']?> DAYS</p>
									</li>
                                    <?php } ?>
                                    <?php if(round($booking_content['baby_seat_price']) != 0){ ?>
									<li style="
									    color: #868686;
										display: block;
										font-size: 13px;
										list-style-type: none;
										margin: 0;
										padding: 0;
										width: 100%;
									">
										Baby Car Protection Seat
										<p style="color: #333326;display: inline;font-size: 13px;line-height: 1;margin: 0;"><?php echo $booking_content['baby_seat_price']; ?> SR x <?php echo $booking_content['no_of_days']?> DAYS</p>
									</li>
                                    <?php } ?>
                                    <?php if(round($booking_content['dropoff_charges']) != 0){ ?>
                                        <li style="
									    color: #868686;
										display: block;
										font-size: 13px;
										list-style-type: none;
										margin: 0;
										padding: 0;
										width: 100%;
									">
                                            Dropoff charges
                                            <p style="color: #333326;display: inline;font-size: 13px;line-height: 1;margin: 0;"><?php echo $booking_content['dropoff_charges']; ?> SR x <?php echo $booking_content['no_of_days']?> DAYS</p>
                                        </li>
                                    <?php } ?>
								</ul>
							</div>
						</td>
						<td  style="padding-left: 10px; width: 50%;" class="bookFeature">
							<div class="bCenter" style="
								display: inline-block;
								margin: 15px 20px;
								max-width: 240px;
								min-height: 115px;
								text-align: left;
								width: 100%;
							">
								<label style="
								    color: #9f063f;
									display: block;
									font-size: 12px;
									font-weight: bold;
									line-height: 1.3;
									margin: 0 0 15px;
									text-transform: uppercase;
									width: 100%;
								">FEATURES</label>
								<ul>
                                    <?php
                                    $path_user = custom::baseurl('/resources/views/frontend/emails/images/users.png');
                                    $path_trans = custom::baseurl('/resources/views/frontend/emails/images/trans.png');
                                    $path_door = custom::baseurl('/resources/views/frontend/emails/images/door.png');
                                    $path_bags = custom::baseurl('/resources/views/frontend/emails/images/bags.png');

                                    ?>
									<li style="display: block;float: left;list-style-type: none; margin: 0;padding: 0;text-align: left;
    width: 25%;">
										<div class="spIconF"><img src="<?php if(isset($message)) { echo $message->embed($path_user); } else { echo $path_user; } ?>" alt="Pass" height="30" width="30" /> </div>
										<p style="color: #777777;font-size: 10px;text-align: left; text-indent: 10px;"><?php echo $booking_content['no_of_passengers']; ?></p>
									</li>
									<li style="display: block;float: left;list-style-type: none; margin: 0;padding: 0;text-align: center;
    width: 25%;">
										<div class="spIconF"><img src="<?php if(isset($message)) { echo $message->embed($path_trans); } else { echo $path_trans; } ?>" alt="trans" height="30" width="30" /></div>
										<p style="color: #777777;font-size: 10px;text-align: center;"><?php echo $booking_content['transmission']; ?></p>
									</li>
									<li style="display: block;float: left;list-style-type: none; margin: 0;padding: 0;text-align: center;
    width: 25%;">
										<div class="spIconF"><img src="<?php if(isset($message)) { echo $message->embed($path_door); } else { echo $path_door; } ?>" alt="Door" height="30" width="30" /></div>
										<p style="color: #777777;font-size: 10px;text-align: center;"><?php echo $booking_content['no_of_doors']; ?></p>
									</li>
									<li style="display: block;float: left;list-style-type: none; margin: 0;padding: 0;text-align: center;
    width: 25%;">
										<div class="spIconF"><img src="<?php if(isset($message)) { echo $message->embed($path_bags); } else { echo $path_bags; } ?>" alt="bags" height="30" width="30" /></div>
										<p style="color: #777777;font-size: 10px;text-align: center;"><?php echo $booking_content['no_of_bags']; ?></p>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</td>
		<td></td>
	</tr>
</table><!-- /Extra and Features -->
<table class="head-wrap" bgcolor="#fff" style="margin: 0 auto;max-width: 680px;width: 100%;" cellspacing="0" cellpadding="0" >
	<tr>
		<td class="header container"  style="clear: both;display: block;margin: 0 auto;max-width: 100%">
			<div class="content"  style="display: block;margin: 0 auto;max-width: 100%;padding: 0px;">
				<table class="priceSec" cellspacing="0" cellpadding="0" style=" width: 100%;color: #fff;
		text-align: center;
		text-transform: uppercase; ">
					<tr>
						<td align="left" style="border-right: 2px solid #fff;padding: 0 10px 0 50px;width: 50%;background-color: #676767;" class="grayBox">
							<div class="bCenter" style="display: inline-block;margin: 20px auto;max-width: 240px;text-align: left;width: 100%;">
								<h2 style="font-size: 12px;font-weight: bold;line-height: 1;margin: 0;">RENT PER DAY <?php echo $booking_content['rent_price']; ?></h2>
								<h2 style="font-size: 12px;font-weight: bold;line-height: 1;margin: 0;">TOTAL RENT FOR <?php echo $booking_content['no_of_days']?> DAYS</h2>
								<h3 style="font-size: 25px;line-height: 1;"><?php echo intval($booking_content['rent_price']) * intval($booking_content['no_of_days']); ?> SR</h3>
								<h2 style="font-size: 12px;font-weight: bold;line-height: 1;margin: 0;">&nbsp;</h2>
                                <?php if(round($booking_content['discount_price']) != 0){ ?>
                                    <h2 style="font-size: 12px;font-weight: bold;line-height: 1;margin: 0;">DISCOUNT APPLIED</h2>
                                    <h3 style="font-size: 25px;line-height: 1;"><?php echo $booking_content['discount_price']; ?> SR</h3>
                                <?php } ?>
							</div>
						</td>
						<td align="left" style=" padding: 0 10px 0 30px; width: 50%;background-color: #f8ab1d;" class="yellowBox" >
							<div class="bCenter" style="display: inline-block;margin: 20px auto;max-width: 240px;text-align: left;width: 100%;">
								<h2 style="font-size: 12px;font-weight: bold;line-height: 1;margin: 0;">TOTAL PAID</h2>
								<h3 style="font-size: 25px;line-height: 1;color: #9f063f;"><?php echo $booking_content['total_sum']; ?> SAR</h3>
								<h2 style="font-size: 12px;font-weight: bold;line-height: 1;margin: 0;">

                                    <?php
                                    $path_sadad = custom::baseurl('/resources/views/frontend/emails/images/sadatFFF.png');
                                    ?>

                                    By
                                    <?php if($booking_content['payment_method'] == "Sadad"){ ?>
									 <img src="<?php if(isset($message)) { echo $message->embed($path_sadad); } else { echo $path_sadad; } ?>" height="13" width="54" alt="Sadat"/>
                                    <?php }
                                        if($booking_content['payment_method'] == "Cash"){
                                            echo "Cash";
                                        }
                                        if($booking_content['payment_method'] == "cc") {
                                            echo "Points";
                                        }
                                        ?>
                                </h2>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table><!-- /Extra and Features -->


<table class="head-wrap"  bgcolor="#2a2521" style="border-top: 2px solid #feab1d;margin: 0 auto;max-width: 680px;width: 100%;" >
	<tr>
		<td>&nbsp;</td>
		<td class="header container" style="clear: both;display: block;margin: 0 auto;max-width: 600px">
			<div class="content" style="display: block;margin: 0 auto;max-width: 630px;padding: 15px;" >
				<table class="" style=" width: 100%; " >
					<tr>
						<td align="left" >
							<p style="color: #fff: 9px; margin: 0;">COPYRIGHTS 2016. ALL RIGHTS RESERVED.</p>
						</td>
						<td align="right" >
							<div class="footFollowUs">
								<span style="
									color: #fff;
									display: inline-block;
									font-size: 7px;
									margin: 6px 10px 0 0;
									vertical-align: top;
								">FOLLOW   US</span>

                                <?php
                                $path_fb = custom::baseurl('/resources/views/frontend/emails/images/facebook.png');
                                $path_tw = custom::baseurl('/resources/views/frontend/emails/images/twitter.png');
                                $path_ln = custom::baseurl('/resources/views/frontend/emails/images/linkdin.png');
                                $path_ins = custom::baseurl('/resources/views/frontend/emails/images/insta.png');
                                $path_yt = custom::baseurl('/resources/views/frontend/emails/images/youtube.png');
                                ?>

								<ul style="display: inline-block;margin: 0;padding: 0 0 0 5px;vertical-align: top;">
									<?php $social = custom::social_links(); ?>
									<li style="display: inline-block;margin: 0;padding: 0 3px;">
										<a href="<?php echo $social->facebook_link; ?>" target="_blank" class="facebook" >
											<img src="<?php if(isset($message)) { echo $message->embed($path_fb); } else { echo $path_fb; } ?>" alt="Facebook" height="16" width="16" />
										</a>
									</li>
									<li style="display: inline-block;margin: 0;padding: 0 3px;">
										<a href="<?php echo $social->twitter_link; ?>" target="_blank" class="twitter" >
											<img src="<?php if(isset($message)) { echo $message->embed($path_tw); } else { echo $path_tw; } ?>" alt="Twitter" height="16" width="16" />
										</a>
									</li>
									<li style="display: inline-block;margin: 0;padding: 0 3px;">
										<a href="<?php echo $social->linkedin_link; ?>" target="_blank" class="linkedin">
											<img src="<?php if(isset($message)) { echo $message->embed($path_ln); } else { echo $path_ln; } ?>" alt="Linkdin" height="16" width="16" />
										</a>
									</li>
									<li style="display: inline-block;margin: 0;padding: 0 3px;">
										<a href="<?php echo $social->instagram_link; ?>" class="instagram">
											<img src="<?php if(isset($message)) { echo $message->embed($path_ins); } else { echo $path_ins; } ?>" alt="insta" height="16" width="16" />
										</a>
									</li>
									<li style="display: inline-block;margin: 0;padding: 0 3px;">
										<a href="<?php echo $social->youtube_link; ?>" class="youtube">
											<img src="<?php if(isset($message)) { echo $message->embed($path_yt); }  else { echo $path_yt; } ?>" alt="youTube" height="16" width="24" />
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