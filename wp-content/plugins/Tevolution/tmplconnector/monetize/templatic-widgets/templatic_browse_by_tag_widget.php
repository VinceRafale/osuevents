<?php
/*
 * Create the templatic browse by categories widget
 */
	
class templatic_browse_by_tag extends WP_Widget {
	function templatic_browse_by_tag() {
	//Constructor
		$widget_ops = array('classname' => 'widget browse_by_tag Templatic Browse By tag', 'description' => __('Display the list of tags for selected post type') );
		$this->WP_Widget('templatic_browse_by_tag', __('T &rarr; Browse By Tag'), $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget

		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']); 		
		$post_type = empty($instance['post_type']) ? 'post' : apply_filters('widget_post_type', $instance['post_type']); 
		$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type,'public'   => true, '_builtin' => true ));
		if($post_type!='post'){
				$taxo=$taxonomies[1];
		}else
			$taxo='post_tag';
		
				
		if ( $title <> "" ) { 
			echo ' <h3 class="widget-title">'.$title.'</h3>';
		}	
		$args = array( 'taxonomy' => $taxo );
		$terms = get_terms($taxo, $args);
		if($terms):
			echo '<ul>';
			foreach ($terms as $term) {	?>
				<li><a href="<?php echo get_term_link($term->slug, $taxo);?>"><?php _e($term->name,DOMAIN);?></a></li>
			<?php }
			echo '</ul>';
		else:
			_e('No Tag Available',DOMAIN);
		endif;
			
		
		echo $after_widget;		
	}

	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_type'] = strip_tags($new_instance['post_type']);			
		return $instance;

	}

	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '','post_type'=>'','post_number' => '') );
		$title = strip_tags($instance['title']);		
		$post_type = strip_tags($instance['post_type']);
		$post_number = strip_tags($instance['post_number']);
	?>
	<p>
	  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:');?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
	  </label>
	</p>	
    <p>
    	<label for="<?php echo $this->get_field_id('post_type');?>" ><?php _e('Select Post for tag:');?>    	
    	<select  id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>" class="widefat">        	
    <?php
		$all_post_types = get_post_types();
		foreach($all_post_types as $post_types){
			if( $post_types != "page" && $post_types != "attachment" && $post_types != "revision" && $post_types != "nav_menu_item" ){
				?>
                	<option value="<?php echo $post_types;?>" <?php if($post_types== $post_type)echo "selected";?>><?php echo esc_attr($post_types);?></option>
                <?php				
			}
		}
	?>	
    	</select>
    </label>
	    <span><?php _e('Display all tags list in front side for selected post tags',DOMAIN);?></span>
    </p>
	<!--<p>
	  <label for="<?php echo $this->get_field_id('post_number'); ?>"><?php _e('Number of posts:');?>
	  <input class="widefat" id="<?php echo $this->get_field_id('post_number'); ?>" name="<?php echo $this->get_field_name('post_number'); ?>" type="text" value="<?php echo esc_attr($post_number); ?>" />
	  </label>
	</p>-->	
	<?php
	}
}
/*
 * templatic recent post widget init
 */
add_action( 'widgets_init', create_function('', 'return register_widget("templatic_browse_by_tag");') );
?>