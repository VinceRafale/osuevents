<?php
/* NAME : FETCH CLAIMS
DESCRIPTION : THIS FUNCTION FETCHES CLAIMS IN A METABOX DISPLAYING ON WORDPRESS DASHBOARD */
function fetch_claims()
{
	global $wpdb,$claim_db_table_name; ?>
	<script type="text/javascript">
	/* <![CDATA[ */
	function confirmSubmit(str) {
			var answer = confirm("<?php echo DELETE_CONFIRM_ALERT; ?>");
			if (answer){
				window.location = "<?php echo site_url(); ?>/wp-admin/index.php?poid="+str;
				alert('<?php echo ENTRY_DELETED; ?>');
			}
		}
	function claimer_showdetail(str)
	{	
		if(document.getElementById('comments_'+str).style.display == 'block')	{
			document.getElementById('comments_'+str).style.display = 'none';
		} else {
			document.getElementById('comments_'+str).style.display = '';
		}
	}
	/* ]]> */
	</script>
	<?php /* DISPLAY CLAIM DATA IN TABLE */
	echo "<table class='widefat'>
	<tr>
		<th>".ID_TEXT."</th>
		<th>".TITLE_TEXT."</th>
		<th>".AUTHOR_NAME_TEXT."</th>
		<th>".CLAIMER_TEXT."</th>
		<th>".CONTACT_NUM_TEXT."</th>
		<th>".STATUS."</th>
		<th>".ACTION_TEXT."</th>
	</tr>";
	$claim_post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = 'claim'");
	if(count($claim_post_ids) != 0)
	{
		$counter =0;
		foreach ($claim_post_ids as $claim_post_id) :
		$data = get_post_meta($claim_post_id, 'post_claim_data',true);
		/* FETCH CLAIM DATA */
		$post_id = $data['post_id'];
		$post_title = $data['post_title'];
		$claimer_name = $data['claimer_name'];
		$name = str_word_count($claimer_name,1);
		$claimer_contact = $data['claimer_contact'];
		$author_id = $data['author_id'];
		$status = $data['claim_status'];
		$msg = $data['claim_msg'];
		
			$udata = get_userdata($author_id);
			echo "<tr><td>".$claim_post_id."</td>
			<td>";?>
			<a href="<?php echo site_url().'/wp-admin/post.php?post='.$post_id.'&action=edit';?>" title="<?php echo VIEW_CLAIM; ?>"><?php echo $post_title."</a></td>
			<td>".$udata->display_name."</td>
			<td>".$claimer_name."</td>
			<td>".$claimer_contact."</td>";
			if($status == 'approved' && get_post_meta($post_id,'is_verified',true) == 1) : ?>
				<td id="verified"><?php echo YES_VERIFIED; ?></td>
			<?php elseif($status == 'declined') : ?>
				<td id="declined"><?php echo DECLINED; ?></td>
			<?php else : ?>
				<td id="unapproved"><?php echo PENDING; ?></td>
			<?php endif; echo "<td>"; ?>
			<a href="javascript:void(0);claimer_showdetail('<?php echo $claim_post_id;?>');"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/details.png" alt="<?php echo DETAILS_CLAIM; ?>" title="<?php echo DETAILS_CLAIM; ?>" border="0" /></a>&nbsp;&nbsp;
			<a href="<?php echo site_url().'/wp-admin/post.php?post='.$post_id.'&action=edit&verified=yes&clid='.$claim_post_id .'&user='.$name[0]?>" title="<?php echo VERIFY_CLAIM; ?>"><img style="width:16px; height:16px;" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/accept.png" alt="<?php echo VERIFY_CLAIM; ?>" border="0" /></a>&nbsp;&nbsp;
			<a href="<?php echo site_url().'/wp-admin/post.php?post='.$post_id.'&action=edit';?>" title="<?php echo VIEW_CLAIM; ?>"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/view.png" alt="<?php echo VIEW_CLAIM; ?>" border="0" /></a>&nbsp;&nbsp;
			<a href="javascript:void(0);" onclick="return confirmSubmit(<?php echo $claim_post_id; ?>);" title="<?php echo DELETE_CLAIM; ?>"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/delete.png" alt="<?php echo DELETE_CLAIM; ?>" border="0" /></a>
			<?php echo "</tr>"; ?>
			<tr id='<?php echo "comments_".$claim_post_id; ?>' style='display:none; padding:5px;'><td colspan="7"><?php echo $msg; ?> </td></tr>
		<?php
		$c = $counter ++;
		endforeach;
	}
	else
	{
		echo "<tr><td colspan='6'>".NO_CLAIM_REQUEST."</td></tr>";
	}
	echo "</table>";
}
if(isset($_REQUEST['poid']) && @$_REQUEST['poid'] != "")
{
	global $wpdb,$post;
	$vclid = $_REQUEST['poid'];
	/* DELETING THE CLAIM ON CLICK OF DELETE BUTTON OF DASHBOARD METABOX */	
	delete_post_meta($vclid, 'post_claim_data');
	delete_post_meta($vclid,'is_verified',0);
	wp_delete_post($vclid);
}

