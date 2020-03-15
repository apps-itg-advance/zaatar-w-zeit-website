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
                @php
                   $discount=0;
                   $wallet=0;
                   $pay_online=0;
                   $total=0;
                   $method='';
                   $specials=array();
                    $adderss_array=array($row->Province,$row->City,$row->Line1,$row->Line2)
                @endphp
                <div class="order-box p-3 favourite-box">
                    <h4 class="title">
                        ORDER {{$row->OrderId}}
                        <span>{{$row->OrderDate}}</span>
                    </h4>
                    <div class="order-info py-2 py-md-4 cursor-pointer" data-toggle="collapse" data-target=".order-history-{{$row->OrderId}}">
                        <div class="row align-items-center mb-3">
                            <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666">
                                Address
                            </div>
                            <div class="col-sm-8 text-808080 futura-medium font-size-14">
                                {{implode(', ',$adderss_array)}}
                            </div>
                        </div>
                        <div class="order-history-details order-history-{{$row->OrderId}} collapse">
                            <div class="row">
                                <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                    Order
                                </div>
                                <div class="col-sm-6">
                                <?php
                                    foreach($row->Items as $item){

                                          $specials=array();
                                           if($item->OpenItem=='0'){

                                               echo '<div class="row">
                                                   <div class="col-md-8">
                                                       <h5 class="mb-0">'.$item->ItemName.'</h5>
                                                       <div class="text-808080"></div>
                                                   </div>
                                                   <div class="col-md-4"> <h5 class="mb-0" style="text-align: right !important;">'.number_format($item->UnitPrice).'</h5></div>
                                               </div>';
                                               }
                                           else{
                                               array_push($specials,$item);
                                           }
                                    }
                                    ?>
                                </div>
                            </div>
                            @php
                                $payments=(isset($row->Payments) and is_array($row->Payments)) ? $row->Payments:array();

                                   foreach ($payments as $payment)
                                    {
                                        if($payment->PaymentName=='voucher')
                                        {
                                        $discount=$payment->PaymentAmount;
                                        }
                                        elseif ($payment->PaymentName=='wallet')
                                         {
                                            $wallet=$payment->PaymentAmount;
                                         }
                                        elseif($payment->PaymentName=='credit')
                                        {
                                            $pay_online=$payment->PaymentAmount;
                                            $total=$total+$payment->PaymentAmount;
                                            $method=$payment->PaymentLabel;
                                        }
                                        else{
                                            $total=$total+$payment->PaymentAmount;
                                            $method=$payment->PaymentLabel;
                                        }
                                }
                            @endphp

                            <div class="row mt-3">
                                <div class="col-md-8 offset-2">
                                    <div class="total-block text-right">
                                        Delivery fee <span class="price d-inline-block ml-4" style="width: 30% !important;">
                                            {{number_format($row->DeliveryCharge). ' '.$currency}}
                                </span>
                                    </div>
                                    <div class="total-block text-right">
                                        SubTotal <span class="price d-inline-block ml-4" style="width: 30% !important;">
                                            {{number_format($row->GrossAmount). ' '.$currency}}
                                </span>
                                    </div>

                                    <div class="total-block text-right">
                                        Discount <span class="price d-inline-block ml-4" style="width: 30% !important;">
                                            {{number_format($discount ?? 0). ' '.$currency}}
                                </span>
                                    </div>
                                    <div class="total-block text-right">
                                        Wallet <span class="price d-inline-block ml-4" style="width: 30% !important;">
                                            {{number_format($wallet ?? 0). ' '.$currency}}
                                </span>
                                    </div>
                                    <div class="total-block text-right">
                                        Payment <span class="price d-inline-block ml-4" style="width: 30% !important;">
                                            {{number_format($pay_online ?? 0 ). ' '.$currency}}
                                </span>
                                    </div>

                                    <hr/>

                                    <div class="total-block text-right futura-b">
                                        Total <span class="price d-inline-block ml-4" style="width: 30% !important;">
                                             {{number_format($total ?? 0 ). ' '.$currency}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @php
                                $open_items=(isset($row->OpenItems) and is_array($row->OpenItems)) ? $row->OpenItems : array();
                                $go_green='No';
                                $gift='No';
                                foreach ($open_items as $open_i)
                                {
                                    if(isset($open_i->Label))
                                    {
                                        if($open_i->Label=='Real Green')
                                        {
                                            $go_green=$open_i->Value;
                                        }
                                        elseif($open_i->Label=='Gift')
                                        {
                                            $gift=$open_i->Value;
                                        }
                                    }
                                }
                            @endphp
                            <div class="order-info">
                                <div class="row align-items-center">
                                    <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                        Wallet
                                    </div>
                                    <div class="col-6 text-808080 mb-3 futura-book">
                                        {{$wallet>0? 'Yes':'No'}}
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                        Gift
                                    </div>
                                    <div class="col-6 text-808080 mb-3 futura-book">
                                        {{$gift}}
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                        Go Green
                                    </div>
                                    <div class="col-6 text-808080 mb-3 futura-book">
                                        {{$go_green}}
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                        Method
                                    </div>
                                    <div class="col-6 text-808080 mb-3 futura-book">
                                        {{$method ?? ''}}
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                        Special Instructions
                                    </div>
                                    <div class="col-6 text-808080 mb-3 futura-book">
                                        {{isset($specials[0]->ItemName)?$specials[0]->ItemName:''}}
                                    </div>
                                </div>
                            </div>







                        </div>
                    </div>


                    <div class="action-div text-right">
						<?php //dd($query); ?>
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