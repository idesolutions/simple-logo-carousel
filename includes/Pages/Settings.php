<?php

/**
 * Manages and handles the settings page
 *
 * @package ide-interactive/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Pages;

use PLSimpleLogoCarousel\Base\BaseController;

class Settings extends BaseController
{
    /**
     * register our settings page
     */
    public function register()
    {
        add_action('admin_enqueue_scripts', array($this, 'admin_logo_script_and_styles'));
        add_action('admin_menu', array($this, 'create_menus'));
        add_action('admin_post_update_initial_carousel_options', array($this, 'update_initial_carousel_options'));
    }

    /**
     * add a settings option to the menu
     */
    public function create_menus()
    {
        // reference the submenu
        global $submenu;

        // add a "Settings" link to the cpt slc carousel
        add_submenu_page('edit.php?post_type=slc_carousel', __('Simple Logo Carousel - Settings'), __('Settings'), 'manage_options', 'settings', array($this, 'render_settings_page'));
    }

    /**
     * renders the settings page
     */
    function render_settings_page()
    {
        require_once $this->plugin_path . 'templates/admin/settings.php';
    }

    /**
     * enqueue our stylesheet and scripts
     *
     * @param $hook
     */
    function admin_logo_script_and_styles($hook)
    {
        // if we are in the settings page
        if ($hook == 'slc_carousel_page_settings') {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            wp_add_inline_script('wp-color-picker', 'jQuery(document).ready(function($){$(".color-field").each( function() {$(this).wpColorPicker();});});');
        }
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
        $new_options['slc_carousel_disable_lazy_load_class'] = '';

        // check if we already have assigned values and if not set them
        $merged_options = wp_parse_args($options, $new_options);
        $compare_options = array_diff_key($new_options, $options);

        if (empty($options) || !empty($compare_options)) {
            update_option('slc_initial_carousel_options', $merged_options);
        }
        return $merged_options;
    }

    /**
     * update our initial carousel options
     */
    function update_initial_carousel_options()
    {
        // check that user has proper security level
        if (!current_user_can('manage_options')) {
            wp_die('Not allowed');
        }

        // check if nonce field configuration form is present
        check_admin_referer('slc_update_initial_carousel_options_nonce');

        // retrieve plugin settings
        $options = $this->create_initial_carousel_options();

        // sanitize our fields
        $options['slc_carousel_show_title'] = isset($_POST['slc_carousel_show_title']) ? sanitize_text_field($_POST['slc_carousel_show_title']) : 'false';
        $options['slc_carousel_title_color'] = isset($_POST['slc_carousel_title_color']) ? sanitize_text_field($_POST['slc_carousel_title_color']) : '#222222';
        $options['slc_carousel_hover_text_color'] = isset($_POST['slc_carousel_hover_text_color']) ? sanitize_text_field($_POST['slc_carousel_hover_text_color']) : '#222222';
        $options['slc_carousel_hover_text_background_color'] = isset($_POST['slc_carousel_hover_text_background_color']) ? sanitize_text_field($_POST['slc_carousel_hover_text_background_color']) : '#ffffff';
        $options['slc_carousel_hover_text_background_color_opacity'] = isset($_POST['slc_carousel_hover_text_background_color_opacity']) ? floatval($_POST['slc_carousel_hover_text_background_color_opacity']) : 0.8;
        $options['slc_carousel_accessibility'] = isset($_POST['slc_carousel_accessibility']) ? sanitize_text_field($_POST['slc_carousel_accessibility']) : 'true';
        $options['slc_carousel_autoplay'] = isset($_POST['slc_carousel_autoplay']) ? sanitize_text_field($_POST['slc_carousel_autoplay']) : 'true';
        $options['slc_carousel_autoplay_speed'] = isset($_POST['slc_carousel_autoplay_speed']) ? intval($_POST['slc_carousel_autoplay_speed']) : 3000;
        $options['slc_carousel_arrows'] = isset($_POST['slc_carousel_arrows']) ? sanitize_text_field($_POST['slc_carousel_arrows']) : 'false';
        $options['slc_carousel_arrow_color'] = isset($_POST['slc_carousel_arrow_color']) ? sanitize_text_field($_POST['slc_carousel_arrow_color']) : '#222222';
        $options['slc_carousel_arrow_size'] = isset($_POST['slc_carousel_arrow_size']) ? sanitize_text_field($_POST['slc_carousel_arrow_size']) : '50px';
        $options['slc_carousel_arrow_offset'] = isset($_POST['slc_carousel_arrow_offset']) ? sanitize_text_field($_POST['slc_carousel_arrow_offset']) : '-25px';
        $options['slc_carousel_custom_arrows'] = isset($_POST['slc_carousel_custom_arrows']) ? sanitize_text_field($_POST['slc_carousel_custom_arrows']) : 'false';
        $options['slc_carousel_left_arrow_image'] = isset($_POST['slc_carousel_left_arrow_image']) ? sanitize_text_field($_POST['slc_carousel_left_arrow_image']) : '';
        $options['slc_carousel_right_arrow_image'] = isset($_POST['slc_carousel_right_arrow_image']) ? sanitize_text_field($_POST['slc_carousel_right_arrow_image']) : '';
        $options['slc_carousel_arrow_image_max_width'] = isset($_POST['slc_carousel_arrow_image_max_width']) ? sanitize_text_field($_POST['slc_carousel_arrow_image_max_width']) : '50px';
        $options['slc_carousel_center_mode'] = isset($_POST['slc_carousel_center_mode']) ? sanitize_text_field($_POST['slc_carousel_center_mode']) : 'false';
        $options['slc_carousel_animation'] = isset($_POST['slc_carousel_animation']) ? sanitize_text_field($_POST['slc_carousel_animation']) : 'ease';
        $options['slc_carousel_draggable'] = isset($_POST['slc_carousel_draggable']) ? sanitize_text_field($_POST['slc_carousel_draggable']) : 'true';
        $options['slc_carousel_pause_on_hover'] = isset($_POST['slc_carousel_pause_on_hover']) ? sanitize_text_field($_POST['slc_carousel_pause_on_hover']) : 'true';
        $options['slc_carousel_pause_on_focus'] = isset($_POST['slc_carousel_pause_on_focus']) ? sanitize_text_field($_POST['slc_carousel_pause_on_focus']) : 'true';
        $options['slc_carousel_speed'] = isset($_POST['slc_carousel_speed']) ? intval($_POST['slc_carousel_speed']) : 1000;
        $options['slc_carousel_swipe'] = isset($_POST['slc_carousel_swipe']) ? sanitize_text_field($_POST['slc_carousel_swipe']) : 'true';
        $options['slc_carousel_slide_vertical_alignment'] = isset($_POST['slc_carousel_slide_vertical_alignment']) ? sanitize_text_field($_POST['slc_carousel_slide_vertical_alignment']) : 'center';
        $options['slc_carousel_disable_lazy_load_class'] = isset($_POST['slc_carousel_disable_lazy_load_class']) ? sanitize_text_field($_POST['slc_carousel_disable_lazy_load_class']) : '';

        // store updated options array to database
        update_option('slc_initial_carousel_options', $options);

        // redirect the page to the configuration form
        wp_redirect(add_query_arg(array('page' => 'settings&post_type=slc_carousel', 'message' => '1'), admin_url('edit.php')));
        exit;
    }
}