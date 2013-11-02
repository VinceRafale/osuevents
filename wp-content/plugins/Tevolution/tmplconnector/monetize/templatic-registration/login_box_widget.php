<?php
/*
name : widget_register_new_user_plugin
description : Function to register new user BOF */
function widget_register_new_user_plugin( $user_login, $user_email ) {
	$errors = new WP_Error();

	$sanitized_user_login = sanitize_user( $user_login );
	$user_email = apply_filters('user_registration_email', $user_email );

	// Check the username
	if ( $sanitized_user_login == '' ) {
		$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.' ,DOMAIN) );
	} elseif ( ! validate_username( $user_login ) ) {
		$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.' ,DOMAIN) );
		$sanitized_user_login = '';
	} elseif ( username_exists( $sanitized_user_login ) ) {
		$errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered, please choose another one.',DOMAIN ) );
	}

	
	$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

	if ($errors->get_error_code()){
		return $errors;
	}

	if(!empty($errors)){
	$user_pass = wp_generate_password();
	$user_id = wp_create_user($sanitized_user_login, $user_pass, $user_email ); 
	}
	if (!$user_id ) {
		return $errors;
	}
	
	update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.


	if($user_id>0)
	{
	$creds['user_login'] = $user_login;
	$creds['user_password'] = $user_pass;
	$user = wp_signon($creds, @$secure_cookie); 
	wp_new_user_notification( $user_id, $user_pass );
	wp_redirect(site_url());
	}
	return $user_id;
}
/**-- Function to register new user EOF --**/

/*
name : templatic_widget_retrieve_password
description : Function for retrive password BOF --**/
function templatic_widget_retrieve_password() {

	global $wpdb;
	$errors = new WP_Error();
	$login = trim($_POST['user_login']);
	if (empty( $_POST['user_login'] ) )
		$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.',DOMAIN));

	if ( strpos($_POST['user_login'], '@') ) {
		$user_data = get_user_by('email',$login);
		if ( empty($user_data) )
			$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.',DOMAIN));
	} else {
		$user_data = get_user_by('email',$login);
	}

	do_action('lostpassword_post');

	if ( $errors->get_error_code() )
		return $errors;

	if ( !$user_data ) {
		$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.',DOMAIN));
		return $errors;
	}

	 /* redefining user_login ensures we return the right case in the email */
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;

	do_action('retreive_password', $user_login);  // Misspelled and deprecated
	do_action('retrieve_password', $user_login);

	$user_email = $_POST['widget_user_remail'];
	$user_login = $_POST['user_login'];
	
	$user = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE user_login like \"$user_login\" or user_email like \"$user_login\"");
	if ( empty( $user ) )
		return new WP_Error('invalid_key', __('Invalid key',DOMAIN));
		
	$new_pass = wp_generate_password(12,false);

	do_action('password_reset', $user, $new_pass);

	wp_set_password($new_pass, $user->ID);
	update_usermeta($user->ID, 'default_password_nag', true); //Set up the Password change nag.
	$message  = '<p><b>'.__('Your login Information :',DOMAIN).'</b></p>';
	$message  .= '<p>'.sprintf(__('Username: ',DOMAIN).'%s', $user->user_login) . "</p>";
	$message .= '<p>'.sprintf(__('Password: ',DOMAIN).'%s', $new_pass) . "</p>";
	$message .= '<p>You can login to : <a href="'.home_url().'/' . "\">Login</a> or the URL is :  ".home_url()."/?ptype=login</p>";
	$message .= '<p>Thank You,<br> '.get_option('blogname').'</p>';
	$user_email = $user_data->user_email;
	$user_name = $user_data->user_nicename;
	$fromEmail = get_site_emailId_plugin();
	$fromEmailName = get_site_emailName_plugin();
	$title = sprintf('[%s]'.__(' Your new password',DOMAIN), get_option('blogname'));
	$title = apply_filters('password_reset_title', $title);
	$message = apply_filters('password_reset_message', $message, $new_pass);
	templ_send_email($fromEmail,$fromEmailName,$user_email,$user_name,$title,$message,$extra='');///forgot password email
	return true;
}
/**-- Function for retrive password EOF --**/

