@extends('layouts.template')

@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/circle.css')}}">
@endsection


@section('javascript')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKj9Yaoy4JVcFoj455Kz9IFeuHHyxMwM4&libraries=places,drawing,geometry"></script>
    <script>
        @php
            if($type=='register')
        {
        echo "  jQuery('#editprofileModal').modal();";
        }
        @endphp
		$("body").on('click', '#edit-profile', function(){
			jQuery('#editprofileModal').modal();
		});
    </script>

    <script>
		var marker = null;
		var modalMap = null;

		$("#editprofileModal").on('shown.bs.modal',function(){
			if($("#modal_map").length>0){
				loadModalMap.init();
			}
		});

		var loadModalMap = function () {

			function initMap() {

				var viewLat = $("#modal_map").data('latitude');
				var viewLng = $("#modal_map").data('longitude');
				if(viewLat == '') viewLat=33.890156884426496;
				if(viewLng == '') viewLng=35.50199890136718;

				console.log('lat: '+viewLat);
				console.log('lon: '+viewLng);
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
    </script>
@endsection

@section('content')
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-favourite-items">

        <div class="col-xl-12 float-none p-0 mx-auto">
            <div class="title-div mb-4 pb-2">
                <h2 class="title">Loyalty</h2>
            </div>
            <div class="loyaltygraph-wrapper">
                <div class="user-div">
                    @php
                        $details=array();
                        if(isset($query->details))
                        {
                         $details=$query->details;
                        }
                    @endphp
                    <h4>{{@$details->FirstName.' '.@$details->LastName}}</h4>
                    <button type="button" id="edit-profile" class="btn btn-8DBF43 text-uppercase bg-white text-8DBF43 btn-1-anim">Edit Profile</button>
                    <br>
                    <button type="button" class="btn btn-8DBF43 bg-white text-uppercase mt-2 btn-1-anim"><a class="text-8DBF43" href="{{route('customer.order-history')}}">Order History</a></button>
                    <br>
                    <button type="button" class="btn btn-8DBF43 text-uppercase mt-2"><a href="{{route('logout')}}" class="cursor-pointer text-white">Logout</a></button>
                </div>

                <div class="col-xl-8 col-lg-10 float-none p-0 mx-auto loyaltygraph-div pt-4 mb-5 pb-3">
                    <div class="row">
                        @php
                            if($query->details->LevelMaxCollection> $query->details->TierBalance)
                            {
                             $per=intval(($query->details->TierBalance*100)/$query->details->LevelMaxCollection);
                            }
                        else{
                            $per=100;
                        }


                        @endphp

                        <div  class="col-md-3 d-flex align-items-end">
                            <div style="font-size: 16px !important;">
                                @php
                                    isset($next_level->NeededPoints) ? $next_level->NeededPoints.' Points left' : ''
                                @endphp
                            </div>

                        </div>
                        <div  class="col-md-6">
                            <div class="c100 p{{$per}} big green">
                                <img src="/assets/images/arrow-down.png"  class="{{($query->details->Threshold> $query->details->TierBalance) ? '':'d-none'}}" alt="zwz profile arrow">
                                <span><div style="font-size: 68px !important;">{{$query->details->LevelName}} <br> <small>{{number_format($query->details->TierBalance)}} points</small></div></span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-3 d-flex align-items-end">
                            <div style="font-size: 16px !important;">
                                @if($query->details->Threshold> $query->details->TierBalance)
                                    COLLECT {{$query->details->Threshold-$query->details->TierBalance}} points for a {{$query->details->TotalOp}} % discount
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-xl-10 col-lg-10 float-none p-0 mx-auto wallet-wrapper">
                    @include('customers._vouchers',array('vouchers'=>$vouchers,'checkout'=>false))
					<?php /*
                        <div class="title-div mb-4">
                            <h2 class="title">Wallet</h2>
                        </div>
                        @php
                            $array_colors=array('bg-AFD27C','bg-9DBFC1','bg-808080','bg-8DBF43');
                        @endphp
                        <div id="wallet-carousel" class="owl-carousel wallet-carousel">
                            @for($i=0;$i<count($vouchers);$i++)
                                @php
                                    $rand = array_rand($array_colors, 1);
                                    $type_l=$vouchers[$i]['ValueType']=='percentage' ? '%':'';
                                @endphp
                            <div class="item {{$array_colors[$rand]}}" data-mh="matchHeight">
                                <div class="item-div text-white p-4">
                                    <div class="py-4 item-quantity text-right">
                                        <div class="float-right">{{$vouchers[$i]['Qty']}} quantity</div>
                                    </div>
                                    <div class="item-discount text-uppercase">
                                        {{$vouchers[$i]['Value'].$type_l}} Discount
                                    </div>
                                    <div class="items-vouchers">
                                        @foreach($vouchers[$i]['ExpiryDates'] as $key=>$value)
                                            <div class="voucher mb-1">{{$value}} vouchers expire {{$key}}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                         */ ?>
                </div>
            </div>
        </div>
    </div>
    @include('partials.cart')
    @include('customers._edit_profile')
@endsection
