<?php
/*
 * Create the templatic recent post widget
 */
	
class templatic_recent_post extends WP_Widget {
	function templatic_recent_post() {
	//Constructor
		$widget_ops = array('classname' => 'widget Templatic Listing Post', 'description' => __('Show Listing post with post thumbnail, post title, post content, post categorywise, gravatar with order by option.') );
		$this->WP_Widget('templatic_recent_post', __('T &rarr; Listing Post'), $widget_ops);
	}
	function widget($args, $instance) {
		// prints the widget
		extract($args, EXTR_SKIP);
		// defaults
			$instance = wp_parse_args( (array)$instance, array(
			'title' => '',
			'post_type'=>'',
			'post_type_taxonomy' => '',
			'post_number' => 0,			
			'orderby' => '',
			'order' => '',
			'show_image' => 0,
			'image_alignment' => '',
			'image_size' => '',
			'show_gravatar' => 0,
			'gravatar_alignment' => '',
			'gravatar_size' => '',
			'show_title' => 0,
			'show_byline' => 0,
			'post_info' => '[post_date] ' . __('By', DOMAIN) . ' [post_author_posts_link] [post_comments]',
			'show_content' => 'excerpt',
			'content_limit' => '',
			'more_text' => __('[Read More...]', DOMAIN),			
			) );
		

		echo $before_widget;
		// Set up the author bio
		if (!empty($instance['title']))
			echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
		
		remove_all_actions('posts_where');	
		$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $instance['post_type'],'public'   => true, '_builtin' => true ));	
		
		if($instance['post_type_taxonomy'])
			$cat_id=$instance['post_type_taxonomy'];
		else
		{
			$args=array('type'=> 'post','child_of'=> 0,'taxonomy'=> $taxonomies[0]);
			$categories = get_categories( $args ); 
			foreach($categories as $cat)
				$cat_id.=$cat->term_id.",";				
			$cat_id=substr($cat_id,0,-1);
		}
		$featured_arg=array('post_type' => $instance['post_type'], 'showposts' => $instance['post_number'],'orderby' => $instance['orderby'], 'order' => $instance['order'],'tax_query' => array(                
							array(
								'taxonomy' =>$taxonomies[0],
								'field' => 'id',
								'terms' =>array($cat_id),
								'operator'  => 'IN'
							)            
						 ));		
		remove_all_actions('posts_orderby');
		$featured_posts = new WP_Query($featured_arg);
		if($featured_posts->have_posts()) : 
			while($featured_posts->have_posts()) : $featured_posts->the_post();
				echo '<div '; post_class(); echo '>';
					if(!empty($instance['show_image'])) :
						printf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute('echo=0'), esc_attr( $instance['image_alignment'] ), featured_get_image( array( 'format' => 'html', 'size' => $instance['image_size'] ) ) );
					endif;
					/*Show gravatar */
					if(!empty($instance['show_gravatar'])) :
						echo '<span class="'.esc_attr($instance['gravatar_alignment']).'">';
						echo get_avatar( get_the_author_meta('ID'), $instance['gravatar_size'] );
						echo '</span>';
					endif;
					/* show post title*/
					if(!empty($instance['show_title'])) :
						printf( '<h2><a href="%s" title="%s">%s</a></h2>', get_permalink(), the_title_attribute('echo=0'), the_title_attribute('echo=0') );
					endif;
					if(!empty($instance['show_content'])) :					
						if($instance['show_content'] == 'excerpt') :
							the_excerpt();
						elseif($instance['show_content'] == 'content-limit') :							
							the_content_limit( (int)$instance['content_limit'], esc_html( $instance['more_text'] ) );
						else :
							the_content( esc_html( $instance['more_text'] ) );
						endif;					
					endif;
						
				echo '</div><!--end post_class()-->';
			endwhile;
		endif;
	
		echo $after_widget;		
	}

	function update($new_instance, $old_instance) {
		//save the widget				
		return $new_instance;
		//return $instance;
	}

	function form($instance) {

		//widgetform in backend
		$instance = wp_parse_args( (array)$instance, array(
			'title' => '',
			'post_type'=>'',
			'post_type_taxonomy' => '',
			'post_number' => 0,			
			'orderby' => '',
			'order' => '',
			'show_image' => 0,
			'image_alignment' => '',
			'image_size' => '',
			'show_gravatar' => 0,
			'gravatar_alignment' => '',
			'gravatar_size' => '',
			'show_title' => 0,
			'show_byline' => 0,
			'post_info' => '[post_date] ' . __('By', DOMAIN) . ' [post_author_posts_link] [post_comments]',
			'show_content' => 'excerpt',
			'content_limit' => '',
			'more_text' => __('[Read More...]', DOMAIN),			
			) );
		

	?>
	<p>
	  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:');?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
	  </label>
	</p>
     <p>
    	<label for="<?php echo $this->get_field_id('post_type');?>" ><?php _e('Select Post:');?>    	
    	<select  id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>" class="widefat">        	
    <?php
		$all_post_types = get_post_types();
		foreach($all_post_types as $post_types){
			if( $post_types != "page" && $post_types != "attachment" && $post_types != "revision" && $post_types != "nav_menu_item" ){
				?>
                	<option value="<?php echo $post_types;?>" <?php if($post_types== $instance['post_type'])echo "selected";?>><?php echo esc_attr($post_types);?></option>
                <?php				
			}
		}
	?>	
    	</select>
    </label>
    </p>
    <p>
    	<label for="<?php echo $this->get_field_id('post_type_taxonomy');?>" ><?php _e('Select Category:');?>    	
    	<select id="<?php echo $this->get_field_id('post_type_taxonomy'); ?>" name="<?php echo $this->get_field_name('post_type_taxonomy'); ?>" class="widefat" >      
        	<option value=""><?php _e('---Select Category wise recent post ---'); ?></option>
     <?php
			$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
			$taxonomies = array_filter( $taxonomies, 'templatic_exclude_taxonomies' );?>              
                <?php
						foreach ( $taxonomies as $taxonomy ) {							
							$query_label = '';
							if ( !empty( $taxonomy->query_var ) )
								$query_label = $taxonomy->query_var;
							else
								$query_label = $taxonomy->name;
							
							if($taxonomy->labels->name!='Tags' && $taxonomy->labels->name!='Format'):	
								?>
                                <optgroup label="<?php echo esc_attr( $taxonomy->object_type[0])."-".esc_attr($taxonomy->labels->name); ?>">
                                    <?php
									$terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=1' );
									foreach ( $terms as $term ) {		
									$term_value=$term->term_id;	?>
									<option style="margin-left: 8px; padding-right:10px;" value="<?php echo $term_value ?>" <?php if($instance['post_type_taxonomy']==$term_value) echo "selected";?>><?php echo '-' . esc_attr( $term->name ); ?></option><?php } ?>                                    </optgroup>
                                <?php
								endif;								
						}			
		?>
        	</select>
    </label>
    </p>
	<p>
	  <label for="<?php echo $this->get_field_id('post_number'); ?>"><?php _e('Number of posts:');?>
	  <input class="widefat" id="<?php echo $this->get_field_id('post_number'); ?>" name="<?php echo $this->get_field_name('post_number'); ?>" type="text" value="<?php echo $instance['post_number']; ?>" />
	  </label>
	</p>	
    <p>
    <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order By', DOMAIN); ?>:</label>
        <select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
        <option style="padding-right:10px;" value="date" <?php selected('date', $instance['orderby']); ?>><?php _e('Date', DOMAIN); ?></option>
        <option style="padding-right:10px;" value="title" <?php selected('title', $instance['orderby']); ?>><?php _e('Title', DOMAIN); ?></option>
        <option style="padding-right:10px;" value="parent" <?php selected('parent', $instance['orderby']); ?>><?php _e('Parent', DOMAIN); ?></option>
        <option style="padding-right:10px;" value="ID" <?php selected('ID', $instance['orderby']); ?>><?php _e('ID', DOMAIN); ?></option>
        <option style="padding-right:10px;" value="comment_count" <?php selected('comment_count', $instance['orderby']); ?>><?php _e('Comment Count', DOMAIN); ?></option>
        <option style="padding-right:10px;" value="rand" <?php selected('rand', $instance['orderby']); ?>><?php _e('Random', DOMAIN); ?></option>
    </select>
    </p>
    <p>
    	<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Sort Order', DOMAIN); ?>:</label>
        <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
            <option style="padding-right:10px;" value="DESC" <?php selected('DESC', $instance['order']); ?>><?php _e('Descending (3, 2, 1)', DOMAIN); ?></option>
            <option style="padding-right:10px;" value="ASC" <?php selected('ASC', $instance['order']); ?>><?php _e('Ascending (1, 2, 3)', DOMAIN); ?></option>
        </select>
    </p>
    <p>
            <input id="<?php echo $this->get_field_id('show_gravatar'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_gravatar'); ?>" value="1" <?php checked(1, $instance['show_gravatar']); ?>/> <label for="<?php echo $this->get_field_id('show_gravatar'); ?>"><?php _e('Show Author Gravatar', DOMAIN); ?></label>
        
        <label for="<?php echo $this->get_field_id('gravatar_size'); ?>"><?php _e('Gravatar Size', DOMAIN); ?>:</label>
        <select id="<?php echo $this->get_field_id('gravatar_size'); ?>" name="<?php echo $this->get_field_name('gravatar_size'); ?>">
            <option style="padding-right:10px;" value="45" <?php selected(45, $instance['gravatar_size']); ?>><?php _e('Small (45px)', DOMAIN); ?></option>
            <option style="padding-right:10px;" value="65" <?php selected(65, $instance['gravatar_size']); ?>><?php _e('Medium (65px)', DOMAIN); ?></option>
            <option style="padding-right:10px;" value="85" <?php selected(85, $instance['gravatar_size']); ?>><?php _e('Large (85px)', DOMAIN); ?></option>
            <option style="padding-right:10px;" value="125" <?php selected(125, $instance['gravatar_size']); ?>><?php _e('Extra Large (125px)', DOMAIN); ?></option>
        </select>
    </p>
    <p>
		<input id="<?php echo $this->get_field_id('show_image'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_image'); ?>" value="1" <?php checked(1, $instance['show_image']); ?>/> <label for="<?php echo $this->get_field_id('show_image'); ?>"><?php _e('Show Featured Image', DOMAIN); ?></label>
        
        <label for="<?php echo $this->get_field_id('image_size'); ?>"><?php _e('Image Size', DOMAIN); ?>:</label>
        <?php $sizes = get_additional_image_sizes(); ?>
        <select id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>">
            <option style="padding-right:10px;" value="thumbnail">thumbnail (<?php echo get_option('thumbnail_size_w'); ?>x<?php echo get_option('thumbnail_size_h'); ?>)</option>
            <?php
            foreach((array)$sizes as $name => $size) :
            echo '<option style="padding-right: 10px;" value="'.esc_attr($name).'" '.selected($name, $instance['image_size'], FALSE).'>'.esc_html($name).' ('.$size['width'].'x'.$size['height'].')</option>';
            endforeach;
            ?>
        </select>
    </p>
    <p>
        <input id="<?php echo $this->get_field_id('show_title'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_title'); ?>" value="1" <?php checked(1, $instance['show_title']); ?>/> 
        <label for="<?php echo $this->get_field_id('show_title'); ?>"><?php _e('Show Post Title', DOMAIN); ?></label>
    </p>
   <p>
        <label for="<?php echo $this->get_field_id('show_content'); ?>"><?php _e('Content Type', DOMAIN); ?>:</label>
        <select id="<?php echo $this->get_field_id('show_content'); ?>" name="<?php echo $this->get_field_name('show_content'); ?>">
        <option value="content" <?php selected('content' , $instance['show_content'] ); ?>><?php _e('Show Content', DOMAIN); ?></option>
        <option value="excerpt" <?php selected('excerpt' , $instance['show_content'] ); ?>><?php _e('Show Excerpt', DOMAIN); ?></option>
        <option value="content-limit" <?php selected('content-limit' , $instance['show_content'] ); ?>><?php _e('Show Content Limit', DOMAIN); ?></option>
        <option value="" <?php selected('' , $instance['show_content'] ); ?>><?php _e('No Content', DOMAIN); ?></option>
        </select>
   </p>
   <p>
        <label for="<?php echo $this->get_field_id('content_limit'); ?>"><?php _e('Limit content to', DOMAIN); ?></label> <input type="text" id="<?php echo $this->get_field_id('image_alignment'); ?>" name="<?php echo $this->get_field_name('content_limit'); ?>" value="<?php echo esc_attr(intval($instance['content_limit'])); ?>" size="3" /> <?php _e('characters', DOMAIN); ?>
	</p>
    <p>        
        <label for="<?php echo $this->get_field_id('more_text'); ?>"><?php _e('More Text (if applicable)', DOMAIN); ?>:</label>
        <input type="text" id="<?php echo $this->get_field_id('more_text'); ?>" name="<?php echo $this->get_field_name('more_text'); ?>" value="<?php echo esc_attr($instance['more_text']); ?>" />
    </p>
	<?php
	}
}
/*
 * templatic recent post widget init
 */
add_action( 'widgets_init', create_function('', 'return register_widget("templatic_recent_post");') );
/*
 * Function Name:the_content_limit
 * Return : Display the limited content
 */
function the_content_limit($max_char, $more_link_text = 'Read More ->', $stripteaser = true, $more_file = '') {	
	global $post;
	$content = get_the_content();
	$content = strip_tags($content);
	$content = substr($content, 0, $max_char);
	$content = substr($content, 0, strrpos($content, " "));
	$more_link_text='<a href="'.get_permalink().'">'.$more_link_text.'</a>';
	$content = $content." ".$more_link_text;
	echo $content;	
}
/* 
 * Function name: featured_get_image
 * Return: pass post image;
 */

function featured_get_image($arg)
{
	global $post;
	if($arg['format']=='html')
	{
		$image = bdw_get_images_plugin($post->ID,$arg['size']);	
		$thumb_img = $image[0]['file'];
		if($thumb_img)
			echo '<img class="img thumbnail " src="'.$thumb_img.'" />';		
	}else
	{
		$image = bdw_get_images_plugin($post->ID,$arg['size']);	
		$thumb_img = $image[0]['file'];
		echo $thumb_img;
	}	
}
?>