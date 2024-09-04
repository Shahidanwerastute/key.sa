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
		body.is_mobile .myAccountSec .myProfDetail {
			margin-bottom: 100px;
		}

	</style>
    <section class="myAccountSec">
		<div class="myAccountWrapper">
			@include('frontend.mobile.layouts.profile_inner_section')
			<div class="myProfDetail">
				<?php
                $data = custom::loggedInUserProfileInnerInfo();
                $user_data = $data['user_data'];
                if ($user_data->loyalty_card_type == 'Bronze' || $user_data->loyalty_card_type == '') // For Silver Type
                {
                    $label = ($lang == 'eng' ? 'Bronze Points' : 'نقاط برونزية');
                    $image_path = $base_url.'/public/frontend/images/bronze_card_img.png?v=0.1';
                }elseif ($user_data->loyalty_card_type == 'Silver') // For Silver Type
                {
                    $label = ($lang == 'eng' ? 'Silver Points' : 'مجموع النقاط');
                    $image_path = $base_url.'/public/frontend/images/silver_card_img.png?v=0.1';
                }elseif ($user_data->loyalty_card_type == 'Golden') // For Golden Type
                {
                    $label = ($lang == 'eng' ? 'Golden Points' : 'غولدنبوانتس');
                    $image_path = $base_url.'/public/frontend/images/golden_card_img.png?v=0.1';
                }elseif ($user_data->loyalty_card_type == 'Platinum') // For Platinum Type
                {
                    $label = ($lang == 'eng' ? 'Platinum Points' : 'النقاط البلاتينية');
                    $image_path = $base_url.'/public/frontend/images/platinum_card_img.png?v=0.1';
                }
				?>
				<ul class="col-loyalty">
					<li class="cardType">
