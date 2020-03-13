@extends('layouts.template')

@section('content')

    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-favourite-items">
        <div class="title-div mb-4 pb-2 text-center">
            <h2 class="title text-8DBF43">{{$_cat_title}}</h2>
        </div>

        <div class="col-lg-12 float-none p-0 mx-auto">
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
                        <div class="media">
                            <input type="hidden" name="ItemId" value="{{$row->ID}}">
                            <input type="hidden" id="MakeMeal{{$row->ID}}" value="{{$has_meal}}">
                            <input type="hidden" name="ItemsName" value="{{$row->ItemName}}">
                            <input type="hidden" name="ItemsPLU" value="{{$row->PLU}}">
                            <input type="hidden" name="TotalAmounts" value="{{$row->Price}}">
                            <input type="hidden" name="QuickOrder{{$row->ID}}" id="QuickOrder{{$row->ID}}" value="0">

                            <img src="{{$row->ThumbnailImg}}" class="mr-3 img-thum"  alt="...">
                            <div class="media-body">
                                <h5 class="mt-0">
                                    <a href="#">{{$row->ItemName}}</a>
                                    <span class="price">{{number_format($row->Price)}} {{$currency}}</span>
                                </h5>
                                <div class="content">{{$row->Details}}</div>
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
                                @if(session('is_login'))
                                    <a onclick="SetFavourite({{$row->ID}})" id="Favourite{{$row->ID}}" href="javascript:void(0)" class="effect-underline link-favourite mr-3 {{$active_f}}">Favourite</a>
                                @else
                                    <a onclick="loginAlert()" class="effect-underline link-favourite mr-3 cursor-pointer">Favourite</a>
                                @endif
                                <a onclick="OpenModel({{$row->ID}})" class="link-customize pointer effect-underline">Customize</a>
                            </div>
                            <div class="col-sm-5 text-center">
                                <div class="input-group mx-auto item-plus-minus">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-link pointer" data-code="{{$row->ID}}" onclick="AddQty({{$row->ID}})"><img src="{{asset('assets/images/icon-plus.png')}}" /></button>
                                    </div>
                                    <input type="text" name="qty[{{$row->ID}}]" id="qty_{{$row->ID}}" class="form-control qty_all" value="{{isset($item_qty[$row->PLU])? $item_qty[$row->PLU]:0}}">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-link pointer" data-code="{{$row->ID}}" onclick="SubQty({{$row->ID}},{{$row->PLU}})"><img src="{{asset('assets/images/icon-minus.png')}}" /></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('menu._menu_details',array('row'=>$row))
                @include('menu._make_meal',array('row'=>$row))
                    </form>
                @endforeach

            </div>
        </div>

    </div>
    @include('partials.cart')
@endsection

