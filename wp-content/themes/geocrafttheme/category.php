<?php
/**
 * The template for displaying Category pages.
 *
 */
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="content_wrapper">
    <div class="grid_16 alpha">
        <div class="featured_content">
            <h1 class="featured_title"><?php printf(CAT_ARC . __(' %s', THEME_SLUG), '' . single_cat_title('', false) . ''); ?></h1>
            <?php
            $category_description = category_description();
            if (!empty($category_description))
                echo '' . $category_description . '';
            /* Run the loop for the category page to output the posts.
             * If you want to overload this in a child theme then include a file
             * called loop-category.php and that will be used instead.
             */
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
        </div>
    </div>
    <div class="grid_8 omega">
        <?php
        global $post;
        $post_type = $post->post_type;
        if ($post_type == 'post'):
            get_sidebar('blog');
        elseif ($post_type == POST_TYPE):
            get_sidebar(POST_TYPE);
        elseif ($post_type == 'page'):
            get_sidebar();
        endif;
        ?>
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>