<?php

/**
 * Manages and handles everything relating to the logo post type
 * view which also includes rendering and saving the page data
 *
 * @package ide-solutions/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Pages;


defined( 'ABSPATH' ) || exit;
use PLSimpleLogoCarousel\Base\BaseController;
use PLSimpleLogoCarousel\Base\Security;

class Logo extends BaseController
{
    /**
     * register our logo post type data
     */
    public function register()
    {
        add_action('add_meta_boxes', array($this, 'create_metaboxes'));
        add_action('save_post_slc_logo', array($this, 'save_logo_meta'), 1, 2);
        add_action('admin_enqueue_scripts', array($this, 'admin_logo_script_and_styles'));
        add_filter('manage_slc_logo_posts_columns', array($this, 'logo_posts_columns'));
        add_action('manage_slc_logo_posts_custom_column', array($this, 'logo_column'), 10, 2);
        add_action('admin_menu', array($this, 'create_menus'));
    }

    /**
     * add an add logo option to the menu
     */
    public function create_menus()
    {
        // reference the submenu
        global $submenu;

        // add an "Add Logo" link to the cpt slc carousel
        $submenu['edit.php?post_type=slc_carousel'][] = array(__('Add Logo', 'simple-logo-carousel'), 'edit_simple_logo_carousels', 'post-new.php?post_type=slc_logo');
    }

    /**
     * create metaboxes
     */
    public function create_metaboxes()
    {
        add_meta_box(
            'slc_cpt_logo_options',
            __('Logo Options', 'simple-logo-carousel'),
            array($this, 'logo_options'),
            'slc_logo',
            'normal',
            'default'
        );
    }

    /**
     * renders the logo options metabox
     */
    function logo_options()
    {
        require_once $this->plugin_path . 'templates/admin/logo-metabox.php';
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

        // if we are in the logo custom post type
        if ($hook == 'post-new.php' || $hook == 'post.php' && $post->post_type === 'slc_logo') {
            wp_enqueue_style('admin_slc_stylesheet', $this->plugin_url . 'assets/admin/css/slc-admin.css');
        }
    }

    /**
     * saves our metabox data
     *
     * @param $post_id
     * @param $post
     */
    function save_logo_meta($post_id, $post)
    {
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id) || 'slc_logo' !== $post->post_type) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $nonce = isset($_POST['slc_cpt']) ? sanitize_text_field(wp_unslash($_POST['slc_cpt'])) : '';
        if (!$nonce || !wp_verify_nonce($nonce, 'verify_slc')) {
            return;
        }

        $external_url = isset($_POST['slc_external_url']) ? esc_url_raw(wp_unslash($_POST['slc_external_url'])) : '';
        $url_target   = isset($_POST['slc_url_target']) ? Security::url_target(wp_unslash($_POST['slc_url_target'])) : '_self';
        $alt_text     = isset($_POST['slc_alt_text']) ? sanitize_text_field(wp_unslash($_POST['slc_alt_text'])) : '';
        $hover_text   = isset($_POST['slc_hover_text']) ? sanitize_text_field(wp_unslash($_POST['slc_hover_text'])) : '';

        update_post_meta($post_id, 'slc_external_url', $external_url);
        update_post_meta($post_id, 'slc_url_target', $url_target);
        update_post_meta($post_id, 'slc_alt_text', $alt_text);
        update_post_meta($post_id, 'slc_hover_text', $hover_text);
    }

    /**
     * add our custom columns to the logo custom post type posts view
     *
     * @param $columns
     * @return array
     */
    function logo_posts_columns($columns)
    {
        // create an array of the columns we are displaying
        $columns = array(
            'cb' => $columns['cb'],
            'title' => __('Title', 'simple-logo-carousel'),
            'link_url' => __('Link URL', 'simple-logo-carousel'),
            'target' => __('Target', 'simple-logo-carousel'),
            'alt_text' => __('Alt Text', 'simple-logo-carousel'),
            'category' => __('Categories', 'simple-logo-carousel'),
            'logo' => __('Logo', 'simple-logo-carousel')
        );

        // return the array
        return $columns;
    }

    /**
     * display our logo details inside the columns in posts view
     *
     * @param $column
     * @param $post_id
     */
    function logo_column($column, $post_id)
    {
        // if there is a logo column
        if ('logo' === $column) {
            // echo out the logo
            echo wp_kses_post(get_the_post_thumbnail($post_id, array(80, 80)));
        } // if there is a link url column
        else if ('link_url' === $column) {
            echo '<a href="' . esc_url(get_post_meta($post_id, 'slc_external_url', true)) . '" target="_blank" rel="noopener noreferrer">' . esc_html(get_post_meta($post_id, 'slc_external_url', true)) . '</a>';
        } // if there is a target column
        else if ('target' === $column) {
            $target = esc_html(get_post_meta($post_id, 'slc_url_target', true));
            if ($target == '_blank') {
                _e('New Window or Tab', 'simple-logo-carousel');
            } else {
                _e('Same Window', 'simple-logo-carousel');
            }
        } // if there is a alt text column
        else if ('alt_text' === $column) {
            $altText = esc_html(get_post_meta($post_id, 'slc_alt_text', true));
            if ($altText == '') {
                echo '<em>' . esc_html('No alt text was provided.', 'simple-logo-carousel') . '</em>';
            } else {
                echo esc_html($altText);
            }
        } // if there is a category column
        else if ('category' === $column) {
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
                echo esc_html($categories);
            } else {
                // else inform there is not one
                echo '<em>' . esc_html__('No category found.', 'simple-logo-carousel') . '</em>';
            }
        }
    }
}