<link rel="stylesheet" type="text/css" href="<?php echo TEMPL_PLUGIN_URL;?>tmplconnector/monetize/templatic-generalizaion/css/style.css">
<script type='text/javascript' src='<?php echo TEMPL_PLUGIN_URL;?>tmplconnector/monetize/templatic-generalizaion/js/jquery.leanModal.min.js'></script>
<script type="text/javascript">
	jQuery(function() {
		jQuery('a[rel*=leanModal_send_inquiry]').leanModal({ top : 200, closeButton: ".modal_close" });		
	});
</script>      
<div id="inquiry_div" class="templ_popup_forms clearfix" style="display:none;">
<?php global $post,$wp_query; ?>
    <form name="inquiry_frm" id="inquiry_frm" action="#" method="post"> 
        <input type="hidden" id="listing_id" name="listing_id" value="<?php _e($post->ID,DOMAIN); ?>"/>
        <input type="hidden" id="request_uri" name="request_uri" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>"/>
        <input type="hidden" id="link_url" name="link_url" value="<?php	the_permalink();?>"/>
        <?php $userdata = get_userdata($post->post_author); ?>  
        <input type="hidden" name="to_name" id="to_name" value="<?php _e($userdata->display_name,DOMAIN);?>" />
        <input type="hidden" id="author_email" name="author_email" value="<?php echo $userdata->user_email; ?>"/>
        <div class="email_to_friend">
        	<h3 class="h3"><?php _e("Inquiry for ",DOMAIN); _e(stripslashes($post->post_title),DOMAIN); ?></h3>
        	<a class="modal_close" href="#"></a>
    	</div>
          <?php $tmpdata = get_option('templatic_settings');?>          
            <div class="form_row clearfix" ><label><?php _e('Full name',DOMAIN); ?> : <span>*</span></label> <input name="full_name" id="full_name" type="text"  /><span id="full_nameInfo"></span></div>
        
            <div class="form_row clearfix" ><label> <?php _e('Your email ',DOMAIN); ?> : <span>*</span></label> <input name="your_iemail" id="your_iemail" type="text"  /><span id="your_iemailInfo"></span></div>
            
            <div class="form_row clearfix" ><label> <?php _e('Contact number ',DOMAIN); ?> : </label> <input name="contact_number" id="contact_number" type="text"  /><span id="contact_numberInfo"></span></div>	
            
            <div class="form_row clearfix" ><label> <?php _e('Subject',DOMAIN); ?> : <span>*</span></label>
            <input name="inq_subject" id="inq_subject" type="text"  value="<?php if(isset($tmpdata['send_inquirey_email_sub'])){ _e($tmpdata['send_inquirey_email_sub'],DOMAIN);}else{ _e('Inquiry email',DOMAIN);}?>" />
            <input name="to_email" id="to_email" type="hidden" value="<?php echo get_post_meta($post->ID,'email',true); ?>"  />
            <span id="inq_subInfo"></span></div>
            <div class="form_row  clearfix" ><label> <?php _e(' Message',DOMAIN); ?> : <span>*</span></label> <textarea rows="5" name="inq_msg" id="inq_msg"><?php _e('Hello,
I would like to inquire more about this listing. Please let me know how can I get in touch with you. Waiting for your prompt reply?',DOMAIN); ?></textarea><span id="inq_msgInfo"></span></div>
            <div id="inquiry_frm_popup"></div>
            <?php   $tmpdata = get_option('templatic_settings');
                    $display = $tmpdata['user_verification_page'];					
                    if( $tmpdata['recaptcha'] == 'recaptcha')
                    {
                        $a = get_option("recaptcha_options");
                        if(file_exists(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php') && is_plugin_active('wp-recaptcha/wp-recaptcha.php') && in_array('sendinquiry',$display))
                        {							
                            require_once(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php');
                            echo '<label class="recaptcha_claim">'.WORD_VERIFICATION.' : </label>  <span>*</span>';
                            $publickey = $a['public_key']; // you got this from the signup page 							
							?>
                            <div class="claim_recaptcha_div"><?php echo recaptcha_get_html($publickey); ?> </div>
                    <?php }
                    }
                    elseif($tmpdata['recaptcha'] == 'playthru')
                    { ?>
                    <?php /* CODE TO ADD PLAYTHRU PLUGIN COMPATIBILITY */
                        if(file_exists(ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php') && is_plugin_active('are-you-a-human/areyouahuman.php')  && in_array('sendinquiry',$display))
                        {
                            require_once( ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php');
                            require_once(ABSPATH.'wp-content/plugins/are-you-a-human/includes/ayah.php');
                            $ayah = ayah_load_library();
                            echo $ayah->getPublisherHTML();
                        }
                    }
                    ?>
            <div class="row  clearfix" ><input name="Send" type="submit" value="<?php _e('Send',DOMAIN); ?>" class="button send_button" /></div>
    </form>
</div>

<!-- here -->
<?php
global $post,$wpdb;
if($_POST['your_iemail'] != "")
{	
	/* CODE TO CHECK WP-RECAPTCHA */
	$tmpdata = get_option('templatic_settings');
	$display = $tmpdata['user_verification_page'];
	if( $tmpdata['recaptcha'] == 'recaptcha')
	{
		if(file_exists(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php') && is_plugin_active('wp-recaptcha/wp-recaptcha.php') && in_array('sendinquiry',$display))
		{
			require_once( ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php');
			$a = get_option("recaptcha_options");
			$privatekey = $a['private_key'];
			$resp = recaptcha_check_answer ($privatekey,getenv("REMOTE_ADDR"),$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);						
								
			if ($resp->is_valid =="")
			{
				echo "<script>alert('Invalid captcha. Please try again.');</script>";
				return false;
			}
		}
	}
	/* END OF CODE - CHECK WP-RECAPTCHA */	
	$yourname = $_POST['full_name'];
	$youremail = $_POST['your_iemail'];
	$contact_num = $_POST['contact_number'];
	$frnd_subject = $_POST['inq_subject'];
	$frnd_comments = $_POST['inq_msg'];
	$post_id = $_POST['listing_id'];	
	$post->post_author;
	$to_email = (get_post_meta($post->ID,'email',true)!="")? get_post_meta($post->ID,'email',true): get_the_author_meta( 'user_email', $post->post_author )  ;
	$to_name =  get_the_author();
	if($post_id != "")
	{
		$productinfosql = "select ID,post_title from $wpdb->posts where ID ='".$post_id."'";
		$productinfo = $wpdb->get_results($productinfosql);
		foreach($productinfo as $productinfoObj)
		{
			$post_title = stripslashes($productinfoObj->post_title); 
		}
	}
	///////Inquiry EMAIL START//////
	global $General;
	global $upload_folder_path;
	$store_name = get_option('blogname');
	$tmpdata = get_option('templatic_settings');	;
	$email_subject =$tmpdata['send_inquirey_email_sub'];
	$email_content =$tmpdata['send_inquirey_email_description'];	
	
	
	if($email_content == "" && $email_subject=="")
	{
		$message1 =  __('[SUBJECT-STR]You might be interested in [SUBJECT-END]
		<p>Dear [#to_name#],</p>
		<p>[#frnd_comments#]</p>
		<p>Link : <b>[#post_title#]</b> </p>
		<p>Contact number : [#contact#]</p>
		<p>From, [#your_name#]</p>
		<p>Sent from -[#$post_url_link#]</p></p>',DOMAIN);
		$filecontent_arr1 = explode('[SUBJECT-STR]',$message1);
		$filecontent_arr2 = explode('[SUBJECT-END]',$filecontent_arr1[1]);
		$subject = $filecontent_arr2[0];
		if($subject == '')
		{
			$subject = $frnd_subject;
		}
		$client_message = $filecontent_arr2[1];
	} else {
		$client_message = $email_content;
	}
	$subject = $frnd_subject;

	$post_url_link = '<a href="'.$_REQUEST['link_url'].'">'.$post_title.'</a>';
	/////////////customer email//////////////
	$yourname_link = __('<b><a href="'.get_option('siteurl').'">'.get_option('blogname').'</a></b>.',DOMAIN);
	$search_array = array('[#to_name#]','[#frnd_subject#]','[#post_title#]','[#frnd_comments#]','[#your_name#]','[#$post_url_link#]','[#contact#]');
	$replace_array = array($to_name,$frnd_subject,$post_url_link,$frnd_comments,$yourname,$yourname_link,$contact_num);
	$client_message = str_replace($search_array,$replace_array,$client_message,$contact_num); 
	
	templ_send_email($youremail,$yourname,$to_email,$to_name,$subject,$client_message,$extra='');///To clidne email
	//////Inquiry EMAIL END////////		
	if(get_option('siteurl').'/' == $_REQUEST['request_uri']){
			echo "<script>alert('".__('Email sent successfully',DOMAIN)."');</script>";
	} else {
		echo "<script>alert('".__('Email sent successfully',DOMAIN)."');</script>";
	}
	
}?>
<script>
var $q = jQuery.noConflict();
$q(document).ready(function(){

//global vars
	var enquiry1frm = $q("#inquiry_frm");
	var full_name = $q("#full_name");
	var full_nameInfo = $q("#full_nameInfo");
	var your_iemail = $q("#your_iemail");
	var your_iemailInfo = $q("#your_iemailInfo");
	var sub = $q("#inq_subject");
	var subinfo = $q("#inq_subInfo");
	var frnd_comments = $q("#inq_msg");
	var frnd_commentsInfo = $q("#inq_msgInfo");
	//On blur
	full_name.blur(validate_full_name1);
	your_iemail.blur(validate_your_iemail);
	sub.blur(validate_subject);
	frnd_comments.blur(validate_frnd_comments);
	frnd_comments.keyup(validate_frnd_comments);
	//On Submitting

	enquiry1frm.submit(function(){

		if(validate_full_name1() & validate_your_iemail() & validate_subject() & validate_frnd_comments())
		{ 
			return true;
		}
		else
		{ 
			return false;
		}

	});
	//validation functions
	function validate_full_name1()
	{	
		if(full_name.val() == '')
		{
			full_name.addClass("error");

			full_nameInfo.text("<?php _e('Please enter your full name',DOMAIN);?>");

			full_nameInfo.addClass("message_error2");

			return false;
		}else{
			full_name.removeClass("error");

			full_nameInfo.text("");

			full_nameInfo.removeClass("message_error2");

			return true;

		}

	}
	function validate_your_iemail()
	{ 
		var isvalidemailflag = 0;

		if(your_iemail.val() == '')
		{
			isvalidemailflag = 1;

		}else {

			if(your_iemail.val() != '')
			{

				var a = your_iemail.val();

				var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;

				//if it's valid your_iemail

				if(filter.test(a)){

					isvalidemailflag = 0;

				}else{

					isvalidemailflag = 1;	

				}

			}
		}
		if(isvalidemailflag == 1)
		{
			your_iemail.addClass("error");

			your_iemailInfo.text("<?php _e('Please enter your valid email address',DOMAIN);?>");

			your_iemailInfo.addClass("message_error2");

			return false;

		}else
		{
			your_iemail.removeClass("error");

			your_iemailInfo.text("");

			your_iemailInfo.removeClass("message_error");

			return true;

		}

		

	}
	function validate_subject()

	{ 
		if($q("#inq_subject").val() == '')
		{
			sub.addClass("error");

			subinfo.text("<?php _e('Please enter subject line',DOMAIN);?>");

			subinfo.addClass("message_error2");

			return false;

		}

		else{

			sub.removeClass("error");

			subinfo.text("");

			subinfo.removeClass("message_error2");

			return true;

		}

	}

	
	function validate_frnd_comments()
	{
		if($q("#inq_msg").val() == '')
		{
			frnd_comments.addClass("error");

			frnd_commentsInfo.text("<?php _e('Please enter message',DOMAIN);?>");

			frnd_commentsInfo.addClass("message_error2");

			return false;
		}else{

			frnd_comments.removeClass("error");

			frnd_commentsInfo.text("");

			frnd_commentsInfo.removeClass("message_error2");

			return true;

		}

	}
});
</script>