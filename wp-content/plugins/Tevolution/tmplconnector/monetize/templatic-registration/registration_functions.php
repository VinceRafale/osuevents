<?php

add_action('init','templ_user_custom_fields');
function templ_user_custom_fields(){
	
}
/*
Name : allow_autologin_after_reg
Description : Redirect on plugin dashboard after activating plugin
*/
function allow_autologin_after_reg()
{
  if (get_option('allow_autologin_after_reg') || get_option('allow_autologin_after_reg') == '')
  { 
	return true; 
  }else{
    return false;
  }
}
define('TT_REGISTRATION_FOLDER_PATH',TEMPL_MONETIZE_FOLDER_PATH.'templatic-registration/');
include_once(TT_REGISTRATION_FOLDER_PATH.'registration_main.php');

$form_fields_usermeta_usermeta = array();

/* NAME : FETCH THE CURRENT USER
DESCRIPTION : THIS FUNCTION WILL FETCH THE CURRENT USER */

add_action('admin_init','user_role_assign');
function user_role_assign()
{
	global $current_user;
	$current_user = wp_get_current_user();
}
/* EOF - FETCH THE USER */

/* NAME : DISPLAY CUSTOM FIELDS
DESCRIPTION : THIS FUNCTION WILL DISPLAY THE USER CUSTOM FIELDS IN FRONT AND BACKEND */

	global $current_user;
	if(isset($current_user->ID))
	{
		//$custom_metaboxes = templ_get_usermeta_plugin('profile');
	}
	else
	{ 
		global $wpdb,$custom_post_meta_db_table_name;
		$args = array(
			'post_type'       => 'custom_user_field',
			'post_status'     => 'publish',
			'numberposts'	  => -1,
			'meta_key' => 'sort_order',
			'orderby' => 'meta_value_num',
			'meta_value_num'=>'sort_order',
			'order' => 'ASC'
		);
		$custom_metaboxes_fields = get_posts( $args );
	}
	
	if(isset($custom_metaboxes_fields) && $custom_metaboxes_fields != '')
	{
		foreach($custom_metaboxes_fields as $custom_metaboxes)
		{
			$name = $custom_metaboxes->post_name;
			$site_title = stripslashes($custom_metaboxes->post_title);
			$type = get_post_meta($custom_metaboxes->ID,'ctype',true);
			$default_value = get_post_meta($custom_metaboxes->ID,'default_value',true);
			$is_require = get_post_meta($custom_metaboxes->ID,'is_require',true);
			$admin_desc = $custom_metaboxes->post_content;
			$option_values = get_post_meta($custom_metaboxes->ID,'option_values',true);
			$on_registration = get_post_meta($custom_metaboxes->ID,'on_registration',true);
			$on_profile = get_post_meta($custom_metaboxes->ID,'on_profile',true);
			$on_author_page =  get_post_meta($custom_metaboxes->ID,'on_author_page',true);
			if(is_admin())
			{
				$label =  '<tr><th>'.$site_title.'</th>';
				$outer_st =  '<table class="form-table">';
				$outer_end =  '</table>';
				$tag_st =  '<td>';
				$tag_end =  '<span class="message_note">'.$admin_desc.'</span></td></tr>';
				$tag_before = '';
				$tag_after = '';
			} else {
				$label =  $site_title;
				$outer_st =  '<div class="form_row clearfix">';
				$outer_end =  '</div>';
				$tag_st =  '';
				$tag_end =  '<span class="message_note">'.$admin_desc.'</span>';
				$tag_before = '';
				$tag_after = '';
			}
			if($type == 'text')
			{
				$form_fields_usermeta[$name] = array(
												"label"		=> $label,
												"type"		=>	'text',
												"default"	=>	$default_value,
												"extra"		=>	'id="'.$name.'" size="25" class="textfield"',
												"is_require"	=>	$is_require,
												"outer_st"	=>	$outer_st,
												"outer_end"	=>	$outer_end,
												"tag_st"	=>	$tag_st,
												"tag_end"	=>	$tag_end,
												"tag_before"=>	$tag_before,
												"tag_after"=>	$tag_after,
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
											);
			}
			if($type == 'head')
			{
				$form_fields_usermeta[$name] = array(
												"label"		=> $label,
												"type"		=>	'head',
												"default"	=>	$default_value,
												"extra"		=>	'id="'.$name.'" size="25" class="head"',
												"is_require"	=>	$is_require,
												"outer_st"	=>	$outer_st,
												"outer_end"	=>	$outer_end,
												"tag_st"	=>	$tag_st,
												"tag_end"	=>	$tag_end,
												"tag_before"=>	$tag_before,
												"tag_after"=>	$tag_after,
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
											);
			}
			elseif($type == 'checkbox')
			{
				$form_fields_usermeta[$name] = array(
												"label"		=> $label,
												"type"		=>	'checkbox',
												"default"	=>	$default_value,
												"extra"		=>	'id="'.$name.'" size="25" class="checkbox"',
												"is_require"	=>	$is_require,
												"outer_st"	=>	$outer_st,
												"outer_end"	=>	$outer_end,
												"tag_st"	=>	$tag_st,
												"tag_end"	=>	$tag_end,
												"tag_before"=>	$tag_before,
												"tag_after"=>	$tag_after,
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
												);
			}
			elseif($type == 'textarea')
			{
				$form_fields_usermeta[$name] = array(
												"label"		=> $label,
												"type"		=>	'textarea',
												"default"	=>	$default_value,
												"extra"		=>	'id="'.$name.'" size="25" class="textarea"',
												"is_require"	=>	$is_require,
												"outer_st"	=>	$outer_st,
												"outer_end"	=>	$outer_end,
												"tag_st"	=>	$tag_st,
												"tag_end"	=>	$tag_end,
												"tag_before"=>	$tag_before,
												"tag_after"=>	$tag_after,
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
												);
			}
			elseif($type == 'texteditor')
			{
				$form_fields_usermeta[$name] = array(
												"label"		=> $label,
												"type"		=>	'texteditor',
												"default"	=>	$default_value,
												"extra"		=>	'id="'.$name.'" size="25" class="mce"',
												"is_require"	=>	$is_require,
												"outer_st"	=>	$outer_st,
												"outer_end"	=>	$outer_end,
												"tag_st"	=>	$tag_st,
												"tag_end"	=>	$tag_end,
												"tag_before"=>	'<div class="clear">',
												"tag_after"=>	'</div>',
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
												);
			}
			elseif($type == 'select')
			{
				//$option_values=explode(",",$option_values );
				$form_fields_usermeta[$name] = array(
												"label"		=> $label,
												"type"		=>	'select',
												"default"	=>	$default_value,
												"extra"		=>	'id="'.$name.'"',
												"options"	=> 	$option_values,
												"is_require"	=>	$is_require,
												"outer_st"	=>	$outer_st,
												"outer_end"	=>	$outer_end,
												"tag_st"	=>	'',
												"tag_end"	=>	'',
												"tag_before"=>	$tag_before,
												"tag_after"=>	$tag_after,
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
												);
			}
			elseif($type == 'radio')
			{
				//$option_values=explode(",",$option_values );
				$form_fields_usermeta[$name] = array(
												"label"		=> $label,
												"type"		=>	'radio',
												"default"	=>	$default_value,
												"extra"		=>	'',
												"options"	=> 	$option_values,
												"is_require"	=>	$is_require,
												"outer_st"	=>	$outer_st,
												"outer_end"	=>	$outer_end,
												"tag_st"	=>	'',
												"tag_end"	=>	$tag_end,
												"tag_before"=>	'<div class="form_cat">',
												"tag_after"=>	'</div>',
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
												);
			}
			elseif($type == 'multicheckbox')
			{
				//$option_values=explode(",",$option_values );
				$form_fields_usermeta[$name] = array(
												"label"		=> $label,
												"type"		=>	'multicheckbox',
												"default"	=>	$default_value,
												"extra"		=>	'',
												"options"	=> 	$option_values,
												"is_require"	=>	$is_require,
												"outer_st"	=>	$outer_st,
												"outer_end"	=>	$outer_end,
												"tag_st"	=>	'',
												"tag_end"	=>	$tag_end,
												"tag_before"=>	'<div class="form_cat">',
												"tag_after"=>	'</div>',
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
												);
			}
			elseif($type == 'date')
			{
				$form_fields_usermeta[$name] = array(
												"label"		=> $label,
												"type"		=>	'date',
												"default"	=>	$default_value,
												"extra"		=>	'id="'.$name.'" size="25" class="textfield_date"',
												"is_require"	=>	$is_require,
												"outer_st"	=>	$outer_st,
												"outer_end"	=>	$outer_end,
												"tag_st"	=>	$tag_st,
												"tag_end"	=>	$tag_end,
												"tag_before"=>	$tag_before,
												"tag_after"=>	$tag_after,
												//"tag_st"	=>	'<img src="'.get_template_directory_uri().'/images/cal.gif" alt="Calendar"  onclick="displayCalendar(document.userform.'.$name.',\'yyyy-mm-dd\',this)" style="cursor: pointer;" align="absmiddle" border="0" class="calendar_img" />',
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
												);
			}
			elseif($type == 'upload')
			{
				$form_fields_usermeta[$name] = array(
												"label"		=> $label,
												"type"		=>	'upload',
												"default"	=>	$default_value,
												"extra"		=>	'id="'.$name.'" class="textfield"',
												"is_require"	=>	$is_require,
												"outer_st"	=>	$outer_st,
												"outer_end"	=>	$outer_end,
												"tag_st"	=>	$tag_st,
												"tag_end"	=>	$tag_end,
												"tag_before"=>	$tag_before,
												"tag_after"=>	$tag_after,
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
												);
			}
			elseif($type == 'head')
			{
				$form_fields_usermeta[$name] = array(
												"label"		=> $label,
												"type"		=>	'head',
												"outer_st"	=>	'<h1 class="form_title">',
												"outer_end"	=>	'</h1>',
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page
												);
			}
			elseif($type == 'geo_map')
			{
				$form_fields_usermeta[$name] = array(
												"label"		=> '',
												"type"		=>	'geo_map',
												"default"	=>	$default_value,
												"extra"		=>	'',
												"is_require"	=>	$is_require,
												"outer_st"	=>	'',
												"outer_end"	=>	'',
												"tag_st"	=>	'',
												"tag_end"	=>	'',
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
												);		
			}
			elseif($type == 'image_uploader')
			{
				$form_fields_usermeta[$name] = array(
												"label"		=> '',
												"type"		=>	'image_uploader',
												"default"	=>	$default_value,
												"extra"		=>	'',
												"is_require"	=>	$is_require,
												"outer_st"	=>	'',
												"outer_end"	=>	'',
												"tag_st"	=>	'',
												"tag_end"	=>	'',
												"tag_before"=>	$tag_before,
												"tag_after"=>	$tag_after,
												"on_registration"	=>	$on_registration,
												"on_profile"	=>	$on_profile,
												"on_author_page" => $on_author_page,
												);		
			}
		}
	}
