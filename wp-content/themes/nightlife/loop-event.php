<?php
/**
 * Loop Template
 *
 * Displays the entire post content.
 *
 * @package supreme
 * @subpackage Template
 */
templatic_display_views(); /* Display view button */
?>
	<div id="loop_listing" class="eventlist indexlist">
		<?php if ( have_posts() ) : 

		while ( have_posts() ) : the_post(); ?>
			
			<?php do_atomic( 'before_entry' ); // supreme_before_entry ?>
				<?php $featured=get_post_meta(get_the_ID(),'featured_h',true);
					$calss_featured=$featured=='h'?'featured_h':'';
				?>
            
					<div id="post-<?php the_ID(); ?>" class="post event <?php echo $calss_featured;?>">
                    <?php
					if($calss_featured=$featured){echo '<span class="featured"></span>';}
					?>
						<?php 
							if ( current_theme_supports( 'get-the-image' ) ) : 	
								get_the_image(array('post_id'=> get_the_ID(),'size'=>'event-home-thumb','image_class'=>'img','default_image'=>get_stylesheet_directory_uri()."/images/img_not_available.png"));					
							endif; ?>
						<div class="content">
							<?php $address = get_post_meta($post->ID,'address',true);
								if(!$address){ 	$address='-'; }
								echo apply_atomic_shortcode( 'byline', '<span class="date">' . __('[entry-published]', 'supreme' ) . '</span>'); 
								?>
								<span class="date">
									<?php echo $date = date("d M",strtotime(get_post_meta($post->ID,'st_date',true)));?>
								</span>
								<span class="title">
									<a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php the_title(); ?></a>
									<?php if($address)
									echo "<b>".$address."</b>"; ?>
								</span> 											
						</div><!-- .entry-content -->

						
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

		<?php endif;  wp_reset_postdata();
?>
	</div>