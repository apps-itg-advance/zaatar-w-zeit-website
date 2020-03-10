@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/circle.css')}}">
@endsection

@section('content')
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-favourite-items">

        <div class="col-xl-12 float-none p-0 mx-auto">
            <div class="title-div mb-4 pb-2">
                <h2 class="title">Loyalty</h2>
            </div>
            <div class="loyaltygraph-wrapper">
                <div class="user-div">
                    @php
                        $details=array();
                        if(isset($query->details))
                        {
                         $details=$query->details;
                        }
                    @endphp
                    <h4>{{@$details->FirstName.' '.@$details->LastName}}</h4>
                    <button type="button" id="edit-profile" class="btn btn-8DBF43 text-uppercase">Edit Profile</button>
                </div>

                <div class="col-xl-8 col-lg-10 float-none p-0 mx-auto loyaltygraph-div pt-4 mb-5 pb-3">
                    <div class="row">
                        @php
                            $per=intval(($query->details->TierBalance*100)/$query->details->LevelMaxCollection);
                        @endphp

                    <div  class="col-md-3 d-flex align-items-end">
                        <div style="font-size: 16px !important;">
                            {{$next_level->NeededPoints}} Points left
                        </div>

                        </div>
                        <div  class="col-md-6">
                            <div class="c100 p{{$per}} big green">
                                <span><div>{{$query->details->LevelName}} <br> <small>{{$query->details->TierBalance}} points</small></div></span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-3 d-flex align-items-end">
                            <div style="font-size: 16px !important;">
                                @if($query->details->Threshold> $query->details->TierBalance)
                              COLLECT {{$query->details->Threshold-$query->details->TierBalance}} points for a {{$query->details->TotalOp}} % discount
                                 @endif
                            </div>

                        </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xl-10 col-lg-10 float-none p-0 mx-auto wallet-wrapper">
                        <div class="title-div mb-4">
                            <h2 class="title">Wallet</h2>
                        </div>
                        <div id="wallet-carousel" class="owl-carousel wallet-carousel">
                            <div class="item bg-AFD27C" data-mh="matchHeight">
                                <div class="item-div text-white p-4">
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
                                </div>
                            </div>
                            <div class="item bg-8DBF43" data-mh="matchHeight">
                                <div class="item-div text-white p-4">
                                    <div class="py-4 item-quantity text-right">
                                        <div class="float-right">10 quantity</div>
                                    </div>
                                    <div class="item-discount text-uppercase">
                                        10% Discount
                                    </div>
                                    <div class="items-vouchers">
                                        <div class="voucher">5 vouchers expire 1/1/2021</div>
                                    </div>
                                </div>
                            </div>
                            <div class="item bg-808080" data-mh="matchHeight">
                                <div class="item-div text-white p-4">
                                    <div class="py-4 item-quantity text-right">
                                        <div class="float-right">10 quantity</div>
                                    </div>
                                    <div class="item-discount text-uppercase">
                                        Free Kids Meal
                                    </div>
                                    <div class="items-vouchers">
                                        <div class="voucher">5 vouchers expire 1/1/2021</div>
                                    </div>
                                </div>
                            </div>
                            <div class="item bg-AFD27C" data-mh="matchHeight">
                                <div class="item-div text-white p-4">
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
                                </div>
                            </div>
                            <div class="item bg-8DBF43" data-mh="matchHeight">
                                <div class="item-div text-white p-4">
                                    <div class="py-4 item-quantity text-right">
                                        <div class="float-right">10 quantity</div>
                                    </div>
                                    <div class="item-discount text-uppercase">
                                        10% Discount
                                    </div>
                                    <div class="items-vouchers">
                                        <div class="voucher">5 vouchers expire 1/1/2021</div>
                                    </div>
                                </div>
                            </div>
                            <div class="item bg-808080" data-mh="matchHeight">
                                <div class="item-div text-white p-4">
                                    <div class="py-4 item-quantity text-right">
                                        <div class="float-right">10 quantity</div>
                                    </div>
                                    <div class="item-discount text-uppercase">
                                        Free Kids Meal
                                    </div>
                                    <div class="items-vouchers">
                                        <div class="voucher">5 vouchers expire 1/1/2021</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.cart')
        @include('customers._edit_profile')
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
            @php
                if($type=='register')
            {
            echo "  jQuery('#editprofileModal').modal();";
            }
        @endphp
        $("#edit-profile").click(function(){
            jQuery('#editprofileModal').modal();
        });

    </script>
@endsection
