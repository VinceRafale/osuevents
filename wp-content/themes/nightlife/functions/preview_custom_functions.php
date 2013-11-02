<?php 
/*Remove action for preview page  */
session_start();
if(isset($_POST['preview'])){	
	$_SESSION['custom_fields'] = $_POST; 
}
if($_SESSION['custom_fields']['cur_post_type']==CUSTOM_POST_TYPE_EVENT )
{	
	if($_FILES['organizer_logo']['name'])
	{
		$upload_file['organizer_logo'] = get_file_upload($_FILES['organizer_logo']);
		$_SESSION['upload_file'] = $upload_file;		
	}
	remove_action('tmpl_preview_page_gallery','tmpl_preview_detail_page_gallery_display'); /*Remove preview page post image gallery  */
	remove_action('templ_preview_address_map','templ_preview_address_map_display');/* Remove preview page post map */
	remove_action('tmpl_preview_page_fields_collection','tmpl_preview_detail_page_fields_collection_display');/* Remove preview post page custom field */
	remove_action('templ_preview_page_file_upload','templ_preview_page_file_upload_display');/* Remove preview post page custom field */
	
	/*Add action after preview post title */
	add_action('templ_preview_after_post_title','previw_afte_post_title_custom_field');
	function previw_afte_post_title_custom_field()
	{	
		if(isset($_SESSION['custom_fields']))
		{
			
			$cur_post_id = $_SESSION['custom_fields']['cur_post_id'];
			$cur_post_type = get_post_meta($cur_post_id,'template_post_type',true);
			
			$st_date=$_SESSION['custom_fields']['st_date'];
			$end_date=$_SESSION['custom_fields']['end_date'];
			$st_time=$_SESSION['custom_fields']['st_time'];
			$end_time=$_SESSION['custom_fields']['end_time'];
			$address=$_SESSION['custom_fields']['address'];
			$phone=$_SESSION['custom_fields']['phone'];
			$email=$_SESSION['custom_fields']['email'];
			$website=$_SESSION['custom_fields']['website'];
			
			?>
			<div class="event_detail clearfix">
				<div class="col1">
				<?php if($st_date!=""):?><p class="date"><span><?php _e('STARTING DATE',T_DOMAIN)?></span><?php echo date("M dS,Y",strtotime($st_date));?></p><?php endif;?>
				<?php if($end_date!=""):?><p class="date"><span><?php _e('ENDING DATE',T_DOMAIN)?></span><?php echo date("M dS,Y",strtotime($end_date));?></p><?php endif;?>
				 <?php if($st_time!="" && $end_time!=""):?> <p class="time"><span><?php _e('TIME',T_DOMAIN)?></span><?php echo $st_time." - ".$end_time;?></p><?php endif;?>
                 <?php if($website!="" ):?> <p class="website"><span><?php _e('WEBSITE',T_DOMAIN)?></span><?php echo $website;?></p><?php endif;?>
				</div>
				<div class="col2">
					<?php if($address!=""):?><p class="location"><span><?php _e('LOCATION',T_DOMAIN)?></span><?php echo $address;?></p><?php endif;?>
					<?php if($phone!=""):?><p class="phone"><span><?php _e('PHONE',T_DOMAIN)?></span><?php echo $phone;?></p><?php endif;?>
					<?php if($email!=""):?><p class="email"><span><?php _e('EMAIL',T_DOMAIN)?></span><?php echo $email?></p><?php endif;?>
				</div>
			</div>
		<?php
		}
	}
	
	add_action('tmpl_preview_page_gallery','previw_page_gallery_map_image');
	function previw_page_gallery_map_image()
	{	
		if(isset($_SESSION))
		{
			wp_enqueue_script( 'jquery-ui-tabs' );		
			$geo_latitude = $_SESSION['custom_fields']['geo_latitude'];
			$geo_longitude =$_SESSION['custom_fields']['geo_longitude'];
			$address = $_SESSION['custom_fields']['address'];
			$map_type =$_SESSION['custom_fields']['map_view'];
			$post_title=$_SESSION['custom_fields']['post_title'];
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
				<ul>
					<?php  if($geo_latitude && $geo_longitude):?>
					<li><a href="#location_map"><?php _e('Location Map',T_DOMAIN);?></a></li>
					<?php endif;?>
					<?php if(isset($_SESSION['file_info']) && $_SESSION["file_info"]!=""):?>
					<li><a href="#image_gallery"><?php _e('Image Gallery',T_DOMAIN);?></a></li>		
					<?php endif;?>               	
				</ul>
				 <!-- Location Map-->
				 <?php if($geo_latitude && $geo_longitude):?>
				<div id="location_map">
					<div class="google_map" id="detail_google_map_id">                
						<?php include_once ('google_map_detail.php');?> 
					</div>               
				</div><!-- google map #end -->   
				<?php endif;?>
				<?php if($_SESSION["file_info"]!=""):?>
					<!--Image Gallery Start -->
					 <div id="image_gallery">   
					 <?php	
							$thumb_img_counter = 0;
							$thumb_img_counter = $thumb_img_counter+count($_SESSION["file_info"]);
							$image_path = get_image_phy_destination_path_plugin();
							$tmppath = "/".$upload_folder_path."tmp/";						
							foreach($_SESSION["file_info"] as $image_id=>$val):
								 $thumb_image = get_template_directory_uri().'/images/tmp/'.trim($val);
								break;
							endforeach;	
							
							if(isset($_REQUEST['pid']) && $_REQUEST['pid']!="")
							{	/* exicute when comes for edit the post */
								$large_img_arr = bdw_get_images_plugin($_REQUEST['pid'],'medium');
								$thumb_img_arr = bdw_get_images_plugin($_REQUEST['pid'],'thumb');
								$largest_img_arr = bdw_get_images_plugin($_REQUEST['pid'],'large');		
							}
						 ?>
							 <div class="image_content_details">
								 <div class="graybox">
								 <?php $f=0; foreach($_SESSION["file_info"] as $image_id=>$val):
										$curry = date("Y");
										$currm = date("m");
										$src = get_template_directory().'/images/tmp/'.$val;
										$img_title = pathinfo($val);
										
								  ?>
									<?php if($largest_img_arr): ?>
											<?php  foreach($largest_img_arr as $value):
												 $name = end(explode("/",$value['file']));
												  if($val == $name):	
											?>
												<img src="<?php echo  $value['file'];?>" alt="" class="Thumbnail thumbnail large post_imgimglistimg"/>
											<?php endif;
												endforeach;?>
									<?php else: ?>								
										<img src="<?php echo $thumb_image;?>" alt=""  class="Thumbnail thumbnail large post_imgimglistimg"/>
									<?php endif; ?>    
								  <?php if($f==0) break; endforeach;?>
								 </div>
							 </div>						
							 <div id="gallery" class="image_title_space">
								<ul class="more_photos">
								 <?php
									foreach($_SESSION["file_info"] as $image_id=>$val)
									{
										$curry = date("Y");
										$currm = date("m");
										$src = get_template_directory().'/images/tmp/'.$val;
										$img_title = pathinfo($val);						
										if($val):
										if(file_exists($src)):
												 $thumb_image = get_template_directory_uri().'/images/tmp/'.$val; ?>
												 <li><a href="<?php echo $thumb_image;?>" title="<?php echo $img_title['filename']; ?>"><img src="<?php echo $thumb_image;?>" alt="" height="70" width="70" title="<?php echo $img_title['filename'] ?>" /></a></li>
										<?php else: ?>
											<?php
												if($largest_img_arr):
												foreach($largest_img_arr as $value):
													$name = end(explode("/",$value['file']));									
													if($val == $name):?>
													<li><a href="<?php echo $value['file']; ?>" title="<?php echo $img_title['filename']; ?>"><img src="<?php echo $value['file'];?>" alt="" height="70" width="70" title="<?php echo $img_title['filename'] ?>" /></a></li>
											<?php
													endif;
												endforeach;
												endif;
											?>
										<?php endif; ?>
										
										<?php else: ?>
										<?php if($thumb_img_arr): ?>
											<?php 
											$thumb_img_counter = $thumb_img_counter+count($thumb_img_arr);
											for($i=0;$i<count($thumb_img_arr);$i++):
												$thumb_image = $large_img_arr[$i];
												
												if(!is_array($thumb_image)):
											?>
											  <li><a href="<?php echo $thumb_image;?>" title="<?php echo $img_title['filename']; ?>"><img src="<?php echo $thumb_image;?>" alt="" height="70" width="70" title="<?php echo $img_title['filename'] ?>" /></a></li>
											  <?php endif;?>
										<?php endfor; ?>
										<?php endif; ?>	
										<?php endif; ?>
									<?php
									$thumb_img_counter++;
									} ?>
									</ul>
							 </div>                           
					</div>
					<?php endif;?>
					<!--Finish Image gallery -->
			</div>
		<?php
		}
	}
	
	/*add action after preview post content */
	add_action('tmpl_preview_page_fields_collection','previce_detail_page_custom_fields');
	function previce_detail_page_custom_fields($cur_post_type)
	{
		$session=$_SESSION['custom_fields'];
		if(is_active_addons('custom_fields_templates'))
		{
			$heading_type = fetch_heading_per_post_type($cur_post_type);
			if(count($heading_type) > 0)
			 {
				foreach($heading_type as $_heading_type)
				 {
					$post_meta_info = tmpl_show_on_detail($cur_post_type,$_heading_type); /* return fields selected for detail page  */
				 }
			 }
			else
			 {
				 $post_meta_info = tmpl_show_on_detail($cur_post_type,''); /* return fields selected for detail page  */
			 }
		}
		else{
			$post_meta_info = array();
		}
		if($post_meta_info)
		{ 
			global $wpdb,$post,$upload;
			$heading_type = fetch_heading_per_post_type($cur_post_type);
			if(count($heading_type) > 0)
			  {
				foreach($heading_type as $_heading_type)
				 {
					$post_meta_info_arr[$_heading_type] = tmpl_show_on_detail($cur_post_type,$_heading_type);
				 }
			  }
			else
			  {
				  $post_meta_info_arr[] = tmpl_show_on_detail($cur_post_type,'');
			  }			
			echo "<div class='single_custom_field'>";
			echo "<ul class='list'>";
			$i=0;
			foreach($post_meta_info_arr as $key=> $post_meta_info)
			 {				
				while ($post_meta_info->have_posts()) : $post_meta_info->the_post();
					$key_custom=get_post_meta($post->ID,'htmlvar_name',true);					
				
				if($post->post_name != 'post_excerpt' && $post->post_name != 'post_content' && $post->post_name != 'post_title' && $post->post_name != 'category' && $session[$post->post_name] != '')
				{										
					if($key_custom!='st_date' && $key_custom!='end_date' && $key_custom!='st_time' && $key_custom!='end_time' && $key_custom!='event_type' && $key_custom!='phone' && $key_custom!='email' && $key_custom!='website' && $key_custom!='twitter' && $key_custom!='facebook' && $key_custom!='video' && $key_custom!='organizer_name' && $key_custom!='organizer_email' && $key_custom!='organizer_logo' && $key_custom!='organizer_address' && $key_custom!='organizer_contact' && $key_custom!='organizer_website' && $key_custom!='organizer_mobile' && $key_custom!='organizer_desc' && $key_custom!='post_images' && $key_custom!='org_info' && $key_custom!='address' && $key_custom!='map_view'){
						if($i==0)_e('<h3>Custom Fields</h3>',DOMAIN);
						
						if(get_post_meta($post->ID,"ctype",true) == 'multicheckbox')
						{
							foreach($session[$post->post_name] as $value)
							{
								$_value .= $value.",";	 
							}
							echo "<li><p>".$post->post_title." :&nbsp;</p> <p> ".substr($_value,0,-1)."</p></li>"; 
						}else
						{
	
							 echo "<li><p>".$post->post_title." :&nbsp;</p><p>".$session[$post->post_name]."</p></li>";
						}
						$i++;
					}					
				}
				endwhile;
			}
			echo "</ul>";
			echo "</div>";
		}	
	}
	
	add_action('templ_preview_after_post_content','preview_after_post_content_organizers');	
	function preview_after_post_content_organizers()
	{
		
		if(isset($_SESSION['custom_fields']))
		{		
			$org_address=$_SESSION['custom_fields']['organizer_address'];
			$org_contact=$_SESSION['custom_fields']['organizer_contact'];
			$org_mobile=$_SESSION['custom_fields']['organizer_mobile'];
			$org_email=$_SESSION['custom_fields']['organizer_email'];
			$org_website=$_SESSION['custom_fields']['organizer_website'];	
			$reg_desc=$_SESSION['custom_fields']['reg_desc'];	
			$org_logo=$_SESSION['upload_file']['organizer_logo'];
			$video=$_SESSION['custom_fields']['video'];			
			if($org_address!="" || $org_contact!="" || $org_mobile!="" || $org_email!="" || $$org_website!='' || $reg_desc!='' || $org_logo!='' || $video!=''):
			?>
			<div class="description">           
			<h3><?php _e('ORGANIZED BY',T_DOMAIN);?></h3>
				<?php if($org_logo):?>
               	<div class="org_logo"><img src="<?php echo $org_logo?>"  width="200" height="235"/></div>
               <?php endif;?>
				<div class="info">	           
                	<h4><?php echo $_SESSION['custom_fields']['organizer_name'];?></h4>
                    <?php if($org_address!="" || $org_mobile!="" || $org_email!="" || $org_website!=""):?>
					<span class="address"><?php echo $org_address;?></span>
					<span class="phone"><?php echo $org_contact;?></span>
					<span class="phone"><?php echo $org_mobile;?></span>
					<span class="email"><?php echo $org_email;?></span>
					<span class="website"><?php echo $org_website;?></span>
                    <?php endif;?>
				</div>				
				<div class="org_desc"><?php echo $_SESSION['custom_fields']['organizer_desc'];?> </div>     
				<div class="org_desc"><?php echo $reg_desc;?> </div>       
			</div>  
			 <?php if($video):?>
                <div class="org_video">
                    <?php echo stripslashes($video);?>
                </div>
            <?php endif;?>      
             <div class="event_social_media">		          
                <?php if($_SESSION['custom_fields']['facebook']!="" ):?>
                    <a href="<?php echo $_SESSION['custom_fields']['facebook'];?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/i_facebook21.png" alt="facebook"  /></a>
                <?php endif;?>
                <?php if($_SESSION['custom_fields']['twitter']!="" ):?>
                    <a href="<?php echo $_SESSION['custom_fields']['twitter'];?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/i_twitter2.png" alt="twitter"  /></a>
                <?php endif;?>
          </div>     
			<?php
			endif;
		}		
	}
}
?>