<?php
/**
 * The template used to display Tag Archive pages
 *
 * @package WordPress
 * 
 */
get_header();
?>
<!--Start Content Wrapper-->
<div class="content_wrapper">
    <div class="grid_16 alpha">
        <div class="featured_content">
            <?php if (have_posts()) : ?> 
                <h1><?php printf(__(TAG_ARC . ' %s', THEME_SLUG), '' . single_cat_title('', false) . ''); ?></h1>
                <?php
                if (have_posts()) :
                    while (have_posts()): the_post();
                        ?>
                        <!--Start Featured Post-->
                        <div <?php post_class('featured_post post'); ?> id="post-<?php the_ID(); ?>">
                            <!--Start Featured thumb-->
                            <!--End Featured thumb-->
                            <div class="f_post_content">
                                <h1 class="f_post_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                                <div class="post_meta">
                                    <ul class="meta-nav">
                                        <li class="author"><?php echo 'By '; ?><?php printf('%s', the_author_posts_link()); ?></li>
                                        <li class="date"><?php the_time('M-j-Y') ?></li>
                                        <li class="category"><?php the_category(', '); ?></li>
                                        <li class="comment"><?php comments_popup_link('0 Comments.', '1 Comment.', '% Comments.'); ?></li>
                                    </ul>
                                </div>
                                <div class="featured_thumb blog">
                                    <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                        <?php inkthemes_get_thumbnail(205, 143); ?>
                                    <?php } else { ?>
                                        <?php inkthemes_get_image(205, 143); ?> 
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                        <!--End Featured Post-->
                        <?php
                    endwhile;
                    inkthemes_pagination();
                    wp_reset_query();
                else:
                    ?>
                    <div class="featured_post featured">
                        <p class="place"><?php echo NO_POST_FOUND; ?></p>
                    </div>
                <?php
                endif;
                ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="grid_8 omega">
        <?php get_sidebar(); ?>
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>