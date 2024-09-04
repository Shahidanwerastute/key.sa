<!--Limousine Mode Pickup Branches Modal-->
<div class="modal fade modal-lg" id="limousineModePickupBranchesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content pt-2 pb-2">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title">Select Pickup Destination</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="nav-col">
                            <h2>Select Destination</h2>
                            <ul>
                                @foreach(custom::get_limousine_branches() as $limousine_branch)
                                    <li>
                                        <a href="javascript:void(0);">
                                            {{$lang == 'eng' ? $limousine_branch->eng_branch_name : $limousine_branch->arb_branch_name}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="map-col">
                            <h2>Select Location in Map</h2>
                            @foreach(custom::get_limousine_branches() as $limousine_branch)
                                @php(sleep(2))
                                <div class="g-map" id="pickup_map_{{$limousine_branch->branch_id}}"></div>
                                <script>
                                    var branch_delivery_coordinates = [];
                                    var branch_id = {{$limousine_branch->branch_id}};
                                    $.ajax({
                                        type: 'GET',
                                        url: base_url + '/get_branch_delivery_coordinates',
                                        dataType: "json",
                                        data: {'branch_id': branch_id},
                                        success: function (response) {
                                            $('.loaderSpiner').hide();
                                            if (response.status == true) {
                                                var coordinates = response.coordinates.split('|');
                                                for (var i = 0; i < coordinates.length; i++) {
                                                    var coordin = coordinates[i].split(",");
                                                    var latitude = parseFloat(coordin[0]);
                                                    var longitude = parseFloat(coordin[1]);
                                                    branch_delivery_coordinates.push({ lat: latitude, lng: longitude });
                                                }
                                                var selector = 'pickup_map_{{$limousine_branch->branch_id}}';
                                                setTimeout(function() {

                                                    var limousine_map = new google.maps.Map(document.getElementById(selector), {
                                                        zoom: 11,
                                                        center: { lat: latitude, lng: longitude },
                                                        gestureHandling: 'greedy'
                                                    });

                                                    var limousine_polygon = new google.maps.Polygon({
                                                        paths: branch_delivery_coordinates,
                                                        strokeColor: '#000000',
                                                        strokeOpacity: 0.8,
                                                        strokeWeight: 2,
                                                        fillOpacity: 0, // Set fillOpacity to 0 to make the polygon transparent,
                                                    });

                                                    limousine_polygon.setMap(limousine_map);

                                                    var limousine_bounds = new google.maps.LatLngBounds();
                                                    branch_delivery_coordinates.forEach(coord => limousine_bounds.extend(coord));
                                                    limousine_map.fitBounds(limousine_bounds);

                                                }, 500);
                                            }
                                        }
                                    });

                                    limousine_polygon.addListener('click', function(event) {
                                        var clickedLat = event.latLng.lat();
                                        var clickedLng = event.latLng.lng();

                                        alert(clickedLat + clickedLng);
                                    });

                                    limousine_map.addListener('click', function(event) {
                                        alert(clickedLat + clickedLng);
                                    });
                                </script>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Limousine Mode Pickup Branches Modal-->

<!--Limousine Mode Dropoff Branches Modal-->
<div class="modal fade modal-lg" id="limousineModeDropoffBranchesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content pt-2 pb-2">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title">Select Dropoff Destination</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="nav-col">
                            <h2>Select Destination</h2>
                            <ul>
                                @foreach(custom::get_limousine_branches() as $limousine_branch)
                                    <li><a href="javascript:void(0);" onclick="showMapForLimousineBranch('dropoff', {{$limousine_branch->branch_id}});">{{$lang == 'eng' ? $limousine_branch->eng_branch_name : $limousine_branch->arb_branch_name}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="map-col">
                            <h2>Select Location in Map</h2>
                            <div class="g-map" id="dropoff_map"><p>Selection location first!</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Limousine Mode Dropoff Branches Modal-->