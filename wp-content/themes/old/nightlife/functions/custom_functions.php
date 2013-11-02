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
		$permalink =  get_term_link( $current_term->slug, $current_term->taxonomy );			
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
		$permalink=get_bloginfo('url');		
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
	
	if(!is_search()){
	echo "<div class='smart_tab clearfix'>
			<p class='left'>
				<a class='first gridview ".$upcoming_active."' href='".$upcoming."'>UPCOMING EVENTS</a>
				<a class='second gridview ".$current_active."' href='".$current."'>CURRENT EVENTS</a>
				<a class='last listview ".$past_active."' href='".$past."'>PAST EVENTS</a>
			</p>
			
			<p class='right viewsbox'>
				<a class='switcher first gridview' id='gridview' href='#'>GRID VIEW</a>
				<a class='switcher last listview active' id='listview' href='#'>LIST VIEW</a>
			</p>
	 </div>";
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
			$post_img = bdw_get_images_plugin($post->ID,'large');
			$post_images = $post_img[0]['file'];
			$attachment_id = $post_img[0]['id'];
			$attach_data = get_post($attachment_id);
			$img_title = $attach_data->post_title;
			$img_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
			
			$post_img_thumb = bdw_get_images_plugin($post->ID,'thumbnail'); 
			$post_images_thumb = $post_img_thumb[0]['file'];
			$attachment_id1 = $post_img_thumb[0]['id'];
			$attach_idata = get_post($attachment_id1);
			$post_img_title = $attach_idata->post_title;
			$post_img_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);	
		}		
		if($single_htmlvar_name['address'])
		{
			$geo_latitude = get_post_meta($post->ID,'geo_latitude',true);
			$geo_longitude = get_post_meta($post->ID,'geo_longitude',true);
			$address = get_post_meta($post->ID,'address',true);
			$map_type =get_post_meta($post->ID,'map_view',true);			
		}					
		wp_enqueue_script( 'jquery-ui-tabs' );
		?>
		<script type="text/javascript">
			jQuery.noConflict();
			jQuery(document).ready(function($) {
					jQuery("#tabs").tabs();
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
									get_the_image(array('post_id'=> get_the_ID(),'link_to_post'=>'false','size'=>'large','image_class'=>'post_img img listimg','default_image'=>get_stylesheet_directory_uri()."/images/img_not_available.png"));
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
		echo '<h2 class="date">' . sprintf( get_the_time( esc_attr__( 'd') ) ) . '<span>'.sprintf( get_the_time( esc_attr__( 'M') ) ).'</span></h2>';		
	}
}
/*Add action after listing post title for display event address */
add_action('templ_after_post_title','templ_event_listing_page_address');
function templ_event_listing_page_address()
{
	global $post;
	if(is_archive())
	{
		$address=get_post_meta($post->ID,'address',true);
		echo "<span class='address'>".$address."</span>";	
	}
}

