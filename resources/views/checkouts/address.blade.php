@extends('layouts.template')
@section('content')
    @php
        $addresses=(isset($addresses) and $addresses!=null)? $addresses:array();
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
            @if(isset($addresses))
            @foreach($addresses as $address)
                @php
                        $eta='';
                        $checked='';
                        if($address->IsDefault==1)
                        {
                            $checked='checked="checked"';
                            $eta=$address->DeliveryEta.' Min';
                        }
                @endphp
            <div class="summary-item mb-5 mb-sm-4">
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" {{$checked}} id="customRadio{{$address->ID}}" name="AddressId" value="{{$address->ID}}" data-open="{{$address->OpenHours}}" data-close="{{$address->CloseHours}}" data-eta="{{$address->DeliveryEta}}" onclick="ShowETA({{$address->ID}})" class="custom-control-input">
                    <input type="hidden" id="{{$address->ID}}" name="{{$address->ID}}" value="{{json_encode($address)}}">
                    <label class="custom-control-label" for="customRadio{{$address->ID}}">
                        <p class="text-uppercase m-0">{{$address->Name}}<span>Delivery Around</span> <span class="delivery-eta" id="eta-{{$address->ID}}">{{$eta}}</span></p>
                        <span class="d-block">{{$address->CityName}} , {{$address->ProvinceName}} <br>{{$address->Line1}}<br>{{$address->Line2}}</span>
                    </label>
                </div>
                <div class="buttons">
                    <a href="#" onclick="EditAddress({{json_encode($address)}})" class="d-inline-block mr-3"><img src="{{asset('assets/images/icon-checkout-edit.png')}}" /></a>
                    <a href="javascript:void(0)" onclick="DeleteAddress({{$address->ID}})" class="d-inline-block"><img src="{{asset('assets/images/icon-checkout-close.png')}}" /></a>
                </div>
            </div>

            @endforeach
            @endif
                <div class="edit-address modal fade" id="edit-address" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div id="displayData"></div>
                </div>
                <div class="add-address modal fade" id="add-address" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div id="displayAddData"></div>
                </div>
        </div>
        <div class="action-buttons text-center">
            <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm">Confirm</button>
            @if(isset($settings->Required) and !$settings->Required)
                <button type="button" class="btn btn-B3B3B3 text-uppercase skip" onclick="SkipBtn('address')">Skip</button>
            @endif
        </div>
    </div>
    </div>
@endsection
@section('javascript')
    <script>
        $(".confirm").click(function(){
	        spinnerButtons('show', $(this));
            var that=$(this);
	        var radioValue = $("input[name='AddressId']:checked").val();
            var address=$("#"+radioValue).val();

            if(radioValue){
                var open_time=$("#customRadio"+radioValue).data('open');
                var close_time=$("#customRadio"+radioValue).data('close');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'get',
                    url:'{{route('checkout.datetime')}}',
                    success:function(current_date){
                        if(current_date <open_time || current_date > close_time)
                        {
                            Swal.fire({
                                title: 'Warning!',
                                text: 'Sorry, the outlet is already closed',
                                icon: 'warning',
                                confirmButtonText: 'Close'
                            });
                            spinnerButtons('hide', that);
                            return false;
                        }
                        else{
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
                    }
                });


            }
            else{
	            Swal.fire({
		            title: 'Warning!',
		            text: 'You must choose an option!',
		            icon: 'warning',
		            confirmButtonText: 'Close'
	            });
	            spinnerButtons('hide', $(this));
	            return null;
            }

        });
        function ShowETA(id){
            var x=$("#customRadio"+id).data('eta');
            $('.delivery-eta').html('');
            $('#eta-'+id).html(x+' Min');
        }
        function DeleteAddress(address_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    window.location = '{{route('customer.address.delete')}}'+'/'+address_id;
                }
            })
        }
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
