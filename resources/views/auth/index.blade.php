@extends('layouts.template')
@section('css')
<link rel="stylesheet" href="{{asset('assets/phone-input/css/intlTelInput.css')}}">
<link rel="stylesheet" href="{{asset('assets/datepicker/css/bootstrap-datepicker.css')}}">
<link href="/assets/bootstrap-pincode-input-master/css/bootstrap-pincode-input.css" rel="stylesheet">
<style type="text/css">
    .phone-css{
        padding-left: 95px !important;
    }
    .intl-tel-input {
        display: table-cell;
    }

    .intl-tel-input .selected-flag {
        z-index: 4;
    }

    .intl-tel-input .country-list {
        z-index: 5;
    }

    .input-group .intl-tel-input .form-control {
        border-top-left-radius: 4px;
        border-top-right-radius: 0;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 0;
    }
    .required{
        color: #d9534f;
        font-family: 'Futura-Medium-BT';
    }
    .modal-dialog{
        width: 100%;
        max-width: 100%;
        height: 100%;
        overflow: hidden;
        margin: 0;
    }
    .modal-content{
        height: 100%;
    }
    .modal-body{
        width: 20%;
        margin: 0 auto;
        margin-top: 6%;
    }
    .modal-close{
        position: absolute;
        right: 15px;
        top: 100px;
    }
    .modal-close img{
        width: 40px;
    }
</style>
@endsection
@section('content')
    @include('auth._login',array('sKey'=>$sKey));
    @include('auth._pin',array('sKey'=>$sKey));
    @include('auth._register',array('sKey'=>$sKey));
