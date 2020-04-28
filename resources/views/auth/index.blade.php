@extends('layouts.template')
@section('css')
<link rel="stylesheet" href="{{asset('assets/phone-input/css/intlTelInput.css')}}">
{{--<link rel="stylesheet" href="{{asset('assets/datepicker/css/bootstrap-datepicker.css')}}">--}}
<link href="/assets/bootstrap-pincode-input-master/css/bootstrap-pincode-input.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        width: 50%;
        margin: 0 auto;
        margin-top: 8%;
    }
    .modal-body .form-container{
        width: 40%;
        margin: 0 auto;
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
    {{--<script src="{{asset('assets/datepicker/js/bootstrap-datepicker.js')}}"></script>--}}
    <script src="{{asset('assets/bootstrap-pincode-input-master/js/bootstrap-pincode-input.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="text/javascript">

	    //validate mobile if lebanon (+961) maximum digits are 8 numbers
	    $('.phone-css').on('keyup', function(e) {
            var mobile=$('.phone-css').val();
            ValidateIntMobile($('.iti__selected-dial-code').text(),mobile);
	    });
        function ValidateIntMobile(code,mobile)
        {
            var css_mobile='.phone-css';
            if(code==='+961')
            {
                var mobile_code1=mobile.charAt(0);
                if(mobile_code1!='3' && mobile_code1!='7' && mobile_code1!='8')
                {
                    $(css_mobile).val('');
                    return false;
                }
                else{
                    var mobile_code=mobile.charAt(1);
                    if(mobile.charAt(0)==='7')
                    {
                        if(mobile_code!='0' && mobile_code!='1' && mobile_code!='6' && mobile_code!='8')
                        {
                            $(css_mobile).val(mobile.charAt(0));
                            return false;
                        }
                    }
                    else if(mobile.charAt(0)==='8')
                    {
                        if(mobile_code!='1')
                        {
                            $(css_mobile).val(mobile.charAt(0));
                            return false;
                        }
                    }

                }
                if(mobile.charAt(0)==='3')
                {
                    if(mobile.length>7)
                    {
                        $(css_mobile).val($(css_mobile).val().substring(0, 7));
                        return true;
                    }
                    else if(mobile.length<7)
                    {
                        return false;
                    }
                    else{
                        return true;
                    }
                }
                else{
                    if(mobile.length>8)
                    {
                        $(css_mobile).val($(css_mobile).val().substring(0, 8));
                        return true;
                    }
                    else if(mobile.length<8)
                    {
                        return false;
                    }
                    else{
                        return true;
                    }
                }

            }
            else if(code==='+971')
            {
                if(mobile.charAt(0)!=5)
                {
                    $(css_mobile).val('');
                    return false;
                }
                else{
                    var mobile_code=mobile.charAt(1);
                    if(mobile_code!='0' && mobile_code!='2' && mobile_code!='4' && mobile_code!='5' && mobile_code!='6' && mobile_code!='8' )
                    {
                        $(css_mobile).val(mobile.charAt(0));
                        return false;
                    }
                }
                if(mobile.length>9)
                {
                    $(css_mobile).val($(css_mobile).val().substring(0, 9));
                    return true;
                }
                else if(mobile.length<9)
                {
                    return false;
                }
                else{
                    return true;
                }
            }
            else if(code==='+966')
            {
                if(mobile.charAt(0)!=5)
                {
                    $(css_mobile).val('');
                    return false;
                }
                else{
                    var mobile_code=mobile.charAt(1);
                    if(mobile_code!='0' && mobile_code!='1' && mobile_code!='3' && mobile_code!='4' && mobile_code!='5' && mobile_code!='6' && mobile_code!='7' && mobile_code!='8' && mobile_code!='9' )
                    {
                        $(css_mobile).val(mobile.charAt(0));
                        return false;
                    }
                }
                if(mobile.length>9)
                {
                    $(css_mobile).val($(css_mobile).val().substring(0, 9));
                    return true;
                }
                else if(mobile.length<9)
                {
                    return false;
                }
                else{
                    return true;
                }
            }
        }
	    function validateMobileMaxIfLebanon(){
            var mobile=$('.phone-css').val();
            ValidateIntMobile($('.iti__selected-dial-code').text(),mobile);
            /*
		    if($('.iti__selected-dial-code').text()==='+961'){
			    if($('.phone-css').val().length>=8){
				    $('.phone-css').val($('.phone-css').val().substring(0, 8));
			    }
		    }
		    else if($('.iti__selected-dial-code').text()==='+971')
            {
                var mobile=$('.phone-css').val();
                ValidateIntMobile($('.iti__selected-dial-code').text(),mobile);
               /* var code=mobile.substring(0,2);
                alert(code.length);
                if(code!='50' && code!='52' && code!='54' && code!='55' && code!='56' && code!='58' )
                {

                    alert('error')
                }
                else{
                    alert('correct')
                }
                alert(mobile.charAt(0));
                */
                /*
                var mnumber= $('.phone-css').val();
                x=mnumber.substring(0, mnumber.length);
                if(jQuery.inArray("test", myarray) !== -1)
                if(x)
                if($('.phone-css').val().length==9)
                {

                }
                */

           // }
	    }

        $('body').on('click keydown','#country-listbox li', function(e){
            var mobile=$('.phone-css').val();
            ValidateIntMobile($('.iti__selected-dial-code').text(),mobile);

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

        	// flatpickr(".flatpickr");
	        // $(".flatpickr").flatpickr();
	        $(".flatpickr-today").flatpickr({
                maxDate: 'today'
            });

	        // $('.datepicker').datepicker({
            //     format: 'YY-mm-dd',
            // });
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
                 initialCountry: "<?=@strtolower($country_code)?>",
                // localizedCountries: { '<?=@strtolower($country_code)?>': '<?=@$country?>' },
                // nationalMode: false,
                // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                // placeholderNumberType: "MOBILE",
                preferredCountries: ['lb', 'sa','ae'],
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
        /*     if($flag=='pin'){
                echo "jQuery('#pin-modal').modal();";
            }
            else{
                echo "jQuery('#login-modal').modal();";
            } */
            @endphp

            if(sessionStorage && sessionStorage.getItem("page")!=null){
                jQuery('#'+sessionStorage.getItem("page")+'-modal').modal();
            }else{
	            jQuery('#login-modal').modal();
            }

	        if($('input[type="tel"]').length>0){
		        $('input[type="tel"]').focus();
	        }

	        $('#pin-modal').on('shown.bs.modal', function (e) {
		        if($('input.pincode-input-text.first').length>0){
			        $('input.pincode-input-text.first').focus();
		        }
	        });

	        $('#register-modal').on('shown.bs.modal', function (e) {
                $('#Register').find('input[type="text"]').filter(':first').focus();
	        });

	        function validateEmail(email) {
		        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	            return re.test(email);
            }

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
                var mobile=$('.phone-css').val();
                validated=ValidateIntMobile($('.iti__selected-dial-code').text(),mobile);
                if(!validated){
	                Swal.fire({
		                title: 'Warning!',
		                text: 'Some fields are invalid!',
		                icon: 'warning',
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
	                        // $('.pincode-input-container input.first').focus();
	                        $('#pin-modal').on('shown.bs.modal', function() {
		                        $('.pincode-input-container input.first').focus();
	                        })
                        }
                        else{
                            Swal.fire({
                                title: 'Warning!',
                                text: result.message,
                                icon: 'warning',
                                confirmButtonText: 'Close'
                            });
                            spinnerButtons('hide',  $(that));
                            return false;
                           // jQuery('#LoginMsg').html(result.message);
                        }
	                    if(sessionStorage) {
		                    sessionStorage.setItem("page", "pin");
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
	                            if(sessionStorage) {
		                            sessionStorage.setItem("page", "register");
	                            }
                            }
                            else{
	                            sessionStorage.setItem("page", "login");
                                location.replace('{{route('customer.index')}}'+'/'+result.type);
                            }
                        }
                        else{
	                        Swal.fire({
		                        title: 'Warning!',
		                        text: 'Pin Code is invalid!',
		                        icon: 'warning',
		                        confirmButtonText: 'Close'
	                        });
                            jQuery('#PinMsg').html(result.message);
                        }
	                    $('#Pinbtn').addClass('d-none');
                    }
                });
            });

            $('#Registerbtn').on('click', function(event){
            	spinnerButtons('show', $(this));
	            var validated = true;
                var dob=$('#R_dob{{$sKey}}').val();
                var firstn=$('#R_FirstName{{$sKey}}').val();
                var lastn=$('#R_FamilyName{{$sKey}}').val();
                var gender=$('#R_Gender{{$sKey}}').val();
	            if(dob=='')
	            {
		            jQuery('#R_dob').html('DOB is required');
		            validated = false;
	            }
	            else
	            {
		            jQuery('#R_dob').html(' ');
	            }
	            if( firstn==''){
		            jQuery('#R_FirstName').html('Name is required');
		            validated = false;
	            }
	            else
	            {
		            jQuery('#R_FirstName').html(' ');
	            }
	            if( lastn==''){
		            jQuery('#R_FamilyName').html('Family Name is required');
		            validated = false;
	            }
	            else
	            {
		            jQuery('#R_FamilyName').html(' ');
	            }
	            if( gender==''){
		            jQuery('#R_Gender').html('Gender is required');
		            validated = false;
	            }
	            else
	            {
		            jQuery('#R_Gender').html(' ');
	            }

	            if(!validated){
		            Swal.fire({
			            title: 'Warning!',
			            text: 'Some fields are required!',
			            icon: 'warning',
			            confirmButtonText: 'Close'
		            });
		            spinnerButtons('hide', $(this));
		            return null;
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
	                    spinnerButtons('hide', $(this));
                        if(result.message=='success')
                        {
                            // jQuery('#mobileNb{{$sKey}}').val(result.data['MobileNumber']);
                            // jQuery('#request_id{{$sKey}}').val(result.data['RequestId']);
                            // jQuery('#country_code{{$sKey}}').val(result.data['CountryCode']);
                           // jQuery('#login-modal').modal('hide');
                          //  jQuery('#pin-modal').modal();
	                        sessionStorage.setItem("page", "login");
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
	            if(sessionStorage) {
		            sessionStorage.setItem("page", "login");
	            }
            });

            $('#BackToPinBtn').on('click', function(event){
                event.preventDefault();
                jQuery('#register-modal').modal('hide');
                jQuery('#pin-modal').modal();
	            if(sessionStorage) {
		            sessionStorage.setItem("page", "pin");
	            }
            });
        });

    </script>
@endsection