/* add action after post content for display the category and tag information */
add_action('templ_after_post_content','templ_after_post_content_category');
function templ_after_post_content_category()
{
	global $post;	
	if(is_archive())
	{
		
		$st_date = date('M d, Y',strtotime(get_post_meta($post->ID,'st_date',true)));
		$end_date = date('M d, Y',strtotime(get_post_meta($post->ID,'end_date',true)));
		if($end_date && $st_date && strtotime(get_post_meta($post->ID,'st_date',true)) < strtotime(get_post_meta($post->ID,'end_date',true))){	 /* if st date and end date both are set */
			$event_date = date('M d, Y',strtotime(get_post_meta($post->ID,'st_date',true))).' to '.date('M d, Y',strtotime(get_post_meta($post->ID,'end_date',true)));
		}else if(($st_date && !$end_date) || (strtotime(get_post_meta($post->ID,'st_date',true)) == strtotime(get_post_meta($post->ID,'end_date',true)))){ /* if only st date is set or st date is less the or equal to end date*/
			$event_date = date('M d, Y',strtotime(get_post_meta($post->ID,'st_date',true)));				
		}else{
			$event_date = date('M d, Y',strtotime(get_post_meta($post->ID,'st_date',true))).' to '.date('M d, Y',strtotime(get_post_meta($post->ID,'end_date',true)));
		}
		?>
			
		<p class="date"> <span><?php _e('Date',T_DOMAIN);?> : </span> <?php echo $event_date; ?><br> <span><?php _e('Timing',T_DOMAIN);?> : </span> <?php echo get_post_meta($post->ID,'st_time',true).' to '.get_post_meta($post->ID,'end_time',true);?> </p>		
        <?php		
		the_taxonomies(array('before'=>'<p class="bottom_line"><span class="i_category">','sep'=>'</span>&nbsp;&nbsp;<span class="i_tag">','after'=>'</span></p>'));		
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
	
		$templateurl = get_bloginfo('stylesheet_directory').'/cache/';
		$home = get_bloginfo('url');
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
		if($single_htmlvar_name['st_time'])
			$st_time=get_post_meta($post->ID,'st_time',true);
		if($single_htmlvar_name['end_time'])
			$en_time=get_post_meta($post->ID,'end_time',true);
		?>
		<div class="event_detail clearfix">
			<div class="col1">
				<?php if(get_post_meta($post->ID,'st_date',true)!="" && $single_htmlvar_name['st_date']):?><p class="date"><span><?php _e('STARTING DATE',T_DOMAIN)?></span><?php echo date("M dS,Y",strtotime(get_post_meta($post->ID,'st_date',true)));?></p><?php endif;?>
				<?php if(get_post_meta($post->ID,'end_date',true)!="" && $single_htmlvar_name['end_date']):?><p class="date"><span><?php _e('ENDING DATE',T_DOMAIN)?></span><?php echo date("M dS,Y",strtotime(get_post_meta($post->ID,'end_date',true)));?></p><?php endif;?>
			    <?php if($st_time!="" && $en_time!="" ):?> <p class="time"><span><?php _e('TIME',T_DOMAIN)?></span><?php echo $st_time." - ".$en_time;?></p><?php endif;?>
                <?php if(get_post_meta($post->ID,'website',true)!="" && $single_htmlvar_name['website']):?><p class="website"><span><?php _e('WEBSITE',T_DOMAIN)?></span><?php echo get_post_meta($post->ID,'website',true);?></p><?php endif;?>
			</div>
			<div class="col2">
				<?php if(get_post_meta($post->ID,'address',true)!="" && $single_htmlvar_name['address']):?><p class="location"><span><?php _e('LOCATION',T_DOMAIN)?></span><?php echo get_post_meta($post->ID,'address',true);?></p><?php endif;?>
				<?php if(get_post_meta($post->ID,'phone',true)!="" && $single_htmlvar_name['phone']):?><p class="phone"><span><?php _e('PHONE',T_DOMAIN)?></span><?php echo get_post_meta($post->ID,'phone',true);?></p><?php endif;?>
				<?php if(get_post_meta($post->ID,'email',true)!="" && $single_htmlvar_name['email']):?><p class="email"><span><?php _e('EMAIL',T_DOMAIN)?></span><?php echo get_post_meta($post->ID,'email',true);?></p><?php endif;?>
			</div>    
		</div>
		<?php
		
		$event_type = get_post_meta($post->ID,'event_type',true);		
		$recurrence_occurs=get_post_meta($post->ID,'recurrence_occurs',true);
		/* Recurring Event  */
		if(trim(strtolower($event_type)) == trim(strtolower('Recurring event')))
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
			<div id="show_recurring"  onclick="return show_recurring_event('show');" ><button class="reverse"><?php _e('Show '.$recurrence_occurs.' occurences',T_DOMAIN); ?></button></div>
			<div id="hide_recurring" style="display:none;" onclick="return show_recurring_event('hide');" ><button class="reverse"><?php _e('Hide '.$recurrence_occurs.' occurences',T_DOMAIN); ?></button></div>
            <div id="recurring_events" style="display:none;" class="recurring_info">
           		<?php echo recurrence_event($post->ID);?>
            </div>
    	<?php
		}// Finish the recurring event if condition
		
		/* Regular Event  */
		if((trim(strtolower($event_type)) == trim(strtolower('Regular event')) && $event_type != '' ))
		{		
		?>
			<div class="attending_event"> 
				<?php echo attend_event_html($post->post_author,$post->ID);	  ?>
                <div class="clearfix"></div>
		   </div>  
		<?php }// Finish regular event if condition
	endif;	
}

/*
 * Add action for display the event organized after the post content
 */
