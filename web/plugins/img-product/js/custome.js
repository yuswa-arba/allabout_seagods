$(function () {
    // Slideshow 1
    $("#slider1").responsiveSlides({
        auto: false,
        pager: true,
        nav: true,
        speed: 500,
        maxwidth: 800,
        namespace: "centered-btns"
    });
    // Slideshow 2
    $("#slider2").responsiveSlides({
        auto: false,
        pager: true,
        nav: true,
        speed: 500,
        maxwidth: 800,
        namespace: "transparent-btns"
    });
    // Slideshow 3
    $("#slider3").responsiveSlides({
        auto: false,
        pager: false,
        nav: true,
        speed: 500,
        maxwidth: 800,
        namespace: "large-btns"
    });

    // Zoom Image
    setTimeout(function(){
        wheelzoom(document.querySelectorAll('img.for-scroll-zoom'), {maxZoom: 1.0});
    }, 1500);
});