@section('javascript')
    <script src="{{asset('assets/js/jquery.matchHeight-min.js')}}"></script>
    <script type="text/javascript">
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }
        function CustomizeItem() {
            $("#ItemModify").val(1);
        }

        function OpenModel(id) {
            $("#SItemId").val(id);
            var qty=$("#qty_"+id).val();
            $("#SQty").val(qty);
            jQuery('#cartbig-modal-'+id).modal();
            return false;
        }
        function MakeMealModel(id) {
            var hasM= $("#MakeMeal"+id).val();
            $("#SItemId").val(id);
            var qty=$("#qty_"+id).val();
            $("#SQty").val(qty);
            if(hasM >0){
                jQuery('#makeMeal-modal'+id).modal();
            }
            else{
                AddToCart(id);
            }
            return false;
        }
        function SubmitCustomize(id) {
            $("#QuickOrder"+id).val(1);
            AddToCart(id);
        }
        function CalculateTotal(cat_id,max_qty,id,item_id) {
            var ItemId="qty_"+item_id;
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
                $("#"+CheckId).prop('checked', false);
                return false;
            }
            else{
                var mVal=$("#"+CheckId).val();
                var res = mVal.split("-");
               // var mPrice=parseFloat(res[2])*currentQty;
                var mPrice=parseFloat(res[2]);
                if($("#"+CheckId).is(':checked'))
                {

                    var nTotal=parseFloat($("#TotalAmount"+item_id).val())+mPrice;
                }
                else{
                    var nTotal=parseFloat($("#TotalAmount"+item_id).val())-mPrice;
                }
                $("#TotalAmount"+item_id).val(nTotal);
                $("#DisplayTotal"+item_id).text(formatNumber(nTotal)+' LBP');
            }
        }
        function CalculateMakeMealTotal(id,item_id) {
            var CheckId='makeMealH'+id;

                var mVal=$("#"+CheckId).val();
                var res = mVal.split("-");
                if($("#"+CheckId).is(':checked'))
                {
                    $(".Sub"+id).removeAttr("disabled");

                    var nTotal=parseFloat($("#TotalAmount"+item_id).val())+parseFloat(res[1]);
                }
                else{
                    $(".Sub"+id).prop("checked", false);
                    $(".Sub"+id).attr("disabled", true);
                    var nTotal=parseFloat($("#TotalAmount"+item_id).val())-parseFloat(res[1]);
                }
                $("#TotalAmount"+item_id).val(nTotal);
                $("#DisplayTotal"+item_id).text(formatNumber(nTotal)+' {{$currency}}');
            }
        function CalculateMakeMealTotalQ(id,item_id) {
            var CheckId='makeMealL'+id;

            var mVal=$("#"+CheckId).val();
            var res = mVal.split("-");

            if($("#"+CheckId).is(':checked'))
            {
                $(".Sub"+id).removeAttr("disabled");
                var nTotal=parseFloat($("#TotalAmountQ"+item_id).val())+parseFloat(res[1]);
            }
            else{
                $(".Sub"+id).prop("checked", false);
                $(".Sub"+id).attr("disabled", true);
                var nTotal=parseFloat($("#TotalAmountQ"+item_id).val())-parseFloat(res[1]);
            }
            $("#TotalAmountQ"+item_id).val(nTotal);
            $("#DisplayTotalQ"+item_id).text(formatNumber(nTotal)+' {{$currency}}');
        }
        function AddQty(id) {
	        var hasM = $("#MakeMeal"+id).val();
	        if(hasM==0){
		        // loader('show');
		        var spinnerContainerElement = $("button[data-code='" + id + "']").closest('.item-plus-minus');
		        spinner('show', spinnerContainerElement);
		        $("button[data-code='" + id + "']").prop('disabled',true);
	        }
            var currentTotal=parseFloat($("#TotalAmount"+id).val());
            var ItemId="qty_"+id;
            var currentQty=parseInt($("#"+ItemId).val());
            var newQty=currentQty+1;
            $("#"+ItemId).val(newQty);
            var newTotal=currentTotal;
           // $("#TotalAmount"+id).val(newTotal);
            $("#QuickOrder").val('1');
            $("#DisplayTotal"+id).text(formatNumber(newTotal)+' LBP');
             MakeMealModel(id);
        }
        function SubQty(id,plu) {
        	//sp spinner
            var spinnerContainerElement = $("button[data-code='" + id + "']").closest('.item-plus-minus');
            spinner('show', spinnerContainerElement);

	        var currentTotal=parseFloat($("#TotalAmount"+id).val());
            var ItemId="qty_"+id;

            var currentQty=parseInt($("#"+ItemId).val());
            if(currentQty >0)
            {
                var newQty=currentQty-1;
                $("#"+ItemId).val(newQty);
                var newTotal=currentTotal*newQty;
              //  $("#TotalAmount"+id).val(newTotal);
                $("#DisplayTotal"+id).text(formatNumber(newTotal)+' LBP');
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:'{{route('carts.remove')}}',
                data:{id:plu},
                success:function(data){
                    _getCountCartItems();
                    LoadCart();
                    spinner('hide', spinnerContainerElement);
                }
            });
	        spinner('hide', spinnerContainerElement);
        }
        function AddToCart(id)
        {
	        var spinnerContainerElement = $("button[data-code='" + id + "']").closest('.item-plus-minus');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:'POST',
                url:'{{route('carts.store')}}',
                data:$("#Form"+id).serialize(),
                success:function(data){
                    _getCountCartItems();
                    LoadCart();
                    jQuery('.cartbig-modal').modal('hide');
	                $("button[data-code='" + id + "']").prop('disabled',false);
	                // loader('hide');
                    $('#qty_'+id).val(data);
	                spinner('hide', spinnerContainerElement);
                }
            });

        }
        $('#Form').on('submit', function(event){
           // event.preventDefault();
         //   AddToCart(0);

        });
    </script>
@endsection
@section('css')
    <style type="text/css">
        .pointer{
            cursor: pointer;
        }
    </style>
@endsection