<?php 
$order_id = $_REQUEST['pid'];
global $page_title,$wpdb;
if($_REQUEST['pid']){
	$post_type = get_post_type($_REQUEST['pid']);
	$post_type_object = get_post_type_object($post_type);
	$post_type_label = $post_type_object->labels->singular_name;
}
if(isset($_REQUEST['renew']) && $_REQUEST['renew']!="")
{
	$page_title = RENEW_SUCCESS_TITLE;
}else
{
	$page_title = __("$post_type_label Successfull",DOMAIN);
	if(function_exists('icl_register_string')){
		$context = get_option('blogname');
		icl_register_string($context,$post_type_label." Successfull",$post_type_label." Successfull");
		$page_tile = icl_t($context,$post_type_label." Successfull",$post_type_label." Successfull");
	}
}
get_header(); 

do_action('templ_before_success_container_breadcrumb');


if(isset($_REQUEST['paydeltype']) && $_REQUEST['paydeltype']=='prebanktransfer')
{
	//MAIL SENDING TO CLIENT AND ADMIN START
	global $payable_amount,$last_postid,$stripe_options,$wpdb,$monetization,$sql_post_id;
	$transaction_tabel = $wpdb->prefix."transactions";
	$user_id = $wpdb->get_var("select user_id from $transaction_tabel order by trans_id DESC limit 1");
	$user_id = $user_id;
	$sql_transaction = "select max(trans_id) as trans_id from $transaction_tabel where user_id = $user_id and status=0 ";
	$sql_data = $wpdb->get_var($sql_transaction);
	$sql_status_update = $wpdb->query("update $transaction_tabel set status=0 where trans_id=$sql_data");
	$get_post_id = $wpdb->get_var("select post_id from $transaction_tabel where trans_id=$sql_data");
	$wpdb->query("UPDATE $wpdb->posts SET post_status='".fetch_posts_default_paid_status()."' where ID = '".$get_post_id."'");
	//$trans_status = $wpdb->query("update $transaction_tabel SET status = 1 where post_id = '".$get_post_id."'");
	$pmethod = 'payment_method_'.$_REQUEST['paydeltype'];
	$payment_detail = get_option($pmethod,true);
	$bankname = $payment_detail['payOpts'][0]['value'];
	$account_id = $payment_detail['payOpts'][1]['value'];
	$sql_post_id = $wpdb->get_var("select post_id from $transaction_tabel where user_id = $user_id and trans_id=$sql_data");
	$suc_post = get_post($sql_post_id);
	$sql_payable_amt = $wpdb->get_var("select payable_amt from $transaction_tabel where user_id = $user_id and trans_id=$sql_data");
	$post_title = $suc_post->post_title;
	$post_content = $suc_post->post_content;
	$paid_amount = display_amount_with_currency_plugin(get_post_meta($sql_post_id,'paid_amount',true));
	$user_details = get_userdata( $user_id );
	$first_name = $user_details->user_login;
	$last_name = $user_details->last_name;
	$fromEmail = get_site_emailId_plugin();
	$fromEmailName = get_site_emailName_plugin(); 	
	$toEmail = $user_details->user_email;
	$toEmailName = $first_name;
	$theme_settings = get_option('templatic_settings');
	
	//	Payment success Mail to client END		
	$client_mail_subject =  apply_filters('prebanktransfer_client_subject',$theme_settings['payment_success_email_subject_to_client']);
	$client_mail_content = $theme_settings['payment_success_email_content_to_client'];
	
	$client_transaction_mail_content = '<p>Thank you for your cooperation with us.</p>';
	$client_transaction_mail_content .= '<p>You successfully completed your payment by Pre Bank Transfer.</p>';
	$client_transaction_mail_content .= "<p>".__('Your submitted id is:')." : ".$sql_post_id."</p>";
	$client_transaction_mail_content .= '<p>'.__('View more detail from').' <a href="'.get_permalink($sql_post_id).'">'.$suc_post->post_title.'</a></p>';
	
	$search_array = array('[#to_name#]','[#transaction_details#]','[#site_name#]');
	$replace_array = array($toEmailName,$client_transaction_mail_content,$fromEmailName);
	$client_message = apply_filters('prebanktransfer_client_message',str_replace($search_array,$replace_array,$client_mail_content),$toEmailName,$fromEmailName);
	templ_send_email($fromEmail,$fromEmailName,$toEmail,$toEmailName,$client_mail_subject,$client_message,$extra='');///To client email
	
	//Payment success Mail to admin START
	$admin_mail_subject =  apply_filters('prebanktransfer_admin_subject',$theme_settings['payment_success_email_subject_to_admin']);
	$admin_mail_content = $theme_settings['post_pre_bank_trasfer_msg_content'];
	
	$admin_transaction_mail_content .= "<p>Payment recieved from $toEmailName via Pre Bank Transfer.</p>";
	$submiited_id  = $sql_post_id;
	$submitted_link = get_permalink($sql_post_id);
	
	$search_array = array('[#payable_amt#]','[#bank_name#]','[#account_number#]','[#submition_Id#]','[#submited_information_link#]','[#site_name#]');
	$replace_array = array($sql_payable_amt,$bankname,$account_id,$submiited_id,$submitted_link,$fromEmailName);
	$admin_message = apply_filters('prebanktransfer_admin_message',str_replace($search_array,$replace_array,$admin_mail_content),$fromEmailName,$toEmailName);
	templ_send_email($toEmail,$toEmailName,$fromEmail,$fromEmailName,$admin_mail_subject,$admin_message,$extra='');///To client email
	//Payment success Mail to admin FINISH
}
global $upload_folder_path,$wpdb;
?>
    <div class="content_<?php echo stripslashes(get_option('ptthemes_sidebar_left'));  ?>" id="content">
	 <h1 class="page_head"><?php echo $page_title; ?></h1>
     <div class="posted_successful">
	<?php
		do_action('tevolution_submition_success_msg');
	?> 
	</div>
     
     <?php do_action('tevolution_submition_success_post_content');?>
     
    
	

</div> <!-- content #end -->
<?php 
	if(isset($_REQUEST['pid']) && $_REQUEST['pid']!=""){
		$cus_post_type = apply_filters('success_page_sidebar_post_type',get_post_type($_REQUEST['pid']),$_REQUEST['pid']);
	}	
?>
<div class="sidebar" id="sidebar-primary">
<?php 
	if(isset($cus_post_type) && $cus_post_type!=""){
		dynamic_sidebar($cus_post_type.'_detail_sidebar');
	}else{
		dynamic_sidebar('primary');
	}
?>
</div>
<?php
	unset($_SESSION['category']);
	unset($_SESSION['custom_fields']);
	unset($_SESSION['upload_file']);

get_footer(); ?>