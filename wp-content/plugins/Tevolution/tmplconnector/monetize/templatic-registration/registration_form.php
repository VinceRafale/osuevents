<?php 
function my_init() {
	if (!is_admin()) {
		
	}
}
add_action('init', 'my_init');

if ( get_option('users_can_register') ) { ?>
<div id="sign_up">
  <div class="login_content"> <?php echo stripslashes(get_option('ptthemes_reg_page_content'));?> </div>
  <div class="registration_form_box">
    <h2>
      <?php 
			 if(@$_REQUEST['page']=='login' && @$_REQUEST['page1']=='sign_up')
			{
				_e(REGISTRATION_NOW_TEXT,DOMAIN);
			}else
			{
				 _e(REGISTRATION_NOW_TEXT,DOMAIN);
			}
			 ?>
    </h2>
    <?php
if ( @$_REQUEST['emsg']==1)
{
	echo "<p class=\"error_msg\"> ".EMAIL_USERNAME_EXIST_MSG." </p>";
}elseif(@$_REQUEST['emsg']=='regnewusr')
{
	echo "<p class=\"error_msg\"> ".REGISTRATION_DESABLED_MSG." </p>";
}elseif(@$_REQUEST['reg'] == 1)
{
	echo "<p class=\"success_msg\"> ".REGISTRATION_SUCCESS_MSG."</p>";
}
elseif(@$_REQUEST['ecptcha'] == 'captcha')
{
	echo "<p class=\"error_msg\"> ".INVALIDCAPTCHA."</p>";
}
elseif(@$_REQUEST['ecptcha'] == 'play')
{
	echo "<p class=\"error_msg\"> ".INVALIDPLAY."</p>";
}
global $submit_form_validation_id;
$submit_form_validation_id = "userform";
?>
 
    <form name="userform" id="userform" action="<?php echo tmpl_get_ssl_normal_url(home_url().'/?ptype=login&amp;action=register'); ?>" method="post" enctype="multipart/form-data" >  
      <input type="hidden" name="reg_redirect_link" value="<?php if(isset($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER'];?>" />
	  <input type="hidden" name="user_email_already_exist" id="user_email_already_exist" value="" />
	   <input type="hidden" name="user_fname_already_exist" id="user_fname_already_exist" value="" />
	  
      <?php do_action('templ_registration_form_start');?>
		<?php
		
			//fetch the user custom fields for registration page.
			fetch_user_registration_fields('register');
			
 		$pcd = explode(',',get_option('ptthemes_captcha_dislay'));	
				
		if(in_array('User registration page',$pcd) || in_array('Both',$pcd) ){
			$a = get_option("recaptcha_options");
			if( file_exists(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php') && is_plugin_active('wp-recaptcha/wp-recaptcha.php') && $a['show_in_registration'] == '1' ){
				echo '<label>'.WORD_VERIFICATION.'</label>';
				$publickey = $a['public_key']; // you got this from the signup page
				echo recaptcha_get_html($publickey); 
			}
		}
		do_action('templ_registration_form_end');?>
		<?php $tmpdata = get_option('templatic_settings');
		$display = @$tmpdata['user_verification_page'];
		if(isset($tmpdata['recaptcha']) && $tmpdata['recaptcha'] == 'recaptcha')
		{
			$a = get_option("recaptcha_options");
			if(file_exists(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php') && is_plugin_active('wp-recaptcha/wp-recaptcha.php') && in_array('registration',$display))
			{
				require_once(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php');
				echo '<label class="recaptcha_claim">'.WORD_VERIFICATION.' : </label>  <span>*</span>';
				$publickey = $a['public_key']; // you got this from the signup page ?>
				<div class="claim_recaptcha_div"><?php echo recaptcha_get_html($publickey); ?> </div>
		<?php }
		}
		elseif(isset($tmpdata['recaptcha']) && $tmpdata['recaptcha'] == 'playthru')
		{ ?>
		<?php /* CODE TO ADD PLAYTHRU PLUGIN COMPATIBILITY */
			if(file_exists(ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php') && is_plugin_active('are-you-a-human/areyouahuman.php')  && in_array('registration',$display))
			{
				require_once( ABSPATH.'wp-content/plugins/are-you-a-human/areyouahuman.php');
				require_once(ABSPATH.'wp-content/plugins/are-you-a-human/includes/ayah.php');
				$ayah = ayah_load_library();
				echo $ayah->getPublisherHTML();
			}
		}
			/* ENF OF CODE */?>
      <input type="submit" name="registernow" value="<?php _e(REGISTER_NOW_TEXT,DOMAIN);?>" class="b_registernow" id="registernow_form" />
    </form>
  </div>
</div>
<?php include_once(TT_REGISTRATION_FOLDER_PATH . 'registration_validation.php');?>
<?php } ?>