add_action('templ_after_post_content','event_custom_fields');
function event_custom_fields()
{
	global $post,$single_htmlvar_name,$single_pos_title;
	if($post->post_type==CUSTOM_POST_TYPE_EVENT && is_single()):
		$i=0;
		$j=0;
		echo '<div class="single_custom_field">';
		foreach($single_htmlvar_name as $key=> $_htmlvar_name):
	
			if($key!="st_date" && $key!="end_date" && $key!="end_date" && $key!="st_time" && $key!="end_time" && $key!="event_type" && $key!="phone" && $key!="email" && $key!="website" && $key!="twitter" && $key!="facebook" && $key!="video" && $key!="organizer_name" && $key!="organizer_email" && $key!="organizer_logo" && $key!="organizer_address" && $key!="organizer_contact" && $key!="organizer_website" && $key!="organizer_mobile" && $key!="organizer_desc" && $key!="post_images" && $key!="org_info" && $key!="address" && $key!="map_view")
			{
				
		?>
			<?php if($_htmlvar_name == 'multicheckbox' && get_post_meta($post->ID,$key,true) !=''):
					if($i==0)_e('<h3>Custom Fields</h3>',DOMAIN);
			?>
				<li><label><?php echo $single_pos_title[$j]; ?></label> : <span><?php echo implode(",",get_post_meta($post->ID,$key,true)); ?></span></li>
			<?php else: 
					if(get_post_meta($post->ID,$key,true) !=''):
						if($i==0)_e('<h3>Custom Fields</h3>',DOMAIN);
					?>
					<li><label><?php echo $single_pos_title[$j]; ?></label> : <span><?php echo get_post_meta($post->ID,$key,true); ?></span></li>
				<?php endif; ?>
			<?php endif; ?>
	<?php $i++; }// first if condition finish
			$j++;
		endforeach; 
		echo '</div>';
	endif;
	
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
            <?php if($reg_desc!="" && $single_htmlvar_name['reg_desc']){ echo "<div class='org_desc'>".$reg_desc."</div>";}?>        
        </div> 
        <?php if($single_htmlvar_name['video'] && $video):?>
        	<div class="org_video">
            	<?php echo stripslashes($video);?>
            </div>
        <?php endif;?>
        
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
		endif;
	endif;
}
/*
 * Add action in categories page for display post image slider.
 */
add_action('templ_after_categories_title','flexslider_before_category_title',10);
function flexslider_before_category_title()
{
	global $wpdb,$post;
	
	if (have_posts()) : 
	?>
    <!-- Start flexslider in taxonomy page-->
    <div class="flexslider flexslider_inner">
    	<ul class="slides">
    <?php
		while (have_posts()) : the_post();				
			$taxonomy_slider = bdw_get_images_plugin($post->ID,'taxonomy-slider');			
			if($taxonomy_slider[0]['file']):
			?>
			<li>
            	<img src="<?php echo $taxonomy_slider[0]['file']?>"  width="640" height="200"/>
                <div class="slider_content">
                	<div class="slide_event_info">
                    	<span class="image"><?php echo date('d',strtotime(get_post_meta($post->ID,'st_date',true)));?></span>
                    	<p>
                        	<span><?php echo date(' F jS, Y, g:i a',strtotime(get_post_meta($post->ID,'st_date',true))); ?></span>
	                    	<a href="<?php the_permalink();?>"><?php the_title(); ?></a>
                    	</p>
                    </div>
                </div>
            </li>
        <?php	
			endif;
		endwhile;
		?>
    	</ul>
    </div><!--Finish the flexslider in taxonomy page -->
    <?php
	endif;
}

/*
 * Add action in categories page for display the additional information before categories title.
 */
add_action('templ_after_categories_title','before_category_titel_smart_tab',11);

/*
 * Function Name: before_category_titel_smart_tab
 * Return : Display the smart tab before the category title
 */
