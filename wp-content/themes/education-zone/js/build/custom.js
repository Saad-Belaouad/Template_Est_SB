jQuery(document).ready(function($) {

    var rtl;
    
    if (education_zone_data.rtl == '1') {
        rtl = true;
    } else {
        rtl = false;
    }

    $('.testimonial-slide').owlCarousel({
        //mode: "slide",
        items: 1,
        mouseDrag: false,
        dots: false,
        nav: true,
        rtl: rtl,
    });

    $('.number').counterUp({
        delay: 10,
        time: 5000
    });

    $('.photo-gallery .gallery').addClass('owl-carousel');

    $(".photo-gallery .gallery").owlCarousel({
        items: 5,
        autoplay: false,
        loop: false,
        nav: true,
        dots: false,
        rtl: false,
        autoHeight: true,
        autoHeightClass: 'owl-height',
        mouseDrag: false,
        responsive: {
            0: {
                items: 2,
            },
            641: {
                items: 3,
            },
            768: {
                items: 4,
            },
            981: {
                items: 5,
            }
        }
    });

    //mobile menu
    $('.mobile-menu .main-navigation ul .menu-item-has-children').append('<div class="angle-down"></div>');
    $('.mobile-menu .main-navigation ul li .angle-down').click(function() {
        $(this).prev().slideToggle();
        $(this).toggleClass('active');
    });

    $('.mobile-menu .secondary-nav ul .menu-item-has-children').append('<div class="angle-down"></div>');
    $('.mobile-menu .secondary-nav ul li .angle-down').click(function() {
        $(this).prev().slideToggle();
        $(this).toggleClass('active');
    });

    $('.mobile-header .menu-opener').click(function() {
        $('body').addClass('menu-open');
    });

    $('.mobile-menu').prepend('<div class="btn-close-menu"></div>');
    $('.btn-close-menu').click(function() {
        $('body').removeClass('menu-open');
    });
    $('.footer-overlay').click(function() {
        $('body').removeClass('menu-open');
    });

    //accessibility menu
    $("#site-navigation ul li a").focus(function(){
       $(this).parents("li").addClass("focus");
   }).blur(function(){
       $(this).parents("li").removeClass("focus");
   });

   $("#secondary-navigation > a").focus(function(){
       $(this).parents("#secondary-navigation").addClass("focus");
   }).blur(function(){
       $(this).parents("#secondary-navigation").removeClass("focus");
   });

   $("#secondary-navigation ul li a").focus(function(){
       $(this).parents("#secondary-navigation").addClass("focus");
   }).blur(function(){
       $(this).parents("#secondary-navigation").removeClass("focus");
   });

   $("#secondary-navigation ul li a").focus(function(){
       $(this).parents("li").addClass("focus");
   }).blur(function(){
       $(this).parents("li").removeClass("focus");
   });
   
});
