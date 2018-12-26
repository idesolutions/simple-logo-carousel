<?php
// register the carousel post type
add_action( 'init', 'slc_create_carousel_post_type' );
function slc_create_carousel_post_type() {
    $labels = array(
        'name'                  => 'Logo Carousels',
        'singular_name'         => 'Logo Carousel',
        'add_new'               => 'Add Carousel',
        'add_new_item'          => 'Add Carousel',
        'edit'                  => 'Edit Carousel',
        'edit_item'             => 'Edit Carousel',
        'new_item'              => 'New Carousel',
        'view'                  => 'View Carousel',
        'view_item'             => 'View Carousel',
        'search_items'          => 'Search Carousels',
        'not_found'             => 'No Carousels Found',
        'not_found_in_trash'    => 'No Carousels Found In Trash',
        'parent'                => 'Parent Carousel'
    );
    $args = array(
        'label'                 => 'Carousel',
        'labels'                => $labels,
        'supports'              => array( 'title'),
        'taxonomies'            => array( 'slc_logo_category' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'menu_icon'             => 'dashicons-slides',
        'register_meta_box_cb'  => 'slc_cpt_metaboxes',
        'capabilities' => array(
            'edit_post' => 'edit_logo_carousel',
            'edit_posts' => 'edit_logo_carousels',
            'edit_others_posts' => 'edit_other_logo_carousels',
            'publish_posts' => 'publish_logo_carousels',
            'read_post' => 'read_logo_carousels',
            'read_private_posts' => 'read_private_logo_carousels',
            'delete_post' => 'delete_logo_carousel'
        ),
        'map_meta_cap' => true
    );
    register_post_type( 'slc_carousel', $args);
}

// register the logo post type
add_action( 'init', 'slc_create_logo_post_type' );
function slc_create_logo_post_type() {
    $labels = array(
        'name'                  => 'Logos',
        'singular_name'         => 'Logo',
        'add_new'               => 'Add Logo',
        'add_new_item'          => 'Add Logo',
        'edit'                  => 'Edit Logo',
        'edit_item'             => 'Edit Logo',
        'new_item'              => 'New Logo',
        'view'                  => 'View Logo',
        'view_item'             => 'View Logo',
        'search_items'          => 'Search Logos',
        'not_found'             => 'No Logos Found',
        'not_found_in_trash'    => 'No Logos Found In Trash',
        'parent'                => 'Parent Logo'
    );
    $args = array(
        'label'                 => 'Logo',
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail', 'page-attributes'),
        'taxonomies'            => array( 'slc_logo_category' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => 'edit.php?post_type=slc_carousel',
        'menu_position'         => 20,
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'menu_icon'             => 'dashicons-slides',
        'register_meta_box_cb'  => 'slc_cpt_metaboxes',
        'capabilities' => array(
            'edit_post' => 'edit_logo_carousel',
            'edit_posts' => 'edit_logo_carousels',
            'edit_others_posts' => 'edit_other_logo_carousels',
            'publish_posts' => 'publish_logo_carousels',
            'read_post' => 'read_logo_carousels',
            'read_private_posts' => 'read_private_logo_carousels',
            'delete_post' => 'delete_logo_carousel'
        ),
        'map_meta_cap' => true
    );
    register_post_type( 'slc_logo', $args);
}

// register our logo taxonomy
add_action( 'init', 'slc_create_logo_taxonomy' );
function slc_create_logo_taxonomy() {
    $labels = array(
        'name'                  => __( 'Logo Categories'),
        'singular_name'         => __( 'Logo Category'),
        'search_items'          => __( 'Search Logo Categories' ),
        'all_items'             => __( 'All Logo Categories' ),
        'parent_item'           => __( 'Parent Logo Category' ),
        'parent_item_colon'     => __( 'Parent Logo Category:' ),
        'edit_item'             => __( 'Edit Logo Category' ), 
        'update_item'           => __( 'Update Logo Category' ),
        'add_new_item'          => __( 'Add Logo Category' ),
        'new_item_name'         => __( 'New Logo Category Name' ),
        'menu_name'             => __( 'Logo Categories' )
    );    
    
    // register the taxonomy 
    register_taxonomy(
        'slc_logo_category',
        array(
            'slc_carousel',
            'slc_logo'
        ), 
        array(
            'labels'                => $labels,
            'public'                => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'show_admin_column'     => true,
            'hierarchical'          => true,
            'query_var'             => false,
            'rewrite'               => false
        )
    );
}

// add custom post type capabilities
function slc_add_capabilities() {
    // gets the administrator role
    $admins = get_role( 'administrator' );

    $admins->add_cap( 'edit_logo_carousel' ); 
    $admins->add_cap( 'edit_logo_carousels' ); 
    $admins->add_cap( 'edit_other_logo_carousels' ); 
    $admins->add_cap( 'publish_logo_carousel' ); 
    $admins->add_cap( 'read_logo_carousel' ); 
    $admins->add_cap( 'read_private_logo_carousels' ); 
    $admins->add_cap( 'delete_logo_carousel' ); 
}

// hide the menu items from user with no capabilities
function slc_remove_menu_items() {
    if( !current_user_can( 'administrator' ) ):
        remove_menu_page( 'edit.php?post_type=slc_carousel' );
    endif;
}
add_action( 'admin_menu', 'slc_remove_menu_items' );

function slc_install() {
    // add custom post type capabilities
    add_action( 'admin_init', 'slc_add_capabilities');
 
    // clear the permalinks after the post type has been registered
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'slc_install' );

// render the admin stylesheet
add_action('admin_enqueue_scripts', 'slc_admin_stylesheet');
function slc_admin_stylesheet() {
    wp_enqueue_style( 'scp_stylesheet', plugins_url( 'admin/css/slc-admin.css', __FILE__ ) );
}

// load in wp color picker
add_action( 'admin_enqueue_scripts', 'scp_enqueue_color_picker' );
function scp_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker');
}

// add in extra menu optiosn to our cpt
add_action('admin_menu', 'slc_add_menu_to_cpt');
function slc_add_menu_to_cpt() {
    // reference the submenu
    global $submenu;

    // add an add logo link to the cpt slc carousel
    $submenu['edit.php?post_type=slc_carousel'][] = array( 'Add Logo', 'manage_options', 'post-new.php?post_type=slc_logo' );

    // add a how to page
    add_submenu_page('edit.php?post_type=slc_carousel', 'Simple Logo Carousel', 'How To', 'manage_options', 'my-menu2', 'ch2pho_config_page');
}

// render the how to page
function ch2pho_config_page() {
    ?>
    <div class="wrap wrap-general slc">
        <img src="<?php echo plugin_dir_url( __FILE__ ) . 'admin\images\simple-logo-carousel-logo.png'; ?>" alt="Simple Logo Carousel" />
        <div class="postbox">
            <h2 class="hndle">Simple Logo Carousel - How To</h2>
            <div class="inside">
                <p><b>Visit the YouTube channel <a href="https://www.youtube.com/channel/UCeto2S7J0vwAeNAdonRH80w" target="_blank">here</a> to find tutorial videos or read on below.</b></p>
                <p>To create your first logo carousel, follow the steps below:</p>
                <h3>1. Adding Logos</h3>
                <p>To add a logo, located the left-hand sidebar and hover over <b>Logo Carousels</b>. Once you see the different options click on <b>Add Logo</b>.</p>
                <p>Once you've clicked on <b>Add Logo</b>, you'll be redirected to a new page that looks similar to a classic WordPress editor.</p>
                <p>From there you'll have the following options:</p>
                <ol>
                    <li><p><b>Title</b><br />The title of your logo, similar to how you would name a post or page.</p></li>
                    <li><p><b>Logo Options - Link To URL</b><br />The URL you would like your logo to link to, or you can leave it blank.</p></li>
                    <li><p><b>Logo Options - Target</b><br />Choose if you'd like the link to open in the current window or a new tab if there is a link.</p></li>
                    <li><p><b>Logo Options - Alt Text</b><br />The alt text to be used for the logo. By default, if this is blank, the featured image's alt text is used. If there is none, the default falls back onto the logo's title.</p></li>
                    <li><p><b>Post Attributes - Order</b><br />The order option determines the order of how the logo appears on the carousel.</p></li>
                    <li><p><b>Featured Image</b><br />The featured image is the logo you would like displayed.</p></li>
                    <li><p><b>Logo Categories</b><br />Set categories to organize your logos into different groups to display in the carousel.</p></li>
                </ol>
                <p>You can always edit your logos by going back to the left-hand sidebar and hovering over <b>Logo Carousels</b> and clicking on <b>Logos</b>.</p>
                <p>To edit the categories repeat the same process as above except this time click on <b>Logo Categories</b>.</p>
                <h3>2. Creating a Carousel</h3>
                <p>To create a logo carousel, located the left-hand sidebar and hover over <b>Logo Carousels</b>. Once you see the different options click on <b>Add Carousel</b>.</p>
                <p>Once you've clicked on <b>Add Carousel</b>, you'll be redirected to a new page that looks similar to a classic WordPress editor.</p>
                <p>From there you'll have the following options:</p>
                <ol>
                    <li><p><b>Title</b><br />The title of your carousel, similar to how you would name a post or page.</p></li>
                    <li><p><b>Shortcode</b><br />The shortcode that you will paste onto a post or page to display the carousel.</p></li>
                    <li><p><b>Logo Display Options</b><br />The amount of logos to show and slide on a screen at one time. Desktop, tablet, and mobile breakpoint options are available.</p></li>
                    <li><p><b>Carousel Options</b><br />Different options for handling the display and functionality of the carousel.</p>
                        <ul style="padding-left: 15px;">
                            <li><b>Accessibility</b> - Enables tabbing and arrow key navigation.</li>
                            <li><b>Autoplay</b> - Enables autoplay.</li>
                            <li><b>Autoplay Speed</b> - Autoplay speed in milliseconds.</li>
                            <li><b>Arrows</b> - Show previous and next arrows.</li>
                            <li><b>Arrow Color</b> - The color of the arrows.</li>
                            <li><b>Arrow Size</b> - The size of the arrows. All CSS units are available.</li>
                            <li><b>Arrow Offset</b> - How far out the arrows are on the left and right sides. All CSS units are available.</li>
                            <li><b>Center Mode</b> - Enables centered view with partial view of the previous and next logos. Use with odd-numbered logo count.</li>
                            <li><b>Animation</b> - Animation type.</li>
                            <li><b>Draggable</b> - Enable mouse dragging.</li>
                            <li><b>Pause On Focus </b> - Pause autoplay on focus.</li>
                            <li><b>Pause On Hover</b> - Pause autoplay on hover.</li>
                            <li><b>Speed</b> - Carousel animation speed.</li>
                            <li><b>Swipe</b> - Enable swiping.</li>
                        </ul>
                    </li>
                    <li><p><b>Logo Categories</b><br />The category of logos to display. Leaving this option blank displays everything by default.</p>
                </ol>
                <p>You can always edit your logo carousels by going back to the left-hand sidebar and hovering over <b>Logo Carousels</b> and clicking on <b>Logo Carousels</b>.</p>
                <h3>3. Display Your Carousel</h3>
                <p>To display your carousel, copy the shortcode while editing one of your carousels and paste it onto a post or page.</p>
                <p>Alternatively, you can also go to the <b>Logo Carousels</b> page and copy the shortcode that appears in the <b>Shortcode</b> column where all the carousels are displayed.</p>
            </div>
        </div>
        <p>Simple Logo Carousel is powered by <b><a href="http://kenwheeler.github.io/slick/">slick</a></b>.</p>
    </div>
    <?php 
}

// add our meta boxes to our custom post type
function slc_cpt_metaboxes() {
    // metabox for logo options
    add_meta_box(
        'slc_cpt_logo_options',
		'Logo Options',
		'slc_cpt_logo_options',
		'slc_logo',
		'normal',
		'default'
    );

    // metabox for carousel shortcode
    add_meta_box(
        'slc_cpt_carousel_shortcode',
		'Shortcode',
		'slc_cpt_carousel_shortcode',
		'slc_carousel',
		'normal',
		'default'
    );

    // metabox for carousel logo display options
    add_meta_box(
        'slc_cpt_carousel_logo_display_options',
		'Logo Display Options',
		'slc_cpt_carousel_logo_display_options',
		'slc_carousel',
		'normal',
		'default'
    );

    // metabox for carousel options
    add_meta_box(
        'slc_cpt_carousel_options',
		'Carousel Options',
		'slc_cpt_carousel_options',
		'slc_carousel',
		'normal',
		'default'
    );
}

// add in columns in our carousel cpt admin
add_filter( 'manage_slc_carousel_posts_columns', 'slc_filter_carousel_posts_columns' );
function slc_filter_carousel_posts_columns( $columns ) {
    // create an array of the columns we are displaying
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __('Title'),
        'category' => __('Categories'),
        'shortcode' => __('Shortcode')
    );

    // return the array
    return $columns;
}

