<?php
/**
 * The template for displaying Category pages.
 *
 */
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="content_wrapper">

    <!--Start Info bar-->
    <div class="info_bar"> <span class="info_desc"><span class="info_detail"><?php if (geocraft_get_option('home_feature_txt') != '') echo geocraft_get_option('home_feature_txt'); ?><?php printf(__('&nbsp;IN' . ' %s', THEME_SLUG), '' . strtoupper(single_cat_title('', false)) . ''); ?></span></span> </div>
    <!--End Info bar-->
    <script type="text/javascript" src="<?php echo TEMPLATEURL ?>/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo TEMPLATEURL ?>/js/jquery.jcarousel.min.js"></script>

    <?php
    $limit = geocraft_get_option('slider_limit');
    $post_type = POST_TYPE;
    //$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $sticky = get_option('sticky_posts');
    global $post;
    //$cat = $_REQUEST['listcat'];
	$cat = single_cat_title("", false);
    query_posts(array(
            'post_type' => POST_TYPE,
            'showposts' => $limit,
                // 'paged' => $paged, 
            'listcat' => $cat,
            'meta_query' => array(
                array(
                    'key' => 'geocraft_f_checkbox2',
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
                    wrap: 'circular',
                    scroll: 1,
                    auto: 1,                
                    initCallback: mycarousel_initCallback
                });
            });
        </script>
        <div id="wrap">
            <ul id="mycarousel" class="jcarousel-skin-tango">
                <?php
                while (have_posts()) : the_post();
                    $is_featured = get_post_meta($post->ID, 'geocraft_f_checkbox2', true);
                    $address = get_post_meta($post->ID, 'geo_address', true);
                    $has_image = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
                    $img_meta = get_post_meta($post->ID, 'geocraft_meta_image1', true);
                    if ($is_featured):
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
                                    <?php echo $address; ?></section>                          
                            </div>
                        </li>
                        <?php
                        $count = $count + 1;
                    endif;
                endwhile;
                ?>       
            </ul>
        </div>
        <?php
    else:
        ?>
        <p class="place"><?php echo NO_LST_FND; ?></p>
    <?php
    endif;
    wp_reset_query();
    ?>
    <div class="info_bar">&nbsp;<span class="info_desc"></span> </div>
    <div class="clear"></div>

    <div class="grid_16 alpha">
        <div class="featured_content">
            <h1 class="featured_title"><?php printf(__(ALL_LISTING . ' %s', THEME_SLUG), '' . single_cat_title('', false) . ''); ?></h1>
            <?php
            if (have_posts()) :
                $category_description = category_description();
                if (!empty($category_description))
                    echo '' . $category_description . '';
                /* Run the loop for the category page to output the posts.
                 * If you want to overload this in a child theme then include a file
                 * called loop-category.php and that will be used instead.
                 */
                ?>
                <?php get_template_part('loop', 'category'); ?>
                <div class="clear"></div>
                <?php inkthemes_pagination(); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="grid_8 omega">
        <?php get_sidebar('listing'); ?>
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>