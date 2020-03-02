<div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-cartitems"></div>
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
                url:'{{route('carts.index')}}',
                success:function(data){
                    $(".col-cartitems").html(data);
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
                    //$(".col-cartitems").html(data);
                }
            });
        }


    </script>
@endsection



