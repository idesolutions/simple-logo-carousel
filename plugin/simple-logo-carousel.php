<?php 
/* 
Plugin Name: Simple Logo Carousel
Plugin URI: https://www.tonyvle.com/products/simple-logo-carousel/
Description: A simplistic carousel to elegantly display logos. Powered by <a href="http://kenwheeler.github.io/slick/">slick</a>.
Version: 1.0
Author: tvledesign LLC
Author URI: https://www.tonyvle.com
License: GPL2
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

define( 'slc', 1 );

require plugin_dir_path( __FILE__ ) . 'slc-post-type.php';

class Simple_Logo_Carousel {
    function __construct() {
        add_shortcode( 'simple-logo-carousel', array( $this, 'slc_shortcode' ) );
    }

    // output javascript
    function slc_main_js($id) {
        ?>
        <script src="<?php echo plugins_url( 'includes/libs/slick/slick.min.js', __FILE__ ); ?>"></script>
        <script>
                // get our arrow settings
                $slcArrowColor = "<?php echo get_post_meta($id, 'slc_carousel_arrowcolor', true); ?>";
                $slcArrowSize = "<?php echo get_post_meta($id, 'slc_carousel_arrowsize', true); ?>";
                $slcArrowOffset = "<?php echo get_post_meta($id, 'slc_carousel_arrowoffset', true); ?>";

            (function($) {
                // initialize our slick with our logo carousel
                $('.slc-logos').slick({
                    autoplay: <?php echo get_post_meta($id, 'slc_carousel_autoplay', true); ?>,
                    autoplaySpeed: <?php echo get_post_meta($id, 'slc_carousel_autoplayspeed', true); ?>,
                    arrows: <?php echo get_post_meta($id, 'slc_carousel_arrows', true); ?>,
                    prevArrow: '<button type="button" class="slick-prev" style="font-size:' + $slcArrowSize + ';line-height:' + $slcArrowSize + ';color:' + $slcArrowColor + ';margin-top:calc(-' + $slcArrowSize + '/2);left:' + $slcArrowOffset + '">&#x276C;</button>',
                    nextArrow: '<button type="button" class="slick-next" style="font-size:' + $slcArrowSize + ';line-height:' + $slcArrowSize + ';color:' + $slcArrowColor + ';margin-top:calc(-' + $slcArrowSize + '/2);right:' + $slcArrowOffset + '">&#x276D;</button>',
                    centerMode: <?php echo get_post_meta($id, 'slc_carousel_centermode', true); ?>,
                    cssEase: '<?php echo get_post_meta($id, 'slc_carousel_animation', true); ?>',
                    dots: false,
                    draggable: <?php echo get_post_meta($id, 'slc_carousel_draggable', true); ?>,
                    pauseOnFocus: <?php echo get_post_meta($id, 'slc_carousel_pauseonfocus', true); ?>,
                    pauseOnHover: <?php echo get_post_meta($id, 'slc_carousel_pauseonhover', true); ?>,
                    speed: <?php echo get_post_meta($id, 'slc_carousel_speed', true); ?>,
                    swipe: <?php echo get_post_meta($id, 'slc_carousel_swipe', true); ?>,
                    slidesToShow: <?php echo get_post_meta($id, 'slc_carousel_slidestoshow_desktop', true); ?>,
                    slidesToScroll: <?php echo get_post_meta($id, 'slc_carousel_slidestoscroll_desktop', true); ?>,
                    responsive: [
                        {
                            breakpoint: 980,
                            settings: {
                                slidesToShow: <?php echo get_post_meta($id, 'slc_carousel_slidestoshow_tablet', true); ?>,
                                slidesToScroll: <?php echo get_post_meta($id, 'slc_carousel_slidestoscroll_tablet', true); ?>
                            }
                        }, {
                            breakpoint: 767,
                            settings: {
                                sslidesToShow: <?php echo get_post_meta($id, 'slc_carousel_slidestoshow_mobile', true); ?>,
                                slidesToScroll: <?php echo get_post_meta($id, 'slc_carousel_slidestoscroll_mobile', true); ?>
                            }
                        }
                    ]
                });
            })( jQuery );
        </script>
        <?php 
    }

    // display our logo carousel
    function slc_shortcode( $atts = [] ) {
        // define attributes and their defaults
        extract( shortcode_atts( array (
            'id' => ''
        ), $atts ) );

        // if there is no id
        if($id == '' || $id == null) {
            return '<p>Please make sure your carousel shortcode contains an ID.</p>';
        } else {
            // enqueue our javascript and stylesheet
            wp_enqueue_style( 'scp_stylesheet', plugins_url( 'public/css/slc-main.css', __FILE__ ) );
            add_action('wp_footer', function() use ($id) {
                $this->slc_main_js($id);
            });

            // create an empty string to hold our taxonomy
            $slcCategoriesString = '';
            $slcCategories = array();
            $slcTaxonomyQuery = array();

            // if there is a taxonomy 
            if(get_the_terms($id, 'slc_logo_category')) {
                // for each category in the taxonomy
                foreach ( get_the_terms($id, 'slc_logo_category') as $category ) {
                    $slcCategoriesString .= $category->slug . ', ';
                }

                // remove the last comma from categories
                $slcCategoriesString = substr($slcCategoriesString, 0, -2);

                // turn our categories in an array
                $slcCategories = explode(',', $slcCategoriesString);
            }

            // foreach category in the array
            foreach($slcCategories as $category) {
                // create a tempoary array
                $slcNewArray = array ('taxonomy' => 'slc_logo_category', 'field' => 'slug', 'terms' => $category);

                // push it into our query taxonomy array
                array_push($slcTaxonomyQuery, $slcNewArray);
            }

            // if the category string is not empty or null
            if($slcCategoriesString != '' || $slcCategoriesString != null) {
         
                // start a query to get the logos
                $query = new WP_Query( 
                    array( 
                        'post_type' => 'slc_logo', 
                        'posts_per_page' => -1, 
                        'post_status' => 'publish', 
                        'orderby' => 'menu_order',
                        'tax_query' => array(
                            'relation' => 'OR',
                            $slcTaxonomyQuery
                        )
                    ) 
                );
            } else {
                // start a query to get the logos
                $query = new WP_Query( 
                    array( 
                        'post_type' => 'slc_logo', 
                        'posts_per_page' => -1, 
                        'post_status' => 'publish', 
                        'orderby' => 'menu_order'
                    ) 
                );
            }

            // create an empty output to store our html
            $output = '';

            // if the query have posts
            if ( $query->have_posts() ) :

                // output our container for our logos
                $output .= '<div class="slc-logos">';

                // while there are posts
                while ( $query->have_posts() ) : $query->the_post(); 

                    // create a container for an individual logo
                    $output .= '<div class="slc-logo">';

                    // if there is a link
                    if(get_post_meta(get_the_id(), 'slc_external_url', true) != '' && get_post_meta(get_the_id(), 'slc_external_url', true) != null) {
                        // output the link
                        $output .= '<a href="' . get_post_meta(get_the_id(), 'slc_external_url', true) . '" target="' . get_post_meta(get_the_id(), 'slc_url_target', true) . '">';
                    }

                    // create an empty string to store our alt tag
                    $altText = '';

                    // if there is an alt text
                    if(get_post_meta(get_the_id(), 'slc_alt_text', true) != '' && get_post_meta(get_the_id(), 'slc_alt_text', true) != null) {
                        // set the alt text
                        $altText = get_post_meta(get_the_id(), 'slc_alt_text', true);
                    } else {
                        // if there is no alt text check if the image has one
                        $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
                        $imageAltText = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);

                        // if the alt tag on the image is not empty
                        if($imageAltText != '' && $imageAltText != null) {
                            // set the alt text
                            $altText = $imageAltText;
                        } else {
                            // else if there is no alt text on the image default to the title if there is one
                            if(get_the_title() != '' && get_the_title() != null) {
                                $altText = get_the_title();
                            } else {
                                // default to the site title
                                $altText = get_bloginfo();
                            }
                        }
                    }
                    
                    // output the logo image
                    $output .= '<img src="' . get_the_post_thumbnail_url(get_the_id(), 'full')  . '" alt="' . $altText . '" class="slc-logo-img" />';

                    // if there is a link
                    if(get_post_meta(get_the_id(), 'slc_external_url', true) != '' && get_post_meta(get_the_id(), 'slc_external_url', true) != null) {
                        $output .= '</a>';
                    }

                    $output .= '</div>';
            
                endwhile;

                $output .= '</div>';

            endif;
            wp_reset_postdata();
        
            return $output;
        }
    }
}
$simple_logo_carousel = new Simple_Logo_Carousel();