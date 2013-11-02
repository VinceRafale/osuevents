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
	<div id="search_listing" class="event_search">
		<?php if ( have_posts() ) : 

		while ( have_posts() ) : the_post(); ?>
			
			<?php do_atomic( 'before_entry' ); // supreme_before_entry ?>
			
					<div id="post-<?php the_ID(); ?>" class="post event">
						<?php 
							if ( current_theme_supports( 'get-the-image' ) ) : 	
								get_the_image(array('post_id'=> get_the_ID(),'size'=>'thumbnail','image_class'=>'img post_img listimg','default_image'=>get_stylesheet_directory_uri()."/images/img_not_available.png"));					
							endif; ?>
						<div class="entry">
							<?php $address = get_post_meta($post->ID,'address',true);
								if(!$address){ 	$address='-'; }
								echo apply_atomic_shortcode( 'byline', '<span class="date">' . __('[entry-published]', 'supreme' ) . '</span>'); 
								?>
								<h2 class="date">
									<?php echo $date = date("d",strtotime(get_post_meta($post->ID,'st_date',true)));?>
                                    <span><?php echo $date = date("M",strtotime(get_post_meta($post->ID,'st_date',true)));?></span>
								</h2>
								<h2 class="title">
									<a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php the_title(); ?></a>									
								</h2> 			
                                <span class="address"><?php if($address){echo $address; }?></span>
                                <?php $length = custom_excerpt_length();
									echo print_excerpt($length); ?>
                                <?php								
									$st_date = date('M d, Y',strtotime(get_post_meta($post->ID,'st_date',true)));
									$end_date = date('M d, Y',strtotime(get_post_meta($post->ID,'end_date',true)));
									if($end_date && $st_date && strtotime(get_post_meta($post->ID,'st_date',true)) < strtotime(get_post_meta($post->ID,'end_date',true))){	 /* if st date and end date both are set */
										$event_date = date('M d, Y',strtotime(get_post_meta($post->ID,'st_date',true))).' to '.date('M d, Y',strtotime(get_post_meta($post->ID,'end_date',true)));
									}else if(($st_date && !$end_date) || (strtotime(get_post_meta($post->ID,'st_date',true)) == strtotime(get_post_meta($post->ID,'end_date',true)))){ /* if only st date is set or st date is less the or equal to end date*/
										$event_date = date('M d, Y',strtotime(get_post_meta($post->ID,'st_date',true)));				
									}else{
										$event_date = date('M d, Y',strtotime(get_post_meta($post->ID,'st_date',true))).' to '.date('M d, Y',strtotime(get_post_meta($post->ID,'end_date',true)));
									}
									?>
										
									<p class="date"> <span><?php _e('Date',T_DOMAIN);?> : </span> <?php echo $event_date; ?><br> <span><?php _e('Timing',T_DOMAIN);?> : </span> <?php echo get_post_meta($post->ID,'st_time',true).' to '.get_post_meta($post->ID,'end_time',true);?> </p>		
									<?php		
									the_taxonomies(array('before'=>'<p class="bottom_line"><span class="i_category">','sep'=>'</span>&nbsp;&nbsp;<span class="i_tag">','after'=>'</span></p>'));		
								?>
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