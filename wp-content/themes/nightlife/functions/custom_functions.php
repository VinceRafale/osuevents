<?php
/*	
name : get_category_dl_options
decription : get a dropdown of categories -- */
function get_category_dl_options($selected)
{
		$cat_args = array('name' => 'scat', 'id' => 'scat', 'selected' => $selected, 'class' => 'select', 'orderby' => 'name', 'echo' => '0', 'hierarchical' => 1, 'taxonomy'=>'ecategory','hide_empty'  => 0);
		$cat_args['show_option_none'] = __('Select Category',T_DOMAIN);
		return wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
}
/*	
name : get_formated_date
decription : get default date format -- */
function get_formated_date($date)
{
	return mysql2date(get_option('date_format'), $date);
}
/*	
name : get_formated_date
decription : get default time format -- */
function get_formated_time($time)
{
	return mysql2date(get_option('time_format'), $time, $translate=true);;
}
/*	
name : bdw_get_images_with_info
description :Function for Getting the custom added size of image -- */
function bdw_get_images_with_info($iPostID,$img_size='thumb') 
{
    $arrImages =& get_children('order=ASC&orderby=menu_order ID&post_type=attachment&post_mime_type=image&post_parent=' . $iPostID );
	
	$return_arr = array();
	if($arrImages) 
	{		
       foreach($arrImages as $key=>$val)
	   {
	   		$id = $val->ID;
			if($img_size == 'large')
			{
				$img_arr = wp_get_attachment_image_src($id,'full');	// THE FULL SIZE IMAGE INSTEAD
				$imgarr['id'] = $id;
				$imgarr['file'] = $img_arr[0];
				$return_arr[] = $imgarr;
			}
			elseif($img_size == 'medium')
			{
				$img_arr = wp_get_attachment_image_src($id, 'medium'); //THE medium SIZE IMAGE INSTEAD
				$imgarr['id'] = $id;
				$imgarr['file'] = $img_arr[0];
				$return_arr[] = $imgarr;
			}
			elseif($img_size == 'thumb')
			{
				$img_arr = wp_get_attachment_image_src($id, 'thumbnail'); // Get the thumbnail url for the attachment
				$imgarr['id'] = $id;
				$imgarr['file'] = $img_arr[0];
				$return_arr[] = $imgarr;
			}
			elseif($img_size == 'detail_page_image')
			{
				$img_arr = wp_get_attachment_image_src($id, 'detail_page_image'); // Get the thumbnail url for the attachment
				$imgarr['id'] = $id;
				$imgarr['file'] = $img_arr[0];
				$return_arr[] = $imgarr;
			}
	   }
	  return $return_arr;
	}
}

/*	
name : recent_comments
description :Function for getting recent comments -- */
function recent_comments($g_size = 30, $no_comments = 10, $comment_lenth = 60, $show_pass_post = false) {
        global $wpdb, $tablecomments, $tableposts,$rating_table_name;
		$tablecomments = $wpdb->comments;
		$tableposts = $wpdb->posts;
		$request = "SELECT ID, comment_ID, comment_content, comment_author,comment_post_ID, comment_author_email FROM $tableposts, $tablecomments WHERE $tableposts.ID=$tablecomments.comment_post_ID AND post_status = 'publish' and $tableposts.post_type='".CUSTOM_POST_TYPE_EVENT."'";

        if(!$show_pass_post) { $request .= "AND post_password ='' "; }

        $request .= "AND comment_approved = '1' ORDER BY $tablecomments.comment_date DESC LIMIT $no_comments";
        $comments = $wpdb->get_results($request);

        foreach ($comments as $comment) {
		$comment_id = $comment->comment_ID;
		$comment_content = strip_tags($comment->comment_content);
		$comment_excerpt = mb_substr($comment_content, 0, $comment_lenth)."";
		$permalink = get_permalink($comment->ID)."#comment-".$comment->comment_ID;
		$comment_author_email = $comment->comment_author_email;
		$comment_post_ID = $comment->comment_post_ID;
		$post_title = get_the_title($comment_post_ID);
		$permalink = get_permalink($comment_post_ID);
		
		echo '<li class="clearfix">';
		echo "<span class=\"li".$comment_id."\">";
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
			if(is_active_addons('templatic_ratings')):
				$post_rating = $wpdb->get_var("select rating_rating from $rating_table_name where comment_id=\"$comment_id\"");
				echo draw_rating_star_plugin($post_rating);
			endif;
 			echo "<a class=\"comment_excerpt\" href=\"" . $permalink . "\" title=\"View the entire comment\">";
			echo $comment_excerpt;
			echo "</a>";
			
			echo '</li>';

	            }

}



/*	Function to add theme color settings options in wordpress customizer START	*/
	function nightlife_register_customizer_settings($wp_customize){
		/*	Add Settings START */
			
			$wp_customize->add_setting('supreme_theme_settings[color_picker_color1]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"nightlife_customize_supreme_color1",
				'sanitize_js_callback' => 	"nightlife_customize_supreme_color1",
				//'transport' => 'postMessage',
			));
			
			$wp_customize->add_setting('supreme_theme_settings[color_picker_color2]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"nightlife_customize_supreme_color2",
				'sanitize_js_callback' => 	"nightlife_customize_supreme_color2",
				//'transport' => 'postMessage',
			));
			
			$wp_customize->add_setting('supreme_theme_settings[color_picker_color3]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"nightlife_customize_supreme_color3",
				'sanitize_js_callback' => 	"nightlife_customize_supreme_color3",
				//'transport' => 'postMessage',
			));
			
			$wp_customize->add_setting('supreme_theme_settings[color_picker_color4]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"nightlife_customize_supreme_color4",
				'sanitize_js_callback' => 	"nightlife_customize_supreme_color4",
				//'transport' => 'postMessage',
			));
			
			$wp_customize->add_setting('supreme_theme_settings[color_picker_color5]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"nightlife_customize_supreme_color5",
				'sanitize_js_callback' => 	"nightlife_customize_supreme_color5",
				//'transport' => 'postMessage',
			));
			
			$wp_customize->add_setting('supreme_theme_settings[color_picker_color6]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"nightlife_customize_supreme_color6",
				'sanitize_js_callback' => 	"nightlife_customize_supreme_color6",
				//'transport' => 'postMessage',
			));
			
		/* Add Control START */	
		/*
			Primary: 	 Effect on buttons, links and main headings.
			Secondary: 	 Effect on sub-headings.
			Content: 	 Effect on content.
			Sub-text: 	 Effect on sub-texts.
			Background:  Effect on body & menu background. 
		
		*/
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_picker_color1', array(
				'label'   => __( 'Primary', T_DOMAIN),
				'section' => 'colors',
				'settings'   => 'supreme_theme_settings[color_picker_color1]',
			) ) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_picker_color2', array(
				'label'   => __( 'Secondary: buttons, headings', T_DOMAIN ),
				'section' => 'colors',
				'settings'   => 'supreme_theme_settings[color_picker_color2]',
			) ) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_picker_color3', array(
				'label'   => __( 'Content:', T_DOMAIN ),
				'section' => 'colors',
				'settings'   => 'supreme_theme_settings[color_picker_color3]',
			) ) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_picker_color4', array(
				'label'   => __( 'Sub-text:', T_DOMAIN ),
				'section' => 'colors',
				'settings'   => 'supreme_theme_settings[color_picker_color4]',
			) ) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_picker_color5', array(
				'label'   => __( 'Body Background:', T_DOMAIN ),
				'section' => 'colors',
				'settings'   => 'supreme_theme_settings[color_picker_color5]',
			) ) );
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_picker_color6', array(
				'label'   => __( 'Header & Footer Background:', T_DOMAIN ),
				'section' => 'colors',
				'settings'   => 'supreme_theme_settings[color_picker_color6]',
			) ) );
			$wp_customize->remove_control('background_color');
	}		

	/*  Handles changing settings for the live preview of the theme START.  */	
	function nightlife_customize_supreme_color1( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[color_picker_color1]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "nightlife_customize_supreme_color1", $setting, $object );
	}	
	function nightlife_customize_supreme_color2( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[color_picker_color2]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "nightlife_customize_supreme_color2", $setting, $object );
	}	
	function nightlife_customize_supreme_color3( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[color_picker_color3]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "nightlife_customize_supreme_color3", $setting, $object );
	}	
	function nightlife_customize_supreme_color4( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[color_picker_color4]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "nightlife_customize_supreme_color4", $setting, $object );
	}
	function nightlife_customize_supreme_color5( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[color_picker_color5]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "nightlife_customize_supreme_color5", $setting, $object );
	}
	
	function nightlife_customize_supreme_color6( $setting, $object ) {
		
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( "supreme_theme_settings[color_picker_color6]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* Return the sanitized setting and apply filters. */
		return apply_filters( "nightlife_customize_supreme_color6", $setting, $object );
	}


/*	Function to add theme color settings options in wordpress customizer END	*/







/*
Name :templatic_display_views
Disscription : to display the views selction button   
*/
function templatic_display_views(){ 
		
	global $wpdb,$wp_query;	
	$current_term = $wp_query->get_queried_object();	
	if(!is_home() && !is_search())
	{ 	
		if(!is_tax() && is_archive())
		{			
			$post_type=(get_post_type()!='')? get_post_type() : get_query_var('post_type');
			$permalink = get_post_type_archive_link($post_type);
		}
		else{
			$permalink =  get_term_link( $current_term->slug, $current_term->taxonomy );			
		}
		if(strstr($permalink,'?'))
		{
			$upcoming= $permalink."&amp;etype=upcoming";
			$current= $permalink."&amp;etype=current";
			$past= $permalink."&amp;etype=past";
		}else
		{ 
			$upcoming = $permalink."?etype=upcoming";
			$current= $permalink."?etype=current";
			$past= $permalink."?etype=past";
		}	
		
		
	}else
	{			
		$permalink= home_url();		
		if(strstr($permalink,'?'))
		{
			$upcoming= $permalink."&amp;etype=upcoming";
			$current= $permalink."&amp;etype=current";
			$past= $permalink."&amp;etype=past";
		}else
		{ 
			$upcoming = $permalink."?etype=upcoming";
			$current= $permalink."?etype=current";
			$past= $permalink."?etype=past";
		}
		
		
	}	
	
	
	$_REQUEST['etype']=!isset($_REQUEST['etype'])?'current':$_REQUEST['etype'];
	$upcoming_active=(isset($_REQUEST['etype']) && $_REQUEST['etype'] =='upcoming')?'active':'';
	$current_active=(isset($_REQUEST['etype']) && $_REQUEST['etype'] =='current')?'active':'';
	$past_active=(isset($_REQUEST['etype']) && $_REQUEST['etype'] =='past')?'active':'';		
	
	if((!is_search() && is_home()) ||  CUSTOM_POST_TYPE_EVENT=='event' || get_post_type()== CUSTOM_POST_TYPE_EVENT){
		?>
          <div class='smart_tab clearfix'>
                    <p class='left'>
                         <a class='first listview <?php echo $past_active;?>' href="<?php echo $past;?>"><?php _e('PAST EVENTS',T_DOMAIN);?></a>				
                         <a class='second gridview <?php echo $current_active;?>' href="<?php echo $current;?>"><?php _e('CURRENT EVENTS',T_DOMAIN);?></a>
                         <a class='last gridview <?php echo $upcoming_active;?>' href="<?php echo $upcoming;?>"><?php _e('UPCOMING EVENTS',T_DOMAIN);?></a>
                    </p>
                    
                    <p class='right viewsbox'>
                         <a class='switcher first gridview' id='gridview' href='#'><?php _e('GRID VIEW',T_DOMAIN);?></a>
                         <a class='switcher last listview active' id='listview' href='#'><?php _e('LIST VIEW',T_DOMAIN);?></a>
                    </p>		
           </div>

      <?php
	 }
}
 /*
Name :nightlife_script
Disscription : load js in footer  
*/
add_action('wp_footer', 'nightlife_script');
		
function nightlife_script(){
	wp_enqueue_script('script', get_stylesheet_directory_uri()."/js/script.js");
	wp_enqueue_script('cokkies', get_stylesheet_directory_uri()."/js/cookie.js");
	wp_enqueue_script('placeholder', get_stylesheet_directory_uri()."/js/modernizr.js");
}

/*
 * Add action for set the single post type breadcrumb
 */
add_action('templ_before_single_container_breadcrumb','single_post_type_breadcrumb');

/*
 * display the bread crumb
 * Function Name:single_post_type_breadcrumb 
 */
function single_post_type_breadcrumb()
{
	the_breadcrumb();	
}
/* Add action function before post title for remove details page custom field and image gallery for event */
add_action('templ_before_post_title','remove_add_action');
function remove_add_action()
{
	global $post;	
	if(get_post_type()==CUSTOM_POST_TYPE_EVENT)
	{
		/*
		 * Remove action for detail custom field collection
		 */
		remove_action('tmpl_detail_page_custom_fields_collection','detail_fields_colletion');
		
		/*
		 * Remove action for single post image gallery
		 */
		remove_action('tmpl_detail_page_image_gallery','single_post_image_gallery');
		/*Remove view counter filter on the_content */
		remove_filter( 'the_content','view_count');
		/*Remove action listing page custom field for event post type */
		remove_action('templ_listing_custom_field','templ_custom_field_display');
		/* Remove Sharing buttons */
		remove_filter('the_content','view_sharing_buttons');
	}
}

/*
 * Add Action for Display the map and gallery before the post content
 */
add_action('templ_before_post_content','templ_map_gallery_tab_before_post_content');
/*
 * Function Name: templ_map_gallery_tab
 * Return: display the tab for google map and post image gallery
 */
function templ_map_gallery_tab_before_post_content()
{
	global $post,$single_htmlvar_name;
	
	if(is_single() && get_post_type()==CUSTOM_POST_TYPE_EVENT):		
		if($single_htmlvar_name['post_images'])
		{
			if(tmpl_is_parent($post)){	
				$post_img = bdw_get_images_plugin($post->post_parent,'large');
				$post_img_thumb = bdw_get_images_plugin($post->post_parent,'thumbnail');
				if($single_htmlvar_name['address'])
				{
					$geo_latitude = get_post_meta($post->post_parent,'geo_latitude',true);
					$geo_longitude = get_post_meta($post->post_parent,'geo_longitude',true);
					$address = get_post_meta($post->post_parent,'address',true);
					$map_type =get_post_meta($post->post_parent,'map_view',true);			
				}
			}else{
				$post_img = bdw_get_images_plugin($post->ID,'large');
				$post_img_thumb = bdw_get_images_plugin($post->ID,'thumbnail');
				if($single_htmlvar_name['address'])
				{
					$geo_latitude = get_post_meta($post->ID,'geo_latitude',true);
					$geo_longitude = get_post_meta($post->ID,'geo_longitude',true);
					$address = get_post_meta($post->ID,'address',true);
					$map_type =get_post_meta($post->ID,'map_view',true);			
				}
			}
			$post_images = $post_img[0]['file'];
			$attachment_id = $post_img[0]['id'];
			$attach_data = get_post($attachment_id);
			$img_title = $attach_data->post_title;
			$img_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
			
			 
			$post_images_thumb = $post_img_thumb[0]['file'];
			$attachment_id1 = $post_img_thumb[0]['id'];
			$attach_idata = get_post($attachment_id1);
			$post_img_title = $attach_idata->post_title;
			$post_img_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);	
		}		
									
		?>        
    	<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
		<script type="text/javascript">
			var map;   
			jQuery(document).ready(function($) {
			var latLng = new google.maps.LatLng(<?php echo $geo_latitude;?>, <?php echo $geo_longitude;?>);
			Demo.map = new google.maps.Map(Demo.mapContainer, {
			<?php
			if(get_option('ptthemes_scale_factor')){
			$ptthemes_scale_factor = get_option('ptthemes_scale_factor');
			} else {
			$ptthemes_scale_factor = 13;
			}
			?>
			zoom: <?php echo $ptthemes_scale_factor;?>,
			center: latLng,
			<?php
			if($map_type=='Road Map' || $map_type=='Satellite Map'|| $map_type=='Terrain Map'){
			if($map_type=='Satellite Map') {
			$map_type = SATELLITE;
			} elseif($map_type=='Terrain Map') {
			$map_type = TERRAIN;
			} else {
			$map_type = ROADMAP;
			}
			?>
			mapTypeId: google.maps.MapTypeId.<?php echo $map_type;?>
			<?php
			} else {
			?>
			mapTypeId: google.maps.MapTypeId.ROADMAP
			<?php
			}
			?>
			});
			var marker = new google.maps.Marker({
			position: latLng,
			map: Demo.map,
			title:"<?php echo trim($post->post_title);?>"
			});

			$(function() {
			$("#tabs").tabs({
			activate: function(e, ui) {
			var center = Demo.map.getCenter();
			google.maps.event.trigger(Demo.map, "resize");
			Demo.map.setCenter(center);
			}
			});
			});
			});
		</script>	
		<style type="text/css">
		.ui-tabs .ui-tabs-hide {
			 display: none;
		}
		</style>	        
          <div id="tabs">
        	<span class="share_link">           
        <?php	
				$url = 'http://www.facebook.com/sharer.php?u=' . rawurlencode(get_permalink($post->ID)) . '&amp;t=' . rawurlencode($post->post_title);				
				$tmpdata = get_option('templatic_settings');
				$facebook_share_detail_page =  @$tmpdata['facebook_share_detail_page']; 
				$google_share_detail_page =  @$tmpdata['google_share_detail_page'];
				$twitter_share_detail_page =  @$tmpdata['twitter_share_detail_page'];
				if($facebook_share_detail_page=='yes')
				{
					$title=urlencode($post->post_title);
					$url=urlencode(get_permalink($post->ID));
					$summary=urlencode(htmlspecialchars($post->post_content));
					$image=urlencode($post_images);
					?>
                    <a onClick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo $title;?>&amp;p[summary]=<?php echo $summary;?>&amp;p[url]=<?php echo $url; ?>&amp;&amp;p[images][0]=<?php echo $image;?>','sharer','toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)" id="facebook_share_button">Facebook Share.</a>
                <?php
				}// Finish facebook share detail page if condition
				?>
				<!-- Place this tag where you want the +1 button to render. -->
                <?php if($google_share_detail_page =='yes'):?>                
                    <div class="g-plus" data-action="share" data-annotation="bubble"></div>                                                              
                    <!-- Place this tag after the last +1 button tag. -->                
               	<?php endif;?>
                
                <?php if($twitter_share_detail_page=='yes'):?>
                    <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-text="<?php echo $post->post_content;?>" data-url="<?php echo get_permalink($post->ID); ?>" data-counturl="<?php echo get_permalink($post->ID); ?>">Tweet</a>
   					 <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            <?php endif;?>
			</span>
            <ul>
            	<?php  if($geo_latitude && $geo_longitude ):?>
                <li><a href="#location_map"><?php _e('Location Map',T_DOMAIN);?></a></li>
                <?php endif;?>
                <?php if($post_images!=""):?>
                <li><a href="#image_gallery"><?php _e('Image Gallery',T_DOMAIN);?></a></li>		
                <?php endif;?>
            </ul>
            <?php if($geo_latitude && $geo_longitude ):?>
            <!-- Location Map-->
            <div id="location_map">
                <div class="google_map" id="detail_google_map_id"> 
				<?php include_once ('google_map_detail.php');?> 
                </div>  <!-- google map #end -->
            </div>
            <?php endif;?>
            <?php if($single_htmlvar_name['post_images'] && $post_images!=""):?>
            <!--Image Gallery Start -->
            <div id="image_gallery">               
               		<div class="row">
					
                         <div class="image_content_details">
                             <div class="graybox">
                                    <?php
									if(tmpl_is_parent($post)){	
											get_the_image(array('post_id'=> $post->post_parent,'link_to_post'=>'false','size'=>'large','image_class'=>'post_img img listimg','default_image'=>get_stylesheet_directory_uri()."/images/img_not_available.png"));
									}else{
											get_the_image(array('post_id'=> get_the_ID(),'link_to_post'=>'false','size'=>'large','image_class'=>'post_img img listimg','default_image'=>get_stylesheet_directory_uri()."/images/img_not_available.png"));
									}
									?>
                             </div>
                         </div>            
  
                    <div class="row image_title_space">
                        <?php if(count($post_images)>0): ?>                           
                            <div id="gallery">
                                <ul class="more_photos">
                                    <?php for($im=0;$im<count($post_img_thumb);$im++): ?>
                                    <li>
                                        <a href="<?php echo $post_img[$im]['file'];?>" title="<?php echo $img_title; ?>">
                                            <img src="<?php echo $post_img_thumb[$im]["file"];?>" height="70" width="70"  title="<?php echo $img_title; ?>" alt="<?php echo $img_alt; ?>" />
                                       </a>
                                    </li>
                                    <?php endfor; ?>
                                </ul>
                           </div>     
                        <?php endif;?>
                     </div>
                 </div>    
            </div><!--Finish image gallery -->    
            <?php endif;?>        
        </div>
    <?php
	endif;
}

/*
 * Add actio n after the post tilte
 */
add_action('templ_after_post_title','add_to_my_calendar',10);
/*
 * Function Name: add_to_my_calendar
 * Return : display the add to my calendar in sigle page
 */