// display our details inside the columns
add_action( 'manage_slc_carousel_posts_custom_column', 'slc_carousel_column', 10, 2);
function slc_carousel_column( $column, $post_id ) {
    // if there is a category column
    if('category' === $column) {
        // if there is a taxonomy
        if(get_the_terms($post_id, 'slc_logo_category')) {
            // create an empty string to hold our categories
            $categories = '';
            
            // for each category in the taxonomy
            foreach ( get_the_terms($post_id, 'slc_logo_category') as $category ) {
                $categories .= $category->name . ', ';
            }

            // remove the last comma from categories
            $categories = substr($categories, 0, -2);

            // display it
            echo $categories;
        } else {
            // else inform there is not one
            echo '<em>No categories were selected, all logos are being shown.</em>';
        }
    }

    // if there is a shortcode column
    else if ('shortcode' === $column) {
        // output the shortcode
        echo '<input type="text" value="[simple-logo-carousel id=' . $post_id . ']" style="min-width:245px;" readonly />';
    }
}

// add in columns in our logo cpt admin
add_filter( 'manage_slc_logo_posts_columns', 'slc_filter_logo_posts_columns' );
function slc_filter_logo_posts_columns( $columns ) {
    // create an array of the columns we are displaying
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __('Title'),
        'link_url' => __( 'Link URL' ),
        'target' => __('Target'),
        'alt_text' => __('Alt Text'),
        'category' => __('Categories'),
        'logo' => __( 'Logo' )
    );

    // return the array
    return $columns;
}