<!--						<img src="<?php echo $image_path; ?>" alt="Card" height="170" width="280"/>-->
							<div class="loyalty_card_img" style="background-image: url('<?php echo $image_path; ?>');min-height: 160px;min-width: 240px;background-size: 100%;background-repeat: no-repeat;">
								<ul class="Name_Id_Number">
									<li><?php echo $user_data->first_name; ?> <?php echo $user_data->last_name; ?></li>
									<li><?php echo $user_data->id_no; ?></li>
								</ul>
							</div>
					</li>
					<div class="col-points">
						<h3><?php echo $label; ?></h3>
						<p><?php echo $user_data->loyalty_points; ?></p>
					</div>
				</ul>
				<div class="row noFloatingRow">
					<div class="col-lg-6 col-md-12 isNoFloat">
						<div class="row noFloatingRow">
							<div class="col-sm-6 isNoFloat marginBtm20">
								<label>@lang('labels.name')</label>
								<p><?php echo $user_data->first_name; ?> <?php echo $user_data->last_name; ?></p>
							</div>
							<div class="col-sm-6 isNoFloat marginBtm20">
								<label>@lang('labels.mobile_no')</label>
								<?php
								if ($lang == 'arb') {
									$mobileFieldStyle = 'style="direction: ltr; text-align: right;"';
								} else {
									$mobileFieldStyle = '';
								}
								?>
								<p <?php echo $mobileFieldStyle; ?>><?php echo $user_data->mobile_no; ?></p>
							</div>
							<div class="col-sm-12 isNoFloat marginBtm20">
								<label>@lang('labels.email_address')</label>
								<p><?php echo $user_data->email; ?></p>
							</div>
							<?php if($user_data->dob != '' && $user_data->dob != '0000-00-00' && $user_data->dob != '1970-01-01')
							{ ?>
							<div class="col-sm-6 isNoFloat marginBtm20">
								<label>@lang('labels.date_of_birth')</label>
								<p><?php echo date('d/m/Y', strtotime($user_data->dob)); ?></p>
							</div>
							<?php } ?>
							<?php if (isset($user_data->nationality) && $user_data->nationality != '')
							{ ?>
							<div class="col-sm-6 isNoFloat marginBtm20">
								<label>@lang('labels.nationality')</label>
								<p><?php echo($lang == 'eng' ? $country_data->eng_country_name : $country_data->arb_country_name) ?></p>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 isNoFloat">
						<div class="row noFloatingRow">
							<div class="col-sm-6 isNoFloat">
								<div class="row noFloatingRow">
									<div class="col-md-12 isNoFloat marginBtm20">
										<label>@lang('labels.id_number')</label>
										<p><?php echo $user_data->id_no; ?></p>
									</div>
									<?php if (isset($user_data->id_image) && $user_data->id_image != '')
									{ ?>
									<div class="col-md-12 isNoFloat">
										<div class="imgBox">
											<a href="<?php echo $base_url; ?>/public/pdf/<?php echo $user_data->id_image; ?>"
											   target="_blank">View Copy of ID Card</a>
										</div>
									</div>
									<?php } ?>

									<?php if($user_data->id_expiry_date != '0000-00-00' && $user_data->id_expiry_date != '1970-01-01')
									{ ?>
									<div class="col-md-12 isNoFloat">
										<label class="grayLbl"><?php echo($lang == 'eng' ? 'EXPIRY' : 'انقضاء'); ?></label>
										<p><?php echo date('d/m/Y', strtotime($user_data->id_expiry_date)); ?></p>
									</div>
									<?php } ?>
								</div>

							</div>
							<div class="col-sm-6 isNoFloat">
								<div class="row noFloatingRow">
									<?php if (isset($user_data->license_no) && $user_data->license_no != '')
									{ ?>
									<div class="col-md-12 isNoFloat marginBtm20">
										<label>@lang('labels.driving_license_number')</label>
										<p><?php echo $user_data->license_no; ?></p>
									</div>
									<?php } ?>
									<?php if (isset($user_data->license_image) && $user_data->license_image != '')
									{ ?>
									<div class="col-md-12 isNoFloat">
										<div class="imgBox">
											<a href="<?php echo $base_url; ?>/public/pdf/<?php echo $user_data->license_image; ?>"
											   target="_blank">View Copy of Driving License</a>
										</div>
									</div>
									<?php } ?>
									<?php if($user_data->license_expiry_date != '0000-00-00' && $user_data->license_expiry_date != '1970-01-01')
									{ ?>
									<div class="col-md-12 isNoFloat">
										<label class="grayLbl"><?php echo($lang == 'eng' ? 'EXPIRY' : 'انقضاء'); ?></label>
										<p><?php echo date('d/m/Y', strtotime($user_data->license_expiry_date)); ?></p>
									</div>
									<?php } ?>
								</div>

								<?php if($user_data->id_expiry_date != '0000-00-00' && $user_data->id_expiry_date != '1970-01-01')
								{ ?>
								<div class="col-md-12 isNoFloat">
									<label class="grayLbl"><?php echo($lang == 'eng' ? 'EXPIRY' : 'انقضاء'); ?></label>
									<p><?php echo date('d/m/Y', strtotime($user_data->id_expiry_date)); ?></p>
								</div>
								<?php } ?>
							</div>

						</div>
					</div>
				</div>
				<h4>
					<a class="btn-edit" href="<?php echo $lang_base_url; ?>/edit-my-profile">
						<?php echo ($lang == 'eng' ? 'Edit' : 'تعديل').' '.($lang == 'eng' ? 'Profile' : 'الملف الشخصي'); ?>
					</a>
				</h4>
				<?php if ($user_data->payment_method != '')
				{ ?>
				<div class="paymentOption">
					<ul>
						<li>
							<div class="imgBox"><img
										src="<?php echo $base_url; ?>/public/frontend/images/paymentOption_1.png"
										alt="Method" width="25" height="26"></div>
							<h2><?php echo($lang == 'eng' ? 'PREFERRED' : 'مفضلة'); ?>
								<span><?php echo($lang == 'eng' ? 'PAYMENT METHOD' : 'طريقة الدفع او السداد'); ?></span>
							</h2>
						</li>
						<li>

							<p>
								<?php if ($user_data->payment_method == "cc") {
									echo Lang::get('labels.credit_card');
								} elseif ($user_data->payment_method == "cash") {
									echo Lang::get('labels.cash');
								} elseif ($user_data->payment_method == "sadad") {
									echo Lang::get('labels.sadad');
								} elseif ($user_data->payment_method == "points") {
									echo Lang::get('labels.loyalty_points');
								}
								?>

							</p>
						</li>
						<!--<li>
							<label>CARD NO.</label>
							<p>92 XXXX XXXX 1234</p>
						</li>
						<li>
							<label>EXPIRY</label>
							<p>27TH JUNE 2017</p>
						</li>-->
					</ul>


				</div>
				<?php } ?>
			</div>

			<?php $site = custom::site_settings(); ?>
			@if($site->refer_and_earn_option == 'on')
				<div class="btn-share-and-earn-on-subscription-wrapper">
					<a href="<?php echo $lang_base_url . '/refer_and_earn'; ?>">{{($lang == 'eng' ? 'Share & Earn on Subscription' : 'شارك و اكسب مع حجوزات الاشتراك')}}</a>
				</div>
			@endif
		</div>
        <script>
            $.get(base_url + '/cronjob/loyaltySyncCronJob?from_mobile=1&user_id_no=<?php echo $user_data->id_no; ?>', function( data ) {
            });
        </script>

    </section>
@endsection