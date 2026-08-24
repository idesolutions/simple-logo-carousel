<?php
/**
 * Trigger this file on plugin uninstall.
 *
 * @package ide-solutions/simple-logo-carousel
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
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
    $post_ids = get_posts(array(
        'fields'         => 'ids',
        'numberposts'    => -1,
        'post_type'      => $post_type,
        'post_status'    => 'any',
        'no_found_rows'  => true,
        'suppress_filters' => true,
    ));

    foreach ($post_ids as $post_id) {
        wp_delete_post((int) $post_id, true);
    }
}
