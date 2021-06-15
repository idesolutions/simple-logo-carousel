<?php
/**
 * Trigger this file on plugin uninstall
 *
 * @package productive-laziness/simple-logo-carousel
 */

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;

// delete our slc options
delete_option('slc_initial_carousel_options');
delete_site_option('slc_initial_carousel_options');

// delete all terms in custom taxonomy
$wpdb->query("
    DELETE FROM
    {$wpdb->terms}
    WHERE term_id IN
    ( SELECT * FROM (
        SELECT {$wpdb->terms}.term_id
        FROM {$wpdb->terms}
        JOIN {$wpdb->term_taxonomy}
        ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id
        WHERE taxonomy = 'slc_logo_category'
    ) as T
    );
");
$wpdb->query("DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'films_category'");

// delete our logos and posts
$posts = get_posts(
    array(
        'numberposts' => -1,
        'post_type' => 'slc_carousel',
        'post_status' => 'any',
    )
);

foreach ($posts as $post) {
    wp_delete_post($post->ID, true);
}

$posts = get_posts(
    array(
        'numberposts' => -1,
        'post_type' => 'slc_logo',
        'post_status' => 'any',
    )
);

foreach ($posts as $post) {
    wp_delete_post($post->ID, true);
}