	<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>
	<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( 'Published by [entry-author] on [entry-published] [entry-permalink]', 'supreme' ) . '</div>'); ?>

	<div class="entry-summary"><?php the_content(); ?></div><!-- .entry-summary -->