<?php 
// Register widgetized areas
if ( function_exists('register_sidebar') )
{
	register_sidebars(1,array('id' => 'primary_menu_content', 'name' => 'Primary Menu Content', 'description' => 'Widgets placed in this area will be displayed with primary menu only its support only subimt event link and wordpress search widget.','before_widget' => '<div class="widget">','after_widget' => '</div>','before_title' => '<h3><span>','after_title' => '</span></h3>'));
	register_sidebars(1,array('id' => 'front_sidebar', 'name' => 'Front Page - Sidebar', 'description' => 'Set the widgets you like to show on home page sidebar, Like event calendar,advertisement etc.','before_widget' => '<div class="widget">','after_widget' => '</div>','before_title' => '<h3><span>','after_title' => '</span></h3>'));
	register_sidebars(1,array('id' => 'below_header', 'name' => 'Homepage Banner', 'description' => 'Widgets placed in this area will be display below header area.','before_widget' => '<div class="widget">','after_widget' => '</div>','before_title' => '<h3><span>','after_title' => '</span></h3>'));
	register_sidebars(1,array('id' => 'header_search', 'name' => 'Inner page - Header Section', 'description' => 'Widgets placed in this area will be display above main navigation, this widget area will show only in inner pages.','before_widget' => '<div class="widget">','after_widget' => '</div>','before_title' => '<h3><span>','after_title' => '</span></h3>'));
	register_sidebars(1,array('id'=>'footer1','name'=>'Above Footer','description'=>'Display wigets in full width just above the footer','before_widget'=>'<div class="widget">','after_widget'=>'</div>','before_title'=>'<h3>','after_title'=>'</h3>'));
	register_sidebars(1,array('id'=>'footer2','name'=>'Footer 1','description'=>'Display wigets in the first column of the footer','before_widget'=>'<div class="widget">','after_widget'=>'</div>','before_title'=>'<h3>','after_title'=>'</h3>'));
	register_sidebars(1,array('id'=>'footer3','name'=>'Footer 2','description'=>'Display wigets in 4 columns in footer after the footer 2 area','before_widget'=>'<div class="widget">','after_widget'=>'</div>','before_title'=>'<h3>','after_title'=>'</h3>'));
		
}
//END OF WIDGET AREAS
/*
Name : templ_remove_widgetareas
Description : remove unnecessory widget areas
*/
function templ_remove_widgetareas(){
	// Unregsiter some of the TwentyTen sidebars
	unregister_sidebar( 'after-content' );
	unregister_sidebar( 'subsidiary-2c' );
	unregister_sidebar( 'subsidiary-3c' );
	unregister_sidebar( 'subsidiary-4c' );
	unregister_sidebar( 'subsidiary-5c' );
	unregister_sidebar( 'after-header-2c' );
	unregister_sidebar( 'after-header-3c' );
	unregister_sidebar( 'after-header-4c' );
	unregister_sidebar( 'widgets-template' );
	unregister_sidebar( 'after-header-5c' );
	unregister_sidebar( 'after-header' );
	unregister_sidebar( 'secondary' );
	unregister_sidebar( 'before-content' );
	unregister_sidebar( 'after-singular' );
	unregister_sidebar( 'entry' );
	unregister_sidebar( 'header' );
}
add_action( 'init', 'templ_remove_widgetareas', 11 );
/* =============================== REGISTER WIDGETS ======================================= */

