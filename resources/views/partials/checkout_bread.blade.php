@php

    $_css_address_active='';
    $_css_wallet_active='';
    $_css_gift_active='';
    $_css_green_active='';
    $_css_payment_active='';
    $_css_special_ins_active='';
    if(isset($_active_css)){
    switch ($_active_css)
        {
        case 'address':
            $_css_address_active='active';
           break;
         case 'wallet':
            $_css_address_active='active';

            $_css_wallet_active='active';
           break;
         case 'gift':
            $_css_address_active='active';
            $_css_wallet_active='active';

            $_css_gift_active='active';
           break;
         case 'green':
            $_css_address_active='active';
            $_css_wallet_active='active';
            $_css_gift_active='active';

            $_css_green_active='active';
           break;
        case 'special_instructions':
            $_css_address_active='active';
            $_css_wallet_active='active';
            $_css_gift_active='active';
            $_css_green_active='active';
          //  $_css_payment_active='active';

            $_css_special_ins_active='active';
       break;
         case 'payment':
            $_css_address_active='active';
            $_css_wallet_active='active';
            $_css_gift_active='active';
            $_css_green_active='active';
            $_css_special_ins_active='active';

            $_css_payment_active='active';
           break;
        }
}
else{
 $_css_address_active='active';
            $_css_wallet_active='active';
            $_css_gift_active='active';
            $_css_green_active='active';
            $_css_payment_active='active';

            $_css_special_ins_active='active';
}
$_css_wallet_active=isset($LEVEL_ID) and $LEVEL_ID==''? 'disabled':$_css_wallet_active;
@endphp
<div class="checkout-navblock mb-5">
    <ul class="checkout-nav">
        <li class="{{$_css_address_active}} {{request()->segment(2)=='address'?'current':''}}"><a href="{{$_css_address_active=='active'?route('checkout.address'):'#'}}">Address</a></li>
        <li class="{{$_css_wallet_active}} {{request()->segment(2)=='wallet'?'current':''}}"><a href="{{$_css_wallet_active=='active'?route('checkout.wallet'):'#'}}">Wallet</a></li>
        <li class="{{$_css_gift_active}} {{request()->segment(2)=='gift'?'current':''}}"><a href="{{$_css_gift_active=='active'?route('checkout.gift'):'#'}}">Gift</a></li>
        <li class="{{$_css_green_active}} {{request()->segment(2)=='green'?'current':''}}"><a href="{{$_css_green_active=='active'?route('checkout.green'):'#'}}">The Real Green</a></li>
        <li class="{{$_css_special_ins_active}} {{request()->segment(2)=='special-instructions'?'current':''}}"><a href="{{$_css_special_ins_active=='active'?route('checkout.special_instructions'):'#'}}">Special Instructions</a></li>
        <li class="{{$_css_payment_active}} {{request()->segment(2)=='payment'?'current':''}}"><a href="{{$_css_payment_active=='active'?route('checkout.payment'):'#'}}">Payment</a></li>
    </ul>
</div>