/* EOF - FETCH CLAIMS IN DASHBOARD METABOX */

/* NAME :ADD A DASHBOARD METABOX
DESCRIPTION : THIS FUNCTION WILL ADD A METABOX IN WORDPRESS DASHBOARD */
function add_claim_dashboard_metabox()
{
	global $wp_meta_boxes,$current_user;
	if(is_super_admin($current_user->ID)) {
		wp_add_dashboard_widget('claim_dashboard_metabox', 'Ownership claims', 'fetch_claims');
		$wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'];
	}
}
/* EOF - CLAIM DASHBOARD METABOX */

/* NAME : ADD METABOX IN POSTS
DESCRIPTION : THIS FUNCTION WILL ADD A METABOX ON ADD/EDIT PAGE OF EVERY POST */
function add_claim_metabox_posts ()
{
	$tmpdata = get_option('templatic_settings');
	$post_type = $tmpdata['claim_post_type_value'];
	if($post_type){
	foreach($post_type as $type) :
	add_meta_box("Claim post", "Claim post", "fetch_meta_options", $type, "side", "high");
	endforeach;
	}
}
/* EOF - ADD METABOX IN POSTS */

/* NAME : FETCH META OPTIONS
DESCRIPTION : THIS FUNCTION WILL FETCH THE CLAIM DATA IN POST'S METABOX */
function fetch_meta_options()
{
	global $wpdb,$post;
	$claim_status = "";
	
	/* VERIFY THE USER */
	if(@$_REQUEST['verified'] == 'yes' && $_REQUEST['user'])
	{
		$clid = $_REQUEST['clid'];
		$_REQUEST['user'];
		/* UPDATE CLAIM STATUS WHEN THE ADMIN VERIFIES THE AUTHOR */
		$data = get_post_meta($clid, 'post_claim_data',true);
		$post_id = $data['post_id'];
		$request_uri = $data['request_uri'];
		$link_url = $data['link_url'];
		$claimer_id = $data['claimer_id'];
		$post_title = $data['post_title'];
		$claimer_name = $data['claimer_name'];
		$claimer_email = $data['claimer_email'];
		$claimer_contact = $data['claimer_contact'];
		$author_id = $data['author_id'];
		$claim_status = $data['claim_status'];
		$claim_msg = $data['claim_msg'];
		$post = array('post_id' => $post_id,
					  'request_uri' => $request_uri,
					  'link_url' => $link_url,
					  'claimer_id' => $claimer_id,
					  'author_id' => $author_id,
					  'post_title' => $post_title,
					  'claimer_name' => $claimer_name,
					  'claimer_email' => $claimer_email,
					  'claimer_contact' => $claimer_contact ,
					  'claim_msg' => $claim_msg,
					  'claim_status' => 'approved');
		update_post_meta($clid,'post_claim_data',$post); /* UPDATING THE WHOLE CLAIM DATA ARRAY */
		add_post_meta($post_id,'is_verified',1);
		$wpdb->update( $wpdb->posts, array('post_excerpt' => 'approved'), array('ID' => $clid));
		if ($claimer_id != '' && $claimer_id == '0' && $_REQUEST['user']) :
			add_verified_user($clid); /* CALL A FUNCTION TO ADD VERIFIED USER */
		endif;
	}
	
	/* DELETE THE USER */
	else if(@$_REQUEST['verified'] == 'no' && $_REQUEST['clid'])
	{
		$clid = $_REQUEST['clid'];
		$data = get_post_meta($clid, 'post_claim_data',true);
		$post_id = $data['post_id'];
		delete_post_meta($clid, 'post_claim_data');
		wp_delete_post($clid);
		delete_post_meta($post_id,'is_verified',0);
	}
	
	/*DECLINE THE USER */
	else if(@$_REQUEST['decline'] == 'yes' && $_REQUEST['clid'])
	{
		$clid = $_REQUEST['clid'];
		$_REQUEST['user'];
		/* UPDATE CLAIM STATUS WHEN THE ADMIN DECLINES THE AUTHOR */
		$data = get_post_meta($clid, 'post_claim_data',true);
		$post_id = $data['post_id'];
		$request_uri = $data['request_uri'];
		$link_url = $data['link_url'];
		$claimer_id = $data['claimer_id'];
		$post_title = $data['post_title'];
		$claimer_name = $data['claimer_name'];
		$claimer_email = $data['claimer_email'];
		$claimer_contact = $data['claimer_contact'];
		$author_id = $data['author_id'];
		$claim_status = $data['claim_status'];
		$claim_msg = $data['claim_msg'];
		$post = array('post_id' => $post_id,
					  'request_uri' => $request_uri,
					  'link_url' => $link_url,
					  'claimer_id' => $claimer_id,
					  'author_id' => $author_id,
					  'post_title' => $post_title,
					  'claimer_name' => $claimer_name,
					  'claimer_email' => $claimer_email,
					  'claimer_contact' => $claimer_contact ,
					  'claim_msg' => $claim_msg,
					  'claim_status' => 'declined');
		update_post_meta($clid,'post_claim_data',$post); /* UPDATING THE WHOLE CLAIM DATA ARRAY */
		update_post_meta($post_id,'is_verified',0);
		$wpdb->update( $wpdb->posts, array('post_excerpt' => 'declined'), array('ID' => $clid));
	}

	/* PRINT THE DATA IN METABOX */	
	$data = get_post_meta(@$clid,'post_claim_data',true);
	if($data['claim_status'] == 'approved' && get_post_meta($data['post_id'],'is_verified',true) == '1')
	{
		$post_id = $data['post_id'];?>
		<h4><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/verified.png" alt="<?php echo YES_VERIFIED;?>" border="0" align="middle" style="position:relative; top:-4px; margin-right:5px;" /> <?php echo POST_VERIFIED_TEXT; ?></h4>
		<a href="<?php echo site_url().'/wp-admin/post.php?post='.$post_id.'&action=edit&verified=no&clid='.$clid;?>" title="<?php echo REMOVE_CLAIM_REQUEST; ?>"><?php echo REMOVE_CLAIM_REQUEST; ?></a>
	<?php }
	else
	{
		$id = @$_REQUEST['clid'];
		$post_claim_id = $wpdb->get_col("SELECT ID from $wpdb->posts WHERE (post_content = '".$_REQUEST['post']."' OR post_content = '".$post->ID."') AND post_status = 'publish' AND (post_excerpt = 'approved' OR post_excerpt = '') and post_type='claim'"); /* FETCH TOTAL NUMBER OF CLAIMS FOR A POST */
		$data = get_post_meta($id,'post_claim_data',true);
		$post_id = $data['post_id'];
		if(count($post_claim_id) == '')
		{
			echo "<p>" . NO_CLAIM . "<p/>";
		}
		else
		{
			/* CONDITION TO DISPLAY THE COUNT OF CLAIMS IN METABOX */
			if(count($post_claim_id) == 1) :
				echo "<p>" . count($post_claim_id). " user has claimed for this post.<p/>";
			else :
				echo "<p>" . count($post_claim_id). " users have claimed for this post.<p/>";
			endif;
			
			foreach($post_claim_id as $key => $val) :
				$data = get_post_meta($val,'post_claim_data',true);
				if($data['claim_status'] == 'pending') :
				$user_data = get_userdata($data['claimer_id']);
				$claim_user = get_post_meta($val,'post_claim_data',true);
				$name = str_word_count($claim_user['claimer_name'],1);?>
		<ul><li>
		<a href="<?php echo site_url().'/wp-admin/post.php?post='.$post->ID.'&action=edit&verified=yes&clid='.$val.'&user='.$name[0];?>" title="<?php echo VERIFY_CLAIM; ?>" class="verify_this">
		<strong><?php echo VERIFY_CLAIM; ?></strong></a>/<a href="<?php echo site_url().'/wp-admin/post.php?post='.$post->ID.'&action=edit&decline=yes&clid='.$val.'&user='.$name[0];?>" title="<?php echo DECLINE_CLAIM; ?>" class="verify_this"><strong><?php echo DECLINE_CLAIM; ?></strong></a>
		<?php $current_link = get_author_posts_url(@$user_data->ID);
		if($user_data != '' && $data['claimer_id'] != '0') {?>
		<a href="<?php echo $current_link; ?> "><?php echo $user_data->display_name; ?></a>
		<?php } else { echo $name[0]; }?>
		</li></ul><?php endif; ?>
		<?php endforeach; ?>
		<?php } ?>
<?php }
}
/* EOF - FETCH META OPTIONS */

