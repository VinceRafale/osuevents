<?php
class claim_widget extends WP_Widget
{
	function claim_widget()
	{
		/* CONSTRUCTOR */
		$widget_ops = array('classname' => 'widget claim_widget', 'description' => 'Display a link and button for the users to claim on a post. This widget is to be placed in Detail page sidebar widget areas.' );		
		$this->WP_Widget('claim_widget', 'T &rarr; Claim Ownership', $widget_ops);
	}
	
	function widget($args, $instance)
	{
		/* THIS WILL PRINT THE WIDGET IN FRONT END */
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']); 
		$display_type = empty($instance['display_type']) ? 'Link' : apply_filters('widget_display_type', $instance['display_type']); 		
		echo $before_widget;
		if (!empty($instance['title']))
			echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
		?>
		
          
		<?php insert_claim_ownership_data($_POST); /* CALL A FUNCTION TO INSERT CLAIM POST DATA */?>
		
		
		<?php /* DISPLAYING THE WIDGET IN FRONT END */
		global $post,$wpdb,$claimpost;
		$post=$claimpost;		
		$tmpdata = get_option('templatic_settings'); /* FETCH CLAIM SETTINGS */
		$post_type = $tmpdata['claim_post_type_value']; /* FETCH CLAIM POST TYPES */
		$ptype = get_post_type($post->ID);
		
		if(is_array($post_type) && in_array($ptype,$post_type)):
			foreach ($post_type as $key => $val) :
				if($val == $ptype || $post_type == '') /* CONDITION FOR WHICH POST TYPES IT SHOULD DISPLAY */
				{
					echo '<div class="widget login_widget">';
					if(is_single() || is_page() && !is_page_template('page-template_form.php') ) : 						
						if(get_post_meta($post->ID,'is_verified',true) == 1)
						{ ?>
						<p class="i_verfied"><?php echo OWNER_VERIFIED; ?></p>
					<?php }
						else
						{ 
							$current_ip = $_SERVER["REMOTE_ADDR"]; /* FETCH CURRENT USER IP ADDRESS */												
							$post_claim_id = $wpdb->get_col("SELECT ID from $wpdb->posts WHERE (post_content = '".$post->ID."') AND post_status = 'publish' AND (post_excerpt = 'approved' OR post_excerpt = '') AND post_type='claim'");
							if(count($post_claim_id) > 0 )
							{
								foreach($post_claim_id as $key=>$val)
								{
									$data = get_post_meta($val,'post_claim_data',true); /* FETCH CLAIM ID */									
									if( $post->ID == $data['post_id'] )
									{
										$user_ip = $data['claimer_ip']; /* FETCH IP ADDRESS OF CLAIMED POST */
										if($current_ip == $user_ip && $user_ip != '')
										{ ?>
											<p class="claimed"><?php echo ALREADY_CLAIMED; ?>.</p>
								  <?php } else {
											if($display_type == 'Link') : ?>
											<a href="#claim_listing" id="trigger_id" title="claim_ownership" class="i_claim c_sendtofriend"><?php echo CLAIM_BUTTON;?></a>
											<?php else : ?>
											<a href="#claim_listing" id="trigger_id" title="claim_ownership" class="i_claim c_sendtofriend">
											<input type="button" value="<?php echo CLAIM_BUTTON;?>" /></a>
											<?php endif;
											include_once (TEMPL_MONETIZE_FOLDER_PATH . "templatic-claim_ownership/popup_claim_form.php"); ?>
											<?php }
									}
								}
							}
							else
							{
								if($display_type == 'Link') : ?>
									<a href="#claim_listing" id="trigger_id" title="claim_ownership" class="i_claim c_sendtofriend"><?php echo CLAIM_BUTTON;?></a>
								<?php else : ?>
									<a href="#claim_listing" id="trigger_id" title="claim_ownership" class="i_claim c_sendtofriend">
									<input type="button" value="<?php echo CLAIM_BUTTON;?>" /></a>
								<?php endif;
								include_once (TEMPL_MONETIZE_FOLDER_PATH . "templatic-claim_ownership/popup_claim_form.php"); 
							}
						}
					endif;
					echo '</div>';
				}
			endforeach;
		else:
		_e('Please select the claim '.$ptype.' post type on claim setting option from backend.',DOMAIN);		
		endif;
				echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		/* SAVE THE WIDGET */
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['display_type'] = strip_tags($new_instance['display_type']);
		return $instance;
	}
	
	function form($instance)
	{
		/* DISPLAY WIDGET FORM IN BACK END */
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'display_type' => 'link') );
		$title = strip_tags($instance['title']); 
		$display_type = strip_tags($instance['display_type']); ?>
		
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo TITLE_TEXT; ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('display_type'); ?>"><?php echo SELECT_DISPLAY_TYPE; ?> :</label>
		<label for="<?php echo $this->get_field_id('display_type'); ?>"><input type="radio" id="<?php echo $this->get_field_id('display_type'); ?>" name="<?php echo $this->get_field_name('display_type'); ?>" value="Link" <?php if($display_type == 'Link' || $display_type ==''){ echo 'checked="checked"'; }?> /><?php echo LINK;?></label>
		<label for="<?php echo $this->get_field_id('display_type'); ?>"><input type="radio" id="<?php echo $this->get_field_id('display_type'); ?>" name="<?php echo $this->get_field_name('display_type'); ?>" <?php if($display_type == 'Button'){ echo 'checked="checked"'; }?> value="Button" /><?php echo BUTTON;?>
		</label></p>
	<?php
	}
}?>