function add_to_my_calendar()
{
	global $post;
	if(is_single() && get_post_type()==CUSTOM_POST_TYPE_EVENT):
	?>
		<script src="<?php echo get_stylesheet_directory_uri().'/js/add_to_cal.js'?>"></script>
        <?php
        $args=array('outlook'=>1,'google_calender'=>1,'yahoo_calender'=>1,'ical_cal'=>1);
        $icalurl = get_event_ical_info($post->ID);	
        //wp enqueue script('addtocalender',get_stylesheet_directory_uri().'/js/add_to_cal.js');
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
         
                jQuery(".addtocalendar").hide();
                jQuery(".calendar_show").show();
         
            jQuery('.calendar_show').click(function(){
                jQuery(".addtocalendar").slideToggle();
            });	 
        });	
        </script>
        <div class="calendar">
            <a href="#" class="calendar_show"><?php _e('Add to my calendar',T_DOMAIN);?></a>     
            <div class="addtocalendar">
                <ul>
                <?php if($args['outlook']){?><li class="i_calendar"><a href="<?php echo $icalurl['ical']; ?>"> <?php _e('Outlook Calendar',T_DOMAIN);?></a> </li><?php }?>
                <?php if($args['google_calender']){?><li class="i_google"><a href="<?php echo $icalurl['google']; ?>" target="_blank"> <?php _e('Google Calendar',T_DOMAIN);?> </a> </li><?php }?>
                <?php if($args['yahoo_calender']){?><li class="i_yahoo"><a href="<?php echo $icalurl['yahoo']; ?>" target="_blank"><?php _e('Yahoo! Calendar',T_DOMAIN);?></a> </li><?php }?>
                <?php if($args['ical_cal']){?><li class="i_calendar"><a href="<?php echo $icalurl['ical']; ?>"> <?php _e('iCal Calendar',T_DOMAIN);?> </a> </li><?php }?>
                </ul>
            </div>
        </div>
    <?php
	endif;
}
/*
 * Function Name: templ_listing_page_post_info
 * Return: display the post published date
 */
function templ_listing_page_post_info()
{
	global $post;
	if(is_archive())
	{	
		wp_reset_postdata();
		$start_date=get_post_meta($post->ID,'st_date',true);
		
		if($start_date!="")
		{
			echo '<h2 class="date">' . date('d',strtotime($start_date)) . '<span>'.date_i18n('M',strtotime($start_date)).'</span></h2>';		
		}
		else
		{
			echo '<h2 class="date">' . sprintf( get_the_time( esc_attr__( 'd') ) ) . '<span>'.sprintf( get_the_time( esc_attr__( 'M') ) ).'</span></h2>';		
		}
	}
}
/*Add action after listing post title for display event address */
add_action('templ_after_post_title','templ_event_listing_page_address');
function templ_event_listing_page_address()
{
	global $post;
	if(is_archive())
	{
		if(tmpl_is_parent($post)){ 
			$address=get_post_meta($post->post_parent,'address',true);
		}else{ $address=get_post_meta($post->ID,'address',true); }
		
		if($address)
		 {
			echo "<span class='address'>".$address."</span>";
		 }
	}
}

/* add action after post content for display the category and tag information */
add_action('templ_after_post_content','templ_after_post_content_category');
function templ_after_post_content_category()
{
	global $post,$htmlvar_name;		
	if(is_archive() && get_post_type()==CUSTOM_POST_TYPE_EVENT)
	{	if($htmlvar_name['st_date'])	
			$st_date = date('M d, Y',strtotime(get_post_meta($post->ID,'st_date',true)));
		if($htmlvar_name['end_date'])
			$end_date = date('M d, Y',strtotime(get_post_meta($post->ID,'end_date',true)));
		if($end_date && $st_date && strtotime(get_post_meta($post->ID,'st_date',true)) < strtotime(get_post_meta($post->ID,'end_date',true))){	 /* if st date and end date both are set */
			$event_date = date_i18n(get_option('date_format'),strtotime(get_post_meta($post->ID,'st_date',true))).' to '.date_i18n(get_option('date_format'),strtotime(get_post_meta($post->ID,'end_date',true)));
		}else if(($st_date && !$end_date) || (strtotime(get_post_meta($post->ID,'st_date',true)) == strtotime(get_post_meta($post->ID,'end_date',true)))){ /* if only st date is set or st date is less the or equal to end date*/
			$event_date = date_i18n(get_option('date_format'),strtotime(get_post_meta($post->ID,'st_date',true)));				
		}else{
			$event_date = date_i18n(get_option('date_format'),strtotime(get_post_meta($post->ID,'st_date',true))).' to '.date_i18n(get_option('date_format'),strtotime(get_post_meta($post->ID,'end_date',true)));
		}
		?>
		<?php if(($htmlvar_name['st_date'] && $htmlvar_name['end_date']) || ($htmlvar_name['st_time'] && $htmlvar_name['end_time'])):?>	
               <p class="date"> <?php if($htmlvar_name['st_date'] && $htmlvar_name['end_date']):?><span><?php _e('Date',T_DOMAIN);?> : </span> <?php echo $event_date; ?><br><?php endif;?>
				<?php if($htmlvar_name['st_time'] && $htmlvar_name['end_time']):?>
                     <span><?php _e('Timing',T_DOMAIN);?> : </span> <?php echo get_post_meta($post->ID,'st_time',true).' to '.get_post_meta($post->ID,'end_time',true);?>	
                    <?php endif;?>
                </p>	
           <?php endif;?>
        <?php						
	}
}
/* Add action for display the taxonomy page custom field */
add_action('templ_listing_custom_field','templ_event_category_custom_field',10,2);
function templ_event_category_custom_field($custom_field,$pos_title)
{
	global $post,$wpdb;		
	if(is_archive() && get_post_type()==CUSTOM_POST_TYPE_EVENT)
	{
	?>
     <div class="postmetadata">
        <ul>
		<?php $i=0; 
          if($custom_field)
          foreach($custom_field as $key=> $_htmlvar_name):?>
          	<?php if($key!='st_date' && $key!='end_date' && $key!='st_time' && $key!='end_time'):?>
                    <?php if($_htmlvar_name == 'multicheckbox' && get_post_meta($post->ID,$key,true) !=''): ?>
                         <li><label><?php echo $pos_title[$i]; ?></label> : <span><?php echo implode(",",get_post_meta($post->ID,$key,true)); ?></span></li>
                    <?php else: 
                         if(get_post_meta($post->ID,$key,true) !=''):
                         ?>
                         <li><label><?php echo $pos_title[$i]; ?></label> : <span><?php echo get_post_meta($post->ID,$key,true); ?></span></li>
                    <?php endif; ?>
                    <?php endif; ?>
               <?php endif;?>
          <?php $i++; endforeach; ?>
        </ul>
     </div>
     <?php	
	}
}

/*
 * Function Name:get_event_ical_info
 * Return : show the display add to calendar 
 */
function get_event_ical_info($post_id) {	
	require_once(TEMPLATE_FUNCTION_FOLDER_PATH.'ical/iCalcreator.class.php');
	$cal_post = get_post($post_id);
	if ($cal_post) {
		$location = get_post_meta($post_id,'address',true);
		$start_year = date('Y',strtotime(get_post_meta($post_id,'st_date',true)));
		$start_month = date('m',strtotime(get_post_meta($post_id,'st_date',true)));
		$start_day = date('d',strtotime(get_post_meta($post_id,'st_date',true)));
		
		$end_year = date('Y',strtotime(get_post_meta($post_id,'end_date',true)));
		$end_month = date('m',strtotime(get_post_meta($post_id,'end_date',true)));
		$end_day = date('d',strtotime(get_post_meta($post_id,'end_date',true)));
		
		$start_time = get_post_meta($post_id,'st_time',true);
		$end_time = get_post_meta($post_id,'end_time',true);
		if (($start_time != '') && ($start_time != ':')) { $event_start_time = explode(":",$start_time); }
		if (($end_time != '') && ($end_time != ':')) { $event_end_time = explode(":",$end_time); }
		
		$post_title = get_the_title($post_id);
		$v = new vcalendar();                          
		$e = new vevent();  
		$e->setProperty( 'categories' , 'Events' );                   
		
		if (isset( $event_start_time)) { @$e->setProperty( 'dtstart' 	,  @$start_year, @$start_month, @$start_day, @$event_start_time[0], @$event_start_time[1], 00 ); } else { $e->setProperty( 'dtstart' ,  $start_year, $start_month, $start_day ); } // YY MM dd hh mm ss
		if (isset($event_end_time)) { @$e->setProperty( 'dtend'   	,  $end_year, $end_month, $end_day, $event_end_time[0], $event_end_time[1], 00 );  } else { $e->setProperty( 'dtend' , $end_year, $end_month, $end_day );  } // YY MM dd hh mm ss
		$e->setProperty( 'description' 	, strip_tags($cal_post->post_excerpt) ); 
		if (isset($location)) { $e->setProperty( 'location'	, $location ); } 
		$e->setProperty( 'summary'	, $post_title );                 
		$v->addComponent( $e );                        
	
		$templateurl = get_stylesheet_directory_uri().'/cache/';
		$home = home_url();
		$dir = str_replace($home,'',$templateurl);
		$dir = str_replace('/wp-content/','wp-content/',$dir);
		
		$v->setConfig( 'directory', $dir ); 
		$v->setConfig( 'filename', 'event-'.$post_id.'.ics' ); 
		$v->saveCalendar(); 
		////OUT LOOK & iCAL URL//
		$output['ical'] = $templateurl.'event-'.$post_id.'.ics';
		////GOOGLE URL//
		$google_url = "http://www.google.com/calendar/event?action=TEMPLATE";
		$google_url .= "&text=".urlencode($post_title);
		if (isset($event_start_time) && isset($event_end_time)) { 
			$google_url .= "&dates=".@$start_year.@$start_month.@$start_day."T".str_replace('.','',@$event_start_time[0]).str_replace('.','',@$event_start_time[1])."00/".@$end_year.@$end_month.@$end_day."T".str_replace('.','',@$event_end_time[0]).str_replace('.','',@$event_end_time[1])."00"; 

		} else { 
			$google_url .= "&dates=".$start_year.$start_month.$start_day."/".$end_year.$end_month.$end_day; 
		}
		
		$google_url .= "&sprop=website:".$home;
		$google_url .= "&details=".strip_tags($cal_post->post_excerpt);
		if (isset($location)) { $google_url .= "&location=".$location; } else { $google_url .= "&location=Unknown"; }
		$google_url .= "&trp=true";
		$output['google'] = $google_url;
		////YAHOO URL///
		$yahoo_url = "http://calendar.yahoo.com/?v=60&view=d&type=20";
		$yahoo_url .= "&title=".str_replace(' ','+',$post_title);
		if (isset($event_start_time)) 
		{ 
			$yahoo_url .= "&st=".@$start_year.@$start_month.@$start_day."T".@$event_start_time[0].@$event_start_time[1]."00"; 
		}
		else
		{ 
			$yahoo_url .= "&st=".$start_year.$start_month.$start_day;
		}
		if(isset($event_end_time))
		{
			//$yahoo_url .= "&dur=".$event_start_time[0].$event_start_time[1];
		}
		$yahoo_url .= "&desc=".__('For+details,+link+').get_permalink($post_id).' - '.str_replace(' ','+',strip_tags($cal_post->post_excerpt));
		$yahoo_url .= "&in_loc=".str_replace(' ','+',$location);
		$output['yahoo'] = $yahoo_url;
	}
	return $output;
} 
 
add_action('templ_after_post_title','event_meta_after_title',11);
/*
 * Function Name: post_meta_after_title
 * Return : display the event details
 */
function event_meta_after_title()
{	
	global $post,$wpdb,$claim_db_table_name ,$single_htmlvar_name;		
	if(get_post_type()==CUSTOM_POST_TYPE_EVENT && is_single()):	
		if(tmpl_is_parent($post)){
			if($single_htmlvar_name['st_time'])
				$st_time=get_post_meta($post->post_parent,'st_time',true);
			if($single_htmlvar_name['end_time'])
				$en_time=get_post_meta($post->post_parent,'end_time',true);
			$address = get_post_meta($post->post_parent,'address',true);
			$website = get_post_meta($post->post_parent,'website',true);
			$phone = get_post_meta($post->post_parent,'phone',true);
			$email = get_post_meta($post->post_parent,'email',true);
		}else{
			if($single_htmlvar_name['st_time'])
				$st_time=get_post_meta($post->ID,'st_time',true);
			if($single_htmlvar_name['end_time'])
				$en_time=get_post_meta($post->ID,'end_time',true);
			$address = get_post_meta($post->ID,'address',true);
			$website = get_post_meta($post->ID,'website',true);
			$phone = get_post_meta($post->ID,'phone',true);
			$email = get_post_meta($post->ID,'email',true);
		}
		
		?>
		<div class="event_detail clearfix">
			<div class="col1">
				<?php if(get_post_meta($post->ID,'st_date',true)!="" && $single_htmlvar_name['st_date']):?><p class="date"><span><?php _e('STARTING DATE',T_DOMAIN)?></span><?php echo date_i18n(get_option('date_format'),strtotime(get_post_meta($post->ID,'st_date',true)));?></p><?php endif;?>
				<?php if(get_post_meta($post->ID,'end_date',true)!="" && $single_htmlvar_name['end_date']):?><p class="date"><span><?php _e('ENDING DATE',T_DOMAIN)?></span><?php echo date_i18n(get_option('date_format'),strtotime(get_post_meta($post->ID,'end_date',true)));?></p><?php endif;?>
			    <?php if($st_time!="" && $en_time!="" ):?> <p class="time"><span><?php _e('TIME',T_DOMAIN)?></span><?php echo $st_time." - ".$en_time;?></p><?php endif;?>
                <?php if($website !="" && $single_htmlvar_name['website']):?><p class="website"><span><?php _e('WEBSITE',T_DOMAIN)?></span><?php echo $website; ?></p><?php endif;?>
			</div>
			<div class="col2">
				<?php if($address!="" && $single_htmlvar_name['address']):?><p class="location"><span><?php _e('LOCATION',T_DOMAIN)?></span><?php echo $address;?></p><?php endif;?>
				<?php if($phone !="" && $single_htmlvar_name['phone']):?><p class="phone"><span><?php _e('PHONE',T_DOMAIN)?></span><?php echo $phone; ?></p><?php endif;?>
				<?php if($email !="" && $single_htmlvar_name['email']):?><p class="email"><span><?php _e('EMAIL',T_DOMAIN)?></span><?php echo $email; ?></p><?php endif;?>
				<?php
				$prd_id =  get_post_meta($post->ID,'templ_event_ticket',true);
				$booked_tckt_id =  get_post_meta($post->ID,'templ_event_ticket_booked',true);
				$total_tickets = get_post_meta($prd_id,'_stock',true);
				if(get_post_meta($prd_id,'_stock',true) && is_plugin_active('woocommerce/woocommerce.php')){
					$event_tckt_id = "<a href=".get_permalink($prd_id).">".$total_tickets."</a>";
					echo "<p class='ticket'>";
					 echo $event_tckt_id; _e(' tickets are available.',T_DOMAIN);
					 echo "<a href=".get_permalink($prd_id)." class='bookn_tab'>".__(' Book now',T_DOMAIN)."</a>";
					echo "</p>";
				}
				?>
            </div>    
		</div>
		<?php

		$event_type = get_post_meta($post->ID,'event_type',true);	
					
		$recurrence_occurs = get_post_meta($post->ID,'recurrence_occurs',true);
		/* Recurring Event  */
		if(trim(strtolower($event_type)) == trim(strtolower('Recurring event')) && !tmpl_is_parent($post))
		{
			?>
            <script type="text/javascript">
			function show_recurring_event(type)
			{
				if(type == 'show')
				{
					document.getElementById("show_recurring").style.display = 'none';
					document.getElementById("hide_recurring").style.display = '';
					document.getElementById("recurring_events").style.display = 'block';
				}
				else if(type == 'hide')
				{
					document.getElementById("show_recurring").style.display = '';
					document.getElementById("hide_recurring").style.display = 'none';
					document.getElementById("recurring_events").style.display = 'none';
				}
				return true;
			}
			</script>
			
			<div id="show_recurring"  onclick="return show_recurring_event('show');" ><button class="reverse"><?php echo sprintf(__('Show %s occurences',T_DOMAIN), $recurrence_occurs);  ?></button></div>
			<div id="hide_recurring" style="display:none;" onclick="return show_recurring_event('hide');" ><button class="reverse"><?php echo sprintf(__('Hide %s occurences',T_DOMAIN), $recurrence_occurs);  ?></button></div>
            <div id="recurring_events" style="display:none;" class="recurring_info">
           		<?php echo recurrence_event($post->ID);?>
            </div>
    	<?php
		}// Finish the recurring event if condition
		
		/* Regular Event  */

		if(trim(strtolower($event_type)) == trim(strtolower('Regular event')))
		{	
		?>
			<div class="attending_event clearfix"> 
				<?php 
					
					echo attend_event_html($post->post_author,$post->ID);
		?>
            </div>  
		<?php // Finish regular event if condition
		}
	endif;	
}


/*
 * Add action for display the event organized after the post content
 */
add_action('templ_after_post_content','event_custom_fields');
function event_custom_fields()
{
	$post_type=get_post_type();
	$heading_type = fetch_heading_per_post_type(get_post_type());
	
	if(count($heading_type) > 0)
	{
		foreach($heading_type as $_heading_type)
		{	
			$custom_metaboxes[$_heading_type] = get_post_custom_fields_templ_plugin($post_type,'','',$_heading_type);//custom fields for custom post type..
		}
	}	
	global $post,$single_htmlvar_name,$single_pos_title;
	if($post->post_type==CUSTOM_POST_TYPE_EVENT && is_single()):
		$i=0;
		$j=0;
		if(is_array($single_htmlvar_name)):
		echo '<div class="single_custom_field">';		
		foreach($custom_metaboxes['[#taxonomy_name#]'] as $key=> $_htmlvar_name):
		
			if($key!="st_date" && $key!="end_date" && $key!="end_date" && $key!="st_time" && $key!="end_time" && $key!="event_type" && $key!="phone" && $key!="email" && $key!="website" && $key!="twitter" && $key!="facebook" && $key!="video" && $key!="organizer_name" && $key!="organizer_email" && $key!="organizer_logo" && $key!="organizer_address" && $key!="organizer_contact" && $key!="organizer_website" && $key!="organizer_mobile" && $key!="organizer_desc" && $key!="post_images" && $key!="org_info" && $key!="address" && $key!="map_view" && $key!="reg_desc" && $key!="post_content" && $key!="post_excerpt" &&  $key!='category' && $key!='post_title' && $key!='basic_inf')
			{		
		?>
			<?php if($_htmlvar_name['type'] == 'multicheckbox' && get_post_meta($post->ID,$key,true) !=''):
					if($i==0)_e('<h3>Custom Fields</h3>',DOMAIN);
			?>
						<li><label><?php echo $_htmlvar_name['label']; ?></label> : <span><?php echo implode(",",get_post_meta($post->ID,$key,true)); ?></span></li>
	               <?php elseif($_htmlvar_name['type']=='upload' && get_post_meta($post->ID,$key,true) !=''):
						if($i==0)_e('<h3>Custom Fields</h3>',DOMAIN);
			?>
               	 		<li><label><?php echo $_htmlvar_name['label']; ?> </label>: <span> Click here to download File <a href="<?php echo stripslashes(get_post_meta($post->ID,$key,true)); ?>">Download</a></span></li>
			<?php else: 			
					if(get_post_meta($post->ID,$key,true) !=''):
						if($i==0)_e('<h3>Custom Fields</h3>',DOMAIN);
					?>
					<li><label><?php echo $_htmlvar_name['label']; ?></label> : <span><?php echo stripslashes(get_post_meta($post->ID,$key,true)); ?></span></li>
				<?php endif; ?>
			<?php endif; ?>
	<?php $i++; }// first if condition finish
			$j++;
		endforeach; 		
		echo '</div>';
		endif;
	endif;
	wp_reset_query();
		
}

add_action('templ_after_post_content','event_organized_single_post');
/*
 * Function Name: event_organized_single_post
 * Return: display the event organized details
 */
