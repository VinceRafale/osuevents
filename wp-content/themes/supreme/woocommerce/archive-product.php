<?php get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // rainbow_before_content ?>
	
	<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>
	
	<div id="content">

		<?php do_atomic( 'open_content' ); // rainbow_open_content ?>

		<div class="hfeed">
		
			<?php if ( !is_search() ) : ?>

				<div class="loop-meta">
					<h1 class="loop-title"><?php _e( 'All Products', 'woocommerce' ); ?></h1>
				</div><!-- .loop-meta -->
			
			<?php else : ?>

				<?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template. ?>

			<?php endif; ?>
			
			<?php get_sidebar( 'before-content' ); // Loads the sidebar-before-content.php template. ?>

			<?php do_action('woocommerce_before_main_content'); ?>
			
			<div class="hentry page">
				<div class="entry-content">
				
				<?php
					
					if ( !empty( $shop_page_content  ) ) echo apply_filters( 'the_content', $shop_page_content );
					
					woocommerce_get_template_part( 'loop', 'shop' );
					
				?>
			
				</div><!-- .entry-content -->
			</div><!-- .hentry -->

			<?php do_action('woocommerce_after_main_content'); ?>
			
			<?php get_sidebar( 'after-content' ); // Loads the sidebar-after-content.php template. ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // rainbow_close_content ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // rainbow_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>