@php
    $Skey=session()->get('skey');
        $user=session()->get('user'.$Skey);
        $full_name=@$user->details->FirstName.' '.@$user->details->LastName;
        if(strlen($full_name)>40){
            $full_name=substr($full_name,0,40).'...';
        }
@endphp

<h4 class="title text-center">@lang("order_summary")</h4>
@if($fav==1 and $cart==null)
    <h4 class="text-center" style="margin-top: 2px !important; font-weight: normal !important; font-size: 30px !important; line-height: 0px;">@lang('add_favourites')</h4>
@endif
<?php /* <h5 class="user">{{$full_name}}</h5> */ ?>
<div class="cart-items my-3">
    @php
        $_total=0;
    @endphp
    @if($cart!=null)
        @foreach($cart as $key=>$values)
            <div class="cart-item mb-4">

                <h5 class="name text-4D4D4D"><span class="name-value" style="float: left; width: 45%;">{{htmlspecialchars_decode($values['name'])}}</span>
                    <span class="price d-inline-block ml-3" style=" width: 18%;text-align: right; float: right; margin-right: 6rem;">{{number_format($values['price'])}}</span></h5>
                <div class="info text-808080">
                    <div class="clearfix"></div>
                    @php

                        $modifiers=$values['modifiers'];
                        $_total+=$values['price'];
                        $md_array=array();
                        for($i=0;$i<count($modifiers);$i++)
                        {
                            array_push($md_array,$modifiers[$i]['name']);
                        }
                    @endphp
                    {{implode(', ',$md_array )}}
                </div>
                <div class="cart-btns">
                    <a href="javascript:void(0)" onclick="editItem({{$key}})"><img src="{{asset('assets/images/icon-cart-edit.png')}}" /></a>
                    <a href="javascript:void(0)" onclick="_copyItem({{$key}})"><img src="{{asset('assets/images/icon-cart-add.png')}}" /></a>
                    <a href="javascript:void(0)" onclick="_deleteItem({{$key}})"><img src="{{asset('assets/images/icon-cart-delete.png')}}" /></a>
                </div>
                @if(isset($values['meal']))
                    @php
                        $meal=$values['meal'];
                    @endphp
                    @if($meal!=null and isset($meal['name']))
                        <div class="clearfix"></div>
                        <div class="speacial-meal bg-8DBF43">
                            MEAL <span class="d-inline-block mx-3">{{$meal['name']}}</span><span class="d-inline-block">{{number_format($meal['price'])}}</span>
                            <a href="javascript:void(0)" onclick="_deleteMeal({{$key}})" class="close"><img src="{{asset('assets/images/icon-close-white.png')}}" /></a>
                        </div>
                    @endif
                @endif
            </div>
            <div class="clearfix"></div>
        @endforeach

    @endif

</div>
    <div class="carttotal-block mt-3">
        <div class="delivery-fee text-right"><span class="float-left">@lang('delivery_fee')</span> @if($cart!=null){{number_format($delivery_fees).' '.$currency}}  @endif &nbsp;</div>
        <hr/>
        <div class="total-fee text-right"><span class="float-left">@lang('total')</span>  @if($cart!=null){{number_format(($_total+$delivery_fees)).' '.$currency}} @else <span style="color: white">&nbsp; </span>  @endif </div>

    </div>
    <div class="action-buttons text-center mt-5 mb-3">
        <button @if($cart!=null) class="btn btn-B3B3B3 text-uppercase" @else class="btn btn-B3B3B3 text-uppercase btn-B3B3B3-non-hover" @endif onclick="_destroyCart()" id="DestroyBtn">@lang('clear_all')</button>
        <a id="CheckOutBtn" @if($cart!=null) href="{{route('checkout.address')}}" class="btn btn-8DBF43 text-uppercase " @else href="javascript:void(0)" class="btn btn-B3B3B3 text-uppercase btn-B3B3B3-non-hover" @endif >@lang('checkout')</a>
    </div>
<script>
	$( document ).ready(function() {

		function adjustScrolling(){
			var scroll = $(window).scrollTop();
			if(scroll>75){
				$('.cartbox-wrapper .cart-dropdown').css('marginTop','-70px');
			}else{
				$('.cartbox-wrapper .cart-dropdown').css('marginTop','0px');
			}
		}

		$(function() {
			adjustScrolling();
		});

		$(window).scroll(function (event) {
			//adjustScrolling();
		});

	});
</script>
