@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
    <style>
        .link-favourite{
            margin-right: 10%;
        }
    </style>
@endsection

@section('content')
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-order-items col-favourite-items">

        <div class="col-xl-11 float-none mx-auto p-0">

            <br>
            <h4 class="title-1">Order History</h4>

            @foreach($query as $row)
                <div class="order-box p-3 favourite-box">
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
<!--                                --><?php //dd($adderss_array); ?>
                                {{implode(', ',$adderss_array)}}
                            </div>
                        </div>
                    </div>
                    <div class="action-div text-right">
<!--                        --><?php //dd($query); ?>
                        <?php
                            $active_f='';
                           if($row->Favorite=='1')
                               {
                                   $active_f='active';
                               }
                        ?>
                        <a onclick="SetFavouriteOrder({{$row->OrderId}})" id="Favourite{{$row->OrderId}}" href="javascript:void(0)" class="effect-underline link-favourite {{$active_f}}">Favourite</a>
                        <a class="btn btn-orderrepeat"><img src="{{asset('assets/images/icon-refresh.png')}}" height="15" class="mr-1"/> Repeat Order</a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
    @include('partials.cart')
@endsection

@section('javascript')
    <script>
	    function SetFavouriteOrder(orderId)
	    {
		    // console.log(item.ID);
		    if($("#Favourite" + orderId).hasClass('href-disabled') || $("#Favourite" + orderId).hasClass('active')){
			    return null;
		    }
		    $("#Favourite" + orderId).addClass('href-disabled');
		    $.ajax({
			    headers: {
				    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
			    type:'POST',
			    data:{order_id:orderId},
			    url:'{{route('customer.set.favourite-order')}}',
			    success:function(data){
				    Swal.fire({
					    // position: 'top-end',
					    icon: 'success',
					    title: 'Your favourite order was added successfully.',
					    showConfirmButton: false,
					    timer: 1200

				    });
				    $("#Favourite" + orderId).removeClass('href-disabled').addClass('active');
			    }
		    });
	    }
    </script>
@endsection