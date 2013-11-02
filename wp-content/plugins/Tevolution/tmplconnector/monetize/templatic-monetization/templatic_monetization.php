<div class="wrap">
<h2><?php _e('Monetization',DOMAIN);?></h2>

<p class="description"> <?php _e(' Here is the monetization module, a bunch of classic features from Templatic which helps you monetize your website easily. Here you will be able to set the currency settings, create packages to be charged the users and also the payment gateways.',DOMAIN);?></p>

<?php if(@$message){?>
<div class="updated fade below-h2" id="message" style="padding:5px; font-size:12px; width:47%" >
  <?php _e($message,DOMAIN);?>
</div>
<?php }?>

<div id="icon-options-general" class="icon32"><br></div>
	<h2 class="nav-tab-wrapper">
	<?php  
	  	$tab = '';
		if(isset($_REQUEST['tab']))
		{
			$tab = $_REQUEST['tab'];
		}
		$class = ' nav-tab-active'; ?>
	 <a id="currency_settings" class='nav-tab<?php if($tab == 'currency_settings' || @$_REQUEST['tab'] == '' ) echo $class;  ?>' href='?page=monetization&tab=currency_settings'><?php _e('Currency Settings',DOMAIN); ?> </a>
	 <a id="packages_settings" class='nav-tab<?php if($tab == 'packages' ) echo $class;  ?>' href='?page=monetization&tab=packages'><?php _e('Price Packages',DOMAIN); ?> </a>
	 <a id="payment_options_settings" class='nav-tab<?php if($tab == 'payment_options') echo $class;  ?>' href='?page=monetization&tab=payment_options'><?php _e('Payment Gateways',DOMAIN); ?> </a>
	 <a class='nav-tab<?php if($tab == 'manage_coupon') echo $class;  ?>' href='?page=monetization&tab=manage_coupon'><?php _e('Manage Coupons',DOMAIN); ?> </a>
    </h2>
	<?php
		if($tab == 'payment_options')
		{ 
			/* to fetch current installed payment add-ons */
			payment_option_plugin_function();
		}
		elseif( $tab == 'currency_settings'  || $tab == '' )
		{
			include (TEMPL_MONETIZATION_PATH."currency_settings_form.php");
		}
		elseif( $tab == 'manage_coupon' )
		{
			manage_coupon_plugin_function();
		}
		else
		{
			if((isset($_REQUEST['action']) && $_REQUEST['action'] == 'add_package') || (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'))
			 {
				 
				 include (TEMPL_MONETIZATION_PATH."add_price_packages.php");
			 }
			 else
			 {
				if($tab == 'packages'){
				 include (TEMPL_MONETIZATION_PATH."price_packages_list.php"); }
			 }
		}		
?>
</div>