/* EOF - DISPLAY CUSTOM FIELDS */

/*
name : add_author_box
description : add action to fetch author page fileds for author page */

add_action('author_box', 'add_author_box');
function add_author_box($content)
{
	global $current_user,$wp_query;
	$qvar = $wp_query->query_vars;
	$authname = $qvar['author_name'];

	if(isset($_POST['auth_csutom_post']))
	{	
		update_usermeta( $_POST['author_id'], 'author_custom_post', $_POST['author_custom_post'] ); 
	}

	if(isset($authname) && $authname !='') :
		$curauth = get_userdatabylogin($authname);
	else :
		$curauth = get_userdata(intval($_REQUEST['author']));
	endif;

		global $form_fields_usermeta;
		$dirinfo = wp_upload_dir();
		$path = $dirinfo['path'];
		$url = $dirinfo['url'];
		$subdir = $dirinfo['subdir'];
		$basedir = $dirinfo['basedir'];
		$baseurl = $dirinfo['baseurl'];
		
		?>
		
		<div class="author_cont">
		<div class="author_photo">
		<?php
		 echo get_avatar($curauth->ID, 75 );  ?>
		 <?php 
		  if($current_user->ID == $curauth->ID)
		  {
		   ?>
		  <div class="editProfile"><a href="<?php echo get_option('siteurl');?>/?ptype=profile" ><?php echo PROFILE_EDIT_TEXT;?> </a> </div>
		  <?php } ?>
		</div>
		<div class="right_box">
		<?php
		 if(is_array($form_fields_usermeta) && !empty($form_fields_usermeta)){
		 foreach($form_fields_usermeta as $key=> $_form_fields_usermeta)
		  {
				if($_form_fields_usermeta['type']=='head' && $_form_fields_usermeta['on_author_page']==1):
					echo '<h2>'. $_form_fields_usermeta['label'].'</h2>';
				endif;
			
				 if(get_user_meta($curauth->ID,$key,true) != ""): 
					if($_form_fields_usermeta['on_author_page']): 
					if($_form_fields_usermeta['type']!='upload') :
		 ?>	  
		 <?php if($_form_fields_usermeta['type']=='multicheckbox'):  ?>
				<?php
					$checkbox = '';
					foreach(get_user_meta($curauth->ID,$key,true) as $check):
							$checkbox .= $check.",";
					endforeach; ?>
					<p><label><?php echo $_form_fields_usermeta['label']; ?></label> : <?php echo substr($checkbox,0,-1); 
				?></p>
				<?php else:  ?>
					<p><label><?php echo $_form_fields_usermeta['label']; ?></label> : <?php echo get_usermeta($curauth->ID,$key,true); ?></p>
					
				<?php endif;
				endif;
				if($_form_fields_usermeta['type']=='upload')
				{?>
				<p><label  style="vertical-align:top;"><?php echo $_form_fields_usermeta['label']." : "; ?></label> <img src="<?php echo get_usermeta($curauth->ID,$key,true);?>" style="width:150px;height:150px" /></p>
				<?php }
				endif;				
			endif;
		  }
		  }
		  $posttaxonomy = get_option("templatic_custom_post");
		  $posttaxonomy = get_post_types();	 
		  $author_post= get_user_meta($curauth->ID, 'author_custom_post',true ); 
		  if($posttaxonomy):?>   
			<strong><?php _e('Select the listing type to show your submited listing.',DOMAIN);?></strong>
		  <form action="" method="post">
			<input type="hidden" name="author_id" value="<?php echo $qvar['author'];?>" />
		  <?php
			echo '<ul class=author_custom_post"">';               
			foreach($posttaxonomy as $key=>$_posttaxonomy):
				if( $key != "page" && $key != "attachment" && $key != "revision" && $key != "nav_menu_item" ):
			?>
				<li> <?php ?>
					<input type="checkbox" name="author_custom_post[]" value="<?php echo $key?>" <?php if(is_array($author_post)){ if(in_array($key,$author_post))echo "checked";}?>/>&nbsp;<?php echo $_posttaxonomy; ?>
				</li>	
			<?php
				endif;
			endforeach;
			echo '</ul>';
			?>
				<input type="submit" name="auth_csutom_post" value="Submit" />
			</form>
		 <?php endif; ?>
			</div>
			<div class="clearfix"></div>
		  </div>
		  <?php if($author_post): $i=0;  
				$author_link=apply_filters('templ_login_widget_dashboardlink_filter',get_author_posts_url($curauth->ID));
				if(strpos($author_link, "?"))
					$author_link=apply_filters('templ_login_widget_dashboardlink_filter',get_author_posts_url($curauth->ID))."&";
				else
					$author_link=apply_filters('templ_login_widget_dashboardlink_filter',get_author_posts_url($curauth->ID))."?";
		  ?>
			<div class="author_post_tabs">
				<h3 class="author_custom_post_wrapper">
				<?php foreach($posttaxonomy as $key=>$_posttaxonomy):?>            	
					<?php if(in_array($key,$author_post)):
						$active_tab=($key==$_REQUEST['custom_post']) ?'nav-author-post-tab-active':'';
						if($active_tab=="" && !isset($_REQUEST['custom_post']))
						{
							if($i==0)
							{
								$active_tab ='nav-author-post-tab-active';						
								$custom_post_type=$key;
							}
						}
					?>
						<a href="<?php echo $author_link;?>custom_post=<?php  echo $key;?>" class="author_post_tab <?php echo $active_tab;?>"><?php echo $_posttaxonomy; ?></a>
					<?php endif;?>
				<?php $i++; endforeach;?>
			 </h3>
			</div>        
				<?php
					if(isset($_REQUEST['custom_post']) && $_REQUEST['custom_post']!="")
						$post_type=$_REQUEST['custom_post'];
					else
						$post_type=$custom_post_type;
					
					$posts_per_page=get_option('posts_per_page');
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$args=array(
							'post_type'  =>$post_type,
							'author'=>$curauth->ID,
							'post_status' => 'publish',
							'paged'=>$paged,
							'order_by'=>'date',
							'order' => 'DESC'
						);					
					query_posts( $args );					
				?>      
		  <?php endif; 
}
/*
Name : templ_fetch_registration_onsubmit
Desc : fecth login and registration form in submit page template
*/
function templ_fetch_registration_onsubmit(){  ?>
	<div id="login_user_meta" style="display:none;">
	 <input type="hidden" name="user_email_already_exist" id="user_email_already_exist" value="<?php if($_SESSION['custom_fields']['user_email_already_exist']) { echo "1"; } ?>" />
	   <input type="hidden" name="user_fname_already_exist" id="user_fname_already_exist" value="<?php if($_SESSION['custom_fields']['user_fname_already_exist']) { echo "1"; } ?>" />
	    <?php
			$user_meta_array = user_fields_array();
			display_usermeta_fields($user_meta_array);/* fetch registration form */
			include_once(TT_REGISTRATION_FOLDER_PATH . 'registration_validation.php');
		?>
	</div>
<?php
}

