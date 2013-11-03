<?php
/**
 * The main front page file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Geocraft
 *
 */
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="content_wrapper">
    <!--Start Info bar-->
    <div class="info_bar"> <span class="info_desc"><span class="info_detail">&nbsp;&nbsp;<?php if (geocraft_get_option('home_feature_txt') != '') echo geocraft_get_option('home_feature_txt'); ?>&nbsp;&nbsp;</span></span> </div>
    <!--End Info bar-->
    <script type="text/javascript" src="<?php echo TEMPLATEURL ?>/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo TEMPLATEURL ?>/js/jquery.jcarousel.min.js"></script>
    <div id="wrap">
        <?php 
        $count = 1;
        $class = '';
        $check = false;
        $limit = geocraft_get_option('slider_limit');
        query_posts(array(
            'post_type' => POST_TYPE,
            'showposts' => $limit,
            'meta_query' => array(
                array(
                    'key' => 'geocraft_f_checkbox1',
                    'value' => 'on',
                    'compare' => 'NOT NULL',
                ),
                 array(
                    'key' => 'geocraft_listing_type',
                    'value' => 'pro',
                    'compare' => 'NOT NULL',
                )
            )
        ));
        if (have_posts()) :
            ?>
            <script type="text/javascript" language="javascript">
                function mycarousel_initCallback(carousel)
                {
                    // Disable autoscrolling if the user clicks the prev or next button.
                    carousel.buttonNext.bind('click', function() {
                        carousel.startAuto(0);
                    });

                    carousel.buttonPrev.bind('click', function() {
                        carousel.startAuto(0);
                    });

                    // Pause autoscrolling if the user moves with the cursor over the clip.
                    carousel.clip.hover(function() {
                        carousel.stopAuto();
                    }, function() {
                        carousel.startAuto();
                    });
                };
                jQuery(document).ready(function() {
                    jQuery('#mycarousel').jcarousel({
                        wrap: 'both',
                        scroll: 1,
                        auto: 1,
                        initCallback: mycarousel_initCallback
                    });
                });
            </script>
            <ul id="mycarousel" class="jcarousel-skin-tango">
                <?php
                while (have_posts()) :the_post();
                    global $post;
                    $is_featured = get_post_meta($post->ID, 'geocraft_f_checkbox1', true);
                    $address = get_post_meta($post->ID, 'geo_address', true);
                    $has_image = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
                    $img_meta = get_post_meta($post->ID, 'geocraft_meta_image1', true);
                        ?>
                        <li class="list">
                            <div class="slider-item">
                                <div class="post-thumb">
                                    <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                        <?php inkthemes_get_thumbnail(195, 140, 'fpic', $img_meta); ?>
                                    <?php } else { ?>
                                        <?php inkthemes_get_image(195, 140, 'fpic', $img_meta); ?>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <section>
                                    <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                                    <ul class="star_rating">
                                        <?php
                                        global $post;
                                        echo geocraft_get_post_rating_star($post->ID);
                                        ?>
                                    </ul>
                                    <?php echo $address; ?>
                                </section>
                            </div>
                        </li>
                        <?php
                        $count = $count + 1;               
                endwhile;
                ?>
            </ul>
        <?php endif;
        wp_reset_query(); ?>
    </div>
    <div class="info_bar">&nbsp;<span class="info_desc"></span> </div>
    <div class="clear"></div>

    <div class="grid_16 alpha">
        <div class="featured_content">
            <h1 class="featured_title"><?php if (geocraft_get_option('home_recent_txt') != '') echo geocraft_get_option('home_recent_txt'); ?></h1>
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
                                    <p class="f_post_meta"><img src="<?php echo TEMPLATEURL . '/images/location-icon.png'; ?>"/>&nbsp;<?php echo get_post_meta($post->ID, 'geo_address', true); ?></p>
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
                /* Get all Sticky Posts */
                $sticky = get_option('sticky_posts');
                /* Sort Sticky Posts, newest at the top */
                rsort($sticky);
                /* Get top 5 Sticky Posts */
                $sticky = array_slice($sticky, 0, 5);
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
                                    <p class="f_post_meta"><img src="<?php echo TEMPLATEURL . '/images/location-icon.png'; ?>"/>&nbsp;<?php echo get_post_meta($post->ID, 'geo_address', true); ?></p>
                                <?php endif; ?>
        <?php the_excerpt(); ?>
        <!--                                <a class="read-more" href="<?php the_permalink() ?>"><?php _e('Read More', THEME_SLUG); ?></a>-->
                            </div>
                        </div>
                    </div>
                    <!--End Featured Post-->
                    <?php
                endwhile;
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
        </div>
    </div>
    <div class="grid_8 omega">
<?php get_sidebar('home'); ?>
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>