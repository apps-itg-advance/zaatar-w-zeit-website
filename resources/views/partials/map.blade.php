<script>
	var marker = null;
	var infoWindow;
	var map = null;
	var modalMap = null;

	var loadModalMap = function () {

		function initMap(id) {
			var viewLat = $("#modal_map"+id).data('latitude');
			var viewLng = $("#modal_map"+id).data('longitude');
			if(viewLat == '') viewLat=33.890156884426496;
			if(viewLng == '') viewLng=35.50199890136718;

			modalMap = new google.maps.Map(document.getElementById('modal_map'+id), {
				center: {lat: viewLat, lng: viewLng},
				zoom: 12,
				scrollwheel: true
			});

			var latID = $("#modal_map"+id).data('latitudeid');
			var lngID = $("#modal_map"+id).data('longitudeid');

			marker = new google.maps.Marker({
				position:{lat: viewLat, lng: viewLng},
				map: modalMap,
				draggable:true,
				anchorPoint: new google.maps.Point(0, -29)
			});

			google.maps.event.addListener(marker, 'dragend', function (event) {
				$('#'+latID).val(this.getPosition().lat());
				$('#'+lngID).val(this.getPosition().lng());
				$('#manual_latitude'+id).val(this.getPosition().lat());
				$('#manual_longitude'+id).val(this.getPosition().lng());
			});
		}
		return {
			//main function to initiate the module
			init: function(id) {
				initMap(id);
			}
		};
	}();

	function currentLocation(id) {
		// Try HTML5 geolocation.
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (position) {
				var pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};

				infoWindow = new google.maps.InfoWindow;

				var lat = position.coords.latitude;
				var lng = position.coords.longitude;

				$('#modal_latitude'+id).val(lat);
				$('#modal_longitude'+id).val(lng);

				var map = new google.maps.Map(document.getElementById('modal_map'+id), {
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

				google.maps.event.addListener(marker, 'dragend', function (event) {
					$('#modal_latitude'+id).val(this.getPosition().lat());
					$('#modal_longitude'+id).val(this.getPosition().lng());
					$('#manual_latitude'+id).val(this.getPosition().lat());
					$('#manual_longitude'+id).val(this.getPosition().lng());
				});

				infoWindow.setPosition(pos);
				infoWindow.setContent('Location found.');
				infoWindow.open(modalMap);
				modalMap.setCenter(pos);
			}, function () {
				handleLocationError(true, infoWindow, modalMap.getCenter());
			});
		} else {
			// Browser doesn't support Geolocation
			handleLocationError(false, infoWindow, modalMap.getCenter());
		}
	}

	function handleLocationError(browserHasGeolocation, infoWindow, pos) {
		if(browserHasGeolocation){
			Swal.fire({
				// position: 'top-end',
				icon: 'warning',
				title: '<?php echo app('translator')->get('please_allow_geolocation_service_browser.'); ?>',
				showConfirmButton: false,
				timer: 1200
			});
		}else{
			Swal.fire({
				// position: 'top-end',
				icon: 'warning',
				title: "<?php echo app('translator')->get('your_browser_dont_support_geolocation.'); ?>",
				showConfirmButton: false,
				timer: 1200
			});
			infoWindow.setPosition(pos);
			infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
			infoWindow.open(map);
		}
	}


	// function setMarkerOnMap(mapID, latID, lngID){
	// 	var lat = parseFloat(document.getElementById(latID).value);
	// 	var lng = parseFloat(document.getElementById(lngID).value);
	// 	if(lat && lng){
	//
	// 		var mapLatID = $('#'+mapID).data('latitudeid');
	// 		var mapLngID = $('#'+mapID).data('longitudeid');
	//
	// 		$('#'+mapLatID).val(lat);
	// 		$('#'+mapLngID).val(lng);
	//
	// 		var map = new google.maps.Map(document.getElementById(mapID), {
	// 			center: {lat: lat, lng: lng},
	// 			zoom: 12,
	// 			scrollwheel: false
	// 		});
	// 		marker = new google.maps.Marker({
	// 			position:{lat: lat, lng: lng},
	// 			map: map,
	// 			draggable:true,
	// 			anchorPoint: new google.maps.Point(0, -29)
	// 		});
	//
	// 		google.maps.event.addListener(marker, 'dragend', function (event) {
	// 			$('#modal_latitude').val(this.getPosition().lat());
	// 			$('#modal_longitude').val(this.getPosition().lng());
	// 			$('#manual_latitude').val(this.getPosition().lat());
	// 			$('#manual_longitude').val(this.getPosition().lng());
	// 		});
	//
	// 	}else{
	// 		alert('Please Enter valid latitude and longitude');
	// 	}
	//
	// 	// localStorage.setItem('mylat', lat);
	// 	// localStorage.setItem('mylng', lng);
	// }


</script>

<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDKj9Yaoy4JVcFoj455Kz9IFeuHHyxMwM4"></script>

{{--<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=initAutocomplete" async defer></script>--}}
