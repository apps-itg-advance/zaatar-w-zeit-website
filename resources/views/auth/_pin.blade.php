<form id="PinForm" action="#">
    <div class="login-modal modal fade" id="pin-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content float-none p-0 mx-auto">
                <div class="modal-body">
                    <div class="form-group">
                        <label id="PinMsg" style="color: red !important;"></label>
                    </div>
                    <div class="form-group">
                        <label>PIN Code</label>
                        {{--<input type="text" class="form-control" id="Pin" name="pin{{$sKey}}" />--}}
                        <input name="pin{{$sKey}}" type="text" id="Pin" class="mb-2">
                    </div>
                    <div class="py-5">
                        <button type="button" id="Pinbtn" class="btn btn-submit btn-login btn-block text-uppercase">Confirmation</button>
                        <button type="button" id="Backbtn" class="btn btn-submit btn-login btn-block text-uppercase">Back</button>
                        <button type="button" id="Resendbtn" class="btn btn-submit btn-login btn-block text-uppercase">Resend Pin Code</button>
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
				// $("#pincode-callback").html("Complete callback from 6-digit test: Current value: " + value);
				// $(errorElement).html("I'm sorry, but the code not correct");

				// $("#pincode-callback").html("This is the 'complete' callback firing. Current value: " + value);
                $('#Pinbtn').click();
				// check the code
				// if(value!="123456"){
				// 	$(errorElement).html("The code is not correct. Should be '1234'");
				// }else{
				// 	alert('code is correct!');
				// }

			}
		});
	});
</script>