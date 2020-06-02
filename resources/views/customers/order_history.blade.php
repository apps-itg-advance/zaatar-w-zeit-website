@extends('layouts.template')

@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
    <style>
        .link-favourite{
            margin-right: 10%;
        }
    </style>
@endsection

@section('content')
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-order-items col-favourite-items">
        <div class="col-xl-11 float-none mx-auto p-0">
            <br>
            <h4 class="title-1">@lang('order_history')</h4>
            @include('customers._order_grid',array('query'=>$query,'favourite'=>false))
        </div>
    </div>
    @include('partials.cart')
@endsection
