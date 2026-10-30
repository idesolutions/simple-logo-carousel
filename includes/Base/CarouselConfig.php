<?php
/**
 * Builds everything a carousel renders from its saved settings: inline CSS, slick options, the logo query and
 * alt text. The front-end shortcode and the admin metaboxes share this so they cannot drift apart.
 *
 * @package ide-solutions/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Base;

defined( 'ABSPATH' ) || exit;

final class CarouselConfig
{
    /**
     * Inline CSS for one carousel. Every value passes through Security, so only colors, numbers and a fixed set
     * of keywords can reach the stylesheet.
     *
     * @param int $id Carousel post ID.
     * @return string
     */
    public static function css($id)
    {
        $id = absint($id);

        $titleColor = Security::color(get_post_meta($id, 'slc_carousel_title_color', true), '#222222');
        $hoverTextColor = Security::color(get_post_meta($id, 'slc_carousel_hover_text_color', true), '#222222');
        $hoverTextBackgroundColor = Security::color(get_post_meta($id, 'slc_carousel_hover_text_background_color', true), '#ffffff');
        $hoverTextBackgroundColorOpacity = Security::opacity(get_post_meta($id, 'slc_carousel_hover_text_background_color_opacity', true), 0.8);
        $slideVerticalAlignment = Security::vertical_alignment(get_post_meta($id, 'slc_carousel_slide_vertical_alignment', true));
        $arrowImageMaxWidth = Security::css_dimension(get_post_meta($id, 'slc_carousel_arrow_image_max_width', true), '50px');
        $arrowColor = Security::color(get_post_meta($id, 'slc_carousel_arrow_color', true), '#222222');
        $arrowSize = Security::css_dimension(get_post_meta($id, 'slc_carousel_arrow_size', true), '50px');
        $arrowOffset = Security::css_dimension(get_post_meta($id, 'slc_carousel_arrow_offset', true), '-10px');

        $css = ".slc-carousel-id-{$id} .slc-logo-title { color: {$titleColor}; } .slc-carousel-id-{$id}.slc-logos { padding: 0 } .slc-carousel-id-{$id} .slc-hover-text { color: {$hoverTextColor}; } .slc-carousel-id-{$id} .slc-logo-container:before { background-color: {$hoverTextBackgroundColor}; opacity: {$hoverTextBackgroundColorOpacity}; } .slc-carousel-id-{$id}.slick-initialized .slick-track { display: flex; align-items: {$slideVerticalAlignment}; }";

        if (!self::uses_custom_arrows($id)) {
            $css .= " .slc-carousel-id-{$id} .slick-prev { font-size: {$arrowSize}; line-height: {$arrowSize}; color: {$arrowColor}; margin-top: calc(-{$arrowSize}/2); left:{$arrowOffset} } .slc-carousel-id-{$id} .slick-next { font-size: {$arrowSize}; line-height: {$arrowSize}; color: {$arrowColor}; margin-top: calc(-{$arrowSize}/2); right:{$arrowOffset} }";
        } else {
            $css .= " .slc-carousel-id-{$id} .slick-prev.slick-custom-arrow, .slc-carousel-id-{$id} .slick-next.slick-custom-arrow { max-width: {$arrowImageMaxWidth} } .slc-carousel-id-{$id} .slick-prev { left:{$arrowOffset} } .slc-carousel-id-{$id} .slick-next { right:{$arrowOffset} }";
        }

        return $css;
    }

    /**
     * Options handed to slick through wp_localize_script().
     *
     * @param int $id Carousel post ID.
     * @return array<string, mixed>
     */
    public static function options($id)
    {
        $id = absint($id);

        $prevArrow = '<button type="button" class="slick-prev">&#x276C;</button>';
        $nextArrow = '<button type="button" class="slick-next">&#x276D;</button>';

        if (self::uses_custom_arrows($id)) {
            $prevArrow = '<img src="' . esc_url(get_post_meta($id, 'slc_carousel_left_arrow_image', true)) . '" class="slick-prev slick-custom-arrow" alt="' . esc_attr__('Arrow to go to previous logo on carousel.', 'simple-logo-carousel') . '"/>';
            $nextArrow = '<img src="' . esc_url(get_post_meta($id, 'slc_carousel_right_arrow_image', true)) . '" class="slick-next slick-custom-arrow" alt="' . esc_attr__('Arrow to go to next logo on carousel.', 'simple-logo-carousel') . '"/>';
        }

        $flag = static function ($key) use ($id) {
            return 'true' === get_post_meta($id, $key, true);
        };

        $options = array(
            'options' => array(
                'autoplay' => $flag('slc_carousel_autoplay'),
                'autoplaySpeed' => Security::nonnegative_int(get_post_meta($id, 'slc_carousel_autoplay_speed', true), 3000),
                'arrows' => $flag('slc_carousel_arrows'),
                'prevArrow' => $prevArrow,
                'nextArrow' => $nextArrow,
                'centerMode' => $flag('slc_carousel_center_mode'),
                'cssEase' => Security::animation(get_post_meta($id, 'slc_carousel_animation', true)),
                'draggable' => $flag('slc_carousel_draggable'),
                'pauseOnFocus' => $flag('slc_carousel_pause_on_focus'),
                'pauseOnHover' => $flag('slc_carousel_pause_on_hover'),
                'speed' => Security::nonnegative_int(get_post_meta($id, 'slc_carousel_speed', true), 1000),
                'randomOrder' => $flag('slc_carousel_random_order'),
                'swipe' => $flag('slc_carousel_swipe'),
            ),
            'breakpoints' => array(),
        );

        $rows = json_decode((string) get_post_meta($id, 'slc_carousel_logo_display_options', true), true);
        if (is_array($rows)) {
            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $options['breakpoints'][] = array(
                    'breakpoint' => (isset($row['breakpoint']) && 'default' === (string) $row['breakpoint']) ? 'default' : absint($row['breakpoint'] ?? 0),
                    'show' => max(1, absint($row['show'] ?? 1)),
                    'scroll' => max(1, absint($row['scroll'] ?? 1)),
                );
            }
        }

        if (empty($options['breakpoints'])) {
            $options['breakpoints'][] = array('breakpoint' => 'default', 'show' => 1, 'scroll' => 1);
        }

        return $options;
    }

    /**
     * Arguments for the query that fetches a carousel's logos: published logos, from any of the carousel's
     * categories (or all logos when it has none), in date or random order.
     *
     * @param int $id Carousel post ID.
     * @return array<string, mixed>
     */
    public static function logo_query_args($id)
    {
        $id = absint($id);

        $args = array(
            'post_type' => 'slc_logo',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'true' === get_post_meta($id, 'slc_carousel_random_order', true) ? 'rand' : 'date',
            'no_found_rows' => true,
        );

        $terms = get_the_terms($id, 'slc_logo_category');
        if (!empty($terms) && !is_wp_error($terms)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'slc_logo_category',
                    'field' => 'term_id',
                    'terms' => array_map('intval', wp_list_pluck($terms, 'term_id')),
                    'operator' => 'IN',
                ),
            );
        }

        return $args;
    }

    /**
     * Saved display order as a map of logo ID to position.
     *
     * @param int $id Carousel post ID.
     * @return array<int, int>
     */
    public static function display_order($id)
    {
        $rows = json_decode((string) get_post_meta(absint($id), 'slc_carousel_logo_display_order', true), true);
        $order = array();
        if (is_array($rows)) {
            foreach ($rows as $row) {
                if (is_array($row) && !empty($row['id'])) {
                    $order[absint($row['id'])] = Security::nonnegative_int($row['order'] ?? 9999, 9999);
                }
            }
        }
        return $order;
    }

    /**
     * Alt text for a logo, as plain text for the caller to escape: its own alt text, then the image's, then the
     * logo title, then the site name.
     *
     * @param int $logo_id Logo post ID.
     * @return string
     */
    public static function alt_text($logo_id)
    {
        $logo_id = absint($logo_id);

        $alt = (string) get_post_meta($logo_id, 'slc_alt_text', true);

        if ('' === $alt) {
            $thumbnail_id = get_post_thumbnail_id($logo_id);
            $alt = $thumbnail_id ? (string) get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true) : '';
        }
        if ('' === $alt) {
            $alt = wp_strip_all_tags(get_the_title($logo_id));
        }
        if ('' === $alt) {
            $alt = get_bloginfo('name');
        }

        return $alt;
    }

    /**
     * Whether the carousel uses its own arrow images instead of the built-in arrows.
     *
     * @param int $id Carousel post ID.
     * @return bool
     */
    private static function uses_custom_arrows($id)
    {
        return 'true' === Security::boolean(get_post_meta($id, 'slc_carousel_custom_arrows', true), 'false');
    }
}
