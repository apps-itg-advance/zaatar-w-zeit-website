<script src="{{asset('assets/js/jquery.matchHeight-min.js')}}"></script>
<script type="text/javascript">
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
    function CustomizeItem() {
        $("#ItemModify").val(1);
    }

    function OpenModel(id) {
        $("#SItemId").val(id);
        var qty=$("#qty_"+id).val();
        $("#SQty").val(qty);
        jQuery('#cartbig-modal-'+id).modal();
        return false;
    }
    function MakeMealModel(id) {
        var hasM= $("#MakeMeal"+id).val();
        $("#SItemId").val(id);
        var qty=$("#qty_"+id).val();
        $("#SQty").val(qty);
        if(hasM >0){
            jQuery('#makeMeal-modal'+id).modal();
        }
        else{
            AddToCart(id,1);
        }
        return false;
    }
    function SubmitCustomize(id) {

        AddToCart(id,0);
    }
    function CustomizedLabel(item_id) {
        var ItemMCount=parseInt($('.Item'+item_id+':checked').length);
        if(ItemMCount>0)
        {
            $('#CustomizedLink'+item_id).addClass("active");
            $('#Customize'+item_id).html("Customized");
        }


    }
    function CalculateTotal(cat_id,max_qty,id,item_id) {
        var ItemId="qty_"+item_id;
        var currentQty=parseInt($("#"+ItemId).val());
        if(currentQty==0)
        {
            currentQty=1;
        }
        var CheckId='Modifier'+id;
        var GroupCss='m-'+cat_id+'-'+item_id;
        var GCount=parseInt($('.'+GroupCss+':checked').length);
        if(max_qty >0 && GCount>max_qty)
        {
            Swal.fire({
                // position: 'top-end',
                icon: 'warning',
                title: 'You can\'t select more than '+max_qty+' option',
                showConfirmButton: false,
                timer: 1200
            });
            $("#"+CheckId).prop('checked', false);
            return false;
        }
        else{
            var mVal=$("#"+CheckId).val();
            var res = mVal.split("-");
            // var mPrice=parseFloat(res[2])*currentQty;
            var mPrice=parseFloat(res[2]);
            if($("#"+CheckId).is(':checked'))
            {

                var nTotal=parseFloat($("#TotalAmount"+item_id).val())+mPrice;
            }
            else{
                var nTotal=parseFloat($("#TotalAmount"+item_id).val())-mPrice;
            }
            $("#TotalAmount"+item_id).val(nTotal);
            $("#DisplayTotal"+item_id).text(formatNumber(nTotal)+' LBP');
        }
    }
    function CalculateMakeMealTotal(id,item_id) {
        var CheckId='makeMealH'+id;

        var mVal=$("#"+CheckId).val();
        var res = mVal.split("-");
        if($("#"+CheckId).is(':checked'))
        {
            $(".Subd"+id).removeAttr("disabled");

            var nTotal=parseFloat($("#TotalAmount"+item_id).val())+parseFloat(res[1]);
        }
        else{
            $(".Subd"+id).prop("checked", false);
            $(".Subd"+id).attr("disabled", true);
            var nTotal=parseFloat($("#TotalAmount"+item_id).val())-parseFloat(res[1]);
        }
        $("#TotalAmount"+item_id).val(nTotal);
        $("#DisplayTotal"+item_id).text(formatNumber(nTotal)+' {{$currency}}');
    }
    function CalculateMakeMealTotalQ(id,item_id) {
        var CheckId='makeMealL'+id;

        var mVal=$("#"+CheckId).val();
        var res = mVal.split("-");

        if($("#"+CheckId).is(':checked'))
        {
            $(".Sub"+id).removeAttr("disabled");
            var nTotal=parseFloat($("#TotalAmountQ"+item_id).val())+parseFloat(res[1]);
        }
        else{
            $(".Sub"+id).prop("checked", false);
            $(".Sub"+id).attr("disabled", true);
            var nTotal=parseFloat($("#TotalAmountQ"+item_id).val())-parseFloat(res[1]);
        }
        $("#TotalAmountQ"+item_id).val(nTotal);
        $("#DisplayTotalQ"+item_id).text(formatNumber(nTotal)+' {{$currency}}');
    }
    function AddQty(id) {
        var hasM = $("#MakeMeal"+id).val();
        if(hasM==0){
            // loader('show');
            var spinnerContainerElement = $("button[data-code='" + id + "']").closest('.item-plus-minus');
            spinner('show', spinnerContainerElement);
            $("button[data-code='" + id + "']").prop('disabled',true);
        }
        var currentTotal=parseFloat($("#TotalAmount"+id).val());
        var ItemId="qty_"+id;
        var currentQty=parseInt($("#"+ItemId).val());
        var newQty=currentQty+1;
        $("#"+ItemId).val(newQty);
        var newTotal=currentTotal;
        // $("#TotalAmount"+id).val(newTotal);
        $("#QuickOrder").val('1');
        $("#DisplayTotal"+id).text(formatNumber(newTotal)+' LBP');
        MakeMealModel(id);
    }
    function SubQty(id,plu) {
        //sp spinner
        var spinnerContainerElement = $("button[data-code='" + id + "']").closest('.item-plus-minus');
        spinner('show', spinnerContainerElement);

        var currentTotal=parseFloat($("#TotalAmount"+id).val());
        var ItemId="qty_"+id;

        var currentQty=parseInt($("#"+ItemId).val());
        if(currentQty >0)
        {
            var newQty=currentQty-1;
            $("#"+ItemId).val(newQty);
            var newTotal=currentTotal*newQty;
            //  $("#TotalAmount"+id).val(newTotal);
            $("#DisplayTotal"+id).text(formatNumber(newTotal)+' LBP');
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            url:'{{route('carts.remove')}}',
            data:{id:plu},
            success:function(data){
                _getCountCartItems();
                LoadCart();
                var res = data.split("-");
                if(res[1]>0)
                {
                    $('#CustomizedLink'+id).addClass("active");
                    $('#Customize'+id).html("Customized");
                }
                else{
                    $('#CustomizedLink'+id).removeClass("active");
                    $('#Customize'+id).html("Customize");
                }
                spinner('hide', spinnerContainerElement);
            }
        });
        spinner('hide', spinnerContainerElement);
    }
    function AddToCart(id,quick)
    {
        var spinnerContainerElement = $("button[data-code='" + id + "']").closest('.item-plus-minus');
        $("#QuickOrder"+id).val(quick);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            url:'{{route('carts.store')}}',
            data:$("#Form"+id).serialize(),
            success:function(data){
                _getCountCartItems();
                LoadCart();
                jQuery('.cartbig-modal').modal('hide');
                $("button[data-code='" + id + "']").prop('disabled',false);
                // loader('hide');
                var res = data.split("-");
                $('#qty_'+id).val(res[0]);
                if(res[1]>0)
                {
                    $('#CustomizedLink'+id).addClass("active");
                    $('#Customize'+id).html("Customized");
                }
                else{
                    $('#CustomizedLink'+id).removeClass("active");
                    $('#Customize'+id).html("Customize");
                }
                spinner('hide', spinnerContainerElement);
            }
        });

    }
    $('#Form').on('submit', function(event){
        // event.preventDefault();
        //   AddToCart(0);

    });
</script>