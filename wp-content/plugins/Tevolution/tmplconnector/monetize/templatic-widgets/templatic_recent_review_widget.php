<?php
define('NUMBER_REVIEWS_TEXT',__('Number of Reviews',DOMAIN));
class templatic_recent_review extends WP_Widget {
	function templatic_recent_review() {
	//Constructor
		$widget_ops = array('classname' => 'widget recent_reviews Recent Review', 'description' => 'Shows the latest commented post/taxonomy' );		
		$this->WP_Widget('widget_comment', 'T &rarr; Recent Review', $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$post_type = empty($instance['post_type']) ? 'post' : apply_filters('widget_post_type', $instance['post_type']);
		$count = empty($instance['count']) ? '5' : apply_filters('widget_count', $instance['count']);
 		
		echo $before_widget;

 		  if(function_exists('recent_review_comments')) {
			recent_review_comments(30, $count, 100, false,$post_type,$title);
		  }

		echo $after_widget;		
	}
	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_type'] = strip_tags($new_instance['post_type']);
		$instance['count'] = strip_tags($new_instance['count']);
 		return $instance;
	}
	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'post_type'=>'', 'count' => '' ) );		
		$title = strip_tags($instance['title']);
		$post_type = strip_tags($instance['post_type']);
		$count = strip_tags($instance['count']);
 ?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo TITLE_TEXT; ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
	<p>
    	<label for="<?php echo $this->get_field_id('post_type');?>" ><?php _e('Select Post:');?>    	
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
    </p>
        <p><label for="<?php echo $this->get_field_id('count'); ?>"><?php echo NUMBER_REVIEWS_TEXT; ?>: <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" /></label></p>
<?php
	}
}

add_action( 'widgets_init', create_function('', 'return register_widget("templatic_recent_review");') );
/*	
name : recent_comments
description :Function for getting recent comments -- */
function recent_review_comments($g_size = 30, $no_comments = 10, $comment_lenth = 60, $show_pass_post = false,$post_type='post',$title='') {
        global $wpdb, $tablecomments, $tableposts,$rating_table_name;
		$tablecomments = $wpdb->comments;
		$tableposts = $wpdb->posts;
		
		 if(is_plugin_active('wpml-translation-management/plugin.php')){
			$language = ICL_LANGUAGE_CODE;			
			$icl_translations=$wpdb->prefix."icl_translations icl_translations";
			$request = "SELECT ID, comment_ID, comment_content, comment_author,comment_post_ID, comment_author_email FROM $tableposts, $tablecomments ,$icl_translations WHERE $tableposts.ID=icl_translations.element_id  AND icl_translations.language_code = '".$language."' AND $tableposts.ID=$tablecomments.comment_post_ID AND post_type='".$post_type."' AND post_status = 'publish' ";	
			if(!$show_pass_post) { $request .= "AND post_password ='' "; }	
			$request .= "AND comment_approved = '1' ORDER BY $tablecomments.comment_date DESC LIMIT 0,$no_comments";
			
	 	}else
		{
			$request = "SELECT ID, comment_ID, comment_content, comment_author,comment_post_ID, comment_author_email FROM $tableposts, $tablecomments WHERE $tableposts.ID=$tablecomments.comment_post_ID AND post_type='".$post_type."' AND post_status = 'publish' ";	
			if(!$show_pass_post) { $request .= "AND post_password ='' "; }	
			$request .= "AND comment_approved = '1' ORDER BY $tablecomments.comment_date DESC LIMIT 0,$no_comments";
		}
        $comments = $wpdb->get_results($request);
		if($comments){
		if ( $title <> "") { 
			echo ' <h3 class="widget-title">'.$title.'</h3>';
		}
		echo '<ul class="recent_comments">';
        foreach ($comments as $comment) {
		$comment_id = $comment->comment_ID;
		$comment_content = strip_tags($comment->comment_content);
		$comment_excerpt = mb_substr($comment_content, 0, $comment_lenth)."";
		$permalink = get_permalink($comment->ID)."#comment-".$comment->comment_ID;
		$comment_author_email = $comment->comment_author_email;
		$comment_post_ID = $comment->comment_post_ID;
		$post_title = stripslashes(get_the_title($comment_post_ID));
		$permalink = get_permalink($comment_post_ID);
		
		
		echo "<li class='clearfix'><span class=\"li".$comment_id."\">";
		if (function_exists('get_avatar')) {
					  if ('' == @$comment->comment_type) {
						  echo  '<a href="'.$permalink.'">';
						 echo get_avatar($comment->comment_author_email, 60);
						 echo '</a>';
					  } elseif ( ('trackback' == $comment->comment_type) || ('pingback' == $comment->comment_type) ) {
						 echo  '<a href="'.$permalink.'">';
						  echo get_avatar($comment->comment_author_email, 60);
					  }
				   } elseif (function_exists('gravatar')) {
					  echo  '<a href="'.$permalink.'">';
					  echo "<img src=\"";
					  if ('' == $comment->comment_type) {
						 echo get_avatar($comment->comment_author_email, 60);
						  echo '</a>';
					  } elseif ( ('trackback' == $comment->comment_type) || ('pingback' == $comment->comment_type) ) {
						echo  '<a href="'.$permalink.'">';
						 echo get_avatar($comment->comment_author_email, 60);
						 echo '</a>';
					  }
					  echo "\" alt=\"\" class=\"avatar\" />";
				   }
		echo "</span>\n";
		echo '' ;
		echo  '<a href="'.$permalink.'" class="title">'.$post_title.'</a>';
		$tmpdata = get_option('templatic_settings');
		if($tmpdata['templatin_rating']=='yes'):
			$post_rating = $wpdb->get_var("select rating_rating from $rating_table_name where comment_id=\"$comment_id\"");
			echo draw_rating_star_plugin($post_rating);
		endif;
		echo "<a class=\"comment_excerpt\" href=\"" . $permalink . "\" title=\"View the entire comment\">";
		echo $comment_excerpt;
		echo "</a>";			
		echo '</li>';
    	}
		echo "</ul>";
	}
}
?>