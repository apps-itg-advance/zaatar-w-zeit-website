<style type="text/css">
    .border-green{
        border: 3px solid #8DBF43;
    }
    .border-white{
        border: 5px solid white;

    }
</style>
<div class="title-div mb-4">
    <h2 class="title">Wallet</h2>
</div>
@php
    $array_colors=array('bg-AFD27C','bg-9DBFC1','bg-808080','bg-8DBF43');
    $rand = array_rand($array_colors, 1);
@endphp
<div id="wallet-carousel" class="owl-carousel wallet-carousel">
    @php
        $array_colors=array('bg-AFD27C','bg-9DBFC1','bg-808080');
        $rand = array_rand($array_colors, 1);
    @endphp
    @if($checkout and $wallet_balance>0)
        <div class="item active bg-8DBF43" data-mh="matchHeight" id="wallet-b">
            <div class="item-div active text-white p-3"  id="wallet-b-1">
                <div class="py-4 item-quantity text-right">
                    <div class="float-right"></div>
                </div>
                <div class="item-discount text-uppercase" >
                    YOU HAVE <span class="wallet-balance">{{number_format($wallet_balance)}}</span> {{$currency}}
                </div>
                <div class="items-vouchers">
                    <div class="row">
                        <div class="col-md-12">How much would you like to use?</div>
                        <div class="col-md-9"><input name="wallet_amount" id="WalletAmount" class="form-control w-amount"></div>
                        <div class="col-md-3" style="font-size: 20px !important;"> {{$currency}}</div>
                    </div>
                </div>

                <p><img src="{{asset('assets/images/icon-logowhite.png')}}" class="w-auto logo-img"></p>
                <div class="buttons  text-center mt-3">
                    <a href="javascript:void(0)" style="cursor: pointer" onclick="WalletAmount()" class="btn btn-redeem text-uppercase">Redeem</a>
                </div>
            </div>

        </div>
    @endif
    @for($i=0;$i<count($vouchers);$i++)
        @php
            $rand = array_rand($array_colors, 1);
           // $type_l=$vouchers[$i]['ValueType']=='percentage' ? '%':'';
        @endphp
        <div class="item {{$array_colors[$rand]}}"  id="voucher-b{{$vouchers[$i]->Id}}" data-mh="matchHeight">
            <div class="item-div text-white p-3" id="voucher-b1{{$vouchers[$i]->Id}}">
                <div class="py-4 item-quantity text-right">
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
                <p><img src="{{asset('assets/images/icon-logowhite.png')}}" class="w-auto logo-img"></p>
                <div class="buttons text-center mt-3">
                    <a href="javascript:void(0)" style="cursor: pointer" onclick="SelectRedeem('{{$vouchers[$i]->Id}}')" class="btn btn-redeem text-uppercase">Redeem</a>
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
        $("#wallet-b").addClass("border-green");
        $("#wallet-b-1").addClass("border-white");

    });
    function SelectRedeem(val)
    {
        var title_v=$('.title-'+val).data('title');
        var old_value=$("#voucher").val();
        if(val!=old_value) {
            if (old_value != '') {
                var old_qty_all = $('.qty' + old_value).data('title');
                var old_qty = $('.vqty' + old_value).data('title');
                $('.qty' + old_value).html((old_qty_all));
                $('.vqty' + old_value).html((old_qty));
                $("#voucher-b"+old_value).removeClass("border-green");
                $("#voucher-b1"+old_value).removeClass("border-white");
            }

            var qty_all = $('.qty' + val).data('title');
            var qty = $('.vqty' + val).data('title');
            $('.qty' + val).html((qty_all - 1));
            $('.vqty' + val).html((qty - 1));
            $("#voucher").val(val);
            $("#voucher-b"+val).addClass("border-green");
            $("#voucher-b1"+val).addClass("border-white");
        }
    }
    function WalletAmount(val,vtype,vcategory)
    {
        $("#WalletAmount").val();
    }
    $(".confirm").click(function(){
	    spinnerButtons('show', $(this));
        var radioValue =  $("#voucher").val();
        var walletValue =   $("#WalletAmount").val();
        if(radioValue!='')
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
            window.location = '{{route('checkout.gift')}}';
        }


    });

</script>
@endsection