@php
    dump(session()->all());
    $_address=array($cart_info->City,$cart_info->Line1,$cart_info->Line2,$cart_info->Apartment);
    $_total=0;

@endphp
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header col-xl-12 float-none mx-auto">
            <h5 class="modal-title" id="exampleModalCenterTitle">Order Summary</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body py-0 col-xl-12 orders-wrapper float-none mx-auto">
            <div class="order-box">
                <div class="order-info pt-2 pt-md-4">
                    <div class="row">
                        <div class="col-sm-3 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Address
                        </div>
                        <div class="col-sm-9 text-808080 mb-3">
                            {{implode(', ',$_address)}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Order
                        </div>
                        <div class="col-sm-9">
                            @foreach($cart as $key=>$values)
                            <div class="mb-3">
                                <h5 class="mb-0">{{$values['name']}} <span class="d-inline-block ml-3">{{$values['price']}}</span></h5>
                                <div class="text-808080">
                                    @php
                                        $modifiers=$values['modifiers'];
                                        $_total+=$values['price']*$values['quantity'];
                                        $md_array=array();
                                        for($i=0;$i<count($modifiers);$i++)
                                        {
                                            array_push($md_array,$modifiers[$i]['name']);
                                        }
                                    @endphp
                                    {{implode(', ',$md_array )}}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="total-block text-right">
                    Total <span class="price d-inline-block ml-4">{{$_total}}</span>
                </div>
                <hr/>
                <div class="delivery-block text-right mb-4">
                    Delivery fee <span class="price d-inline-block ml-4">{{$delivery_charge}} {{$currency}}</span>
                </div>
                <div class="order-info">
                    <div class="row align-items-center">
                        <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Wallet
                        </div>
                        <div class="col-8 text-808080 mb-3">
                            10% discount
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Gift
                        </div>
                        <div class="col-8 text-808080 mb-3">
                            Yes
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Go Green
                        </div>
                        <div class="col-8 text-808080 mb-3">
                            Yes
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-4 text-left text-sm-right text-label text-uppercase text-666666 mb-3">
                            Method
                        </div>
                        <div class="col-8 text-808080 mb-3">
                            Online Payment
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-B3B3B3 mb-3 mr-sm-4 text-uppercase">Close</button>
            <button type="button" class="btn btn-8DBF43 mb-3 text-uppercase">Confirm</button>
        </div>
    </div>
</div>
