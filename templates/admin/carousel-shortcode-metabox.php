<?php

// get our post
global $post;
?>

<label for="slc-alt-text" class="slc-label">
    <span class="slc-small"><?php esc_html_e('Copy and paste the shortcode below to display your carousel.', 'simple-logo-carousel'); ?></span>
</label>
<input type="text" name="slc_external_url" id="slc-external-url" class="slc-text-field"
       value="[simple-logo-carousel id=<?php esc_attr_e($post->ID); ?>]" readonly>