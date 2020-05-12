<style type="text/css">
    .border-green{
        border: 3px solid #8DBF43;
    }
    .border-white{
        border: 5px solid white;

    }
    .checkout-wrapper .wallet-wrapper .item-div .items-vouchers{
        min-height: 120px !important;
    }
</style>
<div class="title-div mb-4">
    <h2 class="title">Wallet</h2>
</div>
@php
    $array_colors=array('#AFD27C','#9DBFC1','#808080','#8DBF43');
    $rand = array_rand($array_colors, 1);
    $cart_wallet=isset($cart_wallet) ? $cart_wallet :'';
    $cart_voucher_id=isset($cart_vouchers['VParentId']) ? $cart_vouchers['VParentId'] :'';
@endphp
<div id="wallet-carousel" class="owl-carousel wallet-carousel">
    @php
        $rand = array_rand($array_colors, 1);
    @endphp
    @if($checkout and $wallet_balance>0)
        <div class="slide-shadow item active bg-8DBF43" data-mh="matchHeight" id="wallet-b">
            <div class="item-div active text-white p-3"  id="wallet-b-1">
                <div class="py-4 item-quantity  float-right">
                    <div class="float-right"></div>
                </div>
                <div class="item-discount text-uppercase" >
                    YOU HAVE <span class="wallet-balance">{{number_format($wallet_balance)}}</span> {{$currency}}
                </div>
                <div class="items-vouchers">
                    <div class="row">
                        <div class="col-md-12">How much would you like to use?</div>
                        <div class="col-md-9"><input name="wallet_amount" value="{{$cart_wallet}}" id="WalletAmount" class="form-control w-amount"></div>
                        <div class="col-md-3" style="font-size: 20px !important;"> {{$currency}}</div>
                    </div>
                </div>

                <p class=" float-left"><img src="{{asset('assets/images/icon-logowhite.png')}}" class="w-auto logo-img"></p>
                <div class="buttons  float-right "  style="margin: 10px !important;">
                    <a href="javascript:void(0)" style="cursor: pointer" class="btn btn-redeem text-uppercase redeem-wallet">Redeem</a>
                </div>
            </div>

        </div>
    @endif
    @for($i=0;$i<count($vouchers);$i++)
        @php
            $rand = array_rand($array_colors, 1);
           // $type_l=$vouchers[$i]['ValueType']=='percentage' ? '%':'';
        	$bg_color=(isset($vouchers[$i]->Color) and $vouchers[$i]->Color!='') ? $vouchers[$i]->Color:$array_colors[$rand];
        @endphp
        <div class="slide-shadow item " style="background-color: {{$bg_color}}"  id="voucher-b{{$vouchers[$i]->Id}}" data-mh="matchHeight">
            <div class="item-div text-white p-3" id="voucher-b1{{$vouchers[$i]->Id}}">
                <div class="py-4 item-quantity  float-right">
                    <div class="float-right" ><span class="qty{{$vouchers[$i]->Id}}" data-title="{{count($vouchers[$i]->Vouchers)}}">{{count($vouchers[$i]->Vouchers)}}</span> quantity</div>
                </div>
                <div class="item-discount text-uppercase title-{{$vouchers[$i]->Id}}" data-title="{{$vouchers[$i]->Title}}">
                    {{$vouchers[$i]->Title}}
                </div>
                @php
                    $v=$vouchers[$i]->Vouchers;
                    for ($j=0;$j<count($v);$j++)
                    {
                        $qty=1;
                        for($k=$j+1;$k<count($v);$k++)
                        {
                            if($v[$j]->ExpiryDate==$v[$k]->ExpiryDate)
                            {
                            $qty++;
                            }
                        }
                        $array_exp[$v[$j]->ExpiryDate]=$qty;
                    }
                @endphp
                <div class="items-vouchers">
                    @php $css='vqty'.$vouchers[$i]->Id; @endphp
                    @foreach($array_exp as $key=>$value)
                        <div class="voucher"><span class="{{$css}}" data-title="{{$value}}">{{$value}}</span>  vouchers expire {{$key}}</div>
                        @php $css='' @endphp
                    @endforeach

                </div>
                <p class=" float-left"><img src="{{asset('assets/images/icon-logowhite.png')}}" class="w-auto logo-img"></p>
                <div class="buttons float-right" style="margin: 10px !important;">
                    <a href="javascript:void(0)" style="cursor: pointer" onclick="SelectRedeem('{{$vouchers[$i]->Id}}')" class="btn btn-redeem text-uppercase redeem-{{$vouchers[$i]->Id}}">Redeem</a>
                </div>
            </div>

        </div>

    @endfor

