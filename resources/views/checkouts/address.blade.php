@extends('layouts.template')
@section('content')
    @php

    @endphp
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">

    <div class="title-div text-center mb-4">
        <h2 class="title">Checkout</h2>
    </div>
    @include('partials.checkout_bread')
    <div class="col-xl-6 col-lg-8 col-md-9 col-sm-12 item-summary float-none p-0 mx-auto">
        <div class="title-div mb-4">
            <h4 class="title">Address
                @if(count($addresses)<3)
                <a href="javascript:void(0)" onclick="AddAddress()" class="d-inline-block ml-5"><img src="{{asset('assets/images/icon-checkout-plus.png')}}" /></a>
                @endif
            </h4>
        </div>
        <div class="summary-items">
            @foreach($addresses as $address)
                @php
                    $checked='';
                    if($address->IsDefault==1)
                    {
                        $checked='checked="checked"';
                    }
                @endphp
            <div class="summary-item mb-5 mb-sm-4">
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" {{$checked}} id="customRadio{{$address->ID}}" name="AddressId" value="{{$address->ID}}" class="custom-control-input">
                    <input type="hidden" id="{{$address->ID}}" name="{{$address->ID}}" value="{{json_encode($address)}}">
                    <label class="custom-control-label" for="customRadio{{$address->ID}}">
                        <p class="text-uppercase m-0">{{$address->Name}}</p>
                        <span class="d-block">{{$address->CityName}} , {{$address->ProvinceName}} <br>{{$address->Line1}}<br>{{$address->Line2}}</span>
                    </label>
                </div>
                <div class="buttons">
                    <a href="#" onclick="EditAddress({{json_encode($address)}})" class="d-inline-block mr-3"><img src="{{asset('assets/images/icon-checkout-edit.png')}}" /></a>
                    <a href="{{route('customer.address.delete',['id'=>$address->ID])}}" class="d-inline-block"><img src="{{asset('assets/images/icon-checkout-close.png')}}" /></a>
                </div>
            </div>

            @endforeach
                <div class="edit-address modal fade" id="edit-address" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div id="displayData"></div>
                </div>
                <div class="add-address modal fade" id="add-address" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div id="displayAddData"></div>
                </div>
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
            var radioValue = $("input[name='AddressId']:checked").val();
            var address=$("#"+radioValue).val();

            if(radioValue){

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'POST',
                    data:{data:address},
                    url:'{{route('checkout.address.store')}}',
                    success:function(data){
                        window.location = '{{route('checkout.wallet')}}';
                    }
                });

            }
            else{
                alert('Please select an address');
            }

        });
        function EditAddress(address)
        {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                data:address,
                url:'{{route('customer.address.edit')}}',
                success:function(data){
                    $("#displayData").html(data);
                    jQuery('#edit-address').modal();
                   // LoadCart();
                   // _getCountCartItems();
                    //$(".col-cartitems").html(data);
                }
            });
           //jQuery('#editprofileModal').modal();
        }
        function AddAddress()
        {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:'{{route('customer.address.add')}}',
                success:function(data){
                    $("#displayAddData").html(data);
                    jQuery('#add-address').modal();
                    // LoadCart();
                    // _getCountCartItems();
                    //$(".col-cartitems").html(data);
                }
            });
            //jQuery('#editprofileModal').modal();
        }

    </script>
@endsection
