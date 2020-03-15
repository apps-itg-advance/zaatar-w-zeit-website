
<div class="title-div mb-4">
    <h2 class="title">Wallet</h2>
</div>
@php
    $array_colors=array('bg-AFD27C','bg-9DBFC1','bg-808080','bg-8DBF43');
    $rand = array_rand($array_colors, 1);
@endphp
<div id="wallet-carousel" class="owl-carousel wallet-carousel">
    @if($checkout and $wallet_balance>0)
        <div class="item {{$array_colors[$rand]}}" data-mh="matchHeight">
            <div class="item-div text-white p-4">
                <div class="py-4 item-quantity text-right">
                    <div class="float-right"></div>
                </div>
                <div class="item-discount text-uppercase">
                    YOU HAVE {{number_format($wallet_balance).' '.$currency}}
                </div>
                <div class="items-vouchers">
                <div class="row">
                    <div class="col-md-12">How much would you like to use?</div>
                    <div class="col-md-9"><input name="wallet_amount" id="WalletAmount" class="form-control"></div>
                    <div class="col-md-3" style="font-size: 20px !important;"> {{$currency}}</div>
                </div>
                </div>

                <p><img src="{{asset('assets/images/icon-logowhite.png')}}" class="w-auto logo-img"></p>
                @if($checkout)
                    <div class="buttons text-right mt-3">
                        <a href="javascript:void(0)" style="cursor: pointer" onclick="WalletAmount()" class="btn btn-redeem text-uppercase">Redeem</a>
                    </div>
                @endif
            </div>

        </div>
    @endif
    @for($i=0;$i<count($vouchers);$i++)
        @php
            $rand = array_rand($array_colors, 1);
           // $type_l=$vouchers[$i]['ValueType']=='percentage' ? '%':'';
        @endphp
        <div class="item {{$array_colors[$rand]}}" data-mh="matchHeight">
            <div class="item-div text-white p-4">
                <div class="py-4 item-quantity text-right">
                    <div class="float-right">{{count($vouchers[$i]->Vouchers)}} quantity</div>
                </div>
                <div class="item-discount text-uppercase">
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

                    @foreach($array_exp as $key=>$value)
                        <div class="voucher mb-1">{{$value}} vouchers expire {{$key}}</div>
                    @endforeach

                </div>
            <p><img src="{{asset('assets/images/icon-logowhite.png')}}" class="w-auto logo-img"></p>
                @if($checkout)
                    <div class="buttons text-right mt-3">
                        <a href="javascript:void(0)" style="cursor: pointer" onclick="SelectRedeem('{{$vouchers[$i]->Id}}')" class="btn btn-redeem text-uppercase">Redeem</a>
                    </div>
                @endif
            </div>

        </div>

    @endfor
</div>
@if($checkout)
    <input type="hidden" name="Voucher" id="voucher">
    <div class="action-buttons text-center pt-4">
        <button type="button" class="btn btn-8DBF43 text-uppercase confirm">Confirm</button>
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
    $("#WalletAmount").keyup(function() {
        var use=$("#WalletAmount").val();
        var balance={{$wallet_balance}};
        if(use>balance)
        {
            $("#WalletAmount").val(balance)
        }

    });
    function SelectRedeem(val)
    {
        $("#voucher").val(val);
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