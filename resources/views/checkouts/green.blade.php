@extends('layouts.template')
@section('content')
    @php
        $query=$delivery_info->RealGreen;
    @endphp
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">

        <div class="title-div text-center mb-4">
            <h2 class="title">Checkout</h2>
        </div>
        @include('partials.checkout_bread')
        <div class="col-xl-7 col-lg-10 float-none p-0 mx-auto item-summary">
            <div class="title-div mb-4">
                <h2 class="title">The Real Green</h2>
            </div>
            <div class="greeninfo mb-5">
                <div class="line-1">Our aim is to reduce waste for sustainability.</div>
                <div class="line-2 text-8DBF43">We have removed plastic straws and cutlery bags with all delivery orders.</div>
            </div>
            <div class="radios-green">
                @foreach($query as $row)
                <div class="custom-control custom-radio mb-4">
                    <input type="radio" id="go_green{{$row->ID}}" name="go_green" value="{{$row->Title}}" class="custom-control-input">
                    <label class="custom-control-label text-uppercase" for="go_green{{$row->ID}}">
                       {{$row->Title}}
                    </label>
                </div>
               @endforeach
            </div>
            <div class="greeninfo my-5">
                <div class="line-1">Help us limit our straw and cutlery usage. We will be providing a paper straw and a cutlery bag only when requested.</div>
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
            var radioValue = $("input[name='go_green']:checked").val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType:'json',
                data: {query: radioValue},
                url: '{{route('checkout.green.store')}}',
                success: function (data) {
                    window.location = '{{route('checkout.payment')}}';
                }
            });


        });
    </script>
@endsection
