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
        <div class="col-xl-6 col-lg-10 float-none p-0 mx-auto item-summary">
            <div class="title-div mb-4">
                <h2 class="title ml-0">The Real Green</h2>
            </div>
            <div class="greeninfo mb-5">
                <div class="line-1">Our aim is to reduce waste for sustainability.</div>
                <div class="line-2 text-8DBF43">We have removed plastic straws and cutlery bags with all delivery orders.</div>
            </div>
            <div class="radios-green">
                @foreach($query as $row)
                    @php
                        $check='';
                        if(isset($cart_green['Id']) and $cart_green['Id']==$row->ID)
                        {
                            $check='checked="checked"';
                        }
                    @endphp
                <div class="custom-control custom-radio mb-4">
                    <input type="radio" id="go_green{{$row->ID}}" {{$check}} name="go_green" value="{{$row->Title.'-:'.$row->ID.'-:'.$row->PLU}}" class="custom-control-input">
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
                <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm">Confirm</button>
                @if(isset($settings->Required) and !$settings->Required)
                    <button type="button" class="btn btn-B3B3B3 text-uppercase skip" onclick="SkipBtn('green')">Skip</button>
                @endif
            </div>
        </div>

    </div>
@endsection
@section('javascript')
    <script>
        $(".confirm").click(function(){
	        spinnerButtons('show', $(this));
	        var radioValue = $("input[name='go_green']:checked").val();
	        if(!radioValue || radioValue==undefined){
		        Swal.fire({
			        title: 'Warning!',
			        text: 'You must choose an option!',
			        icon: 'warning',
			        confirmButtonText: 'Close'
		        });
		        spinnerButtons('hide', $(this));
		        return null;
	        }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType:'json',
                data: {query: radioValue},
                url: '{{route('checkout.green.store')}}',
                success: function (data) {
                    window.location = '{{route('checkout.special_instructions')}}';

                }
            });


        });
    </script>
@endsection
