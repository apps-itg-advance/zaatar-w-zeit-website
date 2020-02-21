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
                <div class="order-info py-2 py-md-4">
                    <div class="row align-items-center">
                        <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666">
                            Address
                        </div>
                        <div class="col-sm-8 text-808080">
                            Oat bread, remove pickles, Add-on fries, Add turkey, thin bread, toasted, Cut in half
                        </div>
                    </div>
                </div>
                <div class="action-div text-right">
                    <button  onclick="window.location = '{{route('order.details')}}';" type="button" class="btn btn-8DBF43 text-uppercase">Open</button>
                </div>
            </div>
            <div class="order-box mb-3 p-3">
                <h4 class="title">
                    ORDER 15673A
                    <span>10/12/2019 - 15:00</span>
                </h4>
                <div class="order-info py-2 py-md-4">
                    <div class="row align-items-center">
                        <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666">
                            Address
                        </div>
                        <div class="col-sm-8 text-808080">
                            Oat bread, remove pickles, Add-on fries, Add turkey, thin bread, toasted, Cut in half
                        </div>
                    </div>
                </div>
                <div class="action-div text-right">
                    <button onclick="window.location = '{{route('order.details')}}';" type="button" class="btn btn-8DBF43 text-uppercase">Open</button>
                </div>
            </div>
            <div class="more-div text-right">
                <a href="#" class="text-4D4D4D">more...</a>
            </div>
            <div class="protocol-div mt-4">
                <div class="title-div mb-3">
                    <h4 class="title">Rules & Regulations</h4>
                </div>
                <div class="text-808080">
                    lorem ipsum is simply dummy text of the printing and typesetting industry. lorem ipsum has been the industry's standard dummy text ever since the 1500s, pagemaker including versions of lorem ipsum.
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
