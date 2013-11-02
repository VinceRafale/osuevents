<?php session_start();

function init_templ_profile(){ 
global $current_user,$wpdb;
if($_POST)
{ 
	if(!$current_user->ID) {
		wp_redirect(get_settings('home').'/index.php?page=login');
		exit;
	}
	if($_REQUEST['chagepw'])
	{
		$new_passwd = $_POST['new_passwd'];
		if($new_passwd)
		{
			$user_id = $current_user->ID;
			wp_set_password($new_passwd, $user_id);
			$_SESSION['session_message'] = PW_CHANGE_SUCCESS_MSG;
		}		
	}else
	{
	$user_id = $current_user->ID;
	$user_email = $_POST['user_email'];
	$userName = $_POST['user_fname'];
	$user_website = $_POST['user_website'];
	$pwd = $_POST['pwd'];
	$cpwd = $_POST['cpwd'];
	if(isset($_REQUEST['Update']))	{
		if($user_email)	{
			$check_users=$wpdb->get_var("select ID from $wpdb->users where user_email like \"$user_email\" where ID!=\"$user_id\"");
			if($check_users){
				wp_redirect(site_url().'/?ptype=profile&emsg=wemail');exit;	
			}
		}else {
			wp_redirect(site_url().'/?ptype=profile&emsg=empty_email');exit;
		} if($pwd!=$cpwd)	{
			wp_redirect(site_url().'/?ptype=profile&emsg=pw_nomatch');exit;
		}
	}
	if($userName){
		if($pwd)
		{
			$pwd = md5($pwd);
			$subsql = " , user_pass=\"$pwd\"";	
		}
		$updateUsersql = "update $wpdb->users set user_email=\"$user_email\", display_name=\"$userName\" $subsql  where ID=\"$user_id\"";
		$wpdb->query($updateUsersql);
		
		global $upload_folder_path;
		global $form_fields_usermeta;
	//	$custom_metaboxes = templ_get_usermeta_plugin();

		foreach($form_fields_usermeta as $fkey=>$fval)
		{
			$fldkey = "$fkey";
			$$fldkey = $_POST["$fkey"];
			if($fval['type']=='upload')
			{	
				if($_FILES[$fkey]['name'] && $_FILES[$fkey]['size']>0) {
					$dirinfo = wp_upload_dir();
					$path = $dirinfo['path'];
					$url = $dirinfo['url'];
					$destination_path = $path."/";
					$destination_url = $url."/";
					
					$src = $_FILES[$fkey]['tmp_name'];
					$file_ame = date('Ymdhis')."_".$_FILES[$fkey]['name'];
					$target_file = $destination_path.$file_ame;
					if(move_uploaded_file($_FILES[$fkey]["tmp_name"],$target_file))
					{
						$image_path = $destination_url.$file_ame;
					}else
					{
						$image_path = '';	
					}					
					$_POST[$fkey] = $image_path;
					$fldkey = $image_path;
					update_usermeta($user_id, $fkey, $fldkey);	
				}
				else{
					$_POST[$fkey]=$_POST['prev_upload'];
				}
			}
			else
				update_usermeta($user_id, $fkey, $$fldkey); // User Custom Metadata Here
		}
		$_SESSION['session_message'] = INFO_UPDATED_SUCCESS_MSG;
	}
	
	if(isset($_REQUEST['update_profile']))
	{
	
	global $upload_folder_path;
	
		//$custom_metaboxes = templ_get_usermeta_plugin();
		
		foreach($custom_metaboxes as $fkey=>$fval)
		{
			$fldkey = "$fkey";
			$$fldkey = $_POST["$fkey"];
			if($fval['type']=='upload')
			{
				
				if($_FILES[$fkey]['name'] && $_FILES[$fkey]['size']>0) {
					$dirinfo = wp_upload_dir();
					$path = $dirinfo['path'];
					$url = $dirinfo['url'];
					$destination_path = $path."/";
					$destination_url = $url."/";
					
					$src = $_FILES[$fkey]['tmp_name'];
					$file_ame = date('Ymdhis')."_".$_FILES[$fkey]['name'];
					$target_file = $destination_path.$file_ame;
					if(move_uploaded_file($_FILES[$fkey]["tmp_name"],$target_file))
					{
						$image_path = $destination_url.$file_ame;
					}else
					{
						$image_path = '';	
					}				
					$_POST[$fkey] = $image_path;
					$fldkey = $image_path;
					
				}
				else{
					$_POST[$fkey]=$_POST['prev_upload'];
				}
			}
			update_usermeta($user_id, $fkey, $$fldkey);
			 // User Custom Metadata Here
		}
			
		$user_id = $current_user->ID;
		$user_facebook = $_REQUEST['user_facebook'];
		$user_twitter = $_REQUEST['user_twitter'];
		$description = $_REQUEST['description'];

		update_usermeta($user_id, 'user_facebook', $user_facebook);
		update_usermeta($user_id, 'user_twitter',$user_twitter);		
		update_usermeta($user_id, 'description', trim($description));	
			// User Address Information Here
		$user_website = $_POST['user_website'];
		$userName = $_POST['user_fname'].' '.$_POST['user_lname'];
		$updateUsersql = "update $wpdb->users set user_url=\"$user_website\" where ID=\"$user_id\"";
		$wpdb->query($updateUsersql);
		$_SESSION['session_message'] = INFO_UPDATED_SUCCESS_MSG;
		
	}	
	}
	wp_redirect(get_author_posts_url($current_user->ID));
}

$page_title = EDIT_PROFILE_TITLE;
global $page_title;
get_header(); ?>

<?php if ( get_option('templatic-breadcrumbs' )) {  ?>
    <div class="breadcrumb clearfix">
        <div class="breadcrumb_in"><a href="<?php echo site_url(); ?>"><?php _e('Home',DOMAIN); ?></a> &raquo; <?php echo EDIT_PROFILE_TITLE; ?> </div>
    </div>
<?php } ?>
<div id="content" class="content" >
<!--  CONTENT AREA START -->
  <div class="entry">
    <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
      <div class="post-meta">
        <?php //templ_page_title_above(); //page title above action hook?>
        <?php //add_filter( 'wp_title', EDIT_PROFILE_TITLE, 10, 3 ); //page tilte filter?>
        <?php //templ_page_title_below(); //page title below action hook?>
      </div>
     <?php
			if ( @$_REQUEST['msg']=='success')
			{
				echo "<p class=\"success_msg\"> ".EDIT_PROFILE_SUCCESS_MSG." </p>";
			}else
			if ( @$_REQUEST['emsg']=='empty_email')
			{
				echo "<p class=\"error_msg\"> ".EMPTY_EMAIL_MSG." </p>";
			}elseif ( @$_REQUEST['emsg']=='wemail')
			{
				echo "<p class=\"error_msg\"> ".ALREADY_EXIST_MSG." </p>";
			}elseif ( @$_REQUEST['emsg']=='pw_nomatch')
			{
				echo "<p class=\"error_msg\"> ".PW_NO_MATCH_MSG." </p>";
			}
			
  if(isset($_SESSION['session_message']) && $_SESSION['session_message'] !='')
	{
		echo '<p class="success_msg">'.$_SESSION['session_message'].'</p>';
		$_SESSION['session_message'] = '';
	}
	
	global $submit_form_validation_id;
	$submit_form_validation_id = "userform";
   ?>
  
   <div class="reg_cont_right">
   <form name="userform" id="userform" action="<?php echo site_url().'/?ptype=profile'; ?>" method="post" enctype="multipart/form-data" >  
	<input type="hidden" name="user_email_already_exist" id="user_email_already_exist" value="1" />
	<input type="hidden" name="user_fname_already_exist" id="user_fname_already_exist" value="1" />
	<h1><?php _e(EDIT_PROFILE_PAGE_TITLE);?></h1>
	<?php
	if($_POST)
	{
		$user_email = $_POST['user_email'];	
		$user_fname = $_POST['user_fname'];	
	}else
	{
		$user_email = $current_user->user_email;	
		$user_fname = $current_user->display_name;
	}
	?>
	<?php do_action('templ_profile_form_start');
	//fetch the user custom fields for profile page
		fetch_user_registration_fields('profile');
	
	do_action('templ_profile_form_end');?>
	  
	  
	  
		<input type="submit" name="update" value="<?php echo EDIT_PROFILE_UPDATE_BUTTON;?>" class="b_registernow" />
	  
		 <input type="button" name="Cancel" value="<?php echo PRO_CANCEL_BUTTON; ?>" class="b_registernow" onclick="window.location.href='<?php echo get_author_posts_url($current_user->ID);?>'"/>
	  
   </form>
   <form name="chngpwdform" id="chngpwdform" action="<?php echo site_url().'/?ptype=profile&amp;chagepw=1'; ?>" method="post">
        <?php if($message1) { ?>
          <div class="sucess_msg"> <?php echo PW_CHANGE_SUCCESS_MSG; ?> </div>
          </td>
          <?php } ?>
         	 <h1> <?php echo CHANGE_PW_TEXT; ?> </h1>
               <div class="form_row clearfix">
                <label>
                <?php echo NEW_PW_TEXT; ?> <span class="indicates">*</span></label>   
                <input type="password" name="new_passwd" id="new_passwd"  class="textfield" />
                </div>
                <div class="form_row clearfix ">
                <label>
                <?php echo CONFIRM_NEW_PW_TEXT; ?> <span class="indicates">*</span></label>
                <input type="password" name="cnew_passwd" id="cnew_passwd"  class="textfield" />
                </div>
				<input type="submit" name="update" value="<?php echo EDIT_PROFILE_UPDATE_BUTTON;?>" class="b_registernow" onclick="return chk_form_pw();" />
  
				<input type="button" name="Cancel" value="<?php echo PRO_CANCEL_BUTTON; ?>" class="b_registernow" onclick="window.location.href='<?php echo get_author_posts_url($current_user->ID);?>'"/>
    </form>
   </div>
   <div class="clearfix"></div>

    </div>
  </div>
  </div>
  	<?php get_sidebar('primary'); ?>
  <script type="text/javascript">
	/* <![CDATA[ */
function chk_form_pw()
{
	if(document.getElementById('new_passwd').value == '')
	{
		alert("<?php _e('Please enter '.NEW_PW_TEXT) ?>");
		document.getElementById('new_passwd').focus();
		return false;
	}
	if(document.getElementById('new_passwd').value.length < 4 )
	{
		alert("<?php _e('Please enter '.NEW_PW_TEXT.' minimum 5 chars') ?>");
		document.getElementById('new_passwd').focus();
		return false;
	}
	if(document.getElementById('cnew_passwd').value == '')
	{
		alert("<?php _e('Please enter '.CONFIRM_NEW_PW_TEXT) ?>");
		document.getElementById('cnew_passwd').focus();
		return false;
	}
	if(document.getElementById('cnew_passwd').value.length < 4 )
	{
		alert("<?php _e('Please enter '.CONFIRM_NEW_PW_TEXT.' minimum 5 chars') ?>");
		document.getElementById('cnew_passwd').focus();
		return false;
	}
	if(document.getElementById('new_passwd').value != document.getElementById('cnew_passwd').value)
	{
		alert("<?php _e(NEW_PW_TEXT.' and '.CONFIRM_NEW_PW_TEXT.' should be same') ?>");
		document.getElementById('cnew_passwd').focus();
		return false;
	}
}
/* ]]> */
</script>
<?php include_once(TT_REGISTRATION_FOLDER_PATH . 'registration_validation.php');?>
<?php 
 get_footer(); exit;
}

add_action('init', 'init_templ_profile',11);  ?>