function event_organized_single_post()
{
	global $post,$single_htmlvar_name;		
	if($post->post_type==CUSTOM_POST_TYPE_EVENT && is_single()):
		$org_address=get_post_meta($post->ID,'organizer_address',true);
		$org_address=get_post_meta($post->ID,'organizer_address',true);
		
		$org_mobile=get_post_meta($post->ID,'organizer_mobile',true);
		$org_contact=get_post_meta($post->ID,'organizer_contact',true);
		$org_email=get_post_meta($post->ID,'organizer_email',true);
		$org_website=get_post_meta($post->ID,'organizer_website',true);
		$org_logo=get_post_meta($post->ID,'organizer_logo',true);
		$org_desc=get_post_meta($post->ID,'organizer_desc',true);
		$reg_desc=get_post_meta($post->ID,'reg_desc',true);
		$org_logo=get_post_meta($post->ID,'organizer_logo',true);
		$video=get_post_meta($post->ID,'video',true);
		if($org_address || $org_mobile || $org_email || $org_website || $org_logo || $org_desc):
	?>
    	<div class="description">
        	<h3><?php _e('ORGANIZED BY',T_DOMAIN);?></h3>
			<?php if($single_htmlvar_name['organizer_logo'] && $org_logo):?>
            	<div class="org_logo"><img src="<?php echo $org_logo?>"  width="200" height="235"/></div>
            <?php endif;?>
            <div class="info">	
            <?php if(get_post_meta($post->ID,'organizer_name',true)!="" && $single_htmlvar_name['organizer_name']){ echo "<h4>".get_post_meta($post->ID,'organizer_name',true)."</h4>";}?>
            	<?php if($org_address!="" && $single_htmlvar_name['organizer_address']):?>           
	            	<span class="address"><?php echo $org_address;?></span>
                <?php endif;?>
                <?php if($org_contact!="" && $single_htmlvar_name['organizer_contact']):?>           
               		<span class="phone"><?php echo $org_contact;?></span>
                <?php endif;?>
                <?php if($org_mobile!="" && $single_htmlvar_name['organizer_mobile']):?>           
               		<span class="phone"><?php echo $org_mobile;?></span>
                <?php endif;?>
                <?php if($org_email!="" && $single_htmlvar_name['organizer_email']):?>
                	<span class="email"><?php echo $org_email;?></span>
                <?php endif;?>
                <?php if($org_website!="" && $single_htmlvar_name['organizer_website']):?>
	                <span class="website"><?php echo $org_website;?></span>
				<?php endif;?>               
            </div>           
            <?php if($org_desc!="" && $single_htmlvar_name['organizer_desc']){ echo "<div class='org_desc'>".$org_desc."</div>";}?>        
            <?php if($reg_desc!="" && $single_htmlvar_name['reg_desc']){ echo "<div class='org_reg_desc'>".$reg_desc."</div>";}?>        
        </div> 
        <?php if($single_htmlvar_name['video'] && $video):?>
        	<div class="org_video">
            	<?php echo stripslashes($video);?>
            </div>
        <?php endif;// if condition for video
		endif; ?>          
           
	<?php 
		/* Display Organizer Information*/
		$post_type=get_post_type();
		$heading_type = fetch_heading_per_post_type(get_post_type());
		
		if(count($heading_type) > 0)
		{
			foreach($heading_type as $_heading_type)
			{	
				$custom_metaboxes[$_heading_type] = get_post_custom_fields_templ_plugin($post_type,'','',$_heading_type);//custom fields for custom post type..
			}
		}	
		global $post,$single_htmlvar_name,$single_pos_title;
		if($post->post_type==CUSTOM_POST_TYPE_EVENT && is_single()):
			$i=0;
			$j=0;
			if(is_array($single_htmlvar_name)):
			echo '<div class="single_custom_field">';		
			foreach($custom_metaboxes['Organizer Information'] as $key=> $_htmlvar_name):
			
				if($key!="st_date" && $key!="end_date" && $key!="end_date" && $key!="st_time" && $key!="end_time" && $key!="event_type" && $key!="phone" && $key!="email" && $key!="website" && $key!="twitter" && $key!="facebook" && $key!="video" && $key!="organizer_name" && $key!="organizer_email" && $key!="organizer_logo" && $key!="organizer_address" && $key!="organizer_contact" && $key!="organizer_website" && $key!="organizer_mobile" && $key!="organizer_desc" && $key!="post_images" && $key!="org_info" && $key!="address" && $key!="map_view" && $key!="reg_desc" && $key!="post_content" && $key!="post_excerpt")
				{
					
			?>
				<?php if($_htmlvar_name['type'] == 'multicheckbox' && get_post_meta($post->ID,$key,true) !=''):
						if($i==0)_e('<h3>Orgnizer Custom Fields</h3>',DOMAIN);
				?>
							<li><label><?php echo $_htmlvar_name['label']; ?></label> : <span><?php echo implode(",",get_post_meta($post->ID,$key,true)); ?></span></li>
					<?php elseif($_htmlvar_name['type']=='upload' && get_post_meta($post->ID,$key,true) !=''):
							if($i==0)_e('<h3>Orgnizer Custom Fields</h3>',DOMAIN);
				?>
							<li><label><?php echo $_htmlvar_name['label']; ?> </label>: <span> Click here to download File <a href="<?php echo stripslashes(get_post_meta($post->ID,$key,true)); ?>">Download</a></span></li>
				<?php else: 			
						if(get_post_meta($post->ID,$key,true) !=''):
							if($i==0)_e('<h3>Orgnizer Custom Fields</h3>',DOMAIN);
						?>
						<li><label><?php echo $_htmlvar_name['label']; ?></label> : <span><?php echo stripslashes(get_post_meta($post->ID,$key,true)); ?></span></li>
					<?php endif; ?>
				<?php endif; ?>
		<?php $i++; }// first if condition finish
				$j++;
			endforeach; 		
			echo '</div>';
			endif;
		endif;
		wp_reset_query();
		?>
          <div class="event_social_media">		
            <div class="addthis_toolbox addthis_default_style">
                <a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c873bb26489d97f" class="addthis_button_compact sharethis">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/i_share.png" alt=""  />
                </a>                
            </div>
             <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c873bb26489d97f"></script>
 			<?php if(get_post_meta($post->ID,'facebook',true)!="" && $single_htmlvar_name['facebook']):?>
	            <a href="<?php echo get_post_meta($post->ID,'facebook',true);?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/i_facebook21.png" alt="facebook"  /></a>
            <?php endif;?>
    		<?php if(get_post_meta($post->ID,'twitter',true)!="" && $single_htmlvar_name['twitter']):?>
	            <a href="<?php echo get_post_meta($post->ID,'twitter',true);?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/i_twitter2.png" alt="twitter"  /></a>
            <?php endif;?>
            <?php
			$settings = get_option( "templatic_settings" );   	
		   	if(isset($settings['templatic_view_counter']) && $settings['templatic_view_counter']=='Yes')
		    {
				$sep =" , ";
				$custom_content.="<b class='post_views'>".sprintf('Visited %s times',"<span>".user_single_post_visit_count($post->ID)."</span>");
				$custom_content.= $sep."<span>".user_single_post_visit_count_daily($post->ID)."</span>"." Visits today</b>";
				echo $custom_content;
		    }
			?>            
      </div>       
          <?php
	endif;//if condition for check the event single page
}
/*
 * Add action in categories page for display post image slider.
 */
add_action('templ_after_archive_title','flexslider_before_category_title',10); 
 
add_action('templ_after_categories_title','flexslider_before_category_title',10);

function flexslider_before_category_title()
{
	global $wpdb,$post;
	
	if (have_posts()) : 
	?>
    <!-- Start flexslider in taxonomy page-->
    <div class="flexslider flexslider_inner">
    	<script type="text/javascript">	   
	    jQuery(window).load(function(){
		 jQuery('.flexslider').flexslider({
		   animation: "slide",
		   animationLoop: false,		   
		   start: function(slider){
			jQuery('body').removeClass('loading');
		   }
		 });
	    });
  </script>
    	<ul class="slides">
    <?php
		while (have_posts()) : the_post();		
			$is_parent = $post->post_parent;
			if($is_parent != 0){
				$post = get_post($is_parent);
				global $post;
			}else{
				global $post;
			}
			if(function_exists('get_templ_image'))			
				$taxonomy_slider=get_templ_image($post->ID,$size='taxonomy-slider');
			if($taxonomy_slider):
			?>
			<li>
            	<img src="<?php echo $taxonomy_slider;?>" />
                <div class="slider_content">
                	<div class="slide_event_info">
                    	<span class="image"><?php echo date('d',strtotime(get_post_meta($post->ID,'st_date',true)));?></span>
                    	<p>
                        	<span><?php echo date_i18n(get_option("date_format"),strtotime(get_post_meta($post->ID,'st_date',true))); ?>, <?php echo get_post_meta($post->ID,'st_time',true); ?></span>
	                    	<a href="<?php the_permalink();?>"><?php the_title(); ?></a>
                    	</p>
                    </div>
                </div>
            </li>
        <?php	
			endif;
		endwhile;
		wp_reset_query();
		?>
    	</ul>
    </div><!--Finish the flexslider in taxonomy page -->
    <?php
	endif;
}

/*
 * Add action in categories page for display the additional information before categories title.
 */
add_action('templ_after_archive_title','before_category_titel_smart_tab',11);
add_action('templ_after_categories_title','before_category_titel_smart_tab',11);

/*
 * Function Name: before_category_titel_smart_tab
 * Return : Display the smart tab before the category title
 */
function before_category_titel_smart_tab()
{
	//global $post;		
	
	templatic_display_views();			
	if(get_post_type()== CUSTOM_POST_TYPE_EVENT):?>
         <div class="taxonomy-sortoption">
          <form method="post" action="" name="sort_by_result_frm">
               <select id="sortby_id" class="category" onchange="sort_as_set()" name="sortby">
                    <option value=""> <?php _e('Sort events',T_DOMAIN);?></option>
                     <option value="title_asc" <?php if(isset($_REQUEST['sortby'])&& $_REQUEST['sortby']=='title_asc'){ echo 'selected="selected"';}?>> <?php _e('Title Ascending',T_DOMAIN);?></option>
                     <option value="title_desc" <?php if(isset($_REQUEST['sortby'])&& $_REQUEST['sortby']=='title_desc'){ echo 'selected="selected"';}?>> <?php _e('Title Descending',T_DOMAIN);?></option>
                     <option value="stdate_low_high" <?php if(isset($_REQUEST['sortby'])&& $_REQUEST['sortby']=='stdate_low_high'){ echo 'selected="selected"';}?>> <?php _e('Start Date low to high',T_DOMAIN);?></option>
                     <option value="stdate_high_low" <?php if(isset($_REQUEST['sortby'])&& $_REQUEST['sortby']=='stdate_high_low'){ echo 'selected="selected"';}?>> <?php _e('Start Date high to low',T_DOMAIN);?></option>
                     <option value="address_high_low" <?php if(isset($_REQUEST['sortby'])&& $_REQUEST['sortby']=='address_high_low'){ echo 'selected="selected"';}?>> <?php _e('Address (A-Z)',T_DOMAIN);?></option>
                     <option value="address_low_high" <?php if(isset($_REQUEST['sortby'])&& $_REQUEST['sortby']=='address_low_high'){ echo 'selected="selected"';}?>> <?php _e('Address (Z-A)',T_DOMAIN);?></option>
                 </select>
             </form>
         </div>
         <script type="text/javascript">
               function sort_as_set()
               {
                    if(document.getElementById('sortby_id').value)			
                         document.sort_by_result_frm.submit();
     
               }
          </script>
    <?php
    endif;
}
/* Remove action for Category Page image*/
remove_action('tmpl_category_page_image','tmpl_category_page_image');
remove_action('tmpl_archive_page_image','tmpl_category_page_image');

add_action('tmpl_archive_page_image','event_taxonomy_page_image');
/* Add Action tmpl_category_page_image in taxonomy page */
add_action('tmpl_category_page_image','event_taxonomy_page_image');
function event_taxonomy_page_image()
{
	global $post;	
	$is_parent = $post->post_parent;
	if($is_parent != 0){
		$post = get_post($is_parent);
		global $post;
	}else{
		global $post;
	}
	if(function_exists('get_templ_image'))
		$thumb_img=get_templ_image($post->ID,$size='thumbnail');
	?>
    <!-- List view image -->
    <a href="<?php the_permalink();?>" class="post_img img listimg">
    <?php if($thumb_img):?>
	    <img src="<?php echo $thumb_img; ?>"  alt="<?php echo $img_alt; ?>"  title="<?php echo $img_title; ?>" />
    <?php else:?>    
   		<img src="<?php echo CUSTOM_FIELDS_URLPATH; ?>/images/img_not_available.png" alt="" height="210" width="210"  />
    <?php endif;?>
    </a>
    <!--Finish list View image -->    
    <?php			
	if(function_exists('get_templ_image'))
		$thumb_img=get_templ_image($post->ID,$size='taxonomy-thumbnail');
	?>
    <!-- Grid View image-->
    <a href="<?php the_permalink();?>" class="post_img img gridimg">
    <?php if($thumb_img!=""):?>
	    <img src="<?php echo $thumb_img; ?>"  alt="<?php echo $img_alt; ?>" />
    <?php else:?>    
   		<img src="<?php echo CUSTOM_FIELDS_URLPATH; ?>/images/img_not_available.png" alt="" height="150" width="310"  />
    <?php endif;?>
    </a>
    <!--Finish Grid View Image -->
	<?php	
}

add_filter('tmpl_before_page_image','tmpl_category_page_before_image');
function tmpl_category_page_before_image($post){
		global $post;
		$is_parent = $post->post_parent;
		if($is_parent !=0)
			$featured = get_post_meta($is_parent,'featured_c',true);
		return $featured;
        
}
/*
 * Function Name: ptthemes_postcodes_metabox_insert
 * Return : insert address and logitude and latitude on postcode table for search by address
 */
function ptthemes_postcodes_insert($last_postid)
{
	global $post,$wpdb;
	$tbl_postcodes = $wpdb->prefix . "postcodes";
	if($wpdb->get_var("SHOW TABLES LIKE \"$tbl_postcodes\"") != $tbl_postcodes)
	{
		$tbl_postcodes_ = 'CREATE TABLE IF NOT EXISTS `'.$tbl_postcodes.'` (
		`pcid` bigint(20) NOT NULL AUTO_INCREMENT,
		`post_id` bigint(20) NOT NULL,
		`address` varchar(255) NOT NULL,
		`latitude` varchar(255) NOT NULL,
		`longitude` varchar(255) NOT NULL,
		 PRIMARY KEY (`pcid`)
		)';
		$wpdb->query($tbl_postcodes_);	
	}
	
	if(strstr($_SERVER['REQUEST_URI'],'wp-admin') && isset($_POST['post_type']) &&  $_POST['post_type'] == 'event'  )
	{
		$post_address = $_POST['address'];
		$latitude = $_POST['geo_latitude'];
		$longitude = $_POST['geo_longitude'];
		$pID = $_POST['post_ID'];
		$pcid = $wpdb->get_var("select pcid from $tbl_postcodes where post_id = '".$pID."'");
		//echo $pcid;exit;
		if($pcid){
			$postcodes_update = "UPDATE $tbl_postcodes set 
				address = '".$post_address."',
				latitude ='".$latitude."',
				longitude='".$longitude."' where pcid = '".$pcid."' and post_id = '".$pID."'";
				$wpdb->query($postcodes_update);
			}
		else
		{
			$postcodes_insert = 'INSERT INTO '.$tbl_postcodes.' set 
					pcid="",
					post_id="'.$pID.'",
					address = "'.$post_address.'",
					latitude ="'.$latitude.'",
					longitude="'.$longitude.'"';
					$wpdb->query($postcodes_insert);
		}
		
	}
	/* save editional data when submit event from front end */
	if(!strstr($_SERVER['REQUEST_URI'],'wp-admin') && get_post_type( $last_postid) == CUSTOM_POST_TYPE_EVENT)
	{
		$post_address 	= $_SESSION['custom_fields']['address'];
		$latitude 		= $_SESSION['custom_fields']['geo_latitude'];
		$longitude 		= $_SESSION['custom_fields']['geo_longitude'];
		$pcid = $wpdb->get_var("select pcid from $tbl_postcodes where post_id = '".$last_postid."'");

		if($pcid){
			$postcodes_update = "UPDATE $tbl_postcodes set 
				address = '".$post_address."',
				latitude ='".$latitude."',
				longitude='".$longitude."' where pcid = '".$pcid."' and post_id = '".$last_postid."'";
				$wpdb->query($postcodes_update);
			}
		else
		{
			$postcodes_insert = 'INSERT INTO '.$tbl_postcodes.' set 
					pcid="",
					post_id="'.$last_postid.'",
					address = "'.$post_address.'",
					latitude ="'.$latitude.'",
					longitude="'.$longitude.'"';
					$wpdb->query($postcodes_insert);
		}
		$event_type = $_SESSION['custom_fields']['event_type'];
		if(trim(strtolower($event_type)) == trim(strtolower('Recurring event')) && isset($_SESSION['custom_fields']['cur_post_type']) &&  $_SESSION['custom_fields']['cur_post_type'] == 'event')
		{
			update_post_meta($last_postid, 'recurrence_occurs', $_SESSION['custom_fields']['recurrence_occurs']);
			update_post_meta($last_postid, 'recurrence_per', $_SESSION['custom_fields']['recurrence_per']);
			update_post_meta($last_postid, 'recurrence_onday', $_SESSION['custom_fields']['recurrence_onday']);
	
			update_post_meta($last_postid, 'recurrence_bydays', implode(',',$_SESSION['custom_fields']['recurrence_bydays']));
	
			update_post_meta($last_postid, 'recurrence_onweekno', $_SESSION['custom_fields']['recurrence_onweekno']);
			update_post_meta($last_postid, 'recurrence_days', $_SESSION['custom_fields']['recurrence_days']);	
			update_post_meta($last_postid, 'monthly_recurrence_byweekno', $_SESSION['custom_fields']['monthly_recurrence_byweekno']);	
			update_post_meta($last_postid, 'recurrence_byday', $_SESSION['custom_fields']['recurrence_byday']);	
			update_post_meta($last_postid, 'st_date', $_SESSION['custom_fields']['st_date']);	
			update_post_meta($last_postid, 'end_date', $_SESSION['custom_fields']['end_date']);	
			
			$start_date = templ_recurrence_dates($last_postid);
			update_post_meta($last_postid,'recurring_search_date',$start_date);
			
			templ_save_recurrence_events( $_SESSION['custom_fields'],$last_postid);// to save event recurrences - front end
		}
	}

	/* save editional data when submit event from backend */
	if(strstr($_SERVER['REQUEST_URI'],'wp-admin') && isset($_REQUEST['event_type']) && isset($_REQUEST['action'])  && $_REQUEST['action'] == 'editpost') 
	{
		$event_type = $_POST['event_type'];
		$pID = $_POST['ID'];
		
		if(trim(strtolower($event_type)) == trim(strtolower('Recurring event')) && isset($_POST['post_type']) &&  $_POST['post_type'] == 'event')
		{
			update_post_meta($pID, 'recurrence_occurs', $_POST['recurrence_occurs']);
			update_post_meta($pID, 'recurrence_per', $_POST['recurrence_per']);
			update_post_meta($pID, 'recurrence_onday', $_POST['recurrence_onday']);
	
			update_post_meta($pID, 'recurrence_bydays', implode(',',$_POST['recurrence_bydays']));
	
			update_post_meta($pID, 'recurrence_onweekno', $_POST['recurrence_onweekno']);
			update_post_meta($pID, 'recurrence_days', $_POST['recurrence_days']);	
			update_post_meta($pID, 'monthly_recurrence_byweekno', $_POST['monthly_recurrence_byweekno']);	
			update_post_meta($pID, 'recurrence_byday', $_POST['recurrence_byday']);	
		}
		if(trim(strtolower($event_type)) == trim(strtolower('Recurring event')) && isset($_POST['post_type']) &&  $_POST['post_type'] == 'event' )
		{ 
			$start_date = templ_recurrence_dates($pID);
			$post_data = $_POST;
			$parent_data = get_post($last_postid);
			$parent_post_status = get_post_meta($pID,'tmpl_post_status',true);
			if($parent_post_status == 'draft' && $parent_post_status != ''){
				$fetch_status = 'pending';
			}else{
				$fetch_status = 'private';
			}
			/* to delete the old recurrences BOF */
			$args =	array( 
						'post_type' => 'event',
						'posts_per_page' => -1	,
						'post_status' => array($fetch_status),
						'meta_query' => array(
						'relation' => 'AND',
							array(
									'key' => '_event_id',
									'value' => $pID,
									'compare' => '=',
									'type'=> 'text'
								),
							)
						);
			$post_query = null;
			$post_query = new WP_Query($args);
			if($post_query){
				while ($post_query->have_posts()) : $post_query->the_post();
					  $post_status = $post->post_status;
					  if($post_status =='pending' && $parent_post_status =='publish')
					  {
						$my_post['ID'] = $post->ID;
						$my_post['post_status'] = 'private';
						wp_update_post( $my_post );
					  }else{
						wp_delete_post($post->ID);
					  }
				endwhile;
				wp_reset_query();
			}
			/* to delete the old recurrences EOF */
			templ_save_recurrence_events($post_data,$pID);// to save event recurrences
			update_post_meta($pID,'recurring_search_date',$start_date);
			
		}
		
	}
	/* delete additional data event from backend */
	if(strstr($_SERVER['REQUEST_URI'],'wp-admin')  && isset($_REQUEST['action'])  && $_REQUEST['action'] == 'trash') 
	{
		$pID = $_REQUEST['post'];
		$event_type = get_post_meta($pID,'event_type',true);
		$post_type = get_post_type($pID);

		if(trim(strtolower($event_type)) == trim(strtolower('Recurring event')) && isset($post_type) &&  $post_type == 'event' )
		{ 
			/* to delete the old recurrences BOF */
			$args =	array( 
						'post_type' => 'event',
						'posts_per_page' => -1	,
						'post_status' => array('private'),
						'meta_query' => array(
						'relation' => 'AND',
							array(
									'key' => '_event_id',
									'value' => $pID,
									'compare' => '=',
									'type'=> 'text'
								),
							)
						);
			$post_query = null;
			$post_query = new WP_Query($args);
			if($post_query){
				while ($post_query->have_posts()) : $post_query->the_post();
					echo $post->ID;
					wp_delete_post($post->ID);
				endwhile;
				wp_reset_query();
			}
			/* to delete the old recurrences EOF */
			
			
		}
		
	}
}
/*
Name : delete_recurring_event
Description : to delete recurring data from front end.
*/
if(!strstr($_SERVER['REQUEST_URI'],'wp-admin') )
	add_action('delete_post', 'delete_recurring_event'); // to delete the post of old recurrencies

