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
        {{--.no-js #loader { display: none;  }--}}
        {{--.js #loader { display: block; position: absolute; left: 100px; top: 0; }--}}
        {{--.se-pre-con {--}}
            {{--position: fixed;--}}
            {{--left: 0px;--}}
            {{--top: 0px;--}}
            {{--width: 100%;--}}
            {{--height: 100%;--}}
            {{--z-index: 9999;--}}
            {{--background: url({{asset('assets/loaders/loader-64x/Preloader_4.gif')}}) center no-repeat #fff;--}}
        {{--}--}}
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script>
		//paste this code under head tag or in a seperate js file.
		// Wait for window load
		$(window).load(function() {
			// Animate loader off screen
			// $(".se-pre-con").fadeOut(2000);
			$("#loader").fadeOut(2000);
			_getCountCartItems();
		});

    </script>
</head>
<body class="{{$body_css ?? '' }}">

<div id="loader">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 290.27 97.88"><defs><style>.cls-1{fill:#8dbf43;}</style></defs><title>ZWZ App - iconsonly</title><g id="top_bar" data-name="top bar"><path class="cls-1" d="M94.83,37.29C53.48,67.78,20.12,66,2.36,61c-2.16-.64-2-0.57-2.29.09S0.25,63.26,6,64.75c6.89,1.76,10.41.64,12.09,3s8,15.55,22.71,23.53S82,100.44,106.69,70.34s47.7-57.91,50-60.19C145.14,12.52,118.33,15,92.78,6.49c-84.31-28.16-82.37,44-79.12,52.19,0,0,28.74,6.91,81-22,2.89-1.68,2.08-.71.15,0.59"/><path class="cls-1" d="M141.71,88.29C155.79,73.15,183.48,31.15,210.94,11s69.51-3.25,75.92,33.44c6.1,35-23.23,56.91-54.56,50.57-46.67-9.44-90.58-6.69-90.58-6.69M271,28.51c0.62,0.47,1.23.5-1.45-3-4.06-5.29-12-12.19-24.83-14.57-14.2-2.63-27.42,4.43-35,9.67-3.09,2.15-4.15,3.55.46,1a50.45,50.45,0,0,1,33.26-5.08c12.41,2.24,20.07,6.26,24.61,9.67,0,0,1.15,1,2.91,2.29"/><path class="cls-1" d="M285.91,89.16a4.41,4.41,0,0,1,1.71.34,4.27,4.27,0,0,1,1.43,1,4.22,4.22,0,0,1,.9,1.39,4.44,4.44,0,0,1,.31,1.66,4.38,4.38,0,0,1-.33,1.7,4.06,4.06,0,0,1-1,1.39,4.53,4.53,0,0,1-1.43.94,4.26,4.26,0,0,1-1.64.33,4.22,4.22,0,0,1-1.67-.34,4.3,4.3,0,0,1-1.42-1,4.47,4.47,0,0,1-.94-1.41,4.19,4.19,0,0,1-.32-1.62,4.28,4.28,0,0,1,.34-1.69,4.43,4.43,0,0,1,1-1.44,4.18,4.18,0,0,1,1.38-.92A4.32,4.32,0,0,1,285.91,89.16Zm0,0.8a3.57,3.57,0,0,0-1.36.26,3.5,3.5,0,0,0-1.13.75,3.62,3.62,0,0,0-.8,1.18,3.55,3.55,0,0,0-.28,1.39,3.43,3.43,0,0,0,1,2.47,3.54,3.54,0,0,0,1.16.8,3.47,3.47,0,0,0,1.37.28,3.58,3.58,0,0,0,1.36-.26,3.65,3.65,0,0,0,1.17-.76,3.34,3.34,0,0,0,.78-1.14,3.7,3.7,0,0,0,0-2.74,3.39,3.39,0,0,0-.75-1.13A3.51,3.51,0,0,0,285.92,90ZM284.08,91H286a2.8,2.8,0,0,1,1.54.35,1.16,1.16,0,0,1,.53,1,1.26,1.26,0,0,1-.27.81,1.44,1.44,0,0,1-.75.48l1.06,2.11h-1.45l-0.9-1.9h-0.38v1.9h-1.31V91Zm1.31,0.82v1.28h0.47A1.1,1.1,0,0,0,286.5,93a0.54,0.54,0,0,0,.21-0.46,0.57,0.57,0,0,0-.22-0.5,1.25,1.25,0,0,0-.71-0.16h-0.39Z"/></g></svg>
</div>

{{--<div class="se-pre-con"></div>--}}
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
