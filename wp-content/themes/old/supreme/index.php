<?php
/**
 * Index Template
 *
 * This is the default template.  It is used when a more specific template can't be found to display
 * posts.  It is unlikely that this template will ever be used, but there may be rare cases.
 *
 * @package supreme
 * @subpackage Template
 */

get_header(); // Loads the header.php template. ?>

<?php do_atomic( 'before_content' ); // supreme_before_content ?>

<div id="content">

	<?php do_atomic( 'open_content' ); // supreme_open_content ?>
	
	<div class="hfeed">
	
	<?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template. ?>
	
	<?php get_sidebar( 'before-content' ); // Loads the sidebar-before-content.php template. ?>
	
		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>
			
			<?php do_atomic( 'before_entry' ); // supreme_before_entry ?>
			
					<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

						<?php do_atomic( 'open_entry' ); // supreme_open_entry ?>

						<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
						
						<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __('Published by [entry-author] on [entry-published] [entry-comments-link zero="Respond" one="%1$s" more="%1$s"] [entry-edit-link] [entry-permalink]', 'supreme' ) . '</div>'); ?>

						<?php get_sidebar( 'entry' ); // Loads the sidebar-entry.php template. ?>
						
						<div class="entry-content">
							
							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'supreme' ) ); ?>
							<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'supreme' ), 'after' => '</p>' ) ); ?>

						</div><!-- .entry-content -->

						<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( 'Filed under: [entry-terms taxonomy="category"] [entry-terms taxonomy="post_tag" before="and Tagged: "]', 'supreme' ) . '</div>' ); ?>

						<?php do_atomic( 'close_entry' ); // supreme_close_entry ?>

					</div><!-- .hentry -->
			
			<?php do_atomic( 'after_entry' ); // supreme_after_entry ?>
			
				<?php endwhile; ?>

			<?php else : ?>
			
				<div class="<?php hybrid_entry_class(); ?>">

					<h2 class="entry-title"><?php _e( 'No Entries', 'supreme' ); ?></h2>
				
					<div class="entry-content">
						<p><?php _e( 'Apologies, but no entries were found.', 'supreme' ); ?></p>
					</div>
				
				</div><!-- .hentry .error -->

		<?php endif; ?>
		
	<?php get_sidebar( 'after-content' ); // Loads the sidebar-after-content.php template. ?>
		
	</div><!-- .hfeed -->
	
	<?php do_atomic( 'close_content' ); // supreme_close_content ?>
	
	<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>

</div><!-- #content -->

<?php do_atomic( 'after_content' ); // supreme_after_content ?>

<?php get_footer(); // Loads the header.php template. ?>