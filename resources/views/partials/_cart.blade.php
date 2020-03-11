@php
    $Skey=session()->get('skey');
        $user=session()->get('user'.$Skey);

@endphp
<div class="cartbox-wrapper">
    <div class="cart-dropdown" id="cart-dropdown">
        <h4 class="title text-center">Order Summary</h4>
        <h5 class="user">{{@$user->details->FirstName.' '.@$user->details->LastName}}</h5>
        <div class="cart-items my-5">
            @php
                $_total=0;
            @endphp
            @if($cart!=null)
            @foreach($cart as $key=>$values)
                <div class="cart-item mb-4">
                    <h5 class="name text-4D4D4D"> {{$values['name']}}<span class="price d-inline-block ml-3">{{number_format($values['price'])}}</span></h5>
                    <div class="info text-808080">
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
                    <div class="cart-btns">
                        <a href="javascript:void(0)" onclick="editItem({{$key}})"><img src="{{asset('assets/images/icon-cart-edit.png')}}" /></a>
                        <a href="javascript:void(0)" onclick="_copyItem({{$key}})"><img src="{{asset('assets/images/icon-cart-add.png')}}" /></a>
                        <a href="javascript:void(0)" onclick="_deleteItem({{$key}})"><img src="{{asset('assets/images/icon-cart-delete.png')}}" /></a>
                    </div>
                    @if(isset($values['meal']))
                        @php
                            $meal=$values['meal'];
                        @endphp
                        @if($meal!=null)
                    <div class="speacial-meal bg-8DBF43">
                        MEAL <span class="d-inline-block mx-3">{{$meal['name']}}</span><span class="d-inline-block">{{number_format($meal['price'])}}</span>
                        <a href="javascript:void(0)" onclick="_deleteMeal({{$key}})" class="close"><img src="{{asset('assets/images/icon-close-white.png')}}" /></a>
                    </div>
                    @endif
                    @endif
                </div>
            @endforeach
            @endif
        </div>
        @if($cart!=null)
        <div class="carttotal-block mt-3">
            <div class="delivery-fee text-right"><span class="float-left">Delivery fee</span> {{number_format($delivery_fees).' '.$currency}}</div>
            <hr/>
            <div class="total-fee text-right"><span class="float-left">Total</span> {{number_format(($_total+$delivery_fees)).' '.$currency}}</div>
        </div>
        <div class="action-buttons text-center mt-5 mb-3">
            <button class="btn btn-B3B3B3 text-uppercase" onclick="_destroyCart()" id="DestroyBtn">Clear All</button>
            <a href="{{route('checkout.summary')}}" class="btn btn-8DBF43 text-uppercase" >Checkout</a>
        </div>
            @endif
    </div>
</div>

<script>
	$( document ).ready(function() {
		function adjustWidth() {
			var parentwidth = $(".col-cartitems").width();
			document.getElementById("cart-dropdown").style.width = parentwidth+'px';
		}
		$(function() {
			adjustWidth();
		});
		$(window).resize(
			function() {
				adjustWidth();
			});

		function adjustScrolling(){
			var scroll = $(window).scrollTop();
			if(scroll>75){
				$('.cartbox-wrapper .cart-dropdown').css('marginTop','-80px');
			}else{
				$('.cartbox-wrapper .cart-dropdown').css('marginTop','0px');
			}
		}

		$(function() {
			adjustScrolling();
		});

		$(window).scroll(function (event) {
			adjustScrolling();
		});

	});
</script>