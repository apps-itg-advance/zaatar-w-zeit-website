@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
@endsection

@section('content')
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-order-items col-favourite-items">
        <div class="col-xl-11 float-none mx-auto p-0">
            @include('customers._favourite_menu')
            @foreach($favouriteOrders as $row)
                @php
                    $discount=0;
                    $wallet=0;
                    $pay_online=0;
                    $total=0;
                    $method='';
                    $specials=array();
                    $adderss_array=array($row->Province,$row->City,$row->Line1,$row->Line2);
                @endphp
                @if(!$row->Favorite)
                    @continue
                @endif
                <div class="order-box p-3 fav-box-shadow">
                    <h4 class="title">
                        @lang('order') {{$row->OrderId}}
                        <span>{{$row->DeliveryTime ?? \Carbon\Carbon::parse($row->DeliveryTime)->format('d/m/Y - H:i')}}</span>
                    </h4>
                    <div class="order-info py-2 py-md-4 cursor-pointer" data-toggle="collapse"
                         data-target=".order-history-{{$row->OrderId}}">
                        <div class="row align-items-center">
                            <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666">
                                @lang('address')
                            </div>
                            <div class="col-sm-8 text-808080 futura-medium font-size-14">
                                @php
                                    $adderss_array=array($row->Province,$row->City,$row->Line1,$row->Line2);
                                @endphp
                                {{implode(', ',$adderss_array)}}
                            </div>
                        </div>

                        <div class="order-history-details order-history-{{$row->OrderId}} collapse">
                            <div class="row">
                                <div
                                    class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                    @lang('order')
                                </div>
                                <div class="col-sm-6 mt-1">
                                    <?php
                                    $item = isset($row->Items) ? $row->Items : array();
                                    $specials = array();
                                    for ($i = 0; $i < count($item); $i++) {
                                        $array_modifiers = array();
                                        if ($item[$i]->OpenItem == '0' and $item[$i]->MenuType == '1') {
                                            $amount = $item[$i]->GrossPrice;
                                            for ($j = $i + 1; $j < count($item); $j++) {
                                                if ($item[$j]->MenuType != '1') {
                                                    if ($item[$j]->MenuType != '5' and $item[$j]->MenuType != '6') {
                                                        array_push($array_modifiers, $item[$j]->ItemName);
                                                    }
                                                    $amount += $item[$j]->GrossPrice;
                                                } else {
                                                    break;
                                                }
                                            }
                                            echo '<div class="row mb-2">
                                                   <div class="col-md-8">
                                                       <h5 class="mb-0">' . $item[$i]->ItemName . '</h5>
                                                       <div class="text-808080">' . implode(', ', $array_modifiers) . '</div>
                                                   </div>
                                                   <div class="col-md-4"> <h5 class="mb-0" style="text-align: right !important;">' . number_format($amount) . '</h5></div>
                                               </div>';

                                        } elseif ($item[$i]->OpenItem == '1' and $item[$i]->PLU == '0') {
                                            array_push($specials, $item[$i]->ItemName);
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
                                        @lang('delivery_fee') <span class="price d-inline-block ml-4"
                                                                    style="width: 30% !important;">
                                            {{number_format($row->DeliveryCharge). ' '.$currency}}
                                </span>
                                    </div>
                                    <div class="total-block text-right">
                                        @lang('sub_total') <span class="price d-inline-block ml-4" style="width: 30%;">
                                            {{number_format($row->GrossAmount). ' '.$currency}}
                                </span>
                                    </div>

                                    <div class="total-block text-right">
                                        @lang('discount_large')
                                        <span class="price d-inline-block ml-4" style="width: 30% !important;">
                                            {{number_format($discount ?? 0). ' '.$currency}}
                                </span>
                                    </div>
                                    <div class="total-block text-right">
                                        @lang('wallet') <span class="price d-inline-block ml-4"
                                                              style="width: 30% !important;">
                                            {{number_format($wallet ?? 0). ' '.$currency}}
                                </span>
                                    </div>
                                    <div class="total-block text-right">
                                        @lang('payment')
                                        <span class="price d-inline-block ml-4" style="width: 30% !important;">
                                            {{number_format($pay_online ?? 0 ). ' '.$currency}}
                                </span>
                                    </div>

                                    <hr/>

                                    <div class="total-block text-right futura-b">
                                        @lang('total') <span class="price d-inline-block ml-4"
                                                             style="width: 30% !important;">
                                             {{number_format($total ?? 0 ). ' '.$currency}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @php
                                $open_items=(isset($row->OpenItems) and is_array($row->OpenItems)) ? $row->OpenItems : array();
                                $go_green=app('translator')->get('no');
                                $gift=app('translator')->get('no');
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
                                    <div
                                        class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                        @lang('wallet')
                                    </div>
                                    <div class="col-6 text-808080 mb-3 futura-book">
                                        {{$wallet>0? app('translator')->get('yes'):app('translator')->get('no')}}
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div
                                        class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                        @lang('gift')
                                    </div>
                                    <div class="col-6 text-808080 mb-3 futura-book">
                                        {{$gift}}
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div
                                        class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                        @lang('go_green')
                                    </div>
                                    <div class="col-6 text-808080 mb-3 futura-book">
                                        {{$go_green}}
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div
                                        class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                        @lang('method')
                                    </div>
                                    <div class="col-6 text-808080 mb-3 futura-book">
                                        {{$method ?? ''}}
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div
                                        class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                        @lang('special_instructions')
                                    </div>
                                    <div class="col-6 text-808080 mb-3 futura-book">
                                        {{isset($specials)? implode(' , ',$specials):''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="action-div text-right">
                        <a class="btn btn-orderrepeat" onclick="RepeatOrder({{$row->OrderId}})"><img
                                src="{{asset('assets/images/icon-refresh.png')}}" height="15"
                                class="mr-1"/>@lang('repeat_order')</a>
                    </div>
                    <a href="#" onclick="RemoveFavouriteOrder({{$row->OrderId}})" id="Favourite{{$row->OrderId}}"
                       class="link-close"><img src="{{asset('assets/svg/icon-close.svg')}}" width="24"></a>
                </div>
            @endforeach
        </div>
    </div>
    @include('partials.cart')
@endsection

@section('javascript')
    <script>
        function RemoveFavouriteOrder(orderId) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {order_id: orderId},
                url: '{{route('customer.remove.favourite-order')}}',
                success: function (data) {
                    Swal.fire({
                        // position: 'top-end',
                        icon: 'success',
                        title: '<?php echo app('translator')->get('your_favourite_order_removed_successfully.'); ?>',
                        showConfirmButton: false,
                        timer: 1200
                    });
                    $("#Favourite" + orderId).closest('.order-box').remove();
                }
            });
        }
    </script>
@endsection
