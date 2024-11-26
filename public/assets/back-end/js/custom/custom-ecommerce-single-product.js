/*
---------------------------------------
    : Custom - Sinlge Product js :
---------------------------------------
*/
"use strict";
$(document).ready(function() {
    $('.products-box-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        centerMode: true,
        draggable: false,
        asNavFor: '.products-box-nav',
        focusOnChange: true,
        autoplay: false,
    });
    $('.products-box-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.products-box-for',
        dots: false,
        arrows: false,
        centerMode: true,
        draggable: false,
        focusOnSelect: true,
        autoplay: false,
        autoplaySpeed: 3000,
        responsive: [
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 3
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 2
              }
            }
          ]
    });
});
