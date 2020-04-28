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
            @endphp
            <div class="col-favourite">
                <div class="favourite-box">
                    <div class="media" style="height: 200px;">
                        <input type="hidden" name="ItemId" value="{{$row->ID}}">
                        <input type="hidden" id="MakeMeal{{$row->ID}}" value="{{$has_meal}}">
                        <input type="hidden" name="ItemsName" value="{{$row->ItemName}}">
                        <input type="hidden" name="ItemsPLU" value="{{$row->PLU}}">
                        <input type="hidden" name="TotalAmounts" value="{{$row->Price}}">
                        <input type="hidden" name="QuickOrder{{$row->ID}}" id="QuickOrder{{$row->ID}}" value="0">

                        <img src="{{asset($row->LocalThumbnailImg)}}" class="mr-3 img-thum"  alt="...">
                        <div class="media-body">
                            <h5 class="mt-0">
                                <a href="#" style="max-width: 60% !important; float: left !important;">{{htmlspecialchars_decode($row->ItemName)}}</a>
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
                                $customize= 'Customize';
                                $cust_css='';
                                if(isset($items_customized[$row->PLU]) and $items_customized[$row->PLU]=='1')
                                {
                                    $customize='Customized';
                                    $cust_css='active';
                                }
                            @endphp
                            <a onclick="OpenModel({{$row->ID}})" id="CustomizedLink{{$row->ID}}"  class="link-customize pointer effect-underline {{$cust_css}}"><span class="customize-label" id="Customize{{$row->ID}}">{{$customize}}</span></a>
                        </div>
                        <div class="col-sm-5 text-center">
                            <div class="input-group mx-auto item-plus-minus">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-link pointer" data-code="{{$row->ID}}" onclick="SubQty({{$row->ID}},{{$row->PLU}})"><img src="{{asset('assets/images/icon-minus.png')}}" /></button>
                                </div>
                                <input type="text" name="qty[{{$row->ID}}]" id="qty_{{$row->ID}}" class="form-control qty_all" value="{{isset($item_qty[$row->PLU])? $item_qty[$row->PLU]:0}}" style="background: none !important" readonly="readonly">
                                @if($row->QuickOrder==1)
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-link pointer" data-code="{{$row->ID}}" onclick="AddQty({{$row->ID}})"><img src="{{asset('assets/images/icon-plus.png')}}" /></button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($display_favourite)
                    <a href="#!" class="link-close remove-fav-item" data-code="{{$row->FavouriteId}}"><img src="{{asset('assets/images/icon-close.png')}}" /></a>
                    @endif
                </div>
            </div>
            @include('menu._menu_details',array('row'=>$row))
            @include('menu._make_meal',array('row'=>$row))
        </form>
    @endforeach

</div>
