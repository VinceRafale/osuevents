<?php
/**
 * Loop Excerpt Template
 *
 * Displays the excerpts of posts.
 *
 * @package supreme
 * @subpackage Template
 */
?>

		<ul class="loop-entries">
		
		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>
			
			<?php do_atomic( 'before_entry' ); // supreme_before_entry ?>

			<li id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">
			
				<?php do_atomic( 'open_entry' ); // supreme_open_entry ?>
				
				<?php get_template_part( 'content', get_post_format() ); ?>
					
				<?php do_atomic( 'close_entry' ); // supreme_close_entry ?>

			</li><!-- .hentry -->
			
			<?php do_atomic( 'after_entry' ); // supreme_after_entry ?>
			
				<?php endwhile; ?>

			<?php else : ?>

				<?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>

		<?php endif; ?>

		</ul><!-- .loop-content -->