// display our details inside the columns
add_action( 'manage_slc_logo_posts_custom_column', 'slc_logo_column', 10, 2);
function slc_logo_column( $column, $post_id ) {
    // if there is a logo column
    if ( 'logo' === $column ) {
        // echo out the logo
        echo get_the_post_thumbnail( $post_id, array(80, 80) );
    }

    // if there is a link url column
    else if('link_url' === $column) {
        echo get_post_meta($post_id, 'slc_external_url', true);
    }

    // if there is a target column
    else if('target' === $column) {
        $target = get_post_meta($post_id, 'slc_url_target', true);
        if ($target == '_blank') {
            echo 'New Window or Tab';
        } else {
            echo 'Same Window';
        }
    }

    // if there is a alt text column
    else if('alt_text' === $column) {
        $altText = get_post_meta($post_id, 'slc_alt_text', true);
        if($altText == '') {
            echo '<em>No alt text was provided.</em>';
        } else {
            echo $altText;
        }
    }

    // if there is a category column
    else if('category' === $column) {
        // if there is a taxonomy
        if(get_the_terms($post_id, 'slc_logo_category')) {
            // create an empty string to hold our categories
            $categories = '';
            
            // for each category in the taxonomy
            foreach ( get_the_terms($post_id, 'slc_logo_category') as $category ) {
                $categories .= $category->name . ', ';
            }

            // remove the last comma from categories
            $categories = substr($categories, 0, -2);

            // display it
            echo $categories;
        } else {
            // else inform there is not one
            echo '<em>No category found.</em>';
        }
    }
}

