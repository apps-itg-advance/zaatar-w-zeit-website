@extends('layouts.template')
@section('css')
<link rel="stylesheet" href="{{asset('assets/phone-input/css/intlTelInput.css')}}">
<style type="text/css">
    .phone-css{
        padding-left: 75px !important;
    }
</style>
@endsection
@section('content')
    <form id="LogIn" action="#">
    <div class="login-modal modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                        <input type="text" class="form-control" name="pin{{$sKey}}" />
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
@endsection
@section('javascript')
    <script src="{{asset('assets/js/jquery.matchHeight-min.js')}}"></script>
    <script src="{{asset('assets/phone-input/js/intlTelInput.js')}}"></script>
    <script type="text/javascript">

        $('body').on('click keydown','#country-listbox li', function(e){
            if ( e.which == 13 || e.which == 1 ) {
                var code = $(this).find('.iti__dial-code').text();
                $('input[type="hidden"][name="country_code{{$sKey}}"]').val(code);
            }
        });



        jQuery(document).ready( function() {
            $('body').on('load','#country-listbox li', function(e){
                var code = $(this).find('.iti__dial-code').text();
                $('input[type="hidden"][name="country_code{{$sKey}}"]').val(code);
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
                hiddenInput: "full_number{{$sKey}}",
                // initialCountry: "auto",
                // localizedCountries: { 'de': 'Deutschland' },
                // nationalMode: false,
                // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                // placeholderNumberType: "MOBILE",
                preferredCountries: ['lb', 'sa'],
                separateDialCode: true,
                utilsScript: "{{asset('assets/phone-input/js/utils.js')}}",
            });
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

             if($flag=='pin'){
                echo "jQuery('#pin-modal').modal();";
            }
            else{
                echo "jQuery('#login-modal').modal();";
            }
            @endphp
           // jQuery('#login-modal').modal();
            $('#Loginbtn').on('click', function(event){
                event.preventDefault();
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
                           // jQuery('#country_code{{$sKey}}').val(result.data['CountryCode']);
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
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'POST',
                    url:'{{route('auth.pin')}}',
                    data:$("#PinForm").serialize(),
                    dataType:'json',
                    success:function(data){
                        if(data.message=='success')
                        {
                            location.replace('{{route('customer.index')}}'+'/'+data.type);
                        }
                        else{

                            jQuery('#PinMsg').html(data.message);
                        }
                    }
                });
            });
            $('#Resendbtn').on('click', function(event){
                event.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'POST',
                    url:'{{route('auth.pinresend')}}',
                    dataType:'json',
                    success:function(data){
                        jQuery('#PinMsg').html(data.message);
                    }
                });
            });

            $('#Backbtn').on('click', function(event){
                event.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'POST',
                    url:'{{route('auth.pin')}}',
                    data:$("#PinForm").serialize(),
                    dataType:'json',
                    success:function(data){
                        if(data.status=='success')
                        {
                            location.replace('{{route('customer.index')}}'+'/'+data.type);
                        }
                        else{

                            jQuery('#PinMsg').html(data.message);
                        }
                    }
                });
            });
        });


    </script>
@endsection