@extends('layouts.template')
@section('content')
    @php

        $query=$delivery_info->SpecialInstructions;
    @endphp
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">

    <div class="title-div text-center mb-4">
        <h2 class="title">Checkout</h2>
    </div>
    @include('partials.checkout_bread')
        <div class="col-xl-7 col-lg-10 float-none p-0 mx-auto item-summary">
            <div class="title-div mb-5">
                <h2 class="title">Special Instructions</h2>
            </div>
            <div class="radios-green mb-5">
                @foreach($query as $row)
                <div class="custom-control custom-radio mb-4">
                    <input type="radio" id="instructions{{$row->ID}}" name="sp_i" value="{{json_encode($row)}}" class="custom-control-input">
                    <label class="custom-control-label text-uppercase" for="instructions{{$row->ID}}">
                        {{$row->Title}}
                    </label>
                </div>
                @endforeach
            </div>
            <div class="action-buttons text-center">
                <button type="button" class="btn btn-8DBF43 text-uppercase confirm">Checkout</button>
            </div>
        </div>
    </div>
    <div class="OrderSummary modal fade" id="OrderSummary" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div id="OrderSummaryDisplay"></div>
    </div>

@endsection
@section('javascript')
    <script>
        $(".confirm").click(function(){
            var radioValue = $("input[name='sp_i']:checked").val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {query: radioValue},
                url: '{{route('checkout.special.instructions.store')}}',
                success: function (data) {
                    $("#OrderSummaryDisplay").html(data);
                    jQuery('#OrderSummary').modal();
                }
            });

        });
    </script>

@endsection
