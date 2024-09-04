<script type="text/javascript">
    var medium = '<?php echo custom::checkIfMobielTabOrPC(); ?>';
    var map;
    var pickOrDrop;
    // Map Icon Image
    var iconBase = "{{custom::baseurl('public/frontend/images/main-pointer.png?v=0.1')}}";

    function initialize(address1, address2, hours, hoursrange, phone1, phone2, mobile, email, agent_name, newLat, newLng, branch_id) {
        if (document.getElementById(pickOrDrop) === null) {
            return false;
        }

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

        <?php
        $siteSettings = custom::site_settings();
        $isRangeOn = $siteSettings->date_range_mode=='on'?true:false;
        $startRange = date('d-m-Y',strtotime($siteSettings->start_date));
        $endRange = date('d-m-Y',strtotime($siteSettings->end_date));
        if($isRangeOn) { ?>
        var workinghoursdaterange = "<?php echo $startRange.' - '.$endRange.': '; ?>"+ hoursrange;
        <?php }else{ ?>
        var workinghoursdaterange = "";
        <?php } ?>


        // InfoWindow content
        var content = '<div class="mapPopUp" id="iw-container">' +
            '<div class="leftPU"> ' +
            '<h2>' + address + '</h2>' +
            '<p>&nbsp;</p>';
        if (phone1 != "" && phone2 != "") {
            content += '<p><strong>T:</strong> ' + phone1 + ' ' + phone2 + ' </p>';
        }
        if (mobile != "") {
            content += '<p><strong>M:</strong>' + mobile + ' </p>';
        }
        if (email != "") {
            content += '<p><strong>E:</strong>&nbsp; ' + email + '</p>';
        }
        if (agent_name != "") {
            content += '<p><strong>&nbsp;&nbsp;&nbsp;</strong>&nbsp; &nbsp;' + agent_name + '</p>';
        }
        content += '</div>' +
            '<div class="rightPU"> ' +
            '<h3>' + openingLabel + '</h3>' +
            '<p>' + workinghours + ': ' + hours + '</p>' +
            '<p>' + workinghoursdaterange + '</p>' +
            '<a href="<?php echo $lang_base_url . '/printMapPopups/';?>' + branch_id + '" class="printBtn" target="_blank"><img src="<?php echo custom::baseurl('public/frontend/images/print.png'); ?>" alt="Print" width="15" height="14"> Print </a>' +
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

    //Setting Location with jQuery
    $(document).ready(function () {

        google.maps.event.addDomListener(window, 'load', initialize);

        $('.fleetDropDown#pickUp .locaListFix ul.pickup_branches_list li').on('click', function () {

            $('.fleetDropDown#pickUp .locaListFix ul.pickup_branches_list li').removeClass('active');
            $(this).addClass('active');

            $('.fleetDropDown#dropOff .locaListFix ul.dropoff_branches_list li').removeClass('active');
            $('.fleetDropDown#dropOff .locaListFix ul.dropoff_branches_list li a[data-branchid="' + $(this).find('a').data('branchid') + '"]').parent('li').addClass('active');

            $('.fleetDropDown').slideUp();
            $('.fleetDropDown').removeClass('mapShown backSandGrayPlus');
            $('.fleetDropDown .dropMapLoc').removeClass('open');
        });
        $('.fleetDropDown#dropOff .locaListFix ul.dropoff_branches_list li').on('click', function () {
            $('.fleetDropDown#dropOff .locaListFix ul.dropoff_branches_list li').removeClass('active');
            $(this).addClass('active');

            $('.fleetDropDown').slideUp();
            $('.fleetDropDown').removeClass('mapShown backSandGrayPlus');
            $('.fleetDropDown .dropMapLoc').removeClass('open');
        });

        if (medium == 'pc') {
            $('.showMapPointer').on('mouseover', function () {

                var loc_lat = $(this).data('loclat');
                var loc_long = $(this).data("loclong");
                var address1 = $(this).data("address1");
                var address2 = $(this).data("address2");
                var hours = $(this).data("hours");
                var hoursrange = $(this).data("hoursrange");
                var phone1 = $(this).data("phone1");
                var phone2 = $(this).data("phone2");
                var mobile = $(this).data("mobile");
                var email = $(this).data("email");
                var agent_name = $(this).data("agent");
                var branch_id = $(this).data("branch_id");

                setTimeout(function () {
                    // initialize(address1, address2, hours, hoursrange, phone1, phone2, mobile, email, agent_name, loc_lat, loc_long, branch_id);
                }, 500);
            });
        } else {
            $('.showMapPointer').on('touchstart', function () {

                var loc_lat = $(this).data('loclat');
                var loc_long = $(this).data("loclong");
                var address1 = $(this).data("address1");
                var address2 = $(this).data("address2");
                var hours = $(this).data("hours");
                var hoursrange = $(this).data("hoursrange");
                var phone1 = $(this).data("phone1");
                var phone2 = $(this).data("phone2");
                var mobile = $(this).data("mobile");
                var email = $(this).data("email");
                var agent_name = $(this).data("agent");
                var branch_id = $(this).data("branch_id");

                setTimeout(function () {
                    // initialize(address1, address2, hours, hoursrange, phone1, phone2, mobile, email, agent_name, loc_lat, loc_long, branch_id);
                }, 500);
            });
        }
    });

    /*=============================================
     search Result Function
     ============================================*/

    function isScrolledIntoView(elem) {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();

        var elemTop = $(elem).offset().top;
        var elemBottom = elemTop + $(elem).height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }


    function showMapFn(typeVal) {
        if ($(typeVal).parent('li').hasClass('pickup_mode_pickup') || $(typeVal).parent('li').hasClass('pickup_mode_dropoff')) {
            //alert($(typeVal).parent('li').attr('class'));
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
        } else if ($(typeVal).parent('li').hasClass('delivery_mode_pickup') || $(typeVal).parent('li').hasClass('delivery_mode_dropoff')) {
            var locationType = $(typeVal).prevAll("label").text();

            if ($(typeVal).hasClass("backSandGrayPlus")) {
                $('.fleetDropDown#dropOffDeliveryMode').addClass('active backSandGrayPlus');
                $('.fleetDropDown#dropOffDeliveryMode').slideDown('slow');
            } else {
                $('.fleetDropDown').removeClass('backSandGrayPlus');
                $('.fleetDropDown#pickUpDeliveryMode').slideDown('slow');
            }

        }


        // scrolling function
        if ((page == "" || page == "en" || page == "home") && medium == 'pc') {
            if (!isScrolledIntoView($('#forScrollHelp'))) {
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
        $('.pickupTab .locaListFix > ul > li > a').on('mouseover', function () {

            /*$('.pickupTab').removeClass('mapShown');
            $('.pickupTab .dropMapLoc').removeClass('open');
            $(this).closest('.pickupTab').addClass('mapShown');
            $('.pickupTab.mapShown .dropMapLoc').addClass('open');*/


        })

        $('.pickupTab .locaListFix ul li a').on('click', function () {


            var pickOrDropId = $(this).closest(".pickupTab").attr("id");
            console.log(pickOrDropId);
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

        $('.deliveryTab .locaListFix > ul > li > a').on('click', function () {


            var pickOrDropId = $(this).closest(".deliveryTab").attr("id");
            if (pickOrDropId == "pickUpDeliveryMode") {
                $("#from_region_id").val($(this).data("regionid"));
                $("#from_city_id").val($(this).data("cityid"));
                $("#from_branch_id").val($(this).data("branchid"));
                $("#to_city_id").val($(this).data("cityid"));
                $("#to_branch_id").val($(this).data("branchid"));

            } else if (pickOrDropId == "dropOffDeliveryMode") {
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
<style>
    .gm-style-iw {
        background-color: transparent;
        box-shadow: none;
    }
</style>
<?php

$site = custom::site_settings();
$sessionVals = Session::get('search_data');
//echo $sessionVals['allIsOkForPickup'];exit();
//echo $edit_booking_id;exit();
//echo '<pre>';print_r($sessionVals);
//echo $mod_id;exit();

    $AirportRegions = custom::getAirportRegions($lang);
    $airport_pickup_regions = $AirportRegions['airport_pickup_regions'];
    $airport_dropoff_regions = $AirportRegions['airport_dropoff_regions'];

?>
<script>
    var isDelMode = '<?php echo(isset($sessionVals['is_delivery_mode']) && $sessionVals['is_delivery_mode'] != "" ? $sessionVals['is_delivery_mode'] : 0); ?>';
    var isLimousine = '<?php echo(isset($sessionVals['isLimousine']) && $sessionVals['isLimousine'] == 1 ? 1 : 0); ?>';
</script>
<style>
    .pick-up-button{
        cursor: pointer;
        background-color: #ffff;
        padding: 5px 0px;
        text-align: center;
        border: 1px solid #000000;
        border-radius: 5px;
        color: #000000;
    }
    .pick-up-button.active{
        background-color: #fe7e00;
        color: #ffff;
        border: 1px solid #fe7e00;
    }


    .searchBarSec .serFormArea {
        float: unset;
        height: 50px;
        display: flex;
        align-items: end;
    }

    .search-main-container-new-design .searchBarSec {
        padding-bottom: 30px;
        padding-top: 10px;
    }

    @media screen and (max-width: 1200px) {
        .searchBarSec .serFormArea {
            height: auto;

        }
    }

</style>

@if($siteSettings->daily_with_delivery_flow == 'on' || $siteSettings->monthly_with_delivery_flow == 'on' || $siteSettings->weekly_with_delivery_flow == 'on' || $siteSettings->subscription_with_delivery_flow == 'on')
    <div class="row ms-3 me-3 pb-4 subscription_with_delivery_flow_tabs">
        <div class="col-lg-2 col-5 pick-up-button pickup <?php echo(isset($sessionVals['is_subscription_with_delivery_flow']) && $sessionVals['is_subscription_with_delivery_flow'] == 0 ? 'active' : ''); ?>">
            {{$lang == 'eng' ? 'Pickup' : 'استلام'}}
        </div>
        <div class="col-lg-2 col-5 pick-up-button ms-3 me-3 delivery <?php echo(isset($sessionVals['is_subscription_with_delivery_flow']) && $sessionVals['is_subscription_with_delivery_flow'] == 1 ? 'active' : ''); ?>">
            {{$lang == 'eng' ? 'Delivery' : 'توصيل'}}
        </div>
    </div>
@endif
@if($siteSettings->limousine_mode == 'on')
    <div class="row ms-3 me-3 pb-4 limousine_mode_sub_tabs" style="display: none;">
        <div class="col-lg-2 col-5 pick-up-button round-trip active">
            {{$lang == 'eng' ? 'Round Trip' : 'رحلة ذهاب وعودة'}}
        </div>
        <div class="col-lg-2 col-5 pick-up-button ms-3 me-3 one-way">
            {{$lang == 'eng' ? 'One Way' : 'رحلة ذهاب فقط'}}
        </div>
    </div>
@endif
<form class="serFormArea search-new-design" action="<?php echo $lang_base_url; ?>/search-results" method="post" style="display: none;">
    <?php if(isset($mod_id) && $mod_id != ""){

    // if (isset($show_mod_id) && $show_mod_id) commented this line because Fozan said to show car id on search, if any issue occur, uncomment this line and remove below line
    if ((isset($show_mod_id) && $show_mod_id) || (isset($mod_id) && $mod_id != ""))
    { ?>
    <input type="hidden" id="mod_id" name="mod_id" value="<?php echo $mod_id; ?>">
    <?php } } ?>

        <input type="hidden" id="is_subscription_with_delivery_flow" name="is_subscription_with_delivery_flow" value="<?php echo (isset($sessionVals['is_subscription_with_delivery_flow']) ? $sessionVals['is_subscription_with_delivery_flow'] : 0); ?>">

    <input type="hidden" id="from_region_id" name="from_region_id"
           value="<?php echo (isset($sessionVals['from_region_id']) ? $sessionVals['from_region_id'] : ""); ?>">
    <input type="hidden" id="from_city_id" name="from_city_id" value="<?php echo (isset($sessionVals['from_city_id']) ? $sessionVals['from_city_id'] : ""); ?>">
    <input type="hidden" id="from_branch_id" name="from_branch_id"
           value="<?php echo (isset($sessionVals['from_branch_id']) ? $sessionVals['from_branch_id'] : ""); ?>">

    <input type="hidden" id="to_city_id" name="to_city_id" value="<?php echo (isset($sessionVals['to_city_id']) ? $sessionVals['to_city_id'] : ""); ?>">
    <input type="hidden" id="to_branch_id" name="to_branch_id" value="<?php echo (isset($sessionVals['to_branch_id']) ? $sessionVals['to_branch_id'] : ""); ?>">

    <input type="hidden" class="isDeliveryMode" name="is_delivery_mode"
           value="<?php echo(isset($sessionVals['is_delivery_mode']) && $sessionVals['is_delivery_mode'] != '' ? $sessionVals['is_delivery_mode'] : 0); ?>">
    <input type="hidden" class="pickup_delivery_coordinate" name="pickup_delivery_coordinate"
           value="<?php echo(isset($sessionVals['pickup_delivery_coordinate']) && $sessionVals['pickup_delivery_coordinate'] != '' ? $sessionVals['pickup_delivery_coordinate'] : '') ?>">
    <input type="hidden" class="dropoff_delivery_coordinate" name="dropoff_delivery_coordinate"
           value="<?php echo(isset($sessionVals['dropoff_delivery_coordinate']) && $sessionVals['dropoff_delivery_coordinate'] != '' ? $sessionVals['dropoff_delivery_coordinate'] : '') ?>">
    <input type="hidden" class="allIsOkForPickup" name="allIsOkForPickup"
           value="<?php echo(isset($sessionVals['allIsOkForPickup']) && $sessionVals['allIsOkForPickup'] != '' ? $sessionVals['allIsOkForPickup'] : 0); ?>">
    <input type="hidden" class="allIsOkForDropoff" name="allIsOkForDropoff"
           value="<?php echo(isset($sessionVals['allIsOkForDropoff']) && $sessionVals['allIsOkForDropoff'] != '' ? $sessionVals['allIsOkForDropoff'] : 0); ?>">
    <input type="hidden" id="delivery_charges" name="delivery_charges"
           value="<?php echo(isset($sessionVals['delivery_charges']) && $sessionVals['delivery_charges'] != '' ? $sessionVals['delivery_charges'] : 0); ?>">
        <input type="hidden" class="isLimousine" name="isLimousine"
               value="<?php echo(isset($sessionVals['isLimousine']) && $sessionVals['isLimousine'] != '' ? $sessionVals['isLimousine'] : 0); ?>">
        <input type="hidden" class="isRoundTripForLimousine" name="isRoundTripForLimousine"
               value="<?php echo(isset($sessionVals['isRoundTripForLimousine']) && $sessionVals['isRoundTripForLimousine'] != '' ? $sessionVals['isRoundTripForLimousine'] : 1); ?>">


    <ul>

        <!-- Input fields for delivery mode -->
        <li class="big delivery_mode_pickup">
            <label><?php echo($lang == 'eng' ? 'Delivery Location' : 'مكان التوصيل'); ?></label>
            <input type="text"
                   class="backLocation showMapFn from_branch_field_for_delivery required-for-search filterBranchesFromForDelivery"
                   id="myInputFromForDelivery"
                   name="from_branch_name" value="<?php echo isset($sessionVals['from_branch_name'])?$sessionVals['from_branch_name']:''; ?>"
                   placeholder="<?php echo($lang == 'eng' ? 'Select Delivery Location' : 'حدد موقع التوصيل'); ?>"
                   onclick="getCurrentLocation();"/>
            <div id="pickUpDeliveryMode" class="fleetDropDown deliveryTab">
                <div class="locaListFix mCustomScrollbar">
                    <ul id="myULFromForDelivery">
                        <?php //echo '<pre>';print_r($pickup_regions);exit(); ?>
                        <?php foreach ($delivery_pickup_regions as $key => $region){ ?>
                        <?php $from_city_data = explode('|', $key); ?>
                        <li class="filterDataParentFrom" id="from_city_<?php echo $from_city_data[0]; ?>">

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
                        <li class="filterDataFromForDelivery" id="from_branch_<?php echo $reg->id; ?>"
                            data-val_id="<?php echo $reg->id; ?>"><a class="showMapPointer"
                                                                     onclick="$('.from_branch_field_for_delivery').val($(this).text());openGoogleMapPopup('<?php echo trim($lat); ?>','<?php echo trim($long); ?>','<?php echo $reg->id; ?>', 'pickup', '<?php echo $reg->delivery_charges; ?>');"
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
                <div class="clearfix"></div>
            </div>
        </li>
        <li class="big delivery_mode_dropoff"><!-- 	 hidden-sm hidden-xs		-->
            <label><?php echo ($lang == 'eng' ? 'Returning Location' : 'مكان الإرجاع'); ?></label>
            <input type="text"
                   class="backSandGrayPlus backLocation showMapFn to_branch_field_for_delivery to_branch_field filterBranchesToForDelivery"
                   id="myInputToForDelivery"
                   name="to_branch_name" value="<?php echo ($lang == 'eng' ? 'Return will be to the nearest branch' : 'يتم تسليم السيارة في اقرب فرع'); ?>"
                   placeholder="<?php echo ($lang == 'eng' ? 'Return will be to the nearest branch' : 'يتم تسليم السيارة في اقرب فرع'); ?>"
                    {{--onclick="showMapFn(this)"--}}/>
            <div id="dropOffDeliveryMode" class="fleetDropDown deliveryTab">
                <div class="locaListFix mCustomScrollbar">
                    <ul id="myULToForDelivery">
                        <?php foreach ($delivery_dropoff_regions as $key => $region){ ?>
                        <?php $to_city_data = explode('|', $key); ?>
                        <li class="filterDataParentTo" id="to_city_<?php echo $to_city_data[0]; ?>">
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
                        <li class="filterDataTo toBid_<?php echo $reg->id; ?>" id="to_branch_<?php echo $reg->id; ?>"
                            data-val_id="<?php echo $reg->id; ?>"><a
                                    href="javascript:void(0);" class="showMapPointer"
                                    onclick="$('.to_branch_field_for_delivery').val($(this).text());openGoogleMapPopup('<?php echo trim($lat); ?>','<?php echo trim($long); ?>','<?php echo $reg->id; ?>', 'dropoff', '<?php echo $reg->delivery_charges; ?>');"
                                    data-loclat="<?php echo str_replace(" ","",$lat); ?>"
                                    data-loclong="<?php echo str_replace(" ","",$long); ?>"
                                    data-regionid="<?php echo $reg->region_id; ?>"
                                    data-cityid="<?php echo $reg->city_id; ?>" data-branchid="<?php echo $reg->id; ?>"
                                    data-address1="<?php echo $reg->address_line_1;?>"
                                    data-address2="<?php echo $reg->address_line_2;?>"
                                    data-hours="<?php echo $reg->opening_hours;?>"
                                    data-hoursrange="<?php echo $reg->opening_hours_date_range;?>"
                                    data-phone1="<?php echo $reg->phone1;?>" data-phone2="<?php echo $reg->phone2;?>"
                                    data-mobile="<?php echo $reg->mobile;?>" data-agent="<?php echo $reg->agent_name;?>"
                                    data-branch_id="<?php echo $reg->id;?>"
                                    data-email="<?php echo $reg->email;?>"><?php echo($lang == 'eng' ? $reg->eng_title : $reg->arb_title); ?></a>
                        </li>
                        <?php
                        }
                        } ?>

                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </li>

        <!-- Input fields for pickup mode -->
        <li class="big pickup_mode_pickup hide">
            <label>@lang('labels.pickup')</label>
            <input type="text"
                   class="backLocation showMapFn from_branch_field_for_pickup  from_branch_field filterBranchesFromForPickup"
                   id="myInputFromForPickup"
                   value="<?php echo ((isset($sessionVals['from_branch_id']) && $sessionVals['from_branch_id'] != '')?custom::get_branch_name($sessionVals['from_branch_id'],$lang):''); ?>"
                   placeholder="@lang('labels.select_search_bar')"
                   onclick="showMapFn(this)" readonly/>
        </li>
        <li class="big pickup_mode_dropoff none"><!-- 	 hidden-sm hidden-xs		-->
            <label>@lang('labels.dropoff')</label>
            <input type="text"
                   class="backSandGrayPlus backLocation showMapFn to_branch_field_for_pickup to_branch_field filterBranchesToForPickup"
                   id="myInputToForPickup"
                   value="<?php echo ((isset($sessionVals['to_branch_id']) && $sessionVals['to_branch_id'] != '')?custom::get_branch_name($sessionVals['to_branch_id'],$lang):''); ?>"
                   placeholder="@lang('labels.select_search_bar')"
                   onclick="showMapFn(this)" readonly/>
        </li>

        <li class="big limousine_mode_pickup hide">
            <label>@lang('labels.pickup')</label>
            <input type="text"
                   class="backLocation from_branch_field_for_pickup  from_branch_field"
                   value="<?php echo ((isset($sessionVals['from_branch_id']) && $sessionVals['from_branch_id'] != '')?custom::get_branch_name($sessionVals['from_branch_id'],$lang):''); ?>"
                   placeholder="@lang('labels.select_search_bar')"
                   onclick="$('#limousineModePickupBranchesModal').modal('show');" readonly/>
        </li>
        <li class="big limousine_mode_dropoff hide"><!-- 	 hidden-sm hidden-xs		-->
            <label>@lang('labels.dropoff')</label>
            <input type="text"
                   class="backSandGrayPlus backLocation to_branch_field_for_pickup to_branch_field"
                   value="<?php echo ((isset($sessionVals['to_branch_id']) && $sessionVals['to_branch_id'] != '')?custom::get_branch_name($sessionVals['to_branch_id'],$lang):''); ?>"
                   placeholder="@lang('labels.select_search_bar')"
                   onclick="$('#limousineModeDropoffBranchesModal').modal('show');" readonly/>
        </li>

        <li class="small pickup_time_sec">
            <label>@lang('labels.pickup_schedule')</label>
            <input type="text" class="backCalendar required-for-search notranslate" placeholder="" name="pickup_date"
                   id="datepicker_from"
                   value="<?php if (isset($sessionVals['pickup_date']) && $sessionVals['pickup_date'] != '') echo date('d-m-Y', strtotime($sessionVals['pickup_date'])); ?>"/>
            <select data-check="<?php if (isset($sessionVals['pickup_time']) && $sessionVals['pickup_time'] != '') echo $sessionVals['pickup_time']; ?>" id="pickUpTime" name="pickup_time" class="backSandGrayPlus backClock required-for-search time-select-2-box notranslate">
                <?php
                $start=strtotime('00:00');
                $end=strtotime('23:30');
                for ($i=$start;$i<=$end;$i = $i + 30*60){
                $timeInterval = date('H:i',$i);
                $selectedTime = isset($sessionVals['pickup_time']) && $sessionVals['pickup_time'] == $timeInterval ? 'selected':'';
                $selectedTime2 = isset($sessionVals['pickup_time']) && $sessionVals['pickup_time'] == '' && $timeInterval == '09:00'? 'selected':'';
                ?>
                <option value="<?php echo $timeInterval; ?>" <?php echo $selectedTime.' '.$selectedTime2; ?>><?php echo $timeInterval; ?></option>
                <?php } ?>
            </select>
        </li>
        <li class="small li_for_others dropoff_time_sec">
            <label>@lang('labels.dropoff_schedule')</label>
            <input type="text" class="backCalendar required-for-search notranslate" placeholder="" name="dropoff_date"
                   id="datepicker_to"
                   value="<?php if (isset($sessionVals['pickup_date']) && $sessionVals['pickup_date'] != '') echo date('d-m-Y', strtotime($sessionVals['dropoff_date'])); ?>"/>
            <select id="dropOffTime" name="dropoff_time" class="backSandGrayPlus backClock required-for-search time-select-2-box notranslate">
                <?php
                $start=strtotime('00:00');
                $end=strtotime('23:30');
                for ($i=$start;$i<=$end;$i = $i + 30*60){
                $timeInterval = date('H:i',$i);
                $selectedTime = isset($sessionVals['dropoff_time']) && $sessionVals['dropoff_time'] == $timeInterval ? 'selected':'';
                $selectedTime2 = isset($sessionVals['pickup_time']) && $sessionVals['pickup_time'] == '' && $timeInterval == '09:00'? 'selected':'';
                ?>
                <option value="<?php echo $timeInterval; ?>" <?php echo $selectedTime.' '.$selectedTime2; ?>><?php echo $timeInterval; ?></option>
                <?php } ?>
            </select>
        <!--<input id="dropOffTime" type="text" class="backSandGrayPlus timepicker required-for-search" name="dropoff_time"
                   value="<?php //echo $sessionVals['dropoff_time']; ?>"/>-->
        </li>
        <li class="small li_for_hourly_only" style="display: none;">
            <label>@lang('labels.book_for_hours')</label>
            <select id="book_for_hours" name="book_for_hours" class="backSandGrayPlus time-select-2-box" style="width: 100%;height: 45px;border: 0;background-color: #dfdfd0;">
                <?php for ($i = 2; $i <= 5; $i++){
                    $selected = '';
                    if (isset($sessionVals['book_for_hours']) && $sessionVals['book_for_hours'] > 0 && $i == $sessionVals['book_for_hours']) {
                        $selected = 'selected';
                    } elseif ($i == 2) {
                        $selected = 'selected';
                    }
                    ?>
                <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?> @lang('labels.hours')</option>
                <?php } ?>
            </select>
        </li>
        <li class="small li_for_subscription_only" style="display: none;">
            <label>@lang('labels.subscribe_for_months')</label>
            <select id="subscribe_for_months" name="subscribe_for_months" class="backSandGrayPlus time-select-2-box" style="width: 100%;height: 45px;border: 0;background-color: #dfdfd0;">
                <?php
                $subscribe_for_months = [3,6,9,12];
                foreach($subscribe_for_months as $for_month){
                $selected = '';
                if (isset($sessionVals['subscribe_for_months']) && $sessionVals['subscribe_for_months'] > 0 && $for_month == $sessionVals['subscribe_for_months']) {
                    $selected = 'selected';
                } elseif ($for_month == 1) {
                    $selected = 'selected';
                }
                    ?>
                <option value="<?php echo $for_month; ?>" <?php echo $selected; ?>><?php echo $for_month; ?> @lang('labels.months')</option>
                <?php } ?>
            </select>
        </li>
        <?php if (Session::get('customer_id_no_for_loyalty') != '')
        { ?>
        <input type="hidden" name="id_no_for_loyalty" value="<?php echo Session::get('customer_id_no_for_loyalty'); ?>">
        <?php } ?>
        <li class="submitBtn add">
            <?php if ($site->maintenance_mode == 'on')
            { ?>
            <input type="button" class="serchhomeBtn add" value="@lang('labels.search')"
                   onclick="siteUnderMaintenance();"/>
            <?php }else{ ?>
            <input type="button" class="serchhomeBtn add" id="serchhomeBtn" value="@lang('labels.search')"/>
            <?php } ?>
        </li>
    </ul>
    <div id="pickUp" class="fleetDropDown pickupTab dropdown-new-tabs">
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
                                <div class="col-md-8 col-12">
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
                                                                                                 onclick="$('.from_branch_field_for_pickup').val($(this).text());$('.to_branch_field_for_pickup').val($(this).text());"
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
                                                                                                 onclick="$('.from_branch_field_for_pickup').val($(this).text());$('.to_branch_field_for_pickup').val($(this).text());"
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
                                <div class="col-md-4 col-12">
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
    <div id="dropOff" class="fleetDropDown pickupTab">
        <div class="locaListFix mCustomScrollbar">
            <div class="dropdown-tabs">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <ul class="nav nav-tabs tabs-left sideways">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#dropoff-airports"><?php echo ($lang == 'eng' ? 'Airports' : 'المطارات'); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#dropoff-all-ranches"><?php echo ($lang == 'eng' ? 'All Branches' : 'جميع الفروع'); ?></a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-8">
                                    <div class="tabs-parent-box">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="dropoff-airports">
                                                <ul class="dropoff_branches_list" id="myULToForPickup">
                                                    <?php foreach ($airport_dropoff_regions as $key => $region){ ?>
                                                    <?php $to_city_data = explode('|', $key); ?>

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
                                                    <li class="filterDataTo" id="to_branch_<?php echo $reg->id; ?>" data-val_id="<?php echo $reg->id; ?>"><a
                                                                href="javascript:void(0);" class="showMapPointer"
                                                                onclick="$('.to_branch_field_for_pickup').val($(this).text());"
                                                                data-loclat="<?php echo str_replace(" ","",$lat); ?>"
                                                                data-loclong="<?php echo str_replace(" ","",$long); ?>"
                                                                data-regionid="<?php echo $reg->region_id; ?>"
                                                                data-cityid="<?php echo $reg->city_id; ?>" data-branchid="<?php echo $reg->id; ?>"
                                                                data-address1="<?php echo $reg->address_line_1;?>"
                                                                data-address2="<?php echo $reg->address_line_2;?>"
                                                                data-hours="<?php echo $reg->opening_hours;?>"
                                                                data-hoursrange="<?php echo $reg->opening_hours_date_range;?>"
                                                                data-phone1="<?php echo $reg->phone1;?>" data-phone2="<?php echo $reg->phone2;?>"
                                                                data-mobile="<?php echo $reg->mobile;?>" data-agent="<?php echo $reg->agent_name;?>"
                                                                data-branch_id="<?php echo $reg->id;?>"
                                                                data-email="<?php echo $reg->email;?>"><?php echo($lang == 'eng' ? $reg->eng_title : $reg->arb_title); ?></a>


                                                        <div class="filterDataParentTo" id="to_city_<?php echo $to_city_data[0]; ?>">
                                                            <h3><?php echo $to_city_data[1]; ?></h3></div>
                                                    </li>
                                                    <?php
                                                    }
                                                    } ?>

                                                </ul>
                                            </div>
                                            <div class="tab-pane" id="dropoff-all-ranches">
                                                <ul class="dropoff_branches_list" id="myULToForPickup">
                                                    <?php foreach ($dropoff_regions as $key => $region){ ?>
                                                    <?php $to_city_data = explode('|', $key); ?>
                                                    <li class="filterDataParentTo" id="to_city_<?php echo $to_city_data[0]; ?>">
                                                        <h3><?php echo $to_city_data[1]; ?></h3></li>
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
                                                    <li class="filterDataTo" id="to_branch_<?php echo $reg->id; ?>" data-val_id="<?php echo $reg->id; ?>"><a
                                                                href="javascript:void(0);" class="showMapPointer"
                                                                onclick="$('.to_branch_field_for_pickup').val($(this).text());"
                                                                data-loclat="<?php echo str_replace(" ","",$lat); ?>"
                                                                data-loclong="<?php echo str_replace(" ","",$long); ?>"
                                                                data-regionid="<?php echo $reg->region_id; ?>"
                                                                data-cityid="<?php echo $reg->city_id; ?>" data-branchid="<?php echo $reg->id; ?>"
                                                                data-address1="<?php echo $reg->address_line_1;?>"
                                                                data-address2="<?php echo $reg->address_line_2;?>"
                                                                data-hours="<?php echo $reg->opening_hours;?>"
                                                                data-hoursrange="<?php echo $reg->opening_hours_date_range;?>"
                                                                data-phone1="<?php echo $reg->phone1;?>" data-phone2="<?php echo $reg->phone2;?>"
                                                                data-mobile="<?php echo $reg->mobile;?>" data-agent="<?php echo $reg->agent_name;?>"
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
                                <div class="col-4">
                                    <div class="branch-timing-main-box">
                                        <div class="branch-time-content-box to-branch-timings" style="display: none;">
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
                                        <div class="branch-time-content-box to-branch-location" style="display: none;">
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
            <div style="" id="map-canvas-dropoff"></div>
        </div>
        <div class="clearfix"></div>
    </div>

    <!-- Delivery mode dropdowns -->


</form>
<!-- Below code is here because we need to hardcode the values for delivery mode and all ok only on home page, so on other pages we can use the selected values -->
<!-- --------------------------------------- -->
<?php $site = custom::site_settings(); ?>
<?php $isDelMode = (isset($sessionVals['is_delivery_mode']) && $sessionVals['is_delivery_mode'] != "" ? $sessionVals['is_delivery_mode'] : 0); ?>
<script>
    <?php if (isset($has_searchbar))
    {
    if ($site->delivery_mode == 'off') { ?>
    $(".isDeliveryMode").val(0);
    $(".allIsOkForPickup").val(1);
    $(".allIsOkForDropoff").val(1);

    $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');
    $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');

    $(".from_branch_field_for_pickup").attr("name", "from_branch_name");
    $(".to_branch_field_for_pickup").attr("name", "to_branch_name");
    $(".from_branch_field_for_pickup, .to_branch_field_for_pickup").addClass("required-for-search");

    $(".from_branch_field_for_delivery").removeAttr("name");
    $(".to_branch_field_for_delivery").removeAttr("name");
    $(".from_branch_field_for_delivery").removeClass("required-for-search");
    <?php } else if ($site->delivery_mode == 'on') { ?>
    $('.isDeliveryMode').val(1);
    $('.allIsOkForPickup').val(0);
    $('.allIsOkForDropoff').val(0);

    $('.pickup_mode_pickup, .pickup_mode_dropoff').addClass('hide');
    $('.delivery_mode_pickup, .delivery_mode_dropoff').removeClass('hide');

    $('.from_branch_field_for_delivery').attr('name', 'from_branch_name');
    $('.to_branch_field_for_delivery').attr('name', 'to_branch_name');
    $('.from_branch_field_for_delivery').addClass('required-for-search');

    $('.from_branch_field_for_pickup').removeAttr('name');
    $('.to_branch_field_for_pickup').removeAttr('name');
    $('.from_branch_field_for_pickup, .to_branch_field_for_pickup').removeClass('required-for-search');
    <?php } ?>
    <?php }else{
    if ($isDelMode == '1') // checking from session for inner pages
    { ?>
    $('.pickup_mode_pickup, .pickup_mode_dropoff').addClass('hide');
    $('.delivery_mode_pickup, .delivery_mode_dropoff').removeClass('hide');
    <?php }else{ ?>
    $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');
    $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');
    <?php }
    }?>
    $(document).ready(function() {
        $('.time-select-2-box').select2({
            minimumResultsForSearch: -1
        });

    });
</script>
<!-- --------------------------------------- -->

<script>
    $(document).ready(function () {
        setTimeout(function () {
            <?php
            if (!isset($sessionVals['is_delivery_mode'])) { ?>
            // $('.searchButtons').find('ul li:visible:first').click();
            $('.searchButtons').find('ul li#pickup_tab').click();
            <?php } else { ?>
            if (isDelMode == 1) {
                $('.searchButtons').find('ul li#delivery_tab').click();
            } else if (isDelMode == 0) {
                if (isLimousine == 1) {
                    $('.searchButtons').find('ul li#limousine_mode_tab').click();
                } else {
                    $('.searchButtons').find('ul li#pickup_tab').click();
                }
            } else if (isDelMode == 2) {
                $('.searchButtons').find('ul li#hourly_renting_tab').click();
            } else if (isDelMode == 3) {
                $('.searchButtons').find('ul li#monthly_renting_tab').click();
            } else if (isDelMode == 4) {
                $('.searchButtons').find('ul li#subscription_renting_tab').click();
            }
            setFormFieldVals();
            <?php } ?>
            $('.serFormArea').show();
        }, 250);
    });


    function setFormFieldVals() {
        $('input[name="from_branch_name"]').val("<?php echo isset($sessionVals['from_branch_name']) ? $sessionVals['from_branch_name'] : ''; ?>");
        $('input[name="to_branch_name"]').val("<?php echo isset($sessionVals['to_branch_name']) ? $sessionVals['to_branch_name'] : ''; ?>");
        $('input[name="from_region_id"]').val("<?php echo isset($sessionVals['from_region_id']) ? $sessionVals['from_region_id'] : ''; ?>");
        $('input[name="from_city_id"]').val("<?php echo isset($sessionVals['from_city_id']) ? $sessionVals['from_city_id'] : ''; ?>");
        $('input[name="from_branch_id"]').val("<?php echo isset($sessionVals['from_branch_id']) ? $sessionVals['from_branch_id'] : ''; ?>");
        $('input[name="to_city_id"]').val("<?php echo isset($sessionVals['to_city_id']) ? $sessionVals['to_city_id'] : ''; ?>");
        $('input[name="to_branch_id"]').val("<?php echo isset($sessionVals['to_branch_id']) ? $sessionVals['to_branch_id'] : ''; ?>");
        $('input[name="is_delivery_mode"]').val("<?php echo isset($sessionVals['is_delivery_mode']) ? $sessionVals['is_delivery_mode'] : ''; ?>");
        $('input[name="pickup_delivery_coordinate"]').val("<?php echo isset($sessionVals['pickup_delivery_coordinate']) ? $sessionVals['pickup_delivery_coordinate'] : ''; ?>");
        $('input[name="dropoff_delivery_coordinate"]').val("<?php echo isset($sessionVals['dropoff_delivery_coordinate']) ? $sessionVals['dropoff_delivery_coordinate'] : ''; ?>");
        $('input[name="allIsOkForPickup"]').val("<?php echo isset($sessionVals['allIsOkForPickup']) ? $sessionVals['allIsOkForPickup'] : 0; ?>");
        $('input[name="allIsOkForDropoff"]').val("<?php echo isset($sessionVals['allIsOkForDropoff']) ? $sessionVals['allIsOkForDropoff'] : 0; ?>");
        $('input[name="delivery_charges"]').val("<?php echo isset($sessionVals['delivery_charges']) ? $sessionVals['delivery_charges'] : 0; ?>");
    }

    function setSearchFormTabsWidth() {
        if ($('.searchButtons ul li').length == 1) {
            $('.searchButtons').hide();
        } else {
            $('.searchButtons ul li').each(function () {
                $(this).css('width', 'calc(100% /' + ($('.searchButtons ul li').length) + ')');
            });
        }
    }

    setSearchFormTabsWidth();

    /*--------------------Working on getting current location for nearest delivery branch logic-------------------*/

    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(getCurrentLocationSuccess, getCurrentLocationError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function getCurrentLocationSuccess(position) {
        // we will send ajax call here to get the nearest branch depending upon these current latitude and longitude coordinates
        if (position.coords.latitude > 0 && position.coords.longitude > 0) {
            $('.loaderSpiner').show();
            $.ajax({
                type: 'POST',
                url: lang_base_url + '/fetch_nearest_delivery_branch',
                dataType: 'JSON',
                data: {'current_latitude': position.coords.latitude, 'current_longitude': position.coords.longitude},
                success: function (response) {
                    $('.loaderSpiner').hide();
                    if (response.status == true) {

                        // putting values in pickup fields
                        $("#from_region_id").val(response.branch.region_id);
                        $("#from_city_id").val(response.branch.city_id);
                        $("#from_branch_id").val(response.branch.branch_id);

                        // putting values in dropoff fields
                        $("#to_city_id").val(response.branch.city_id);
                        $("#to_branch_id").val(response.branch.branch_id);

                        // opening map popup according to user current location
                        openGoogleMapPopup(position.coords.latitude, position.coords.longitude, response.branch.branch_id, 'pickup', response.branch.branch_delivery_charges);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }

    function getCurrentLocationError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("Request denied to get location details.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location details are unavailable.");
                break;
            case error.TIMEOUT:
                alert("The request to get your location details timed out.");
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
        }
    }

    $('.pickup_branches_list li').mouseover(function() {
        var hours = $(this).find('a').data('hours');
        var latitude = $(this).find('a').data('loclat');
        var longitude = $(this).find('a').data('loclong');
        var location_map_link = 'https://www.google.com/maps/search/?api=1&query='+latitude+',' + longitude;

        // setting time in from branch
        $('.from-branch-timings').find('.branch-time-text-box').find('h5').text(hours);
        $('.from-branch-timings').show();

        // setting time in to branch
        $('.to-branch-timings').find('.branch-time-text-box').find('h5').text(hours);
        $('.to-branch-timings').show();

        // setting location in from branch
        $('.from-branch-location').find('.branch-time-text-box').find('a').attr('href', location_map_link);
        $('.from-branch-location').show();

        // setting location in to branch
        $('.to-branch-location').find('.branch-time-text-box').find('a').attr('href', location_map_link);
        $('.to-branch-location').show();
    });

    $('.dropoff_branches_list li').mouseover(function() {
        var hours = $(this).find('a').data('hours');
        var latitude = $(this).find('a').data('loclat');
        var longitude = $(this).find('a').data('loclong');
        var location_map_link = 'https://www.google.com/maps/search/?api=1&query='+latitude+',' + longitude;

        // setting time in to branch
        $('.to-branch-timings').find('.branch-time-text-box').find('h5').text(hours);
        $('.to-branch-timings').show();

        // setting location in to branch
        $('.to-branch-location').find('.branch-time-text-box').find('a').attr('href', location_map_link);
        $('.to-branch-location').show();
    });

    $(function () {
        $('.daterange_picker').daterangepicker({
            opens: 'left'
        }, function (start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    });
</script>