// save our logo meta data
add_action( 'save_post_slc_logo', 'slc_cpt_save_logo_meta', 1, 2 );
function slc_cpt_save_logo_meta( $post_id, $post ) {
	// return if the user doesn't have edit permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
    }

    // verify taxonomies meta box nonce
	if ( !isset( $_POST['slc_cpt'] ) || !wp_verify_nonce( $_POST['slc_cpt'], basename( __FILE__ ) ) ){
		return;
    }
    
    // update and save our meta data
    update_post_meta($post->ID, 'slc_external_url', $_POST['slc_external_url']);
    update_post_meta($post->ID, 'slc_url_target', $_POST['slc_url_target']);
    update_post_meta($post->ID, 'slc_alt_text', $_POST['slc_alt_text']);

    return $post;
}

// save our carousel meta data
add_action( 'save_post_slc_carousel', 'slc_cpt_save_carousel_meta', 1, 2 );
function slc_cpt_save_carousel_meta( $post_id, $post ) {
	// return if the user doesn't have edit permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
    }

    // verify taxonomies meta box nonce
	if ( !isset( $_POST['slc_cpt'] ) || !wp_verify_nonce( $_POST['slc_cpt'], basename( __FILE__ ) ) ){
		return;
    }
    
    // update and save our meta data
    if($_POST['slc_carousel_slidestoshow_desktop'] <= 0 || $_POST['slc_carousel_slidestoshow_desktop'] == null || $_POST['slc_carousel_slidestoshow_desktop'] == '') {
        update_post_meta($post->ID, 'slc_carousel_slidestoshow_desktop', '1');
    } else {
        update_post_meta($post->ID, 'slc_carousel_slidestoshow_desktop', $_POST['slc_carousel_slidestoshow_desktop']);
    }

    if($_POST['slc_carousel_slidestoscroll_desktop'] <= 0 || $_POST['slc_carousel_slidestoscroll_desktop'] == null || $_POST['slc_carousel_slidestoscroll_desktop'] == '') {
        update_post_meta($post->ID, 'slc_carousel_slidestoscroll_desktop', '1');
    } else {
        update_post_meta($post->ID, 'slc_carousel_slidestoscroll_desktop', $_POST['slc_carousel_slidestoscroll_desktop']);
    }

    if($_POST['slc_carousel_slidestoshow_tablet'] <= 0 || $_POST['slc_carousel_slidestoshow_tablet'] == null || $_POST['slc_carousel_slidestoshow_tablet'] == '') {
        update_post_meta($post->ID, 'slc_carousel_slidestoshow_tablet', '1');
    } else {
        update_post_meta($post->ID, 'slc_carousel_slidestoshow_tablet', $_POST['slc_carousel_slidestoshow_tablet']);
    }

    if($_POST['slc_carousel_slidestoscroll_tablet'] <= 0 || $_POST['slc_carousel_slidestoscroll_tablet'] == null || $_POST['slc_carousel_slidestoscroll_tablet'] == '') {
        update_post_meta($post->ID, 'slc_carousel_slidestoscroll_tablet', '1');
    } else {
        update_post_meta($post->ID, 'slc_carousel_slidestoscroll_tablet', $_POST['slc_carousel_slidestoscroll_tablet']);
    }

    if($_POST['slc_carousel_slidestoshow_mobile'] <= 0 || $_POST['slc_carousel_slidestoshow_mobile'] == null || $_POST['slc_carousel_slidestoshow_mobile'] == '') {
        update_post_meta($post->ID, 'slc_carousel_slidestoshow_mobile', '1');
    } else {
        update_post_meta($post->ID, 'slc_carousel_slidestoshow_mobile', $_POST['slc_carousel_slidestoshow_mobile']);
    }

    if($_POST['slc_carousel_slidestoscroll_mobile'] <= 0 || $_POST['slc_carousel_slidestoscroll_mobile'] == null || $_POST['slc_carousel_slidestoscroll_mobile'] == '') {
        update_post_meta($post->ID, 'slc_carousel_slidestoscroll_mobile', '1');
    } else {
        update_post_meta($post->ID, 'slc_carousel_slidestoscroll_mobile', $_POST['slc_carousel_slidestoscroll_mobile']);
    }

    update_post_meta($post->ID, 'slc_carousel_accessibility', $_POST['slc_carousel_accessibility']);
    update_post_meta($post->ID, 'slc_carousel_autoplay', $_POST['slc_carousel_autoplay']);

    if($_POST['slc_carousel_autoplayspeed'] <= 0 || $_POST['slc_carousel_autoplayspeed'] == null || $_POST['slc_carousel_autoplayspeed'] == '') {
        update_post_meta($post->ID, 'slc_carousel_autoplayspeed', '3000');
    } else {
        update_post_meta($post->ID, 'slc_carousel_autoplayspeed', $_POST['slc_carousel_autoplayspeed']);
    }
    
    update_post_meta($post->ID, 'slc_carousel_arrows', $_POST['slc_carousel_arrows']);

    if($_POST['slc_carousel_arrowcolor'] == null || $_POST['slc_carousel_arrowcolor'] == '') {
        update_post_meta($post->ID, 'slc_carousel_arrowcolor', '#222222');
    } else {
        update_post_meta($post->ID, 'slc_carousel_arrowcolor', $_POST['slc_carousel_arrowcolor']);
    }

    if($_POST['slc_carousel_arrowsize'] == null || $_POST['slc_carousel_arrowsize'] == '') {
        update_post_meta($post->ID, 'slc_carousel_arrowsize', '50px');
    } else {
        update_post_meta($post->ID, 'slc_carousel_arrowsize', $_POST['slc_carousel_arrowsize']);
    }

    if($_POST['slc_carousel_arrowoffset'] == null || $_POST['slc_carousel_arrowoffset'] == '') {
        update_post_meta($post->ID, 'slc_carousel_arrowoffset', '-10px');
    } else {
        update_post_meta($post->ID, 'slc_carousel_arrowoffset', $_POST['slc_carousel_arrowoffset']);
    }

    update_post_meta($post->ID, 'slc_carousel_centermode', $_POST['slc_carousel_centermode']);
    update_post_meta($post->ID, 'slc_carousel_animation', $_POST['slc_carousel_animation']);
    update_post_meta($post->ID, 'slc_carousel_draggable', $_POST['slc_carousel_draggable']);
    update_post_meta($post->ID, 'slc_carousel_pauseonfocus', $_POST['slc_carousel_pauseonfocus']);
    update_post_meta($post->ID, 'slc_carousel_pauseonhover', $_POST['slc_carousel_pauseonhover']);

    if($_POST['slc_carousel_speed'] <= 0 || $_POST['slc_carousel_speed'] == null || $_POST['slc_carousel_speed'] == '') {
        update_post_meta($post->ID, 'slc_carousel_speed', '1000');
    } else {
        update_post_meta($post->ID, 'slc_carousel_speed', $_POST['slc_carousel_speed']);
    }

    update_post_meta($post->ID, 'slc_carousel_swipe', $_POST['slc_carousel_swipe']);

    return $post;
}

