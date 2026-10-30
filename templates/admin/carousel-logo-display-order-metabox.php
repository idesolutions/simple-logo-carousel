<?php
defined( 'ABSPATH' ) || exit;


// get our post
global $post;
global $pagenow;
$saved_post = $post;
$carouselId = $post ? (int) $post->ID : 0;

$output = '';

// get our meta information
$slcLogoDisplayOrder = get_post_meta($carouselId, 'slc_carousel_logo_display_order', true);
$savedOrder = \PLSimpleLogoCarousel\Base\CarouselConfig::display_order($carouselId);

// if this is a new page
if ($pagenow != 'post-new.php') {
    // the logos this carousel shows: same query as the shortcode
    $query = new \WP_Query(\PLSimpleLogoCarousel\Base\CarouselConfig::logo_query_args(get_the_ID()));

    // if the query have posts
    if ($query->have_posts()) {
        $logoLabel = __('Logo', 'simple-logo-carousel');

        $output .= '<table id="slc-logo-display-order-table">'
            . '<thead><tr><th></th><th>' . esc_html($logoLabel) . '</th></tr></thead>'
            . '<tbody class="slc-main-tbody">';

        // keep track of our post items
        $logos = array();

        // while there are posts
        while ($query->have_posts()) {
            $query->the_post();
            $logoPath = get_the_post_thumbnail_url(get_the_id(), 'thumbnail');
            $title = get_the_title();
            $id = get_the_id();

            $altText = \PLSimpleLogoCarousel\Base\CarouselConfig::alt_text($id);

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
            $id = $logo['id'];
            $altText = $logo['text'];
            $title = $logo['title'];
            $image = $logo['image'];
            $order = $savedOrder[$id] ?? 9999;
            $plugin_path = plugin_dir_url(dirname(__FILE__, 2)) . '/assets/admin/images/sort-solid.svg';
            $output .= '<tr class="slc-draggable" data-order="' . esc_attr((int) $order) . '" data-id="' . esc_attr((int) $id) . '">';
            $output .= '<td><img class="slc-sort-btn" src="' . esc_url($plugin_path) . '" alt="" /></td>';
            $output .= '<td><img src="' . esc_url($image) . '" alt="' . esc_attr($altText) . '" /><p>' . esc_html($title) . '</p></td>';
            $output .= '</tr>';
        }

        $output .= '</tbody></table>';
    }

    wp_reset_postdata();
    if ($saved_post) {
        $post = $saved_post;
    }

    echo wp_kses_post($output);
} else {
    echo '<p>' . esc_html__('To rearrange the logos, please make sure you save your post first', 'simple-logo-carousel') . '.</p>';
}
?>

<textarea name="slc_carousel_logo_display_order" id="slc-carousel-logo-display-order">
    <?php if (!empty($slcLogoDisplayOrder)) {
        echo esc_textarea($slcLogoDisplayOrder);
    } ?>
</textarea>