function delete_recurring_event()
{
	global $wpdb,$post,$post_id;

	/* to delete the old recurrences BOF */
	$args =	array( 
				'post_type' => 'event',
				'posts_per_page' => -1	,
				'post_status' => array('private'),
				'meta_query' => array(
				'relation' => 'AND',
					array(
							'key' => '_event_id',
							'value' => $_REQUEST['pid'],
							'compare' => '=',
							'type'=> 'text'
						),
					)
				);
	$post_query = null;
	$post_query = new WP_Query($args);
	if($post_query){
		while ($post_query->have_posts()) : $post_query->the_post();
			wp_delete_post($post->ID);
		endwhile;wp_reset_query();
	}
	remove_action('delete_post', 'delete_recurring_event');
	/* to delete the old recurrences EOF */
}
/*
Name : tmpl_set_my_categories
Description : set the categories of recurrence events 
*/
function tmpl_set_my_categories($last_rec_post_id,$post_id=''){
	$cat_1 = "";
		$recurring_update = $_REQUEST['recurring_update'];

		if(strstr($_SERVER['REQUEST_URI'],'wp-admin') && !isset($recurring_update) && $recurring_update == ''){
			$cats = $_REQUEST['tax_input']['ecategory']; 
			$tags = $_REQUEST['tax_input']['etags']; 
			$tags = explode(',',$tags);
		}else if(isset($recurring_update) && $recurring_update != '')
		{
			$terms = wp_get_post_terms( $post_id, 'ecategory' );
			$terms_tag = wp_get_post_terms( $post_id, 'etags' );
			
			$cat_count = count($terms);
			$sep =",";
			
				for($c=0; $c < $cat_count ; $c++){
					
					if(($cat_count - 1)  == $c)
						$sep = "";
					$cat_1 .= $terms[$c]->term_id.$sep;
				
				}
			
			$sep =",";
			$term_count = count($terms_tag);
			{
				for($c=0; $c < $term_count ; $c++){
				
					if(($term_count - 1)  == $c)
						$sep = "";
					$tag_1 .= $terms_tag[$c]->name.$sep;
				
				}
				
			}
			$cats = explode(',',$cat_1);
			$tags = explode(',',$tag_1);
		}
		else{
			if($_SESSION['category']){
				$cats = $_SESSION['category']; 
			}else{
				$cats = $_SESSION['custom_fields']['category']; 
			}
			$tags = $_SESSION['custom_fields']['e_tags']; 
			$sep =",";
			for($c=0; $c < count($cats) ; $c++){
				$cat_0 = explode(',',$cats[$c]);
				if((count($cats) - 1)  == $c)
					$sep = "";
				$cat_1 .= $cat_0[0].$sep;
				
			}
			$cats = explode(',',$cat_1);
		
		}

		wp_set_post_terms( $last_rec_post_id, $cats,'ecategory' ,false);
		wp_set_post_terms( $last_rec_post_id, $tags,'etags' ,false);
}
/*
Name : templ_update_rec_data
Description : it's update other recurrances while update the evenets
*/	
function templ_update_rec_data($post_data,$post_id,$st_date,$end_date){
	remove_action('save_post','ptthemes_postcodes_insert');
	$recurring_update = $_REQUEST['recurring_update'];
	$parent_data = get_post($post_id);
	$parent_post_status = get_post_meta($parent_data->ID,'tmpl_post_status',true);
	$p_status = $parent_data->post_status;
	if($parent_post_status =='draft' && $p_status == 'draft'){
		$child_status = 'pending';
	}else{
		$child_status = 'private';
	}
	if(isset($recurring_update) && $recurring_update != '')
	{
		$post_details = array('post_title' => $post_data->post_title,
					'post_content' => $post_data->post_content,
					'post_status' => $child_status,
					'post_type' => 'event',
					'post_name' => str_replace(' ','-',$post_data->post_title)."-".$st_date,
					'post_parent' => $post_id,
				  );
	}
	else
	{
		$post_details = array('post_title' => $post_data['post_title'],
					'post_content' => $post_data['post_content'],
					'post_status' => $child_status,
					'post_type' => 'event',
					'post_name' => str_replace(' ','-',$post_data['post_title'])."-".$st_date,
					'post_parent' => $post_id,
				  );
	}
	$alive_days = get_post_meta($post_id,'alive_days',true);
	$last_rec_post_id = wp_insert_post($post_details); // insert recurrences of events 
	if(isset($recurring_update) && $recurring_update != '')
		tmpl_set_my_categories($last_rec_post_id,$post_id); // assign category of parent post
	if((isset($_REQUEST['tax_input']['ecategory']) && $_REQUEST['tax_input']['ecategory']!='') || $_SESSION['custom_fields']['category'] !='' || $_SESSION['category'])
		tmpl_set_my_categories($last_rec_post_id,$post_id); // assign category of parent post
	$st_time = get_post_meta($post_id,'st_time',true);
	$end_time = get_post_meta($post_id,'end_time',true);
	$address = get_post_meta($post_id,'address',true);
	/* add parent post valy with different date and time */
	update_post_meta($last_rec_post_id,'event_type','Regular event'); 
	update_post_meta($last_rec_post_id,'end_date',$end_date); 
	update_post_meta($last_rec_post_id,'st_date',$st_date);
	update_post_meta($last_rec_post_id,'st_time',$st_time);
	update_post_meta($last_rec_post_id,'end_time',$end_time);
	update_post_meta($last_rec_post_id,'_event_id',$post_id); 
	update_post_meta($last_rec_post_id,'address',$address); 
	update_post_meta($last_rec_post_id,'alive_days',$alive_days); 
	if(!strstr($_SERVER['REQUEST_URI'],'wp-admin'))
		update_post_meta($post_id,'tmpl_post_status',$parent_data->post_status); 

}
/*
 *Function Name : templ_recurrence_dates
 *Description : return recurrence dates.
 */
function templ_save_recurrence_events($post_data,$pID)
{

	global $wpdb,$current_user;
	$post_id = $pID;
	$start_date = strtotime(get_post_meta($post_id,'st_date',true));
	$end_date = strtotime(get_post_meta($post_id,'end_date',true));
	$tmpl_end_date = strtotime(get_post_meta($post_id,'end_date',true));
	$recurrence_occurs = get_post_meta($post_id,'recurrence_occurs',true);//reoccurence type
	$recurrence_per = get_post_meta($post_id,'recurrence_per',true);//no. of occurence.
	$current_date = date('Y-m-d');
	$recurrence_days = get_post_meta($post_id,'recurrence_days',true);	//on which day
	$recurrence_list = "";
	
	
	if($recurrence_occurs == 'daily' )
	{
		$days_between = ceil(abs($end_date - $start_date) / 86400);
		for($i=0;$i<($days_between);$i++)
		{
			$class= ($i%2) ? "odd" : "even";
			if(($i%$recurrence_per) == 0 )
			{
				$j = $i+$recurrence_days;
				$st_date1 = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) . " +$i day");
				if($recurrence_days==0)
					$recurrence_days=1;
				
				$st_date2 = strtotime(date("Y-m-d", $st_date1) );
				$st_date = date_i18n(get_option('date_format'),strtotime(date("Y-m-d", $st_date2)));
				$end_date =  date_i18n(get_option('date_format'),strtotime(date("Y-m-d", strtotime($st_date)) . " +".$recurrence_days." day"));
				if($tmpl_end_date < strtotime($end_date)){
					$end_date = date_i18n(get_option('date_format'),strtotime(date("Y-m-d", $tmpl_end_date)));
				}
				templ_update_rec_data($post_data,$post_id,$st_date,$end_date);

			}
			else
			{
				continue;
			}
		}
	}
	if($recurrence_occurs == 'weekly' )
	{ 
		$recurrence_interval = get_post_meta($post_id,'recurrence_per',true);//no. of occurence.
		$days_between = ceil(abs($end_date - $start_date) / 86400);
		$l = 0;
		$count_recurrence = 0;
		$current_week = 0;
		$recurrence_list .= "<ul>";
		
		if(strstr(get_post_meta($post_id,'recurrence_bydays',true),","))
			$recurrence_byday = explode(',',get_post_meta($post_id,'recurrence_byday',true));	//on which day
		else
			$recurrence_byday = get_post_meta($post_id,'recurrence_byday',true);	//on which day
		$start_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) );
		$end_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'end_date',true))) );
		
		//sort out week one, get starting days and then days that match time span of event (i.e. remove past events in week 1)
		$weekdays = explode(",", get_post_meta($post_id,'recurrence_bydays',true));
		$matching_days = array(); 
		$aDay = 86400;  // a day in seconds
		$aWeek = $aDay * 7;
			$start_of_week = get_option('start_of_week'); //Start of week depends on WordPress
			//first, get the start of this week as timestamp
			$event_start_day = date('w', $start_date);
			$offset = 0;
			if( $event_start_day > $start_of_week ){
				$offset = $event_start_day - $start_of_week; //x days backwards
			}elseif( $event_start_day < $start_of_week ){
				$offset = $start_of_week;
			}
			$start_week_date = $start_date - ( ($event_start_day - $start_of_week) * $aDay );
			//then get the timestamps of weekdays during this first week, regardless if within event range
			$start_weekday_dates = array(); //Days in week 1 where there would events, regardless of event date range
			for($i = 0; $i < 7; $i++){
				$weekday_date = $start_week_date+($aDay*$i); //the date of the weekday we're currently checking
				$weekday_day = date('w',$weekday_date); //the day of the week we're checking, taking into account wp start of week setting
				if( in_array( $weekday_day, $weekdays) ){
					$start_weekday_dates[] = $weekday_date; //it's in our starting week day, so add it
				}
			}
	
			//for each day of eventful days in week 1, add 7 days * weekly intervals
			foreach ($start_weekday_dates as $weekday_date){
				//Loop weeks by interval until we reach or surpass end date
				while($weekday_date <= $end_date){
					if( $weekday_date >= $start_date && $weekday_date <= $end_date ){
						$matching_days[] = $weekday_date;
					}					
					$weekday_date = $weekday_date + strtotime("+$recurrence_interval week", date("Y-m-d",$weekday_date));
				}
			}//done!
			 sort($matching_days);
			 $tmd = count($matching_days);
			 for($z=0;$z<count($matching_days);$z++)
			{
				$st_date1 = $matching_days[$z];
				if($z <= ($tmd-1)){
					if($recurrence_days==0)
						$recurrence_days=1;
				
					$st_date2 = strtotime(date("Y-m-d", $matching_days[$z]));
					$st_date = date_i18n('Y-m-d',strtotime(date("Y-m-d", $st_date2)));
					$end_date =  date_i18n("Y-m-d",strtotime(date("Y-m-d", strtotime($st_date)) . " +".$recurrence_days." day"));
					if($tmpl_end_date < strtotime($end_date)){
						$end_date = date_i18n('Y-m-d',strtotime(date("Y-m-d", $tmpl_end_date)));
					}
					templ_update_rec_data($post_data,$post_id,$st_date,$end_date);
				
				}
			}

	}

	if($recurrence_occurs == 'monthly' )
	{
		$recurrence_interval = get_post_meta($post_id,'recurrence_per',true);//no. of occurence.
		$days_between = ceil(abs($end_date - $start_date) / 86400);
		$recurrence_byweekno = get_post_meta($post_id,'monthly_recurrence_byweekno',true);	//on which day
		$l = 0;
		$month_week = 0;
		$count_recurrence = 0;
		$current_month = 0;
		$recurrence_list .= "<ul>";
		
			if(strstr(get_post_meta($post_id,'recurrence_bydays',true),","))
				$recurrence_byday = explode(',',get_post_meta($post_id,'recurrence_byday',true));	//on which day
			else
				$recurrence_byday = get_post_meta($post_id,'recurrence_byday',true);	//on which day
			$start_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) );
			$end_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'end_date',true))) );
		
		$matching_days = array(); 
		$aDay = 86400;  // a day in seconds
		$aWeek = $aDay * 7;		 
		$current_arr = getdate($start_date);
		$end_arr = getdate($end_date);
		$end_month_date = strtotime( date('Y-m-t', $end_date) ); //End date on last day of month
		$current_date = strtotime( date('Y-m-1', $start_date) ); //Start date on first day of month
		while( $current_date <= $end_month_date ){
			 $last_day_of_month = date('t', $current_date);
			//Now find which day we're talking about
			$current_week_day = date('w',$current_date);
			$matching_month_days = array();
			//Loop through days of this years month and save matching days to temp array
			for($day = 1; $day <= $last_day_of_month; $day++){
				if((int) $current_week_day == $recurrence_byday){
					$matching_month_days[] = $day;
				}
				$current_week_day = ($current_week_day < 6) ? $current_week_day+1 : 0;							
			}
			//Now grab from the array the x day of the month
			$matching_day = ($recurrence_byweekno > 0) ? $matching_month_days[$recurrence_byweekno-1] : array_pop($matching_month_days);
			$matching_date = strtotime(date('Y-m',$current_date).'-'.$matching_day);
			if($matching_date >= $start_date && $matching_date <= $end_date){
				$matching_days[] = $matching_date;
			}
			//add the number of days in this month to make start of next month
			$current_arr['mon'] += $recurrence_interval;
			if($current_arr['mon'] > 12){
				//FIXME this won't work if interval is more than 12
				$current_arr['mon'] = $current_arr['mon'] - 12;
				$current_arr['year']++;
			}
			$current_date = strtotime("{$current_arr['year']}-{$current_arr['mon']}-1"); 
			
		}
		sort($matching_days);
			$tmd = count($matching_days);
			 for($z=0;$z<count($matching_days);$z++)
			{
				$class= ($z%2) ? "odd" : "even";
				$st_date1 = $matching_days[$z];
				date("Y-m-d", $matching_days[$z]);
				if($z <= ($tmd-1)){
					if($recurrence_days==0)
						$recurrence_days=1;
				
					$st_date2 = strtotime(date("Y-m-d", $matching_days[$z]) );
					$st_date = date_i18n('Y-m-d',strtotime(date("Y-m-d", $st_date2)));
					$end_date =  date_i18n("Y-m-d",strtotime(date("Y-m-d", strtotime($st_date)) . " +".$recurrence_days." day"));
					if($tmpl_end_date < strtotime($end_date)){
						$end_date = date_i18n('Y-m-d',strtotime(date("Y-m-d", $tmpl_end_date)));
					}
					templ_update_rec_data($post_data,$post_id,$st_date,$end_date);

				}
			}
			
	}
	if($recurrence_occurs == 'yearly' )
	{

		$date1 = get_post_meta($post_id,'st_date',true);
		$date2 = get_post_meta($post_id,'end_date',true);
		$st_startdate1 = explode("-",$date1);
		$st_year = $st_startdate1[0];
		$st_month = $st_startdate1[1];
		$st_day = $st_startdate1[2];
		$st_date1 = mktime(0, 0, 0, $st_month, $st_day, $st_year);
		$st_date__month = (int)date('n', $st_date1); //get the current month of start date.
		$diff = abs(strtotime($date2) - strtotime($date1));
		$years_between = floor($diff / (365*60*60*24));
		$recurrence_list .= "<ul>";
		for($i=0;$i<($years_between+1);$i++)
		{
			$class= ($i%2) ? "odd" : "even";
			$startdate = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) . " +$i year");
			$startdate1 = explode("-",date('Y-m-d',$startdate));
			$year = $startdate1[0];
			$month = $startdate1[1];
			$day = $startdate1[2];
			$date2 = mktime(0, 0, 0, $month, $day, $year);
			$month = (int)date('n', $date2); //get the current month.
			
			if($month == $st_date__month  && $i%$recurrence_per == 0)
			{				
				$st_date1 = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))). " +$i year");
				if($recurrence_days==0)
					$recurrence_days=1;
				
				$st_date2 = strtotime(date("Y-m-d", $st_date1));
				$st_date = date_i18n('Y-m-d',strtotime(date("Y-m-d", $st_date2)));
				$end_date =  date_i18n("Y-m-d",strtotime(date("Y-m-d", strtotime($st_date)) . " +".$recurrence_days." day"));
				if($tmpl_end_date < strtotime($end_date)){
					$end_date = date_i18n('Y-m-d',strtotime(date("Y-m-d", $tmpl_end_date)));
				}
				templ_update_rec_data($post_data,$post_id,$st_date,$end_date);

			}
			else
			{
				continue;
			}
		}
	}

}


add_action('save_post', 'ptthemes_postcodes_insert');
/*
 * Function Name: attend_event_html
 * Return : Event attending yes or no
 */
function attend_event_html($user_id,$post_id)
{
	global $current_user;
	$post = get_post($post_id);
	$user_meta_data = get_user_meta($current_user->ID,'user_attend_event',true);
	echo get_avatar($current_user->user_email,35);

	if($user_meta_data && in_array('#'.$post_id.'#',$user_meta_data))
	{
		?>
	<span id="attend_event_<?php echo $post_id;?>" class="fav"  > 
	<span class="span_msg"><?php
	if($current_user->ID){
		echo "<a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a>, ".REMOVE_EVENT_MSG." <strong>".$post->post_title."</strong>";
	}else{
		echo "<a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a> ".REMOVE_EVENT_MSG." <strong>".$post->post_title."</strong>";
	}
	
	?>
	
	<span id="attended_persons" class="attended_persons"><?php echo templ_atended_persons($post_id); ?></span>
	</span>
	
	<a href="javascript:void(0);" class="addtofav b_review" onclick="javascript:addToAttendEvent('<?php echo $post_id;?>','remove');"><?php echo REMOVE_EVENT_TEXT;?></a>  
	
	</span>    
		<?php
	}else{
	?>
	<span id="attend_event_<?php echo $post_id;?>" class="fav">
	<span class="span_msg"><?php 
	if($current_user->ID){
		echo "<a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a>,".ATTEND_EVENT_MSG." <strong>".$post->post_title."</strong> ?";
	}else{
		echo "<a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a> ".ATTEND_EVENT_MSG." <strong>".$post->post_title."</strong> ?";
	}
	?>
	<span id="attended_persons" class="attended_persons"><?php echo templ_atended_persons($post_id); ?></span>
	</span>
	
	<a href="javascript:void(0);" class="addtofav b_review"  onclick="javascript:addToAttendEvent(<?php echo $post_id;?>,'add');"><?php echo ATTEND_EVENT_TEXT;?></a>
	
	</span>
	<?php } 
}

