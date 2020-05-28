@php
    $_css_address_active='';
    $_css_wallet_active='';
    $_css_gift_active='';
    $_css_green_active='';
    $_css_payment_active='';
    $_css_special_ins_active='';
    if(isset($_active_css)){
    $checkout_steps=session()->has('checkout_steps')? session()->get('checkout_steps'):'address';
   // $_active_css;
    switch ($checkout_steps)
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
$_css_wallet_active=(isset($LEVEL_ID) and $LEVEL_ID=='')? 'disabled':$_css_wallet_active;
//$_css_address_active=(session()->has('cart_info') and session()->get('cart_info')!=null)? 'active':'';
 /*
 $_css_wallet_active='active';
            $_css_gift_active='active';
            $_css_green_active='active';
            $_css_payment_active='active';

            $_css_special_ins_active='active';
*/
@endphp
<div class="checkout-navblock mb-5">
    <ul class="checkout-nav">
        <li class="{{$_css_address_active}} {{request()->segment(2)=='address'?'current':''}}"><a href="{{$_css_address_active=='active'?route('checkout.address'):'#'}}">@lang('address')</a></li>
        <li class="{{$_css_wallet_active}} {{request()->segment(2)=='wallet'?'current':''}}"><a href="{{$_css_wallet_active=='active'?route('checkout.wallet'):'#'}}">@lang('wallet')</a></li>
        <li class="{{$_css_gift_active}} {{request()->segment(2)=='gift'?'current':''}}"><a href="{{$_css_gift_active=='active'?route('checkout.gift'):'#'}}">@lang('gift')</a></li>
        <li class="{{$_css_green_active}} {{request()->segment(2)=='green'?'current':''}}"><a href="{{$_css_green_active=='active'?route('checkout.green'):'#'}}">@lang('the_real_green')</a></li>
        <li class="{{$_css_special_ins_active}} {{request()->segment(2)=='special-instructions'?'current':''}}"><a href="{{$_css_special_ins_active=='active'?route('checkout.special_instructions'):'#'}}">@lang('special_instructions')</a></li>
        <li class="{{$_css_payment_active}} {{request()->segment(2)=='payment'?'current':''}}"><a href="{{$_css_payment_active=='active'?route('checkout.payment'):'#'}}">@lang('payment')
            </a></li>
    </ul>
</div>