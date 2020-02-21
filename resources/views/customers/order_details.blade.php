@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
@endsection

@section('content')
    <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-order-items">

        <div class="title-div mb-4 pb-2">
            <h2 class="title">Order History</h2>
        </div>
        <div class="col-xl-10 float-none p-0">
            <div class="order-box mb-3 p-3">
                <h4 class="title">
                    ORDER 15673A
                    <span>10/12/2019 - 15:00</span>
                </h4>
                <div class="order-info pt-2 pt-md-4">
                    <div class="row">
                        <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Address
                        </div>
                        <div class="col-sm-8 text-808080 mb-3">
                            Oat bread, remove pickles, Add-on fries, Add turkey, thin bread, toasted, Cut in half
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Order
                        </div>
                        <div class="col-sm-8">
                            <div class="mb-3">
                                <h5 class="mb-0">Famous Chicken <span class="d-inline-block ml-3">11,250</span></h5>
                                <div class="text-808080">
                                    Oat bread, remove pickles, Add-on fries, Add turkey, thin bread, toasted, Cut in half
                                </div>
                            </div>
                            <div class="mb-3">
                                <h5 class="mb-0">Famous Chicken <span class="d-inline-block ml-3">11,250</span></h5>
                                <div class="text-808080">
                                    Oat bread, remove pickles, Add-on fries, Add turkey, thin bread, toasted, Cut in half
                                </div>
                            </div>
                            <div class="mb-3">
                                <h5 class="mb-0">Famous Chicken <span class="d-inline-block ml-3">11,250</span></h5>
                                <div class="text-808080">
                                    Oat bread, remove pickles, Add-on fries, Add turkey, thin bread, toasted, Cut in half
                                </div>
                            </div>
                            <div class="mb-3">
                                <h5 class="mb-0">Famous Chicken <span class="d-inline-block ml-3">11,250</span></h5>
                                <div class="text-808080">
                                    Oat bread, remove pickles, Add-on fries, Add turkey, thin bread, toasted, Cut in half
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="total-block text-right">
                    Total <span class="price d-inline-block ml-4">30,000 LL</span>
                </div>
                <hr/>
                <div class="delivery-block text-right mb-4">
                    Delivery fee <span class="price d-inline-block ml-4">2,000 LL</span>
                </div>
                <div class="order-info">
                    <div class="row align-items-center">
                        <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Wallet
                        </div>
                        <div class="col-8 text-808080 mb-3">
                            10% discount
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Gift
                        </div>
                        <div class="col-8 text-808080 mb-3">
                            Yes
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Go Green
                        </div>
                        <div class="col-8 text-808080 mb-3">
                            Yes
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Method
                        </div>
                        <div class="col-8 text-808080 mb-3">
                            Online Payment
                        </div>
                    </div>
                </div>
                <div class="action-div text-right">
                    <button type="button" class="btn btn-B3B3B3 text-uppercase">Close</button>
                </div>
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
        $("#edit-profile").click(function(){
            jQuery('#editprofileModal').modal();
        });

    </script>
@endsection
