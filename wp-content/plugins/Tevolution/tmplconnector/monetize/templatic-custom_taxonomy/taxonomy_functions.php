<?php
/*
Name : listing_fields_collection
Desc : Return the collection for category listing page
*/
function listing_fields_collection()
{
	global $wpdb,$post;
	remove_all_actions('posts_where');
	$cus_post_type = get_post_type();
	$args = 
	array( 'post_type' => 'custom_fields',
	'posts_per_page' => -1	,
	'post_status' => array('publish'),
	'meta_query' => array(
	   'relation' => 'AND',
		array(
			'key' => 'post_type_'.$cus_post_type.'',
			'value' => $cus_post_type,
			'compare' => '=',
			'type'=> 'text'
		),
		array(
			'key' => 'show_on_page',
			'value' =>  array('user_side','both_side'),
			'compare' => 'IN'
		),
		array(
			'key' => 'is_active',
			'value' =>  '1',
			'compare' => '='
		),
		array(
			'key' => 'show_on_listing',
			'value' =>  '1',
			'compare' => '='
		)
	),
		'meta_key' => 'sort_order',
		'orderby' => 'meta_value',
		'order' => 'ASC'
	);
	$post_query = null;
	add_filter('posts_join', 'custom_field_posts_where_filter');
	$post_query = new WP_Query($args);
	remove_filter('posts_join', 'custom_field_posts_where_filter');
	return $post_query;
}
/* EOF */

/* NAME : custom fields for detail page
DESCRIPTION : this function wil return the custom fields of the post detail page */
function details_field_collection()
{
	global $wpdb,$post,$htmlvar_name;
	remove_all_actions('posts_where');
	remove_all_actions('posts_orderby');
	$cus_post_type = get_post_type();
	$args = 
	array( 'post_type' => 'custom_fields',
	'posts_per_page' => -1	,
	'post_status' => array('publish'),
	'meta_query' => array(
	   'relation' => 'AND',
		array(
			'key' => 'post_type_'.$cus_post_type.'',
			'value' => $cus_post_type,
			'compare' => '=',
			'type'=> 'text'
		),
		array(
			'key' => 'show_on_page',
			'value' =>  array('user_side','both_side'),
			'compare' => 'IN'
		),
		array(
			'key' => 'is_active',
			'value' =>  '1',
			'compare' => '='
		),
		array(
			'key' => 'show_on_detail',
			'value' =>  '1',
			'compare' => '='
		)
	),
		'meta_key' => 'sort_order',
		'orderby' => 'meta_value_num',
		'meta_value_num'=>'sort_order',
		'order' => 'ASC'
	);
	$post_meta_info = null;
	add_filter('posts_join', 'custom_field_posts_where_filter');
	$post_meta_info = new WP_Query($args);
	remove_filter('posts_join', 'custom_field_posts_where_filter');
	return $post_meta_info;
}
/* EOF */

