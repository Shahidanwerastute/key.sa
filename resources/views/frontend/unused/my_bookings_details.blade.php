@extends('frontend.layouts.template')

@section('content')

<section class="textBannerSec" >
	<div class="container-md">

	</div>
</section>
<section class="myAccountSec">
	<div class="container-md">
		<div class="myAccountWrapper">
			@include('frontend.layouts.profile_inner_section')
			<div class="myProfDetail">
				<a href="<?php echo $lang_base_url; ?>/my-bookings" class="back2Page"><img src="<?php echo $base_url; ?>/public/frontend/images/goBack.png" alt="<--" height="26" width="26" /> Go Back</a>
				<h1><strong>My  </strong> BOOKINGS</h1>
				<div class="row">
					<div class="col-md-12">
						<div class="myBookingRow notPicked">
							<div class="topName">
								<h4>Your Reservation: <span> 9847395846 </span></h4>
							</div>
							<div class="topOptions">
								<h3>NOT PICKED</h3>
								<div class="buttonsOpt">
									<a href="javascript:void(0);">
										<button class="grayishButton"><img src="<?php echo $base_url; ?>/public/frontend/images/cancel.png" alt="X" width="15" height="14"> Cancel</button>
									</a>
									<a href="my_bookings_details.php">
										<button class="grayishButton"><img src="<?php echo $base_url; ?>/public/frontend/images/edit.png" alt="E" width="15" height="14">Edit</button>
									</a>
									<a href="javascript:void(0);">
										<button class="grayishButton"><img src="<?php echo $base_url; ?>/public/frontend/images/print.png" alt="P" width="15" height="14">Print</button>
									</a>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="clearfix"></div>
							<div class="mBookingDTL">
								<div class="col twoBig">
									<label>Pick Up</label>
									<ul>
										<li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/location.png" alt="" width="13" height="18">JEDDAH AIRPORT, NORTH TERMINAL</li>
										<li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/calendar.png" alt="" width="16" height="18"> 23 / 01 / 2017</li>
										<li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/clock.png" alt="" width="18" height="18"> 10:30 AM</li>
									</ul>
								</div>
								<div class="col twoBig">
									<label>Drop Off</label>
									<ul>
										<li><img class="abImg" src="<?php echo$base_url; ?>/public/frontend/images/location.png" alt="" width="13" height="18">JEDDAH AIRPORT, NORTH TERMINAL</li>
										<li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/calendar.png" alt="" width="16" height="18"> 23 / 01 / 2017</li>
										<li><img class="abImg" src="<?php echo $base_url; ?>/public/frontend/images/clock.png" alt="" width="18" height="18"> 10:30 AM</li>
									</ul>
								</div>
								<div class="col imgBox">
									<div class="displayTable">
										<div class="disTableCell">
											<img src="<?php echo $base_url; ?>/public/frontend/images/car_1.png" alt="Car" width="274" height="132">
										</div>
									</div>
								</div>
								<div class="col small">
									<label>Features</label>
									<ul>
										<li><div class="spIconF person"></div>		<p>5</p>		</li>
										<li><div class="spIconF transmition"></div>	<p>Auto</p>		</li>
										<li><div class="spIconF door"></div>		<p>4</p>		</li>
										<li><div class="spIconF bag"></div>			<p>2</p>		</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
@endsection