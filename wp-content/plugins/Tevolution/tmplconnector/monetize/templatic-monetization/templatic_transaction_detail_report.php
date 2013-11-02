<?php
$orderId = $_REQUEST['trans_id'];
if(isset($_REQUEST['submit']) && $_REQUEST['submit'] !='')
{
	global $wpdb,$transection_db_table_name;
	$transection_db_table_name = $wpdb->prefix . "transactions";
	
	$ordersql = "select * from $transection_db_table_name where trans_id=\"$orderId\"";
	$orderinfo = $wpdb->get_row($ordersql);

	$pid = $orderinfo->post_id;
	$payment_type = $orderinfo->payment_method;
	$payment_date = $orderinfo->payment_date;
	$trans_status = $wpdb->query("update $transection_db_table_name SET status = '".$_REQUEST['ostatus']."' where trans_id = '".$orderId."'");
	$user_detail = get_userdata($orderinfo->user_id); // get user details 
	$user_email = $user_detail->user_email;
	$user_login = $user_detail->display_name;
	$my_post['ID'] = $pid;
	if(isset($_REQUEST['ostatus']) && $_REQUEST['ostatus']== 1)
		$status = 'publish';
	else
		$status = 'draft';
	$my_post['post_status'] = $status;
	wp_update_post( $my_post );
	
	if(isset($_REQUEST['ostatus']) && $_REQUEST['ostatus']== 1)
		$payment_status = APPROVED_TEXT;
	elseif(isset($_REQUEST['ostatus']) && $_REQUEST['ostatus']== 2)
		$payment_status = ORDER_CANCEL_TEXT;
	elseif(isset($_REQUEST['ostatus']) && $_REQUEST['ostatus']== 0)
		$payment_status = PENDING_MONI;
	$to = get_site_emailId_plugin();
	$productinfosql = "select ID,post_title,guid,post_author from $wpdb->posts where ID = $pid";
	$productinfo = get_post($pid);
   $post_name = $productinfo->post_title;
	$transaction_details="";
	$transaction_details .= "--------------------------------------------------</br>";
		$transaction_details .= "Payment Details for Listing $post_name;</br>";
		$transaction_details .= "--------------------------------------------------</br>";
		$transaction_details .= "  Status: $payment_status</br>";
		$transaction_details .= "    Type: $payment_type</br>";
		$transaction_details .= "  Date: $payment_date</br>";
		$transaction_details .= "--------------------------------------------------</br>";
		$transaction_details = __($transaction_details,DOMAIN);
		$subject = get_option('post_payment_success_admin_email_subject');
		if(!$subject)
		{
			$subject = __("Place Listing Submitted and Payment Success Confirmation Email","templatic");
		}
		$content = get_option('payment_success_email_content_to_admin');
		if(!$content)
		{
			$content = "<p>Dear [#to_name#],</p><p>[#transaction_details#]</p></br><p>We hope you enjoy . Thanks!</p><p>[#site_name#]</p>";
		}
		$store_name = get_option('blogname');
		$fromEmail = get_option('admin_email');
		$fromEmailName = stripslashes(get_option('blogname'));	
		$search_array = array('[#to_name#]','[#transaction_details#]','[#site_name#]');
		$replace_array = array($fromEmail,$transaction_details,$store_name);
		$filecontent = str_replace($search_array,$replace_array,$content);
		@templ_send_email($fromEmail,$fromEmailName,$to,$user_login,$subject,$filecontent,''); // email to admin
		// post details
			$post_link = site_url().'/?ptype=preview&alook=1&pid='.$pid;
			$post_title = '<a href="'.$post_link.'">'.stripslashes($productinfo->post_title).'</a>'; 
			$aid = $productinfo->post_author;
			$userInfo = get_userdata($aid);
			$to_name = $userInfo->user_nicename;
			$to_email = $userInfo->user_email;
			$user_email = $userInfo->user_email;
		
		$transaction_details ="";
		$transaction_details .= "Information Submitted URL</br>";
		$transaction_details .= "--------------------------------------------------</br>";
		$transaction_details .= "  $post_title</br>";
		$transaction_details = __($transaction_details,DOMAIN);
		
		$subject = get_option('payment_success_email_subject_to_client');
		if(!$subject)
		{
			$subject = __("Payment Success Confirmation Email","templatic");
		}
		$content = get_option('payment_success_email_content_to_client');
		if(!$content)
		{
			$content = "<p>Dear [#to_name#],</p><p>[#transaction_details#]</p><br><p>We hope you enjoy. Thanks!</p><p>[#site_name#]</p>";
		}
		$store_name = get_option('blogname');
		$search_array = array('[#to_name#]','[#transaction_details#]','[#site_name#]');
		$replace_array = array($to_name,$transaction_details,$store_name);
		$content = str_replace($search_array,$replace_array,$content);
		//@mail($user_email,$subject,$content,$headers);// email to client
		templ_send_email($fromEmail,$fromEmailName,$user_email,$user_login,$subject,$content,$extra='');
}
global $wpdb,$transection_db_table_name;

$ordersql = "select * from $transection_db_table_name where trans_id=\"$orderId\"";
$orderinfoObj = $wpdb->get_row($ordersql);

?>
<div class="wrap">
<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php echo TRANSACTION_REPORT_TEXT; ?> <a title="Back to transaction list" class="add-new-h2" name="btnviewlisting" href="<?php echo site_url() ?>/wp-admin/admin.php?page=transcation"><?php echo BACK_TO_TRANSACTION_LINK; ?></a>
	</h2>
	<p class="description"><?php echo TRANSACTION_REPORT_DESC; ?></p>
<table class="form-table"  width="100%">
<?php if($_REQUEST['msg']=='success'){ ?>
<tr>
  <td class="update-nag" style="text-align:left;"><?php echo ORDER_STATUS_SAVE_MSG;?></td>
</tr>
<?php }?>
<tr>
  <td><table width="100%">
      <tr>
        <td><?php echo get_order_detailinfo_tableformat($orderId);?> </td>
      </tr>
      <tr>
        <td><form action="<?php echo site_url("/wp-admin/admin.php?page=transcation&amp;action=edit&amp;msg=success&amp;trans_id=".$_GET['trans_id']);?>" method="post">
            <input type="hidden" name="act" value="orderstatus">
            <table width="75%" class="widefat post" >
              <tr>
                <td width="10%"><strong><?php echo ORDER_STATUS_TITLE; ?> :</strong></td>
                <td width="90%">

                <select name="ostatus">
                    <option value="0" <?php if($orderinfoObj->status==0){?> selected="selected"<?php }?>><?php _e(PENDING_MONI);?></option>
                    <option value="1" <?php if($orderinfoObj->status==1){?> selected="selected"<?php }?>><?php _e(APPROVED_TEXT);?></option>
					 <option value="2" <?php if($orderinfoObj->status==2){?> selected="selected"<?php }?>><?php _e(ORDER_CANCEL_TEXT);?></option>
                  </select></td>
              </tr>
              <tr>
                <td></td>
                <td><input type="submit" name="submit" value="<?php echo ORDER_UPDATE_TITLE; ?>" class="button-secondary action" ></td>
              </tr>
            </table>
			<input type="hidden" name="update_transaction_status" id="update_transaction_status" value="<?php echo $orderinfoObj->post_id; ?>" />
          </form></td>
      </tr>
     </table>
     </td></tr></table>
	</div>