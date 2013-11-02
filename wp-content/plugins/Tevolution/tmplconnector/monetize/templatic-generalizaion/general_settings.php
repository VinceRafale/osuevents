<?php
if($_POST){
	templatic_load_settings_page();
}
/*
Name : templatic_load_settings_page
Description : redirect user on right tab after save
*/
function templatic_load_settings_page() {
	if ( $_POST["settings-submit"] == 'Y' ) 
	{
		templatic_save_settings();
		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		$sub_url_parameters = isset($_GET['sub_tab'])? '&sub_tab='.$_GET['sub_tab'] : '';
		echo "<script>location.href='".admin_url('admin.php?page=templatic_settings&'.$url_parameters.$sub_url_parameters.'')."'</script>";
	
	}
}
/*
Name : templatic_save_settings
Description : Save all general settings
*/
function templatic_save_settings() {
   global $pagenow;
   $settings = get_option( "templatic_settings" ); 
   
   if ( $pagenow == 'admin.php' && $_GET['page'] == 'templatic_settings' )
   {		
		/* POST BLOCKED IP ADDRESSES */
		if(isset($_POST['block_ip']) && $_POST['block_ip']!="")
		{
			/* CALL A FUNCTION TO SAVE IP DATA */			
			insert_ip_address_data($_POST['block_ip']);
		}			
		if(isset($_REQUEST['sub_tab']) && $_REQUEST['sub_tab']=="widgets")
			$_POST['templatic_widgets']=isset($_POST['templatic_widgets'])?$_POST['templatic_widgets']:array();
			
		if(isset($_REQUEST['sub_tab']) && $_REQUEST['sub_tab']=="captcha")
			$_POST['user_verification_page']=isset($_POST['user_verification_page'])?$_POST['user_verification_page']:array();		
		if(isset($_REQUEST['sub_tab']) && $_REQUEST['sub_tab']=="email")
		{
			$_POST['send_to_frnd']=isset($_POST['send_to_frnd'])?$_POST['send_to_frnd']:'';		
			$_POST['send_inquiry']=isset($_POST['send_inquiry'])?$_POST['send_inquiry']:'';		
		}
		
		foreach($_POST as $key=>$val)
		{
			$settings[$key] = isset($_POST[$key])?$_POST[$key]:'';			
			update_option('templatic_settings', $settings);
		}				
   }
}
?>

<?php

// general setting tab filter
add_filter('templatic_general_settings_tab', 'general_setting',10); 
function general_setting($tabs ) {
	
	$tabs['general']='General settings';			
	return $tabs;
}
/*
 * create action for captcha-setting-data
 */
