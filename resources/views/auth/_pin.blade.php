<form id="PinForm" action="#">
    <div class="login-modal modal fade" id="pin-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content float-none p-0 mx-auto">
                <div class="modal-body">
                    <div class="form-container">
                        <div class="form-group">
                            {{--<input type="text" class="form-control" id="Pin" name="pin{{$sKey}}" />--}}
                            <label><?php echo app('translator')->get('not_registered'); ?></label>
                            <input name="pin{{$sKey}}" type="text" id="Pin" class="mb-2">
                            <label id="PinMsg" class="text-danger"></label>
                        </div>
                        <div class="py-5">
                            <button type="button" id="Pinbtn" class="btn btn-submit btn-login btn-block text-uppercase d-none">@lang('confirmation')</button>
                            <button type="button" id="Backbtn" class="btn btn-submit btn-login btn-block text-uppercase">@lang('back')</button>
                            <button type="button" id="Resendbtn" class="btn btn-submit btn-login btn-block text-uppercase">@lang('resend_pin_code')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
	$(document).ready(function() {
		$('#Pin').pincodeInput({
			hidedigits: false, inputs: 6, complete: function (value, e, errorElement) {
				$('#Pinbtn').removeClass('d-none');
				$('.pincode-input-container').find('input').prop('disabled',true);
				$('#Pinbtn').click();
			}
		});
        $(".pincode-input-text").prop("pattern","[0-9]*");
        $(".pincode-input-text").prop("type","number");
        $(".pincode-input-text").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
	});
</script>