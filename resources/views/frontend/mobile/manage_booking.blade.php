@extends('frontend.layouts.template')

@section('content')
    <?php //echo '<pre>';print_r($sliders);exit();
    $site = custom::site_settings(); ?>
    <section class="standardPageSec BannerTextSty offersPage">
       	<div class="bg-booking" style="background-image: url(<?php echo custom::baseurl('/'); ?>/public/uploads/bg-booking.png);"></div>
       	<div class="div-cell">
			<div class="container-md">
				<div class="row">
					<article class="intro">
						<div class="head">
							<h1><?php echo ($lang == 'eng' ? 'Manage Booking' : 'إدارة الحجز'); ?></h1>
                            <?php echo $home_content['mng_booking_mob_desc_'.$lang]; ?>
						</div>
						<div class="bookRefHome mobil-manage-booking-new-design">
							<div class="bkForm">
								<input type="text" placeholder="@lang('labels.booking_reference')" class="booking_reference_no_for_home">
								<?php if ($site->maintenance_mode == 'on'){ ?>
								<input type="button" onclick="siteUnderMaintenance();" value="<?php echo ($lang == 'eng'?'Search':'البحث');?>">
								<?php }else{ ?>
								<input type="button" class="manageBookingFromHome" value="<?php echo ($lang == 'eng'?'Search':'البحث');?>">
								<?php } ?>
							</div>
						</div>
					</article>
				</div>
			</div>
        </div>
    </section>

@endsection