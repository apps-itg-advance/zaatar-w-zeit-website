@extends('layouts.template')
@section('content')
    @php
        $query=$delivery_info->PaymentMethods;
    @endphp
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">

    <div class="title-div text-center mb-4">
        <h2 class="title">Checkout</h2>
    </div>
    @include('partials.checkout_bread')
        <div class="col-xl-6 col-lg-10 float-none p-0 mx-auto item-summary">
            <div class="title-div mb-5">
                <h2 class="title ml-0">Payment</h2>
            </div>
            <div class="radios-green mb-5">
                @foreach($query as $row)
                    @if($row->Promo=='0')
                        <div class="custom-control custom-radio mb-4">
                            <input type="radio" id="payment{{$row->PaymentId}}" name="payments" value="{{json_encode($row)}}" class="custom-control-input">
                            <label class="custom-control-label text-uppercase" for="payment{{$row->PaymentId}}">
                                {{$row->Label}}
                            </label>
                        </div>
                    @endif
                @endforeach
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
            var radioValue = $("input[name='payments']:checked").val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType:'json',
                data: {query: radioValue},
                url: '{{route('checkout.payment.store')}}',
                success: function (data) {
                    window.location = '{{route('checkout.special_instructions')}}';
                }
            });

        });
    </script>
@endsection
