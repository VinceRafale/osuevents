<?php
/**
 * Main loop for displaying places
 *
 * @package Geocraft
 * @author InkThemes
 *
 */
?>
    <?php
    /* Get all Sticky Posts */
    $sticky = get_option('sticky_posts');
    /* Sort Sticky Posts, newest at the top */
    rsort($sticky);
    /* Get top 5 Sticky Posts */
    $sticky = array_slice($sticky, 0, 5);
    /* Query Sticky Posts */
    $limit = get_option('posts_per_page');
    $post_type = POST_TYPE;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $sticky = get_option('sticky_posts');
    query_posts(array(
        'post__in' => $sticky,
        'ignore_sticky_posts' => 1,
        'post_type' => $post_type,
        'showposts' => $limit,
        'paged' => $paged
    ));
    $wp_query->is_archive = true;
    $wp_query->is_home = false;
    ?>
<?php if (have_posts()) : ?>
    <?php
    while (have_posts()): the_post();
        global $post;
        //$featured_post_list = get_post_meta($post->ID, 'geocraft_f_checkbox2', true);
        $featured_class = '';
        $is_pro = get_post_meta($post->ID, 'geocraft_listing_type', true);
        if ($is_pro == 'pro') {
            $featured_class = 'featured';
        }
        $img_meta = get_post_meta($post->ID, 'geocraft_meta_image1', true);
        $imgfind = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        $is_featured = get_post_meta($post->ID, 'geocraft_f_checkbox1', true);
        ?>
        <!--Start Featured Post-->
        <div class="featured_post">
            <div class="<?php echo $featured_class; ?>">
                <!--Start Featured thumb-->
                <div class="featured_thumb">
                    <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                        <?php inkthemes_get_thumbnail(128, 108, '', $img_meta); ?>                    
                    <?php } else { ?>
                        <?php inkthemes_get_image(128, 108, '', $img_meta); ?> 
                        <?php
                    }
                    ?>
                    <?php if ($is_pro == 'pro') { ?>
                        <img class="ribbon" src="<?php echo get_template_directory_uri(); ?>/images/ribbon.png"/>                   
                    <?php } ?>
                    <ul class="star_rating">
                        <?php
                        global $post;
                        echo geocraft_get_post_rating_star($post->ID);
                        ?>
                    </ul>
                    <span class="review_desc"><?php comments_popup_link(N_RV, _RV, '% ' . REVIEW); ?></span> </div>
                <!--End Featured thumb-->
                <div class="f_post_content">
                    <h4 style="margin-bottom: 3px !important;" class="f_post_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
                    <?php if (get_post_meta($post->ID, 'geo_address', true)): ?>
                        <p class="f_post_meta"><img src="<?php echo TEMPLATEURL . '/images/location-icon.png'; ?>"/>&nbsp;&nbsp;<?php echo get_post_meta($post->ID, 'geo_address', true); ?></p>                               
                    <?php endif; ?>
                    <?php the_excerpt(); ?>
        <!--                                <a class="read-more" href="<?php the_permalink() ?>"><?php _e('Read More', THEME_SLUG); ?></a>-->
                </div>
            </div>
        </div>
        <!--End Featured Post-->
        <?php
    endwhile;
    endif;
    wp_reset_query();
    $limit = get_option('posts_per_page');
    $post_type = POST_TYPE;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    query_posts(array(
        'post__not_in' => $sticky,
        'ignore_sticky_posts' => 1,
        'post_type' => $post_type,
        'showposts' => $limit,
        'paged' => $paged
    ));
    if (have_posts()) :
    while (have_posts()): the_post();
        global $post;
        //$featured_post_list = get_post_meta($post->ID, 'geocraft_f_checkbox2', true);
        $featured_class = '';
        $is_pro = get_post_meta($post->ID, 'geocraft_listing_type', true);
        if ($is_pro == 'pro') {
            $featured_class = 'featured';
        }
        $img_meta = get_post_meta($post->ID, 'geocraft_meta_image1', true);
        $imgfind = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        $is_featured = get_post_meta($post->ID, 'geocraft_f_checkbox1', true);
        ?>
        <!--Start Featured Post-->
        <div class="featured_post">
            <div class="<?php echo $featured_class; ?>">
                <!--Start Featured thumb-->
                <div class="featured_thumb">
                    <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                        <?php inkthemes_get_thumbnail(128, 108, '', $img_meta); ?>                    
                    <?php } else { ?>
                        <?php inkthemes_get_image(128, 108, '', $img_meta); ?> 
                        <?php
                    }
                    ?>
                    <?php if ($is_pro == 'pro') { ?>
                        <img class="ribbon" src="<?php echo get_template_directory_uri(); ?>/images/ribbon.png"/>                   
                    <?php } ?>
                    <ul class="star_rating">
                        <?php
                        global $post;
                        echo geocraft_get_post_rating_star($post->ID);
                        ?>
                    </ul>
                    <span class="review_desc"><?php comments_popup_link(N_RV, _RV, '% ' . REVIEW); ?></span> </div>
                <!--End Featured thumb-->
                <div class="f_post_content">
                    <h4 style="margin-bottom: 3px !important;" class="f_post_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
                    <?php if (get_post_meta($post->ID, 'geo_address', true)): ?>
                        <p class="f_post_meta"><img src="<?php echo TEMPLATEURL . '/images/location-icon.png'; ?>"/>&nbsp;&nbsp;<?php echo get_post_meta($post->ID, 'geo_address', true); ?></p>                               
                    <?php endif; ?>
                    <?php the_excerpt(); ?>
        <!--                                <a class="read-more" href="<?php the_permalink() ?>"><?php _e('Read More', THEME_SLUG); ?></a>-->
                </div>
            </div>
        </div>
        <!--End Featured Post-->
        <?php
    endwhile;
    inkthemes_pagination();
    wp_reset_query();
    ?>
<?php else: ?>
    <div class="featured_post">
        <p class="place"><?php echo NO_LST_FND; ?></p>
    </div>
<?php
endif;
wp_reset_query();
?>