@php $fav=$favourite ? 1: 0;
@endphp
@foreach($query as $row)
    @php
        $discount=0;
        $wallet=0;
        $pay_online=0;
        $total=0;
        $method='';
        $specials=array();
        $adderss_array=array($row->City,$row->Line1,$row->Line2,$row->Apartment,$row->CompanyName);


    @endphp
    @if($favourite and !$row->Favorite)
        @continue
    @endif
    <div class="order-box p-3 favourite-box data-row">
        <h4 class="title">
            ORDER {{$row->OrderId}}
            <span>{{\Carbon\Carbon::parse($row->OrderDate)->format('d/m/Y - H:i')}}</span>
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
                    <div class="col-sm-6 mt-1">
                        <?php
                        $item=isset($row->Items) ? $row->Items : array();
                        $specials=array();
                        for($i=0;$i<count($item);$i++){
                            $meal_display='';
                            $array_modifiers=array();
                            if($item[$i]->OpenItem=='0' and $item[$i]->MenuType=='1'){
                                $amount=$item[$i]->GrossPrice;
                                for($j=$i+1;$j<count($item);$j++)
                                {
                                    if($item[$j]->MenuType!='1')
                                    {
                                        if($item[$j]->MenuType!='5' and $item[$j]->MenuType!='6')
                                        {
                                            array_push($array_modifiers,$item[$j]->ItemName);
                                        }
                                        elseif($item[$j]->MenuType=='5')
                                            {
                                              $meal_display='<div class="speacial-meal">MEAL <span class="d-inline-block mx-3">'.$item[$j]->ItemName.'</span><span class="d-inline-block">'.number_format($item[$j]->GrossPrice).'</span></div>';
                                            }
                                        $amount+=$item[$j]->GrossPrice;
                                    }
                                    else{
                                        break;
                                    }
                                }
                                echo '<div class="row mb-2">
                                                   <div class="col-md-8">
                                                       <h5 class="mb-0">'.$item[$i]->ItemName.'</h5>
                                                       <div class="text-808080">'.implode(', ',$array_modifiers).'</div>
                                                       '.$meal_display.'
                                                   </div>
                                                   <div class="col-md-4"> <h5 class="mb-0" style="text-align: right !important;">'.number_format($amount).'</h5></div>
                                               </div>';

                            }
                            elseif($item[$i]->OpenItem=='1' and $item[$i]->ItemId=='0'){
                                array_push($specials,$item[$i]->ItemName);
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
                               // $total=$total+$payment->PaymentAmount;
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
                                //$go_green=$open_i->Value;
                                $info=isset($open_i->Info) ? $open_i->Info:array();
                                $go_green='';
                                foreach ($info as $i_info)
                                {
                                    $go_green.=$i_info->ItemName.' ';
                                }
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
                            {{isset($specials)? implode(' , ',$specials):''}}
                        </div>
                    </div>
                    @if($row->ScheduleTime!='0000-00-00 00:00:00')
                    <div class="row align-items-center">
                        <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Scheduled
                        </div>
                        <div class="col-6 text-808080 mb-3 futura-book">
                            {{$row->ScheduleTime}}
                        </div>
                    </div>
                    @endif
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
            @if(!$favourite)
                <a onclick="SetFavouriteOrder({{$row->OrderId}})" id="Favourite{{$row->OrderId}}" href="javascript:void(0)" class="effect-underline link-favourite {{$active_f}}">Favourite</a>
            @endif
                <a class="btn btn-orderrepeat" onclick="RepeatOrder({{$row->OrderId}})"><img src="{{asset('assets/images/icon-refresh.png')}}" height="15" class="mr-1"/> Repeat Order</a>
        </div>
        @if($favourite)
            <a href="#" onclick="RemoveFavouriteOrder({{$row->OrderId}})" id="Favourite{{$row->OrderId}}" class="link-close"><img src="{{asset('assets/svg/icon-close.svg')}}" width="24"></a>
        @endif
    </div>
@endforeach
@if($row_total>3)
<h1 class="load-more">Load More</h1>
<input type="hidden" id="row" value="0">
<input type="hidden" id="all" value="{{$row_total}}">
@endif
@section('javascript')
    @parent
    <script>
        function RepeatOrder(orderId)
        {
        $.ajax({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:'POST',
        data:{order_id:orderId},
        url:'{{route('customer.order.repeat')}}',
        success:function(data){
        LoadCart();
         _getCountCartItems();
      /*  Swal.fire({
        // position: 'top-end',
        icon: 'success',
        title: 'Your favourite order was removed successfully.',
        showConfirmButton: false,
        timer: 1200
        }); */
        // $("#Favourite" + orderId).closest('.order-box').remove();
        }
        });
        }
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
        $(document).ready(function(){
            var limit=Number({{$limit}});
            // Load more data
            $('.load-more').click(function(){
                var row = Number($('#row').val());
                var allcount = Number($('#all').val());

                row = row + limit;

                if(row <= allcount){
                    $("#row").val(row);

                    $.ajax({
                        url: '{{route('customer.order.more')}}',
                        type: 'get',
                        data: {row:row,fav:{{$fav}}},
                        beforeSend:function(){
                            $(".load-more").text("Loading...");
                        },
                        success: function(response){

                            // Setting little delay while displaying new content
                            setTimeout(function() {
                                // appending posts after last post with class="post"
                                $(".data-row:last").after(response).show().fadeIn("slow");

                                var rowno = row + 3;
                                // checking row value is greater than allcount or not
                                if(rowno > allcount){

                                    // Change the text and background
                                    $('.load-more').hide();
                                   // $('.load-more').css("background","darkorchid");
                                }else{
                                    $(".load-more").text("Load more");
                                }
                            }, 2000);


                        }
                    });
                }else{
                    alert('test');
                    $('.load-more').text("Loading...");

                    // Setting little delay while removing contents
                    setTimeout(function() {

                        // When row is greater than allcount then remove all class='post' element after 3 element
                        $('.data-row:nth-child(limit)').nextAll('.data-row').remove().fadeIn("slow");

                        // Reset the value of row
                        $("#row").val(0);

                        // Change the text and background
                        $('.load-more').text("Load more");
                       // $('.load-more').css("background","#15a9ce");

                    }, 2000);


                }

            });

        });
    </script>
@endsection
