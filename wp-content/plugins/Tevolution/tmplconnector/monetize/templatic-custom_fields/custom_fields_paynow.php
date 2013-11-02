<?php 
ob_start();
if(isset($_SESSION['file_info']) &&($_SESSION['file_info']=='' || empty($_SESSION['file_info'][0]))){
	$_SESSION['file_info']= explode(',',$_SESSION['custom_fields']['imgarr']);
}
global $wpdb,$last_postid,$payable_amount;
global $current_user;
$current_user = wp_get_current_user();
$current_user_id = $current_user->ID;
/* fetch package information if monetization is activated */
if(is_active_addons('monetization') && class_exists('monetization')){
	global $monetization;
	$listing_price_info = $monetization->templ_get_price_info($_SESSION['custom_fields']['package_select'],$_SESSION['custom_fields']['total_price']);
	$listing_price_info = $listing_price_info[0];
	$payable_amount = $_SESSION['custom_fields']['total_price'];
	/* calculate total amout with coupon */
	if($_SESSION['custom_fields']['add_coupon'])
	{
		$payable_amount = get_payable_amount_with_coupon_plugin($payable_amount,$_SESSION['custom_fields']['add_coupon']);
	}
	/* redirect on preview page if monetization active + no payment method selected */
	if($_REQUEST['pid']=='' && isset($_REQUEST['paymentmethod']) && $_REQUEST['paymentmethod'] == '' && $payable_amount > 0)
	{
		wp_redirect(get_option( 'siteurl' ).'/?page=preview&msg=nopaymethod');
		exit;
	}
}else{
	$payable_amount =0;
}
/* insert user only when templatic login.registration wizard is activated */
if($current_user->ID =='' && $_SESSION['custom_fields'] && is_active_addons('templatic-login'))
{	
	$current_user_id=templ_insertuser_with_listing();	
}
$cat_display = get_option('templatic-category_type');

