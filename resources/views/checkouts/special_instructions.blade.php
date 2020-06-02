@extends('layouts.template')
@section('content')
    @php

        $query=$delivery_info->SpecialInstructions;
    @endphp
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 float-none p-0 mx-auto">

    <div class="title-div text-center mb-4">
        <h2 class="title">@lang('checkout')</h2>
    </div>
    @include('partials.checkout_bread')
        <div class="col-xl-6 col-lg-10 float-none p-0 mx-auto item-summary">
            <form id="FormSp" method="post">
            <div class="title-div mb-5">
                <h2 class="title ml-0">@lang('special_instructions')</h2>
            </div>
            <div class="radios-green mb-5">
                @foreach($query as $row)
                    @php
                        $check='';
                        if(isset($cart_sp_instructions) and count((array)$cart_sp_instructions)>0)
                        {
                        foreach($cart_sp_instructions as $csp)
                        {
                         if(isset($csp['ID']) and $csp['ID']==$row->ID)
                            {
                                $check='checked="checked"';
                                break;
                            }
                        }

                        }

                    @endphp
                <div class="custom-control custom-radio mb-4">
                    <input type="checkbox" id="instructions{{$row->ID}}" {{$check}} name="sp_i[]" value="{{json_encode($row)}}" class="custom-control-input sp_inst">
                    <label class="custom-control-label text-uppercase" for="instructions{{$row->ID}}">
                        {{$row->Title}}
                    </label>
                </div>
                @endforeach
            </div>
            <div class="action-buttons text-center">
                <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm">@lang('confirm')</button>
                @if(isset($settings->Required) and !$settings->Required)
                    <button type="button" class="btn btn-B3B3B3 text-uppercase skip" onclick="SkipBtn('special_inst')">@lang('skip')</button>
                @endif
            </div>
            </form>
        </div>
    </div>


@endsection
@section('javascript')
    <script>
        $(".confirm").click(function(){
	        spinnerButtons('show', $(this));
            var that = this;
            var radioValue = $("input[name='sp_i[]']:checked").val();
            if(!radioValue || radioValue==undefined){
                Swal.fire({
                    title: 'Warning!',
                    text: 'You must choose an option!',
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
                type: 'POST',
                data: $("#FormSp").serialize(),
                url: '{{route('checkout.special.instructions.store')}}',
                success: function (data) {
                    window.location = '{{route('checkout.payment')}}';
                }
            });

        });
    </script>

@endsection
