<?php

// get our data information
$options = get_option('slc_initial_carousel_options');
$slcShowTitle = esc_attr($options['slc_carousel_show_title']);
$slcTitleColor = esc_attr($options['slc_carousel_title_color']);
$slcHoverTextColor = esc_attr($options['slc_carousel_hover_text_color']);
$slcHoverTextBackgroundColor = esc_attr($options['slc_carousel_hover_text_background_color']);
$slcHoverTextBackgroundColorOpacity = esc_attr($options['slc_carousel_hover_text_background_color_opacity']);
$slcAccessibility = esc_attr($options['slc_carousel_accessibility']);
$slcAutoplay = esc_attr($options['slc_carousel_autoplay']);
$slcAutoplaySpeed = esc_attr($options['slc_carousel_autoplay_speed']);
$slcArrows = esc_attr($options['slc_carousel_arrows']);
$slcArrowColor = esc_attr($options['slc_carousel_arrow_color']);
$slcArrowSize = esc_attr($options['slc_carousel_arrow_size']);
$slcArrowOffset = esc_attr($options['slc_carousel_arrow_offset']);
$slcCustomArrows = esc_attr($options['slc_carousel_custom_arrows']);
$slcLeftArrowImage = esc_attr($options['slc_carousel_left_arrow_image']);
$slcRightArrowImage = esc_attr($options['slc_carousel_right_arrow_image']);
$slcArrowImageMaxWidth = esc_attr($options['slc_carousel_arrow_image_max_width']);
$slcCenterMode = esc_attr($options['slc_carousel_center_mode']);
$slcAnimation = esc_attr($options['slc_carousel_animation']);
$slcDraggable = esc_attr($options['slc_carousel_draggable']);
$slcPauseOnHover = esc_attr($options['slc_carousel_pause_on_hover']);
$slcPauseOnFocus = esc_attr($options['slc_carousel_pause_on_focus']);
$slcSpeed = esc_attr($options['slc_carousel_speed']);
$slcSwipe = esc_attr($options['slc_carousel_swipe']);
$slcSlideVerticalAlignment = esc_attr($options['slc_carousel_slide_vertical_alignment']);
$slcDisableLazyLoadClass = esc_attr($options['slc_carousel_disable_lazy_load_class']);
?>

<?php if (isset($_GET['message']) && $_GET['message'] == '1') { ?>
    <div id='message' class='updated fade'>
        <p><strong><?php _e('Settings Updated', 'tennis-plus-court-booking'); ?></strong></p>
    </div>
<?php } ?>

