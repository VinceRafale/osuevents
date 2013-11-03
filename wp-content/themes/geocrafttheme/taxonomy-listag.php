<?php
/**
 * The template used to display Place Tag Archive pages
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
      <?php if ( have_posts() ) : ?> 
      <h1><?php printf( __( TAG_ARC.' %s',THEME_SLUG ), '' . single_cat_title( '', false ) . '' );?></h1>
      <?php get_template_part( 'loop', 'index' ); ?>
      <?php /* Display navigation to next/previous pages when applicable */ ?>
      <?php if (  $wp_query->max_num_pages > 1 ) : ?>
      <?php inkthemes_pagination(); ?>
      <?php endif; ?>
      <?php endif; ?>
        </div>
    </div>
    <div class="grid_8 omega">
        <?php get_sidebar(); ?>
    </div>
</div>
<!--End Content Wrapper-->
<?php get_footer(); ?>