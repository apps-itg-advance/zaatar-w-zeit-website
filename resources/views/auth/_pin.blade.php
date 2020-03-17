<form id="PinForm" action="#">
    <div class="login-modal modal fade" id="pin-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content float-none p-0 mx-auto">
                <div class="modal-body">
                    <div class="form-container">
                        <div class="form-group">
                            {{--<input type="text" class="form-control" id="Pin" name="pin{{$sKey}}" />--}}
                            <label>Enter the validation code sent by SMS</label>
                            <input name="pin{{$sKey}}" type="text" id="Pin" class="mb-2">
                            <label id="PinMsg" class="text-danger"></label>
                        </div>
                        <div class="py-5">
                            <button type="button" id="Pinbtn" class="btn btn-submit btn-login btn-block text-uppercase d-none">Confirmation</button>
                            <button type="button" id="Backbtn" class="btn btn-submit btn-login btn-block text-uppercase">Back</button>
                            <button type="button" id="Resendbtn" class="btn btn-submit btn-login btn-block text-uppercase">Resend Pin Code</button>
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
	});
</script>