<div class="wrap">
    <h1><?php _e('Settings'); ?></h1>
    <h2 class="title"><?php _e('Initial Carousel Options', 'simple-logo-carousel'); ?></h2>
    <p><?php _e('The initial carousel options are loaded on every new carousel made.', 'simple-logo-carousel'); ?></p>
    <!-- FORM -->
    <form method="post" method="post" action="admin-post.php">
        <?php wp_nonce_field('slc_update_initial_carousel_options_nonce'); ?>
        <input type="hidden" name="action" value="update_initial_carousel_options"/>
        <!-- TABLE -->
        <table class="form-table">
            <!-- SHOW TITLE -->
            <tr>
                <th>
                    <label for="slc-carousel-show-title"><?php _e('Show Title', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_show_title" id="slc-carousel-show-title">
                        <?php
                        if (empty($slcShowTitle)) {
                            ?>
                            <option value="true"><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false" selected><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="true"
                                <?php if ($slcShowTitle == 'true') {
                                    echo 'selected';
                                } ?>><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"
                                <?php if ($slcShowTitle != 'true') {
                                    echo 'selected';
                                } ?>><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Shows title under logo. Default is ', 'simple-logo-carousel'); ?>
                        <strong><?php _e('false', 'simple-logo-carousel'); ?></strong>.</p>
                </td>
            </tr>
            <!-- TITLE COLOR -->
            <tr>
                <th>
                    <label for="slc-carousel-title-color"><?php _e('Title Color', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="text" class="color-field" name="slc_carousel_title_color" id="slc-carousel-title-color"
                           value="<?php if (empty($slcTitleColor)) {
                               echo '#222222';
                           } else {
                               esc_attr_e($slcTitleColor);
                           } ?>"/>
                    <p class="description"><?php _e('The color of the title. Default is', 'simple-logo-carousel'); ?>
                        <strong>#222222</strong>.</p>
                </td>
            </tr>
            <!-- HOVER TEXT COLOR -->
            <tr>
                <th>
                    <label for="slc-carousel-hover-text-color"><?php _e('Hover Text Color', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="text" class="color-field" name="slc_carousel_hover_text_color"
                           id="slc-carousel-hover-text-color"
                           value="<?php if (empty($slcHoverTextColor)) {
                               echo '#222222';
                           } else {
                               esc_attr_e($slcHoverTextColor);
                           } ?>"/>
                    <p class="description"><?php _e('The color of the hover text. Default is', 'simple-logo-carousel'); ?>
                        <strong>#222222</strong>.</p>
                </td>
            </tr>
            <!-- HOVER TEXT BACKGROUND COLOR -->
            <tr>
                <th>
                    <label for="slc-carousel-hover-text-background-color"><?php _e('Hover Text Background Color', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="text" class="color-field" name="slc_carousel_hover_text_background_color"
                           id="slc-carousel-hover-text-background-color"
                           value="<?php if (empty($slcHoverTextBackgroundColor)) {
                               echo '#ffffff';
                           } else {
                               esc_attr_e($slcHoverTextBackgroundColor);
                           } ?>"/>
                    <p class="description"><?php _e('The color of the hover text background. Default is', 'simple-logo-carousel'); ?>
                        <strong>#ffffff</strong>.</p>
                </td>
            </tr>
            <!-- HOVER TEXT BACKGROUND COLOR OPACITY -->
            <tr>
                <th>
                    <label for="slc-carousel-hover-text-background-color-opacity"><?php _e('Hover Text Background Color Opacity', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="number" name="slc_carousel_hover_text_background_color_opacity"
                           id="slc-carousel-hover-text-background-color-opacity" min="0" max="1" step="0.1"
                           value="<?php if (empty($slcHoverTextBackgroundColorOpacity)) {
                               echo '0.8';
                           } else {
                               esc_attr_e($slcHoverTextBackgroundColorOpacity);
                           } ?>"/>
                    <p class="description"><?php _e('The opacity of the hover text background. Default is', 'simple-logo-carousel'); ?>
                        <strong>0.8</strong>.</p>
                </td>
            </tr>
            <!-- ACCESSIBILITY -->
            <tr>
                <th>
                    <label for="slc-carousel-accessibility"><?php _e('Accessibility', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_accessibility" id="slc-carousel-accessibility">
                        <?php
                        if (empty($slcAccessibility)) {
                            ?>
                            <option value="true" selected><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="true"
                                <?php if ($slcAccessibility == 'true') {
                                    echo 'selected';
                                } ?>><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"
                                <?php if ($slcAccessibility != 'true') {
                                    echo 'selected';
                                } ?>><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Enables tabbing and arrow key navigation. Default is'); ?>
                        <strong><?php _e('true', 'simple-logo-carousel'); ?></strong>.</p>
                </td>
            </tr>
            <!-- AUTOPLAY -->
            <tr>
                <th>
                    <label for="slc-carousel-autoplay"><?php _e('Autoplay', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_autoplay" id="slc-carousel-autoplay">
                        <?php
                        if (empty($slcAutoplay)) {
                            ?>
                            <option value="true" selected><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="true"
                                <?php if ($slcAutoplay == 'true') {
                                    echo 'selected';
                                } ?>><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"
                                <?php if ($slcAutoplay != 'true') {
                                    echo 'selected';
                                } ?>><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Enables autoplay. Default is'); ?>
                        <strong><?php _e('true', 'simple-logo-carousel'); ?></strong>.</p>
                </td>
            </tr>
            <!-- AUTOPLAY SPEED -->
            <tr>
                <th>
                    <label for="slc-carousel-autoplay-speed"><?php _e('Autoplay Speed', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="number" name="slc_carousel_autoplay_speed" id="slc-carousel-autoplay-speed"
                           value="<?php if (!isset($slcAutoplaySpeed)) {
                               echo '3000';
                           } else {
                               esc_attr_e($slcAutoplaySpeed);
                           } ?>" min="0" step="1"/>
                    <p class="description"><?php _e('Autoplay speed in milliseconds. Default is', 'simple-logo-carousel'); ?>
                        <strong>3000</strong>.</p>
                </td>
            </tr>
            <!-- CAROUSEL ARROWS -->
            <tr>
                <th>
                    <label for="slc-carousel-arrows"><?php _e('Arrows', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_arrows" id="slc-carousel-arrows">
                        <?php
                        if (empty($slcArrows)) {
                            ?>
                            <option value="true"><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false" selected><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="true"
                                <?php if ($slcArrows == 'true') {
                                    echo 'selected';
                                } ?>><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"
                                <?php if ($slcArrows != 'true') {
                                    echo 'selected';
                                } ?>><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Show previous and next arrows. Default is', 'simple-logo-carousel'); ?>
                        <strong><?php _e('false', 'simple-logo-carousel'); ?></strong>.</p>
                </td>
            </tr>
            <!-- ARROW COLOR -->
            <tr>
                <th>
                    <label for="slc-carousel-arrow-color"><?php _e('Arrow Color', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="text" class="color-field" name="slc_carousel_arrow_color" id="slc-carousel-arrow-color"
                           value="<?php if (empty($slcArrowColor)) {
                               echo '#222222';
                           } else {
                               esc_attr_e($slcArrowColor);
                           } ?>"/>
                    <p class="description"><?php _e('The color of the arrows. Default is', 'simple-logo-carousel'); ?>
                        <strong>#222222</strong>.</p>
                </td>
            </tr>
            <!-- ARROW SIZE -->
            <tr>
                <th>
                    <label for="slc-carousel-arrow-size"><?php _e('Arrow Size', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="text" name="slc_carousel_arrow_size" id="slc-carousel-arrow-size"
                           value="<?php if (empty($slcArrowSize)) {
                               echo '50px';
                           } else {
                               esc_attr_e($slcArrowSize);
                           } ?>"/>
                    <p class="description"><?php _e('The size of the arrows. All CSS units are available. Default is', 'simple-logo-carousel'); ?>
                        <strong>50px</strong>.</p>
                </td>
            </tr>
            <!-- ARROW OFFSET -->
            <tr>
                <th>
                    <label for="slc-carousel-arrow-offset"><?php _e('Arrow Offset', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="text" name="slc_carousel_arrow_offset" id="slc-carousel-arrow-offset"
                           value="<?php if (empty($slcArrowOffset)) {
                               echo '-25px';
                           } else {
                               esc_attr_e($slcArrowOffset);
                           } ?>"/>
                    <p class="description"><?php _e('How far out the arrows are on the left and right sides. All CSS units are available. Default is', 'simple-logo-carousel'); ?>
                        <strong>-25px</strong>.</p>
                </td>
            </tr>
            <!-- CUSTOM ARROWS -->
            <tr>
                <th>
                    <label for="slc-carousel-custom-arrows"><?php _e('Custom Arrows', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_custom_arrows" id="slc-carousel-custom-arrows">
                        <?php
                        if (empty($slcCustomArrows)) {
                            ?>
                            <option value="true"><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false" selected><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="true"
                                <?php if ($slcCustomArrows == 'true') {
                                    echo 'selected';
                                } ?>><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"
                                <?php if ($slcCustomArrows != 'true') {
                                    echo 'selected';
                                } ?>><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Use custom arrows. Default is', 'simple-logo-carousel'); ?>
                        <strong><?php _e('false', 'simple-logo-carousel'); ?></strong>.</p>
                </td>
            </tr>
            <!-- LEFT ARROW IMAGE -->
            <tr>
                <th>
                    <label for="slc-carousel-left-arrow-image"><?php _e('Left Arrow Image', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="text" name="slc_carousel_left_arrow_image" id="slc-carousel-left-arrow-image"
                           value="<?php if (!empty($slcLeftArrowImage)) {
                               esc_attr_e($slcLeftArrowImage);
                           } ?>"/>
                    <p class="description"><?php _e('Left arrow image URL.', 'simple-logo-carousel'); ?></p>
                </td>
            </tr>
            <!-- RIGHT ARROW IMAGE -->
            <tr>
                <th>
                    <label for="slc-carousel-right-arrow-image"><?php _e('Right Arrow Image', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="text" name="slc_carousel_right_arrow_image" id="slc-carousel-right_arrow_image"
                           value="<?php if (!empty($slcRightArrowImage)) {
                               esc_attr_e($slcRightArrowImage);
                           } ?>"/>
                    <p class="description"><?php _e('Right arrow image URL.', 'simple-logo-carousel'); ?></p>
                </td>
            </tr>
            <!-- ARROW IMAGE MAX WIDTH -->
            <tr>
                <th>
                    <label for="slc-carousel-arrow-image-max-width"><?php _e('Arrow Image Max Width', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="text" name="slc_carousel_arrow_image_max_width" id="slc-carousel-arrow_image_max_width"
                           value="<?php if (empty($slcArrowImageMaxWidth)) {
                               echo '50px';
                           } else {
                               esc_attr_e($slcArrowImageMaxWidth);
                           } ?>"/>
                    <p class="description"><?php _e('Max width of arrow image. Default is', 'simple-logo-carousel'); ?>
                        <strong>50px</strong>.</p>
                </td>
            </tr>
            <!-- CENTER MODE -->
            <tr>
                <th>
                    <label for="slc-carousel-center-mode"><?php _e('Center Mode', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_center_mode" id="slc-carousel-center-mode">
                        <?php
                        if (empty($slcCenterMode)) {
                            ?>
                            <option value="true"><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false" selected><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="true"
                                <?php if ($slcCenterMode == 'true') {
                                    echo 'selected';
                                } ?>><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"
                                <?php if ($slcCenterMode != 'true') {
                                    echo 'selected';
                                } ?>><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Enables centered view with partial view of the previous and next logos. Use with odd-numbered logo count. Default is', 'simple-logo-carousel'); ?>
                        <strong><?php _e('false', 'simple-logo-carousel'); ?></strong>.</p>
                </td>
            </tr>
            <!-- CAROUSEL ANIMATION -->
            <tr>
                <th>
                    <label for="slc-carousel-animation"><?php _e('Animation', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_animation" id="slc-carousel-animation">
                        <?php
                        if (empty($slcAnimation)) {
                            ?>
                            <option value="linear"><?php _e('Linear', 'simple-logo-carousel'); ?></option>
                            <option value="ease" selected><?php _e('Ease', 'simple-logo-carousel'); ?></option>
                            <option value="ease-in"><?php _e('Ease In', 'simple-logo-carousel'); ?></option>
                            <option value="ease-out"><?php _e('Ease Out', 'simple-logo-carousel'); ?></option>
                            <option value="ease-in-out"><?php _e('Ease In Out', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="linear"
                                <?php if ($slcAnimation == 'linear') {
                                    echo 'selected';
                                } ?>><?php _e('Linear', 'simple-logo-carousel'); ?></option>
                            <option value="ease"
                                <?php if ($slcAnimation == 'ease') {
                                    echo 'selected';
                                } ?>><?php _e('Ease', 'simple-logo-carousel'); ?></option>
                            <option value="ease-in"
                                <?php if ($slcAnimation == 'ease-in') {
                                    echo 'selected';
                                } ?>><?php _e('Ease In', 'simple-logo-carousel'); ?></option>
                            <option value="ease-out"
                                <?php if ($slcAnimation == 'ease-out') {
                                    echo 'selected';
                                } ?>><?php _e('Ease Out', 'simple-logo-carousel'); ?></option>
                            <option value="ease-in-out"
                                <?php if ($slcAnimation == 'ease-in-out') {
                                    echo 'selected';
                                } ?>><?php _e('Ease In Out', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Animation type. Default is'); ?>
                        <strong><?php _e('ease', 'simple-logo-carousel'); ?></strong>.</p>
                </td>
            </tr>
            <!-- CAROUSEL DRAGGABLE -->
            <tr>
                <th>
                    <label for="slc-carousel-draggable"><?php _e('Draggable', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_draggable" id="slc-carousel-draggable">
                        <?php
                        if (empty($slcDraggable)) {
                            ?>
                            <option value="true" selected><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="true"
                                <?php if ($slcDraggable == 'true') {
                                    echo 'selected';
                                } ?>><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"
                                <?php if ($slcDraggable != 'true') {
                                    echo 'selected';
                                } ?>><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Enable mouse dragging. Default is', 'simple-logo-carousel'); ?>
                        <strong><?php _e('true', 'simple-logo-carousel'); ?> </strong>.</p>
                </td>
            </tr>
            <!-- PAUSE ON FOCUS -->
            <tr>
                <th>
                    <label for="slc-carousel-pause-on-focus"><?php _e('Pause On Focus', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_pause_on_focus" id="slc-carousel-pause-on-focus">
                        <?php
                        if (empty($slcPauseOnFocus)) {
                            ?>
                            <option value="true" selected><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="true"
                                <?php if ($slcPauseOnFocus == 'true') {
                                    echo 'selected';
                                } ?>><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"
                                <?php if ($slcPauseOnFocus != 'true') {
                                    echo 'selected';
                                } ?>><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Pause autoplay on focus. Default is', 'simple-logo-carousel'); ?>
                        <strong><?php _e('true', 'simple-logo-carousel'); ?></strong>.</p>
                </td>
            </tr>
            <!-- PAUSE ON HOVER -->
            <tr>
                <th>
                    <label for="slc-carousel-pause-on-hover"><?php _e('Pause On Hover', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_pause_on_hover" id="slc-carousel-pause-on-hover">
                        <?php
                        if (empty($slcPauseOnHover)) {
                            ?>
                            <option value="true" selected><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="true"
                                <?php if ($slcPauseOnHover == 'true') {
                                    echo 'selected';
                                } ?>><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"
                                <?php if ($slcPauseOnHover != 'true') {
                                    echo 'selected';
                                } ?>><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Pause autoplay on hover. Default is', 'simple-logo-carousel'); ?>
                        <strong><?php _e('true', 'simple-logo-carousel'); ?></strong>.</p>
                </td>
            </tr>
            <!-- SLIDE VERTICAL ALIGNMENT -->
            <tr>
                <th>
                    <label for="slc-carousel-slide-vertical-alignment"><?php _e('Slide Vertical Alignment', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_slide_vertical_alignment" id="slc-carousel-slide-vertical-alignment">
                        <?php
                        if (empty($slcSlideVerticalAlignment)) {
                            ?>
                            <option value="flex-start"><?php _e('Top', 'simple-logo-carousel'); ?></option>
                            <option value="center" selected><?php _e('Middle', 'simple-logo-carousel'); ?></option>
                            <option value="flex-end"><?php _e('Bottom', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="flex-start"
                                <?php if ($slcSlideVerticalAlignment == 'flex-start') {
                                    echo 'selected';
                                } ?>><?php _e('Top', 'simple-logo-carousel'); ?></option>
                            <option value="center"
                                <?php if ($slcSlideVerticalAlignment == 'center') {
                                    echo 'selected';
                                } ?>><?php _e('Middle', 'simple-logo-carousel'); ?></option>
                            <option value="bottom"
                                <?php if ($slcSlideVerticalAlignment == 'flex-end') {
                                    echo 'selected';
                                } ?>><?php _e('Bottom', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Slide alignment on the carousel. Default is', 'simple-logo-carousel'); ?>
                        <strong><?php _e('middle', 'simple-logo-carousel'); ?></strong>.</p>
                </td>
            </tr>
            <!-- CAROUSEL SPEED -->
            <tr>
                <th>
                    <label for="slc-carousel-speed"><?php _e('Speed', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="number" name="slc_carousel_speed" id="slc-carousel-speed"
                           value="<?php if (empty($slcSpeed)) {
                               echo '1000';
                           } else {
                               esc_attr_e($slcSpeed);
                           } ?>" min="1" step="1"/>
                    <p class="description"><?php _e('Carousel animation speed. Default is', 'simple-logo-carousel'); ?>
                        <strong>1000</strong>.</p>
                </td>
            </tr>
            <!-- CAROUSEL SWIPE -->
            <tr>
                <th>
                    <label for="slc-carousel-swipe"><?php _e('Swipe', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <select name="slc_carousel_swipe" id="slc-carousel-swipe">
                        <?php
                        if (empty($slcSwipe)) {
                            ?>
                            <option value="true" selected><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="true"
                                <?php if ($slcSwipe == 'true') {
                                    echo 'selected';
                                } ?>><?php _e('True', 'simple-logo-carousel'); ?></option>
                            <option value="false"
                                <?php if ($slcSwipe != 'true') {
                                    echo 'selected';
                                } ?>><?php _e('False', 'simple-logo-carousel'); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <p class="description"><?php _e('Enable swiping. Default is', 'simple-logo-carousel'); ?>
                        <strong><?php _e('true', 'simple-logo-carousel'); ?></strong>.</p>
                </td>
            </tr>
            <!-- DISABLE LAZY LOAD CLASS -->
            <tr>
                <th>
                    <label for="slc-carousel-disable-lazy-load-class"><?php esc_html_e('Disable Lazy Load Class', 'simple-logo-carousel'); ?></label>
                </th>
                <td>
                    <input type="text" name="slc_carousel_disable_lazy_load_class"
                           id="slc-carousel-disable-lazy-load-class"
                           value="<?php if (empty($slcDisableLazyLoadClass)) {
                               echo '';
                           } else {
                               esc_attr_e($slcDisableLazyLoadClass);
                           } ?>"/>
                    <p class="description"><?php esc_html_e('Add class to disable lazy loading. Multiple classes should be separated by a space.', 'simple-logo-carousel'); ?></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>