// EVENT SEARCH WIDGET STARTS ===============================================================================
class eventsearch extends WP_Widget {
	function eventsearch() {
	//Constructor
		$widget_ops = array('classname' => 'widget Event search', 'description' => 'Display a search form where you can get better options to search an event.' );		
		$this->WP_Widget('eventsearch', 'T &rarr; Events search', $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$desc1 = empty($instance['desc1']) ? '' : apply_filters('widget_desc1', $instance['desc1']);
		 ?>						 	
    <script type="text/javascript">
    function set_search()
	{
		var sr = '';

		if(document.getElementById('skw').value=='<?php _e("Search for","templatic");?>')
		{
			document.getElementById('skw').value = '';
		}else
		{
			sr = sr + document.getElementById('skw').value;
		}
		if(document.getElementById('scat').value=='-1')
		{
			document.getElementById('scat').value = '';
		}
		else
		{
			sr = sr + "-" + document.getElementById('scat').options[document.getElementById('scat').selectedIndex].text;
		}
		if(document.getElementById('sdate').value)
		{
			sr = sr + "-" + document.getElementById('sdate').value;
		}
		if(document.getElementById('saddress').value)
		{
			sr = sr + "-" + document.getElementById('saddress').value;
		}
		if(sr)
		{
			document.getElementById('sr').value = sr;
		}else
		{
			document.getElementById('sr').value = ' ';
		}
	}
    </script>
	<script type="text/javascript">
					jQuery(function(){
						var pickerOpts = {						
							showOn: "both",
							dateFormat: 'yy-mm-dd',
							buttonImage: "<?php echo TEMPL_PLUGIN_URL;?>css/datepicker/images/cal.png",
							buttonText: "Show Datepicker"
						};	
						jQuery("#sdate").datepicker(pickerOpts);
					});
				</script>
     <div class="widget event_search">
     	<?php if($title!=""):?>
  		 <h3><?php echo $title; ?> </h3> 
        <?php endif;?>
         <form action="<?php echo home_url();?>/" id="srchevent" name="srchevent" method="get"> 
			<input type="hidden" name="s" value="" id="sr" />
			<input type="hidden" name="t" value="event" />
         
          
          <div class="form_row">
          <?php if(@$_REQUEST['skw'])
		  {
			$skw = $_REQUEST['skw'];  
		  }?>
          	<label><?php echo SEARCH_EVENT_TEXT;?></label>
            <input type="text" onblur="if (this.value == '') {this.value = '<?php _e('Search for',T_DOMAIN);?>';}" onfocus="if (this.value == '<?php _e('Search for',T_DOMAIN);?>') {this.value = '';}" class="textfield xl" id="skw" name="skw" value="<?php echo @$skw;?>" />
          
          </div>
		  <div class="form_row">
          <label><?php echo SELECT_CATEGORY_TEXT;?></label>
		  <?php echo get_category_dl_options(@$_REQUEST['scat']);?>
          
          </div>
		  <div class="form_row">
		  <label><?php echo EVENT_START_TEXT;?></label>
          <input type="text" name="sdate" id="sdate" value="<?php echo @$_REQUEST['sdate'];?>"   size="25"  />
		  </div>
		  <div class="form_row">
		  <label><?php echo ZIP_OR_ADD_TEXT;?></label>
          <input name="saddress" id="saddress" type="text" value="<?php echo @$_REQUEST['saddress'];?>" class="textfield xl"  />
		  </div>
		  <div class="form_row">
		  <?php 
			$default_custom_metaboxes = get_search_post_fields_templ_plugin('event','custom_fields','post');
			display_search_custom_post_field_plugin($default_custom_metaboxes,'custom_fields','post');//displaty custom fields html. ?>
		  </div>
		  <div class="form_row">
          <input name="search" type="submit" value="<?php echo SEARCH_EVENTS_TEXT;?>" class="b_search_event" onclick="set_search();" />
		  </div>
	</div>
	</form>
	<?php
	}
	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 't1' => '', 't2' => '', 't3' => '',  'img1' => '', 'desc1' => '' ) );		
		$title = strip_tags($instance['title']);
		
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo TITLE_TEXT; ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
<?php
	}
}
register_widget('eventsearch');
// EVENT SEARCH WIDGET ENDS
// LATEST NEWS WIDGET STARTS ======================================================================================

class eventwidget extends WP_Widget {
	function eventwidget() {
	//Constructor
		$widget_ops = array('classname' => 'widget Latest News', 'description' => 'Display a list of Latest Blog posts.' );
		$this->WP_Widget('eventwidget', 'T &rarr; Latest News', $widget_ops);
	}

	function widget($args, $instance) {
	// prints the widget

		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
 		$category = empty($instance['category']) ? '' : apply_filters('widget_category', $instance['category']);
		$post_number = empty($instance['post_number']) ? '5' : apply_filters('widget_post_number', $instance['post_number']);
		$post_link = empty($instance['post_link']) ? '' : apply_filters('widget_post_link', $instance['post_link']);

		// if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		echo '';
		 ?>
         <?php if($title!=""):?>
  		 	<h3><?php echo $title; ?> </h3> 
         <?php endif;?>
          <ul class="listingview">
                
				<?php 
			        global $post;
				   $suppress_filters='true';
				   if(is_plugin_active('wpml-translation-management/plugin.php') && function_exists('icl_object_id')){
						$category = icl_object_id($category,'category',false,ICL_LANGUAGE_CODE);
						$suppress_filters='false';
				   }
				$args = array( 'suppress_filters' => $suppress_filters , 'numberposts' => $post_number, 'post_type' => 'post' , 'category_name' =>  $category);
	            	$latest_menus = get_posts($args);
                    foreach($latest_menus as $post) :
                    setup_postdata($post);
			    ?>
                <?php $post_images = bdw_get_images_with_info($post->ID,'thumb');
				$thumb = $post_images[0]['file']; ?>
				<li class="clearfix">
				<?php if(get_post_meta($post->ID,'featured_h',true) == 'h' ) { ?><div class="featured_tag"></div><?php }?>
				<?php if ( $thumb != '' ) { ?>
				<a class="post_img" href="<?php the_permalink(); ?>">
				<img src="<?php echo $thumb; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /></a>
				<?php
				} else { ?>
				<a href="<?php echo get_permalink($post->ID); ?>" class="post_img"><img src="<?php echo get_stylesheet_directory_uri()."/images/img_not_available.png"; ?>"  alt="<?php echo $post_img[0]['alt']; ?>" /></a>
				<?php } ?>
                   <h3> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>  </h3>
                   <p class="date"><?php the_time('j F Y') ?> <?php _e('at',T_DOMAIN);?> <?php the_time('H : s A') ?></p> 
               </li>
    
<?php endforeach; wp_reset_query();?>
<?php
	    echo '</ul>';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['category'] = strip_tags($new_instance['category']);
		$instance['post_number'] = strip_tags($new_instance['post_number']);
		$instance['post_link'] = strip_tags($new_instance['post_link']);
		return $instance;

	}

	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'category' => '', 'post_number' => '' ) );
		$title = strip_tags($instance['title']);
		$category = strip_tags($instance['category']);
		$post_number = strip_tags($instance['post_number']);
		$post_link = strip_tags($instance['post_link']);

