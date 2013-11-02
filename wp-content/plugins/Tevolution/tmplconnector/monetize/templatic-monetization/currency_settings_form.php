<?php
if(@$_REQUEST['submit_currency'] != '')
	{
		update_option('currency_symbol',$_REQUEST['currency_symbol']);
		update_option('currency_code',$_REQUEST['currency_code']);
		update_option('currency_pos',$_REQUEST['currency_pos']);
	}
?>
<script type="text/javascript">
function check_currency_form()
{
	jQuery.noConflict();
	var currency_symbol = jQuery('#currency_symbol').val();
	var currency_code = jQuery('#currency_code').val();
	if( currency_symbol == "" || currency_code == "" )
	{
		if(currency_symbol =="")
			jQuery('#cur_sym').addClass('form-invalid');
			jQuery('#cur_sym').change(func_cur_sym);
		if(currency_code == '')
			jQuery('#cur_code').addClass('form-invalid');
			jQuery('#cur_code').change(func_cur_code);
		return false;
	}
	function func_cur_sym()
	{
		var currency_symbol = jQuery('#package_name').val();
		if( currency_symbol == '' )
		{
			jQuery('#cur_sym').addClass('form-invalid');
			return false;
		}
		else if( currency_symbol != '' )
		{
			jQuery('#cur_sym').removeClass('form-invalid');
			return true;
		}
	}
	function func_cur_code()
	{
		var currency_code = jQuery('#package_amount').val();
		if( currency_code == '' )
		{
			jQuery('#cur_code').addClass('form-invalid');
			return false;
		}
		else if( currency_code != '' )
		{
			jQuery('#cur_code').removeClass('form-invalid');
			return true;
		}
	}
}
</script>
<div class="wrap"><br/>
	<p class="description"><?php echo ADD_NEW_DESC;?>.</p>
<form action="<?php echo site_url();?>/wp-admin/admin.php?page=monetization&tab=currency_settings" method="post" name="currency_settings" id="currency_form" onclick="return check_currency_form();">
	<table style="width:40%"  class="form-table">
	<tbody>
		<tr class="" id="cur_sym">
			<td valign="top">
			<label for="currency_symbol" class="form-textfield-label"><?php echo CURRENCY_SYMB; ?> <span class="description">(<?php echo REQUIRED_TEXT; ?>)</span></label>
			</td>
			<td>
				<input type="text" class="regular-text" class="form-radio radio" value="<?php echo get_option('currency_symbol'); ?>" name="currency_symbol" id="currency_symbol" />
				<br/><span class="description"><?php echo CURRENCY_SYMB_DESC; ?>.</span>
			</td>
		</tr>
		<tr class="" id="cur_code">
			<td valign="top">
				<label for="currency_code" class="form-textfield-label"><?php echo CURRENCY_CODE; ?> <span class="description">(<?php echo REQUIRED_TEXT; ?>)</span></label>
			</td>
			<td>
				<input type="text" class="regular-text" class="form-radio radio" value="<?php echo get_option('currency_code'); ?>" name="currency_code" id="currency_code" />
				<br/><span class="description"><?php echo CURRENCY_CODE_DESC; ?>.</span>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<label for="currency_pos" class="form-textfield-label"><?php echo CURRENCY_POS; ?> <span class="description">(<?php echo REQUIRED_TEXT; ?>)</span></label>
			</td>
			<td>
				<select name="currency_pos" id="currency_pos">
				<option value="1" <?php if(get_option('currency_pos') == '1') { echo "selected=selected"; } ?>><?php echo SYMB_BFR_AMT; ?></option>
				<option value="2" <?php if(get_option('currency_pos') == '2') { echo "selected=selected"; } ?>><?php echo SPACE_BET_BFR_AMT; ?></option>
				<option value="3" <?php if(get_option('currency_pos') == '3') { echo "selected=selected"; } ?>><?php echo SYM_AFTR_AMT; ?></option>
				<option value="4" <?php if(get_option('currency_pos') == '4') { echo "selected=selected"; } ?>><?php echo SPACE_BET_AFTR_AMT; ?></option>
				</select><br/><span class="description"><?php echo CURRENCY_POS_DESC; ?>.</span>
			</td>
		</tr>
	</tbody>
	</table>
	<input type="submit" class="button-primary form-submit form-submit submit" value="Save Currency" name="submit_currency" id="submit_currency">
</form>
</div>