/*
Nane " templ_atended_persons
args : post id
description : count how many numbers of users going to attend the event (regular event attenders)
*/
function templ_atended_persons($post_id){
	global $wpdb;
	$qry_results = $wpdb->get_results("select * from $wpdb->usermeta where meta_key LIKE '%user_attend_event%' and meta_value LIKE '%#$post_id#%' ");	
	$peoples = count($qry_results);
	
	if($peoples >0){
		$page_template_url=get_permalink(get_option('recurring_event_page_template_id'));		
		if(strstr($page_template_url,'?'))		
			$userlist_url=$page_template_url.'&eid='.$post_id;
		else
			$userlist_url=$page_template_url.'?eid='.$post_id;
		
		if($peoples == 1){
			return $peoples." <a href='".$userlist_url."' target='_blank'>".__('person is attending.',T_DOMAIN)." </a>";
		}else{
			return $peoples." <a href='".$userlist_url."' target='_blank'>".__('peoples is attending.',T_DOMAIN)." </a>";			
		}
	}else{
		return __('No one is attending yet.',T_DOMAIN);
	}
}
/*
Name : attend_recurring_event_persons
description : list all recurring dates on detail page (recurring event attenders)
*/
function attend_recurring_event_persons($post_id,$st_date,$end_date){
	global $wpdb;	
	$qry_results = $wpdb->get_results("select * from $wpdb->usermeta where meta_key LIKE '%user_attend_event_st_date%' and meta_value LIKE '%$post_id"._."$st_date%'");	
	$peoples = count($qry_results);
	
	if($peoples >0){
		$page_template_url=get_permalink(get_option('recurring_event_page_template_id'));		
		if(strstr($page_template_url,'?'))		
			$userlist_url=$page_template_url.'&eid='.$post_id;
		else
			$userlist_url=$page_template_url.'?eid='.$post_id;
			
			if($peoples == 1){
				return $peoples." <a href='".$userlist_url."' target='_blank'>".__('person is attending.',T_DOMAIN)." </a>";
			}else{
				return $peoples." <a href='".$userlist_url."' target='_blank'>".__('peoples are attending.',T_DOMAIN)." </a>";
			}
	}else{
		return __('No one is attending yet.',T_DOMAIN);
	}	
}
/*
Name : attend_recurring_event_html
description : list all recurring dates on detail page
*/
function attend_recurring_event_html($user_id,$post_id,$st_date,$end_date)
{
	global $current_user,$post;
	$a = "";
	
	$post = get_post($post_id);
	$user_meta_data = get_user_meta($current_user->ID,'user_attend_event',true);
	$user_attend_event_start_date = get_user_meta($current_user->ID,'user_attend_event_st_date',true);
	$user_attend_event_end_date = get_user_meta($current_user->ID,'user_attend_event_end_date',true);
	$a .= get_avatar($current_user->user_email,35);
	if($user_meta_data && in_array("#".$post_id."#",$user_meta_data) && in_array($post_id."_".$st_date,$user_attend_event_start_date) && in_array($post_id."_".$end_date,$user_attend_event_end_date))
	{
		if($current_user->ID){
		$a.="<span id='attend_event_$post_id-$st_date' class='fav' > 
		<span class='span_msg'><a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a>, ".REMOVE_EVENT_MSG." <strong>".$post->post_title."</strong>
		<span id='attend_persons_$post_id-$st_date' class='attended_persons'>".attend_recurring_event_persons($post->ID,$st_date,$end_date)."</span>
		</span>		
		<a href='javascript:void(0)' class='addtofav b_review' onclick='javascript:addToAttendEvent(".$post_id.",\"remove\",\"".$st_date."\",\"".$end_date."\")'>".REMOVE_EVENT_TEXT."</a>   </span>    
	";	
		}else{
		$a.="<span id='attend_event_$post_id-$st_date' class='fav' > 
		<span class='span_msg'><a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a> ".REMOVE_EVENT_MSG."<strong>".$post->post_title."</strong>
		<span id='attend_persons_$post_id-$st_date' class='attended_persons'>".attend_recurring_event_persons($post->ID,$st_date,$end_date)."</span>
		</span>
		
		<a href='javascript:void(0)' class='addtofav b_review' onclick='javascript:addToAttendEvent(".$post_id.",\"remove\",\"".$st_date."\",\"".$end_date."\")'>".REMOVE_EVENT_TEXT."</a>   </span>    
	";	
		}
	}else{
		if($current_user->ID){
		$a.="<span id='attend_event_$post_id-$st_date' class='fav'>
		<span class='span_msg'>"."<a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a>, ".ATTEND_EVENT_MSG." <strong>".$post->post_title."</strong> ?
		<span id='attend_persons_$post_id-$st_date' class='attended_persons'>".attend_recurring_event_persons($post->ID,$st_date,$end_date)."</span>
		</span>
		<a href='javascript:void(0)' class='addtofav b_review'  onclick='javascript:addToAttendEvent(".$post_id.",\"add\",\"".$st_date."\",\"".$end_date."\")'>".ATTEND_EVENT_TEXT."</a></span>";
		}else{
		$a.="<span id='attend_event_$post_id-$st_date' class='fav'>
		<span class='span_msg'>"."<a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a> ".ATTEND_EVENT_MSG." <strong>".$post->post_title."</strong> ?
		<span id='attend_persons_$post_id-$st_date' class='attended_persons'>".attend_recurring_event_persons($post->ID,$st_date,$end_date)."</span>
		</span>
		<a href='javascript:void(0)' class='addtofav b_review'  onclick='javascript:addToAttendEvent(".$post_id.",\"add\",\"".$st_date."\",\"".$end_date."\")'>".ATTEND_EVENT_TEXT."</a></span>";
		}
	} 
	return $a;
}
/*
 *Function Name : recurrence_event
 *Description : start of function for recurrence event.
 */
function recurrence_event($post_id)
{
	
	global $wpdb,$current_user,$post;
	$start_date = strtotime(get_post_meta($post_id,'st_date',true));
	$end_date = strtotime(get_post_meta($post_id,'end_date',true));
	$recurrence_occurs = get_post_meta($post_id,'recurrence_occurs',true);//reoccurence type
	$recurrence_per = get_post_meta($post_id,'recurrence_per',true);//no. of occurence.
	$current_date = date('Y-m-d');
	$recurrence_days = get_post_meta($post_id,'recurrence_days',true);	//on which day
	$recurrence_list = "";
	_e('This is a ',T_DOMAIN);echo $recurrence_occurs;_e(' Event.',T_DOMAIN);	
	if($recurrence_occurs == 'daily' )
	{
		$days_between = ceil(abs($end_date - $start_date) / 86400);
		$recurrence_list .= "<ul>";
		for($i=0;$i<($days_between+1);$i++)
		{
			$class= ($i%2) ? "odd" : "even";
			if(($i%$recurrence_per) == 0 )
			{
				$j = $i+$recurrence_days;
				$st_date1 = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) . " +$i day");
				$st_date = date_i18n(get_option("date_format"), $st_date1);
				$end_date1 = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) . " +$j day");
				$post_end_date  = strtotime(get_post_meta($post_id,'end_date',true));
				if($end_date1 >  $post_end_date)
					$end_date1 = $post_end_date;
				$end_date = date_i18n(get_option("date_format"), $end_date1);
				$st_time = get_formated_time(get_post_meta($post_id,'st_time',true));
				$end_time = get_formated_time(get_post_meta($post_id,'end_time',true));
				
				/*
					fetch child recurring events of parent recurring event
				*/
				$args=
				array( 
				'post_type' => 'event',
				'posts_per_page' => -1	,
				'post_status' => array('private'),
				'post_parent' => $post_id,
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'event_type',
						'value' => 'Regular event',
						'compare' => '=',
						'type'=> 'text'
					)
				),
				'meta_key' => 'st_date',
				'orderby' => 'meta_value_num',
				'meta_value_num'=>'sort_order',
				'order' => 'ASC'
				);
				$post_query = null;
				$post_query = new WP_Query($args);
					if($post_query){
						$post = $post_query->posts[$i];
					}
					
				$recurrence_list .= "<li class=$class>";
				$recurrence_list .= "<div class='date_info'>
				<p>
					  <strong>From</strong>   $st_date $st_time
							  <strong>To </strong>   $end_date.$end_time <br/>
				</p>
								</div>";				
				
				$recurrence_list .= "<div class='attending_event'> ";
				$recurrence_list .= attend_recurring_event_html($post->post_author,$post->ID,date_i18n(get_option("date_format"), $st_date1),date_i18n(get_option("date_format"),$end_date1));
				$recurrence_list .= "	<div class='clearfix'></div>
				
				</div>  ";
						
				$recurrence_list .= "</li>";
			}
			else
			{
				continue;
			}
		}
	}
	if($recurrence_occurs == 'weekly' )
	{
		$recurrence_interval = get_post_meta($post_id,'recurrence_per',true);//no. of occurence.
		$days_between = ceil(abs($end_date - $start_date) / 86400);
		$l = 0;
		$count_recurrence = 0;
		$current_week = 0;
		$recurrence_list .= "<ul>";
		
		if(strstr(get_post_meta($post_id,'recurrence_bydays',true),","))
			$recurrence_byday = explode(',',get_post_meta($post_id,'recurrence_byday',true));	//on which day
		else
			$recurrence_byday = get_post_meta($post_id,'recurrence_byday',true);	//on which day
		$start_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) );
		$end_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'end_date',true))) );
		
		//sort out week one, get starting days and then days that match time span of event (i.e. remove past events in week 1)
		$weekdays = explode(",", get_post_meta($post_id,'recurrence_bydays',true));
		$matching_days = array(); 
		$aDay = 86400;  // a day in seconds
		$aWeek = $aDay * 7;
			$start_of_week = get_option('start_of_week'); //Start of week depends on WordPress
			//first, get the start of this week as timestamp
			$event_start_day = date('w', $start_date);
			$offset = 0;
			if( $event_start_day > $start_of_week ){
				$offset = $event_start_day - $start_of_week; //x days backwards
			}elseif( $event_start_day < $start_of_week ){
				$offset = $start_of_week;
			}
			$start_week_date = $start_date - ( ($event_start_day - $start_of_week) * $aDay );
			//then get the timestamps of weekdays during this first week, regardless if within event range
			$start_weekday_dates = array(); //Days in week 1 where there would events, regardless of event date range
			for($i = 0; $i < 7; $i++){
				$weekday_date = $start_week_date+($aDay*$i); //the date of the weekday we're currently checking
				$weekday_day1 = date('Y-m-d',$weekday_date); //the day of the week we're checking, taking into account wp start of week setting		
				
				$weekday_day = date('w',$weekday_date); //the day of the week we're checking, taking into account wp start of week setting		
				if( in_array( $weekday_day, $weekdays) ){
					$start_weekday_dates[] = $weekday_date; //it's in our starting week day, so add it
				}
			}
			//for each day of eventful days in week 1, add 7 days * weekly intervals
			foreach ($start_weekday_dates as $weekday_date){
				//Loop weeks by interval until we reach or surpass end date
				
				while($weekday_date <= $end_date){
					if( $weekday_date >= $start_date && $weekday_date <= $end_date ){
						$matching_days[] = $weekday_date;
					}											
					//$weekday_date = $weekday_date + strtotime("+$recurrence_interval week", date("Y-m-d",$weekday_date));
					$weekday_date= strtotime("+$recurrence_interval week", $weekday_date);
				}
			}//done!
			sort($matching_days);
			for($z=0;$z<count($matching_days);$z++)
			{
				$class= ($z%2) ? "odd" : "even";
				$st_date1 = $matching_days[$z];
				$st_date = date_i18n(get_option('date_format'), $st_date1);
				$st_end_date = date("Y-m-d", $matching_days[$z]);
				$end_date1 = strtotime(date("Y-m-d", strtotime($st_end_date)) . " +$recurrence_days day");
				$post_end_date  = strtotime(get_post_meta($post_id,'end_date',true));
				if($end_date1 >  $post_end_date)
					$end_date1 = $post_end_date;
				$end_date = date_i18n(get_option('date_format'), $end_date1);
				$st_time = get_formated_time(get_post_meta($post_id,'st_time',true));
				$end_time = get_formated_time(get_post_meta($post_id,'end_time',true));
				/*
					fetch child recurring events of parent recurring event
				*/
				$args=
				array( 
				'post_type' => 'event',
				'posts_per_page' => -1	,
				'post_status' => array('private'),
				'post_parent' => $post_id,
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'event_type',
						'value' => 'Regular event',
						'compare' => '=',
						'type'=> 'text'
					)
				),
				'meta_key' => 'st_date',
				'orderby' => 'meta_value_num',
				'meta_value_num'=>'sort_order',
				'order' => 'ASC'
				);
				$post_query = null;
				$post_query = new WP_Query($args);
					if($post_query->have_posts()){
						global $post;
						$post = $post_query->posts[$z];
					}
				$recurrence_list .= "<li class=$class>";
				$recurrence_list .= "<div class='date_info'>
					<p>
						  <strong>From</strong>   $st_date $st_time
								  <strong>To </strong>   $end_date $end_time <br/>
					</p>
						</div>";				
				$recurrence_list .= "<div class='attending_event'> ";
				$recurrence_list .= attend_recurring_event_html($post->post_author,$post->ID,date_i18n(get_option('date_format'), $st_date1),date_i18n(get_option('date_format'),$end_date1));
				$recurrence_list .= "	<div class='clearfix'></div>
			   </div>  ";
				 
				$recurrence_list .= "</li>";
			}
	}
	
	if($recurrence_occurs == 'monthly' )
	{
		$recurrence_interval = get_post_meta($post_id,'recurrence_per',true);//no. of occurence.
		$days_between = ceil(abs($end_date - $start_date) / 86400);
		$recurrence_byweekno = get_post_meta($post_id,'monthly_recurrence_byweekno',true);	//on which day
		$l = 0;
		$month_week = 0;
		$count_recurrence = 0;
		$current_month = 0;
		$recurrence_list .= "<ul>";
		
			if(strstr(get_post_meta($post_id,'recurrence_bydays',true),","))
				$recurrence_byday = explode(',',get_post_meta($post_id,'recurrence_byday',true));	//on which day
			else
				$recurrence_byday = get_post_meta($post_id,'recurrence_byday',true);	//on which day
			$start_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) );
			$end_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'end_date',true))) );
		
		$matching_days = array(); 
		$aDay = 86400;  // a day in seconds
		$aWeek = $aDay * 7;		 
		$current_arr = getdate($start_date);
		$end_arr = getdate($end_date);
		$end_month_date = strtotime( date('Y-m-t', $end_date) ); //End date on last day of month
		$current_date = strtotime( date('Y-m-1', $start_date) ); //Start date on first day of month
		while( $current_date <= $end_month_date ){
			 $last_day_of_month = date('t', $current_date);
			//Now find which day we're talking about
			$current_week_day = date('w',$current_date);
			$matching_month_days = array();
			//Loop through days of this years month and save matching days to temp array
			for($day = 1; $day <= $last_day_of_month; $day++){
				if((int) $current_week_day == $recurrence_byday){
					$matching_month_days[] = $day;
				}
				$current_week_day = ($current_week_day < 6) ? $current_week_day+1 : 0;							
			}
			//Now grab from the array the x day of the month
			$matching_day = ($recurrence_byweekno > 0) ? $matching_month_days[$recurrence_byweekno-1] : array_pop($matching_month_days);
			$matching_date = strtotime(date('Y-m',$current_date).'-'.$matching_day);
			if($matching_date >= $start_date && $matching_date <= $end_date){
				$matching_days[] = $matching_date;
			}
			//add the number of days in this month to make start of next month
			$current_arr['mon'] += $recurrence_interval;
			if($current_arr['mon'] > 12){
				//FIXME this won't work if interval is more than 12
				$current_arr['mon'] = $current_arr['mon'] - 12;
				$current_arr['year']++;
			}
			$current_date = strtotime("{$current_arr['year']}-{$current_arr['mon']}-1"); 
			
			
		}
		sort($matching_days);
		for($z=0;$z<count($matching_days);$z++)
		{ 
			$class= ($z%2) ? "odd" : "even";
			$st_date1 = $matching_days[$z];
			$st_date = date_i18n(get_option('date_format'), $matching_days[$z]);
			$st_end_date = date("Y-m-d", $matching_days[$z]);
			$end_date1 = strtotime(date("Y-m-d", strtotime($st_end_date)) . " +$recurrence_days day");
			$post_end_date  = strtotime(get_post_meta($post_id,'end_date',true));
			if($end_date1 >  $post_end_date)
				$end_date1 = $post_end_date;
			$end_date = date_i18n(get_option('date_format'), $end_date1);
			$st_time = get_formated_time(get_post_meta($post_id,'st_time',true));
			$end_time = get_formated_time(get_post_meta($post_id,'end_time',true));
			/*
					fetch child recurring events of parent recurring event
				*/
				$args=
				array( 
				'post_type' => 'event',
				'posts_per_page' => -1	,
				'post_status' => array('private'),
				'post_parent' => $post_id,
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'event_type',
						'value' => 'Regular event',
						'compare' => '=',
						'type'=> 'text'
					)
				),
				'meta_key' => 'st_date',
				'orderby' => 'meta_value_num',
				'meta_value_num'=>'sort_order',
				'order' => 'ASC'
				);
				$post_query = null;
				$post_query = new WP_Query($args);
					if($post_query->have_posts()){
						global $post;
						$post = $post_query->posts[$z];
					}
			$recurrence_list .= "<li class=$class>";
			$recurrence_list .= "<div class='date_info'>
			<p>
				  <strong>From</strong>   $st_date $st_time
						  <strong>To </strong>   $end_date $end_time <br/>
			</p>
							</div>";							
			$recurrence_list .= "<div class='attending_event'> ";
			$recurrence_list .= attend_recurring_event_html($post->post_author,$post->ID,date_i18n(get_option("date_format"), $st_date1),date_i18n(get_option("date_format"),$end_date1));
			$recurrence_list .= "	<div class='clearfix'></div>
			</div>  ";						
			$recurrence_list .= "</li>";
		}
			
	}
	if($recurrence_occurs == 'yearly' )
	{
		$date1 = get_post_meta($post_id,'st_date',true);
		$date2 = get_post_meta($post_id,'end_date',true);
		$st_startdate1 = explode("-",$date1);
		$st_year = $st_startdate1[0];
		$st_month = $st_startdate1[1];
		$st_day = $st_startdate1[2];
		$st_date1 = mktime(0, 0, 0, $st_month, $st_day, $st_year);
		$st_date__month = (int)date('n', $st_date1); //get the current month of start date.
		$diff = abs(strtotime($date2) - strtotime($date1));
		$years_between = floor($diff / (365*60*60*24));
		$recurrence_list .= "<ul>";		
		for($i=0;$i<($years_between+1);$i++)
		{
			$class= ($i%2) ? "odd" : "even";
			$startdate = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) . " +$i year");
			$startdate1 = explode("-",date('Y-m-d',$startdate));
			$year = $startdate1[0];
			$month = $startdate1[1];
			$day = $startdate1[2];
			$date2 = mktime(0, 0, 0, $month, $day, $year);
			$month = (int)date('n', $date2); //get the current month.
			if($month == $st_date__month  && $i%$recurrence_per == 0)
			{
				$st_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))). " +$i year");
				$st_date = date_i18n(get_option('date_format'), $st_date);
				
				$end_date = $date2 = mktime(0, 0, 0, $month, $day+$recurrence_days, $year);
				$post_end_date  = strtotime(get_post_meta($post_id,'end_date',true));
				if($end_date >  $post_end_date)
					$end_date = $post_end_date;
				$end_date = date_i18n(get_option('date_format'), $end_date);
				$st_time = get_formated_time(get_post_meta($post_id,'st_time',true));
				$end_time = get_formated_time(get_post_meta($post_id,'end_time',true));
				
				/*
					fetch child recurring events of parent recurring event
				*/
				$args=
				array( 
				'post_type' => 'event',
				'posts_per_page' => -1	,
				'post_status' => array('private'),
				'post_parent' => $post_id,
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'event_type',
						'value' => 'Regular event',
						'compare' => '=',
						'type'=> 'text'
					)
				),
				'meta_key' => 'st_date',
				'orderby' => 'meta_value_num',
				'meta_value_num'=>'sort_order',
				'order' => 'ASC'
				);
				$post_query = null;
				$post_query = new WP_Query($args);
					if($post_query->have_posts()){
						global $post;
						$post = $post_query->posts[$i];
					}
	
				$recurrence_list .= "<li class=$class>";
				$recurrence_list .= "<div class='date_info'>
				<p>
					  <strong>From</strong>   $st_date $st_time
							  <strong>To </strong>   $end_date $end_time <br/>
				</p>
								</div>";
							
				$recurrence_list .= "<div class='attending_event'> ";
				$recurrence_list .= attend_recurring_event_html($post->post_author,$post->ID,date_i18n(get_option('date_format'), $st_date1),date_i18n(get_option('date_format'),$end_date1));
				$recurrence_list .= "	<div class='clearfix'></div>
			    </div>  ";
						 
				$recurrence_list .= "</li>";

			}
			else
			{
				continue;
			}
		}
	}
	return $recurrence_list;
}
/*
 *Function Name : templ_recurrence_dates
 *Description : return recurrence dates.
 */