function before_category_titel_smart_tab()
{
	//global $post;	
	
	templatic_display_views();	
	
	
	?>
    <div class="taxonomy-sortoption">
    	<form method="post" action="" name="sort_by_result_frm">
        	<select id="sortby_id" class="category" onchange="sort_as_set()" name="sortby">
            	<option value=""> <?php _e('Select Sorting',T_DOMAIN);?></option>
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
}
/* Remove action for Category Page image*/
remove_action('tmpl_category_page_image','tmpl_category_page_image');
/* Add Action tmpl_category_page_image in taxonomy page */
add_action('tmpl_category_page_image','event_taxonomy_page_image');
function event_taxonomy_page_image()
{
	global $post;		
	$post_img = bdw_get_images_plugin($post->ID,'thumbnail');
	$thumb_img = $post_img[0]['file'];
	$attachment_id = $post_img[0]['id'];
	$attach_data = get_post($attachment_id);
	$img_title = $attach_data->post_title;
	$img_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
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
	$taxonomy_image_url = bdw_get_images_plugin($post->ID,'taxonomy-thumbnail');	
	$thumb_img = $taxonomy_image_url[0]['file'];
	$attachment_id = $taxonomy_image_url[0]['id'];
	$attach_data = get_post($attachment_id);
	$img_title = $attach_data->post_title;
	$img_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
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
				$wpdb->prepare($wpdb->query($postcodes_update));
			}
		else
		{
			$postcodes_insert = 'INSERT INTO '.$tbl_postcodes.' set 
					pcid="",
					post_id="'.$pID.'",
					address = "'.$post_address.'",
					latitude ="'.$latitude.'",
					longitude="'.$longitude.'"';
					$wpdb->prepare($wpdb->query($postcodes_insert));
		}
	}

	if(!strstr($_SERVER['REQUEST_URI'],'wp-admin') && get_post_type( $last_postid) == CUSTOM_POST_TYPE_EVENT)
	{
		$post_address 	= $_SESSION['custom_fields']['address'];
		$latitude 		= $_SESSION['custom_fields']['geo_latitude'];
		$longitude 		= $_SESSION['custom_fields']['geo_longitude'];
		$pcid = $wpdb->get_var("select pcid from $tbl_postcodes where post_id = '".$last_postid."'");
		//echo $pcid;exit;
		if($pcid){
			$postcodes_update = "UPDATE $tbl_postcodes set 
				address = '".$post_address."',
				latitude ='".$latitude."',
				longitude='".$longitude."' where pcid = '".$pcid."' and post_id = '".$last_postid."'";
				$wpdb->prepare($wpdb->query($postcodes_update));
			}
		else
		{
			$postcodes_insert = 'INSERT INTO '.$tbl_postcodes.' set 
					pcid="",
					post_id="'.$last_postid.'",
					address = "'.$post_address.'",
					latitude ="'.$latitude.'",
					longitude="'.$longitude.'"';
					$wpdb->prepare($wpdb->query($postcodes_insert));
		}
	}
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
			update_post_meta($pID, 'daily_event', $_POST['daily_event']);	
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
	echo get_avatar($current_user->user_email,35,35);
	if($user_meta_data && in_array($post_id,$user_meta_data))
	{
		?>
	<span id="attend_event_<?php echo $post_id;?>" class="fav"  > 
	<span class="span_msg"><?php
	if($current_user->ID){
		echo "<a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a>, ".REMOVE_EVENT_MSG;
	}else{
		echo "<a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a> ".REMOVE_EVENT_MSG;
	} ?></span>
	
	<a href="javascript:void(0);" class="addtofav b_review" onclick="javascript:addToAttendEvent('<?php echo $post_id;?>','remove');"><?php echo REMOVE_EVENT_TEXT;?></a>   </span>    
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
	?></span>
	<a href="javascript:void(0);" class="addtofav b_review"  onclick="javascript:addToAttendEvent(<?php echo $post_id;?>,'add');"><?php echo ATTEND_EVENT_TEXT;?></a></span>
	<?php } 
}
function attend_event_html1($user_id,$post_id,$st_date,$end_date)
{
	global $current_user,$post;
	$a = "";
	
	$post = get_post($post_id);
	$user_meta_data = get_user_meta($current_user->ID,'user_attend_event',true);
	$user_attend_event_start_date = get_user_meta($current_user->ID,'user_attend_event_st_date',true);
	$user_attend_event_end_date = get_user_meta($current_user->ID,'user_attend_event_end_date',true);
	$a .= get_avatar($current_user->user_email,35,35);
	if($user_meta_data && in_array($post_id,$user_meta_data) && in_array($post_id."_".$st_date,$user_attend_event_start_date) && in_array($post_id."_".$end_date,$user_attend_event_end_date))
	{
		if($current_user->ID){
		$a.="<span id='attend_event_$post_id-$st_date' class='fav' > 
		<span class='span_msg'>".$current_user->display_name.", ".REMOVE_EVENT_MSG."</span>
		<a href='javascript:void(0)' class='addtofav b_review' onclick='javascript:addToAttendEvent(".$post_id.",\"remove\",\"".$st_date."\",\"".$end_date."\")'>".REMOVE_EVENT_TEXT."</a>   </span>    
	";	
		}else{
		$a.="<span id='attend_event_$post_id-$st_date' class='fav' > 
		<span class='span_msg'>".$current_user->display_name." ".REMOVE_EVENT_MSG."</span>
		<a href='javascript:void(0)' class='addtofav b_review' onclick='javascript:addToAttendEvent(".$post_id.",\"remove\",\"".$st_date."\",\"".$end_date."\")'>".REMOVE_EVENT_TEXT."</a>   </span>    
	";	
		}
	}else{
		if($current_user->ID){
		$a.="<span id='attend_event_$post_id-$st_date' class='fav'>
		<span class='span_msg'>"."<a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a>, ".ATTEND_EVENT_MSG." <strong>".$post->post_title."</strong> ?</span>
		<a href='javascript:void(0)' class='addtofav b_review'  onclick='javascript:addToAttendEvent(".$post_id.",\"add\",\"".$st_date."\",\"".$end_date."\")'>".ATTEND_EVENT_TEXT."</a></span>";
		}else{
		$a.="<span id='attend_event_$post_id-$st_date' class='fav'>
		<span class='span_msg'>"."<a href='".get_author_posts_url($current_user->ID)."'>".$current_user->display_name."</a> ".ATTEND_EVENT_MSG." <strong>".$post->post_title."</strong> ?</span>
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
	$daily_event = get_post_meta($post_id,'daily_event',true);
	$current_date = date('Y-m-d');
	$recurrence_days = get_post_meta($post_id,'recurrence_days',true);	//on which day
	$recurrence_list = "";
	_e('This is a ',T_DOMAIN);echo $recurrence_occurs;_e(' Event.',T_DOMAIN);	
	if($recurrence_occurs == 'daily' && $daily_event != 'yes')
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
				$st_date = date('l dS \o\f F Y', $st_date1);
				$end_date1 = strtotime(date("Y-m-d", strtotime(get_post_meta($post_id,'st_date',true))) . " +$j day");
				$post_end_date  = strtotime(get_post_meta($post_id,'end_date',true));
				if($end_date1 >  $post_end_date)
					$end_date1 = $post_end_date;
				$end_date = date('l dS \o\f F Y', $end_date1);
				$st_time = get_formated_time(get_post_meta($post_id,'st_time',true));
				$end_time = get_formated_time(get_post_meta($post_id,'end_time',true));
				$recurrence_list .= "<li class=$class>";
				$recurrence_list .= "<div class='date_info'>
				<p>
					  <strong>From</strong>   $st_date $st_time
							  <strong>To </strong>   $end_date.$end_time <br/>
				</p>
								</div>";				
				
				$recurrence_list .= "<div class='attending_event'> ";
				$recurrence_list .= attend_event_html1($post->post_author,$post->ID,date("Y-m-d", $st_date1),date("Y-m-d",$end_date1));
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
	if($recurrence_occurs == 'weekly' && $daily_event != 'yes')
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
				$st_date = date('l dS \o\f F Y', $st_date1);
				$st_end_date = date("Y-m-d", $matching_days[$z]);
				$end_date1 = strtotime(date("Y-m-d", strtotime($st_end_date)) . " +$recurrence_days day");
				$post_end_date  = strtotime(get_post_meta($post_id,'end_date',true));
				if($end_date1 >  $post_end_date)
					$end_date1 = $post_end_date;
				$end_date = date('l dS \o\f F Y', $end_date1);
				$st_time = get_formated_time(get_post_meta($post_id,'st_time',true));
				$end_time = get_formated_time(get_post_meta($post_id,'end_time',true));
				$recurrence_list .= "<li class=$class>";
				$recurrence_list .= "<div class='date_info'>
					<p>
						  <strong>From</strong>   $st_date $st_time
								  <strong>To </strong>   $end_date $end_time <br/>
					</p>
						</div>";				
				$recurrence_list .= "<div class='attending_event'> ";
				$recurrence_list .= attend_event_html1($post->post_author,$post->ID,date("Y-m-d", $st_date1),date("Y-m-d",$end_date1));
				$recurrence_list .= "	<div class='clearfix'></div>
			   </div>  ";
				 
				$recurrence_list .= "</li>";
			}
	}
	
	if($recurrence_occurs == 'monthly' && $daily_event != 'yes')
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
			$st_date = date('l dS \o\f F Y', $matching_days[$z]);
			$st_end_date = date("Y-m-d", $matching_days[$z]);
			$end_date1 = strtotime(date("Y-m-d", strtotime($st_end_date)) . " +$recurrence_days day");
			$post_end_date  = strtotime(get_post_meta($post_id,'end_date',true));
			if($end_date1 >  $post_end_date)
				$end_date1 = $post_end_date;
			$end_date = date('l dS \o\f F Y', $end_date1);
			$st_time = get_formated_time(get_post_meta($post_id,'st_time',true));
			$end_time = get_formated_time(get_post_meta($post_id,'end_time',true));
			$recurrence_list .= "<li class=$class>";
			$recurrence_list .= "<div class='date_info'>
			<p>
				  <strong>From</strong>   $st_date $st_time
						  <strong>To </strong>   $end_date $end_time <br/>
			</p>
							</div>";							
			$recurrence_list .= "<div class='attending_event'> ";
			$recurrence_list .= attend_event_html1($post->post_author,$post->ID,date("Y-m-d", $st_date1),date("Y-m-d",$end_date1));
			$recurrence_list .= "	<div class='clearfix'></div>
		   </div>  ";						
			$recurrence_list .= "</li>";
		}
			
	}
	if($recurrence_occurs == 'yearly' && $daily_event != 'yes')
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
				$st_date = date('l dS \o\f F Y', $st_date);
				$end_date = $date2 = mktime(0, 0, 0, $month, $day+$recurrence_days, $year);
				$post_end_date  = strtotime(get_post_meta($post_id,'end_date',true));
				if($end_date >  $post_end_date)
					$end_date = $post_end_date;
				$end_date = date('l dS \o\f F Y', $end_date);
				$st_time = get_formated_time(get_post_meta($post_id,'st_time',true));
				$end_time = get_formated_time(get_post_meta($post_id,'end_time',true));
				$recurrence_list .= "<li class=$class>";
				$recurrence_list .= "<div class='date_info'>
				<p>
					  <strong>From</strong>   $st_date $st_time
							  <strong>To </strong>   $end_date $end_time <br/>
				</p>
								</div>";
							
				$recurrence_list .= "<div class='attending_event'> ";
				$recurrence_list .= attend_event_html1($post->post_author,$post->ID,date("Y-m-d", $st_date1),date("Y-m-d",$end_date1));
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
	$daily_event = get_post_meta($post_id,'daily_event',true);
	$current_date = date('Y-m-d');
	$recurrence_days = get_post_meta($post_id,'recurrence_days',true);	//on which day
	$recurrence_list = "";
	
	if($recurrence_occurs == 'daily' && $daily_event != 'yes')
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
				$st_date .= date('Y-m-d', $st_date1).",";
			}
			else
			{
				continue;
			}
		}
	}
	if($recurrence_occurs == 'weekly' && $daily_event != 'yes')
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
				$st_date .= date('Y-m-d', $matching_days[$z]).",";
				}
			}

	}
	
	if($recurrence_occurs == 'monthly' && $daily_event != 'yes')
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
				if($z <= ($tmd-1)){
				$st_date .= date('Y-m-d', $matching_days[$z]).",";
				}
			}
			
	}
	if($recurrence_occurs == 'yearly' && $daily_event != 'yes')
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
				$st_date .= date('Y-m-d', $st_date).",";

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
	$user_meta_data[]=$post_id;
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
		echo '<span class="span_msg">'.$current_user->display_name." ,".REMOVE_EVENT_MSG." <strong>".$post->post_title."</strong>".'</span><a href="javascript:void(0);" class="addtofav b_review" onclick="javascript:addToAttendEvent(\''.$post_id.'\',\'remove\');">'.REMOVE_EVENT_TEXT.'</a>';exit;	
		}
	elseif($user_meta_data && in_array($post_id,$user_meta_data,true) && in_array($post_id."_".$st_date,$user_attend_event_start_date,true) && in_array($post_id."_".$end_date,$user_attend_event_end_date,true))
	{
		echo '<span class="span_msg">'.$current_user->display_name." ,".REMOVE_EVENT_MSG." <strong>".$post->post_title."</strong>".'</span><a href="javascript:void(0);" class="addtofav b_review" onclick="javascript:addToAttendEvent(\''.$post_id.'\',\'remove\',\''.$st_date.'\',\''.$end_date.'\');">'.REMOVE_EVENT_TEXT.'</a>';exit;	
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
	if(in_array($post_id,$user_meta_data))
	{
		$i = 0;
		$user_new_data = array();
		foreach($user_meta_data as $key => $value)
		{
			
			if($post_id == $value && $i == 0)
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
		echo '<span class="span_msg"><a href='.get_author_posts_url($current_user->ID).'>'.$current_user->display_name.'</a>, '.ATTEND_EVENT_MSG.' <strong>'.$post->post_title.'</strong> ?</span><a class="addtofav b_review" href="javascript:void(0);"  onclick="javascript:addToAttendEvent(\''.$post_id.'\',\'add\');">'.ATTEND_EVENT_TEXT.'</a>';exit;
	}
	else
	{
		echo '<span class="span_msg">'.$current_user->display_name." ,".ATTEND_EVENT_MSG.' <strong>'.$post->post_title.'</strong></span><a class="addtofav b_review" href="javascript:void(0);"  onclick="javascript:addToAttendEvent(\''.$post_id.'\',\'add\',\''.$st_date.'\',\''.$end_date.'\');">'.ATTEND_EVENT_TEXT.'</a>';exit;
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
	return array (1 => __ ( 'Mon',T_DOMAIN ), 2 => __ ( 'Tue',T_DOMAIN ), 3 => __ ( 'Wed',T_DOMAIN ), 4 => __ ( 'Thu',T_DOMAIN ), 5 => __ ( 'Fri',T_DOMAIN ), 6 => __ ( 'Sat',T_DOMAIN ), 7 => __ ( 'Sun',T_DOMAIN ) );
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
		$daily_event = $_SESSION['custom_fields']['daily_event'];
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
		$daily_event = get_post_meta(@$_REQUEST['post'],'daily_event',true);
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
		$daily_event = get_post_meta(@$_REQUEST['pid'],'daily_event',true);
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
			<input type="text" id="recurrence-per" name='recurrence_per' size='2' value='<?php echo $recurrence_per ; ?>' />
			
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
						
		<div class="form_daily_event form_row clearfix">
			<label for="daily_event">
			<input id="daily_event" type="checkbox"  name="daily_event" value="yes" <?php if(@$daily_event =='yes'){ ?>checked=checked <?php }?>/>
			<?php _e('Is this a daily event ?',T_DOMAIN); ?></label>
		</div>
		<span><?php _e( 'For a recurring event, a one day event will be created on each recurring date within this date range.', T_DOMAIN ); ?></span><br/>
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
		
		if(document.getElementById('search').value=='<?php _e("Which event you like to search?","templatic");?>')
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
	}
	</script>
    <div class="<?php echo $class;?>">
        <form method="get" id="searchform" name="searchform" action="<?php echo esc_url( home_url( '/' ) );?>" onsubmit="return templatic_nightlife_checkform();">
            <input type="text" name="search" id="search" class="input_white" value= "Which event you like to search?"  onfocus="if (this.value=='Which event you like to search?' || this.value=='Please enter word you wnat to search'){ this.value = ''}" onblur="if(this.value.length==0){this.value='Which event you like to search?';}"/>
            <input type="hidden" name="post_type" value="<?php echo CUSTOM_POST_TYPE_EVENT;?>" />
            <input type="text" id="<?php echo $date_id;?>" name="date" class="input_grey when" value="When?" onfocus="if (this.value=='When?') this.value = ''" onblur="if(this.value.length==0){this.value='When?';}"/>
            <input type="text" name="location" id="location" class="input_grey where"  value="Where?" onfocus="if (this.value=='Where?') this.value = ''"  onblur="if(this.value.length==0){this.value='Where?';}"/>
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
					jQuery('#s').val('Please enter word you wnat to search');
					return false;
				}else if(search=="Which event you like to search?"){
					jQuery('#s').val('Please enter word you wnat to search');
					return false;
				}else{
					if(jQuery('.when').val()=="When?"){jQuery('.when').val('');}
					if(where=='Where?'){jQuery('#location').val('');}
					return true;
				}
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
		$start_date = date( 'l, F d, Y', $values['start_time'] );
		$end_date = date( 'l, F d, Y', $values['end_time'] );

		/* getting 'start' and 'end' time
		'g:i a' will give us something
		like 6:30 pm */
		$start_time = date( 'g:i a', $values['start_time'] );
		$end_time = date( 'g:i a', $values['end_time'] );

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
		$start_date = date( 'l, F d, Y', $values['start_time'] );
		$end_date = date( 'l, F d, Y', $values['end_time'] );

		/* getting 'start' and 'end' time
		'g:i a' will give us something
		like 6:30 pm */
		$start_time = date( 'g:i a', $values['start_time'] );
		$end_time = date( 'g:i a', $values['end_time'] );

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

if(is_taxonomy($post) || is_category()){
//add_action("the_content",'templ_display_content');
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
	if ( is_active_sidebar( 'primary' ) ) :
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
	echo get_avatar($post->post_author);
	echo "<span class='top_line'>".apply_atomic_shortcode( 'entry_meta_category',  __( '[entry-terms taxonomy="category"]', 'supreme' )  );
	echo the_author_meta( 'user_nicename' , $post->post_author )."</span>";
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
	if(!is_single()){echo '<a class="moretag" href="'. get_permalink($post->ID) . '"> Read more &raquo;</a>'; }elseif(is_single()){ echo apply_atomic_shortcode( 'entry_meta_category', __( 'Filed under: [entry-terms taxonomy="category"] [entry-terms taxonomy="post_tag" before="and Tagged: "]', 'supreme' )); } echo '<span class="post_date">'.get_formated_date($post->post_date)."</span></div>";		

}
add_action("{$prefix}_open_menu_primary",'after_primary_menu');
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
				$templatic_url = __('Start Tour',T_DOMAIN);
				$pointer_id = 'templatic_plugin';
				$download_url = site_url()."/wp-admin/plugins.php?nightlife_tour_step=1";
				$done = true;
				$postion = false;
			
			}elseif(isset($_REQUEST['nightlife_tour_step']) && $_REQUEST['nightlife_tour_step'] == '1'){
				$pointer_content = '<h3>' . __( 'Activate Tevolution Plugin', T_DOMAIN ) . '</h3>';
				$pointer_content .= '<p>' . __( 'Please activate Tevolution plugin to start your journey of <b>nightlife</b>.', T_DOMAIN ) . '</p>';
				$templatic_url = __('Next',T_DOMAIN);
				$pointer_id = 'templatic-system';
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
		add_action( 'admin_print_footer_scripts',get_content_in_wp_pointer );
	}
	
	// at first assume we don't want to show pointers
	$do_add_script = false;
	
	// in true, include the scripts
	if ( $enqueue ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'utils' ); // for user settings
	}
}
add_action( 'admin_enqueue_scripts', 'fb_enqueue_wp_pointer' );

