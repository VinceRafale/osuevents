<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 */
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="content_wrapper">
    <div class="grid_16 alpha">
        <div class="content">
            <h1 class="featured_title"><?php the_title(); ?></h1>
            <?php while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; // end of the loop. ?>
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
