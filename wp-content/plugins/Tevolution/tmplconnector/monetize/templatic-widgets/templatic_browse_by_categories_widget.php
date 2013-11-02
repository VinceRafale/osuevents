<?php
/*
 * Create the templatic browse by categories widget
 */
	
class templatic_browse_by_categories extends WP_Widget {
	function templatic_browse_by_categories() {
	//Constructor
		$widget_ops = array('classname' => 'widget Templatic Browse By categories browse_by_categories', 'description' => __('Display the list of categories listing for selected post type') );
		$this->WP_Widget('templatic_browse_by_categories', __('T &rarr; Browse By Categories'), $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget

		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']); 		
		$post_type = empty($instance['post_type']) ? 'post' : apply_filters('widget_post_type', $instance['post_type']); 		
		$categories_count = empty($instance['categories_count']) ? '0' : '1';
		// Get all the taxonomies for this post type
		$output = 'names'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type,'public'   => true, '_builtin' => true ));
		if($post_type!='post'){	
				$taxo=$taxonomies[0];
		}else
			$taxo='category';
						
		if ( $title <> "" ) { 
			echo ' <h3 class="widget-title">'.$title.'</h3>';
		}		
		$cat_args = array(
						'taxonomy'=>$taxo,
						'orderby' => 'name', 
						'show_count' => $categories_count, 
						'hide_empty'	=> 0,
						'hierarchical' => 'true',
						'title_li'=>'');	
		echo "<ul>";
		wp_list_categories(apply_filters('widget_categories_args', $cat_args));
		echo "</ul>";			
		
		

		echo $after_widget;		
	}

	function update($new_instance, $old_instance) {
	//save the widget	
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_type'] = strip_tags($new_instance['post_type']);	
		$instance['categories_count'] = strip_tags($new_instance['categories_count']);
		return $instance;

	}

	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '','post_type'=>'', 'categories_count' => '',) );
		$title = strip_tags($instance['title']);
		$post_type = strip_tags($instance['post_type']);			
		$categories_count = strip_tags($instance['categories_count']);
	?>
	<p>
	  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:');?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
	  </label>
	</p>	   
    <p>
    	<label for="<?php echo $this->get_field_id('post_type');?>" ><?php _e('Select Post:');?>     </label>	
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
   
    	<span><?php _e('Display all categories list in front side for selected post type',DOMAIN);?></span>
    </p>
	<p>
      <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('categories_count'); ?>" name="<?php echo $this->get_field_name('categories_count'); ?>"  <?php if(esc_attr($categories_count)) echo 'checked';?> />
	  <label for="<?php echo $this->get_field_id('categories_count'); ?>"><?php _e('Show Categories Count:');?></label>
	</p>	
	<?php
	}
}
/*
 * templatic recent post widget init
 */
add_action( 'widgets_init', create_function('', 'return register_widget("templatic_browse_by_categories");') );


?>