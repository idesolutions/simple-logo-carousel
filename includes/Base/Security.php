<?php
/**
 * Security and strict value sanitization helpers.
 *
 * @package ide-solutions/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Base;

defined( 'ABSPATH' ) || exit;

final class Security
{
    public static function boolean($value, $default = 'false')
    {
        $value = is_scalar($value) ? sanitize_text_field((string) $value) : '';
        return in_array($value, array('true', 'false'), true) ? $value : $default;
    }

    public static function color($value, $default = '#222222')
    {
        $color = sanitize_hex_color(is_scalar($value) ? (string) $value : '');
        return $color ? $color : $default;
    }

    public static function opacity($value, $default = 0.8)
    {
        if (!is_numeric($value)) {
            return (float) $default;
        }
        return max(0, min(1, (float) $value));
    }

    public static function nonnegative_int($value, $default = 0)
    {
        if (!is_numeric($value)) {
            return (int) $default;
        }
        return max(0, (int) $value);
    }

    public static function positive_int($value, $default = 1)
    {
        $value = self::nonnegative_int($value, $default);
        return $value > 0 ? $value : (int) $default;
    }

    public static function css_dimension($value, $default = '0px')
    {
        $value = is_scalar($value) ? trim((string) $value) : '';
        if (preg_match('/^-?(?:\\d+(?:\\.\\d+)?|\\.\\d+)(?:px|em|rem|%|vw|vh)?$/i', $value)) {
            return $value;
        }
        return $default;
    }

    public static function animation($value, $default = 'ease')
    {
        $value = is_scalar($value) ? sanitize_key((string) $value) : '';
        return in_array($value, array('linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out'), true) ? $value : $default;
    }

    public static function vertical_alignment($value, $default = 'center')
    {
        $value = is_scalar($value) ? sanitize_key((string) $value) : '';
        return in_array($value, array('flex-start', 'center', 'flex-end'), true) ? $value : $default;
    }

    public static function url_target($value)
    {
        return '_blank' === $value ? '_blank' : '_self';
    }

    public static function class_list($value)
    {
        if (!is_scalar($value)) {
            return '';
        }
        $classes = preg_split('/\\s+/', trim((string) $value));
        $classes = array_filter(array_map('sanitize_html_class', $classes));
        return implode(' ', array_unique($classes));
    }

    public static function display_options_json($value)
    {
        $decoded = json_decode(is_scalar($value) ? (string) $value : '', true);
        if (!is_array($decoded)) {
            return '[{"breakpoint":"default","show":1,"scroll":1}]';
        }

        $clean = array();
        foreach ($decoded as $row) {
            if (!is_array($row)) {
                continue;
            }
            $breakpoint = isset($row['breakpoint']) && 'default' === (string) $row['breakpoint']
                ? 'default'
                : self::nonnegative_int($row['breakpoint'] ?? 0, 0);
            $clean[] = array(
                'breakpoint' => $breakpoint,
                'show'       => self::positive_int($row['show'] ?? 1, 1),
                'scroll'     => self::positive_int($row['scroll'] ?? 1, 1),
            );
        }

        if (empty($clean)) {
            $clean[] = array('breakpoint' => 'default', 'show' => 1, 'scroll' => 1);
        }
        return wp_json_encode($clean);
    }

    public static function display_order_json($value)
    {
        $decoded = json_decode(is_scalar($value) ? (string) $value : '', true);
        if (!is_array($decoded)) {
            return '';
        }
        $clean = array();
        foreach ($decoded as $row) {
            if (!is_array($row)) {
                continue;
            }
            $id = absint($row['id'] ?? 0);
            if (!$id) {
                continue;
            }
            $clean[] = array('id' => $id, 'order' => self::nonnegative_int($row['order'] ?? 9999, 9999));
        }
        return $clean ? wp_json_encode($clean) : '';
    }
}
