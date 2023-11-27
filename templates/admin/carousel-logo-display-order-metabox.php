<?php

// get our post
global $post;
global $pagenow;
$saved_post = $post;

// variables to hold our categories and queries
$categories = '';
$taxonomyQuery = array();
$output = '';

// get our meta information
$slcLogoDisplayOrder = get_post_meta($post->ID, 'slc_carousel_logo_display_order', true);

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
        $logoLabel = __('Logo', 'simple-logo-carousel');

        $output .= <<<TEMPLATE
        <table id="slc-logo-display-order-table">
            <thead>
                <tr>
                    <th></th>
                    <th>$logoLabel</th>
                </tr>
            </thead>
            <tbody class="slc-main-tbody">
TEMPLATE;

        // keep track of our post items
        $logos = array();

        // while there are posts
        while ($query->have_posts()) {
            $query->the_post();
            $altText = '';
            $logoPath = get_the_post_thumbnail_url(get_the_id(), 'thumbnail');
            $title = get_the_title();
            $id = get_the_id();

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

            // push post details to array
            array_push($logos, array(
                'id' => $id,
                'image' => $logoPath,
                'text' => $altText,
                'title' => $title,
            ));
        }

        // for each item in our logos
        foreach ($logos as $logo) {
            $order = 9999;
            $id = $logo['id'];
            $altText = $logo['text'];
            $title = $logo['title'];
            $image = $logo['image'];

            if (!empty($slcLogoDisplayOrder)) {
                $displayOrder = json_decode($slcLogoDisplayOrder);

                foreach ($displayOrder as $logoOrder) {
                    if ($logoOrder->id == $id) {
                        $order = $logoOrder->order;
                        break;
                    }
                }
            }

            $plugin_path = plugin_dir_url(dirname(__FILE__, 2)) . '/assets/admin/images/sort-solid.svg';
            $output .= <<<TEMPLATE
            <tr class="slc-draggable" data-order="$order" data-id="$id">
                <td>
                    <img class="slc-sort-btn" src="$plugin_path">
                </td>
                <td>
                    <img src="$image" alt="$altText" />
                    <p>$title</p>
                </td>
            </tr>
TEMPLATE;
        }

        $output .= '</tbody></table>';
    }

    wp_reset_postdata();
    if ($saved_post) {
        $post = $saved_post;
    }

    echo $output;
} else {
    echo '<p>' . __('To rearrange the logos, please make sure you save your post first', 'simple-logo-carousel') . '.</p>';
}
?>

<textarea name="slc_carousel_logo_display_order" id="slc-carousel-logo-display-order">
    <?php if (!empty($slcLogoDisplayOrder)) {
        echo esc_textarea($slcLogoDisplayOrder);
    } ?>
</textarea>