add_action('tmpl_detail_page_custom_fields_collection','detail_fields_colletion');
/*
Name : detail_fields_colletion
Desc : Return the collection for detail/single page
*/
function detail_fields_colletion()
{
	global $wpdb,$post,$detail_post_type;
	$detail_post_type = $post->post_type;
	if($_REQUEST['pid'])
	{
		$cus_post_type = get_post_type($_REQUEST['pid']);
		$PostTypeObject = get_post_type_object($cus_post_type);
		$PostTypeLabelName = $PostTypeObject->labels->name;
		$single_pos_id = $_REQUEST['pid'];
	}
	else
	{	$cus_post_type = get_post_type($post->ID);
		$PostTypeObject = get_post_type_object($cus_post_type);
		$PostTypeLabelName = $PostTypeObject->labels->name;
		$single_pos_id = $post->ID;
	}
	$heading_type = fetch_heading_per_post_type($cus_post_type);
	remove_all_actions('posts_where');
	$post_query = null;
	if(count($heading_type) > 0)
	  {
		foreach($heading_type as $_heading_type)
		 {
			$args = 
			array( 'post_type' => 'custom_fields',
			'posts_per_page' => -1	,
			'post_status' => array('publish'),
			'meta_query' => array(
			   'relation' => 'AND',
				array(
					'key' => 'post_type_'.$cus_post_type.'',
					'value' => $cus_post_type,
					'compare' => '=',
					'type'=> 'text'
				),
				array(
					'key' => 'show_on_page',
					'value' =>  array('user_side','both_side'),
					'compare' => 'IN'
				),
				array(
					'key' => 'is_active',
					'value' =>  '1',
					'compare' => '='
				),
				array(
					'key' => 'heading_type',
					'value' =>  $_heading_type,
					'compare' => '='
				),
				array(
					'key' => 'show_on_detail',
					'value' =>  '1',
					'compare' => '='
				)
			),
				'meta_key' => 'sort_order',
				'orderby' => 'meta_value_num',
				'meta_value_num'=>'sort_order',
				'order' => 'ASC'				
			);
		$post_query = new WP_Query($args);
		$post_meta_info = $post_query;
		$suc_post = get_post($single_pos_id);
		
				if($post_meta_info->have_posts())
				  {
					echo "<div class='grid02 rc_rightcol clearfix'>";
					echo "<ul class='list'>";					
					  $i=0;
					while ($post_meta_info->have_posts()) : $post_meta_info->the_post();	
						if($i==0)
						{
							if($post->post_name!='post_excerpt' && $post->post_name!='post_content' && $post->post_name!='post_title' && $post->post_name!='post_images' && $post->post_name!='post_category')
							{
								if($_heading_type == "[#taxonomy_name#]")								 
									echo "<li><h2>".ucfirst($PostTypeLabelName)." Information</h2></li>";
								else
									echo "<li><h2>".$_heading_type."</h2></li>";  
							}
							$i++;
						}
					
							if(get_post_meta($single_pos_id,$post->post_name,true))
							  {
								if(get_post_meta($post->ID,"ctype",true) == 'multicheckbox')
								  {
									foreach(get_post_meta($single_pos_id,$post->post_name,true) as $value)
									 {
										$_value .= $value.",";
									 }
									 echo "<li><p>".$post->post_title." : </p> <p> ".substr($_value,0,-1)."</p></li>";
								  }
								else
								 {
									 if(get_post_meta($post->ID,'ctype',true) == 'upload')
									 {
									 	echo "<li><p>".$post->post_title." : </p> <p> Click here to download File <a href=".get_post_meta($single_pos_id,$post->post_name,true).">Download</a></p></li>";
									 }
									 else
									 {
										 echo "<li><p>".$post->post_title." : </p> <p> ".get_post_meta($single_pos_id,$post->post_name,true)."</p></li>";
									 }
								 }
							  }							
							if($post->post_name == 'post_excerpt' && $suc_post->post_excerpt!='')
							 {
								$suc_post_excerpt = $suc_post->post_excerpt;
								?>
                                     <li>
                                     <div class="row">
                                        <div class="twelve columns">
                                             <div class="title_space">
                                                 <div class="title-container">
                                                     <h1><?php _e('Post Excerpt');?></h1>
                                                     <div class="clearfix"></div>
                                                 </div>
                                                 <?php echo $suc_post_excerpt;?>
                                             </div>
                                         </div>
                                     </div>
                                     </li>
                                <?php
							 }
		
							if(get_post_meta($post->ID,"ctype",true) == 'geo_map')
							 {
								$add_str = get_post_meta($single_pos_id,'address',true);
								$geo_latitude = get_post_meta($single_pos_id,'geo_latitude',true);
								$geo_longitude = get_post_meta($single_pos_id,'geo_longitude',true);
								$map_view = get_post_meta($single_pos_id,'map_view',true);								
							 }		 
					endwhile;wp_reset_query();
					echo "</ul>";
					echo "</div>";
				  }		
		   }
	  }
	 else
	  {			
		$args = 
		array( 'post_type' => 'custom_fields',
		'posts_per_page' => -1	,
		'post_status' => array('publish'),
		'meta_query' => array(
		   'relation' => 'AND',
			array(
				'key' => 'post_type_'.$cus_post_type.'',
				'value' => $cus_post_type,
				'compare' => '=',
				'type'=> 'text'
			),
			array(
				'key' => 'show_on_page',
				'value' =>  array('user_side','both_side'),
				'compare' => 'IN'
			),
			array(
				'key' => 'is_active',
				'value' =>  '1',
				'compare' => '='
			),
			array(
				'key' => 'show_on_detail',
				'value' =>  '1',
				'compare' => '='
			)
		),
			'meta_key' => 'sort_order',
			'orderby' => 'meta_value',
			'order' => 'ASC'
		);				
		$post_query = new WP_Query($args);
		$post_meta_info = $post_query;
		$suc_post = get_post($single_pos_id);				
		if($post_meta_info->have_posts())
		{	
			$i=0;
			/*Display the post_detail gheading only one time also with if any custom field create. */
			while ($post_meta_info->have_posts()) : $post_meta_info->the_post();	
				if($i==0)
				if($post->post_name != 'post_excerpt' && $post->post_name != 'post_content' && $post->post_name != 'post_title' && $post->post_name != 'post_images' && $post->post_name != 'post_category')
				{
					echo '<div class="title-container clearfix">';	
					//echo '<h1>'.POST_DETAIL.'</h1>';
					$CustomFieldHeading = apply_filters('CustomFieldsHeadingTitle',POST_DETAIL);
				
					echo '<h1>'.$CustomFieldHeading.'</h1>';
				
					echo '</div>';
					$i++;
				}			
			endwhile;wp_reset_query();	//Finish this while loop for display POST_DETAIL	  		
			  ?>              
		<?php echo "<div class='grid02 rc_rightcol clearfix'>";
                echo "<ul class='list'>";
                if($_heading_type!="")			
                    echo "<h2>".$_heading_type."</h2>";	
			
			while ($post_meta_info->have_posts()) : $post_meta_info->the_post();				
					if(get_post_meta($single_pos_id,$post->post_name,true))
					  {
						if(get_post_meta($post->ID,"ctype",true) == 'multicheckbox')
						  {
							foreach(get_post_meta($single_pos_id,$post->post_name,true) as $value)
							 {
								$_value .= $value.",";
							 }
							 echo "<li><p class='tevolution_field_title'>".$post->post_title." : </p> <p class='tevolution_field_value'> ".substr($_value,0,-1)."</p></li>";
						  }
						else
						 {
							 echo "<li><p class='tevolution_field_title'>".$post->post_title." : </p> <p class='tevolution_field_value'> ".get_post_meta($single_pos_id,$post->post_name,true)."</p></li>";
						 }
					  }							
					if($post->post_name == 'post_excerpt' && $suc_post->post_excerpt!="")
					 {
						$suc_post_excerpt = $suc_post->post_excerpt;
						?>
                           <li>
                           <div class="row">
                              <div class="twelve columns">
                                   <div class="title_space">
                                       <div class="title-container">
                                           <h1><?php _e('Post Excerpt');?></h1>
                                           <div class="clearfix"></div>
                                       </div>
                                       <?php echo $suc_post_excerpt;?>
                                   </div>
                               </div>
                           </div>
                           </li>
				  <?php
					 }

					if(get_post_meta($post->ID,"ctype",true) == 'geo_map')
					 {
						$add_str = get_post_meta($single_pos_id,'address',true);
						$geo_latitude = get_post_meta($single_pos_id,'geo_latitude',true);
						$geo_longitude = get_post_meta($single_pos_id,'geo_longitude',true);								
					 }
  
			endwhile;wp_reset_query();
			echo "</ul>";
			echo "</div>";
		  }
	  }
		if($suc_post_con):
		do_action('templ_before_post_content');/*Add action for before the post content. */?> 
             <div class="row">
                <div class="twelve columns">
                     <div class="title_space">
                         <div class="title-container">
                             <h1><?php _e('Post Description', DOMAIN);?></h1>
                          </div>
                         <?php echo $suc_post_con;?>
                     </div>
                 </div>
             </div>
   		<?php do_action('templ_after_post_content'); /*Add Action for after the post content. */
		endif;		
			$tmpdata = get_option('templatic_settings');	
			$show_map='';
			if(isset($tmpdata['map_detail_page']) && $tmpdata['map_detail_page']=='yes')
				$show_map=$tmpdata['map_detail_page'];
			if($add_str != '')
			{
			?>
				<div class="row">
					<div class="title_space">
						<div class="title-container">
							<h1><?php _e('Map',DOMAIN); ?></h1>
						</div>
						<p><strong><?php _e('Location : '); echo $add_str;?></strong></p>
					</div>
					<div id="gmap" class="graybox img-pad">
						<?php 						
						if($geo_longitude &&  $geo_latitude ):
								$pimgarr = bdw_get_images_plugin($single_pos_id,'thumb',1);
								$pimg = $pimgarr[0];
								if(!$pimg):
									$pimg = CUSTOM_FIELDS_URLPATH."images/img_not_available.png";
								endif;	
								$title = $suc_post->post_title;
								$address = $add_str;
								require_once (TEMPL_MONETIZE_FOLDER_PATH . 'templatic-custom_fields/preview_map.php');
								$retstr ="";
								$retstr .= "<div class=\"forrent\"><img src=\"$pimg\" width=\"192\" height=\"134\" alt=\"\" />";
								$retstr .= "<h6><a href=\"\" class=\"ptitle\" style=\"color:#444444;font-size:14px;\"><span>$title</span></a></h6>";
								if($address){$retstr .= "<span style=\"font-size:10px;\">$address</span>";}
								$retstr .= "</div>";
								preview_address_google_map_plugin($geo_latitude,$geo_longitude,$retstr,$map_view);
							  else:
								if(is_ssl()){
									$url = 'https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q='.$add_str.'&amp;ie=UTF8&amp;z=14&amp;iwloc=A&amp;output=embed';
								}else{
									$url = 'http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q='.$add_str.'&amp;ie=UTF8&amp;z=14&amp;iwloc=A&amp;output=embed';
								}
						?>
								<iframe src="<?php echo $url; ?>" height="358" width="100%" scrolling="no" frameborder="0" ></iframe>
						<?php endif; ?>
					</div>
				</div>
			<?php }

}
/* EOF */

