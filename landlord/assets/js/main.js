(function($) {
    "use strict";

    /*
    |--------------------------------------------------------------------------
    | Template Name:Dreamwell
    | Author: Laralink
    | Version: 1.0.0
    |--------------------------------------------------------------------------
    |--------------------------------------------------------------------------
    | TABLE OF CONTENTS:
    |--------------------------------------------------------------------------
    |
    | 01. Preloader
    | 02. Mobile Menu
    | 03. Sticky Header
    | 04. Dynamic Background
    | 05. Accordian
    | 06. Review
    | 07. Tabs
    | 08. Slick Slider
    | 09. Counter Animation
    | 10. E-commerce
    | 11. Property View
    | 12. Light Gallery
    | 13. Modal Video
    | 14. Scroll Up
    | 15. Load More Portfolio Items
    | 16. Round Percent
    | 17. Smooth Page Scroll
    */

    /*===============================================================
      Scripts initialization
    =================================================================*/
    $.exists = function(selector) {
        return $(selector).length > 0;
    };

    $(window).on("load", function() {
        preloader();
    });

    $(function() {
        mainNav();
        stickyHeader();
        dynamicBackground();
        themeMode();
        accordian();
        review();
        tabs();
        slickInit();
        counterInit();
        ecommerce();
        listAndGridView();
        lightGallery();
        modalVideo();
        scrollUp();
        loadMore();
        roundPercentInit();
        smoothScroll();
        if ($.exists(".wow")) {
            new WOW().init();
        }
    });
    $(window).on("scroll", function() {
        showScrollUp();
        stickyHeader();
    });
    $(window).on("resize", function() {
        slickInit();
    });
    /*===============================================================
      1. Preloader
    =================================================================*/
    function preloader() {
        $(".cs_preloader").fadeOut();
        $("cs_preloader_in").delay(150).fadeOut("slow");
    }

    /*===============================================================
      2. Mobile Menu
    =================================================================*/
    function mainNav() {
        $(".cs_nav").append('<span class="cs_menu_toggle"><span></span></span>');
        $(".menu-item-has-children").append(
            '<span class="cs_menu_dropdown_toggle"><span></span></span>'
        );
        $(".cs_menu_toggle").on("click", function() {
            $(this)
                .toggleClass("cs_toggle_active")
                .siblings(".cs_nav_list")
                .toggleClass("cs_active");
        });
        $(".cs_menu_dropdown_toggle").on("click", function() {
            $(this).toggleClass("active").siblings("ul").slideToggle();
            $(this).parent().toggleClass("active");
        });
    }
    /*===============================================================
      3. Sticky Header
    =================================================================*/
    function stickyHeader() {
        var scroll = $(window).scrollTop();
        if (scroll >= 10) {
            $(".cs_sticky_header").addClass("cs_sticky_active");
        } else {
            $(".cs_sticky_header").removeClass("cs_sticky_active");
        }
    }

    /*===============================================================
      4. Dynamic Background
    =================================================================*/
    function dynamicBackground() {
        $("[data-src]").each(function() {
            var src = $(this).attr("data-src");
            $(this).css({
                "background-image": "url(" + src + ")",
            });
        });
    }
    /*===============================================================
      5. Theme Mode
    =================================================================*/
    function themeMode() {
        var storageKey = "dreamwell-theme";
        var root = document.documentElement;
        var mediaQuery =
            typeof window.matchMedia === "function" ?
            window.matchMedia("(prefers-color-scheme: dark)") :
            null;

        function readStoredTheme() {
            try {
                return localStorage.getItem(storageKey);
            } catch (error) {
                return null;
            }
        }

        function writeStoredTheme(theme) {
            try {
                localStorage.setItem(storageKey, theme);
            } catch (error) {
                return;
            }
        }

        function getPreferredTheme() {
            var storedTheme = readStoredTheme();
            if (storedTheme === "light" || storedTheme === "dark") {
                return storedTheme;
            }
            return mediaQuery && mediaQuery.matches ? "dark" : "light";
        }

        function renderButton() {
            if (document.querySelector(".cs_theme_toggle")) {
                return document.querySelector(".cs_theme_toggle");
            }

            var button = document.createElement("button");
            button.type = "button";
            button.className = "cs_theme_toggle";
            button.innerHTML =
                '<span class="cs_theme_toggle_icon" aria-hidden="true">' +
                '<svg class="cs_theme_toggle_sun" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                '<circle cx="12" cy="12" r="4.25" stroke="currentColor" stroke-width="1.8"/>' +
                '<path d="M12 2.75V5.25M12 18.75V21.25M21.25 12H18.75M5.25 12H2.75M18.54 5.46L16.77 7.23M7.23 16.77L5.46 18.54M18.54 18.54L16.77 16.77M7.23 7.23L5.46 5.46" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>' +
                "</svg>" +
                '<svg class="cs_theme_toggle_moon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                '<path d="M20.22 15.14A8.74 8.74 0 0 1 8.86 3.78a8.75 8.75 0 1 0 11.36 11.36Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>' +
                "</svg>" +
                "</span>";
            document.body.appendChild(button);
            return button;
        }

        function syncButton(button, theme) {
            var isDark = theme === "dark";
            var label = isDark ? "Switch to light mode" : "Switch to dark mode";
            button.setAttribute("aria-label", label);
            button.setAttribute("title", label);
            button.setAttribute("aria-pressed", isDark ? "true" : "false");
        }

        function applyTheme(theme) {
            root.setAttribute("data-theme", theme);
            root.style.colorScheme = theme;
            syncButton(themeButton, theme);
        }

        var themeButton = renderButton();
        applyTheme(getPreferredTheme());

        themeButton.addEventListener("click", function() {
            var nextTheme =
                root.getAttribute("data-theme") === "dark" ? "light" : "dark";
            writeStoredTheme(nextTheme);
            applyTheme(nextTheme);
        });

        if (mediaQuery) {
            var handleSystemThemeChange = function(event) {
                var storedTheme = readStoredTheme();
                if (storedTheme === "light" || storedTheme === "dark") {
                    return;
                }
                applyTheme(event.matches ? "dark" : "light");
            };

            if (typeof mediaQuery.addEventListener === "function") {
                mediaQuery.addEventListener("change", handleSystemThemeChange);
            } else if (typeof mediaQuery.addListener === "function") {
                mediaQuery.addListener(handleSystemThemeChange);
            }
        }
    }
    /*===============================================================
      5. Accordian
    =================================================================*/
    function accordian() {
        $(".cs_accordian").children(".cs_accordian_body").hide();
        $(".cs_accordian.active").children(".cs_accordian_body").show();
        $(".cs_accordian_head").on("click", function() {
            $(this)
                .parent(".cs_accordian")
                .siblings()
                .children(".cs_accordian_body")
                .slideUp(250);
            $(this).siblings().slideDown(250);
            $(this)
                .parent()
                .parent()
                .siblings()
                .find(".cs_accordian_body")
                .slideUp(250);
            /* Accordian Active Class */
            $(this).parents(".cs_accordian").addClass("active");
            $(this).parent(".cs_accordian").siblings().removeClass("active");
        });
    }
    /*===============================================================
      6. Review
    =================================================================*/
    function review() {
        $(".cs_rating").each(function() {
            var review = $(this).data("rating");
            var reviewVal = review * 20 + "%";
            $(this).find(".cs_rating_percentage").css("width", reviewVal);
        });
    }
    /*===============================================================
      7. Tabs
     ================================================================*/
    function tabs() {
        $(".cs_tabs .cs_tab_links a").on("click", function(e) {
            var currentAttrValue = $(this).attr("href");
            $(".cs_tabs " + currentAttrValue)
                .fadeIn(400)
                .siblings()
                .hide();
            $(this).parents("li").addClass("active").siblings().removeClass("active");
            e.preventDefault();
        });
    }
    /*===============================================================
      8. Slick Slider
    =================================================================*/
    function slickInit() {
        if ($.exists(".cs_slider")) {
            $(".cs_slider").each(function() {
                // Slick Variable
                var $ts = $(this).find(".cs_slider_container");
                var $slickActive = $(this).find(".cs_slider_wrapper");
                // Auto Play
                var autoPlayVar = parseInt($ts.attr("data-autoplay"), 10);
                // Auto Play Time Out
                var autoplaySpdVar = 3000;
                if (autoPlayVar > 1) {
                    autoplaySpdVar = autoPlayVar;
                    autoPlayVar = 1;
                }
                // Slide Change Speed
                var speedVar = parseInt($ts.attr("data-speed"), 10);
                // Slider Loop
                var loopVar = Boolean(parseInt($ts.attr("data-loop"), 10));
                // Slider Center
                var centerVar = Boolean(parseInt($ts.attr("data-center"), 10));
                // Variable Width
                var variableWidthVar = Boolean(
                    parseInt($ts.attr("data-variable-width"), 10)
                );
                // Pagination
                var paginaiton = $(this)
                    .find(".cs_pagination")
                    .hasClass("cs_pagination");
                // Slide Per View
                var slidesPerView = $ts.attr("data-slides-per-view");
                if (slidesPerView == 1) {
                    slidesPerView = 1;
                }
                if (slidesPerView == "responsive") {
                    var slidesPerView = parseInt($ts.attr("data-add-slides"), 10);
                    var lgPoint = parseInt($ts.attr("data-lg-slides"), 10);
                    var mdPoint = parseInt($ts.attr("data-md-slides"), 10);
                    var smPoint = parseInt($ts.attr("data-sm-slides"), 10);
                    var xsPoing = parseInt($ts.attr("data-xs-slides"), 10);
                }
                // Fade Slider
                var fadeVar = parseInt($($ts).attr("data-fade-slide"));
                fadeVar === 1 ? (fadeVar = true) : (fadeVar = false);

                // Slick Active Code
                $slickActive.slick({
                    autoplay: autoPlayVar,
                    dots: paginaiton,
                    centerPadding: "28%",
                    speed: speedVar,
                    infinite: loopVar,
                    autoplaySpeed: autoplaySpdVar,
                    centerMode: centerVar,
                    fade: fadeVar,
                    prevArrow: $(this).find(".cs_left_arrow"),
                    nextArrow: $(this).find(".cs_right_arrow"),
                    appendDots: $(this).find(".cs_pagination"),
                    slidesToShow: slidesPerView,
                    variableWidth: variableWidthVar,
                    swipeToSlide: true,
                    responsive: [{
                            breakpoint: 1600,
                            settings: {
                                slidesToShow: lgPoint,
                            },
                        },
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: mdPoint,
                            },
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: smPoint,
                            },
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: xsPoing,
                            },
                        },
                    ],
                });
            });
        }
    }
    /*================================================================
       9. Counter Animation
     =================================================================*/
    function counterInit() {
        if ($.exists(".odometer")) {
            $(window).on("scroll", function() {
                function winScrollPosition() {
                    var scrollPos = $(window).scrollTop(),
                        winHeight = $(window).height();
                    var scrollPosition = Math.round(scrollPos + winHeight / 1.2);
                    return scrollPosition;
                }

                $(".odometer").each(function() {
                    var elemOffset = $(this).offset().top;
                    if (elemOffset < winScrollPosition()) {
                        $(this).html($(this).data("count-to"));
                    }
                });
            });
        }
    }
    /*===============================================================
      10. Ecommerce
    =================================================================*/
    function ecommerce() {
        // Heart toggle
        $(".cs_heart_icon").on("click", function() {
            $(this).children("i").toggleClass("fa-solid");
        });
        // Category Widget Toggle
        $(".cs_filter_widget_title").on("click", function() {
            $(this)
                .toggleClass("active")
                .siblings(".cs_filter_widget_content")
                .slideToggle()
                .parent(".cs_filter_widget")
                .toggleClass("active");
        });
        // Product Single Slider
        if ($.exists(".cs_single_property_slider_1")) {
            $(".cs_single_property_slider_1").slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                arrows: false,
                asNavFor: ".cs_single_property_nav_1",
            });
        }
        if ($.exists(".cs_single_property_nav_1")) {
            $(".cs_single_property_nav_1").slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                asNavFor: ".cs_single_property_slider_1",
                focusOnSelect: true,
                arrows: false,
            });
        }
        if ($.exists(".cs_single_property_slider_2")) {
            $(".cs_single_property_slider_2").slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                arrows: false,
                asNavFor: ".cs_single_property_nav_2",
            });
        }

        if ($.exists(".cs_single_property_nav_2")) {
            $(".cs_single_property_nav_2").slick({
                slidesToShow: 6,
                slidesToScroll: 1,
                asNavFor: ".cs_single_property_slider_2",
                focusOnSelect: true,
                arrows: false,
            });
        }
        // Range Slider
        if ($.exists(".cs_slider_range")) {
            $(".cs_slider_range").slider({
                range: true,
                min: 0,
                max: 1000000,
                values: [150000, 700800],
                slide: function(event, ui) {
                    $(".cs_amount").val(
                        "Price: $" + ui.values[0] + " - $" + ui.values[1]
                    );
                },
            });
        }
        if ($.exists(".cs_amount")) {
            $(".cs_amount").val(
                "Price: $" +
                $(".cs_slider_range").slider("values", 0) +
                " - $" +
                $(".cs_slider_range").slider("values", 1)
            );
        }
        // Advanced Search
        $(".advanced_search").click(function() {
            $(this).parents().siblings(".cs_advanced_options_wrapper").slideToggle();
        });
        // Read More Property Details
        $(".cs_read_more").click(function() {
            $(this).siblings(".cs_property_description").toggleClass("active");
            $(this).siblings(".cs_property_description").hasClass("active") ?
                $(this).html("Show Less") :
                $(this).html("Show More");
        });
    }
    /*================================================================
       11. List And Grid View
     =================================================================*/
    function listAndGridView() {
        $(".cs_list_view").on("click", function() {
            $(this).addClass("active").siblings().removeClass("active");
            $(".cs_products_view").addClass("cs_grid_3").addClass("active");
        });

        $(".cs_grid_view").on("click", function() {
            $(this).addClass("active").siblings().removeClass("active");
            $(".cs_products_view").addClass("cs_grid_3").removeClass("active");
        });
    }
    /*==============================================================
   12. Light Gallery
 =================================================================*/
    function lightGallery() {
        $(".cs_gallery_list").each(function() {
            $(this).lightGallery({
                selector: ".cs_gallery_item",
                subHtmlSelectorRelative: false,
                thumbnail: false,
                mousewheel: true,
            });
        });
    }
    /*===============================================================
      13. Modal Video
      ===============================================================*/
    function modalVideo() {
        if ($.exists(".cs_video_open")) {
            $("body").append(`
        <div class="cs_video_popup">
          <div class="cs_video_popup-overlay"></div>
          <div class="cs_video_popup-content">
            <div class="cs_video_popup-layer"></div>
            <div class="cs_video_popup-container">
              <div class="cs_video_popup-align">
                <div class="embed-responsive embed-responsive-16by9">
                  <iframe class="embed-responsive-item" src="about:blank"></iframe>
                </div>
              </div>
              <div class="cs_video_popup-close"></div>
            </div>
          </div>
        </div>
      `);
            $(document).on("click", ".cs_video_open", function(e) {
                e.preventDefault();
                var video = $(this).attr("href");

                $(".cs_video_popup-container iframe").attr("src", `${video}`);

                $(".cs_video_popup").addClass("active");
            });
            $(".cs_video_popup-close, .cs_video_popup-layer").on(
                "click",
                function(e) {
                    $(".cs_video_popup").removeClass("active");
                    $("html").removeClass("overflow-hidden");
                    $(".cs_video_popup-container iframe").attr("src", "about:blank");
                    e.preventDefault();
                }
            );
        }
    }
    /*===============================================================
      14. Scroll Up
    =================================================================*/
    function scrollUp() {
        $(".cs_scrolltop_btn").on("click", function(e) {
            e.preventDefault();
            $("html,body").animate({
                    scrollTop: 0,
                },
                0
            );
        });
    }
    /* For Scroll Up */
    function showScrollUp() {
        let scroll = $(window).scrollTop();
        if (scroll >= 350) {
            $(".cs_scrolltop_btn").addClass("show");
        } else {
            $(".cs_scrolltop_btn").removeClass("show");
        }
    }
    /*===============================================================
     15. Load More Portfolio Items
    =================================================================*/
    function loadMore() {
        $(".cs_property_item").slice(0, 4).show();
        $("#loadMoreProperty").on("click", function(e) {
            e.preventDefault();
            console.log($(".cs_property_item"));
            $(".cs_property_item:hidden").slice(0, 2).slideDown();
            if ($(".cs_property_item:hidden").length <= 1) {
                $("#loadMoreProperty")
                    .text("No More to show")
                    .css("cursor", "not-allowed");
            }
        });
    }
    /*===============================================================
      16. Round Percent
    =================================================================*/
    function roundPercentInit() {
        if ($.exists(".cs_round_progress_percentage")) {
            $(window).on("scroll", function() {
                function winScrollPosition() {
                    var scrollPos = $(window).scrollTop(),
                        winHeight = $(window).height();
                    var scrollPosition = Math.round(scrollPos + winHeight / 1.2);
                    return scrollPosition;
                }

                $(".cs_round_progress_percentage").each(function() {
                    var roundEffect = $(this).offset().top;
                    if (roundEffect < winScrollPosition()) {
                        $(this).each(function() {
                            let roundRadius = $(this).find("circle").attr("r");
                            let roundPercent = $(this).data("percent");
                            let roundCircum = 2 * roundRadius * Math.PI;
                            let roundDraw = (roundPercent * roundCircum) / 100 - 3;
                            $(this).css("stroke-dasharray", roundDraw + " 999");
                        });
                    }
                });
            });
        }
    }
    /*==========================================================
      17. Smooth Page Scroll
    =============================================================*/
    function smoothScroll() {
        if (typeof Lenis !== "undefined") {
            const lenis = new Lenis({
                duration: 1.2,
                smooth: true,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            });

            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }

            requestAnimationFrame(raf);
        }
    }
})(jQuery); // End of use strict
