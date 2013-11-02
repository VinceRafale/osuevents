<?php

/**
 * bbPress User Profile Edit
 *
 * @package supreme
 * @subpackage Template
 */

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // supreme_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // supreme_open_content ?>

		<div class="hfeed">
		
			<?php get_sidebar( 'before-content' ); // Loads the sidebar-before-content.php template. ?>

			<div id="bbp-user-<?php bbp_current_user_id(); ?>" class="bbp-single-user">

				<?php bbp_get_template_part( 'bbpress/content', 'single-user-edit'   ); ?>
						
			</div><!-- .bbp-single-user -->
			
			<?php get_sidebar( 'after-content' ); // Loads the sidebar-after-content.php template. ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // supreme_close_content ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // supreme_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>
