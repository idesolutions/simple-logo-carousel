jQuery(document).ready(function () {
    const slcCarouselLogoDisplayOptions = jQuery('#slc-carousel-logo-display-options');

    // if the add breakpoint button is clicked on
    jQuery('#add-breakpoint-btn').on('click', function (e) {
        e.preventDefault();

        // clone the first row
        let row = jQuery('#slc-logo-display-options-table > tbody > tr:first-child').clone();

        // reset all values
        row.find('input.breakpoint').val('').attr('required', 'required');
        row.find('input.slides-to-show').val('1');
        row.find('input.slides-to-scroll').val('1');
        row.find('span.default').remove();

        // add a row to the table
        jQuery('#slc-logo-display-options-table > tbody').append(row);
        DataToJsonString();
    });

    // if the sort breakpoint button is clicked on
    jQuery('#sort-breakpoint-btn').on('click', function (e) {
        e.preventDefault();
        let rows = jQuery('#slc-logo-display-options-table > tbody > tr');

        // for each row entry
        rows.each(function (e) {
            // if this row has the default span
            if (jQuery(this).find('span.default').length == 0) {
                // assign the breakpoint to this row
                jQuery(this).attr('data-id', jQuery(this).find('input.breakpoint').val());
            } else {
                // assign the number 0 to this row
                jQuery(this).attr('data-id', 0);
            }
        });

        // sort our row
        rows.sort(function (a, b) {
            var keyA = jQuery(a).attr('data-id');
            var keyB = jQuery(b).attr('data-id');


            return keyA - keyB;
        });

        // append the row in order
        jQuery.each(rows, function (index, row) {
            jQuery('#slc-logo-display-options-table > tbody').append(row);
        });

        // update json data
        DataToJsonString();
    });

    // if the delete row button is clicked on
    jQuery('body').on('click', '.delete-row', function (e) {
        e.preventDefault();

        // delete the current row
        jQuery(this).parent().parent().remove();

        // update json data
        DataToJsonString();
    });

    // if any input change is detected inside the logo display options
    jQuery('body').on('change keyup', '#slc-logo-display-options-table input', function (e) {
        // update json data
        DataToJsonString();
    });

    // turn our logo display options into a json array
    function DataToJsonString() {
        let data = [];

        // for each row entry
        jQuery('#slc-logo-display-options-table > tbody > tr').each(function (e) {
            // if this row has the default span
            if (jQuery(this).find('span.default').length == 0) {
                data.push({
                    breakpoint: jQuery(this).find('input.breakpoint').val(),
                    show: jQuery(this).find('input.slides-to-show').val(),
                    scroll: jQuery(this).find('input.slides-to-scroll').val()
                });
            } else {
                data.push({
                    breakpoint: 'default',
                    show: jQuery(this).find('input.slides-to-show').val(),
                    scroll: jQuery(this).find('input.slides-to-scroll').val()
                });
            }
        });

        // set our text area to our json data
        slcCarouselLogoDisplayOptions.val(JSON.stringify(data));
    }

    // trim empty spacing from logo display options textarea
    slcCarouselLogoDisplayOptions.val(slcCarouselLogoDisplayOptions.val().trim());

    // on sort click and hold
    jQuery('#slc-logo-display-options-table tbody > tr  > td:first-child').on('mousedown touchstart', function () {
        jQuery('#slc-logo-display-options-table > tbody').sortable('enable');
    }).bind('mouseup mouseleave touchend', function () {
        jQuery('#slc-logo-display-options-table > tbody').sortable('disable');
    });

    // initiate and disable sortable on load
    jQuery('#slc-logo-display-options-table > tbody').sortable({
        items: 'tr[data-id!=default]',
        update: function () {
            DataToJsonString();
        },
    });
    jQuery('#slc-logo-display-options-table > tbody').sortable('disable');
});