/* add action for send to friend and send inquiry email */

add_action('templ_send_friend_inquiry_email','send_friend_inquiry_email');

/*
 * Function Name: send_friend_inquiry_email
 * Return : display button for send to friend and inquiry mail
 */

function send_friend_inquiry_email()
{
	$tmpdata = get_option('templatic_settings');	
	if(isset($tmpdata['send_to_frnd'])&& $tmpdata['send_to_frnd']=='send_to_frnd')
	{
		
		$claim_content_link='<a class="button small_btn" rel="leanModal_email_friend" href="#basic-modal-content" id="send_friend_id"  title="Mail to a friend" >'. MAIL_TO_FRIEND.'</a>&nbsp;&nbsp;';				
		send_email_to_friend();
		$link.= $claim_content_link;
	}
	if(isset($tmpdata['send_inquiry'])&& $tmpdata['send_inquiry']=='send_inquiry')
	{			
		$claim_content_link='<a class="button small_btn" rel="leanModal_send_inquiry"  href="#inquiry_div" title="I" id="send_inquiry_id" >'.SEND_INQUIRY.'</a>&nbsp;&nbsp;';	
		send_inquiry();			
		$link.= $claim_content_link;
	}
	$link.='<div style="display: none; opacity: 0.5;" id="lean_overlay"></div>';
	echo $link;
}