if($_POST)
{
	if($_POST['paynow'])
	{  
		$custom_fields = $_SESSION['custom_fields'];
		$custom = array();
		$post_title = stripslashes($custom_fields['post_title']);
		$description = $custom_fields['post_content'];
		$post_excerpt = $custom_fields['post_excerpt'];
		$catids_arr = array();
		$my_post = array();
		$alive_days = $listing_price_info['alive_days'];
		$payment_method = $_REQUEST['paymentmethod'];
		$coupon = $custom_fields['add_coupon'];
		$featured_type = $custom_fields['featured_type'];
		$pid = $_REQUEST['pid']; /* it will be use when going for RENEW */
		
		if($payable_amount <= 0)
		{	
			if($_SESSION['custom_fields']['last_selected_pkg'] !='')
			{
				global $monetization;
				$post_default_status = $monetization->templ_get_packaget_post_status($current_user->ID, get_post_meta($custom_fields['cur_post_id'],'template_post_type',true));
			}else{
				$post_default_status = fetch_posts_default_status();
			}
		}else
		{
			$post_default_status = 'draft';
		}		
		
		$my_post['post_status'] = $post_default_status;
		if($current_user_id)
		{
			$my_post['post_author'] = $current_user_id;
		}
		$my_post['post_title'] = $post_title;
		$my_post['post_name'] = $post_title;
		$my_post['post_content'] = $description;
		$my_post['post_category'] = $custom_fields['category'];
		$my_post['post_excerpt'] = $post_excerpt;
		$my_post['post_type'] = get_post_meta($custom_fields['cur_post_id'],'template_post_type',true);
		/* Here array separated by category id and price amount */
		if($_SESSION['category'])
		{
			$category_arr = $_SESSION['category'];
			foreach($category_arr as $_category_arr)
			 {
				$category[] = explode(",",$_category_arr);
			 }
			foreach($category as $_category)
			 {
				 $post_category[] = $_category[0];
				 $category_price[] = $_category[1];
			 }
		}
		
		/*Set the post per subscription limite post count on user meta table  */
		if($_REQUEST['pid'] =='')
		{
				$package_post=get_post_meta($_SESSION['custom_fields']['package_select'],'limit_no_post',true);
				$user_limit_post=get_user_meta($current_user_id,'list_of_post',true);
				if($package_post!=$user_limit_post)
				{
					$limit_post=get_user_meta($current_user_id,'list_of_post',true);				
					update_usermeta($current_user_id,'list_of_post',$limit_post+1);
					update_usermeta($current_user_id,'package_select',$_SESSION['custom_fields']['package_select']);
				}else
				{
					update_usermeta($current_user_id,'package_select',$_SESSION['custom_fields']['package_select']);
					update_usermeta($current_user_id,'list_of_post',1);
				}			
		}		
		/*Finish post per subscription limite post count on user meta table  */
	//	exit;
		
		if(isset($_REQUEST['pid']) && $_REQUEST['pid'] != '')
		{
			if($custom_fields['renew'])
			{
				if($post_status==''){
					$post_status ='publish';
				}
				$my_post['post_date'] = date('Y-m-d H:i:s');
				$my_post['post_status'] = $post_default_status;
				$my_post['ID'] = $_REQUEST['pid'];
				$my_post['comment_status'] = 'open';				
				$last_postid = wp_insert_post($my_post);
				update_post_meta($last_postid,'stripe_cancelled',0);
				/* Finish the place geo_latitude and geo_longitude in postcodes table*/
				if(is_plugin_active('wpml-translation-management/plugin.php')){
					if(function_exists('wpml_insert_templ_post'))
						wpml_insert_templ_post($last_postid,$my_post['post_type']); /* insert post in language */
				}				
				$post_tax = fetch_page_taxonomy($_SESSION['custom_fields']['cur_post_id']);
				wp_set_post_terms( $last_postid,'',$post_tax,false);
				if($post_category){
				foreach($post_category as $_post_category)
				 {
					if(taxonomy_exists($post_tax)):
						wp_set_post_terms( $last_postid,$_post_category,$post_tax,true);
					endif;
				 }
				}
				foreach($custom_fields as $key=>$val)
				{
					if($key != 'category' && $key != 'post_title' && $key != 'post_content' && $key != 'imgarr' && $key != 'Update' && $key != 'post_excerpt')
					  {
						if($key=='recurrence_bydays')
						{
							$val=implode(',',$val);
							update_post_meta($last_postid, $key, $val);
						}
						else
						{
							update_post_meta($last_postid, $key, $val);
						}
					  }
				}
			
				if(isset($_SESSION['upload_file']) && $_SESSION['upload_file']!="")
				{
					foreach($_SESSION['upload_file'] as $key=> $valfile)
					{
						update_post_meta($last_postid, $key, $valfile);
					}
				}

			}
			else
			{ /* Condtion for Edit post */
				$my_post['ID'] = $_REQUEST['pid'];
				$my_post['post_title'] = stripslashes($custom_fields['post_title']);
				$my_post['post_name'] = $custom_fields['post_title'];
				$my_post['post_content'] = $custom_fields['post_content'];
				$my_post['post_excerpt'] = $custom_fields['post_excerpt'];
				$my_post['post_type'] = get_post_meta($custom_fields['cur_post_id'],'template_post_type',true);
				$my_post['post_status'] = 'publish';
				$my_post['comment_status'] = 'open';				
				
				$last_postid = wp_insert_post( $my_post );
				
				/* Finish the place geo_latitude and geo_longitude in postcodes table*/
				if(is_plugin_active('wpml-translation-management/plugin.php')){
					if(function_exists('wpml_insert_templ_post'))
						wpml_insert_templ_post($post_id,$my_post['post_type']); /* insert post in language */
				}
				
				foreach($custom_fields as $key=>$val)
				{
					if($key != 'category' && $key != 'post_title' && $key != 'post_content' && $key != 'imgarr' && $key != 'Update' && $key != 'post_excerpt' && $key != 'alive_days')
					  {
						if($key=='recurrence_bydays')
						{
							$val=implode(',',$val);
							update_post_meta($last_postid, $key, $val);
						}
						else
						{
							update_post_meta($last_postid, $key, $val);
						}
					  }
				}
			
				if(isset($_SESSION['upload_file']) && $_SESSION['upload_file']!="")
				{
					foreach($_SESSION['upload_file'] as $key=> $valfile)
					{
						update_post_meta($last_postid, $key, $valfile);
					}
				}
			}
		}else
		{ 			
			$my_post['comment_status'] = 'open';
			$last_postid = wp_insert_post($my_post); //Insert the post into the database			
			$post_tax = fetch_page_taxonomy($_SESSION['custom_fields']['cur_post_id']);
			/* Finish the place geo_latitude and geo_longitude in postcodes table*/
			if(is_plugin_active('wpml-translation-management/plugin.php')){
				if(function_exists('wpml_insert_templ_post'))
					wpml_insert_templ_post($last_postid,$my_post['post_type']); /* insert post in language */
			}			
			if($post_category){
			foreach($post_category as $_post_category)
			 {
				if(taxonomy_exists($post_tax)):
					wp_set_post_terms( $last_postid,$_post_category,$post_tax,true);
				endif;
			 }
			 }
			 
			/* insert custom fields */
			foreach($custom_fields as $key=>$val)
			{
				if($key != 'category' && $key != 'post_title' && $key != 'post_content' && $key != 'imgarr' && $key != 'Update' && $key != 'post_excerpt')
				  {
					  if($key=='recurrence_bydays')
						{
							$val=implode(',',$val);
							update_post_meta($last_postid, $key, $val);
						}else
						{
							update_post_meta($last_postid, $key, $val);
						}
				  }
			} 
			if(isset($_SESSION['upload_file']) && $_SESSION['upload_file'] !=''){
					foreach($_SESSION['upload_file'] as $key=> $valfile)
					{
						update_post_meta($last_postid, $key, $valfile);
					} 
			}
		}
		if(class_exists('monetization')){
			if($custom_fields['renew'] || !$custom_fields['pid'])
			{
				global $monetization;
				$monetize_settings = $monetization->templ_set_price_info($last_postid,$pid,$payable_amount,$alive_days,$payment_method,$coupon,$featured_type);
			}
		}
		if(is_active_addons('monetization')){
			global $trans_id;
			$trans_id = insert_transaction_detail($_REQUEST['paymentmethod'],$last_postid);
			
		} 

		if(isset($_SESSION["file_info"]) && $_SESSION['file_info']!="")
		{
			$menu_order = 0;
			foreach($_SESSION["file_info"] as $image_id=>$val)
			{
				//$src = get_image_tmp_phy_path().$image_id.'.jpg';
				$src = TEMPLATEPATH."/images/tmp/".$val;
				if(file_exists($src) && $val != '')
				{
					$menu_order++;
					$dest_path = get_image_phy_destination_path_plugin().$val;
					$original_size = get_image_size_plugin($src);
					$thumb_info = image_resize_custom_plugin($src,$dest_path,get_option('thumbnail_size_w'),get_option('thumbnail_size_h'));
					$medium_info = image_resize_custom_plugin($src,$dest_path,get_option('medium_size_w'),get_option('medium_size_h'));
					$post_img = move_original_image_file_plugin($src,$dest_path);

					$post_img['post_status'] = 'attachment';
					$post_img['post_parent'] = $last_postid;
					$post_img['post_type'] = 'attachment';
					$post_img['post_mime_type'] = 'image/jpeg';
					$post_img['menu_order'] = $menu_order;

					$dirinfo = wp_upload_dir();		
					$path = $dirinfo['path'];
					$url = $dirinfo['url'];
					$subdir = $dirinfo['subdir'];
					$basedir = $dirinfo['basedir'];
					$baseurl = $dirinfo['baseurl'];	
					 $wp_filetype = wp_check_filetype(basename($val), null );
					$attachment = array(
						 'guid' => $baseurl.$subdir."/"._wp_relative_upload_path( $val ),
						 'post_mime_type' => $wp_filetype['type'],
						 'post_title' => preg_replace('/\.[^.]+$/', '', basename($val)),
						 'post_content' => '',
						 'post_status' => 'inherit',
						 'menu_order' => $menu_order
					  );		

					//$last_postimage_id = wp_insert_post( $post_img ); // Insert the post into the database
		
					  $img_attachment = substr($subdir."/".$val,1);
					  $attach_id = wp_insert_attachment( $attachment, $img_attachment, $last_postid );
					 
					  require_once(ABSPATH . 'wp-admin/includes/image.php');					 
					  $upload_img_path=$basedir.$subdir."/"._wp_relative_upload_path( $val);
					  $attach_data = wp_generate_attachment_metadata( $attach_id, $upload_img_path );					
					  wp_update_attachment_metadata( $attach_id, $attach_data );

					
				}
			}
		}

		if(!$_REQUEST['pid']){
		update_post_meta($last_postid, 'remote_ip',getenv('REMOTE_ADDR'));
		update_post_meta($last_postid,'ip_status',$_SESSION['custom_fields']['ip_status']);
		}
	  /* Code for update menu for images */
	  
	  if($_REQUEST['pid'])
		  {
			$j = 1;
			foreach($_SESSION["file_info"] as $arrVal)
			 {
				$expName = array_slice(explode(".",$arrVal),0,1);
				$wpdb->query('update '.$wpdb->posts.' set  menu_order = "'.$j.'" where post_name = "'.$expName[0].'"  and post_parent = "'.$_REQUEST['pid'].'"');
				$j++;	
			 }
		  }

	/* End Code for update menu for images */
		///////ADMIN EMAIL START//////
			$fromEmail = get_site_emailId_plugin();
			$fromEmailName = get_site_emailName_plugin();
			$store_name = get_option('blogname');
			$tmpdata = get_option('templatic_settings');
			$email_content =  stripslashes($tmpdata['post_submited_success_email_content']);
			$email_subject =  stripslashes($tmpdata['post_submited_success_email_subject']);
			
			$email_content_user =  stripslashes($tmpdata['post_submited_success_email_user_content']);
			$email_subject_user =  stripslashes($tmpdata['post_submited_success_email_user_subject']);
			
			if(!$email_subject)
			{
				$email_subject = __('New Post listing of ID:#'.$last_postid);	
			}
			if($_REQUEST['pid']){
				$email_subject = __('Post updated of ID:#'.$last_postid);
			}
			if(isset($_SESSION['custom_fields']['renew']))
			{
				$email_subject = __('Post renew of ID:#'.$last_postid);
			}
			if(!$email_content)
			{
				$email_content = __('<p>Dear [#to_name#],</p>
				<p>A New listing has been submitted on your site. Here is the information about the listing:</p>
				[#information_details#]
				<br>
				<p>[#site_name#]</p>');
			}
			if($_REQUEST['pid'] )
			{
				$email_content = __('<p>Dear [#to_name#],</p>
				<p>Post has been updated on your site. Here is the information about the property:</p>
				[#information_details#]
				<br>
				<p>[#site_name#]</p>');
			}
			if(isset($_SESSION['custom_fields']['renew']))
			{
				$email_content = __('<p>Dear [#to_name#],</p>
				<p>Post has been renew on your site. Here is the information about the property:</p>
				[#information_details#]
				<br>
				<p>[#site_name#]</p>');
				
			}				
			
			if(!$email_subject_user)
			{
				$email_subject_user = __(sprintf('New Post listing of ID:#%s',$last_postid));	
			}
			if($_REQUEST['pid']){
				$email_subject_user = __(sprintf('Post updated of ID:#%s',$last_postid));
			}
			if(isset($_SESSION['custom_fields']['renew']))
			{
				$email_subject_user = __(sprintf('Post renew of ID:#%s',$last_postid));
				
			}	
			if(!$email_content_user)
			{
				$email_content_user = __('<p>Dear [#to_name#],</p><p>A New Post has been submitted by you . Here is the information about the Post:</p>[#information_details#]<br><p>[#site_name#]</p>');
			}
			if($_REQUEST['pid'])
			{
				$email_content_user = __('<p>Dear [#to_name#],</p><p>Your Post has been updated by you . Here is the information about the Post:</p>[#information_details#]<br><p>[#site_name#]</p>');
			}
			if(isset($_SESSION['custom_fields']['renew']))
			{
				$email_content_user = __('<p>Dear [#to_name#],</p><p>Your Post has been renew by you . Here is the information about the Post:</p>[#information_details#]<br><p>[#site_name#]</p>');
				
			}	
			$information_details = "<p>".__('ID')." : ".$last_postid."</p>";
			$information_details .= '<p>'.__('View more detail from').' <a href="'.get_permalink($last_postid).'">'.stripslashes($my_post['post_title']).'</a></p>';
			global $payable_amount;
			if(is_active_addons('monetization') && $payable_amount > 0){
				$information_details .= '<p>'.__('Payment Status: <b>Pending</b>').'</p>';
			}else{
				$information_details .= '<p>'.__('Payment Status: <b>Success</b>').'</p>';
			}	
			$post_type=get_post_meta($custom_fields['cur_post_id'],'template_post_type',true);
			$show_on_email=get_post_custom_fields_templ_plugin($post_type);			
			if($show_on_email)
			{
				$information_details.='<ul>';
				foreach($show_on_email as $key=>$val)
				{					
					if($key=='post_title' && $val['show_in_email'])
					{
						$information_details.= '<li><label>'.$val['label'].' :</label>'.$my_post['post_title'].'</li>';
					}
					if($key=='post_content' && $val['show_in_email'] && $my_post['post_content']!='')
					{
						$information_details.= '<li><label>'.$val['label'].' :</label>'.$my_post['post_content'].'</li>';
					}
					if($key=='post_excerpt' && $val['show_in_email'] && $my_post['post_excerpt']!='')
					{
						$information_details.= '<li><label>'.$val['label'].' :</label>'.$my_post['post_excerpt'].'</li>';
					}
					
					if($val['type'] == 'multicheckbox' && get_post_meta($last_postid,$val['htmlvar_name'],true) !='' && $val['show_in_email']=='1')
					{
						$information_details.='<li><label>'.$val['label'].' :</label> '.  implode(",",get_post_meta($last_postid,$val['htmlvar_name'],true)).'</li>';
					}else{					
						if($val['show_in_email']=='1' && get_post_meta($last_postid,$val['htmlvar_name'],true)!="")
						{
							$information_details.= '<li><label>'.$val['label'].' :</label>'.get_post_meta($last_postid,$val['htmlvar_name'],true).'</li>';
						}
					}
					
				}
				$information_details.='</ul>';
			}
			
			$search_array = array('[#to_name#]','[#information_details#]','[#site_name#]');
			$uinfo = get_userdata($current_user_id);
			$user_fname = $uinfo->display_name;
			$user_email = $uinfo->user_email;
			$replace_array_admin = array($fromEmail,$information_details,$store_name);
			$replace_array_client =  array($user_email,$information_details,$store_name);
			$email_content_admin = str_replace($search_array,$replace_array_admin,$email_content);
			$email_content_client = str_replace($search_array,$replace_array_client,$email_content_user);									
			templ_send_email($user_email,$user_fname,$fromEmail,$fromEmailName,$email_subject,$email_content_admin,$extra='');///To admin email			
			templ_send_email($fromEmail,$fromEmailName,$user_email,$user_fname,$email_subject_user,$email_content_client,$extra='');//to client email			
		//////ADMIN EMAIL END////////


		if(is_active_addons('monetization') && ($payable_amount != '' || $payable_amount >= 0) && $_REQUEST['paymentmethod']){
			payment_menthod_response_url($_REQUEST['paymentmethod'],$last_postid,$custom_fields['renew'],$_REQUEST['pid'],$payable_amount);
		}else{
			$suburl = "&pid=$last_postid";
			if(isset($_REQUEST['lang']) && $_REQUEST['lang']!="")
			{
				wp_redirect(get_option('siteurl').'/?page=success&lang='.$_REQUEST['lang'].$suburl);
			}
			else{
				wp_redirect(get_option('siteurl').'/?page=success'.$suburl);
			}
		}
		
	}
}
?>