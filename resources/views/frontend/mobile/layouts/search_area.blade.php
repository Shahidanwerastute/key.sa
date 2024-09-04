<?php
$site = custom::site_settings();
?>
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

    // google.maps.event.addDomListener(window, 'load', initialize);

    //Setting Location with jQuery
    $(document).ready(function () {

        $('.fleetDropDown#pickUp .locaListFix ul li a').on('click', function () {
            $('.fleetDropDown#pickUp .locaListFix ul li a').removeClass('active');
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
                    initialize(address1, address2, hours, hoursrange, phone1, phone2, mobile, email, agent_name, loc_lat, loc_long, branch_id);
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
                    initialize(address1, address2, hours, hoursrange, phone1, phone2, mobile, email, agent_name, loc_lat, loc_long, branch_id);
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
                //$('.fleetDropDown#dropOff').slideDown('slow');

                pickOrDrop = "map-canvas-dropoff";

            } else {
                $('.fleetDropDown').removeClass('backSandGrayPlus');
                if ($(".fleetDropDown#pickUp").hasClass("mapShown")) {
                    $('.fleetDropDown#dropOff').removeClass('mapShown');
                    $('.fleetDropDown#dropOff .dropMapLoc').removeClass('open');
                }
                clossingFun();
                $('.fleetDropDown#pickUp').addClass('active');
                //$('.fleetDropDown#pickUp').slideDown('slow');

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

            $('.pickupTab').removeClass('mapShown');
            $('.pickupTab .dropMapLoc').removeClass('open');
            $(this).closest('.pickupTab').addClass('mapShown');
            $('.pickupTab.mapShown .dropMapLoc').addClass('open');


        })

        $('.pickupTab .locaListFix > ul > li > a').on('click', function () {


            var pickOrDropId = $(this).closest(".pickupTab").attr("id");
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
<style type="text/css">
    .dp-highlight .ui-state-default {
        background: #484;
        color: #FFF;
    }
    .ui-datepicker.ui-datepicker-multi  {
        width: 100% !important;
    }
    .ui-datepicker-multi .ui-datepicker-group {
        float:none;
    }
    #datepicker {
        height: 300px;
        overflow-x: scroll;
    }
    .ui-widget { font-size: 100% }

    .hourlyMode {
        float: none !important;
        width: 100% !important;
    }
</style>
<?php

$site = custom::site_settings();
$sessionVals = Session::get('search_data');
//echo $sessionVals['allIsOkForPickup'];exit();
//echo $edit_booking_id;exit();
//echo '<pre>';print_r($sessionVals);exit;
//echo $mod_id;exit();
?>
<script>
    var isDelMode = '<?php echo( isset($sessionVals['is_delivery_mode']) && $sessionVals['is_delivery_mode'] != "" ? $sessionVals['is_delivery_mode'] : 0); ?>';
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
    }

    .search-main-container-new-design .searchBarSec {
        padding-bottom: 30px;
        padding-top: 10px;
    }
.pick-up-mobile-h-text h5{
    text-align: center;
    color: #fe7e00;
    text-transform: uppercase;
}


</style>
<?php
    $key = '';
    if (isset($_REQUEST['pickup'])) {
        $key = 'pickup';
    }

    if (isset($_REQUEST['monthly'])) {
        $key = 'monthly';
    }

    if (isset($_REQUEST['weekly'])) {
        $key = 'weekly';
    }

    if (isset($_REQUEST['subscription'])) {
        $key = 'subscription';
    }
?>

@if(
    (isset($_REQUEST['pickup']) && $siteSettings->daily_with_delivery_flow == 'on') ||
    (isset($_REQUEST['monthly']) && $siteSettings->monthly_with_delivery_flow == 'on') ||
    (isset($_REQUEST['weekly']) && $siteSettings->weekly_with_delivery_flow == 'on') ||
    (isset($_REQUEST['subscription']) && $siteSettings->subscription_with_delivery_flow == 'on')
    )
    <div class="row justify-content-center mt-3 pb-4 subscription_with_delivery_flow_tabs pick-up-mobile-h-text">

        <h5>{{$lang == 'eng' ? 'Select pickup Type' : 'اختر نوع الاستلام'}}</h5>
            <div class="col-5 mt-2 pick-up-button pickup {{isset($_REQUEST['t']) && $_REQUEST['t'] == 'pickup' ? 'active' : ''}}" onclick="window.location.href = '<?php echo $lang_base_url . '?'.$key.'=1&t=pickup'; ?>';">
                {{$lang == 'eng' ? 'Pickup' : 'استلام'}}
            </div>
            <div class="col-5 mt-2 pick-up-button ms-3 me-3 delivery {{isset($_REQUEST['t']) && $_REQUEST['t'] == 'delivery' ? 'active' : ''}}" onclick="window.location.href = '<?php echo $lang_base_url . '?'.$key.'=1&t=delivery'; ?>';">
                {{$lang == 'eng' ? 'Delivery' : 'توصيل'}}
            </div>
    </div>
@endif

<form class="serFormArea" action="<?php echo $lang_base_url; ?>/search-results" method="post" style="display: none;">
    <?php if(isset($mod_id) && $mod_id != ""){

    // if (isset($show_mod_id) && $show_mod_id) commented this line because Fozan said to show car id on search, if any issue occur, uncomment this line and remove below line
    if ((isset($show_mod_id) && $show_mod_id) || (isset($mod_id) && $mod_id != "")){ ?>
        <input type="hidden" id="mod_id" name="mod_id" value="<?php echo $mod_id; ?>">
    <?php } } ?>

        <input type="hidden" id="is_subscription_with_delivery_flow" name="is_subscription_with_delivery_flow" value="{{(isset($_REQUEST['pickup']) || isset($_REQUEST['monthly']) || isset($_REQUEST['weekly']) || isset($_REQUEST['subscription'])) && isset($_REQUEST['t']) && $_REQUEST['t'] == 'delivery' ? 1 : 0}}">

    <input type="hidden" id="from_region_id" name="from_region_id" value="<?php echo (isset($sessionVals['from_region_id']) ? $sessionVals['from_region_id'] : ""); ?>">
    <input type="hidden" id="from_city_id" name="from_city_id" value="<?php echo (isset($sessionVals['from_city_id']) ? $sessionVals['from_city_id'] : ""); ?>">
    <input type="hidden" id="from_branch_id" name="from_branch_id" value="<?php echo (isset($sessionVals['from_branch_id']) ? $sessionVals['from_branch_id'] : ""); ?>">

    <input type="hidden" id="to_city_id" name="to_city_id" value="<?php echo (isset($sessionVals['to_city_id']) ? $sessionVals['to_city_id'] : ""); ?>">
    <input type="hidden" id="to_branch_id" name="to_branch_id" value="<?php echo (isset($sessionVals['to_branch_id']) ? $sessionVals['to_branch_id'] : ""); ?>">

    <input type="hidden" class="isDeliveryMode" name="is_delivery_mode" value="<?php echo(isset($sessionVals['is_delivery_mode']) && $sessionVals['is_delivery_mode'] != '' ? $sessionVals['is_delivery_mode'] : 0); ?>">
    <input type="hidden" class="pickup_delivery_coordinate" name="pickup_delivery_coordinate" value="<?php echo(isset($sessionVals['pickup_delivery_coordinate']) && $sessionVals['pickup_delivery_coordinate'] != '' ? $sessionVals['pickup_delivery_coordinate'] : '') ?>">
    <input type="hidden" class="dropoff_delivery_coordinate" name="dropoff_delivery_coordinate" value="<?php echo(isset($sessionVals['dropoff_delivery_coordinate']) && $sessionVals['dropoff_delivery_coordinate'] != '' ? $sessionVals['dropoff_delivery_coordinate'] : '') ?>">
    <input type="hidden" class="allIsOkForPickup" name="allIsOkForPickup" value="<?php echo(isset($sessionVals['allIsOkForPickup']) && $sessionVals['allIsOkForPickup'] != '' ? $sessionVals['allIsOkForPickup'] : 0); ?>">
    <input type="hidden" class="allIsOkForDropoff" name="allIsOkForDropoff" value="<?php echo(isset($sessionVals['allIsOkForDropoff']) && $sessionVals['allIsOkForDropoff'] != '' ? $sessionVals['allIsOkForDropoff'] : 0); ?>">
    <input type="hidden" id="delivery_charges" name="delivery_charges" value="<?php echo(isset($sessionVals['delivery_charges']) && $sessionVals['delivery_charges'] != '' ? $sessionVals['delivery_charges'] : 0); ?>">
        <input type="hidden" id="book_for_hours" name="book_for_hours" value="<?php echo(isset($sessionVals['book_for_hours']) && $sessionVals['book_for_hours'] != '' ? $sessionVals['book_for_hours'] : 0); ?>">
        <input type="hidden" id="subscribe_for_months" name="subscribe_for_months" value="<?php echo(isset($sessionVals['subscribe_for_months']) && $sessionVals['subscribe_for_months'] != '' ? $sessionVals['subscribe_for_months'] : 0); ?>">

    <ul>

        <!-- Input fields for delivery mode -->
        <?php if(isset($_REQUEST['delivery']) || ((isset($_REQUEST['pickup']) || isset($_REQUEST['monthly']) || isset($_REQUEST['weekly']) || isset($_REQUEST['subscription'])) && isset($_REQUEST['t']) && $_REQUEST['t'] == 'delivery')){ ?>
            <li class="big delivery_mode_pickup deliv_wiz1">
                <label><?php echo($lang == 'eng' ? 'Delivery Location' : 'مكان التوصيل'); ?></label>
                <input type="text"
                       class="backLocation showMapFn from_branch_field_for_delivery delv-required-for-search filterBranchesFromForDelivery"
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
                                                                         onclick="$('.from_branch_field_for_delivery').val($(this).text());openGoogleMapPopup('<?php echo trim($lat); ?>','<?php echo trim($long); ?>','<?php echo $reg->id; ?>', 'pickup', '<?php echo $reg->delivery_charges; ?>');$('#from_city_ylw_bx').text($(this).text());"
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
            </li>
            <li class="big delivery_mode_dropoff deliv_wiz1"><!-- 	 hidden-sm hidden-xs		-->
                <label><?php echo ($lang == 'eng' ? 'Returning Location' : 'مكان الإرجاع'); ?></label>
                <input type="text"
                       class="backSandGrayPlus backLocation showMapFn to_branch_field_for_delivery to_branch_field delv-required-for-search filterBranchesToForDelivery"
                       id="myInputToForDelivery"
                       name="to_branch_name" value="<?php echo ($lang == 'eng' ? 'Return will be to the nearest branch' : 'يتم تسليم السيارة في اقرب فرع'); ?>"
                       placeholder="<?php echo ($lang == 'eng' ? 'Return will be to the nearest branch' : 'يتم تسليم السيارة في اقرب فرع'); ?>"
                       {{--onclick="showMapFn(this)--}}"/>
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
                                        onclick="$('.to_branch_field_for_delivery').val($(this).text());openGoogleMapPopup('<?php echo trim($lat); ?>','<?php echo trim($long); ?>','<?php echo $reg->id; ?>', 'dropoff', '<?php echo $reg->delivery_charges; ?>');$('#to_city_ylw_bx').text($(this).text());"
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
            </li>
        <?php } ?>

        <span class="wiz1 <?php echo ((isset($_REQUEST['delivery']) || ((isset($_REQUEST['pickup']) || isset($_REQUEST['monthly']) || isset($_REQUEST['weekly']) || isset($_REQUEST['subscription'])) && isset($_REQUEST['t']) && $_REQUEST['t'] == 'delivery'))?'hide':'');?>" style="">
            <!-- Input fields for pickup mode -->
            <div class="big pickup_mode_pickup field">
                <a href="javascript:void(0)" id="btn_pickup_icon" >Pickup Location</a>
                <div class="pickup-loc-drop"  style="display:none;">
                <label><?php echo ($lang == 'eng'?'Select Pickup Location':'حدد موقع التسليم');?></label>
                <input type="text"
                       class="backLocation showMapFn from_branch_field_for_pickup  from_branch_field filterBranchesFromForPickup"
                       id="myInputFromForPickup"
                       value="<?php echo isset($sessionVals['from_branch_name'])?$sessionVals['from_branch_name']:''; ?>"
                       placeholder="@lang('labels.select_search_bar')"
                       onclick="showMapFn(this)"/>
                </div>
            </div>
            <div class="field">
            	<label><?php echo ($lang == 'eng'?'Select City':'اختر المدينة');?></label>
            	<select id="fromCities" name="" class="required-for-search">
					<option value=""><?php echo ($lang == 'eng'?'Select City ':'اختار مدينة');?></option>
					<?php foreach ($pickup_regions as $key => $region){ ?>
					<?php
                    $from_city_data = explode('|', $key);
                    $get_sess_city_id = isset($sessionVals['from_city_id'])?$sessionVals['from_city_id']:'';
					if($from_city_data[0] == $get_sess_city_id){
					    $selected = 'selected';
                    }else{
                        $selected = '';
                    }
					?>
						<option value="<?php echo $from_city_data[0].'|'.$region[0]->region_id; ?>" <?php //echo $selected; ?>><?php echo $from_city_data[1];?></option>
					<?php } ?>
				</select>
            </div>
            <div class="field" style="display: none;">
            	<label><?php echo ($lang == 'eng'?'Select Branch':'اختر الفرع');?></label>
            	<select id="fromBranches" name="" class="required-for-search">
					<option value=""><?php echo ($lang == 'eng'?'Select Branch ':'اختار فرع');?></option>
				</select>
            </div>
        </span>

        <span class="wiz2" style="display: none;">
            <div class="big pickup_mode_dropoff field">
                <a href="javascript:void(0);" id="btn_dropoff_icon">Dropoff Location</a>
                <div class="dropoff-loc-drop"  style="display:none;">
                <label><?php echo ($lang == 'eng'?'Select Drop Off Location':'اختر موقع التسليم');?></label>
                <input type="text"
                       class="backSandGrayPlus backLocation showMapFn to_branch_field_for_pickup to_branch_field filterBranchesToForPickup"
                       id="myInputToForPickup"
                       value="<?php echo isset($sessionVals['to_branch_name'])?$sessionVals['to_branch_name']:''; ?>"
                       placeholder="@lang('labels.select_search_bar')"
                       onclick="showMapFn(this)"/>
                </div>
            </div>
            <div class="field">
            	<label><?php echo ($lang == 'eng'?'Select City ':'اختار مدينة');?></label>
				<select id="toCities" name="" class=" ">
					<option value=""><?php echo ($lang == 'eng'?'Select City ':'اختار مدينة');?></option>
					<?php foreach ($pickup_regions as $key => $region){ ?>
					<?php $from_city_data = explode('|', $key); ?>
					<option value="<?php echo $from_city_data[0].'|'.$region[0]->region_id; ?>"><?php echo $from_city_data[1];?></option>
					<?php } ?>
				</select>
            </div>
            <div class="field">
            	<label><?php echo ($lang == 'eng'?'Select Branch ':'اختار فرع')?></label>
				<select id="toBranches" name="" class=" ">
					<option value=""><?php echo ($lang == 'eng'?'Select Branch ':'اختار فرع')?></option>
				</select>
            </div>
        </span>
        <?php
        $edit = 0;
        /*echo '<script>
                var edit_check_js = 0;
            </script>';*/
        if((isset($_REQUEST['pickup']) || isset($_REQUEST['hourly']) || isset($_REQUEST['subscription'])) && isset($_REQUEST['sess'])){
            if($_REQUEST['sess'] == 1 && $_REQUEST['wiz'] == 2){
                $edit = 1;
                echo '<script>
                        edit_check_js =1;
                        $(document).ready(function() {
                            getDropOffWiz();
                            triggerDatePicker();
                        });
                    </script>';
            }
            if($_REQUEST['sess'] == 1 && $_REQUEST['wiz'] == 3){
                $edit = 1;
                echo '<script>
                        edit_check_js =1;
                        $(document).ready(function() {
                            $("#datepicker_from").val("");
                            $("#datepicker_to").val("");
                            $("#pickup_date_gry_bx").text("");
                            $("#dropoff_date_gry_bx").text("");
                            getDatePickerWiz();
                            triggerDatePicker();
                        });
                    </script>';
            }
            if($_REQUEST['sess'] == 1 && $_REQUEST['wiz'] == 4){
                $edit = 1;
                echo '<script>
                        edit_check_js =1;
                        $(document).ready(function() {
                          getTimePickerWiz();
                          triggerDatePicker();
                        });
                    </script>';
            }

        }


        if((isset($_REQUEST['delivery']) || ((isset($_REQUEST['pickup']) || isset($_REQUEST['monthly']) || isset($_REQUEST['weekly']) || isset($_REQUEST['subscription'])) && isset($_REQUEST['t']) && $_REQUEST['t'] == 'delivery')) && isset($_REQUEST['sess'])){
            if($_REQUEST['sess'] == 1 && $_REQUEST['wiz'] == 3){
                $edit = 1;
                echo '<script>
                        edit_check_js =1;
                        $(document).ready(function() {
                            $("#datepicker_from").val("");
                            $("#datepicker_to").val("");
                            $("#pickup_date_gry_bx").text("");
                            $("#dropoff_date_gry_bx").text("");
                            getDatePickerWiz_delivery();
                            triggerDatePicker();
                        });
                    </script>';
            }
            if($_REQUEST['sess'] == 1 && $_REQUEST['wiz'] == 4){
                $edit = 1;
                echo '<script>
                        edit_check_js =1;
                        $(document).ready(function() {
                          getTimePickerWiz_delivery();
                          triggerDatePicker();
                        });
                    </script>';
            }

        }

        ?>
        <?php
            if($edit == 1){
                $from_br = isset($sessionVals['from_branch_id'])?$sessionVals['from_branch_id']:'';
                $to_br = isset($sessionVals['to_branch_id'])?$sessionVals['to_branch_id']:'';
            }else{
                $from_br = isset($sessionVals['from_branch_name'])?$sessionVals['from_branch_name']:'';
                $to_br = isset($sessionVals['to_branch_name'])?$sessionVals['to_branch_name']:'';
            }

            $from_cit = isset($sessionVals['from_city_id'])?$sessionVals['from_city_id']:'';
            $to_cit = isset($sessionVals['to_city_id'])?$sessionVals['to_city_id']:'';
            $pick_date = isset($sessionVals['pickup_date'])?date('d M Y',strtotime($sessionVals['pickup_date'])):'';
            $drop_date = isset($sessionVals['dropoff_date'])?date('d M Y',strtotime($sessionVals['dropoff_date'])):'';
            $pickup_time = isset($sessionVals['pickup_time'])?$sessionVals['pickup_time']:'';
            $dropoff_time = isset($sessionVals['dropoff_time'])?$sessionVals['dropoff_time']:'';
            custom::branch_mob_divs(true,$lang,$from_br,$to_br,$from_cit,$to_cit,$pick_date,$drop_date,$pickup_time,$dropoff_time,$lang_base_url,$edit);
        ?>
        <span class="wiz3" style="display: none;">

            <div class="datepicker-scroll">
                <div class="datepicker_mobile" id="datepicker_mobile_id"></div>
            </div>

            <li class="small ">
                <input type="hidden" class="backCalendar required-for-search" placeholder="" name="pickup_date" id="datepicker_from" value="<?php if (isset ($sessionVals['pickup_date']) && $sessionVals['pickup_date'] != '') echo date('d-m-Y', strtotime($sessionVals['pickup_date'])); ?>"/>
            </li>
            <li class="small">
                <input type="hidden" class="backCalendar required-for-search" placeholder="" name="dropoff_date" id="datepicker_to" value="<?php if (isset($sessionVals['dropoff_date']) && $sessionVals['dropoff_date'] != '') echo date('d-m-Y', strtotime($sessionVals['dropoff_date'])); ?>"/>
            </li>
        </span>

        <span class="wiz4" style="display: none;">
           	<div class="time-picker">
				<ul class="pickUpTimeMobile <?php echo (isset($_REQUEST['hourly']) || isset($_REQUEST['subscription']) ? 'hourlyMode' : ''); ?>">
					<?php
					$start=strtotime('00:00');
					$end=strtotime('23:30');
					for ($i=$start;$i<=$end;$i = $i + 30*60){
					$timeInterval = date('H:i',$i);
					$selectedTime = isset($sessionVals['pickup_time']) && $sessionVals['pickup_time'] == $timeInterval ? 'selected':($timeInterval == '09:00'?'selected':'');
					?>
					<li class="select_pickup_time <?php echo $selectedTime; ?>" data-pickUpTime="<?php echo $timeInterval; ?>"><?php echo $timeInterval; ?></li>
					<?php } ?>
				</ul>
				<ul class="dropOffTimeMobile" style="display: <?php echo (isset($_REQUEST['hourly']) || isset($_REQUEST['subscription']) ? 'none' : 'block'); ?>;">
					<?php
					$start=strtotime('00:00');
					$end=strtotime('23:30');
					for ($i=$start;$i<=$end;$i = $i + 30*60){
					$timeInterval = date('H:i',$i);
					$selectedTime = isset($sessionVals['dropoff_time']) && $sessionVals['dropoff_time'] == $timeInterval ? 'selected':($timeInterval == '09:00'?'selected':'');
					?>
					<li class="select_dropoff_time <?php echo $selectedTime; ?>" data-dropOffTime="<?php echo $timeInterval; ?>"><?php echo $timeInterval; ?></li>
						<?php } ?>
				</ul>
            </div>

            <input type="hidden" name="pickup_time" id="pickUpTime_hdn" value="" class="required-for-search"/>
            <input type="hidden" name="dropoff_time" id="dropOffTime_hdn" value="" class="required-for-search" />

        </span>


        <?php if (Session::get('customer_id_no_for_loyalty') != ''){ ?>
            <input type="hidden" name="id_no_for_loyalty" value="<?php echo Session::get('customer_id_no_for_loyalty'); ?>">
        <?php } ?>

        <script>
            /*
            * wizards function for pickup mode
            * */
            if (lang === 'eng') {
                var required_msg = "Please fill out this field.";
            } else {
                var required_msg = "الرجاء تعبئة الخانة";
            }
            //when wiz == 3
            function getDatePickerWiz(){
                $('.wiz1').hide();
                $('.wiz3').show();
                $('.wiz4').hide();
                $('.city_branch_tabs').show();
                $('.time_tabs').show();
                $('.time_tabs2').hide();
            }

            function getDatePickerWiz_delivery(){
                $('.wiz1').hide();
                $('.deliv_wiz1').hide();
                $('.wiz3').show();
                $('.city_branch_tabs').show();
                $('.time_tabs').show();
            }
            //when wiz == 4
            function getTimePickerWiz(){
                $('.select_pickup_time').removeClass('selected');
                $('.select_dropoff_time').removeClass('selected');
                $('#pickup_time_gry_bx').text('');
                $('#dropoff_time_gry_bx').text('');
                $('.wiz1').hide();
                $('.wiz3').hide();
                $('.wiz4').show();
                $('.city_branch_tabs').show();
                $('.time_tabs').show();
                $('.time_tabs2').show();
            }
            function getTimePickerWiz_delivery(){
                $('.select_pickup_time').removeClass('selected');
                $('.select_dropoff_time').removeClass('selected');
                $('#pickup_time_gry_bx').text('');
                $('#dropoff_time_gry_bx').text('');
                $('.wiz1').hide();
                $('.deliv_wiz1').hide();
                $('.wiz3').hide();
                $('.wiz4').show();
                $('.city_branch_tabs').show();
                $('.time_tabs').show();
                $('.time_tabs2').show();
            }
            function getDropOffWiz(){
                $('.select_pickup_time').removeClass('selected');
                $('.select_dropoff_time').removeClass('selected');
                $('#pickup_time_gry_bx').text('');
                $('#dropoff_time_gry_bx').text('');
                $('.wiz1').hide();
                //skip the wiz2
                $('.city_branch_tabs').hide();
                $('.time_tabs').hide();
                $('.time_tabs2').hide();
                $('.wiz2').show();
                $('.wiz3').hide();
                $('.wiz4').hide();
            }
            function wiz1Next(){
                var returnVal = true;
                $("select.required-for-search").each(function () {
                    if ($(this).val() === '') {
                        $(this).attr("data-original-title", required_msg);
                        $(this).tooltip('show');
                        returnVal = false;
                    }
                });
                if(returnVal) {
                    $('#decison-alert').modal('show');
                }
                /*if(confirm("Are you going to drop off at same location?")) {
                    $('.wiz1').hide();
                    $('.if_branch_same').hide();
                    //skip the wiz2
                    $('.wiz3').show();
                    $('.city_branch_tabs').show();
                    $('.time_tabs').show();
                }else{
                    $("#myInputToForPickup").val("");
                    $('.wiz1').hide();
                    $('.wiz2').show();
                }*/
            }
            $(document).on('click','#btn-yes',function () {
                $('#decison-alert').modal('hide');
                $('.wiz1').hide();
                $('.if_branch_same').hide();
                //skip the wiz2
                $('.wiz3').show();
                $('.city_branch_tabs').show();
                $('.time_tabs').show();
            });
            $(document).on('click','#btn-no',function () {
                $('#decison-alert').modal('hide');
                $("#myInputToForPickup").val("");
                $("#toCities").addClass("required-for-search");
                $("#toBranches").addClass("required-for-search");
                $('.wiz1').hide();
                $('.wiz2').show();
            });
            function wiz2Next() {
                var returnVal = true;
                $("select.required-for-search").each(function () {
                    if ($(this).val() === '') {
                        $(this).attr("data-original-title", required_msg);
                        $(this).tooltip('show');
                        returnVal = false;
                    }
                });
                if(returnVal || edit_check_js === 1) {
                    $('.wiz2').hide();
                    $('.wiz3').show();
                    $('.city_branch_tabs').show();
                    $('.time_tabs').show();
                }
            }
            function wiz3Next() {
                var returnVal = true;
                if (
                    $('#datepicker_from').val() === '' ||
                    ($('.isDeliveryMode').val() !== '2' && $('.isDeliveryMode').val() !== '4' && $('#datepicker_to').val() === '')
                ) {
                    if (($('.isDeliveryMode').val() === '2' || $('.isDeliveryMode').val() === '4') && $('#datepicker_from').val() === '') {
                        $('#date-select-alert').find('p').text(lang === 'eng' ? 'Please select Pick up date' : 'اختار تاريخ موقع الاستلام');
                    } else {
                        $('#date-select-alert').find('p').text(lang === 'eng' ? 'Please select Pick up and Drop off date' : 'اختار تاريخ موقع الاستلام و التسليم');
                    }
                    $('#date-select-alert').modal('show');
                    returnVal = false;
                }
                if(returnVal) {
                    $('.select_pickup_time').removeClass('selected');
                    $('.select_dropoff_time').removeClass('selected');
                    $('#pickup_time_gry_bx').text('');
                    $('#dropoff_time_gry_bx').text('');
                    $('.wiz3').hide();
                    $('.wiz4').show();
                    $('.city_branch_tabs').show();
                    $('.time_tabs').show();
                    $('.time_tabs2').show();
                }
            }
            /*
            * wizards function for delivery mode
            * */
            function deliv_wiz1Next() {
                var returnVal = true;
                $("input.delv-required-for-search").each(function () {
                    if ($(this).val() === '') {
                        $(this).attr("data-original-title", required_msg);
                        $(this).tooltip('show');
                        returnVal = false;
                    }
                });
                if(returnVal || edit_check_js === 1) {
                    $('.wiz1').hide();
                    $('.deliv_wiz1').hide();
                    $('.wiz3').show();
                    $('.city_branch_tabs').show();
                    $('.time_tabs').show();
                }
            }

            function deliv_wiz2Next() {
                var returnVal = true;

                <?php if ((isset($_REQUEST['pickup']) || isset($_REQUEST['monthly']) || isset($_REQUEST['weekly']) || isset($_REQUEST['subscription'])) && isset($_REQUEST['t']) && $_REQUEST['t'] == 'delivery') { ?>
                    if ($('#datepicker_from').val() === '') {
                        $('#date-select-alert').find('p').text(lang === 'eng' ? 'Please select Pick up date' : 'اختار تاريخ موقع الاستلام');
                        $('#date-select-alert').modal('show');
                        returnVal = false;
                    }
                <?php } else { ?>
                    if ($('#datepicker_from').val() === '' || $('#datepicker_to').val() === '') {
                        $('#date-select-alert').modal('show');
                        returnVal = false;
                    }
                <?php } ?>

                if(returnVal || edit_check_js === 1) {
                    $('.select_pickup_time').removeClass('selected');
                    $('.select_dropoff_time').removeClass('selected');
                    $('#pickup_time_gry_bx').text('');
                    $('#dropoff_time_gry_bx').text('');
                    $('.wiz1').hide();
                    $('.wiz3').hide();
                    $('.wiz4').show();
                    $('.city_branch_tabs').show();
                    $('.time_tabs').show();
                    $('.time_tabs2').show();
                }
            }

            function searchValidationCheck() {
                var returnVal = true;
                $("select.required-for-search").each(function () {
                    if ($(this).val() === '') {
                        $(this).attr("data-original-title", required_msg);
                        $(this).tooltip('show');
                        returnVal = false;
                    }
                });
                return returnVal;
            }
        </script>

        <span class="wiz1 btn-next" style="display:<?php echo((isset($_REQUEST['delivery']) || ((isset($_REQUEST['pickup']) || isset($_REQUEST['monthly']) || isset($_REQUEST['weekly']) || isset($_REQUEST['subscription'])) && isset($_REQUEST['t']) && $_REQUEST['t'] == 'delivery')) ? 'block' : 'none')?>;">
            <li class="submitBtn add">
                <?php if ($site->maintenance_mode == 'on'){ ?>
                <input type="button" class="serchhomeBtn add wiz1Next" value="@lang('labels.search')" onclick="siteUnderMaintenance();"/>
                <?php }else{ ?>
                <input value="<?php echo ($lang == 'eng'?'Next':'التالي');?>" type="button" class="serchhomeBtn add wiz1Next" <?php echo ((isset($_REQUEST['delivery']) || ((isset($_REQUEST['pickup']) || isset($_REQUEST['monthly']) || isset($_REQUEST['weekly']) || isset($_REQUEST['subscription'])) && isset($_REQUEST['t']) && $_REQUEST['t'] == 'delivery'))?'onclick="deliv_wiz1Next()"':'onclick="wiz1Next()"')?>/>
                <?php } ?>
            </li>
        </span>

        <span class="wiz2 btn-next" style="display: none;">
            <li class="submitBtn add">
                <input type="button" class="serchhomeBtn add" onclick="wiz2Next();" value="<?php echo ($lang == 'eng'?'Next':'التالي');?>"/>
            </li>
        </span>

        <span class="wiz3 btn-next" style="display: none;">
            <li class="submitBtn add">
                <input type="button" class="serchhomeBtn add" <?php echo ((isset($_REQUEST['delivery']) || ((isset($_REQUEST['pickup']) || isset($_REQUEST['monthly']) || isset($_REQUEST['weekly']) || isset($_REQUEST['subscription'])) && isset($_REQUEST['t']) && $_REQUEST['t'] == 'delivery'))?'onclick="deliv_wiz2Next()"':'onclick="wiz3Next()"')?> value="<?php echo ($lang == 'eng'?'Next':'التالي');?>"/>
            </li>
        </span>

        <span class="wiz4 btn-next" style="display: none;">
            <li class="submitBtn add">
                <?php if ($site->maintenance_mode == 'on'){ ?>
                <input type="button" class="serchhomeBtn add" value="@lang('labels.search')" onclick="siteUnderMaintenance();"/>
                <?php }else{ ?>
                <input type="button" class="serchhomeBtn add" id="serchhomeBtn" value="@lang('labels.search')"/>
                <?php } ?>
            </li>
        </span>

    </ul>
    <div id="pickUp" class="fleetDropDown pickupTab">
        <div class="locaListFix mCustomScrollbar">
            <ul id="myULFromForPickup">
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
                                                             onclick="$('.from_branch_field_for_pickup').val($(this).text());$('.to_branch_field_for_pickup').val($(this).text());$('#from_branch_ylw_bx').text($(this).text());$('#from_city_ylw_bx').text($('#from_city_<?php echo $reg->city_id; ?>').find('h3').text());$('#fromCities').val('');$('#fromBranches').val('');$('#fromCities').removeClass('required-for-search');$('#fromBranches').removeClass('required-for-search');set_from_city_branch_also('<?php echo $reg->city_id.'|'.$reg->region_id; ?>',$(this).text(),'<?php echo $reg->id; ?>');"
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
        <div class="dropMapLoc">
            <div style="" id="map-canvas-pickup"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="dropOff" class="fleetDropDown pickupTab">
        <div class="locaListFix mCustomScrollbar">
            <ul id="myULToForPickup">
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
                            onclick="$('.to_branch_field_for_pickup').val($(this).text());$('#to_branch_ylw_bx').text($(this).text());$('#to_city_ylw_bx').text($('#to_city_<?php echo $reg->city_id; ?>').find('h3').text());$('#toCities').val('');$('#toBranches').val('');$('#toCities').removeClass('required-for-search');$('#toBranches').removeClass('required-for-search');set_to_city_branch_also('<?php echo $reg->city_id.'|'.$reg->region_id; ?>',$(this).text(),'<?php echo $reg->id; ?>');"
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

            if(!is_mobile) {
                $('.pickup_mode_pickup, .pickup_mode_dropoff').removeClass('hide');
                $('.delivery_mode_pickup, .delivery_mode_dropoff').addClass('hide');
            }

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

            if(!is_mobile){
                $('.pickup_mode_pickup, .pickup_mode_dropoff').addClass('hide');
                $('.delivery_mode_pickup, .delivery_mode_dropoff').removeClass('hide');
            }

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

</script>
<!-- --------------------------------------- -->
<script>
    $(document).ready(function () {
        <?php if ($active_menu == 'fleet') { ?>
            $('.isDeliveryMode').val(0);
        <?php } ?>
        $('.serFormArea').show();

        //to show pickup location drodown
        $('#btn_pickup_icon').click(function() {
            $('.pickup-loc-drop').slideToggle("slow");
        });

        //To show dropoff location dropdown
        $('#btn_dropoff_icon').click(function() {
            $('.dropoff-loc-drop').slideToggle("slow");
        });
    });
    function triggerDatePicker(){
        $("#datepicker_mobile_id").datepicker({
            minDate: 0,
            numberOfMonths: [1,1],
            beforeShowDay: function(date) {
                var date1 = $.datepicker.parseDate("dd-mm-yy", "");
                var date2 = $.datepicker.parseDate("dd-mm-yy", "");
                return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
            },
            onSelect: function(dateText, inst) {
                var date1 = $.datepicker.parseDate("dd-mm-yy", $("#datepicker_from").val());
                var date2 = $.datepicker.parseDate("dd-mm-yy", $("#datepicker_to").val());
                var selectedDate = $.datepicker.parseDate("dd-mm-yy", dateText);

                if(lang === "eng"){
                    var get_date_format = $.datepicker.formatDate("dd M yy", selectedDate, {
                        monthNamesShort: $.datepicker.regional[ "en" ].monthNamesShort
                    });
                }else{
                    $.datepicker.regional["ar"] = {monthNames: ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"]}
                    var get_date_format = $.datepicker.formatDate("dd MM yy", selectedDate, {
                        monthNames: $.datepicker.regional[ "ar" ].monthNames
                    });
                }

                if (!date1 || date2) {
                    $("#datepicker_from").val(dateText);
                    $("#datepicker_to").val("");
                    $("#pickup_date_gry_bx").text(get_date_format);
                    $("#dropoff_date_gry_bx").text("");
                    $(this).datepicker();
                } else if( selectedDate < date1 ) {
                    $("#datepicker_from").val(dateText);
                    $("#datepicker_to").val("");
                    $("#pickup_date_gry_bx").text(get_date_format);
                    $("#dropoff_date_gry_bx").text("");
                    $(this).datepicker();
                } else {
                    $("#datepicker_to").val(dateText);
                    $("#dropoff_date_gry_bx").text(get_date_format);
                    $(this).datepicker();
                }
            }
        });
    }

    function selectHoursWiz() {
        $('#book_for_hours_select_modal').modal('show');
    }

    function selectSubscriptionMonthsWiz() {
        $('#book_for_subscription_months_select_modal').modal('show');
    }

    function select_book_for_hours(book_for_hours_val) {
        if (book_for_hours_val > 0) {
            $('#book_for_hours').val(book_for_hours_val);
            $('.book_for_hours').text(book_for_hours_val + " " + (lang == 'eng' ? 'Hours' : 'ساعات'));
        } else {
            $('#book_for_hours').val(0);
            $('.book_for_hours').text(lang == 'eng' ? 'Please select booking hours' : 'الرجاء تحديد ساعات الحجز');
        }
    }

    function select_subscribe_for_months(subscribe_for_months_val) {
        if (subscribe_for_months_val > 0) {
            $('#subscribe_for_months').val(subscribe_for_months_val);
            $('.subscribe_for_months').text(subscribe_for_months_val + " " + (lang == 'eng' ? 'Months' : 'أشهر'));
        } else {
            $('#subscribe_for_months').val(0);
            $('.subscribe_for_months').text(lang == 'eng' ? 'Please select booking months' : 'الرجاء تحديد أشهر الحجز');
        }
    }

    <?php if (isset($_REQUEST['subscription'])) { ?>
        select_subscribe_for_months(3);
    <?php } ?>

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
</script>