@endsection
@section('javascript')
    <script src="{{asset('assets/js/jquery.matchHeight-min.js')}}"></script>
    <script src="{{asset('assets/phone-input/js/intlTelInput.js')}}"></script>
    <script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('assets/bootstrap-pincode-input-master/js/bootstrap-pincode-input.js')}}"></script>
    <script type="text/javascript">

        $('body').on('click keydown','#country-listbox li', function(e){
            if ( e.which == 13 || e.which == 1 ) {
                var code = $(this).find('.iti__dial-code').text();


                $('input[type="hidden"][name="country_code{{$sKey}}"]').val(code);
            }
        });

        function ValidateMobile(id) {
            var s=$("#"+id).val();
            new_mobile= s.replace(/^0+/, '');
            $("#"+id).val(new_mobile);
        }

        jQuery(document).ready( function() {
            $('.datepicker').datepicker({
                format: 'YY-mm-dd',
            });
            $('.phone-css').on('keypress', function(key) {
                if(key.charCode < 48 || key.charCode > 57) return false;
            });
            var input = document.querySelector(".phone-css");
            window.intlTelInput(input, {
                allowDropdown: true,
                // autoHideDialCode: false,
                // autoPlaceholder: "off",
                // dropdownContainer: document.body,
                excludeCountries: ["il"],
                // formatOnDisplay: false,
                // geoIpLookup: function(callback) {
                //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                //     var countryCode = (resp && resp.country) ? resp.country : "";
                //     callback(countryCode);
                //   });
                // },
                hiddenInput: "full_number",
                // initialCountry: "auto",
                // localizedCountries: { 'de': 'Deutschland' },
                // nationalMode: false,
                // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                // placeholderNumberType: "MOBILE",
                preferredCountries: ['lb', 'sa'],
                separateDialCode: true,
                utilsScript: "{{asset('assets/phone-input/js/utils.js')}}",
            });
                var code = $("#country-listbox").find('.iti__active').data('dial-code');
                $('input[type="hidden"][name="country_code{{$sKey}}"]').val(code);
            @php
            $fkey='LoginRequestId'.$sKey;
            if (session()->has($fkey)) {
             $request_id=session()->get($fkey);
                //$request_id=session::get($fkey);
                if($request_id!='')
                {
                    $flag='pin';
                }
                else{
                $flag='login';
                }
            }
            else{

                $flag='login';
            }
echo "jQuery('#login-modal').modal();";
        /*     if($flag=='pin'){
                echo "jQuery('#pin-modal').modal();";
            }
            else{
                echo "jQuery('#login-modal').modal();";
            } */
            @endphp

            function validateEmail(email) {
	            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	            return re.test(email);
            }

           // jQuery('#login-modal').modal();
            $('#Loginbtn, #Loginbtn1').on('click', function(event){
	            spinnerButtons('show', $(this));
	            var that = this;
                event.preventDefault();
            	var validated = true;
                var mobile=$('#mobile{{$sKey}}').val();
                var email=$('#email{{$sKey}}').val();
                if(mobile=='')
                {
                    jQuery('#R_Mobile').html('Mobile is required');
                    validated = false;
                }
                else
                {
	                jQuery('#R_Mobile').html(' ');
                }
                if( email==''){
	                jQuery('#R_Email').html('Email is required');
	                validated = false;
                }
                else if( !validateEmail(email)){
	                jQuery('#R_Email').html('Email is not valid');
	                validated = false;
                }
                else
                {
	                jQuery('#R_Email').html(' ');
                }

                if(!validated){
	                Swal.fire({
		                title: 'Error!',
		                text: 'Invalid Input Data: some fields are not valid!',
		                icon: 'error',
		                confirmButtonText: 'Close'
	                });
	                spinnerButtons('hide', $(this));
	                return null;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, 
                    type:'POST',
                    url:'{{route('auth.signin')}}',
                    data:$("#LogIn").serialize(),
                    dataType:'json',
                    success:function(result){
                        if(result.status=='success')
                        {
                           // jQuery('#mobileNb{{$sKey}}').val(result.data['MobileNumber']);
                           // jQuery('#request_id{{$sKey}}').val(result.data['RequestId']);
	                        spinnerButtons('hide', $(that));
                           $('#Pin{{$sKey}}').val(result.data['CountryCode']);
                            jQuery('#login-modal').modal('hide');
                            jQuery('#pin-modal').modal();
                        }
                        else{
                            jQuery('#LoginMsg').html(data.message);
                        }
                    }
                });
            });


            $('#Pinbtn').on('click', function(event){
                event.preventDefault();
	            spinnerButtons('show', $(this));
	            var that = this;

	            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'POST',
                    url:'{{route('auth.pin')}}',
                    data:$("#PinForm").serialize(),
                    dataType:'json',
                    success:function(result){
	                    spinnerButtons('hide', $(that));
	                    $('.pincode-input-container').find('input').prop('disabled',false);
                        if(result.message=='success')
                        {
                            if(result.type=='register')
                            {
                                 $('#R_RequestId{{$sKey}}').val(result.data['RequestId']);
                                 $('#R_MobileNumber{{$sKey}}').val(result.data['MobileNumber']);
                                 $('#R_Email{{$sKey}}').val(result.data['Email']);
                                jQuery('#pin-modal').modal('hide');
                                jQuery('#register-modal').modal();
                            }
                            else{
                                location.replace('{{route('customer.index')}}'+'/'+result.type);
                            }
                        }
                        else{
	                        Swal.fire({
		                        title: 'Some fields are required!',
		                        icon: 'warning',
		                        confirmButtonText: 'Close'
	                        });
                            jQuery('#PinMsg').html(result.message);
                        }
                    }
                });
            });
            $('#Registerbtn').on('click', function(event){
                var dob=$('#R_dob{{$sKey}}').val();
                var firstn=$('#R_FirstName{{$sKey}}').val();
                var lastn=$('#R_FamilyName{{$sKey}}').val();
                if(dob=='' || firstn=='' || lastn=='')
                {
                    jQuery('#R_dob').html('DOB is required');
                    jQuery('#R_FirstName').html('Name is required');
                    jQuery('#R_FamilyName').html('Family Name is required');
                    return false;
                }

                event.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'POST',
                    url:'{{route('auth.register')}}',
                    data:$("#Register").serialize(),
                    dataType:'json',
                    success:function(result){

                        if(result.message=='success')
                        {
                            // jQuery('#mobileNb{{$sKey}}').val(result.data['MobileNumber']);
                            // jQuery('#request_id{{$sKey}}').val(result.data['RequestId']);
                            // jQuery('#country_code{{$sKey}}').val(result.data['CountryCode']);
                           // jQuery('#login-modal').modal('hide');
                          //  jQuery('#pin-modal').modal();
                            location.replace('{{route('customer.index')}}');
                        }
                        else{
                            jQuery('#RegisterMsg').html(result.message);
                        }
                    }
                });
            });
            $('#Resendbtn').on('click', function(event){
	            spinnerButtons('show', $(this));
	            var that = this;
                event.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'POST',
                    url:'{{route('auth.pinresend')}}',
                    dataType:'json',
                    success:function(data){
	                    spinnerButtons('hide', $(that));
                        // jQuery('#PinMsg').html(data.message);
	                    Swal.fire({
		                    title: 'Resend Pin Code!',
		                    text: 'New Pin Code was sent to your mobile number.',
		                    icon: 'success',
		                    confirmButtonText: 'Close'
	                    });
                    }
                });
            });
            $('#Backbtn').on('click', function(event){
                event.preventDefault();
                jQuery('#pin-modal').modal('hide');
                jQuery('#login-modal').modal();
            });
        });
    </script>
@endsection