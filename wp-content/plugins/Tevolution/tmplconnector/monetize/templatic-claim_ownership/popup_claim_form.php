<script type="text/javascript" src='<?php echo TEMPL_PLUGIN_URL; ?>tmplconnector/monetize/templatic-claim_ownership/js/jquery.leanModal.min.js'></script>
<script type='text/javascript' src='<?php echo TEMPL_PLUGIN_URL; ?>tmplconnector/monetize/templatic-claim_ownership/js/basic.js'></script>
<div id="claim_listing" class="templ_popup_forms clearfixb" style="display:none;">
	<div class="claim_ownership">
	<?php global $wp_query,$current_user,$claimpost;
	if($claimpost == '')
	{
		global $post;
		$post = $post;
	} else {
	 $post = $claimpost; } ?>
	<form name="claim_listing_frm" id="claim_listing_frm" action="<?php echo the_permalink($post->ID); ?>" method="post">
		<input type="hidden" id="post_id" name="post_id" value="<?php echo $post->ID; ?>"/>
		<input type="hidden" id="request_uri" name="request_uri" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>"/>
		<input type="hidden" id="link_url" name="link_url" value="<?php	echo get_permalink($post->ID); ?>"/>
		<input type="hidden" name="claimer_id" id="claimer_id" value="<?php if(is_user_logged_in()) { echo get_current_user_id(); } else { ?>0<?php } ?>" />
		<input type="hidden" id="author_id" name="author_id" value="<?php echo $post->post_author; ?>" />
		<input type="hidden" id="post_title" name="post_title" value="<?php echo $post->post_title; ?>" />
		<input type="hidden" id="claim_status" name="claim_status" value="pending"/>
		<input type="hidden" id="claimer_ip" name="claimer_ip" value="<?php echo $_SERVER["REMOTE_ADDR"]; ?>"/>
		<div id="claim-header" class="claim-header">
			<h3 class="h3"><?php echo OWNER_TEXT; ?></h3>
			<h4 class="h4"><?php echo VERIFY_OWNERSHIP_FOR; echo "&nbsp;<strong>".$post->post_title."</strong>";?></h4>
			<p id="reply_send_success" class="success_msg" style="display:none;"></p>
			<a class="modal_close" href="#"></a>
		</div>
		<div class="form_row clearfix"><label><?php echo FULL_NAME;?><span>*</span></label> <input name="claimer_name" id="claimer_name" type="text"  /><span id="claimer_nameInfo"></span></div>
		<div class="form_row clearfix"><label> <?php echo EMAIL;?><span>*</span></label> <input name="claimer_email" id="claimer_email" type="text"  /><span id="claimer_emailInfo"></span></div>
		<div class="form_row clearfix"><label> <?php echo CONTACT;?></label> <input name="claimer_contact" id="claimer_contact" type="text"  /></div>
		<div class="form_row clearfix"><label><?php echo CLAIM;?><span>*</span></label> <textarea name="claim_msg" id="claim_msg" cols="10" rows="5" ><?php _e('Hello, I would like to notify you that I am the owner of this listing. I would like to verify it&lsquo;s authenticity.',DOMAIN); ?></textarea><span id="claim_msgInfo"></span></div>
		<div id="claim_ship_cap"></div>
		<div class="form_row clearfix"><input name="Send" class="send_button" type="submit" value="<?php _e('Submit',DOMAIN)?> " /></div>
	</form>
	</div>
</div>
<div id="lean_overlay" ></div>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{
	//global vars
	var claimerfrm = jQuery("#claim_listing_frm");
	var claimer_name = jQuery("#claimer_name");
	var claimer_nameInfo = jQuery("#claimer_nameInfo");
	var claimer_email = jQuery("#claimer_email");
	var claimer_emailInfo = jQuery("#claimer_emailInfo");
	var claim_msg = jQuery("#claim_msg");
	var claim_msgInfo = jQuery("#claim_msgInfo");
	//On blur
	claimer_name.blur(validate_claimer_name);
	claimer_email.blur(validate_claimer_email);
	claim_msg.blur(validate_claim_msg);
	//On Submitting
	claimerfrm.submit(function()
	{
		if(validate_claimer_name() & validate_claimer_email() & validate_claim_msg() )
		{
			return true;
		}
		else
		{
			return false;
		}
	} );
	//validation functions
	function validate_claimer_name()
	{
		if(claimer_name.val() == '')
		{
			claimer_name.addClass("error");
			claimer_nameInfo.text("<?php _e('This field is mendatory. Please enter your name.',DOMAIN);?>");
			claimer_nameInfo.addClass("message_error2");
			return false;
		}
		else
		{
			claimer_name.removeClass("error");
			claimer_nameInfo.text("");
			claimer_nameInfo.removeClass("message_error2");
			return true;
		}
	}
	function validate_claimer_email()
	{
		var isvalidemailflag = 0;
		if(claimer_email.val() == '')
		{
			isvalidemailflag = 1;
		}
		else
		{
			if(claimer_email.val() != '')
			{
				var a = claimer_email.val();
				var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
				//if it's valid claimer_email
				if(filter.test(a))
				{
					isvalidemailflag = 0;
				}
				else
				{
					isvalidemailflag = 1;
				}
			}
		}
		if(isvalidemailflag == 1)
		{
			claimer_email.addClass("error");
			claimer_emailInfo.text("<?php _e('Please enter your valid email address.',DOMAIN);?>");
			claimer_emailInfo.addClass("message_error2");
			return false;
		}
		else
		{
			claimer_email.removeClass("error");
			claimer_emailInfo.text("");
			claimer_emailInfo.removeClass("message_error");
			return true;
		}
	}
	function validate_claim_msg()
	{
		if(jQuery("#claim_msg").val() == '')
		{
			claim_msg.addClass("error");
			claim_msgInfo.text("<?php _e('Please enter your claim message.',DOMAIN);?>");
			claim_msgInfo.addClass("message_error2");
			return false;
		}
		else
		{
			claim_msg.removeClass("error");
			claim_msgInfo.text("");
			claim_msgInfo.removeClass("message_error2");
			return true;
		}
	}
} );
</script>