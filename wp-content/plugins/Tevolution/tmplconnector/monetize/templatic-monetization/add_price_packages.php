<script type="text/javascript">
function showcategory(str)
{  	
	if (str=="")
	  {
	  document.getElementById("field_category").innerHTML="";
	  return;
	  }else{
	  document.getElementById("field_category").innerHTML="";
	  document.getElementById("process").style.display ="block";
	  }
		if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
		else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
		xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		 document.getElementById("process").style.display ="none";
		 document.getElementById("field_category").innerHTML=xmlhttp.responseText;
		}
	  } 
	  url = "<?php echo plugin_dir_url( __FILE__ ); ?>ajax_categories_dropdown.php?post_type="+str
	  xmlhttp.open("GET",url,true);
	  xmlhttp.send();
}
</script>
<script type="text/javascript" src="<?php echo TEMPL_PLUGIN_URL.'tmplconnector/monetize/templatic-monetization/add_package_validations.js';?>"></script>
<?php global $wpdb,$post;
if(isset($_REQUEST['package_id']) && $_REQUEST['package_id'] !== '')
{
	$pkid = $_REQUEST['package_id'];
	$package_id = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID = '".$pkid."' AND post_status = 'publish'");
	$id = $package_id[0]->ID;
} ?>
<div class="wrap">
	<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
	<h2><?php echo ADD_NEW; ?>
	<a id="back_to_list" href="<?php echo site_url();?>/wp-admin/admin.php?page=monetization&tab=packages" name="btnviewlisting" class="add-new-h2" title="<?php echo BACK_LINK_TEXT; ?>"/><?php echo BACK_LINK_TEXT; ?></a>
	</h2>
	<p class="description"><?php echo ADD_NEW_PACKAGE_DESC;?>.</p>
	
	<form action="<?php echo site_url();?>/wp-admin/admin.php?page=monetization&action=add_package&tab=packages" method="post" name="monetization" id="monetization" onsubmit="return check_frm();" >
	<input type="hidden" name="package_id" value="<?php if(isset($_REQUEST['package_id']) && $_REQUEST['package_id'] !== '') { echo $_REQUEST['package_id']; } ?>">
	
	<table style="width:60%"  class="form-table" id="form_table_monetize">
	<thead>
		<tr>
			<th colspan="2"><h3><?php echo MONETIZATION_SETTINGS; ?></h3></th>
		</tr>
	</thead>
	<tbody>
		<tr class="" id="package_type">
			<th valign="top">
				<label for="package_type"><?php echo PACKAGE_TYPE; ?> <span class="description">(<?php echo REQUIRED_TEXT; ?>)</span></label>
			</th>
			<td>
				<input type="radio" class="form-radio radio" value="1" name="package_type" id="pay_per_post" <?php if((isset($id) && $id != '') && get_post_meta($id,'package_type',true) == '1') { echo  "checked=checked"; }?> onclick="showlistpost(this);" />&nbsp;<label for="pay_per_post"><?php echo PAY_PER_POST; ?></label></br>
				<span class="description"><?php echo PER_POST_DESC; ?>.</span><br/><br/>
				<input type="radio" class="form-radio radio" value="2" name="package_type" id="pay_per_sub" <?php if((isset($id) && $id != '') && get_post_meta($id,'package_type',true) == '2') { echo  "checked=checked"; }?> onclick="showlistpost(this);" />&nbsp;<label for="pay_per_sub"><?php echo PAY_PER_SUB; ?></label></br>
				<span class="description"><?php echo PER_SUBSCRIPTION_DESC; ?>.</span>
			</td>
		</tr>
        <tr id="number_of_post" <?php if((isset($id) && $id != '') && get_post_meta($id,'package_type',true) == '2'):?> style="display:'';"<?php else:?> style="display:none;"<?php endif;?>>
        <th valign="top"><label for="limit_no_post"><?php echo LIMIT_NO_POST;?></label></th>
           <td>
            <input type="text" class="regular-text"  name="limit_no_post" value=" <?php if((isset($id) && $id != '') && get_post_meta($id,'limit_no_post',true) !="") { echo  get_post_meta($id,'limit_no_post',true); }?>"  id="limit_no_post" /><br />
                <span class="description"><?php echo NO_POST_DESC; ?>.</span>
           </td>
        </tr>
		<tr class="" id="package_title">
			<th valign="top">
				<label for="package_title" class="form-textfield-label"><?php echo PACKAGE_TITLE; ?> <span class="description">(<?php echo REQUIRED_TEXT; ?>)</span></label>
			</th>
			<td>
				<input type="text" class="regular-text" value="<?php if(isset($package_id[0]) && $package_id[0] != '') { echo $package_id[0]->post_title; } ?>" name="package_name" id="package_name" />
				<br/><span class="description"><?php echo PACKAGE_NAME_DESC; ?>.</span>
			</td>
		</tr>
		<tr>
			<th valign="top">
				<label for="package_desc" class="form-textfield-label"><?php echo PACKAGE_DESC_TITLE; ?></label>
			</th>
			<td>
				<textarea name="package_desc" cols="50" rows="5" id="title_desc"><?php if(isset($package_id[0]) && $package_id[0] != '') { echo stripslashes($package_id[0]->post_content); } ?></textarea><br/><span class="description"><?php echo PACKAGE_DESC; ?>.</span>
			</td>
		</tr>
		<tr>
			<th valign="top">
				<label for="package_post_type" class="form-textfield-label"><?php echo SELECT_POST_TYPES; ?> <span class="description">(<?php echo REQUIRED_TEXT; ?>)</span></label>
			</th>
			<td>
				<select name="package_post_type" id="package_post_type" onChange="showcategory(this.value);">
				<?php if(isset($id) && $id != '') { 
					$pctype = get_post_meta($id,"package_post_type",true);
					$pkg_post_type = explode(',',$pctype); }
					$post_types = get_post_types('','objects');
					foreach ($post_types as $post_type)
					{
						if($post_type->name!='nav_menu_item' && $post_type->name!='attachment' && $post_type->name!='revision' && $post_type->name!='page')
						{
							if( $post_type->name == 'post')
							{
								$slugs = 'category';
							}
							else
							{
								$slugs = $post_type->slugs[0];
							} ?>
				<option value="<?php echo $post_type->name.",".$slugs; ?>" <?php if(isset($pkg_post_type[0]) && $pkg_post_type[0] != '' && $pkg_post_type[0] == $post_type->name){ echo 'selected="selected"';}?>><?php echo $post_type->label;?></option>
                 <?php  }
					} ?>
				<option value="all" <?php if(isset($pkg_post_type[0]) && $pkg_post_type[0] != '' && $pkg_post_type[0] == 'all'){ echo 'selected="selected"'; } elseif(isset($id) && $id == '' && get_post_meta($id, 'package_post_type', true) == ""){ echo 'selected="selected"'; } ?>><?php _e('All',DOMAIN);?></option>
				</select><br/><span class="description"><?php echo POST_TYPE_DESC; ?>.</span>
			</td>
		</tr>
		<tr>
			<th valign="top">
				<label for="package_categories" class="form-textfield-label"><?php echo PACKAGE_CATEGORIES; ?> </label>
			</th>
			<td>
				<div class="element" id="field_category">
				<?php $pctype = '';
					if(isset($id) && $id != '')
					{
						$pctype = get_post_meta($id,"package_post_type",true);
						$post_type = explode(',',$pctype);
						$tax = get_post_meta($id,"category",true);
						$pid = $tax;
					
						if($post_type[0] == 'all')
						{
							get_wp_category_checklist_plugin('',$pid);
						}
						else
						{
							get_wp_category_checklist_plugin('',$pid);
						}
					}
					else
					{
						get_wp_category_checklist_plugin('','');
					} ?>
				</div>
				<span id='process' style='display:none;'><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/process.gif" alt='Processing..' /></span>
				<span class="description"><?php echo PACKAGE_CATEGORIES_DESC; ?>.</span>
			</td>
		</tr>
		<tr>
			<th valign="top">
				<label for="show_package" class="form-textfield-label"><?php echo SHOW_PACKAGE; ?></label>
			</th>
			<td>
				<input type="checkbox" name="show_package" id="show_package" value="1" <?php if(isset($id) && $id != '' && get_post_meta($id, 'show_package', true) == 1){ echo 'checked=checked'; } ?> />
				&nbsp;<label for="show_package"><?php echo SHOW_PACKAGE_TITLE; ?></label><br/>
				<span class="description"><?php echo SHOW_PACKAGE_DESC;?>.</span>
			</td>
		</tr>
		<tr class="" id="package_price">
			<th valign="top">
				<label for="package_amount" class="form-textfield-label"><?php echo PACKAGE_AMOUNT; ?> <span class="description">(<?php echo REQUIRED_TEXT; ?>)</span></label>
			</th>
			<td>
				<input type="text" class="regular-text" name="package_amount" id="package_amount" value="<?php if(isset($id) && $id != '') { echo get_post_meta($id, 'package_amount', true); } ?>">
				<br/><span class="description"><?php echo PRICE_AMOUNT_DESC;?>.</span>
			</td>
		</tr>
		<?php $recurring = get_post_meta($id, 'recurring', true); ?>
		<tr class="" id="billing_period" <?php if($recurring == 1) { ?>style="display:none;";<?php } ?>>
			<th valign="top">
				<label for="billing_period" class="form-textfield-label"><?php echo BILLING_PERIOD; ?> <span class="description">(<?php echo REQUIRED_TEXT; ?>)</span></label>
			</th>
			<td>
				<input type="text" class="regular-text billing_num" name="validity" id="validity" value="<?php if(isset($id) && $id != '') { echo get_post_meta($id, 'validity', true); } ?>">
				<select name="validity_per" id="validity_per" class="textfield billing_per">
					<option value="D" <?php if(isset($id) && $id != '' && get_post_meta($id, 'validity_per', true) == 'D'){ echo 'selected="selected"';}?>><?php echo DAYS_TEXT; ?></option>
					<option value="M" <?php if(isset($id) && $id != '' && get_post_meta($id, 'validity_per', true) == 'M'){ echo 'selected="selected"';}?>><?php echo MONTHS_TEXT; ?></option>
					<option value="Y" <?php if(isset($id) && $id != '' && get_post_meta($id, 'validity_per', true) == 'Y'){ echo 'selected="selected"';}?>><?php echo YEAR_TEXT; ?></option>
				</select><br/>
				<span class="description"><?php echo BILLING_PERIOD_DESC;?>.</span>
			</td>
		</tr>
		<tr class="">
			<th valign="top">
				<label for="package_status" class="form-textfield-label"><?php echo PACKAGE_STATUS; ?></label>
			</th>
			<td>
				<input type="checkbox" name="package_status" id="package_status" value="1" <?php if(isset($id) && $id != '' && get_post_meta($id, 'package_status', true) == 1){ echo 'checked=checked'; } ?> />
				&nbsp;<label for="package_status"><?php echo ACTIVE; ?></label><br/>
				<span class="description"><?php echo PACKAGE_STATUS_DESC;?>.</span>
			</td>
		</tr>
		<tr>
			<th valign="top">
				<label for="is_recurring" class="form-textfield-label" style="width:100px;"><?php echo IS_RECURRING; ?>?</label>
			</th>
			<td>
				<select name="recurring" id="recurring" onChange="rec_div_show(this.value)">
					<option value="1" <?php if((isset($id) && get_post_meta($id, 'recurring', true) == 1)){ echo 'selected=selected'; }?> ><?php echo YES;?></option>
					<option value="0" <?php if((isset($id) && get_post_meta($id, 'recurring', true) == 0) || (!isset($id) || $id == '')){ echo 'selected=selected'; }?> ><?php echo NO;?></option>
				</select><br/>
				<span class="description"><?php echo RECURRING_DESC;?>.</span>
			</td>
		</tr>
		<tr id="rec_tr" <?php if((isset($id) && get_post_meta($id, 'recurring', true) == 0)  || (!isset($id) || $id == '')){ echo 'style="display:none;"'; }?>>
			<th valign="top">
				<label for="recurring_billing" class="form-textfield-label"><?php echo RECURRING_BILLING_PERIOD; ?></label>
			</th>
			<td>
				<span class="option_label"><?php echo CHARGE_USER; ?> </span>
				<input type="text" class="textfield billing_num" name="billing_num" id="billing_num" value="<?php if(isset($id) && $id != '') { echo get_post_meta($id, 'billing_num', true); } ?>">
				<select name="billing_per" id="billing_per" class="textfield billing_per">
					<option value="D" <?php if(isset($id) && $id != '' && get_post_meta($id, 'billing_per', true) =='D'){ echo 'selected=selected';}?> ><?php echo DAYS_TEXT; ?></option>
					<option value="M" <?php if(isset($id) && $id != '' && get_post_meta($id, 'billing_per', true) =='M'){ echo 'selected=selected';}?> ><?php echo MONTHS_TEXT; ?></option>
					<option value="Y" <?php if(isset($id) && $id != '' && get_post_meta($id, 'billing_per', true) =='Y'){ echo 'selected=selected';}?> ><?php echo YEAR_TEXT; ?></option>
				</select><br/>
				<span class="description"><?php echo RECURRING_BILLING_PERIOD_DESC; ?>.</span>
			</td>
		</tr>
		<tr id="rec_tr1" <?php if((isset($id) && get_post_meta($id, 'recurring', true) == 0)  || (!isset($id) || $id == '')){ echo 'style="display:none;"'; }?>>
			<th valign="top">
				<label for="billing_cycle" class="form-textfield-label"><?php echo RECURRING_BILLING_CYCLE; ?></label>
			</th>
			<td>
				<input type="text" class="textfield" name="billing_cycle" id="billing_cycle" value="<?php if(isset($id) && $id != '') { echo get_post_meta($id, 'billing_cycle', true); } ?>"><br/><span class="description"><?php echo RECURRING_BILLING_CYCLE_DESC; ?>.</span>
			</td>
		</tr>
	</tbody>
	
	<thead>
		<tr>
			<th colspan="2"><h3><?php echo SETTINGS_FOR_FEATURED; ?></h3>
			<span class="description"><?php echo SETTINGS_FOR_FEATURED_DESC; ?>.</span></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th valign="top">
				<label for="is_featured" class="form-textfield-label"><?php echo IS_FEATURED; ?>?</label>
			</th>
			<td>
				<label for="is_featured"><input type="checkbox" name="is_featured" id="is_featured" value="1" <?php if(isset($id) && $id != '' && get_post_meta($id, 'is_featured', true) == 1){ echo 'checked=checked'; } ?> onClick="show_featured_package(this.id);" />&nbsp;
				<?php echo ACTIVE; ?></label><br/>
				<span class="description"><?php echo FEATURED_STATUS_DESC; ?>.</span>
			</td>
		</tr>
		<tr id="featured_home" <?php if((isset($id) && get_post_meta($id, 'is_featured', true) == 0)  || (!isset($id) || $id == '')) { echo 'style="display:none;"'; } ?>>
			<th valign="top">
				<label for="feature_amount" class="form-textfield-label"><?php echo FEATURED_AMOUNT_HOME; ?></label>
			</th>
			<td>
				<input type="text" name="feature_amount" id="feature_amount" value="<?php if(isset($id) && $id != '' &&get_post_meta($id, 'feature_amount', true) != "") { echo get_post_meta($id, 'feature_amount', true); } ?>"><br/>
				<span class="description"><?php echo FEATURED_AMOUNT_HOME_DESC;?>.</span>
			</td>
		</tr>
		<tr id="featured_cat" <?php if((isset($id) && get_post_meta($id, 'is_featured', true) == 0)  || (!isset($id) || $id == '')){ echo 'style="display:none;"'; } ?>>
			<th valign="top">
				<label for="feature_cat_amount" class="form-textfield-label"><?php echo FEATURED_AMOUNT_CAT; ?></label>
			</th>
			<td>
				<input type="text" name="feature_cat_amount" id="feature_cat_amount" value="<?php if(isset($id) && $id != '' &&get_post_meta($id, 'feature_cat_amount', true) != "") { echo get_post_meta($id, 'feature_cat_amount', true); } ?>">
				<br/><span class="description"><?php _e(FEATURED_AMOUNT_CAT_DESC,DOMAIN);?>.</span>
			</td>
		</tr>
	</tbody>
	</table>
	<input type="submit" class="button-primary form-submit form-submit submit" value="Save Settings" name="submit" id="submit-1">
	</form>
</div>
<?php
/* POSTING PACKAGE DATA TO THE DATABASE */
if(isset($_POST['package_name']) && isset($_REQUEST['action']) && $_REQUEST['action'] == 'add_package')
{
	/* CALL A FUNCTION TO INSERT DATA INTO DATABASE */
	global $monetization;
	$monetization->insert_package_data($_POST);
}
?>