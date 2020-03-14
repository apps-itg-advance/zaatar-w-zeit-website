@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/circle.css')}}">
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
        <div class="col-xl-8 p-0 mx-auto item-summary wallet-wrapper float-none" >

            @include('customers._vouchers',array('vouchers'=>$vouchers,'checkout'=>true))
        </div>

    </div>
@endsection
