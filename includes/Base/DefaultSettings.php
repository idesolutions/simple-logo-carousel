<?php

/**
 * Registers our default settings
 *
 * @package productive-laziness/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Base;

use PLSimpleLogoCarousel\Base\BaseController;

class DefaultSettings extends BaseController
{
    /**
     * register our default settings
     */
    public static function register()
    {
        self::create_initial_carousel_options();
    }

    /**
     * create our initial carousel options
     *
     * @return mixed
     */
    public static function create_initial_carousel_options()
    {
        // get our options
        $options = get_option('slc_initial_carousel_options', array());

        // assign our default values
        $new_options['slc_carousel_show_title'] = 'false';
        $new_options['slc_carousel_title_color'] = '#222222';
        $new_options['slc_carousel_hover_text_color'] = '#222222';
        $new_options['slc_carousel_hover_text_background_color'] = '#ffffff';
        $new_options['slc_carousel_hover_text_background_color_opacity'] = 0.8;
        $new_options['slc_carousel_accessibility'] = 'true';
        $new_options['slc_carousel_autoplay'] = 'true';
        $new_options['slc_carousel_autoplay_speed'] = 3000;
        $new_options['slc_carousel_arrows'] = 'false';
        $new_options['slc_carousel_arrow_color'] = '#222222';
        $new_options['slc_carousel_arrow_size'] = '50px';
        $new_options['slc_carousel_arrow_offset'] = '-25px';
        $new_options['slc_carousel_custom_arrows'] = 'false';
        $new_options['slc_carousel_left_arrow_image'] = '';
        $new_options['slc_carousel_right_arrow_image'] = '';
        $new_options['slc_carousel_arrow_image_max_width'] = '50px';
        $new_options['slc_carousel_center_mode'] = 'false';
        $new_options['slc_carousel_animation'] = 'ease';
        $new_options['slc_carousel_draggable'] = 'true';
        $new_options['slc_carousel_pause_on_hover'] = 'true';
        $new_options['slc_carousel_pause_on_focus'] = 'true';
        $new_options['slc_carousel_speed'] = 1000;
        $new_options['slc_carousel_swipe'] = 'true';
        $new_options['slc_carousel_slide_vertical_alignment'] = 'center';

        // check if we already have assigned values and if not set them
        $merged_options = wp_parse_args($options, $new_options);
        $compare_options = array_diff_key($new_options, $options);

        if (empty($options) || !empty($compare_options)) {
            update_option('slc_initial_carousel_options', $merged_options);
        }
        return $merged_options;
    }
}