// render out cpt logo options meta box
function slc_cpt_logo_options() {
    // get our post
    global $post;

    // validate our field to make sure the form
    // request came from the site
    wp_nonce_field( basename( __FILE__ ), 'slc_cpt' );

    // get our data information
    $slcExternalURL = get_post_meta($post->ID, 'slc_external_url', true);
    $slcUrlTarget = get_post_meta($post->ID, 'slc_url_target', true);
    $slcAltText = get_post_meta($post->ID, 'slc_alt_text', true);
    
    // output our html
    echo '<label for="slc-external-url" class="slc-label">Link To URL - <span class="slc-small">Enter the URL in the field below to have your logo link to a page or external site. Leave this option blank if you do not wish to have it link anywhere.</span></label>';
    echo '<input type="text" name="slc_external_url" id="slc-external-url" class="slc-text-field" value="' . $slcExternalURL  . '" placeholder="Please enter the full URL">';
    
    echo '<label for="slc-url-target" class="slc-label">Target - <span class="slc-small">Specify how you\'d like to open up the link above. This option will not matter if the field above is blank.</span></label>';
    echo '<select name="slc_url_target" id="slc-url-target" class="slc-text-field">';
    if ($slcUrlTarget == null || $slcUrlTarget == '') {
        echo '<option value="_blank" selected>New Window or Tab</option>';
        echo '<option value="_self">Same Window</option>';
    } else {
        if ($slcUrlTarget == '_blank') {
            echo '<option value="_blank" selected>New Window or Tab</option>';
            echo '<option value="_self">Same Window</option>';
        } else {
            echo '<option value="_blank">New Window or Tab</option>';
            echo '<option value="_self" selected>Same Window</option>';
        }
    }
    echo '</select>';

    echo '<label for="slc-alt-text" class="slc-label">Alt Text - <span class="slc-small">Leave this field blank to default to the image\'s default alt text. If there is none, this will default to the title.</span></label>';
    echo '<input type="text" name="slc_alt_text" id="slc-alt-text" class="slc-text-field" value="' . $slcAltText  . '" placeholder="Alt Text">';
}

// render out cpt carousel shortcode meta box
function slc_cpt_carousel_shortcode($post_id) {
    // get our post
    global $post;

    // validate our field to make sure the form
    // request came from the site
    wp_nonce_field( basename( __FILE__ ), 'slc_cpt' );

    // output our html
    echo '<label for="slc-alt-text" class="slc-label"><span class="slc-small">Copy and paste the shortcode below to display your carousel.</span></label>';
    echo '<input type="text" name="slc_external_url" id="slc-external-url" class="slc-text-field" value="[simple-logo-carousel id=' . $post->ID  . ']" readonly>';
}