function templ_recurrence_dates($post_id)
{
	
	global $wpdb,$current_user,$post;
	$start_date = strtotime(get_post_meta($post_id,'st_date',true));
	$end_date = strtotime(get_post_meta($post_id,'end_date',true));
	$recurrence_occurs = get_post_meta($post_id,'recurrence_occurs',true);//reoccurence type
	$recurrence_per = get_post_meta($post_id,'recurrence_per',true);//no. of occurence.
	$current_date = date('Y-m-d');
	$recurrence_days = get_post_meta($post_id,'recurrence_days',true);	//on which day
	$recurrence_list = "";
	
	if($recurrence_occurs == 'daily' )
	{
		$days_between = ceil(abs($end_date - $start_date) / 86400);
		$recurrence_list .= "<ul>";
		for($i=0;$i<($days_between);$i++)
		{
			$class= ($i%2) ? "odd" : "even";
			if(($i%$recurrence_per) == 0 )
			{
				$j = $i+$recurrence_days;
				$st_date1 = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) . " +$i day");
				if($recurrence_days==0)
					$recurrence_days=1;
				for($rd=0;$rd<$recurrence_days;$rd++)
				{
					$st_date2 = strtotime(date("Y-m-d", $st_date1) . " +$rd day");
					$st_date .= date_i18n("Y-m-d", $st_date2).",";
				}
//				$st_date .= date('Y-m-d', $st_date1).",";
			}
			else
			{
				continue;
			}
		}
	}
	if($recurrence_occurs == 'weekly' )
	{
		$recurrence_interval = get_post_meta($post_id,'recurrence_per',true);//no. of occurence.
		$days_between = ceil(abs($end_date - $start_date) / 86400);
		$l = 0;
		$count_recurrence = 0;
		$current_week = 0;
		$recurrence_list .= "<ul>";
		
		if(strstr(get_post_meta($post_id,'recurrence_bydays',true),","))
			$recurrence_byday = explode(',',get_post_meta($post_id,'recurrence_byday',true));	//on which day
		else
			$recurrence_byday = get_post_meta($post_id,'recurrence_byday',true);	//on which day
		$start_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) );
		$end_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'end_date',true))) );
		
		//sort out week one, get starting days and then days that match time span of event (i.e. remove past events in week 1)
		$weekdays = explode(",", get_post_meta($post_id,'recurrence_bydays',true));
		$matching_days = array(); 
		$aDay = 86400;  // a day in seconds
		$aWeek = $aDay * 7;
			$start_of_week = get_option('start_of_week'); //Start of week depends on WordPress
			//first, get the start of this week as timestamp
			$event_start_day = date('w', $start_date);
			$offset = 0;
			if( $event_start_day > $start_of_week ){
				$offset = $event_start_day - $start_of_week; //x days backwards
			}elseif( $event_start_day < $start_of_week ){
				$offset = $start_of_week;
			}
			$start_week_date = $start_date - ( ($event_start_day - $start_of_week) * $aDay );
			//then get the timestamps of weekdays during this first week, regardless if within event range
			$start_weekday_dates = array(); //Days in week 1 where there would events, regardless of event date range
			for($i = 0; $i < 7; $i++){
				$weekday_date = $start_week_date+($aDay*$i); //the date of the weekday we're currently checking
				$weekday_day = date('w',$weekday_date); //the day of the week we're checking, taking into account wp start of week setting
				if( in_array( $weekday_day, $weekdays) ){
					$start_weekday_dates[] = $weekday_date; //it's in our starting week day, so add it
				}
			}
			
			//for each day of eventful days in week 1, add 7 days * weekly intervals
			foreach ($start_weekday_dates as $weekday_date){
				//Loop weeks by interval until we reach or surpass end date
				while($weekday_date <= $end_date){
					if( $weekday_date >= $start_date && $weekday_date <= $end_date ){
						$matching_days[] = $weekday_date;
					}					
					$weekday_date = $weekday_date + strtotime("+$recurrence_interval week", date("Y-m-d",$weekday_date));
				}
			}//done!
			 sort($matching_days);
			 $tmd = count($matching_days);
			 for($z=0;$z<count($matching_days);$z++)
			{
				$class= ($z%2) ? "odd" : "even";
				$st_date1 = $matching_days[$z];
				if($z <= ($tmd-1)){
					if($recurrence_days==0)
						$recurrence_days=1;
					for($rd=0;$rd<$recurrence_days;$rd++)
					{
						$st_date1 = strtotime(date("Y-m-d", $matching_days[$z]) . " +$rd day");
						$st_date .= date_i18n(get_option("date_format"), $st_date1).",";
					}
				}
			}

	}
	
	if($recurrence_occurs == 'monthly' )
	{
		$recurrence_interval = get_post_meta($post_id,'recurrence_per',true);//no. of occurence.
		$days_between = ceil(abs($end_date - $start_date) / 86400);
		$recurrence_byweekno = get_post_meta($post_id,'monthly_recurrence_byweekno',true);	//on which day
		$l = 0;
		$month_week = 0;
		$count_recurrence = 0;
		$current_month = 0;
		$recurrence_list .= "<ul>";
		
			if(strstr(get_post_meta($post_id,'recurrence_bydays',true),","))
				$recurrence_byday = explode(',',get_post_meta($post_id,'recurrence_byday',true));	//on which day
			else
				$recurrence_byday = get_post_meta($post_id,'recurrence_byday',true);	//on which day
			$start_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) );
			$end_date = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'end_date',true))) );
		
		$matching_days = array(); 
		$aDay = 86400;  // a day in seconds
		$aWeek = $aDay * 7;		 
		$current_arr = getdate($start_date);
		$end_arr = getdate($end_date);
		$end_month_date = strtotime( date('Y-m-t', $end_date) ); //End date on last day of month
		$current_date = strtotime( date('Y-m-1', $start_date) ); //Start date on first day of month
		while( $current_date <= $end_month_date ){
			 $last_day_of_month = date('t', $current_date);
			//Now find which day we're talking about
			$current_week_day = date('w',$current_date);
			$matching_month_days = array();
			//Loop through days of this years month and save matching days to temp array
			for($day = 1; $day <= $last_day_of_month; $day++){
				if((int) $current_week_day == $recurrence_byday){
					$matching_month_days[] = $day;
				}
				$current_week_day = ($current_week_day < 6) ? $current_week_day+1 : 0;							
			}
			//Now grab from the array the x day of the month
			$matching_day = ($recurrence_byweekno > 0) ? $matching_month_days[$recurrence_byweekno-1] : array_pop($matching_month_days);
			$matching_date = strtotime(date('Y-m',$current_date).'-'.$matching_day);
			if($matching_date >= $start_date && $matching_date <= $end_date){
				$matching_days[] = $matching_date;
			}
			//add the number of days in this month to make start of next month
			$current_arr['mon'] += $recurrence_interval;
			if($current_arr['mon'] > 12){
				//FIXME this won't work if interval is more than 12
				$current_arr['mon'] = $current_arr['mon'] - 12;
				$current_arr['year']++;
			}
			$current_date = strtotime("{$current_arr['year']}-{$current_arr['mon']}-1"); 
			
		}
		sort($matching_days);
			$tmd = count($matching_days);
			 for($z=0;$z<count($matching_days);$z++)
			{
				$class= ($z%2) ? "odd" : "even";
				$st_date1 = $matching_days[$z];
				date("Y-m-d", $matching_days[$z]);
				if($z <= ($tmd-1)){
					if($recurrence_days==0)
						$recurrence_days=1;
					for($rd=0;$rd<$recurrence_days;$rd++)
					{
						$st_date2 = strtotime(date("Y-m-d", $matching_days[$z]) . " +$rd day");
						$st_date .= date_i18n("Y-m-d", $st_date2).",";
					}
				}
			}
			
	}
	if($recurrence_occurs == 'yearly' )
	{
		$date1 = get_post_meta($post_id,'st_date',true);
		$date2 = get_post_meta($post_id,'end_date',true);
		$st_startdate1 = explode("-",$date1);
		$st_year = $st_startdate1[0];
		$st_month = $st_startdate1[1];
		$st_day = $st_startdate1[2];
		$st_date1 = mktime(0, 0, 0, $st_month, $st_day, $st_year);
		$st_date__month = (int)date('n', $st_date1); //get the current month of start date.
		$diff = abs(strtotime($date2) - strtotime($date1));
		$years_between = floor($diff / (365*60*60*24));
		$recurrence_list .= "<ul>";
		for($i=0;$i<($years_between+1);$i++)
		{
			$class= ($i%2) ? "odd" : "even";
			$startdate = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) . " +$i year");
			$startdate1 = explode("-",date('Y-m-d',$startdate));
			$year = $startdate1[0];
			$month = $startdate1[1];
			$day = $startdate1[2];
			$date2 = mktime(0, 0, 0, $month, $day, $year);
			$month = (int)date('n', $date2); //get the current month.
			
			if($month == $st_date__month  && $i%$recurrence_per == 0)
			{				
				$st_date1 = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))). " +$i year");
				if($recurrence_days==0)
					$recurrence_days=1;
				for($rd=0;$rd<$recurrence_days;$rd++)
				{
					$st_date2 = strtotime(date("Y-m-d", $st_date1) . " +$rd day");
					$st_date .= date_i18n(get_option("date_format"), $st_date2).",";
				}

			}
			else
			{
				continue;
			}
		}
	}
	return $st_date;
}


function add_to_attend_event($post_id,$st_date='',$end_date='')
{
	global $current_user,$post;
	$post = get_post($post_id);
	$user_meta_data = array();
	$user_meta_data = get_user_meta($current_user->ID,'user_attend_event',true);
	$user_meta_data[]= "#".$post_id."#";
	update_user_meta($current_user->ID, 'user_attend_event', $user_meta_data);
	if($st_date)
	{
		$user_meta_start_date = array();
		$user_meta_start_date = get_user_meta($current_user->ID,'user_attend_event_st_date',true);
		$user_meta_start_date[]=$post_id."_".$st_date;
		update_user_meta($current_user->ID, 'user_attend_event_st_date', $user_meta_start_date);
	}
	if($end_date)
	{
		$user_meta_end_date = array();
		$user_meta_end_date = get_user_meta($current_user->ID,'user_attend_event_end_date',true);
		$user_meta_end_date[]=$post_id."_".$end_date;
		update_user_meta($current_user->ID, 'user_attend_event_end_date', $user_meta_end_date);
	}
	
	$user_meta_data = get_user_meta($current_user->ID,'user_attend_event',true);
	$user_attend_event_start_date = get_user_meta($current_user->ID,'user_attend_event_st_date',true);	
	$user_attend_event_end_date = get_user_meta($current_user->ID,'user_attend_event_end_date',true);
	$a .= get_avatar($current_user->user_email,35,35);
	
	if(!$st_date)
	{
		echo '<span class="span_msg"><a href='.get_author_posts_url($current_user->ID).'>'.$current_user->display_name.'</a>, '.REMOVE_EVENT_MSG." <strong>".$post->post_title."</strong>".'<span id="attended_persons" class="attended_persons">'.templ_atended_persons($post_id).'</span>'.'</span><a href="javascript:void(0);" class="addtofav b_review" onclick="javascript:addToAttendEvent(\''.$post_id.'\',\'remove\');">'.REMOVE_EVENT_TEXT.'</a>';exit;	
		}
	elseif($user_meta_data && in_array("#".$post_id."#",$user_meta_data,true) && in_array($post_id."_".$st_date,$user_attend_event_start_date,true) && in_array($post_id."_".$end_date,$user_attend_event_end_date,true))
	{
		echo '<span class="span_msg"><a href='.get_author_posts_url($current_user->ID).'>'.$current_user->display_name.'</a>, '.REMOVE_EVENT_MSG." <strong>".$post->post_title."</strong>".'<span id="attended_persons" class="attended_persons">'.attend_recurring_event_persons($post_id,$st_date,$end_date).'</span>'.'</span><a href="javascript:void(0);" class="addtofav b_review" onclick="javascript:addToAttendEvent(\''.$post_id.'\',\'remove\',\''.$st_date.'\',\''.$end_date.'\');">'.REMOVE_EVENT_TEXT.'</a>';exit;	
	}
}
/*
 * Function Name: remove_from_attend_event
 * Return : Remove attend event
 */
//This function would remove the favorited property earlier
function remove_from_attend_event($post_id,$st_date='',$end_date='')
{
	global $current_user;
	$user_meta_data = array();
	$post= get_post($post_id);
	$user_meta_data = get_user_meta($current_user->ID,'user_attend_event',true);
	if(in_array("#".$post_id."#",$user_meta_data))
	{
		$i = 0;
		$user_new_data = array();
		foreach($user_meta_data as $key => $value)
		{
			
			if("#".$post_id."#" == $value && $i == 0)
			{
				$value= '';
				$i++;
			}else{
				$user_new_data[] = $value;
			}
		}	
		$user_meta_data	= $user_new_data;
	}
	update_user_meta($current_user->ID, 'user_attend_event', $user_meta_data);
	
	$user_attend_event_st_date = array();
	$user_attend_event_st_date = get_user_meta($current_user->ID,'user_attend_event_st_date',true);
	
	if($st_date)
	{
	if(in_array($post_id."_".$st_date,$user_attend_event_st_date))
	{
		$user_new_data = array();
		foreach($user_attend_event_st_date as $key => $value)
		{
			if($post_id."_".$st_date == $value)
			{
				$value= '';
			}else{
				$user_new_data[] = $value;
			}
		}
		$user_attend_event_st_date	= $user_new_data;
	}
	update_user_meta($current_user->ID, 'user_attend_event_st_date', $user_attend_event_st_date);
	
	$user_attend_event_end_date = array();
	$user_attend_event_end_date = get_user_meta($current_user->ID,'user_attend_event_end_date',true);
	if(in_array($post_id."_".$end_date,$user_attend_event_end_date))
	{
		$user_new_data = array();
		foreach($user_attend_event_end_date as $key => $value)
		{
			if($post_id."_".$end_date == $value)
			{
				$value= '';
			}else{
				$user_new_data[] = $value;
			}
		}	
		$user_attend_event_end_date	= $user_new_data;
	}
	update_user_meta($current_user->ID, 'user_attend_event_end_date', $user_attend_event_end_date);
	}
	if(!$st_date)
	{
		echo '<span class="span_msg"><a href='.get_author_posts_url($current_user->ID).'>'.$current_user->display_name.'</a>, '.ATTEND_EVENT_MSG.' <strong>'.$post->post_title.'</strong> ? <span id="attended_persons" class="attended_persons">'.templ_atended_persons($post_id).'</span></span><a class="addtofav b_review" href="javascript:void(0);"  onclick="javascript:addToAttendEvent(\''.$post_id.'\',\'add\');">'.ATTEND_EVENT_TEXT.'</a>';exit;
	}
	else
	{
		echo '<span class="span_msg"><a href='.get_author_posts_url($current_user->ID).'>'.$current_user->display_name.'</a>, '.ATTEND_EVENT_MSG.' <strong>'.$post->post_title.'</strong>? <span id="attended_persons" class="attended_persons">'.attend_recurring_event_persons($post_id,$st_date,$end_date).'</span></span><a class="addtofav b_review" href="javascript:void(0);"  onclick="javascript:addToAttendEvent(\''.$post_id.'\',\'add\',\''.$st_date.'\',\''.$end_date.'\');">'.ATTEND_EVENT_TEXT.'</a>';exit;
	}

}

/*Add action wp_footer for add to attendevent script */
add_action('wp_footer', 'addtoattendevent_script'); 
function addtoattendevent_script()
{
	?>
    <script type="text/javascript">
	/* <![CDATA[ */
	function addToAttendEvent(post_id,action,st_date,end_date)
	{
		<?php 
		global $current_user;
		if($current_user->ID==''){ 
		?>
		window.location.href="<?php echo home_url(); ?>/?page=login&page1=sign_in";
		<?php 
		} else {
		?>
		var fav_url; 
		if(action == 'add')
		{
			if(st_date == 'undefined' || st_date == '')
				fav_url = '<?php echo get_stylesheet_directory_uri(); ?>/functions/ajax_event.php?ptype=favorite&action=add&pid='+post_id;
			else
				fav_url = '<?php echo get_stylesheet_directory_uri(); ?>/functions/ajax_event.php?ptype=favorite&action=add&pid='+post_id+'&st_date='+st_date+'&end_date='+end_date;
		}
		else
		{
			if(st_date == 'undefined' || st_date == '')
				fav_url = '<?php echo get_stylesheet_directory_uri(); ?>/functions/ajax_event.php?ptype=favorite&action=removed&pid='+post_id;
			else
				fav_url = '<?php echo get_stylesheet_directory_uri(); ?>/functions/ajax_event.php?ptype=favorite&action=remove&pid='+post_id+'&st_date='+st_date+'&end_date='+end_date;
			
		}
		var $ac = jQuery.noConflict();
		$ac.ajax({	
			url: fav_url ,
			type: 'GET',
			dataType: 'html',
			timeout: 20000,
			error: function(){
				alert("Error loading user's attending event.");
			},
			success: function(html){	
			<?php 
			if($_REQUEST['list']=='favourite')
			{ ?>
				//document.getElementById('list_property_'+post_id).style.display='none';	
				document.getElementById('post_'+post_id).style.display='none';	
				<?php
			}
			?>
				if(!st_date)
				{
					document.getElementById('attend_event_'+post_id).innerHTML=html;
				}
				else
				{
					document.getElementById('attend_event_'+post_id+'-'+st_date).innerHTML=html;
				}
			}
		});
		return false;
		<?php } ?>
	}
	/* ]]> */
	</script>
	<?php
		
}/** 
name : event_rec_option_items
description : to fetch the recurrence of an event BOF **/
function event_rec_option_items($array, $saved_value) {
	$output = "";
	foreach($array as $key => $item) {
		$selected ='';
		if ($key == $saved_value)
			$selected = "selected='selected'";
		$output .= "<option value='".esc_attr($key)."' $selected >".esc_html($item)."</option>\n";

	}
	echo $output;
}
/** to fetch the recurrence of an event EOF **/

/**
name: event_checkbox_items
desciption : to fetch the days of an event BOF **/
function event_checkbox_items($name, $array, $saved_values, $horizontal = true) {
	$output = "";
	foreach($array as $key => $item) {
		$checked = "";
		if (in_array($key, $saved_values))		
			 $checked = "checked='checked'";
		$output .=  "<input type='checkbox' name='".esc_attr($name)."' value='".esc_attr($key)."' $checked /> ".esc_html($item)."&nbsp; ";
		if(!$horizontal)
			$output .= "<br/>\n";
	}
	echo $output;

}
/** 
name : event_get_hour_format
description : to fetch the hour format of an event **/
function event_get_hour_format(){
	return get_option('date_format_custom') ? "H:i":"h:i A";
}
/** 
name : event_get_days_names
description : to fetch the days name **/
function event_get_days_names(){
	return array ( 0 => __( 'Sun',T_DOMAIN ), 1 => __( 'Mon',T_DOMAIN ), 2 => __( 'Tue',T_DOMAIN ), 3 => __( 'Wed',T_DOMAIN ), 4 => __( 'Thu',T_DOMAIN ), 5 => __( 'Fri',T_DOMAIN ), 6 => __( 'Sat',T_DOMAIN ) );
}
/*
name : recurring_event_js
description : include recurring_event_js for recurring events
*/
add_action('wp_footer','recurring_event_js');
add_action('admin_head','recurring_event_js');
function recurring_event_js(){
	wp_enqueue_script('recurring_js', get_stylesheet_directory_uri().'/js/recurring_event.js');
}
/*
name : tmpl_get_recurring
description : fetch html for recurring events
*/
add_action('tmpl_custom_fields_event_type_after','tmpl_get_recurring');
function tmpl_get_recurring($event_type='Recurring event')
{
	global $post;
	$name_of_day = event_get_days_names();
	$hours_format = event_get_hour_format();
	if($_SESSION['custom_fields'] && $_REQUEST['backandedit'])
	{
		$event_type = $_SESSION['custom_fields']['event_type'];
		$recurrence_occurs = $_SESSION['custom_fields']['recurrence_occurs'];
		$recurrence_per = $_SESSION['custom_fields']['recurrence_per'];
		$recurrence_onday = $_SESSION['custom_fields']['recurrence_onday'];
		$recurrence_onweekno = $_SESSION['custom_fields']['recurrence_onweekno'];
		$recurrence_days = $_SESSION['custom_fields']['recurrence_days'];
		$recurrence_byday = $_SESSION['custom_fields']['recurrence_byday'];
		$monthly_recurrence_byweekno = $_SESSION['custom_fields']['monthly_recurrence_byweekno'];
	}
	elseif(strstr($_SERVER['REQUEST_URI'],'wp-admin') && isset($_REQUEST['action']) && isset($_GET['post']))
	{
		$event_type = get_post_meta(@$_GET['post'],'event_type',true);
		$recurrence_occurs = get_post_meta(@$_GET['post'],'recurrence_occurs',true);
		$recurrence_per = get_post_meta(@$_GET['post'],'recurrence_per',true);
		$recurrence_onday = get_post_meta(@$_GET['post'],'recurrence_onday',true);
		$recurrence_onweekno = get_post_meta(@$_GET['post'],'recurrence_onweekno',true);
		$recurrence_days = get_post_meta(@$_GET['post'],'recurrence_days',true);
		$monthly_recurrence_byweekno = get_post_meta(@$_GET['post'],'monthly_recurrence_byweekno',true);
		$recurrence_byday = get_post_meta(@$_GET['post'],'recurrence_bydays',true);
	}
	else
	{
		$event_type = get_post_meta(@$_REQUEST['pid'],'event_type',true);
		$recurrence_occurs = get_post_meta(@$_REQUEST['pid'],'recurrence_occurs',true);
		$recurrence_per = get_post_meta(@$_REQUEST['pid'],'recurrence_per',true);
		$recurrence_onday = get_post_meta(@$_REQUEST['pid'],'recurrence_onday',true);
		$recurrence_onweekno = get_post_meta(@$_REQUEST['pid'],'recurrence_onweekno',true);
		$recurrence_days = get_post_meta(@$_REQUEST['pid'],'recurrence_days',true);
		$monthly_recurrence_byweekno = get_post_meta(@$_REQUEST['pid'],'monthly_recurrence_byweekno',true);
		$recurrence_byday = get_post_meta(@$_REQUEST['pid'],'recurrence_byday',true);
	}
?>
	<div class="form_row clearfix" id="recurring_event" <?php if(trim(strtolower($event_type)) == trim(strtolower('Recurring event'))){  ?>style="display:inline-block;" <?php }else{ ?> style="display:none;zoom:1" <?php } ?>>
		 <div class="clearfix">
			 <div class="form_row clearfix">
			 <label><?php _e('Event will repeat',T_DOMAIN); ?></label>
			 <select id="recurrence-occurs" name="recurrence_occurs">
				<?php
					$rec_options = array ("daily" => __ ( 'Daily', T_DOMAIN ), "weekly" => __ ( 'Weekly', T_DOMAIN ), "monthly" => __ ( 'Monthly', T_DOMAIN ), 'yearly' => __('Yearly',T_DOMAIN) );
					event_rec_option_items ( $rec_options,$recurrence_occurs); 
				echo @$recurrence_occurs; ?>
			</select>
            </div>
            
            
			<label><?php _e ( 'every', T_DOMAIN )?></label>
			<input type="text" id="recurrence-per" name='recurrence_per' size='2' value='<?php echo $recurrence_per ; ?>'/>
			<span id="rec-ocr-error" class="error" style="display:none;"><?php _e('It will be better to select regular event for single day.',T_DOMAIN); ?></span>
			<span class='rec-span' id="recurrence-perday" <?php if((@$recurrence_occurs =='daily' && @$recurrence_per == 1) || !$recurrence_occurs){ ?>style="display:inline-block;"<?php }else{ ?>style="display:none;"<?php } ?>><?php _e ( 'day', T_DOMAIN )?></span>
			<span class='rec-span' id="recurrence-perdays" <?php  if(@$recurrence_occurs =='daily' && @$recurrence_per > 1){ ?>style="display:inline-block;"<?php }else{ ?>style="display:none;"<?php } ?>><?php _e ( 'days', T_DOMAIN ) ?></span>
			
			<span class='rec-span' id="recurrence-perweek" <?php if(@$recurrence_occurs =='weekly' && @$recurrence_per == 1){ ?>style="display:inline-block;"<?php }else{ ?>style="display:none;"<?php } ?>><?php _e ( 'week on', T_DOMAIN); ?></span>
			<span class='rec-span' id="recurrence-perweeks" <?php if(@$recurrence_occurs =='weekly' && @$recurrence_per > 1){ ?>style="display:inline-block;"<?php }else{ ?>style="display:none;"<?php } ?>><?php _e ( 'weeks on', T_DOMAIN); ?></span>

			<span class='rec-span' id="recurrence-permonth" <?php if(@$recurrence_occurs =='monthly' && @$recurrence_per == 1){ ?>style="display:inline-block;"<?php }else{ ?>style="display:none;"<?php } ?>><?php _e ( 'month on the', T_DOMAIN )?></span>
			<span class='rec-span' id="recurrence-permonths" <?php if(@$recurrence_occurs =='monthly' && @$recurrence_per > 1){ ?>style="display:inline-block;"<?php }else{ ?>style="display:none;"<?php } ?>><?php _e ( 'months on the', T_DOMAIN )?></span>

			
			<span class='rec-span' id="recurrence-peryear" <?php if(@$recurrence_occurs =='yearly' && @$recurrence_per == 1){   ?>style="display:inline-block;"<?php }else{ ?>style="display:none;"<?php } ?>><?php _e ( 'year', T_DOMAIN )?></span> 
			<span class='rec-span' id="recurrence-peryears" <?php if(@$recurrence_occurs =='yearly' && @$recurrence_per > 1){   ?>style="display:inline-block;"<?php }else{ ?>style="display:none;"<?php } ?>><?php _e ( 'years', T_DOMAIN ) ?></span>

		 </div>
						 
		 <div class="form_weekly_days form_row clearfix" id="weekly-days" <?php  if(@$recurrence_occurs =='weekly' || $recurrence_occurs =='weekly'){  ?>style="display:inline-block;"<?php }else{ ?>style="display:none;"<?php } ?>>
			<?php
				$saved_bydays =  explode ( ",", $recurrence_byday ); 
				event_checkbox_items ( 'recurrence_bydays[]', $name_of_day, $saved_bydays ); 
			?>
		 </div>
		<div class="form_row monthly_opt_container" id="monthly_opt_container" <?php if($recurrence_occurs =='monthly'){ ?>style="display:inline-block;"<?php }else{ ?>style="display:none;"<?php } ?>>
			<select id="monthly-modifier" name="monthly_recurrence_byweekno">
				<?php
					$weeks_options = array ("1" => __ ( 'first', T_DOMAIN ), '2' => __ ( 'second', T_DOMAIN ), '3' => __ ( 'third', T_DOMAIN ), '4' => __ ( 'fourth', T_DOMAIN ), '-1' => __ ( 'last', T_DOMAIN ) ); 
					event_rec_option_items ( $weeks_options, $monthly_recurrence_byweekno  ); 
				?>
			</select>
			<select id="recurrence-weekday" name="recurrence_byday">
				<?php event_rec_option_items ( $name_of_day, $recurrence_byday  ); ?>
			</select>
			<?php _e('of each month',T_DOMAIN); ?>
			&nbsp;
		</div>
		
        <div class="form_last_days form_row clearfix">
			<label><?php _e('Each event ends after ',T_DOMAIN); ?></label>
			<input id="end_days" type="text"  maxlength="8" name="recurrence_days" value="<?php echo $recurrence_days; ?>" />
			<?php _e('day(s)',T_DOMAIN); ?>
		</div>
		
	</div>
<?php
}

