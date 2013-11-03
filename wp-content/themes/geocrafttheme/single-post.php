<?php
/**
 * Single post page for displaying detailed about
 * the selected post. 
 */
get_header();
?>
<!--Start Content Wrapper-->
<div class="content_wrapper">
    <div class="grid_16 alpha">
        <div class="featured_content">
            <?php
            if (have_posts()) :
                while (have_posts()): the_post();
                    ?>
                    <!--Start Featured Post-->
                    <div <?php post_class('single'); ?> id="post-<?php the_ID(); ?>">           
                        <div class="f_post_content post_content">
                            <h1 class="f_post_title"><?php the_title(); ?></h1>
                            <div class="post_meta">                             
                                <ul class="meta-nav">
                                    <li class="author"><?php echo 'By '; ?><?php printf('%s', the_author_posts_link()); ?></li>
                                    <li class="date"><?php the_time('M-j-Y') ?></li>
                                    <li class="category"><?php the_category(', '); ?></li>
                                    <li class="comment"><?php comments_popup_link('0 Comments.', '1 Comment.', '% Comments.'); ?></li>
                                </ul>
                            </div>
                            <?php the_content(); ?>
                        </div>
                    </div>
                    <!--End Featured Post-->
                    <div class="line"></div>
                    <h2>Comments</h2>
                    <div class="post-comments">
                        <!--Start Comment box-->
                        <?php comments_template('/blog-comments.php', true); ?>
                        <!--End Comment box-->
                    </div>
                    <?php
                endwhile;
            else:
                ?>
                <div class="featured_post featured">
                    <p class="place"><?php echo NO_POST_FOUND; ?></p>
                </div>
            <?php
            endif;
            ?>
        </div>
         <nav id="nav-single"> <span class="nav-previous">
        <?php previous_post_link('%link', __('<span class="meta-nav">&larr;</span> Previous Post ', THEME_SLUG)); ?>
        </span> <span class="nav-next">
        <?php next_post_link('%link', __('Next Post <span class="meta-nav">&rarr;</span>', THEME_SLUG)); ?>
        </span> </nav>
    </div>
    <div class="grid_8 omega">
        <?php get_sidebar('blog'); ?>
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>
