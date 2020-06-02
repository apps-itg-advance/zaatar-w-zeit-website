<div class="cartbig-modal modal fade" id="cartbig-modal-{{$row->ID}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="row">
                    <div class="col-lg-6 image-col">
                        <img src="{{asset(isset($row->LocalThumbnailImg) ? $row->LocalThumbnailImg : $row->ThumbnailImg)}}" class="img-fluid d-block mx-auto" />
                    </div>
                    <div class="col-lg-6 text-col py-4">

                        <h5><span style="float: none !important;" id="ItemNameDetails{{$row->ID}}">{{$row->item_name}}</span><span>{{number_format($row->Price)}}</span></h5>
                        <div class="info">{{substr(htmlspecialchars_decode($row->Details),0,250)}}</div>
                    </div>
                </div>
                @php
                        $display_fv=isset($display_favourite) ? $display_favourite:false;
                        $modifiers=$row->Modifiers;

                @endphp

                <div class="items-row row mt-4">
                    @foreach($modifiers as $modifier)
                        <div class="col-lg-4 col-md-6 item-col"  data-mh="matchHeight">
                            @php
                                $fv_selected='';
                                $m_details=$modifier->details;
                                $category_name=$m_details->CategoryName;
                                $category_id=$m_details->ID;
                                $modifier_items=$m_details->items;
                                $max_qty=$m_details->MaxQuantity;

                            @endphp
                            <h5 class="text-uppercase text-center mb-3">{{$category_name}}</h5>
                            @foreach($modifier_items as $m_item)
                                <div class="custom-control custom-radio mb-1">
                                    <input type="checkbox" {{(isset($row->fav_selected_array[$m_item->ID]))? $row->fav_selected_array[$m_item->ID]:''}} onclick="CalculateTotal({{$category_id}},{{$max_qty}},{{$m_item->RowId}},{{$row->ID}})" id="Modifier{{$m_item->RowId}}"  name="modifiers{{$row->ID}}[{{$category_id}}][]" value="{{$m_item->ID.'-'.$m_item->PLU.'-'.str_replace(',','',$m_item->Price).'-'.$category_name.' '.$m_item->ModifierName}}" class="custom-control-input m-{{$category_id}}-{{$row->ID}} Item{{$row->ID}}">
                                    <label  class="custom-control-label" for="Modifier{{$m_item->RowId}}" style="vertical-align: bottom;">
                                        <div style="float: left; max-width: 75%; overflow: hidden;">{{$m_item->ModifierName}}</div>
                                        <span class="price" style="vertical-align: bottom; display: inline-block; height: 100%">{{$m_item->Price>0 ?  number_format($m_item->Price):''}}</span>
                                    </label>
                                </div>
                                <div class="clearfix"></div>

                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div class="items-row items-favourite row align-items-center mt-3">
                    <div class="col-lg-12 col-md-12 item-col" style="margin-left: -10px !important;">
                        <h5 class="favourite-title futura-b">@lang('want_to_personalize?')</h5>
                        <div class="col-lg-12 col-md-12"  style="margin-left: -10px !important;">
                            @php
                                $txtF=app('translator')->get('favourite_customizes_item_name');
                                $active_f='';
                            if($row->IsFavorite=='1')
                            {
                            $active_f='link-favourite-u active';
                            }
                            @endphp
                                @if(session('is_login'))
                                    <a onclick="SetFavourite({{$row->ID}},1)" id="CFavourite{{$row->ID}}" href="javascript:void(0)" class="effect-underline link-favourite-u mr-3 {{$active_f}}"></a>
                                @else
                                    <a onclick="loginAlert()" class="effect-underline link-favourite-u mr-3 cursor-pointer"></a>
                                @endif
                            @php echo $txtF @endphp
                            <input type="text" name="favourite_name" id="favourite_name{{$row->ID}}" placeholder=<?= app('translator')->get('ex_yara_s_famous'); ?> class="txt-favourite" value="{{$row->fav_name}}">
                        </div>
                    </div>
                </div>
                @php
                    $make_meal=$row->MakeMeal;
                @endphp
                @if(is_object($make_meal))

                    <div class="items-row items-meal-row row align-items-center mt-3">
                        <div class="col-lg-4 col-md-12 item-col">
                            <div class="custom-control custom-radio mb-1">
                                <input type="checkbox"  value="{{$make_meal->ID.'-'.str_replace(',','',$make_meal->Price).'-'.$make_meal->Details.'-'.$make_meal->PLU}}"  onclick="CalculateMakeMealTotal({{$make_meal->ID}},{{$row->ID}})"  id="makeMealH{{$make_meal->ID}}" name="make_meal_d[{{$row->ID}}][Title]" class="custom-control-input">
                                <label class="custom-control-label text-uppercase futura-b" for="makeMealH{{$make_meal->ID}}">
                                    {{$make_meal->Title}}
                                    <span class="price">{{number_format($make_meal->Price)}}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 py-3 text-left text-lg-center text-808080 futura-medium">
                            {{$make_meal->Details}}
                        </div>
                        <div class="col-lg-5 col-md-12">
                            @php
                                $meal_items=$make_meal->Items;
                            @endphp
                            @if(is_array($meal_items) and count($meal_items)>0)
                            @foreach($meal_items as $meal_item)
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" value="{{$meal_item->ID.'-'.$meal_item->PLU.'-'.$make_meal->ID.'-'.$meal_item->Name}}" id="makeMeal{{$meal_item->ID.'-'.$make_meal->ID}}" name="make_meal_d[{{$row->ID}}][Items][{{$make_meal->ID}}]" class="custom-control-input Subd{{$make_meal->ID}}" disabled>
                                    <label class="custom-control-label list-i" for="makeMeal{{$meal_item->ID.'-'.$make_meal->ID}}">{{$meal_item->Name}}</label>
                                </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer pt-0">
                <span class="title d-inline-block">@lang('total')</span>
                <span class="amount d-inline-block mx-5" id="DisplayTotal{{$row->ID}}">{{number_format($row->Price)}} {{$currency}}</span>
                <input type="hidden" id="TotalAmount{{$row->ID}}" name="TotalAmount[{{$row->ID}}]" value="{{str_replace(',','',$row->Price)}}">
                <a class="btn btn-8DBF43 text-uppercase btn-a" onclick="AddToCart({{$row->ID}},0)">@lang('confirm')</a>
            </div>
        </div>
    </div>
</div>