/* End function */


/*
 *  add action for display single post image gallery
 */
add_action('tmpl_detail_page_image_gallery','single_post_image_gallery');
/*
 * Function Name: single_post_image_gallery
 * Return : display the single post image gallery in detail page.
 */
function single_post_image_gallery()
{
	global $post;
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
	?>
    <div class="row">
		 <?php if(count($post_images)>0): ?>
             <div class="content_details">
                 <div class="graybox">
                        <img id="replaceimg" src="<?php echo $post_images;?>" alt="<?php echo $img_alt; ?>" title="<?php echo $img_title; ?>"  />
                 </div>
             </div>            
         <?php endif; ?>
        <div class="row title_space">
            <?php if(count($post_images)>0): ?>
                <div class="title-container">
                    <h1><?php echo MORE_PHOTOS; ?></h1>
                 </div>
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
    <?php
}
/* EOF - display gallery */
/*
 * Add action for display related post
 */
add_action('tmpl_related_post','related_post_by_categories');
/*
 * Function Name: related_post_by_single_post
 * Return : Display the related post from single post
 */
function related_post_by_categories()
{
	global $post,$claimpost,$sitepress;
	$claimpost = $post;	
	$tmpdata = get_option('templatic_settings');
	$related_post =  @$tmpdata['related_post']; 	
	$taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post->post_type,'public'   => true, '_builtin' => true ));	
	remove_all_actions('posts_where');	
	if($related_post=='tags')
	{		
		$tags = wp_get_post_terms( $post->ID , $taxonomies[1],array("fields" => "names"));		
		$postQuery=array(
  			      'post_type'  => $post->post_type,			      
				  'tax_query' => array(                
						array(
							'taxonomy' =>$taxonomies[1],
							'field' => 'name',
							'terms' => $tags,
							'operator'  => 'IN'
						)            
					 ),  
			      'post__not_in' => array($post->ID),
			      'posts_per_page' => 3,
				  'showposts'=>-1,
			      'caller_get_posts'=>1,
				  'orderby' => 'RAND',
				 // 'order'  => 'DESC',
			    );
	}
	else
	{		
		 $terms = wp_get_post_terms($post->ID, $taxonomies[0], array("fields" => "names"));	
		 $postQuery = array(
			'post_type'    => $post->post_type,
			'post_status'  => 'publish',
			'tax_query' => array(                
						array(
							'taxonomy' =>$taxonomies[0],
							'field' => 'name',
							'terms' => $terms,
							'operator'  => 'IN'
						)            
					 ),
			'posts_per_page'=> 3,			
			'caller_get_posts'=>1,
			'orderby'      => 'RAND',
			'post__not_in' => array($post->ID)
		);
	}
	if(is_plugin_active('wpml-translation-management/plugin.php')){
		add_filter('posts_where', array($sitepress,'posts_where_filter'));	
	}	
	
	$my_query = new wp_query($postQuery);		
	 if( $my_query->have_posts() ) :
	 ?>
     <div class="realated_post clearfix">  
    	 <h3><span><?php echo sprintf(__('Related %ss',DOMAIN),ucfirst($post->post_type));?></span></h3>
		 <ul class="related_post_grid_view clearfix">
         <?php
		  while ( $my_query->have_posts() ) : $my_query->the_post();		   
		    $post_rel_img =  bdw_get_images_plugin(get_the_ID(),'thumb'); 			
			$title = @$post->post_title;
			$alt = $post->post_title;
		 ?>
         <li>
			<?php if($post_rel_img[0]){  ?>
            	<a class="post_img" href="<?php echo get_permalink(get_the_ID());?>"><img  src="<?php echo $post_rel_img[0];?>" alt="<?php echo $alt; ?>" title="<?php echo $title; ?>" width='150' height="150" /> </a>
            <?php 	}else{ ?>
            	<a class="post_img" href="<?php echo get_permalink(get_the_ID());  ?>"><img src="<?php echo TEMPL_PLUGIN_URL."/tmplconnector/monetize/images/no-image.png"; ?>"  width="150" height="150" alt="<?php echo $post_img[0]['alt']; ?>" /></a>
            <?php } ?>
         	<h3><a href="<?php echo get_permalink(get_the_ID());?>" > <?php the_title();?> </a></h3>
            <?php 	
				$TemplaticSettings = get_option('supreme_theme_settings');
				if(isset($TemplaticSettings['supreme_archive_display_excerpt']) && $TemplaticSettings['supreme_archive_display_excerpt']==1){	
					if(function_exists('tevolution_excerpt_length')){	
						$length = tevolution_excerpt_length();
						if(function_exists('print_excerpt')){
							echo print_excerpt($length);
						}
					}
				}else{
					the_content(); 
				}
			?>		
         </li>
         <?php endwhile;?>
         </ul>     
     </div>     
     <?php
	wp_reset_query();
	 endif;
}
/* EOF - related posts */

