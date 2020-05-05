@extends('layouts.template')
@section('css')
	<link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/circle.css')}}">
	<style>
		/* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
		#map {
			height: 100%;
		}
		/* Optional: Makes the sample page fill the window. */
		html, body {
			height: 100%;
			margin: 0;
			padding: 0;
		}
		#description {
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
		}

		#infowindow-content .title {
			font-weight: bold;
		}

		#infowindow-content {
			display: none;
		}

		#map #infowindow-content {
			display: inline;
		}

		.pac-card {
			margin: 10px 10px 0 0;
			border-radius: 2px 0 0 2px;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			outline: none;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
			background-color: #fff;
			font-family: Roboto;
		}

		#pac-container {
			padding-bottom: 12px;
			margin-right: 12px;
		}

		.pac-controls {
			display: inline-block;
			padding: 5px 11px;
		}

		.pac-controls label {
			font-family: Roboto;
			font-size: 13px;
			font-weight: 300;
		}

		#pac-input {
			background-color: #fff;
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
			margin-left: 12px;
			padding: 0 11px 0 13px;
			text-overflow: ellipsis;
			width: 400px;
		}

		#pac-input:focus {
			border-color: #4d90fe;
		}

		#title {
			color: #fff;
			background-color: #4d90fe;
			font-size: 25px;
			font-weight: 500;
			padding: 6px 12px;
		}
		#target {
			width: 345px;
		}
	</style>
@endsection
@section('content')
	<div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-favourite-items">
		<div class="col-xl-11 float-none p-0 mx-auto">
			<div class="title-div mb-2 pb-2">
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
				<div class="col-xl-11 col-lg-10 float-none p-0 mx-auto wallet-wrapper">
					<div class="title-div mb-4">
						<h2 class="title">Wallet</h2>
					</div>
					<div id="wallet-carousel" class="owl-carousel wallet-carousel">
						@php
							$array_colors=array('bg-AFD27C','bg-9DBFC1','bg-808080');
						@endphp
						@for($i=0;$i<count($vouchers);$i++)
							@php
								$rand = array_rand($array_colors, 1);
                               // $type_l=$vouchers[$i]['ValueType']=='percentage' ? '%':'';
							@endphp
							<div class="item {{$array_colors[$rand]}}"  id="voucher-b{{$vouchers[$i]->Id}}" data-mh="matchHeight">
								<div class="item-div text-white p-3" id="voucher-b1{{$vouchers[$i]->Id}}">
									<div class="py-4 item-quantity text-right">
										<div class="float-right" ><span class="qty{{$vouchers[$i]->Id}}" data-title="{{count($vouchers[$i]->Vouchers)}}">{{count($vouchers[$i]->Vouchers)}}</span> quantity</div>
									</div>
									<div class="item-discount text-uppercase title-{{$vouchers[$i]->Id}}" data-title="{{$vouchers[$i]->Title}}">
										{{$vouchers[$i]->Title}}
									</div>
									@php
										$v=$vouchers[$i]->Vouchers;
                                        for ($j=0;$j<count($v);$j++)
                                        {
                                            $qty=1;
                                            for($k=$j+1;$k<count($v);$k++)
                                            {
                                                if($v[$j]->ExpiryDate==$v[$k]->ExpiryDate)
                                                {
                                                $qty++;
                                                }
                                            }
                                            $array_exp[$v[$j]->ExpiryDate]=$qty;
                                        }
									@endphp
									<div class="items-vouchers">
										@php $css='vqty'.$vouchers[$i]->Id; @endphp
										@foreach($array_exp as $key=>$value)
											<div class="voucher"><span class="{{$css}}" data-title="{{$value}}">{{$value}}</span>  vouchers expire {{$key}}</div>
											@php $css='' @endphp
										@endforeach

									</div>
									<p><img src="{{asset('assets/images/icon-logowhite.png')}}" class="w-auto logo-img"></p>
									<div class="buttons text-center mt-3">
									</div>
								</div>

							</div>

						@endfor
					</div>
				</div>
				<div class="clearfix" style="margin-bottom: 30px !important;"></div>
			</div>
		</div>
	</div>

	@include('partials.cart')
    @include('customers._edit_profile')


@endsection
@section('javascript')
	<script src="{{asset('assets/js/owl.carousel.min.js')}}"></script>
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


		$("#editprofileModal").on('shown.bs.modal',function(){
			if($("#modal_map").length>0){
				loadModalMap.init();
			}
		});
		$(document).ready(function(){
			$('.owl-carousel').owlCarousel({
				loop : false,
				navText : ['', ''],
				margin : 40,
				dots : false,
				nav : true,
				responsive:{
					0:{
						items:1
					},
					575:{
						items:1
					},
					767:{
						items:2
					},
					991:{
						items:1
					},
					1200:{
						items:2
					},
					1500:{
						items:2
					}
				}
			})
		});
	</script>
@endsection