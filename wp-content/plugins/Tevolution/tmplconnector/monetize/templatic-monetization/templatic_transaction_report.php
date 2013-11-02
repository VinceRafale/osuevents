<?php
global $wpdb,$transection_db_table_name;
if(count($_REQUEST['cf'])>0)
{
	for($i=0;$i<count($_REQUEST['cf']);$i++)
	{
		$cf = explode(",",$_REQUEST['cf'][$i]);
		$orderId = $cf[0];
		if(isset($_REQUEST['action']) && $_REQUEST['action'] !='')
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
			
			if(isset($_REQUEST['action']) && $_REQUEST['action']== 'confirm')
				$payment_status = APPROVED_TEXT;
			elseif(isset($_REQUEST['action']) && $_REQUEST['action']== 'pending')
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
	}
}

include(TEMPL_MONETIZATION_PATH."admin_transaction_class.php");	/* class to fetch transaction class */
//----------------------------------------------------



?>
<script>
function change_transstatus(tid,post_id){
		if (tid=="")
	  {
	  document.getElementById("p_status_"+tid).innerHTML="";
	  return;
	  }
	  if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }else{// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
		xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("p_status_"+tid).innerHTML=xmlhttp.responseText;
		}
	  }
	  url = "<?php echo plugin_dir_url( __FILE__ ); ?>ajax_update_status.php?post_id="+post_id+"&trans_id="+tid;
	  xmlhttp.open("GET",url,true);
	  xmlhttp.send();
}
</script>
<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2><?php _e('Transaction Report',DOMAIN);?></h2>
<p class="description"> <?php _e('You can view whole transaction report in this section. You can search for a transaction or sort them according to the payment gateway too. It also allows you to export all the transactions in a CSV file.',DOMAIN);?></p>

    <form method="post" action="" name="ordersearch_frm">
        <table class="form-table" cellspacing="1" cellpadding="4" border="0" width="70%" >
            <tr>
				<th valign="center"><?php _e('Search by transaction ID',DOMAIN); ?></td>
				<td valign="center"><input type="text" class="regular-text" value="" name="srch_orderno" id="srch_orderno" /><br /></td>
            </tr>
            <tr>
				<th  valign="center"><?php _e('Post Type',DOMAIN); ?></td>
				<td valign="center">	
				<?php
			$custom_post_types_args = array();
			$custom_post_types = get_post_types($custom_post_types_args,'objects');
			$i = 0;
			?>
				<select name="post_types" id="post_types" style="width:300px;" >
				<option value="0"><?php echo PLEASE_SELECT; ?></option>
			<?php
            foreach ($custom_post_types as $content_type) {
                if($content_type->name!='nav_menu_item' && $content_type->name!='attachment' && $content_type->name!='revision' && $content_type->name!='page')
                    {
						
            ?><option value="<?php echo $content_type->name; ?>" <?php if(isset($_REQUEST['post_types']) && $_REQUEST['post_types']== $content_type->name ) {?> selected="selected" <?php } ?>><?php echo $content_type->name; ?></option>
                    
            <?php
						 }
					}
				$i++;	
        ?></select><br /></td>
        	</tr>
            <tr>
				<th valign="center"><?php _e('Payment Type',DOMAIN); ?></td>
				<td valign="center">
				<?php
					$targetpage = site_url("/wp-admin/admin.php?page=transcation");
					$paymentsql = "select * from $wpdb->options where option_name like 'payment_method_%' order by option_id";
					$paymentinfo = $wpdb->get_results($paymentsql);
					if($paymentinfo)
					{
						foreach($paymentinfo as $paymentinfoObj)
						{
							$paymentInfo = unserialize($paymentinfoObj->option_value);
							$paymethodKeyarray[$paymentInfo['key']] = $paymentInfo['key'];
							ksort($paymethodKeyarray);
						}
					} ?>
					<select name="srch_payment" style="width:300px;">
						<option value=""> <?php _e('Select Payment Type',DOMAIN); ?> </option>
						<?php 
						if(!empty($paymethodKeyarray))
						{
							foreach($paymethodKeyarray as $key=>$value) {
								if($value) { ?>
								<option value="<?php echo $value;?>" <?php if($value == @$_REQUEST['srch_payment']){?> selected<?php }?>><?php echo $value;?></option>
						<?php	} 	
							}
						}?>
					</select></td>
            </tr>
			<tr>	
				<th valign="center"><?php _e('Name/Email',DOMAIN); ?></td>
				<td valign="center" colspan="4"><input type="text" class="regular-text" value="" name="srch_name" id="srch_name" /><br /></td>
			</tr>
			<tr>	
				<th valign="center"><input type="submit" name="Search" value="<?php _e('Search'); ?>" class="button-secondary action"  />&nbsp;<input type="reset" name="Default Reset" value="<?php _e('Reset'); ?>" onclick="window.location.href='<?php echo $targetpage;?>'" class="button-secondary action" /></td>
				<td></td>
        	</tr>
            <tr>
            	<td colspan="2"><p class="description"><?php _e('You can export the transation data from here ',DOMAIN); ?><a class="button-primary" href="<?php echo plugin_dir_url( __FILE__ ).'export_transaction.php';?>" title="Export To CSV" class="i_export"><?php _e('Export To CSV',DOMAIN);?></a></p></td>
            </tr>
    </table>
	<br />
<?php
$templ_list_table = new wp_list_transaction();
$templ_list_table->prepare_items();
$templ_list_table->display();
?>
<input type="hidden" name="update_transaction_status" id="update_transaction_status" value="1" />
</form>
<?php
echo '</div>'; ?>
<script>
function reportshowdetail(custom_id)
{
	if(document.getElementById('reprtdetail_'+custom_id).style.display=='none')
	{
		document.getElementById('reprtdetail_'+custom_id).style.display='';
	}else
	{
		document.getElementById('reprtdetail_'+custom_id).style.display='none';	
	}
}

</script>