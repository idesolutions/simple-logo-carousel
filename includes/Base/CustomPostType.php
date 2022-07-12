<?php

/**
 * Registers our custom post types
 *
 * @package productive-laziness/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Base;

class CustomPostType
{
    /**
     * register our post types
     */
    public function register()
    {
        add_action('init', array($this, 'create_carousel_post_type'));
        add_action('init', array($this, 'create_logo_post_type'));
    }

    /**
     * create our carousel post type
     */
    public function create_carousel_post_type()
    {
        $labels = array(
            'name' => __('Logo Carousels', 'simple-logo-carousel'),
            'singular_name' => __('Logo Carousel', 'simple-logo-carousel'),
            'add_new' => __('Add Carousel', 'simple-logo-carousel'),
            'add_new_item' => __('Add Carousel', 'simple-logo-carousel'),
            'edit' => __('Edit Carousel', 'simple-logo-carousel'),
            'edit_item' => __('Edit Carousel', 'simple-logo-carousel'),
            'new_item' => __('New Carousel', 'simple-logo-carousel'),
            'view' => __('View Carousel', 'simple-logo-carousel'),
            'view_item' => __('View Carousel', 'simple-logo-carousel'),
            'search_items' => __('Search Carousels', 'simple-logo-carousel'),
            'not_found' => __('No Carousels Found', 'simple-logo-carousel'),
            'not_found_in_trash' => __('No Carousels Found In Trash', 'simple-logo-carousel'),
            'parent' => __('Parent Carousel', 'simple-logo-carousel')
        );
        $args = array(
            'label' => __('Carousel', 'simple-logo-carousel'),
            'labels' => $labels,
            'supports' => array('title'),
            'taxonomies' => array('slc_logo_category'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 20,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'menu_icon' => 'dashicons-slides',
            'capabilities' => array(
                'edit_post' => 'edit_simple_logo_carousel',
                'read_post' => 'read_simple_logo_carousel',
                'delete_post' => 'delete_simple_logo_carousel',
                'edit_posts' => 'edit_simple_logo_carousels',
                'edit_others_posts' => 'edit_others_simple_logo_carousels',
                'publish_posts' => 'publish_simple_logo_carousels',
                'read_private_posts' => 'read_private_simple_logo_carousels',
                'create_posts' => 'edit_simple_logo_carousels',
            ),
            'map_meta_cap' => true
        );
        register_post_type('slc_carousel', $args);
    }

    /**
     * create our logo post type
     */
    public function create_logo_post_type()
    {
        $labels = array(
            'name' => __('Logos', 'simple-logo-carousel'),
            'singular_name' => __('Logo', 'simple-logo-carousel'),
            'add_new' => __('Add Logo', 'simple-logo-carousel'),
            'add_new_item' => __('Add Logo', 'simple-logo-carousel'),
            'edit' => __('Edit Logo', 'simple-logo-carousel'),
            'edit_item' => __('Edit Logo', 'simple-logo-carousel'),
            'new_item' => __('New Logo', 'simple-logo-carousel'),
            'view' => __('View Logo', 'simple-logo-carousel'),
            'view_item' => __('View Logo', 'simple-logo-carousel'),
            'search_items' => __('Search Logos', 'simple-logo-carousel'),
            'not_found' => __('No Logos Found', 'simple-logo-carousel'),
            'not_found_in_trash' => __('No Logos Found In Trash', 'simple-logo-carousel'),
            'parent' => __('Parent Logo', 'simple-logo-carousel')
        );
        $args = array(
            'label' => __('Logo', 'simple-logo-carousel'),
            'labels' => $labels,
            'supports' => array('title', 'thumbnail', 'page-attributes'),
            'taxonomies' => array('slc_logo_category'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=slc_carousel',
            'menu_position' => 20,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'menu_icon' => 'dashicons-slides',
            'capabilities' => array(
                'edit_post' => 'edit_simple_logo_carousel',
                'read_post' => 'read_simple_logo_carousel',
                'delete_post' => 'delete_simple_logo_carousel',
                'edit_posts' => 'edit_simple_logo_carousels',
                'edit_others_posts' => 'edit_others_simple_logo_carousels',
                'publish_posts' => 'publish_simple_logo_carousels',
                'read_private_posts' => 'read_private_simple_logo_carousels',
                'create_posts' => 'edit_simple_logo_carousels',
            ),
            'map_meta_cap' => true
        );
        register_post_type('slc_logo', $args);
    }
}