?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo TITLE_TEXT; ?>:
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('category'); ?>"><?php echo CATEGORY_SLUGS_TEXT; ?>:
  <input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" type="text" value="<?php echo esc_attr($category); ?>" />
  </label>  
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('post_number'); ?>"><?php echo NUMBER_POSTS_TEXT; ?>:
  <input class="widefat" id="<?php echo $this->get_field_id('post_number'); ?>" name="<?php echo $this->get_field_name('post_number'); ?>" type="text" value="<?php echo esc_attr($post_number); ?>" />
  </label>
</p>
<?php
	}
}
register_widget('eventwidget');
// LATEST NEWS WIDGET ENDS


// LATEST EVENTS WIDGET STARTS ===================================================================================

class onecolumnslist extends WP_Widget {
	function onecolumnslist() {
	//Constructor
		$widget_ops = array('classname' => 'widget category List View', 'description' => 'Display a list of Latest Events. To be placed in Front page sidbar widget area.' );
		$this->WP_Widget('onecolumnslist', 'T &rarr; Latest Events', $widget_ops);
	}

	function widget($args, $instance) {
	// prints the widget

		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
 		$category = empty($instance['category']) ? '' : apply_filters('widget_category', $instance['category']);
		$post_number = empty($instance['post_number']) ? '5' : apply_filters('widget_post_number', $instance['post_number']);
		$post_link = empty($instance['post_link']) ? '' : apply_filters('widget_post_link', $instance['post_link']);
		$more_link = empty($instance['more_link']) ? '' : apply_filters('widget_more_link', $instance['more_link']);
		$character_cout = empty($instance['character_cout']) ? '15' : apply_filters('widget_character_cout', $instance['character_cout']);
		$sorting = empty($instance['event_sorting']) ? 'Latest Published' : apply_filters('widget_event_sorting', $instance['event_sorting']);
		 ?>
         <?php if($title!=""):?>
  		 <h3><?php echo $title; ?> </h3> 
        <?php endif;?>
          <ul class="listingview clearfix">
		  <?php //$type = get_option('ptthemes_event_sorting');
			if ( $sorting != '' )
			{
				global $wpdb;
				if ( $sorting == 'Random' )
				{
					$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id = p.ID and $wpdb->postmeta.meta_key like \"st_date\") ASC, rand()";
				}
				elseif ( $sorting == 'Alphabetical' )
				{
					$orderby = "p.post_title ASC";
				}
				else
				{
					$orderby = "(select $wpdb->postmeta.meta_value from $wpdb->postmeta where $wpdb->postmeta.post_id=p.ID and $wpdb->postmeta.meta_key = 'featured_h') ASC, p.post_date DESC";
				}
			}
			?>
		  <?php 
			global $post,$wpdb;
			 
			
			if($category)
			{
				$sqlsql = " and p.ID in (select tr.object_id from $wpdb->term_relationships tr join $wpdb->term_taxonomy t on t.term_taxonomy_id=tr.term_taxonomy_id  join $wpdb->terms tm  on t.term_id=tm.term_id where tm.slug like '$category'  )";
			}
			if(is_plugin_active('wpml-translation-management/plugin.php')){
				$language = ICL_LANGUAGE_CODE;			
				$icl_translations=$wpdb->prefix."icl_translations icl_translations";
				@$sql = "select p.* from $wpdb->posts p ,$icl_translations where p.ID=icl_translations.element_id  AND icl_translations.language_code = '".$language."' and  p.post_type='".CUSTOM_POST_TYPE_EVENT."' and p.post_status='publish' $sqlsql order by $orderby limit $post_number";
			 }else
			 {
				@$sql = "select p.* from $wpdb->posts p where p.post_type='".CUSTOM_POST_TYPE_EVENT."' and p.post_status='publish' $sqlsql order by $orderby limit $post_number";
			 }			
			
			$latest_menus = $wpdb->get_results($sql);
			$pcount=0;
			if($latest_menus)
			{
				foreach($latest_menus as $post) :
				setup_postdata($post);
				$pcount++; ?>
					<?php $post_images = bdw_get_images_with_info($post->ID,'thumb');
					$thumb = $post_images[0]['file']; ?>
					<li class="clearfix">
					<?php if(get_post_meta($post->ID,'featured_h',true) == 'h' ) { ?><div class="featured_img_s"></div><?php }?>
					<?php if ( $thumb != '' ) { ?>
					<a class="post_img" href="<?php the_permalink(); ?>">
					<img src="<?php echo $thumb; ?>" width="85" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /></a>
					<?php
					} else { ?>
					<a href="<?php echo get_permalink($post->ID); ?>" class="post_img"><img src="<?php echo get_stylesheet_directory_uri()."/images/img_not_available.png"; ?>" width="85" alt="<?php echo @$post_img[0]['alt']; ?>" /></a>
					<?php } ?>
            		<h3> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3> 
                    <p> <span> <?php echo get_formated_date(get_post_meta($post->ID,'st_date',true));?> <?php _e('at',T_DOMAIN);?> <?php echo get_formated_time(get_post_meta($post->ID,'st_time',true))?></span> 
                    <?php echo get_post_meta($post->ID,'address',true);?> </p>
            	 </li>
				<?php endforeach; ?>
                 <?php }else{
				  _e('<p>Not a single Event is there.</p>',T_DOMAIN);
			 		}
				 ?>
<?php

	    echo '</ul>';

		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['category'] = strip_tags($new_instance['category']);
		$instance['post_number'] = strip_tags($new_instance['post_number']);
		$instance['post_link'] = strip_tags($new_instance['post_link']);
		$instance['more_link'] = strip_tags($new_instance['more_link']);
		$instance['character_cout'] = strip_tags($new_instance['character_cout']);
		$instance['event_sorting'] = strip_tags($new_instance['event_sorting']);
		return $instance;

	}

	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'category' => '', 'post_number' => '','character_cout' => '','more_link' => '', 'event_sorting' => 'Latest Published' ) );
		$title = strip_tags($instance['title']);
		$category = strip_tags($instance['category']);
		$post_number = strip_tags($instance['post_number']);
		$post_link = strip_tags($instance['post_link']);
		$more_link = strip_tags($instance['more_link']);
		$character_cout = strip_tags($instance['character_cout']);
		$sorting = strip_tags($instance['event_sorting']);

