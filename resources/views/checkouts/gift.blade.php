@extends('layouts.template')
@section('content')
    @php
        $query=$delivery_info->Gift;
    @endphp
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">

        <div class="title-div text-center mb-4">
            <h2 class="title">Checkout</h2>
        </div>
        @include('partials.checkout_bread')
        <div class="col-xl-6 col-lg-10 col-md-12 col-sm-12 float-none p-0 mx-auto item-summary">
            <div class="title-div mb-4">
                <h2 class="title">Gift</h2>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <p><img src="{{asset('assets/images/gift-image.png')}}" class="img-fluid d-block mx-auto" /></p>
                </div>
                <div class="col-sm-6 pl-4">

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">To</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="gift_to{{$skey}}" name="gift_to{{$skey}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">From</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="gift_from{{$skey}}" name="gift_from{{$skey}}">
                        </div>
                    </div>
                    <div class="radios mt-4">
                        @foreach($query as $row)
                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="customRadio{{$row->ID}}" name="gift_value" value="{{$row->Title.'-:'.$row->ID.'-:'.$row->PLU}}" class="custom-control-input">
                                <label class="custom-control-label futura-medium" for="customRadio{{$row->ID}}">
                                    {{$row->Title}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="action-buttons text-center mt-3 pt-4">
                <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm">Confirm</button>
                @if(isset($settings->Required) and !$settings->Required)
                    <button type="button" class="btn btn-B3B3B3 text-uppercase skip" onclick="SkipBtn('gift')">Skip</button>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>

		$(".confirm").click(function(){
			spinnerButtons('show', $(this));
			to=$("#gift_to{{$skey}}").val();
			from=$("#gift_from{{$skey}}").val();
			var radioValue = $("input[name='gift_value']:checked").val();

			if(radioValue!='' && radioValue!=undefined &&  to!='' && from!='') {
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: 'POST',
					dataType:'json',
					data: {gift_to: to, gift_from: from, gift_value: radioValue},
					url: '{{route('checkout.gift.store')}}',
					success: function (data) {
						window.location = '{{route('checkout.green')}}';
					}
				});
			}
			else{
				Swal.fire({
					title: 'Warning!',
					text: 'Some fields are required!',
					icon: 'warning',
					confirmButtonText: 'Close'
				});
				spinnerButtons('hide', $(this));
				return null;
			}
		});
    </script>
@endsection
