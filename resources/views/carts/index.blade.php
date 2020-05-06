@extends('layouts.template')
@section('css')
    <style type="text/css">
        .col-cartitems{
            display: block !important;
        }
        .cart-items {
            height: auto !important;
        }
        .cartbox-wrapper .cart-dropdown{
            position:relative !important;
            height: auto !important;
        }
        .mt-3, .my-3 {
            margin-top: 2rem !important;
        }
    </style>
@endsection
@section('content')
    @include('partials.cart')
@endsection
