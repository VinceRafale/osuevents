<?php
/**
 * Template Name: Widgets
 *
 * The Widgets template is a page template that is completely widgetized. It houses the 
 * 'Widgets Template' widget area. Customizations to this page should be done through widgets.
 *
 * @package supreme
 * @subpackage Template
 */

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // supreme_before_content ?>
	
	<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>

	<div id="content" class="multiple">

		<?php do_atomic( 'open_content' ); // supreme_open_content ?>

		<div class="hfeed">
		
			<div id="widgets-template" class="sidebar sidebar-alt">
		
				<?php dynamic_sidebar( 'widgets-template' ); ?>
				
			</div><!-- #widgets-template .sidebar -->

			<?php wp_reset_query(); ?>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<?php do_atomic( 'after_singular' ); // supreme_after_singular ?>

			<?php endwhile; ?>

			<?php else: ?>

			<?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>

			<?php endif; ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // supreme_close_content ?>
		
	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // supreme_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>