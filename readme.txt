=== Simple Logo Carousel ===
Contributors: tvledesign, ideinteractive
Tags: logo carousel, logo slider, carousel, slider
Requires at least: 5.0
Tested up to: 6.8.1
Requires PHP: 7.4
Stable tag: 1.9.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simplistic carousel to elegantly display logos. Powered by slick.

== Description ==
Simple Logo Carousel is a free WordPress plugin powered by [slick](http://kenwheeler.github.io/slick/). The plugin helps you create and manage logos that can be rearranged into carousels to be displayed anywhere on your site.

= Features =

* Powered by slick.
* Easily upload and manage logos.
* Link logos to an internal or external link.
* Categorize logos for different carousels.
* Display carousels anywhere on your site via a shortcode.

== Installation ==
1. Upload the entire simple-logo-carousel folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

You will then find a 'Logo Carousels' menu in your WordPress admin panel.

== Frequently Asked Questions ==
= Do I need to select a category when creating my carousel? =
No. By default, if you select no categories, all logos will be displayed.

= Is there a premium version of Simple Logo Carousel? =
No. Simple Logo Carousel is a free plugin and at this time, will not have a premium version.

== Screenshots ==

1. View of all the logos under the Logos post type.
2. View of the Logo Options when creating a new Logo.
3. View of the carousel under the Logo Carousels post type.
4. View of the carousel's shortcode and logo display order.
5. General view of the carousel options and logo display options.
6. View of the logo carousel on the website.

== Changelog ==

= 1.9.4 =

* Fixed vulnerability for Stored Cross-Site Scripting via the "id" parameter due to insufficient input sanitization and output escaping.

= 1.9.3 =

* Allow Autoplay Speed to be saved as 0 to allow for continuous scrolling when Animation is set to Linear.

= 1.9.2 =

* Added reordering preview support for when editing logo carousel in admin.

= 1.9.1 =

* Added in swipeToSlide and touchThreshold on Slick.js initialize to add support for better touch and drag.

= 1.9 =

* Added "Disable Lazy Load Class" option to Settings and Carousel Settings. You can now add class (or classes) to disable images on the carousel from lazy loading depending on the lazy load plugin you're using.

= 1.8 =

* Updated escaping and sanitization locations.
* Set default CSS to margin 0 and padding 0 for hover text.
* Fixed breakpoint bug where breakpoints were not saving in the admin.

= 1.7 =

* Added preview area in Logo Carousels.
* Added logo display order option in Logo Carousels.
* Remove page attributes from Logos since they are no longer used.
* Updated it so arrow offset also applies to custom arrow images.
* Center logos by default on carousel with margin auto.

= 1.6 =

* Updated main stylesheet so styles only apply to plugin's carousels in case slick is being used elsewhere.
* Made minor edits in admin and public JavaScript files where "==" is now "===".
* Updated plugin logo.
* Fixed it so sort icon shows up when adding new breakpoints in the Logo Display Options.
* Fixed it so you can drag and drop newly made breakpoints without reloading the page in the Logo Display Options.
* Updated drag and drop functionality to prevent dragging rows into each other by accident.
* Dropped support for PHP 5.6 and lower.

= 1.5 =

* Plugin recoded to add composer autoload support for better organization.
* Added translation support.
* Fixed typo in read_private_posts permissions from "read_privae_simple_logo_carousels" to "read_private_simple_logo_carousels".
* Added ability to add multiple breakpoints to define more slides to show and scroll options.
* Removed "How To" dedicated page and added condensed "How To" box to the side in Logo Carousel post screen.
* Changed carousel options field name.
* Removed left/right padding on .slc-logos where it was accidentally using the arrow's image max width.
* Added Settings page to set Global Carousel Options. These settings will automatically be loaded into new carousels so you don't have to keep reconfiguring the same setup.

= 1.4 =

* Fixed carousel logos padding.
* Added slide vertical alignment option for carousels.
* Updated How To page.

= 1.3 =

* Added ability to display text over logo image on hover.
* Fixed issue where carousel styles are overwriting each other.

= 1.2 =

* Added ability to include logo title underneath logo in carousel options.
* Added ability to use custom arrow images instead of default text option.
* Fixed "Cannot read property 'add' of null" when multiple carousels in use on same page.

= 1.1 =

* Fixed arrow color and size not appearing properly.
* Fixed "jQuery Is Not Defined" error when jQuery isn't present when plugin is activated.

= 1.0 =

* Initial release.

== Upgrade Notice ==
= 1.5 =
If you are upgrading from any prior versions to 1.5, please bear in mind your carousel options will be cleared. You will have to reconfigure your carousel options again to have them displayed properly.