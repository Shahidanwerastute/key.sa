@extends('frontend.layouts.template')

@section('content')
<?php $site = custom::site_settings(); ?>
<section class="textBannerSec" >
	<div class="container-md">
		<h1>
		@lang('labels.registration')
			<!--<strong>s</strong>
			<span></span>-->
		</h1>
	</div>
</section>
<section class="standardPageSec BannerTextSty">
	<div class="container-md">
		<div class="registrationPage">
			<div class="whiteBox1240">
				<div class="leftSec">
					<h1><?php echo ($lang == 'eng' ? Lang::get('labels.new') : Lang::get('labels.customer')); ?> <br /><?php echo ($lang == 'eng' ? Lang::get('labels.customer') : Lang::get('labels.new')); ?></h1>
					<p>@lang('labels.after_register_reservation_msg')</p>
					<?php if ($site->maintenance_mode == 'on')
					{ ?>
					<a href="javascript:void(0);" onclick="siteUnderMaintenance();"><input style="text-transform: capitalize;" type="button" value="@lang('labels.register_new')" class="edBtn" /></a>
					<?php }else{ ?>
					<a href="<?php echo $lang_base_url; ?>/create-ind-user"><input style="text-transform: capitalize;" type="button" value="@lang('labels.register_new')" class="edBtn" /></a>
					<?php } ?>
				</div>
				<div class="rightSec ">
					<h1>@lang('labels.already_customer') <br/></h1>
					<p>@lang('labels.already_customer_then_login_msg')</p>

					<?php if ($site->maintenance_mode == 'on')
					{ ?>
					<a href="javascript:void(0);" onclick="siteUnderMaintenance();"><input style="text-transform: capitalize;" type="button" value="@lang('labels.create_login')" class="edBtn" /></a>
					<?php }else{ ?>
					<a href="<?php echo $lang_base_url; ?>/create-ind-login"><input style="text-transform: capitalize;" type="button" value="@lang('labels.create_login')" class="edBtn" /></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection