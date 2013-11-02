<?php
/*
 * Create the templatic advertisement widget
 */
	
class templatic_advertisements extends WP_Widget {
	function templatic_advertisements() {
	//Constructor
		$widget_ops = array('classname' => 'widget Templatic Advertisements', 'description' => __('Show the advertisements. here You can paste HTML, JavaScript, an IFrame into this widget. ') );
		$this->WP_Widget('templatic_advertisements', __('T &rarr; Advertisements'), $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget

		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$ads = empty($instance['ads']) ? '' : $instance['ads'];
		if ( $title <> "" ) { 
			echo ' <h3 class="widget-title">'.$title.'</h3>';
		}
		?>
        <div class="advertisements">
			<?php echo $ads; ?>
        </div>
        <?php
		echo $after_widget;		
	}

	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['ads'] = $new_instance['ads'];
		return $instance;
	}

	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'ads' => '') );		
		$title = strip_tags($instance['title']);
		$ads = ($instance['ads']);
	?>
	<p>
    	<label for="<?php  echo $this->get_field_id('title'); ?>"><?php _e('Title',DOMAIN);?>: 
        <input class="widefat" id="<?php  echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label>
    </p>     
	<p>
    	<label for="<?php echo $this->get_field_id('ads'); ?>">
			<?php _e('Advertisement code <small>(ex.&lt;a href="#"&gt;&lt;img src="http://templatic.com/banner.png" /&gt;&lt;/a&gt; and google ads code here )</small>',DOMAIN);?>: 
       		<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('ads'); ?>" name="<?php echo $this->get_field_name('ads'); ?>"><?php echo $ads; ?></textarea>
       	</label>
    </p>
	<?php
	}
}
/*
 * templatic advertisements widget init
 */
add_action( 'widgets_init', create_function('', 'return register_widget("templatic_advertisements");') );
?>