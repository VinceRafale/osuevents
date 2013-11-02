<?php get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // supreme_before_content ?>

	<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>
	
	<div id="content">

		<?php do_atomic( 'open_content' ); // supreme_open_content ?>

		<div class="hfeed">
		
			<?php get_sidebar( 'before-content' ); // Loads the sidebar-before-content.php template. ?>
	  
			<?php do_action('woocommerce_before_main_content'); ?>
			
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			
					<?php do_action( 'woocommerce_before_single_product' ); ?>
				
					<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

						<div class="product-header">
					
							<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
						
							<div class="product-summary">
								<?php do_action( 'woocommerce_single_product_summary' ); ?>
								<?php echo apply_atomic_shortcode( 'entry_edit_link', '[entry-edit-link before="<p>" after="</p>"]' ); ?>
							</div><!-- .product-summary -->
						
						</div><!-- .product-header -->
						
						<div class="entry-content product-content">
						
							<?php do_action( 'woocommerce_after_single_product_summary' ); ?>
							
						</div><!-- .entry-content -->
						
					</div><!-- .hentry -->
					
					<?php do_action( 'woocommerce_after_single_product' ); ?>
			
			<?php endwhile; ?>

			<?php do_action('woocommerce_after_main_content'); ?>
			
			<?php do_atomic( 'after_entry' ); // supreme_after_entry ?>
		
			<?php get_sidebar( 'after-singular' ); // Loads the sidebar-after-content.php template. ?>
			
			<?php do_atomic( 'after_singular' ); // supreme_after_singular ?>
		
		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // supreme_close_content ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // supreme_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>