/*  Go inside when user login */
if(isset($_REQUEST['widgetptype']) == 'login')
{	
	include_once( ABSPATH.'wp-load.php' );
	include_once(ABSPATH.'wp-includes/registration.php');

	$secure_cookie = '';
	if ( !empty($_POST['log']) && !force_ssl_admin() ) {
		$user_name = sanitize_user($_POST['log']);
		if ( $user = get_userdata($user_name) ) {
			if ( get_user_option('use_ssl', $user->ID) ) {
				$secure_cookie = true;
				force_ssl_admin(true);
			}
		}
	} 

	if(@$_REQUEST['redirect_to']=='' && @$user)
	{
		$_REQUEST['redirect_to'] = get_author_posts_url($user->ID);
	}

	if ( isset( $_REQUEST['redirect_to'] ) ) {
		$redirect_to = $_REQUEST['redirect_to'];
		/*  Redirect to https if user wants ssl */
		if ( $secure_cookie && false !== strpos($redirect_to, 'wp-admin') )
			$redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
	} else {
		$redirect_to = admin_url();
	}

	if ( !$secure_cookie && is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
		$secure_cookie = false;

	$user = wp_signon('', $secure_cookie);

	$redirect_to = apply_filters('login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '', $user);

	
	if (!is_wp_error($user) ) {
		// If the user can't edit posts, send them to their profile.
		if ( !current_user_can('edit_posts') && ( empty( $redirect_to ) || $redirect_to == 'wp-admin/' || $redirect_to == admin_url() ) )
			$redirect_to = admin_url('profile.php');
		wp_safe_redirect($redirect_to);
		exit();
	}

	$errors = $user;
	
	/*  If cookies are disabled we can't log in even with a valid user+pass */
	if ( isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]) )
		$errors->add('test_cookie', __("<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a> to use WordPress.",DOMAIN));

	
			if ( !is_wp_error($user) ) 
			{
				wp_safe_redirect($redirect_to);
				exit();
			}
}