</div>
@if($checkout)
    <input type="hidden" name="Voucher" id="voucher">
    <div class="action-buttons text-center pt-4">
        <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm">Confirm</button>
        @if(isset($settings->Required) and !$settings->Required)
            <button type="button" class="btn btn-B3B3B3 text-uppercase skip" onclick="SkipBtn('wallet')">Skip</button>
        @endif
    </div>
@endif

@section('javascript')
<script src="{{asset('assets/js/owl.carousel.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        @if($cart_wallet!='')
        $(".redeem-wallet").click();
        @endif
        @if($cart_voucher_id!='')
        SelectRedeem({{$cart_voucher_id}});
        @endif
    });
    jQuery('.wallet-carousel').owlCarousel({
        loop : false,
        navText : ['', ''],
        margin : 40,
        dots : false,
        nav : true,
        responsive:{
            0:{
                items:1
            },
            575:{
                items:1
            },
            767:{
                items:2
            },
            991:{
                items:1
            },
            1200:{
                items:2
            },
            1500:{
                items:2
            }
        }
    });
    $(".w-amount").keyup(function() {
        var val_amount=$(this).val();
        var balance={{$wallet_balance}};

        if(val_amount>balance)
        {
            val_amount=balance;
            $(".w-amount").val(balance);

        }
        var new_value=balance-val_amount;
        $(".wallet-balance").html(formatNumber(new_value));

    });
    $(".redeem-wallet").click(function() {
        var x=$(".w-amount").val();
        if(x>0)
        {
        if($(this).text()=='Redeemed')
        {
            $(this).text('Redeem');
            $(".w-amount").val('');
            $("#wallet-b").removeClass("border-green");
            $("#wallet-b-1").removeClass("border-white");
        }
        else{
            $(this).text('Redeemed');
            $("#wallet-b").addClass("border-green");
            $("#wallet-b-1").addClass("border-white");
            $(".wallet-balance").html(formatNumber({{$wallet_balance}}));
        }
        }
        else{
            Swal.fire({
                // position: 'top-end',
                icon: 'warning',
                title: 'You must select an amount',
                showConfirmButton: 'close'
            });
        }
    });

    function SelectRedeem(val)
    {
        var title_v=$('.title-'+val).data('title');
        var old_value=$("#voucher").val();
        var tlt= $('.redeem-' + val).text();
        if(tlt=='Redeem')
        {
            if(val!=old_value) {
                if (old_value != '') {
                    var old_qty_all = $('.qty' + old_value).data('title');
                    var old_qty = $('.vqty' + old_value).data('title');
                    $('.qty' + old_value).html((old_qty_all));
                    $('.vqty' + old_value).html((old_qty));
                    $("#voucher-b"+old_value).removeClass("border-green");
                    $("#voucher-b1"+old_value).removeClass("border-white");
                    $('.redeem-' + old_value).text('Redeem');
                }

                var qty_all = $('.qty' + val).data('title');
                var qty = $('.vqty' + val).data('title');
                $('.qty' + val).html((qty_all - 1));
                $('.vqty' + val).html((qty - 1));
                $("#voucher").val(val);
                $("#voucher-b"+val).addClass("border-green");
                $("#voucher-b1"+val).addClass("border-white");
                $('.redeem-' + val).text('Redeemed');
            }
        }
        else{
            var qty_all = $('.qty' + val).data('title');
            var qty = $('.vqty' + val).data('title');
            $('.qty' + val).html((qty_all));
            $('.vqty' + val).html((qty));
            $("#voucher").val('');
            $("#voucher-b"+val).removeClass("border-green");
            $("#voucher-b1"+val).removeClass("border-white");
            $('.redeem-' + val).text('Redeem');
        }


    }
    function WalletAmount()
    {
        $("#wallet-b").addClass("border-green");
        $("#wallet-b-1").addClass("border-white");
    }
    $(".confirm").click(function(){
	    spinnerButtons('show', $(this));
        var radioValue =  $("#voucher").val();
        var walletValue =   $("#WalletAmount").val();
        var txtWallet=$(".redeem-wallet").text();

        if(radioValue!='' || (walletValue!='' && walletValue>0 && txtWallet=='Redeemed'))
        {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType:'json',
                data: {vid: radioValue,wallet_amount:walletValue},
                url: '{{route('checkout.loyalty.store')}}',
                success: function (data) {
                   window.location = '{{route('checkout.gift')}}';
                }
            });
        }
        else{
            spinnerButtons('hide', $(this));
            Swal.fire({
                // position: 'top-end',
                icon: 'warning',
                title: 'You must select an amount or a voucher',
                showConfirmButton: 'close'
            });
        }


    });

</script>
@endsection