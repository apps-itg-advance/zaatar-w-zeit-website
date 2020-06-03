@extends('layouts.template')
@section('content')
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">
        <div class="title-div text-center mb-4">
            <h2 class="title">@lang('checkout')</h2>
        </div>
        @include('partials.checkout_bread')
        <div class="col-xl-6 col-lg-8 col-md-9 col-sm-12 item-summary float-none p-0 mx-auto">
            <div class="title-div mb-4">
                <h4 class="title ml-0">Summary</h4>
            </div>
            <div class="summary-items">
                @php
                    $_total=0;
                @endphp
                @foreach($cart as $key=>$values)
                <div class="summary-item mb-4">
                    <h4>{{htmlspecialchars_decode($values['name'])}} <span class="d-inline-block ml-3">{{number_format($values['price'])}}</span></h4>
                    <div class="info text-808080">
                        @php

                            $modifiers=$values['modifiers'];
                            $_total+=$values['price']*$values['quantity'];
                            $md_array=array();
                            for($i=0;$i<count($modifiers);$i++)
                            {
                                array_push($md_array,$modifiers[$i]['name']);
                            }
                        $meal=isset($values['meal']) ? $values['meal']:array();
                        @endphp
                        {{implode(', ',$md_array )}}

                        {!! isset($meal['name'])? '<br>Make meal : '.$meal['name'].' '.number_format($meal['price']):'' !!}
                    </div>
                    <div class="buttons">
                        <a href="javascript:void(0)" onclick="_copyItem({{$key}})" class="d-inline-block mx-1"><img src="{{asset('assets/images/icon-checkout-plus.png')}}" /></a>
                        <a href="javascript:void(0)" onclick="editItem({{$key}})" class="d-inline-block"><img src="{{asset('assets/images/icon-checkout-edit.png')}}" /></a>
                        <a href="javascript:void(0)"  onclick="_deleteItem({{$key}})" class="d-inline-block"><img src="{{asset('assets/svg/icon-checkout-close.svg')}}" class="icon-checkou" /></a>
                    </div>
                </div>


                @endforeach
            </div>
            <br>

                <div class="delivery-block text-right">
                    @lang('delivery_fee') <span class="price d-inline-block ml-4">{{number_format($delivery_fees).' '.$currency}}</span>
                </div>

            <hr class="m-0" />
            <div class="total-block text-right mb-5">
                @lang('total') <span class="price d-inline-block ml-4" id="TotalV">{{number_format(($_total+$delivery_fees)).' '.$currency}}</span>
            </div>
            <div class="action-buttons text-center">
                <button type="button" class="btn btn-8DBF43 text-uppercase confirm">@lang('confirm')</button>
            </div>
        </div>
    </div>

@endsection
@section('javascript')
    <script>
        $(".confirm").click(function(){
            spinnerButtons('show', $(this));


           /*    $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'POST',
                    data:{order_schedule:order_schedulev,schedule_date:schedule_datev},
                    url:'{{route('checkout.schedule.save')}}',
                    success:function(data){
                       window.location = '{{route('checkout.address')}}';
                    }
                }); */


            window.location = '{{route('checkout.address')}}';
        });

        function _copyItem(id) {
            $.ajax({
                type:'GET',
                url:'{{route('carts.copy.item')}}'+'/'+id,
                success:function(data){
                    window.location = '{{route('checkout.summary')}}';
                }
            });
        }
        function _deleteItem(id) {
            $.ajax({
                type:'GET',
                url:'{{route('carts.delete')}}'+'/'+id,
                success:function(data){
                    window.location = '{{route('checkout.summary')}}';
                    //OpenCart();
                }
            });
        }
    </script>

@endsection

@section('css')
    <link href="{{asset('assets/datetime/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" media="screen">
    <style>
        .checkout-wrapper .item-summary .summary-items .summary-item .buttons {top: -4px !important;}
        .hidden-input{
            display: none;
        }
    </style>
@stop