/*************************** LOAD THE BASE CLASS *******************************

 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class taxonmy_list_table extends WP_List_Table
{
	/***** FETCH ALL THE DATA AND STORE THEM IN AN ARRAY *****
	* Call a function that will return all the data in an array and we will assign that result to a variable $_posttaxonomy. FIRST OF ALL WE WILL FETCH DATA FROM POST META TABLE STORE THEM IN AN ARRAY $_posttaxonomy */
	function fetch_taxonomy_data( $_posttaxonomy)
	{ 

		$tax_label  = $_posttaxonomy['labels']['name'];
		$tax_desc = $_posttaxonomy['description'];
		$tax_category = $_posttaxonomy['taxonomies'][0];
		$tax_tags = $_posttaxonomy['taxonomies'][1];
		$tax_slug = $_posttaxonomy['query_var'];
		
		$edit_url = admin_url("admin.php?page=custom_taxonomy&action=edit-type&amp;post-type=$tax_slug");
		$meta_data = array(
			'title'	=> '<strong><a href="'.$edit_url.'">'.$tax_label.'</a></strong>',
			'tax_desc' 	=> $tax_desc,
			'tax_category' => $tax_category,
			'tax_tags' 	=> $tax_tags,
			'tax_slug' 	=> $tax_slug
			);
		return $meta_data;
	}
	/* FETCH TAXONOMY DATA */
	function taxonomy_data()
	{
		global $post;
		$posttaxonomy = get_option("templatic_custom_post");
		if($posttaxonomy):
			foreach($posttaxonomy as $key=>$_posttaxonomy):
						$taxonomy_data[] = $this->fetch_taxonomy_data($_posttaxonomy);
			endforeach;
		endif;
		return $taxonomy_data;
	}
	/* EOF - FETCH TAXONOMY DATA */
	
	/* DEFINE THE COLUMNS FOR THE TABLE */
	function get_columns()
	{
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Post Type Name',DOMAIN),
			'tax_desc' => __('Description',DOMAIN),
			'tax_category' => __('Taxonomy Name',DOMAIN),
			'tax_tags' => __('Tags',DOMAIN)
			);
		return $columns;
	}
	
	function process_bulk_action()
	{ 
		//Detect when a bulk action is being triggered...
		if('delete' === $this->current_action() )
		{
			 $_SESSION['custom_msg_type'] = 'delete';
			 $post_type = get_option("templatic_custom_post");
			 $taxonomy = get_option("templatic_custom_taxonomy");
			 $tag = get_option("templatic_custom_tags");
			 foreach($_REQUEST['checkbox'] as $tax_post_type)
			  {
				 $taxonomy_slug = $post_type[$tax_post_type]['slugs'][0];
				 $tag_slug = $post_type[$tax_post_type]['slugs'][1];
				 
				 unset($post_type[$tax_post_type]);
				 unset($taxonomy[$taxonomy_slug]);
				 unset($tag[$tag_slug]);
				 update_option("templatic_custom_post",$post_type);
				 update_option("templatic_custom_taxonomy",$taxonomy);
				 update_option("templatic_custom_tags",$tag);
				 unlink(get_template_directory()."/taxonomy-".$taxonomy_slug.".php");
				 unlink(get_template_directory()."/taxonomy-".$tag_slug.".php");
				 unlink(get_template_directory()."/single-".$post_type.".php");
			 }	 
			 wp_redirect(admin_url("admin.php?page=custom_taxonomy"));
			 $_SESSION['custom_msg_type'] = 'delete';
			 exit;
		}
	}
    
	function prepare_items()
	{
		$per_page = $this->get_items_per_page('taxonomy_per_page', 10);
		$columns = $this->get_columns(); /* CALL FUNCTION TO GET THE COLUMNS */
        $hidden = array();
		$sortable = array();
        $sortable = $this->get_sortable_columns(); /* GET THE SORTABLE COLUMNS */
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->process_bulk_action(); /* FUNCTION TO PROCESS THE BULK ACTIONS */
		$data = $this->taxonomy_data(); /* RETIRIVE THE PACKAGE DATA */
		
		/* FUNCTION THAT SORTS THE COLUMNS */
		function usort_reorder($a,$b)
		{
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
		if(is_array($data))
	        usort( $data, 'usort_reorder');
		
		$current_page = $this->get_pagenum(); /* GET THE PAGINATION */
		$total_items = count($data); /* CALCULATE THE TOTAL ITEMS */
		if(is_array($data))
			$this->found_data = array_slice($data,(($current_page-1)*$per_page),$per_page); /* TRIM DATA FOR PAGINATION*/
		$this->items = $this->found_data; /* ASSIGN SORTED DATA TO ITEMS TO BE USED ELSEWHERE IN CLASS */
		/* REGISTER PAGINATION OPTIONS */
		
		$this->set_pagination_args( array(
            'total_items' => $total_items,      //WE have to calculate the total number of items
            'per_page'    => $per_page         //WE have to determine how many items to show on a page
        ) );
	}
	
	/* To avoid the need to create a method for each column there is column_default that will process any column for which no special method is defined */
	function column_default( $item, $column_name )
	{
		switch( $column_name )
		{
			case 'cb':
			case 'title':
			case 'tax_desc':
			case 'tax_category':
			case 'tax_tags':
			case 'tax_slug':
			return $item[ $column_name ];
			default:
			return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
	
	/* DEFINE THE COLUMNS TO BE SORTED */
	function get_sortable_columns()
	{
		$sortable_columns = array(
			'title' => array('title',true)
			);
		return $sortable_columns;
	}
	
	/* DEFINE THE LINKS DISPPLAYING BELOW THE TITLE */
	function column_title($item)
	{
		$actions = array(
			'edit' => sprintf('<a href="?page=%s&action=%s&post-type=%s">Edit</a>',$_REQUEST['page'],'edit-type',$item['tax_slug']),
			'delete' => sprintf('<a href="?page=%s&post-type=%s">Delete Permanently</a>','delete-type',$item['tax_slug'])
			);
		
		return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions , $always_visible = false) );
	}
	
	/* DEFINE THE BULK ACTIONS */
	function get_bulk_actions()
	{
		$actions = array(
			'delete' => 'Delete permanently'
			);
		return $actions;
	}
	
	/* CHECKBOX TO SELECT ALL THE TAXONOMIES */
	function column_cb($item)
	{ 
		return sprintf(
			'<input type="checkbox" name="checkbox[]" id="checkbox[]" value="%s" />', $item['tax_slug']
			);
	}
}

