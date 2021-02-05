@extends('layouts.template')
@section('content')
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-favourite-items">
        <div class="col-lg-12 float-none p-0 mx-auto">
            @php
                $active='btn-8DBF43';
            @endphp
            <div class="title-div mb-5 pb-2">
                <div class="row-favourite mx-auto" style="box-shadow: none !important;">
                    <div class="col-favourite">
                        <div class="favourite-box" style="-webkit-box-shadow:none !important;">
                            <a href="{{route('customer.favourite.items')}}"
                               class="btn btn-fav {{(!isset($sub_active) or $sub_active=='fav')? $active : 'btn-orders'}} mr-2">@lang('favourites')</a>
                            <a href="{{route('customer.favourite.orders')}}"
                               class="btn {{isset($sub_active) && $sub_active=='orders'? $active : 'btn-orders'}}">@lang('orders')</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-favourite mx-auto">
                <favorites-list-component
                    :items="{{json_encode($query->data)}}"
                    :title="{{json_encode($page_title)}}">
                </favorites-list-component>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-cartitems">
        <div class="cartbox-wrapper">
            <div class="cart-dropdown" id="cart-dropdown">
                <cart-component
                    :cart="{{json_encode($cart)}}"
                ></cart-component>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{asset('assets/js/blazy.min.js')}}"></script>
    <script>
        (function () {
            // Initialize
            var bLazy = new Blazy();
        })();
    </script>
    @include('menu._menu_js')
    <script type="text/javascript">
        $('body').on('click', '.remove-fav-item', function () {
            spinnerOver('show', $(this).closest('.favourite-box'));
            var itemId = $(this).data('code');
            var that = this;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{route('customer.remove.favourite')}}',
                data: {item_id: itemId},
                success: function (data) {
                    $(that).closest('.col-favourite').remove();
                    $('#cartbig-modal-' + itemId).remove();
                    _getCountCartItems();
                    LoadCart();
                    Swal.fire({
                        // position: 'top-end',
                        icon: 'success',
                        title: '<?php echo app('translator')->get('your_favourite_item_removed_successfully.'); ?>',
                        showConfirmButton: false,
                        timer: 1200
                    });
                }
            });
        });
    </script>
@endsection
@section('css')
    <style type="text/css">
        .pointer {
            cursor: pointer;
        }
    </style>
@endsection
