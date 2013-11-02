<?php
/*
Template Name: Template - Recurring Events User List
*/
get_header(); // Loads the header.php template. ?>

	

	<?php do_atomic( 'before_content' ); // supreme_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // supreme_open_content ?>

		<div class="hfeed">
			
			<?php get_sidebar( 'before-content' ); // Loads the sidebar-before-content.php template. ?>
			<?php if (current_theme_supports( 'breadcrumb-trail' ) && hybrid_get_setting('supreme_show_breadcrumb') ) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>
			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php do_atomic( 'before_entry' ); // supreme_before_entry ?>

					<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

						<?php do_atomic( 'open_entry' ); // supreme_open_entry ?>

						<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>

						<div class="entry-content">
                              	<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', T_DOMAIN ) ); ?>
							<?php
							global $wpdb;
							if(isset($_REQUEST['eid']) && $_REQUEST['eid']!=""):							
								$event_id=$_REQUEST['eid'];
								$qry_results = $wpdb->get_results("select user_id from $wpdb->usermeta where meta_key LIKE '%user_attend_event%' and meta_value LIKE '%#$event_id#%' ");	
								$user_attend='<h1 class="page-title entry-title"><a href="'.get_permalink($event_id).'" >'.get_the_title($event_id).'</a> Event Attend User list.</h1>';
								$user_attend.='<ul class="user_list">';
								foreach($qry_results as $res)
								{			
									$user = get_userdata($res->user_id);
									$user_attend.='<li>';
									$user_attend.='<div class="user_gravater"><a href="'.get_bloginfo('url').'/author/' . $user->user_nicename . '">'.str_replace("alt=''",'',get_avatar($user->user_email, '100')).'</a></div>';
									$user_attend.='<div class="user_info"><span>Name:&nbsp;<a href="'.get_bloginfo('url').'/author/' . $user->user_nicename . '">'.$user->display_name.'</a><br />';
									$user_attend.= __('From',T_DOMAIN).": <span style='color:#222222'>".get_post_meta($event_id,'st_date',true)."</span><br/>".__('To',T_DOMAIN).": <span style='color:#222222'>".get_post_meta($event_id,'end_date',true)."</span>";
									$user_attend.='<br/>Email:&nbsp;'.$user->user_email.'</div>';
									$user_attend.='</li>';
									
								}
								$user_attend.='</ul>';	
								echo $user_attend;
						 else:// request eid if condition
							echo "<div class='error'>"; _e('Invalid request, you need to select "persons attending" from event detail page to see the attending persons list of the event.',T_DOMAIN);
							echo "</div>";
						 endif;?>
	 						
						</div><!-- .entry-content -->						
						
						<?php do_atomic( 'close_entry' ); // supreme_close_entry ?>

					</div><!-- .hentry -->

					<?php do_atomic( 'after_entry' ); // supreme_after_entry ?>

					<?php get_sidebar( 'after-singular' ); // Loads the sidebar-after-singular.php template. ?>

					<?php do_atomic( 'after_singular' ); // supreme_after_singular ?>
					

				<?php endwhile; ?>

			<?php endif; ?>
			
			<?php get_sidebar( 'after-content' ); // Loads the sidebar-after-content.php template. ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // supreme_close_content ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // supreme_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>