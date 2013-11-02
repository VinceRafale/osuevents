<?php
/*
 * Create the templatic about us widget
 */
	
class templatic_aboust_us extends WP_Widget {
	function templatic_aboust_us() {
	//Constructor
		$widget_ops = array('classname' => 'widget Templatic About Us', 'description' => __('Through this widget you can show the information about site/your/company. You can use <html> tags too'),'before_widget'=>'<div class="column_wrap">' );
		$this->WP_Widget('templatic_aboust_us', __('T &rarr; About Us'), $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget

		extract($args, EXTR_SKIP);
	
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']); 		
		$about_us = empty($instance['about_us']) ? '' : apply_filters('widget_about_us', $instance['about_us']);
		echo $before_widget;
		if ( $title <> "" ) { 
			echo $before_title;
			echo $title;
			echo $after_title;
		}
		?>
        <div class="templatic_about_us">
        	<?php echo $about_us;?>
        </div>
        <?php		
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['about_us'] = $new_instance['about_us'];
		return $instance;

	}

	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '',  'about_us' => '',) );
		$title = $instance['title'];		
		$about_us = $instance['about_us'];
	?>
	<p>
	  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:');?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
	  </label>
	</p>	
	<p>	  
    	<label for="<?php echo $this->get_field_id('about_us'); ?>"><?php _e('Description:');?>
    	<textarea class="widefat" name="<?php echo $this->get_field_name('about_us'); ?>" cols="20" rows="16"><?php echo esc_attr($about_us); ?></textarea>	
        </label>
	</p>	
	<?php
	}
}
/*
 * templatic about us widget init
 */
add_action( 'widgets_init', create_function('', 'return register_widget("templatic_aboust_us");') );
?>