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
                @php
                    $eta='';
                    $checked='';
                @endphp
            @foreach($addresses as $address)
                @php

                     /*   if($address->IsDefault=='1' and $eta=='')
                        {
                            //$checked='checked="checked"';
                            $eta='<span class="delivery-txt">Delivery around </span>'.$address->DeliveryEta.' Min';
                        }
                        else{
                            $eta='';
                            $checked='';
                        } */
                        @endphp
            <div class="summary-item mb-5 mb-sm-4">
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" onclick="InitCalander({{$address->ID}})" {{$checked}} id="customRadio{{$address->ID}}" name="AddressId" value="{{$address->ID}}" data-open="{{$address->OpenHours}}" data-close="{{$address->CloseHours}}" data-eta="{{$address->DeliveryEta}}" onclick="ShowETA({{$address->ID}})" class="custom-control-input">
                    <input type="hidden" id="{{$address->ID}}" name="{{$address->ID}}" value="{{json_encode($address)}}">
                    <label class="custom-control-label" for="customRadio{{$address->ID}}">
                        <p class="text-uppercase m-0">{{$address->Name}}<span class="delivery-eta" id="eta-{{$address->ID}}">{!! $eta !!}</span></p>
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
            <div class="sch">
            <div class="row">
                <div class="col-sm-12"></div>
                <div class="col-sm-2">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="order_new" required name="order_schedule"  onclick="ShowCalender()" value="new" class="custom-control-input">
                        <label class="custom-control-label" for="order_new">New</label>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="order_schedule" required name="order_schedule" onclick="ShowCalender()" value="schedule" class="custom-control-input">
                        <label class="custom-control-label" for="order_schedule">Scheduled</label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="control-group hidden-input">
                        <div class="controls input-append date form_datetime" data-date="<?=date('Y-m-d h:i:s')?>" data-date-format="yyyy-mm-dd hh:ii" data-link-field="dtp_input1">
                            <input size="16" type="text" value="" readonly class="form-control" name="schedule_date">
                            <span class="add-on"><i class="icon-th"></i></span><br>
                        </div>
                    </div>
                </div>
            </div>
            </div>
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
    <script type="text/javascript" src="{{asset('assets/datetime/js/bootstrap-datetimepicker.js')}}" charset="UTF-8"></script>

    <script>
        $(".confirm").click(function(){
	        spinnerButtons('show', $(this));
            var that=$(this);
	        var radioValue = $("input[name='AddressId']:checked").val();
            var order_schedulev=$("input[name='order_schedule']:checked").val();
            var schedule_datev = $('input[name="schedule_date"]').val();

            var address=$("#"+radioValue).val();

            if(radioValue){
                var open_time=$("#customRadio"+radioValue).data('open');
                var close_time=$("#customRadio"+radioValue).data('close');

                if(order_schedulev==undefined)
                {
                    Swal.fire({
                        icon: 'warning',
                        title: 'You must select an order type (new or scheduled).',
                        showConfirmButton: 'close'
                    });
                    spinnerButtons('hide', that);
                    return false;
                }
                else if(order_schedulev=='schedule' && schedule_datev=='') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'You must select schedule date.',
                        showConfirmButton: 'close'
                    });
                    spinnerButtons('hide', that);
                    return false;
                }
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
                                data:{data:address,order_schedule:order_schedulev,schedule_date:schedule_datev},
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
        function ShowCalender() {
            if($('#order_schedule').is(":checked"))
                $(".hidden-input").show();
            else
                $(".hidden-input").hide();
        }
        function ShowETA(id){
            var x=$("#customRadio"+id).data('eta');
            $('.delivery-eta').html('');
            $('#eta-'+id).html('<span class="delivery-txt">Delivery around </span>'+x+' Min');
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
        function InitCalander(id) {
            $('.sch').show();
            var open_time=$("#customRadio"+id).data('open');
            var _open=open_time.split(':');
            var close_time=$("#customRadio"+id).data('close');
            var _close=close_time.split(':');
            var array_hours=[];
            for(var i=0;i<24;i++)
            {
                if(i<parseInt(_open[0]) || i>parseInt(_close[0]))
                {
                    array_hours.push(i);
                    console.log(i);
                }

            }
            $('.form_datetime').datetimepicker({
                //language:  'fr',
                startDate:'<?=$current_date?>',
                format: 'yyyy-mm-dd hh:ii',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                showMeridian: 1,
                hoursDisabled: array_hours

            });
        }
    </script>

@endsection
@section('css')
    <link href="{{asset('assets/datetime/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" media="screen">
    <style>
        .hidden-input{
            display: none;
        }
        .sch{
            display: none;
            width: 100%;
        }
    </style>
@stop