/**/
function search_form($class,$date_id,$radius,$distance)
{		
	$form_url = site_url()."/";	
	?>
	<script>
	function search_filter()
	{
		var sr = '';
		
		if(document.getElementById('search').value=='<?php _e("Which event you like to search?",T_DOMAIN);?>')
		{
			document.getElementById('search').value = '';
		}else
		{
			sr = document.getElementById('search').value;
		}
		if(sr)
		{
			document.getElementById('search_id').value = sr;
		}else
		{
			document.getElementById('search_id').value = ' ';
		}
		templatic_nightlife_checkform();
	}
	</script>
    <div class="<?php echo $class;?>">
        <form method="get" id="searchform" name="searchform" action="<?php echo esc_url( home_url( '/' ) );?>" >
            <input type="text" name="search" id="search" class="input_white"  placeholder="<?php _e('Which event you like to search?',T_DOMAIN); ?>" />
            <input type="hidden" name="post_type" value="<?php echo CUSTOM_POST_TYPE_EVENT;?>" />
            <input type="text" id="<?php echo $date_id;?>" name="date" class="input_grey when" placeholder="<?php _e('When?','templatic'); ?>" />
            <input type="text" name="location" id="location" autocomplete="off" class="input_grey where"  placeholder="<?php _e('Where?','templatic'); ?>"/>
            <input type="hidden" name="radius"  value="<?php echo $radius; ?>" />
            <input type="hidden" name="distance"   value="<?php echo $distance; ?>" />
			<input type="hidden" name="is_search"   value="1" />
			<input type="hidden" name="s" value="" id="search_id" />
            <input type="submit" value="" name="" class="submit" onclick="return search_filter();" />
        </form>
		<script type="text/javascript">
			function templatic_nightlife_checkform(){
				jQuery.noConflict();
				var search = jQuery('#s').val();
				var when = jQuery('#<?php echo $date_id;?>').val();
				var where = jQuery('#location').val();
				
				if(search==""){
					jQuery('#s').val('<?php _e('Please enter word you want to search',T_DOMAIN); ?>');
					return false;
				}else if(search=="<?php _e('Which event you like to search?',T_DOMAIN); ?>"){
					jQuery('#s').val('<?php _e('Please enter word you want to search',T_DOMAIN); ?>');
					return false;
				}else{
					if(jQuery('.when').val()=="When?"){jQuery('.when').val('');}
					if(where=='Where?'){jQuery('#location').val('');}
					return true;
				}
			}
			var browserName=navigator.appName; 
		if(browserName =='Microsoft Internet Explorer' ){
			jQuery(function(){
				jQuery("#s").val('<?php _e('Which event you like to search?',T_DOMAIN); ?>');
				jQuery("#s").focus(function(){
				this.select();
			});
			jQuery("#s").click(function(){
			jQuery("#s").val('');
			});
		
				jQuery("#slider_search_date").val('When?');
				jQuery("#slider_search_date").focus(function(){
				this.select();
				});
				jQuery("#slider_search_date").click(function(){
				jQuery("#slider_search_date").val('');
				});
				
				jQuery("#location").val('Where?');
				jQuery("#location").focus(function(){
				this.select();
				});
				jQuery("#location").click(function(){
				jQuery("#location").val('');
				});
			})
		}
		</script>
    </div>			
	<?php					
	return $searh_form;
}
/*
 * Function Name: get_authorlisting_evnets
 * Return : Number of author event
 */
function get_authorlisting_evnets($cur_author){
	global $wpdb;
	$post_count = 0;
	$post_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = '" . $cur_author . "' AND post_type = 'event' AND post_status = 'publish'");
	return $post_count;
}
/*
 * Funtion Name : _iscurlinstalled
 * Description : Returns tru/false , check CURL is enable or not
 */
function _iscurlinstalled() {
	if  (in_array  ('curl', get_loaded_extensions())) {
		return true;
	}
	else{
		return false;
	}
}
/*
 * Function Name : facebook_events
 * arguments : $user_id as user id
 * Description : Returns facebook events
*/

function facebook_events($user_id)
{
	$appID = get_user_meta($user_id,'appID',true);	
	$secret = get_user_meta($user_id,'secret',true);
	$pageID = get_user_meta($user_id,'pageID',true);	
	$config = array(
		'appId' => $appID,
		'secret' => $secret,
	  );
	 if(class_exists('Facebook')){
	 $facebook = new Facebook($config);
	 $user_id = $facebook->getUser();
	if(_iscurlinstalled())
	{
		if($appID) 
		{

		  /*  We have a user ID, so probably a logged in user.
		   If not, we'll get an exception, which we handle below. */
		  try {
		  

		/* just a heading once it creates an event */
		$fql    =   "SELECT eid,name, pic, start_time, end_time, location, description 
				FROM event WHERE eid IN ( SELECT eid FROM event_member WHERE uid = $pageID ) 
				ORDER BY start_time asc";			
				$param  =   array(
				'method'    => 'fql.query',
				'query'     => $fql,
				'callback'  => '');
				$fqlResult   =   $facebook->api($param);
	
	if(!$fqlResult)
	{?>
		 <p class="message" ><?php echo NO_FACEBOOK_EVENT;?> </p> 
	<?php }
	
	/* looping through retrieved data */
	foreach( $fqlResult as $keys => $values ){
		/* see here for the date format I used
		The pattern string I used 'l, F d, Y g:i a'
		will output something like this: July 30, 2015 6:30 pm */

		/* getting 'start' and 'end' date,
		'l, F d, Y' pattern string will give us
		something like: Thursday, July 30, 2015 */
		$start_date = date_i18n( get_option("date_format"), $values['start_time'] );
		$end_date = date_i18n( get_option("date_format"), $values['end_time'] );

		/* getting 'start' and 'end' time
		'g:i a' will give us something
		like 6:30 pm */
		$start_time = date(get_option("tinme_format"), $values['start_time'] );
		$end_time = date( get_option("time_format"), $values['end_time'] );

		//printing the data
		$link = "http://www.facebook.com/events/".$values['eid'];
	   echo "<div class='facebook_event  clearfix'>";
			echo "<a class='event_img'><img  src={$values['pic']} /></a>";
	   		echo "<div class='fb_content'>";
			echo "<h3><a href='".$link."'>{$values['name']}</a></h3>";
			echo "<p class='date'> ";
			if( $start_date == $end_date ){
				/* if $start_date and $end_date is the same
				it means the event will happen on the same day
				so we will have a format something like:
				July 30, 2015 - 6:30 pm to 9:30 pm */
				echo "<span>Start date :</span> {$start_date} "."<br/>";
			}else{
				echo "<span>Start date :</span> {$start_date}"."<br/>";
				echo "<span>End date : </span>{$end_date}"."<br/>";
			}
			echo "<span>Time : </span>{$start_time} - {$end_time}"."<br/>";
			if($values['location']){
			echo "<span>Location</span> : " . $values['location'] . "<br/>";
			}
			if($values['description']){
			echo "<span>More Info : </span>" . $values['description'] ;
			}
			echo "</p>";
			echo "</div>";
		echo "</div>";
	
		
	}
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	<script type='text/javascript'>
	//just to add some hover effects
	$(document).ready(function(){

	$('.event').hover(
		function () {
			$(this).css('background-color', '#CFF');
		}, 
		function () {
			$(this).css('background-color', '#E3E3E3');
		}
	);

	});</script>
	<?php
			/* FQL queries return the results in an array, so we have to get the user's name from the first element in the array. */
		   

		  } catch(FacebookApiException $e) {
			/* If the user is logged out, you can have a user ID even though the access token is invalid.In this case, we'll get an exception, so we'll just ask the user to login again here. */
			$login_url = $facebook->getLoginUrl(); 
			echo 'Please <a href="' . $login_url . '">login.</a>';
			error_log($e->getType());
			error_log($e->getMessage());
		  }   
		}
		}else{
			_e('Facebook Plugin not installed.',T_DOMAIN);
		}
	 }else
	 {
		_e('<p class="error">CURL is not installed on your server, please enbale CURL to use Facebook evenst API.</p>',T_DOMAIN);
	 }
}

/*
 * Function Name : facebook_events
 * Description : Returns facebook events for page template
*/

function facebook_events_template()
{
	global $post;		
	$appID = get_post_meta($post->ID,'facebook_app_id',true);
	$secret = get_post_meta($post->ID,'facebook_secret_id',true);
	$pageID = get_post_meta($post->ID,'facebook_page_id',true);			
	
	$config = array(
		'appId' => $appID,
		'secret' => $secret,
	  );
	if(_iscurlinstalled())
	{
	 if(class_exists('Facebook')){
	 $facebook = new Facebook($config);
	 $user_id = $facebook->getUser();
	
		if($appID) 
		{

		  /*  We have a user ID, so probably a logged in user.
		   If not, we'll get an exception, which we handle below. */
		  try {
		  

		/* just a heading once it creates an event */
			$fql    =   "SELECT eid,name, pic, start_time, end_time, location, description 
				FROM event WHERE eid IN ( SELECT eid FROM event_member WHERE uid = $pageID ) 
				ORDER BY start_time asc";			
				$param  =   array(
				'method'    => 'fql.query',
				'query'     => $fql,
				'callback'  => '');
				$fqlResult   =   $facebook->api($param);
	
	if(!$fqlResult)?>
		 <p class="message" ><?php echo NO_FACEBOOK_EVENT;?> </p> 
	<?php
	
	/* looping through retrieved data */
	foreach( $fqlResult as $keys => $values ){
		/* see here for the date format I used
		The pattern string I used 'l, F d, Y g:i a'
		will output something like this: July 30, 2015 6:30 pm */

		/* getting 'start' and 'end' date,
		'l, F d, Y' pattern string will give us
		something like: Thursday, July 30, 2015 */
		$start_date = date_i18n( get_option("date_format"), $values['start_time'] );
		$end_date = date_i18n( get_option("date_format"), $values['end_time'] );

		/* getting 'start' and 'end' time
		'g:i a' will give us something
		like 6:30 pm */
		$start_time = date( get_option("time_format"), $values['start_time'] );
		$end_time = date( get_option("time_format"), $values['end_time'] );

		//printing the data
		$link = "http://www.facebook.com/events/".$values['eid'];
	   echo "<div class='facebook_event  clearfix'>";
			echo "<a class='event_img'><img  src={$values['pic']} /></a>";
	   		echo "<div class='fb_content'>";
			echo "<h3><a href='".$link."'>{$values['name']}</a></h3>";
			echo "<p class='date'> ";
			if( $start_date == $end_date ){
				/* if $start_date and $end_date is the same
				it means the event will happen on the same day
				so we will have a format something like:
				July 30, 2015 - 6:30 pm to 9:30 pm */
				echo "<span>Start date :</span> {$start_date} "."<br/>";
			}else{
				echo "<span>Start date :</span> {$start_date}"."<br/>";
				echo "<span>End date : </span>{$end_date}"."<br/>";
			}
			echo "<span>Time : </span>{$start_time} - {$end_time}"."<br/>";
			if($values['location']){
			echo "<span>Location</span> : " . $values['location'] . "<br/>";
			}
			if($values['description']){
			echo "<span>More Info : </span>" . $values['description'] ;
			}
			echo "</p>";
			echo "</div>";
		echo "</div>";

		
	}
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	<script type='text/javascript'>
	//just to add some hover effects
	$(document).ready(function(){

	$('.event').hover(
		function () {
			$(this).css('background-color', '#CFF');
		}, 
		function () {
			$(this).css('background-color', '#E3E3E3');
		}
	);

	});</script>
	<?php
			/* FQL queries return the results in an array, so we have to get the user's name from the first element in the array. */
		   

		  } catch(FacebookApiException $e) {
			/* If the user is logged out, you can have a user ID even though the access token is invalid.In this case, we'll get an exception, so we'll just ask the user to login again here. */
			$login_url = $facebook->getLoginUrl(); 
			echo 'Please <a href="' . $login_url . '">login.</a>';
			error_log($e->getType());
			error_log($e->getMessage());
		  }   
		}
		}else{
			_e('Facebook Plugin not installed.',T_DOMAIN);
		}
	}else
	{
		_e('<p class="error">CURL is not installed on your server, please enbale CURL to use Facebook evenst API.</p>',T_DOMAIN);
	}
}
/* add action to display gravtar in blog listing page */
if(!is_singular() && @$_REQUEST['adv_search'] != 1 ){
	add_action("{$prefix}_open_entry",'templ_display_avatar');
}

add_action( "{$prefix}_close_main", 'templ_front_sidebar' );
add_filter( "{$prefix}_byline", 'visual_byline' );
add_filter( "{$prefix}_entry_meta", 'visual_entry_meta' );
/*
 * Function Name : templa_display_avatar
 * Description : Return the gravar of post author speciall for blog listing page 
*/
function templ_front_sidebar(){
	if(is_home() && @$_REQUEST['page'] == ''){
	if ( is_active_sidebar( 'front_sidebar' ) ) :
	do_atomic( 'before_sidebar_primary' ); // supreme_before_sidebar_primary ?>

	<div id="sidebar-primary" class="sidebar">

		<?php do_atomic( 'open_sidebar_primary' ); // supreme_open_sidebar_primary ?>

		<?php dynamic_sidebar( 'front_sidebar' ); ?>

		<?php do_atomic( 'close_sidebar_primary' ); // supreme_close_sidebar_primary ?>

	</div><!-- #sidebar-primary -->

	<?php do_atomic( 'after_sidebar_primary' ); // supreme_after_sidebar_primary 
	endif; 
	}
}
function templ_display_avatar(){
	global $post;
	if($post->post_type !='page' && !is_search()){
		$user = get_user_by('id', $post->post_author);
		echo get_avatar($post->post_author);
		echo "<span class='top_line'>".apply_atomic_shortcode( 'entry_meta_category',  __( '[entry-terms taxonomy="category"]', T_DOMAIN )  );
		echo "<a href=".get_author_posts_url($post->post_author).">".$user->user_nicename."</a></span>";
	}
	
}
function visual_byline(){
	echo "";
}
function templ_display_content(){
	global $post; 
	echo get_the_content($post->ID);
}
function visual_entry_meta()
{
	global $post;
	echo "<div class='entry-meta'>";
	if(!is_single()){ echo '<a class="moretag" href="'. get_permalink($post->ID) . '">'.__('Read more &raquo;',T_DOMAIN).'</a>'; }elseif(is_single()){ echo apply_atomic_shortcode( 'entry_meta_category', __( 'Filed under: [entry-terms taxonomy="category"] [entry-terms taxonomy="post_tag" before="and Tagged: "]', T_DOMAIN )); } echo '<span class="post_date">'.get_formated_date($post->post_date)."</span></div>";		

}
add_action("{$prefix}_close_menu_primary",'after_primary_menu');
function after_primary_menu()
{
	dynamic_sidebar('primary_menu_content');
}

add_action('templ_before_preview_container_breadcrumb','templ_preview_page_sidebar');
add_action('templ_before_success_container_breadcrumb','templ_success_page_sidebar');
/* success page breadcrumbs */
function templ_success_page_sidebar(){
	echo "<div class='breadcrumb'>";
	_e('Home &rarr; Success','nightlife');
	echo "</div>";
}


function templ_preview_page_sidebar(){
	echo "<div class='breadcrumb'>";
	_e('Home &rarr; Preview','nightlife');
	echo "</div>";
}