// render out cpt carousel logo display options meta box
function slc_cpt_carousel_logo_display_options() {
    // get our post
    global $post;

    // validate our field to make sure the form
    // request came from the site
    wp_nonce_field( basename( __FILE__ ), 'slc_cpt' );

    // get our data information
    $slcSlidesToShowDesktop = get_post_meta($post->ID, 'slc_carousel_slidestoshow_desktop', true);
    $slcSlidesToScrollDesktop = get_post_meta($post->ID, 'slc_carousel_slidestoscroll_desktop', true);
    $slcSlidesToShowTablet = get_post_meta($post->ID, 'slc_carousel_slidestoshow_tablet', true);
    $slcSlidesToScrollTablet = get_post_meta($post->ID, 'slc_carousel_slidestoscroll_tablet', true);
    $slcSlidesToShowMobile = get_post_meta($post->ID, 'slc_carousel_slidestoshow_mobile', true);
    $slcSlidesToScrollMobile = get_post_meta($post->ID, 'slc_carousel_slidestoscroll_mobile', true);

    // output our html
    echo '<h3 class="slc-header">Desktop - <span class="slc-small">981px or Greater</span></h3>';

    echo '<label for="slc-carousel-slidestoshow" class="slc-label">Slides To Show - <span class="slc-small">Numbers of logos to show. Default is <strong>1</strong>.</span></label>';
    if($slcSlidesToShowDesktop == '' || $slcSlidesToShowDesktop == null) {
        echo '<input type="number" name="slc_carousel_slidestoshow_desktop" id="slc-carousel-slidestoshow" value="1" />';
    } else {
        echo '<input type="number" name="slc_carousel_slidestoshow_desktop" id="slc-carousel-slidestoshow" value="' . $slcSlidesToShowDesktop . '" />';
    }

    echo '<label for="slc-carousel-slidestoscroll" class="slc-label">Slides to Scroll - <span class="slc-small">Numbers of logos to scroll at a time. Default is <strong>1</strong>.</span></label>';
    if($slcSlidesToScrollDesktop == '' || $slcSlidesToScrollDesktop == null) {
        echo '<input type="number" name="slc_carousel_slidestoscroll_desktop" id="slc-carousel-slidestoscroll" value="1" />';
    } else {
        echo '<input type="number" name="slc_carousel_slidestoscroll_desktop" id="slc-carousel-slidestoscroll" value="' . $slcSlidesToScrollDesktop . '" />';
    }

    echo '<br><br><hr>';
    echo '<h3 class="slc-header">Tablet - <span class="slc-small">768px to 980px</span></h3>';

    echo '<label for="slc-carousel-slidestoshow" class="slc-label">Slides To Show - <span class="slc-small">Numbers of logos to show. Default is <strong>1</strong>.</span></label>';
    if($slcSlidesToShowTablet == '' || $slcSlidesToShowTablet == null) {
        echo '<input type="number" name="slc_carousel_slidestoshow_tablet" id="slc-carousel-slidestoshow" value="1" />';
    } else {
        echo '<input type="number" name="slc_carousel_slidestoshow_tablet" id="slc-carousel-slidestoshow" value="' . $slcSlidesToShowTablet . '" />';
    }

    echo '<label for="slc-carousel-slidestoscroll" class="slc-label">Slides to Scroll - <span class="slc-small">Numbers of logos to scroll at a time. Default is <strong>1</strong>.</span></label>';
    if($slcSlidesToScrollTablet == '' || $slcSlidesToScrollTablet == null) {
        echo '<input type="number" name="slc_carousel_slidestoscroll_tablet" id="slc-carousel-slidestoscroll" value="1" />';
    } else {
        echo '<input type="number" name="slc_carousel_slidestoscroll_tablet" id="slc-carousel-slidestoscroll" value="' . $slcSlidesToScrollTablet . '" />';
    }

    echo '<br><br><hr>';
    echo '<h3 class="slc-header">Mobile - <span class="slc-small">767px and Below</span></h3>';
    
    echo '<label for="slc-carousel-slidestoshow" class="slc-label">Slides To Show - <span class="slc-small">Numbers of logos to show. Default is <strong>1</strong>.</span></label>';
    if($slcSlidesToShowMobile == '' || $slcSlidesToShowMobile == null) {
        echo '<input type="number" name="slc_carousel_slidestoshow_mobile" id="slc-carousel-slidestoshow" value="1" />';
    } else {
        echo '<input type="number" name="slc_carousel_slidestoshow_mobile" id="slc-carousel-slidestoshow" value="' . $slcSlidesToShowMobile . '" />';
    }

    echo '<label for="slc-carousel-slidestoscroll" class="slc-label">Slides to Scroll - <span class="slc-small">Numbers of logos to scroll at a time. Default is <strong>1</strong>.</span></label>';
    if($slcSlidesToScrollMobile == '' || $slcSlidesToScrollMobile == null) {
        echo '<input type="number" name="slc_carousel_slidestoscroll_mobile" id="slc-carousel-slidestoscroll" value="1" />';
    } else {
        echo '<input type="number" name="slc_carousel_slidestoscroll_mobile" id="slc-carousel-slidestoscroll" value="' . $slcSlidesToScrollMobile . '" />';
    }
    echo '<br><br>';
}

