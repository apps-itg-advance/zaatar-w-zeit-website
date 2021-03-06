@extends('layouts.template')
@section('content')
    @php
        $query=$delivery_info->PaymentMethods;
    $p_id=(isset($cart_payment->PaymentId) and $cart_payment->PaymentId!='')?$cart_payment->PaymentId :'';
    $usd_check=(isset($cart_payment_currency) and $cart_payment_currency=='USD')? 'checked=checked':'';
    $lbp_check=(isset($cart_payment_currency) and $cart_payment_currency=='LBP') ? 'checked=checked':'';
    @endphp
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">
        <div class="title-div text-center mb-4">
            <h2 class="title">@lang('checkout')</h2>
        </div>
{{--        @include('partials.checkout_bread')--}}
        <div class="col-xl-6 col-lg-10 float-none p-0 mx-auto item-summary">
            <div class="title-div mb-5">
                <h2 class="title ml-0">{{$settings->Label}}
				</h2>
            </div>
            <div class="radios-green mb-5">
                @foreach($query as $row)
                    @php
                        $check='';
                        $usd_check='';
                        $lbp_check='';
                        if(isset($cart_payment->PaymentId) and $cart_payment->PaymentId==$row->PaymentId)
                        {
                        $check='checked="checked"';

                        }

                    @endphp
                    @if($row->Promo=='0')

                        <div class="custom-control custom-radio mb-4">
                            <input type="radio" {{$check}} id="payment{{$row->PaymentId}}" data-name="{{$row->Name}}" data-id="{{$row->PaymentId}}" name="payments" value="{{json_encode($row)}}" onclick="ShowCurrency({{$row->PaymentId}})" class="custom-control-input">
                            <label class="custom-control-label text-uppercase" for="payment{{$row->PaymentId}}">
                                {{$row->Label}}
                            </label>
                            @if($row->Name=='credit')
								@if(count($row->Currencies)>0)
                                <div class="hide-info" id="currency-{{$row->PaymentId}}">
                                    <div class="row">
                                        <div class="col-md-1"></div>
										@foreach($row->Currencies as $currency)
                                        <div class="col-md-3">
                                            <input type="radio" id="pay-{{$currency->Currency}}-{{$row->PaymentId}}" name="payment_currency{{$row->PaymentId}}" value="{{$currency->Currency}}" class="custom-control-input curr req{{$row->PaymentId}}">
                                            <label class="custom-control-label text-uppercase" for="pay-{{$currency->Currency}}-{{$row->PaymentId}}">{{$currency->Currency}}</label>
                                        </div>
										@endforeach
                                    </div>
                                </div>
								@endif
                            @endif

                        </div>

                    @endif
                @endforeach
            </div>
            <div class="action-buttons text-center">
                <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm">@lang('confirm')</button>
                @if(isset($settings->Required) and !$settings->Required)
                    <button type="button" class="btn btn-B3B3B3 text-uppercase skip" onclick="SkipBtn('{{$settings->LocalStepName}}')">@lang('skip')</button>
                @endif
            </div>
        </div>
    </div>
    <div class="OrderSummary modal fade" id="OrderSummary" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div id="OrderSummaryDisplay"></div>
    </div>
@endsection
@section('javascript')
    <script>
		$(document).ready(function() {
			$('.hide-info').hide();
            @if($p_id!='')
			ShowCurrency({{$p_id}});
            @endif
		});
		function ShowCurrency(id)
		{
			$('.hide-info').hide();
			$("#pay-{{$cart_payment_currency}}-"+id).prop("checked", true);

<?php /*
            @if($cart_payment_currency=='USD')
			$("#pay-usd-"+id).prop("checked", true);
            @endif
            @if($cart_payment_currency=='LBP')
			$("#pay-lbp-"+id).prop("checked", true);
            @endif
 */ ?>
            @if($cart_payment_currency=='')
			$(".curr").prop("checked", false);
            @endif
			$(".curr").prop("required", false);
			$(".req"+id).prop('required',true);
			$('#currency-'+id).show();

		}
		$(".confirm").click(function(){
			spinnerButtons('show', $(this));
			var that = this;
			var radioValue = $("input[name='payments']:checked").val();
			var id = $("input[name='payments']:checked").data('id');
			var pname = $("input[name='payments']:checked").data('name');
			var currency = $("input[name='payment_currency"+id+"']:checked").val();
			if(!radioValue || radioValue==undefined){
				Swal.fire({
					title: '<?php echo app('translator')->get('warning!'); ?>',
					text: '<?php echo app('translator')->get('you_must_choose_option!'); ?>',
					icon: 'warning',
					confirmButtonText: '<?php echo app('translator')->get('close'); ?>'
				});
				spinnerButtons('hide', $(this));
				return null;
			}
			if((!currency || currency==undefined) && pname=='credit'){
				Swal.fire({
					title: '<?php echo app('translator')->get('warning!'); ?>',
					text: '<?php echo app('translator')->get('you_must_choose_currency!'); ?>',
					icon: 'warning',
					confirmButtonText: '<?php echo app('translator')->get('close'); ?>'
				});
				spinnerButtons('hide', $(this));
				return null;
			}
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: 'POST',
				data: {query: radioValue,currency :currency},
				url: '{{route('checkout.payment.store')}}',
				success: function (data) {
			 if(data=='error_empty_item')
					{
						Swal.fire({
							title: '<?php echo app('translator')->get('error!'); ?>',
							text: '<?php echo app('translator')->get('you_must_select_an_item!'); ?>',
							icon: 'danger',
							confirmButtonText: '<?php echo app('translator')->get('close'); ?>'
						});
						spinnerButtons('hide', $(that));
						return null;

					}
					else if(pname=='credit')
					{
						window.location = '{{route('checkout.payment.cards')}}';
					}

					else{
						spinnerButtons('hide', $(that));
						$("#OrderSummaryDisplay").html(data);
						jQuery('#OrderSummary').modal();
					}

				}
			});
		});
    </script>
@endsection
