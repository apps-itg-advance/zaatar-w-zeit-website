<div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-cartitems">
    <div class="cartbox-wrapper">
        <div class="cart-dropdown" id="cart-dropdown">
        </div>
    </div>
</div>
@php
$fav_flag=(isset($favourite) and $favourite=true) ? 1:0;
@endphp
@section('javascriptCart')
    <script>
		jQuery(document).ready( function() {
			LoadCart();
		});
		function LoadCart()
		{

			//event.preventDefault();
			$.ajax({
				type:'GET',
				data:{fav:{{$fav_flag}}},
				url:'{{route('carts.index')}}',
				success:function(data){
					$("#cart-dropdown").html(data);
				}
			});
		}
		function _deleteItem(id) {
			$.ajax({
				type:'GET',
				url:'{{route('carts.delete')}}'+'/'+id,
				success:function(data){
					_getCountCartItems();
					LoadCart();
					var res = data.split("-");
					if($('#qty_'+res[1]).length > 0) {
						$('#qty_' + res[1]).val(res[0]);
					}
					if($('#CustomizedLink'+res[1]).length > 0) {
						if(res[2]>0)
						{
							$('#CustomizedLink'+res[1]).addClass("active");
							$('#Customize'+res[1]).html("Customized");
						}
						else{
							$('#CustomizedLink'+res[1]).removeClass("active");
							$('#Customize'+res[1]).html("Customize");
						}
					}
					return false;
					//OpenCart();
				}
			});
		}
		function _deleteMeal(id) {
			$.ajax({
				type:'GET',
				url:'{{route('carts.delete.meal')}}'+'/'+id,
				success:function(data){
					_getCountCartItems();
					LoadCart();


					return false;
					//OpenCart();
				}
			});
		}
		function _copyItem(id) {
			$.ajax({
				type:'GET',
				url:'{{route('carts.copy.item')}}'+'/'+id,
				success:function(data){
					_getCountCartItems();
					LoadCart();
					var res = data.split("-");
					if($('#qty_'+res[1]).length > 0) {
						$('#qty_' + res[1]).val(res[0]);
					}
					return false;
				}
			});
		}

		function _destroyCart() {
			$.ajax({
				type:'GET',
				url:'{{route('carts.destroy')}}',
				success:function(data){
					LoadCart();
					_getCountCartItems();
					$(".qty_all").val(0);
					if($('.link-customize').length > 0) {
						$('.link-customize').removeClass("active");
						$('.customize-label').html("Customize");
					}
					//$(".col-cartitems").html(data);
				}
			});
		}

		function adjustWidth() {
			var parentwidth = $(".col-cartitems").width();
			document.getElementById("cart-dropdown").style.width = parentwidth+'px';
			// alert(parentwidth);
		}

		$(function() {
			adjustWidth();
		});

		$(window).resize(
			function() {
				adjustWidth();
			});

    </script>
@endsection



