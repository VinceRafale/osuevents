<?php
/* =============================== Feedburner Subscribe widget START ====================================== */
if(!class_exists('subscribewidget')){
	class subscribewidget extends WP_Widget {
		function subscribewidget() {
		//Constructor
			$widget_ops = array('classname' => 'widget Newsletter subscribe', 'description' => __('Newsletter Subscribe Widget','supreme') );		
			$this->WP_Widget('subscribewidget',apply_filters('subscribewidget_filter',__('T &rarr; Newsletter Subscribe','supreme')), $widget_ops);
		}
		function widget($args, $instance) {
		// prints the widget
		extract($args, EXTR_SKIP);
		$id = empty($instance['id']) ? '' : apply_filters('widget_id', $instance['id']);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$text = empty($instance['text']) ? '' : apply_filters('widget_text', $instance['text']);
	 echo $before_widget;
		?>
	  <div class="subscribe newsletter_subscribe_footer_widget">
						<div class="subscribe_wall">
        	<div class="subscribe_cont">
												<?php if($title){?><h3 class="widget-title"><?php echo $title; ?></h3><?php }?>
												<?php if($text){?><p><?php echo $text; ?></p><?php }?>
            <form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $id; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true" >
              <input type="text" name="name" value="<?php _e('Your Name','supreme');?>" class="field" onfocus="if (this.value == 'Your Name') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Your Name';}"  />
              <input type="text" name="email" value="" class="field" onfocus="if (this.placeholder == 'Your Email Address') {this.placeholder = '';}" onblur="if (this.placeholder == '') {this.placeholder = 'Your Email Address';}"  placeholder="<?php _e('Your Email Address','supreme');?>"/>
              <input type="hidden" value="<?php echo $id; ?>" name="uri"   />
              <input type="hidden" value="<?php bloginfo('name'); ?>" name="title" />
              <input type="hidden" name="loc" value="en_US"/>
              <input class="replace" type="submit" name="submit" value="<?php _e('Subscribe','supreme');?>" />
            </form>
         </div>
						</div>
	  </div>
	<?php
			 echo $after_widget;
		}
		function update($new_instance, $old_instance) {
		//save the widget
			$instance = $old_instance;		
			$instance['id'] = strip_tags($new_instance['id']);
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['text'] = strip_tags($new_instance['text']);
		
			
			return $instance;
		}
		function form($instance) {
		//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array( 'id' => '', 'title' => '', 'text' => '') );		
			$id = strip_tags($instance['id']);
			$title = strip_tags($instance['title']);
			$text = strip_tags($instance['text']);
			
	?>
			<p>
			  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:',supreme);?>
			  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			  </label>
			</p>
			<p>
			  <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text Under Title:',supreme);?>
			  <input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo esc_attr($text); ?>" />
			  </label>
			</p>
			<p>
			  <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Feedburner ID:',supreme);?>
			  <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo esc_attr($id); ?>" />
			  </label>
			</p>


	<?php
		}
	}
	register_widget('subscribewidget');
}
/* =============================== Feedburner Subscribe widget END ====================================== */
?>