add_action('templatic_general_setting_data','captcha_setting_data');
function captcha_setting_data($column)
{	
	$tmpdata = get_option('templatic_settings');
	switch($column)
	{
		case 'captcha':						
				$user_verification_page =  @$tmpdata['user_verification_page'];?>
				<p class="description"><?php _e('The settings listed here are common for the whole plugin. You just need to select the forms where you want to enable captcha.',DOMAIN); ?></p>
				<tr>
				<th><label><?php _e('Enable',DOMAIN);?></label></th>
	
				<td>
				   <div class="input_wrap"> <input type="radio" id="recaptcha" name="recaptcha" value="recaptcha" <?php if(isset($tmpdata['recaptcha']) && $tmpdata['recaptcha'] == 'recaptcha'){?>checked="checked"<?php }?> /><label for="recaptcha">&nbsp;<?php _e('WP-reCaptcha',DOMAIN);?></label></div>
			 
				<div class="input_wrap"> <input type="radio" id="playthru" name="recaptcha" <?php if(isset($tmpdata['recaptcha']) &&$tmpdata['recaptcha'] == 'playthru'){?> checked="checked"<?php }?> value="playthru" /><label for="playthru">&nbsp;<?php _e('Playthru',DOMAIN);?></label></div>
					<div class="clearfix"></div>
					<p class="description"><?php _e('You can use any of these captcha options in your site. You can select one for you from here. You can get the plugins here : <br/> <a href="http://wordpress.org/extend/plugins/are-you-a-human/">Are You a Human</a> <br/> <a href="http://wordpress.org/extend/plugins/wp-recaptcha/">WP-reCaptcha</a>',DOMAIN); ?></p>
				</td>
			 </tr>
			 <tr>
				<th><label><?php _e('Enable User verification on',DOMAIN);?></label></th>
				<td class="captcha_chk">
	
				  <label><input type='checkbox' name="user_verification_page[]" id="user_verification_page" <?php if(count($user_verification_page) > 0 && in_array('registration', $user_verification_page)){ echo "checked=checked"; } ?> value="registration"/> <?php _e('Registration page',DOMAIN); ?></label><div class="clearfix"></div>
				  <label><input type='checkbox' name="user_verification_page[]" id="user_verification_page" <?php if(count($user_verification_page) > 0 && in_array('submit', $user_verification_page)){ echo "checked=checked"; } ?> value="submit"/> <?php _e('Submit listing page',DOMAIN); ?></label><div class="clearfix"></div>				  
				  <label><input type='checkbox' name="user_verification_page[]" id="user_verification_page" <?php if(count($user_verification_page) > 0 && in_array('claim', $user_verification_page)){ echo "checked=checked"; } ?> value="claim"/> <?php _e('Claim Ownership',DOMAIN); ?></label><div class="clearfix"></div>
				   <label><input type='checkbox' name="user_verification_page[]" id="user_verification_page" <?php if(count($user_verification_page) > 0 && in_array('emaitofrd', $user_verification_page)){ echo "checked=checked"; } ?> value="emaitofrd"/> <?php _e('Email to Friend',DOMAIN); ?></label><div class="clearfix"></div><div class="clearfix"></div>
                   <!--<label><input type='checkbox' name="user_verification_page[]" id="user_verification_page" <?php if(count($user_verification_page) > 0 && in_array('sendinquiry', $user_verification_page)){ echo "checked=checked"; } ?> value="sendinquiry"/> <?php //_e('Send Inquiry',DOMAIN); ?></label><div class="clearfix"></div><div class="clearfix"></div>-->
					<p class="description"><?php _e('Just check mark the forms where you want to use the captcha.',DOMAIN); ?></p>
				</td>
			 </tr>
			<?php					
			break;
	}
}
/*
 * Create email setting data action
 */
