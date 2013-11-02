<?php
/*
name :is_on_ssl_url
description : check whether url is ssl enable or not.*/
function is_on_ssl_url()
{
	$tmpdata = get_option('templatic_settings');
	if(isset($tmpdata['templatic-is_allow_ssl']) && $tmpdata['templatic-is_allow_ssl'] == 'Yes')
	{
		return true;
	}
	else
	{
		return false;
	}
}
/*
name :tmpl_get_ssl_normal_url
description : replace http with https if ssl is enable.*/
function tmpl_get_ssl_normal_url($url)
{
	if(is_on_ssl_url())
	{
		$url = str_replace('http://','https://',$url);
	}
	return $url;
}

/*
 * Fincation Name: send_email_to_friend
 * Include popup_form.php file
 */
function send_email_to_friend()
{	
	include_once(TEMPL_MONETIZE_FOLDER_PATH."templatic-generalizaion/popup_frms.php");
}
/*
 * Fincation Name: send_inquiry
 * include popup_inquiry_frm.php
 */
function send_inquiry()
{
	include_once(TEMPL_MONETIZE_FOLDER_PATH."templatic-generalizaion/popup_inquiry_frm.php");	
}

/*
 * Function Name: send_friend_email_data
 * Return : send email to friend
 */
function send_friend_email_data($postdetail)
{
	global $wpdb,$General,$upload_folder_path,$post;
	if(@$postdetail['yourname'])
	{
		/* CODE TO CHECK WP-RECAPTCHA */
		$tmpdata = get_option('templatic_settings');
		$display = $tmpdata['user_verification_page'];
		if( $tmpdata['recaptcha'] == 'recaptcha')
		{
			if(file_exists(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php') && is_plugin_active('wp-recaptcha/wp-recaptcha.php') && in_array('emaitofrd',$display))
			{
				require_once( ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php');
				$a = get_option("recaptcha_options");
				$privatekey = $a['private_key'];
				$resp = recaptcha_check_answer ($privatekey,getenv("REMOTE_ADDR"),$postdetail["recaptcha_challenge_field"],$postdetail["recaptcha_response_field"]);						
					
				if ($resp->is_valid=="")
				{
					echo "<script>alert('Invalid captcha. Please try again.');</script>";
					return false;	
				}				
			}
		}
		else
		{
			if(file_exists(ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php') && is_plugin_active('are-you-a-human/areyouahuman.php')  && in_array('emaitofrd',$display) && $tmpdata['recaptcha'] == 'playthru')
			{
				require_once( ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php');
				require_once(ABSPATH.'wp-content/plugins/are-you-a-human/includes/ayah.php');
				$ayah = new AYAH();
		
				/* The form submits to itself, so see if the user has submitted the form.
				Use the AYAH object to get the score. */
				$score = $ayah->scoreResult();		
				if(!$score && $score=="")			
				{
					echo "<script>alert('You need to play the game to send the mail successfully.');</script>";	
					return false;
				}
			}
		}
		
		
		/* END OF CODE - CHECK WP-RECAPTCHA */	
		$yourname = $postdetail['yourname'];
		$youremail = $postdetail['youremail'];
		$frnd_subject = $postdetail['frnd_subject'];
		$frnd_comments = $postdetail['frnd_comments'];
		$to_friend_email = $postdetail['to_friend_email'];
		$to_name = $postdetail['to_name_friend'];
		///////Inquiry EMAIL START//////
		global $General,$wpdb;
		global $upload_folder_path;
		$post_title = stripslashes($post->post_title);
		$tmpdata = get_option('templatic_settings');	;
		$email_subject =$tmpdata['mail_friend_sub'];
		$email_content =$tmpdata['mail_friend_description'];
		
		
		if($email_content == "" && $email_subject=="")
		{
			$message1 =  __('[SUBJECT-STR]You might be interested in [SUBJECT-END]
			<p>Dear [#$to_name#],</p>
			<p>[#$frnd_comments#]</p>
			<p>Link : <b>[#$post_title#]</b> </p>
			<p>From, [#$your_name#]</p>',DOMAIN);
			$filecontent_arr1 = explode('[SUBJECT-STR]',$message1);
			$filecontent_arr2 = explode('[SUBJECT-END]',$filecontent_arr1[1]);
			$subject = $filecontent_arr2[0];
			if($subject == '')
			{
				$subject = $frnd_subject;
			}
			$client_message = $filecontent_arr2[1];
		}else
		{
			$client_message = $email_content;
		}
		$subject = $frnd_subject;
		
		$post_url_link = '<a href="'.$_REQUEST['link_url'].'">'.$post_title.'</a>';
		/////////////customer email//////////////
		//$yourname_link = __($yourname.'<br>Sent from - <b><a href="'.get_option('siteurl').'">'.get_option('blogname').'</a></b>.',DOMAIN);
		$search_array = array('[#$to_name#]','[#$post_title#]','[#$frnd_comments#]','[#$your_name#]','[#$post_url_link#]');
		$replace_array = array($to_name,$post_url_link,nl2br($frnd_comments),$yourname,$post_url_link);
		$client_message = str_replace($search_array,$replace_array,$client_message);	
		templ_send_email($youremail,$yourname,$to_friend_email,$to_name,$subject,$client_message,$extra='');///To clidne email
		
		//////Inquiry EMAIL END////////	
		echo "<script>alert('Email sent successfully');location.href='".$_REQUEST['link_url']."'</script>";
	}
		
}
 
?>