/* NAME : FETCH CUSTOM POST TYPES
DESCRIPTION : THIS FUNCTION WILL FETCH ALL THE POST TYPES */
function fetch_post_types_labels()
{
	$types = get_post_types('','objects');
	return $types;
}

/* FILTERS TO ADD A COLUMN ON ALL USRES PAGE */
add_filter('manage_users_columns', 'add_test_column');
add_filter('manage_users_custom_column', 'view_test_column', 10, 3);

/* FUNCTION TO ADD A COLUMN */
function add_test_column($columns)
{
	$types = fetch_post_types_labels();
	foreach($types as $key => $values )
	{
		if(!($key == 'post' || $key == 'page' || $key == 'attachment' || $key == 'revision' || $key == 'nav_menu_item'))
		{
			foreach( $values as $label => $val)
			{
				$columns[$val->name] = $val->name;
			}
		}
	}
	return $columns;
}

/* FUNCTION TO DISPLAY NUMBER OF ARTICLES */
function view_test_column($out, $column_name, $user_id)
{
	global $wpdb,$articles;
	switch ( $column_name )
	{
		case $column_name :
			$result = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = '".strtolower($column_name)."' AND post_author = ".$user_id." AND post_status = 'publish'");
			if( count($result) > 0 )
			{
				$articles = "<a href='edit.php?post_type=".strtolower($column_name)."&author=".$user_id."' class='edit' title='View posts by this author'>".count($result)."</a>";
			}
			else
			{
				$articles = count($result);
			}
		break;
	}
	return $articles; 
}
/* EOF - ADD COLUMN ON ALL USERS PAGE */