?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo TITLE_TEXT; ?>:
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('category'); ?>"><?php echo CATEGORY_SLUGS_TEXT; ?>:
  <input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" type="text" value="<?php echo esc_attr($category); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('post_number'); ?>"><?php echo NUMBER_POSTS_TEXT; ?>:
  <input class="widefat" id="<?php echo $this->get_field_id('post_number'); ?>" name="<?php echo $this->get_field_name('post_number'); ?>" type="text" value="<?php echo esc_attr($post_number); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('event_sorting'); ?>"><?php echo SORT_EVENT; ?>:
  <select name="<?php echo $this->get_field_name('event_sorting'); ?>" id="<?php echo $this->get_field_id('event_sorting'); ?>">
	<option selected="selected" value="Latest Published"><?php _e('Latest Published',T_DOMAIN); ?></option>
	<option <?php if ($sorting == 'Random') { echo 'selected=selected'; } ?> value="Random"><?php _e('Random',T_DOMAIN); ?></option>
	<option <?php if ($sorting == 'Alphabetical') { echo 'selected=selected'; } ?> value="Alphabetical"><?php _e('Alphabetical',T_DOMAIN); ?></option>
  </select>
  </label>
</p>
<?php
	}
}
register_widget('onecolumnslist');
// LATEST EVENTS WIDGET ENDS

// FEATURED VIDEO WIDGET STARTS ==========================================================================

class spotlightpost extends WP_Widget {
	function spotlightpost() {
	//Constructor
		$widget_ops = array('classname' => 'widget Featured Video', 'description' => 'Display a list of Videos added in Events.' );
		$this->WP_Widget('spotlight_post', 'T &rarr; Featured Video', $widget_ops);
	}

