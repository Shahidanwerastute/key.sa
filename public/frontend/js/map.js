(function($){
	$.fn.mapmarker = function(options){
		var opts = $.extend({}, $.fn.mapmarker.defaults, options);

		return this.each(function() {
			// Apply plugin functionality to each element
			var map_element = this;
			addMapMarker(map_element, opts.zoom, opts.center, opts.markers);
		});
	};
	
	// Set up default values
	var defaultMarkers = {
		"markers": []
	};

	$.fn.mapmarker.defaults = {
		zoom	: 8,
		center	: 'KSA',
		markers	: defaultMarkers
	}
	
	// Main function code here (ref:google map api v3)
	function addMapMarker(map_element, zoom, center, markers){
		//console.log($.fn.mapmarker.defaults['center']);
		
		//Set center of the Map
		var myOptions = {
		  zoom: zoom,
            gestureHandling: 'greedy',
		  mapTypeId: google.maps.MapTypeId.ROADMAP,
		  scrollwheel: false,
		styles: [
			{stylers: [{ visibility: 'simplified' }]},
			{elementType: 'labels', stylers: [{ visibility: 'off' }]}
		]
		}
		
		var styledMapType = new google.maps.StyledMapType(
            [{
    "featureType": "water",
    "elementType": "geometry",
    "stylers": [{
        "color": "#ffffff"
    }, {
        "lightness": 17
    }]
}, {
    "featureType": "landscape",
    "elementType": "geometry",
    "stylers": [{
        "color": "#e1e5e4"
    }, {
        "lightness": 20
    }]
}, {
    "featureType": "road.highway",
    "elementType": "geometry.fill",
    "stylers": [{
        "color": "#b5d48f"
    }, {
        "lightness": 17
    }]
}, {
    "featureType": "road.highway",
    "elementType": "geometry.stroke",
    "stylers": [{
        "color": "#ffffff"
    }, {
        "lightness": 29
    }, {
        "weight": 0.2
    }]
}, {
    "featureType": "road.arterial",
    "elementType": "geometry",
    "stylers": [{
        "color": "#ffffff"
    }, {
        "lightness": 18
    }]
}, {
    "featureType": "road.local",
    "elementType": "geometry",
    "stylers": [{
        "color": "#ffffff"
    }, {
        "lightness": 16
    }]
}, {
    "featureType": "poi",
    "elementType": "geometry",
    "stylers": [{
        "color": "#f5f5f5"
    }, {
        "lightness": 21
    }]
}, {
    "featureType": "poi.park",
    "elementType": "geometry",
    "stylers": [{
        "color": "#dedede"
    }, {
        "lightness": 21
    }]
}, {
    "elementType": "labels.text.stroke",
    "stylers": [{
        "visibility": "on"
    }, {
        "color": "#ffffff"
    }, {
        "lightness": 16
    }]
}, {
    "elementType": "labels.text.fill",
    "stylers": [{
        "saturation": 36
    }, {
        "color": "#333333"
    }, {
        "lightness": 40
    }]
}, {
    "elementType": "labels.icon",
    "stylers": [{
        "visibility": "off"
    }]
}, {
    "featureType": "transit",
    "elementType": "geometry",
    "stylers": [{
        "color": "#f2f2f2"
    }, {
        "lightness": 19
    }]
}, {
    "featureType": "administrative",
    "elementType": "geometry.fill",
    "stylers": [{
        "color": "#fefefe"
    }, {
        "lightness": 20
    }]
}, {
    "featureType": "administrative",
    "elementType": "geometry.stroke",
    "stylers": [{
        "color": "#fefefe"
    }, {
        "lightness": 17
    }, {
        "weight": 1.2
    }]
}],
            {name: 'Styled Map'});
		
		var map = new google.maps.Map(map_element, myOptions);
		map.mapTypes.set('styled_map', styledMapType);
        map.setMapTypeId('styled_map');
		var geocoder = new google.maps.Geocoder();
		var infowindow = null;
		var baloon_text = "";
		
		geocoder.geocode( { 'address': center}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				//In this case it creates a marker, but you can get the lat and lng from the location.LatLng
				map.setCenter(results[0].geometry.location);
			}
			else{
				console.log("Geocode was not successful for the following reason: " + status);
			}
		});
		var previousMarker;
		//run the marker JSON loop here
		$.each(markers.markers, function(i, the_marker){
			latitude=the_marker.latitude;
			longitude=the_marker.longitude;
			icon=the_marker.icon;
			var baloon_text=the_marker.baloon_text;
			
			if(latitude!="" && longitude!=""){
				var marker = new google.maps.Marker({
					map: map, 
					position: new google.maps.LatLng(latitude,longitude),
					animation: google.maps.Animation.DROP,
					icon: icon
				});
				
				// Set up markers with info windows 
				google.maps.event.addListener(marker, 'click', function() {
					// Close all open infowindows
					
					//change the previous marker to green.
					if (typeof previousMarker !== 'undefined') {			
						previousMarker.setIcon("images/map-marker.png");
					}

					//change the clicked market to black
					marker.setIcon("images/select-marker.png");
					
					previousMarker = marker;
					
					if (infowindow) {
						infowindow.close();
					}
					
					infowindow = new google.maps.InfoWindow({
						content: baloon_text
					});
					
					infowindow.open(map,marker);
				});
			}
		});
	}

})(jQuery);