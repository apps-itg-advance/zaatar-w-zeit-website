@extends('layouts.template')
@section('css')
    <style type="text/css">
        .col-cartitems{
            display: block !important;
        }
        .cart-items {
            height: auto !important;
        }
    </style>
@endsection
@section('content')
    @include('partials.cart')
@endsection
