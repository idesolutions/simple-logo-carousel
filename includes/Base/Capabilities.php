<?php

/**
 * Registers our capabilities
 *
 * @package ide-solutions/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Base;


defined( 'ABSPATH' ) || exit;
class Capabilities
{
    /**
     * register our capabilities
     */
    public function register()
    {
        add_action('init', array($this, 'create_capabilities'));
        add_action('admin_menu', array($this, 'set_menu_item_view_state'));
    }

    /**
     * every capability our post types and taxonomy use
     *
     * Each post type capability is defined explicitly (see CustomPostType) so nothing falls back to WordPress's
     * core post capabilities: an Editor, for instance, cannot delete carousels just because they can delete posts.
     *
     * @return string[]
     */
    public static function all()
    {
        return array(
            'edit_simple_logo_carousel',
            'read_simple_logo_carousel',
            'delete_simple_logo_carousel',
            'edit_simple_logo_carousels',
            'edit_others_simple_logo_carousels',
            'edit_private_simple_logo_carousels',
            'edit_published_simple_logo_carousels',
            'publish_simple_logo_carousels',
            'read_private_simple_logo_carousels',
            'delete_simple_logo_carousels',
            'delete_others_simple_logo_carousels',
            'delete_private_simple_logo_carousels',
            'delete_published_simple_logo_carousels',
            'manage_simple_logo_carousel_terms',
            'edit_simple_logo_carousel_terms',
            'delete_simple_logo_carousel_terms',
            'assign_simple_logo_carousel_terms',
        );
    }

    /**
     * give the administrator role our capabilities (only the ones it is missing, so nothing is rewritten on
     * every request)
     */
    public function create_capabilities()
    {
        $role = get_role('administrator');
        if (!$role) {
            return;
        }

        foreach (self::all() as $capability) {
            if (!$role->has_cap($capability)) {
                $role->add_cap($capability);
            }
        }
    }

    /**
     * hide the carousel menu from users without capabilities
     */
    function set_menu_item_view_state()
    {
        if (!current_user_can('edit_simple_logo_carousels')):
            remove_menu_page('edit.php?post_type=slc_carousel');
        endif;
    }
}