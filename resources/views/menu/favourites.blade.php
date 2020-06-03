@extends('layouts.template')
@section('content')
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-favourite-items">
            <div class="col-lg-12 float-none p-0 mx-auto">
				@include('customers._favourite_menu')
                <div class="row row-favourite mx-auto">

					@include('menu._menu_grid',array('display_favourite'=>true))
				</div>
            </div>
    </div>
    @include('partials.cart',array('favourite'=>true))
@endsection

@section('javascript')
	<script src="{{asset('assets/js/blazy.min.js')}}"></script>
	<script>
		(function() {
			// Initialize
			var bLazy = new Blazy();
		})();
	</script>
	@include('menu._menu_js')
		<script type="text/javascript">
				$('body').on('click', '.remove-fav-item', function(){
					// var spinnerContainerElement = $("button[data-code='" + id + "']").closest('.item-plus-minus');
					spinnerOver('show', $(this).closest('.favourite-box'));
					var itemId = $(this).data('code');
					var that = this;
					$.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						type:'POST',
						url:'{{route('customer.remove.favourite')}}',
						data:{item_id:itemId},
						success:function(data){
							$(that).closest('.col-favourite').remove();
							$('#cartbig-modal-'+itemId).remove();
							_getCountCartItems();
							LoadCart();
							Swal.fire({
								// position: 'top-end',
								icon: 'success',
								title: '<?php echo app('translator')->get('your_favourite_item_removed_successfully.'); ?>',
								showConfirmButton: false,
								timer: 1200
							});
							// spinner('hide', $(that).closest('.favourite-box'));
							// jQuery('.cartbig-modal').modal('hide');
							// $("button[data-code='" + itemId + "']").prop('disabled',false);
							// loader('hide');
						}
					});
					// spinner('hide', $(this).closest('.col-favourite'));
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