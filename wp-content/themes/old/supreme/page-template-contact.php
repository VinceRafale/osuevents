<?php
/*
Template Name: Template - Contact Us
*/
$tmpdata = get_option('templatic_settings');
$display = $tmpdata['user_verification_page'];	
if($_POST['contact_s'])
{
	function send_contact_email($data)
	{
		$toEmailName = get_option('blogname');
		$toEmail = get_option('admin_email');
		$subject = $data['your-subject'];
		$message = '';
		$message .= '<p>'.DEAR.$toEmailName.',</p>';
		$message .= '<p>'.NAME.' : '.$data['your-name'].',</p>';
		$message .= '<p>'.EMAIL.' : '.$data['your-email'].',</p>';
		$message .= '<p>'.MESSAGE.' : '.nl2br($data['your-message']).'</p>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		// Additional headers
		$headers .= 'To: '.$toEmailName.' <'.$toEmail.'>' . "\r\n";
		$headers .= 'From: '.$data['your-name'].' <'.$data['your-email'].'>' . "\r\n";
		
		// Mail it
		wp_mail($toEmail, $subject, $message, $headers);
			
		if(strstr($_REQUEST['request_url'],'?'))
			{
				if(strstr($_REQUEST['request_url'],'?ecptcha'))
				{
					 $contact_url = explode("?", $_REQUEST['request_url']);
					  $url = $contact_url[0]."?msg=success";
				}
				else
					$url =  $_REQUEST['request_url'].'&msg=success'	;	
			}else
			{
				$url =  $_REQUEST['request_url'].'?msg=success'	;
			}
		echo "<script type='text/javascript'>location.href='".$url."';</script>";
	}
	if( $tmpdata['recaptcha'] == 'recaptcha')
	{
		if(file_exists(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php') && is_plugin_active('wp-recaptcha/wp-recaptcha.php') && in_array('contactus',$display))
		{
			require_once( ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php');
			$a = get_option("recaptcha_options");
			$privatekey = $a['private_key'];
			$resp = recaptcha_check_answer ($privatekey,getenv("REMOTE_ADDR"),$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);						
								
			if ($resp->is_valid =="")
			{
				wp_redirect($_REQUEST['request_url'].'/?ecptcha=captch');
				exit;		
			}else{
				$data = $_POST;
				send_contact_email($data);
			}
		}
	}else if(file_exists(ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php') && is_plugin_active('are-you-a-human/areyouahuman.php')  && in_array('contactus',$display) && $tmpdata['recaptcha'] == 'playthru')
	{
		require_once( ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php');
		require_once(ABSPATH.'wp-content/plugins/are-you-a-human/includes/ayah.php');
		$ayah = new AYAH();

		/* The form submits to itself, so see if the user has submitted the form.
		Use the AYAH object to get the score. */
		$score = $ayah->scoreResult();
	
		if($score)			
		{
			$data = $_POST;
			send_contact_email($data);			
		}else
		{
			echo "<script>alert('You need to play the game to send the mail successfully.');</script>";			
		}
	}
	
	
}
?>
<?php get_header(); ?>

<?php do_atomic( 'before_content' ); // supreme_before_content ?>
<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'separator' => '&raquo;' ) ); ?>

<div id="content" class="multiple">
  <?php do_atomic( 'open_content' ); // supreme_open_content ?>
  <div class="hfeed">
    <!--  CONTENT AREA START -->
    <!-- contact -->
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <?php global $is_home; 
	echo $post->post_content;
	?><div style="padding:20px 0 20px 0;"><?php dynamic_sidebar('contact_page_widget');?></div>
	<?php 
	
	if ( get_option( 'ptthemes_breadcrumbs' ) == 'Yes') { ?><div class="breadcums"><ul class="page-nav"><li><?php yoast_breadcrumb('',''); ?></li></ul></div><?php } ?>
		
	 <?php $a = get_option('recaptcha_options'); ?>
	  <script type="text/javascript">
			 var RecaptchaOptions = {
				theme : '<?php echo $a['registration_theme']; ?>',
				lang : '<?php echo $a['recaptcha_language']; ?>'
			 };
	  </script>
	<?php
if($_REQUEST['msg'] == 'success'){
?>
    <p class="success_msg"> <?php echo CONTACT_SUCCESS_TEXT;?> </p>
    <?php
}
?>
<?php  if(isset($_REQUEST['ecptcha']) == 'captch') {
		$a = get_option("recaptcha_options");
		$blank_field = $a['no_response_error'];
		$incorrect_field = $a['incorrect_response_error'];
		echo '<div class="error_msg">'.$incorrect_field.'</div>';
		}?>
    <form action="<?php echo get_permalink($post->ID);?>" method="post" id="contact_frm" name="contact_frm" class="wpcf7-form">
      <input type="hidden" name="request_url" value="<?php echo $_SERVER['REQUEST_URI'];?>" />
      <div class="form_row">
        <label><?php _e("Name","templatic");?> <span class="indicates">*</span></label>
        <div><input type="text" name="your-name" id="your-name" value="" class="textfield" size="40" /></div>
        <span id="your_name_Info" class="error"></span> 
	  </div>
      <div class="form_row">
        <label>
          <?php _e("Email","templatic");?>
          <span class="indicates">*</span></label>
        <div><input type="text" name="your-email" id="your-email" value="" class="textfield" size="40" /></div>
        <span id="your_emailInfo"  class="error"></span> 
	  </div>
      <div class="form_row">
        <label>
          <?php _e("Subject","templatic");?>
          <span class="indicates">*</span></label>
        <div><input type="text" name="your-subject" id="your-subject" value="" size="40" class="textfield" /></div>
        <span id="your_subjectInfo"></span> 
	  </div>
      <div class="form_row">
        <label>
          <?php _e("Message","templatic");?>
          <span class="indicates">*</span></label>
        <div><textarea name="your-message" id="your-message" cols="40" class="textarea textarea2" rows="10"></textarea></div>
        <span id="your_messageInfo"  class="error"></span> 
	  </div>
      <?php 
			if( $tmpdata['recaptcha'] == 'recaptcha')
			{
				$a = get_option("recaptcha_options");
				if(file_exists(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php') && is_plugin_active('wp-recaptcha/wp-recaptcha.php') && in_array('contactus',$display))
				{							
					require_once(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php');
					echo '<div class="form_row">';
					echo '<label class="recaptcha_claim">'.WORD_VERIFICATION.' <span>*</span></label>';
					$publickey = $a['public_key']; // you got this from the signup page 					
					?>
					<div class="claim_recaptcha_div"><?php echo recaptcha_get_html($publickey); ?> </div>
                    
			<?php 
					echo '</div>';
				}
			}
			elseif($tmpdata['recaptcha'] == 'playthru')
			{ ?>
			<?php /* CODE TO ADD PLAYTHRU PLUGIN COMPATIBILITY */
				if(file_exists(ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php') && is_plugin_active('are-you-a-human/areyouahuman.php')  && in_array('contactus',$display))
				{
					echo '<div class="form_row">';
					require_once( ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php');
					require_once(ABSPATH.'wp-content/plugins/are-you-a-human/includes/ayah.php');
					$ayah = ayah_load_library();
					echo $ayah->getPublisherHTML();
					echo '</div>';
				}
			}
		?>
      <div class="form_row"><input type="submit" value="Send" name="contact_s" class="b_submit" /></div>
    </form>
    <script type="text/javascript">
var $c = jQuery.noConflict();
$c(document).ready(function(){

	//global vars
	var enquiryfrm = $c("#contact_frm");
	var your_name = $c("#your-name");
	var your_email = $c("#your-email");
	var your_subject = $c("#your-subject");
	var your_message = $c("#your-message");
	
	var your_name_Info = $c("#your_name_Info");
	var your_emailInfo = $c("#your_emailInfo");
	var your_subjectInfo = $c("#your_subjectInfo");
	var your_messageInfo = $c("#your_messageInfo");
	
	//On blur
	your_name.blur(validate_your_name);
	your_email.blur(validate_your_email);
	your_subject.blur(validate_your_subject);
	your_message.blur(validate_your_message);

	//On key press
	your_name.keyup(validate_your_name);
	your_email.keyup(validate_your_email);
	your_subject.keyup(validate_your_subject);
	your_message.keyup(validate_your_message);
	
	

	//On Submitting
	enquiryfrm.submit(function(){
		if(validate_your_name() & validate_your_email() & validate_your_subject() & validate_your_message())
		{
			hideform();
			return true
		}
		else
		{
			return false;
		}
	});

	//validation functions
	function validate_your_name()
	{
		
		if($c("#your-name").val() == '')
		{
			your_name.addClass("error");
			your_name_Info.text("<?php _e('Please enter your name','templatic'); ?>");
			your_name_Info.addClass("message_error");
			return false;
		}
		else
		{
			your_name.removeClass("error");
			your_name_Info.text("");
			your_name_Info.removeClass("message_error");
			return true;
		}
	}

	function validate_your_email()
	{
		var isvalidemailflag = 0;
		if($c("#your-email").val() == '')
		{
			isvalidemailflag = 1;
		}else
		if($c("#your-email").val() != '')
		{
			var a = $c("#your-email").val();
			var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
			//if it's valid email
			if(filter.test(a)){
				isvalidemailflag = 0;
			}else{
				isvalidemailflag = 1;	
			}
		}
		
		if(isvalidemailflag)
		{
			your_email.addClass("error");
			your_emailInfo.text("<?php _e('Please enter valid email address','templatic'); ?>");
			your_emailInfo.addClass("message_error");
			return false;
		}else
		{
			your_email.removeClass("error");
			your_emailInfo.text("");
			your_emailInfo.removeClass("message_error");
			return true;
		}
	}

	

	function validate_your_subject()
	{
		if($c("#your-subject").val() == '')
		{
			your_subject.addClass("error");
			your_subjectInfo.text("<?php _e('Please enter a subject','templatic'); ?>");
			your_subjectInfo.addClass("message_error");
			return false;
		}
		else{
			your_subject.removeClass("error");
			your_subjectInfo.text("");
			your_subjectInfo.removeClass("message_error");
			return true;
		}
	}

	function validate_your_message()
	{
		if($c("#your-message").val() == '')
		{
			your_message.addClass("error");
			your_messageInfo.text(" <?php _e("Please enter message","templatic"); ?> ");
			your_messageInfo.addClass("message_error");
			return false;
		}
		else{
			your_message.removeClass("error");
			your_messageInfo.text("");
			your_messageInfo.removeClass("message_error");
			return true;
		}
	}

});
</script>
  </div>
  <?php do_atomic( 'close_content' ); // supreme_close_content ?>
  <!--  CONTENT AREA END -->
</div>
<?php do_atomic( 'after_content' ); // supreme_after_content ?>
<?php get_footer();?>
