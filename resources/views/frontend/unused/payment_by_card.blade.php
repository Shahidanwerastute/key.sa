<?php include('header.php'); ?>
<script>
	$('body').removeClass('homePage');
	$('header .logoMenuTop ul.nav li').removeClass('active');
</script>
<section class="searchNbookSec">
	<div class="container-md <?php echo custom::addClass(); ?>">
		<?php echo custom::deliveryPickupTabsArea($lang); ?>
		<div class="searchBarSec" style="display: none;">
			<div class="serText_1">
				BOOK
				<span>A CAR</span>
			</div>
			<?php include('search_area.php');?>
		</div>
		<div class="bookingStepsLink">
			<ul>
				<li class="prev"><a href="javascript:void(0);"><span>01</span> BOOKING CRITERIA</a> </li>
				<li><a href="booking.php"><span>02</span> SELECT A VEHICLE</a> </li>
				<li><a href="price.php"><span>03</span> PRICE &amp; EXTRAS</a> </li>
				<li class="active"><a href="javascript:void(0);"><span>04</span> PAYMENT</a> </li>
			</ul>
			<div class="clearfix"></div>
		</div>
	</div>
</section>
<section class="pricePageSec">
	<div class="container-md">
		<div class="pricePgWrapper ">
			<div class="leftCol">
				<div class="imgBox peddLftSet">
					<img src="images/car_1.png" alt="Car" height="132" width="274" />
				</div>
				<div class="carSumery peddLftSet">
					<div class="bookName">
						<h2>TOYOTA YARIS  <span>OR SIMILAR</span></h2>
						<div class="helpBox">
							<a class="click" href="javascript:void(0);">?</a>
							<p class="popTextP">LOREM IPSUM DOLOR SIT AMET, CONSECTETUR ADIPISCING ELIT. VESTIBULUM ELEMENTUM, NEQUE VEL IMPERDIET VESTIBULUM, TORTOR SEM EFFICITUR SAPIEN, ET DICTUM ELIT SEM NON DIAM. NULLAM FAUCIBUS A SEM NON ALIQUAM. NAM ALIQUAM.</p>
						</div>
					</div>
					<div class="subCat">
						<a href="javascript:void(0);">KIA</a>
						<a class="sep"> \ </a>
						<a href="javascript:void(0);">HYUNDAI</a>
						<a class="sep"> \ </a>
						<a href="javascript:void(0);">HONDA</a>
					</div>
					<h3>ECONOMY</h3>
				</div>
				<div class="basicDetails">
					<div class="col twoBig peddLftSet">
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
					<div class="col twoBig peddLftSet">
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
					<div class="col bookFeature peddLftSet">
						<label>FEATURES</label>
						<ul>
							<li><div class="spIconF person"></div>		<p>5</p>		</li>
							<li><div class="spIconF transmition"></div>	<p>Auto</p>		</li>
							<li><div class="spIconF door"></div>		<p>4</p>		</li>
							<li><div class="spIconF bag"></div>			<p>2</p>		</li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="col twoBig peddLftSet">
						<label>RENTAL PERIOD</label>
						<ul>
							<li>5 DAYS</li>
						</ul>
					</div>
					<div class="col twoBig peddLftSet">
						<label>RENT Per day</label>
						<ul>
							<li><h4>180 SAR</h4></li>
						</ul>
					</div>
				</div>
				<div class="extraPrice peddLftSet">
					<label>EXTRA SERVICES</label>
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
					<label>EXTRAS TOTAL: </label>
					<h4>3025 SR</h4>
				</div>
				<div class="totalWdisValu peddLftSet">
					<ul>
						<li>
							<label>TOTAL RENT FOR 5 DAYS</label>
							<h2>3925 SR</h2>
						</li>
						<li>
							<label>DISCOUNT ON PROMO CODE</label>
							<h3>300 SR</h3>
						</li>
						<li>
							<h4>
								YOU PAY TOTAL
								<span>3625 SAR</span>
							</h4>
						</li>
					</ul>
				</div>
			</div>
			<div class="rightCol">
				<h1>
					PAYMENT
					<span>&amp; SUMMARY</span>
				</h1>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ac ipsum id metus tincidunt tempus. In lacinia finibus fermentum. Nunc ullamcorper sem non porttitor facilisis.	</p>
				<h3 class="bigPrice">
					900 SR
					<span>Total</span>
				</h3>
				<form action="javascript:void(0);" method="get">
					<div class="payFrmUserInfo">
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ac ipsum id metus tincidunt tempus. In lacinia finibus fermentum. Nunc ullamcorper sem non porttitor facilisis. Praesent egect condimentum velit. Fusce id rutrum urna, ullamcorper egestas eros. Sed vehicula auctor consequat. Nunc convallis, lacus lacinia consectetur imperdiet, sem erat convallis arcu, id tempor quam tellus sed dolor.	</p>
						<ul class="formFields">
							<li>
								<label>FIRST NAME</label>
								<p>Abdul</p>
							</li>
							<li>
								<label>LAST NAME</label>
								<p>Basit</p>
							</li>
							<li>
								<label>ID TYPE</label>
								<p>CNIC</p>
							</li>
							<li>
								<label>ID NUMBER</label>
								<p>01-123456789-01</p>
							</li>
							<li>
								<label>ID EXPIRY</label>
								<p>25-Dec-2017</p>
							</li>
							<li>
								<label>MOBILE NO.</label>
								<p>+966 XX XXXXXXX</p>
							</li>
							<li>
								<label>EMAIL ADDRESS</label>
								<p>info@domain.com</p>
							</li>
						</ul>
					</div>

					<div class="payFrmUserInfo">
						<div class="paymentOption">
							<div class="creditCardInfo">
								<div class="row">
									<div class="col-sm-6">
										<label>CARD HOLDER’S FULL NAME</label>
										<input type="text" placeholder="WRITE HERE" />
									</div>
									<div class="col-sm-6">
										<label>CREDIT CARD NUMBER</label>
										<input type="text" placeholder="WRITE HERE" />
									</div>
									<div class="col-sm-5">
										<label>CVC NUMBER</label>
										<input type="text" placeholder="WRITE HERE" />
									</div>
									<div class="col-sm-7">
										<label>EXPIRY DATE</label>
										<div class="row">
											<div class="col-xs-6">
												<input type="text" placeholder="Month" />
											</div>
											<div class="col-xs-6">
												<input type="text" placeholder="WRITE HERE" />
											</div>
										</div>

									</div>

								</div>
								<link rel="stylesheet" href="https://www.paytabs.com/theme/express_checkout/css/express.css">
								<script src="https://www.paytabs.com/express/express_checkout_v3.js"></script> <!-- Button Code for PayTabs Express Checkout -->
								<script type="text/javascript">
									Paytabs("#express_checkout").expresscheckout({
										settings:{
											merchant_id: "10015610",
											secret_key: "fEDxuCrm03dwZtsQvDk4lthLH8xLTNE8shFXT7jUawq90T0vxlATOREw0bhTZW1sdHp7OJ1Zgr9glaLSwlLkdqRdC97B593iQtqF",
											amount : "10.00",
											currency : "USD",
											title : "Mr. John Doe",
											product_names: "Product1,Product2,Product3",
											order_id: 25,
											url_redirect: "http://key.ed.sa/paytab/express.php",
											display_customer_info:0,
											display_billing_fields:0,
											display_shipping_fields:0,
											language: "en",
											redirect_on_reject: 0,
											is_tokenization: false,
											is_iframe: { load: "onbodyload", //onbodyload
												show: 1,
											},
										},
										customer_info:{
											first_name: "Fozan", //we can also pre fill it
											last_name: "Baghdadi",
											phone_number: "54646546465",
											email_address: "fozan.baghdadi@edesign.com.sa",
											country_code: ""
										},
										billing_address:{
											full_address: "addr 1",
											city: "city 1",
											state: "state 1",
											country: "BHR",
											postal_code: "12345"
										},
										shipping_address:{
											shipping_first_name: "",
											shipping_last_name: "",
											full_address_shipping: "",
											city_shipping: "",
											state_shipping: "",
											country_shipping: "",
											postal_code_shipping: ""
										},
									});
								</script>







							</div>
							<input type="submit" class="redishButtonRound payNBtn" value="Pay now" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<?php include('footer.php'); ?>