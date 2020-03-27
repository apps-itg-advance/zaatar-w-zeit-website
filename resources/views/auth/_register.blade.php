<form id="Register" action="#">
    <input type="hidden" name="request_id{{$sKey}}" id="R_RequestId{{$sKey}}" />
    <div class="login-modal modal fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content float-none p-0 mx-auto">
                <div class="modal-body">
                    <h3 class="text-white text-center futura-book">Create an account</h3>

                    <div class="form-container">

                        <div class="form-group m-0">
                            <label id="RegisterMsg" style="color: rgb(217, 83, 79) !important;"></label>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text"  name="first_name{{$sKey}}" maxlength="70" id="R_FirstName{{$sKey}}" class="form-control" required />
                            <div class="required" id="R_FirstName"></div>
                        </div>
                        <div class="form-group">
                            <label>Family Name</label>
                            <input type="text"  name="family_name{{$sKey}}" maxlength="70" id="R_FamilyName{{$sKey}}" class="form-control"  required />
                            <div class="required" id="R_FamilyName"></div>
                        </div>

                        <input type="hidden" name="country_code{{$sKey}}" id="R_CountryCode{{$sKey}}" />
                        <input type="hidden" class="form-control phone-css" name="mobile{{$sKey}}" id="R_MobileNumber{{$sKey}}" />

                        <input type="hidden" readonly="readonly" class="form-control" name="email{{$sKey}}" id="R_Email{{$sKey}}" />

                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender{{$sKey}}" id="R_Gender{{$sKey}}" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <div class="required" id="R_Gender"></div>
                        </div>
                        <div class="form-group">
                            <label>DOB</label>
                            {{--<div class="input-group date " data-provide="datepicker" data-date-format="yyyy-mm-dd" >--}}
                            <div class="input-group">
                                {{--<div class="flatpickr">--}}
                                    {{--<input class="flatpickr flatpickr-input active" type="text" placeholder="Select Date.."> <!-- input is mandatory -->--}}
                                {{--</div>--}}

                                <input type="text" class="form-control flatpickr-today active"  name="dob{{$sKey}}" id="R_dob{{$sKey}}"  required />
                                {{--<div class="input-group-addon input-button" style="float: right">--}}
                                    {{--<i class="fa fa-calendar fa-2x" data-toggle style="color: #8dbf43" aria-hidden="true"></i>--}}
                                {{--</div>--}}
                            </div>
                            <div class="required" id="R_dob"></div>
                        </div>
                        <div class="mt-3">
                            <button type="button" id="Registerbtn"  class="btn btn-submit btn-login btn-block text-uppercase">Register</button>
                            <button type="button" id="BackToPinBtn" class="btn btn-submit btn-login btn-block text-uppercase">Back</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<style>
    .flatpickr-calendar{
        font-family: 'Futura-Medium-BT' !important;
    }
</style>