<form id="Register" action="#">
    <input type="hidden" name="request_id{{$sKey}}" id="R_RequestId{{$sKey}}" />
    <div class="login-modal modal fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content col-sm-7 float-none p-0 mx-auto">
                <div class="modal-body">
                    <h3 class="text-center my-4 title text-uppercase">Create an account</h3>
                    <div class="form-group">
                        <label id="RegisterMsg" style="color: red !important;"></label>
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text"  name="first_name{{$sKey}}" id="R_FirstName{{$sKey}}" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Family Name</label>
                        <input type="text"  name="family_name{{$sKey}}" id="R_FamilyName{{$sKey}}" class="form-control"  />
                    </div>
                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="hidden" name="country_code{{$sKey}}" id="R_CountryCode{{$sKey}}" />
                        <input type="tel" class="form-control phone-css" name="mobile{{$sKey}}" id="R_MobileNumber{{$sKey}}" />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" readonly="readonly" class="form-control" name="email{{$sKey}}" id="R_Email{{$sKey}}" />
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender{{$sKey}}" id="R_Gender" class="form-control">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="py-5">
                        <button type="button" id="Registerbtn"  class="btn btn-submit btn-login btn-block text-uppercase">Register</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