/*
name : get_content_in_wp_pointer
description : templatic theme is activated and Tevolution is not installed than show pointer to download it.
*/
function get_content_in_wp_pointer() 
{
	
	if(function_exists('is_active_addons'))
	{ 
		if(!is_active_addons('custom_taxonomy') || !is_active_addons('custom_fields_templates'))
		{
			$pointer_content = '<h3>' . __( 'Welcome To Nightlife!.', T_DOMAIN ) . '</h3>';
			$pointer_content .= '<p>' . __( 'Thank you for activating Tevolution . Please activate Custom post type manage and Custom field manager to use wordPress nightlife theme at its best.', T_DOMAIN ) . '</p>';
			$templatic_url = __('Next',T_DOMAIN);
			$download_url = site_url()."/wp-admin/admin.php?page=templatic_system_menu&WpEcoWorldactive";
		}
	}
	else
	{ 
		if(!file_exists(ABSPATH."wp-content/plugins/Tevolution/templatic.php")){
			$pointer_content = '<h3>' . __( 'Welcome To Nightlife!.', T_DOMAIN ) . '</h3>';
			$pointer_content .= '<p>' . __( 'Thank you for installing Nightlife - To use wordPress NightLife theme you have to install templatic Tevolution plugin too.', T_DOMAIN ) . '</p>';
		
			$templatic_url = __('Download WpEcoWprld',T_DOMAIN);
			$download_url = "http://templatic.com";
		}else{
			if(!isset($_REQUEST['nightlife_tour_step']) && $_REQUEST['nightlife_tour_step'] == ''){
				$pointer_content = '<h3>' . __( 'Welcome To Nightlife!.', T_DOMAIN ) . '</h3>';
				$pointer_content .= '<p>' . __( 'Thank you for installing Nightlife - We find Tevolution in your plugin directory, Please activate it.', T_DOMAIN ) . '</p>';
				$templatic_url = __('Activate',T_DOMAIN);
				$pointer_id = 'templatic_plugin';
				$download_url = site_url()."/wp-admin/plugins.php?nightlife_tour_step=1";
				$done = true;
				$postion = false;
			
			}elseif(isset($_REQUEST['nightlife_tour_step']) && $_REQUEST['nightlife_tour_step'] == '1'){
				$pointer_content = '<h3>' . __( 'Activate Tevolution Plugin', T_DOMAIN ) . '</h3>';
				$pointer_content .= '<p>' . __( 'Please activate Tevolution plugin to start your journey of <b>nightlife</b>.', T_DOMAIN ) . '</p>';
				$templatic_url = __('Next',T_DOMAIN);
				$pointer_id = 'tevolution';
				$download_url = site_url()."/wp-admin/widgets.php?nightlife_tour_step=2";
				$done = false;
				$postion = true;
			
			}
		}
		
	}?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
		$('#<?php echo $pointer_id ?>').pointer({
		content: '<?php echo $pointer_content; ?>',
		position: {
		<?php if($postion) { ?>
					edge: 'left',
					align: 'center',
					at: 'left center',
					offset: '150 -15'
		<?php } else{?>
					my: 'left top',
					at: 'center bottom',
					offset: '-25 0'
		<?php } ?>
			},buttons: function( event, t ) {
		
			var $buttonClose = jQuery('<a class="button-secondary" style="margin-right:10px;" href="#">Dismiss</a>');
					$buttonClose.bind( 'click.pointer', function() {
						
						$.post( ajaxurl, {
						pointer: 'templatic_ecosystem_plugin',
						action: 'dismiss-wp-pointer'
					});
						t.element.pointer('close');
					});
		
				<?php if($done) { ?>
					var $buttonNext = $('<a class="button-primary" href="<?php echo $download_url; ?>#<?php echo $pointer_id ?>"><?php echo $templatic_url; ?></a>');
				<?php } ?>
					var buttons = $('<div class="tiptour-buttons">');
					<?php if($done) { ?> buttons.append($buttonNext); <?php } ?>
					buttons.append($buttonClose);
					return buttons;
			
			
		}		
			}).pointer('open');
		});
		//]]>
	</script>
<?php
}

function fb_enqueue_wp_pointer( $hook_suffix ) 
{
	$enqueue = FALSE;
	$templatic_WpEcoWorld_plugin_seen_it = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	// at first assume we don't want to show pointers
	$do_add_script = false;
	// Handle our first pointer announcing the plugin's new settings screen.
	if ( ! in_array( 'templatic_ecosystem_plugin', $templatic_WpEcoWorld_plugin_seen_it ) && !isset($_REQUEST['WpEcoWorldactive']) ) 
	{
		$enqueue = TRUE;
		$do_add_script = true;
		// hook to function that will output pointer script just for templatic_ecosystem_plugin
		add_action( 'admin_print_footer_scripts','get_content_in_wp_pointer' );
	}
	
	// at first assume we don't want to show pointers
	$do_add_script = false;
	
	// in true, include the scripts
	if ( $enqueue ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'utils' ); // for user settings
	}
	wp_enqueue_script ("jhide", "<script> jQuery('#postimagediv').hide();  </script>",11);
	wp_enqueue_script ("divhide", "<script> jQuery('#post-stylesheets').hide(); </script>",20);
	wp_enqueue_script ("divtaghide", "<script> jQuery('#tagsdiv-etags').hide(); </script>",19);
}
add_action( 'admin_enqueue_scripts', 'fb_enqueue_wp_pointer' );

/* add action with woocommerce only */
if(is_plugin_active('woocommerce/woocommerce.php')){
	add_action('admin_init','woocommerce_compatitbility');
}
/*
name : woocommerce_compatitbility
desc : add meta box in add event page if woocommerce is activated
*/
function woocommerce_compatitbility(){
	if(strstr($_SERVER['REQUEST_URI'],'/wp-admin/')){
		add_meta_box( 'woocommerce_templatic_prds', __('Select Events Ticket',DOMAIN), 'woocommerce_templatic_prds', 'event', 'side', 'core', '');
		add_action('save_post','woocommerce_templatic_events_save');
	}
	
}

/*
name : woocommerce_templatic_prds
desc : html for adding metabox, return metabox
*/
function woocommerce_templatic_prds($post_id){
	global $wpdb,$post_id;
	$get_prds = get_posts(array('post_type'=>'product'));
	$prd_id = get_post_meta($post_id,'templ_event_ticket',true);
	$templ_event_ticket_ids = get_post_meta($post_id,'templ_event_ticket_ids',true);
	
	echo "<div style='margin:0px 0px 15px 10px;'>";
	echo "<select name='event_ticket_for' id='event_ticket_for' class='clearfix' style='padding:2px;  width:80%;'>";
	echo "<option value=''>Select a ticket</option>";
	foreach($get_prds as $event_d){
		setup_postdata($event_d);
		if(trim($prd_id) == $event_d->ID){ $selected = 'selected=selected';}else{ $selected='';}
		echo "<option value='".$event_d->ID."' $selected>".$event_d->post_title."</option>";	
	}
	echo "</select>";
	echo "<div class='clearfix'></div><div class='clearfix'></div><br/>";
	$total_tickets = explode(',',$templ_event_ticket_ids);
	$booked_tickets = get_post_meta($post_id,'templ_event_ticket_booked',true);
	if($booked_tickets){ $booked_tickets = explode(',',$booked_tickets);}
	if($templ_event_ticket_ids !=''){ // display generated ticket id 
		$available_tckts = get_post_meta($prd_id,'_stock',true);
		echo "<b>".$available_tckts."</b> "; _e('tickets are available.',DOMAIN); echo "<br/>";
	}
	echo "</div>";
}
/*
Name : woocommerce_templatic_events_save
Desc : save events of tickets 
*/
function woocommerce_templatic_events_save($post_id){
	global $wpdb,$post_id;
	$prd_id =  $_POST['event_ticket_for'];

	$booked_tickets =  $_POST['templ_event_ticket_booked'];
	if($booked_tickets){
		$booked_tickets =  implode(',',$_POST['templ_event_ticket_booked']);
	}
	$qty = get_post_meta($prd_id,'_stock',true);
	if($qty !=''){
		for($i=1 ; $i <= $qty; $i++){
			$tickets .= "E".$post_id.$i.",";
		}
	} 
	update_post_meta($post_id,'templ_event_ticket',$prd_id);
	update_post_meta($prd_id,'templ_prd_for_ticket',$post_id);// update product to set the event for the product
	update_post_meta($post_id,'templ_event_ticket_ids',$tickets); // total ticktes generated
	update_post_meta($post_id,'templ_event_ticket_booked',$booked_tickets); // booked tickets
}
/* add meta box for select event of the ticket */

/* code for remove the edit link from recurring events */
add_filter('post_row_actions', 'tmpl_qe_download_link', 10, 2);
add_action( 'admin_menu', 'tmpl_remove_meta_boxes' );
function tmpl_remove_meta_boxes($post_id)
{
	//remove custom setting metabox in staff custom post type echo "asdhasdghasfdgh";
	if($post_id!=''){
		global $post;
		$post_edit = $_REQUEST['post'];
		$post = get_post($post_edit);
		if($post->post_status == 'private' && $_REQUEST['action'] =='edit'){
			remove_meta_box('ptthemes-settings', 'event', 'normal');
			remove_meta_box('trackbacksdiv', 'event', 'normal');
			remove_meta_box('slugdiv', 'event', 'normal');
			remove_meta_box('revisionsdiv', 'event', 'normal');
			remove_meta_box('authordiv', 'event', 'normal');
			remove_meta_box('ecategorydiv', 'event', 'normal');
			remove_meta_box('tagsdiv', 'event', 'normal');
			remove_meta_box('postimagediv', 'event', 'normal');
			remove_meta_box('post-stylesheets', 'event', 'normal');
		
			add_action('admin_init', 'remove_all_media_buttons');
		}
	}
}

/* remove add media button for private post for events */
function remove_all_media_buttons()
{
    remove_all_actions('media_buttons');	
	add_meta_box('tmpl_recurring_dates','Event is on','tmpl_recurring_on','event','side','high');
}

// for Custom Post Types
// add_filter('cpt_name_row_actions', 'tmpl_qe_download_link', 10, 2);

function tmpl_qe_download_link($actions, $post) {
	if($post->post_status =='private' && $post->post_type =='event'){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
		$plugin = "woocommerce/woocommerce.php";
		$url = get_edit_post_link( $post->ID );
		if(is_plugin_active($plugin)){
			$actions['edit'] = "<a href='".$url."'>".__('Manage tickets',T_DOMAIN)."</a>";
		}else{
			unset($actions['edit'],$actions['trash']);
		}
		unset($actions['inline hide-if-no-js'],$actions['trash']);
		
	}
    return $actions; 
}
/*
Name :tmpl_recurring_on
description : show recurring dates
*/
function tmpl_recurring_on($post){
	global $post;
	echo "<p class='error'>";
	_e('This event is the recurrence of the event.',T_DOMAIN);
	echo "</p>";
	$st_date = get_post_meta($post->ID,'st_date',true);
	$end_date =  get_post_meta($post->ID,'end_date',true);
	$st_time =  get_post_meta($post->ID,'st_time',true);
	$end_time =  get_post_meta($post->ID,'end_time',true);
	$address =  get_post_meta($post->ID,'address',true);
	if($st_date){
		echo "<p>";
		_e('Start date',DOMAIN); echo ": <b>". $st_date." ".$st_time."</b>"; 
		echo "</p>";
	}
	if($end_date){
		echo "<p>";
			_e('End date',DOMAIN); echo ": <b>".$end_date." ".$end_time."</b>";
		echo "</p>";
	}
	if($address){
		echo "<p>";
			_e('Address',DOMAIN); echo ": <b>".$address."</b>";
		echo "</p>";
	}
}
/*
Name : tmpl_is_parent
Description : return true if post have parent post
*/
function tmpl_is_parent($post){
	if($post->post_parent){
		return true;
	}else{
		return false;
	}
}

/*
Name :tmpl_the_title_trim
Desc : remove the title trim from post title when post is private
*/
function tmpl_the_title_trim($title) {
	$title = esc_attr($title);
	$findthese = array(
		'#Protected:#',
		'#Private:#'
	);
	$replacewith = array(
		'', // What to replace "Protected:" with
		'' // What to replace "Private:" with
	);
	
	$title = preg_replace($findthese, $replacewith, $title);
	return $title;
}
add_action('init','tmpl_single_page_title'); // remove Private text form private post 
function tmpl_single_page_title(){
	add_filter('the_title', 'tmpl_the_title_trim');
	
	/* upgrade old database querries */
	
	if(isset($_REQUEST['recurring_update']) && $_REQUEST['recurring_update'] == 'true'){
		global $wpdb,$post;
		/* to delete the old recurrences BOF */
		$args =	array( 
					'post_type' => 'event',
					'posts_per_page' => -1	,
					'post_status' => array('publish'),
					'meta_query' => array(
					'relation' => 'AND',
						array(
								'key' => 'event_type',
								'value' => 'Recurring event',
								'compare' => '=',
								'type'=> 'text'
							),
						)
					);
		$rec_query = null;
		$rec_query = new WP_Query($args);
		
		if($rec_query){
			while ($rec_query->have_posts()) : $rec_query->the_post();
			
				$post_data = get_post($post->ID);
				$postt = $post->post_title;
				templ_save_recurrence_events($post_data,$post->ID);
			endwhile;
			wp_reset_query();
		}
	}
}

function tmpl_showMessage($message, $errormsg = false)
{
	if ($errormsg) {
		echo '<div id="message" class="error" style="color:#2A6AA0;">';
	}
	else {
		echo '<div id="message" class="updated fade">';
	}

	echo "<p><strong>$message</strong></p></div>";
}    
function tmpl_show_admin_recurring()
{
    // Shows as an error message. You could add a link to the right page if you wanted.
	if(get_option('tmpl_recurring_updates') ==''){
		tmpl_showMessage("Nightlife has been upgraded with new recurring concept , to go ahead with that you needs to upgrade all old recurring events. <a href=".site_url()."/wp-admin/edit.php?post_type=event&recurring_update=true".">Click Here</a> to upgrade your events.", true);
		add_option('tmpl_recurring_updates','completed');
	}
  
}

/*
	Call showAdminMessages() when showing other admin messages. 
*/
add_action('admin_notices', 'tmpl_show_admin_recurring'); 

/* add action to add listing setting option */
add_action('templatic_general_setting_data','nightlife_post_page_setting_data',9);
function nightlife_post_page_setting_data($column)
{
	$tmpdata = get_option('templatic_settings');
		switch($column)
		{
			case 'listing' :
			?>
			 <tr>
			 	<td colspan="2"><h3><?php _e('Nightlife Listing Settings',DOMAIN);?></h3></td>
			</tr>
			<tr>
				<th><label><?php _e('Select default tab for home,category and tag page',DOMAIN); ?></label></th>
				<td>
					<div class="element">
						 <div class="input_wrap">
							<?php $templatic_current_tab =  @$tmpdata['templatic-current_tab']; ?>
						  <select id="templatic-current_tab" name="templatic-current_tab" style="vertical-align:top;width:200px;" >
							<option value=""><?php  _e('Please select current tab',DOMAIN);  ?></option>
							<option value="past" <?php if($templatic_current_tab == 'past' ) { echo "selected=selected";  } ?>><?php _e('Past',DOMAIN); ?></option>
							<option value="current" <?php if($templatic_current_tab == 'current' ) { echo "selected=selected";  } ?>><?php _e('Current',DOMAIN); ?></option>
							<option value="upcoming" <?php if($templatic_current_tab == 'upcoming' ) { echo "selected=selected";  } ?>><?php _e('Upcoming',DOMAIN); ?></option>
						</select> 
					</div>
					</div>
				   <label for="ilc_tag_class"><p class="description"><?php _e('Select the tab you want to display by default.',DOMAIN);?></p></label>
				</td>
			 </tr>
			 <tr>
				<th><label><?php _e('Select default sort order for home page',DOMAIN); ?></label></th>
				<td>
					<div class="element">
						 <div class="input_wrap">
							<?php $templatic_sort_order =  @$tmpdata['templatic-sort_order']; ?>
						  <select id="templatic-sort_order" name="templatic-sort_order" style="vertical-align:top;width:200px;" >
							<option value=""><?php  _e('Please select sort order',DOMAIN);  ?></option>
							<option value="published" <?php if($templatic_sort_order == 'published' ) { echo "selected=selected";  } ?>><?php _e('Latest Published',DOMAIN); ?></option>
							<option value="random" <?php if($templatic_sort_order == 'random' ) { echo "selected=selected";  } ?>><?php _e('Random',DOMAIN); ?></option>
							<option value="alphabetical" <?php if($templatic_sort_order == 'alphabetical' ) { echo "selected=selected";  } ?>><?php _e('Alphabetical',DOMAIN); ?></option>
							<option value="s_date" <?php if($templatic_sort_order == 's_date' ) { echo "selected=selected";  } ?>><?php _e('As Per Start Date',DOMAIN); ?></option>
						</select> 
					</div>
					</div>
				   <label for="ilc_tag_class"><p class="description"><?php _e('Select the sort order you want to display listing events.',DOMAIN);?></p></label>
				</td>
			 </tr>
			 <?php
			 break;
		}
}

/*
 post expiration - cange post status for recurring event
*/
add_action('tmpl_post_expired_beforemail','tmpl_post_expired_beforemail_fn');

function tmpl_post_expired_beforemail_fn($post){
	
	$post_event_type = get_post_meta($post->ID,'event_type',true);
	$post_status = $post->post_status;
	if($post_event_type =='Recurring event'){
		$args =	array( 
					'post_type' => 'event',
					'posts_per_page' => -1	,
					'post_parent' => $post->ID,
					'meta_query' => array(
					'relation' => 'AND',
						array(
								'key' => 'event_type',
								'value' => 'Regular event',
								'compare' => '=',
								'type'=> 'text'
							),
						)
					);
		$rec_query = null;
		$rec_query = new WP_Query($args);
		
		if($rec_query){
				while ($rec_query->have_posts()) : $rec_query->the_post();
					$my_post['ID'] = $post->ID;
					if($post_status =='publish'){
						$my_post['post_status'] = 'private';
					}else{
						$my_post['post_status'] = 'pending';
					}
					wp_update_post($my_post);
				endwhile;
		}
	}
}


/*
NAME : FETCH DATA FOR EVENT POST TYPE
DESCRIPTION : FETCH EVENT CATEGORIES, TAGS, ADDRESS ETC FIELD TO DISPLAY THEM IN EVENTS PAGE - BACK END
*/
add_action( 'manage_event_posts_custom_column', 'templatic_manage_event_columns', 10, 2 );
function templatic_manage_event_columns( $column, $post_id )
{
//echo '<link href="'.get_template_directory_uri().'/monetize/admin.css" rel="stylesheet" type="text/css" />';
	global $post;

	switch( $column ) {
	
		case 'post_category' :
			/* Get the post_category for the post. */
			$templ_events = get_the_terms($post_id,CUSTOM_CATEGORY_TYPE_EVENT);
			if (is_array($templ_events)) {
				foreach($templ_events as $key => $templ_event) {
					$edit_link = home_url()."/wp-admin/edit.php?".CUSTOM_CATEGORY_TYPE_EVENT."=".$templ_event->slug."&post_type=".CUSTOM_POST_TYPE_EVENT;
					$templ_events[$key] = '<a href="'.$edit_link.'">' . $templ_event->name . '</a>';
					}
				echo implode(' , ',$templ_events);
			}else {
				_e( 'Uncategorized',T_DOMAIN);
			}
			break;
		case 'post_tags' :
			/* Get the post_tags for the post. */
			$templ_event_tags = get_the_terms($post_id,CUSTOM_TAG_TYPE_EVENT);
			if (is_array($templ_event_tags)) {
				foreach($templ_event_tags as $key => $templ_event_tag) {
					$edit_link = home_url()."/wp-admin/edit.php?".CUSTOM_TAG_TYPE_EVENT."=".$templ_event_tag->slug."&post_type=".CUSTOM_POST_TYPE_EVENT;
					$templ_event_tags[$key] = '<a href="'.$edit_link.'">' . $templ_event_tag->name . '</a>';
				}
				echo implode(' , ',$templ_event_tags);
			}else {
				_e( '' ,T_DOMAIN);
			}
				
			break;
		case 'geo_address' :
			/* Get the address for the post. */
			$geo_address = get_post_meta( $post_id, 'address', true );
				if($geo_address != ''){
					$geo_address = $geo_address;
				} else {
					$geo_address = ' ';
				}
				echo $geo_address;
			break;
		case 'start_timing' :
			/* Get the start_timing for the post. */
			$st_date = get_post_meta( $post_id, 'st_date', true );
				if($st_date != ''){
					$st_date = $st_date.' '.get_post_meta( $post_id, 'st_time', true );
				} else {
					$st_date = ' ';
				}
				echo $st_date;
			break;
		case 'end_timing' :
			/* Get the end_timing for the post. */
			$end_date = get_post_meta( $post_id, 'end_date', true );
				if($end_date != ''){
					$end_date = $end_date.' '.get_post_meta( $post_id, 'end_time', true );
				} else {
					$end_date = ' ';
				}
				echo $end_date;
			break;
		case 'event_type_' :
			/* Get the event_type for the post. */
				$event_type = trim(get_post_meta( $post_id, 'event_type', true ));
				if(strtolower($event_type) == trim(strtolower('Recurring event'))){				
					$e_type = "<span style='color:green;'>".__('Recurring event',T_DOMAIN)."</span>";
				} else {
					 $e_type = __('Regular event',T_DOMAIN);;
				}
				if($post->post_parent !=0){
					$post_parent = get_post($post->post_parent );
					$e_type = __('Recurrence of ',T_DOMAIN)."<a href='".get_edit_post_link($post->post_parent)."'>".$post_parent->post_title."</a>";
				}
				echo $e_type;
			break;
		
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}
/* EOF - FETCH DATA IN BACK END */

				
/*
NAME : FUNCTION TO DISPLAY EVENT POST TYPE IN BACK END
DESCRIPTION : THIS FUNCTION ADDS COLUMNS IN EVENT POST TYPE IN BACK END
*/
add_filter( 'manage_edit-event_columns', 'templatic_edit_event_columns',11 ) ;
function templatic_edit_event_columns( $columns )
{ 
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => EVENT_TITLE_HEAD,
		'author' => AUTHOR_TEXT,
		'event_type_' => EVENT_TYPE_TEXT,
		'geo_address' => ADDRESS,
		'start_timing' => EVENT_ST_TIME,
		'end_timing' => EVENT_END_TIME,
		'post_category' => CATGORIES_TEXT,
		'post_tags' => TAGS_TEXT_HEAD,
		'comments' => '<img src="'.get_template_directory_uri().'/images/comment-grey-bubble.png" alt="Comments">',
		'date' => DATE_TEXT
	);
	return $columns;
}
/* END OF FUNCTION */
/*
 * ADMIN COLUMN - SORTING - MAKE HEADERS SORTABLE
 * https://gist.github.com/906872
 */
add_filter("manage_edit-event_sortable_columns", 'event_sort',11);
function event_sort($columns) {
	$custom = array(
		'event_type' 	=> 'event_type'
	);
	return wp_parse_args($custom, $columns);
}



?>