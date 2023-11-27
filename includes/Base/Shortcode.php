<?php

/**
 * Registers our shortcodes
 *
 * @package ide-interactive/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Base;

class Shortcode extends BaseController
{
    /**
     * register our post types
     */
    public function register()
    {
        add_shortcode('simple-logo-carousel', array($this, 'carousel_shortcode'));
    }

    /**
     * outputs our carousel shortcode
     * @param array $atts
     * @return string
     */
    function carousel_shortcode($atts = [])
    {
        // define attributes and their defaults
        extract(shortcode_atts(array(
            'id' => ''
        ), $atts));

        // if not id is found
        if (empty($id)) {
            return '<p>Please make sure your carousel shortcode contains an ID.</p>';
        } else {
            // enqueue our scripts
            wp_enqueue_script('jquery');
            wp_enqueue_script('slc_slick_js', esc_url($this->plugin_url . 'assets/public/libs/slick/slick.min.js'), true);
            wp_localize_script('slc_slick_js', 'slc_carousel_params_' . $id, $this->get_carousel_options($id));
            wp_enqueue_script('slc_main_js', esc_url($this->plugin_url . 'assets/public/js/slc-main.js'), true);

            // enqueue our styles
            wp_enqueue_style('slc_main_css', esc_url($this->plugin_url . 'assets/public/css/slc-main.css'));
            wp_add_inline_style('slc_main_css', $this->get_carousel_css($id));

            // variables to hold our categories and queries
            $categories = '';
            $taxonomyQuery = array();

            // if there is a taxonomy
            if (get_the_terms($id, 'slc_logo_category')) {
                // for each category in the taxonomy
                foreach (get_the_terms($id, 'slc_logo_category') as $category) {
                    // get the slug
                    $categories .= $category->slug . ', ';
                }

                // remove the last comma from categories
                $categories = substr($categories, 0, -2);

                // turn our categories in an array
                $categories = explode(',', $categories);
            }

            // if the category string is not empty or null
            if ($categories != '' || $categories != null) {
                // foreach category in the array
                foreach ($categories as $category) {
                    // create a temporary array
                    $taxonomy = array('taxonomy' => 'slc_logo_category', 'field' => 'slug', 'terms' => $category);

                    // push it into our query taxonomy array
                    array_push($taxonomyQuery, $taxonomy);
                }

                // start a query to get the logos
                $query = new \WP_Query(
                    array(
                        'post_type' => 'slc_logo',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'tax_query' => array(
                            'relation' => 'OR',
                            $taxonomyQuery
                        )
                    )
                );
            } else {
                // start a query to get the logos
                $query = new \WP_Query(
                    array(
                        'post_type' => 'slc_logo',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                    )
                );
            }

            // create our output variable
            $output = '';

            // if the query have posts
            if ($query->have_posts()) {

                $output .= '<div class="slc-logos slc-carousel-id-' . $id . '" data-id="' . $id . '">';

                // get our logo display order
                $slcLogoDisplayOrder = get_post_meta($id, 'slc_carousel_logo_display_order', true);
                if (!empty($slcLogoDisplayOrder)) {
                    $slcLogoDisplayOrder = json_decode($slcLogoDisplayOrder);
                }

                // while there are posts
                while ($query->have_posts()) {
                    $query->the_post();

                    $order = 9999;
                    if (!empty($slcLogoDisplayOrder)) {
                        foreach ($slcLogoDisplayOrder as $logoOrder) {
                            if ($logoOrder->id == get_the_id()) {
                                $order = $logoOrder->order;
                                break;
                            }
                        }
                    }

                    // create a container for an individual logo
                    $output .= '<div class="slc-logo" data-order="' . $order . '">';

                    // if there is a link
                    if (!empty(esc_url(get_post_meta(get_the_id(), 'slc_external_url', true)))) {
                        // output the link
                        $output .= '<a href="' . esc_url(get_post_meta(get_the_id(), 'slc_external_url', true)) . '" target="' . esc_html(get_post_meta(get_the_id(), 'slc_url_target', true)) . '">';
                    }

                    // if there is an alt text
                    if (!empty(esc_html(get_post_meta(get_the_id(), 'slc_alt_text', true)))) {
                        // set the alt text
                        $altText = esc_html(get_post_meta(get_the_id(), 'slc_alt_text', true));
                    } else {
                        // if there is no alt text check if the image has one
                        $thumbnail_id = get_post_thumbnail_id(get_the_ID());
                        $imageAltText = esc_html(get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true));

                        // if the alt tag on the image is not empty
                        if ($imageAltText != '' && $imageAltText != null) {
                            // set the alt text
                            $altText = $imageAltText;
                        } else {
                            // else if there is no alt text on the image default to the title if there is one
                            if (get_the_title() != '' && get_the_title() != null) {
                                $altText = get_the_title();
                            } else {
                                // default to the site title
                                $altText = get_bloginfo();
                            }
                        }
                    }

                    // create logo container
                    $output .= '<div class="slc-logo-container';

                    // if hover text is empty
                    if (empty(esc_html(get_post_meta(get_the_id(), 'slc_hover_text', true)))) {
                        $output .= ' slc-no-hover';
                    }

                    $output .= '">';

                    if (empty(esc_attr(get_post_meta($id, 'slc_carousel_disable_lazy_load_class', true)))) {
                        $output .= '<img src="' . get_the_post_thumbnail_url(get_the_id(), 'full') . '" alt="' . $altText . '" class="slc-logo-img"/>';
                    } else {
                        $output .= '<img src="' . get_the_post_thumbnail_url(get_the_id(), 'full') . '" alt="' . $altText . '" class="slc-logo-img ' . esc_attr(get_post_meta($id, 'slc_carousel_disable_lazy_load_class', true)) . '"/>';
                    }

                    // if there is a hover text
                    if (!empty(esc_html(get_post_meta(get_the_id(), 'slc_hover_text', true)))) {
                        // output the hover text
                        $output .= '<p class="slc-hover-text">' . esc_html(get_post_meta(get_the_id(), 'slc_hover_text', true)) . '</p>';
                    }

                    $output .= '</div>';

                    // if the carousel is displaying the logo title
                    if (get_post_meta($id, 'slc_carousel_show_title', true) == 'true') {
                        // if there is a title on the logo
                        if (get_the_title() != '' && get_the_title() != null) {
                            // output the title
                            $output .= '<p class="slc-logo-title">' . get_the_title() . '</p>';
                        }
                    }

                    // if there is a link
                    if (!empty(esc_url(get_post_meta(get_the_id(), 'slc_external_url', true)))) {
                        $output .= '</a>';
                    }

                    $output .= '</div>';
                }

                $output .= '</div>';
            }

            wp_reset_postdata();

            return $output;
        }
    }

    /**
     * get our inline css
     *
     * @param $id
     * @return string
     */
    function get_carousel_css($id)
    {
        // get our carousel options
        $titleColor = esc_html(get_post_meta($id, 'slc_carousel_title_color', true));
        $hoverTextColor = esc_html(get_post_meta($id, 'slc_carousel_hover_text_color', true));
        $hoverTextBackgroundColor = esc_html(get_post_meta($id, 'slc_carousel_hover_text_background_color', true));
        $hoverTextBackgroundColorOpacity = esc_html(get_post_meta($id, 'slc_carousel_hover_text_background_color_opacity', true));
        $slideVerticalAlignment = esc_html(get_post_meta($id, 'slc_carousel_slide_vertical_alignment', true));
        $arrowImageMaxWidth = esc_html(get_post_meta($id, 'slc_carousel_arrow_image_max_width', true));
        $arrowColor = esc_html(get_post_meta($id, 'slc_carousel_arrow_color', true));
        $arrowSize = esc_html(get_post_meta($id, 'slc_carousel_arrow_size', true));
        $arrowOffset = esc_html(get_post_meta($id, 'slc_carousel_arrow_offset', true));

        // create our css output
        $cssOutput = ".slc-carousel-id-{$id} .slc-logo-title { color: {$titleColor}; } .slc-carousel-id-{$id}.slc-logos { padding: 0 } .slc-carousel-id-{$id} .slc-hover-text { color: {$hoverTextColor}; } .slc-carousel-id-{$id} .slc-logo-container:before { background-color: {$hoverTextBackgroundColor}; opacity: {$hoverTextBackgroundColorOpacity}; } .slc-carousel-id-{$id}.slick-initialized .slick-track { display: flex; align-items: {$slideVerticalAlignment}; }";

        if (get_post_meta($id, 'slc_carousel_custom_arrows', true) == 'false') {
            $cssOutput .= " .slc-carousel-id-{$id} .slick-prev { font-size: {$arrowSize}; line-height: {$arrowSize}; color: {$arrowColor}; margin-top: calc(-{$arrowSize}/2); left:{$arrowOffset} } .slc-carousel-id-{$id} .slick-next { font-size: {$arrowSize}; line-height: {$arrowSize}; color: {$arrowColor}; margin-top: calc(-{$arrowSize}/2); right:{$arrowOffset} }";
        } else {
            $cssOutput .= " .slc-carousel-id-{$id} .slick-prev.slick-custom-arrow, .slc-carousel-id-{$id} .slick-next.slick-custom-arrow { max-width: {$arrowImageMaxWidth} } .slc-carousel-id-{$id} .slick-prev { left:{$arrowOffset} } .slc-carousel-id-{$id} .slick-next { right:{$arrowOffset} }";
        }

        // return our css output
        return $cssOutput;
    }

    /**
     * get our carousel options
     *
     * @param $id
     * @return array
     */
    function get_carousel_options($id)
    {
        // get our carousel options
        $autoplay = esc_html(get_post_meta($id, 'slc_carousel_autoplay', true));
        $autoplaySpeed = esc_html(get_post_meta($id, 'slc_carousel_autoplay_speed', true));
        $arrows = esc_html(get_post_meta($id, 'slc_carousel_arrows', true));
        $prevArrow = '<button type="button" class="slick-prev">&#x276C;</button>';
        $nextArrow = '<button type="button" class="slick-next">&#x276D;</button>';

        // if we are using custom arrows
        if (esc_html(get_post_meta($id, 'slc_carousel_custom_arrows', true)) != 'false') {
            $prevArrow = '<img src="' . esc_attr(get_post_meta($id, 'slc_carousel_left_arrow_image', true)) . '" class="slick-prev slick-custom-arrow" alt="' . __('Arrow to go to previous logo on carousel.', 'simple-logo-carousel') . '"/>';
            $nextArrow = '<img src="' . esc_attr(get_post_meta($id, 'slc_carousel_right_arrow_image', true)) . '" class="slick-next slick-custom-arrow" alt="' . __('Arrow to go to next logo on carousel.', 'simple-logo-carousel') . '"/>';
        }

        $centerMode = esc_html(get_post_meta($id, 'slc_carousel_center_mode', true));
        $cssEase = esc_html(get_post_meta($id, 'slc_carousel_animation', true));
        $draggable = esc_html(get_post_meta($id, 'slc_carousel_draggable', true));
        $pauseOnFocus = esc_html(get_post_meta($id, 'slc_carousel_pause_on_focus', true));
        $pauseOnHover = esc_html(get_post_meta($id, 'slc_carousel_pause_on_hover', true));
        $speed = esc_html(get_post_meta($id, 'slc_carousel_speed', true));
        $swipe = esc_html(get_post_meta($id, 'slc_carousel_swipe', true));

        // add our carousel options into an array
        $options['options'] = array(
            'autoplay' => $autoplay == 'true',
            'autoplaySpeed' => intval($autoplaySpeed),
            'arrows' => $arrows == 'true',
            'prevArrow' => $prevArrow,
            'nextArrow' => $nextArrow,
            'centerMode' => $centerMode == 'true',
            'cssEase' => $cssEase,
            'draggable' => $draggable == 'true',
            'pauseOnFocus' => $pauseOnFocus == 'true',
            'pauseOnHover' => $pauseOnHover == 'true',
            'speed' => intval($speed),
            'swipe' => $swipe == 'true'
        );

        // get our logo display options
        $logoDisplayOptions = get_post_meta($id, 'slc_carousel_logo_display_options', true);
        $breakpoints = json_decode($logoDisplayOptions);

        // for each breakpoint add it to the options array
        foreach ($breakpoints as $breakpoint) {
            $options['breakpoints'][] = array(
                'breakpoint' => $breakpoint->breakpoint,
                'show' => $breakpoint->show,
                'scroll' => $breakpoint->scroll
            );
        }

        // return our options object
        return $options;
    }
}