// render out cpt carousel options meta box
function slc_cpt_carousel_options() {
    // get our post
    global $post;

    // validate our field to make sure the form
    // request came from the site
    wp_nonce_field( basename( __FILE__ ), 'slc_cpt' );

    // get our data information
    $slcAccessibility = get_post_meta($post->ID, 'slc_carousel_accessibility', true);
    $slcAutoplay = get_post_meta($post->ID, 'slc_carousel_autoplay', true);
    $slcAutoplaySpeed = get_post_meta($post->ID, 'slc_carousel_autoplayspeed', true);
    $slcArrows = get_post_meta($post->ID, 'slc_carousel_arrows', true);
    $slcArrowColor = get_post_meta($post->ID, 'slc_carousel_arrowcolor', true);
    $slcArrowSize = get_post_meta($post->ID, 'slc_carousel_arrowsize', true);
    $slcArrowOffset = get_post_meta($post->ID, 'slc_carousel_arrowoffset', true);
    $slcCenterMode = get_post_meta($post->ID, 'slc_carousel_centermode', true);
    $slcAnimation = get_post_meta($post->ID, 'slc_carousel_animation', true);
    $slcDraggable = get_post_meta($post->ID, 'slc_carousel_draggable', true);
    $slcPauseOnHover  = get_post_meta($post->ID, 'slc_carousel_pauseonhover', true);
    $slcSpeed = get_post_meta($post->ID, 'slc_carousel_speed', true);
    $slcSwipe = get_post_meta($post->ID, 'slc_carousel_swipe', true);

    // output our html
    echo '<label for="slc-carousel-accessibility" class="slc-label">Accessibility - <span class="slc-small">Enables tabbing and arrow key navigation. Default is <strong>true</strong>.</span></label>';
    echo '<select name="slc_carousel_accessibility" id="slc-carousel-accessibility">';
    if ($slcAccessibility == null || $slcAccessibility == '') {
        echo '<option value="true" selected>True</option>';
        echo '<option value="false">False</option>';
    } else {
        if ($slcAccessibility == 'true') {
            echo '<option value="true" selected>True</option>';
            echo '<option value="false">False</option>';
        } else {
            echo '<option value="true">True</option>';
            echo '<option value="false" selected>False</option>';
        }
    }
    echo '</select>';

    echo '<label for="slc-carousel-autoplay" class="slc-label">Autoplay - <span class="slc-small">Enables autoplay. Default is <strong>true</strong>.</span></label>';
    echo '<select name="slc_carousel_autoplay" id="slc-carousel-autoplay">';
    if ($slcAutoplay == null || $slcAutoplay == '') {
        echo '<option value="true" selected>True</option>';
        echo '<option value="false">False</option>';
    } else {
        if ($slcAutoplay == 'true') {
            echo '<option value="true" selected>True</option>';
            echo '<option value="false">False</option>';
        } else {
            echo '<option value="true">True</option>';
            echo '<option value="false" selected>False</option>';
        }
    }
    echo '</select>';

    echo '<label for="slc-carousel-autoplayspeed" class="slc-label">Autoplay Speed - <span class="slc-small">Autoplay speed in milliseconds. Default is <strong>3000</strong>.</span></label>';
    if($slcAutoplaySpeed == '' || $slcAutoplaySpeed == null) {
        echo '<input type="number" name="slc_carousel_autoplayspeed" id="slc-carousel-autoplayspeed" value="3000" />';
    } else {
        echo '<input type="number" name="slc_carousel_autoplayspeed" id="slc-carousel-autoplayspeed" value="' . $slcAutoplaySpeed . '" />';
    }

    echo '<label for="slc-carousel-arrows" class="slc-label">Arrows - <span class="slc-small">Show previous and next arrows. Default is <strong>false</strong>.</span></label>';
    echo '<select name="slc_carousel_arrows" id="slc-carousel-arrows">';
    if ($slcArrows == null || $slcArrows == '') {
        echo '<option value="true">True</option>';
        echo '<option value="false" selected>False</option>';
    } else {
        if ($slcArrows == 'true') {
            echo '<option value="true" selected>True</option>';
            echo '<option value="false">False</option>';
        } else {
            echo '<option value="true">True</option>';
            echo '<option value="false" selected>False</option>';
        }
    }
    echo '</select>';

    echo '<label for="slc-carousel-arrowcolor" class="slc-label">Arrow Color - <span class="slc-small">The color of the arrows. Default is <strong>#222222</strong>.</span></label>';
    if ($slcArrowColor == null || $slcArrowColor == '') {
        echo '<input type="text" class="color-field" name="slc_carousel_arrowcolor" id="slc-carousel-arrowcolor" value="#222222" />';
    } else {
        echo '<input type="text" class="color-field" name="slc_carousel_arrowcolor" id="slc-carousel-arrowcolor" value="' . $slcArrowColor . '" />';
    }

    echo '<label for="slc-carousel-arrowsize" class="slc-label">Arrow Size - <span class="slc-small">The size of the arrows. All CSS units are available. Default is <strong>50px</strong>.</span></label>';
    if ($slcArrowSize == null || $slcArrowSize == '') {
        echo '<input type="text" name="slc_carousel_arrowsize" id="slc-carousel-arrowsize" value="50px" />';
    } else {
        echo '<input type="text" name="slc_carousel_arrowsize" id="slc-carousel-arrowsize" value="' . $slcArrowSize . '" />';
    }

    echo '<label for="slc-carousel-arrowoffset" class="slc-label">Arrow Offset - <span class="slc-small">How far out the arrows are on the left and right sides. All CSS units are available. Default is <strong>-25px</strong>.</span></label>';
    if ($slcArrowOffset == null || $slcArrowOffset == '') {
        echo '<input type="text" name="slc_carousel_arrowoffset" id="slc-carousel-arrowoffset" value="-25px" />';
    } else {
        echo '<input type="text" name="slc_carousel_arrowoffset" id="slc-carousel-arrowoffset" value="' . $slcArrowOffset . '" />';
    }

    echo '<label for="slc-carousel-centermode" class="slc-label">Center Mode - <span class="slc-small">Enables centered view with partial view of the previous and next logos. Use with odd-numbered logo count. Default is <strong>false</strong>.</span></label>';
    echo '<select name="slc_carousel_centermode" id="slc-carousel-centermode">';
    if ($slcCenterMode == null || $slcCenterMode == '') {
        echo '<option value="true">True</option>';
        echo '<option value="false" selected>False</option>';
    } else {
        if ($slcCenterMode == 'true') {
            echo '<option value="true" selected>True</option>';
            echo '<option value="false">False</option>';
        } else {
            echo '<option value="true">True</option>';
            echo '<option value="false" selected>False</option>';
        }
    }
    echo '</select>';

    echo '<label for="slc-carousel-animation" class="slc-label">Animation - <span class="slc-small">Animation type. Default is <strong>ease</strong>.</span></label>';
    echo '<select name="slc_carousel_animation" id="slc-carousel-animation">';
    if ($slcAnimation == null || $slcAnimation == '') {
        echo '<option value="linear">Linear</option>';
        echo '<option value="ease" selected>Ease</option>';
        echo '<option value="ease-in">Ease In</option>';
        echo '<option value="ease-out">Ease Out</option>';
        echo '<option value="ease-in-out">Ease In Out</option>';
    } else {
        if ($slcAnimation == 'linear') {
            echo '<option value="linear" selected>Linear</option>';
            echo '<option value="ease">Ease</option>';
            echo '<option value="ease-in">Ease In</option>';
            echo '<option value="ease-out">Ease Out</option>';
            echo '<option value="ease-in-out">Ease In Out</option>';
        } else if ($slcAnimation == 'ease') {
            echo '<option value="linear">Linear</option>';
            echo '<option value="ease" selected>Ease</option>';
            echo '<option value="ease-in">Ease In</option>';
            echo '<option value="ease-out">Ease Out</option>';
            echo '<option value="ease-in-out">Ease In Out</option>';
        } else if ($slcAnimation == 'ease-in') {
            echo '<option value="linear">Linear</option>';
            echo '<option value="ease">Ease</option>';
            echo '<option value="ease-in" selected>Ease In</option>';
            echo '<option value="ease-out">Ease Out</option>';
            echo '<option value="ease-in-out">Ease In Out</option>';
        } else if ($slcAnimation == 'ease-out') {
            echo '<option value="linear">Linear</option>';
            echo '<option value="ease">Ease</option>';
            echo '<option value="ease-in">Ease In</option>';
            echo '<option value="ease-out" selected>Ease Out</option>';
            echo '<option value="ease-in-out">Ease In Out</option>';
        } else if ($slcAnimation == 'ease-in-out') {
            echo '<option value="linear">Linear</option>';
            echo '<option value="ease">Ease</option>';
            echo '<option value="ease-in">Ease In</option>';
            echo '<option value="ease-out">Ease Out</option>';
            echo '<option value="ease-in-out" selected>Ease In Out</option>';
        }
    }
    echo '</select>';

    echo '<label for="slc-carousel-draggable" class="slc-label">Draggable - <span class="slc-small">Enable mouse dragging. Default is <strong>true</strong>.</span></label>';
    echo '<select name="slc_carousel_draggable" id="slc-carousel-draggable">';
    if ($slcDraggable == null || $slcDraggable == '') {
        echo '<option value="true" selected>True</option>';
        echo '<option value="false">False</option>';
    } else {
        if ($slcDraggable == 'true') {
            echo '<option value="true" selected>True</option>';
            echo '<option value="false">False</option>';
        } else {
            echo '<option value="true">True</option>';
            echo '<option value="false" selected>False</option>';
        }
    }
    echo '</select>';

    echo '<label for="slc-carousel-pauseonfocus" class="slc-label">Pause On Focus - <span class="slc-small">Pause autoplay on focus. Default is <strong>true</strong>.</span></label>';
    echo '<select name="slc_carousel_pauseonfocus" id="slc-carousel-pauseonfocus">';
    if ($slcPauseOnFocus == null || $slcPauseOnFocus == '') {
        echo '<option value="true" selected>True</option>';
        echo '<option value="false">False</option>';
    } else {
        if ($slcPauseOnFocus == 'true') {
            echo '<option value="true" selected>True</option>';
            echo '<option value="false">False</option>';
        } else {
            echo '<option value="true">True</option>';
            echo '<option value="false" selected>False</option>';
        }
    }
    echo '</select>';

    echo '<label for="slc-carousel-pauseonhover" class="slc-label">Pause On Hover - <span class="slc-small">Pause autoplay on hover. Default is <strong>true</strong>.</span></label>';
    echo '<select name="slc_carousel_pauseonhover" id="slc-carousel-pauseonhover">';
    if ($slcPauseOnHover == null || $slcPauseOnHover == '') {
        echo '<option value="true" selected>True</option>';
        echo '<option value="false">False</option>';
    } else {
        if ($slcPauseOnHover == 'true') {
            echo '<option value="true" selected>True</option>';
            echo '<option value="false">False</option>';
        } else {
            echo '<option value="true">True</option>';
            echo '<option value="false" selected>False</option>';
        }
    }
    echo '</select>';
    
    echo '<label for="slc-carousel-speed" class="slc-label">Speed - <span class="slc-small">Carousel animation speed. Default is <strong>1000</strong>.</span></label>';
    if($slcSpeed == '' || $slcSpeed == null) {
        echo '<input type="number" name="slc_carousel_speed" id="slc-carousel-speed" value="1000" />';
    } else {
        echo '<input type="number" name="slc_carousel_speed" id="slc-carousel-speed" value="' . $slcSpeed . '" />';
    }

    echo '<label for="slc-carousel-swipe" class="slc-label">Swipe - <span class="slc-small">Enable swiping. Default is <strong>true</strong>.</span></label>';
    echo '<select name="slc_carousel_swipe" id="slc-carousel-swipe">';
    if ($slcSwipe == null || $slcSwipe == '') {
        echo '<option value="true" selected>True</option>';
        echo '<option value="false">False</option>';
    } else {
        if ($slcSwipe == 'true') {
            echo '<option value="true" selected>True</option>';
            echo '<option value="false">False</option>';
        } else {
            echo '<option value="true">True</option>';
            echo '<option value="false" selected>False</option>';
        }
    }
    echo '</select>';
    echo '<br><br>';

    // echo out our javascript for the color picker
    echo '<script>
        jQuery(document).ready(function($){
            $(".color-field").each( function() {
                $(this).wpColorPicker();
            });
        });
    </script>';
}