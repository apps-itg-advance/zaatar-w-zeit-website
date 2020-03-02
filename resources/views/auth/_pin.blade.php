<form id="PinForm" action="#">
    <div class="login-modal modal fade" id="pin-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content col-sm-7 float-none p-0 mx-auto">
                <div class="modal-body">
                    <div class="form-group">
                        <label id="PinMsg" style="color: red !important;"></label>
                    </div>
                    <div class="form-group">
                        <label>PIN Code</label>
                        <input type="text" class="form-control" id="Pin" name="pin{{$sKey}}" />
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
