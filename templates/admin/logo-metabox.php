<?php

// get our post
global $post;

// validation to make sure the request came from this page and site
wp_nonce_field('verify_slc', 'slc_cpt');

// get our meta information
$slcExternalURL = esc_url(get_post_meta($post->ID, 'slc_external_url', true));
$slcUrlTarget = esc_html(get_post_meta($post->ID, 'slc_url_target', true));
$slcAltText = esc_html(get_post_meta($post->ID, 'slc_alt_text', true));
$slcHoverText = esc_html(get_post_meta($post->ID, 'slc_hover_text', true));
?>

<table class="form-table">
    <!-- LINK TO URL -->
    <tr>
        <th>
            <label for="slc-external-url"><?php _e('Link To URL', 'simple-logo-carousel'); ?></label>
        </th>
        <td>
            <input type="text" name="slc_external_url" id="slc-external-url" class="slc-text-field"
                   value="<?php echo $slcExternalURL; ?>" placeholder="Please enter the full URL">
            <p class="description"><?php _e('Enter the URL in the field below to have your logo link to a page or external site. Leave this option blank if you do not wish to have it link anywhere.', 'simple-logo-carousel'); ?></p>
        </td>
    </tr>
    <!-- URL TARGET -->
    <tr>
        <th>
            <label for="slc-url-target"><?php _e('Target', 'simple-logo-carousel'); ?> </label>
        </th>
        <td>
            <select name="slc_url_target" id="slc-url-target" class="slc-text-field">
                <?php
                if (empty($slcUrlTarget)) {
                    ?>
                    <option value="_blank" selected><?php _e('New Window or Tab', 'simple-logo-carousel'); ?></option>
                    <option value="_self"><?php _e('Same Window', 'simple-logo-carousel'); ?></option>
                    <?php
                } else {
                    ?>
                    <option value="_blank"
                            <?php if ($slcUrlTarget == '_blank') { ?>selected<?php } ?>><?php _e('New Window or Tab', 'simple-logo-carousel'); ?></option>'
                    <option value="_self"
                            <?php if ($slcUrlTarget != '_blank') { ?>selected<?php } ?>><?php _e('Same Window', 'simple-logo-carousel'); ?></option>'
                    <?php
                }
                ?>
            </select>
            <p class="description"><?php _e('Specify how you\'d like to open up the link above. This option will not matter if the field above is blank.', 'simple-logo-carousel'); ?></p>
        </td>
    </tr>
    <!-- ALT TEXT -->
    <tr>
        <th>
            <label for="slc-alt-text"><?php _e('Alt Text', 'simple-logo-carousel'); ?></label>
        </th>
        <td>
            <input type="text" name="slc_alt_text" id="slc-alt-text" class="slc-text-field"
                   value="<?php echo $slcAltText; ?>"
                   placeholder="Alt Text">
            <p class="description"><?php _e('Leave this field blank to default to the image\'s default alt text. If there is none, this will default to the title.', 'simple-logo-carousel'); ?></p>
        </td>
    </tr>
    <!-- HOVER TEXT -->
    <tr>
        <th>
            <label for="slc-hover-text"><?php _e('Hover Text', 'simple-logo-carousel'); ?></label>
        </th>
        <td>
            <input type="text" name="slc_hover_text" id="slc-hover-text" class="slc-text-field"
                   value="<?php echo $slcHoverText; ?>"
                   placeholder="Hover Text">
            <p class="description"><?php _e('Leave this field blank if you do not want to use this feature. If there is text here, this text will appear upon hovering over your logo.', 'simple-logo-carousel'); ?></p>
        </td>
    </tr>
</table>