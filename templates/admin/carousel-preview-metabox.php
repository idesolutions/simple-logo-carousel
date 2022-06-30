<?php

// get our post
global $post;
global $pagenow;
$saved_post = $post;

// variables to hold our categories and queries
$categories = '';
$taxonomyQuery = array();
$output = '';

// if this is a new page
if ($pagenow != 'post-new.php') {
    // if there is a taxonomy
    if (get_the_terms(get_the_ID(), 'slc_logo_category')) {
        // for each category in the taxonomy
        foreach (get_the_terms(get_the_ID(), 'slc_logo_category') as $category) {
            // get the slug
            $categories .= $category->slug . ', ';
        }

        // remove the last comma from categories
        $categories = substr($categories, 0, -2);

        // turn our categories in an array
        $categories = explode(',', $categories);
    }

    // if the category string is not empty or null
    if ($categories != '' || $categories != null) {
        // foreach category in the array
        foreach ($categories as $category) {
            // create a temporary array
            $taxonomy = array('taxonomy' => 'slc_logo_category', 'field' => 'slug', 'terms' => $category);

            // push it into our query taxonomy array
            array_push($taxonomyQuery, $taxonomy);
        }

        // start a query to get the logos
        $query = new \WP_Query(
            array(
                'post_type' => 'slc_logo',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'tax_query' => array(
                    'relation' => 'OR',
                    $taxonomyQuery
                )
            )
        );
    } else {
        // start a query to get the logos
        $query = new \WP_Query(
            array(
                'post_type' => 'slc_logo',
                'posts_per_page' => -1,
                'post_status' => 'publish',
            )
        );
    }

    // if the query have posts
    if ($query->have_posts()) {

        $output .= '<div class="slc-logos slc-carousel-id-' . get_the_ID() . '" data-id="' . get_the_ID() . '">';

        // while there are posts
        while ($query->have_posts()) {
            $query->the_post();

            // create a container for an individual logo
            $output .= '<div class="slc-logo">';

            // if there is a link
            if (!empty(esc_url(get_post_meta(get_the_id(), 'slc_external_url', true)))) {
                // output the link
                $output .= '<a href="' . esc_url(get_post_meta(get_the_id(), 'slc_external_url', true)) . '" target="' . esc_html(get_post_meta(get_the_id(), 'slc_url_target', true)) . '">';
            }

            // if there is an alt text
            if (!empty(esc_html(get_post_meta(get_the_id(), 'slc_alt_text', true)))) {
                // set the alt text
                $altText = esc_html(get_post_meta(get_the_id(), 'slc_alt_text', true));
            } else {
                // if there is no alt text check if the image has one
                $thumbnail_id = get_post_thumbnail_id(get_the_ID());
                $imageAltText = esc_html(get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true));

                // if the alt tag on the image is not empty
                if ($imageAltText != '' && $imageAltText != null) {
                    // set the alt text
                    $altText = $imageAltText;
                } else {
                    // else if there is no alt text on the image default to the title if there is one
                    if (get_the_title() != '' && get_the_title() != null) {
                        $altText = get_the_title();
                    } else {
                        // default to the site title
                        $altText = get_bloginfo();
                    }
                }
            }

            // create logo container
            $output .= '<div class="slc-logo-container';

            // if hover text is empty
            if (empty(esc_html(get_post_meta(get_the_id(), 'slc_hover_text', true)))) {
                $output .= ' slc-no-hover';
            }

            $output .= '">';

            $output .= '<img src="' . get_the_post_thumbnail_url(get_the_id(), 'full') . '" alt="' . $altText . '" class="slc-logo-img"/>';

            // if there is a hover text
            if (!empty(esc_html(get_post_meta(get_the_id(), 'slc_hover_text', true)))) {
                // output the hover text
                $output .= '<p class="slc-hover-text">' . esc_html(get_post_meta(get_the_id(), 'slc_hover_text', true)) . '</p>';
            }

            $output .= '</div>';

            // if the carousel is displaying the logo title
            if (get_post_meta(get_the_ID(), 'slc_carousel_show_title', true) == 'true') {
                // if there is a title on the logo
                if (get_the_title() != '' && get_the_title() != null) {
                    // output the title
                    $output .= '<p class="slc-logo-title">' . get_the_title() . '</p>';
                }
            }

            // if there is a link
            if (!empty(esc_url(get_post_meta(get_the_id(), 'slc_external_url', true)))) {
                $output .= '</a>';
            }

            $output .= '</div>';
        }

        $output .= '</div>';
    }

    wp_reset_postdata();
    if ($saved_post) {
        $post = $saved_post;
    }

    echo $output;
} else {
    echo '<p>' . __('To preview the carousel, please make sure you save your post first.', 'simple-logo-carousel') . '</p>';
}