<?php include('header.php'); ?>
<script>
	$('body').removeClass('homePage');
	$('body').addClass('contUs');
	$('header .logoMenuTop ul.nav li').removeClass('active');
</script>
<section class="textBannerSec" >
	<div class="container-md">

	</div>
</section>
<section class="myAccountSec">
	<div class="container-md">
		<div class="myAccountWrapper">
			<div class="myProfDetail manageBooking">
				<h1><strong>MANAGE  </strong> BOOKINGS</h1>
				<div class="bookingDetailSec">
					<div class="myBookingRow notPicked">
						<div class="topName">
							<h4>Your Reservation: <span> 9847395846 </span></h4>
						</div>
						<div class="topOptions">
							<h3>NOT PICKED</h3>
							<div class="buttonsOpt">
								<a href="javascript:void(0);">
									<button class="grayishButton" data-bs-toggle="modal" data-bs-target="#confirmDel"><img src="images/cancel.png" alt="X" width="15" height="14"> Cancel</button>
								</a>
								<a href="my_bookings_details.php">
									<button class="grayishButton"><img src="images/edit.png" alt="E" width="15" height="14">Edit</button>
								</a>
								<a href="javascript:void(0);">
									<button class="grayishButton"><img src="images/print.png" alt="P" width="15" height="14">Print</button>
								</a>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="sixBoxStr">
						<div class="col carSumery">
							<div class="bCenter">
								<div class="bookName">
									<h2>TOYOTA YARIS  <span>OR SIMILAR</span></h2>
								</div>
								<h3>ECONOMY</h3>
								<ul class="userInfo">
									<li><strong>Basit Chughtai</strong></li>
									<li><strong>Id:</strong> 21234567</li>
									<li><strong>M:</strong> +966 55 1234567</li>
									<li><strong>E:</strong> user@key.sa</li>
								</ul>
							</div>
						</div>
						<div class="col twoBig ">
							<div class="bCenter">
								<label>Pick Up</label>
								<ul>
									<li title="JEDDAH AIRPORT, NORTH TERMINAL">
										<img class="abImg" src="images/location.png" alt="" width="13" height="18">
										JEDDAH AIRPORT, NORTH TERMINAL
									</li>
									<li><img class="abImg" src="images/calendar.png" alt="" width="16" height="18"> 23 / 01 / 2017</li>
									<li><img class="abImg" src="images/clock.png" alt="" width="18" height="18"> 10:30 AM</li>
								</ul>
							</div>
						</div>
						<div class="col twoBig ">
							<div class="bCenter">
								<label>Drop Off</label>
								<ul>
									<li title="JEDDAH AIRPORT, NORTH TERMINAL">
										<img class="abImg" src="images/location.png" alt="" width="13" height="18">
										JEDDAH AIRPORT, NORTH TERMINAL
									</li>
									<li><img class="abImg" src="images/calendar.png" alt="" width="16" height="18"> 23 / 01 / 2017</li>
									<li><img class="abImg" src="images/clock.png" alt="" width="18" height="18"> 10:30 AM</li>
								</ul>
							</div>
						</div>
						<div class="col imgBox">
							<div class="bCenter">
								<img src="images/car_1.png" alt="Car" width="274" height="132">
							</div>
						</div>
						<div class="col extraPrice ">
							<div class="bCenter">
								<label>EXTRA</label>
								<ul>
									<li>
										Loss Damage Waiver
										<p>2,000 SR</p>
									</li>
									<li>
										GPS
										<p>50 SR x 5 DAYS</p>
									</li>
									<li>
										Extra driver
										<p>150 SR x 5 DAYS</p>
									</li>
									<li>
										Baby Car Protection Seat
										<p>5 SR x 5 DAYS</p>
									</li>
								</ul>
							</div>
						</div>
						<div class="col bookFeature ">
							<div class="bCenter">
								<label>FEATURES</label>
								<ul>
									<li><div class="spIconF person"></div>		<p>5</p>		</li>
									<li><div class="spIconF transmition"></div>	<p>Auto</p>		</li>
									<li><div class="spIconF door"></div>		<p>4</p>		</li>
									<li><div class="spIconF bag"></div>			<p>2</p>		</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="col subTtl full yellowBox">
							<div class="bCenter">
								<p>TOTAL PAID</p>
								<p><strong>600 SAR</strong></p>
								<p><span class="buy">BY <img src="images/sadatFFF.png" alt="Sadat" height="13" width="54" /> </span> </p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Modal -->
<div class="modal fade" id="confirmDel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				<h4 class="modal-title" id="myModalLabel">Confirm</h4>
			</div>
			<div class="modal-body confmDelfrm text-center">
				<br />
				<p>Are you sure you want to delete your booking?</p>
				<form action="javascript:void(0);" method="get">
					<input type="email" required placeholder="ENTER YOUR EMAIL ADDRESS" />
					<div class="twoBtnEd">
						<input type="submit" class="redishButtonRound" value="Yes" />
						<input type="button" class="grayishButton" value="No"  data-bs-dismiss="modal" />
						<div class="clearfix"></div>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>