function nightlife_author() {
	global $post,$author_post;
	$author_post=$post;
	if(is_author() && is_user_logged_in() && get_post_type()==CUSTOM_POST_TYPE_EVENT)
	{
		//$title.=$title;
		$link='';
		$postid=$post->ID;
		$post_type=$post->post_type;
		$postdate = $post->post_date;
		//get the submited page id from post typpe
		$args=array(	
			'post_type' => 'page',
			'post_status' => 'publish',				
			'meta_query' => array(
								array(
									'key' => '_wp_page_template',
									'value' => 'page-template_form.php',
									'compare' => '='
									),				
								array(
									'key' => 'template_post_type',
									'value' => $post_type,
									'compare' => '='
									)
								)
				);
		remove_all_actions('posts_where');
		$the_query  = new WP_Query( $args );	
		if( $the_query->have_posts()):
			foreach($the_query as $post):				
				$page_id=$post->ID;
			endforeach;
			//get the front side submited page id permalink					
			$page_link=get_permalink($page_id);
			$edit_link = '';
			$review_link = '';
			if(strpos($page_link, "?"))
			{
				$edit_link = $page_link."&pid=".$postid."&action=edit";
				$review_link = $page_link."&pid=".$postid."&renew=1";
				$delete_link = $page_link."&pid=".$postid."&page=preview&action=delete";
			}
			else
			{
				$edit_link = $page_link."?pid=".$postid."&action=edit";
				$review_link = $page_link."?pid=".$postid."&renew=1";
				$delete_link = $page_link."?pid=".$postid."&page=preview&action=delete";
			}
			$exp_days = get_time_difference_plugin( $postdate, $postid);
			$link = '';
			if($exp_days > 0 && $exp_days != '' )
			 {
				$link.='<a class="button tiny_btn post-edit-link" title="Edit Item" href="'.$edit_link.'" target="_blank">Edit</a>&nbsp;&nbsp;';
			 }
			else
			 {		
				$link.='<a class="button tiny_btn post-edit-link" title="Renew Item" href="'.$review_link.'" target="_blank">Renew</a>&nbsp;&nbsp;';
			 }	
			 $link.='<a class="button tiny_btn post-edit-link" title="Delete Item" href="'.$delete_link.'" target="_blank">Delete</a>&nbsp;&nbsp;';
		endif;
		$title.=$link;		
	}
	$post=$author_post;
   echo $title;
}
add_action('templ_show_edit_renew_delete_link', 'nightlife_author');
?>