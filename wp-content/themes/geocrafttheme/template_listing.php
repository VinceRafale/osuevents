<?php
/**
 * Template Name: Template: Listing
 *
 */
?>
<?php get_header(); ?>
<!--Start Content Wrapper-->
<div class="content_wrapper">
    <div class="grid_16 alpha">
        <div class="featured_content">  
            <?php get_template_part('loop', 'listing'); ?>
        </div>
    </div>
    <div class="grid_8 omega">
        <?php get_sidebar('listing'); ?>
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>