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
                    <button type="button" id="edit-profile" class="btn btn-8DBF43 text-uppercase bg-white text-8DBF43">Edit Profile</button>
                    <br>
                    <button type="button" class="btn btn-8DBF43 bg-white text-uppercase mt-2"><a class="text-8DBF43" href="{{route('customer.order-history')}}">Order History</a></button>
                    <br>
                    <button type="button" class="btn btn-8DBF43 text-uppercase mt-2"><a href="{{route('logout')}}" class="cursor-pointer text-white">Logout</a></button>
                </div>

                <div class="col-xl-8 col-lg-10 float-none p-0 mx-auto loyaltygraph-div pt-4 mb-5 pb-3">
                    <div class="row">
                        @php
                            if($query->details->LevelMaxCollection> $query->details->TierBalance)
                            {
                             $per=intval(($query->details->TierBalance*100)/$query->details->LevelMaxCollection);
                            }
                        else{
                            $per=100;
                        }


                        @endphp

                    <div  class="col-md-3 d-flex align-items-end">
                        <div style="font-size: 16px !important;">
                            @php
                                isset($next_level->NeededPoints) ? $next_level->NeededPoints.' Points left' : ''
                            @endphp
                        </div>

                        </div>
                        <div  class="col-md-6">
                            <div class="c100 p{{$per}} big green">
                                <img src="/assets/images/arrow-down.png"  class="{{($query->details->Threshold> $query->details->TierBalance) ? '':'d-none'}}" alt="zwz profile arrow">
                                <span><div style="font-size: 68px !important;">{{$query->details->LevelName}} <br> <small>{{number_format($query->details->TierBalance)}} points</small></div></span>
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
                        @php
                            $array_colors=array('bg-AFD27C','bg-9DBFC1','bg-808080','bg-8DBF43');
                        @endphp
                        <div id="wallet-carousel" class="owl-carousel wallet-carousel">
                            @for($i=0;$i<count($vouchers);$i++)
                                @php
                                    $rand = array_rand($array_colors, 1);
                                    $type_l=$vouchers[$i]['ValueType']=='percentage' ? '%':'';
                                @endphp
                            <div class="item {{$array_colors[$rand]}}" data-mh="matchHeight">
                                <div class="item-div text-white p-4">
                                    <div class="py-4 item-quantity text-right">
                                        <div class="float-right">{{$vouchers[$i]['Qty']}} quantity</div>
                                    </div>
                                    <div class="item-discount text-uppercase">
                                        {{$vouchers[$i]['Value'].$type_l}} Discount
                                    </div>
                                    <div class="items-vouchers">
                                        @foreach($vouchers[$i]['ExpiryDates'] as $key=>$value)
                                            <div class="voucher mb-1">{{$value}} vouchers expire {{$key}}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endfor
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
