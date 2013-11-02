<?php 
$activated = @$_REQUEST['activated'];
$deactivate = @$_REQUEST['deactivate'];
if($activated){
templatic_module_activationmsg('templatic_payment_options','Payment Options','',$mod_message='You can Activate or Deactivate the payment gateways straight away from <a href='.site_url()."/wp-admin/admin.php?page=monetization".'><strong>here</strong></a>. On activation of each gateway, it will automatically integrate with your site. This module is dependent on Custom Post Types module hence make sure you are active on Custom Post type manager module as well in order to use the payment gateways on your site.',$realted_mod =''); 
}else{
templatic_module_activationmsg('templatic_payment_options','Payment Options','',$mod_message='',$realted_mod =''); 
}?>
<div id="templatic_payments" class="postbox widget_div">
	<div title="Click to toggle" class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e('Templatic - Payment Options',DOMAIN); ?></span></h3>
	<div class="inside">
		<?php
		_e('Another classic feature from templatic. This feature adds the payment gateways like Paypal, Paypal Pro, Google Checkout etc. on your site. You are free to edit these options and also you can enable or disable each of them. ',DOMAIN);

		?>
		<div class="clearfix"></div>
		<?php if(!is_active_addons('templatic_payment_options')) { ?>
		<div id="publishing-action">
		<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&activated=templatic_payment_options&true=1";?>" class="button-primary"><?php _e('Activate &rarr;',DOMAIN); ?></a></div>
		
		<?php } 
		if (is_active_addons('templatic_payment_options')) : ?>
		<div class="settings_style">
			<a href="<?php echo site_url()."/wp-admin/admin.php?page=templatic_system_menu&deactivate=templatic_payment_options&true=0";?>" class="deactive_lnk"><?php _e('Deactivate ',DOMAIN); ?></a>
			<a class="templatic-tooltip set_lnk" href="<?php echo site_url()."/wp-admin/admin.php?page=monetization";?>"><?php _e('Settings',DOMAIN); ?><span class="custom">
			<?php _e('This link will redirect you to Payment Options page where you can Activate or Deactivate Payment gateways on your site.',DOMAIN);?>
			<b class="tooltip_arrow"></b>
			</span></a>
		</div>
		<?php endif; ?>
	</div>
</div>