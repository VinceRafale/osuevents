<?php
global $wp_query,$wpdb,$wp_rewrite;
add_action('init','templ_pricing_options');
function templ_pricing_options(){
	include(TEMPL_MONETIZATION_PATH."templatic-payment_options/admin_payment_options_class.php");	/* class to fetch payment gateways */
}
/*
name : templatic_payment_option_preview_page
description : fetch all the active payment method for preview page.*/
function templatic_payment_option_preview_page()
{
		global $wpdb,$monetization;
		$paymentsql = "select * from $wpdb->options where option_name like 'payment_method_%' order by option_id";
		$paymentinfo = $wpdb->get_results($paymentsql);		
		if($paymentinfo)
		{
			$paymentOptionArray = array();
			$paymethodKeyarray = array();
			$i=0;
			foreach($paymentinfo as $paymentinfoObj)
			{
				$paymentInfo = unserialize($paymentinfoObj->option_value);
				if($paymentInfo['isactive'])
				{
					$paymethodKeyarray[] = $paymentInfo['key'];
					$paymentOptionArray[$paymentInfo['display_order']][] = $paymentInfo;
					$i++;
				}
			}
			if($i==1):?>
               	<h5 class="payment_head"> <?php _e("Pay With",DOMAIN); ?></h5>
               <?php else:?>
				<h5 class="payment_head"> <?php _e("Select Payment Method",DOMAIN); ?></h5>
               <?php 
			endif;
			echo '<ul class="payment_method">';
			ksort($paymentOptionArray);
			if($paymentOptionArray)
			{
				foreach($paymentOptionArray as $key=>$paymentInfoval)
				{
					for($i=0;$i<count($paymentInfoval);$i++)
					{
						$paymentInfo = $paymentInfoval[$i];
						$jsfunction = 'onclick="showoptions(this.value);"';
						$chked = '';
						if($key==1)
						{
							$chked = 'checked="checked"';
						}
						$disable_input = false;
						$payment_display_name = "";
						$listing_price_info = $monetization->templ_get_price_info($_SESSION['custom_fields']['package_select'],$_SESSION['custom_fields']['total_price']);
						$payment_display_name = $paymentInfo['name'];
					?>
		<li id="<?php echo $paymentInfo['key'];?>">
		  <label><input <?php echo $jsfunction;?>  type="radio" value="<?php echo $paymentInfo['key'];?>" id="<?php echo $paymentInfo['key'];?>_id" name="paymentmethod" <?php echo $chked; if($disable_input){echo "disabled=true";}?> />  
						<?php echo $payment_display_name; ?></label> 
						<?php if(file_exists(ABSPATH . 'wp-content/plugins/Tevolution-'.$paymentInfo['key'].'/includes/'.$paymentInfo['key'].'.php'))
						{
							include(ABSPATH . 'wp-content/plugins/Tevolution-'.$paymentInfo['key'].'/includes/'.$paymentInfo['key'].'.php');
						}

						
						
						if(file_exists(TEMPL_PAYMENT_FOLDER_PATH.$paymentInfo['key'].'/'.$paymentInfo['key'].'.php'))
						{
						
							include_once(TEMPL_PAYMENT_FOLDER_PATH.$paymentInfo['key'].'/'.$paymentInfo['key'].'.php');
							
						} 
					 ?> </li>
		  <?php
					}
				}
			}else
			{
			?>
			<li><?php echo NO_PAYMENT_METHOD_MSG;?></li>
			<?php
			}
			
		?>
 	  
  </ul>
  <?php
		}
		?>
		<script type="text/javascript">
		 /* <![CDATA[ */
		function showoptions(paymethod)
		{
		<?php
		for($i=0;$i<count($paymethodKeyarray);$i++)
		{
		?>
		showoptvar = '<?php echo $paymethodKeyarray[$i]?>options';
		if(eval(document.getElementById(showoptvar)))
		{
			document.getElementById(showoptvar).style.display = 'none';
			if(paymethod=='<?php echo $paymethodKeyarray[$i]?>')
			{
				document.getElementById(showoptvar).style.display = '';
			}
		}
		
		<?php
		}	
		?>
		}
		<?php
		for($i=0;$i<count($paymethodKeyarray);$i++)
		{
		?>
		if(document.getElementById('<?php echo $paymethodKeyarray[$i];?>_id').checked)
		{
		showoptions(document.getElementById('<?php echo $paymethodKeyarray[$i];?>_id').value);
		}
		<?php
		}	
		?>
		/* ]]> */
		 </script>
		 <?php	
}
/*
name : templatic_get_payment_options
description : fetch payment option values. */
function templatic_get_payment_options($method)
{
	global $wpdb;
	$paymentsql = "select * from $wpdb->options where option_name like 'payment_method_$method'";
	$paymentinfo = $wpdb->get_results($paymentsql);
	if($paymentinfo)
	{
		foreach($paymentinfo as $paymentinfoObj)
		{
			$option_value = unserialize($paymentinfoObj->option_value);
			$paymentOpts = $option_value['payOpts'];
			$optReturnarr = array();
			for($i=0;$i<count($paymentOpts);$i++)
			{
				$optReturnarr[$paymentOpts[$i]['fieldname']] = $paymentOpts[$i]['value'];
			}
			//echo "<pre>";print_r($optReturnarr);
			return $optReturnarr;
		}
	}
}
/*
Name: payment_menthod_response_url
Desc : Return Response url of payment method
*/
function payment_menthod_response_url($paymentmethod,$last_postid,$renew,$pid,$payable_amount)
{
	global $current_user;
	if($pid>0 && $renew=='')
	{
		wp_redirect(get_author_link($echo = false, $current_user->ID));
		exit;
	}else
	{
		if($payable_amount == '' || $payable_amount <= 0)
		{
			$suburl .= "&pid=$last_postid";
			wp_redirect(get_option('siteurl')."/?page=success$suburl");
			exit;
		}else
		{
			$paymentmethod = $paymentmethod;
			$paymentSuccessFlag = 0;
			if($paymentmethod == 'prebanktransfer' || $paymentmethod == 'payondelivery')
			{
				if($renew){
					$suburl = "&renew=1";
				}
				$suburl .= "&pid=$last_postid";
				//wp_redirect(site_url().'/?page=success&paydeltype='.$paymentmethod.$suburl);
				echo '<script type="text/javascript">location.href="'.site_url().'/?page=success&paydeltype='.$paymentmethod.$suburl.'";</script>';
			}
			else
			{
				if(file_exists(TEMPL_PAYMENT_FOLDER_PATH.$paymentmethod.'/'.$paymentmethod.'_response.php') && $paymentmethod == 'paypal')
				{
					include_once(TEMPL_PAYMENT_FOLDER_PATH.$paymentmethod.'/'.$paymentmethod.'_response.php');
				}
				elseif(file_exists(ABSPATH. 'wp-content/plugins/Tevolution-'.$paymentmethod.'/includes/'.$paymentmethod.'_response.php'))
				{
					include_once(ABSPATH. 'wp-content/plugins/Tevolution-'.$paymentmethod.'/includes/'.$paymentmethod.'_response.php');
				}
			}	
		}
	}
}
/*
Name:templ_payment_methods
Desc : List all payment methods installed
*/
function templ_payment_methods(){ 
	global $wpdb;
	if(isset($_REQUEST['install']) && $_REQUEST['install']!='' || isset($_REQUEST['uninstall']) && $_REQUEST['uninstall']!='')
	{
		if($_REQUEST['install'])
		{
			$foldername = $_REQUEST['install'];
		}else
		{
			$foldername = $_REQUEST['uninstall'];
		}

		if(file_exists(ABSPATH . 'wp-content/plugins/Tevolution-'.$foldername))
		{
			include(ABSPATH . 'wp-content/plugins/Tevolution-'.$foldername.'/includes/install.php');
		}
		elseif(file_exists(plugin_dir_path( __FILE__ ).'payment/'.$foldername))
		{
			include(plugin_dir_path( __FILE__ ).'payment/'.$foldername.'/install.php');
		}else
		{
			$install_message = "Sorry there is no such payment gateway";	
		}
	}
	if(@$_GET['status']!='' && @$_GET['id']!='')
	{
		$paymentupdsql = "select option_value from $wpdb->options where option_id='".@$_GET['id']."'";
		$paymentupdinfo = $wpdb->get_results($paymentupdsql);
		if($paymentupdinfo)
		{
			foreach($paymentupdinfo as $paymentupdinfoObj)
			{
				$option_value = unserialize($paymentupdinfoObj->option_value);
				$option_value['isactive'] = $_GET['status'];
				$option_value_str = serialize($option_value);
				$message = "Status updated successfully.";
			}
		}	
		$updatestatus = "update $wpdb->options set option_value= '$option_value_str' where option_id='".$_GET['id']."'";
		$wpdb->query($updatestatus);
	}
	?>
<div class="wrap">
	<div id="icon-edit" class="icon32"><br></div>
	<h2><?php _e('Manage Payment Options',DOMAIN); ?></h2>
	<p class="description"><?php _e('Here is a list of payment gateways. You can Activate or Deactivate them from here',DOMAIN); ?>. <strong><?php _e('Note',DOMAIN); ?> : </strong><?php _e('You need to purchase a plugin to integrate a payment gateway in order to use it on your site',DOMAIN); ?>.</p>
	<?php
	$wp_list_payment_options = New wp_list_payment_options();
	$wp_list_payment_options->prepare_items();
	$wp_list_payment_options->display();
	?>
	
</div>
<?php 
}
/*
Name :return_page
Desc : payment options return page 
*/
add_action( 'init', 'return_page' );
function return_page()
{
	if(@$_REQUEST['ptype'] == 'return')
	{
		include (TEMPL_PAYMENT_FOLDER_PATH . $_REQUEST['pmethod']."/return.php");
		exit;
	}
	if(@$_REQUEST['ptype'] == 'cancel_return')
	{
		include (TEMPL_PAYMENT_FOLDER_PATH . $_REQUEST['pmethod']."/cancel.php");
		exit;
	}
	if(@$_REQUEST['ptype'] == 'notifyurl')
	{
		include (TEMPL_PAYMENT_FOLDER_PATH . $_REQUEST['pmethod']."/ipn_process.php");
		exit;
	}
}

/*
 * Add action for display the paypal successfull return message display
 * Function Name: successfull_return_paypal_content
 * Return: display the paypal successfull message display
 */
add_action('paypal_successfull_return_content','successfull_return_paypal_content',10,3);

function successfull_return_paypal_content($post_id,$subject,$content)
{
	echo "<h3>".$subject."</h3>";
	echo "<p>".$content."</p>";
}

?>