	function widget($args, $instance) {
	// prints the widget

		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$category = empty($instance['category']) ? '' : apply_filters('widget_category', $instance['category']);
		$post_number = empty($instance['post_number']) ? '5' : apply_filters('widget_post_number', $instance['post_number']);
		$post_link = empty($instance['post_link']) ? '' : apply_filters('widget_post_link', $instance['post_link']);

		// if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		echo ' <div class="featured_video">';?>
        		<?php if($title!=""):?>
                <h3 class="clearfix"> <span class="fl"><?php echo $title; ?> </span>
                  <?php if ( $video_link <> "" ) { ?>	 
                   <span class="more"><a href="<?php echo $video_link; ?>"> <?php _e('View All',T_DOMAIN);?></a> </span> 
          		<?php } ?>
                 </h3>
		        <?php endif;?>
				<?php 
			        global $post;
					$args = array( 'numberposts' => -1,'taxonomy' => CUSTOM_CATEGORY_TYPE_EVENT , 'category' => $category, 'post_type' => CUSTOM_POST_TYPE_EVENT);
			        $latest_menus = get_posts( $args );
				
                    $v = 0;
					foreach($latest_menus as $post) :
                    setup_postdata($post); ?>
	 
                <?php  if(get_post_meta($post->ID,'video',true) != "" && $v < $post_number){ ?>
                     <div class="video">
                    <?php echo get_post_meta($post->ID,'video',true);?>
                    	<h4><a class="widget-title" href="<?php the_permalink(); ?>"><?php the_title(); ?> </a></h4>
                    </div>
                    <?php $v++; }?>   
                 <?php endforeach; ?>
                <?php

	    echo '</div>';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['category'] = strip_tags($new_instance['category']);
		$instance['post_number'] = strip_tags($new_instance['post_number']);
		$instance['post_link'] = strip_tags($new_instance['post_link']);
		return $instance;

	}

	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'category' => '', 'post_number' => '' ) );
		$title = strip_tags($instance['title']);
		$category = strip_tags($instance['category']);
		$post_number = strip_tags($instance['post_number']);
		$post_link = strip_tags($instance['post_link']);

?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo TITLE_TEXT; ?>:
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('category'); ?>"><?php echo CATEGORY_SLUGS_TEXT; ?>:
    <input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" type="text" value="<?php echo esc_attr($category); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('post_number'); ?>"><?php echo NUMBER_POSTS_TEXT; ?>:
    <input class="widefat" id="<?php echo $this->get_field_id('post_number'); ?>" name="<?php echo $this->get_field_name('post_number'); ?>" type="text" value="<?php echo esc_attr($post_number); ?>" />
  </label>
</p>
<?php
	}
}
register_widget('spotlightpost');


// EVENTS CALENDAR WIDGET STARTS ================================================================
class my_event_calender_widget extends WP_Widget {
	function my_event_calender_widget() {
	//Constructor
		$widget_ops = array('classname' => 'widget Event Listing calendar.', 'description' => 'In Calendar The highlighted dates describes the total events occurs in a particular month. Also you will be able to see the events occurs on a particular date in a popover box.' );		
		$this->WP_Widget('event_calendar', 'T &rarr; Event Listing Calendar', $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget
		global $post;
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		include_once (TEMPLATE_FUNCTION_FOLDER_PATH . 'calendar/calendar.php');
		if($title)
		{
			echo '<h3>'.$title.'</h3>';	
		}
		get_my_event_calendar();
	}
	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );		
		$title = strip_tags($instance['title']);
		?>
        
        
        
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo TITLE_TEXT; ?>:
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label>
</p>
        <?php
	}
}
register_widget('my_event_calender_widget');
// EVENTS CALENDER WIDGET ENDS




// fetch category wise post ================================================================================
class categoryposts extends WP_Widget {
	function categoryposts() {
	//Constructor
		$widget_ops = array('classname' => 'widget categorywise events ', 'description' => 'Display categorywise Latest Events. This widget is to be placed in subsidiary widget area.' );
		$this->WP_Widget('categoryposts', 'T &rarr; Categorywise events', $widget_ops);
	}

