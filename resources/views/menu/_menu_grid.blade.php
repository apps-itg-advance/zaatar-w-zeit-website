@php
    $icons=(isset($query->icons) and count((array)$query->icons)>0) ? $query->icons:array() ;
    $array_icon=array();
    foreach ($icons as $icon)
    {
        if(isset($icon->IconName))
        {
            $array_icon[$icon->IconName]=array('URL'=>$icon->IconUrl,'Label'=>$icon->IconLabel);
        }

    }
@endphp
<div class="row-favourite mx-auto">
    <input type="hidden" name="SItemId" id="SItemId">
    <input type="hidden" name="SQty" id="SQty">
    <input type="hidden" name="ItemModify" id="ItemModify" value="0">
    @foreach($query->data as $row)
        <form id="Form{{$row->ID}}" action="#" method="post">
            {{csrf_field()}}
            @php
                $has_meal=is_object($row->MakeMeal)  ? 1 :0;
            $has_modifier=(is_array($row->Modifiers) and count($row->Modifiers)>0)  ? 1 :0;
                         $fav_name='';
                        $fv_array=array();
                        $fav_selected_array=array();

                        if($row->IsFavorite=='1'){
                           if(isset($row->FavoriteData) and $row->FavoriteData!='')
                           {
                                $fv_array=json_decode($row->FavoriteData);

                                if(is_object($fv_array))
                                {
                                $fav_name=isset($fv_array->customName)? $fv_array->customName:'';
                                if(isset($fv_array->Modifiers))
                                {
                                    foreach ($fv_array->Modifiers as $fv_row)
                                        {
                                            if(isset($fv_row->details->items))
                                            {
                                                $fv_items=$fv_row->details->items;
                                                foreach ($fv_items as $fv_item)
                                                {
                                                if(isset($fv_item->isSelected) and $fv_item->isSelected==true)
                                                {
                                                $fav_selected_array[$fv_item->ID]='checked="checked"';
                                                }

                                                }
                                            }
                                        }
                                }

                                }
                           }
                        }
                        $item_name=($fav_name!='') ? $fav_name:htmlspecialchars_decode($row->ItemName);
                        $row->fav_name=$fav_name;
                        $row->item_name=$item_name;
                        $row->fav_selected_array=$fav_selected_array;
            @endphp
            <div class="col-favourite">
                <div class="favourite-box">
                    <div class="media media-item">
                        <input type="hidden" name="ItemId" value="{{$row->ID}}">
                        <input type="hidden" id="MakeMeal{{$row->ID}}" value="{{$has_meal}}">
                        <input type="hidden" name="ItemsName" value="{{$row->ItemName}}">
                        <input type="hidden" name="ItemsPLU" value="{{$row->PLU}}">
                        <input type="hidden" name="TotalAmounts" value="{{$row->Price}}">
                        <input type="hidden" name="QuickOrder{{$row->ID}}" id="QuickOrder{{$row->ID}}" value="0">

                        <img  alt="loading.." data-src="{{asset(isset($row->LocalThumbnailImg) ? $row->LocalThumbnailImg : $row->ThumbnailImg)}}" @if($has_modifier==1) style="cursor: pointer" onclick="OpenModel({{$row->ID}})" @endif class="mr-3 img-thum b-lazy"  alt="...">
                        <div class="media-body">
                            <h5 class="mt-0">
                                <a id="ItemName{{$row->ID}}" href="javascript:void(0)" @if($has_modifier==1) onclick="OpenModel({{$row->ID}})" @endif style="max-width: 60% !important; float: left !important;">{{$row->item_name}}</a>
                                <span class="price" style="max-width: 38% !important; float:right !important; vertical-align: text-top">{{number_format($row->Price)}} {{$currency}}</span>
                                <div class="clearfix"></div>
                                <ul class="icon">
                                <?php
                                    foreach ($array_icon as $key=>$val)
                                        {
                                            if(isset($row->$key) and $row->$key=="1")
                                            {
                                                echo '<li><img src="'.$val['URL'].'" /> '.$val['Label'].'</li>';
                                            }
                                        }
                                ?>
                                </ul>
                            </h5>
                            <div class="content">{{substr(htmlspecialchars_decode($row->Details),0,250)}}</div>
                        </div>
                    </div>
                    <div class="mediabox row align-items-center">
                        <div class="col-sm-7 text-center">
                            @php
                                $active_f='';
                               if($row->IsFavorite=='1')
                                   {
                                       $active_f='active';

                                   }
                            @endphp
                            @if(!$display_favourite)
                                @if(session('is_login'))
                                    <a onclick="SetFavourite({{$row->ID}})" id="Favourite{{$row->ID}}" href="javascript:void(0)" class="effect-underline link-favourite mr-3 {{$active_f}}"><span>Favourite</span></a>
                                @else
                                    <a onclick="loginAlert()" class="effect-underline link-favourite mr-3 cursor-pointer"><span>Favourite</span></a>
                                @endif
                            @endif
                            @php
                                $x=0;
                                    $customize= 'Customize';
                                    $cust_css='';
                                    if(isset($items_customized[$row->PLU]) and $items_customized[$row->PLU]=='1')
                                    {
                                        $customize='Customized';
                                        $cust_css='active';
                                    }
                                elseif($row->IsFavorite=='1' and count($fav_selected_array)>0)
                                    {
                                        $customize='Customized';
                                        $cust_css='active';
                                        $x=1;

                                    }
                            @endphp
                            @if($has_modifier==1)
{{--                            <a onclick="OpenModel({{$row->ID}})" id="CustomizedLink{{$row->ID}}"  class="link-customize pointer effect-underline {{$cust_css}}"><span class="customize-label" id="Customize{{$row->ID}}">{{$customize}}</span></a>--}}
                            <a onclick="OpenModel({{$row->ID}})" id="CustomizedLink{{$row->ID}}"  class="link-customize pointer effect-underline {{$cust_css}}"><span class="customize-label" id="Customize{{$row->ID}}">@lang('customized')</span></a>
                            @endif
                        </div>
                        <div class="col-sm-5 text-center">
                            <div class="input-group mx-auto item-plus-minus">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-link btn-link-minus pointer" data-code="{{$row->ID}}" onclick="SubQty({{$row->ID}},{{$row->PLU}})">&nbsp;</button>
                                </div>
                                <input type="text" name="qty[{{$row->ID}}]" id="qty_{{$row->ID}}" class="form-control qty_all" value="{{isset($item_qty[$row->PLU])? $item_qty[$row->PLU]:0}}" style="background: none !important" readonly="readonly">
                                @if($row->QuickOrder==1)
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-link btn-link-plus pointer" data-code="{{$row->ID}}" onclick="AddQty({{$row->ID}})">&nbsp;</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($display_favourite)
                        <input type="hidden" id="MCust{{$row->ID}}" value="{{$x}}">
                    <a href="#!" class="link-close remove-fav-item" data-code="{{$row->FavouriteId}}"><img src="{{asset('assets/images/icon-close.png')}}" /></a>
                    @endif
                </div>
            </div>
            @include('menu._menu_details',array('row'=>$row,'display_favourite'=>$display_favourite))
            @include('menu._make_meal',array('row'=>$row))
        </form>
    @endforeach
    @if(empty($query->data))
        <h3 style="text-align: center"><?php echo app('translator')->get('No Items Here!'); ?></h3>
    @endif
    <div class="food-icon-container">
        <ul class="food-icon">
            @foreach($icons as $icn)
                <li><img src="{{$icn->IconUrl}}" />{{$icn->IconLabel}}</li>
            @endforeach
        </ul>
    </div>

</div>
