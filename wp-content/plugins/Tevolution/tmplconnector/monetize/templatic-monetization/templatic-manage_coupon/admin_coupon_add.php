<style type="text/css">
.form_style .description { padding-bottom: 10px; }
</style> 
 
<?php /* file contain the code for adding data */
global $wpdb,$current_user;
$post_id = @$_REQUEST['cf'];
$post_val = get_post($post_id);
/* start to save coupon */
if(isset($_POST['save_coupon']) && $_POST['save_coupon'] != "")
{
	$my_post = array();
	//$admin_title = $_POST['admin_title'];
	$site_title	= $_POST['couponcode'];
	$coupondisc = $_POST['coupondisc'];
	$couponamt	= $_POST['couponamt'];
	$startdate	= $_POST['startdate'];
	$enddate	= $_POST['enddate'];
	
	$my_post['post_title'] = $site_title;
	$my_post['post_type'] = 'coupon_code';
	$my_post['post_status'] = 'publish';
	$my_post['startdate'] = $startdate;
	$my_post['enddate'] = $enddate;
	
	$custom = array("coupondisc"		=> $coupondisc,
					"couponamt" 		=> $couponamt,
					"startdate"			=> $startdate,
					"enddate"			=> $enddate
					);

	if(isset($_REQUEST['cf']) && $_REQUEST['cf']!="")
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
			wpml_insert_templ_post($last_postid,'coupon_code'); /* insert post in language */
	}
	foreach($custom as $key=>$val)
	{				
		update_post_meta($last_postid, $key, $val);
	}
	
	$url = site_url().'/wp-admin/admin.php';	
	echo '<form action="'.$url.'#option_manage_coupon" method="get" id="frm_manage_coupon" name="frm_manage_coupon">
	<input type="hidden" value="monetization" name="page"><input type="hidden" value="manage_coupon" name="tab"><input type="hidden" value="'.$msgtype.'" name="msgtype">
	</form>
	<script>document.frm_manage_coupon.submit();</script>
	';exit;
} /* end to dave coupon */
?> 
<script type="text/javascript" src="<?php echo TEMPL_PLUGIN_URL.'tmplconnector/monetize/templatic-monetization/templatic-manage_coupon/add_coupon_code_validations.js';?>"></script> 
<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<form class="form_style" action="<?php echo site_url();?>/wp-admin/admin.php?page=monetization&tab=manage_coupon&action=addnew" method="post" name="coupon_frm"  >
		<h2>
			<?php if(isset($_REQUEST['cf']) && $_REQUEST['cf']!="")
				  {
					  _e('Edit Coupon',DOMAIN); 
				   	 $custom_msg = 'Here you can edit coupon code detail.'; 
				  }else{ 
				  		_e('Add a Coupon',DOMAIN); 
						$custom_msg = '';
				  }?>
	  <a id="coupon_code_add" href="<?php echo site_url();?>/wp-admin/admin.php?page=monetization&tab=manage_coupon" name="btnviewlisting" class="add-new-h2" title="<?php _e('Back to Manage coupon list',DOMAIN);?>"><?php _e('Back to coupons list',DOMAIN); ?></a> 
    </h2>
    <p class="description"><?php _e($custom_msg,DOMAIN);?></p>
	 <input type="hidden" name="couponact" value="addcoupon">
  <input type="hidden" name="cf" id="cf" value="<?php if(isset($_REQUEST['cf']))echo $_REQUEST['cf'];?>">
 <div id="chck_coupon" style="display:none;" class="error"></div>
  <?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='exist'){?>
  <div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);" >
    <p><?php echo COUPON_CODE_EXIST_ERROR;?></p>
  </div>
  <?php } ?>
  <table style="width:30%" cellpadding="3" cellspacing="3" class="form-table" id="form_table_coupon" >
  <tr id="startdate1">
      <td valign="top"><?php echo COUPON_ST_DATE; ?> <span class="description">(<?php echo REQUIRED_TEXT; ?>)</span></td>
      <td>
      <script type="text/javascript">	
		jQuery(function(){
		var pickerOpts = {
				showOn: "both",
				dateFormat: 'yy-mm-dd',
				buttonImage: "<?php echo TEMPL_PLUGIN_URL; ?>css/datepicker/images/cal.png",
				buttonText: "Show Datepicker"
			};	
			jQuery("#startdate").datepicker(pickerOpts);
			jQuery("#enddate").datepicker(pickerOpts);
		});
	</script>
       <input type="text" class="regular-text" name="startdate" id="startdate" class="textfield"  value="<?php echo get_post_meta($post_id,"startdate",true); ?>" size="25" />
		<p class="description"><?php echo COUPON_ST_DATE_DESC; ?>.</p>
	  </td>
    </tr>
	<tr id="enddate1">
      <td valign="top"><?php echo COUPON_END_DATE; ?> <span class="description">(<?php echo REQUIRED_TEXT; ?>)</span></td>
      <td >
       <input type="text" class="regular-text" name="enddate" id="enddate" class="textfield"  value="<?php echo get_post_meta($post_id,"enddate",true); ?>" size="25" /><p class="description"><?php echo COUPON_END_DATE_DESC; ?>.</p> 
	  </td>
    </tr>
    <tr id="couponcode1">
      <td valign="top"><?php echo COUPON_CODE_TITLE;?> <span class="description">(<?php echo REQUIRED_TEXT; ?>)</span></td>
      <td >
        <input type="text" class="regular-text" autocomplete="off"  name="couponcode" id="couponcode" value="<?php if(isset($post_val->post_title))echo $post_val->post_title; ?>" onkeyup="chkcoupon();" ><p class="description"><?php echo COUPON_CODE_DESC; ?>.</p>
	  </td>
		
    </tr>
    <tr>
      <td valign="top"><?php echo DISCOUNT_TYPE_TITLE; ?></td>
      <td>
        <label for="coupondiscper"><input type="radio" id="coupondiscper" name="coupondisc" value="per" <?php if(get_post_meta($post_id,"coupondisc",true) == 'per' || get_post_meta($post_id,"coupondisc",true) ==''){?>checked="checked"<?php }?> />
         <?php _e('Percentage');?>(%)</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="coupondiscamt"><input type="radio" id="coupondiscamt" name="coupondisc" <?php if(get_post_meta($post_id,"coupondisc",true) == 'amt'){?> checked="checked"<?php }?> value="amt" /> <?php _e('Amount');?></label>
		 <p class="description"><?php echo COUPON_TYPE_DESC; ?>.</p>
	</td>
    </tr>
    
    <tr>
      <td valign="top"><?php echo DISCOUNT_AMOUNT_TITLE;?></td>
      <td>
        <input type="text" class="regular-text" name="couponamt" id="couponamt" value="<?php echo get_post_meta($post_id,"couponamt",true);?>" >
		<p class="description"><?php echo COUPON_AMOUNT_DESC; ?>.</p>
	  </td>
    </tr>
    <tr id="save_coupon" >
      <td>&nbsp;</td>
      <td><input type="submit" class="button-primary" name="save_coupon" onclick="return check_frm();" id="save" value="<?php _e('Save all changes',DOMAIN);?>" />
       </td>
    </tr>
  </table>
