<?php
/* 
Plugin Name: Simple Logo Carousel
Plugin URI: https://www.ideinteractive.com/products/simple-logo-carousel/
Description: A simplistic carousel to elegantly display logos. Powered by <a href="http://kenwheeler.github.io/slick/">slick</a>.
Version: 1.9.1
Author: IDE Interactive
Author URI: https://www.ideinteractive.com/
License: GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

Simple Logo Carousel is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Simple Logo Carousel is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Simple Logo Carousel. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

// prevent this file from being accessed directly
defined('ABSPATH') or die('Permission denied.');

// load our autoload if the file exists
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

/**
 * run our during plugin activation
 */
function activate_plslc_plugin()
{
    PLSimpleLogoCarousel\Base\Activate::activate();
}

register_activation_hook(__FILE__, 'activate_plslc_plugin');

/**
 * run when plugin gets deactivated
 */
function deactivate_plslc_plugin()
{
    PLSimpleLogoCarousel\Base\Deactivate::deactivate();
}

register_deactivation_hook(__FILE__, 'deactivate_plslc_plugin');

// register the services
if (class_exists('PLSimpleLogoCarousel\\Init')) {
    PLSimpleLogoCarousel\Init::register_services();
}