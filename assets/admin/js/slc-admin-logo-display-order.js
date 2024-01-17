jQuery(document).ready(function () {
    const slcCarouselLogoDisplayOrder = jQuery('#slc-carousel-logo-display-order');

    // turn our logo display order into a json array
    function DataToJsonString() {
        let data = [];
        let order = 1;

        // for each row entry
        jQuery('#slc-logo-display-order-table > tbody.slc-main-tbody > tr').each(function (e) {
            jQuery(this).attr('data-order', order);
            jQuery(this).find('.order-number').text(order.toString());
            data.push({
                id: jQuery(this).attr('data-id'),
                order: jQuery(this).attr('data-order')
            });
            order++;
        });

        // set our text area to our json data
        slcCarouselLogoDisplayOrder.val(JSON.stringify(data));

        //updating preview logo container
        let $slider = jQuery(".slc-logos");

        // sort the data array based on the 'order' property
        let previewData = data.slice();   //creating deep copy
        previewData.sort(function (a, b) {
            return a.order - b.order;
        });

        // create an empty container to hold the sorted slides
        var $sortedSlides = [];

        // loop through the sorted data and append the corresponding slide elements
        previewData.forEach(function (item) {

            var slideId = item.id;

            // find the slide element with the matching 'data-id' attribute
            var $slide = $slider.find('.slc-logo[data-id="' + slideId + '"]:not(.slick-cloned)');

            // append the slide to the sorted container
            $sortedSlides.push($slide[0]);
        });

        // removing all slides
        // https://github.com/kenwheeler/slick/issues/673#issuecomment-158764194
        $slider.slick('slickRemove', null, null, true);


        $sortedSlides.forEach(function (slide) {
            $slider.slick('slickAdd', slide);
        });
    }

    // trim empty spacing from logo display order textarea
    slcCarouselLogoDisplayOrder.val(slcCarouselLogoDisplayOrder.val().trim());

    // sort our logos
    jQuery('#slc-logo-display-order-table .slc-main-tbody').find('.slc-draggable').sort(function (a, b) {
        return jQuery(a).attr('data-order') - jQuery(b).attr('data-order');
    }).appendTo('#slc-logo-display-order-table .slc-main-tbody').ready(function () {
        // initiate and disable sortable on load
        jQuery('#slc-logo-display-order-table > tbody.slc-main-tbody').sortable({
            items: 'tr.slc-draggable',
            update: function () {
                DataToJsonString();
            },
        });
        jQuery('#slc-logo-display-order-table > tbody.slc-main-tbody').sortable('disable');

        // on sort click and hold
        jQuery('#slc-logo-display-order-table > tbody.slc-main-tbody').on('mousedown touchstart', 'tr.slc-draggable  > td:first-child', function () {
            jQuery('#slc-logo-display-order-table > tbody.slc-main-tbody').sortable('enable');
        }).bind('mouseup mouseleave touchend', function () {
            jQuery('#slc-logo-display-order-table > tbody.slc-main-tbody').sortable('disable');
        });
    });
});