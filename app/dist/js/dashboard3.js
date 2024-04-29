$(function () {
    //
    // Carousel
    if(document.querySelector('.collectibles-carousel')){
      $(".collectibles-carousel").owlCarousel({
        loop: true,
        margin: 30,
        mouseDrag: true,
        autoplay: true,
        autoplayTimeout: 4000,
        autoplaySpeed: 2000,
        nav: false,
        dots: false,
        rtl: false,
        
  
        // items:10,   
        //   itemsDesktop : [1199,10],
        //   itemsDesktopSmall : [980,9],
        //   itemsTablet: [768,5],
        //   itemsTabletSmall: false,
        //   itemsMobile : [479,4],
        responsive: {
          0: {
            items: 1,
          },
          576: {
            items: 1,
          },
          768: {
            items: 2,
          },
        },
      });
    }
   

    $(".counter-carousel").owlCarousel({
      loop: true,
      margin: 30,
      mouseDrag: true,
      autoplay: true,
      autoplayTimeout: 4000,
      autoplaySpeed: 2000,
      nav: false,
      rtl: true,
      responsive: {
        0: {
          items: 2,
        },
        576: {
          items: 2,
        },
        768: {
          items: 3,
        },
        1200: {
          items: 5,
        },
        1400: {
          items: 6,
        },
      },
    });


    $(".counter-carousel-withdraw").owlCarousel({
      loop: true,
      margin: 30,
      mouseDrag: true,
      autoplay: true,
      autoplayTimeout: 4000,
      autoplaySpeed: 2000,
      nav: false,
      rtl: true,
      responsive: {
        0: {
          items: 1,
        },
        576: {
          items: 1,
        },
        768: {
          items: 4,
        },
      },
    });


});