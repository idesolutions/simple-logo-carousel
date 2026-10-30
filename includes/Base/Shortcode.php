<?php

/**
 * Registers our shortcodes
 *
 * @package ide-solutions/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Base;


defined( 'ABSPATH' ) || exit;
class Shortcode extends BaseController
{
    /**
     * register our shortcode
     */
    public function register()
    {
        add_shortcode('simple-logo-carousel', array($this, 'carousel_shortcode'));
    }

    /**
     * outputs our carousel shortcode
     *
     * @param array|string $atts
     * @return string
     */
    function carousel_shortcode($atts = [])
    {
        $atts = shortcode_atts(array('id' => ''), $atts, 'simple-logo-carousel');
        $id = absint($atts['id']);

        // Only render published carousel records.
        if (!$id || 'slc_carousel' !== get_post_type($id) || 'publish' !== get_post_status($id)) {
            // Tell editors what is wrong; show visitors nothing.
            if (current_user_can('edit_posts')) {
                return '<p>' . esc_html__('Please make sure your carousel shortcode contains the ID of a published carousel.', 'simple-logo-carousel') . '</p>';
            }
            return '';
        }

        $this->enqueue_assets($id);

        // WP_Query (not get_posts) so multilingual and other query filters still apply, as before
        $query = new \WP_Query(CarouselConfig::logo_query_args($id));
        $logos = $query->posts;
        if (empty($logos)) {
            return '';
        }

        $displayOrder = CarouselConfig::display_order($id);
        $disableLazyLoadClass = Security::class_list(get_post_meta($id, 'slc_carousel_disable_lazy_load_class', true));
        $showTitle = 'true' === get_post_meta($id, 'slc_carousel_show_title', true);

        $output = '<div class="slc-logos slc-carousel-id-' . esc_attr($id) . '" data-id="' . esc_attr($id) . '">';

        foreach ($logos as $logo) {
            $logoId = (int) $logo->ID;
            $imageUrl = get_the_post_thumbnail_url($logoId, 'full');

            // a logo without an image has nothing to show
            if (!$imageUrl) {
                continue;
            }

            $link = esc_url(get_post_meta($logoId, 'slc_external_url', true));
            $hoverText = (string) get_post_meta($logoId, 'slc_hover_text', true);
            $title = get_the_title($logo);

            $output .= '<div class="slc-logo" data-order="' . esc_attr((int) ($displayOrder[$logoId] ?? 9999)) . '">';

            if ('' !== $link) {
                $target = Security::url_target(get_post_meta($logoId, 'slc_url_target', true));
                $rel = '_blank' === $target ? ' rel="noopener noreferrer"' : '';
                $output .= '<a href="' . $link . '" target="' . esc_attr($target) . '"' . $rel . '>';
            }

            $output .= '<div class="slc-logo-container' . ('' === $hoverText ? ' slc-no-hover' : '') . '">';
            $output .= '<img src="' . esc_url($imageUrl) . '" alt="' . esc_attr(CarouselConfig::alt_text($logoId)) . '" class="slc-logo-img' . ('' !== $disableLazyLoadClass ? ' ' . esc_attr($disableLazyLoadClass) : '') . '"/>';

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

        return $output;
    }

    /**
     * enqueue the scripts and styles a carousel needs
     *
     * @param int $id
     */
    private function enqueue_assets($id)
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('slc_slick_js', $this->plugin_url . 'assets/public/libs/slick/slick.min.js', array('jquery'), SLC_VERSION, true);
        wp_localize_script('slc_slick_js', 'slc_carousel_params_' . $id, CarouselConfig::options($id));
        wp_enqueue_script('slc_main_js', $this->plugin_url . 'assets/public/js/slc-main.js', array('jquery', 'slc_slick_js'), SLC_VERSION, true);

        wp_enqueue_style('slc_main_css', $this->plugin_url . 'assets/public/css/slc-main.css', array(), SLC_VERSION);
        wp_add_inline_style('slc_main_css', CarouselConfig::css($id));
    }
}
