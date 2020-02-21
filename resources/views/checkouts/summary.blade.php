@extends('layouts.template')
@section('content')
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">
        <div class="title-div text-center mb-4">
            <h2 class="title">Checkout</h2>
        </div>
        @include('partials.checkout_bread')
        <div class="col-xl-7 col-lg-8 col-md-9 col-sm-12 item-summary float-none p-0 mx-auto">
            <div class="title-div mb-4">
                <h4 class="title">Summary</h4>
            </div>
            <div class="summary-items">
                @php
                    $_total=0;

                @endphp
                @foreach($cart as $key=>$values)
                <div class="summary-item mb-4">
                    <h4><span id="Qty-{{$key}}">{{$values['quantity']}} </span>{{$values['name']}} <span class="d-inline-block ml-3">{{$values['price']}}</span></h4>
                    <div class="info text-808080">
                        @php

                            $modifiers=$values['modifiers'];
                            $_total+=$values['price']*$values['quantity'];
                            $md_array=array();
                            for($i=0;$i<count($modifiers);$i++)
                            {
                                array_push($md_array,$modifiers[$i]['name']);
                            }
                        @endphp
                        {{implode(', ',$md_array )}}
                    </div>
                    <div class="buttons">
                        <a href="javascript:void(0)" onclick="addItemQty({{$key}},'Qty-','TotalV')" class="d-inline-block mx-1"><img src="{{asset('assets/images/icon-checkout-plus.png')}}" /></a>
                        <a href="javascript:void(0)" onclick="editItem({{$key}})" class="d-inline-block"><img src="{{asset('assets/images/icon-checkout-edit.png')}}" /></a>
                        <a href="#" class="d-inline-block"><img src="{{asset('assets/svg/icon-checkout-close.svg')}}" class="icon-checkou" /></a>
                    </div>
                </div>
                    <div class="cartbig-modal modal fade" id="cartbig-modal-{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div id="edit-data-{{$key}}"></div>
                    </div>

                @endforeach
            </div>
            <div class="delivery-block text-right">
                Delivery fee <span class="price d-inline-block ml-4">{{$delivery_fees.' '.$currency}}</span>
            </div>
            <hr/>
            <div class="total-block text-right mb-5">
                Total <span class="price d-inline-block ml-4" id="TotalV">{{($_total+$delivery_fees).' '.$currency}}</span>
            </div>
            <div class="action-buttons text-center">
                <button type="button" class="btn btn-8DBF43 text-uppercase confirm">Confirm</button>
            </div>
        </div>
    </div>

@endsection
@section('javascript')
    <script>
        $(".confirm").click(function(){
            window.location = '{{route('checkout.address')}}';
        });

    </script>
@endsection