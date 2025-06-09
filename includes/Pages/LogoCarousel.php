<?php

/**
 * Manages and handles everything relating to the logo post type
 * view which also includes rendering and saving the page data
 *
 * @package ide-interactive/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Pages;

use PLSimpleLogoCarousel\Base\BaseController;

class LogoCarousel extends BaseController
{
    /**
     * register our logo post type data
     */
    public function register()
    {
        add_action('add_meta_boxes', array($this, 'create_metaboxes'));
        add_action('save_post_slc_carousel', array($this, 'save_carousel_meta'), 1, 2);
        add_action('admin_enqueue_scripts', array($this, 'admin_logo_script_and_styles'));
        add_filter('manage_slc_carousel_posts_columns', array($this, 'carousel_posts_columns'));
        add_action('manage_slc_carousel_posts_custom_column', array($this, 'carousel_column'), 10, 2);
    }

    /**
     * create metaboxes
     */
    public function create_metaboxes()
    {
        // metabox for carousel shortcode
        add_meta_box(
            'slc_cpt_carousel_shortcode',
            __('Shortcode', 'simple-logo-carousel'),
            array($this, 'carousel_shortcode'),
            'slc_carousel',
            'normal',
            'default'
        );

        // metabox for preview
        add_meta_box(
            'slc_cpt_carousel_preview',
            __('Preview', 'simple-logo-carousel'),
            array($this, 'carousel_preview'),
            'slc_carousel',
            'normal',
            'default'
        );

        // metabox for logo display order
        add_meta_box(
            'slc_cpt_carousel_logo_display_order',
            __('Logo Display Order', 'simple-logo-carousel'),
            array($this, 'carousel_logo_display_order'),
            'slc_carousel',
            'normal',
            'default'
        );

        // metabox for carousel logo display options
        add_meta_box(
            'slc_cpt_carousel_logo_display_options',
            __('Logo Display Options', 'simple-logo-carousel'),
            array($this, 'carousel_logo_display_options'),
            'slc_carousel',
            'normal',
            'default'
        );

        // metabox for carousel options
        add_meta_box(
            'slc_cpt_carousel_options',
            __('Carousel Options', 'simple-logo-carousel'),
            array($this, 'carousel_options'),
            'slc_carousel',
            'normal',
            'default'
        );

        // metabox for how to
        add_meta_box(
            'slc_cpt_carousel_how_to',
            __('How To', 'simple-logo-carousel'),
            array($this, 'carousel_how_to'),
            'slc_carousel',
            'side',
            'default'
        );
    }

    /**
     * renders the carousel how to
     */
    function carousel_how_to()
    {
        require_once $this->plugin_path . 'templates/admin/carousel-how-to-metabox.php';
    }

    /**
     * renders the carousel preview
     */
    function carousel_preview()
    {
        require_once $this->plugin_path . 'templates/admin/carousel-preview-metabox.php';
    }

    /**
     * renders the carousel logo display order
     */
    function carousel_logo_display_order()
    {
        require_once $this->plugin_path . 'templates/admin/carousel-logo-display-order-metabox.php';
    }

    /**
     * renders the carousel shortcode metabox
     */
    function carousel_shortcode()
    {
        require_once $this->plugin_path . 'templates/admin/carousel-shortcode-metabox.php';
    }

    /**
     * renders the carousel logo display options metabox
     */
    function carousel_logo_display_options()
    {
        require_once $this->plugin_path . 'templates/admin/carousel-logo-display-options-metabox.php';
    }

    /**
     * renders the carousel logo display options metabox
     */
    function carousel_options()
    {
        require_once $this->plugin_path . 'templates/admin/carousel-options-metabox.php';
    }

    /**
     * enqueue our stylesheet and scripts
     *
     * @param $hook
     */
    function admin_logo_script_and_styles($hook)
    {
        // reference our post
        global $post;

        if ($post) {
            // if we are in the carousel custom post type
            if ($hook == 'post-new.php' || $hook == 'post.php' && $post->post_type == 'slc_carousel') {
                wp_enqueue_style('admin_slc_stylesheet', $this->plugin_url . 'assets/admin/css/slc-admin.css');
                wp_enqueue_style('admin_logo_display_options_slc_stylesheet', $this->plugin_url . 'assets/admin/css/slc-admin-logo-display-options.css');
                wp_enqueue_style('admin_logo_display_order_slc_stylesheet', $this->plugin_url . 'assets/admin/css/slc-admin-logo-display-order.css');
                wp_enqueue_style('admin_logo_preview_slc_stylesheet', $this->plugin_url . 'assets/admin/css/slc-admin-preview.css');
            }

            // if we are in the carousel custom post type and not the logo custom post type
            if ($post->post_type == 'slc_carousel' && ($hook == 'post.php' || $hook == 'post-new.php') && $post->post_type != 'slc_logo') {
                wp_enqueue_script('jquery');
                wp_enqueue_script('jquery-ui-core');
                wp_enqueue_style('wp-color-picker');
                wp_enqueue_script('wp-color-picker');
                wp_add_inline_script('wp-color-picker', 'jQuery(document).ready(function($){$(".color-field").each( function() {$(this).wpColorPicker();});});');
                wp_enqueue_script('admin_logo_display_options_slc_script', $this->plugin_url . 'assets/admin/js/slc-admin-logo-display-options.js', array('jquery'));
                wp_enqueue_script('admin_logo_display_order_slc_script', $this->plugin_url . 'assets/admin/js/slc-admin-logo-display-order.js', array('jquery'));
                wp_add_inline_script('admin_logo_display_options_slc_script', 'let contentUrl="' . $this->plugin_url . '";');
                wp_enqueue_script('slc_slick_js', esc_url($this->plugin_url . 'assets/public/libs/slick/slick.min.js'), true);
                wp_localize_script('slc_slick_js', 'slc_carousel_params_' . $post->ID, $this->get_carousel_options($post->ID));
                wp_enqueue_script('slc_main_js', esc_url($this->plugin_url . 'assets/public/js/slc-main.js'), true);
                wp_enqueue_style('slc_main_css', esc_url($this->plugin_url . 'assets/public/css/slc-main.css'));
                wp_add_inline_style('slc_main_css', $this->get_carousel_css($post->ID));
            }
        }
    }

    /**
     * saves our metabox data
     *
     * @param $post_id
     * @param $post
     */
    function save_carousel_meta($post_id, $post)
    {
        // return if the user doesn't have edit permissions.
        if (!current_user_can('edit_simple_logo_carousel', $post_id)) {
            return;
        }

        // verify taxonomies meta box nonce
        if (!isset($_POST['slc_cpt']) || !wp_verify_nonce(sanitize_key($_POST['slc_cpt']), 'verify_slc')) {
            return;
        }

        // update and save our meta data
        if (empty($_POST['slc_carousel_logo_display_options'])) {
            update_post_meta($post->ID, 'slc_carousel_logo_display_options', '[{"breakpoint": "default", "show": 1, "scroll": 1}]');
        } else {
            update_post_meta($post->ID, 'slc_carousel_logo_display_options', sanitize_text_field($_POST['slc_carousel_logo_display_options']));
        }

        if (empty($_POST['slc_carousel_logo_display_order'])) {
            update_post_meta($post->ID, 'slc_carousel_logo_display_order', '');
        } else {
            update_post_meta($post->ID, 'slc_carousel_logo_display_order', sanitize_text_field($_POST['slc_carousel_logo_display_order']));
        }

        update_post_meta($post->ID, 'slc_carousel_show_title', sanitize_text_field($_POST['slc_carousel_show_title']));

        if (empty($_POST['slc_carousel_title_color'])) {
            update_post_meta($post->ID, 'slc_carousel_title_color', '#222222');
        } else {
            update_post_meta($post->ID, 'slc_carousel_title_color', sanitize_hex_color($_POST['slc_carousel_title_color']));
        }

        if (empty($_POST['slc_carousel_hover_text_color'])) {
            update_post_meta($post->ID, 'slc_carousel_hover_text_color', '#222222');
        } else {
            update_post_meta($post->ID, 'slc_carousel_hover_text_color', sanitize_hex_color($_POST['slc_carousel_hover_text_color']));
        }

        if (empty($_POST['slc_carousel_hover_text_background_color'])) {
            update_post_meta($post->ID, 'slc_carousel_hover_text_background_color', '#ffffff');
        } else {
            update_post_meta($post->ID, 'slc_carousel_hover_text_background_color', sanitize_hex_color($_POST['slc_carousel_hover_text_background_color']));
        }

        if (is_numeric($_POST['slc_carousel_hover_text_background_color_opacity'])) {
            if (sanitize_text_field($_POST['slc_carousel_hover_text_background_color_opacity']) <= 0 || empty($_POST['slc_carousel_hover_text_background_color_opacity'])) {
                update_post_meta($post->ID, 'slc_carousel_hover_text_background_color_opacity', '0.8');
            } else {
                update_post_meta($post->ID, 'slc_carousel_hover_text_background_color_opacity', sanitize_text_field($_POST['slc_carousel_hover_text_background_color_opacity']));
            }
        } else {
            update_post_meta($post->ID, 'slc_carousel_hover_text_background_color_opacity', '0.8');
        }

        update_post_meta($post->ID, 'slc_carousel_accessibility', sanitize_text_field($_POST['slc_carousel_accessibility']));
        update_post_meta($post->ID, 'slc_carousel_autoplay', sanitize_text_field($_POST['slc_carousel_autoplay']));

        if (is_numeric($_POST['slc_carousel_autoplay_speed'])) {
            if (sanitize_text_field($_POST['slc_carousel_autoplay_speed']) < 0 || !isset($_POST['slc_carousel_autoplay_speed'])) {
                update_post_meta($post->ID, 'slc_carousel_autoplay_speed', '3000');
            } else {
                update_post_meta($post->ID, 'slc_carousel_autoplay_speed', sanitize_text_field($_POST['slc_carousel_autoplay_speed']));
            }
        } else {
            update_post_meta($post->ID, 'slc_carousel_autoplay_speed', '3000');
        }

        update_post_meta($post->ID, 'slc_carousel_arrows', sanitize_text_field($_POST['slc_carousel_arrows']));

        if (empty($_POST['slc_carousel_arrow_color'])) {
            update_post_meta($post->ID, 'slc_carousel_arrow_color', '#222222');
        } else {
            update_post_meta($post->ID, 'slc_carousel_arrow_color', sanitize_hex_color($_POST['slc_carousel_arrow_color']));
        }

        if (empty($_POST['slc_carousel_arrow_size'])) {
            update_post_meta($post->ID, 'slc_carousel_arrow_size', '50px');
        } else {
            update_post_meta($post->ID, 'slc_carousel_arrow_size', sanitize_html_class($_POST['slc_carousel_arrow_size']));
        }

        if (empty($_POST['slc_carousel_arrow_offset'])) {
            update_post_meta($post->ID, 'slc_carousel_arrow_offset', '-10px');
        } else {
            update_post_meta($post->ID, 'slc_carousel_arrow_offset', sanitize_html_class($_POST['slc_carousel_arrow_offset']));
        }

        update_post_meta($post->ID, 'slc_carousel_custom_arrows', sanitize_text_field($_POST['slc_carousel_custom_arrows']));
        update_post_meta($post->ID, 'slc_carousel_left_arrow_image', esc_url_raw($_POST['slc_carousel_left_arrow_image']));
        update_post_meta($post->ID, 'slc_carousel_right_arrow_image', esc_url_raw($_POST['slc_carousel_right_arrow_image']));

        if (empty($_POST['slc_carousel_arrow_image_max_width'])) {
            update_post_meta($post->ID, 'slc_carousel_arrow_image_max_width', '50px');
        } else {
            update_post_meta($post->ID, 'slc_carousel_arrow_image_max_width', sanitize_html_class($_POST['slc_carousel_arrow_image_max_width']));
        }

        update_post_meta($post->ID, 'slc_carousel_center_mode', sanitize_text_field($_POST['slc_carousel_center_mode']));
        update_post_meta($post->ID, 'slc_carousel_animation', sanitize_text_field($_POST['slc_carousel_animation']));
        update_post_meta($post->ID, 'slc_carousel_draggable', sanitize_text_field($_POST['slc_carousel_draggable']));
        update_post_meta($post->ID, 'slc_carousel_pause_on_focus', sanitize_text_field($_POST['slc_carousel_pause_on_focus']));
        update_post_meta($post->ID, 'slc_carousel_pause_on_hover', sanitize_text_field($_POST['slc_carousel_pause_on_hover']));
        update_post_meta($post->ID, 'slc_carousel_slide_vertical_alignment', sanitize_text_field($_POST['slc_carousel_slide_vertical_alignment']));
        update_post_meta($post->ID, 'slc_carousel_disable_lazy_load_class', sanitize_text_field($_POST['slc_carousel_disable_lazy_load_class']));

        if (is_numeric($_POST['slc_carousel_speed'])) {
            if (sanitize_text_field($_POST['slc_carousel_speed']) <= 0 || empty($_POST['slc_carousel_speed'])) {
                update_post_meta($post->ID, 'slc_carousel_speed', '1000');
            } else {
                update_post_meta($post->ID, 'slc_carousel_speed', sanitize_text_field($_POST['slc_carousel_speed']));
            }
        } else {
            update_post_meta($post->ID, 'slc_carousel_speed', '1000');
        }

        update_post_meta($post->ID, 'slc_carousel_swipe', sanitize_text_field($_POST['slc_carousel_swipe']));

        return $post;
    }

    /**
     * add our custom columns to the carousel custom post type posts view
     *
     * @param $columns
     * @return array
     */
    function carousel_posts_columns($columns)
    {
        // create an array of the columns we are displaying
        $columns = array(
            'cb' => $columns['cb'],
            'title' => __('Title', 'simple-logo-carousel'),
            'category' => __('Categories', 'simple-logo-carousel'),
            'shortcode' => __('Shortcode', 'simple-logo-carousel')
        );

        // return the array
        return $columns;
    }

    /**
     * display our carousel details inside the columns in posts view
     *
     * @param $column
     * @param $post_id
     */
    function carousel_column($column, $post_id)
    {
        // if there is a category column
        if ('category' === $column) {
            // if there is a taxonomy
            if (get_the_terms($post_id, 'slc_logo_category')) {
                // create an empty string to hold our categories
                $categories = '';

                // for each category in the taxonomy
                foreach (get_the_terms($post_id, 'slc_logo_category') as $category) {
                    $categories .= $category->name . ', ';
                }

                // remove the last comma from categories
                $categories = substr($categories, 0, -2);

                // display it
                esc_html_e($categories);
            } else {
                // else inform there is not one
                echo '<em>' . esc_html__('No categories were selected, all logos are being shown.', 'simple-logo-carousel') . '</em>';
            }
        } // if there is a shortcode column
        else if ('shortcode' === $column) {
            // output the shortcode
            echo '<input type="text" value="[simple-logo-carousel id=' . esc_attr($post_id) . ']" style="min-width:245px;" readonly />';
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

        if ($logoDisplayOptions) {
            $breakpoints = json_decode($logoDisplayOptions);

            // for each breakpoint add it to the options array
            foreach ($breakpoints as $breakpoint) {
                $options['breakpoints'][] = array(
                    'breakpoint' => $breakpoint->breakpoint,
                    'show' => $breakpoint->show,
                    'scroll' => $breakpoint->scroll
                );
            }
        }

        // return our options object
        return $options;
    }
}