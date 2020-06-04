@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
@endsection
@section('content')


    <div class="col-xl-10 col-lg-11 col-md-11 col-sm-12 float-none p-0 mx-auto">
        <div class="title-div text-center mb-4">
            <h2 class="title">@lang('online_credit_card_payment')</h2>
        </div>
        @include('partials.checkout_bread')
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-10 float-none p-0 mx-auto item-summary wallet-wrapper">
            <div class="title-div">
                <h2 class="title">@lang('credit_cards')</h2>
            </div>


            <div class="variable-width mb-3">
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
                    <div class="item credit-card-{{$css}} out-{{$card->Token}}" data-mh="matchHeight" id="out-{{$card->Token}}">
                        <div class="item-div text-white p-3 in-{{$card->Token}}" id="in-{{$card->Token}}">
                            <a href="javascript:void(0)" onclick="DeleteCards({{$card->Id}})" class="d-inline-block"><img src="{{asset('assets/images/icon-checkout-close.png')}}" /></a>

                            <div class="card-code">{{$card->Card}}</div>
                            <div class="clearfix"></div>
                            <div class="card-date">Valid Thru<br>{{$month.' / '.$year}}</div>
                            <div class="buttons text-center mt-3">
                                <a href="javascript:void(0)" onclick="SelectCard('{{$card->Token}}')" class="btn btn-redeem active text-uppercase">@lang('pay_with_this_card')</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


            <div class="action-buttons text-center pt-4">
                <input type="hidden" name="token_card" id="TokenCard">
                <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm">@lang('confirm')</button>
                <button type="button" class="btn btn-B3B3B3 text-uppercase new-card">@lang('pay_with_a_new_card')</button>

            </div>
        </div>
    </div>
    <div class="OrderSummary modal fade" id="OrderSummary" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div id="OrderSummaryDisplay"></div>
    </div>
@endsection
@section('javascript')
    <script src="{{asset('assets/js/owl.carousel.min.js')}}"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script type="text/javascript">
        var countrylang = $('html').attr('lang');
	    $('.variable-width').slick({
            rtl:countrylang === 'ar'? true: false,
		    autoplay: false,
		    dots: false,
		    infinite: false,
		    speed: 300,
            // slidesToShow:countrylang === 'ar'? -1: 1,
            // slidesToShow: 1,
		    centerMode: false,
		    variableWidth: true,
		    responsive: [
			    {
				    breakpoint: 768,
				    settings: {
					    arrows: false,
					    centerMode: true,
					    centerPadding: '40px',
					    slidesToShow: 3
				    }
			    },
		    ]
	    });







        var windowHeight = jQuery(window).height();
        var headerHeight = jQuery('.header-wrapper').height();
        var contentHeight = jQuery('.content-container').height();
        var finalheight = windowHeight;
        if(contentHeight < finalheight) {
            jQuery(".content-container").css("min-height", windowHeight+50);
        }
        function SelectCard(card)
        {

            var old=$('#TokenCard').val();

            if(old==card)
            {
                $('#TokenCard').val('');
                $(".out-"+old).removeClass("border-green");
                $(".in-"+old).removeClass("border-white");
            }
            else{
                //alert(card);
                $('#TokenCard').val(card);
                if(old!='')
                {
                    $(".out-"+old).removeClass("border-green");
                    $(".in-"+old).removeClass("border-white");
                }

                $(".out-"+card).addClass("border-green");
                $(".in-"+card).addClass("border-white");
            }

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
                    items:1
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
                            title: '<?php echo app('translator')->get('warning!'); ?>',
                            text: '<?php echo app('translator')->get('you_must_choose_card!'); ?>',
                            icon: 'warning',
                            confirmButtonText: '<?php echo app('translator')->get('close'); ?>'
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
                    title: '<?php echo app('translator')->get('warning!'); ?>',
                    text: '<?php echo app('translator')->get('you_must_choose_card!'); ?>',
                    icon: 'warning',
                    confirmButtonText: '<?php echo app('translator')->get('close'); ?>'
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
        function DeleteCards(id) {
            Swal.fire({
                title: '<?php echo app('translator')->get('are_you_sure?'); ?>',
                text: "<?php echo app('translator')->get('you_wont_be_able_revert_this!'); ?>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?php echo app('translator')->get('yes_delete_it!'); ?>'
            }).then((result) => {
                if (result.value) {
                    window.location = '{{route('credit.cards.delete')}}'+'/'+id;
                }
            })
        }
    </script>
@endsection