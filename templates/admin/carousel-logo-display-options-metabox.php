<?php

// get our post
global $post;

// validation to make sure the request came from this page and site
wp_nonce_field('verify_slc', 'slc_cpt');

// get our meta information
$slcLogoDisplayOptions = get_post_meta($post->ID, 'slc_carousel_logo_display_options', true);
?>

<table id="slc-logo-display-options-table">
    <thead>
    <tr>
        <th></th>
        <th><?php _e('Breakpoint', 'simple-logo-carousel', 'simple-logo-carousel'); ?> <span
                    class="slc-required">*</span></th>
        <th><?php _e('Options', 'simple-logo-carousel', 'simple-logo-carousel'); ?> <span class="slc-required">*</span>
        </th>
        <th></th>
    </tr>
    </thead>
    <tbody class="slc-main-tbody">
    <?php if (empty($slcLogoDisplayOptions)) { ?>
        <tr data-id="default">
            <td></td>
            <td>
                <input type="number" class="breakpoint" value=""/>
                <span class="size">px</span>
                <span class="default"><?php _e('Default', 'simple-logo-carousel'); ?></span>
            </td>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <label><?php _e('Slides To Show', 'simple-logo-carousel'); ?></label>
                        </th>
                        <td>
                            <input type="number" class="slides-to-show" value="1" min="1" step="1" required/>
                            <p class="description"><?php _e('Numbers of logos to show. Default is', 'simple-logo-carousel'); ?>
                                <strong>1</strong>.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Slides to Scroll', 'simple-logo-carousel'); ?></label>
                        </th>
                        <td>
                            <input type="number" class="slides-to-scroll" value="1" min="1" step="1" required/>
                            <p class="description"><?php _e('Numbers of logos to scroll at a time. Default is', 'simple-logo-carousel'); ?>
                                <strong>1</strong>.</p>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <a class="delete-row" href="#"><span>-</span></a>
            </td>
        </tr>
    <?php } else {
        $breakpoints = json_decode($slcLogoDisplayOptions);

        foreach ($breakpoints as $breakpoint) {
            ?>
            <tr <?php if ($breakpoint->breakpoint == 'default') { ?>data-id="default"
                <?php } else { ?>class="slc-draggable"<?php } ?>>
                <td>
                    <?php if ($breakpoint->breakpoint != 'default') { ?>
                        <img class="slc-sort-btn"
                             src="<?php echo $this->plugin_url . 'assets/admin/images/sort-solid.svg'; ?>"/>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($breakpoint->breakpoint == 'default') { ?>
                        <input type="number" class="breakpoint" value=""/>
                        <span class="size">px</span>
                        <span class="default"><?php _e('Default', 'simple-logo-carousel', 'simple-logo-carousel'); ?></span>
                    <?php } else { ?>
                        <input type="number" class="breakpoint" value="<?php echo $breakpoint->breakpoint; ?>"
                               min="1" step="1" required/>
                        <span class="size">px</span>
                    <?php } ?>
                </td>
                <td>
                    <table class="form-table">
                        <tr>
                            <th>
                                <label><?php _e('Slides To Show', 'simple-logo-carousel'); ?></label>
                            </th>
                            <td>
                                <input type="number" class="slides-to-show" value="<?php echo $breakpoint->show; ?>"
                                       min="1" step="1" required/>
                                <p class="description"><?php _e('Numbers of logos to show. Default is', 'simple-logo-carousel'); ?>
                                    <strong>1</strong>.</p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php _e('Slides to Scroll', 'simple-logo-carousel'); ?></label>
                            </th>
                            <td>
                                <input type="number" class="slides-to-scroll" value="<?php echo $breakpoint->scroll; ?>"
                                       min="1" step="1" required/>
                                <p class="description"><?php _e('Numbers of logos to scroll at a time. Default is', 'simple-logo-carousel'); ?>
                                    <strong>1</strong>.</p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <a class="delete-row" href="#"><span>-</span></a>
                </td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>

<textarea name="slc_carousel_logo_display_options" id="slc-carousel-logo-display-options">
    <?php if (empty($slcLogoDisplayOptions)) { ?>
        [{"breakpoint": "default", "show": 1, "scroll": 1}]
    <?php } else {
        echo esc_html($slcLogoDisplayOptions);
    } ?>
</textarea>

<div id="slc-logo-display-options-footer">
    <a href="#" id="sort-breakpoint-btn" class="button button-large">Sort</a>
    <a href="#" id="add-breakpoint-btn" class="button button-primary button-large">Add Breakpoint</a>
</div>