@extends('frontend.layouts.template')


@section('content')

    <style>
        body.fleet .carsGrid .col.bookFeature {
            padding: 0 20px !important;
        }

        body.fleet section.fleet-booking-sect.fleet .carsGrid .singleRow .bookDtlSec .bookPSec .col.bookBtn {
            width: 100% !important;
        }
    </style>

    <?php
    $siteSettings = custom::site_settings();
    $AirportRegions = custom::getAirportRegions($lang);
    $airport_pickup_regions = $AirportRegions['airport_pickup_regions'];
    $airport_dropoff_regions = $AirportRegions['airport_dropoff_regions'];
    ?>

    <section class="textBannerSec searchBarSec fleet-new-design">
        <div class="container-fluid">
            <h1>
                @lang('labels.fleet')
                    <!--<strong>s</strong>-->
                    <!--<span>login </span>-->
            </h1>
            <p><?php echo ($lang == 'eng' ? 'Key fleet contains modern and reliable vehicles that meet the needs of all customers, ranging from small economy cars, medium to large sedans, luxury vehicles and SUVs/ family cars.' : 'يحتوي اسطول المفتاح على سيارات حديثة وموثوقة تلبي احتياجات جميع العملاء بدء من السيارات الاقتصادية الصغيرة،سيارات السيدان المتوسطة والكبيرة ،السيارات الفاخرة وسيارات <br> الدفع الرباعي / العائلية'); ?></p>
            <form action="javascript:void(0);" class="fleetSerBar serFormArea">
                <ul>
                    <li class="big">
                        <label>@lang('labels.display_models_for_location')</label>
                        <input type="text" class="backLocation showMapFn from_branch_field required-for-search"
                               name="from_branch_name" value="" placeholder="@lang('labels.select')"
                               onclick="showMapFn(this)"/>
                    </li>
                    <li>
                        <label>@lang('labels.fleet_class')</label>
                        <select class="select-box-style" name="cat_id" onchange="fleet_serch_cat_filter($(this).val(), '{{route('fleet'.($lang == 'eng' ? '-ar' : ''))}}',false);">
                            <option value="0"><?php echo($lang == 'eng' ? 'ALL' : 'الكل'); ?></option>
                            <?php foreach ($categories as $category){ ?>
                            <option value="<?php echo $category->id; ?>" <?php echo $car_category == $category->id ?'selected':''; ?>><?php echo($lang == 'eng' ? $category->eng_title : $category->arb_title); ?></option>
                            <?php } ?>
                        </select>
                    </li>
                    <li>
                        <label>@lang('labels.model_year')</label>
                        <select class="select-box-style" name="model" onchange="search_cars_with_all_fields();">
                            <option value="">@lang('labels.select')</option>
                            <?php foreach($years as $year){
                                if ($year > 2021) { ?>
                            <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                <?php }
                                ?>
                            <?php } ?>
                        </select>
                    </li>
                </ul>
                <input type="hidden" name="class" value="all" />
                <input type="hidden" name="branch" value="" id="searchBranchId" />
                <input type="hidden" id="from_region_id" name="from_region_id" value="1">

                {{--<input type="button" class="redishButton" value="Search" />--}}

                <div id="pickUp" class="fleetDropDown search-main-container-new-design">
                    <div class="locaListFix">
                        <div class="dropdown-tabs">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-3">
                                        <ul class="nav nav-tabs tabs-left sideways">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#pickup-airports"><?php echo ($lang == 'eng' ? 'Airports' : 'المطارات'); ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#pickup-all-ranches"><?php echo ($lang == 'eng' ? 'All Branches' : 'جميع الفروع'); ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-8 col-xs-12">
                                                <div class="tabs-parent-box">
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="pickup-airports">
                                                            <ul class="pickup_branches_list" id="myULFromForPickupForAirportsOnly">
                                                                <?php //echo '<pre>';print_r($pickup_regions);exit(); ?>
                                                                <?php foreach ($airport_pickup_regions as $key => $region){ ?>
                                                                <?php $from_city_data = explode('|', $key); ?>


                                                                <?php foreach ($region as $reg){

                                                                $latLongArr = explode(',',$reg->map_latlng);

                                                                if($reg->map_latlng != "" && strpos($reg->map_latlng,",") !==false && count($latLongArr)===2 ){

                                                                    $lat = $latLongArr[0];
                                                                    $long = $latLongArr[1];
                                                                }else{
                                                                    $lat = "85";
                                                                    $long = "-180";
                                                                }
                                                                ?>
                                                                <li class="filterDataFromForPickup" id="from_branch_<?php echo $reg->id; ?>"
                                                                    data-val_id="<?php echo $reg->id; ?>"><a class="showMapPointer"
                                                                                                             onclick="$('.from_branch_field').val($(this).text());$('.to_branch_field').val($(this).text());setBranchIdInHiddenField('<?php echo $reg->id; ?>');"
                                                                                                             data-loclat="<?php echo str_replace(" ","",$lat); ?>"
                                                                                                             data-loclong="<?php echo str_replace(" ","",$long); ?>"
                                                                                                             data-regionid="<?php echo $reg->region_id; ?>"
                                                                                                             data-cityid="<?php echo $reg->city_id; ?>"
                                                                                                             data-branchid="<?php echo $reg->id; ?>"
                                                                                                             data-address1="<?php echo $reg->address_line_1;?>"
                                                                                                             data-address2="<?php echo $reg->address_line_2;?>"
                                                                                                             data-hours="<?php echo $reg->opening_hours;?>"
                                                                                                             data-hoursrange="<?php echo $reg->opening_hours_date_range;?>"
                                                                                                             data-phone1="<?php echo $reg->phone1;?>"
                                                                                                             data-phone2="<?php echo $reg->phone2;?>"
                                                                                                             data-mobile="<?php echo $reg->mobile;?>"
                                                                                                             data-agent="<?php echo $reg->agent_name;?>"
                                                                                                             data-branch_id="<?php echo $reg->id;?>"
                                                                                                             data-email="<?php echo $reg->email;?>"><?php echo($lang == 'eng' ? $reg->eng_title : $reg->arb_title); ?></a>

                                                                    <div class="filterDataParentFrom" id="from_city_<?php echo $from_city_data[0]; ?>">
                                                                        <h3><?php echo $from_city_data[1]; ?></h3></div>

                                                                </li>
                                                                <?php
                                                                }
                                                                } ?>
                                                            </ul>
                                                        </div>
                                                        <div class="tab-pane" id="pickup-all-ranches">
                                                            <ul class="pickup_branches_list" id="myULFromForPickupAllBranches">
                                                                <?php //echo '<pre>';print_r($pickup_regions);exit(); ?>
                                                                <?php foreach ($pickup_regions as $key => $region){ ?>
                                                                <?php $from_city_data = explode('|', $key); ?>
                                                                <li class="filterDataParentFrom" id="from_city_<?php echo $from_city_data[0]; ?>">
                                                                    <h3><?php echo $from_city_data[1]; ?></h3></li>

                                                                <?php foreach ($region as $reg){

                                                                $latLongArr = explode(',',$reg->map_latlng);

                                                                if($reg->map_latlng != "" && strpos($reg->map_latlng,",") !==false && count($latLongArr)===2 ){

                                                                    $lat = $latLongArr[0];
                                                                    $long = $latLongArr[1];
                                                                }else{
                                                                    $lat = "85";
                                                                    $long = "-180";
                                                                }
                                                                ?>
                                                                <li class="filterDataFromForPickup" id="from_branch_<?php echo $reg->id; ?>"
                                                                    data-val_id="<?php echo $reg->id; ?>"><a class="showMapPointer"
                                                                                                             onclick="$('.from_branch_field').val($(this).text());$('.to_branch_field').val($(this).text());setBranchIdInHiddenField('<?php echo $reg->id; ?>');"
                                                                                                             data-loclat="<?php echo str_replace(" ","",$lat); ?>"
                                                                                                             data-loclong="<?php echo str_replace(" ","",$long); ?>"
                                                                                                             data-regionid="<?php echo $reg->region_id; ?>"
                                                                                                             data-cityid="<?php echo $reg->city_id; ?>"
                                                                                                             data-branchid="<?php echo $reg->id; ?>"
                                                                                                             data-address1="<?php echo $reg->address_line_1;?>"
                                                                                                             data-address2="<?php echo $reg->address_line_2;?>"
                                                                                                             data-hours="<?php echo $reg->opening_hours;?>"
                                                                                                             data-hoursrange="<?php echo $reg->opening_hours_date_range;?>"
                                                                                                             data-phone1="<?php echo $reg->phone1;?>"
                                                                                                             data-phone2="<?php echo $reg->phone2;?>"
                                                                                                             data-mobile="<?php echo $reg->mobile;?>"
                                                                                                             data-agent="<?php echo $reg->agent_name;?>"
                                                                                                             data-branch_id="<?php echo $reg->id;?>"
                                                                                                             data-email="<?php echo $reg->email;?>"><?php echo($lang == 'eng' ? $reg->eng_title : $reg->arb_title); ?></a>
                                                                </li>
                                                                <?php
                                                                }
                                                                } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-xs-12">
                                                <div class="branch-timing-main-box">
                                                    <div class="branch-time-content-box from-branch-timings" style="display: none;">
                                                        <h3><?php echo ($lang == 'eng' ? 'Branch Timing' : 'توقيت الفرع'); ?></h3>
                                                        <div class="branch-time-box">
                                                            <div class="branch-icon">
                                                                <img src="<?php echo custom::baseurl('public/frontend/images/icon-clock.png'); ?>" alt="">
                                                            </div>
                                                            <div class="branch-time-text-box">
                                                                <h5>24 Hours / 7 Days</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="branch-time-content-box from-branch-location" style="display: none;">
                                                        <h3><?php echo ($lang == 'eng' ? 'Branch Location' : 'موقع الفرع'); ?></h3>
                                                        <div class="branch-time-box">
                                                            <div class="branch-icon">
                                                                <img src="<?php echo custom::baseurl('public/frontend/images/icon-locate.png'); ?>" alt="">
                                                            </div>
                                                            <div class="branch-time-text-box">
                                                                <a href="#" target="_blank"><?php echo ($lang == 'eng' ? 'Click Here' : 'انقر هنا'); ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dropMapLoc">
                        <div style="" id="map-canvas-pickup"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </form>
        </div>
    </section>
    <section class="bookingSec fleet fleet-booking-sect">
        <div class="container-md">
            <div class="containsData fleetLoadMorePage search-new-box <?php echo ($siteSettings->website_cars_listing_style == 'grid' ? 'carsGrid' : 'carsList'); ?>">
                <?php echo custom::fleetPageHtml($car_models,$base_url,$lang_base_url,$lang); ?>
                <?php /*foreach($car_models as $car_model){ */?><!--
		<div class="singleRow">
			<div class="imgBox">
				<img src="<?php /*echo $base_url; */?>/public/uploads/<?php /*echo $car_model['image1']; */?>" alt="car" height="132" width="274" />
			</div>
			<div class="bookDtlSec">
				<div class="bookName">
					<h2><?php /*echo $car_model['ct_'.$lang.'_title']; */?> <?php /*echo $car_model[$lang.'_title']; */?></h2>
				</div>
				<h3><?php /*echo $car_model['cc_'.$lang.'_title']; */?></h3>
				<div class="bookPSec">
					<div class="col bookFeature">
						<h4>@lang('labels.features')</h4>
						<ul>
							<li><div class="spIconF person"></div>		<p><?php /*echo $car_model['no_of_passengers']; */?></p>		</li>
							<li><div class="spIconF transmition"></div>	<p><?php /*echo ($car_model['transmission'] == 'Auto' ? ($lang == 'eng' ? 'Auto' : 'اتوماتيك') : ($lang == 'eng' ? 'Manual' : 'عادي')); */?></p>		</li>
							<li><div class="spIconF door"></div>		<p><?php /*echo $car_model['no_of_doors']; */?></p>		</li>
							<li><div class="spIconF bag"></div>			<p><?php /*echo $car_model['no_of_bags']; */?></p>		</li>
						</ul>
					</div>
					{{--<div class="col totalRent">
						<h4>STARTING FROM</h4>
						<p>150 SR</p>
					</div>--}}
                        <div class="col bookBtn">
                            <a href="<?php /*echo $lang_base_url.'/fleet/booking/'.$car_model['id']; */?>"><input type="button" class="edBtn" value="@lang('labels.book_now_btn')" ></a>
					</div>

					<div class="clearfix"></div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	--><?php /*} */?>
            </div>
            <div class="moreRecordsDiv"><button class="loadMore" onclick="loadMoreCars();">@lang('labels.load_more')</button></div>
            <div class="noRecordFoundDiv" style="display: none;"><div class="noResultFound"><span>No more record found</span></div></div>
        </div>

    </section>

    <script type="text/javascript">
        var map;
        var pickOrDrop;
        // Map Icon Image
        var iconBase = "{{custom::baseurl('public/frontend/images/main-pointer.png?v=0.1')}}";
        function initialize(address1, address2, hours, phone1, phone2, mobile, email, agent_name, newLat, newLng,branch_id) {
            map = new google.maps.Map(document.getElementById(pickOrDrop), {
                center: new google.maps.LatLng(newLat, newLng),//Setting Initial Position
                zoom: 17,
                mapTypeId: 'roadmap',
                gestureHandling: 'greedy'
            });
                    <?php if($lang == "eng"){ ?>
            var address = address1;
            var openingLabel = "OPENING HOURS:";
            var workinghours = "Working hours";
                    <?php }else{ ?>
            var address = address2;
            var openingLabel = "ساعات الدوام:";
            var workinghours = "ساعات الدوام:";
            <?php } ?>
            // InfoWindow content
            var content = '<div class="mapPopUp" id="iw-container">' +
                    '<div class="leftPU"> ' +
                    '<h2>' + address + '</h2>' +
                    '<p>&nbsp;</p>';
            if(phone1 != "" && phone2 != "") {
                content += '<p><strong>T:</strong> ' + phone1 + ' ' + phone2 + ' </p>';
            }if(mobile != "") {
                content += '<p><strong>M:</strong>' + mobile + ' </p>';
            }if(email != "") {
                content += '<p><strong>E:</strong>&nbsp; ' + email + '</p>';
            }if(agent_name != "") {
                content += '<p><strong>&nbsp;&nbsp;&nbsp;</strong>&nbsp; &nbsp;' + agent_name + '</p>';
            }
            content += '</div>' +
                    '<div class="rightPU"> ' +
                    '<h3>'+openingLabel+'</h3>' +
                    '<p>' +workinghours+': '+ hours + '</p>' +
                    '<a href="<?php echo $lang_base_url.'/printMapPopups/';?>'+branch_id+'" class="printBtn" target="_blank"><img src="<?php echo custom::baseurl('public/frontend/images/print.png') ; ?>" alt="Print" width="15" height="14"> Print </a>' +
                    '</div>' +
                    '<div class="clearfix"></div> ' +
                    '</div>';

            // A new Info Window is created and set content
            var infowindow = new google.maps.InfoWindow({
                content: content,

                // Assign a maximum value for the width of the infowindow allows
                // greater control over the various content elements
                maxWidth: 430
            });

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(newLat, newLng),
                title: "",
                map: map,
                icon: iconBase,
            });

            google.maps.event.addListener(marker, 'click', function () {
                map.panTo(marker.getPosition());
                //map.setCenter(marker.getPosition()); // sets center without animation
                infowindow.open(map, marker);
            });

            //infowindow.open(map,marker); //to open the infowindow by default

            // Event that closes the Info Window with a click on the map
            /*	google.maps.event.addListener(map, 'click', function() {
             infowindow.close();
             });
             */
        }

        google.maps.event.addDomListener(window, 'load', initialize);

        //Setting Location with jQuery
        $(document).ready(function () {

            $('.fleetDropDown#pickUp .locaListFix ul.pickup_branches_list li').on('click', function () {
                $('.fleetDropDown#pickUp .locaListFix ul.pickup_branches_list li').removeClass('active');
                $(this).addClass('active');

                //===
                $('.fleetDropDown#dropOff .locaListFix ul li a').removeClass('active');
                $('.fleetDropDown#dropOff .locaListFix ul li a[data-branchid="' + $(this).data('branchid') + '"]').addClass('active');
                //===
                $('.fleetDropDown').slideUp();
                $('.fleetDropDown').removeClass('mapShown backSandGrayPlus');
                $('.fleetDropDown .dropMapLoc').removeClass('open');
            });
            $('.fleetDropDown#dropOff .locaListFix ul li a').on('click', function () {
                $('.fleetDropDown#dropOff .locaListFix ul li a').removeClass('active');
                $(this).addClass('active');

                $('.fleetDropDown').slideUp();
                $('.fleetDropDown').removeClass('mapShown backSandGrayPlus');
                $('.fleetDropDown .dropMapLoc').removeClass('open');
            });

            $('.showMapPointer').on('mouseover', function () {

                var loc_lat = $(this).data('loclat')
                var loc_long = $(this).data("loclong");
                var address1 = $(this).data("address1");
                var address2 = $(this).data("address2");
                var hours = $(this).data("hours");
                var phone1 = $(this).data("phone1");
                var phone2 = $(this).data("phone2");
                var mobile = $(this).data("mobile");
                var email = $(this).data("email");
                var agent_name = $(this).data("agent");
                var branch_id = $(this).data("branch_id");

                setTimeout(function () {
                    initialize(address1, address2, hours, phone1, phone2, mobile, email, agent_name, loc_lat, loc_long,branch_id);
                }, 500);
            })
        });

        /*=============================================
         search Result Function
         ============================================*/

        function isScrolledIntoView(elem)
        {
            var docViewTop = $(window).scrollTop();
            var docViewBottom = docViewTop + $(window).height();

            var elemTop = $(elem).offset().top;
            var elemBottom = elemTop + $(elem).height();

            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
        }


        function showMapFn(typeVal) {
            var locationType = $(typeVal).prevAll("label").text();

            // You can change its value by using using this "locationType"
            //     alert(locationType );
            if ($(typeVal).hasClass("backSandGrayPlus")) {
                if ($(".fleetDropDown#dropOff").hasClass("mapShown")) {
                    $('.fleetDropDown#pickUp').removeClass('mapShown');
                    $('.fleetDropDown#pickUp .dropMapLoc').removeClass('open');
                }
                clossingFun();
                $('.fleetDropDown#dropOff').addClass('active backSandGrayPlus');
                $('.fleetDropDown#dropOff').slideDown('slow');

                pickOrDrop = "map-canvas-dropoff";

            } else {
                $('.fleetDropDown').removeClass('backSandGrayPlus');
                if ($(".fleetDropDown#pickUp").hasClass("mapShown")) {
                    $('.fleetDropDown#dropOff').removeClass('mapShown');
                    $('.fleetDropDown#dropOff .dropMapLoc').removeClass('open');
                }
                clossingFun();
                $('.fleetDropDown#pickUp').addClass('active');
                $('.fleetDropDown#pickUp').slideDown('slow');

                pickOrDrop = "map-canvas-pickup";
            }




            // scrolling function
            if (page == "home") {
                if(!isScrolledIntoView($('#forScrollHelp')))
                {
                    $('body,html').animate({
                        scrollTop: 500
                    }, 500);

                }
            }


        }
        ;
        function clossingFun(e) {
            $('.fleetDropDown').hide();
            $('.fleetDropDown').removeClass('active backSandGrayPlus');
        }
        ;

        $(document).ready(function () {
            $('.fleetDropDown .locaListFix > ul > li > a').on('mouseover', function () {

                $('.fleetDropDown').removeClass('mapShown');
                $('.fleetDropDown .dropMapLoc').removeClass('open');
                $(this).closest('.fleetDropDown').addClass('mapShown');
                $('.fleetDropDown.mapShown .dropMapLoc').addClass('open');

                var pickOrDropId = $(this).closest(".fleetDropDown").attr("id");
                if (pickOrDropId == "pickUp") {
                    $("#from_region_id").val($(this).data("regionid"));
                    $("#from_city_id").val($(this).data("cityid"));
                    $("#from_branch_id").val($(this).data("branchid"));
                    $("#to_city_id").val($(this).data("cityid"));
                    $("#to_branch_id").val($(this).data("branchid"));

                } else if (pickOrDropId == "dropOff") {
                    $("#to_city_id").val($(this).data("cityid"));
                    $("#to_branch_id").val($(this).data("branchid"));
                }


            })
        });
        $(document).mouseup(function (e) {
            var container = $(".showMapFn, .fleetDropDown");
            if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                $('.fleetDropDown').slideUp();
                $('.fleetDropDown').removeClass('mapShown backSandGrayPlus');
                $('.fleetDropDown .dropMapLoc').removeClass('open');
            }
        });
        /*=======  END  ======*/
    </script>
@endsection