

jQuery(document).ready( function () {

    //new Mmenu( document.querySelector( '#mobile-menu' ));

    jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop() > 80) {
            //jQuery('.header-wrapper').addClass("header-wrapper-fixed");
        } else {
            //jQuery('.header-wrapper').removeClass("header-wrapper-fixed");
        }
    });

});
