@extends('layouts.template')
@section('content')
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">
        <div class="title-div text-center mb-4">
            <h2 class="title">@lang('checkout')</h2>
        </div>
        @include('partials.checkout_bread')
        <checkout-wizard
            :cart="{{json_encode($cart)}}"
            :step="{{json_encode($step)}}"
            :checkout-data="{{json_encode($checkoutData)}}"
            :checkout-info="{{json_encode($checkoutInfo)}}"
        ></checkout-wizard>
    </div>
@endsection
