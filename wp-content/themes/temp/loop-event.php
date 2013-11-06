<?php
/**
 * Loop Template
 *
 * Displays the entire post content.
 *
 * @package supreme
 * @subpackage Template
 */
$tmpdata = get_option('templatic_settings');
$home_listing = $tmpdata['home_listing_type_value'];
if(is_array($home_listing) && in_array('event',$home_listing)){ /* show only when event post type is selected */
templatic_display_views(); /* Display view button */
}
?>
	<div id="loop_listing" class="eventlist indexlist">
		<?php if ( have_posts() ) : 

		while ( have_posts() ) : the_post(); ?>
			
			<?php do_atomic( 'before_entry' ); // supreme_before_entry ?>
				<?php
				$is_parent = $post->post_parent;	
				if($is_parent != 0){
					$featured = get_post_meta($is_parent,'featured_h',true);
					$calss_featured=$featured=='h'?'featured_h':'';
				}else{
					$featured = get_post_meta(get_the_ID(),'featured_h',true);
					$calss_featured=$featured=='h'?'featured_h':'';
				}
				?>
            
					<div id="post-<?php the_ID(); ?>" class="post event <?php echo $calss_featured;?>">
                    <?php
					if($featured==h){echo '<span class="featured"><img src="'.get_stylesheet_directory_uri().'/images/featured_img.png" /></span>';}
					?>
						<?php 
							if ( current_theme_supports( 'get-the-image' ) ) : 	
								if($is_parent != 0){
									get_the_image(array('post_id'=>$is_parent,'size'=>'event-home-thumb','image_class'=>'img','default_image'=>get_stylesheet_directory_uri()."/images/img_not_available.png"));		
								}else{
									get_the_image(array('post_id'=> get_the_ID(),'size'=>'event-home-thumb','image_class'=>'img','default_image'=>get_stylesheet_directory_uri()."/images/img_not_available.png"));		
								}								
							endif; ?>
						<div class="content">
							<?php 
								if($is_parent != 0){ $address = get_post_meta($is_parent,'address',true);
								}else{ $address = get_post_meta($post->ID,'address',true);
								}
								if(!$address){ 	$address='-'; }
								echo apply_atomic_shortcode( 'byline', '<span class="date">' . __('[entry-published]', T_DOMAIN ) . '</span>'); 
								?>
								<span class="date">
									<?php echo $date = date_i18n("d M",strtotime(get_post_meta($post->ID,'st_date',true))); ?>
								</span>
								<span class="title">
									<?php if($is_parent != 0){ 
										   ?>
									<a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php  echo $post->post_title;  ?></a>
									<?php }else{ ?>
									<a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php the_title(); ?></a>
									<?php }
									if($address)
									echo "<b>".$address."</b>"; ?>
								</span> 											
						</div><!-- .entry-content -->

						
						<?php do_atomic( 'close_entry' ); // supreme_close_entry ?>

					</div><!-- .hentry -->
			
			<?php do_atomic( 'after_entry' ); // supreme_after_entry ?>
			
				<?php endwhile; ?>

			<?php else : ?>
			
				<?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>

		<?php endif;  wp_reset_postdata();
?>
	</div>