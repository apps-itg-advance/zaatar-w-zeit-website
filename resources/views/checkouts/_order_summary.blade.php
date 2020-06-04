@php
    $_address=array($cart_info->City,$cart_info->Line1,$cart_info->Line2,$cart_info->Apartment,$cart_info->Company);
    $_total=$delivery_charge;
$discount=0;

@endphp
<form id="PlaceOrder" action="#" method="post">
    @csrf
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header col-xl-12 float-none mx-auto border-bottom-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-0 col-xl-12 orders-wrapper float-none mx-auto">
                <div class="row">
                    <div class="col-md-6 offset-2">
                        <h2 class="futura-medium">@lang("order_summary")</h2>
                    </div>
                </div>
                <div class="order-box">
                    <div class="order-info pt-2 pt-md-4">
                        <div class="row">
                            <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('address')
                            </div>
                            <div class="col-sm-6 text-808080 mb-3 futura-book">
                                {{implode(', ',$_address)}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('order')
                            </div>
                            <div class="col-sm-6">
                                @foreach($cart as $key=>$values)
                                    @php
                                        $price=$values['price'];
                                        if(isset($cart_vouchers['ItemPlu']))
                                        {
                                        if($values['plu']==$cart_vouchers['ItemPlu'])
                                            {
                                                 if($cart_vouchers['ValueType']=='percentage')
                                                    {
                                                        $discount=$values['price']*$cart_vouchers['Value']/100;
                                                    }
                                                    elseif($cart_vouchers['ValueType']=='flat_rate')
                                                    {
                                                        $discount=$values['price'];
                                                    }
                                            }
                                    }
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-8"><h5 class="mb-0"> {{htmlspecialchars_decode($values['name'])}}</h5>
                                            <div class="text-808080">
                                                @php
                                                    $modifiers=$values['modifiers'];
                                                    $_total+=$values['price']*$values['quantity'];
                                                    $md_array=array();
                                                    for($i=0;$i<count($modifiers);$i++)
                                                    {
                                                        array_push($md_array,$modifiers[$i]['name']);
                                                    }
                                                @endphp
                                                {{implode(', ',$md_array )}}

                                            </div>
                                            @if(isset($values['meal']['name']))
                                                @php
                                                    $meal=$values['meal'];
                                                @endphp
                                                @if($meal!=null)
                                                    <div class="speacial-meal">
                                                        @lang('meal') <span class="d-inline-block mx-3">{{$meal['name']}}</span><span class="d-inline-block">{{number_format($meal['price'])}}</span>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="col-md-4"> <h5 class="mb-0" style="text-align: right;">{{number_format($price)}}</h5></div>
                                    </div>
                              @endforeach
                            </div>
                        </div>
                    </div>
                    @php
                        if(isset($cart_vouchers['Id']) and $cart_vouchers['Id']!=null and $cart_vouchers['ItemPlu']==0)
                            {
                                if($cart_vouchers['ValueType']=='percentage')
                                {
                                    $discount=($_total-$cart_wallet)*$cart_vouchers['Value']/100;
                                }
                                elseif($cart_vouchers['ValueType']=='flat_rate')
                                {
                                    $discount=$cart_vouchers['Value'];
                                }
                    }
                     $payment=(isset($cart_payment->Name) and $cart_payment->Name=='credit')? ($_total-$cart_wallet-$discount):0;

                    @endphp
                    <div class="row">
                        <div class="col-md-8 offset-2">
                            <hr/>
                            <div class="total-block text-right">
                                @lang('delivery_fee') <span class="price d-inline-block ml-4" style="width: 30% !important;">{{number_format($delivery_charge)}} {{$currency}}</span>
                            </div>
                            <div class="total-block text-right">
                                @lang('sub_total') <span class="price d-inline-block ml-4" style="width: 30% !important;">{{number_format($_total)}} {{$currency}}</span>
                            </div>
                            <div class="total-block text-right">
                                @lang('discount_large')
                                <span class="price d-inline-block ml-4" style="width: 30% !important;">{{number_format($discount)}} {{$currency}}</span>
                            </div>
                            @if(isset($LEVEL_ID) and $LEVEL_ID!='')
                            <div class="total-block text-right">
                                @lang('wallet') <span class="price d-inline-block ml-4" style="width: 30% !important;">{{number_format($cart_wallet)}} {{$currency}}</span>
                            </div>
                            @endif
                            <div class="total-block text-right">
                                @lang('payment')
                                <span class="price d-inline-block ml-4" style="width: 30% !important;">{{number_format($payment)}} {{$currency}}</span>
                            </div>
                            <hr/>
                            <div class="total-block text-right">
                                @lang('total') <span class="price d-inline-block ml-4" style="width: 30% !important;">{{number_format($_total-$cart_wallet-$discount-$payment)}} {{$currency}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-info">
                        @if(isset($LEVEL_ID) and $LEVEL_ID!='')
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('wallet')
                            </div>
                            <div class="col-6 text-808080 mb-3 futura-book">{{$cart_wallet >0 ? 'Yes':app('translator')->get('no')}}
                                @php
                                   /* if(isset($cart_vouchers['Id']) and $cart_vouchers['Id']!='')
                                    {
                                        if($cart_vouchers['ValueType']=='percentage')
                                        {
                                            echo $cart_vouchers['Value'] .' %';
                                        }
                                        elseif($cart_vouchers['ValueType']=='flat_rate')
                                        {
                                            echo ' - '.number_format($cart_vouchers['Value']).' '.$currency;
                                        }
                                        else{
                                        echo $cart_vouchers['Value'];
                                        }
                                       //  echo ' From : '.$cart_gift->GiftFrom.'<br> To : '.$cart_gift->GiftTo.'<br> Message : '.$cart_gift->GiftOpenItem;
                                    }
*/
                                @endphp
                            </div>
                        </div>
                        @endif
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('gift')
                            </div>
                            <div class="col-6 text-808080 mb-3 futura-book">
                                @php
                                    if(isset($cart_gift->GiftOpenItem) and $cart_gift->GiftOpenItem!='')
                                    {
                                        echo 'Yes';
                                       //  echo ' From : '.$cart_gift->GiftFrom.'<br> To : '.$cart_gift->GiftTo.'<br> Message : '.$cart_gift->GiftOpenItem;
                                    }
                                    else{
                                        echo app('translator')->get('no');
                                    }
                                @endphp
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('go_green')
                            </div>
                            <div class="col-6 text-808080 mb-3 futura-book">
                                @php
                                    if(isset($cart_green['Title']) and $cart_green['Title']!='')
                                    {
                                        echo $cart_green['Title'];
                                       //  echo ' From : '.$cart_gift->GiftFrom.'<br> To : '.$cart_gift->GiftTo.'<br> Message : '.$cart_gift->GiftOpenItem;
                                    }
                                    else{
                                        echo app('translator')->get('no');
                                    }
                                @endphp
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('method')
                            </div>
                            <div class="col-6 text-808080 mb-3 futura-book">
                                @php
                                    if(isset($cart_payment->PaymentId) and $cart_payment->PaymentId!='')
                                    {
                                        echo $cart_payment->Label;
                                       //  echo ' From : '.$cart_gift->GiftFrom.'<br> To : '.$cart_gift->GiftTo.'<br> Message : '.$cart_gift->GiftOpenItem;
                                    }
                                    else{
                                        echo 'N/A';
                                    }
                                @endphp
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('special_instructions')
                            </div>
                            <div class="col-6 text-808080 mb-3 futura-book">
                                @php
                                    $array_sp=array();
                                        if(isset($cart_sp_instructions[0]['Title']) and $cart_sp_instructions[0]['Title']!='')
                                        {
                                            foreach ($cart_sp_instructions as $spi)
                                            {
                                              array_push($array_sp,$spi['Title']);
                                            }
                                        }
                                        if(count($array_sp)>0)
                                        {
                                         echo implode(' , ',$array_sp);
                                        }
                                @endphp
                            </div>
                        </div>
                        @if(isset($order_schedule) and $order_schedule=='schedule')
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('scheduled')
                            </div>
                            <div class="col-6 text-808080 mb-3 futura-book">{{$schedule_date}}</div>
                        </div>
                      @endif
                    </div>
                </div>
                <a class="btn btn-8DBF43 mb-3 text-uppercase confirm float-right futura-book btn-confirm">@lang('confirm')</a>
                <button type="button" style="margin-right: 10px" class="btn btn-B3B3B3 mb-3 text-uppercase float-right futura-book btn-confirm cancel" data-dismiss="modal">@lang('cancel')</button>

            </div>
        </div>
    </div>
</form>
<script type="text/javascript">

    $('body').on('click', '.confirm', function(){
        spinnerButtons('show', $(this));
        var that = this;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            dataType:'json',
            url:'{{route('checkout.store')}}',
            data:$("#PlaceOrder").serialize(),
            success:function(res){

               if(res.url=='home')
                {
                    if(res.status=='success')
                    {
                        msg_title="<?php echo app('translator')->get('order_submitted_successfully.'); ?>";
                        msg_icon='success';
                        xurl='{{route('customer.index')}}'+'#'+res.OrderId;
                    }
                    else{
                        msg_title=res.message;
                        msg_icon='error';
                        xurl='{{route('checkout.payment')}}';
                    }
                    Swal.fire({
                        // position: 'top-end',
                        icon: msg_icon,
                        title: msg_title,
                        showConfirmButton: true
                    }).then((result) => {
                        if (result.value) {
                            location.replace(xurl);
                        }
                    });

                }
                else{
                   Swal.fire({
                       // position: 'top-end',
                       icon: 'success',
                       title: "<?php echo app('translator')->get('please_wait...'); ?>",
                       showConfirmButton: false,
                       timer: 3200
                   });
                   location.replace('{{route('checkout.online')}}');
                }
            }
        });
    });
</script>