/*
Name : templ_fecth_login_onsubmit
Desc : fecth login form in submit page template
*/
function templ_fecth_login_onsubmit(){ ?>
	<div class="login_submit clearfix" id="loginform">
                  <div class="sec_title">
                  	<h3 class="form_title spacer_none"><?php _e(LOGINORREGISTER_PLUGIN);?></h3>
                  </div>

					<?php if(isset($_REQUEST['usererror'])==1)
                    {
                        if(isset($_SESSION['userinset_error']))
                        {
                            for($i=0;$i<count($_SESSION['userinset_error']);$i++)
                            {
                                echo '<div class="error_msg">'.$_SESSION['userinset_error'][$i].'</div>';
                            }
                            echo "<br>";
                        }
                    }
                    ?>   

				  <?php if(isset($_REQUEST['emsg'])==1): ?>
                    <div class="error_msg"><?php _e(INVALID_USER_PW_MSG_PLUGIN,DOMAIN);?></div>
                  <?php endif; ?>
                  <div class="user_type clearfix">
                    <label class="lab1"><?php _e(IAM_TEXT_PLUGIN,DOMAIN);?> </label>
                    <label class="radio_lbl"><input name="user_login_or_not" type="radio" value="existing_user" <?php if($user_login_or_not=='existing_user'){ echo 'checked="checked"';}else{ echo 'checked="checked"'; }?> onclick="set_login_registration_frm('existing_user');" /> <?php _e(EXISTING_USER_TEXT_PLUGIN);?> </label>
                    <?php 
						$users_can_register = get_option('users_can_register');
						if($users_can_register):
					?>
                    <label class="radio_lbl"><input name="user_login_or_not" type="radio" value="new_user" <?php if($user_login_or_not=='new_user'){ echo 'checked="checked"';}?> onclick="set_login_registration_frm('new_user');" /> <?php _e(NEW_USER_TEXT,DOMAIN);?> </label>
                    <?php endif;?>
                  </div>
                  <form name="loginform" class="sublog_login" id="login_user_frm_id" action="<?php echo get_settings('home').'/index.php?page=login'; ?>" method="post" >
					  <div class="form_row clearfix lab2_cont">
						<label class="lab2"><?php _e(LOGIN_TEXT,DOMAIN);?><span class="required">*</span></label>
						<input type="text" class="textfield slog_prop " id="user_login" name="log" />
					  </div>
					  
					   <div class="form_row learfix lab2_cont">
						<label class="lab2"><?php _e(PASSWORD_TEXT,DOMAIN);?><span class="required">*</span> </label>
						<input type="password" class="textfield slog_prop" id="user_pass" name="pwd" />
					  </div>
					  
					  <div class="form_row clearfix">
					  <input name="submit" type="submit" value="<?php _e(SUBMIT_BUTTON,DOMAIN);?>" class="button_green submit" />
					  </div>
                           <?php do_action('login_form');?>
					  <?php	$login_redirect_link = get_permalink();?>
					  <input type="hidden" name="redirect_to" value="<?php echo $login_redirect_link; ?>" />
					  <input type="hidden" name="testcookie" value="1" />
					  <input type="hidden" name="pagetype" value="<?php echo $login_redirect_link; ?>" />
				  </form>
    </div>
<?php
} 

