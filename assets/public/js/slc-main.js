jQuery(document).ready(function () {

    // for each logo carousel
    jQuery('.slc-logos').each(function () {
        // get the id
        const id = jQuery(this).attr('data-id');
        const params = window['slc_carousel_params_' + id];
        let initialSlidesToShow = 1;
        let initialSlidesToScroll = 1;

        // create our breakpoints object
        let breakpoints = [];

        // for each breakpoint in our params
        jQuery(params['breakpoints']).each(function (e) {
            // if this breakpoint is the default
            if (this.breakpoint === 'default') {
                initialSlidesToShow = parseInt(this.show);
                initialSlidesToScroll = parseInt(this.scroll);
            } else {
                // add our breakpoint to the breakpoints object
                let breakpoint = {
                    breakpoint: this.breakpoint,
                    settings: {
                        slidesToShow: parseInt(this.show),
                        slidesToScroll: parseInt(this.scroll)
                    }
                };

                breakpoints.push(breakpoint);
            }
        });

        // sort our logos
        jQuery('.slc-carousel-id-' + id).find('.slc-logo').sort(function (a, b) {
            return jQuery(a).attr('data-order') - jQuery(b).attr('data-order');
        }).appendTo('.slc-carousel-id-' + id).ready(function () {
            // initialize our slick with our logo carousel
            jQuery('.slc-carousel-id-' + id).slick({
                autoplay: params['options'].autoplay,
                autoplaySpeed: params['options'].autoplaySpeed,
                arrows: params['options'].arrows,
                prevArrow: params['options'].prevArrow,
                nextArrow: params['options'].nextArrow,
                centerMode: params['options'].centerMode,
                cssEase: params['options'].cssEase,
                dots: false,
                draggable: params['options'].draggable,
                pauseOnFocus: params['options'].pauseOnFocus,
                pauseOnHover: params['options'].pauseOnHover,
                speed: params['options'].speed,
                swipe: params['options'].swipe,
                slidesToShow: initialSlidesToShow,
                slidesToScroll: initialSlidesToScroll,
                swipeToSlide: true,
                touchThreshold: 100,
                responsive: breakpoints
            });
        });
    });

});