</form>
</div>
<script type="text/javascript">
jQuery.noConflict();
var xmlHTTP;
/* call this function to validate coupon code using ajax */
function chkcoupon()
{
	if (window.XMLHttpRequest)
	{
	  	xmlhttp=new XMLHttpRequest();
	}
	else
	{
	 	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
  	if(xmlhttp == null)
	{
		alert("Your browser not support the AJAX");	
		return;
	}
	var couponcode = '';
	var startdate = '';
	var enddate = '';
	if(document.getElementById("couponcode"))
	{
		couponcode = document.getElementById("couponcode").value;
		startdate = document.getElementById("startdate").value;
		enddate = document.getElementById("enddate").value;
		post_id = document.getElementById("cf").value;
	}
	var url = "<?php echo TEMPL_AJAX_CHK_COUPON_URL; ?>ajax_check_coupon_code_exist.php?add_coupon="+couponcode+"&startdate="+startdate+"&enddate="+enddate+"&post_id="+post_id;

	xmlhttp.open("GET",url,true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.send(null);
	xmlhttp.onreadystatechange=function()
	{
	   	if(xmlhttp.readyState==4 && xmlhttp.status==200)
	   	{
			if(xmlhttp.responseText)
			{
				document.getElementById("chck_coupon").innerHTML = xmlhttp.responseText;
				document.getElementById("chck_coupon").style.display = '';
				jQuery("#chck_coupon").addClass('error_msg');
				jQuery("#startdate").val('');
				jQuery("#enddate").val('');
				jQuery("#couponcode").val('');
			}
			else
			{
				document.getElementById("chck_coupon").innerHTML = '';
				document.getElementById("chck_coupon").style.display = 'none';
				document.getElementById("save_coupon").style.display = ''
				jQuery("#chck_coupon").removeClass('error_msg');
			}
		}
	}
	return false;
}
</script>