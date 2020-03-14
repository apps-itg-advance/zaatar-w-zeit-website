@php
    $_address=array($cart_info->City,$cart_info->Line1,$cart_info->Line2,$cart_info->Apartment);
    $_total=$delivery_charge;
$discount=0;

@endphp
<form action="{{route('checkout.store')}}" method="post">
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
                        <h2 class="futura-medium">Order Summary</h2>
                    </div>
                </div>
                <div class="order-box">
                    <div class="order-info pt-2 pt-md-4">
                        <div class="row">
                            <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                Address
                            </div>
                            <div class="col-sm-6 text-808080 mb-3 futura-book">
                                {{implode(', ',$_address)}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                Order
                            </div>
                            <div class="col-sm-6">
                                @foreach($cart as $key=>$values)
                                    @php
                                        $price=$values['price'];
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
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-8"><h5 class="mb-0"> {{$values['name']}}</h5>
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
                                        </div>
                                        <div class="col-md-4"> <h5 class="mb-0" style="text-align: right !important;">{{number_format($price)}}</h5></div>
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
                                    $discount=$_total*$cart_vouchers['Value']/100;
                                }
                                elseif($cart_vouchers['ValueType']=='flat_rate')
                                {
                                    $discount=$cart_vouchers['Value'];
                                }
                    }
                    @endphp
                    <div class="row">
                        <div class="col-md-8 offset-2">
                            <div class="delivery-block text-right mb-2">
                                Delivery fee <span class="price d-inline-block ml-4">{{number_format($delivery_charge)}} {{$currency}}</span>
                            </div>
                            <hr/>
                            <div class="total-block text-right">
                                SubTotal <span class="price d-inline-block ml-4">{{number_format($_total)}} {{$currency}}</span>
                            </div>
                            <div class="total-block text-right">
                            Discount <span class="price d-inline-block ml-4">{{number_format($discount)}} {{$currency}}</span>
                            </div>
                            <div class="total-block text-right">
                                Payment <span class="price d-inline-block ml-4">{{number_format($cart_wallet)}} {{$currency}}</span>
                            </div>
                            <div class="total-block text-right">
                                Total <span class="price d-inline-block ml-4">{{number_format($_total-$cart_wallet-$discount)}} {{$currency}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-info">
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                Wallet
                            </div>
                            <div class="col-6 text-808080 mb-3 futura-book">{{$cart_wallet}}
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
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                Gift
                            </div>
                            <div class="col-6 text-808080 mb-3 futura-book">
                                @php
                                    if(isset($cart_gift->GiftOpenItem) and $cart_gift->GiftOpenItem!='')
                                    {
                                        echo 'Yes';
                                       //  echo ' From : '.$cart_gift->GiftFrom.'<br> To : '.$cart_gift->GiftTo.'<br> Message : '.$cart_gift->GiftOpenItem;
                                    }
                                    else{
                                        echo 'No';
                                    }
                                @endphp
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                Go Green
                            </div>
                            <div class="col-6 text-808080 mb-3 futura-book">
                                @php
                                    if(isset($cart_green) and $cart_green!='')
                                    {
                                        echo $cart_green;
                                       //  echo ' From : '.$cart_gift->GiftFrom.'<br> To : '.$cart_gift->GiftTo.'<br> Message : '.$cart_gift->GiftOpenItem;
                                    }
                                    else{
                                        echo 'No';
                                    }
                                @endphp
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                Method
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
                    </div>
                </div>
                <button type="submit" class="btn btn-8DBF43 mb-3 text-uppercase confirm float-right futura-book btn-confirm">Confirm</button>
            </div>
        </div>
    </div>
</form>
