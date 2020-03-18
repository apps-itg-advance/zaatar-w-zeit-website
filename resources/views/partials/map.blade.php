<script>
	var polygon = null;
	var data_layer_zone = null;
	var data_layer_slope25 = null;
	var data_layer_slope5 = null;
	var data_layer_plot = null;
	var data_layer_roads = null;
	var data_layer_rivers = null;

	var marker = null;
	var plots = zones = [];
	var zoneMarkers = [];
	var plotMarkers = [];
	var infoWindow;

	var map = null;
	var modalMap = null;
	var all_overlays = [];
	var selectedShape;

	var panel;
	var PlotGeoJsonInput;
	var LotGeoJsonInput;
	var ZONEGeoJsonInput;
	var ZONENewData;
	var PlotNewData;

	var bounds;
	var geometry;

	var loadModalMap = function () {

		function initMap() {

			var viewLat = $("#modal_map").data('latitude');
			var viewLng = $("#modal_map").data('longitude');
			if(viewLat == '') viewLat=33.890156884426496;
			if(viewLng == '') viewLng=35.50199890136718;

			modalMap = new google.maps.Map(document.getElementById('modal_map'), {
				center: {lat: viewLat, lng: viewLng},
				zoom: 12,
				scrollwheel: true
			});

			var input = document.getElementById('pac-input');

			var latID = $("#modal_map").data('latitudeid');
			var lngID = $("#modal_map").data('longitudeid');

			var autocomplete = new google.maps.places.Autocomplete(input);

			// Set initial restrict to the greater list of countries.
			autocomplete.setComponentRestrictions(
				{'country': ['LB']}
			);

			marker = new google.maps.Marker({
				position:{lat: viewLat, lng: viewLng},
				map: modalMap,
				draggable:true,
				anchorPoint: new google.maps.Point(0, -29)
			});

			autocomplete.addListener('place_changed', function() {

				// infowindow.close();

				marker.setVisible(false);
				var place = autocomplete.getPlace();

				if (!place.geometry) {
					// User entered the name of a Place that was not suggested and
					// pressed the Enter key, or the Place Details request failed.
					window.alert("No details available for input: '" + place.name + "'");
					return;
				}

				// If the place has a geometry, then present it on a map.
				if (place.geometry.viewport) {
					modalMap.fitBounds(place.geometry.viewport);
					$('#'+latID).val(place.geometry.location.lat());
					$('#'+lngID).val(place.geometry.location.lng());
				} else {
					modalMap.setCenter(place.geometry.location);
					modalMap.setZoom(7);
				}
				marker.setPosition(place.geometry.location);
				marker.setVisible(true);

				var address = '';
				if (place.address_components) {
					address = [
						(place.address_components[0] && place.address_components[0].short_name || ''),
						(place.address_components[1] && place.address_components[1].short_name || ''),
						(place.address_components[2] && place.address_components[2].short_name || '')
					].join(' ');
				}
				// infowindow.open(map, marker);
			});
			google.maps.event.addListener(marker, 'dragend', function (event) {
				$('#'+latID).val(this.getPosition().lat());
				$('#'+lngID).val(this.getPosition().lng());
				$('#manual_latitude').val(this.getPosition().lat());
				$('#manual_longitude').val(this.getPosition().lng());
			});
		}
		return {
			//main function to initiate the module
			init: function() {
				initMap();
			}
		};
	}();

	function setMarkerOnMap(mapID, latID, lngID){
		var lat = parseFloat(document.getElementById(latID).value);
		var lng = parseFloat(document.getElementById(lngID).value);
		if(lat && lng){

			var mapLatID = $('#'+mapID).data('latitudeid');
			var mapLngID = $('#'+mapID).data('longitudeid');

			$('#'+mapLatID).val(lat);
			$('#'+mapLngID).val(lng);

			var map = new google.maps.Map(document.getElementById(mapID), {
				center: {lat: lat, lng: lng},
				zoom: 12,
				scrollwheel: false
			});
			marker = new google.maps.Marker({
				position:{lat: lat, lng: lng},
				map: map,
				draggable:true,
				anchorPoint: new google.maps.Point(0, -29)
			});
		}else{
			alert('Please Enter valid latitude and longitude');
		}

		// localStorage.setItem('mylat', lat);
		// localStorage.setItem('mylng', lng);
	}


	// function initAutocomplete() {
	// 	var map = new google.maps.Map(document.getElementById('modal_map'), {
	// 		center: {lat: -33.8688, lng: 151.2195},
	// 		zoom: 13,
	// 		mapTypeId: 'roadmap'
	// 	});
    //
	// 	// Create the search box and link it to the UI element.
	// 	var input = document.getElementById('pac-input');
	// 	var searchBox = new google.maps.places.SearchBox(input);
	// 	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    //
	// 	// Bias the SearchBox results towards current map's viewport.
	// 	map.addListener('bounds_changed', function() {
	// 		searchBox.setBounds(map.getBounds());
	// 	});
    //
	// 	var markers = [];
	// 	// Listen for the event fired when the user selects a prediction and retrieve
	// 	// more details for that place.
	// 	searchBox.addListener('places_changed', function() {
	// 		var places = searchBox.getPlaces();
    //
	// 		if (places.length == 0) {
	// 			return;
	// 		}
    //
	// 		// Clear out the old markers.
	// 		markers.forEach(function(marker) {
	// 			marker.setMap(null);
	// 		});
	// 		markers = [];
    //
	// 		// For each place, get the icon, name and location.
	// 		var bounds = new google.maps.LatLngBounds();
	// 		places.forEach(function(place) {
	// 			if (!place.geometry) {
	// 				return;
	// 			}
	// 			var icon = {
	// 				url: place.icon,
	// 				size: new google.maps.Size(71, 71),
	// 				origin: new google.maps.Point(0, 0),
	// 				anchor: new google.maps.Point(17, 34),
	// 				scaledSize: new google.maps.Size(25, 25)
	// 			};
    //
	// 			// Create a marker for each place.
	// 			markers.push(new google.maps.Marker({
	// 				map: map,
	// 				icon: icon,
	// 				title: place.name,
	// 				position: place.geometry.location
	// 			}));
    //
	// 			if (place.geometry.viewport) {
	// 				// Only geocodes have viewport.
	// 				bounds.union(place.geometry.viewport);
	// 			} else {
	// 				bounds.extend(place.geometry.location);
	// 			}
	// 		});
	// 		map.fitBounds(bounds);
	// 	});
	// }
</script>

{{--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKj9Yaoy4JVcFoj455Kz9IFeuHHyxMwM4&libraries=places&callback=initAutocomplete,drawing,geometry"></script>--}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKj9Yaoy4JVcFoj455Kz9IFeuHHyxMwM4&libraries=places"></script>
{{--<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=initAutocomplete" async defer></script>--}}
