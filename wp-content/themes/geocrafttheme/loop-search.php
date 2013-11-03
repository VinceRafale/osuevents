<?php
$search_string = preg_replace('/^[0-9a-zA-Z-]/', '', strtolower($_REQUEST['s']));
$search_loc = preg_replace('/^[0-9a-zA-Z-]/', '', strtolower($_REQUEST['location']));
$querys = custom_search($search_string, $search_loc);
$querys2 = custom_search_by_freelisting($search_string, $search_loc);
if ($querys || $querys2) {
    if ($search_string != '' && $search_loc != '') {
        /**
         * Search query for pro listing 
         */
        foreach ($querys as $query) {
            $featured_post_list = get_post_meta($query->ID, 'geocraft_f_checkbox2', true);
            $featured_class = '';
            $featured_class = '';
            $is_pro = get_post_meta($query->ID, 'geocraft_listing_type', true);
            if ($is_pro == 'pro') {
                $featured_class = 'featured';

                $img_meta = get_post_meta($query->ID, 'geocraft_meta_image1', true);
                $imgfind = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $query->post_content, $matches);
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
                            <?php if ($is_pro != 'free') { ?>
                                <img class="ribbon" src="<?php echo get_template_directory_uri(); ?>/images/ribbon.png"/>                   
                            <?php } ?>
                            <ul class="star_rating">
                                <?php
                                global $post;
                                echo geocraft_get_post_rating_star($query->ID);
                                ?>
                            </ul>
                            <span class="review_desc"><?php comments_popup_link(N_RV, _RV, '% ' . REVIEW); ?></span> </div>
                        <!--End Featured thumb-->
                        <div class="f_post_content">
                            <h4 style="margin-bottom: 3px !important;" class="f_post_title"><a href="<?php echo $query->guid ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo $query->post_title; ?></a></h4>
                            <?php if (get_post_meta($query->ID, 'geo_address', true)): ?>
                                <p class="f_post_meta"><img src="<?php echo TEMPLATEURL . '/images/location-icon.png'; ?>"/>&nbsp;&nbsp;<?php echo get_post_meta($query->ID, 'geo_address', true); ?></p>                               
                            <?php endif; ?>
                            <p><?php
                $excerpt = preg_replace("/<img[^>]+\>/i", "", $query->post_content);
                $excerpt = substr($excerpt, 0, 120);
                printf("%s ", $excerpt);
                            ?><a href="<?php the_permalink() ?>">[...]<?php echo RD_MORE; ?></a></p>
                        </div>
                    </div>
                </div>
                <!--End Featured Post-->
                <?php
            }
        }

        foreach ($querys as $query) {
            $featured_post_list = get_post_meta($query->ID, 'geocraft_f_checkbox2', true);
            $featured_class = '';
            $featured_class = '';
            $is_pro = get_post_meta($query->ID, 'geocraft_listing_type', true);
            if ($is_pro == 'free') {

                $img_meta = get_post_meta($query->ID, 'geocraft_meta_image1', true);
                $imgfind = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $query->post_content, $matches);
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
                            <?php if ($is_pro != 'free') { ?>
                                <img class="ribbon" src="<?php echo get_template_directory_uri(); ?>/images/ribbon.png"/>                   
                            <?php } ?>
                            <ul class="star_rating">
                                <?php
                                global $post;
                                echo geocraft_get_post_rating_star($query->ID);
                                ?>
                            </ul>
                            <span class="review_desc"><?php comments_popup_link(N_RV, _RV, '% ' . REVIEW); ?></span> </div>
                        <!--End Featured thumb-->
                        <div class="f_post_content">
                            <h4 style="margin-bottom: 3px !important;" class="f_post_title"><a href="<?php echo $query->guid ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo $query->post_title; ?></a></h4>
                            <?php if (get_post_meta($query->ID, 'geo_address', true)): ?>
                                <p class="f_post_meta"><img src="<?php echo TEMPLATEURL . '/images/location-icon.png'; ?>"/>&nbsp;&nbsp;<?php echo get_post_meta($query->ID, 'geo_address', true); ?></p>                               
                            <?php endif; ?>
                            <p><?php
                $excerpt = preg_replace("/<img[^>]+\>/i", "", $query->post_content);
                $excerpt = substr($excerpt, 0, 120);
                printf("%s ", $excerpt);
                            ?><a href="<?php the_permalink() ?>">[...]<?php echo RD_MORE; ?></a></p>
                        </div>
                    </div>
                </div>
                <!--End Featured Post-->
                <?php
            }
        }
    }
    elseif ($search_string == '' && $search_loc != '') {
        $limit = get_option('posts_per_page');
        $post_type = POST_TYPE;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $sticky = get_option('sticky_posts');
        query_posts(array(
            'post_type' => POST_TYPE,
            'post_status' => 'publish',
            'showposts' => $limit,
            'paged' => $paged,
            'meta_query' => array(
                array(
                    'key' => 'geo_address',
                    'value' => $search_loc,
                    'compare' => 'LIKE',
                )
            )
        ));
        if (have_posts()) :
            while (have_posts()): the_post();
                global $post;
                $is_pro = get_post_meta($post->ID, 'geocraft_listing_type', true);
                if ($is_pro == 'pro') {
                    $featured_class = 'featured';
                }
                $img_meta = get_post_meta($post->ID, 'geocraft_meta_image1', true);
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
                            <?php if ($is_pro != 'free') { ?>
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
                            <h4 style="margin-bottom: 3px !important;" class="f_post_title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo the_title(); ?></a></h4>
                            <?php if (get_post_meta($post->ID, 'geo_address', true)): ?>
                                <p class="f_post_meta"><img src="<?php echo TEMPLATEURL . '/images/location-icon.png'; ?>"/>&nbsp;&nbsp;<?php echo get_post_meta($post->ID, 'geo_address', true); ?></p>                               
                            <?php endif; ?>
                            <p><?php
                the_excerpt();
                            ?>
                        </div>
                    </div>
                </div>
                <!--End Featured Post-->
                <?php
            endwhile;
            inkthemes_pagination();
        endif;
        wp_reset_query();
    }
}
else {
    ?>
    <article id="post-0" class="post no-results not-found">
        <header class="entry-header">
            <h1 class="entry-title">
                <?php echo NTH_FND; ?>
            </h1>
        </header>
        <!-- .entry-header -->
        <div class="entry-content">
            <p>
                <?php echo SRY_NT_FND; ?>
            </p>
            <?php get_search_form(); ?>                        
        </div>
        <!-- .entry-content -->
    </article>
    <?php
}
?>