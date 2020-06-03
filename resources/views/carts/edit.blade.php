<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form action="{{route('carts.update')}}" method="post" >
        {{csrf_field()}}
        <input type="hidden" id="qty" name="qty" value="{{$item['quantity']}}">
        <input type="hidden" id="item_id" name="item_id" value="{{$row->ID}}">
        <input type="hidden" id="plu" name="plu" value="{{$row->PLU}}">
        <input type="hidden" id="item_name" name="item_name" value="{{$row->ItemName}}">
        <input type="hidden" id="amount" name="amount" valnav main-nav justify-content-centerue="{{$row->Price}}">
        <input type="hidden" id="key" name="key" value="{{$key}}">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" onclick="CloseModel()" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="row">
                <div class="col-lg-6 image-col">
                    <img src="{{$row->DetailsImg}}" class="img-fluid d-block mx-auto" />
                </div>
                <div class="col-lg-6 text-col py-4">
                    <h5>{{htmlspecialchars_decode($row->ItemName)}}<span>{{number_format($row->Price)}}</span></h5>
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
                                            if($m['plu']==$m_item->PLU)
                                            {
                                                $checked='checked="checked"';
                                                break;
                                            }
                                        }
                                    }

                            @endphp
                            <div class="custom-control custom-radio mb-1">
                                <input type="checkbox" {{ $checked }} onclick="CalculateTotal({{$category_id}},{{$max_qty}},{{$m_item->RowId}},{{$row->ID}})" id="ModifierE{{$m_item->RowId}}"  name="modifiers[{{$category_id}}][]" value="{{$m_item->ID.'-'.$m_item->PLU.'-'.$m_item->Price.'-'.$category_name.' '.$m_item->ModifierName}}" class="custom-control-input m-{{$category_id}}-{{$row->ID}}">
                                <label class="custom-control-label" for="ModifierE{{$m_item->RowId}}">
                                    {{$m_item->ModifierName}}
                                    <span class="price">{{$m_item->Price>0 ?  number_format($m_item->Price):''}}</span>
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
                @php
                    $_meal=isset($item['meal']) ? $item['meal'] :array();
                       $h_checked='';
                           $meal_items=$make_meal->Items;
                           if(isset($_meal['id']) and $_meal['id']==$make_meal->ID)
                           {
                               $h_checked='checked="checked"';
                           }

                @endphp
                <div class="items-row items-meal-row row align-items-center mt-3">
                    <div class="col-lg-4 col-md-12 item-col">
                        <div class="custom-control custom-radio mb-1">
                            <input type="checkbox" {{$h_checked}} value="{{$make_meal->ID.'-'.$make_meal->Price.'-'.$make_meal->Details}}"  onclick="CalculateMakeMealTotalE({{$make_meal->ID}},{{$row->ID}})"  id="makeMealE{{$make_meal->ID}}" name="make_meal[{{$row->ID}}][Title]"  class="custom-control-input">
                            <label class="custom-control-label text-uppercase" for="makeMealE{{$make_meal->ID}}">
                                @lang('make_a_meal')
                                <span class="price">{{$make_meal->Price}}</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 py-3 text-left text-lg-center text-808080">
                        {{$make_meal->Details}}
                    </div>
                    <div class="col-lg-5 col-md-12">
                        @if(is_array($meal_items) and count($meal_items)>0)
                        @foreach($meal_items as $meal_item)
                            @php
                                $b_checked='';
                                    $_m_meal=isset($_meal['items']) ? $_meal['items']:array();
                                    foreach($_m_meal as $ml)
                                    {
                                         if(isset($ml['plu']) and $ml['plu']==$meal_item->PLU)
                                        {
                                            $b_checked='checked="checked"';
                                        }
                                    }


                            @endphp
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" {{$b_checked}} value="{{$meal_item->ID.'-'.$meal_item->PLU.'-'.$make_meal->ID.'-'.$meal_item->Name}}" id="makeMeal_{{$meal_item->ID}}" name="make_meal[{{$row->ID}}][Items][{{$make_meal->ID}}]" class="custom-control-input mealItem">
                                <label class="custom-control-label" for="makeMeal_{{$meal_item->ID}}">{{$meal_item->Name}}</label>
                            </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="modal-footer pt-0">
            <span class="title d-inline-block">@lang('total')</span>
<<<<<<< Updated upstream
            <span class="amount d-inline-block mx-5" id="DisplayTotalE{{$row->ID}}">{{$item['price']}} {{$currency}}</span>
=======
            <span class="amount d-inline-block mx-5" id="DisplayTotalE{{$row->ID}}">{{number_format($item['price'])}} {{$currency}}</span>
>>>>>>> Stashed changes
            <input type="hidden" id="TotalAmountE{{$row->ID}}" name="TotalAmount" value="{{$item['price']}}">
            <button type="submit" class="btn btn-8DBF43 text-uppercase">@lang('confirm')</button>
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
        var CheckId='ModifierE'+id;
        var GroupCss='m-'+cat_id+'-'+item_id;
        var GCount=parseInt($('.'+GroupCss+':checked').length);
        if(max_qty >0 && GCount>max_qty)
        {
            Swal.fire({
                // position: 'top-end',
                icon: 'warning',
                title: '<?php echo app('translator')->get('you_cant_select_more_than'); ?>'+max_qty+' <?php echo app('translator')->get('option'); ?>',
                showConfirmButton: false,
                timer: 1200
            });
            $("#"+CheckId).prop('checked', false);
            return false;
        }
        else{
            var mVal=$("#"+CheckId).val();
            var res = mVal.split("-");
            var mPrice=parseFloat(res[2])*currentQty;

            if($("#"+CheckId).is(':checked'))
            {

                var nTotal=parseFloat($("#TotalAmountE"+item_id).val())+mPrice;
            }
            else{
                var nTotal=parseFloat($("#TotalAmountE"+item_id).val())-mPrice;

            }
            $("#TotalAmountE"+item_id).val(nTotal);
            $("#DisplayTotalE"+item_id).text(nTotal+' LBP');
        }
    }
    function CalculateMakeMealTotalE(id,item_id) {
        var CheckId='makeMealE'+id;

        var mVal=$("#"+CheckId).val();
        var res = mVal.split("-");
        if($("#"+CheckId).is(':checked'))
        {
            var nTotal=parseFloat($("#TotalAmountE"+item_id).val())+parseFloat(res[1]);
        }
        else{
            $(".mealItem").prop('checked', false);
            var nTotal=parseFloat($("#TotalAmountE"+item_id).val())-parseFloat(res[1]);
        }

        $("#TotalAmountE"+item_id).val(nTotal);
        $("#DisplayTotalE"+item_id).text(nTotal+' {{$currency}}');
    }
</script>
