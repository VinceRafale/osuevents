<?php
/*
 * Create the templatic recent post widget
 */
	
class templatic_popular_post_technews extends WP_Widget {
	function templatic_popular_post_technews() {
	//Constructor
		$widget_ops = array('classname' => 'widget Templatic Popular Posts Widget ', 'description' => __('Widget list the popular post as per total views , daily views or comments.( you can also select another post-type)') );
		$this->WP_Widget('templatic_popular_post_technews', __('T &rarr; Popular Posts Widget '), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$post_type = empty($instance['post_type']) ? 'post' : apply_filters('widget_post_type', $instance['post_type']);	
		$number = empty($instance['number']) ? '5' : apply_filters('widget_number', $instance['number']);
		$slide = empty($instance['slide']) ? '5' : apply_filters('widget_slide', $instance['slide']);
		$popular_per = empty($instance['popular_per']) ? 'comments' : apply_filters('widget_popular_per', $instance['popular_per']);
		$pagination_position = empty($instance['pagination_position']) ? 0 : apply_filters('widget_pagination_position', $instance['pagination_position']);
		if ( $title <> "" ) { 
			echo ' <h3 class="widget-title">'.$title.'</h3>';
		}
		global $wpdb,$posts,$post,$query_string;
		$now = gmdate("Y-m-d H:i:s",time());
		$lastmonth = gmdate("Y-m-d H:i:s",gmmktime(date("H"), date("i"), date("s"), date("m")-12,date("d"),date("Y")));

		if($popular_per == 'views'){	       
	        $popularposts = "SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE  post_status = 'publish' AND meta_key = 'viewed_count' AND post_password = '' AND post_type='$post_type' ORDER BY views DESC LIMIT 0,$number";
			
		}elseif($popular_per == 'dailyviews'){
			$popularposts = "SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE  post_status = 'publish' AND meta_key = 'viewed_count_daily' AND post_password = '' AND post_type='$post_type' ORDER BY views DESC LIMIT 0,$number";
		}else{
			$popularposts = "SELECT COUNT(ID) as count FROM $wpdb->posts, $wpdb->comments WHERE comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish' AND post_date < '$now' AND post_date > '$lastmonth' AND comment_status = 'open' AND post_type='$post_type' LIMIT 0,$number"; 
		}
		$totalpost = $wpdb->get_results($popularposts);	
		@$countpost = ($totalpost[0]->count < $number) ? $totalpost[0]->count : $number ;
		if($popular_per == 'views' || $popular_per == 'dailyviews' ){
		$countpost = count($totalpost) ; }
		$dot = ceil($countpost / $slide);
		if ( $pagination_position == 1  ) {
		?>
          <div class="postpagination clearfix">
			<?php if($dot != 1) { ?>
				<a num="1" rel="0" rev="<?php echo $slide; ?>" class="active">1</a>
				<?php
					for($c = 1; $c < $dot; $c++) {
						$start = ($c * $slide);
						echo '<a num="'.($c+1).'" rel="'.$start.'" rev="'.$slide.'">'.($c+1).'&nbsp;</a>';
					}
				?>
				
			<?php } ?>
		  </div>
		 <?php } ?> 
			<div class="popular_post templatic_popular_post_technews"><ul class="listingview clearfix list" id="list"></ul></div>
		<?php 
			if ( $pagination_position!=1 ) {
		?>
		  <div class="postpagination clearfix">
			<?php if($dot != 1) { ?>
				<a num="1" rel="0" rev="<?php echo $slide; ?>" class="active">1</a>
				<?php
					for($c = 1; $c < $dot; $c++) {
						$start = ($c * $slide);
						echo '<a num="'.($c+1).'" rel="'.$start.'" rev="'.$slide.'">'.($c+1).'&nbsp;</a>';
					}
				?>
				
			<?php } ?>
		  </div>
		 <?php } ?>	
			
			<script type="text/javascript">
			jQuery('.postpagination a').click(function(){				
						var start =  parseInt(jQuery(this).attr('rel'));
						var end =  parseInt(jQuery(this).attr('rev'));	
						var num =parseInt(jQuery(this).attr('num'));
						jQuery('.postpagination a').attr('class','');
						jQuery(this).attr('class','active');					
						jQuery('#list').load('<?php echo TEMPL_PLUGIN_URL; ?>tmplconnector/monetize/templatic-widgets/loadpopularpost.php', { "limitarr[]": [start, end,(start + end),'<?php echo $post_type;?>',num,'<?php echo $popular_per;?>',<?php echo $number;?>]}, function(){});
				});
				
				jQuery('#list').load('<?php echo TEMPL_PLUGIN_URL; ?>tmplconnector/monetize/templatic-widgets/loadpopularpost.php', { "limitarr[]": [0, <?php echo $slide; ?>,<?php echo $number; ?>,'<?php echo $post_type;?>',1,'<?php echo $popular_per;?>',<?php echo $number;?>] }, function(){});
				
            </script>
        <?php
	
		echo $after_widget;			
	}
	
	function update($new_instance, $old_instance) {		
		return $new_instance;
	}
	
	
	function form($instance) {
		$instance = wp_parse_args( (array)$instance, array(
			'title' => '',
			'post_type'=>'',			
			'number' => 0,		
			'slide'=>0,
			'popular_per' => '',					
			'pagination_position' => '',					
			) );
		//widgetform in backend			
		?>
        <p>
        	<label for="<?php  echo $this->get_field_id('title'); ?>"><?php _e('Title',DOMAIN);?>: 
            <input class="widefat" id="<?php  echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" /></label>
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('post_type');?>" ><?php _e('Select Post:');?>    	
            <select id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>" class="widefat" >        	
        <?php
            $all_post_types = get_post_types();
            foreach($all_post_types as $post_types){
                if( $post_types != "page" && $post_types != "attachment" && $post_types != "revision" && $post_types != "nav_menu_item" ){
                    ?>
                        <option value="<?php echo esc_attr($post_types);?>" <?php if($post_types== $instance['post_type'])echo "selected";?>><?php echo $post_types;?></option>
                    <?php				
                }
            }
        ?>	
            </select>
        </label>
        </p>
		<p>
        	<label for="<?php  echo $this->get_field_id('number'); ?>"><?php _e('Total Number of Posts',DOMAIN);?> 
            <input class="widefat" id="<?php  echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $instance['number']; ?>" /></label>
        </p>

   		<p>
        	<label for="<?php  echo $this->get_field_id('slide'); ?>"><?php _e('Number of Posts Per Slide',DOMAIN);?> 
            <input class="widefat" id="<?php  echo $this->get_field_id('slide'); ?>" name="<?php echo $this->get_field_name('slide'); ?>" type="text" value="<?php echo $instance['slide']; ?>" /></label>
        </p>
		
		<p>
        	<label for="<?php  echo $this->get_field_id('popular_per'); ?>"><?php _e('Shows post as per view counting/comments',DOMAIN);?> 
            <select class="widefat" id="<?php  echo $this->get_field_id('popular_per'); ?>" name="<?php echo $this->get_field_name('popular_per'); ?>">
                <option value="views" <?php if($instance['popular_per'] == 'views') { ?>selected='selected'<?php } ?>><?php _e('Total views',DOMAIN); ?></option>
                <option value="dailyviews" <?php if($instance['popular_per'] == 'dailyviews') { ?>selected='selected'<?php } ?>><?php _e('Daily views',DOMAIN); ?></option>
                <option value="comments" <?php if($instance['popular_per'] == 'comments') { ?>selected='selected'<?php } ?>><?php _e('Total comments',DOMAIN); ?></option>
            </select>
            </label>
        </p>
		
		<p>
        	<label for="<?php  echo $this->get_field_id('pagination_position'); ?>"><?php _e('Pagination Position',DOMAIN);?> 
            <select class="widefat" id="<?php  echo $this->get_field_id('pagination_position'); ?>" name="<?php echo $this->get_field_name('pagination_position'); ?>">
                <option value="0" <?php if($instance['pagination_position'] == 0) { ?>selected='selected'<?php } ?>><?php _e('After Posts',DOMAIN); ?></option>
                <option value="1" <?php if($instance['pagination_position'] == 1) { ?>selected='selected'<?php } ?>><?php _e('Before Posts',DOMAIN); ?></option>
            </select>
            </label>
        </p>

		<?php
	}
}

/*
 * templatic popular post technews widget init
 */
add_action( 'widgets_init', create_function('', 'return register_widget("templatic_popular_post_technews");') );
?>