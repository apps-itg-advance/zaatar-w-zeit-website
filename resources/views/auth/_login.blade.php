<form id="LogIn" action="#">
    <div class="login-modal modal" id="login-modal"  aria-labelledby="exampleModalCenterTitle" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {{--<a href="/" class="modal-close"><img src="/assets/images/close-button.png" alt="zwz lebanon - login page - close page"></a>--}}
            <div class="modal-content float-none p-0 mx-auto">
                <div class="modal-body">

                    <h3 class="text-white text-center futura-book">Your one step closer for fresh goodness!</h3>

                    <div class="form-container">
                        <div class="form-group">
                            <label id="LoginMsg" style="color: red !important;"></label>
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="hidden" name="country_code{{$sKey}}" id="country_code{{$sKey}}" />
                            <input type="tel" class="form-control phone-css" onkeyup="ValidateMobile('mobile{{$sKey}}')" name="mobile{{$sKey}}" id="mobile{{$sKey}}" />
                            <div class="required" id="R_Mobile"></div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email{{$sKey}}" id="email{{$sKey}}" />
                            <div class="required" id="R_Email"></div>
                        </div>
                        <div class="mt-5">
                            <button type="submit" id="Loginbtn" class="btn btn-submit btn-login btn-block text-uppercase">
                                <span>Login</span>
                            </button>
                        </div>
                        <div class="mt-2">
                            <a href="/" style="color: black;"><button type="button" class="btn btn-submit btn-login btn-block text-uppercase">Return to Home Page</button></a>
                        </div>
                        <div class="mt-5">
                            <p class="text-white mb-2 futura-medium">Not Registered yet?</p>
                            <button type="button" id="Loginbtn1" class="btn btn-submit btn-login btn-block text-uppercase">Sign up</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>