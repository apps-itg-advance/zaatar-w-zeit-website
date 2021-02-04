@extends('layouts.template')
@section('content')
    <items-list-component :items="{{json_encode($query->data)}}"
                          :combos="{{json_encode($query->Combo)}}"
                          :cart="{{json_encode($cart)}}"
                          :title="{{json_encode($cat_title)}}">
    </items-list-component>
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
