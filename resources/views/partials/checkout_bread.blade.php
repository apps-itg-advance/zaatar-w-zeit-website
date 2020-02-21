@php

    $_css_address_active='';
    $_css_wallet_active='';
    $_css_gift_active='';
    $_css_green_active='';
    $_css_payment_active='';
    $_css_special_ins_active='';
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
         case 'payment':
            $_css_address_active='active';
            $_css_wallet_active='active';
            $_css_gift_active='active';
            $_css_green_active='active';

            $_css_payment_active='active';
           break;
         case 'special_instructions':
            $_css_address_active='active';
            $_css_wallet_active='active';
            $_css_gift_active='active';
            $_css_green_active='active';
            $_css_payment_active='active';

            $_css_special_ins_active='active';
           break;
        }
@endphp
<div class="checkout-navblock mb-5">
    <ul class="checkout-nav">
        <li class="{{$_css_address_active}}"><a href="#">Address</a></li>
        <li class="{{$_css_wallet_active}}"><a href="#">Wallet</a></li>
        <li class="{{$_css_gift_active}}"><a href="#">Gift</a></li>
        <li class="{{$_css_green_active}}"><a href="#">The Real Green</a></li>
        <li class="{{$_css_payment_active}}"><a href="#">Payment</a></li>
        <li class="{{$_css_special_ins_active}}"><a href="#">Special Instructions</a></li>
    </ul>
</div>