/*
 * Function Name: the_breadcrumb
 * Return : Display the breadcrumb
 */
function the_breadcrumb() {
	if (!is_home()) {
		echo '<div class="breadcrumb"><a href="';
		echo get_option('home');
		echo '">Home';
		echo "</a>";
		if (is_category() || is_single() || is_archive()) {
			the_category('title_li=');
			if(is_archive())
			{		
				echo " » ";
				single_cat_title();
			}
			if (is_single()) {
				echo " » ";
				the_title();
			}
		} elseif (is_page()) {
			echo the_title();
		}		
		echo "</div>";
	}	
}
/*
 * Add Action display for single post page next previous pagination before comment
 */

add_action('tmpl_single_post_pagination','single_post_pagination');
/*
 * Function Name: single_post_pagination
 * Return : Display the next and previous  pagination in single post page
 */
function single_post_pagination()
{
	global $post;	
	?>
    <div class="pos_navigation clearfix">
        <div class="post_left fl"><?php previous_post_link('%link',''.__('Previous',DOMAIN)) ?></div>
        <div class="post_right fr"><?php next_post_link('%link',__('Next',DOMAIN).'') ?></div>
    </div>
    <?php
}

/*
 * Add action display post categories and tag before the post comments
 */
add_action('tmpl_before_comments','single_post_categories_tags'); 
function single_post_categories_tags()
{
	global $post;		
	the_taxonomies(array('before'=>'<p class="bottom_line"><span class="i_category">','sep'=>'</span>&nbsp;&nbsp;<span class="i_tag">','after'=>'</span></p>'));
}

/*
 * add action for display the post info
 */
add_action('templ_post_info','post_info');
function post_info()
{
	global $post;
	$num_comments = get_comments_number();
	if ( comments_open() ) {
		if ( $num_comments == 0 ) {
			$comments = __('No Comments',DOMAIN);
		} elseif ( $num_comments > 1 ) {
			$comments = $num_comments .' '. __('Comments',DOMAIN);
		} else {
			$comments = __('1 Comment',DOMAIN);
		}
		$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
	}
	?>
    <div class="byline">
		<?php
        $author = '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author_meta( 'display_name' ) ) . '">' . get_the_author_meta( 'display_name' ) . '</a></span>';
        $published = '<abbr class="published" title="' . sprintf( get_the_time( esc_attr__( ' F jS, Y, g:i a') ) ) . '">' . sprintf( get_the_time( esc_attr__( ' F jS, Y, g:i a') ) ) . '</abbr>';
        echo sprintf(__('Published by %s on %s %s',DOMAIN),$author,$published,$write_comments);
        ?>
    </div>
    <?php		
}
/* Add action for display the image in taxonomy page */
add_action('tmpl_category_page_image','tmpl_category_page_image');
/*
 * Function Name: tmpl_category_page_image
 */
function tmpl_category_page_image()
{
	global $post;		
	$post_img = bdw_get_images_plugin($post->ID,'thumbnail');
	$thumb_img = $post_img[0]['file'];
	$attachment_id = $post_img[0]['id'];
	$attach_data = get_post($attachment_id);
	$img_title = $attach_data->post_title;
	$img_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
	?>
    <a href="<?php the_permalink();?>" class="post_img">
    <?php if($thumb_img):?>
	    <img src="<?php echo $thumb_img; ?>" height="126" width="150" alt="<?php echo $img_alt; ?>" title="<?php echo $img_title; ?>" />
    <?php else:?>    
   		<img src="<?php echo CUSTOM_FIELDS_URLPATH; ?>/images/img_not_available.png" alt="" height="156" width="180"  />
    <?php endif;?>
    </a>
	<?php
}

