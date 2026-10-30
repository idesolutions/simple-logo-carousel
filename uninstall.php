<?php
/**
 * Trigger this file on plugin uninstall.
 *
 * @package ide-solutions/simple-logo-carousel
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// remove the capabilities we added to the administrator role
$slc_role = get_role('administrator');
if ($slc_role) {
    foreach (array(
        'edit_simple_logo_carousel', 'read_simple_logo_carousel', 'delete_simple_logo_carousel',
        'edit_simple_logo_carousels', 'edit_others_simple_logo_carousels', 'edit_private_simple_logo_carousels',
        'edit_published_simple_logo_carousels', 'publish_simple_logo_carousels', 'read_private_simple_logo_carousels',
        'delete_simple_logo_carousels', 'delete_others_simple_logo_carousels', 'delete_private_simple_logo_carousels',
        'delete_published_simple_logo_carousels', 'manage_simple_logo_carousel_terms',
        'edit_simple_logo_carousel_terms', 'delete_simple_logo_carousel_terms', 'assign_simple_logo_carousel_terms',
    ) as $slc_cap) {
        $slc_role->remove_cap($slc_cap);
    }
}

delete_option('slc_initial_carousel_options');
delete_site_option('slc_initial_carousel_options');

$terms = get_terms(array(
    'taxonomy'   => 'slc_logo_category',
    'hide_empty' => false,
    'fields'     => 'ids',
));

if (!is_wp_error($terms)) {
    foreach ($terms as $term_id) {
        wp_delete_term((int) $term_id, 'slc_logo_category');
    }
}

foreach (array('slc_carousel', 'slc_logo') as $post_type) {
    $slc_query = new WP_Query(array(
        'fields'         => 'ids',
        'posts_per_page' => -1,
        'post_type'      => $post_type,
        'post_status'    => 'any',
        'no_found_rows'  => true,
    ));

    foreach ($slc_query->posts as $post_id) {
        wp_delete_post((int) $post_id, true);
    }
}