	function widget($args, $instance) {
	// prints the widget

		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
 		$slug = empty($instance['category']) ? '' : apply_filters('widget_category', $instance['category']);
		$post_number = empty($instance['post_number']) ? '5' : apply_filters('widget_post_number', $instance['post_number']);
		$post_link = empty($instance['post_link']) ? '' : apply_filters('widget_post_link', $instance['post_link']);
		$character_cout = empty($instance['character_cout']) ? '15' : apply_filters('widget_character_cout', $instance['character_cout']);
		
	global $post,$wpdb;
	$today = date('Y-m-d G:i:s');
	wp_reset_query();
	//$category = explode(",",$category);
	
	if(is_plugin_active('wpml-translation-management/plugin.php')){
		$category_ID =  get_term_by( 'slug',$slug, 'ecategory' );	
		$slug=$category_ID->slug;
		$title=ucfirst($category_ID->name);
	}
	if($slug)
	{
		$args=
		array( 
		'post_type' => 'event',
		'posts_per_page' => $post_number,
		'post_status' => array('publish','private'),
		'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'st_date',
					'value' => $today,
					'compare' => '>',
					'type'=> 'text'
				),
				array(
					'key' => 'end_date',
					'value' =>  $today,
					'compare' => '>='
				)
			),
		'tax_query' => array(
			array(
				'taxonomy' => CUSTOM_CATEGORY_TYPE_EVENT,
				'field' => 'slug',
				'terms' => array($slug),
				'operator'  => 'IN'
			)
			),
			'meta_key' => 'st_date',
			'orderby' => 'meta_value',
			'order' => 'DESC'
		);
		
	}
	else
	{
		$args=
			array( 
			'post_type' => 'event',
			'posts_per_page' => $post_number,
			'post_status' => array('publish'),
			'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'st_date',
						'value' => $today,
						'compare' => '>=',
						'type'=> 'text'
					),
					array(
						'key' => 'end_date',
						'value' =>  $today,
						'compare' => '>='
					)
				),
				'meta_key' => 'st_date',
				'orderby' => 'meta_value',
				'order' => 'DESC'
			);
	}
	$post_query = null;
	remove_action('pre_get_posts','slider_advance_search');
	remove_all_actions('posts_where');
	remove_all_actions('posts_orderby');
	
	add_filter( 'posts_clauses', 'remove_recurring_event',10,2 );
	$post_query = new WP_Query($args);
	remove_filter( 'posts_clauses', 'remove_recurring_event',10,2 );
	//$latest_menus = $post_query;

	if($post_query)
	{ ?>
	

				<div class="index_column">
                	<?php if($title!=""):?>
						<h4><?php echo $title; ?> </h4>
                    <?php endif;?>
						<a class="viewall" href="<?php if($slug) { echo get_term_link($slug, CUSTOM_CATEGORY_TYPE_EVENT);  } else { echo "#"; } ?>"><?php _e('VIEW ALL',T_DOMAIN); ?></a>
						<ul class="eventlist collist">
							<?php 
							
							while($post_query->have_posts()): $post_query->the_post();
							setup_postdata($post);
							?>
							<li>
								 <div class="content">
									<p class="clearfix">
										<span class="date">
											<?php 
										
												echo $date = date("d",strtotime(get_post_meta($post->ID,'st_date',true)));
												
											//	echo date('d',strtotime($date));
											?>
                                            <b><?php echo date_i18n("M",strtotime(get_post_meta($post->ID,'st_date',true)));?></b>
                                        </span>
										<span class="title"><a href="<?php echo get_permalink($post->ID); ?>"><?php the_title(); ?></a><b><?php echo get_post_meta($post->ID,'address',true);?></b></span>
									</p>
									<div class="clearfix"></div>
								</div>
							</li>
					<?php endwhile; ?>
<?php
  			 echo '</ul></div>';
}
	}

	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['category'] = strip_tags($new_instance['category']);
		$instance['post_number'] = strip_tags($new_instance['post_number']);
		$instance['post_link'] = strip_tags($new_instance['post_link']);
		$instance['character_cout'] = strip_tags($new_instance['character_cout']);
		return $instance;
	}

	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'category' => '', 'post_number' => '','character_cout' => '' ) );
		$title = strip_tags($instance['title']);
		$category = strip_tags($instance['category']);
		$post_number = strip_tags($instance['post_number']);
		$post_link = strip_tags($instance['post_link']);
		$character_cout = strip_tags($instance['character_cout']);
?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo TITLE_TEXT; ?>:
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('category'); ?>"><?php echo CATEGORY_SLUGS_TEXT; ?>:
  <input class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" type="text" value="<?php echo esc_attr($category); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('post_number'); ?>"><?php echo NUMBER_POSTS_TEXT; ?>:
  <input class="widefat" id="<?php echo $this->get_field_id('post_number'); ?>" name="<?php echo $this->get_field_name('post_number'); ?>" type="text" value="<?php echo esc_attr($post_number); ?>" />
  </label>
</p>
 
<?php
	}
}
register_widget('categoryposts');
// Category POSTS WIDGET ENDS

// EVENT SEARCH WIDGET STARTS =============================================================
class footersearchwidget extends WP_Widget  {
	function footersearchwidget() {
	//Constructor
		$widget_ops = array('classname' => 'widget Event search', 'description' => 'Display event search widget in Above Footer area.' );		
		$this->WP_Widget('footersearchwidget', 'T &rarr; Event search widget', $widget_ops);
	}
	function widget($args, $instance) {
	
	
	// prints the widget
		extract($args, EXTR_SKIP);
		$radius = empty($instance['radius']) ? '' : $instance['radius'];
		$distance = empty($instance['distance']) ? '' : $instance['distance'];
		
?>
<script type="text/javascript">
	jQuery(function(){
		var pickerOpts = {					
			dateFormat: 'yy-mm-dd'
		};	
		jQuery("#footer_search_date").datepicker(pickerOpts);
	});
</script>
 
<div class="footer_bg1">
	<?php echo search_form('footer_container search_box','footer_search_date',$radius,$distance); ?>
</div> 
 
<?php }
	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;		
		$instance['radius'] = strip_tags($new_instance['radius']);
		$instance['distance'] = strip_tags($new_instance['distance']);
		return $instance;
	}
	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 't1' => '', 't2' => '', 't3' => '',  'img1' => '', 'desc1' => '' ) );
		$radius = empty($instance['radius']) ? '' : $instance['radius'];
		$distance = empty($instance['distance']) ? '' : $instance['distance'];
		
		