/* filtering for featured listing  start*/
if(!strstr($_SERVER['REQUEST_URI'],'/wp-admin/') && $_REQUEST['slider_search'] =='')
{ 
	add_filter('init', 'templ_featured_ordering');
}
function templ_featured_ordering(){
	global $wp_query;
	
		add_action('posts_orderby', 'feature_filter_order');
	
		add_action('pre_get_posts', 'home_page_feature_listing');
		add_action('posts_orderby', 'home_page_feature_listing_orderby');

}
/* FEATURED POSTS FILTER FOR LISTING PAGE */
function feature_filter_order($orderby)
{
	global $wpdb,$wp_query;
	$current_term = $wp_query->get_queried_object();
	 if(is_category() || is_tax())
	 {
		$orderby = " (SELECT $wpdb->postmeta.meta_value from $wpdb->postmeta where ($wpdb->posts.ID = $wpdb->postmeta.post_id) AND $wpdb->postmeta.meta_key = 'featured_c' AND $wpdb->postmeta.meta_value = 'c') DESC,$wpdb->posts.ID DESC";		
	 }
	 return $orderby;
}
/* FETCH FEATURED POSTS FILTER FOR HOME PAGE */
function home_page_feature_listing( &$query)
{	
	if(isset($_REQUEST['post_type']) && $_REQUEST['post_type'] !=''):
		$post_type=$query->query_vars['post_type'];
	else:
		$post_type='';
	endif;
	if(is_home() || is_front_page()){
		$tmpdata = get_option('templatic_settings');
		$home_listing_type_value = $tmpdata['home_listing_type_value'];
		
		$attach = array('attachment');
		if(is_array($home_listing_type_value))
		$merge = array_merge($home_listing_type_value,$attach);
		if($post_type=='booking_custom_field')
			$query->set('post_type',$post_type); // set custom field post type
		else
			$query->set('post_type',$merge); // set post type events 
		$query->set('post_status',array('publish')); // set post type events 
	}else{

		remove_action('pre_get_posts', 'home_page_feature_listing');
	}
}

/* SORT FEATURED POSTS FILTER FOR HOME PAGE */
function home_page_feature_listing_orderby($orderby)
{
	global $wpdb,$wp_query;
	if(is_home() || is_front_page()){
		$orderby = " (SELECT $wpdb->postmeta.meta_value from $wpdb->postmeta where ($wpdb->posts.ID = $wpdb->postmeta.post_id) AND $wpdb->postmeta.meta_key = 'featured_type' AND $wpdb->postmeta.meta_value = 'h') DESC,$wpdb->posts.ID DESC";
	}
	return $orderby;
}
/* filtering for featured listing end*/


add_action('templ_listing_custom_field','templ_custom_field_display',10,2);
function templ_custom_field_display($custom_field,$pos_title)
{
	global $post,$wpdb;	
	?>
     <div class="postmetadata">
        <ul>
		<?php $i=0; 
          if($custom_field)
          foreach($custom_field as $key=> $_htmlvar_name):?>
                    <?php if($_htmlvar_name == 'multicheckbox' && get_post_meta($post->ID,$key,true) !=''): ?>
                         <li><label><?php echo $pos_title[$i]; ?></label> : <span><?php echo implode(",",get_post_meta($post->ID,$key,true)); ?></span></li>
                    <?php else: 
                         if(get_post_meta($post->ID,$key,true) !=''):
                         ?>
                         <li><label><?php echo $pos_title[$i]; ?></label> : <span><?php echo get_post_meta($post->ID,$key,true); ?></span></li>
                    <?php endif; ?>
                    <?php endif; ?>
          <?php $i++; endforeach; ?>
        </ul>
     </div>
     <?php	
}

/*
 * Function Name:get_templ_image
 * Argument : post id and image size
 * Return : image src if featured image in available
 */
function get_templ_image($post_id,$size='thumbnail') {

	global $post;
	/*get the thumb image*/	
	$thumbnail = wp_get_attachment_image_src ( get_post_thumbnail_id ( $post_id ), $size ) ;	
	if($thumbnail[0]!='')
	{
		$image_src=$thumbnail[0];		
	}else
	{
		$post_img_thumb = bdw_get_images_plugin($post_id,$size); 
		$image_src = $post_img_thumb[0]['file'];
	}	
	return $image_src;
}
?>