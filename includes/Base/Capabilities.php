<?php

/**
 * Registers our capabilities
 *
 * @package productive-laziness/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Base;

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
     * create our capabilities
     */
    public function create_capabilities()
    {
        // gets the administrator role
        $role = get_role('administrator');

        // add in the capabilities
        $role->add_cap('edit_simple_logo_carousel');
        $role->add_cap('read_simple_logo_carousel');
        $role->add_cap('delete_simple_logo_carousel');
        $role->add_cap('edit_simple_logo_carousels');
        $role->add_cap('edit_others_simple_logo_carousels');
        $role->add_cap('publish_simple_logo_carousels');
        $role->add_cap('read_private_simple_logo_carousels');
        $role->add_cap('manage_simple_logo_carousel_terms');
        $role->add_cap('edit_simple_logo_carousel_terms');
        $role->add_cap('delete_simple_logo_carousel_terms');
        $role->add_cap('assign_simple_logo_carousel_terms');
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