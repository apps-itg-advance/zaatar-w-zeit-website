@extends('layouts.template')
@section('content')

    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-favourite-items">
        <!--Loyalty Component-->
        <loyalty-component></loyalty-component>

        <div class="title-div mt-4">
            @php
                $details=array();
                if(isset($query->details))
                {
                 $details=$query->details;
                }
            @endphp
            <h3 class="text-8DBF43 text-uppercase font-weight-bold">{{@$details->FirstName.' '.@$details->LastName}}</h3>
        </div>

        <!--Wallet Component-->

        <hr/>

        <!--Orders List Component-->
        <orders-list-component></orders-list-component>
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
