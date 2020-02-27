@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
@endsection

@section('content')
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">

        <div class="title-div text-center mb-4">
            <h2 class="title">Checkout</h2>
        </div>
        @include('partials.checkout_bread')
        @php
            $array_colors=array('bg-AFD27C','bg-9DBFC1','bg-808080','bg-8DBF43');
        @endphp
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-10 float-none p-0 mx-auto item-summary wallet-wrapper">

            <div class="title-div">
                <h2 class="title ml-0">Wallet</h2>
            </div>
            <div class="checkout-wallet ">
                <div  class="row">
                    @for($i=0;$i<count($vouchers);$i++)
                        @php
                            $rand = array_rand($array_colors, 1);
                            $type_l=$vouchers[$i]['ValueType']=='percentage' ? '%':'';
                        @endphp
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="item-box {{$array_colors[$rand]}}" data-mh="matchHeight">
                            <div class="item-div text-white p-3">
                                <div class="py-4 item-quantity float-right">
                                    <div>{{$vouchers[$i]['Qty']}} <span>quantity</span></div>
                                </div>
                                <div class="item-discount text-uppercase">
                                    {{$vouchers[$i]['Value'].$type_l}} Discount
                                </div>
                                <div class="items-vouchers">
                                    @foreach($vouchers[$i]['ExpiryDates'] as $key=>$value)
                                        <div class="voucher mb-1">{{$value}} vouchers expire {{$key}}</div>
                                    @endforeach
                                    <div class="logo py-3"><img src="{{asset('assets/images/icon-logowhite.png')}}" height="27"></div>
                                </div>
                                <div class="buttons text-right mt-3">
                                    <a href="javascript:void(0)" style="cursor: pointer" onclick="SelectRedeem('{{$vouchers[$i]['Value']}}','{{$vouchers[$i]['ValueType']}}')" class="btn btn-redeem text-uppercase">Redeem</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            <input type="hidden" name="Voucher" id="voucher">
            <div class="action-buttons text-center pt-4">
                <button type="button" class="btn btn-8DBF43 text-uppercase confirm">Confirm</button>
            </div>

        </div>

    </div>
@endsection
@section('javascript')
    <script src="{{asset('assets/js/owl.carousel.min.js')}}"></script>
    <script type="text/javascript">
        jQuery('.wallet-carousel').owlCarousel({
            loop : true,
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
        function SelectRedeem(val,vtype)
        {
            $("#voucher").val(val+'-'+vtype);
        }
        $(".confirm").click(function(){
            var radioValue =  $("#voucher").val();
            if(radioValue!='')
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    dataType:'json',
                    data: {query: radioValue},
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
