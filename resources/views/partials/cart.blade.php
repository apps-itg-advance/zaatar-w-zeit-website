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
                    //$(".col-cartitems").html(data);
                }
            });
        }


    </script>
@endsection



