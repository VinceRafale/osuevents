<?php
/* =============================== Social widget START ====================================== */
if(!class_exists('social_media')){
	class social_media extends WP_Widget {
		function social_media() {
		//Constructor
			$widget_ops = array('classname' => 'widget social_media', 'description' => 'Social media icon' );		
			$this->WP_Widget('social_media', 'T &rarr; Social Media', $widget_ops);
		}
		function widget($args, $instance) {
		// prints the widget
			extract($args, EXTR_SKIP);
			echo $before_widget;
			echo '<div class="social_media" >';
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$social_description = empty($instance['social_description']) ? '' : apply_filters('widget_title', $instance['social_description']);
			
			$twitter = empty($instance['twitter']) ? '' : apply_filters('widget_twitter', $instance['twitter']);
			$twitter_text = empty($instance['twitter_text']) ? 'Twitter' : apply_filters('widget_twitter_text', $instance['twitter_text']);
			$facebook = empty($instance['facebook']) ? '' : apply_filters('widget_facebook', $instance['facebook']);
			$facebook_text = empty($instance['facebook_text']) ? 'Facebook' : apply_filters('widget_facebook_text', $instance['facebook_text']);
			$googleplus = empty($instance['googleplus']) ? '' : apply_filters('widget_googleplus', $instance['googleplus']);
			$googleplus_text = empty($instance['googleplus_text']) ? 'Google Plus' : apply_filters('widget_googleplus_text', $instance['googleplus_text']);
			$linkedin = empty($instance['linkedin']) ? '' : apply_filters('widget_linkedin', $instance['linkedin']);
			$linkedin_text = empty($instance['linkedin_text']) ? 'Linkedin' : apply_filters('widget_linkedin_text', $instance['linkedin_text']);
			$rss = empty($instance['rss']) ? '' : apply_filters('widget_rss', $instance['rss']);
			$rss_text = empty($instance['rss_text']) ? 'Rss' : apply_filters('widget_rss', $instance['rss_text']);
			$youtube = empty($instance['youtube']) ? '' : apply_filters('widget_youtube', $instance['youtube']);
			$youtube_text = empty($instance['youtube_text']) ? 'Youtube' : apply_filters('widget_youtube_text', $instance['youtube_text']);
			$flickr = empty($instance['flickr']) ? '' : apply_filters('widget_flickr', $instance['flickr']);
			$flickr_text = empty($instance['flickr_text']) ? 'Flickr' : apply_filters('widget_flickr_text', $instance['flickr_text']);
			if($title!="")
			echo $before_title;
				echo $title;
			echo $after_title;
			if($social_description!=""): ?>
				<p class="social_description"><?php echo stripcslashes($social_description);?></p>
               <?php endif;?>
			<div class="social_media">
                 <ul class="social_media_list">
                     <?php if ( $twitter <> "" ){?>	
                         <li><a href="<?php echo $twitter; ?>" class="twitter" target="_blank" ><abbr>t</abbr><?php echo sprintf(__('%s','supreme'), $twitter_text);?></a></li>
                     <?php }?>    
                     <?php if ( $facebook <> "" ){ ?>	
                         <li> <a href="<?php echo $facebook; ?>" class="facebook" target="_blank" ><abbr>F</abbr><?php echo sprintf(__('%s','supreme'), $facebook_text);?></a></li>
                     <?php }?>  
                         <?php if ( $googleplus <> "" ){ ?>	
                         <li> <a href="<?php echo $googleplus; ?>" class="googleplus" target="_blank" ><abbr>g</abbr><?php echo sprintf(__('%s','supreme'), $googleplus_text);?></a> </li>
                     <?php }?>  
                     <?php if ( $linkedin <> "" ){?>	
                         <li> <a href="<?php echo $linkedin; ?>" class="linkedin" target="_blank" ><abbr>l</abbr><?php echo sprintf(__('%s','supreme'), $linkedin_text);?> </a></li>
                     <?php }?>  
                     <?php if ( $rss <> "" ){?>	
                         <li> <a href="<?php echo $rss; ?>" class="rssfeed" target="_blank" ><abbr>r</abbr><?php echo sprintf(__('%s','supreme'), $rss_text);?> </a></li>
                     <?php }?> 
                     <?php if ( $youtube <> "" ){?>	
                         <li> <a href="<?php echo $youtube; ?>" class="youtube" target="_blank" ><abbr>y</abbr><?php echo sprintf(__('%s','supreme'), $youtube_text);?> </a></li>
                     <?php }?> 
                     <?php if ( $flickr <> "" ){?>	
                         <li> <a href="<?php echo $flickr; ?>" class="flickr" target="_blank" ><abbr>n</abbr><?php echo sprintf(__('%s','supreme'), $flickr_text);?> </a></li>
                     <?php }?> 
     
                 </ul>
             </div>
		<?php
			echo '</div>';
			echo $after_widget;
		}
		function update($new_instance, $old_instance) {
		//save the widget
			$instance = $old_instance;		
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['social_description'] = strip_tags($new_instance['social_description']);
			$instance['twitter'] = ($new_instance['twitter']);
			$instance['twitter_text'] = ($new_instance['twitter_text']);
			$instance['facebook'] = ($new_instance['facebook']);
			$instance['facebook_text'] = ($new_instance['facebook_text']);
			$instance['googleplus'] = ($new_instance['googleplus']);
			$instance['googleplus_text'] = ($new_instance['googleplus_text']);
			$instance['linkedin'] = ($new_instance['linkedin']);
			$instance['linkedin_text'] = ($new_instance['linkedin_text']);
			$instance['rss'] = ($new_instance['rss']);
			$instance['rss_text'] = ($new_instance['rss_text']);
			$instance['youtube']=($new_instance['youtube']);
			$instance['youtube_text'] = ($new_instance['youtube_text']);
			$instance['flickr']=($new_instance['flickr']);
			$instance['flickr_text'] = ($new_instance['flickr_text']);
			return $instance;
		}
		function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'social_description' => '', 'twitter' => '', 'facebook' => '','googleplus'=>'', 'digg' => '',  'linkedin' => '', 'myspace' => '','rss' => '','youtube'=>'','flickr'=>'' ) );		
		$title = strip_tags($instance['title']);
		$social_description = strip_tags($instance['social_description']);
		$twitter = ($instance['twitter']);
		$facebook = ($instance['facebook']);
		$googleplus = ($instance['googleplus']);
		$linkedin = ($instance['linkedin']);		
		$rss = ($instance['rss']);
		$youtube=($instance['youtube']);
		$flickr=($instance['flickr']);
		
		
	?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','supreme');?>: 
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label>
    </p>
    
				<p>
        <label for="<?php echo $this->get_field_id('social_description'); ?>"><?php _e('Description','supreme');?>: 
        <input class="widefat" id="<?php echo $this->get_field_id('social_description'); ?>" name="<?php echo $this->get_field_name('social_description'); ?>" type="text" value="<?php echo esc_attr($social_description); ?>" /></label>
    </p>
				
    <p><i>Please specify full URL to your profiles.</i></p>
     
    <p><label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter profile URL','supreme');?>: <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo esc_attr($twitter); ?>" /></label></p>
    <p><label><input class="widefat" id="<?php echo $this->get_field_id('twitter_text'); ?>" name="<?php echo $this->get_field_name('twitter_text'); ?>"  placeholder="Text with social media icon" type="text" value="<?php echo esc_attr($instance['twitter_text']); ?>" /></label></p>
    
    <p><label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook profile URL','supreme');?> : <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo esc_attr($facebook); ?>" /></label></p>
    <p><label><input class="widefat" id="<?php echo $this->get_field_id('facebook_text'); ?>" name="<?php echo $this->get_field_name('facebook_text'); ?>"  placeholder="Text with social media icon" type="text" value="<?php echo esc_attr($instance['facebook_text']); ?>" /></label></p>
     
    <p><label for="<?php echo $this->get_field_id('googleplus'); ?>"><?php _e('Google Plus profile URL','supreme');?> : <input class="widefat" id="<?php echo $this->get_field_id('googleplus'); ?>" name="<?php echo $this->get_field_name('googleplus'); ?>" type="text" value="<?php echo esc_attr($googleplus); ?>" /></label><label></p>
    <p><input class="widefat" id="<?php echo $this->get_field_id('googleplus_text'); ?>" name="<?php echo $this->get_field_name('googleplus_text'); ?>"  placeholder="Text with social media icon" type="text" value="<?php echo esc_attr($instance['googleplus_text']); ?>" /></label></p>
    
    <p>
        <label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('Linkedin profile URL','supreme');?> : 
        <input class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" type="text" value="<?php echo esc_attr($linkedin); ?>" /></label>
    </p>
    <p>  
        <label>
        <input class="widefat" id="<?php echo $this->get_field_id('linkedin_text'); ?>" name="<?php echo $this->get_field_name('linkedin_text'); ?>"  placeholder="Text with social media icon" type="text" value="<?php echo esc_attr($instance['linkedin_text']); ?>" />
    	</label>
    </p>
    
    <p>
        <label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e('RSS feeds URL','supreme');?> : 
        <input class="widefat" id="<?php echo $this->get_field_id('rss'); ?>" name="<?php echo $this->get_field_name('rss'); ?>" type="text" value="<?php echo esc_attr($rss); ?>" /></label>
    </p>
    <p>    
        <label>
        <input class="widefat" id="<?php echo $this->get_field_id('rss_text'); ?>" name="<?php echo $this->get_field_name('rss_text'); ?>"  placeholder="Text with social media icon" type="text" value="<?php echo esc_attr($instance['rss_text']); ?>" />
        </label>
    </p>
    
    <p>
        <label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('You Tube','supreme');?> : 
        <input class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" type="text" value="<?php echo esc_attr($youtube); ?>" /></label>
    </p>
    <p>   
        <label>
        <input class="widefat" id="<?php echo $this->get_field_id('youtube_text'); ?>" name="<?php echo $this->get_field_name('youtube_text'); ?>"  placeholder="Text with social media icon" type="text" value="<?php echo esc_attr($instance['youtube_text']); ?>" />
        </label>
    </p>
    
    <p>
        <label for="<?php echo $this->get_field_id('flickr'); ?>"><?php _e('Flickr','supreme');?> : 
        <input class="widefat" id="<?php echo $this->get_field_id('flickr'); ?>" name="<?php echo $this->get_field_name('flickr'); ?>" type="text" value="<?php echo esc_attr($flickr); ?>" /></label>
    </p>
    <p>    
        <label>
        <input class="widefat" id="<?php echo $this->get_field_id('flickr_text'); ?>" name="<?php echo $this->get_field_name('flickr_text'); ?>" placeholder="Text with social media icon" type="text" value="<?php echo esc_attr($instance['flickr_text']); ?>" />
        </label>
    </p>
	<?php
		}
	}
	register_widget('social_media');
}
/* =============================== Social widget START ====================================== */
?>