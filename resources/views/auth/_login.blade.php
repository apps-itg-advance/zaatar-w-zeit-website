<form id="LogIn" action="#">
    <div class="login-modal modal" id="login-modal"  aria-labelledby="exampleModalCenterTitle" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {{--<a href="/" class="modal-close"><img src="/assets/images/close-button.png" alt="zwz lebanon - login page - close page"></a>--}}
            <div class="modal-content float-none p-0 mx-auto">
                <div class="modal-body">
                    <div class="form-group">
                        <label id="LoginMsg" style="color: red !important;"></label>
                    </div>
                    <div class="form-group">
                        <label>Mobile</label>
                        <div class="required" id="R_Mobile"></div>
                        <input type="hidden" name="country_code{{$sKey}}" id="country_code{{$sKey}}" />
                        <input type="tel" class="form-control phone-css" onkeyup="ValidateMobile('mobile{{$sKey}}')" name="mobile{{$sKey}}" id="mobile{{$sKey}}" required />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <div class="required" id="R_Email"></div>
                        <input type="email" class="form-control" name="email{{$sKey}}" id="email{{$sKey}}" required />
                    </div>
                    <div class="py-5">
                        <button type="submit" id="Loginbtn" class="btn btn-submit btn-login btn-block text-uppercase">Login</button>
                    </div>
                    <div class="mt-5">
                        <p class="text-white mb-2 futura-medium">Not Registered yet?</p>
                        <button type="button" id="Loginbtn1" class="btn btn-submit btn-login btn-block text-uppercase">Sign up</button>
                    </div>
                    <a href="/" class="text-white futura-medium float-right mt-2"><span class="fa fa-long-arrow-alt-left"></span> Back to Menu</a>
                </div>
            </div>
        </div>
    </div>
</form>
