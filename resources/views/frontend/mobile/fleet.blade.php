@extends('frontend.layouts.template')
@section('content')
    <section class="textBannerSec searchBarSec">
        <div class="container-md">
            <a class="btn-filter" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#mob-menus"><?php echo ($lang == 'eng' ? 'Filter' : 'ترتيب'); ?></a>
            <div id="mob-menus" class="wrap-dropdown collapse">
                <form action="javascript:void(0);" class="fleetSerBar serFormArea">
                    <div class="shotingLink">
                        <input type="text" id="fleet_text" placeholder="@lang('labels.fleet_class')">
                        <ul class="fleet_optns" style="display: none;">
                            <li class="<?php echo $car_category == '' ?'active':''; ?>"><a id="all" href="javascript:void(0);" data-categoryid="0" onclick="fleet_serch_cat_filter($(this).data('categoryid'), '{{route('fleet'.($lang == 'eng' ? '-ar' : ''))}}','<?php echo ($lang == 'eng' ? 'ALL' : 'الكل'); ?>');"><?php echo ($lang == 'eng' ? 'ALL' : 'الكل'); ?></a> </li>
                            <?php foreach($categories as $category){ ?>
                            <li class="<?php echo $car_category == $category->id ?'active':''; ?>"><a id="<?php echo $category->eng_title; ?>" href="javascript:void(0);"  data-categoryid="<?php echo $category->id; ?>" onclick="fleet_serch_cat_filter($(this).data('categoryid'), '{{route('fleet'.($lang == 'eng' ? '-ar' : ''), [str_replace(' ','-',$category->eng_title)])}}','<?php echo ($lang == 'eng' ?  $category->eng_title : $category->arb_title) ?>');"><?php echo ($lang == 'eng' ?  $category->eng_title : $category->arb_title) ?></a> </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <ul>
                        <li class="big">
                            <input type="text" class="backLocation showMapFn from_branch_field required-for-search"
                                   name="from_branch_name" value="" placeholder="@lang('labels.display_models_for_location')"
                                   onclick="showMapFn(this)"/>
                            <div id="pickUp" class="fleetDropDown">
                                <div class="locaListFix mCustomScrollbar">
                                    <ul>
                                        <?php foreach ($pickup_regions as $key => $region){
                                        $exploded_key = explode('|', $key);
                                        ?>
                                        <li><h3><?php echo $exploded_key[1]; ?></h3></li>
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
                                        <li>
                                            <a href="javascript:void(0);" class="showMapPointer"
                                               onclick="$('.from_branch_field').val($(this).text());$('.to_branch_field').val($(this).text());setBranchIdInHiddenField('<?php echo $reg->id; ?>');"
                                               data-loclat="<?php echo str_replace(" ","",$lat); ?>" data-loclong="<?php echo str_replace(" ","",$long); ?>" data-regionid="<?php echo $reg->region_id; ?>"
                                               data-cityid="<?php echo $reg->city_id; ?>" data-branchid="<?php echo $reg->id; ?>"
                                               data-address1="<?php echo $reg->address_line_1;?>"
                                               data-address2="<?php echo $reg->address_line_2;?>" data-hours="<?php echo $reg->opening_hours;?>"
                                               data-phone1="<?php echo $reg->phone1;?>" data-phone2="<?php echo $reg->phone2;?>"
                                               data-mobile="<?php echo $reg->mobile;?>" data-agent="<?php echo $reg->agent_name;?>" data-branch_id="<?php echo $reg->id;?>"
                                               data-email="<?php echo $reg->email;?>">
                                                <?php echo($lang == 'eng' ? $reg->eng_title : $reg->arb_title); ?>
                                            </a>
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

                        </li>
                    </ul>
                    <div class="sub-row">
                        <div class="SelectBoxsW">
                            <select name="model" onchange="search_cars_with_all_fields();">
                                <option value="">@lang('labels.model_year')</option>
                                <?php foreach($years as $year){
                                if ($year > 2021) { ?>
                                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                <?php }
                                ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="SelectBoxsW">
                            <select name="capacity" onchange="search_cars_with_all_fields();">
                                <option value="">@lang('labels.pessengers_capacity')</option>
                                <?php foreach ($passengers as $passenger) {
                                    echo '<option value="'.$passenger->no_of_passengers.'">'.$passenger->no_of_passengers.'</option>';
                                }?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="class" value="all" />
                    <input type="hidden" name="cat_id" value="" id="searchcatId" />
                    <input type="hidden" name="branch" value="" id="searchBranchId" />
                    <input type="hidden" id="from_region_id" name="from_region_id" value="1">
                </form>
            </div>
        </div>
    </section>
    <section class="bookingSec fleet search_fleet-booking-sect">
        <div class="container-md">
            <div class="containsData fleetLoadMorePage">
                <?php echo custom::fleetPageHtml($car_models,$base_url,$lang_base_url,$lang); ?>
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


            });

            $('#fleet_text').focusin(function (){
               $('.fleet_optns').show();
            });
            $('#fleet_text').focusout(function (){
                setTimeout(function(){ $('.fleet_optns').hide(); }, 500);
            });
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