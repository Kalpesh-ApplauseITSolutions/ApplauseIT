
  $(function(){
    $("#header").load("header.html");
    $("#footer").load("footer.html");
  });


(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();
    $(document).ready(function () {
      // Initialize RTL (Right to Left) Carousel
      $(".rtl-carousel").owlCarousel({
        loop: true,
        rtl: true,
        margin: 30,
        autoplay: true,
        autoplayTimeout: 2500,
        autoplayHoverPause: false,
        smartSpeed: 1200,
        nav: false,
        dots: false,
        responsive: {
          0: { items: 2 },
          576: { items: 3 },
          768: { items: 4 },
          992: { items: 5 }
        }
      });

      // Initialize LTR (Left to Right) Carousel
      $(".ltr-carousel").owlCarousel({
        loop: true,
        rtl: false,
        margin: 30,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: false,
        smartSpeed: 1200,
        nav: false,
        dots: false,
        responsive: {
          0: { items: 2 },
          576: { items: 3 },
          768: { items: 4 },
          992: { items: 5 }
        }
      });

      // Refresh carousels on window resize
      $(window).on("resize", function () {
        $(".owl-carousel").trigger("refresh.owl.carousel");
      });
    });
    // Initialize tilt.js
    $('.stats-card').tilt({
      glare: true,
      maxGlare: 0.2,
      reset: false
    });
    // Initiate the wowjs
    new WOW().init();

    $(document).ready(function(){
        $('.stats-card').tilt({
            glare: true,
            maxGlare: 0.2,
            reset: false
        });
    });
    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $('.navbar').addClass('sticky-top shadow-sm');
        } else {
            $('.navbar').removeClass('sticky-top shadow-sm');
        }
    });
    // Dropdown on mouse hover
    const $dropdown = $(".dropdown");
    const $dropdownToggle = $(".dropdown-toggle");
    const $dropdownMenu = $(".dropdown-menu");
    const showClass = "show";
    
    $(window).on("load resize", function() {
        if (this.matchMedia("(min-width: 992px)").matches) {
            $dropdown.hover(
            function() {
                const $this = $(this);
                $this.addClass(showClass);
                $this.find($dropdownToggle).attr("aria-expanded", "true");
                $this.find($dropdownMenu).addClass(showClass);
            },
            function() {
                const $this = $(this);
                $this.removeClass(showClass);
                $this.find($dropdownToggle).attr("aria-expanded", "false");
                $this.find($dropdownMenu).removeClass(showClass);
            }
            );
        } else {
            $dropdown.off("mouseenter mouseleave");
        }
    });


    // Facts counter
    $('[data-toggle="counter-up"]').counterUp({
        delay: 10,
        time: 2000
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        dots: true,
        loop: true,
        center: true,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            }
        }
    });


    // Vendor carousel
    $('.vendor-carousel').owlCarousel({
        loop: true,
        margin: 45,
        dots: false,
        loop: true,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0:{
                items:2
            },
            576:{
                items:4
            },
            768:{
                items:6
            },
            992:{
                items:8
            }
        }
    });
    
})(jQuery);

// <!-- Owl Carousel Initialization -->
  $(document).ready(function(){
    $(".rtl-carousel").owlCarousel({
      items: 4,
      loop: true,
      margin: 10,
      autoplay: true,
      autoplayTimeout: 2000,
      autoplayHoverPause: true,
      rtl: true,
      responsive: {
        0: { items: 2 },
        600: { items: 3 },
        1000: { items: 4 }
      }
    });
    $(".normal-carousel").owlCarousel({
      items: 4,
      loop: true,
      margin: 10,
      autoplay: true,
      autoplayTimeout: 2500,
      autoplayHoverPause: true,
      rtl: false,
      responsive: {
        0: { items: 2 },
        600: { items: 3 },
        1000: { items: 4 }
      }
    });
  });

  $(document).ready(function(){
    // Initialize RTL (Right to Left) Carousel
    $(".rtl-carousel").owlCarousel({
      loop: true,
      rtl: true,
      margin: 30,
      autoplay: true,
      autoplayTimeout: 2500,
      autoplayHoverPause: false,
      smartSpeed: 1200,
      nav: false,
      dots: false,
      responsive: {
        0: { items: 2 },
        576: { items: 3 },
        768: { items: 4 },
        992: { items: 5 }
      }
    });

    // Initialize LTR (Left to Right) Carousel
    $(".ltr-carousel").owlCarousel({
      loop: true,
      rtl: false,
      margin: 30,
      autoplay: true,
      autoplayTimeout: 3000,
      autoplayHoverPause: false,
      smartSpeed: 1200,
      nav: false,
      dots: false,
      responsive: {
        0: { items: 2 },
        576: { items: 3 },
        768: { items: 4 },
        992: { items: 5 }
      }
    });

    // Refresh carousels on window resize
    $(window).on("resize", function(){
      $(".owl-carousel").trigger("refresh.owl.carousel");
    });
  });    