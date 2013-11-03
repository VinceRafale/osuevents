 <?php
/**
 * Template Name: Template: Left Sidebar
 *
 */
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="content_wrapper left_sidebar">
     <div class="grid_8 alpha">
        <?php get_sidebar(); ?>
    </div>
    <div class="grid_16 omega">
        <div class="content">
            <h1 class="featured_title"><?php the_title(); ?></h1>
            <?php while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; // end of the loop. ?>
        </div>
    </div>   
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>
