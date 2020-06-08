@extends('layouts.template')
@section('content')
    @php
        $addresses=(isset($addresses) and $addresses!=null)? $addresses:array();

        $check_schedule=(isset($order_schedule) and $order_schedule=='schedule') ? 'checked="checked"' : '';
        $check_new=((isset($order_schedule) and $order_schedule=='now') or $check_schedule=='') ? 'checked="checked"' : '';
        if($selected_address_id!=null and $selected_address_id!='')
        {
        $select_id=$selected_address_id;
        }
        elseif(isset($selected_address->AddressId)){
         $select_id=$selected_address->AddressId;

        }
    else{
    $select_id='';
    }

    @endphp
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">

    <div class="title-div text-center mb-4">
        <h2 class="title">@lang('checkout')</h2>
    </div>
    @include('partials.checkout_bread')
    <div class="col-xl-6 col-lg-8 col-md-9 col-sm-12 item-summary float-none p-0 mx-auto">
        <div class="title-div mb-4">
            <h4 class="title">@lang('address')
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
                        if($address->ID==$select_id)
                        {
                            $checked='checked="checked"';
                            $eta='<span class="delivery-txt">'.app('translator')->get('delivery_around').'</span>'.$address->DeliveryEta.' Min';
                        }
                    else{
                        $eta='';
                        $checked='';
                    }

                        @endphp
            <div class="summary-item mb-5 mb-sm-4">
                <div class="custom-control custom-radio mb-1">
                    <input type="radio" {{$checked}} id="customRadio{{$address->ID}}" name="AddressId" value="{{$address->ID}}" data-open="{{$address->OpenHours}}" data-close="{{$address->CloseHours}}" data-eta="{{$address->DeliveryEta}}" onclick="ShowETA({{$address->ID}})" class="custom-control-input">
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
                <hr />
            <div class="row">
                <div class="col-sm-12"></div>
                <div class="col-sm-2">
                    <div class="custom-control custom-radio mb-1">
                        @php
                        if($address->OpenHours)
                        {

                        }
                        @endphp
                        <input type="radio" id="order_now" {{$check_new}} required name="order_schedule"  onclick="ShowCalender()" value="now" class="custom-control-input">
                        <label class="custom-control-label" for="order_now"><p class="text-uppercase m-0">@lang('now')</p></label>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" id="order_schedule" {{$check_schedule}} required name="order_schedule" onclick="ShowCalender()" value="schedule" class="custom-control-input">
                        <label class="custom-control-label" for="order_schedule"><p class="text-uppercase m-0">@lang('scheduled')</p></label>
                    </div>
                </div>
                <div class="clearfix" style="height: 50px"></div>
                <div class="col-md-9 hidden-input" >
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="{{(App::isLocale('ar') ? 'ar-custom-label' : 'label-l')}}">
                            <select class="select-l" name="schedule_day"  onchange="RefreshCalander()" id="schedule-day">
                                <option value="today" {{(isset($schedule_day) and $schedule_day=='today') ?'selected':''}}  >Today</option>
                                <option value="tomorrow" {{(isset($schedule_day) and $schedule_day=='tomorrow') ? 'selected':''}} >Tomorrow</option>
                            </select>
                            </label>
                        </div>
                        <div class="col-sm-1"><p>@</p></div>
                        <div class="col-sm-4" id="display-time">

                        </div>
                    </div>
                </div>
                <div class="clearfix" style="height: 70px"></div>

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
            <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm">@lang('confirm')</button>
            @if(isset($settings->Required) and !$settings->Required)
                <button type="button" class="btn btn-B3B3B3 text-uppercase skip" onclick="SkipBtn('address')">@lang('skip')</button>
            @endif
        </div>
    </div>
    </div>
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            @if($select_id!='')
            InitCalander({{$select_id}});
            ShowCalender();
            @endif
        });
        $(".confirm").click(function(){
	        spinnerButtons('show', $(this));
            var that=$(this);
	        var radioValue = $("input[name='AddressId']:checked").val();
            var order_schedulev=$("input[name='order_schedule']:checked").val();
           var schedule_day =  $('select[name="schedule_day"]').find('option:selected').val();
            var schedule_datev = $('select[name="schedule_date"]').find('option:selected').val();
            var address=$("#"+radioValue).val();

            if(radioValue){
                var open_time=$("#customRadio"+radioValue).data('open');
                var close_time=$("#customRadio"+radioValue).data('close');

                if(order_schedulev==undefined)
                {
                    Swal.fire({
                        icon: 'warning',
                        title: '<?php echo app('translator')->get('you_must_select_an_ordertype_(now_scheduled).'); ?>',
                        showConfirmButton: '<?php echo app('translator')->get('close'); ?>'
                    });
                    spinnerButtons('hide', that);
                    return false;
                }
                else if(order_schedulev=='schedule' && schedule_datev=='') {
                    Swal.fire({
                        icon: 'warning',
                        title: '<?php echo app('translator')->get('you_must_select_schedule_date'); ?>',
                        showConfirmButton: '<?php echo app('translator')->get('close'); ?>'
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
                        var date_selected=$("#schedule-day").val();
                        if(date_selected == "today")
                        {
                            if (current_date < open_time || current_date > close_time) {

                                Swal.fire({
                                    title: '<?php echo app('translator')->get('warning!'); ?>',
                                    text: '<?php echo app('translator')->get('sorry_outlet_already_closed'); ?>',
                                    icon: 'warning',
                                    confirmButtonText: '<?php echo app('translator')->get('close'); ?>'
                                });
                                spinnerButtons('hide', that);
                                return false;
                            } else {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: 'POST',
                                    data: {
                                        data: address,
                                        order_schedule: order_schedulev,
                                        schedule_date: schedule_datev,
                                        schedule_day: schedule_day
                                    },
                                    url: '{{route('checkout.address.store')}}',
                                    success: function (data) {
                                        window.location = '{{route('checkout.wallet')}}';
                                    }
                                });
                            }
                        } else {
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: 'POST',
                                data: {
                                    data: address,
                                    order_schedule: order_schedulev,
                                    schedule_date: schedule_datev,
                                    schedule_day: schedule_day
                                },
                                url: '{{route('checkout.address.store')}}',
                                success: function (data) {
                                    window.location = '{{route('checkout.wallet')}}';
                                }
                            });
                        }
                    }
                });


            }
            else{
	            Swal.fire({
		            title: '<?php echo app('translator')->get('warning!'); ?>',
		            text: '<?php echo app('translator')->get('you_must_choose_option!'); ?>',
		            icon: 'warning',
		            confirmButtonText: '<?php echo app('translator')->get('close'); ?>'
	            });
	            spinnerButtons('hide', $(this));
	            return null;
            }

        });
        function ShowCalender() {
            if($('#order_schedule').is(":checked"))
            {
                var AddressId = $("input[name='AddressId']:checked").val();
                $(".hidden-input").show();
                InitCalander(AddressId);
            }

            else
                $(".hidden-input").hide();
        }
        function ShowETA(id){

            var radioValue = $("input[name='AddressId']:checked").val();
            if(radioValue)
            {
                var open_time=$("#customRadio"+radioValue).data('open');
                var close_time=$("#customRadio"+radioValue).data('close');
                console.log("Op :"+open_time+" Clos "+close_time);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'get',
                    url:'{{route('checkout.datetime')}}',
                    success:function(current_date){
                        if(current_date <open_time || current_date > close_time) {
                            $("#order_now").attr('disabled',true);
                            $("#order_now").prop("checked", false);
                        }
                        else
                        {
                            $("#order_now").attr('disabled',false);
                        }
                    }
                });

            }

            var x=$("#customRadio"+id).data('eta');
            $('.delivery-eta').html('');
            $('#eta-'+id).html('<span class="delivery-txt">@lang('delivery_around')</span>'+x+' Min');
            InitCalander(id);
        }
        function DeleteAddress(address_id) {
            Swal.fire({
                title: '<?php echo app('translator')->get('are_you_sure?'); ?>',
                text: "<?php echo app('translator')->get('you_wont_be_able_revert_this!'); ?>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?php echo app('translator')->get('yes_delete_it!'); ?>'
            }).then((result) => {
                if (result.value) {
                    window.location = '{{route('customer.address.delete')}}'+'/'+address_id;
                }
            })
        }
        function EditAddress(address)
        {
            var AddressId = $("input[name='AddressId']:checked").val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                data:{data:address,checked_id:AddressId},
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
        function RefreshCalander() {
            var AddressId = $("input[name='AddressId']:checked").val();
            InitCalander(AddressId);
        }
        function InitCalander(id) {

            $('.sch').show();
            var open_time=$("#customRadio"+id).data('open');
            var _open=open_time.split(':');
            var close_time=$("#customRadio"+id).data('close');
            var date_selected=$("#schedule-day").val();
            var x=$("#customRadio"+id).data('eta');
            console.log("C "+close_time +"X "+x);
            var _close=close_time.split(':');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'get',
                data:{open_time:open_time,close_time:close_time,date_selected:date_selected,eta:x},
                url:'{{route('checkout.calender')}}',
                success:function(data){
                    $("#display-time").html(data);
                }
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
