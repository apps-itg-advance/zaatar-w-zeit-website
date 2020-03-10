<form id="LogIn" action="#">
    <div class="login-modal modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content col-sm-7 float-none p-0 mx-auto">
                <div class="modal-body">
                    <div class="form-group">
                        <label id="LoginMsg" style="color: red !important;"></label>
                    </div>
                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="hidden" name="country_code{{$sKey}}" id="country_code{{$sKey}}" />
                        <input type="tel" class="form-control phone-css" name="mobile{{$sKey}}" id="mobile{{$sKey}}" />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email{{$sKey}}" />
                    </div>
                    <div class="py-5">
                        <button type="button" id="Loginbtn"  class="btn btn-submit btn-login btn-block text-uppercase">Login</button>
                    </div>
                    <div class="mt-5">
                        <p class="text-white mb-2 futura-medium">Not Registered yet?</p>
                        <button type="button" class="btn btn-submit btn-login btn-block text-uppercase">Sign up</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
