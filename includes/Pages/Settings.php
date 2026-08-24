<?php

/**
 * Manages and handles the settings page
 *
 * @package ide-solutions/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Pages;


defined( 'ABSPATH' ) || exit;
use PLSimpleLogoCarousel\Base\BaseController;
use PLSimpleLogoCarousel\Base\Security;

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
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You are not allowed to manage these settings.', 'simple-logo-carousel'));
        }

        check_admin_referer('slc_update_initial_carousel_options_nonce');
        $post_data = wp_unslash($_POST);
        $options = $this->create_initial_carousel_options();

        $options['slc_carousel_show_title'] = Security::boolean($post_data['slc_carousel_show_title'] ?? 'false');
        $options['slc_carousel_title_color'] = Security::color($post_data['slc_carousel_title_color'] ?? '', '#222222');
        $options['slc_carousel_hover_text_color'] = Security::color($post_data['slc_carousel_hover_text_color'] ?? '', '#222222');
        $options['slc_carousel_hover_text_background_color'] = Security::color($post_data['slc_carousel_hover_text_background_color'] ?? '', '#ffffff');
        $options['slc_carousel_hover_text_background_color_opacity'] = Security::opacity($post_data['slc_carousel_hover_text_background_color_opacity'] ?? 0.8, 0.8);
        $options['slc_carousel_accessibility'] = Security::boolean($post_data['slc_carousel_accessibility'] ?? 'true', 'true');
        $options['slc_carousel_autoplay'] = Security::boolean($post_data['slc_carousel_autoplay'] ?? 'true', 'true');
        $options['slc_carousel_autoplay_speed'] = Security::nonnegative_int($post_data['slc_carousel_autoplay_speed'] ?? 3000, 3000);
        $options['slc_carousel_arrows'] = Security::boolean($post_data['slc_carousel_arrows'] ?? 'false');
        $options['slc_carousel_arrow_color'] = Security::color($post_data['slc_carousel_arrow_color'] ?? '', '#222222');
        $options['slc_carousel_arrow_size'] = Security::css_dimension($post_data['slc_carousel_arrow_size'] ?? '', '50px');
        $options['slc_carousel_arrow_offset'] = Security::css_dimension($post_data['slc_carousel_arrow_offset'] ?? '', '-25px');
        $options['slc_carousel_custom_arrows'] = Security::boolean($post_data['slc_carousel_custom_arrows'] ?? 'false');
        $options['slc_carousel_left_arrow_image'] = esc_url_raw($post_data['slc_carousel_left_arrow_image'] ?? '');
        $options['slc_carousel_right_arrow_image'] = esc_url_raw($post_data['slc_carousel_right_arrow_image'] ?? '');
        $options['slc_carousel_arrow_image_max_width'] = Security::css_dimension($post_data['slc_carousel_arrow_image_max_width'] ?? '', '50px');
        $options['slc_carousel_center_mode'] = Security::boolean($post_data['slc_carousel_center_mode'] ?? 'false');
        $options['slc_carousel_animation'] = Security::animation($post_data['slc_carousel_animation'] ?? 'ease');
        $options['slc_carousel_draggable'] = Security::boolean($post_data['slc_carousel_draggable'] ?? 'true', 'true');
        $options['slc_carousel_pause_on_hover'] = Security::boolean($post_data['slc_carousel_pause_on_hover'] ?? 'true', 'true');
        $options['slc_carousel_pause_on_focus'] = Security::boolean($post_data['slc_carousel_pause_on_focus'] ?? 'true', 'true');
        $options['slc_carousel_speed'] = Security::positive_int($post_data['slc_carousel_speed'] ?? 1000, 1000);
        $options['slc_carousel_random_order'] = Security::boolean($post_data['slc_carousel_random_order'] ?? 'false');
        $options['slc_carousel_swipe'] = Security::boolean($post_data['slc_carousel_swipe'] ?? 'true', 'true');
        $options['slc_carousel_slide_vertical_alignment'] = Security::vertical_alignment($post_data['slc_carousel_slide_vertical_alignment'] ?? 'center');
        $options['slc_carousel_disable_lazy_load_class'] = Security::class_list($post_data['slc_carousel_disable_lazy_load_class'] ?? '');

        update_option('slc_initial_carousel_options', $options);

        wp_safe_redirect(add_query_arg(array(
            'post_type' => 'slc_carousel',
            'page'      => 'settings',
            'message'   => '1',
        ), admin_url('edit.php')));
        exit;
    }
}