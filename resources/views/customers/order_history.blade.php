@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
    <style>
        .favourite-link{
            margin-right: 10%;
        }
    </style>
@endsection

@section('content')
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-order-items col-favourite-items">

        <div class="col-xl-11 float-none mx-auto p-0">

            <br>
            <h4 class="title-1">Order History</h4>

            @foreach($query as $row)
                <div class="order-box p-3">
                    <h4 class="title">
                        ORDER {{$row->OrderId}}
                        <span>{{$row->DeliveryTime}}</span>
                    </h4>
                    <div class="order-info py-2 py-md-4">
                        <div class="row align-items-center">
                            <div class="col-sm-4 text-left text-sm-right text-label text-uppercase text-666666">
                                Address
                            </div>
                            <div class="col-sm-8 text-808080 futura-medium font-size-14">
                                @php( $adderss_array=array($row->Province,$row->City,$row->Line1,$row->Line2))
                                {{implode($adderss_array,', ')}}
                            </div>
                        </div>
                    </div>
                    <div class="action-div text-right">
                        <a onclick="SetFavouriteOrder{{$row->OrderId}}" id="Favourite{{$row->OrderId}}" class="btn btn-orderrepeat favourite-link"><img src="{{asset('assets/images/icon-heart.png')}}" height="15" class="mr-1"/> Favourite</a>
                        <a class="btn btn-orderrepeat"><img src="{{asset('assets/images/icon-refresh.png')}}" height="15" class="mr-1"/> Repeat Order</a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
    @include('partials.cart')
@endsection