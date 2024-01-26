/* =================================
 ------------------------------------
 Unica - University Template
 Version: 1.0
 ------------------------------------ 
 ====================================*/


'use strict';


var window_w = $(window).innerWidth();

$(window).on('load', function () {
    /*------------------
     Preloder
     --------------------*/
    $(".loader").fadeOut();
    $("#preloder").delay(400).fadeOut("slow");

});

(function ($) {

    /*------------------
     Navigation
     --------------------*/
    $('.nav-switch').on('click', function (event) {
        $('.main-menu').slideToggle(400);
        event.preventDefault();
    });


    /*------------------
     Background set
     --------------------*/
    $('.set-bg').each(function () {
        var bg = $(this).data('setbg');
        $(this).css('background-image', 'url(' + bg + ')');
    });


    /*------------------
     Hero Slider
     --------------------*/
    var window_h = $(window).innerHeight();
    var header_h = $('.header-section').innerHeight();
    var nav_h = $('.nav-section').innerHeight();

    if (window_w > 767) {
        $('.hs-item').height((window_h) - ((header_h) + (nav_h)));
    }

    $('.hero-slider').owlCarousel({
        loop: true,
        nav: false,
        dots: true,
        mouseDrag: false,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        items: 1,
        autoplay: true
    });


    /*------------------
     Counter
     --------------------*/
    $(".counter").countdown("2018/07/01", function (event) {
        $(this).html(event.strftime("<div class='counter-item'><h4>%D</h4>Days</div>" + "<div class='counter-item'><h4>%H</h4>hours</div>" + "<div class='counter-item'><h4>%M</h4>Mins</div>" + "<div class='counter-item'><h4>%S</h4>secs</div>"));
    });


    /*------------------
     Gallery
     --------------------*/
    $('.gallery').find('.gallery-item').each(function () {
        var pi_height1 = $(this).width(),
            pi_height2 = pi_height1 / 2;

        if ($(this).hasClass('gi-long') && window_w > 991) {
            $(this).css('height', pi_height2);
        } else {
            $(this).css('height', Math.abs(pi_height1));
        }
    });

    $('.gallery').masonry({
        itemSelector: '.gallery-item',
        columnWidth: '.grid-sizer'
    });


    /*------------------
     Testimonial
     --------------------*/
    $('.testimonial-slider').owlCarousel({
        loop: true,
        nav: true,
        dots: true,
        animateOut: 'fadeOutUp',
        animateIn: 'fadeInUp',
        navText: ['<i class="fa fa-angle-left"></i>', '</i><i class="fa fa-angle-right"></i>'],
        items: 1,
        autoplay: true
    });


    /*------------------
     Popup
     --------------------*/
    $('.img-popup').magnificPopup({
        type: 'image',
        mainClass: 'img-popup-warp',
        removalDelay: 400,
    });


    /*------------------
     Back to top
     --------------------*/

    // Set a variable for our button element.
    const scrollToTopButton = document.getElementById('js-top');

    // Let's set up a function that shows our scroll-to-top button if we scroll beyond the height of the initial window.
    const scrollFunc = () => {
        // Get the current scroll value
        let y = window.scrollY;

        // If the scroll value is greater than the window height, let's add a class to the scroll-to-top button to show it!
        if (y > 0) {
            scrollToTopButton.className = "top-link show";
        } else {
            scrollToTopButton.className = "top-link hide";
        }
    };

    window.addEventListener("scroll", scrollFunc);

    const scrollToTop = () => {
        // Let's set a variable for the number of pixels we are from the top of the document.
        const c = document.documentElement.scrollTop || document.body.scrollTop;

        // If that number is greater than 0, we'll scroll back to 0, or the top of the document.
        // We'll also animate that scroll with requestAnimationFrame:
        // https://developer.mozilla.org/en-US/docs/Web/API/window/requestAnimationFrame
        if (c > 0) {
            window.requestAnimationFrame(scrollToTop);
            // ScrollTo takes an x and a y coordinate.
            // Increase the '10' value to get a smoother/slower scroll!
            window.scrollTo(0, c - c / 10);
        }
    };

    // When the button is clicked, run our ScrolltoTop function above!
    scrollToTopButton.onclick = function (e) {
        e.preventDefault();
        scrollToTop();
    }


})(jQuery);

