<?php
/**
 * Loop Template
 *
 * Displays the entire post content.
 *
 * @package supreme
 * @subpackage Template
 */
?>

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
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'supreme' ); ?></p>
					</div>
					
				</div><!-- .hentry .error -->

		<?php endif; ?>