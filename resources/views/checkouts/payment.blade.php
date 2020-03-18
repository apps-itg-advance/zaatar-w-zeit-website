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
                            <input type="radio" id="payment{{$row->PaymentId}}" data-name="{{$row->Name}}" data-id="{{$row->PaymentId}}" name="payments" value="{{json_encode($row)}}" onclick="ShowCurrency({{$row->PaymentId}})" class="custom-control-input">
                            <label class="custom-control-label text-uppercase" for="payment{{$row->PaymentId}}">
                                {{$row->Label}}
                            </label>
                                @if($row->Name=='credit')
                                <div class="hide-info" id="currency-{{$row->PaymentId}}">
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-3">
                                            <input type="radio" id="pay-usd-{{$row->PaymentId}}" name="payment_currency{{$row->PaymentId}}" value="USD" class="custom-control-input curr req{{$row->PaymentId}}">
                                            <label class="custom-control-label text-uppercase" for="pay-usd-{{$row->PaymentId}}">USD</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="radio" id="pay-lbp-{{$row->PaymentId}}" name="payment_currency{{$row->PaymentId}}" value="LBP" class="custom-control-input curr req{{$row->PaymentId}}">
                                            <label class="custom-control-label text-uppercase" for="pay-lbp-{{$row->PaymentId}}">LBP</label>
                                        </div>
                                    </div>
                                </div>
                                 @endif

                        </div>

                    @endif
                @endforeach
            </div>
            <div class="action-buttons text-center">
                <button type="button" class="btn btn-8DBF43 text-uppercase confirm">Confirm</button>
                @if(isset($settings->Required) and !$settings->Required)
                    <button type="button" class="btn btn-B3B3B3 text-uppercase skip" onclick="SkipBtn('payment')">Skip</button>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            $('.hide-info').hide();
        });
        function ShowCurrency(id)
        {
            $('.hide-info').hide();
            $(".curr").prop("checked", false);
            $(".curr").prop("required", false);
            $(".req"+id).prop('required',true);
            $('#currency-'+id).show();

        }
		$(".confirm").click(function(){
			spinnerButtons('show', $(this));
			var radioValue = $("input[name='payments']:checked").val();
            var id = $("input[name='payments']:checked").data('id');
            var pname = $("input[name='payments']:checked").data('name');
            var currency = $("input[name='payment_currency"+id+"']:checked").val();
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
            if((!currency || currency==undefined) && pname=='credit'){
                Swal.fire({
                    title: 'Warning!',
                    text: 'You must choose a currency!',
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
				data: {query: radioValue,currency :currency},
				url: '{{route('checkout.payment.store')}}',
				success: function (data) {
					window.location = '{{route('checkout.special_instructions')}}';
				}
			});
		});
    </script>
@endsection
