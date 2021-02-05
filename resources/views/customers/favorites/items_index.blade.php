@extends('layouts.template')
@section('content')
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-favourite-items">
        <div class="col-lg-12 float-none p-0 mx-auto">
            @php
                $active='btn-8DBF43';
            @endphp
            <div class="title-div mb-5 pb-2">
                <div class="row-favourite mx-auto" style="box-shadow: none !important;">
                    <div class="col-favourite">
                        <div class="favourite-box" style="-webkit-box-shadow:none !important;">
                            <a href="{{route('favorite.items')}}"
                               class="btn btn-fav {{(!isset($sub_active) or $sub_active=='fav')? $active : 'btn-orders'}} mr-2">@lang('favourites')</a>
                            <a href="{{route('favorite.orders')}}"
                               class="btn {{isset($sub_active) && $sub_active=='orders'? $active : 'btn-orders'}}">@lang('orders')</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-favourite mx-auto">
                <div class="col-lg-12 float-none p-0 mx-auto">
                    <favorites-list-component></favorites-list-component>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-cartitems">
        <div class="cartbox-wrapper">
            <div class="cart-dropdown" id="cart-dropdown">
                <cart-component
                    :cart="{{json_encode($cart)}}"
                ></cart-component>
            </div>
        </div>
    </div>
@endsection
