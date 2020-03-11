<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/all.min.css')}}">
    @yield('css')
    <link rel="stylesheet" href="{{asset('assets/css/template1.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
    <link rel="shortcut icon" href="/assets/images/favicon.png" />
    <title>Zaatar W Zeit - ZWZ Web ordering-redesign-Menu</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <style>
        /* Paste this css to your style sheet file or under head tag */
        /* This only works with JavaScript,
        if it's not present, don't show loader */
        .no-js #loader { display: none;  }
        .js #loader { display: block; position: absolute; left: 100px; top: 0; }
        .se-pre-con {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url({{asset('assets/loaders/loader-64x/Preloader_4.gif')}}) center no-repeat #fff;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script>
		//paste this code under head tag or in a seperate js file.
		// Wait for window load
		$(window).load(function() {
			// Animate loader off screen
			$(".se-pre-con").fadeOut(2000);
			_getCountCartItems();
		});

    </script>
</head>
<body class="{{$body_css ?? '' }}">
<div class="se-pre-con"></div>
@include('partials.header')
<div class="content-wrapper {{$class_css ?? 'favourites-wrapper' }} py-5">
    <div class="container">

        <div class="content-container">

            <div class="row">
                @php
                    if(!isset($flag) or $flag==='login' or $flag==='pin')
                    {
                     $flag=false;
                    }
                @endphp
                @if($flag)

                    @include('partials.left')
                @endif
                @yield('content')
            </div>
            <div class="cartbig-modal modal fade" id="edit-cart-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div id="edit-cart"></div>
            </div>
        </div>

    </div>
</div>
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/template.js')}}"></script>

<script type="text/javascript">

	function formatNumber(num) {
		return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
	}
	jQuery(document).ready( function() {
		var windowHeight = jQuery(window).height();
		var headerHeight = jQuery('.header-wrapper').height();
		var contentHeight = jQuery('.content-container').height();
		var finalheight = windowHeight;
		if(contentHeight < finalheight) {
			jQuery(".content-container").css("height", windowHeight);
		}


		//  jQuery(".cart-dropdown").css("height", windowHeight - headerHeight - 10);
		//  jQuery(".cart-dropdown").css("overflow-y", 'scroll');

	});
	function _getCountCartItems() {
		$.ajax({
			type:'GET',
			url:'{{route('carts.count')}}',
			success:function(data){
				$("#CartItems").html(data);
				//OpenCart();
			}
		});
	}
	function addItemQty(key,id,total) {
		$.ajax({
			type:'GET',
			dataType:'json',
			url:'{{route('carts.add_qty')}}'+'/'+key,
			success:function(data){
				$("#"+id+key).html(data.qty);
				$("#"+total).html(data.total);
				//OpenCart();
			}
		});
		return false;
	}
	function editItem(key) {
		$.ajax({
			type:'GET',
			url:'{{route('carts.edit')}}'+'/'+key,
			success:function(data){
				$("#edit-cart").html(data);
				jQuery('#edit-cart-modal').modal();
				//OpenCart();
			}
		});
		return false;
	}
</script>
@yield('javascript')
@yield('javascriptCart')

</body>
</html>