/* Go inside when user register */
if(isset($_REQUEST['widgetptype']) && $_REQUEST['widgetptype'] == 'register')
{
	if ( !get_option('users_can_register') ) {
		$reg_msg = __('User registration is currently not allowed.',DOMAIN);
	}else{
	$user_login = '';
	$user_email = '';
		require_once( ABSPATH . WPINC . '/registration.php');
		$pcd = explode(',',get_option('ptthemes_captcha_dislay'));	
		if((in_array('Add a new place/event page',$pcd) || in_array('Both',$pcd)) && file_exists(ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php') && plugin_is_active('wp-recaptcha') ){
		require_once( ABSPATH.'wp-content/plugins/wp-recaptcha/recaptchalib.php');
		$a = get_option("recaptcha_options");
		$privatekey = $a['private_key'];
  						$resp = recaptcha_check_answer ($privatekey,
                                getenv("REMOTE_ADDR"),
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
								
		if (!$resp->is_valid ) {
			echo "<script>alert('Invalid recaptcha');</script>";
			exit;
		} 
		}

		$user_login = $_POST['widget_user_rlogin'];
		$user_email = $_POST['widget_user_remail'];
		$errors = widget_register_new_user_plugin($user_login, $user_email);
		if ( !is_wp_error($errors) ) {
			$secure_cookie = true;
			$reg_msg = __('Registration complete. Please check your e-mail.',DOMAIN);
		}
		if($errors){
			$errors = $errors;
		}
	}
	$user = wp_signon('', $secure_cookie);	
}


class loginwidget_plugin extends WP_Widget {
	
	function loginwidget_plugin() {
	//Constructor
		$widget_ops = array('classname' => 'Login Dashboard wizard', 'description' => apply_filters('templ_login_widget_desc_filter',__('Widget contain the login form before login , after login contain the links of Dashboard,Edit profile,Logout etc. ',DOMAIN)) );		
		$this->WP_Widget('widget_login', apply_filters('templ_login_widget_title_filter',__('T &rarr; Login Dashboard wizard',DOMAIN)), $widget_ops);
	}
	function widget($args, $instance) {
	// prints the widget
		extract($args, EXTR_SKIP);
		$title = empty($instance['title']) ? 'Dashboard' : apply_filters('widget_title', $instance['title']);
		$desc1 = empty($instance['desc1']) ? '&nbsp;' : apply_filters('widget_desc1', $instance['desc1']);
		if(isset($_REQUEST['widgetptype']) && $_REQUEST['widgetptype'] == 'forgetpass')
		{
		$errors = templatic_widget_retrieve_password();
		if ( !is_wp_error($errors) ) {
			$for_msg = __('Check your e-mail for the new password.',DOMAIN);
			}
		} ?>						
		<script  type="text/javascript" >
        function showhide_forgetpw()
        {
			if(document.getElementById('lostpassword_form').style.display=='none')
			{
				document.getElementById('lostpassword_form').style.display = ''
				document.getElementById('register_form').style.display = 'none'
			}else
			{
				document.getElementById('lostpassword_form').style.display = 'none';
				document.getElementById('register_form').style.display = 'none'
			}	
        }
		 function showhide_register()
        {
			if(document.getElementById('register_form').style.display=='none')
			{
				document.getElementById('register_form').style.display = ''
				document.getElementById('lostpassword_form').style.display = 'none'
			}else
			{
				document.getElementById('register_form').style.display = 'none';
				document.getElementById('lostpassword_form').style.display = 'none'
			}	
        }
        </script>
	<?php 
	/** if page is not login/register then only widget will be display **/
	if(@$_REQUEST['ptype'] != 'login' && @$_REQUEST['ptype'] != 'register'){ ?>
            <div class="widget login_widget" id="login_widget">
          <?php
			global $current_user;
			if($current_user->ID)
			{
			?>
			<h3  class="widget-title"><?php echo $title;?></h3>
			<ul class="xoxo blogroll">
            	<?php 
				$authorlink = get_author_posts_url($current_user->ID);									
					
					echo apply_filters('templ_login_widget_dashboardlink_filter','<li><a href="'. get_author_posts_url($current_user->ID).'">'.__(DASHBOARD_TEXT,DOMAIN).'</a></li>');
					
					
					echo apply_filters('templ_login_widget_editprofilelink_filter','<li><a href="'.site_url('/?ptype=profile').'">'.__(EDIT_PROFILE_PAGE_TITLE,DOMAIN).'</a></li>');
					echo apply_filters('templ_login_widget_editprofilelink_filter','<li><a href="'.site_url('/?ptype=profile').'">'.__(CHANGE_PW_TEXT,DOMAIN).'</a></li>');
					$user_link = get_author_posts_url($current_user->ID);
					if(strstr($user_link,'?') ){$user_link = $user_link.'&list=favourite';}else{$user_link = $user_link.'?list=favourite';}
					//echo apply_filters('templ_login_widget_editprofilelink_filter','<li><a href="'.$user_link.'">'.MY_FAVOURITE_TEXT.'</a></li>');
					echo apply_filters('templ_login_widget_logoutlink_filter','<li><a href="'.wp_logout_url(get_option('siteurl')."/").'">'.__(LOGOUT_TEXT,DOMAIN).'</a></li>');
				?>
			</ul>
			<?php
			}else
			{
			?>
			<?php if($title){?><h3><?php echo $title; ?></h3><?php }?>
            <?php 
			global $errors,$reg_msg ;
			if(@$_REQUEST['widgetptype'] == 'login')
			{
				if(is_object($errors))
				{
					foreach($errors as $errorsObj)
					{
						foreach($errorsObj as $key=>$val)
						{
							for($i=0;$i<count($val);$i++)
							{
							echo "<p class=\"error_msg\">".$val[$i].'</p>';	
							}
						} 
					}
				}
				$errors = new WP_Error();
			}
		include_once(TEMPL_MONETIZE_FOLDER_PATH.'templatic-registration/js/login.js.php');
		?>
		<form name="loginwidgetform" id="loginwidgetform" action="#login_widget" method="post" >
            <input type="hidden" name="widgetptype" value="login" />
           		<div class="form_row"><label><?php _e('Username',DOMAIN);?>  <span>*</span></label>  <input name="log" id="widget_user_login" type="text" class="textfield" /> <span id="user_login_info"></span> </div>
                <div class="form_row"><label><?php _e('Password',DOMAIN);?>  <span>*</span></label>  <input name="pwd" id="widget_user_pass" type="password" class="textfield" /><span id="your_pass_info"></span>  </div>                
               	<input type="hidden" name="redirect_to" value="<?php if(isset($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER']; ?>" />
				<input type="hidden" name="testcookie" value="1" />
                
				<div class="form_row clearfix">
                <input type="submit" name="submit" value="<?php _e('Sign In',DOMAIN);?>" class="b_signin button-primary" /> 
				</div>
				 <p class="forgot_link">
				<!--<a href="javascript:void(0);showhide_register();" class="lw_new_reg_lnk"><?php//_e('New User? Register Now',DOMAIN);?></a> --> 
				<a href="<?php echo tmpl_get_ssl_normal_url(home_url()."?ptype=login"); ?>" class="lw_new_reg_lnk"><?php _e('New User? Register Now',DOMAIN);?></a>
				<a href="javascript:void(0);showhide_forgetpw();" class="lw_fpw_lnk"><?php _e(FORGOT_PW_TEXT,DOMAIN);?></a> </p>
				<?php do_action('login_form');?>
		</form> 
		<?php }
			if(@$_REQUEST['widgetptype'] == 'login')
			{
				if($reg_msg )
			    echo "<p class=\"error_msg\">".$reg_msg.'</p>';	
				if(is_object($errors))
				{
					foreach($errors as $errorsObj)
					{
						foreach($errorsObj as $key=>$val)
						{
							for($i=0;$i<count($val);$i++)
							{
							echo "<p class=\"error_msg\">".$val[$i].'</p>';	
							}
						} 
					}
				}
				$errors = new WP_Error();
			}
			?>

<!--  Forgot password section #start  -->           
        <div id="lostpassword_form"  <?php if(@$_REQUEST['widgetptype'] == 'forgetpass'){?> style="display:block;" <?php }else{?> style="display:none;" <?php }?>>
            <?php 
			
			if(@$_REQUEST['widgetptype'] == 'forgetpass')
			{
				if($for_msg )
			    echo "<p class=\"error_msg\">".$for_msg.'</p>';	
				if(is_object($errors))
				{
					foreach($errors as $errorsObj)
					{
						foreach($errorsObj as $key=>$val)
						{
							for($i=0;$i<count($val);$i++)
							{
							echo "<p class=\"error_msg\">".$val[$i].'</p>';	
							}
						} 
					}
				}else{
				
				}
				//$errors = new WP_Error();
			} ?>
            <h4><?php _e(FORGOT_PW_TEXT,DOMAIN); ?> </h4> 
            <form name="lostpasswordform" id="lostpasswordform" method="post" action="#login_widget">
				<div class="form_row clearfix"> <label>
				<input type="hidden" name="widgetptype" value="forgetpass" />
			   <?php _e('Email',DOMAIN);?>: </label>
				<input type="text" name="user_login" id="user_login1" value="<?php echo esc_attr($user_login); ?>" size="20" class="textfield" />
				<?php do_action('lostpassword_form'); ?>
				</div>
				<input type="submit" name="wp-submit" value="<?php _e('Get New Password',DOMAIN);?>" class="b_forgotpass button-primary" />
            </form>   
            </div>     
           
             <?php }?>
		</div>
<!--  forgot password #end  -->     
 	<?php
	}

	
	function update($new_instance, $old_instance) {
	//save the widget
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['desc1'] = ($new_instance['desc1']);
		return $instance;
	}
	function form($instance) {
	//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );		
		$title = strip_tags($instance['title']);
		$desc1 = ($instance['desc1']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Login Box Title',DOMAIN);?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
<?php
	}
}	

/**- function to add facebook login EOF -**/

function myplugin_register_widgets() {
	register_widget( 'loginwidget_plugin' );
}
add_action( 'widgets_init', 'myplugin_register_widgets' );
?>