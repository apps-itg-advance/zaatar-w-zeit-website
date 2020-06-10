<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<form action="{{route('customer.address.save')}}" method="post" id="AddAddress">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
                @csrf
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body col-xl-10 float-none mx-auto pt-0">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="futura-medium">@lang('add_Address')</h2>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="LoyaltyId" value="{{@$query->LoyaltyId}}">
                        <div class="col-md-12">
                            <label>@lang('address_type')</label>
                            <div class="row">
                                @foreach($addresses_types as $add_type)
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="address_type{{$add_type->ID}}" onclick="ValidateAddressType({{$add_type->ID}})">{{$add_type->Title}}</label>
                                            <input data-code="{{$add_type->ID}}" data-id="{{$add_type->CategoryId}}" type="radio" class="address_type" id="address_type{{$add_type->ID}}" {{in_array($add_type->ID,$address_types)? 'disabled' :''}} name="address_type{{$skey}}" value="{{$add_type->ID}}" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('address_nickname')</label>
                                <input type="text" class="form-control" name="name{{$skey}}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('city')</label>
                                <select class="form-control" name="geo{{$skey}}">
                                    @if (count($cities)>0)
                                        @foreach($cities as $city)
                                            <option value="{{ $city->CityId.'-'.$city->ProvinceId }}">{{ $city->CityName}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('street')</label>
                                <input type="text" class="form-control" name="line1{{$skey}}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('building_name')</label>
                                <input type="text" class="form-control" name="building_name{{$skey}}" value="{{@$building_name}}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('building_number')</label>
                                        <input type="text" class="form-control"  name="building_nbr{{$skey}}"  />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('floor')</label>
                                        <input type="text" class="form-control" name="floor{{$skey}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label>@lang('phone_number')</label>
                                        <input type="text" class="form-control"  name="phone{{$skey}}" readonly value="{{$query->FullMobile}}" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('ext').</label>
                                        <input type="text" class="form-control" name="ext{{$skey}}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="company-input-container">
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" class="form-control"  name="company{{$skey}}" required />
                            </div>
                        </div>

                        <div class="form-group row d-none">
                            <input type="hidden" class="form-control" id="modal_latitude" value="" name="y_location{{$skey}}">
                            <input type="hidden" class="form-control" id="modal_longitude" value=""  name="x_location{{$skey}}">

                            <label class="form-control-label col-md-12">Latitude & Longitude</label>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input class="form-control" type="text" id="manual_latitude" value="" placeholder="Latitude">
                                    </div>
                                    <div class="col-md-5">
                                        <input class="form-control" type="text" id="manual_longitude" value="" placeholder="Longitude">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-sm btn-primary futura-book" onclick="currentLocation('')">@lang('my_location')</button>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-3"></label>
                                <div id="modal_map" class="col-xs-9" style="height:200px" data-latitudeid="modal_latitude" data-longitudeid="modal_longitude" data-latitude="" data-longitude=""></div>
                            </div>
                        </div>
                        <?php /*
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <label>More Details</label>
                                <textarea class="form-control" name="more_details{{$skey}}"></textarea>
                            </div>
                        </div>
 */?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-8DBF43 mb-3 text-uppercase futura-book btn-confirm">@lang('confirm')</button>
                </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {

	    if($(".address_type:checked").data('id') == '3'){
		    $('#company-input-container').removeClass('d-none');
		    $('#company-input-container').find('input').prop('disabled',false);
	    }else{
		    $('#company-input-container').find('input').prop('disabled',true);
		    $('#company-input-container').addClass('d-none');
	    }

	    $('.address_type').on('click', function(){
		    if($(this).data('id')=='3'){
			    $('#company-input-container').removeClass('d-none');
			    $('#company-input-container').find('input').prop('disabled',false);
		    }else{
			    $('#company-input-container').find('input').prop('disabled',true);
			    $('#company-input-container').addClass('d-none');
		    }
	    });

    	$('body').on('click', '.address_type', function(){
    		if($(this).data('id')=='3'){
    			$('#company-input-container').removeClass('d-none');
            }else{
			    $('#company-input-container').addClass('d-none');
            }
        });

            $('#AddAddress').validate({ // initialize the plugin
            rules: {
                address_type{{$skey}}: {
                    required: true
                },
                name{{$skey}}: {
                    required: true,
                    maxlength: 30
                },
                geo{{$skey}}: {
                    required: true
                },
                line1{{$skey}}: {
                    required: true,
                    maxlength: 30
                },
                building_name{{$skey}}: {
                    maxlength: 30
                },
                building_nbr{{$skey}}: {
                    maxlength: 30
                },
                floor{{$skey}}: {
                    maxlength: 30
                }
            },
            submitHandler: function (form) {
                form.submit();// for demo
                return false; // for demo
            }
        });

	    loadModalMap.init('');

    });

</script>
