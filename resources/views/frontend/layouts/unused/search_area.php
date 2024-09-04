<script type="text/javascript">
	var map;
	function initialize()
	{
		map = new google.maps.Map(document.getElementById('map-canvas'), {
			center: new google.maps.LatLng(24.725398,46.2619989),//Setting Initial Position
			zoom: 10,
			mapTypeId: 'roadmap',
			gestureHandling: 'greedy'

		});
	}
	var iconBase = 'images/';
	var icons = {
		parking: {
			icon: iconBase + 'logoIcon.png'
		}
	};


	//set up markers
	var myMarkers = {"markers": [
		{"latitude": "21.6613694", "longitude":"39.1713496", "icon": "images/logoIcon.png", "baloon_text": 'This is <strong>Makkah</strong>'},
		{"latitude": "26.8048659", "longitude":"37.9472178", "icon": "images/logoIcon.png", "baloon_text": 'This is <strong>Makkah</strong>'},
		{"latitude": "26.8048659", "longitude":"37.9472178", "icon": "images/logoIcon.png", "baloon_text": 'This is <strong>Al Awali</strong>'}
	]
	};
	function newLocation(newLat,newLng)
	{
		myMarkers
		map.setCenter({
			lat : newLat,
			lng : newLng
		});
	}

	google.maps.event.addDomListener(window, 'load', initialize);

	//Setting Location with jQuery
	$(document).ready(function ()
	{
		$("#1").on('click', function ()
		{
			newLocation(21.6613694,39.1713496);
		});

		$("#2").on('click', function ()
		{
			newLocation(26.8048659,37.9472178);
		});

		$("#3").on('click', function ()
		{
			newLocation(26.8048659,37.9472178);
		});
		$('.searchBarSec .serFormArea .fleetDropDown .locaListFix ul li a').on('click', function () {
			$('.searchBarSec .serFormArea .fleetDropDown .locaListFix ul li a').removeClass('active');
			$(this).addClass('active');
		});
		$('.showMapPointer').on('click', function () {
			var nLocIs = $(this).data("location");
			//alert(nLocIs);
			//newLocation(48.1293954,11.556663);
		})
	});
</script>
<div class="serFormArea">
	<ul>
		<li class="big">
			<label>Pick Up</label>
			<input type="text" class="backLocation showMapFn" placeholder="Select" onclick="showMapFn(this)" />
		</li>
		<li class="big"><!-- 	 hidden-sm hidden-xs		-->
			<label>Drop Off</label>
			<input type="text" class="backSandGrayPlus backLocation showMapFn" placeholder="Select" onclick="showMapFn(this)" />
		</li>
		<!--<li class="big visible-sm visible-xs ">
			<label>PICKUP &amp; DROP OFF <span class="pull-right">CHANGE DROP OFF</span></label>
			<input type="text" class="backSandGrayPlus backLocation showMapFn" placeholder="Select" onclick="showMapFn('dropOff')" />
			<div class="clearfix"></div>
		</li>-->
		<li class="small ">
			<label>Pickup Schedule</label>
			<input type="text" class="calender backCalendar" placeholder="10/02/2017" />
			<input id="pickUpTime" type="text" class="backSandGrayPlus backClock pickUpTime" placeholder="10:30 AM" value="" />
		</li>
		<li class="small">
			<label>Drop Off Schedule</label>
			<input type="text" class="calender backCalendar" placeholder="10/02/2017" />
			<input type="text" class="backSandGrayPlus backClock pickUpTime" placeholder="10:30AM" />
		</li>
		<li class="submitBtn">
			<a href="booking.php"><input type="submit" value="Search" />
		</li>
	</ul>
	<div class="fleetDropDown">
		<div class="locaListFix mCustomScrollbar">
			<ul>
				<li><h3>JEDDAH</h3></li>
				<li><a href="javascript:void(0);" id="1" class="showMapPointer"  data-location="48.1293954,11.556663" >KING ABDULAZIZ INTERNATIONAL AIRPORT</a></li>
				<li><a href="javascript:void(0);" id="2" class="showMapPointer"  data-location="40.7033127,-73.979681" >MSADIA CENTER, RUWAIS DISTRICT</a></li>
				<li><a href="javascript:void(0);" id="3" class="showMapPointer"  data-location="55.749792,37.632495" >BAGHDADIYAH DISTRICT</a></li>
				<li><a href="javascript:void(0);" class="showMapPointer"  data-location="48.1293954,11.556663" >FAYHA DISTRICT</a></li>
				<li><a href="javascript:void(0);" class="showMapPointer"  data-location="55.749792,37.632495" >MADINA ROAD</a></li>

				<li><h3>RIYADH</h3></li>
				<li><a href="javascript:void(0);" class="showMapPointer"  data-location="55.749792,37.632495" >KING KHALED INTERNATIONAL AIRPORT</a></li>
				<li><a href="javascript:void(0);" class="showMapPointer"  data-location="48.1293954,11.556663" >AL Olaya Riyadh Branch</a></li>
				<li><a href="javascript:void(0);" class="showMapPointer"  data-location="40.7033127,-73.979681" >King Fahad Road Branch</a></li>
				<li><a href="javascript:void(0);" class="showMapPointer"  data-location="55.749792,37.632495" >KING KHALED INTERNATIONAL AIRPORT</a></li>
				<li><a href="javascript:void(0);" class="showMapPointer"  data-location="48.1293954,11.556663" >KING KHALED INTERNATIONAL AIRPORT</a></li>
			</ul>
		</div>
		<div class="dropMapLoc">
			<div style="" id="map-canvas"></div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>