/*
Name : templ_insertuser_with_listing
Desc : return page to insert user
*/

function templ_insertuser_with_listing(){
	include_once(TEMPL_REGISTRATION_FOLDER_PATH.'single_page_checkout_insertuser.php');	
	return $current_user_id;
}

/*
Name : fetch_user_registration_fields
Desc : return user custom fields for register or profile page.
*/

function fetch_user_registration_fields($validate,$user_id='')
{
global $form_fields_usermeta,$user_validation_info,$current_user;
		
	$user_validation_info = array();
	if($form_fields_usermeta){
	foreach($form_fields_usermeta as $key=>$val)
	{
		if($validate == 'register')
			$validate_form = $val['on_registration'];
		else
			$validate_form = $val['on_profile'];
			
		if($validate_form){
        $str = ''; $fval = '';
        $field_val = $key.'_val';
		
        if(isset($field_val) && $field_val){ $fval = $field_val; }else{ $fval = $val['default']; }
      
        if($val['is_require'])
        {
            $user_validation_info[] = array(
                                       'name'	=> $key,
                                       'espan'	=> $key.'_error',
                                       'type'	=> $val['type'],
                                       'text'	=> $val['label'],
                                       );
        }
		
		if($key)
		{
			if($user_id != '' )
			{
				$fval = get_user_meta($user_id,$key,true);
			}
			else
			{
				$fval = get_user_meta($current_user->ID,$key,true);
			}
		}
		
        if($val['type']=='text')
        {
			if(!(is_templ_wp_admin() && ( $key == 'user_email' || $key == 'user_fname'))) /* CONDITION FOR EMAIL AND USER NAME FIELD */
			{
				$str = '<input name="'.$key.'" type="text" '.$val['extra'].' value="'.$fval.'">';
				if($val['is_require'])
				{
					$str .= '<span id="'.$key.'_error"></span>';
				}
			}
        }elseif($val['type']=='hidden')
        {
            $str = '<input name="'.$key.'" type="hidden" '.$val['extra'].' value="'.$fval.'">';	
            if($val['is_require'])
            {
                $str .= '<span id="'.$key.'_error"></span>';	
            }
        }else
        if($val['type']=='textarea')
        {
            $str = '<textarea name="'.$key.'" '.$val['extra'].'>'.$fval.'</textarea>';	
            if($val['is_require'])
            {
                $str .= '<span id="'.$key.'_error"></span>';	
            }
        }else
        if($val['type']=='file')
        {
            $str = '<input name="'.$key.'" type="file" '.$val['extra'].' value="'.$fval.'">';
            if($val['is_require'])
            {
                $str .= '<span id="'.$key.'_error"></span>';	
            }
        }else
        if($val['type']=='include')
        {
            $str = @include_once($val['default']);
        }else
        if($val['type']=='head')
        {
            $str = '';
        }else
        if($val['type']=='date')
        {
			?>
            <script type="text/javascript">	
				jQuery(function(){
				var pickerOpts = {
						showOn: "both",
						dateFormat: 'yy-mm-dd',
						buttonImage: "<?php echo TEMPL_PLUGIN_URL; ?>css/datepicker/images/cal.png",
						buttonText: "Show Datepicker"
					};	
					jQuery("#<?php echo $key;?>").datepicker(pickerOpts);					
				});
			</script>
            <?php
            $str = '<input name="'.$key.'" id="'.$key.'" type="text" '.$val['extra'].' value="'.$fval.'">';				
            if($val['is_require'])
            {
                $str .= '<span id="'.$key.'_error"></span>';	
            }
        }else
        if($val['type']=='catselect')
        {
            $term = get_term( (int)$fval, CUSTOM_CATEGORY_TYPE1);
            $str = '<select name="'.$key.'" '.$val['extra'].'>';
            $args = array('taxonomy' => CUSTOM_CATEGORY_TYPE1);
            $all_categories = get_categories($args);
            foreach($all_categories as $key => $cat) 
            {
            
                $seled='';
                if($term->name==$cat->name){ $seled='selected="selected"';}
                $str .= '<option value="'.$cat->name.'" '.$seled.'>'.$cat->name.'</option>';	
            }
            $str .= '</select>';
            if($val['is_require'])
            {
                $str .= '<span id="'.$key.'_error"></span>';	
            }
        }else
        if($val['type']=='catdropdown')
        {
            $cat_args = array('name' => 'post_category', 'id' => 'post_category_0', 'selected' => $fval, 'class' => 'textfield', 'orderby' => 'name', 'echo' => '0', 'hierarchical' => 1, 'taxonomy'=>CUSTOM_CATEGORY_TYPE1);
            $cat_args['show_option_none'] = __('Select Category',DOMAIN);
            $str .=wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
            if($val['is_require'])
            {
                $str .= '<span id="'.$key.'_error"></span>';	
            }
        }else
        if($val['type']=='select')
        {
			 $option_values_arr = explode(',', $val['options']);
            $str = '<select name="'.$key.'" '.$val['extra'].'>';
			 $str .= '<option value="" >'.PLEASE_SELECT.'</option>';	
            for($i=0;$i<count($option_values_arr);$i++)
            {
                $seled='';
                
                if($fval==$option_values_arr[$i]){ $seled='selected="selected"';}
                $str .= '<option value="'.$option_values_arr[$i].'" '.$seled.'>'.$option_values_arr[$i].'</option>';	
            }
            $str .= '</select>';
            if($val['is_require'])
            {
                $str .= '<span id="'.$key.'_error"></span>';	
            }
        }else
        if($val['type']=='catcheckbox')
        {
            $fval_arr = explode(',',$fval);
            $str .= $val['tag_before'].get_categories_checkboxes_form(CUSTOM_CATEGORY_TYPE1,$fval_arr).$oval.$val['tag_after'];
            if($val['is_require'])
            {
                $str .= '<span id="'.$key.'_error"></span>';	
            }
        }else
        if($val['type']=='catradio')
        {
            $args = array('taxonomy' => CUSTOM_CATEGORY_TYPE1);
            $all_categories = get_categories($args);
            foreach($all_categories as $key1 => $cat) 
            {
                
                
                    $seled='';
                    if($fval==$cat->term_id){ $seled='checked="checked"';}
                    $str .= $val['tag_before'].'<input name="'.$key.'" type="radio" '.$val['extra'].' value="'.$cat->name.'" '.$seled.'> '.$cat->name.$val['tag_after'];	
                
            }
            if($val['is_require'])
            {
                $str .= '<span id="'.$key.'_error"></span>';	
            }
        }else
        if($val['type']=='checkbox')
        {
            if($fval){ $seled='checked="checked"';}
            $str = '<input name="'.$key.'" type="checkbox" '.$val['extra'].' value="1" '.$seled.'>';
            if($val['is_require'])
            {
                $str .= '<span id="'.$key.'_error"></span>';	
            }
        }else
        if($val['type']=='upload')
        {
			$str = '<input name="'.$key.'" type="file" '.$val['extra'].' '.$uclass.' value="'.$fval.'" > ';
			if($fval!=''){
				$str .='<img src="'.$fval.'"  width="125px" height="125px" alt="" />
				<br />
				<input type="hidden" name="prev_upload" value="'.$fval.'" />
				';	
			}
			if($val['is_require'])
			{
				$str .='<span id="'.$key.'_error"></span>';	
			}
        }
        else
        if($val['type']=='radio')
        {
            $options = $val['options'];
            if($options)
            {
                $option_values_arr = explode(',',$options);
                for($i=0;$i<count($option_values_arr);$i++)
                {
                    $seled='';
                    if($fval==$option_values_arr[$i]){$seled='checked="checked"';}
                    $str .= $val['tag_before'].'<input name="'.$key.'" type="radio" '.$val['extra'].'  value="'.$option_values_arr[$i].'" '.$seled.'> '.$option_values_arr[$i].$val['tag_after'];
                }
                if($val['is_require'])
                {
                    $str .= '<span id="'.$key.'_error"></span>';	
                }
            }
        }else
        if($val['type']=='multicheckbox')
        {
            $options = $val['options'];
            if($options)
            {  
				$chkcounter = 0;
                $option_values_arr = explode(',',$options);
                for($i=0;$i<count($option_values_arr);$i++)
                {
                    $chkcounter++;
                    $seled='';
           // 		$fval_arr = explode(',',$fval);
					if($fval)
					{
				   		if(in_array($option_values_arr[$i],$fval)){ $seled='checked="checked"';}
					}
                    $str .= $val['tag_before'].'<input name="'.$key.'[]"  id="'.$key.'_'.$chkcounter.'" type="checkbox" '.$val['extra'].' value="'.$option_values_arr[$i].'" '.$seled.'> '.$option_values_arr[$i].$val['tag_after'];
                }
                if($val['is_require'])
                {
                    $str .= '<span id="'.$key.'_error"></span>';	
                }
            }
        }
        else
        if($val['type']=='packageradio')
        {
            $options = $val['options'];
            foreach($options as $okey=>$oval)
            {
                $seled='';
                if($fval==$okey){$seled='checked="checked"';}
                $str .= $val['tag_before'].'<input name="'.$key.'" type="radio" '.$val['extra'].' value="'.$okey.'" '.$seled.'> '.$oval.$val['tag_after'];	
            }
            if($val['is_require'])
            {
                $str .= '<span id="'.$key.'_error"></span>';	
            }
        }else
        if($val['type']=='geo_map')
        {
            do_action('templ_submit_form_googlemap');	
        }else
        if($val['type']=='image_uploader')
        {
            do_action('templ_submit_form_image_uploader');	
        }
        if($val['is_require'])
        {
            $label = '<label>'.$val['label'].' <span class="indicates">*</span> </label>';
        }else
        {
            $label = '<label>'.$val['label'].'</label>';
        }
		if(!(is_templ_wp_admin() && ( $key == 'user_email' || $key == 'user_fname'))) /* CONDITION FOR EMAIL AND USER NAME FIELD */
		{			
			if($val['type']=='texteditor')
			{
				echo $val['outer_st'].$label.$val['tag_st'];
				 echo $val['tag_before'].$val['tag_after'];
            // default settings
					$settings =   array(
						'wpautop' => true, // use wpautop?
						'media_buttons' => false, // show insert/upload button(s)
						'textarea_name' => $key, // set the textarea name to something different, square brackets [] can be used here
						'textarea_rows' => '10', // rows="..."
						'tabindex' => '',
						'editor_css' => '<style>.wp-editor-wrap{width:640px;margin-left:0px;}</style>', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
						'editor_class' => '', // add extra class(es) to the editor textarea
						'teeny' => false, // output the minimal editor config used in Press This
						'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
						'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
						'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
					);				
					if(isset($fval) && $fval != '') 
					{  $content=$fval; }
					else{$content= $fval; } 				
					wp_editor( $content, $key, $settings);				
			
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				echo $str.$val['tag_end'].$val['outer_end'];
			}else{	
				echo $val['outer_st'].$label.$val['tag_st'].$str.$val['tag_end'].$val['outer_end'];
			}
        }
		}
	}
	}
}
/* NAME : CUSTOMIZE USER DASHBOARD IN BACKEND
DESCRIPTION : THIS FUNCTION WILL ADD USER CUSTOM FIELDS ON DASHBOARD */
add_action('show_user_profile', 'add_extra_profile_fields'); /* CALL A FUNCTION */

