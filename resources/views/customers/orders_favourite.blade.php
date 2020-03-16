@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
@endsection

@section('content')
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-order-items col-favourite-items">

        <div class="col-xl-11 float-none mx-auto p-0">
            @include('customers._favourite_menu')
            @include('customers._order_grid',array('query'=>$favouriteOrders,'favourite'=>true))
        </div>

    </div>
    @include('partials.cart')
@endsection
