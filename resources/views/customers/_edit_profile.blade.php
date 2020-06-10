<div class="editprofileModal modal fade" id="editprofileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{route('customer.store')}}" method="post" id="ProfileForm">
                @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-xl-10 float-none mx-auto">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="futura-medium">@lang('edit_profile')</h2>
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" name="LoyaltyId" value="{{@$details->LoyaltyId}}">
                    <input type="hidden" name="is_default{{$Skey}}" value="1">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('name')</label>
                            <input type="text" class="form-control" maxlength="70" id="FirstName" name="first_name{{$Skey}}" value="{{@$details->FirstName}}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('family_name')</label>
                            <input type="text" class="form-control" maxlength="70" id="LastName" name="last_name{{$Skey}}" value="{{@$details->LastName}}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('phone_number')</label>
                            <input type="text" class="form-control" readonly="readonly" name="mobile{{$Skey}}" value="{{@$details->FullMobile}}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo app('translator')->get('email'); ?></label>
                            <input type="text" class="form-control" readonly="readonly" name="email{{$Skey}}" value="{{@$details->Email}}" />
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <h4 class="m-0">@lang('main_Address')</h4>
                        </div>
                    </div>
                    @php
                        $selectedCity='';

                            $main_address=array();
                                if(count($addresses)>0)
                                {
                                    foreach ($addresses as $address)
                                    {
                                        if($address->IsDefault=='1' or count($addresses)==1)
                                        {
                                         echo '<input type="hidden" name="address_id'.$Skey.'" value="'.$address->ID.'">';
                                            $main_address=$address;
                                            $selectedCity=$address->CityId;
                                            $array_line2=explode('Bldg',$address->Line2);
                                            $array_apartment=explode('Ext:',$address->AptNumber);
                                            if(count($array_line2)>0)
                                            {
                                                $building_name=@$array_line2[0];
                                                $building_nbr=@$array_line2[1];
                                            }
                                            if(count($array_apartment)>0)
                                            {
                                                $floor=@$array_apartment[0];
                                                $ext=@$array_apartment[1];
                                            }

                                        }
                                    }
                                }
                                if(!isset($main_address->ID))
                                {
                                    $main_address=isset($addresses[0]) ? $addresses[0]:array();
                                }
                    @endphp
                    <input type="hidden" name="address_type{{$Skey}}" value="{{(isset($main_address->TypeID) and $main_address->TypeID!='')? $main_address->TypeID : (isset($addresses_types[0]->ID) ? $addresses_types[0]->ID:0)}}" >
                    <?php /*
                    <div class="col-md-12">
                        <label>Address Type</label>
                        <div class="row">

                            @foreach($addresses_types as $add_type)
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="address_type{{$add_type->ID}}" onclick="ValidateAddressType({{$add_type->ID}})">{{$add_type->Title}}</label>
                                        <input data-code="{{$add_type->ID}}"  data-id="{{$add_type->CategoryId}}" type="radio" class="address_type" id="address_type{{$add_type->ID}}" {{(in_array($add_type->ID,$address_types) and (isset($main_address->TypeID) and $main_address->TypeID!=$add_type->ID))? 'disabled' :''}}  {{(isset($main_address->TypeID) and $main_address->TypeID==$add_type->ID )? 'checked' :''}} name="address_type{{$Skey}}" value="{{$add_type->ID}}" required />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
 */?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('address_nickname')</label>
                            <input type="text" class="form-control" name="name{{$Skey}}" value="{{@$main_address->Name}}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('city')</label>
                            <select class="form-control" id="Geo" name="geo{{$Skey}}">
                                @if (count($cities)>0)
                                    @foreach($cities as $city)
                                        <option value="{{ $city->CityId.'-'.$city->ProvinceId }}" {{ $selectedCity == $city->CityId ? 'selected="selected"' : '' }}>{{ $city->CityName}}</option>
                                    @endforeach
                                 @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('street')</label>
                            <input type="text" class="form-control" id="Line1" name="line1{{$Skey}}" value="{{@$main_address->Line1}}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('building_name')</label>
                            <input type="text" class="form-control" name="building_name{{$Skey}}" value="{{@$building_name}}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('building') #</label>
                                    <input type="text" class="form-control"  name="building_nbr{{$Skey}}" value="{{@$building_nbr}}"  />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('floor')</label>
                                    <input type="text" class="form-control" name="floor{{$Skey}}" value="{{@$floor}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('phone_number')</label>
                            <input type="text" class="form-control"  name="phone{{$Skey}}" readonly="readonly"  value="{{@$details->FullMobile}}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>@lang('ext').</label>
                                    <input type="text" class="form-control" name="ext{{$Skey}}" value="{{@$ext}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" id="company-input-container">
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" class="form-control"  name="company{{$Skey}}" value="{{@$main_address->CompanyName}}" required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-0">

                            <div class="form-group row d-none">
                                <input type="hidden" class="form-control" id="modal_latitude0" name="y_location{{$Skey}}" value="{{isset($main_address->YLocation)?$main_address->YLocation:''}}">
                                <input type="hidden" class="form-control" id="modal_longitude0" name="x_location{{$Skey}}" value="{{isset($main_address->XLocation)?$main_address->XLocation:''}}">

                                <label class="form-control-label col-md-12">Latitude & Longitude</label>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input class="form-control" type="text" id="manual_latitude0" placeholder="Latitude" value="{{isset($main_address->YLocation)?$main_address->YLocation:''}}" >
                                        </div>
                                        <div class="col-md-5">
                                            <input class="form-control" type="text" id="manual_longitude0" placeholder="Longitude" value="{{isset($main_address->XLocation)?$main_address->XLocation:''}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-sm btn-primary futura-book" onclick="currentLocation(0)">@lang('my_location')</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3"></label>
                                <div id="modal_map0" class="col-xs-9" style="height:200px" data-latitudeid="modal_latitude0" data-longitudeid="modal_longitude0" data-latitude="{{isset($main_address->YLocation)?$main_address->YLocation:''}}" data-longitude="{{isset($main_address->XLocation)?$main_address->XLocation:''}}"></div>
                            </div>


                            {{--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d52992.273657867634!2d35.46926270113027!3d33.88921334637334!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151f17215880a78f%3A0x729182bae99836b4!2sBeirut%2C%20Lebanon!5e0!3m2!1sen!2sin!4v1578918907711!5m2!1sen!2sin" width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen=""></iframe>--}}
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-8DBF43 mb-3 text-uppercase">@lang('confirm')</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        if($(".address_type:checked").data('id')=='3'){

            $('#company-input-container').removeClass('d-none');
            $('#company-input-container').find('input').prop('disabled',false);
        }else{
            $('#company-input-container').find('input').prop('disabled',true);
            $('#company-input-container').addClass('d-none');
        }

        $('body').on('click','.address_type', function(){
            if($(this).data('id')=='3'){
                $('#company-input-container').removeClass('d-none');
                $('#company-input-container').find('input').prop('disabled',false);
            }else{
                $('#company-input-container').find('input').prop('disabled',true);
                $('#company-input-container').addClass('d-none');
            }
        });


        $("#ProfileForm").submit(function(e) {
		    e.preventDefault();
	    }).validate({
		    rules: {
			    first_name{{$Skey}}: {
				    required: true,
                    maxlength: 70
			    },
			    last_name{{$Skey}}: {
				    required: true,
				    maxlength: 70
			    },
                address_type{{$Skey}}: {
                    required: true
                },
                name{{$Skey}}: {
                    required: true,
                    maxlength: 30
                },
                geo{{$Skey}}: {
                    required: true
                },
			    line1{{$Skey}}: {
				    required: true,
				    maxlength: 30
			    },
			    building_name{{$Skey}}: {
				    maxlength: 30
			    },
			    building_nbr{{$Skey}}: {
				    maxlength: 30
			    },
			    floor{{$Skey}}: {
				    maxlength: 30
			    },

		    },
		    submitHandler: function (form) {
			    form.submit();// for demo
			    return false; // for demo
		    }
	    });
        loadModalMap.init(0);

    });

    // function initService() {
	//     var displaySuggestions = function(predictions, status) {
	// 	    if (status != google.maps.places.PlacesServiceStatus.OK) {
	// 		    alert(status);
	// 		    return;
	// 	    }
    //
	// 	    predictions.forEach(function(prediction) {
	// 		    var li = document.createElement('li');
	// 		    li.appendChild(document.createTextNode(prediction.description));
	// 		    document.getElementById('results').appendChild(li);
	// 	    });
	//     };
	//     var value = $('#pac-input').val();
	//     // var value = document.getElementById('pac-input').value;
	//     var service = new google.maps.places.AutocompleteService();
	//     service.getQueryPredictions({ input: value }, displaySuggestions);
    // }

</script>