function add_extra_profile_fields( $user )
{
	$user_id = $user->ID;
	fetch_user_registration_fields( 'profile',$user_id ); /* CALL A FUNCTION TO DISPLAY CUSTOM FIELDS */
}
add_action('edit_user_profile', 'add_extra_profile_fields');

/* NAME : SAVE CUSTOM FIELDS FROM BACKEND
DESCRIPTION : THIS FUNCTION WILL SAVE CUSTOM FIELD DATA DISPLAYING ON PROFILE PAGE IN BACKEND */
add_action('personal_options_update', 'update_extra_profile_fields'); /* CALL A FUNCTION */

function update_extra_profile_fields( $user_id )
{

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

	/*foreach( $_POST as $key => $val )
	{
		update_user_meta($user_id, $key, $val);
	}		*/
}

add_action( 'edit_user_profile_update', 'update_extra_profile_fields' ); /* UPDATE ANOTHER USER'S DATA */

function modify_form(){
echo  '<script type="text/javascript">
      jQuery("#your-profile").attr("enctype", "multipart/form-data");
        </script>
  ';
}
add_action('admin_footer','modify_form');

/*Convert special character as normal character */
function Unaccent($string)
{
    if (strpos($string = htmlentities($string, ENT_QUOTES, 'UTF-8'), '&') !== false)
    {
        $string = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $string), ENT_QUOTES, 'UTF-8');
    }

    return $string;
}

?>