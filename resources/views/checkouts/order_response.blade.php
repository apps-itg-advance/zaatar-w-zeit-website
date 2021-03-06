@php
    $_address=array($cart_info->City,$cart_info->Line1,$cart_info->Line2,$cart_info->Apartment);
    $_total=0;

@endphp
@extends('layouts.template')
@section('content')
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">

    <div class="title-div text-center mb-4">
        <h2 class="title">@lang('checkout')</h2>
    </div>
    @include('partials.checkout_bread')
        <div class="orders-wrapper">
            <div class="order-box">
                <div class="col-xl-7 col-lg-10 float-none p-0 mx-auto item-summary">
                    <div class="order-info pt-2 pt-md-4">
                        <div class="row">
                            <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('address')
                            </div>
                            <div class="col-sm-8 text-808080 mb-3 futura-book">
                                {{implode(', ',$_address)}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('order')
                            </div>
                            <div class="col-sm-8">
                                @foreach($cart as $key=>$values)
                                    <div class="mb-3">
                                        <h5 class="mb-0">{{$values['name']}} (x{{$values['quantity']}}) <span class="d-inline-block ml-3">{{number_format($values['price'])}}</span></h5>
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
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="delivery-block text-right">
                        @lang('delivery_fee') <span class="price d-inline-block ml-4">{{$delivery_charge}} {{$currency}}</span>
                    </div>
                    <hr/>
                    <div class="total-block text-right">
                        @lang('total') <span class="price d-inline-block ml-4">{{$_total}}</span>
                    </div>
                    <div class="order-info">
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('wallet')
                            </div>
                            <div class="col-8 text-808080 mb-3 futura-book">{{}}
                                @php /*
                                    if(isset($cart_vouchers['Id']) and $cart_vouchers['Id']!='')
                                    {
                                        if($cart_vouchers['ValueType']=='percentage')
                                        {
                                            echo $cart_vouchers['Value'] .' %';
                                        }
                                        elseif($cart_vouchers['ValueType']=='flat_rate')
                                        {
                                            echo ' - '.$cart_vouchers['Value'].' '.$currency;
                                        }
                                        else{
                                        echo $cart_vouchers['Value'];
                                        }
                                       //  echo ' From : '.$cart_gift->GiftFrom.'<br> To : '.$cart_gift->GiftTo.'<br> Message : '.$cart_gift->GiftOpenItem;
                                    }
                                    else{
                                        echo 'N/A';
                                    } */
                                @endphp
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                                @lang('gift')
                            </div>
                            <div class="col-8 text-808080 mb-3 futura-book">
                                @php
                                    if(isset($cart_gift->GiftOpenItem) and $cart_gift->GiftOpenItem!='')
                                    {
                                        echo app('translator')->get('yes');
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
                            <div class="col-8 text-808080 mb-3 futura-book">
                                @php
                                    if(isset($cart_green) and $cart_green!='')
                                    {
                                        echo $cart_green;
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
                            <div class="col-8 text-808080 mb-3 futura-book">
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
            </div>
        </div>
    </div>
    <div class="OrderMessage modal fade" id="OrderMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body py-0 col-xl-10 orders-wrapper float-none mx-auto">
                    <div class="modal-header px-0 pb-0">
                        <h5 class="modal-title ml-0 ml-xl-4" id="exampleModalCenterTitle">{{$query->message}}</h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-8DBF43 mb-3 text-uppercase confirm">@lang('ok')</button>
                </div>
            </div>
        </div>


    </div>

@endsection
@section('javascript')
<script type="text/javascript">
    jQuery('#OrderMessage').modal();
    $(".confirm").click(function(){
        window.location = '{{route('home')}}';
    });

</script>
@endsection

