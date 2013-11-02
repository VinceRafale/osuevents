<?php
global $post,$wpdb;
$id = $_SESSION['custom_fields']['cur_post_id'];
$permalink = get_permalink( $id );
if(isset($_REQUEST['page']) && $_REQUEST['page']=='preview' && isset($_GET['pid']))
{	
	$is_delet_post=1;
}
if(strpos($permalink,'?'))
	{
	  if(isset($_REQUEST['pid']) && $_REQUEST['pid']!=""){ $postid = '&amp;pid='.$_REQUEST['pid']."&action=edit"; }
	  if(isset($_REQUEST['pid']) && $_REQUEST['pid'] !='' && isset($_REQUEST['renew'])){ $postid = '&amp;pid='.$_REQUEST['pid']."&renew=1"; }
	 	 $gobacklink = $permalink."&backandedit=1&fields=custom_fields".@$postid;
}else{
	if(isset($_REQUEST['pid']) && $_REQUEST['pid'] !=''){ $postid = '&amp;pid='.$_REQUEST['pid']."&action=edit"; }
	if(isset($_REQUEST['pid']) && $_REQUEST['pid'] !='' && isset($_REQUEST['renew'])){ $postid = '&amp;pid='.$_REQUEST['pid']."&renew=1"; }
	$gobacklink = $permalink."?backandedit=1&fields=custom_fields".$postid;
}
?>
<div class="published_box">
<?php
if(isset($_REQUEST['lang']) && $_REQUEST['lang']!="")
{
	$form_action_url = tmpl_get_ssl_normal_url(get_option( 'siteurl' ).'/?page=paynow&lang='.$_REQUEST['lang']);
}else{
	$form_action_url = tmpl_get_ssl_normal_url(get_option( 'siteurl' ).'/?page=paynow');
}
global $monetization;
if(is_active_addons('monetization')){
$listing_price_pkg = $monetization->templ_get_price_info($_SESSION['custom_fields']['package_select'],$_SESSION['custom_fields']['total_price']);
}
?>
<form method="post" action="<?php echo $form_action_url; ?>" id="payment-form" name="paynow_frm"  >
	<?php 
	global $payable_amount,$alive_days;
	$payable_amount = @$_POST['total_price'];
	$alive_days = $listing_price_pkg[0]['alive_days'];
	if(isset($listing_price_pkg[0]['alive_days'])){
		$alive_days = $listing_price_pkg[0]['alive_days'];
	}else{
		$alive_days = 30;
	}
		
	if($_REQUEST['msg'] == 'nopaymethod')
	{
	  echo '<div class="error_msg"> No Payment Method Selected. </div>';
	}

	if($_REQUEST['add_coupon']!='')
	{
		if(is_valid_coupon_plugin($_SESSION['custom_fields']['add_coupon']))
		{
			$payable_amount = get_payable_amount_with_coupon_plugin($payable_amount,$_SESSION['custom_fields']['add_coupon']);
		}
	}
	if((isset($_REQUEST['pid'])=='' && $payable_amount>0) || (isset($_POST['renew']) && $payable_amount>0 && isset($_REQUEST['pid'])!='') || $_SESSION['custom_fields']['total_price'] > 0)
	{
		if(!$payable_amount)
		 {
			$payable_amount = $_SESSION['custom_fields']['total_price'];
		 }
		$message = sprintf(__('You are going to submit  post and pay %s for %s days.'),display_amount_with_currency_plugin($payable_amount),$alive_days);
	}else
	{
		if(isset($_REQUEST['pid'])=='')
		{
			 if(is_active_addons('monetization')){ 
				if($payable_amount>0){
					$message = sprintf(__('You are going to submit %s post for %s days.','nightlife'),@$type_title,@$alive_days); 
				}else{
					$message = __('Here is the preview of your submitted information, if you want to improve/edit some information then click on <strong>Go Back And Edit</strong> Link.',DOMAIN);
				}
			 }else{
				$message = "Here is the preview of your submitted information, if you want to improve/edit some information then click on <strong>Go Back And Edit</strong> Link.";
			 }
		}elseif(!$is_delet_post)
		{			
			$message = sprintf(__('You are going to update post.'));
		}
	}
	?>
	<h5 class="post_message"> <?php _e($message,DOMAIN); ?> </h5>
	<?php /* display payment options only when monetization is activated */?>
	<span style="color:red;font-weight:bold;display:block;" id="payment_errors"><?php 
		if(isset($_REQUEST['paypalerror']) && $_REQUEST['paypalerror']=='yes'){
			echo $_SESSION['paypal_errors'];
		}
		if(isset($_REQUEST['eway_error']) && $_REQUEST['eway_error']=='yes'){
			echo $_SESSION['display_message'];
		}
		if(isset($_REQUEST['stripeerror']) && $_REQUEST['stripeerror']=='yes'){
			echo $_SESSION['stripe_errors'];
		}
		if(isset($_REQUEST['psigateerror']) && $_REQUEST['psigateerror']=='yes'){
			echo $_SESSION['psigate_errors'];
		}
	?></span>
	<?php if((isset($_REQUEST['pid'])=='' && $payable_amount>0) || (isset($_POST['renew']) && $payable_amount>0 && $_REQUEST['pid']!='') || $_SESSION['custom_fields']['total_price'] > 0)
	{
		if(is_active_addons('monetization')){
			/* Delete option of pay cash on delivery because we removed it. */
				delete_option('payment_method_payondelivery');
			/* Delete option of pay cash on delivery because we removed it. */
			templatic_payment_option_preview_page();
		}
	}
	?>
	
	<?php
	if(isset($is_delet_post))
	{
		//$post_sql = mysql_query("select post_author,ID from $wpdb->posts where post_author = '".$current_user->ID."' and ID = '".$_REQUEST['pid']."'");
		$post_sql = $wpdb->get_var("select post_author,ID from $wpdb->posts where post_author = '".$current_user->ID."' and ID = '".$_REQUEST['pid']."'");				
	if(($post_sql > 0) || ($current_user->ID == 1)){
		
	?>
		<h5 class="payment_head"><?php echo PRO_DELETE_PRE_MSG;?></h5>
		<input type="button" name="Delete" value="<?php echo PRO_DELETE_BUTTON;?>" class="btn_input_highlight btn_spacer fr" onclick="window.location.href='<?php echo get_option('siteurl');?>/?page=delete&amp;pid=<?php echo $_REQUEST['pid']?>'" />
		<input type="button" name="Cancel" value="<?php echo PRO_PREVIEW_CANCEL_BUTTON;?>" class="btn_input_normal fl" onclick="window.location.href='<?php echo get_author_link($echo = false, $current_user->ID);?>'" />

            <?php  } else { echo "ERROR: SORRY, you can not delete this post."; }?>
	<?php
	}else
	{
	?>   
    <input type="hidden" name="paynow" value="1" />
	<input type="hidden" name="pid" value="<?php echo $_POST['pid'];?>" />
	<?php
	if(isset($_REQUEST['pid']) && $_REQUEST['pid']!="")
	{
	?> 
		<input type="submit" name="paynow" value="<?php _e(PRO_UPDATE_BUTTON,DOMAIN);?>" class="btn_input_highlight btn_spacer fr" />
	<?php
	}else
	{ 
		/* pay and publish button show only when monetization is acivated */
		if(is_active_addons('monetization') && $payable_amount > 0)
		{ $btn_value = PRO_SUBMIT_BUTTON; }else{ $btn_value = PUBLISH_BUTTON; }
		?>
		<input type='submit' name='paynow' id='paynow'  value='<?php _e($btn_value,DOMAIN);?>' class='btn_input_highlight btn_spacer fr' />
          <?php
	}
	?>
    <?php if(isset($_POST['renew']) && $_POST['renew'] == 1): ?>
        <input type="button" name="Cancel" value="<?php _e(PRO_PREVIEW_CANCEL_BUTTON,DOMAIN);?>" class="btn_input_normal fl" onclick="window.location.href='<?php echo get_author_link($echo = false, $current_user->ID);?>'" /><br/>
        <a href="<?php echo $gobacklink; ?>" class="btn_input_normal fl" ><?php _e(GO_BACK_AND_EDIT_TEXT,DOMAIN);?></a>
	<?php else: ?>
        <input type="button" name="Cancel" value="<?php _e(PRO_PREVIEW_CANCEL_BUTTON,DOMAIN);?>" class="btn_input_normal fl" onclick="window.location.href='<?php echo get_author_link($echo = false, $current_user->ID);?>'" /><br/>
        <a href="<?php echo $gobacklink; ?>" class="btn_input_normal fl" ><?php _e(GO_BACK_AND_EDIT_TEXT,DOMAIN);?></a>
	<?php endif; ?>
	 <?php }?>  
     </form>
</div>