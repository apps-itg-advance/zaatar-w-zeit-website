<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form action="{{route('carts.update')}}" method="post" >
        {{csrf_field()}}
        <input type="hidden" id="qty" name="qty" value="{{$item['quantity']}}">
        <input type="hidden" id="item_id" name="item_id" value="{{$row->ID}}">
        <input type="hidden" id="plu" name="plu" value="{{$row->PLU}}">
        <input type="hidden" id="item_name" name="item_name" value="{{$row->ItemName}}">
        <input type="hidden" id="amount" name="amount" value="{{$row->Price}}">
        <input type="hidden" id="key" name="key" value="{{$key}}">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="row align-items-center">
                <div class="col-lg-6 image-col">
                    <img src="{{$row->DetailsImg}}" class="img-fluid d-block mx-auto" />
                </div>
                <div class="col-lg-6 text-col py-4">
                    <h5>{{$row->ItemName}}<span>{{$row->Price}}</span></h5>
                    <div class="info">{{$row->Details}}</div>
                </div>
            </div>
            @php
                $modifiers=$row->Modifiers;
            @endphp

            <div class="items-row row mt-4">
                @foreach($modifiers as $modifier)
                    <div class="col-lg-4 col-md-6 item-col"  data-mh="matchHeight">
                        @php
                            $m_details=$modifier->details;
                            $category_name=$m_details->CategoryName;
                            $category_id=$m_details->ID;
                            $modifier_items=$m_details->items;
                            $max_qty=$m_details->MaxQuantity;
                        @endphp
                        <h5 class="text-uppercase text-center mb-3">{{$category_name}}</h5>
                        @foreach($modifier_items as $m_item)
                            @php
                                $checked='';
                                    $modifiers_select=$item['modifiers'];
                                    if(count($modifiers_select)>0)
                                    {
                                      foreach ($modifiers_select as $m)
                                        {
                                            if($m['id']==$m_item->PLU)
                                            {
                                                $checked='checked="checked"';
                                                break;
                                            }
                                        }
                                    }

                            @endphp
                            <div class="custom-control custom-radio mb-1">
                                <input type="checkbox" {{ $checked }} onclick="CalculateTotal({{$category_id}},{{$max_qty}},{{$m_item->RowId}},{{$row->ID}})" id="Modifier{{$m_item->RowId}}"  name="modifiers[{{$category_id}}][]" value="{{$m_item->ID.'-'.$m_item->PLU.'-'.$m_item->Price.'-'.$category_name.' '.$m_item->ModifierName}}" class="custom-control-input m-{{$category_id}}-{{$row->ID}}">
                                <label class="custom-control-label" for="Modifier{{$m_item->RowId}}">
                                    {{$m_item->ModifierName}}
                                    <span class="price">{{$m_item->Price}}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            @php
                $make_meal=$row->MakeMeal;
            @endphp
            @if(is_object($make_meal))
                <div class="items-row items-meal-row row align-items-center mt-3">
                    <div class="col-lg-4 col-md-12 item-col">
                        <div class="custom-control custom-radio mb-1">
                            <input type="checkbox"  value="{{$make_meal->ID.'-'.$make_meal->Price}}"  onclick="CalculateMakeMealTotal({{$make_meal->ID}},{{$row->ID}})"  id="makeMealH{{$make_meal->ID}}" name="make_meal[{{$make_meal->ID}}]"  name="customRadio" class="custom-control-input">
                            <label class="custom-control-label text-uppercase" for="makeMealH{{$make_meal->ID}}">
                                {{$make_meal->Title}}
                                <span class="price">{{$make_meal->Price}}</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 py-3 text-left text-lg-center text-808080">
                        {{$make_meal->Details}}
                    </div>
                    <div class="col-lg-5 col-md-12">
                        @php
                            $meal_items=$make_meal->Items;
                        @endphp
                        @foreach($meal_items as $meal_item)
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="checkbox" value="{{$meal_item->ID.'-'.$meal_item->PLU}}" id="makeMeal{{$meal_item->ID}}" name="make_meal[{{$meal_item->ID}}]" class="custom-control-input">
                                <label class="custom-control-label" for="makeMeal{{$meal_item->ID}}">{{$meal_item->Name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div class="modal-footer pt-0">
            <span class="title d-inline-block">Total</span>
            <span class="amount d-inline-block mx-5" id="DisplayTotal{{$row->ID}}">{{$item['price']*$item['quantity']}} {{$currency}}</span>
            <input type="hidden" id="TotalAmount{{$row->ID}}" name="TotalAmount" value="{{$item['price']*$item['quantity']}}">
            <button type="submit" class="btn btn-8DBF43 text-uppercase">Confirm</button>
        </div>
    </div>
    </form>
</div>
<script>
    function CalculateTotal(cat_id,max_qty,id,item_id) {
        var ItemId="qty";
        var currentQty=parseInt($("#"+ItemId).val());
        if(currentQty==0)
        {
            currentQty=1;
        }
        var CheckId='Modifier'+id;
        var GroupCss='m-'+cat_id+'-'+item_id;
        var GCount=parseInt($('.'+GroupCss+':checked').length);
        if(max_qty >0 && GCount>max_qty)
        {
            alert('Max Qty :'+max_qty);
            $("#"+CheckId).prop('checked', false);
            return false;
        }
        else{
            var mVal=$("#"+CheckId).val();
            var res = mVal.split("-");
            var mPrice=parseFloat(res[2])*currentQty;
            if(res[2])
            {

            }
            alert(currentQty);
            if($("#"+CheckId).is(':checked'))
            {

                var nTotal=parseFloat($("#TotalAmount"+item_id).val())+mPrice;
            }
            else{
                var nTotal=parseFloat($("#TotalAmount"+item_id).val())-mPrice;
            }
            $("#TotalAmount"+item_id).val(nTotal);
            $("#DisplayTotal"+item_id).text(nTotal+' LBP');
        }
    }
    function CalculateMakeMealTotal(id,item_id) {
        var CheckId='makeMealH'+id;

        var mVal=$("#"+CheckId).val();
        var res = mVal.split("-");
        if($("#"+CheckId).is(':checked'))
        {
            var nTotal=parseFloat($("#TotalAmount"+item_id).val())+parseFloat(res[1]);
        }
        else{
            var nTotal=parseFloat($("#TotalAmount"+item_id).val())-parseFloat(res[1]);
        }

        $("#TotalAmount"+item_id).val(nTotal);
        $("#DisplayTotal"+item_id).text(nTotal+' LBP');
    }
</script>