add_action('templatic_general_setting_data','email_setting_data',10);
function email_setting_data($column)
{
	$tmpdata = get_option('templatic_settings');		
	switch($column)
	{
		case 'email':	
			?>
			 <p class="description"><?php _e('Email settings are common for the whole plugin. Whatever you set here will be common for all the mails sent from your domain.',DOMAIN); ?></p>
            	<tr>					
                    <td>
                       <table style="width:60%"  class="form-table">						
							<tr>
                                <th><label><?php _e('Email',DOMAIN);?></label></th>
                                <td>
                                    <div class="input_wrap"> <input type="radio" id="php_mail" name="php_mail" value="php_mail" <?php if(isset($tmpdata['php_mail']) && $tmpdata['php_mail'] == 'php_mail'){?>checked="checked"<?php }?> /><label for="php_mail">&nbsp;<?php _e('PHP Mail',DOMAIN);?></label></div>
                                 
                                    <div class="input_wrap"> <input type="radio" id="wp_smtp" name="php_mail" <?php if(isset($tmpdata['php_mail']) && $tmpdata['php_mail'] == 'wp_smtp'){?> checked="checked"<?php }?> value="wp_smtp" /><label for="wp_smtp">&nbsp;<?php _e('WP SMTP Mail',DOMAIN);?>
                                    </label></div>
                                   <p class="description"><?php _e('This setting allows you to select the mail function you want to use to send emails from your site. You can either select PHP mail or SMTP mail. By default it will send mails by using PHP Mail.',DOMAIN); ?></p>
                                </td>
							</tr>
                        </table>
                    </td>                  
                </tr>
                <tr>
                	<td>
                    	 <table style="width:60%"  class="form-table">	
                         	<tr>
                            	<th><label><?php _e('Enable',DOMAIN);?></label></th>
                                <td>
								<div class="input_wrap"> <input type="checkbox" id="send_to_frnd" name="send_to_frnd" value="send_to_frnd" <?php if(isset($tmpdata['send_to_frnd']) && $tmpdata['send_to_frnd'] == 'send_to_frnd'){?>checked="checked"<?php }?> /><label for="send_to_frnd">&nbsp;<?php _e('Send to Friend',DOMAIN);?></label></div>
							 
								<div class="input_wrap"> <input type="checkbox" id="send_inquiry" name="send_inquiry" <?php if(isset($tmpdata['send_inquiry']) && $tmpdata['send_inquiry'] == 'send_inquiry'){?> checked="checked"<?php }?> value="send_inquiry" /><label for="send_inquiry">&nbsp;<?php _e('Send Inquiry',DOMAIN);?>
								</label>
                                </div>
								<p class="description"><?php _e('This setting allows you to enable Send to Friend and Send Inquiry emails on your site. The link to show this emails will be seen on post detail page after you enable it from here.',DOMAIN); ?></p>
							</td>
                            </tr>
                         </table>
                    </td>
                </tr>
                <tr>
                	<td>
                    	<h3><?php _e('Send email to friend/Send Inquiry Email Content Settings',DOMAIN);?></h3>
                        <table style="width:60%"  class="widefat post">
                        	<thead>
                                <tr>
                                    <th>
                                    	<label for="email_type" class="form-textfield-label"><?php _e('Email Type',DOMAIN); ?></label>
                                    </th>
                                    <th>
                                    	<label for="email_sub" class="form-textfield-label"><?php _e('Email Subject',DOMAIN); ?></label>
                                    </th>
                                    <th>
                                    	<label for="email_desc" class="form-textfield-label"><?php _e('Email Description',DOMAIN); ?></label>
                                    </th>
                                </tr>
							</thead>
                            <tbody>
                            	<tr>
								<td>
								<label class="form-textfield-label"><?php _e('Send email to friend',DOMAIN); ?></label>
								</td>
								<td>
								<textarea name="mail_friend_sub" style="width:350px; height:130px;"><?php if(isset($tmpdata['mail_friend_sub'])){echo $tmpdata['mail_friend_sub'];}else{echo 'Send to friend';} ?></textarea>
								</td>
								<td>
								<textarea name="mail_friend_description" style="width:350px; height:130px;"><?php if(isset($tmpdata['mail_friend_description'])){echo $tmpdata['mail_friend_description'];}else{echo '<p>Dear [#$to_name#],</p>
<p>[#$frnd_comments#]</p>
<p>Link : <b>[#$post_title#]</b> </p>
<p>From, [#$your_name#]</p>
<p>Sent from -[#$post_url_link#]</p>';}?></textarea>
								</td>
							</tr>
                            <tr>
								<td>
								<label class="form-textfield-label"><?php _e('Send inquiry email',DOMAIN); ?></label>
								</td>
								<td>
								<textarea name="send_inquirey_email_sub" style="width:350px; height:130px;"><?php if(isset($tmpdata['send_inquirey_email_sub'])){echo $tmpdata['send_inquirey_email_sub'];}else{echo 'Inquiry email';}?></textarea>
								</td>
								<td>
								<textarea name="send_inquirey_email_description" style="width:350px; height:130px;"><?php if(isset($tmpdata['send_inquirey_email_description'])){echo $tmpdata['send_inquirey_email_description'];}else{echo '<p>Dear [#to_name#],</p><p>Here is an inquiry for <b>[#post_title#]</b>. </p><p>Below is the message. </p><p><b>Subject : [#frnd_subject#]</b>.</p><p>[#frnd_comments#]</p><p>Thank you,<br /> [#your_name#]</p>';}?></textarea>
								</td>
							</tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            <?php
		break;
	}
}



/*
 * Apply filter for get the general setting tabs
 * if you want to create new main tab in general setting menu then use 'templatic_general_settings_tab' filter hook and pass the tabs arrya in filter hook function and return tabs array.
 */
@$tabs = apply_filters('templatic_general_settings_tab',$tabs);	

echo '<div id="icon-options-general" class="icon32"><br></div>';
echo '<h2 class="nav-tab-wrapper">';
$i=0;
foreach( $tabs as $tab => $name ){
	if($i==0)	
		$tab_key=$tab;	
		
	$current_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:$tab_key;			
	$class = ( $tab == $current_tab) ? ' nav-tab-active' : '';				
	echo "<a class='nav-tab$class' href='?page=templatic_settings&tab=$tab'>$name</a>";	
	$i++;
}
echo '</h2>';
/* Finish the general setting menu main tabs */

/*
 * create the general setting sub tabs
 */
if($current_tab=='general'):
	$i=0;	
	/*Add Filter for create the general setting sub tab for Captcha setting */	 
	add_filter('templatic_general_settings_subtabs', 'captcha_setting',12); 
	function captcha_setting($sub_tabs ) {
		
		$sub_tabs['captcha']='Captcha Settings';		
		$sub_tabs['email']='Email Settings';				
		return $sub_tabs;
	}
	/*Apply filter for create the general setting subtabs */
	/*
	 * if you want to create new subtabs in general setting menu then use 'templatic_general_settings_subtabs' filter hook function and pass the subtabs array in filter hook function and return subtabs array.
	 */	 
	@$sub_tabs = apply_filters('templatic_general_settings_subtabs',$sub_tabs);	
	echo '<h3 class="nav-tab-wrapper">';
	foreach($sub_tabs as $key=>$value)
	{	
		if($i==0)
			$sab_key=$key;				
		$current=isset($_REQUEST['sub_tab'])?$_REQUEST['sub_tab']:$sab_key;
		$class = (isset($current) && ($key == $current)) ? ' nav-tab-active' : '';				
		echo "<a id='$key' class='nav-tab$class' href='?page=templatic_settings&tab=general&sub_tab=$key'>$value</a>";	
		$i++;
	}
	echo '</h3>';
endif;
?>
<!-- Display the message-->
<?php if(isset($_REQUEST['updated']) && $_REQUEST['updated'] == 'true' ): ?>
	<div class="act_success updated" id="message">
		<p><?php echo "<strong>Record updated successfully</strong>"; ?> .</p>
	</div>
<?php endif; ?>
<!--Finish the display message-->

<div class="templatic_settings">
    <form method="post" class="form_style" action="<?php admin_url( 'themes.php?page=templatic_settings' ); ?>">
    	<table class="form-table">
    <?php
		$j=0;
		$i=0;
    	foreach( $tabs as $tab => $name ){
			if($j==0)				
				$tab_key=$tab;					
			
			if($current_tab=='general'): /* Display the general setting subtabs menu */
				//display general s etting tab wise displaydata
				foreach($sub_tabs as $key=>$value)
				{	
					if($i==0)
						$sab_key=$key;				
					$current=isset($_REQUEST['sub_tab'])?$_REQUEST['sub_tab']:$sab_key;					
					if($current==$key)													
						do_action('templatic_general_setting_data',$key);/*add action hook 'templatic_general_setting_data' for show the subtab data. pass the general setting subtabs key.  */
						
					$i++;
				}
			endif;
			
			if(isset($_REQUEST['tab']) && $_REQUEST['tab']==$tab):				
				do_action('templatic_general_data',$tab);/* add action hook 'templatic_general_data' for show the general setting tabs data. pass the general setting tabs key. */		
			endif;
			$tab_key="";
			$current_tab='';
			$j++;
		}	
    ?>
    	</table>
    <p class="submit" style="clear: both;">
      <input type="submit" name="Submit"  class="button-primary" value="Save All Settings" />
      <input type="hidden" name="settings-submit" value="Y" />
    </p>
    </form>
</div>