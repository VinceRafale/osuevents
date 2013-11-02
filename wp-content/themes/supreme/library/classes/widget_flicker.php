<?php
/* Flicker Widget start*/
class supreme_flicker_Widget extends WP_Widget {
	function supreme_flicker_Widget() {
	//Constructor
		$widget_ops = array('classname' => 'widget Flicker Pohotos ', 'description' => 'Display a Flicker Photos.' );		
		$this->WP_Widget('flicker_Widget', 'T &rarr; Flicker Pohotos', $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget		
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']); 	
		$flicker_id = empty($instance['flicker_id']) ? '' : apply_filters('widget_flicker_id', $instance['flicker_id']); 		
		$flicker_number = empty($instance['flicker_number']) ? '' : apply_filters('widget_flicker_number', $instance['flicker_number']);		
		echo $before_widget;?>               
          <h3><?php echo sprintf(__('$s',T_DOMAIN),$title);?></h3>
	      <script type="text/javascript" src="http://www.flicker.com/badge_code_v2.gne?count=<?php echo $flicker_number; ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $flicker_id; ?>"></script>  
         <?php
		echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		//save the widget		
		return $new_instance;
	}
	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );				
		?>
          <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:',T_DOMAIN);?>
               <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
            </label>
          </p>	
		<p>
            <label for="<?php echo $this->get_field_id('flicker_id'); ?>"><?php _e('Flickr Id:',T_DOMAIN);?>
               <input class="widefat" id="<?php echo $this->get_field_id('flicker_id'); ?>" name="<?php echo $this->get_field_name('flicker_id'); ?>" type="text" value="<?php echo esc_attr($instance['flicker_id']); ?>" />
            </label>
          </p>	
          <p>	  
                <label for="<?php echo $this->get_field_id('flicker_number'); ?>"><?php _e('Number of photos:',T_DOMAIN);?>
               <input class="widefat" id="<?php echo $this->get_field_id('flicker_number'); ?>" name="<?php echo $this->get_field_name('flicker_number'); ?>" type="text" value="<?php echo esc_attr($instance['flicker_number']); ?>" />
            </label>
          </p>	          
		<?php
	}
}
register_widget('supreme_flicker_Widget');
/*Finish the flicker photos */
?>