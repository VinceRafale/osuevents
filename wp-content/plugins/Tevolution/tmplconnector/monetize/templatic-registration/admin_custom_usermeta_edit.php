<?php
global $wpdb,$current_user;
$post_id = @$_REQUEST['cf'];
$post_val = get_post($post_id);
if(isset($_POST['save_user']) && $_POST['save_user'] != "")
{
	$my_post = array();
	//$admin_title = $_POST['admin_title'];
	$site_title = $_POST['site_title'];
	$ctype = $_POST['ctype'];
	$htmlvar_name = $_POST['htmlvar_name'];
	$admin_desc = $_POST['admin_desc'];
	//cancel $clabels = $_POST['clabels'];
	//cancel $default_value = $_POST['default_value'];
	$sort_order = $_POST['sort_order'];
	$is_active = $_POST['is_active'];
	$on_registration = $_POST['on_registration'];
	$on_profile = $_POST['on_profile'];
	$option_values = $_POST['option_values'];
	$is_require = $_POST['is_require'];
	$my_post['post_title'] = $site_title;
	$my_post['post_name'] = $htmlvar_name;
	$my_post['post_content'] = $admin_desc;
	$my_post['post_status'] = $is_active;
	$on_author_page = $_REQUEST['on_author_page'];
	$my_post['post_type'] = 'custom_user_field';
	$custom = array("ctype"		=> $ctype,
						"sort_order" 		=> $sort_order,
						"on_registration"	=> $on_registration,
						"on_profile"		=> $on_profile,
						"option_values"		=> $option_values,
						"is_require"		=> $is_require,
						"on_author_page"	=> $on_author_page
					);

	if($_REQUEST['cf'])
	{
		$cf = $_REQUEST['cf'];
		$my_post['ID'] = $_REQUEST['cf'];
		$last_postid = wp_insert_post( $my_post );
		$msgtype = 'edit-suc';
	}else
	{
		$last_postid = wp_insert_post( $my_post );
		$msgtype = 'add-suc';
	}
	/* Finish the place geo_latitude and geo_longitude in postcodes table*/
		if(is_plugin_active('wpml-translation-management/plugin.php')){
			if(function_exists('wpml_insert_templ_post'))
				wpml_insert_templ_post($last_postid,'custom_user_field'); /* insert post in language */
		}
	foreach($custom as $key=>$val)
		{				
			update_post_meta($last_postid, $key, $val);
		}
	
	$url = site_url().'/wp-admin/admin.php';
	echo '<form action="'.$url.'#option_display_custom_usermeta" method="get" id="frm_edit_customuser_fields" name="frm_edit_customuser_fields">
	<input type="hidden" value="user_custom_fields" name="page"><input type="hidden" value="'.$msgtype.'" name="msgtype">
	</form>
	<script>document.frm_edit_customuser_fields.submit();</script>
	';exit;
}
?>
<script type="text/javascript" src="<?php echo TEMPL_PLUGIN_URL.'tmplconnector/monetize/templatic-registration/add_user_custom_fields_validations.js';?>"></script>
<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
	<h2><?php if(isset($_REQUEST['cf']) && $_REQUEST['cf']){  _e('Edit Custom User Meta',DOMAIN); 
	$custom_msg = 'Here you can edit custom user meta detail.'; }else { _e('Add a field for users&rsquo; profile',DOMAIN); $custom_msg = 'Create a new field to show in user dashboard / profile section.';}?>
	  <a href="<?php echo site_url();?>/wp-admin/admin.php?page=user_custom_fields" name="btnviewlisting" id="edit_custom_user_custom_field" class="add-new-h2" title="<?php _e('Back to Manage fields list',DOMAIN);?>"/><?php _e('Back to Manage fields list',DOMAIN); ?></a> 
    </h2>
    <p class="description"><?php _e($custom_msg,DOMAIN);?></p>