?>
    		<p>
			  <label for="<?php echo $this->get_field_id('distance'); ?>"><?php _e('Distance in',T_DOMAIN); ?>:
			   <select id="<?php echo $this->get_field_id('distance'); ?>" name="<?php echo $this->get_field_name('distance'); ?>" style="width:50%;">
				  <option value="Miles" <?php if(esc_attr($distance) == 'Miles'){ echo 'selected="selected"';}?>><?php _e('Miles',T_DOMAIN);?></option>
				  <option value="Kilometer" <?php if(esc_attr($distance) == 'Kilometer'){ echo 'selected="selected"';}?>><?php _e('Kilometer',T_DOMAIN);?></option>
			  </select>
			  </label>
			</p>
			<p>
			  <label for="<?php echo $this->get_field_id('radius'); ?>"><?php _e('Radius',T_DOMAIN); ?>:
			  <input class="widefat" id="<?php echo $this->get_field_id('radius'); ?>" name="<?php echo $this->get_field_name('radius'); ?>" type="text" value="<?php echo esc_attr($radius); ?>" />
			  </label>
			</p>
       
<?php
	}
}
register_widget('footersearchwidget');
// SIDEBAR ADVT WIDGET ENDS


/* =============================== Feedburner Subscribe widget START ====================================== */
if(!class_exists('subscribewidget')){
	class subscribewidget extends WP_Widget {
		function subscribewidget() {
		//Constructor
			$widget_ops = array('classname' => 'widget Newsletter Subscribe', 'description' => __('Newsletter Subscribe Widget',T_DOMAIN) );		
			$this->WP_Widget('widget_subscribewidget', __('T &rarr; Newsletter Subscribe',T_DOMAIN), $widget_ops);
		}
		function widget($args, $instance) {
		// prints the widget
		extract($args, EXTR_SKIP);
		$id = empty($instance['id']) ? '' : apply_filters('widget_id', $instance['id']);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$text = empty($instance['text']) ? '' : apply_filters('widget_text', $instance['text']);
		global $current_user;
	 ?>
    <div class="widget" >
        <div class="news_subscribe">
        <?php if($title){?><h3 class="widget-title"><?php echo $title; ?></h3><?php }?>
        <?php if($text){?><p><?php echo $text; ?></p><?php }?>
        <form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $id; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true" >
          <p>
              <input type="text" name="email" value="<?php if($current_user->user_email) echo $current_user->user_email; else{ _e('Your Email Address',T_DOMAIN); } ?>" class="field" onfocus="if (this.value == 'Your Email Address') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Your Email Address';}"  />
              <input type="hidden" value="<?php echo $id; ?>" name="uri"   />
              <input type="hidden" value="<?php bloginfo('name'); ?>" name="title" />
              <input type="hidden" name="loc" value="en_US"/>
              <input class="replace" type="submit" name="submit" value="<?php _e('Subscribe',T_DOMAIN);?>" />
          </p>
        </form>
        </div>
    </div>
	<?php
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
			$instance = wp_parse_args( (array) $instance, array( 'id' => '', 'title' => 'Subscribe', 'text' => 'Subscribe to our newsletter and get a weekly events schedule right in your inbox. It is free and we promise there will be no spam.', 'name' => '') );		
			$id = strip_tags($instance['id']);
			$title = strip_tags($instance['title']);
			$text = strip_tags($instance['text']);
			
	?>
			<p>
			  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:',T_DOMAIN);?>
			  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			  </label>
			</p>
			<p>
			  <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text Under Title:',T_DOMAIN);?>
			  <input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo esc_attr($text); ?>" />
			  </label>
			</p>
			<p>
			  <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Feedburner ID:',T_DOMAIN);?>
			  <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo esc_attr($id); ?>" />
			  </label>
			</p>


	<?php
		}
	}
	register_widget('subscribewidget');
}
/* =============================== Feedburner Subscribe widget END ====================================== */

// =============================== Feedburner Rssfeed widget ======================================
class rssfeed extends WP_Widget {
	function rssfeed() {
	//Constructor
		$widget_ops = array('classname' => 'widget Rss Feed', 'description' => apply_filters('templ_rssfeed_widget_desc_filter',__('Rss Feed Widget',T_DOMAIN)) );		
		$this->WP_Widget('widget_rssfeed', apply_filters('templ_rssfeed_widget_title_filter',__('T &rarr; Rss Feed',T_DOMAIN)), $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget
		extract($args, EXTR_SKIP);
		$id = empty($instance['id']) ? '' : apply_filters('widget_id', $instance['id']);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$text = empty($instance['text']) ? '' : apply_filters('widget_text', $instance['text']);
?>
    	<div class="widget clearfix" >
        <div class="newsletter">
            <h3> 
             <?php if($title){?><span class="title"><?php echo sprintf(__('%s',T_DOMAIN), $title);?></span> <?php }?> 
              </h3>
            <?php if ( $text <> "" ) { ?>	 
                 <a target="_blank" href="<?php if($id){echo 'http://feeds2.feedburner.com/'.$id;}else{bloginfo('rss_url');} ?>" ><?php echo sprintf(__('%s',T_DOMAIN), $text);?></a>
            <?php } ?>
  			</div>
		  </div>  <!-- #end -->
<?php
	}
	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;		
		$instance['id'] = strip_tags($new_instance['id']);
		$instance['title'] = ($new_instance['title']);
		$instance['text'] = ($new_instance['text']);		
		return $instance;
	}
	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '','id' => '' ) );		
		$id = strip_tags($instance['id']);
		$title = strip_tags($instance['title']);
		$text = strip_tags($instance['text']);
 ?>
 <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:',T_DOMAIN);?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
 <p><label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Feedburner ID (ex :- templatic):',T_DOMAIN);?> <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo esc_attr($id); ?>" /></label></p>
   <p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Short Description:',T_DOMAIN);?> <textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_attr($text); ?></textarea></label></p>
