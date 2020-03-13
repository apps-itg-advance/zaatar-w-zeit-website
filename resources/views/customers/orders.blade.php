@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
@endsection

@section('content')
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-order-items col-favourite-items">

        <div class="col-xl-11 float-none mx-auto p-0">

            @include('customers._favourite_menu')
            @foreach($query as $row)
                <div class="order-box p-3 fav-box-shadow">
                    <h4 class="title">
                        ORDER {{$row->OrderId}}
                        <span>{{$row->DeliveryTime}}</span>
                    </h4>
                    <div class="order-info py-2 py-md-4">
                        <div class="row align-items-center">
                            <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666">
                                Address
                            </div>
                            <div class="col-sm-8 text-808080 futura-medium font-size-14">
                                @php( $adderss_array=array($row->Province,$row->City,$row->Line1,$row->Line2))
                                {{implode(', ',$adderss_array)}}
                            </div>
                        </div>
                    </div>
                    <div class="action-div text-right">
                        <a class="btn btn-orderrepeat"><img src="{{asset('assets/images/icon-refresh.png')}}" height="15" class="mr-1"/> Repeat Order</a>
                    </div>
                    <a href="#" onclick="RemoveFavouriteOrder({{$row->OrderId}})" id="Favourite{{$row->OrderId}}" class="link-close"><img src="{{asset('assets/svg/icon-close.svg')}}" width="24"></a>
                </div>
            @endforeach
        </div>

    </div>
    @include('partials.cart')
@endsection

@section('javascript')
    <script>
		function RemoveFavouriteOrder(orderId)
		{
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type:'POST',
				data:{order_id:orderId},
				url:'{{route('customer.remove.favourite-order')}}',
				success:function(data){
					Swal.fire({
						// position: 'top-end',
						icon: 'success',
						title: 'Your favourite order was removed successfully.',
						showConfirmButton: false,
						timer: 1200
					});
					$("#Favourite" + orderId).closest('.order-box').remove();
				}
			});
		}
    </script>
@endsection
