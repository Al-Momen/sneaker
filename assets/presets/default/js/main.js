/* Main Js Start */

(function ($) {
  "use strict";

  $(document).ready(function () {


    // sidebar dropdown
    $(".has-dropdown > a").on("click", function (e) {
      e.preventDefault();
      var $submenu = $(this).next(".sidebar-submenu");
      var $parent = $(this).parent();
      if ($submenu.css("display") === "block") {
        $submenu.slideUp(200);
        $parent.removeClass("active");
      } else {
        $(".sidebar-submenu").not($submenu).slideUp(200);
        $(".has-dropdown.active").removeClass("active");
        $parent.addClass("active");
        $submenu.slideDown(200);
      }
    });

    $(".dashboard-body__bar-icon").on("click", function () {
      $(".sidebar-menu").addClass('show-sidebar');
      $(".sidebar-overlay").addClass('show');
    });
    $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
      $(".sidebar-menu").removeClass('show-sidebar');
      $(".sidebar-overlay").removeClass('show');
    });

    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
          $('#imagePreview').hide();
          $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    $("#imageUpload").change(function () {
      readURL(this);
    });

  });

  // preloader
  $(window).on("load", function () {
    $("#preloader").fadeOut();

  })


  // sticky header
  $(window).on('scroll', function () {
    if ($(window).scrollTop() >= 60) {
      $('.header').addClass('fixed-header');
    }
    else {
      $('.header').removeClass('fixed-header');
    }
  });



  $('.sidebar-overlay, .close-hide-show').on('click', function () {
    $('.sidebar-menu-wrapper').removeClass('show');
    $(".sidebar-overlay").removeClass('show');
  });




  // tap to top with progress

  if ($('.scroll-top').length > 0) {
    var $scrollTopBtn = $('.scroll-top');
    var $progressPath = $('.scroll-top path');
    var pathLength = $progressPath[0].getTotalLength();

    $progressPath.css({
      transition: 'none',
      strokeDasharray: pathLength + ' ' + pathLength,
      strokeDashoffset: pathLength,
    });

    $progressPath[0].getBoundingClientRect();
    $progressPath.css({
      transition: 'stroke-dashoffset 10ms linear'
    });

    var updateProgress = function () {
      var scroll = $(window).scrollTop();
      var height = $(document).height() - $(window).height();
      var progress = pathLength - (scroll * pathLength / height);
      $progressPath.css('strokeDashoffset', progress);
    };

    updateProgress();

    $(window).on('scroll', updateProgress);

    $(window).on('scroll', function () {
      if ($(this).scrollTop() > 50) {
        $scrollTopBtn.addClass('show');
      } else {
        $scrollTopBtn.removeClass('show');
      }
    });

    $scrollTopBtn.on('click', function (event) {
      event.preventDefault();
      $('html, body').animate({ scrollTop: 0 }, 800);
      return false;
    });
  }


  // swiper slider
  const testimonialSwiper = new Swiper('.testimonial-slider', {
    loop: true,
    slidesPerView: 1,
    allowTouchMove: true,
    spaceBetween: 10,
    speed: 600,


    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },


    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      425: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      575: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      640: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 2,
        spaceBetween: 30,
      },
      1440: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
    },
  });

  const reviewSwiper = new Swiper('.review--slider', {
    loop: true,
    slidesPerView: 1,
    allowTouchMove: true,
    spaceBetween: 20,
    speed: 600,

    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },

  });

  const featuredSwiper = new Swiper('.featured--slider', {
    slidesPerView: 1,
    allowTouchMove: true,
    spaceBetween: 10,
    speed: 600,


    navigation: {
      nextEl: ".featured-next",
      prevEl: ".featured-prev",
    },


    breakpoints: {

      425: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      575: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
      1440: {
        slidesPerView: 4,
        spaceBetween: 30,
      }
    },


  });

  const preownedSwiper = new Swiper('.preowned--slider', {
    slidesPerView: 1,
    allowTouchMove: true,
    spaceBetween: 10,
    speed: 600,


    navigation: {
      nextEl: ".preowned-next",
      prevEl: ".preowned-prev",
    },


    breakpoints: {

      425: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      575: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
      1440: {
        slidesPerView: 4,
        spaceBetween: 30,
      }
    },


  });



  // toggle show hide password
  $(".toggle-password-change").on('click', function () {
    var targetId = $(this).data("target");
    var target = $("#" + targetId);
    var icon = $(this);
    if (target.attr("type") === "password") {
      target.attr("type", "text");
      icon.removeClass("fa-eye-slash");
      icon.addClass("fa-eye");
    } else {
      target.attr("type", "password");
      icon.removeClass("fa-eye");
      icon.addClass("fa-eye-slash");
    }
  });



  // wow init
  new WOW().init();

  // text splitting
  Splitting();




  $(".image--popup").magnificPopup({
    type: "image",
    gallery: {
      enabled: true,
    },
  });

  $(".image--popup-group").magnificPopup({
    type: "image",
    delegate: 'a',
    gallery: {
      enabled: true,
    },
  });

  $('.popup__video').magnificPopup({
    type: 'iframe',
  });




  // gsap
  if ($('.hero-thumb--wrap').length > 0) {
    document.addEventListener("DOMContentLoaded", function () {
      if (window.innerWidth > 991) {

        gsap.registerPlugin(ScrollTrigger);
        gsap.fromTo(".hero-thumb--wrap",
          {
            y: -100,
            scale: 0.8
          },
          {
            y: 0,
            scale: 1,
            ease: "bounce.out",
            duration: 1.5
          }
        );



        gsap.fromTo(".hero-thumb--wrap",
          {
            x: 0,
            y: 0,
            rotate: 0
          },
          {
            x: -200,
            y: 200,
            rotate: 15,
            ease: "power2.out",
            scrollTrigger: {
              trigger: ".hero-ending--point",
              start: "top 90%",
              end: "bottom 0%",
              scrub: true
            }
          }
        );


        gsap.to(".hero-thumb--shadow", {
          x: -200,
          y: 40,

          ease: "power2.out",
          scrollTrigger: {
            trigger: ".hero-ending--point",
            start: "top 90%",
            end: "bottom 0%",
            scrub: true
          }
        });

      }


    });

  }


  // refer dropdown
  $(".caret").click(function () {
    $(this).toggleClass("caret-down");
    $(this).next(".nested").toggleClass("active");
  });


  // On page load
  $('.filter--group').each(function () {
    const $group = $(this);
    const $chevron = $group.find('.icon-chevron');
    const $wrap = $group.find('.filter-item--wrap');

    if ($group.hasClass('show')) {
      $chevron.removeClass('is--down').addClass('is--up');
      $wrap.css('height', 'auto');
    } else {
      $chevron.removeClass('is--up').addClass('is--down');
      $wrap.css('height', '0');
    }
  });


  // filter group hide show
  $('.filter--group .title--wrap').on('click', function () {
    const parent = $(this).closest('.filter--group');

    parent.toggleClass('show');
    parent.find('.icon-chevron').toggleClass('is--up is--down');

    const wrap = parent.find('.filter-item--wrap');

    if (wrap.height() === 0) {
      let fullHeight = wrap.get(0).scrollHeight;
      wrap.css('height', fullHeight + 'px');
      setTimeout(() => {
        wrap.css('height', 'auto');
      }, 150);
    } else {
      wrap.css('height', wrap.height() + 'px');
      setTimeout(() => {
        wrap.css('height', '0');
      }, 10);
    }
  });


  // hero thumb hover animation
  $('.hero-thumb--wrap').hover(
    function () {
      $('.shoe-thumb--one')
        .stop(true)
        .animate({ opacity: 0 }, 300, function () {
          $(this).css('visibility', 'hidden');
        });

      $('.shoe-thumb--two')
        .css('visibility', 'visible')
        .stop(true)
        .animate({ opacity: 1 }, 300);
    },
    function () {
      $('.shoe-thumb--two')
        .stop(true)
        .animate({ opacity: 0 }, 300, function () {
          $(this).css('visibility', 'hidden');
        });

      $('.shoe-thumb--one')
        .css('visibility', 'visible')
        .stop(true)
        .animate({ opacity: 1 }, 300);
    }
  );



  // in details page select color and product view
  $(document).ready(function () {
    const mainImg = $("#productImgSrc");

    function changeImage(newSrc) {
      mainImg.fadeOut(100, function () {
        mainImg.attr("src", newSrc).fadeIn(100);
      });
    }

    $(".item-gallery__image-wrapper img").on("click", function () {
      changeImage($(this).attr("src"));
    });

    $(".form-radio-input").on("change", function () {
      let selectedColor = $(this).val();
      let selectedImage = $(`.item-gallery__image-wrapper img[data-category="${selectedColor}"]`);
      if (selectedImage.length) {
        changeImage(selectedImage.attr("src"));
      }
    });


    // zoom product image and move on mouse
    const mainImgs = $("#productImgSrc");
    $(".main--thumb__preview").on("mousemove", function (event) {
      let preview = $(this);
      let offset = preview.offset();
      let x = (event.pageX - offset.left) / preview.width() * 100;
      let y = (event.pageY - offset.top) / preview.height() * 100;

      mainImgs.css("transform", `scale(2) translate(${(50 - x) / 2}%, ${(50 - y) / 2}%)`);
    });

    $(".main--thumb__preview").on("mouseleave", function () {
      mainImgs.css("transform", "scale(1) translate(0, 0)");
    });


    // select product image
    $(".item-gallery__image-wrapper").on("click", function () {
      $(".item-gallery__image-wrapper").removeClass("active");
      $(this).addClass("active");
    });

  });


  // header search toggle
  $(document).ready(function () {
    $('.search-toggle--btn').on('click', function (e) {
      e.stopPropagation();
      $('.search--bar__wrap').toggleClass('active');
      $('.search-box--wrap').toggleClass('active');
      $('.sidebar-overlay').toggleClass('show');


    });

    $(document).on('click', function (e) {
      if (!$(e.target).closest('.search--bar__wrap, .search-toggle--btn, .search-box--wrap').length) {
        $('.search--bar__wrap').removeClass('active');
        $('.search-box--wrap').removeClass('active');
      }
    });
  });


  // image drag and drop
  $(document).ready(function () {
    var images = [];

    function selectFiles() {
      $("#fileInput").click();
    }

    $(".selectFiles").click(selectFiles);

    function onFileSelect(event) {
      const files = event.target.files;
      if (files.length === 0) return;

      for (let i = 0; i < files.length; i++) {
        if (files[i].type.split('/')[0] !== 'image') continue;
        if (!images.some((e) => e.name == files[i].name)) {
          images.push({
            name: files[i].name,
            url: URL.createObjectURL(files[i])
          });
        }
      }
      updateImages();
    }

    function deleteImage(index) {
      images.splice(index, 1);
      updateImages();
    }

    function updateImages() {
      $("#containerArea").empty();
      images.forEach(function (image, index) {
        var deleteButton = $('<span class="delete"><i class="fa-solid fa-xmark"></i></span>');
        deleteButton.click(function () {
          deleteImage(index);
        });

        var imageDiv = $('<div class="image"></div>')
          .append(deleteButton)
          .append($('<img src="' + image.url + '" alt="..."/>'));
        $("#containerArea").append(imageDiv);
      });
    }

    function onDragOver(event) {
      event.preventDefault();
      $("#dragArea").addClass("isDragging");
      event.originalEvent.dataTransfer.dropEffect = "copy";
    }

    function onDragLeave(event) {
      event.preventDefault();
      $("#dragArea").removeClass("isDragging");
    }

    function onDrop(event) {
      event.preventDefault();
      $("#dragArea").removeClass("isDragging");
      const files = event.originalEvent.dataTransfer.files;

      for (let i = 0; i < files.length; i++) {
        if (files[i].type.split('/')[0] !== 'image') continue;
        if (!images.some((e) => e.name == files[i].name)) {
          images.push({
            name: files[i].name,
            url: URL.createObjectURL(files[i])
          });
        }
      }
      updateImages();
    }

    $("#fileInput").change(onFileSelect);
    $("#dragArea").on("dragover", onDragOver).on("dragleave", onDragLeave).on("drop", onDrop);
  });


  $(".filter--btn").on("click", function () {
    $(".filter--box").toggleClass("d-block");
  });



})(jQuery);


if ($('.range-input input').length > 0) {

  // price range
  const rangeInput = document.querySelectorAll(".range-input input");
  const minDisplay = document.querySelector(".price-input .input-min");
  const maxDisplay = document.querySelector(".price-input .input-max");
  const progress = document.querySelector(".sliderr .progresss");

  let priceGap = 100;

  function updateSliderUI() {
    let minValue = parseInt(rangeInput[0].value);
    let maxValue = parseInt(rangeInput[1].value);

    minDisplay.textContent = minValue;
    maxDisplay.textContent = maxValue;


    progress.style.left = (minValue / rangeInput[0].max) * 100 + "%";
    progress.style.right = 100 - (maxValue / rangeInput[1].max) * 100 + "%";
  }

  rangeInput.forEach(input => {
    input.addEventListener("input", e => {
      let minValue = parseInt(rangeInput[0].value);
      let maxValue = parseInt(rangeInput[1].value);

      if (maxValue - minValue < priceGap) {
        if (e.target.classList.contains("range-min")) {
          rangeInput[0].value = maxValue - priceGap;
        } else {
          rangeInput[1].value = minValue + priceGap;
        }
      }
      updateSliderUI();
    });
  });

  updateSliderUI();
}








