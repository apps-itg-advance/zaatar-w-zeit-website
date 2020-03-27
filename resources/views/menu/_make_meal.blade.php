@php
    $make_meal=$row->MakeMeal;
@endphp
@if(is_object($make_meal))
<div class="cartbig-modal makeMeal-modal{{$row->ID}} modal fade" id="makeMeal-modal{{$row->ID}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                    <div class="col-lg-6 text-col pt-4">
                        <h4 class="futura-b">MAKE IT A MEAL ! </h4>
                        <div class="info">
                            {{$row->ItemName}}  {{$row->Price}}
                        </div>
                        <div class="items-row items-meal-row row align-items-center mt-4">
                            <div class="col-12 item-col">
                                <div class="custom-control custom-radio mb-3">
                                    <input type="checkbox"  value="{{$make_meal->ID.'-'.str_replace(',','',$make_meal->Price).'-'.$make_meal->Details.'-'.$make_meal->PLU}}"  onclick="CalculateMakeMealTotalQ({{$make_meal->ID}},{{$row->ID}})"  id="makeMealL{{$make_meal->ID}}" name="make_meal[{{$row->ID}}][Title]"  class="custom-control-input">

                                    <label class="custom-control-label text-uppercase" for="makeMealL{{$make_meal->ID}}">
                                        {{$make_meal->Details}}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                @php
                                    $meal_items=$make_meal->Items;
                                @endphp
                                @if(is_array($meal_items) and count($meal_items)>0)
                                @foreach($meal_items as $meal_item)
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value="{{$meal_item->ID.'-'.$meal_item->PLU.'-'.$make_meal->ID.'-'.$meal_item->Name}}" id="makeMealQ{{$meal_item->ID}}" name="make_meal[{{$row->ID}}][Items][{{$meal_item->ID}}]" class="custom-control-input Sub{{$make_meal->ID}}" disabled>
                                        <label class="custom-control-label list-i" for="makeMealQ{{$meal_item->ID}}">{{$meal_item->Name}}</label>
                                    </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer text-left justify-content-start p-0 mt-3 mt-lg-4">
                            <span class="title d-inline-block">Total</span>
                            <span class="amount d-inline-block mx-5"  id="DisplayTotalQ{{$row->ID}}">{{number_format($row->Price)}} {{$currency}}</span>
                            <input type="hidden" id="TotalAmountQ{{$row->ID}}" name="TotalAmountQ[{{$row->ID}}]" value="{{str_replace(',','',$row->Price)}}">
                            <a onclick="AddToCart({{$row->ID}},1)" class="btn btn-8DBF43 text-uppercase">Confirm</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endif
