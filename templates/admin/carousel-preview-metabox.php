<?php
defined( 'ABSPATH' ) || exit;


// get our post
global $post;
global $pagenow;

$output = '';

// if this is a new page
if ($pagenow != 'post-new.php') {
    // the logos this carousel shows: same query as the shortcode
    $query = new \WP_Query(\PLSimpleLogoCarousel\Base\CarouselConfig::logo_query_args(get_the_ID()));

    // if the query have posts
    if ($query->have_posts()) {
        $carouselId = (int) get_the_ID();
        $showTitle = 'true' === get_post_meta($carouselId, 'slc_carousel_show_title', true);
        $displayOrder = \PLSimpleLogoCarousel\Base\CarouselConfig::display_order($carouselId);

        $output .= '<div class="slc-logos slc-carousel-id-' . esc_attr($carouselId) . '" data-id="' . esc_attr($carouselId) . '">';

        foreach ($query->posts as $logo) {
            $logoId = (int) $logo->ID;
            $link = esc_url(get_post_meta($logoId, 'slc_external_url', true));
            $hoverText = (string) get_post_meta($logoId, 'slc_hover_text', true);
            $title = get_the_title($logo);

            // create a container for an individual logo
            $output .= '<div class="slc-logo" data-id="' . esc_attr($logoId) . '" data-order="' . esc_attr((int) ($displayOrder[$logoId] ?? 9999)) . '">';

            if ('' !== $link) {
                $output .= '<a href="' . $link . '" target="_blank" rel="noopener noreferrer">';
            }

            $output .= '<div class="slc-logo-container' . ('' === $hoverText ? ' slc-no-hover' : '') . '">';
            $output .= '<img src="' . esc_url(get_the_post_thumbnail_url($logoId, 'full')) . '" alt="' . esc_attr(\PLSimpleLogoCarousel\Base\CarouselConfig::alt_text($logoId)) . '" class="slc-logo-img"/>';

            if ('' !== $hoverText) {
                $output .= '<p class="slc-hover-text">' . esc_html($hoverText) . '</p>';
            }

            $output .= '</div>';

            if ($showTitle && '' !== $title) {
                $output .= '<p class="slc-logo-title">' . esc_html($title) . '</p>';
            }

            if ('' !== $link) {
                $output .= '</a>';
            }

            $output .= '</div>';
        }

        $output .= '</div>';
    }

    // the output above is already escaped; kses is a second line of defence
    $allowed_html = array(
        'div' => array(
            'class' => array(),
            'data-id' => array(),
            'data-order' => array()
        ),
        'a' => array(
            'href' => array(),
            'target' => array(),
            'rel' => array(),
        ),
        'p' => array(
            'class' => array(),
        ),
        'img' => array(
            'src' => array(),
            'alt' => array(),
            'class' => array(),
        ),
    );
    echo wp_kses($output, $allowed_html);
} else {
    echo '<p>' . esc_html__('To preview the carousel, please make sure you save your post first.', 'simple-logo-carousel') . '</p>';
}
