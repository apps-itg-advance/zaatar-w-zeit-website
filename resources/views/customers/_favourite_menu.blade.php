@php
$active='btn-8DBF43 ';
@endphp
<div class="title-div mb-4 pb-2 text-left col-xl-6 col-lg-12 col-favourite fav-padding">
    <a href="{{route('customer.favourite.items')}}" class="btn btn-fav {{(!isset($sub_active) or $sub_active=='fav')? $active : 'btn-orders'}} mr-2">Favourites</a>
    <a href="{{route('customer.favourite.orders')}}" class="btn {{isset($sub_active) && $sub_active=='orders'? $active : 'btn-orders'}}">Orders</a>
</div>