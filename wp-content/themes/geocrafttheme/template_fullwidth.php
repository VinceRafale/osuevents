<?php
/*
  Template Name: Template: Fullwidth
 */
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="content_wrapper">
    <div class="fullwidth">
        <?php if (have_posts()) while (have_posts()) : the_post(); ?>
                <h1>
                    <?php the_title(); ?>
                </h1>
                <?php the_content(); ?>
            <?php endwhile; ?>        
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>
