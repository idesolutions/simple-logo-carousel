<?php
/**
 * Manages our plugin deactivation
 *
 * @package ide-solutions/simple-logo-carousel
 */

namespace PLSimpleLogoCarousel\Base;


defined( 'ABSPATH' ) || exit;
class Deactivate
{
    /**
     * runs on plugin deactivation
     */
    public static function deactivate()
    {
        // flush rewrite rules
        flush_rewrite_rules();
    }
}
