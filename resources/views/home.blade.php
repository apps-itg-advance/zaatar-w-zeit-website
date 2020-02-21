@extends('layouts.template')

@section('content')

    <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-favourite-items">
        <div class="title-div mb-4 pb-2 text-center">
            <h2 class="title text-8DBF43">{{$cat_title}}</h2>
        </div>
        <div class="col-lg-11 float-none p-0 mx-auto">
            <div class="row row-favourite">
                @foreach($query->data as $row)
                <div class="col-xl-6 col-lg-12 mb-5 col-favourite">
                    <div class="favourite-box">
                        <div class="media">
                            <img src="{{$row->ThumbnailImg}}" class="mr-3" style="height: 159px !important;" alt="...">
                            <div class="align-self-center media-body">
                                <h5 class="mt-0">
                                    <a href="#">{{$row->ItemName}}</a>
                                    <span class="price">{{$row->Price}}</span>
                                </h5>
                                <div class="content">{{$row->Details}}</div>
                            </div>
                        </div>
                        <div class="mediabox row align-items-center">
                            <div class="col-sm-7 text-center">
                                <a href="#" class="link-favourite mr-3">Favourite</a>
                                <a onclick="OpenModel({{$row->ID}})" class="link-customize" style="cursor: pointer">Customize</a>
                            </div>
                            <div class="col-sm-5 text-center">
                                <div class="input-group mx-auto">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-link"><img src="{{asset('assets/images/icon-plus.png')}}" /></button>
                                    </div>
                                    <input type="text" class="form-control" value="0">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-link"><img src="{{asset('assets/images/icon-minus.png')}}" /></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cartbig-modal modal fade" id="cartbig-modal-{{$row->ID}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                                            <div class="custom-control custom-radio mb-1">
                                                <input type="checkbox" onclick="CalculateTotal({{$category_id}},{{$max_qty}},{{$m_item->ID}},{{$row->ID}})" id="Modifier{{$m_item->ID}}"  name="modifiers[{{$category_id}}]" value="{{$m_item->ID.'-'.$m_item->PLU.'-'.$m_item->Price}}" class="custom-control-input m-{{$category_id}}">
                                                <label class="custom-control-label" for="Modifier{{$m_item->ID}}">
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
                                    <span class="amount d-inline-block mx-5" id="DisplayTotal{{$row->ID}}">{{$row->Price}} LL</span>
                                    <input type="hidden" id="TotalAmount{{$row->ID}}" name="total_amount[]" value="{{$row->Price}}">
                                    <button class="btn btn-8DBF43 text-uppercase">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script src="{{asset('assets/js/jquery.matchHeight-min.js')}}"></script>
    <script type="text/javascript">

        function OpenModel(id) {
            jQuery('#cartbig-modal-'+id).modal();
            return false;
        }
        function CalculateTotal(cat_id,max_qty,id,item_id) {
            var CheckId='Modifier'+id;
            var GroupCss='m-'+cat_id;
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
                if($("#"+CheckId).is(':checked'))
                {
                    var nTotal=parseFloat($("#TotalAmount"+item_id).val())+parseFloat(res[2]);
                }
                else{
                    var nTotal=parseFloat($("#TotalAmount"+item_id).val())-parseFloat(res[2]);
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
@endsection