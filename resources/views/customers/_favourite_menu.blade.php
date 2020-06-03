@php
$active='btn-8DBF43 ';
@endphp
<div class="title-div mb-5 pb-2">
    <div class="row-favourite mx-auto" style="box-shadow: none !important;">
        <div class="col-favourite">
            <div class="favourite-box" style="-webkit-box-shadow:none !important;">
    <a href="{{route('customer.favourite.items')}}" class="btn btn-fav {{(!isset($sub_active) or $sub_active=='fav')? $active : 'btn-orders'}} mr-2">@lang('favourites')</a>
    <a href="{{route('customer.favourite.orders')}}" class="btn {{isset($sub_active) && $sub_active=='orders'? $active : 'btn-orders'}}">@lang('orders')</a>
</div>
</div>
    </div></div>