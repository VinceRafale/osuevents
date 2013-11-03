<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="content_wrapper">
    <style type="text/css">
        .content_wrapper img{
            max-width: none;
        }
    </style>
    <?php
    $args = array('post_type' => POST_TYPE);
    $loop = new WP_Query($args);
    ?>
    <?php while (have_posts()) : the_post(); ?>
        <div class="depth_article">
            <h1 class="title"><?php the_title(); ?></h1>
            <?php
            /**
             * Get post meta values from place post type
             */
            //Retrieving place details
            global $post, $wpdb, $cfield_tbl_name;
            $p_contactname = get_post_meta($post->ID, 'geocraft_contact', true);
            $p_address = get_post_meta($post->ID, 'geo_address', true);
            $p_number = get_post_meta($post->ID, 'geocraft_phone', true);
            $p_timing = get_post_meta($post->ID, 'geocraft_timing', true);
            $p_categories = get_the_term_list($post->ID, CUSTOM_CAT_TYPE, '', ',', '');
            $p_email = get_post_meta($post->ID, 'geocraft_meta_email', true);
            $p_website = get_post_meta($post->ID, 'geocraft_website', true);
            //Retrieving metabox image values
            $p_image1 = get_post_meta($post->ID, 'geocraft_meta_image1', true);
            $p_image2 = get_post_meta($post->ID, 'geocraft_meta_image2', true);
            $p_image3 = get_post_meta($post->ID, 'geocraft_meta_image3', true);
            $p_image4 = get_post_meta($post->ID, 'geocraft_meta_image4', true);
            $p_image5 = get_post_meta($post->ID, 'geocraft_meta_image5', true);
            $p_facebook = get_post_meta($post->ID, 'geocraft_facebook', true);
            $p_plus = get_post_meta($post->ID, 'geocraft_googleplus', true);
            $p_twitter = get_post_meta($post->ID, 'geocraft_twitter', true);
            $custom_meta = get_custom_field();
            $img2 = $wpdb->get_row("SELECT * FROM $cfield_tbl_name WHERE f_var_nm ='geocraft_meta_image2'", ARRAY_A);
            $img3 = $wpdb->get_row("SELECT * FROM $cfield_tbl_name WHERE f_var_nm ='geocraft_meta_image3'", ARRAY_A);
            $img4 = $wpdb->get_row("SELECT * FROM $cfield_tbl_name WHERE f_var_nm ='geocraft_meta_image4'", ARRAY_A);
            $img5 = $wpdb->get_row("SELECT * FROM $cfield_tbl_name WHERE f_var_nm ='geocraft_meta_image5'", ARRAY_A);
            $listing_type = get_post_meta($post->ID, 'geocraft_listing_type', true);
            $map_add = $wpdb->get_row("SELECT * FROM $cfield_tbl_name WHERE f_var_nm ='geo_address'", ARRAY_A);
            ?>
            <?php
            if ($p_image1 != '' || $p_image2 != '' || $p_image3 != '' || $p_image4 != '' || $p_image5 != '') {
                $omegaclass = "omega";
                ?>
                <div class="grid_12 alpha">
                    <!--Start Article Slider-->               
                    <div class="article_slider">               
                        <div class="flexslider">
                            <ul class="slides">
                                <?php if ($p_image1 != '') { ?>
                                    <li><img src="<?php echo $p_image1; ?>" /> </li>
                                <?php } ?> 
                                <?php if ($listing_type == 'free') { ?>
                                    <?php if (($p_image2 != '') && ($img2['show_free'] == 'true')) { ?>
                                        <li><img src="<?php echo $p_image2; ?>"/></li> <?php } ?>                                
                                    <?php if (($p_image3 != '') && ($img3['show_free'] == 'true')) { ?>
                                        <li><img src="<?php echo $p_image3; ?>" /> </li> <?php } ?>                               
                                    <?php if (($p_image4 != '') && ($img4['show_free'] == 'true')) { ?>
                                        <li><img src="<?php echo $p_image4; ?>" /> </li> <?php } ?>                                
                                    <?php if (($p_image5 != '') && ($img5['show_free'] == 'true')) { ?>
                                        <li><img src="<?php echo $p_image5; ?>" /> </li> <?php } ?>
                                <?php } else { ?>
                                    <?php if ($p_image2 != '') { ?>
                                        <li><img src="<?php echo $p_image2; ?>"/></li>
                                    <?php } ?><?php if ($p_image3 != '') { ?>
                                        <li><img src="<?php echo $p_image3; ?>"/></li>
                                    <?php } ?><?php if ($p_image4 != '') { ?>
                                        <li><img src="<?php echo $p_image4; ?>"/></li>
                                    <?php } ?><?php if ($p_image5 != '') { ?>
                                        <li><img src="<?php echo $p_image5; ?>"/></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>                
                    <!--End Article Slider-->
                </div>
            <?php } ?>  
            <div class="grid_12 <?php echo $omegaclass; ?>">
                <!--Start Article Details-->
                <div class="article_detail">  
                    <div class="article_rating">
                        <label class="rating"><?php echo RATING_TXT; ?></label>
                        <ul class="single_rating">
                            <?php
                            global $post;
                            $is_onlisting = 0;
                            echo geocraft_get_post_rating_star($post->ID);
                            ?>
                        </ul>
                        <label class="reviews"><img class="comment-nib" src="<?php echo TEMPLATEURL ?>/images/comments-32.png"/>&nbsp;<span class="review"><?php comments_number('No Review', '1 Review', '% Review'); ?></span></label>
                    </div>
                    <div class="tbl_des">
                        <table class="ar_desc" style="table-layout: fixed; width: 100%">
                            <?php
                            if ($p_categories):
                                ?>
                                <tr>
                                    <td class="label category"><?php echo S_CATEGORY; ?></td>
                                    <td><?php echo $p_categories; ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="label date"><?php echo S_P_DATE; ?> </td>
                                <td><?php the_time('F j, Y'); ?></td>
                            </tr>
                            <?php
                            $delicious = get_post_meta($post->ID, 'geocraft_delicious', true);
                            $dig = get_post_meta($post->ID, 'geocraft_digg', true);
                            $dribble = get_post_meta($post->ID, 'geocraft_dribbble', true);
                            $flickr = get_post_meta($post->ID, 'geocraft_flickr', true);
                            $linkedin = get_post_meta($post->ID, 'geocraft_linkedin', true);
                            $stumbleupon = get_post_meta($post->ID, 'geocraft_stumbleupon', true);
                            $skype = get_post_meta($post->ID, 'geocraft_skype', true);
                            if ($p_facebook || $p_twitter || $p_plus) :
                                $listing_type = get_post_meta($post->ID, 'geocraft_listing_type', true);
                                ?>
                                <tr <?php if ($listing_type == 'free') echo 'class="social_row"'; ?>>
                                    <td class="label social"><?php echo SOCIAL_LINKS; ?> </td>
                                    <td>
                                        <ul class="social_icon">
                                            <?php if ($p_facebook) : ?>
                                                <li><a target="_new" href="<?php echo $p_facebook; ?>"><img src="<?php echo TEMPLATEURL; ?>/images/facebook.png" alt="facebook" title="Facebook"/></a></li>
                                                <?php
                                            endif;
                                            if ($p_plus) :
                                                ?>
                                                <li><a target="_new" href="<?php echo $p_plus; ?>"><img src="<?php echo TEMPLATEURL; ?>/images/plus.png" alt="google plus" title="Google Plus"/></a></li>
                                                <?php
                                            endif;
                                            if ($p_twitter) :
                                                ?>
                                                <li><a target="_new" href="<?php echo $p_twitter; ?>"><img src="<?php echo TEMPLATEURL; ?>/images/twitter.png" alt="twitter" title="Twitter"/></a></li>
                                            <?php endif; ?>
                                            <?php
                                            if ($delicious) :
                                                ?>
                                                <li><a target="_new" href="<?php echo $delicious; ?>"><img src="<?php echo TEMPLATEURL; ?>/images/delicious_16.png" alt="delicious" title="Delicious"/></a></li>
                                            <?php endif; ?>
                                            <?php
                                            if ($dig) :
                                                ?>
                                                <li><a target="_new" href="<?php echo $dig; ?>"><img src="<?php echo TEMPLATEURL; ?>/images/digg_alt_16.png" alt="dig" title="Dig"/></a></li>
                                            <?php endif; ?>
                                            <?php
                                            if ($dribble) :
                                                ?>
                                                <li><a target="_new" href="<?php echo $dribble; ?>"><img src="<?php echo TEMPLATEURL; ?>/images/dribbble_16.png" alt="dribble" title="Dribble"/></a></li>
                                            <?php endif; ?>
                                            <?php
                                            if ($flickr) :
                                                ?>
                                                <li><a target="_new" href="<?php echo $flickr; ?>"><img src="<?php echo TEMPLATEURL; ?>/images/flickr_16.png" alt="flickr" title="Flickr"/></a></li>
                                            <?php endif; ?>
                                            <?php
                                            if ($linkedin) :
                                                ?>
                                                <li><a target="_new" href="<?php echo $linkedin; ?>"><img src="<?php echo TEMPLATEURL; ?>/images/linkedin_16.png" alt="linkedin" title="Linkedin"/></a></li>
                                            <?php endif; ?>
                                            <?php
                                            if ($stumbleupon) :
                                                ?>
                                                <li><a target="_new" href="<?php echo $stumbleupon; ?>"><img src="<?php echo TEMPLATEURL; ?>/images/stumbleupon_16.png" alt="stumbleupon" title="Stumbleupon"/></a></li>
                                            <?php endif; ?>
                                            <?php
                                            if ($skype) :
                                                ?>
                                                <li><a target="_new" href="<?php echo $skype; ?>"><img src="<?php echo TEMPLATEURL; ?>/images/skype_16.png" alt="skype" title="Skype"/></a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php
                            $listing_type = get_post_meta($post->ID, 'geocraft_listing_type', true);
                            $custom_meta = get_custom_field();
                            foreach ($custom_meta as $meta):
                                $field = $meta['name'];
                                $title = $meta['title'];
                                if ($meta['show_on_listing'] == 1) {
                                    if ($listing_type == 'free' && $meta['show_free'] == 'true') {
                                        if ($meta['type'] != 'image_uploader' && 
                                                $field != 'list_title' && 
                                                $field != 'geo_latitude' && 
                                                $field != 'geo_longitude' && 
                                                $field != 'geocraft_meta_image1' && 
                                                $field != 'geocraft_meta_image2' && 
                                                $field != 'geocraft_meta_image3' && 
                                                $field != 'geocraft_meta_image4' && 
                                                $field != 'geocraft_meta_image5' && 
                                                $field != 'geocraft_description' && 
                                                $field != 'geocraft_twitter' && 
                                                $field != 'geocraft_facebook' && 
                                                $field != 'geocraft_tag' &&
                                                $field != 'geocraft_digg' && 
                                                $field != 'geocraft_dribbble' && 
                                                $field != 'geocraft_googleplus' && 
                                                $field != 'geocraft_delicious' &&
                                                $field != 'geocraft_dribbble' &&
                                                $field != 'geocraft_flickr' &&
                                                $field != 'geocraft_linkedin' &&
                                                $field != 'geocraft_stumbleupon' &&
                                                $field != 'geocraft_skype') {
                                            if (get_post_meta($post->ID, $field, true)) {
                                                ?>
                                                <tr>
                                                       <td class="label default"><?php echo $title; ?> </td>
                                                    <td><?php
                                                    if ($field == 'geocraft_website')
                                                        echo '<a target="new" href="' . get_post_meta($post->ID, $field, true) . '">' . get_post_meta($post->ID, $field, true) . '</a>';
                                                    elseif ($field == 'geocraft_phone')
                                                        echo '<a href=tel:' . get_post_meta($post->ID, $field, true) . '>' . get_post_meta($post->ID, $field, true) . '</a>';
                                                    elseif ($field == 'geocraft_meta_email')
                                                        echo '<a href=mailto:' . get_post_meta($post->ID, $field, true) . '?Subject=subject here&Body=bodytext>' . get_post_meta($post->ID, $field, true) . '</a>';
                                                    else
                                                        echo get_post_meta($post->ID, $field, true);
                                                ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        if ($meta['type'] == 'image_uploader' && $meta['name'] != 'geocraft_meta_image1' && $meta['name'] != 'geocraft_meta_image2' && $meta['name'] != 'geocraft_meta_image3' && $meta['name'] != 'geocraft_meta_image4' && $meta['name'] != 'geocraft_meta_image5') {
                                            echo '<tr>';
                                            echo '<td class="label default">' . $meta['title'] . '</td>';
                                            echo '<td><img src="' . get_post_meta($post->ID, $field, true) . '" alt="Field Image" /></td>';
                                            echo '</tr>';
                                        }
                                    }
                                    if ($listing_type == 'pro') {
                                        if ($meta['type'] != 'image_uploader' && 
                                                $field != 'list_title' && 
                                                $field != 'geo_latitude' && 
                                                $field != 'geo_longitude' && 
                                                $field != 'geocraft_meta_image1' && 
                                                $field != 'geocraft_meta_image2' && 
                                                $field != 'geocraft_meta_image3' && 
                                                $field != 'geocraft_meta_image4' && 
                                                $field != 'geocraft_meta_image5' && 
                                                $field != 'geocraft_description' && 
                                                $field != 'geocraft_twitter' && 
                                                $field != 'geocraft_facebook' && 
                                                $field != 'geocraft_googleplus' && 
                                                $field != 'geocraft_tag' &&
                                                $field != 'geocraft_digg' && 
                                                $field != 'geocraft_dribbble' && 
                                                $field != 'geocraft_delicious' &&
                                                $field != 'geocraft_flickr' &&
                                                $field != 'geocraft_linkedin' &&
                                                $field != 'geocraft_stumbleupon' &&
                                                $field != 'geocraft_skype') {
                                            if (get_post_meta($post->ID, $field, true)) {
                                                ?>
                                                <tr>
                                                    <td class="label default"><?php echo $title; ?> </td>
                                                    <td><?php
                                                    if ($field == 'geocraft_website')
                                                        echo '<a target="new" href="' . get_post_meta($post->ID, $field, true) . '">' . get_post_meta($post->ID, $field, true) . '</a>';
                                                    elseif ($field == 'geocraft_phone')
                                                        echo '<a href=tel:' . get_post_meta($post->ID, $field, true) . '>' . get_post_meta($post->ID, $field, true) . '</a>';
                                                    elseif ($field == 'geocraft_meta_email')
                                                        echo '<a href=mailto:' . get_post_meta($post->ID, $field, true) . '?Subject=subject here&Body=bodytext>' . get_post_meta($post->ID, $field, true) . '</a>';
                                                    else
                                                        echo get_post_meta($post->ID, $field, true);
                                                ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        if ($meta['type'] == 'image_uploader' && 
                                                $meta['name'] != 'geocraft_meta_image1' && 
                                                $meta['name'] != 'geocraft_meta_image2' && 
                                                $meta['name'] != 'geocraft_meta_image3' && 
                                                $meta['name'] != 'geocraft_meta_image4' && 
                                                $meta['name'] != 'geocraft_meta_image5') {
                                            echo '<tr>';
                                            echo '<td class="label default">' . $meta['title'] . '</td>';
                                            echo '<td><img src="' . get_post_meta($post->ID, $field, true) . '" alt="Field Image" /></td>';
                                            echo '</tr>';
                                        }
                                    }
                                }
                            endforeach;
                            ?>                       
                        </table>
                    </div>
                </div>
                <!--End Article Details-->
            </div>       
            <div class="clear"></div>
        </div>
    <?php endwhile; ?>
    <div class="clear"></div>    
    <div class="grid_16 alpha">
        <div class="featured_content">            
            <div id="gc_tab" class="tabbed">
                <ul class="tabnav">
                    <li><a href="#popular"><?php echo S_DESCRIPTION; ?></a></li>
                    <li><a href="#featured"><?php echo S_REVIEWS; ?></a></li>
                </ul>
                <div id="popular" class="tabdiv">
                    <div class="tab_content">
                        <?php the_content(); ?>
                    </div>
                </div>
                <div id="featured" class="tabdiv"> 
                    <div>
                        <!--Start Comment box-->
                        <?php comments_template(); ?>
                        <!--End Comment box-->
                    </div>
                </div>
                <!--featured-->
            </div>
            <!--/widget-->
            <div class="clear"></div>
            <?php if (($map_add['show_free'] == 'true') || ($listing_type == 'pro')) { ?>
                <h2><?php echo S_L_MAP; ?></h2>
                <div style="border:1px solid #ccc;" class="map">
                    <?php gc_single_map(); ?>
                </div> <?php } ?>
            <div class="clear"></div>
            <br/>
            <nav id="nav-single"> <span class="nav-previous">
                    <?php previous_post_link('%link', __('<span class="meta-nav">&larr;</span> Previous Listing ', THEME_SLUG)); ?>
                </span> <span class="nav-next">
                    <?php next_post_link('%link', __('Next Listing <span class="meta-nav">&rarr;</span>', THEME_SLUG)); ?>
                </span> </nav>
            <div class="line"></div>
            <!--Start Related-->
            <div class="related_item">
                <h2><?php echo RELATED_LISTINGS; ?></h2>
                <?php geocraft_get_related_post(); ?>           

            </div>
            <!--End Related-->
        </div>
    </div>
    <div class="grid_8 omega">
        <?php
        $lead_valid = geocraft_get_option('lead_capture');
        $free_flag = false;
        $lead_free = geocraft_get_option('lead_capture_free');
        $listing_type = get_post_meta($post->ID, 'geocraft_listing_type', true);
        if($listing_type == 'free' && $lead_free == 'on'){
            $free_flag = true;
        }
        elseif($listing_type == 'free' && $lead_free !== 'on'){
            $free_flag = false;
        }
        elseif($listing_type == 'pro'){
            $free_flag = true;
        }
        if ($lead_valid == 'on') {
            if ($free_flag == true) {
                save_inquiry();
            }
        }
        ?>
        <?php get_sidebar('listing'); ?>      
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>