<?php
	}}
register_widget('rssfeed');

// =============================== Primary Menu widget ======================================
class addeventurl extends WP_Widget {
	function addeventurl() {
	//Constructor
		$widget_ops = array('classname' => 'widget Add Event URL', 'description' => apply_filters('templ_addeventurl_widget_desc_filter',__('Add Event URL Widget',T_DOMAIN)) );		
		$this->WP_Widget('widget_addeventurl', apply_filters('templ_addeventurl_widget_title_filter',__('T &rarr; Add Event URL',T_DOMAIN)), $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$url = empty($instance['url']) ? '' : apply_filters('widget_text', $instance['url']);
?>
    
	<?php if ( $url <> "" ) { ?>
			<div id="nav_widget" class="sub_event">
				<ul class="clearfix">
                    <li>
                        <a target="_blank" href="<?php echo $url; ?>" ><?php echo sprintf(__('%s',T_DOMAIN), $title);?></a>
                     </li>
                </ul>
			</div>
    <?php } ?>
		
<?php
	}
	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;		
		$instance['title'] = ($new_instance['title']);
		$instance['url'] = ($new_instance['url']);		
		return $instance;
	}
	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '','id' => '' ) );		
		$title = strip_tags($instance['title']);
		$url = ($instance['url']);
 ?>
 <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:',T_DOMAIN);?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
   <p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('URL:',T_DOMAIN);?> <textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>"><?php echo esc_attr($url); ?></textarea></label></p>
<?php
	}
}
register_widget('addeventurl');

/* header search widget */
class searchwithnavigation extends WP_Widget {
	function searchwithnavigation() {
	//Constructor
		$widget_ops = array('classname' => 'widget searchwithnavigation', 'description' => apply_filters('templ_searchwithnavigation_widget_desc_filter',__('Search widget specially for main nanigaion',T_DOMAIN)) );		
		$this->WP_Widget('widget_searchwithnavigation', apply_filters('templ_searchwithnavigation_widget_title_filter',__('T &rarr; Primary Navigation Search',T_DOMAIN)), $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$post_type = empty($instance['post_type']) ? 'post' : apply_filters('widget_post_type', $instance['post_type']);
		if($post_type){
			$post_type = implode(',',$post_type);
		} 
		$search = '<form method="get" class="search-form" id="search-form' . esc_attr( $this->id_base ) . '" action="' . home_url() . '/"><div>';

			/* If a search label was set, add it. */
			if ( !empty( $instance['title'] ) )
				$search .= '<label for="search-text' . esc_attr( $this->id_base ) . '">' . $instance['title'] . '</label>';

			/* Search form text input. */
			$search .= '<input class="search-text" type="text" name="s" id="search-text' . esc_attr( $this->id_base ) . '" value="' . $title . '" onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;" />';
			$search .='<input type="hidden" name = "search_type" value="'.$post_type.'"/>';
			/* Close the form. */
			$search .= '</div></form>'; 
			echo $before_widget;
			echo $search;
			echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;			
		$instance['title'] = ($new_instance['title']);
		$instance['post_type'] = ($new_instance['post_type']);		
		return $instance;
	}
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'post_type' => '') );		
		$title = strip_tags($instance['title']);
		$post_types = ($instance['post_type']);
		if(empty($post_types)){ $post_types =array('post'); }
 ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'hybrid-core' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Search From', 'hybrid-core' ); ?>:</label>
			<?php
				$custom_post_types = get_post_types($custom_post_types_args,'objects');
				$i = 0;
				foreach ($custom_post_types as $content_type) {
					if($content_type->name!='nav_menu_item' && $content_type->name!='attachment' && $content_type->name!='revision' && $content_type->name!='page')
						{ ?>
							  <label for="post_type_<?php echo $i; ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'post_type' ) ."[]"; ?>" id="post_type_<?php echo $i; ?>" value="<?php echo $content_type->name; ?>" <?php if(in_array($content_type->name,$post_types)) { ?> checked="checked" <?php } ?> />
							  <?php echo $content_type->label;?></label>
				<?php		}						
				}

			?>
		</p>
<?php
	}
}
register_widget('searchwithnavigation');
?>