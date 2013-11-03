<div class="group" id="of-option-payment">    
<?php
geocraft_payment_option();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function geocraft_payment_option() {
    if (isset($_POST['submit'])) {
        global $wpdb;
        $paymentupdsql = "select option_value from $wpdb->options where option_name='pay_method_paypal'";
        $paymentupdinfo = $wpdb->get_results($paymentupdsql);
        if ($paymentupdinfo) {
            foreach ($paymentupdinfo as $paymentupdinfoObj) {
                $option_value = unserialize($paymentupdinfoObj->option_value);
                $payment_method = trim($_POST['method_name']);
                $display_order = trim($_POST['pay_order']);
                $paymet_isactive = $_POST['paypal_status'];
                $paypal_sandbox = trim($_POST['paypal_sandbox']);
                if ($payment_method) {
                    $option_value['name'] = $payment_method;
                }
                $option_value['display_order'] = $display_order;
                //$option_value['isactive'] = $paymet_isactive;
                if ($option_value['key'] == 'paypal') {
                    $option_value['paypal_sandbox'] = $paypal_sandbox;
                }
                $paymentOpts = $option_value['payOpts'];
                for ($j = 0; $j < count($paymentOpts); $j++) {
                    $paymentOpts[$j]['value'] = $_POST[$paymentOpts[$j]['fieldname']];
                }
                $option_value['payOpts'] = $paymentOpts;
                $option_value_str = serialize($option_value);
            }
        }

        $updatestatus = "update $wpdb->options set option_value= '$option_value_str' where option_name='pay_method_paypal'";
        $wpdb->query($updatestatus);
    }
    if ($_GET['status'] != '') {
        $option_value['isactive'] = $_GET['status'];
    }
    global $wpdb;
    $paymethodsql = "select option_value from $wpdb->options where option_name='pay_method_paypal'";
    $paymentinfo = $wpdb->get_results($paymethodsql);
    if ($paymentinfo) {
        foreach ($paymentinfo as $paymentinfoObj) {
            $option_value = unserialize($paymentinfoObj->option_value);
            $paymentOpts = $option_value['payOpts'];
        }
    }
    ?>
    <h1><?php echo PAYPAL_SETTING; ?></h1>
    <p><?php echo PAYPAL_DES; ?></p>
<!--    <div class="section section-text ">
        <h3 class="heading">Payment method name</h3>
        <div class="option">
            <div class="controls">
                <input name="method_name" type="text" id="method_name" value="<?php echo $option_value['name']; ?>" class="of-input" />
            </div>
            <div class="explain"></div>
            <div class="clear"> </div>
        </div>
    </div>-->
<!--    <div class="section section-text ">
        <h3 class="heading">Status</h3>
        <div class="option">
            <div class="controls">
                <select name="paypal_status" class="of-input">
                    <option value="1" <?php if ($option_value['isactive'] == 1) { ?> selected="selected" <?php } ?>><?php _e('Activate', THEME_SLUG); ?></option>
                    <option value="0" <?php if ($option_value['isactive'] == '0' || $option_value['isactive'] == '') { ?> selected="selected" <?php } ?>><?php _e('Deactivate', THEME_SLUG); ?></option>
                </select>
            </div>
            <div class="explain"></div>
            <div class="clear"> </div>
        </div>
    </div>-->
    <div class="section section-text ">
        <h3 class="heading"><?php echo PAYPAL_SANDBOX; ?></h3>
        <div class="option">
            <div class="controls">
                <select name="paypal_sandbox" class="of-input">
                    <option value="1" <?php if ($option_value['paypal_sandbox'] == 1) { ?> selected="selected" <?php } ?>><?php echo YES; ; ?></option>
                    <option value="0" <?php if ($option_value['paypal_sandbox'] == '0' || $option_value['paypal_sandbox'] == '') { ?> selected="selected" <?php } ?>><?php echo NO; ?></option>
                </select>
            </div>
            <div class="explain"><p><?php echo PAYPAL_SANDBOX_DES; ?></p></div>
            <div class="clear"> </div>
        </div>
    </div>
<!--    <div class="section section-text ">
        <h3 class="heading">Position (Display order) </h3>
        <div class="option">
            <div class="controls">
                <input name="pay_order" type="text" id="pay_order" value="<?php echo $option_value['display_order']; ?>" class="of-input" />
            </div>
            <div class="explain">This is a numeric value that determines the position of this payment option in the list. e.g. 5</div>
            <div class="clear"> </div>
        </div>
    </div>-->
    <?php
    for ($i = 0; $i < 1; $i++) {
        $payOpts = $paymentOpts[$i];
        ?>
        <div class="section section-text ">
            <h3 class="heading"><?php echo $payOpts['title']; ?></h3>
            <div class="option">
                <div class="controls">
                    <input name="<?php echo $payOpts['fieldname']; ?>" type="text" id="<?php echo $payOpts['fieldname']; ?>" value="<?php echo $payOpts['value']; ?>" class="of-input" />
                </div>
                <div class="explain"><?php echo $payOpts['description']; ?></div>
                <div class="clear"> </div>
            </div>
        </div>
    <?php } 
}
?>
</div>