<form class="form_style" action="<?php echo site_url();?>/wp-admin/admin.php?page=user_custom_fields&action=addnew" method="post" name="custom_fields_frm" onsubmit="return chk_userfield_form();">
	
	
	<input type="hidden" name="save" value="1" /> 
	<?php if(isset($_REQUEST['cf']) && $_REQUEST['cf']){?>
	<input type="hidden" name="cf" value="<?php echo $_REQUEST['cf'];?>" />
	<?php }?>
	<input type="hidden" name="post_type" id="post_type" value="registration" />
	<input type="hidden" name="clabels" id="clabels" value="<?php if(isset($post_val->clabels)) { echo $post_val->clabels; } ?>" />

	<input type="hidden" name="default_value" id="default_value" value="<?php if(isset($post_val->default_value)) { echo $post_val->default_value; } ?>" />
	<input type="hidden" name="admin_title" id="admin_title" value="<?php if(isset($post_val->admin_title)) { echo $post_val->admin_title; } ?>" />
	
	<table class="form-table" style="width:50%;" id="form_table_user_custom_field">       
		<tbody>
		<!-- field type start -->
		<tr style="display:block;" >
            	<th>
                	<label for="field_type" class="form-textfield-label"><?php _e('Field type',DOMAIN);?></label>
            	</th>
            	<td>
            	<select name="ctype" id="ctype" onchange="usershow_option_add(this.value)" >
                  <option value="text" <?php if(get_post_meta($post_id,"ctype",true)=='text'){ echo 'selected="selected"';}?>><?php _e('Text',DOMAIN);?></option>
                   <option value="texteditor" <?php if(get_post_meta($post_id,"ctype",true)=='texteditor'){ echo 'selected="selected"';}?>><?php _e('Text Editor',DOMAIN);?></option>
                   <option value="head" <?php if(get_post_meta($post_id,"ctype",true)=='head'){ echo 'selected="selected"';}?>><?php _e('Text Heading',DOMAIN);?></option>
                   <option value="date" <?php if(get_post_meta($post_id,"ctype",true)=='date'){ echo 'selected="selected"';}?>><?php _e('Date Picker',DOMAIN);?></option>
                   <option value="multicheckbox" <?php if(get_post_meta($post_id,"ctype",true)=='multicheckbox'){ echo 'selected="selected"';}?>><?php _e('Multi Checkbox',DOMAIN);?></option>
                  <option value="radio" <?php if(get_post_meta($post_id,"ctype",true)=='radio'){ echo 'selected="selected"';}?>><?php _e('Radio',DOMAIN);?></option>
                  <option value="select" <?php if(get_post_meta($post_id,"ctype",true)=='select'){ echo 'selected="selected"';}?>><?php _e('Select',DOMAIN);?></option>
                  <option value="textarea" <?php if(get_post_meta($post_id,"ctype",true)=='textarea'){ echo 'selected="selected"';}?>><?php _e('Textarea',DOMAIN);?></option>
                   <option value="upload" <?php if(get_post_meta($post_id,"ctype",true)=='upload'){ echo 'selected="selected"';}?>><?php _e('Upload',DOMAIN);?></option>
                </select>
				<p class="description"><?php _e('Select the type of the custom field.',DOMAIN);?></p>
				</td>
		</tr>
		<!-- field type end -->
		
		<!-- option value start -->
		<tr id="ctype_option_tr_id"  <?php if(get_post_meta($post_id,"ctype",true)=='select'){?> style="display:block;" <?php }else{?> style="display:none;" <?php }?> >
			<th><?php _e('Option values',DOMAIN);?></th>
			<td> <input type="text" class="regular-text" name="option_values" id="option_values" value="<?php echo get_post_meta($post_id,"option_values",true);?>" size="50" />
			  <p class="description"><?php _e('Seperate multiple option values with a comma. eg. Yes, No',DOMAIN);?></p></td>
		</tr>
		<!-- option value end -->
		
		<!-- fieldname start -->
		<tr id="ctype_option_tr_id"  <?php if(get_post_meta($post_id,"ctype",true)=='select'){?> style="display:block;" <?php }else{?> style="display:block;" <?php }?> >
			<th><?php _e('Field name',DOMAIN);?></th>
			<td>  <input type="text" class="regular-text" name="site_title" id="site_title" value="<?php if(isset($post_val->post_title)) { echo $post_val->post_title; } ?>" />
			<p class="description"><?php _e('The name you provide here will be display as the field&rsquo;s name (label) in the front-end.',DOMAIN);?></p></td>
		</tr>
		<!-- field name end -->
		
		<!-- field description start -->
		<tr id="ctype_option_tr_id"  <?php if(get_post_meta($post_id,"ctype",true)=='select'){?> style="display:block;" <?php }else{?> style="display:none;" <?php }?> >
			<th><?php _e('Field description',DOMAIN);?></th>
			<td> <input type="text" class="regular-text" name="admin_desc" id="admin_desc" value="<?php if(isset($post_val->post_content)) { echo $post_val->post_content; } ?>" />
			 <p class="description"><?php _e('Custom field description which will appear in the front-end as well as the backend.',DOMAIN);?></p></td>
		</tr>
		<!-- field description end -->
		
		<!-- htmlvar_name1 name start-->
		<tr id="htmlvar_name1" style="display:block;" >
			<th><?php _e('HTML variable name',DOMAIN);?></th>
			<td><input type="text" class="regular-text" name="htmlvar_name" id="htmlvar_name" value="<?php if(isset($post_val->post_name)) { echo $post_val->post_name; } ?>" />
			 <p class="description"><?php _e('The name you specify should be unique. You will not be able to modify it once you submit the field.',DOMAIN);?></p></td>
		</tr>
		<!-- htmlvar_name1 name end-->
		
		<!-- start order1 start-->
		<tr id="sort_order1" style="display:block;" >
			<th><?php _e('Position (Display order)',DOMAIN);?></th>
			<td> <input type="text" class="regular-text" name="sort_order" id="sort_order"  value="<?php echo get_post_meta($post_id,"sort_order",true);?>" />
			 <p class="description"><?php _e('This is a numeric value that determines the position of the custom field in the front-end and the back-end. e.g. 5',DOMAIN);?></p></td>
		</tr>
		<!-- start order1 end-->
		
		<!-- status start-->
		<tr id="sort_order1" style="display:block;" >
			<th><?php _e('Is Active?',DOMAIN);?></th>
			<td>  <select name="is_active" id="is_active" >
			<option value="publish" <?php if($post_val->post_status=='publish'){ echo 'selected="selected"';}?>><?php _e('Yes',DOMAIN);?></option>
			<option value="draft" <?php if($post_val->post_status=='draft'){ echo 'selected="selected"';}?>><?php _e('No',DOMAIN);?></option>
			</select>
			<p class="description"><?php _e('This setting activates/de-activates the custom field in the front-end and the back-end.',DOMAIN);?></p></td>
		</tr>
		<!-- status end-->
		
		<!-- Compulsory start -->
		<tr id="is_require_id"  <?php if(get_post_meta($post_id,"ctype",true)=='head'){?> style="display:none;" <?php }else{ ?>style="display:block;"<?php }?>>
			<th><?php _e('Compulsory',DOMAIN);?></th>
			<td>     <select name="is_require" id="is_require" >
			<option value="1" <?php if(get_post_meta($post_id,"is_require",true)=='1'){ echo 'selected="selected"';}?>><?php _e('Yes',DOMAIN);?></option>
			<option value="0" <?php if(get_post_meta($post_id,"is_require",true)=='0'){ echo 'selected="selected"';}?>><?php _e('No',DOMAIN);?></option>
			</select>
			<p class="description"><?php _e('Specify whether or not this field is required to be filled in compulsarily by users.',DOMAIN);?></p></td>
		</tr>
		<!-- Compulsory end-->	
		
		<!-- on Registration page start -->
		<tr id="on_registration_id" style="display:block;">
			<th><?php _e('Show on Registration page?',DOMAIN);?></th>
			<td> <select name="on_registration" id="on_registration" >
				<option value="1" <?php if(get_post_meta($post_id,"on_registration",true)=='1'){ echo 'selected="selected"';}?>><?php _e('Yes',DOMAIN);?></option>
				<option value="0" <?php if(get_post_meta($post_id,"on_registration",true)=='0'){ echo 'selected="selected"';}?>><?php _e('No',DOMAIN);?></option>
				</select>
			<p class="description"><?php _e('Specify whether or not this field be shown on the &lsquo;Registration page&rsquo;.',DOMAIN);?></p>
			</td>
		</tr>
		<!-- on Registration page end-->
		<!-- on edit profile  page start -->
		<tr id="on_profile_id" style="display:block;">
			<th><?php _e('Show On Edit Profile Page ?',DOMAIN);?></th>
			<td>
			<select name="on_profile" id="on_profile">
			<option value="1" <?php if(get_post_meta($post_id,"on_profile",true)=='1'){ echo 'selected="selected"';}?>><?php _e('Yes',DOMAIN);?></option>
			<option value="0" <?php if(get_post_meta($post_id,"on_profile",true)=='0'){ echo 'selected="selected"';}?>><?php _e('No',DOMAIN);?></option>
			</select>
			<p class="description"><?php _e('Specify whether or not this field be shown on the &lsquo;user&rsquo;s edit profile page&rsquo;.',DOMAIN);?></p>
			</td>
		</tr>
		<!-- on edit profile page end-->
		
		<!-- in authot box  page start -->
		<tr id="on_profile_id" style="display:block;">
			<th><?php _e('Show On user dashboard Page ?',DOMAIN);?></th>
			<td>
			 <select name="on_author_page" id="on_author_page">
			<option value="1" <?php if(get_post_meta($post_id,"on_author_page",true)=='1'){ echo 'selected="selected"';}?>><?php _e('Yes',DOMAIN);?></option>
			<option value="0" <?php if(get_post_meta($post_id,"on_author_page",true)=='0'){ echo 'selected="selected"';}?>><?php _e('No',DOMAIN);?></option>
			</select>
			 <p class="description"><?php _e('Specify whether or not this field be shown on the &lsquo;user&rsquo;s dashboard page&rsquo;.',DOMAIN);?></p>
			</td>
		</tr>
		<!--in authot box  page end-->

		<tr style="display:block">
			<td class="save" colspan="2">
				<input type="submit" class="button-primary" name="save_user"  id="save" value="<?php _e('Save all changes',DOMAIN);?>" />
			</td>
		</tr>
		</tbody>


</table>
</form>
</div>
<script type="text/javascript">
function usershow_option_add(htmltype)
{
	if(htmltype=='select' || htmltype=='multiselect' || htmltype=='radio' || htmltype=='multicheckbox')
	{
		document.getElementById('ctype_option_tr_id').style.display='block';		
	}else
	{
		document.getElementById('ctype_option_tr_id').style.display='none';	
	}
	
	if(htmltype=='head')
	{
		document.getElementById('is_require_id').style.display='none';	
	}
	else
	{
		document.getElementById('is_require_id').style.display='block';	
	}
}
if(document.getElementById('ctype').value)
{
	usershow_option_add(document.getElementById('ctype').value)	;
}
</script>