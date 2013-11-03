<?php
global $user_ID, $post, $posted, $wpdb, $posted_value;
$posted = unserialize(base64_decode($_POST['posted']));
$paypalamount = $posted['total_cost'];
$post_title = $posted['list_title'];
$paymentOpts = get_payment_optins($_REQUEST['pay_method']);
$merchantid = $paymentOpts['merchantid'];
$returnUrl = $paymentOpts['returnUrl'];
$cancel_return = $paymentOpts['cancel_return'];
$notify_url = $paymentOpts['notify_url'];
$currency_code = get_option('currency_code');
$return_page_id = get_option('geo_notify_page');
$post_id = $posted_value['post_id'];
$pay_method = $_REQUEST['pay_method'];
$returnUrl = site_url("?page_id=$return_page_id&ptype=pstatus&post_id=$post_id&post_title=$post_title&pay_method=$pay_method");
$is_recurring = $paymentOpts['is_recurring'];
$f_period = $posted_value['f_period'];
$f_cycle = $posted_value['f_cycle'];
$installment = $posted_value['installment'];
$s_price = $posted_value['s_price'];
$s_period = $posted_value['s_period'];
$s_cycle = $posted_value['s_cycle'];
$billing = $posted_value['billing'];
$recurring = $posted_value['billing'];
?>

<form name="paypal_sandbox" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" style="padding: 0; margin: 0;">
 <input type="hidden" name="business" value="<?php echo $merchantid; ?>" />
 <!-- Instant Payment Notification & Return Page Details -->
 <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
 <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>" />
 <input type="hidden" name="return" value="<?php echo $returnUrl; ?>" />
 <input type="hidden" name="rm" value="2" />
 <!-- Configures Basic Checkout Fields -->
 <input type="hidden" name="lc" value="" />
 <input type="hidden" name="no_shipping" value="1" />
 <input type="hidden" name="no_note" value="1" />
<!-- <input type="hidden" name="custom" value="localhost" />-->
 <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>" />
 <input type="hidden" name="page_style" value="paypal" />
 <input type="hidden" name="charset" value="utf-8" />
 <input type="hidden" name="item_name" value="<?php echo $post_title; ?>" />
 <?php if($recurring == 1) { ?>
 <input type="hidden" name="amount" value="<?php echo $paypalamount; ?>" />
<!-- <input type="hidden" name="item_number" value="2" />-->
 <input type="hidden" name="cmd" value="_xclick-subscriptions" />
 <!-- Customizes Prices, Payments & Billing Cycle -->
<!-- <input type="hidden" name="src" value="52" />-->
<!-- Value for each installments -->
 <input type="hidden" name="srt" value="<?php echo $installment; ?>" /> 
<!-- <input type="hidden" name="sra" value="5" />-->
<!-- First Price -->
 <input type="hidden" name="a1" value="<?php echo $paypalamount; ?>" />
<!-- First Period -->
 <input type="hidden" name="p1" value="<?php echo $f_period; ?>" />
<!-- First Period Cycle e.g: Days,Months-->
 <input type="hidden" name="t1" value="<?php echo $f_cycle; ?>" />
<!-- Second Period Price-->
 <input type="hidden" name="a3" value="<?php echo $s_price; ?>" />
<!-- Second Period -->
 <input type="hidden" name="p3" value="<?php echo $s_period; ?>" />
<!-- Second Period Cycle -->
 <input type="hidden" name="t3" value="<?php echo $s_cycle; ?>" />
 <!-- Displays The PayPal® Image Button -->
 <?php  } else{ ?>
 <input type="hidden" value="_xclick" name="cmd"/>
 <input type="hidden" name="amount" value="<?php echo $paypalamount; ?>" />
 <?php } ?>
</form>
<div class="wrapper" >
    <div class="clearfix container_message">
        <center><h1 class="head"><?php echo 'Processing.... Please Wait...'; ?></h1></center>
        <center><img class="processing" src="<?php echo TEMPLATEURL . '/images/loading.gif'; ?>"/></center>
    </div>
</div>
<script>
    setTimeout("document.paypal_sandbox.submit()",50); 
</script>



