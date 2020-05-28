@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
@endsection
@section('content')
    <div class="col-xl-10 float-none p-0 mx-auto">
        <div class="title-div text-center mb-4">
            <h2 class="title">@lang('checkout')</h2>
        </div>
        @include('partials.checkout_bread')
        <div class="col-xl-9 col-lg-10 float-none p-0 mx-auto item-summary wallet-wrapper">
           @include('customers._vouchers',array('checkout'=>true,'vouchers'=>$vouchers,'cart_wallet'=>$cart_wallet,'cart_vouchers'=>$cart_vouchers))
        </div>
    </div>


@endsection
