<?php


/**
 * Registers our custom taxonomies
 *
 * @package productive-laziness/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Base;

class Taxonomy
{
    /**
     * register our taxonomies
     */
    public function register()
    {
        add_action('init', array($this, 'create_logo_taxonomy'));
    }

    /**
     * create our logo taxonomy
     */
    public function create_logo_taxonomy()
    {
        $labels = array(
            'name' => __('Logo Categories', 'simple-logo-carousel'),
            'singular_name' => __('Logo Category', 'simple-logo-carousel'),
            'search_items' => __('Search Logo Categories', 'simple-logo-carousel'),
            'all_items' => __('All Logo Categories', 'simple-logo-carousel'),
            'parent_item' => __('Parent Logo Category', 'simple-logo-carousel'),
            'parent_item_colon' => __('Parent Logo Category:', 'simple-logo-carousel'),
            'edit_item' => __('Edit Logo Category', 'simple-logo-carousel'),
            'update_item' => __('Update Logo Category', 'simple-logo-carousel'),
            'add_new_item' => __('Add Logo Category', 'simple-logo-carousel'),
            'new_item_name' => __('New Logo Category Name', 'simple-logo-carousel'),
            'menu_name' => __('Logo Categories', 'simple-logo-carousel'),
        );

        // register the taxonomy
        register_taxonomy(
            'slc_logo_category',
            array(
                'slc_carousel',
                'slc_logo'
            ),
            array(
                'labels' => $labels,
                'public' => false,
                'publicly_queryable' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => false,
                'show_admin_column' => true,
                'hierarchical' => true,
                'query_var' => false,
                'rewrite' => false,
                'capabilities' => array(
                    'manage_terms' => 'manage_simple_logo_carousel_terms',
                    'edit_terms' => 'edit_simple_logo_carousel_terms',
                    'delete_terms' => 'delete_simple_logo_carousel_terms',
                    'assign_terms' => 'assign_simple_logo_carousel_terms',
                )
            )
        );
    }
}