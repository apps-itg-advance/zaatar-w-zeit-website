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
                            <h2 class="futura-medium">Add Address</h2>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="LoyaltyId" value="{{@$query->LoyaltyId}}">
                        <div class="col-md-12">
                            <label>Address Type</label>
                            <div class="row">
                                @foreach($addresses_types as $add_type)
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>{{$add_type->Title}}</label>
                                            <input type="radio" {{in_array($add_type->ID,$address_types)? 'disabled' :''}} name="address_type{{$skey}}" value="{{$add_type->ID}}" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address Name</label>
                                <input type="text" class="form-control" name="name{{$skey}}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City</label>
                                <select class="form-control" name="geo{{$skey}}">
                                    @if (count($cities)>0)
                                        @foreach($cities as $city)
                                            <option value="{{ $city->CityId.'-'.$city->ProvinceId }}">{{ $city->CityName.' - '.$city->ProvinceName }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Street</label>
                                <input type="text" class="form-control" name="line1{{$skey}}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Building Name</label>
                                <input type="text" class="form-control" name="building_name{{$skey}}" value="{{@$building_name}}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Building Number</label>
                                        <input type="text" class="form-control"  name="building_nbr{{$skey}}"  />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Floor</label>
                                        <input type="text" class="form-control" name="floor{{$skey}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control"  name="phone{{$skey}}" readonly value="{{$query->FullMobile}}" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Ext.</label>
                                        <input type="text" class="form-control" name="ext{{$skey}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <label>Pin Location</label>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d52992.273657867634!2d35.46926270113027!3d33.88921334637334!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151f17215880a78f%3A0x729182bae99836b4!2sBeirut%2C%20Lebanon!5e0!3m2!1sen!2sin!4v1578918907711!5m2!1sen!2sin" width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <label>More Details</label>
                                <textarea class="form-control" name="more_details{{$skey}}"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-8DBF43 mb-3 text-uppercase futura-book btn-confirm">Confirm</button>
                </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {

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

    });
</script>