/* NAME : ADD THE VERIFIED USER
DESCRIPTION : THIS FUNTION WILL ADD A USER WHO HAS BEEN VERIFIED FOR THE CLAIMED POST */
function add_verified_user($clid)
{
	global $wpdb,$post;
	$data = get_post_meta($clid,'post_claim_data',true);
	get_post_meta($clid,'is_verified',true);
	$user_name = $data['claimer_name'];
	$name = str_word_count($user_name,1);
	$user_email = $data['claimer_email'];
	$user_pass = wp_generate_password(12,false);
	$new_user_id = wp_create_user( $name[0], $user_pass, $user_email );
	if ( $new_user_id )
	{
		$user_info = get_userdata($new_user_id);
		$user_login = $user_info->user_login;
		$user_pass = $user_info->user_pass;
		$post_title = $claim_user->post_title;
		$post_url_link = '<a href="'.$_REQUEST['link_url1'].'">'.$post_title.'</a>';
		$email_subject = "Claim verified for -&nbsp;".$post_title;
		$fromEmail = get_option('admin_email');
		$fromEmailName = stripslashes(get_option('blogname'));	
		$client_message =  __('<p>Dear '.$user_login.',</p><br/>
			<p>The Claim for the post '.$post_title.' has been verified.</p>
			<p>You can login with the following credentials : </p>
			<p>Username: [#user_login#]</p>
			<p>Password: [#user_password#]</p>
			<p>You can login from [#site_login_url#] or</p><p> the URL is : [#site_login_url_link#].</p>
			<p>From : [#$fromEmailName#]</p>',DOMAIN);
		$filecontent_arr1 = $claim;
		$filecontent_arr2 = $filecontent_arr1;
		$client_message = $filecontent_arr2;
		$subject = $email_subject;
		$yourname_link = __($yourname,DOMAIN);
		$search_array = array('[#user_name#]','[#user_login#]','[#user_password#]','[#site_name#]','[#site_login_url#]','[#site_login_url_link#]');
		$replace_array = array($user_login,$user_login,$user_pass,$store_name,$store_login,$store_login_link);
		$client_message = str_replace($search_array,$replace_array,$client_message);
		
		/* CALL A MAIL FUNCTION */
		templ_send_email($fromEmail,$fromEmailName,$user_email,$user_login,$subject,$client_message,$extra='');
	}
	
	/* UPDATING THE CLAIM DATA */
	$user_info = get_userdata($new_user_id);
	$user_id = $user_info->ID;
	$data = get_post_meta($clid, 'post_claim_data',true);
	$post_id = $data['post_id'];
	$request_uri = $data['request_uri'];
	$link_url = $data['link_url'];
	$claimer_id = $data['claimer_id'];
	$post_title = $data['post_title'];
	$claimer_name = $data['claimer_name'];
	$claimer_email = $data['claimer_email'];
	$claimer_contact = $data['claimer_contact'];
	$author_id = $data['post_author_id'];
	$claim_status = $data['claim_status'];
	$claim_msg = $data['claim_msg'];
	$post = array('post_id' => $post_id,
				  'request_uri' => $request_uri,
				  'link_url' => $link_url,
				  'claimer_id' => $user_id,
				  'author_id' => $user_id,
				  'post_title' => $post_title,
				  'claimer_name' => $claimer_name,
				  'claimer_email' => $claimer_email,
				  'claimer_contact' => $claimer_contact ,
				  'claim_msg' => $claim_msg,
				  'claim_status' => $claim_status);
	update_post_meta($post->ID,'post_claim_data',$post); /* UPDATING THE WHOLE CLAIM DATA ARRAY */
	
	/* UPDATING THE POST TABLE */
	$wpdb->get_results("Update $wpdb->posts set post_author ='".$user_id."' where ID = '".$post_id."' and post_status  = 'publish'");
}
/* EOF - ADD VERIFIED USER */

/* NAME :ADD A WIDGET
DESCRIPTION : THIS FUNCTION WILL REGISTER THE WIDGET OF CLAIM OWNERSHIP */
function add_claim_widget()
{
	register_widget('claim_widget');
}
/* EOF - ADD A WIDGET */

/* NAME : POST CLAIM FORM VALUES
DESCRIPTION : THIS FUNCTION POSTS THE DATA OF THE CLAIM FORM, CREATES A POST AND SAVES DATA IN POSTMETA */
function insert_claim_ownership_data($post_details)
{
	global $wpdb,$General,$upload_folder_path,$post;
	if(@$_POST['claimer_name'])
	{
		/* CODE TO CHECK WP-RECAPTCHA */
		$tmpdata = get_option('templatic_settings');
		$display = $tmpdata['user_verification_page'];
		if( $tmpdata['recaptcha'] == 'recaptcha')
		{
			if(file_exists(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php') && is_plugin_active('wp-recaptcha/wp-recaptcha.php') && in_array('claim',$display))
			{
				require_once( ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php');
				$a = get_option("recaptcha_options");
				$privatekey = $a['private_key'];
				$resp = recaptcha_check_answer ($privatekey,
							getenv("REMOTE_ADDR"),
							$post_details["recaptcha_challenge_field"],
							$post_details["recaptcha_response_field"]);
									
				if ($resp->is_valid )
				{
					echo "<script>alert('Your claim for this post has been sent successfully.');</script>";
				}
				else
				{
					echo "<script>alert('Invalid captcha. Please try again.');</script>";
					return false;	
				}	 
			}
		}
		/* END OF CODE - CHECK WP-RECAPTCHA */
		
		/* POST CLAIM FORM VALUES */
		$yourname = $post_details['claimer_name'];
		$youremail = $post_details['claimer_email'];
		$c_number = $post_details['claimer_contact'];
		$message = $post_details['claim_msg'];
		$claim_post_id = $post_details['post_id'];
		$post_title = $post_details['post_title'];
		$user_id = $current_user->ID;
		$author_id = $post_details['author_id'];
		if($claim_post_id != "")
		{
			$sql = "select ID,post_title from $wpdb->posts where ID ='".$claim_post_id."'";
			$postinfo = $wpdb->get_results($sql);
			foreach($postinfo as $postinfoObj)
			{
				$post_title = $postinfoObj->post_title;
			}
		}
		
		$user_ip = $_SERVER["REMOTE_ADDR"];
		
		/* INSERTING CLAIM POST TYPE IN POST TABLE */
		$id = get_the_title($post->ID);
		$claim_post_type = array(
			 'post_title' => 'Claim for - '.$id.'',
			 'post_content' => ''.$claim_post_id.'',
			 'post_status' => 'publish',
			 'post_author' => 1,
			 'post_type' => "claim",
			);
		$post_id = wp_insert_post( $claim_post_type ); /* INSERT QUERY */

		/* INSERTING CLAIM INFORMATION IN POST META TABLE */
		add_post_meta($post_id, 'post_claim_data', $post_details);
		/* END OF CODE - INSERT VALUES */

		$q = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE ID = 1");
		$to_email = get_option('admin_email');
		$to_name = $q->user_login;
		$site_name = get_option('blogname');
		$email_subject = "Claim to -&nbsp;".$post_title;
		$claim =  __('<p>Dear admin,</p><br/>
			<p>'.$yourname .' has claimed for this post</p>
			<p>[#$message#]</p>
			<p>Link :[#$post_title#]</p>
			<p>From : [#$your_name#]</p>',DOMAIN);
		$filecontent_arr1 = $claim;
		$filecontent_arr2 = $filecontent_arr1;
		$client_message = $filecontent_arr2;
		$subject = $email_subject;
		$post_url_link = '<a href="'.$_REQUEST['link_url'].'">'.$post_title.'</a>';
		$yourname_link = __($yourname,DOMAIN);
		$search_array = array('[#$to_name#]','[#$post_title#]','[#$message#]','[#$your_name#]','[#$post_url_link#]');
		$replace_array = array($to_name,$post_url_link,$message,$yourname_link,$post_url_link);
		$client_message = str_replace($search_array,$replace_array,$client_message);
		
		/* CHECK THE PLAYTHRU */
		if(file_exists(ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php') && is_plugin_active('are-you-a-human/areyouahuman.php')  && in_array('claim',$display) && $tmpdata['recaptcha'] == 'playthru')
		{
			require_once( ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php');
			require_once(ABSPATH.'wp-content/plugins/are-you-a-human/includes/ayah.php');
			$ayah = new AYAH();

			/* The form submits to itself, so see if the user has submitted the form.
			Use the AYAH object to get the score. */
			$score = $ayah->scoreResult();
		
			if($score)
			{
				/* send mail */
				templ_send_email($youremail,$yourname,$to_email,$to_name,$subject,$client_message,$extra='');
			}
			else
			{
				echo "<script>alert('You need to play the game to send the mail successfully.');</script>";
				return false;
			}
		}
		else
		{
			/* CALL A MAIL FUNCTION */
			templ_send_email($youremail,$yourname,$to_email,$to_name,$subject,$client_message,$extra='');
		}
	}
}
?>