@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
@endsection
@section('content')


    <div class="col-xl-10 col-lg-11 col-md-11 col-sm-12 float-none p-0 mx-auto">
        <div class="title-div text-center mb-4">
            <h2 class="title">Online Credit Card Payment</h2>
        </div>
        @include('partials.checkout_bread')
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-10 float-none p-0 mx-auto item-summary wallet-wrapper">
            <div class="title-div">
                <h2 class="title">Credit Cards</h2>
            </div>
            <div id="wallet-carousel" class="owl-carousel wallet-carousel wallet2-carousel mb-3">
                @foreach($cards as $card)
                    @php
                        $css='';
                        if($card->Brand=='MASTERCARD')
                        {
                            $css='master';
                        }
                        elseif($card->Brand=='VISA')
                        {
                            $css='visa';
                        }
                    $month=substr($card->Expiry,0,2);
                    $year=substr($card->Expiry,2,2);
                    @endphp
                <div class="item credit-card-{{$css}}" data-mh="matchHeight" id="out-{{$card->Token}}">
                    <div class="item-div text-white p-3" id="in-{{$card->Token}}">
                        <div class="card-code">{{$card->Card}}</div>
                        <div class="clearfix"></div>
                        <div class="card-date">Valid Thru<br>{{$month.' / '.$year}}</div>
                        <div class="buttons text-center mt-3">
                            <a href="javascript:void(0)" onclick="SelectCard({{$card->Token}})" class="btn btn-redeem active text-uppercase">Pay with this card</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="action-buttons text-center pt-4">
                <input type="hidden" name="token_card" id="TokenCard">
                <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm">Confirm</button>
                <button type="button" class="btn btn-B3B3B3 text-uppercase new-card">Pay with a new card</button>

            </div>
        </div>
    </div>
    <div class="OrderSummary modal fade" id="OrderSummary" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div id="OrderSummaryDisplay"></div>
    </div>
@endsection
@section('javascript')
    <script src="{{asset('assets/js/owl.carousel.min.js')}}"></script>

    <script type="text/javascript">

        var windowHeight = jQuery(window).height();
        var headerHeight = jQuery('.header-wrapper').height();
        var contentHeight = jQuery('.content-container').height();
        var finalheight = windowHeight;
        if(contentHeight < finalheight) {
            jQuery(".content-container").css("min-height", windowHeight+50);
        }

        /*jQuery(".cart-dropdown").css("height", windowHeight - headerHeight - 10);
        jQuery(".cart-dropdown").css("overflow-y", 'scroll');*/

        jQuery('.wallet-carousel').owlCarousel({
            loop : false,
            navText : ['', ''],
            margin : 40,
            dots : false,
            nav : true,
            responsive:{
                0:{
                    items:1
                },
                575:{
                    items:1
                },
                767:{
                    items:2
                },
                991:{
                    items:1
                },
                1200:{
                    items:2
                },
                1500:{
                    items:2
                }
            }
        });
        function SelectCard(card)
        {
            var old=$('#TokenCard').val();
            if(old==card)
            {
                $('#TokenCard').val('');
                $("#out-"+old).removeClass("border-green");
                $("#in-"+old).removeClass("border-white");
            }
            else{
                $('#TokenCard').val(card);
                $("#out-"+old).removeClass("border-green");
                $("#in-"+old).removeClass("border-white");
                $("#out-"+card).addClass("border-green");
                $("#in-"+card).addClass("border-white");
            }

        }
        function SubmitData(card,that)
        {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {card: card},
                url: '{{route('checkout.card.store')}}',
                success: function (data) {
                    if(data=='error')
                    {
                        Swal.fire({
                            title: 'Warning!',
                            text: 'You must choose a card!',
                            icon: 'warning',
                            confirmButtonText: 'Close'
                        });
                    }
                    else{
                        spinnerButtons('hide', $(that));
                        $("#OrderSummaryDisplay").html(data);
                        jQuery('#OrderSummary').modal();
                    }

                }
            });
        }
        $(".confirm").click(function(){
            spinnerButtons('show', $(this));
            var that = this;
            var card = $('#TokenCard').val();
            if(card=='')
            {
                Swal.fire({
                    title: 'Warning!',
                    text: 'You must choose a card!',
                    icon: 'warning',
                    confirmButtonText: 'Close'
                });
                spinnerButtons('hide', $(this));
                return null;

            }
            else{
                SubmitData(card,that);
            }
        });
        $(".new-card").click(function(){
            spinnerButtons('show', $(this));
            var that = this;
            SubmitData('',that);

        });
    </script>
@endsection