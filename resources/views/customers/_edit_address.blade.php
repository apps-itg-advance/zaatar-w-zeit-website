<form action="{{route('customer.address.update')}}" method="post">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{route('customer.store')}}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Edit Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body col-xl-10 float-none mx-auto">

                    <div class="row">
                        <input type="hidden" name="AddressId{{$skey}}" value="{{$address->ID}}">
                        <input type="hidden" name="LoyaltyId" value="{{@$details->LoyaltyId}}">
                        <input type="hidden" name="is_default{{$skey}}" value="1">
                        @php
                            $selectedCity='';

                                $main_address=array();


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



                        @endphp
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address Nickname</label>
                                <input type="text" class="form-control" name="Name{{$skey}}" value="{{@$address->Name}}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City</label>
                                <select class="form-control" name="geo{{$skey}}">
                                    @if (count($cities)>0)
                                        @foreach($cities as $city)
                                            <option value="{{ $city->CityId.'-'.$city->ProvinceId }}" {{ $selectedCity == $city->CityId ? 'selected="selected"' : '' }}>{{ $city->CityName.' - '.$city->ProvinceName }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Street</label>
                                <input type="text" class="form-control" name="line1{{$skey}}" value="{{@$address->Line1}}" />
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
                                        <input type="text" class="form-control"  name="building_nbr{{$skey}}" value="{{@$building_nbr}}"  />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Floor</label>
                                        <input type="text" class="form-control" name="floor{{$skey}}" value="{{@$floor}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control"  name="phone{{$skey}}" readonly="readonly"  value="{{@$details->FullMobile}}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Ext.</label>
                                        <input type="text" class="form-control" name="ext{{$skey}}" value="{{@$ext}}" />
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
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-8DBF43 mb-3 text-uppercase">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</form>