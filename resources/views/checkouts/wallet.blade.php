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
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-10 float-none p-0 mx-auto item-summary wallet-wrapper">
            <div class="title-div">
                <h2 class="title">Wallet</h2>
            </div>
            <div id="wallet-carousel" class="owl-carousel wallet-carousel mb-3">
                <div class="item bg-AFD27C" data-mh="matchHeight">
                    <div class="item-div text-white p-3">
                        <div class="py-4 item-quantity text-right">
                            <div class="float-right">10 quantity</div>
                        </div>
                        <div class="item-discount text-uppercase">
                            10% Discount
                        </div>
                        <div class="items-vouchers">
                            <div class="voucher mb-1">5 vouchers expire 1/1/2020</div>
                            <div class="voucher">5 vouchers expire 1/1/2021</div>
                        </div>
                        <div class="buttons text-center mt-3">
                            <a href="#" class="btn btn-redeem text-uppercase">Redeem</a>
                        </div>
                    </div>
                </div>
                <div class="item bg-8DBF43" data-mh="matchHeight">
                    <div class="item-div text-white p-3">
                        <div class="py-4 item-quantity text-right">
                            <div class="float-right">10 quantity</div>
                        </div>
                        <div class="item-discount text-uppercase">
                            10% Discount
                        </div>
                        <div class="items-vouchers">
                            <div class="voucher">5 vouchers expire 1/1/2021</div>
                        </div>
                        <div class="buttons text-center mt-3">
                            <a href="#" class="btn btn-redeem text-uppercase">Redeem</a>
                        </div>
                    </div>
                </div>
                <div class="item bg-808080" data-mh="matchHeight">
                    <div class="item-div text-white p-3">
                        <div class="py-4 item-quantity text-right">
                            <div class="float-right">10 quantity</div>
                        </div>
                        <div class="item-discount text-uppercase">
                            Free Kids Meal
                        </div>
                        <div class="items-vouchers">
                            <div class="voucher">5 vouchers expire 1/1/2021</div>
                        </div>
                        <div class="buttons text-center mt-3">
                            <a href="#" class="btn btn-redeem text-uppercase">Redeem</a>
                        </div>
                    </div>
                </div>
                <div class="item bg-AFD27C" data-mh="matchHeight">
                    <div class="item-div text-white p-3">
                        <div class="py-4 item-quantity text-right">
                            <div class="float-right">10 quantity</div>
                        </div>
                        <div class="item-discount text-uppercase">
                            10% Discount
                        </div>
                        <div class="items-vouchers">
                            <div class="voucher mb-1">5 vouchers expire 1/1/2020</div>
                            <div class="voucher">5 vouchers expire 1/1/2021</div>
                        </div>
                        <div class="buttons text-center mt-3">
                            <a href="#" class="btn btn-redeem text-uppercase">Redeem</a>
                        </div>
                    </div>
                </div>
                <div class="item bg-8DBF43" data-mh="matchHeight">
                    <div class="item-div text-white p-3">
                        <div class="py-4 item-quantity text-right">
                            <div class="float-right">10 quantity</div>
                        </div>
                        <div class="item-discount text-uppercase">
                            10% Discount
                        </div>
                        <div class="items-vouchers">
                            <div class="voucher">5 vouchers expire 1/1/2021</div>
                        </div>
                        <div class="buttons text-center mt-3">
                            <a href="#" class="btn btn-redeem text-uppercase">Redeem</a>
                        </div>
                    </div>
                </div>
                <div class="item bg-808080" data-mh="matchHeight">
                    <div class="item-div text-white p-3">
                        <div class="py-4 item-quantity text-right">
                            <div class="float-right">10 quantity</div>
                        </div>
                        <div class="item-discount text-uppercase">
                            Free Kids Meal
                        </div>
                        <div class="items-vouchers">
                            <div class="voucher">5 vouchers expire 1/1/2021</div>
                        </div>
                        <div class="buttons text-center mt-3">
                            <a href="#" class="btn btn-redeem text-uppercase">Redeem</a>
                        </div>
                    </div>
                </div>
            </div>
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
            center : true,
            navText : ['', ''],
            margin : 20,
            dots : false,
            nav : true,
            responsive:{
                0:{
                    items:1
                },
                575:{
                    items:2
                },
                767:{
                    items:2
                },
                991:{
                    items:2
                },
                1200:{
                    items:3
                }
            }
        });
        $(".confirm").click(function(){
            window.location = '{{route('checkout.gift')}}';
        });

    </script>
@endsection
